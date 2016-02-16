<?php


error_reporting(E_ALL ^ E_NOTICE);

$dosya='fraggme.php';

include "../../a_cfg_b/cfg.php"; 

$site="http://fragg.me";

//********************************************************************************************************************************************************************



	if ($_GET['nedir']=="kategoriler"){

	$page = cek($site);

	preg_match_all("/<li><a href=\"(.*?)\".*?>(.*?)<\/a><\/li>/",$page,$kategoriler);

	$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

		<items>

		<playlist_name><![CDATA[FRAGG.ME]]></playlist_name>

		"; 

		$pic='fragg.me logo adresi';

		$echo.=playlist('YENİ EKLENENLER',$pic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.'&xml','playlist');



		foreach ($kategoriler[1] as  $key => $kategori ) {

			$name=str_replace('&amp;','&',$kategoriler[2][$key]);

			$link=$mdldzn.$dosya."?nedir=filmler&filmler=".$site.$kategoriler[1][$key];

			$echo.=playlist($name,$pic,$desc1,$link.'&xml','playlist');

			}

		$echo.=anamenudon($desc1,$otoportal);			




	echo $echo;

	}



//********************************************************************************************************************************************************************



	if ($_GET['nedir']=="filmler"){

		$dizianasayfa1=cek($_GET['filmler']);

		$ad=substr($_GET['filmler'],16,-1);

		preg_match("/<div class=\"pages\"><a href=\"(.*?)\" class=\"nextprev\"/",$dizianasayfa1,$prev);

		preg_match("/current\">.*?<\/span> <a href=\"(.*?)\">.*?<\/a>/",$dizianasayfa1,$next);

		preg_match_all("/<a.*?href=\"(.*?)\"><img.*?src=\"(.*?)\" alt=.*?title=\"(.*?)\"/",$dizianasayfa1,$filmler);

		//echo $next[1];

		//echo $prev[1];

		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

			<items>

			<playlist_name><![CDATA[".strtoupper(str_replace($bunu, $degistir, $ad))."]]></playlist_name>

			"; 

		foreach($filmler[1] as $key => $film){

			

			$name=$filmler[3][$key];

			$pic=$filmler[2][$key];

			$link=$mdldzn.$dosya.'?nedir=filmparcalari&filmparcalari='.$site.$filmler[1][$key].'&before='.$_GET['filmler'];

			$echo.=playlist($name,$pic,$desc1,$link.'&xml','playlist');

		}

		if($next[1]<>""){

			$echo.=playlist('İLERİ',$nextpic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.$next[1].'&xml','playlist');

		}

		if($prev[1]<>""){

			$prew=$prev[1];

			$echo.=playlist('GERİ',$prevpic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.$prev[1].'&xml','playlist');

		}



		

		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=kategoriler'.'&xml','playlist');

		$echo.=anamenudon($desc1,$otoportal);			




echo $echo;

	}



//**********************************************************************************************************************************************************************

	if ($_GET['nedir']=="filmparcalari"){

		$dizianasayfa3=cek($_GET['filmparcalari']);

		preg_match("/image: \"(.*?)\"/",$dizianasayfa3,$pictu); 

		preg_match_all("/{ file: \"(.*?)\", height: (.*?), width: (.*?) }/",$dizianasayfa3,$vidlinks); 



		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

			<items>

			<playlist_name><![CDATA[".strtoupper(substr($_GET['filmler'],22,-1))."]]></playlist_name>

			";

		if (($vidlinks[1]<>'')) {

			foreach($vidlinks[1] as $key => $film){

				if (substr($vidlinks[1][$key],-4)<>'webm'){

					$name='Kalite : '.$vidlinks[3][$key].' X '.$vidlinks[2][$key];

					$pic=$pictu[1];

					$link=$vidlinks[1][$key];

					$echo.=playlist($name,$pic,$desc1,$link,'stream');

				}		

			}

		}

		

 



		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc,$mdldzn.$dosya.'?nedir=filmler&filmler='.$_GET['before'].'&xml','playlist');

		$echo.=anamenudon($desc1,$otoportal);			


	echo $echo;

}

?>

