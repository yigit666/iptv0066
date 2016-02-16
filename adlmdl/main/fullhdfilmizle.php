<?php
error_reporting(E_ALL ^ E_NOTICE);
$dosya='fullhdfilmizle.php';
include "../../a_cfg_b/cfg.php"; 
$site="http://www.fullhdfilmizle.org/";
$pic='fullhdfilmizle.png adresi';
//********************************************************************************************************************************************************************
	if ($_GET['search']<>''){
header("Location: ".$mdldzn.$dosya."?nedir=filmler&filmler=".$site."?s=".$_GET['search'].'&h=m6luoaeebktbvvu5qtd0gdqj22');
	}
//********************************************************************************************************************************************************************
	if ($_GET['nedir']=="kategoriler"){
	$page = cek($site);
	preg_match("/Film Kategorileri(.*?)Beğenilen Filmler/s",$page,$subpage);
	preg_match_all("/href=\"(.*?)\" title=.*?>(.*?)</",$subpage[1],$kategoriler);
	preg_match("/filmrobot-form(.*?)sidebar-begenilen-icon/s",$page,$subpage2);
	preg_match_all("/<li><a href=\"(.*?)\".*?>(.*?)<\/a>/",$subpage2[1],$begeni);
	$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
		<items>
		<playlist_name><![CDATA[FULLHDFILMIZLE.ORG]]></playlist_name>
		"; 
		if (($_SERVER['HTTP_USER_AGENT']=='') or ($_SERVER['HTTP_USER_AGENT']=='Mozilla/5.0 seyirTURKVOD 0.1')){
			$echo.=playlist('ARAMA',$pic,'ARAMA FONKSYONU SADECE ENIGMA2 CIHAZLARDA ÇALIŞIR','seyirTURKModul@webot@'.substr($mdldzn,7).$dosya.'?nedir=filmler&filmler='.$site.'?s=@ARAMA','playlist');
			} else {$echo.=smartarama('SMART TV ARAMA',$pic,'BU ARAMA SADECE SMART TVLER İÇİNDİR.',$mdldzn.$dosya,'playlist');}
		foreach ($begeni[1] as  $key => $begen ) {
			$name=str_replace($bunu, $degistir, $begeni[2][$key]);
			$link=$mdldzn.$dosya."?nedir=filmler&filmler=".$begeni[1][$key].'&xml';
			$echo.=playlist($name,$pic,$desc1,$link,'playlist');
			}
		foreach ($kategoriler[1] as  $key => $kategori ) {
			$name=str_replace($bunu, $degistir, $kategoriler[2][$key]);
			$link=$mdldzn.$dosya."?nedir=filmler&filmler=".$kategoriler[1][$key].'&xml';
			$echo.=playlist($name,$pic,$desc1,$link,'playlist');
		}
		$echo.=anamenudon($desc1,$otoportal);			
	echo $echo;
	}
//********************************************************************************************************************************************************************
	if ($_GET['nedir']=="filmler"){
		$spage =str_replace('/yeniler','',$_GET['filmler']);
		$dizianasayfa1=cek($spage);
		$ad=substr($_GET['filmler'],30);
///////////////////////sayfalama//////////////////////////////////////////////////////////////////////
		preg_match("/sayfalama(.*?)clear15/s",$dizianasayfa1,$subpagina);
		preg_match_all("/<li class=\'sayfala\'><a href=\'(.*?)\'>(.*?)<\/a>/",$subpagina[1],$pag);
///////////////////////////////////////////////////////////////////////////////////////////////////////
		preg_match("/index-orta(.*?)sayfalama/s",$dizianasayfa1,$subpage);
		preg_match_all("/src=\"(.*?)\" alt=\"(.*?)\"/",$subpage[1],$resis);
		preg_match_all("/<a href=\"(.*?)\" title(.*?)class/",$subpage[1],$url);
//print_r($url);
		//echo $next[1];
		//echo $prev[1];
		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
			<items>
			<playlist_name><![CDATA[".strtoupper(str_replace($bunu, $degistir, $ad))."]]></playlist_name>
			"; 
		foreach($resis[1] as $key => $film){
			$name=str_replace($bunu, $degistir,$resis[2][$key]);
			$pic=$resis[1][$key];
			$link=$mdldzn.$dosya.'?nedir=filmparcalari&filmparcalari='.$url[1][$key].'&before='.$_GET['filmler'].'&xml';
			$echo.=playlist($name,$pic,$desc1,$link,'playlist');
		}
		foreach($pag[1] as $key => $pa){
			$name='Sayfa : '.$pag[2][$key];
			$link=$mdldzn.$dosya."?nedir=filmler&filmler=".urlencode($pag[1][$key]).'&xml';
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
$page=cek(izle($_GET['filmparcalari'])); 
preg_match("/film-ozeti\">(.*?)<\/div>/s",$page,$ozet); //adres ana sayfa
preg_match("/imdb\">(.*?)</",$page,$imdb); 
preg_match("/<iframe src=\"(.*?)\"/",$page,$link1);
preg_match("/part-sayfalama(.*?)film-yorumlari/s",$page,$parts);
//array_push($link, $_GET['filmparcalari']);
//array_push($isim, 'PART 1');
preg_match_all("/li><a.*?href=\"(.*?)\".*:?>(.*?)<\/a>/",$parts[1],$sonrakiler);
//print_r($sonrakiler);
	foreach($sonrakiler[1] as  $key => $a){
		array_push($link, $sonrakiler[1][$key]);
		array_push($isim,strtoupper($sonrakiler[2][$key]));}
		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
		<items>
		<playlist_name><![CDATA[FİLM LİNKLERİ]]></playlist_name>
		"; 
		$filml=array();
		$pic='vk.png adresi';
		foreach ($link as $kiy => $lynk){
			if($isim[$kiy]=='1'){$lynk=$_GET['filmparcalari'];}
			$page=cek(izle($lynk));
			preg_match("/iframe.*?src=\"(.*?)\"/",$page,$film);
			if ($film[1]==''){
				$pic='novideo.jpg adresi';
				$desc1='Bu filmin kullandığınız cihazda oynatılabilen tipi malesef yok...'; }
			$echo.=playlist($isim[$kiy],$pic,$desc1,str_replace('&#038;','&',str_replace('&amp;','&',$film[1])),'stream');
	}
$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$_GET['before'].'&xml','playlist');
$echo.=anamenudon($desc1,$otoportal);			
echo $echo;
}
?>