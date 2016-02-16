<?php
header("durum: iyiyim");
error_reporting(E_ALL ^ E_NOTICE);
#set_time_limit(60000);
$dosya='ashemale.php';
include "../../a_cfg_b/cfg.php"; 
$site='http://www.ashemaletube.com/';
//********************************************************************************************************************************************************************

	if ($_GET['search']<>''){
header("Location: ".$mdldzn."ashemale.php?nedir=filmler&filmler=".$site."search/".$_GET['search']."/page1.html");


	}
//********************************************************************************************************************************************************************

	if ($_GET['nedir']=="kategoriler"){
	$page = cek($site);
	preg_match("/Hot Videos(.*?)Top Users/s",$page,$subpage);
	preg_match_all("/href=\"(.*?)\".*?title=\"(.*?)\"/",$subpage[1],$kategoriler);
	$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
		<items>
		<playlist_name><![CDATA[ASHEMALETUBE.COM]]></playlist_name>
		"; 
		$pic='adres ashemaletube.png';

		if (($_SERVER['HTTP_USER_AGENT']=='') or ($_SERVER['HTTP_USER_AGENT']=='Mozilla/5.0 seyirTURKVOD 0.1')){

			$echo.=playlist('ARAMA',$pic,'ARAMA FONKSYONU SADECE ENIGMA2 CIHAZLARDA ÇALIŞIR','seyirTURKModul@webot@'.substr($site,7).$dosya.'?nedir=filmler&filmler='.$site.'video/search=@ARAMA','playlist');
		} else {$echo.=smartarama('SMART TV ARAMA',$pic,'BU ARAMA SADECE SMART TVLER İÇİNDİR.',$mdldzn.$dosya,'playlist');}




		//$echo.=playlist('YENİ EKLENENLER',$pic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler=http://www.pornhub.com/video'.'&xml','playlist');
		//$echo.=playlist('EN ÇOK İZLENEN',$pic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler=http://www.pornhub.com/video?o=mv'.'&xml','playlist');
		//$echo.=playlist('EN ÇOK OY ALANLAR',$pic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler=http://www.pornhub.com/video?o=tr'.'&xml','playlist');
		//$echo.=playlist('UZUN METRAJLI FİLMLER',$pic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler=http://www.pornhub.com/video?o=lg'.'&xml','playlist');

		foreach ($kategoriler[1] as  $key => $kategori ) {
			$name=$kategoriler[2][$key];
			if (strpos($kategoriler[1][$key],'http://')!==false){
			$link=$mdldzn.$dosya.'?nedir=filmler&filmler='.$kategoriler[1][$key];}
			else {$link=$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.$kategoriler[1][$key];}
			$echo.=playlist($name,$pic,$desc1,$link.'&xml','playlist');
		}
		$echo.=rabbit($desc1,$rabbit);			


	echo $echo;
	}

//********************************************************************************************************************************************************************

	if ($_GET['nedir']=="filmler"){


		$dizianasayfa1=cek($_GET['filmler']);
///////////////////////sayfalama//////////////////////////////////////////////////////////////////////

		preg_match("/pagination(.*?)Next/s",$dizianasayfa1,$subpagina);
		preg_match_all("/href=\"(.*?)\">(.*?)<\/a>/",$subpagina[0],$pag);
///////////////////////////////////////////////////////////////////////////////////////////////////////

		preg_match("/block\"(.*?)pagination/s", $dizianasayfa1, $subpage);
              preg_match_all("/titlevideospot\">\s*<a href=\"(.*?)\" title=\"(.*?)\".*?<\/a>/",$subpage[1],$filmler);
              preg_match_all("/img.*?src=\"(.*?)\"/",$subpage[1],$resim);
		preg_match_all("/viddata flr\">(.*?)<\/span>/",$subpage[1],$time);
		preg_match_all("/fs11 fll\">(.*?)View/",$subpage[1],$view);
		preg_match_all("/<span class=\"viddata fs11\">(.*?)days ago<\/span>/",$subpage[1],$tarih);

		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
			<items>
			<playlist_name><![CDATA[ASHEMALETUBE.COM]]></playlist_name>
			"; 

		foreach($filmler[1] as $key => $film){
			$name=strtoupper($filmler[2][$key]);
			$pic=$resim[1][$key];
			$link=$mdldzn.$dosya.'?nedir=filmparcalari&filmparcalari='.$site.$filmler[1][$key].'&before='.urlencode($_GET['filmler']);
			$desc='İzlenme sayısı: '.$view[1][$key].'   Süre: '.$time[1][$key].' ve '.$tarih[1][$key].' gün önce eklendi';
			$echo.=playlist($name,$pic,$desc,$link.'&xml','playlist');
		}

		foreach($pag[1] as $key => $pa){
			$name='Sayfa-'.$pag[2][$key];
			$pic='adres ashemaletube.png';
			$link=$mdldzn.$dosya."?nedir=filmler&filmler=".$site.urlencode(str_replace('&amp;','&',$pag[1][$key])).'&xml';
			$echo.=playlist($name,$pic,$desc,$link,'playlist');
		}

		
		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=kategoriler&xml','playlist');
		$echo.=rabbit($desc1,$rabbit);			

echo $echo;

	}
//**********************************************************************************************************************************************************************
	if ($_GET['nedir']=="filmparcalari"){
		$dizianasayfa3=cek($_GET['filmparcalari']);
		preg_match("/\'file\': \"(.*?)\"/",$dizianasayfa3,$vidlink);
		preg_match("/\'image\': \"(.*?)\"/",$dizianasayfa3,$resim);

		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
			<items>
			<playlist_name><![CDATA[VIDEO İZLE]]></playlist_name>
			";

				$name='İZLE';
				$pic=$resim[1];
				$echo.=playlist($name,$pic,$desc1,$vidlink[1],'stream');
		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$_GET['before'].'&xml','playlist');
		$echo.=rabbit($desc1,$rabbit);			
	echo $echo;
}

?>
