#!/usr/bin/env python3
"""Flussi HTTP reali. Default: database SQLite temporaneo, nessun dato della demo toccato.
--url è riservato a un'istanza di collaudo nuova, inizializzata con --demo.
"""
import argparse, http.cookiejar, urllib.request, urllib.parse, urllib.error
import re, os, subprocess, tempfile, time, socket
from pathlib import Path

DEV = Path(__file__).resolve().parents[1]
ROOT = DEV.parent / 'consegna'
checks = 0
class Client:
    def __init__(self, base):
        self.base=base
        self.cookies=http.cookiejar.CookieJar()
        self.open=urllib.request.build_opener(urllib.request.HTTPCookieProcessor(self.cookies))
    def request(self,path,data=None):
        req=urllib.request.Request(self.base+path, data=urllib.parse.urlencode(data).encode() if data is not None else None)
        try: r=self.open.open(req)
        except urllib.error.HTTPError as e: r=e
        return r.status,r.read().decode(),r.url
    def token(self,path):
        status,body,_=self.request(path)
        return re.search(r'name="csrf" value="([^"]+)"',body).group(1)
    def post(self,path,data,token_path=None):
        return self.request(path,dict(data,csrf=self.token(token_path or path)))

def check(condition,label):
    global checks
    assert condition,label
    checks+=1; print('PASS',label)

def run(base):
    guest=Client(base); admin=Client(base); a=Client(base); b=Client(base)
    check(guest.request('/')[0]==200,'home')
    code,body,_=guest.request('/escursioni.php?difficulty=facile')
    check(code==200 and '2 percorsi trovati' in body,'filtro difficoltà')
    check('1 percorso trovato' in guest.request('/escursioni.php?q=rifugio&difficulty=media')[1],'ricerca combinata')
    check('0 percorsi trovati' in guest.request('/escursioni.php?q=%27%20OR%201%3D1--')[1],'ricerca resistente a SQL injection')
    check('0 percorsi trovati' in guest.request('/escursioni.php?q=%25')[1],'wildcard trattati come testo')
    check(guest.request('/escursioni.php?difficulty=invalid')[0]==400,'filtro non valido')
    check(guest.request('/escursione.php?id=999999')[0]==404,'escursione inesistente')
    check(guest.request('/escursione.php?id[]=1')[0]==404,'ID array rifiutato')
    check('login.php' in guest.request('/admin.php')[2],'ospite escluso da amministrazione')
    check(guest.request('/preferiti.php')[0]==405,'GET non modifica preferiti')
    check(guest.request('/logout.php')[0]==405,'GET non esegue logout')
    check(guest.request('/registrazione.php',{'username':'intruso','csrf':'wrong'})[0]==403,'CSRF rifiutato')
    check(guest.post('/registrazione.php',{'username':'a','email':'x','password':'short','confirm':'other'})[0]==422,'registrazione non valida')
    prefix='qa_'+str(time.time_ns())[-12:]
    for c,suffix in [(a,'a'),(b,'b')]:
        code,body,url=c.post('/registrazione.php',{'username':prefix+suffix,'email':prefix+suffix+'@example.test','password':'Prova12345!','confirm':'Prova12345!','role':'admin'})
        check(code==200 and 'area-personale.php' in url,'registrazione '+suffix)
        check(c.request('/admin.php')[0]==403,'ruolo admin da POST ignorato '+suffix)
    code,body,_=admin.post('/login.php',{'username':'admin','password':'admin'})
    check(code==200 and 'Bentornato, admin' in body,'login amministratore')
    data={'title':'Percorso test <script>alert(1)</script>','location':'Valle test','difficulty':'media','distance':'6.2','duration':'125','elevation':'200','description':'Descrizione di prova per collaudare il salvataggio completo delle escursioni.','image':'prati.jpg'}
    check(admin.post('/gestisci-escursione.php',dict(data,distance='-1'))[0]==422,'validazione numeri server')
    code,body,url=admin.post('/gestisci-escursione.php',data)
    check(code==200 and 'escursione.php?id=' in url,'creazione escursione')
    hike_id=urllib.parse.parse_qs(urllib.parse.urlparse(url).query)['id'][0]; detail='/escursione.php?id='+hike_id
    check('&lt;script&gt;' in body and '<script>alert(1)</script>' not in body,'escaping titolo escursione')
    catalog=guest.request('/escursioni.php?q=Percorso%20test')[1]
    check('&lt;script&gt;' in catalog and '<script>alert(1)</script>' not in catalog,'escaping titolo nelle schede HTML')
    marker_title='Percorso {{titolo}} {{localita}} & <script>'
    marker_description='Descrizione di confronto con {{menu}} e <strong>testo</strong> inseriti come dati.'
    code,body,_=admin.post('/gestisci-escursione.php?id='+hike_id,dict(data,title=marker_title,description=marker_description))
    check(code==200 and '<title>Percorso {{titolo}} {{localita}} &amp; &lt;script&gt; | Passo Verde</title>' in body and 'content="Descrizione di confronto con {{menu}} e &lt;strong&gt;testo&lt;/strong&gt; inseriti come dati."' in body,'segnaposto utente conservati come testo in titolo e metadati')
    catalog=guest.request('/escursioni.php?q=Percorso')[1]
    check('Percorso {{titolo}} {{localita}} &amp; &lt;script&gt;' in catalog,'segnaposto utente conservati nelle schede')
    check(a.post('/gestisci-escursione.php?id='+hike_id,data,detail)[0]==403,'utente non modifica escursioni')
    code,body,_=admin.post('/gestisci-escursione.php?id='+hike_id,dict(data,title='Percorso test aggiornato'))
    check(code==200 and 'Percorso test aggiornato' in body,'modifica escursione')
    for _ in range(2): a.post('/preferiti.php',{'hike_id':hike_id,'operation':'add'},detail)
    check(a.request('/area-personale.php')[1].count('class="card"')==1,'preferito persistente senza duplicati')
    check('Percorso test aggiornato' not in b.request('/area-personale.php')[1],'preferiti isolati per utente')
    a.post('/preferiti.php',{'hike_id':hike_id,'operation':'remove'},detail)
    check('class="card"' not in a.request('/area-personale.php')[1],'rimozione preferito')
    check(a.post(detail,{'rating':'6','body':'breve'})[0]==422,'validazione recensione')
    payload='Ottimo sentiero <script>alert(1)</script> & natura.'
    code,body,_=a.post(detail,{'rating':'4','body':payload})
    check(code==200 and '&lt;script&gt;' in body and '<script>alert(1)</script>' not in body,'pubblicazione recensione ed escaping XSS')
    review_id=re.search(r'elimina-recensione.php\?id=(\d+)',body).group(1)
    code,body,_=a.post(detail,{'rating':'5','body':'Recensione modificata, tutto funziona correttamente.'})
    check(code==200 and body.count('class="review"')==1 and 'Recensione modificata' in body,'modifica senza duplicare recensioni')
    check(b.request('/elimina-recensione.php?id='+review_id)[0]==403,'altro utente non accede alla cancellazione')
    check(b.post('/elimina-recensione.php?id='+review_id,{},detail)[0]==403,'altro utente non cancella con POST')
    check(a.request('/elimina-recensione.php?id='+review_id)[0]==200 and 'Recensione modificata' in a.request(detail)[1],'GET conferma non cancella')
    code,body,_=a.post('/elimina-recensione.php?id='+review_id,{})
    check(code==200 and 'Recensione modificata' not in body,'autore cancella recensione')
    code,body,_=a.post(detail,{'rating':'3','body':'Recensione per verifica della moderazione amministratore.'})
    review_id=re.search(r'elimina-recensione.php\?id=(\d+)',body).group(1)
    code,body,_=admin.post('/elimina-recensione.php?id='+review_id,{})
    check(code==200 and 'Recensione per verifica' not in body,'moderazione amministratore')
    a.post('/preferiti.php',{'hike_id':hike_id,'operation':'add'},detail)
    a.post(detail,{'rating':'3','body':'Recensione per verifica cancellazione a cascata.'})
    code,body,_=admin.post('/elimina-escursione.php?id='+hike_id,{})
    check(code==200 and a.request(detail)[0]==404,'amministratore cancella escursione')
    account=a.request('/area-personale.php')[1]
    check('Percorso test aggiornato' not in account and 'Recensione per verifica cancellazione' not in account,'cascata elimina preferiti e recensioni')
    a.post('/logout.php',{},'/area-personale.php')
    check('login.php' in a.request('/area-personale.php')[2],'logout invalida accesso')
    code,body,_=a.post('/login.php',{'username':prefix+'a','password':'Prova12345!'})
    check(code==200 and 'Bentornato' in body,'account persiste dopo logout')
    a.post('/logout.php',{},'/area-personale.php')
    for _ in range(10): code,_,_=a.post('/login.php',{'username':prefix+'a','password':'errata'})
    check(a.post('/login.php',{'username':prefix+'a','password':'errata'})[0]==429,'limite tentativi login')
    print(f'ESITO: {checks} controlli superati. Utenti di collaudo: {prefix}a, {prefix}b')

if __name__=='__main__':
    parser=argparse.ArgumentParser(); parser.add_argument('--url'); args=parser.parse_args()
    if args.url: run(args.url.rstrip('/'))
    else:
        with tempfile.TemporaryDirectory(prefix='passoverde-test-') as temp:
            env=dict(os.environ,DB_DSN='sqlite:'+temp+'/test.sqlite')
            subprocess.run(['php',str(ROOT/'bin/setup.php'),'--demo'],env=env,check=True,stdout=subprocess.DEVNULL)
            with socket.socket() as s: s.bind(('127.0.0.1',0)); port=s.getsockname()[1]
            with open(temp+'/server.log','w') as log:
                server=subprocess.Popen(['php','-S',f'127.0.0.1:{port}','-t',str(ROOT/'public'),str(DEV/'bin/serve.php')],env=env,stdout=log,stderr=log)
                try:
                    for _ in range(50):
                        try: urllib.request.urlopen(f'http://127.0.0.1:{port}'); break
                        except urllib.error.URLError: time.sleep(.1)
                    run(f'http://127.0.0.1:{port}')
                finally: server.terminate(); server.wait()
