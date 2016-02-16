<?php

//tengildet @ turkportal.org, please ask me to use freely :)

include "../../a_cfg_b/cfg.php"; 

$site  = "http://voyeurhit.com"; // WEB ADDRESS OFWEBPAGE.

$pic = "https://lh6.googleusercontent.com/-yil0zCBNhAg/UnkMrLCQhVI/AAAAAAAAACE/PWfHvNpojN4/s488-no/logo_new420.png"; // LOGO OF WEBSITE.

$referer =  $site.'/';

//echo $script;

//***********************************************CAT'S********************************************************

if ((!isset($_GET['films'])) and (!isset($_GET['filmparts']))){   // IF CONDITION TO DECIDE FETCH TO MAIN WEBPAGE.

	$page = cek($site.'/categories/');     //FETCING WEBPAGE USING CURL DEFINED IN CONFIG.PHP.

	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.

	//preg_match("/mainnav(.*?)erotic_story/", $page, $subpage);//NARROVING THE DATA TO GET MORE ACCURATE RESULTS FOR THE FOLLOWING REGEX.

	preg_match_all("/<a href=\"([^\"]*)\".*?src=\"([^\"]*)\" alt=\"([^\"]*)\">.*?date\">(.*?)videos/", $page, $cats);// REGEX FOR CATS.

//print_r($cats);	//DEBUGING.

	$echo .= xmlbaslik($site); // ADDS XML HEADERS TO XML.

	$echo .= playlist('YENİ EKLENENLER',$pic,$desc,$script.'?films='.urlencode($site).'&before='.urlencode($script.'&xml'),'playlist');// ADD 'NEW ADDED' CAT TO XML AS PLAYLIST.
	$echo .= playlist('EN ÇOK OY ALANLAR',$pic,$desc,$script.'?films='.urlencode($site.'/top-rated/').'&before='.urlencode($script.'&xml'),'playlist');// ADD 'NEW ADDED' CAT TO XML AS PLAYLIST.
	$echo .= playlist('POPÜLER VİDEOLAR',$pic,$desc,$script.'?films='.urlencode($site.'/most-popular/').'&before='.urlencode($script.'&xml'),'playlist');// ADD 'NEW ADDED' CAT TO XML AS PLAYLIST.
	$echo .= playlist('SON GÜNCELLEMELER',$pic,$desc,$script.'?films='.urlencode($site.'/latest-updates/').'&before='.urlencode($script.'&xml'),'playlist');// ADD 'NEW ADDED' CAT TO XML AS PLAYLIST.

	foreach ($cats[1] as $key => $cat){ // FOREACH LOOP TO PROCESS ELEMENTS OF CATS ARRAY .

		$name =strtoupper($cats[3][$key]);

		$catlink= $cat;  // CAT LINKS CORRECTION.
		$pic = $cats[2][$key];
		$before = '&before='.urlencode($script.'&xml'); // RETURN URL TO CATS
		$desc = 'Bu bölümde toplam '.$cats[4][$key].'adet video vardır.';
		$nexturl = $script.'?films='.urlencode($catlink); // LINK TO FILMPS

		$namedirect = '&name='.urlencode($name); // DIRECTED NAME TO FILMS 		

		$echo .= playlist($name,$pic,$desc,$nexturl.$before.$namedirect,'playlist');// ADD ALL CAT TO XML AS PLAYLIST.

	}

$echo.=rabbit($descmain,$rabbit); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			

echo $echo; // XML OUTPUT.

}

//***********************************************FILMS********************************************************

if (isset($_GET['films'])){

	$page=cek(izle(urldecode($_GET['films'])));

	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.

///////////////////////PAGINATION//////////////////////////////////////////////////////////////////////
preg_match("/paging(.*?)Next 10 pages/",$page,$subpage);
preg_match('/<a href=\"([^\"]*)\">.*?<\/a>.*?<span>/', $subpage[1], $prev);

preg_match('/<\/span>.*?<a href=\"([^\"]*)\">.*?<\/a>/', $subpage[1], $next);

///////////////////////////////////////////////////////////////////////////////////////////////////////

	//preg_match("/dle-content(.*?)navigation2/", $page, $subpage);//NARROVING THE DATA TO GET MORE ACCURATE RESULTS FOR THE FOLLOWING REGEX.

	preg_match_all("/<a href=\"([^\"]*)\" class=\"thumb\">.*?img src=\"([^\"]*)\" alt=\"([^\"]*)\".*?dur_ovimg\">(.*?)<\/div>/", $page, $films);// REGEX FOR CATS.

	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.

	foreach ($films[1] as $key => $film){ // FOREACH LOOP TO PROCESS ELEMENTS OF FILMS ARRAY.

		$name = strtoupper($films[3][$key]); // REARRANGE FILM NAMES.

		$filmlink= $film;  // FILM LINKS.

		$desc = 'Video Süresi: '.$films[4][$key]; // FILM DESCRIPTION.

		$pic = $films[2][$key]; // FILM PICTURE.



			$before = '&before='.urlencode($script.'?films='.$_GET['films'].'&xml'); // RETURN URL TO FILMS

			$nexturl = $script.'?filmparts='.urlencode($filmlink); // LINK TO FILMPARTS

			$namedirect = '&name='.urlencode($name); // DIRECTED FILM NAME TO FILMPARTS

			$echo .= playlist($name,$pic,$desc,$nexturl.$before.$namedirect,'playlist');// ADD ALL FILM TO XML AS PLAYLIST.


	}



			if (strlen($next[1])<>0){

			$name='Sonraki Sayfa';

			$before = '&before='.urlencode($script.'?films='.$_GET['films'].'&xml'); // RETURN URL TO FILMS FROM PAGES

			$nexturl = $script."?films=".urlencode($site.$next[1]).'&xml';

			$namedirect = '&name='.urlencode($name);

			$echo .= playlist($name,$prevpic,$descmain,$nexturl.$before.$namedirect,'playlist');// ADD NEXT AND PREV PAGES TO XML AS PLAYLIST.

		}

			if (strlen($prev[1])<>0){

			$name='Önceki Sayfa';

			$before = '&before='.urlencode($script.'?films='.$_GET['films'].'&xml'); // RETURN URL TO FILMS FROM PAGES

			$nexturl = $script."?films=".urlencode($site.$prev[1]).'&xml';

			$namedirect = '&name='.urlencode($name);

			$echo .= playlist($name,$nextpic,$descmain,$nexturl.$before.$namedirect,'playlist');// ADD NEXT AND PREV PAGES TO XML AS PLAYLIST.

		}



$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$descmain,$script.'?xml','playlist');

$echo.=rabbit($descmain,$rabbit); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			

echo $echo; // XML OUTPUT.

}

//***********************************************FILMPARTS********************************************************

if (isset($_GET['filmparts'])){

	$frontpage=cek(urldecode($_GET['filmparts']));

	preg_match("/video_url:.*?'http:\/\/voyeurhit.com\/get_file(.*?)'/", $frontpage, $link); 
	preg_match("/preview_url: \'([^\']*)\'/", $frontpage, $pict);  
	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.

		$name = strtoupper($_GET['name']); //  FILM NAMES.

		$echo .= playlist($name,$pict[1],$descmain,'http://voyeurhit.com/get_file/'.$link[1],'stream');// ADD ALL FILM TO XML AS PLAYLIST.

	$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$descmain,urldecode($_GET['before']).'&xml','playlist');

$echo.=rabbit($descmain,$rabbit); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			

echo $echo; // XML OUTPUT.

}

?>