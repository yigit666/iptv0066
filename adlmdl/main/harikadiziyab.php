<?php




error_reporting(E_ALL ^ E_NOTICE);

$dosya='harikadiziyab.php';

include "../../a_cfg_b/cfg.php"; 

$site="http://www.harika-dizi.net";

//********************************************************************************************************************************************************************

	if ($_GET['search']<>''){

header("Location: ".$mdldzn.$dosya."?nedir=filmler&filmler=".$site."/?s=".$_GET['search']);

	}

//********************************************************************************************************************************************************************



	if ($_GET['nedir']=="kategoriler"){

	$page = cek($site.'/category/yabanci-diziler');

	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.



//echo $page;

	preg_match("/<div id=\"s(.*?)cleared/s",$page,$lastpages);



//print_r($lastpages);

	preg_match_all("/<a href=(.*?)><div class=\"kucuk-liste\">(.*?)<\/div>/",$lastpages[1],$kategoriler);

	$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

		<items>

		<playlist_name><![CDATA[YABANCI DİZİ]]></playlist_name>

		"; 

		$pic='http://www.harikadizi.net/wp-content/themes/vidiiv6/images/logo.png';

		if (($_SERVER['HTTP_USER_AGENT']=='') or ($_SERVER['HTTP_USER_AGENT']=='Mozilla/5.0 seyirTURKVOD 0.1')){

			$echo.=playlist('ARAMA',$pic,'LÜTFEN BİR SONRAKİ EKRANDA KLAVYEYİ KULLANABİLMEK İÇİN STOP TUŞUNA BASINIZ.','seyirTURKModul@webot@'.substr($mdldzn,7).$dosya."?nedir=filmler&filmler=".$site.'/?s=@ARAMA','playlist');

		} else {$echo.=smartarama('SMART TV ARAMA',$pic,'BU ARAMA SADECE SMART TVLER İÇİNDİR.',$mdldzn.$dosya,'playlist');}
		$echo.=playlist('YENİ EKLENENLER',$pic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.urlencode($site.'/category/yabanci-diziler').'&xml','playlist');


		foreach ($kategoriler[1] as  $key => $kategori ) {



			if (strpos($kategoriler[2][$key],'script') == false) {

				$name=str_replace($bunu,$degistir,$kategoriler[2][$key]);

				$link=$mdldzn.$dosya."?nedir=filmler&filmler=".$kategoriler[1][$key];

				$echo.=playlist($name,$pic,$desc1,$link.'&xml','playlist');

				}

			}

		$echo.=anamenudon($desc1,$otoportal);			





	echo $echo;

	}



//********************************************************************************************************************************************************************



	if ($_GET['nedir']=="filmler"){

		$dizianasayfa1=cek($_GET['filmler']);

		preg_match("/previouspostslink\">«<\/a><a.*?href=\"([^\"]*)\"/",$dizianasayfa1,$prev);

		preg_match("/href=\"([^\"]*)\" class=\"nextpostslink/",$dizianasayfa1,$next);

		preg_match_all("/<a.*?href=\"(.*?)\" rel=\"bookmark\" title=\"(.*?)\">/",$dizianasayfa1,$filmler);

		preg_match_all("/<div style=\"background-image:url\((.*?)\)/",$dizianasayfa1,$resim);



		//echo $next[1];

		//echo $prev[1];

		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

			<items>

			<playlist_name><![CDATA[".strtoupper(substr($_GET['filmler'],-16,-1))."]]></playlist_name>

			"; 

		foreach($filmler[1] as $key => $film){

			$name=str_replace($bunu,$degistir,$filmler[2][$key]);

			$pic=$resim[1][$key];

			$link=$mdldzn.$dosya.'?nedir=filmparcalari&filmparcalari='.$filmler[1][$key].'&before='.$_GET['filmler'];

			$echo.=playlist($name,$pic,$desc1,$link.'&xml','playlist');

		}

		if($next[1]<>""){

			$echo.=playlist('İLERİ',$nextpic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$next[1].'&xml','playlist');

		}

		if($prev[1]<>""){

			$prew=$prev[1];

			if (substr($prev[1],-1)=='/'){$prew=substr($prev[1],0,-1);}

			$echo.=playlist('GERİ',$prevpic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$prew.'&xml','playlist');

		}



		

		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=kategoriler'.'&xml','playlist');

		$echo.=anamenudon($desc1,$otoportal);			





echo $echo;

	}



//**********************************************************************************************************************************************************************

	if ($_GET['nedir']=="filmparcalari"){

		$dizianasayfa3=cek(izle($_GET['filmparcalari']));

		preg_match("/iframe.*?src=\"http:\/\/vk.com(.*?)\".*?<\/iframe>/",$dizianasayfa3,$vk); 

		preg_match("/iframe.*?src=\"http:\/\/www\.youtube\.com\/embed\/(.*?)\".*?<\/iframe>/",$dizianasayfa3,$yt); 

		preg_match("/iframe.*?src=\"http:\/\/www\.harikadizi\.net(.*?)\".*?<\/iframe>/",$dizianasayfa3,$diger); 
		preg_match("/<iframe.*?src=\"http:\/\/api\.video\.mail\.ru([^\"]*)\"/",$dizianasayfa3,$mailru); 
		if ($mailru[1]<>''){
					$t='http://api.video.mail.ru'.str_replace($vkbunu,$vkdegistir,$mailru[1]);
					$name='MAIL.RU TEK PARÇA';
					$pic='mailru.png adresi';
			}
			if ($diger[1]<>''){

				$dizianasayfa5=cek('http://www.harikadizi.net'.$diger[1]);

				preg_match("/%3D(.*?)&/",$dizianasayfa5,$other); 

				$yt=$other;

			}



		if (($vk[1]<>'')) {

					$t='http://vk.com'.str_replace($vkbunu,$vkdegistir,$vk[1]);

					$name='VK TEK PARÇA';

					$pic=' adres vk.png';



		}



		

		if ($yt[1]<>''){

					$t='http://www.youtube.com/watch?v='.$yt[1];

					$name='YOUTUBE TEK PARÇA';

					$pic='adres youtube1.jpg';



		}







		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

			<items>

			<playlist_name><![CDATA[İZLE]]></playlist_name>

			"; 





			$echo.=playlist($name,$pic,$desc1,$t,'stream');



		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$_GET['before'].'&xml','playlist');

		$echo.=anamenudon($desc1,$otoportal);			


	echo $echo;

}

?>

