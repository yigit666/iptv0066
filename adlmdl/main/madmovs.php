<?php

error_reporting(E_ALL ^ E_NOTICE);

#set_time_limit(60000);

$dosya='madmovs.php';

include "../../a_cfg_b/cfg.php"; 

$site="http://www.madmovs.com/";



//********************************************************************************************************************************************************************



	//if ($_GET['search']<>''){

//header("Location: ".$mdldzn.$dosya."?nedir=filmler&filmler=".$site."/search.php?q=".$_GET['search']);





//	}

//********************************************************************************************************************************************************************



	if ($_GET['nedir']=="kategoriler"){

	$page = cek($site.'categories.html');

	preg_match("/Porn Categories(.*?)featured/s",$page,$spage);

	preg_match_all("/<a href=\"\/(.*?)\".*?title=\"(.*?)\">\s*<h3>(.*?)<\/h3>\s*<img alt=\"(.*?)\" src=\"(.*?)\" \/>/",$spage[1],$kategoriler);

	$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

		<items>

		<playlist_name><![CDATA[KATEGORİLER]]></playlist_name>

		"; 

		//if (($_SERVER['HTTP_USER_AGENT']=='') or ($_SERVER['HTTP_USER_AGENT']=='Mozilla/5.0 seyirTURKVOD 0.1')){



		//	$echo.=playlist('ARAMA',$pic,'ARAMA FONKSYONU SADECE ENIGMA2 CIHAZLARDA ÇALIŞIR','seyirTURKModul@webot@'.substr($mdldzn,7).$dosya."?nedir=filmler&filmler=".$site.'/search.php?q=@ARAMA','playlist');

		//} else {$echo.=smartarama('SMART TV ARAMA',$pic,'BU ARAMA SADECE SMART TVLER İÇİNDİR.',$mdldzn.$dosya,'playlist');}



		$echo.=playlist('YENİ EKLENENLER','adres madmovs.png',$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler=http://www.madmovs.com&xml','playlist');



		foreach ($kategoriler[1] as  $key => $kategori ) {

			$name=$kategoriler[3][$key];

			$link=$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.$kategoriler[1][$key];

			$echo.=playlist($name,$kategoriler[5][$key],$kategoriler[2][$key],$link.'&xml','playlist');

		}

		$echo.=rabbit($desc1,$rabbit);			

	echo $echo;

	}



//********************************************************************************************************************************************************************



	if ($_GET['nedir']=="filmler"){





		$page=cek($_GET['filmler']);

///////////////////////sayfalama//////////////////////////////////////////////////////////////////////



		preg_match("/paging(.*?)banners-horizontal/s",$page,$subpagina);

		preg_match_all("/href=\"(.*?)\" title=.*?\">(.*?)<\/a>/",$subpagina[1],$pag);



///////////////////////////////////////////////////////////////////////////////////////////////////////

		preg_match("/<div class=\"thumbs\">(.*?)class=\"banners \">/s",$page,$spage);

		preg_match_all("/href=\"\/(.*?)\" title=\"(.*?)\">\s*<h3>(.*?)<\/h3>\s*<img alt=\"(.*?)\" data-thumbs=.*?src=\"(.*?)\" \/>\s*<span>(.*?)<\/span>/",$spage[1],$kategoriler);





		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

			<items>

			<playlist_name><![CDATA[MADMOVS.COM]]></playlist_name>

			"; 

		foreach ($kategoriler[1] as $key =>$kate){

			$link=$mdldzn.$dosya.'?nedir=filmparcalari&filmparcalari='.$site.$kategoriler[1][$key].'&before='.urlencode($_GET['filmler']);

			$echo.=playlist($kategoriler[3][$key],$kategoriler[5][$key],'Süre: '.$kategoriler[6][$key].'</br>'.$kategoriler[2][$key],$link.'&xml','playlist');

		}

		foreach($pag[1] as $key => $pa){

			$name='Sayfa-'.$pag[2][$key];

			$pic='adres madmovs.png';

			$link=$mdldzn.$dosya."?nedir=filmler&filmler=http://www.madmovs.com".urlencode($pag[1][$key]).'&xml';

			$echo.=playlist($name,$pic,$desc,$link,'playlist');

		}



		

		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=kategoriler'.'&xml','playlist');

		$echo.=rabbit($desc1,$rabbit);			


echo $echo;



	}

//**********************************************************************************************************************************************************************

	if ($_GET['nedir']=="filmparcalari"){

		$page=cek($_GET['filmparcalari']);

		preg_match("/video\'.*?:.*?\'(.*?)\'/",$page,$link);

		preg_match("/cover\'.*?:.*?\'(.*?)\'/",$page,$resim);



		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

			<items>

			<playlist_name><![CDATA[İZLE]]></playlist_name>

			";



				$name='İZLE';

				$pic=$resim[1];

				$echo.=playlist($name,$resim[1],$desc1,$link[1],'stream');



		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$_GET['before'].'&xml','playlist');

		$echo.=rabbit($desc1,$rabbit);			



	echo $echo;

}



?>

