<?php
error_reporting(E_ALL ^ E_NOTICE);
$dosya='bicapsfilmler.php';
include "../../a_cfg_b/cfg.php"; 
$site="http://www.bicaps.net/";
//********************************************************************************************************************************************************************
	if ($_GET['search']<>''){
header("Location: ".$script."?nedir=filmler&filmler=".$site."?s=".$_GET['search']);
	}
//********************************************************************************************************************************************************************
	if (!isset($_GET['nedir'])){
	$page = cek($site);
	preg_match("/<meta http-equiv=\"refresh\" content=\"0;url=(.*?)\">/",$page,$test);
	if ($test[1]<>''){
	$page=cek($test[1]);
	$page=cek($site);
}
	preg_match_all("/<li.*?class=\"cat-item.*?cat-item-\d+\"><a.*?href=\"(.*?)\".*?title=\".*?\">(.*?)<\/a>/",$page,$kategoriler);
//print_r($kategoriler);
	preg_match("/<div class=\"tam\">(.*?)<\/div>/s",$page,$subpage);
	preg_match_all("/href=\"(.*?)\">(.*?)<\/a>/",$subpage[1],$begeni);
	$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
		<items>
		<playlist_name><![CDATA[BICAPS.COM]]></playlist_name>
		"; 
		$pic='ADRES bicapslogo.png';
		if (($_SERVER['HTTP_USER_AGENT']=='') or ($_SERVER['HTTP_USER_AGENT']=='Mozilla/5.0 seyirTURKVOD 0.1')){
			$echo.=playlist('ARAMA',$pic,'ARAMA FONKSYONU SADECE ENIGMA2 CIHAZLARDA ÇALIŞIR','seyirTURKModul@webot@'.substr($server,7).$dosya.'?nedir=filmler&filmler='.$site.'?s=@ARAMA','playlist');
		} else {$echo.=smartarama('SMART TV ARAMA',$pic,'BU ARAMA SADECE SMART TVLER İÇİNDİR.',$script,'playlist');}
		$echo.=playlist('IMDB7+ FİLMLER','http://www.userlogos.org/files/logos/xSijUx/IMDBlack.png',$desc1,$script.'?nedir=filmler&filmler='.$site.'category/filmler/imdb-7-0/&xml','playlist');
		foreach ($begeni[1] as  $key => $begen ) {
			$name=str_replace($bunu, $degistir, $begeni[2][$key]);
			$link=$script."?nedir=filmler&filmler=".$begeni[1][$key].'&xml';
			$echo.=playlist($name,$pic,$desc1,$link,'playlist');
		}
		foreach ($kategoriler[1] as  $key => $kategori ) {
			if (substr($kategoriler[1][$key],31,7)=='filmler'){
				$name=strtoupper(str_replace($bunu, $degistir, $kategoriler[2][$key]));
				$link=$script."?nedir=filmler&filmler=".$kategoriler[1][$key].'&xml';
				$echo.=playlist(strtoupper($name),$pic,$desc1,$link,'playlist');
			}
		}
		$echo.=anamenudon($desc1,$otoportal);			
	echo $echo;
	}
//********************************************************************************************************************************************************************
	if ($_GET['nedir']=="filmler"){
		$dizianasayfa1=cek($_GET['filmler']);
		$ad=substr($_GET['filmler'],62,-1);
		preg_match("/href='([^']*)' class='previouspostslink/",$dizianasayfa1,$prev);          
		preg_match("/href='([^']*)' class='page larger/",$dizianasayfa1,$next);
		preg_match_all("/href=\"(.*?)\">\s*<span.*?>.*?<\/span>\<img.*src=\"(.*?)\".*?alt=\"(.*?)\"/",$dizianasayfa1,$filmler);
		preg_match_all("/href=\"(.*?)\">\s*<img.*src=\"(.*?)\".*?alt=\"(.*?)\".*.?<\/a>/",$dizianasayfa1,$filmler);
		//echo $next[1];
		//echo $prev[1];
		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
			<items>
			<playlist_name><![CDATA[".strtoupper(str_replace($bunu, $degistir, $ad))."]]></playlist_name>
			"; 
		foreach($filmler[1] as $key => $film){
			$name=str_replace($bunu, $degistir,$filmler[3][$key]);
			$pic=$filmler[2][$key];
			$link=$script.'?nedir=filmparcalari&filmparcalari='.$filmler[1][$key].'&before='.$_GET['filmler'].'&xml';
			if ((strtolower($filmler[3][$key])!=="keremiya") and (trim($filmler[3][$key])!=="") and (strpos($filmler[3][$key],"BiCaps.Com")===False)){
				$echo.=playlist(strtoupper($name),$pic,$desc1,$link,'playlist');
			}
		}
		if($next[1]<>""){
			$echo.=playlist('İLERİ',$nextpic,$desc1,$script.'?nedir=filmler&filmler='.$next[1].'&xml','playlist');
		}
		if($prev[1]<>""){
			$prew=$prev[1];
			if (substr($prev[1],-1)=='/'){$prew=substr($prev[1],0,-1);}
			$echo.=playlist('GERİ',$prevpic,$desc1,$script.'?nedir=filmler&filmler='.$prew.'&xml','playlist');
		}
		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$script.'?xml','playlist');
		$echo.=anamenudon($desc1,$otoportal);			
echo $echo;
	}
//**********************************************************************************************************************************************************************
if ($_GET['nedir']=="filmparcalari"){
$link=array();
$isim=array();
$page=cek($_GET['filmparcalari']);
preg_match("/FILM BILGILERI(.*?)netmen/s",$page,$bilgiler);
preg_match("/<strong>(.*?)Sitemizden/",$bilgiler[1],$konu);
preg_match("/<img src=\"(.*?)\" alt=\"(.*?)\|/",$bilgiler[1],$resis);
preg_match("/IMDB.*?<\/font><\/span>:(.*?)<\/p>/",$bilgiler[1],$imdb);
preg_match("/Oyuncular.*?:(.*?)<\/p>/",$bilgiler[1],$oyuncular);
preg_match("/Yap.*?:(.*?)<\/p>/",$bilgiler[1],$yapim);
$i=(int)$imdb[1];
$rating='<center><img src="RESİM ÖNEK'.$i.'.png" height="30" width="180"/></center>';
$aciklama=$rating.'Yapım: '.$yapim[1].' | IMDB: '.$imdb[1].'<br>Oyuncular: '.$oyuncular[1].'<br>Konu: '.str_replace('</strong></a>',' ',$konu[1]);
preg_match("/<span>(.*?)<\/span>/",$page,$ilk); //adres ana sayfa
array_push($link, $_GET['filmparcalari']);
array_push($isim, strtoupper($ilk[1]));
preg_match_all("/href=\"(.*?)\"><span>(.*?)<\/span>/",$page,$sonrakiler);
	foreach($sonrakiler[1] as  $key => $a){
		array_push($link, $sonrakiler[1][$key]);
		array_push($isim,strtoupper($sonrakiler[2][$key]));}
		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
		<items>
		<playlist_name><![CDATA[FİLM LİNKLERİ]]></playlist_name>
		"; 
		$filml=array();
		foreach ($link as $kiy => $lynk){
			$page=cek($lynk);
			preg_match("/baslik:(.*?)table/s",$page,$b);
			preg_match("/iframe.*?src=\"(.*?)\"/",$b[1],$film);
			//echo $film[1].'<br>';
				if (($film[1]=='') and (strpos($film[1],'youtube')!==true)){
				preg_match("/href=\"(.*?)\".*?target/",$b[1],$film);
				}
			if (strpos($isim[$kiy],'PART')!== false){$pic=' ADRES mailru.png';}
			if (strpos($isim[$kiy],'FRAGMAN')!== false){$pic='ADRES youtube1.jpg';}
			if (strpos($isim[$kiy],'VK')!== false){$pic='ADRES vk.png';}
			if (strpos($isim[$kiy],'DIVXSTAGE')!== false){$pic='http://www.divxstage.eu/images/logo.jpg';}
			if (strpos($isim[$kiy],'MOVSHARE')!== false) {$pic='http://www.movshare.net/images/logo.png';}
			if (strpos($isim[$kiy],'WATCHFREEINHD')!== false) {$pic='ADRES watchfreeinhd.png';}
			$echo.=playlist(strtoupper($isim[$kiy]),$resis[1],$aciklama,str_replace('&#038;','&',str_replace('&amp;','&',$film[1])),'stream');
	}
$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$script.'?nedir=filmler&filmler='.$_GET['before'].'&xml','playlist');
$echo.=anamenudon($desc1,$otoportal);			
echo $echo;
}
?>