<?php
//tengildet @ turkportal.org, please ask me to use freely :)
include "../../a_cfg_b/cfg.php"; 
$site  = "http://www.nostaljifilmizle.com"; // WEB ADDRESS OFWEBPAGE.
$pic = "http://www.nostaljifilmizle.com/wp-content/themes/keremiyav4/logo/logo.png"; // LOGO OF WEBSITE.
$referer =  $site.'/';
//echo $script;
//***********************************************CAT'S********************************************************
if ((!isset($_GET['films'])) and (!isset($_GET['filmparts']))){   // IF CONDITION TO DECIDE FETCH TO MAIN WEBPAGE.
	$page = cek($site);     //FETCING WEBPAGE USING CURL DEFINED IN CONFIG.PHP.
//echo $page;
	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.
	preg_match("/Kategoriler(.*?)sidebarborder/", $page, $subpage);//NARROVING THE DATA TO GET MORE ACCURATE RESULTS FOR THE FOLLOWING REGEX.
//print_r($subpage[1]);
	preg_match_all("/href=\"(.*?)\".*?title.*?\">(.*?)<\/a>/", $subpage[1], $cats);// REGEX FOR CATS.
//print_r($cats);	//DEBUGING.
	$echo .= xmlbaslik($site); // ADDS XML HEADERS TO XML.
	$echo .= playlist('YENİ EKLENENLER',$pic,$desc,$script.'?films='.urlencode($site).'&before='.urlencode($script.'&xml').'&xml','playlist');// ADD 'NEW ADDED' CAT TO XML AS PLAYLIST.
	$echo .= playlist('EN ÇOK İZLENENLER',$pic,$desc,$script.'?films='.urlencode($referer.'en-cok-izlenenler').'&before='.urlencode($script.'&xml').'&xml','playlist');// ADD 'NEW ADDED' CAT TO XML AS PLAYLIST.
	$echo .= playlist('EN ÇOK YORUMLANANLAR',$pic,$desc,$script.'?films='.urlencode($referer.'en-cok-yorumlananlar').'&before='.urlencode($script.'&xml').'&xml','playlist');// ADD 'NEW ADDED' CAT TO XML AS PLAYLIST.
	$echo .= playlist('EN ÇOK BEĞENİLENLER',$pic,$desc,$script.'?films='.urlencode($referer.'en-cok-begenilenler').'&before='.urlencode($script.'&xml').'&xml','playlist');// ADD 'NEW ADDED' CAT TO XML AS PLAYLIST.

	foreach ($cats[1] as $key => $cat){ // FOREACH LOOP TO PROCESS ELEMENTS OF CATS ARRAY .
		$name = strtoupper(str_replace($bunu,$degistir,$cats[2][$key])); // REARRANGE CAT NAMES.
		$catlink= $cat;  // CAT LINKS CORRECTION.
		$before = '&before='.urlencode($script.'&xml'); // RETURN URL TO CATS-this line defins self bölüm
		$nexturl = $script.'?films='.urlencode($catlink); // LINK TO FILMS
		$namedirect = '&name='.urlencode($name); // DIRECTED NAME TO FILMS 	
	if ((strpos($name, 'ARZU OKAY')===False) and (strpos($name, 'AYDEMİR')===False)){
		$echo .= playlist($name,$pic,$desc,$nexturl.$before.$namedirect.'&xml','playlist');// ADD ALL CAT TO XML AS PLAYLIST.
	}
	}
$echo.=anamenudon($descmain,$otoportal); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			
echo $echo; // XML OUTPUT.
}
//***********************************************FILMS********************************************************
if (isset($_GET['films'])){
	$page=cek(urldecode($_GET['films']));
	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.
//echo $page;
///////////////////////PAGINATION//////////////////////////////////////////////////////////////////////
preg_match('/rel=\'prev\' href=\'([^\']*)\' \/>/', $page, $prev);
preg_match('/rel=\'next\' href=\'([^\']*)\' \/>/', $page, $next);
///////////////////////////////////////////////////////////////////////////////////////////////////////
	preg_match_all("/<div class=\"moviefilm\">.*?src=\"([^\"]*)\" alt=.*?href=\"([^\"]*)\">(.*?)<\/a>.*?small>(.*?)<\/small>/", $page, $films);// REGEX FOR CATS.
//print_r($subpage[1]);
	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.
	foreach ($films[2] as $key => $film){ // FOREACH LOOP TO PROCESS ELEMENTS OF FILMS ARRAY.
		$name = strtoupper(str_replace($bunu,$degistir,$films[3][$key])); // REARRANGE FILM NAMES.
		$filmlink= $film;  // FILM LINKS.
		$pic = $films[1][$key]; // FILM PICTURE.
		$desc = 'İZLENME: '.$films[4][$key]; // FILM IMDB RATING.
			$before = '&before='.urlencode($script.'?films='.$_GET['films'].'&xml'); // RETURN URL TO FILMS
			$nexturl = $script.'?filmparts='.urlencode($filmlink); // LINK TO FILMPARTS
			$namedirect = '&name='.urlencode($_GET['name'].' | '.$name); // DIRECTED FILM NAME TO FILMPARTS
			$echo .= playlist($name,$pic,$desc,$nexturl.$before.$namedirect.'&xml','playlist');// ADD ALL FILM TO XML AS PLAYLIST.

	}

			if (strlen($next[1])<>0){
			$name='Sonraki Sayfa';
			$before = '&before='.urlencode($script.'?films='.$_GET['films'].'&xml'); // RETURN URL TO FILMS FROM PAGES
			$nexturl = $script."?films=".urlencode($next[1]).'&xml';
			$namedirect = '&name='.urlencode($name);
			$echo .= playlist($name,$pic,$descmain,$nexturl.$before.$namedirect.'&xml','playlist');// ADD NEXT AND PREV PAGES TO XML AS PLAYLIST.
		}
			if (strlen($prev[1])<>0){
			$name='Önceki Sayfa';
			$before = '&before='.urlencode($script.'?films='.$_GET['films'].'&xml'); // RETURN URL TO FILMS FROM PAGES
			$nexturl = $script."?films=".urlencode($prev[1]).'&xml';
			$namedirect = '&name='.urlencode($name);
			$echo .= playlist($name,$pic,$descmain,$nexturl.$before.$namedirect.'&xml','playlist');// ADD NEXT AND PREV PAGES TO XML AS PLAYLIST.
		}
$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$descmain,$script.'?xml','playlist');
$echo.=anamenudon($descmain,$otoportal); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			
echo $echo; // XML OUTPUT.
}
//***********************************************FILMPARTS********************************************************
if (isset($_GET['filmparts'])){
	$page=cek(urldecode($_GET['filmparts']));
	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.
	preg_match("/iframe src=\"http:\/\/vk\.com(.*?)\"/",$page,$streamlink);
	preg_match("/Konusu:.*?\">(.*?)<\/span>/",$page,$konu);
	preg_match("/Oyuncular:.*?\">(.*?)<\/span>/",$page,$oyuncular);
	preg_match("/Süre:.*?\">(.*?)<\/span>/",$page,$sure);
	preg_match("/Senaryo:.*?\">(.*?)<\/span>/",$page,$senaryo);
	preg_match("/Tarihi:.*?\">(.*?)<\/span>/",$page,$vizyontarihi);
	preg_match("/netmen:.*?\">(.*?)<\/span>/",$page,$yonetmen);
	$desc = 'Vizyon Tarihi: '.$vizyontarihi[1].' | Süre: '.$sure[1].' | Oyuncular: '.$oyuncular[1].' | Yönetmen: '.$yonetmen[1].' | Senaryo: '.$senaryo[1].' | Konu: '.$konu[1];
	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.

		$name=$_GET['name'];
		$link='http://vk.com'.str_replace($vkbunu,$vkdegistir,$streamlink[1]);
		$echo.=playlist($name,$pic,$desc,$link,'stream');
		
		
$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$descmain,urldecode($_GET['before']).'&xml','playlist');
$echo.=anamenudon($descmain,$otoportal); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			
echo $echo; // XML OUTPUT.


}
?>