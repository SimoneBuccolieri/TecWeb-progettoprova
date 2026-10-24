from pathlib import Path
import zipfile,re,json,hashlib
workspace=Path(__file__).resolve().parents[3]
root=workspace/'esempio progetto anno scorso'; exts={'.php','.html','.css','.js','.sql'}
def tokens(s): return re.findall(r'\w+|[^\w\s]',s)
refs=[]
for p in root.rglob('*'):
 if p.is_file() and p.suffix in exts: refs.append((str(p),p.read_text(errors='replace')))
 elif p.suffix=='.zip':
  with zipfile.ZipFile(p) as z:
   for n in z.namelist():
    if Path(n).suffix in exts and not any(x in n for x in ['/vendor/','/node_modules/','__MACOSX/']): refs.append((p.name+'/'+n,z.read(n).decode(errors='replace')))
index={}; N=60
for name,s in refs:
 t=tokens(s)
 for i in range(len(t)-N+1):
  key=hashlib.sha256(' '.join(t[i:i+N]).encode()).digest(); index.setdefault(key,name)
results=[]; count=0
for base in ['public','src','database']:
 for p in (Path(__file__).resolve().parents[2] / 'consegna' / base).rglob('*'):
  if p.suffix not in exts or not p.is_file(): continue
  count+=1; t=tokens(p.read_text()); hits={}
  for i in range(len(t)-N+1):
   key=hashlib.sha256(' '.join(t[i:i+N]).encode()).digest()
   if key in index: hits[index[key]]=hits.get(index[key],0)+1
  if hits: results.append({'file':str(p),'matches':hits})
report={'method':'Finestre di 60 token esatti consecutivi; spazi ignorati, identificatori conservati. Non rileva parafrasi o codice rinominato, non è certificazione di originalità.','files_checked':count,'reference_files':len(refs),'matches':results}
(Path(__file__).resolve().parents[1] / 'docs/confronto-codice.json').write_text(json.dumps(report,ensure_ascii=False,indent=2)); print(json.dumps(report,ensure_ascii=False,indent=2))
