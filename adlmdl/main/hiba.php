<?php

error_reporting(E_ALL ^ E_NOTICE);
$dosya='hiba.php';
include "../../a_cfg_b/cfg.php"; 
$site="http://www.hibasex.com/";
//********************************************************************************************************************************************************************

	if ($_GET['nedir']=="kategoriler"){
	$page = cek($site."categories");
	//preg_match("/id=\"content-area(.*?)padb8/s",$page,$subpage);
	preg_match_all('/href=\"(.*?)\">Videos<\/a>/',$page,$kategoriler);
	$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
		<items>
		<playlist_name><![CDATA[HİBASEX.COM]]></playlist_name>
		"; 
		$pic='adres hibasex.png';
		$echo.=playlist('YENİ EKLENENLER',$pic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$site.'&xml','playlist');

		foreach ($kategoriler[1] as  $key => $kategori ) {
			if ($key<>0){
			$name=substr($kategoriler[1][$key],32);
			$link=$mdldzn.$dosya."?nedir=filmler&filmler=".$kategoriler[1][$key].'&xml';
			$echo.=playlist($name,$pic,$desc1,$link,'playlist');
			}
			}
		$echo.=rabbit($desc1,$rabbit);			


	echo $echo;
	}

//********************************************************************************************************************************************************************

	if ($_GET['nedir']=="filmler"){
		$dizianasayfa1=cek($_GET['filmler']);

///////////////////////sayfalama//////////////////////////////////////////////////////////////////////

		preg_match("/class=\"paging\"(.*?)iframe/s",$dizianasayfa1,$subpagina);
		preg_match_all("/class=\"normal\".*?href=\'(.*?)\'>(.*?)<\/a>/",$subpagina[1],$pag);

///////////////////////////////////////////////////////////////////////////////////////////////////////


		$ad=substr($_GET['filmler'],32);
		preg_match_all("/href=\'(.*?)\'><img.*?src=\'(.*?)\'.*?alt=\'(.*?)\'/",$dizianasayfa1,$filmler);
		//echo $next[1];
		//echo $prev[1];
		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
			<items>
			<playlist_name><![CDATA[".strtoupper(str_replace($bunu, $degistir, $ad))."]]></playlist_name>
			"; 
		foreach($filmler[1] as $key => $film){
			$name=$filmler[3][$key];
			$pic=$filmler[2][$key];
			$link=$mdldzn.$dosya.'?nedir=filmparcalari&filmparcalari='.$filmler[1][$key].'&before='.$_GET['filmler'].'&xml';
			$echo.=playlist($name,$pic,$desc,$link,'playlist');
		}
		
		foreach($pag[1] as $key => $pa){
			$name='Sayfa-'.$pag[2][$key];
			$pic='adres icon.png';
			$link=$mdldzn.$dosya."?nedir=filmler&filmler=".$pag[1][$key].'&xml';
			$echo.=playlist($name,$pic,$desc,$link,'playlist');
		}

		
		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=kategoriler'.'&xml','playlist');
		$echo.=rabbit($desc1,$rabbit);			


echo $echo;
	}

//**********************************************************************************************************************************************************************
	if ($_GET['nedir']=="filmparcalari"){
		$dizianasayfa3=cek($_GET['filmparcalari']);
		preg_match("/flashvars\",\"file=(.*?)&/",$dizianasayfa3,$link); 
		if ($link[1]==''){
					preg_match("/file:.*?\"(.*?)\"/",$dizianasayfa3,$link); 
		}
		//$link=base64_decode($vkmulti[1]);
		$pic='adres flv.png';





		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
			<items>
			<playlist_name><![CDATA[".strtoupper(substr($_GET['filmler'],22,-1))."]]></playlist_name>
			"; 
			$name=$key.'Video İzle';
			$echo.=playlist($name,$pic,$desc1,$link[1],'stream');
		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$_GET['before'].'&xml','playlist');
		$echo.=rabbit($desc1,$rabbit);			

	echo $echo;
}
?>
