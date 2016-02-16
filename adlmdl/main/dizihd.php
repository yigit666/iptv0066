<?php





error_reporting(E_ALL ^ E_NOTICE);

$dosya='dizihd.php';

include "../../a_cfg_b/cfg.php"; 

$site="http://dizihdtv.net";

//********************************************************************************************************************************************************************

	if ($_GET['search']<>''){

header("Location: ".$server.$dosya."?nedir=filmler&filmler=".$site."/?s=".$_GET['search']);

	}

//********************************************************************************************************************************************************************





	if ($_GET['nedir']=="kategoriler"){

	$kates=array('YERLI DIZILER','YABANCI DIZILER','TV SHOW');

	$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

		<items>

		<playlist_name><![CDATA[DİZİHDTV.COM]]></playlist_name>

		"; 

		$kkk=$mdldzn.$dosya.'?nedir=filmler&filmler='.$site;

		if (($_SERVER['HTTP_USER_AGENT']=='') or ($_SERVER['HTTP_USER_AGENT']=='Mozilla/5.0 seyirTURKVOD 0.1')){

			$echo.=playlist('ARAMA',$pic,'LÜTFEN BİR SONRAKİ EKRANDA KLAVYEYİ KULLANABİLMEK İÇİN STOP TUŞUNA BASINIZ.','seyirTURKModul@webot@'.substr($server,7).$dosya.'?nedir=filmler&filmler='.$site.'/?s=@ARAMA','playlist');

		} else {$echo.=smartarama('SMART TV ARAMA',$pic,'BU ARAMA SADECE SMART TVLER İÇİNDİR.',$server.$dosya,'playlist');}

		$echo.=playlist('YENİ EKLENENLER','',$desc1,$kkk.'&xml','playlist');

		foreach ($kates as  $kate ) {

			$name=$kate;

			$pic='ADRES dizihd.png';

			$link=$mdldzn.$dosya."?nedir=altkategoriler&altkategoriler=".$kate;

			$echo.=playlist($name,$pic,$desc1,$link.'&xml','playlist');

			}

		$echo.=anamenudon($desc1,$otoportal);			




	echo $echo;

	}

//********************************************************************************************************************************************************************



	if ($_GET['nedir']=="altkategoriler"){



		$page = cek($site);



		if ($_GET['altkategoriler']=='YERLI DIZILER'){

			preg_match("/id=\"panel-1\">(.*?)id=\"panel-2\">/s",$page,$yerlikaba);

#echo $yerlikaba[1];

			preg_match_all("/<li class=.*?><a href=\"(.*?)\" title=\"(.*?)\">.*?<\/a>/",$yerlikaba[1],$kategoriler);

			$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

				<items>

				<playlist_name><![CDATA[YERLI DIZILER]]></playlist_name>

				"; 



			foreach ($kategoriler[1] as  $key => $kategori ) {

			$name=str_replace($bunu, $degistir, $kategoriler[2][$key]);

			$pic='ADRES dizihd.png';

			$link=$mdldzn.$dosya."?nedir=filmler&filmler=".$kategoriler[1][$key];

				if (strpos( $kategoriler[2][$key],'kategori') === false){

					$echo.=playlist($name,$pic,$desc1,$link.'&xml','playlist');

				}

			}



		}

		

		if ($_GET['altkategoriler']=='YABANCI DIZILER'){

			preg_match("/id=\"panel-2\">(.*?)id=\"panel-3\">/s",$page,$kaba);

			preg_match_all("/<li.*?class=.*?><a.*?href=\"(.*?)\".*?title=\"(.*?)\">.*?<\/a>/",$kaba[1],$kategoriler);

			$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

				<items>

				<playlist_name><![CDATA[YABANCI DIZILER]]></playlist_name>

				"; 



			foreach ($kategoriler[1] as  $key => $kategori ) {

			$name=str_replace($bunu, $degistir, $kategoriler[2][$key]);

			$pic='';

			$link=$mdldzn.$dosya."?nedir=filmler&filmler=".$kategoriler[1][$key];

				if (strpos( $kategoriler[2][$key],'kategori') === false){

					$echo.=playlist($name,$pic,$desc1,$link.'&xml','playlist');

				}

			}



		}

		

		if ($_GET['altkategoriler']=='TV SHOW'){

			preg_match("/id=\"panel-3\">(.*?)id=\"panel-4\">/s",$page,$kaba);

			preg_match_all("/<li class=.*?><a href=\"(.*?)\" title=\"(.*?)\">.*?<\/a>/",$kaba[1],$kategoriler);

			$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
				<items>

				<playlist_name><![CDATA[YABANCI DIZILER]]></playlist_name>

				"; 



			foreach ($kategoriler[1] as  $key => $kategori ) {

			$name=str_replace($bunu, $degistir, $kategoriler[2][$key]);

			$pic='';

			$link=$mdldzn.$dosya."?nedir=filmler&filmler=".$kategoriler[1][$key];

				if (strpos( $kategoriler[2][$key],'kategori') === false){

					$echo.=playlist($name,$pic,$desc1,$link.'&xml','playlist');

				}

			}



		}





		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=kategoriler'.'&xml','playlist');

		$echo.=anamenudon($desc1,$otoportal);			




echo $echo;

	}





//********************************************************************************************************************************************************************



	if ($_GET['nedir']=="filmler"){

		$dizianasayfa1=cek($_GET['filmler']);

		$ad=str_replace('page','sayfa',str_replace('-',' ',substr($_GET['filmler'],30,-5)));







		preg_match("/&#8201;<\/span><a href=\"([^\"]*)\"/",$dizianasayfa1,$prev);          

		preg_match("/span class=\"current.*?<a href=\"([^\"]*)\"/",$dizianasayfa1,$next);

		preg_match_all("/<a.*?href=\".*?\">.*?<img.*?src=\"(.*?)\".*?><\/a>\s*<h2><a.*?href=\"(.*?)\">(.*?)<\/a><\/h2>/",$dizianasayfa1,$filmler);

		//echo $next[1];

		//echo $prev[1];

		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

			<items>

			<playlist_name><![CDATA[".strtoupper(str_replace($bunu, $degistir, $ad))."]]></playlist_name>

			"; 

		foreach($filmler[1] as $key => $film){

			$name=str_replace($bunu, $degistir,$filmler[3][$key]);

			$pic=$filmler[1][$key];

			$link=$mdldzn.$dosya.'?nedir=filmparcalari&filmparcalari='.$filmler[2][$key].'&before='.$_GET['filmler'];

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

			<playlist_name><![CDATA[".substr(strtoupper(str_replace($bunu,$degistir,$_GET['filmparcalari'])),22,-7)."]]></playlist_name>

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

			preg_match("/<iframe.*?src=\".*?youtube\.com\/embed\/(.*?)\"/",$page,$vidlinkyt);

			preg_match("/<iframe src=\"http:\/\/vk\.com(.*?)\"/",$page,$vidlinkvk);

			if ($vidlinkyt[1]<>''){ 

				$name= $onek.($key+1);

				$pic='http://weblopedi.net/wp-content/uploads/youtube1.jpg';

				$echo.=playlist($name,$pic,$desc,'http://www.youtube.com/watch?v='.$vidlinkyt[1],'stream');

			}

			if ($vidlinkvk[1]<>''){

				$name= $onek.($key+1);

				$pic='ADRES vk.png';

				$echo.=playlist($name,$pic,$desc,str_replace($vkbunu,$vkdegistir,('http://vk.com'.$vidlinkvk[1])),'stream');

			}





		}







		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$_GET['before'].'&xml','playlist');

		$echo.=anamenudon($desc1,$otoportal);			





	echo $echo;

}

?>

