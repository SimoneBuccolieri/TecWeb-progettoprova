from pathlib import Path
import re
from xml.sax.saxutils import escape
from reportlab.pdfgen import canvas
from reportlab.platypus import SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle, PageBreak, Image, KeepTogether
from reportlab.platypus.tableofcontents import TableOfContents
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.lib.enums import TA_CENTER, TA_JUSTIFY
from reportlab.lib import colors
from reportlab.lib.pagesizes import A4

BASE=Path(__file__).resolve().parent
ROOT=BASE.parent.parent
OUT=ROOT/'consegna/Relazione_Passo_Verde.pdf'
styles=getSampleStyleSheet()
styles.add(ParagraphStyle(name='BodyAcademic',fontName='Times-Roman',fontSize=11,leading=13.2,alignment=TA_JUSTIFY,spaceAfter=5.5,allowWidows=0,allowOrphans=0))
styles.add(ParagraphStyle(name='SubAcademic',fontName='Times-Bold',fontSize=12,leading=15,spaceBefore=12,spaceAfter=7,keepWithNext=True))
styles.add(ParagraphStyle(name='ChapterAcademic',fontName='Times-Bold',fontSize=16,leading=20,spaceBefore=17,spaceAfter=12,keepWithNext=True))
styles.add(ParagraphStyle(name='CoverAcademic',fontName='Times-Roman',fontSize=17,leading=22,alignment=TA_CENTER,spaceAfter=14))
styles.add(ParagraphStyle(name='CenterAcademic',fontName='Times-Roman',fontSize=11,leading=15,alignment=TA_CENTER,spaceAfter=10))
styles.add(ParagraphStyle(name='TableAcademic',fontName='Times-Roman',fontSize=10,leading=12))
styles.add(ParagraphStyle(name='SmallAcademic',fontName='Times-Roman',fontSize=9,leading=12,alignment=TA_CENTER,spaceAfter=7))
styles.add(ParagraphStyle(name='BulletAcademic',parent=styles['BodyAcademic'],leftIndent=12,firstLineIndent=-9))
styles.add(ParagraphStyle(name='CaptionAcademic',fontName='Times-Italic',fontSize=9,leading=11,alignment=TA_CENTER,spaceAfter=10))

def inline(s):
 s=escape(s)
 for tag in ['br/','b','/b']: s=s.replace('&lt;'+tag+'&gt;','<'+tag+'>')
 return re.sub(r'\*\*(.+?)\*\*',r'<b>\1</b>',s)
def para(s,style='BodyAcademic'): return Paragraph(inline(s),styles[style])
def table(rows,widths=None):
 content=[[para(c,'TableAcademic') for c in row] for row in rows]
 t=Table(content,colWidths=widths or [80,doc.width-80],hAlign='LEFT',repeatRows=1)
 t.setStyle(TableStyle([('VALIGN',(0,0),(-1,-1),'TOP'),('BACKGROUND',(0,0),(-1,0),colors.HexColor('#eeeeee')),('LINEABOVE',(0,0),(-1,0),.6,colors.black),('LINEBELOW',(0,0),(-1,0),.5,colors.black),('LINEBELOW',(0,-1),(-1,-1),.6,colors.black),('LEFTPADDING',(0,0),(-1,-1),6),('RIGHTPADDING',(0,0),(-1,-1),6),('TOPPADDING',(0,0),(-1,-1),5),('BOTTOMPADDING',(0,0),(-1,-1),5)]))
 return t

class AcademicDoc(SimpleDocTemplate):
 def afterFlowable(self,flow):
  if isinstance(flow,Paragraph) and flow.style.name in ('ChapterAcademic','SubAcademic'):
   level=0 if flow.style.name=='ChapterAcademic' else 1
   title=flow.getPlainText(); key='sec-'+re.sub('[^a-zA-Z0-9]','-',title)
   self.canv.bookmarkPage(key)
   self.canv.addOutlineEntry(title,key,level=level,closed=False)
   self.notify('TOCEntry',(level,title,self.page,key))

def page_footer(c,d):
 c.saveState(); c.setFont('Times-Roman',9)
 c.drawCentredString(A4[0]/2,32,str(d.page))
 if d.page>2:
  c.setStrokeColor(colors.HexColor('#bbbbbb')); c.setLineWidth(.3)
  c.line(d.leftMargin,A4[1]-43,A4[0]-d.rightMargin,A4[1]-43)
  c.drawString(d.leftMargin,A4[1]-37,'Passo Verde')
  c.drawRightString(A4[0]-d.rightMargin,A4[1]-37,'Tecnologie Web - A.A. 2026-2027')
 c.restoreState()

doc=AcademicDoc(str(OUT),pagesize=A4,rightMargin=62,leftMargin=62,topMargin=58,bottomMargin=50,title='Relazione Progetto di Tecnologie Web - Passo Verde',author='Persona uno; Persona due; Persona tre; Persona quattro')
story=[Spacer(1,35),para('Relazione Progetto di Tecnologie Web','CoverAcademic'),para('Passo Verde','CoverAcademic'),para('Portale di escursioni e trekking','CenterAcademic')]
logo=BASE/'images/logo_Unipd.png'
if logo.exists():
 im=Image(str(logo),width=100,height=100,kind='proportional'); story +=[Spacer(1,8),im,Spacer(1,15)]
story += [para('Università degli Studi di Padova<br/>Corso di Laurea in Informatica<br/>Anno accademico 2026-2027','CenterAcademic'),Spacer(1,8),table([['Componenti','Matricola'],['Persona uno','Da completare'],['Persona due','Da completare'],['Persona tre','Da completare'],['Persona quattro','Da completare']],[doc.width*.6,doc.width*.4]),Spacer(1,16),para('<b>Informazioni sul sito</b>','CenterAcademic'),para('Indirizzo locale: http://127.0.0.1:8085<br/>URL sul server del corso: da inserire<br/>Email del referente: da completare','CenterAcademic'),table([['Ruolo','Nome utente','Password'],['Utente','user','user'],['Amministratore','admin','admin']],[doc.width*.4,doc.width*.3,doc.width*.3]),Spacer(1,12),para('Versione locale del 20 ottobre 2026. I nomi dei componenti sono segnaposto.<br/>L’indirizzo locale è raggiungibile solo sul computer che esegue il sito.<br/>Le credenziali sono quelle degli account dimostrativi.','SmallAcademic'),PageBreak(),para('Indice','CoverAcademic')]
toc=TableOfContents()
toc.levelStyles=[ParagraphStyle(name='TOC0',fontName='Times-Roman',fontSize=11,leading=14,spaceBefore=4),ParagraphStyle(name='TOC1',fontName='Times-Roman',fontSize=9.5,leading=11.5,leftIndent=14)]
toc.tableStyle=TableStyle([('TOPPADDING',(0,0),(-1,-1),0),('BOTTOMPADDING',(0,0),(-1,-1),0)])
story +=[toc,PageBreak()]
text=(BASE/'Relazione_Passo_Verde.md').read_text().split('<!-- CORPO -->',1)[1]
lines=text.strip().splitlines(); i=0; first=True
while i<len(lines):
 line=lines[i].strip()
 if not line: i+=1; continue
 if line.startswith('!['):
  match=re.fullmatch(r'!\[(.*?)\]\((.*?)\)',line)
  caption,relative=match.groups()
  figure=Image(str(BASE/relative))
  factor=doc.width/figure.imageWidth
  figure.drawWidth=doc.width; figure.drawHeight=figure.imageHeight*factor
  story.append(KeepTogether([figure,para(caption,'CaptionAcademic')]))
  i+=1
 elif line.startswith('# '):
  if not first: story.append(Spacer(1,8))
  story.append(para(line[2:],'ChapterAcademic')); first=False; i+=1
 elif line.startswith('## '): story.append(para(line[3:],'SubAcademic')); i+=1
 elif line.startswith('|'):
  rows=[]
  while i<len(lines) and lines[i].strip().startswith('|'):
   row=[x.strip() for x in lines[i].strip().strip('|').split('|')]
   if not all(re.fullmatch('[-: ]+',c) for c in row): rows.append(row)
   i+=1
  if len(rows[0])==3: widths=[doc.width*.25,doc.width*.37,doc.width*.38]
  else: widths=[doc.width*.27,doc.width*.73]
  story +=[KeepTogether([table(rows,widths)]),Spacer(1,10)]
 elif line.startswith('- '): story.append(para('• '+line[2:],'BulletAcademic')); i+=1
 else:
  group=[line]; i+=1
  while i<len(lines) and lines[i].strip() and not lines[i].startswith(('#','|','- ')):
   group.append(lines[i].strip()); i+=1
  story.append(para(' '.join(group)))
doc.multiBuild(story,onFirstPage=page_footer,onLaterPages=page_footer)
print(OUT)
