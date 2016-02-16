<?php


error_reporting(E_ALL ^ E_NOTICE);

$dosya='filmizlesene.php';

include "../../a_cfg_b/cfg.php"; 

$site="http://www.filmizlesene.com.tr";

$pic='filmizlesene logo adresi';




	if ($_GET['nedir']==""){

	$page=cek($site);
	preg_match("/itle=\"Boxset(.*?)Imdb/s",$page,$subpage);

	preg_match_all("/<li.*?href=\"(.*?)\".*?title=\"(.*?)\">(.*?)<\/a>\s*<ul class=\'children\'>/",$subpage[1],$cats);



	$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

		<items>

		<playlist_name><![CDATA[FILMIZLESENE.COM.TR]]></playlist_name>

		"; 
		$echo.=playlist('YENİ EKLENENLER',$pic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.'&xml','playlist');

			//$echo.=playlist('BOXSET',$pic,$desc,$mdldzn.$dosya."?nedir=kategoriler&kategoriler=kateboxset&xml",'playlist');

			$echo.=playlist('TURLER',$pic,$desc,$mdldzn.$dosya."?nedir=kategoriler&kategoriler=kateturler&xml",'playlist');

			$echo.=playlist('TERCIH',$pic,$desc,$mdldzn.$dosya."?nedir=kategoriler&kategoriler=katetercih&xml",'playlist');

			$echo.=playlist('YERLİ FİLMLER',$pic,$desc,$mdldzn.$dosya."?nedir=kategoriler&kategoriler=kateyerli&xml",'playlist');

			$echo.=playlist('YABANCI FİLMLER',$pic,$desc,$mdldzn.$dosya."?nedir=kategoriler&kategoriler=kateyabanci&xml",'playlist');

			$echo.=playlist('IMDB 7.0+',$pic,$desc,$mdldzn.$dosya."?nedir=filmler&filmler=http://www.filmizlesene.com.tr/tur/imdb-puani-yuksek-filmler&xml",'playlist');



	$echo.=anamenudon($desc1,$otoportal);			



	echo $echo;

}

//********************************************************************************************************************************************************************

	if ($_GET['nedir']=='kategoriler'){

	$page = cek($site);

	if ($_GET['kategoriler']=='kateboxset'){	preg_match("/Boxset\"(.*?)Imdb/s",$page,$subpage);}

	if ($_GET['kategoriler']=='kateturler'){	preg_match("/item-272(.*?)item-80/s",$page,$subpage);}

	if ($_GET['kategoriler']=='katetercih'){	preg_match("/e dublaj(.*?)Türler/s",$page,$subpage);}

	if ($_GET['kategoriler']=='kateyerli'){	preg_match("/item-86\"(.*?)rastgele/s",$page,$subpage);}

	if ($_GET['kategoriler']=='kateyabanci'){preg_match("/item-81\"(.*?)item-85/s",$page,$subpage);}



	preg_match_all("/href=\"(.*?)\".*?title=\"(.*?)\".*?>(.*?)<\/a>/",$subpage[1],$kategoriler);

	$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

		<items>

		<playlist_name><![CDATA[FILMIZLESENE.COM.TR]]></playlist_name>

		"; 

		$pic='filmizlesene ogo adresi';


		foreach ($kategoriler[1] as  $key => $kategori ) {

			$name=str_replace($bunu, $degistir, $kategoriler[3][$key]);

			$link=$mdldzn.$dosya."?nedir=filmler&filmler=".$kategoriler[1][$key].'&xml';

			$echo.=playlist($name,$pic,$kategoriler[3][$key],$link,'playlist');

		}

		$echo.=anamenudon($desc1,$otoportal);			



	echo $echo;

	}

//********************************************************************************************************************************************************************

	if ($_GET['nedir']=="filmler"){

		$dizianasayfa1=cek($_GET['filmler']);

		$ad=substr($_GET['filmler'],30);

///////////////////////sayfalama//////////////////////////////////////////////////////////////////////

		preg_match("/<link rel='prev' href='(.*?)'/",$dizianasayfa1,$prev);

		preg_match("/<link rel='next' href='(.*?)'/",$dizianasayfa1,$next);

///////////////////////////////////////////////////////////////////////////////////////////////////////

		preg_match("/\"sol\"(.*?)sayfalama/s",$dizianasayfa1,$subpage);

		preg_match_all('#<p><img.*?src=\"(.*?)\".*?alt=\"(.*?)\".*?width.*?\/><br \/>(.*?)<\/p>#',$subpage[1],$resiskon);



		preg_match_all("/baslik\"><a.*?href=\"(.*?)\".*?title/",$subpage[1],$link);



		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

			<items>

			<playlist_name><![CDATA[FILMLER]]></playlist_name>

			"; 

		foreach($link[1] as $key => $film){

			$name=str_replace($bunu, $degistir,$resiskon[2][$key]);

			$pic=$resiskon[1][$key];

			$linkas=$mdldzn.$dosya.'?nedir=filmparcalari&filmparcalari='.$link[1][$key].'&before='.$_GET['filmler'].'&xml';

			$echo.=playlist($name,$pic,$resiskon[3][$key],$linkas,'playlist');

		}



			if (strlen($next[1])<>0){

			$name='Sonraki Sayfa';

			$pic=$nextpic;

			$link=$mdldzn.$dosya."?nedir=filmler&filmler=".urlencode($next[1]).'&xml';

			$echo.=playlist($name,$pic,$desc,$link,'playlist');
		}

			if (strlen($prev[1])<>0){

			$name='Önceki Sayfa';

			$pic=$prevpic;

			$link=$mdldzn.$dosya."?nedir=filmler&filmler=".urlencode($prev[1]).'&xml';

			$echo.=playlist($name,$pic,$desc,$link,'playlist');
		}

		

		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=kategoriler'.'&xml','playlist');

		$echo.=anamenudon($desc1,$otoportal);			


echo $echo;

	}

//**********************************************************************************************************************************************************************

if ($_GET['nedir']=="filmparcalari"){

$link=array();

$isim=array();

$page=cek($_GET['filmparcalari']); 

//preg_match("/film-ozeti\">(.*?)<\/div>/s",$page,$ozet); //adres ana sayfa

//preg_match("/imdb\">(.*?)</",$page,$imdb); 

//preg_match("/<iframe src=\"(.*?)\"/",$page,$link1);

//preg_match("/id=\"ikikapa(.*?)alt-ads/s",$page,$parts);
preg_match("/partlari(.*?)class=\"tmz\"/s",$page,$parts);

array_push($link, $_GET['filmparcalari']);

array_push($isim, '1. Kısım');

preg_match_all("/<a.*?href=\"(.*?)\".*?><b>(.*?)<\/b>/",$parts[1],$sonrakiler);

if (strpos('tek part izlemek isteyen',$parts[1])==true){$aa=1;}

//print_r($sonrakiler);

	foreach($sonrakiler[1] as  $key => $a){

		array_push($link, $sonrakiler[1][$key]);

		array_push($isim,strtoupper($sonrakiler[2][$key]));}

		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>

		<items>

		<playlist_name><![CDATA[FİLM LİNKLERİ]]></playlist_name>

		"; 

		$filml=array();

		$pic='vk logo adresi';

		foreach ($link as $kiy => $lynk){

			if($isim[$kiy]=='1'){$lynk=$_GET['filmparcalari'];}

			$page=cek($lynk);

			preg_match("/<iframe.*?src=\"http:\/\/vk(.*?)\" width/",$page,$film);

			if ($film[1]==''){

				$pic='novideo logo adresi';

				$desc1='Bu filmin kullandığınız cihazda oynatılabilen tipi malesef yok...'; }

			if ($aa==1){$desc1='Son Part tek parçadır....';}

			$echo.=playlist($isim[$kiy],$pic,$desc1,str_replace('&#038;','&',str_replace('&amp;','&','http://vk'.$film[1])),'stream');

	}

$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$_GET['before'].'&xml','playlist');

$echo.=anamenudon($desc1,$otoportal);			



echo $echo;

}

?>