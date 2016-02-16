<?php
header("Content-type: text/xml");
error_reporting(E_ALL ^ E_NOTICE);
$dosya='belgesel.php';
include "../../a_cfg_b/cfg.php"; 
$site="http://hdbelgesel.net/";
//********************************************************************************************************************************************************************
	if ($_GET['search']<>''){
header("Location: ".$mdldzn.$dosya."?nedir=filmler&filmler=".$site."/?s=".$_GET['search']);
	}
//********************************************************************************************************************************************************************
	if ($_GET['nedir']=="kategoriler"){
	$page = cek($site."category/ataturk/");
	preg_match_all("/class=\"om-link.*?title=.*?href=\"(.*?)\">(.*?)<\/a>/",$page,$kategoriler);
	$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
		<items>
		<playlist_name><![CDATA[BELGESEL]]></playlist_name>
		"; 
		$pic='http://hdbelgesel.net/wp-content/uploads/2012/09/logo.png';
		if (($_SERVER['HTTP_USER_AGENT']=='') or ($_SERVER['HTTP_USER_AGENT']=='Mozilla/5.0 seyirTURKVOD 0.1')){
			$echo.=playlist('ARAMA',$pic,'LÜTFEN BİR SONRAKİ EKRANDA KLAVYEYİ KULLANABİLMEK İÇİN STOP TUŞUNA BASINIZ.','seyirTURKModul@webot@'.$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.'?s=@ARAMA','playlist');
		} else {$echo.=smartarama('SMART TV ARAMA',$pic,'BU ARAMA SADECE SMART TVLER İÇİNDİR.',$sever.$dosya,'playlist');}
		$echo.=playlist('YENİ EKLENENLER',$pic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.'&xml','playlist');
		foreach ($kategoriler[1] as  $key => $kategori ) {
			$name=str_replace($bunu, $degistir,$kategoriler[2][$key]);
			$link=$mdldzn.$dosya."?nedir=filmler&filmler=http://hdbelgesel.net".$kategoriler[1][$key].'&xml';
			$echo.=playlist($name,$pic,$desc1,$link,'playlist');
			}
		$echo.=anamenudon($desc1,$otoportal);			
	echo $echo;
	}
//********************************************************************************************************************************************************************
	if ($_GET['nedir']=="filmler"){
		$dizianasayfa1=cek($_GET['filmler']);
		$ad=substr($_GET['filmler'],58,-1);
		preg_match("/<li class=\"pager-previous\">.*?href=\"(.*?)\"/",$dizianasayfa1,$prev);
		preg_match("/<li class=\"pager-next\">.*?href=\"(.*?)\">/",$dizianasayfa1,$next);
		preg_match_all("/<div id=\"film-gorsel\"><a.*?href=\"(.*?)\"><img.*?src=\"(.*?)\"/",$dizianasayfa1,$filmler);
		preg_match_all("/<div id=\"film-baslik\"><a href=.*?>(.*?)<\/a><\/div>/",$dizianasayfa1,$ad);
//print_r($filmler);
//print_r($ad);
		//print_r($next);
		//echo $prev[1];
		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
			<items>
			<playlist_name><![CDATA[".strtoupper(str_replace($bunu, $degistir, $ad))."]]></playlist_name>
			"; 
		foreach($filmler[1] as $key => $film){
			$name=str_replace($bunu, $degistir,$ad[1][$key]);
			$pic=$filmler[2][$key];
			$link=$mdldzn.$dosya.'?nedir=filmparcalari&filmparcalari=http://hdbelgesel.net'.$filmler[1][$key].'&before='.$_GET['filmler'].'&xml';
			$echo.=playlist($name,$pic,$desc,$link,'playlist');
		}
		if($next[1]<>""){
			$echo.=playlist('İLERİ',$nextpic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler=http://hdbelgesel.net'.$next[1].'&xml','playlist');
		}
		if($prev[1]<>""){
			$prew=$prev[1];
			if (substr($prev[1],-1)=='/'){$prew=substr($prev[1],0,-1);}
			$echo.=playlist('GERİ',$prevpic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler=http://hdbelgesel.net'.$prew.'&xml','playlist');
		}
		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=kategoriler'.'&xml','playlist');
		$echo.=anamenudon($desc1,$otoportal);			
echo $echo;
	}
//**********************************************************************************************************************************************************************
	if ($_GET['nedir']=="filmparcalari"){
		$dizianasayfa3=cek($_GET['filmparcalari']);
		preg_match("/<p><iframe.*?src=\"http:\/\/vk(.*?)\"/",$dizianasayfa3,$vk); 
		if (($vk[1]<>'')) {
					$t[]='http://vk'.str_replace($vkbunu,$vkdegistir,$vk[1]);
					$name1[]='Bölümü İzle';
					$pic[].='ADRES vk.png';
		}
		preg_match("/<iframe.*?src=\"http:\/\/www\.youtube\.com\/embed\/(.*?)\".*?frameborder|=http%3A\/\/www.\youtube\.com\/watch%3Fv%3D(.*?) img/",$dizianasayfa3,$youtube); 
		if ($youtube[1]==''){preg_match("/=http%3A\/\/www.\youtube\.com\/watch%3Fv%3D(.*?) img/",$dizianasayfa3,$youtube); }
		if ($youtube[1]<>''){
					$t[]='http://www.youtube.com/watch?v='.$youtube[1];
					$name1[]='Bölümü İzle';
					$pic[].='http://weblopedi.net/wp-content/uploads/youtube1.jpg';
		}
		preg_match("/<iframe src=\"http:\/\/www\.dailymotion(.*?)\" /",$dizianasayfa3,$daily); 
		if ($daily[1]<>''){
					$t[]='http://www.dailymotion'.$daily[1];
					$name1[]='Bölümü İzle';
					$pic[].='http://twimg0-a.akamaihd.net/profile_images/2582981050/1g3fhgtb189jyqiutey5.png';
		}
		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
			<items>
			<playlist_name><![CDATA[".strtoupper(substr($_GET['filmler'],22,-1))."]]></playlist_name>
			"; 
		foreach ($t as  $key => $firs ) {
			$name=$name1[$key];
			$echo.=playlist($name,$pic[$key],$desc1,$firs,'stream');
			}
		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$_GET['before'].'&xml','playlist');
		$echo.=anamenudon($desc1,$otoportal);			
	echo $echo;
}
?>