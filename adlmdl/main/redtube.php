<?php




error_reporting(E_ALL ^ E_NOTICE);

#set_time_limit(60000);

$dosya='redtube.php';

include "../../a_cfg_b/cfg.php"; 

$site="http://www.redtube.com/";

//********************************************************************************************************************************************************************



	if ($_GET['search']<>''){

header("Location: ".$mdldzn.$dosya."?nedir=filmler&filmler=".$site."?search=".$_GET['search']);



	}

//********************************************************************************************************************************************************************



	if ($_GET['nedir']=="kategoriler"){

	$page = cek($site."/channels");

	//echo $page;

	preg_match("/categoryHeading(.*?)footer/s",$page,$subpage);

	preg_match_all("/href=\"(.*?)\" title=\"(.*?)\">/",$subpage[1],$kategoriler);

	preg_match_all("/src=\"(.*?)\"/",$subpage[1],$resim);

	preg_match_all("/numberVideos\">\s*(.*?)Videos/",$subpage[1],$sayi);



	$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

		<items>

		<playlist_name><![CDATA[REDTUBE.COM]]></playlist_name>

		"; 

		$pic='http://fc09.deviantart.net/fs70/f/2011/173/0/8/redtube_logo_by_kentulika-d3jn1z9.jpg';



		if (($_SERVER['HTTP_USER_AGENT']=='') or ($_SERVER['HTTP_USER_AGENT']=='Mozilla/5.0 seyirTURKVOD 0.1')){



			$echo.=playlist('ARAMA',$pic,'ARAMA FONKSYONU SADECE ENIGMA2 CIHAZLARDA ÇALIŞIR','seyirTURKModul@webot@'.substr($mdldzn,7).$dosya."?nedir=filmler&filmler=".$site."/?search=@ARAMA",'playlist');

		} else {$echo.=smartarama('SMART TV ARAMA',$pic,'BU ARAMA SADECE SMART TVLER İÇİNDİR.',$mdldzn.$dosya,'playlist');}







		foreach ($kategoriler[1] as  $key => $kategori ) {

			$name=$kategoriler[2][$key];

			$link=$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.urlencode($kategoriler[1][$key]);

			$pic=$resim[1][$key];

			$desc='Bu kategoride '.$sayi[1][$key].' adet video vardır...';

			$echo.=playlist($name,$pic,$desc,$link.'&xml','playlist');

		}

		$echo.=rabbit($desc1,$rabbit);			





	echo $echo;

	}



//********************************************************************************************************************************************************************



	if ($_GET['nedir']=="filmler"){



		$page=cek($_GET['filmler']);

///////////////////////sayfalama//////////////////////////////////////////////////////////////////////



		preg_match("/pages\"(.*?)legalLinks/s",$page,$subpagina);

		preg_match_all("/<a.*?href.*?=.*?\"(.*?)\".*?title.*?=.*?\".*?Page(.*?)\"/",$subpagina[1],$pag);



///////////////////////////////////////////////////////////////////////////////////////////////////////



		preg_match("/videosTable(.*?)pages\"/s",$page,$subpage);



              preg_match_all("/href=\"(.*?)\".*?title=\"(.*?)\".*?class/",$subpage[1],$filmler);

              preg_match_all("/img.*?src=\"(.*?)\"/",$subpage[1],$resim);

		preg_match_all("/<span class=\"d\">(.*?)<\/span>/",$subpage[1],$time);



		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

			<items>

			<playlist_name><![CDATA[REDTUBE.COM]]></playlist_name>

			"; 



		foreach($filmler[1] as $key => $film){

			$name=strtoupper($filmler[2][$key]);

			$pic=$resim[1][$key+1];

			$link=$mdldzn.$dosya.'?nedir=filmparcalari&filmparcalari='.$site.urlencode($filmler[1][$key]).'&before='.urlencode($_GET['filmler']);

			$desc='Süre: '.$time[1][$key];

			$echo.=playlist($name,$pic,$desc,$link.'&xml','playlist');

		}



		foreach($pag[1] as $key => $pa){

			$name='Sayfa : '.$pag[2][$key];

			$pic='http://fc09.deviantart.net/fs70/f/2011/173/0/8/redtube_logo_by_kentulika-d3jn1z9.jpg';

			$link=$mdldzn.$dosya."?nedir=filmler&filmler=".$site.urlencode($pag[1][$key]).'&xml';

			$echo.=playlist($name,$pic,$desc,$link,'playlist');

		}



		

		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=kategoriler'.'&xml','playlist');

		$echo.=rabbit($desc1,$rabbit);			





echo $echo;



	}

//**********************************************************************************************************************************************************************

	if ($_GET['nedir']=="filmparcalari"){

		$embed=str_replace('view_video.php?viewkey=','embed/',$_GET['filmparcalari']);

		$dizianasayfa3=cek($embed);

		preg_match("/mp4_url=(.*?)&/",$dizianasayfa3,$mp4);

		preg_match("/flv_h264_url=(.*?)&/",$dizianasayfa3,$h264);

		preg_match("/vidPoster\" class=\"hidden\"><img.*?src=\"(.*?)\"/",$dizianasayfa3,$resim);





		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

			<items>

			<playlist_name><![CDATA[FİLMİ İZLE]]></playlist_name>

			";



				$pic=$resim[1];

				$echo.=playlist('Alternatif 1',$pic,$desc1,urldecode($mp4[1]),'stream');

				$echo.=playlist('Alternatif 2',$pic,$desc1,urldecode($h264[1]),'stream');

		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$_GET['before'].'&xml','playlist');

		$echo.=rabbit($desc1,$rabbit);			



	echo $echo;

}



?>

