<?php

error_reporting(E_ALL ^ E_NOTICE);
#set_time_limit(60000);
$dosya='ero.php';
include "../../a_cfg_b/cfg.php"; 
$site='http://www.eroprofile.com/';
//********************************************************************************************************************************************************************
	if ($_GET['search']<>''){
header("Location: ".$mdldzn.$dosya."?nedir=filmler&filmler=".$site."m/videos/home?text=".$_GET['search']);
	}
//********************************************************************************************************************************************************************

	if ($_GET['nedir']=="kategoriler"){
	$page = cek($site."m/videos/home");
	preg_match_all("/<option value=\"(.*?)\">(.*?)<\/option>/",$page,$kategoriler);
	$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
		<items>
		<playlist_name><![CDATA[Filmler-1]]></playlist_name>
		"; 
		$pic='http://cdn.eroprofile.com/img/v2/header.png';
		if (($_SERVER['HTTP_USER_AGENT']=='') or ($_SERVER['HTTP_USER_AGENT']=='Mozilla/5.0 seyirTURKVOD 0.1')){
			$echo.=playlist('ARAMA',$pic,'LÜTFEN BİR SONRAKİ EKRANDA KLAVYEYİ KULLANABİLMEK İÇİN STOP TUŞUNA BASINIZ.','seyirTURKModul@webot@'.substr($mdldzn,7).$dosya.'?nedir=filmler&filmler='.$site.'m/videos/home?text=@ARAMA','playlist');
		} else {$echo.=smartarama('SMART TV ARAMA',$pic,'BU ARAMA SADECE SMART TVLER İÇİNDİR.',$site.$dosya,'playlist');}
		$echo.=playlist('YENİ EKLENENLER',$pic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.'m/videos/home'.'&xml','playlist');

		foreach ($kategoriler[1] as  $key => $kategori ) {
			$name=$kategoriler[2][$key];
			$link=$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.'m/videos/home?niche='.$kategoriler[1][$key];
			$echo.=playlist($name,$pic,$desc1,$link.'&xml','playlist');
			}
		$echo.=rabbit($desc1,$rabbit);			


	echo $echo;
	}

//********************************************************************************************************************************************************************

	if ($_GET['nedir']=="filmler"){
		$par=str_replace('tirt','?',$_GET['filmler']);
		$para=str_replace('tirti','&',$par);
		$dizianasayfa1=cek($para);
              preg_match("/sprIco16_66.*?a href=\"(.*?)\".*?prIco16_71/",$dizianasayfa1,$prev);
		preg_match("/pn:link.*?a href=\"(.*?)\".*?rIco16_73/",$dizianasayfa1,$next);
              preg_match_all("/div class=\"preview\">\s.*<a href=\"(.*?)\">\s.*<img src=\"(.*?)\" \/>/",$dizianasayfa1,$filmler);

		//echo $next[1];
		//echo $prev[1];
		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
			<items>
			<playlist_name><![CDATA[".strtoupper(substr($_GET['filmler'],27,-1))."]]></playlist_name>
			"; 
		foreach($filmler[1] as $key => $film){
			$name=strtoupper(substr($filmler[1][$key],15,-1));
			$pic=$filmler[2][$key];
			$link=$mdldzn.$dosya.'?nedir=filmparcalari&filmparcalari='.$filmler[1][$key];
			$pagelast=cek($site.$filmler[1][$key]);
			preg_match("/file:'(.*?)'/",$pagelast,$vidlink);
			$echo.=playlist($name,$pic,$desc1,$vidlink[1],'stream');
		}
		if($prev[1]<>""){
			$echo.=playlist('GERİ',$prevpic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.$prev[1].'&xml','playlist');
		}
		if($next[1]<>""){
			$echo.=playlist('İLERİ',$nextpic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.$next[1].'&xml','playlist');
		}
		
		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=kategoriler'.'&xml','playlist');

		$echo.=rabbit($desc1,$rabbit);			

echo $echo;

	}

?>
