<?php

error_reporting(E_ALL ^ E_NOTICE);
#set_time_limit(60000);
$dosya='spankwiretra.php';
include "../../a_cfg_b/cfg.php"; 
$site='http://www.spankwire.com/';
$home='home';
$cins='Tranny';

//http://www.spankwire.com/categories/Straight
//********************************************************************************************************************************************************************
	if ($_GET['search']<>''){
header("Location: ".$mdldzn.$dosya.'?nedir=filmler&filmler='.$site."/search/tranny/keyword/".$_GET['search']);
	}
//********************************************************************************************************************************************************************

	if ($_GET['nedir']=="anakate"){
	$anakates=array('CATEGORIES','FEATURED','TOP_RATED','MOST_VIWED','NEW_VIDEOS');
	$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
		<items>
		<playlist_name><![CDATA[SHEMALE KATEGORİLER]]></playlist_name>
		"; 
		$pic='http://cdn1.static.spankwire.phncdn.com/Template/www_spankwire_com/img/brand/logo.gif';
		if (($_SERVER['HTTP_USER_AGENT']=='') or ($_SERVER['HTTP_USER_AGENT']=='Mozilla/5.0 seyirTURKVOD 0.1')){
			$echo.=playlist('ARAMA',$pic,'LÜTFEN BİR SONRAKİ EKRANDA KLAVYEYİ KULLANABİLMEK İÇİN STOP TUŞUNA BASINIZ.','seyirTURKModul@webot@'.substr($site,7).$dosya.'?nedir=filmler&filmler='.$site.'search/tranny/keyword/@ARAMA','playlist');
		} else {$echo.=smartarama('SMART TV ARAMA',$pic,'BU ARAMA SADECE SMART TVLER İÇİNDİR.',$mdldzn.$dosya,'playlist');}

		foreach ($anakates as  $key => $kategori ) {
			$name=$anakates[$key];
			$link=$mdldzn.$dosya.'?nedir=altkate&altkate='.$anakates[$key];
			if ($anakates[$key] =='CATEGORIES'){
				$echo.=playlist($name,$pic,$desc1,$mdldzn.$dosya.'?nedir=kategoriler&kategoriler='.$site.'/categories/'.$cins.'&xml','playlist');
			} else {
				$echo.=playlist($name,$pic,$desc1,$link.'&xml','playlist');
				}			
		}
		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.'spankwire.xml','playlist');

		$echo.=rabbit($desc1,$rabbit);			

	echo $echo;
	}


//********************************************************************************************************************************************************************

	if ($_GET['nedir']=="altkate"){
	
	$butkates=array('Today','Yesterday','Week','Month','Year','All_Time');
	$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
		<items>
		<playlist_name><![CDATA[ANA KATEGORİLER]]></playlist_name>
		"; 


	switch ($_GET['altkate']) {
    		case 'FEATURED':
		$tip='Featured';
		$home='home2';
		foreach ($butkates as  $key => $kategori ) {
			$pic='http://cdn1.static.spankwire.phncdn.com/Template/www_spankwire_com/img/brand/logo.gif';
			$name=strtoupper($tip.' - '.$butkates[$key]);
			$altlink=$site.'/'.$home.'/'.$cins.'/'.$tip.'/'.$butkates[$key].'/Submitted';
			$link=$mdldzn.$dosya.'?nedir=filmler&filmler='.$altlink.'&before=?nedir=altkate$altkate='.$_GET['altkate'];
			$echo.=playlist($name,$pic,$desc1,$link.'&xml','playlist');
			}
			break;
    		case 'TOP_RATED':
		$tip='Rating';
		$home='home1';
		foreach ($butkates as  $key => $kategori ) {
			$pic='http://cdn1.static.spankwire.phncdn.com/Template/www_spankwire_com/img/brand/logo.gif';
			$name=strtoupper($tip.' - '.$butkates[$key]);
			$altlink=$site.'/'.$home.'/'.$cins.'/'.$butkates[$key].'/'.$tip;
			$link=$mdldzn.$dosya.'?nedir=filmler&filmler='.$altlink.'&before=?nedir=altkate$altkate='.$_GET['altkate'];
			$echo.=playlist($name,$pic,$desc1,$link.'&xml','playlist');
			}
			break;
    		case 'MOST_VIWED':
		$tip='Views';
		$home='home1';
		foreach ($butkates as  $key => $kategori ) {
			$pic='http://cdn1.static.spankwire.phncdn.com/Template/www_spankwire_com/img/brand/logo.gif';
			$name=strtoupper($tip.' - '.$butkates[$key]);
			$altlink=$site.'/'.$home.'/'.$cins.'/'.$butkates[$key].'/'.$tip;
			$link=$mdldzn.$dosya.'?nedir=filmler&filmler='.$altlink.'&before=?nedir=altkate$altkate='.$_GET['altkate'];
			$echo.=playlist($name,$pic,$desc1,$link.'&xml','playlist');
			}
			break;
    		case 'NEW_VIDEOS':
		$tip='Upcoming';
		$home='home2';
		$butkates[].='VotesLeft';
		foreach ($butkates as  $key => $kategori ) {
			$pic='http://cdn1.static.spankwire.phncdn.com/Template/www_spankwire_com/img/brand/logo.gif';
			$name=strtoupper($tip.' - '.$butkates[$key]);
			$altlink=$site.'/'.$home.'/'.$cins.'/'.$tip.'/'.$butkates[$key].'/Rating';
			$link=$mdldzn.$dosya.'?nedir=filmler&filmler='.$altlink.'&before=?nedir=altkate$altkate='.$_GET['altkate'];
			$echo.=playlist($name,$pic,$desc1,$link.'&xml','playlist');
			}
			break;

}

		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=anakate'.'&xml','playlist');

		$echo.=rabbit($desc1,$rabbit);			

	echo $echo;
	}








//********************************************************************************************************************************************************************

	if ($_GET['nedir']=="kategoriler"){
	$page = cek($_GET['kategoriler']);
	preg_match_all("/<a href=\"(.*?)\" onClick=.*?><img src=\"(.*?)\" alt=\"(.*?)\"/",$page,$kategoriler);
	$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
		<items>
		<playlist_name><![CDATA[Filmler-1]]></playlist_name>
		"; 
		foreach ($kategoriler[1] as  $key => $kategori ) {
			$pic=$kategoriler[2][$key];
			$name=$kategoriler[3][$key];
			$link=$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.$kategoriler[1][$key].'&before=?nedir=kategoriler$kategoriler='.$_GET['kategoriler'];
			$echo.=playlist($name,$pic,$desc1,$link.'&xml','playlist');
			}
		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=anakate'.'&xml','playlist');

		$echo.=rabbit($desc1,$rabbit);			

$_GET['kategoriler']='';
	echo $echo;
	}

//********************************************************************************************************************************************************************

	if ($_GET['nedir']=="filmler"){

		$dizianasayfa1=cek($_GET['filmler']);
              preg_match("/prev\" href=\"(.*?)\"/",$dizianasayfa1,$prev);
		preg_match("/next\" href=\"(.*?)\"/",$dizianasayfa1,$next);
              preg_match_all("/<a href=\"(.*?)\"><img.*?src=\"(.*?)\" onError=/",$dizianasayfa1,$filmler);

		//echo $next[1];
		//echo $prev[1];
		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
			<items>
			<playlist_name><![CDATA[".strtoupper(substr($_GET['filmler'],45,-13))."]]></playlist_name>
			"; 
		foreach($filmler[1] as $key => $film){
			$name=str_replace('-', ' ', strtoupper(substr($filmler[1][$key],1,-13)));
			$pic=$filmler[2][$key];
			$link=$mdldzn.$dosya.'?nedir=filmparcalari&filmparcalari='.$site.$filmler[1][$key].'&before='.$_GET['filmler'];
			//$pagelast=cek('http://www.spankwire.com'.$filmler[1][$key]);
			//preg_match("/flashvars\.video_url = \"(.*?)\";/",$pagelast,$vidlink);
			//$echo.=playlist($name,$pic,$desc1,urldecode($vidlink[1]),'stream');
			$echo.=playlist($name,$pic,$desc1,$link.'&xml','playlist');

		}
		if($prev[1]<>""){
			$echo.=playlist('GERİ',$prevpic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$prev[1].'&xml','playlist');
		}
		if($next[1]<>""){
			$echo.=playlist('İLERİ',$nextpic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$next[1].'&xml','playlist');
		}
		
		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.str_replace('$','&',$_GET['before']).'&xml','playlist');

		$echo.=rabbit($desc1,$rabbit);			


echo $echo;

	}



//********************************************************************************************************************************************************************

	if ($_GET['nedir']=="filmparcalari"){

		$dizianasayfa1=cek($_GET['filmparcalari']);
		preg_match('/flashvars.image_url = "(.*?)"/',$dizianasayfa1,$resim);
		preg_match('/flashvars.quality_180p = "(.*?)"/',$dizianasayfa1,$p180p);
		preg_match('/flashvars.quality_240p = "(.*?)"/',$dizianasayfa1,$p240p);
		preg_match('/flashvars.quality_480p = "(.*?)"/',$dizianasayfa1,$p480p);
		preg_match('/flashvars.quality_720p = "(.*?)"/',$dizianasayfa1,$p720p);
		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
			<items>
			<playlist_name><![CDATA[".str_replace('-',' ',strtoupper(substr($_GET['filmler'],25,-1)))."]]></playlist_name>
			"; 
		if ($p180p[1]<>''){
			$name='180 p';
			$pic=$resim[1];
			$link=urldecode($p180p[1]);
			$echo.=playlist($name,$pic,$desc1,$link,'stream');
		}
		if ($p240p[1]<>''){
			$name='240 p';
			$pic=$resim[1];
			$link=urldecode($p240p[1]);
			$echo.=playlist($name,$pic,$desc1,$link,'stream');
		}	
		if ($p480p[1]<>''){
			$name='480 p';
			$pic=$resim[1];
			$link=urldecode($p480p[1]);
			$echo.=playlist($name,$pic,$desc1,$link,'stream');
		}
		if ($p720p[1]<>''){
			$name='720 p';
			$pic=$resim[1];
			$link=urldecode($p720p[1]);
			$echo.=playlist($name,$pic,$desc1,$link,'stream');
		}
		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$_GET['before'].'&xml','playlist');

		$echo.=rabbit($desc1,$rabbit);			


echo $echo;

	}





?>
