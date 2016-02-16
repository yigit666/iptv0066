<?php

//tengildet @ turkportal.org, please ask me to use freely :)

include "../../a_cfg_b/cfg.php"; 

$site  = "http://porhd.com"; // WEB ADDRESS OFWEBPAGE.

$pic = "https://lh3.googleusercontent.com/-I1N-pxEbkOs/UCmBKd9oxeI/AAAAAAAAAFs/9eVq7BlPwfM/w346-h345/I%2BLOVE%2BPORNO_logo_krivky.jpg"; // LOGO OF WEBSITE.

$referer =  $site.'/';

//echo $script;

//***********************************************CAT'S********************************************************

if ((!isset($_GET['films'])) and (!isset($_GET['filmparts']))){   // IF CONDITION TO DECIDE FETCH TO MAIN WEBPAGE.

	$page = cek($site);     //FETCING WEBPAGE USING CURL DEFINED IN CONFIG.PHP.

	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.

	//preg_match("/mainnav(.*?)erotic_story/", $page, $subpage);//NARROVING THE DATA TO GET MORE ACCURATE RESULTS FOR THE FOLLOWING REGEX.

	preg_match_all("/<a class=\"buttonx1\" href=\"(.*?)\">/", $page, $cats);// REGEX FOR CATS.

//print_r($cats[1]);	//DEBUGING.

	$echo .= xmlbaslik($site); // ADDS XML HEADERS TO XML.

	$echo .= playlist('YENİ EKLENENLER',$pic,$desc,$script.'?films='.urlencode($site).'&before='.urlencode($script.'&xml'),'playlist');// ADD 'NEW ADDED' CAT TO XML AS PLAYLIST.

	foreach ($cats[1] as $cat){ // FOREACH LOOP TO PROCESS ELEMENTS OF CATS ARRAY .

		$names =explode('/',$cat);

		$name = strtoupper(str_replace('-',' ',$names[3])); // REARRANGE CAT NAMES.

		$catlink= $cat;  // CAT LINKS CORRECTION.

		$before = '&before='.urlencode($script.'&xml'); // RETURN URL TO CATS

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

preg_match('/class=\"prev\"><a href=\"(.*?)\"/', $page, $prev);

preg_match('/class=\"next\"><a href=\"(.*?)\"/', $page, $next);

///////////////////////////////////////////////////////////////////////////////////////////////////////

	preg_match("/dle-content(.*?)navigation2/", $page, $subpage);//NARROVING THE DATA TO GET MORE ACCURATE RESULTS FOR THE FOLLOWING REGEX.

	preg_match_all("/href=\"([^\"]*)\" ><img src=\"([^\"]*)\"/", $subpage[1], $films);// REGEX FOR CATS.

	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.

	foreach ($films[1] as $key => $film){ // FOREACH LOOP TO PROCESS ELEMENTS OF FILMS ARRAY.

		$name = strtoupper(substr(str_replace('-',' ',$film),17,-5)); // REARRANGE FILM NAMES.

		$filmlink= $film;  // FILM LINKS.

		//$desc = $films[3][$key]; // FILM DESCRIPTION.

		$pic = $site.$films[2][$key]; // FILM PICTURE.

		if (strpos($films[2][$key],'html')==false){

			if (strpos($films[2][$key],'http')===false){$pic=$site.$films[2][$key];}

			$before = '&before='.urlencode($script.'?films='.$_GET['films'].'&xml'); // RETURN URL TO FILMS

			$nexturl = $script.'?filmparts='.urlencode($filmlink); // LINK TO FILMPARTS

			$namedirect = '&name='.urlencode($name); // DIRECTED FILM NAME TO FILMPARTS

			$echo .= playlist($name,$pic,$descmain,$nexturl.$before.$namedirect,'playlist');// ADD ALL FILM TO XML AS PLAYLIST.

		}

	}



			if (strlen($next[1])<>0){

			$name='Sonraki Sayfa';

			$before = '&before='.urlencode($script.'?films='.$_GET['films'].'&xml'); // RETURN URL TO FILMS FROM PAGES

			$nexturl = $script."?films=".urlencode($next[1]).'&xml';

			$namedirect = '&name='.urlencode($name);

			$echo .= playlist($name,$pic,$descmain,$nexturl.$before.$namedirect,'playlist');// ADD NEXT AND PREV PAGES TO XML AS PLAYLIST.

		}

			if (strlen($prev[1])<>0){

			$name='Önceki Sayfa';

			$before = '&before='.urlencode($script.'?films='.$_GET['films'].'&xml'); // RETURN URL TO FILMS FROM PAGES

			$nexturl = $script."?films=".urlencode($prev[1]).'&xml';

			$namedirect = '&name='.urlencode($name);

			$echo .= playlist($name,$pic,$descmain,$nexturl.$before.$namedirect,'playlist');// ADD NEXT AND PREV PAGES TO XML AS PLAYLIST.

		}



$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$descmain,$script.'?xml','playlist');

$echo.=rabbit($descmain,$rabbit); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			

echo $echo; // XML OUTPUT.

}

//***********************************************FILMPARTS********************************************************

if (isset($_GET['filmparts'])){

	$frontpage=cek(urldecode($_GET['filmparts']));

	preg_match("/<iframe.*?src=\"(.*?)\"/", $frontpage, $link); 

	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.

		$name = strtoupper($_GET['name']); //  FILM NAMES.

		$echo .= playlist($name,$pic,$descmain,$link[1],'stream');// ADD ALL FILM TO XML AS PLAYLIST.

	$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$descmain,urldecode($_GET['before']).'&xml','playlist');

$echo.=rabbit($descmain,$rabbit); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			

echo $echo; // XML OUTPUT.

}

?>