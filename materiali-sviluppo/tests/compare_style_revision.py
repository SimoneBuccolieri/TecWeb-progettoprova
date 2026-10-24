from pathlib import Path
from html.parser import HTMLParser
import os,tempfile,zipfile,subprocess,sys,socket,time,urllib.request,re,json
root=Path(__file__).resolve().parents[2]
sys.path.insert(0,str(root/'materiali-sviluppo/tests'))
from http_test import Client
class Structure(HTMLParser):
 def __init__(self):super().__init__();self.nodes=[]
 def handle_starttag(self,tag,attrs):
  attrs=dict(attrs)
  if attrs.get('name')=='csrf':attrs['value']='TOKEN'
  self.nodes.append(('start',tag,sorted(attrs.items())))
 def handle_startendtag(self,tag,attrs):self.handle_starttag(tag,attrs)
 def handle_endtag(self,tag):self.nodes.append(('end',tag))
 def handle_data(self,data):
  if data.strip():self.nodes.append(('text',' '.join(data.split())))
def shape(html):p=Structure();p.feed(html);return p.nodes
with tempfile.TemporaryDirectory(prefix='passoverde-markup-') as tmp:
 tmp=Path(tmp);old=tmp/'old'
 with zipfile.ZipFile(root/'materiali-sviluppo/archivio/prima-riorganizzazione-2026-10-20.zip') as z:
  for name in z.namelist():
   if name.startswith('consegna/'):
    target=old/Path(name).relative_to('consegna');target.parent.mkdir(parents=True,exist_ok=True);target.write_bytes(z.read(name))
 sources=[old,root/'consegna'];servers=[];runs=[]
 try:
  for i,source in enumerate(sources):
   env=dict(os.environ,DB_DSN='sqlite:'+str(tmp/f'db-{i}.sqlite'))
   subprocess.run(['php',str(source/'bin/setup.php'),'--demo'],env=env,check=True,capture_output=True)
   with socket.socket() as s:s.bind(('127.0.0.1',0));port=s.getsockname()[1]
   server=subprocess.Popen(['php','-S',f'127.0.0.1:{port}','-t',str(source/'public')],env=env,stdout=subprocess.DEVNULL,stderr=subprocess.DEVNULL);servers.append(server)
   base=f'http://127.0.0.1:{port}'
   for _ in range(40):
    try:urllib.request.urlopen(base).close();break
    except OSError:time.sleep(.1)
   guest=Client(base);user=Client(base);admin=Client(base)
   user.post('/login.php',{'username':'user','password':'user'})
   admin.post('/login.php',{'username':'admin','password':'admin'})
   fixtures=[(guest,'/index.php'),(guest,'/escursioni.php'),(guest,'/escursioni.php?q=nessun-risultato'),(guest,'/escursione.php?id=1'),(guest,'/registrazione.php'),(guest,'/login.php'),(guest,'/chi-siamo.php'),(guest,'/404.php'),(user,'/area-personale.php'),(user,'/escursione.php?id=1'),(admin,'/admin.php'),(admin,'/gestisci-escursione.php'),(admin,'/gestisci-escursione.php?id=1'),(admin,'/elimina-escursione.php?id=1')]
   states={path+f'#{j}':client.request(path) for j,(client,path) in enumerate(fixtures)}
   states['login-errors']=guest.post('/login.php',{'username':'nessuno','password':'errata'})
   states['forbidden-admin']=user.request('/admin.php')
   states['invalid-id']=guest.request('/escursione.php?id[]=1')
   states['invalid-filter']=guest.request('/escursioni.php?difficulty=x')
   states['register-errors']=guest.post('/registrazione.php',{'username':'a','email':'x','password':'x','confirm':'y'})
   states['review-errors']=user.post('/escursione.php?id=1',{'rating':'9','body':'x'})
   states['editor-errors']=admin.post('/gestisci-escursione.php',{})
   states['review-posted']=user.post('/escursione.php?id=1',{'rating':'4','body':'Recensione di confronto, con testo di prova.'})
   review_id=re.search(r'elimina-recensione.php\?id=(\d+)',states['review-posted'][1]).group(1)
   states['confirm-review-user']=user.request('/elimina-recensione.php?id='+review_id)
   states['confirm-review-admin']=admin.request('/elimina-recensione.php?id='+review_id)
   user.post('/preferiti.php',{'hike_id':'1','operation':'add'},'/escursione.php?id=1')
   states['saved-detail']=user.request('/escursione.php?id=1')
   states['populated-account']=user.request('/area-personale.php')
   states['populated-admin']=admin.request('/admin.php')
   states['existing-review-errors']=user.post('/escursione.php?id=1',{'rating':'8','body':'x'})
   runs.append(states)
  for key,before in runs[0].items():
   after=runs[1][key]
   assert before[0]==after[0],key
   assert shape(before[1])==shape(after[1]),key
  report = {
   'data': '2026-10-20',
   'archivio': 'prima-riorganizzazione-2026-10-20.zip',
   'stati': len(runs[0]),
   'confronto': 'codici HTTP, struttura, attributi e testi HTML',
   'esclusioni': ['token CSRF casuali', 'spazi di formattazione'],
   'esito': 'uguali',
   'pagine': list(runs[0])
  }
  (root/'materiali-sviluppo/docs/confronto-struttura-2026-10-20.json').write_text(json.dumps(report,ensure_ascii=False,indent=2)+'\n')
  print(f'{len(runs[0])} pagine/stati: struttura HTML, attributi e testi invariati, esclusi i soli token CSRF.')
 finally:
  for server in servers:server.terminate();server.wait()
