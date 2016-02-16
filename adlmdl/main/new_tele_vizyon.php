<?php
//tengildet @ turkportal.org, please ask me to use freely :)
include "../../a_cfg_b/cfg.php"; 
$site  = "http://tele-vizyon.com"; // WEB ADDRESS OFWEBPAGE.
$pic = "http://tele-vizyon.com/wp-content/uploads/2013/03/logo-type3.png"; // LOGO OF WEBSITE.
$referer =  $site.'/';
//echo $script;
//***********************************************CAT'S********************************************************
if ((!isset($_GET['films'])) and (!isset($_GET['filmparts']))){   // IF CONDITION TO DECIDE FETCH TO MAIN WEBPAGE.
	$page = cek($site);     //FETCING WEBPAGE USING CURL DEFINED IN CONFIG.PHP.
//echo $page;
	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.
	preg_match("/cat-item cat-item-12(.*?)Son Yorumlar/", $page, $subpage);//NARROVING THE DATA TO GET MORE ACCURATE RESULTS FOR THE FOLLOWING REGEX.
//print_r($subpage[1]);
	preg_match_all("/href=\"(.*?)\".*?\">(.*?)<\/a>/", $subpage[1], $cats);// REGEX FOR CATS.
//print_r($cats);	//DEBUGING.
	$echo .= xmlbaslik($site); // ADDS XML HEADERS TO XML.
	$echo .= playlist('YENİ EKLENENLER',$pic,$desc,$script.'?films='.urlencode($site).'&before='.urlencode($script.'&xml').'&xml','playlist');// ADD 'NEW ADDED' CAT TO XML AS PLAYLIST.

	foreach ($cats[1] as $key => $cat){ // FOREACH LOOP TO PROCESS ELEMENTS OF CATS ARRAY .
		$name = strtoupper(str_replace($bunu,$degistir,$cats[2][$key])); // REARRANGE CAT NAMES.
		$catlink= $cat;  // CAT LINKS CORRECTION.
		$before = '&before='.urlencode($script.'&xml'); // RETURN URL TO CATS-this line defins self bölüm
		$nexturl = $script.'?films='.urlencode($catlink); // LINK TO FILMS
		$namedirect = '&name='.urlencode($name); // DIRECTED NAME TO FILMS 		
		$echo .= playlist($name,$pic,$desc,$nexturl.$before.$namedirect.'&xml','playlist');// ADD ALL CAT TO XML AS PLAYLIST.
	}
$echo.=anamenudon($descmain,$otoportal); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			
echo $echo; // XML OUTPUT.
}
//***********************************************FILMS********************************************************
if (isset($_GET['films'])){
	$page=cek(urldecode($_GET['films']));
	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.
///////////////////////PAGINATION//////////////////////////////////////////////////////////////////////
//preg_match('/bnnavi(.*)dle-info/', $page, $subpagina);
//preg_match_all("/<a href=\"(.*?)\">(\d*)<\/a>/", $subpagina[1], $pages);
preg_match('/<link rel=\'prev\' href=\'([^\']*)\'/', $page, $prev);
preg_match('/<link rel=\'next\' href=\'([^\']*)\'/', $page, $next);
///////////////////////////////////////////////////////////////////////////////////////////////////////
	preg_match("/Son Eklenen Filmler(.*?)<\/section>/", $page, $subpage);//NARROVING THE DATA TO GET MORE ACCURATE RESULTS FOR THE FOLLOWING REGEX.
	preg_match_all("/<h2><a href=\"(.*?)\".*?title=\"(.*?)\">.*?<\/h2>.*?imdb-score\">(.*?)<\/div>.*?language\">(.*?)<\/div>.*?img.*?src=\"(.*?)\"/", $subpage[1], $films);// REGEX FOR CATS.
//print_r($films);
	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.
	foreach ($films[1] as $key => $film){ // FOREACH LOOP TO PROCESS ELEMENTS OF FILMS ARRAY.
		$name = strtoupper(str_replace($bunu,$degistir,$films[2][$key])); // REARRANGE FILM NAMES.
		$filmlink= $film;  // FILM LINKS.
		$pic = $films[5][$key]; // FILM PICTURE.
		$imdb = $films[3][$key]; // FILM IMDB RATING.
		$language = $films[4][$key];
		$i=(int)$imdb;
		$rating='<center><img src="rsesimöneki'.$i.'.png" height="30" width="180"/></center>';
		$desc = $rating.'IMDB PUANI: '.trim($imdb).'<br>Dil: '.$language.'<br>Konu: '.$films[6][$key]; // FILM DESCRIPTION.

			if (strpos($films[5][$key],'http')===false){$pic=$site.$films[5][$key];}
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
	$films=array();
	$names=array();
	preg_match("/video-before(.*?)Sorun/s",$page,$subpage);
	preg_match("/<span class=\'woca-current-page\'>(.*?)<\/span>/",$page,$first);
	array_push($films,$_GET['filmparts']);
	array_push($names,$first[1]);
	preg_match_all('/href=\"(.*?)\">(.*?)<\/a>/',$subpage[1],$filmler);
	foreach ($filmler[1] as $key => $film){
		array_push($films,$film);
		array_push($names,$filmler[2][$key]);

	}
//print_r($films);
//print_r($names);


	preg_match("/article class(.*?)Bir Cevap/s",$page,$ozetler);
//print_r($ozetler[1]);
	preg_match("/Eklenme: <\/span>(.*?)<\/p>/",$page,$ekleme);
	$tarih = trim($ekleme[1]);
	preg_match("/<img.*?src=\"(.*?)\".*?alt=\"(.*?)\" title/",$page,$imdb);
	preg_match("/<img.*?src=\"(.*?)\" class=/",$ozetler[1],$pics);
	preg_match("/Filmin Konusu:<\/b><\/p>.*?<p>(.*?)<\/p>/",$ozetler[1],$ozet);
	$desc = 'Eklenme Tarihi: '.$tarih.'<br>'.'Konu: '.$ozet[1];
	$pic=$pics[1];

	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.
	foreach($films as $key => $film){
		$name=$names[$key];

		$pagelink=cek($film);
		preg_match("/object-wrapper\"><iframe.*?src=\"([^\"]*)\"/",$pagelink,$flink);
		$link=$flink[1];
		if (strpos($link, 'http')===false){$link='http:'.$link;}
		$link=str_replace($vkbunu,$vkdegistir,$link);
		$link=str_replace("?rel=0","",$link);


		$echo.=playlist($name,$pic,$desc,$link,'stream');
		}
		
$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$descmain,urldecode($_GET['before']).'&xml','playlist');
$echo.=anamenudon($descmain,$otoportal); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			
echo $echo; // XML OUTPUT.


}
?>