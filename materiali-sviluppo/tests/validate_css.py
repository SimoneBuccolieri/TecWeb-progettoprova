import urllib.request, urllib.parse,json,xml.etree.ElementTree as ET
from pathlib import Path
results=[]
for p in (Path(__file__).resolve().parents[2] / 'consegna/public/css').glob('*.css'):
 query=urllib.parse.urlencode({'text':p.read_text(),'profile':'css3','output':'soap12','warning':'1'})
 try:
  req=urllib.request.Request('https://jigsaw.w3.org/css-validator/validator?'+query,headers={'User-Agent':'PassoVerde-validation'})
  raw=urllib.request.urlopen(req,timeout=45).read().decode()
  root=ET.fromstring(raw); ns={'m':'http://www.w3.org/2005/07/css-validator'}
  result={key:root.findtext('.//m:'+key,namespaces=ns) for key in ['validity','errorcount','warningcount']}
  result['messages']=[el.text.strip() for el in root.findall('.//m:message',ns)]
  results.append({'file':p.name,'result':result}); print(p.name,result,flush=True)
 except Exception as e: results.append({'file':p.name,'failure':str(e)}); print(p.name,str(e),flush=True)
(Path(__file__).resolve().parents[1] / 'docs/validazione-css.json').write_text(json.dumps(results,ensure_ascii=False,indent=2))

if any(r.get("failure") or r.get("result",{}).get("validity") != "true" for r in results): raise SystemExit(1)
