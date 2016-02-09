@@ -0,0 +1,629 @@
# -*- coding: utf-8 -*-
import sys
import os
import urllib
import xbmc
import xbmcgui
import xbmcplugin
import xbmcaddon
import logging
from operator import itemgetter


def show_tags():
  tag_handle = int(sys.argv[1])
  xbmcplugin.setContent(tag_handle, 'tags')

  for tag in tags:
    iconPath = os.path.join(home, 'resources', 'media', tag['icon'])
    li = xbmcgui.ListItem(tag['name'], iconImage=iconPath)
    url = sys.argv[0] + '?tag=' + str(tag['id'])
    xbmcplugin.addDirectoryItem(handle=tag_handle, url=url, listitem=li, isFolder=True)

  xbmcplugin.endOfDirectory(tag_handle)


def show_streams(tag):
  stream_handle = int(sys.argv[1])
  xbmcplugin.setContent(stream_handle, 'streams')
  logging.warning('TAG show_streams!!!! %s', tag)
  for stream in streams[str(tag)]:
    logging.debug('STREAM HERE!!! %s', stream['name'])
    iconPath = os.path.join(home, 'resources', 'media', stream['icon'])
    li = xbmcgui.ListItem(stream['name'], iconImage=iconPath)
    xbmcplugin.addDirectoryItem(handle=stream_handle, url=stream['url'], listitem=li)

  xbmcplugin.endOfDirectory(stream_handle)

def get_params():
  """
  Retrieves the current existing parameters from XBMC.
  """
  param = []
  paramstring = sys.argv[2]
  if len(paramstring) >= 2:
    params = sys.argv[2]
    cleanedparams = params.replace('?', '')
    if params[len(params) - 1] == '/':
      params = params[0:len(params) - 2]
    pairsofparams = cleanedparams.split('&')
    param = {}
    for i in range(len(pairsofparams)):
      splitparams = {}
      splitparams = pairsofparams[i].split('=')
      if (len(splitparams)) == 2:
        param[splitparams[0]] = splitparams[1]
  return param

def lower_getter(field):
  def _getter(obj):
    return obj[field].lower()

  return _getter


addon = xbmcaddon.Addon()
username = addon.getSetting('username')
password = addon.getSetting('password')
home = xbmc.translatePath(addon.getAddonInfo('path'))

tags = [
  {
    'name': 'Turk Kanallar',
    'id': 'TurkKanallar',
    'icon': 'Channels.png',
  },
     {
    'name': 'Yabanci Kanallar',
    'id': 'YabanciKanallar',
    'icon': 'yabanci.jpg',
  },
     {
    'name': 'Sohbetler',
    'id': 'Sohbetler',
    'icon': 'sohbetler.jpg',
  },
     {
    'name': 'Dini Kanallar',
    'id': 'DiniKanallar',
    'icon': 'dini.jpg',
  },

     {
    'name': 'Filmler',
    'id': 'Filmler',
    'icon': 'filmler.png',
  },
     {
    'name': 'test',
    'id': 'test',
    'icon': 'dini.jpg',
  },
}]

TurkKanallar = [{
  'name': '01 - BEYAZ TV',
  'url': 'http://hdtvizle.ru/14452/71930/71968/beyaz.m3u8',
  'icon': 'beyaztv.png',
  'disabled': False
},



  {
  'name': '02 - TRT 1',
  'url': 'http://trtcanlitv-lh.akamaihd.net/i/TRT1HD_1@181842/index_1500_av-b.m3u8',
  'icon': 'trt1.png',
  'disabled': False
},



  {
  'name': '03 - AVRUPA 7',
  'url': 'rtmp://livede.gostream.nl:80/kanal7int playpath=kanal7int swfUrl=http://www.gostream.nl/zplayer/flowplayer.ozel.swf?0.9924251136835665 pageUrl=http://www.gostream.nl/play.stream.php?id=2',
  'icon': 'kanal7.jpg',
  'disabled': False                
},
                
  {
  'name': '04 - Kanal D',
  'url': 'http://212.224.108.81/S1/HLS_LIVE/kanald/1000/prog_index.m3u8',
  'icon': 'kanald.png',
  'disabled': False
},

  {
  'name': '05 - CNN TURK',
  'url': 'http://212.224.108.76:80/S1/HLS_LIVE/cnn_turk/1000/prog_index.m3u8',
  'icon': 'cnnturk.jpg',
  'disabled': False
},

  {
  'name': '06 - FOX TV',
  'url': 'http://foxtv-i.mncdn.com/r_foxtv/foxtv3/chunklist_w1495990562.m3u8',
  'icon': 'foxtv.jpg',
  'disabled': False
},

  {
  'name': '07 - Haberturk',
  'url': 'rtmp://live-ciner.mncdn.net/haberturk/ playpath=haberturk2 swfUrl=http://www.haberturk.com/images/flash/flowplayer.commercial-3.1.5.swf pageUrl=http://www.haberturk.com/canliyayin live=true swfVfy=true',
  'icon': 'haberturk.png',
  'disabled': False
},

  {
  'name': '08 - SHOW TV',
  'url': 'rtmp://mn-l.mncdn.com/showtv/ playpath=showtv2 swfUrl=http://static.oroll.com/p.swf?v=21.79&ts=15-12-2013 pageUrl=http://www.showtv.com.tr/canli-yayin live=1 timeout=15',
  'icon': 'showtv.jpg',
  'disabled': False
},

  {
  'name': '09 - STAR TV',
  'url': 'http://188.72.131.226/star.m3u8',
  'icon': 'startv.jpg',
  'disabled': False
},

  {
  'name': '10 - Yol Tv',
  'url': 'rtmp://yol.dyndns.tv:2100/yol/ playpath=yolstream swfUrl=http://p.jwpcdn.com/6/7/jwplayer.flash.swf pageUrl=http://karwan.tv/yol-tv.html',
  'icon': 'yoltv.jpg',
  'disabled': False                
},
  {
  'name': '11 - Sports Tv',
  'url': 'http://hdtvizle.ru/14452/71930/71968/sports.m3u8',
  'icon': 'sport.jpg',
  'disabled': False
},
                
  {
  'name': '12 - Fanatik Tv',
  'url': 'rtmp://live.fanatik.cubecdn.net/fanatiktv/ playpath=stream_720 swfUrl=http://medyanet.doracdn.com/Medyanet/Video/Player-2.6.4.7.swf pageUrl=http://tv.fanatik.com.tr/',
  'icon': 'fanatik.png',
  'disabled': False
},



  {
  'name': '13 - Yumurcak Tv',
  'url': 'http://vivisolivegrp01-lh.akamaihd.net/i/yumurcaktv_primary_1@304288/index_360_av-p.m3u8?sd=10&rebase=on&hdntl=exp=1453280163~acl=%2f*~data=hdntl~hmac=f74fbf9ed8c1f7676da756db80243bff945b9217b9aae4700684b7a1b6ba53de',
  'icon': 'yumurcaktv.png',
  'disabled': False                
},

  {
  'name': '14 - Tv8',
  'url': 'http://178.162.205.71:8081/live_hd/TV8/live_hd/TV8_hi/chunks.m3u8?nimblesessionid=27705827&wmsAuthSign=c2VydmVyX3RpbWU9MS8yNi8yMDE2IDExOjQwOjIyIFBNJmhhc2hfdmFsdWU9NjF3NGtrampVY3ZIVUUyQTRNbzRWUT09JnZhbGlkbWludXRlcz0xMCZpcD04My4yNDkuOTUuNjg=',
  'icon': 'tv8.jpg',
  'disabled': False                
},                
                
  {
  'name': '14 - BEYAZ TV - Alternativ',
  'url': 'http://37.77.2.236:1935/beyaz/beyaz.stream/playlist.m3u8',
  'icon': 'beyaztv.png',
  'disabled': True 
},
  
  {
  'name': '15 - Kanal D - Alternativ',
  'url': 'http://www.iptvsaga.com/play/tv3/0038.m3u8',
  'icon': 'kanald.png',
  'disabled': False
},
   
  {
  'name': '16 - FOX TV - Alternativ',
  'url': 'http://hdtvizle.ru/14452/71930/71968/foxtv.m3u8',
  'icon': 'foxtv.jpg',
  'disabled': False
},

  {
  'name': '17 - FOX TV - Alternativ',
  'url': 'http://188.72.131.226/fox.m3u8',
  'icon': 'foxtv.jpg',
  'disabled': False
},
  
  {
  'name': '18 - SHOW TV - Alternativ',
  'url': 'http://mn-l.mncdn.com:1935/showturktv/showturktv1/chunklist_w343490968.m3u8',
  'icon': 'showtv.jpg',
  'disabled': False
}]


YabanciKanallar = [{
  'name': '01 - ORF 2',
  'url': 'http://apasfiisl.apa.at/ipad/orf2_q4a/orf.sdp/playlist.m3u8',
  'icon': 'alman.png',
  'disabled': False
},
  {
  'name': '02 - Das Erste',
  'url': 'http://daserste_live-lh.akamaihd.net/i/daserste_int@91203/master.m3u8',
  'icon': 'alman.png',
  'disabled': False

},
  {
  'name': '03 - NDR',
  'url': 'http://ndr_fs-lh.akamaihd.net/i/ndrfs_hh@119223/index_3776_av-p.m3u8',
  'icon': 'alman.png',
  'disabled': False

},
  {
  'name': '04 - 24 HD',
  'url': 'http://tagesschau-lh.akamaihd.net/i/tagesschau_1@119231/index_3776_av-p.m3u8',
  'icon': 'alman.png',
  'disabled': False

},
  {
  'name': '05 - Az Tv',
  'url': 'http://188.72.131.226/aztv.m3u8',
  'icon': 'azeri.png',
  'disabled': False

},

  {
  'name': '06 - Idman Tv',
  'url': 'http://188.72.131.226/idman.m3u8',
  'icon': 'azeri.png',
  'disabled': False

},
  {
  'name': '07 - Lider Tv',
  'url': 'http://188.72.131.226/lider.m3u8',
  'icon': 'azeri.png',
  'disabled': False

},

  {
  'name': '08 - Ans Tv',
  'url': 'http://188.72.131.226/aztv.m3u8',
  'icon': 'azeri.png',
  'disabled': False
},

  {
  'name': '09 - Kurdsat',
  'url': 'http://62.210.100.139:1935/kurdsattv/smil:kurdsat.smil/chunklist_w198010984_b886000_slen.m3u8',
  'icon': 'kurd.png',
  'disabled': False
},

  {
  'name': '10 - Zagros Tv',
  'url': 'http://198.100.158.231:1935/kanal10/_definst_/livestream/playlist.m3u8',
  'icon': 'kurd.png',
  'disabled': False
},

  {
  'name': '11 - Kurdmax',
  'url': 'http://live.kurdstream.net:1935/liveTrans//myStream_360p/playlist.m3u8',
  'icon': 'kurd.png',
  'disabled': False
}]

Sohbetler = [{
  'name': 'Abdullah Imamoglu - Fasli genc',
  'url': 'plugin://plugin.video.youtube/?action=play_video&videoid=yq_oiFdx2e8',
  'icon': 'abdullah.jpg',
  'disabled': False
},
  {
  'name': 'Abdullah Imamoglu - Amel İmandan mıdır',
  'url': 'plugin://plugin.video.youtube/?action=play_video&videoid=8OyJmJLDrCU',
  'icon': 'abdullah.jpg',
  'disabled': False

},
  {
  'name': 'Abdullah Imamoglu - bence ve bana göre zihniyeti',
  'url': 'plugin://plugin.video.youtube/?action=play_video&videoid=y36u4CdF-M8',
  'icon': 'abdullah.jpg',
  'disabled': False  
},

{
  'name': 'Abdullah Imamoglu - Irkçılık',
  'url': 'plugin://plugin.video.youtube/?action=play_video&videoid=ZyPA5PnIEHQ',
  'icon': 'abdullah.jpg',
  'disabled': False
},
  {
  'name': 'Abdullah Imamoglu - Müslümanın derdiyle dertleneceğiz',
  'url': 'plugin://plugin.video.youtube/?action=play_video&videoid=muzAMo33Yio',
  'icon': 'abdullah.jpg',
  'disabled': False

},
  {
  'name': 'Abdullah Imamoglu - Fetih rûhu nedir?',
  'url': 'plugin://plugin.video.youtube/?action=play_video&videoid=EuWMdwpvrvY',
  'icon': 'abdullah.jpg',
  'disabled': False

},
  {
  'name': 'Aziz Hoca - Ölüm ve Ölümden Sonraki Hayat',
  'url': 'plugin://plugin.video.youtube/?action=play_video&videoid=EuWMdwpvrvY',
  'icon': 'olum.jpg',
  'disabled': False  
  
}]



DiniKanallar = [{
  'name': '01 - Peace Tv Usa',
  'url': 'rtmp://peace.fms.visionip.tv/live/ playpath=b2b-peace_usa-live-25f-4x3-sdh_1 swfUrl=http://www.peacetv.tv/templates/swf/player.swf pageUrl=http://www.muslimvideo.com/live/online/peacetv-usa.html live=1 timeout=15',
  'icon': 'peace.jpg',
  'disabled': False
},
   {
  'name': '02 - A9 TV',
  'url': 'http://live.harunyahya.tv/hls-live/livepkgr/_definst_/livestream/liveiostr.m3u8',
  'icon': 'a9.jpg',
  'disabled': False

},
  {
  
  'name': '03 - Rehber TV',
  'url': 'rtmp://yayin5.canliyayin.org:1935/live playpath=rehbertv swfUrl=http://www.cagritv.com.tr/playerim/playerim.swf pageUrl=http://www.cagritv.com.tr/cyayin.php live=1 timeout=15',
  'icon': 'rehber.jpg',
  'disabled': False

},
  {
  
  'name': '04 - Kudus TV',
  'url': 'rtmp://yayin8.canliyayin.org/kudustv/ playpath=kudustv swfUrl=http://p.jwpcdn.com/6/8/jwplayer.flash.swf pageUrl=http://www.bedavacanlitvizle.org/yayin/kudustv.php',
  'icon': 'kudustv.jpg',
  'disabled': False

},
  {
 
  'name': '05 - Peace TV UK',
  'url': 'rtmp://peace.fms.visionip.tv/live/ playpath=b2b-peace_sky-live-25f-4x3-sdh_1 swfUrl=http://www.peacetv.tv/templates/swf/player.swf pageUrl=http://www.muslimvideo.com/live/online/peacetv-uk.html',
  'icon': 'peace.jpg',
  'disabled': False

},
  {  
  'name': '06 - Peace TV URDU',
  'url': 'rtmp://peace.fms.visionip.tv/live/ playpath=b2b-peace_asia-live-25f-4x3-sdh_1 swfUrl=http://www.peacetv.tv/templates/swf/player.swf pageUrl=http://www.muslimvideo.com/live/online/peacetv-urdu.html',
  'icon': 'peace.jpg',
  'disabled': False

},
  {
  
  'name': '07 - SEMERKAND TV',
  'url': 'http://semerkandglb.mediatriple.net:1935/semerkandliveedge/semerkand1/chunklist_w1137959173.m3u8',
  'icon': 'semerkand.png',
  'disabled': False

},
  {  
  
  'name': '08 - Medine TV',
  'url': 'rtmp://makkah1live.itworkscdn.net/makkah2live/ playpath=sunnatv swfUrl=http://itworks-me.net/makkah2live/jwplayer.flash.swf pageUrl=http://itworks-me.net/makkah2live/',
  'icon': 'medine.jpg',
  'disabled': False

},
  {  
  
  'name': '09 - Medine TV',
  'url': 'http://livetr.gostream.nl/medine/medine/radyodelisi.m3u8',
  'icon': 'medine.jpg',
  'disabled': False

},
  {  

  'name': '10 - Medine TV',
  'url': 'rtmp://makkah1live.itworkscdn.net/makkah1live/ playpath=squran swfUrl=http://itworks-me.net/makkah1live/jwplayer.flash.swf pageUrl=http://itworks-me.net/makkah1live/',
  'icon': 'medine.jpg',
  'disabled': False

},
  {
  
  'name': '11 - LALEGUL TV',
  'url': 'http://origin.live.web.tv.streamprovider.net/streams/0eb471be049b2d20891b46c9ef4df0fc_live_0_0/index.m3u8',
  'icon': 'lalegultv.jpg',
  'disabled': False

},
  {  

  'name': '12 - KANAL TEK',
  'url': 'http://sr1.netmedya.org:1935/kanaltek/live.sdp/www.webtvizle.org.m3u8',
  'icon': 'kanaltek.png',
  'disabled': False

},
  {  

  'name': '13 - TRT Diyanet',
  'url': 'rtmp://si.trtcdn.com/tv/trtdiyanet playpath=mp4:trtdiyanet_3 swfUrl=http://cdn.livestream.com/chromelessPlayer/examples/jwplayer/players/4.6.swf pageUrl=http://www.tvizleo.com/2013/08/trt-diyanet-izle.html',
  'icon': 'trtdiyanet.png',
  'disabled': False

},
  {  

  'name': '14 - MEKKE',
  'url': 'rtmp://livetr.gostream.nl:80/mekke playpath=mekke live=true timeout=15',
  'icon': 'mekke.jpg',
  'disabled': False

},
  {  
   
  'name': '15 - Islamic TV',
  'url': 'rtmp://wowza04.sharp-stream.com/islamtv playpath=mp4:islamtv swfUrl=http://player.sharp-stream.com/islamtv/jwplayer.flash.swf pageUrl=http://www.islamchannel.tv/',
  'icon': 'islamictv.jpg',
  'disabled': False
},
  {
  
  
  'name': '16 - IQRA TV',
  'url': 'http://cdn01.iqbroadcast.tv:8081/g1/1qr188/playlist.m3u8',
  'icon': 'iqraa.png',
  'disabled': False
}]


Filmler = [{
  'name': '01 - Hz Süleyman',
  'url': 'plugin://plugin.video.youtube/?action=play_video&videoid=qmWveEwRxyk',
  'icon': 'suleyman.jpg',
  'disabled': False
},
  {
  'name': '02 - Hz. Eyyüb (a.s.)',
  'url': 'plugin://plugin.video.youtube/?action=play_video&videoid=m7ZkGnxkeb0',
  'icon': 'eyyup.jpg',
  'disabled': False
},
  {
  'name': '03 - Çağrı - Hz .Muhammed s-a-v',
  'url': 'plugin://plugin.video.youtube/?action=play_video&videoid=dfMCsDcxIIA',
  'icon': 'cagri.jpg',
  'disabled': False
},

  {
  'name': '04 - Davaro',
  'url': 'http://ia801607.us.archive.org/15/items/ks333/davaro.mp4',
  'icon': 'kemalsunal.png',
  'disabled': False
},

  {
  'name': '05 - Kibar Feyzo',
  'url': 'http://ia601606.us.archive.org/5/items/ksfilm6/kibarfeyzo.mp4',
  'icon': 'kemalsunal.png',
  'disabled': False
},

  {
  'name': '06 - Sakar Sakir',
  'url': 'http://ia801602.us.archive.org/2/items/ksfilm9/sakarsakir.mp4',
  'icon': 'kemalsunal.png',
  'disabled': False
},

  {
  'name': '07 - Saban Oglu Saban',
  'url': 'http://ia801602.us.archive.org/2/items/ksfilm9/sabanoglusaban.mp4',
  'icon': 'kemalsunal.png',
  'disabled': False

},

  {
  'name': '08 - Eyvah Eyvah 3',
  'url': 'http://bak01-vod04.myvideo.az/secure/190/360p/1896943.mp4',
  'icon': 'eyvah3.png',
  'disabled': False  
  
},

  {  
  'name': '09 - Eyvah Eyvah 1',
  'url': 'http://bak01-vod04.myvideo.az/secure/189/1883756.mp4',
  'icon': 'eyvah1.jpg',
  'disabled': False
},

  {  
  'name': '10 - Allahın Sadık Kulu - Barla',
  'url': 'http://bak01-vod04.myvideo.az/secure/189/1887818.mp4',
  'icon': 'allahkulu.jpg',
  'disabled': False
},

  {  
  'name': '11 - bi küçük Eylül meselesi',
  'url': 'http://bak01-vod04.myvideo.az/secure/191/1903998.mp4',
  'icon': 'eylul.png',
  'disabled': False
  
},

  { 
  'name': '12 - Bana Bir Soygun Yaz',
  'url': 'plugin://plugin.video.youtube/?action=play_video&videoid=yJ_3s2OPQ7U',
  'icon': 'soygun.jpg',
  'disabled': False
 
},

  {
  'name':'13 - Abimm',
  'url': 'plugin://plugin.video.youtube/?action=play_video&videoid=2jDNKy0PGMo',
  'icon': 'abimm.jpg',
  'disabled': False
  
},

  {  
  'name': '14 - Polis Akademisi Alaturka',
  'url': 'plugin://plugin.video.youtube/?action=play_video&videoid=osuyoVs-TOk',
  'icon': 'polis.jpg',
  'disabled': False
  
  
},

  { 
  'name': '15 - KOLPACINO',
  'url': 'plugin://plugin.video.youtube/?action=play_video&videoid=CELQgGEU2dM',
  'icon': 'kolpacino.jpg',
  'disabled': False
}]

test = [{
 
  {  
  'name': 'test',
  'url': 'http://www.canlitvlive.com/izle/tv8.html',
  'icon': 'polis.jpg',
  'disabled': False
  
  
},

  { 
  'name': 'test',
  'url': 'http://www.canlitvlive.com/izle/kanal-turk.html',
  'icon': 'kolpacino.jpg',
  'disabled': False



}]

streams = {
  'TurkKanallar': sorted((i for i in TurkKanallar if not i.get('disabled', False)), key=lower_getter('name')),
  'YabanciKanallar': sorted((i for i in YabanciKanallar if not i.get('disabled', False)), key=lower_getter('name')),
  'Sohbetler': sorted((i for i in Sohbetler if not i.get('disabled', False)), key=lower_getter('name')),
  'DiniKanallar': sorted((i for i in DiniKanallar if not i.get('disabled', False)), key=lower_getter('name')),
  'Filmler': sorted((i for i in Filmler if not i.get('disabled', False)), key=lower_getter('name')),
  'test': sorted((i for i in test if not i.get('disabled', False)), key=lower_getter('name')),
  # 'TurkKanallar': sorted(TurkKanallar, key=lower_getter('name')),
  # 'YabanciKanallar': sorted(YabanciKanallar, key=lower_getter('name')),
  # 'Sohbetler': sorted(Sohbetler, key=lower_getter('name')),
  # 'DiniKanallar': sorted(DiniKanallar, key=lower_getter('name')),
  # 'Filmler': sorted(Filmler, key=lower_getter('name')),
  
}

PARAMS = get_params()
TAG = None
logging.warning('PARAMS!!!! %s', PARAMS)

try:
  TAG = PARAMS['tag']
except:
  pass

logging.warning('ARGS!!!! sys.argv %s', sys.argv)

if TAG == None:
  show_tags()
else:
  show_streams(TAG)