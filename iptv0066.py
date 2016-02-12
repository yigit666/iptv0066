import sys
import urllib,urllib2
import re,xbmcplugin,xbmcgui,xbmcaddon,xbmc

from xml.dom import minidom

xbmcPlayer = xbmc.Player()
playList = xbmc.PlayList(xbmc.PLAYLIST_VIDEO)


def HadiBakalim():
 
    xorion('http://yigit666.hol.es/LIVE%20STREAM.txt')

def xorion(url):
       
       
    f = urllib2.urlopen(url.encode('utf-8'))
    xmldoc = minidom.parse(f)
    XorNodes = xmldoc.getElementsByTagName('channel')
    
    
    for node in XorNodes:
            baslik = node.getElementsByTagName('title')[0].firstChild.data.encode('utf-8')
            try:
                resim =node.getElementsByTagName('logo_30x30')[0].firstChild.data.encode('utf-8')
            except:
                resim=''

            try:
                stream = node.getElementsByTagName('stream_url')[0].firstChild.data.encode('utf-8')
                url=stream
                addDir('[COLOR white][B][COLOR red]>[/COLOR]'+baslik+'[/B][/COLOR]',url,3,resim) 

            except:
                try:
                    url=node.getElementsByTagName('playlist_url')[0].firstChild.data.encode('utf-8')               
                    addDir('[COLOR yellow][B][COLOR blue]>[/COLOR]'+ baslik +'[/B][/COLOR]',url,2,resim)
                except:
                    url=''


def oynat(url,baslik):       
        playList.clear()
        url = str(url).encode('utf-8', 'ignore')
        
        if "vk.com" in url:
                url= VKoynat(url)
        elif "mail.ru" in url:
                url= MailruOynat(url)
        elif "youtube" in url:
                url= YoutubeOynat(url)
        elif 'rtmp:'  in url:
                url= url
        elif 'rtsp:'  in url:
                url= url								
        elif  'mms:' in url:
                url= url
        elif '.m3u8' in url:
                url= url
        elif url.endswith('.mp4'):
                url= url					
        else:
                url=urlresolver.resolve(url)
        if url:
                addLink(baslik,url,'')
                
                listitem = xbmcgui.ListItem(baslik, iconImage="DefaultFolder.png", thumbnailImage='')
                listitem.setInfo('video', {'name': baslik } )
                playList.add(url,listitem=listitem)
                xbmcPlayer.play(playList)
        else:
                showMessage("[COLOR blue][B]Xor[/B][/COLOR]","[COLOR blue][B]Link bulunamadi ya da acilamiyor[/B][/COLOR]")

def MailruOynat(url):
    try:
        listem=[]
        kalx=[]
        
        request = urllib2.Request(url, None)
        page = urllib2.urlopen(request).read()
  
        sd=re.findall('"sd":"(.*?)"' ,page)
        md=re.findall('"md":"(.*?)"',page)
        hd=re.findall('"hd":"(.*?)"',page)
        
        if sd:      
            listem.append(sd[0])
            kalx.append('Sd Kalite')
			
        if md:        
            listem.append(md[0])
            kalx.append('Md Kalite')
			
        if hd:    
            listem.append(hd[0])
            kalx.append('Hd Kalite')
			
        dialog = xbmcgui.Dialog()
        secim = dialog.select('MailRu Kalite Secin...',kalx)
        
        for i in range(len(kalx)):
          
          if secim == i:
            url=listem[i]
           
            return url
          else:
            pass  
          
    except Exception, e:
       print('**** MailRU Hata: %s' % e)


def VKoynat(url):
        liste=[]
        fixed=''
        gecis=0
        link=get_url(url)
        host=re.compile("host=([^\&]+)").findall(link)
        uid=re.compile("uid=([^\&]+)").findall(link)
        vtag=re.compile("vtag=([^\&]+)").findall(link)
        hd = re.compile("hd_def=([^\&]+)").findall(link)
        if not hd or hd[0]=="-1":
            hd = re.compile("hd=([^\&]+)").findall(link)
        flv = re.compile("no_flv=(.*?)&").findall(link)
        vkstream=host[0]+'u'+uid[0]+'/videos/' + vtag[0]
        x=(int(hd[0])+1)
        if hd >0 or flv == 1:
                for i in range (x):
                        streamkalite = ["240", "360", "480", "720", "1080"] 
                        i=streamkalite[i]+' p'
                        liste.append(i) 
                if gecis==0:
                        dialog = xbmcgui.Dialog()
                        ret = dialog.select('kalite secin...',liste)
                        for i in range (x):
                                if ret == i:
                                        url=vkstream+'.'+str(streamkalite[i])+'.mp4'
                                        fixed=str(streamkalite[i])
                                        return url
                                else:
                                        pass
                else:
                        url=vkstream+'.'+fixed+'.mp4'
                return url

def YoutubeOynat(url):
        
        code=re.match('^[^v]+v=(.{11}).*', url)
        
        url='plugin://plugin.video.youtube/?action=play_video&videoid=' + str(code.group(1))

        return url
    
def dailyoynat(url):
        
        id=re.match('http://www.dailymotion.com/embed/video/([^/]*)$', url)
        
        url = "plugin://plugin.video.dailymotion_com/?url="+str(id.group(1))+"&mode=playVideo"

        return url		

def get_url(url):
        req = urllib2.Request(url)
        req.add_header('User-Agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3')
        response = urllib2.urlopen(req)
        link=response.read()
        response.close()
        return link

def playerdenetle(name, url):
                value=[]
              
                if "vk.com" in url:
                    value.append((name,VKoynat(url)))
                    
               
                if 'youtube' in url:
                    value.append((name,YoutubeOynat(url)))
                    
                if 'dailymotion' in url:
                    value.append((name,dailyoynat(url)))
                    
                if '.' in url:
                    value.append((name,url))
                    
                if  value:
                    return value
                else:
                     showMessage("[COLOR blue][B]Xor[/B][/COLOR]","[COLOR blue][B]Link sorunu!![/B][/COLOR]")

def showMessage(heading='Xor', message = '', times = 2000, pics = ''):
		try: xbmc.executebuiltin('XBMC.Notification("%s", "%s", %s, "%s")' % (heading, message, times, pics))
		except Exception, e:
			xbmc.log( '[%s]: showMessage: exec failed [%s]' % ('', e), 1 )

def addLink(name,url,iconimage):
        ok=True
        liz=xbmcgui.ListItem(name, iconImage="DefaultVideo.png", thumbnailImage=iconimage)
        liz.setInfo( type="Video", infoLabels={ "Title": name } )
        ok=xbmcplugin.addDirectoryItem(handle=int(sys.argv[1]),url=url,listitem=liz)
        return ok


def addDir(name,url,mode,iconimage):
        u=sys.argv[0]+"?url="+urllib.quote_plus(url)+"&mode="+str(mode)+"&name="+urllib.quote_plus(name)
        ok=True
        liz=xbmcgui.ListItem(name, iconImage="DefaultFolder.png", thumbnailImage=iconimage)
        liz.setInfo( type="Video", infoLabels={ "Title": name } )
        ok=xbmcplugin.addDirectoryItem(handle=int(sys.argv[1]),url=u,listitem=liz,isFolder=True)
        return ok

		
def get_params():
        param=[]
        paramstring=sys.argv[2]
        if len(paramstring)>=2:
                params=sys.argv[2]
                cleanedparams=params.replace('?','')
                if (params[len(params)-1]=='/'):
                        params=params[0:len(params)-2]
                pairsofparams=cleanedparams.split('&')
                param={}
                for i in range(len(pairsofparams)):
                        splitparams={}
                        splitparams=pairsofparams[i].split('=')
                        if (len(splitparams))==2:
                                param[splitparams[0]]=splitparams[1]
                                
        return param

           
params=get_params()
url=None
name=None
mode=None

try:
        url=urllib.unquote_plus(params["url"])
except:
        pass
try:
        name=urllib.unquote_plus(params["name"])
except:
        pass
try:
        mode=int(params["mode"])
except:
        pass

if mode==None or url==None or len(url)<1:
       
        HadiBakalim()
       
elif mode==2:
       
        xorion(url)
        
elif mode==3:
       
        oynat(url,name)

xbmcplugin.endOfDirectory(int(sys.argv[1]))
