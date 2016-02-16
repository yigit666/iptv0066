<?php

error_reporting(E_ALL ^ E_NOTICE);

#set_time_limit(60000);

$dosya='beeg.php';

include "../../a_cfg_b/cfg.php"; 

$site="http://beeg.com/";



//********************************************************************************************************************************************************************



	//if ($_GET['search']<>''){

//header("Location: ".$mdldzn.$dosya."?nedir=filmler&filmler=".$site."/search.php?q=".$_GET['search']);





//	}

//********************************************************************************************************************************************************************



	if (!isset($_GET['nedir'])){

	$page = cek($site);

//	preg_match("/Categories<\/div>(.*?)All Categories/s",$page,$spage);

	preg_match_all("/<li><a target=\"_self\" href=\"\/(.*?)\".*?>(.*?)<\/a><\/li>/",$page,$kategoriler);

	$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

		<items>

		<playlist_name><![CDATA[KATEGORİLER]]></playlist_name>

		"; 

		$pic='https://github.com/mikedm139/UnSupportedAppstore.bundle/diff_blob/3f239fa057e5b660351642c0edf81b4a40bfc11e/Contents/Resources/beeg-icon.png';



		//if (($_SERVER['HTTP_USER_AGENT']=='') or ($_SERVER['HTTP_USER_AGENT']=='Mozilla/5.0 seyirTURKVOD 0.1')){



		//	$echo.=playlist('ARAMA',$pic,'ARAMA FONKSYONU SADECE ENIGMA2 CIHAZLARDA ÇALIŞIR','seyirTURKModul@webot@'.substr($mdldzn,7).$dosya."?nedir=filmler&filmler=".$site.'/search.php?q=@ARAMA','playlist');

		//} else {$echo.=smartarama('SMART TV ARAMA',$pic,'BU ARAMA SADECE SMART TVLER İÇİNDİR.',$mdldzn.$dosya,'playlist');}









		$echo.=playlist('YENİ EKLENENLER',$pic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.'&xml','playlist');



		foreach ($kategoriler[1] as  $key => $kategori ) {

			$name=$kategoriler[2][$key];

			$link=$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.$kategoriler[1][$key];

			if ((strpos($name,'div')===false)and (strpos($name,'height')===false)){

				$echo.=playlist($name,$pic,$desc1,$link.'&xml','playlist');

			}

		}

		$echo.=rabbit($desc1,$rabbit);			



	echo $echo;

	}



//********************************************************************************************************************************************************************



	if ($_GET['nedir']=="filmler"){





		$page=cek($_GET['filmler']);

///////////////////////sayfalama//////////////////////////////////////////////////////////////////////



		preg_match("/pager(.*?)PAGER/s",$page,$subpagina);

		preg_match_all("/href=\"(.*?)\" target=\"_self\">(.*?)<\/a>/",$subpagina[1],$pag);



///////////////////////////////////////////////////////////////////////////////////////////////////////





		preg_match("/var.*?tumbid.*?=\[(.*?)\]/", $page, $vidids);

		//preg_match("/var.*?tumbalt.*?=\[(.*?)\]/", $page, $descs);

		preg_match("/var.*?IMGthumb.*?=.*?\'(.*?)\';/",$page, $picpre);



		$vidid=explode(',',$vidids[1]);

		preg_match("/var tumbalt =\[(.*?)\]/",$page,$subdesc);

		$sub=str_replace("'","<>",str_replace("\\'","",$subdesc[1]));

		preg_match_all("/<>(.*?)<>/",$sub,$desc);





		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

			<items>

			<playlist_name><![CDATA[BEEG.COM]]></playlist_name>

			"; 

		foreach ($vidid as $key =>$vidno){

			$name=$desc[1][$key];

			$pic=$picpre[1].$vidid[$key].'.jpg';

			$link=$mdldzn.$dosya.'?nedir=filmparcalari&id='.$vidid[$key].'&before='.urlencode($_GET['filmler']);

			$echo.=playlist($name,$pic,$name,$link.'&xml','playlist');

		}

		foreach($pag[1] as $key => $pa){

			$name='Sayfa-'.$pag[2][$key];

			$pic='adres icon.png';

			$link=$mdldzn.$dosya."?nedir=filmler&filmler=http://beeg.com".urlencode($pag[1][$key]).'&xml';

			$echo.=playlist($name,$pic,$desc,$link,'playlist');

		}



		

		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=kategoriler'.'&xml','playlist');

		$echo.=rabbit($desc1,$rabbit);			




echo $echo;



	}

//**********************************************************************************************************************************************************************

	if ($_GET['nedir']=="filmparcalari"){

		$page=cek($mdldzn.$dosya.$_GET['id']);

		preg_match("/logo.file\':.*?\'(.*?)\'/",$page,$resim);

		//preg_match("/file\':.*?\'(.*?)\'/",$page,$link);



		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

			<items>

			<playlist_name><![CDATA[İZLE]]></playlist_name>

			";



				$name='İZLE';

				$pic=$resim[1];

				$link=izle('http://video.mystreamservice.com/480p/'.$_GET['id'].'.mp4');

				$echo.=playlist($name,$pic,$desc1,$link,'stream');



		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$_GET['before'].'&xml','playlist');

		$echo.=rabbit($desc1,$rabbit);			



	echo $echo;

}



?>

