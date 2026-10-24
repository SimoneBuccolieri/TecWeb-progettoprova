import sys, re, json, urllib.request, urllib.parse
from pathlib import Path
sys.path.insert(0,str(Path(__file__).resolve().parent))
from http_test import Client
base='http://127.0.0.1:8085'; guest=Client(base); user=Client(base); admin=Client(base)
user.post('/login.php',{'username':'user','password':'user'})
admin.post('/login.php',{'username':'admin','password':'admin'})
pages=[(guest,'/index.php'),(guest,'/escursioni.php'),(guest,'/escursione.php?id=1'),(guest,'/registrazione.php'),(guest,'/login.php'),(guest,'/chi-siamo.php'),(guest,'/escursione.php?id=999999'),(user,'/area-personale.php'),(user,'/escursione.php?id=1'),(admin,'/admin.php'),(admin,'/gestisci-escursione.php'),(admin,'/elimina-escursione.php?id=1')]
# Stati senza scritture: errori server, filtri vuoti e modifica.
extra=[(guest,'/escursioni.php?q=nessun-risultato'),(admin,'/gestisci-escursione.php?id=1')]
pages += extra
error_pages=[
 ('registrazione-errori',guest.post('/registrazione.php',{'username':'a','email':'non-email','password':'x','confirm':'y'})),
 ('recensione-errori',user.post('/escursione.php?id=1',{'rating':'9','body':'x'})),
 ('escursione-errori',admin.post('/gestisci-escursione.php',{}))
]
results=[]
responses=[(path,client.request(path)) for client,path in pages]+error_pages
for path,(status,html,_) in responses:
 html=re.sub(r'(name="csrf" value=")[^"]+',r'\1validation-token',html)
 try:
  req=urllib.request.Request('https://validator.w3.org/nu/?out=json',data=html.encode(),headers={'Content-Type':'text/html; charset=utf-8','User-Agent':'PassoVerde-local-validation'})
  result=json.load(urllib.request.urlopen(req,timeout=25)); msgs=result.get('messages',[])
  errors=[m for m in msgs if m['type']=='error']; warnings=[m for m in msgs if m.get('subType')=='warning']
  row={'path':path,'status':status,'errors':errors,'warnings':warnings,'info':len(msgs)-len(errors)-len(warnings)}
  print(path,'errors',len(errors),'warnings',len(warnings),flush=True)
 except Exception as e: row={'path':path,'failure':str(e)}; print(path,str(e),flush=True)
 results.append(row)
(Path(__file__).resolve().parents[1] / 'docs/validazione-html.json').write_text(json.dumps(results,ensure_ascii=False,indent=2))

if any(r.get("failure") or r.get("errors") for r in results): sys.exit(1)
