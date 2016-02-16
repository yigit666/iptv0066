<?php


error_reporting(E_ALL ^ E_NOTICE);

$dosya='trdizi.php';

include "../../a_cfg_b/cfg.php"; 

$site="http://www.trdizi.tv";

//********************************************************************************************************************************************************************

	if ($_GET['search']<>''){

header("Location: ".$mdldzn.$dosya."?nedir=filmler&filmler=".$site."/?s=".$_GET['search']);

	}

//********************************************************************************************************************************************************************



	if ($_GET['nedir']=="kategoriler"){

	$page = cek($site);

	preg_match_all("/ <li class=\"item \d+\"><a href=\"(.*?)\" title=\"\">(.*?)<\/a><\/li>/",$page,$kategoriler);

	$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

		<items>

		<playlist_name><![CDATA[TRDIZI]]></playlist_name>

		"; 

		$pic='logo ADRESİ';

		if (($_SERVER['HTTP_USER_AGENT']=='') or ($_SERVER['HTTP_USER_AGENT']=='Mozilla/5.0 seyirTURKVOD 0.1')){

			$echo.=playlist('ARAMA',$pic,'LÜTFEN BİR SONRAKİ EKRANDA KLAVYEYİ KULLANABİLMEK İÇİN STOP TUŞUNA BASINIZ.','seyirTURKModul@webot@'.substr($mdldzn,7).$dosya."?nedir=filmler&filmler=".$site.'/?s=@ARAMA','playlist');

		} else {$echo.=smartarama('SMART TV ARAMA',$pic,'BU ARAMA SADECE SMART TVLER İÇİNDİR.',$mdldzn.$dosya,'playlist');}

		$echo.=playlist('YENİ EKLENENLER',$pic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.'&xml','playlist');

		foreach ($kategoriler[1] as  $key => $kategori ) {

			$name=$kategoriler[2][$key];

			$link=$mdldzn.$dosya."?nedir=filmler&filmler=".$kategoriler[1][$key];

			$echo.=playlist($name,$pic,$desc1,$link.'&xml','playlist');

			}

		$echo.=anamenudon($desc1,$otoportal);			





	echo $echo;

	}



//********************************************************************************************************************************************************************



	if ($_GET['nedir']=="filmler"){

		$dizianasayfa1=cek($_GET['filmler']);

		preg_match("/<span class='leftok'><a href=\"(.*?)\" class=\"previouspostslink\"><\/a><\/span>/",$dizianasayfa1,$prev);

		preg_match("/<span class='rightok'><a href=\"(.*?)\" class=\"nextpostslink\"><\/a><\/span><\/div>/",$dizianasayfa1,$next);

		preg_match_all("/<div class=\"thumb\"><a href=\"(.*?)\"><img src=\"(.*?)\" alt=\"(.*?)\" width.*?<\/div>/",$dizianasayfa1,$filmler);



		//echo $next[1];

		//echo $prev[1];

		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

			<items>

			<playlist_name><![CDATA[".strtoupper(substr($_GET['filmler'],22,-1))."]]></playlist_name>

			"; 

		foreach($filmler[1] as $key => $film){

			$name=strtoupper($filmler[3][$key]);

			$pic=$filmler[2][$key];

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

		$dizianasayfa3=cek($_GET['filmparcalari']);

		preg_match("/file',encodeURIComponent\('(.*?)'\)/",$dizianasayfa3,$first); 

		if (($first[1]<>'')) {

					$t[]=$first[1];

					preg_match_all("/\">Part.*?<\/a><a href=\"(.*?)\" rel=\"nofollow/",$dizianasayfa3,$parts);

					$name1[]='Trdizi Parça 1';

					$pic[].='ADRES 1.png';

					foreach ($parts[1] as  $key => $part ) {

						$name1[]='Trdizi Parça '.($key+2);

						$sayfa=cek($part);

						preg_match("/file',encodeURIComponent\('(.*?)'\)/",$sayfa,$other); 

						$t[].=$other[1];

						$pic[].='ADRES'.($key+2).'.png';

					}

		}



		preg_match("/embed src=\"http:\/\/www\.youtube\.com\/v\/(.*?)\?/",$dizianasayfa3,$youtube); 

		

		if ($youtube[1]<>''){

					$t[]='http://www.youtube.com/watch?v='.$youtube[1];

					preg_match_all("/\">Part.*?<\/a><a href=\"(.*?)\" rel=\"nofollow/",$dizianasayfa3,$parts);

					$name1[]='Youtube Tek Parça';

					$pic[].='http://weblopedi.net/wp-content/uploads/youtube1.jpg';



					foreach ($parts[1] as  $key => $part ) {

						$name1[]='VK Tek Parça';

						$sayfa=cek($part);

						preg_match("/<iframe src=\"(.*?)\" /",$sayfa,$other); 

						$t[].=$other[1];

						$pic[].='http://www.androidandmobile.com/wp-content/uploads/2013/01/vk.jpg';



					}

		}



		preg_match("/<iframe src=\"http:\/\/vk.com(.*?)\" /",$dizianasayfa3,$vk); 

		if ($vk[1]<>''){

					$t[]='http://vk.com'.$vk[1];

					$name1[]='VK Tek Parça';

					$pic[].='ADRES vk.png';

		}







		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

			<items>

			<playlist_name><![CDATA[".strtoupper(substr($_GET['filmler'],22,-1))."]]></playlist_name>

			"; 



		foreach ($t as  $key => $firs ) {

			$name=$name1[$key];

			$echo.=playlist($name,$pic[$key],$desc1,$firs,'stream');

			}

		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$_GET['before'].'&xml','playlist');

		$echo.=anamenudon($desc1,$otoportal);			


	echo $echo;

}

?>

