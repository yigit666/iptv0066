<?php
error_reporting(E_ALL ^ E_NOTICE);
$dosya='baglanfilmizle.php';
include "../../a_cfg_b/cfg.php"; 
//********************************************************************************************************************************************************************
	if ($_GET['nedir']=="kategoriler"){
	$page = cek("http://www.baglanfilmizle.com");
	preg_match_all("/<a.*?href=\"(.*?)\"><span.*?class=\"kat\"><span.*?class=\"iki\">(.*?)<\/span><\/span><\/a>/",$page,$kategoriler);
	$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
		<items>
		<playlist_name><![CDATA[BAĞLANFİLMİZLE.COM]]></playlist_name>
		"; 
		$pic='LOGO ADRES';
		$echo.=playlist('ARAMA',$pic,'ARAMA FONKSYONU SADECE ENIGMA2 CIHAZLARDA ÇALIŞIR, LÜTFEN BİR SONRAKİ EKRANDA KLAVYEYİ KULLANABİLMEK İÇİN STOP TUŞUNA BASINIZ.','seyirTURKModul@webot@anaserver2.site50.net/enigma/zortals/'.$dosya.'?nedir=filmler&filmler=http://www.baglanfilmizle.com/?s=@ARAMA','playlist');
		$echo.=playlist('YENİ EKLENENLER',$pic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler=http://www.baglanfilmizle.com&xml','playlist');
		foreach ($kategoriler[1] as  $key => $kategori ) {
			$name=str_replace($bunu, $degistir, $kategoriler[2][$key]);
			$link=$mdldzn.$dosya."?nedir=filmler&filmler=".$kategoriler[1][$key].'&xml';
				if (strpos( $kategoriler[2][$key],'kategori') === false){
					$echo.=playlist($name,$pic,$desc1,$link,'playlist');
				}
			}
		$echo.=anamenudon($desc1,$otoportal);			
	echo $echo;
	}
//********************************************************************************************************************************************************************
	if ($_GET['nedir']=="filmler"){
		$dizianasayfa1=cek($_GET['filmler']);
		$ad=str_replace('page','sayfa',str_replace('/',' ',substr($_GET['filmler'],46)));
		preg_match("/\"orta\"(.*?)sayfala/s",$dizianasayfa1,$subpage);
		preg_match("/<a href='([^\']*)' class='inaktif' >\d+<\/a><a class='aktif'/",$dizianasayfa1,$prev);          
		preg_match("/'aktif'>\d+<\/a><a href='([^\']*)'/",$dizianasayfa1,$next);
		preg_match_all("/<img src=\"(.*?)\" alt=\"\" title=\"\" class=\"afis-resim\"\/>/",$dizianasayfa1,$afisler);
		preg_match_all("/<div class=\"bs\">(.*?)<\/div>/",$dizianasayfa1,$adlar);
		preg_match_all("/Hakkında: <\/span><span class=\"iki\">(.*?)<\/span>/",$dizianasayfa1,$konular);
		preg_match_all("/<a href=\"(.*?)\"><img src=.*?alt=\"film izle/",$dizianasayfa1,$filmler);
		if ($_GET['filmler']<>'http://www.baglanfilmizle.com'){
				preg_match_all("/\s<img src=\"(.*?)\" alt=\"\" title=\"\" class=\"afis-resim\"\/>/",$dizianasayfa1,$afisler);
				preg_match_all("/<a href=\"(.*?)\"><img src=.*?Hemen/",$dizianasayfa1,$filmler);
}
		//echo $next[1];
		//echo $prev[1];
		$echo.="<?xml version=\"1.0\" encoding=\"utf-8\" ?>
			<items>
			<playlist_name><![CDATA[".strtoupper(str_replace($bunu, $degistir, $ad))."]]></playlist_name>
			"; 
		foreach($filmler[1] as $key => $film){
			$name=str_replace($bunu, $degistir,$adlar[1][$key+1]);
			$pic=$afisler[1][$key];
			$link=$mdldzn.$dosya.'?nedir=filmparcalari&filmparcalari='.$filmler[1][$key].'&before='.$_GET['filmler'];
			$desc=$konular[1][$key];
			$echo.=playlist($name,$pic,$desc,$link.'&xml','playlist');
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
			<playlist_name><![CDATA[".strtoupper(str_replace($bunu,$degistir,(substr($_GET['filmparcalari'],30,-10))))."]]></playlist_name>
			"; 	
		$dizianasayfa3=cek($_GET['filmparcalari']);
		preg_match_all("/<li class=\"pagelink\">.*?<a href=\"(.*?)\">(.*?)<\/a>/",$dizianasayfa3,$parts); 
		$vidpages[].=$_GET['filmparcalari'];
		$ad[].='1. PART';
			if ($parts[1]<>'') {
				foreach($parts[1] as $key => $part) {
					$ad[].=str_replace($bunu,$degistir,$parts[2][$key]);
					$vidpages[].=$parts[1][$key];
				}
			}
		foreach($vidpages as $key=> $vidpage){
			if (strtoupper($ad[$key])=='FRAGMAN'){
				$page=cek($vidpage);
				preg_match("/<iframe.*?src=\"http:\/\/www\.youtube\.com\/embed\/(.*?)\"/",$page,$vidlinkyt);
				$pic='http://weblopedi.net/wp-content/uploads/youtube1.jpg';
				$echo.=playlist('FRAGMAN',$pic,$desc1,'http://www.youtube.com/watch?v='.$vidlinkyt[1],'stream');
			}
			if (strtoupper($ad[$key])<>'FRAGMAN'){
				$page=cek($vidpage);
				preg_match("/<iframe src=\"http:\/\/vk\.com(.*?)\"/",$page,$vidlinkvk);
				$pic='ADRES vk.png';
					if ($vidlinkvk[1][$key]<>''){
						$echo.=playlist($ad[$key],$pic,$desc1,str_replace($vkbunu,$vkdegistir,('http://vk.com'.$vidlinkvk[1])),'stream');
					}
			}
		}
		$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$desc1,$mdldzn.$dosya.'?nedir=filmler&filmler='.$_GET['before'].'&xml','playlist');
		$echo.=anamenudon($desc1,$otoportal);			

	echo $echo;
}
?>