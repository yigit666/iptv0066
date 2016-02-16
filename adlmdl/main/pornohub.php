<?php
error_reporting(E_ALL ^ E_NOTICE);

#set_time_limit(60000);

$dosya='pornohub.php';

include "../../a_cfg_b/cfg.php"; 

$site="http://www.pornhub.com/video";
$sitealone="http://www.pornhub.com";


//********************************************************************************************************************************************************************



	if ($_GET['search']<>''){

header("Location: ".$mdldzn.$dosya."?nedir=filmler&filmler=".$site."/search?search=".$_GET['search']);

	}

//********************************************************************************************************************************************************************



	if ($_GET['nedir']=="kategoriler"){

	$page = cek($site);

	//echo $page;

	preg_match("/categories tracking(.*?)Production/s",$page,$subpage);

	preg_match_all("/href=\"(.*?)\">(.*?)<\/a>/",$subpage[1],$kategoriler);

	$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

		<items>

		<playlist_name><![CDATA[PORNHUB.COM]]></playlist_name>

		"; 

		$pic='http://cdn1.static.pornhub.phncdn.com/images/pornhub_logo.png';



		if (($_SERVER['HTTP_USER_AGENT']=='') or ($_SERVER['HTTP_USER_AGENT']=='Mozilla/5.0 seyirTURKVOD 0.1')){



			$echo.=playlist('ARAMA',$pic,'ARAMA FONKSYONU SADECE ENIGMA2 CIHAZLARDA ÇALIŞIR','seyirTURKModul@webot@'.substr($mdldzn,7).$dosya."?nedir=filmler&filmler=".$site.'/search=@ARAMA','playlist');

		} else {$echo.=smartarama('SMART TV ARAMA',$pic,'BU ARAMA SADECE SMART TVLER İÇİNDİR.',$mdldzn.$dosya,'playlist');}









		$echo.=playlist('YENİ EKLENENLER',$pic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.'&xml','playlist');

		$echo.=playlist('EN ÇOK İZLENEN',$pic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.'?o=mv'.'&xml','playlist');

		$echo.=playlist('EN ÇOK OY ALANLAR',$pic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.'?o=tr'.'&xml','playlist');

		$echo.=playlist('UZUN METRAJLI FİLMLER',$pic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.'?o=lg'.'&xml','playlist');



		foreach ($kategoriler[1] as  $key => $kategori ) {

			$name=$kategoriler[2][$key];

			$link=$mdldzn.$dosya.'?nedir=filmler&filmler=http://www.pornhub.com'.urlencode($kategoriler[1][$key]);

			$echo.=playlist($name,$pic,$desc1,$link.'&xml','playlist');

		}

		$echo.=rabbit($desc1,$rabbit);			




	echo $echo;

	}



//********************************************************************************************************************************************************************



	if ($_GET['nedir']=="filmler"){





		$dizianasayfa1=cek($_GET['filmler']);

///////////////////////sayfalama//////////////////////////////////////////////////////////////////////



		//preg_match("/pager(.*?)last/s",$dizianasayfa1,$subpagina);

		preg_match_all("/class=\"page.*?\"><a.*?href=\"(.*?)\">(.*?)<\/a>/",$dizianasayfa1,$pag);



///////////////////////////////////////////////////////////////////////////////////////////////////////



		preg_match("/jc-submenu-wrapper(.*?)videos row-5-thumbs/s", $dizianasayfa1, $subpage);

              preg_match_all("/href=\"(.*?)\".*?title=\"(.*?)\".*?class/",$subpage[1],$filmler);

              preg_match_all("/mediumthumb=\"(.*?)\"/",$subpage[1],$resim);

		preg_match_all("/duration\">(.*?)<\/var>/",$subpage[1],$time);

		preg_match_all("/views\"><var>(.*?)<\/var>/",$subpage[1],$view);

		preg_match_all("/added\">(.*?)/",$subpage[1],$tarih);



		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

			<items>

			<playlist_name><![CDATA[PORNHUB.COM]]></playlist_name>

			"; 



		foreach($filmler[1] as $key => $film){

			$name=strtoupper($filmler[2][$key]);

			$pic=$resim[1][$key];

			$link=$mdldzn.$dosya.'?nedir=filmparcalari&filmparcalari='.$sitealone.$filmler[1][$key].'&before='.urlencode($_GET['filmler']);

			$desc='İzlenme sayısı: '.$view[1][$key].'   Süre: '.$time[1][$key].'   Eklenme: '.$tarih[1][$key];

			$echo.=playlist($name,$pic,$desc,$link.'&xml','playlist');

		}



		foreach($pag[1] as $key => $pa){

			$name='Sayfa-'.$pag[2][$key];

			$pic='http://cdn1.static.pornhub.phncdn.com/images/pornhub_logo.png';

			$link=$mdldzn.$dosya."?nedir=filmler&filmler=http://www.pornhub.com/".urlencode(str_replace('&amp;','&',$pag[1][$key])).'&xml';

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
//echo $dizianasayfa3;
		preg_match("/data-src=\"(.*?)\" poster=\"(.*?)\"/",$dizianasayfa3,$vidlink);

		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

			<items>

			<playlist_name><![CDATA[FİLMİ İZLE]]></playlist_name>

			";



				$name='İZLE';

				$pic=$vidlink[2];

				$link=urldecode($vidlink[1]);

				$echo.=playlist($name,$pic,$desc1,$link,'stream');

		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$_GET['before'].'&xml','playlist');

		$echo.=rabbit($desc1,$rabbit);			



	echo $echo;

}



?>

