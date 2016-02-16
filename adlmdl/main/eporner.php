<?php




error_reporting(E_ALL ^ E_NOTICE);

//////////////////////////dosya adını alan bolum//////////////////

#set_time_limit(60000);

$file = $_SERVER["SCRIPT_NAME"];

$break = Explode('/', $file);

$dosya = $break[count($break) - 1]; 

/////////////////////////////////////////////////////////////////

include "../../a_cfg_b/cfg.php"; 

$site="http://www.eporner.com";

$pic='http://static1.nl.eprncdn.com/new/logo.png';

//*****************************************************************************************************************************************

	if ($_GET['nedir']=="kategoriler"){

	$page = cek($site.'/categories/');

//echo $page;

	preg_match("/ies<\/h1>(.*?)bottomspace/s",$page,$spage);

//echo $spage[1];

	preg_match_all("/<a href=\"(.*?)\" title=\"(.*?)\"><img src=\"(.*?)\" alt=.*?><h2>.*?<\/h2><\/a>/",$spage[1],$kategoriler);

//print_r($kategoriler);



	$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

		<items>

		<playlist_name><![CDATA[KATEGORİLER]]></playlist_name>

		"; 

		//if (($_SERVER['HTTP_USER_AGENT']=='') or ($_SERVER['HTTP_USER_AGENT']=='Mozilla/5.0 seyirTURKVOD 0.1')){



		//	$echo.=playlist('ARAMA',$pic,'ARAMA FONKSYONU SADECE ENIGMA2 CIHAZLARDA ÇALIŞIR','seyirTURKModul@webot@'.substr($mdldzn,7).$dosya."?nedir=filmler&filmler=".$site.'/search.php?q=@ARAMA','playlist');

		//} else {$echo.=smartarama('SMART TV ARAMA',$pic,'BU ARAMA SADECE SMART TVLER İÇİNDİR.',$mdldzn.$dosya,'playlist');}

		$echo.=playlist('YENİ EKLENENLER',$pic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.'&xml','playlist');
		$echo.=playlist('HD PORN',$pic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.'/hd/&xml','playlist');
		$echo.=playlist('POPULAR',$pic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.'/weekly_top/&xml','playlist');
		$echo.=playlist('EV YAPIMI',$pic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.'/homemade/&xml','playlist');
		$echo.=playlist('TOP RATED',$pic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.'/top_rated/&xml','playlist');
		$echo.=playlist('SOLO GIRLS',$pic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.'/solo/&xml','playlist');



		foreach ($kategoriler[1] as  $key => $kategori ) {

			$name=$kategoriler[2][$key];

			$link=$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.$kategoriler[1][$key];

			$echo.=playlist($name,$kategoriler[3][$key],$name,$link.'&xml','playlist');

		}

		$echo.=rabbit($desc1,$rabbit);			




	echo $echo;

	}



//********************************************************************************************************************************************************************



	if ($_GET['nedir']=="filmler"){





		$page=cek($_GET['filmler']);

///////////////////////sayfalama//////////////////////////////////////////////////////////////////////



		//preg_match("/pgn(.*?)class=\"info/s",$page,$subpagina);

		preg_match_all("/title=\'Page.*?\'><span.*?>(.*?)<\/span><\/a> <a href=\'(.*?)\'/",$page,$pag);



///////////////////////////////////////////////////////////////////////////////////////////////////////

		//preg_match("/id=\"video\">(.*?)pgn/s",$page,$spage);

		preg_match_all("/duration\" content=\"(.*?)\" \/> <meta.*?content=\"(.*?)\" \/>.*?<meta itemprop=\"description\" content=\"(.*?)\" \/>.*?itemprop=\"name\"><a href=\"(.*?)\" title=\"(.*?)\" id=\"/",$page,$kategoriler);

		//preg_match_all("/background-image: url\((.*?jpg)/",$page,$resimler);





		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

			<items>

			<playlist_name><![CDATA[EPORNER.COM]]></playlist_name>

			"; 

		foreach ($kategoriler[1] as $key =>$kate){

			$link=$mdldzn.$dosya.'?nedir=filmparcalari&filmparcalari='.$site.$kategoriler[4][$key].'&before='.urlencode($_GET['filmler']);

			$echo.=playlist($kategoriler[5][$key],$kategoriler[2][$key],'Süre: '.$kategoriler[1][$key].'</br>'.$kategoriler[3][$key],$link.'&xml','playlist');

		}

		foreach($pag[1] as $key => $pa){

			$name='Sayfa-'.$pag[1][$key];

			$link=$mdldzn.$dosya."?nedir=filmler&filmler=".urlencode($site.$pag[2][$key]).'&xml';

			$echo.=playlist($name,$pic,$desc,$link,'playlist');

		}



		

		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=kategoriler'.'&xml','playlist');

		$echo.=rabbit($desc1,$rabbit);			




echo $echo;



	}

//**********************************************************************************************************************************************************************

	if ($_GET['nedir']=="filmparcalari"){

		$page=cek($_GET['filmparcalari']);

		preg_match("/script type=\"text\/javascript\" src=\"\/config(.*?)\"><\/script>/",$page,$link);
//echo $link[1];
		$spage = cek($site.'/config'.$link[1]);
//echo $spage;
		preg_match_all("/file: \"(.*?)\"\,\s*label: \"(.*?)\"\,\s*type: \"(.*?)\"/",$spage,$resim);

//print_r ($resim);











		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

			<items>

			<playlist_name><![CDATA[İZLE]]></playlist_name>

			";


		foreach ($resim[1] as $key => $kalite){

				$echo.=playlist('Kalite :'.$resim[2][$key],$pic,'Video tipi: '.$resim[3][$key],$resim[1][$key],'stream');

		}

		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$_GET['before'].'&xml','playlist');

		$echo.=rabbit($desc1,$rabbit);			


	echo $echo;

}



?>

