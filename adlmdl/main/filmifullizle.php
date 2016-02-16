<?php


error_reporting(E_ALL ^ E_NOTICE);

$dosya='filmifullizle.php';

include "../../a_cfg_b/cfg.php"; 

$site="http://www.filmifullizle.com";


//********************************************************************************************************************************************************************



	if ($_GET['nedir']=="kategoriler"){

	$page = cek($site);

	preg_match_all("/<li.*?class=.*?><a.*?href=\"(.*?)\".*?title=.*?>(.*?)<\/a>/",$page,$kategoriler);

	$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

		<items>

		<playlist_name><![CDATA[FİLMİFULLİZLE.COM]]></playlist_name>

		"; 

		$pic='buraya logo resmini koy';

		$echo.=playlist('YENİ EKLENENLER',$pic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.'&xml','playlist');

		foreach ($kategoriler[1] as  $key => $kategori ) {



			$name=str_replace($bunu, $degistir, $kategoriler[2][$key]);

			$link=$mdldzn.$dosya."?nedir=filmler&filmler=".$kategoriler[1][$key];

				if (strpos( $kategoriler[2][$key],'kategori') === false){

					$echo.=playlist($name,$pic,$desc1,$link.'&xml','playlist');

				}

			}

		$echo.=anamenudon($desc1,$otoportal);			


	echo $echo;

	}



//********************************************************************************************************************************************************************



	if ($_GET['nedir']=="filmler"){

		$dizianasayfa1=cek($_GET['filmler']);

		$ad=str_replace('page','sayfa',str_replace('/',' ',substr($_GET['filmler'],46)));

		preg_match("/<li><a href=\"([^\"]*)\">&lt;/",$dizianasayfa1,$prev);          

		preg_match("/<li><a href=\"([^\"]*)\">&gt;<\/a><\/li>/",$dizianasayfa1,$next);

		preg_match_all("/<a href=\"(.*?)\"><img src=\"(.*?)\" alt=\"(.*?)\" class=\"captify\" \/><\/a>/",$dizianasayfa1,$filmler);

		//echo $next[1];

		//echo $prev[1];

		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

			<items>

			<playlist_name><![CDATA[".strtoupper(str_replace($bunu, $degistir, $ad))."]]></playlist_name>

			"; 

		foreach($filmler[1] as $key => $film){

			$name=str_replace($bunu, $degistir,$filmler[3][$key]);

			$pic=$filmler[2][$key];

			$link=$mdldzn.$dosya.'?nedir=filmparcalari&filmparcalari='.$filmler[1][$key].'&before='.$_GET['filmler'];

			$echo.=playlist($name,$pic,$desc1,$link.'&xml','playlist');

		}

		if($next[1]<>""){

			$echo.=playlist('İLERİ',$nextpic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$next[1].'&xml','playlist');

		}

		if($prev[1]<>""){

			$echo.=playlist('GERİ',$prevpic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$prev[1].'&xml','playlist');

		}



		

		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=kategoriler'.'&xml','playlist');

		$echo.=anamenudon($desc1,$otoportal);			



echo $echo;

	}



//**********************************************************************************************************************************************************************

	if ($_GET['nedir']=="filmparcalari"){

			$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

			<items>

			<playlist_name><![CDATA[".strtoupper($ad[1])."]]></playlist_name>

			"; 	

		$dizianasayfa3=cek($_GET['filmparcalari']);

		preg_match_all("/<a href=\"(.*?)\">B.*?\d+<\/a>/",$dizianasayfa3,$parts); 

		$vidpages[].=$_GET['filmparcalari'];

		preg_match("/<div class=\"solmeta\".*?;(.*?)<br \/>/s",$dizianasayfa3,$konu);

		$desc=preg_replace('/[\s]+/',' ',$konu[1]);

		$onek='TEK PARÇA';

			if ($parts[1]<>'') {

				foreach($parts[1] as $key => $part) {

					$onek='PARÇA ';

					$vidpages[].=$part;

				}

			}





		foreach($vidpages as $key=> $vidpage){

			$page=cek($vidpage);

			preg_match("/<iframe.*?src=\"http:\/\/www\.youtube\.com\/embed\/(.*?)\"/",$page,$vidlinkyt);

			preg_match_all("/<iframe src=\"http:\/\/vk\.com(.*?)\"/",$page,$vidlinkvk);

			if ($vidlinkyt[1]<>''){ 

				$name= $onek.($key+1);

				$pic='youtube resminin adresini';

				$echo.=playlist($name,$pic,$desc,'http://www.youtube.com/watch?v='.$vidlinkyt[1],'stream');

			}

			if ($vidlinkvk[1]<>''){

				

				$name= $onek.($key+1);

				$pic='vk logosunun adresi';

				foreach ($vidlinkvk[1] as $key => $vk){

					$echo.=playlist($name,$pic,$desc,str_replace($vkbunu,$vkdegistir,('http://vk.com'.$vidlinkvk[1][$key])),'stream');

				}

			}





		}







		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$_GET['before'].'&xml','playlist');

		$echo.=anamenudon($desc1,$otoportal);			





	echo $echo;

}

?>

