<?php

error_reporting(E_ALL ^ E_NOTICE);

$dosya='hitizle.php';

include "../../a_cfg_b/cfg.php"; 

$site="http://www.fullhdonlinefilmizle.net/";







//********************************************************************************************************************************************************************



	if ($_GET['search']<>''){

header("Location: ".$mdldzn.$dosya."?nedir=filmler&filmler=".$site."?s=".$_GET['search']);	}



//**************************************************

	if ($_GET['nedir']=="kategoriler"){

	$page = cek($site);

	preg_match("/<div class=\"sagBlok\">(.*?)sagBlok/s",$page,$subpage);

	preg_match_all("/<li id=.*? class=.*?><a href=\"(.*?)\">(.*?)<\/a>/",$subpage[1],$kategoriler);





	$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

		<items>

		<playlist_name><![CDATA[FULLHDONLINEFILMIZLE.COM]]></playlist_name>

		"; 

		$pic='http://www.fullhdonlinefilmizle.com/wp-content/themes/oz-movie-v3/img/siyah/logo.png';



		if (($_SERVER['HTTP_USER_AGENT']=='') or ($_SERVER['HTTP_USER_AGENT']=='Mozilla/5.0 seyirTURKVOD 0.1')){

			$echo.=playlist('ARAMA',$pic,'LÜTFEN BİR SONRAKİ EKRANDA KLAVYEYİ KULLANABİLMEK İÇİN STOP TUŞUNA BASINIZ.','seyirTURKModul@webot@'.substr($mdldzn,7).$dosya."?nedir=filmler&filmler=".$site.'?s=@ARAMA','playlist');

		} else {$echo.=smartarama('SMART TV ARAMA',$pic,'BU ARAMA SADECE SMART TVLER İÇİNDİR.',$mdldzn.$dosya,'playlist');}

		$echo.=playlist('YENİ EKLENENLER',$pic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.'&xml','playlist');



			foreach ($kategoriler[1] as  $key => $kategori ) {

			$name=str_replace($bunu, $degistir, $kategoriler[2][$key]);

			$link=$mdldzn.$dosya."?nedir=filmler&filmler=".$kategoriler[1][$key];

			$echo.=playlist($name,$pic,$desc1,$link.'&xml','playlist');

			







			}

		$echo.=anamenudon($desc1,$otoportal);			



	echo $echo;

	}



//********************************************************************************************************************************************************************



	if ($_GET['nedir']=="filmler"){

		$dizianasayfa1=cek($_GET['filmler']);

		$ad=substr($_GET['filmler'],32);

		preg_match('/<div class="solBlok.*?">(.*?)<div id="sayfala" class="clear">/s',$dizianasayfa1,$subpage);

		preg_match("/href=\'([^\']*)\' class=\'previouspostslink\'>/",$dizianasayfa1,$prev);

		preg_match("/href=\'([^\']*)\' class=\'nextpostslink\'>/",$dizianasayfa1,$next);

		preg_match_all("/<a href=\"(.*?)\".*?>\s*<img.*?src=\"(.*?)\" class=\"attachment.*?\" alt=\"(.*?)\"/",$subpage[1],$filmler);

	

                 



		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

			<items>

			<playlist_name><![CDATA[".strtoupper(str_replace($bunu, $degistir, $ad))."]]></playlist_name>

			"; 

		foreach($filmler[1] as $key => $film){

			$name=str_replace($bunu, $degistir,$filmler[3][$key]);

			$pic=$filmler[2][$key];

			$link=$mdldzn.$dosya.'?nedir=filmparcalari&filmparcalari='.$filmler[1][$key].'&before='.$_GET['filmler'];

			//$desc1="strtoupper(str_replace($bunu, $degistir,$filmler[3][$key]))";

			$echo.=playlist($name,$pic,$desc1,$link.'&xml','playlist');

		}

		if($next[1]<>""){

			$echo.=playlist('İLERİ',$nextpic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$next[1].'&xml','playlist');

		}

		if($prev[1]<>""){

			$prew=$prev[1];

			$echo.=playlist('GERİ',$prevpic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$prev[1].'&xml','playlist');

		}



		

		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=kategoriler'.'&xml','playlist');

		$echo.=anamenudon($desc1,$otoportal);			



echo $echo;

	}



//**********************************************************************************************************************************************************************

	if ($_GET['nedir']=="filmparcalari"){

		$dizianasayfa3=cek($_GET['filmparcalari']);

		preg_match_all("/iframe.*?src=\"http:\/\/vk\.com\/(.*?)\"/",$dizianasayfa3,$vk);

		preg_match_all("/iframe.*?src=\"http:\/\/api\.video\.mail\.ru(.*?)\"/",$dizianasayfa3,$mailru);



		preg_match('/<div id="filmHakkinda"><p>(.*?)<\/a>/',$dizianasayfa3,$des);

		preg_match('/<img.*?src="(.*?)".*?class="attachment-midi wp-post-image/', $dizianasayfa3, $resim);

		preg_match('/IMDB Puanı:<\/span>.*?rel="tag">(.*?)<\/a/', $dizianasayfa3, $imdb);

		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

			<items>

			<playlist_name><![CDATA[FİLMİ İZLE]]></playlist_name>

			";

			foreach($vk[1] as $key => $film){

				$name='VK linki';

				$pic='adres vk.png';

				$link='http://vk.com/'.str_replace($vkbunu,$vkdegistir,$vk[1][$key]);

				$desc=strtoupper(str_replace($bunu, $degistir,$des[1]));

				$echo.=playlist($name,$resim[1],'(imdb:'.$imdb[1].') '.$des[1],$link,'stream');

		}



			foreach($mailru[1] as $key => $film){

				$name='MAIL.RU linki';

				$pic='adres vk.png';

				$link='<http://api.video.mail.ru'.str_replace($vkbunu,$vkdegistir,$mailru[1][$key]);

				$desc=strtoupper(str_replace($bunu, $degistir,$des[1]));

				$echo.=playlist($name,$resim[1],'(imdb:'.$imdb[1].') '.$des[1],$link,'stream');

		}



		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$_GET['before'].'&xml','playlist');

		$echo.=anamenudon($desc1,$otoportal);			


	echo $echo;

}

?>

