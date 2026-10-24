"""Schemi ricavati dalla navigazione e dallo schema SQL di Passo Verde."""
from pathlib import Path
from PIL import Image, ImageDraw, ImageFont
from math import atan2, cos, sin
BASE=Path(__file__).resolve().parent/'images'
FONT='/System/Library/Fonts/Supplemental/Arial.ttf'
BOLD='/System/Library/Fonts/Supplemental/Arial Bold.ttf'
def make(size):
 im=Image.new('RGB',size,'white');return im,ImageDraw.Draw(im)
def text(d,xy,s,size=32,bold=False,anchor='mm'):
 d.text(xy,s,font=ImageFont.truetype(BOLD if bold else FONT,size),fill='black',anchor=anchor,spacing=9,align='center')
def box(d,rect,title,lines=()):
 x,y,w,h=rect;d.rectangle((x,y,x+w,y+h),outline='black',width=3)
 text(d,(x+w/2,y+35),title,34,True)
 if lines:
  d.line((x,y+65,x+w,y+65),fill='black',width=2)
  for i,s in enumerate(lines):text(d,(x+16,y+95+i*38),s,29,anchor='lm')
def arrow(d,a,b,label=None):
 d.line((a,b),fill='black',width=3)
 angle=atan2(b[1]-a[1],b[0]-a[0]);l=17
 d.polygon([b,(b[0]-l*cos(angle-.4),b[1]-l*sin(angle-.4)),(b[0]-l*cos(angle+.4),b[1]-l*sin(angle+.4))],fill='black')
 if label:
  x=(a[0]+b[0])/2;y=(a[1]+b[1])/2
  d.rectangle((x-88,y-22,x+88,y+22),fill='white');text(d,(x,y),label,28)

im,d=make((1800,940))
xs=[40,670,1300];w=450;h=80
for x,y,t in [(xs[0],45,'Home'),(xs[1],45,'Catalogo'),(xs[2],45,'Dettaglio'),(xs[0],330,'Il progetto'),(xs[1],330,'Accesso / Registrazione'),(xs[2],330,'I miei sentieri'),(xs[2],640,'Amministrazione'),(xs[1],640,'Editor escursione'),(xs[0],640,'Conferme eliminazione')]:
 box(d,(x,y,w,h),t)
arrow(d,(490,85),(670,85));arrow(d,(1120,85),(1300,85))
arrow(d,(265,125),(265,330));arrow(d,(1120,370),(1300,370))
arrow(d,(1525,330),(1525,125),'percorsi')
arrow(d,(1525,410),(1525,640),'solo admin')
arrow(d,(1300,680),(1120,680));arrow(d,(1525,720),(1525,790))
d.line((1525,790,265,790),fill='black',width=3);arrow(d,(265,790),(265,720))
text(d,(900,895),'Collegamenti principali; menu e ritorni comuni omessi.',30)
im.save(BASE/'mappa-pagine.png')

im,d=make((1800,1220))
box(d,(20,380,450,235),'users',['PK id','username, email (UNIQUE)','password_hash, role'])
box(d,(1330,380,450,235),'hikes',['PK id','title, location, difficulty','distance, duration, elevation'])
box(d,(550,40,700,215),'favorites',['PK, FK user_id -> users.id','PK, FK hike_id -> hikes.id'])
box(d,(550,760,700,330),'reviews',['PK id','FK user_id -> users.id','FK hike_id -> hikes.id','rating, body, updated_at','UNIQUE (user_id, hike_id)'])
for a,b in [((470,410),(550,200)),((1330,410),(1250,200)),((470,580),(550,820)),((1330,580),(1250,820))]:
 d.line((a,b),fill='black',width=3)
 x=(a[0]+b[0])/2; y=(a[1]+b[1])/2
 d.rectangle((x-78,y-22,x+78,y+22),fill='white');text(d,(x,y),'1 : 0..N',29)
text(d,(900,1145),'hikes include anche description, image e updated_at.',31)
text(d,(900,1193),'login_attempts è indipendente: attempt_key (PK), attempts, last_attempt.',31)
im.save(BASE/'schema-database.png')
