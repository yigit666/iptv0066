<?php

//tengildet @ turkportal.org, please ask me to use freely :)

include "../../a_cfg_b/cfg.php"; 

$site  = "http://www.hdtubes.net"; // WEB ADDRESS OF WEBPAGE.

$pic = "adres youhudporn.png"; // LOGO OF WEBSITE.

$referer =  $site.'/';

//echo $script;

//***********************************************CAT'S********************************************************

if ((!isset($_GET['films'])) and (!isset($_GET['filmparts']))){   // IF CONDITION TO DECIDE FETCH TO MAIN WEBPAGE.

	$page = cek($site);     //FETCING WEBPAGE USING CURL DEFINED IN CONFIG.PHP.

	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.

	preg_match("/mainnav(.*?)erotic_story/", $page, $subpage);//NARROVING THE DATA TO GET MORE ACCURATE RESULTS FOR THE FOLLOWING REGEX.

	preg_match_all("/href=\"(.*?)\"/", $subpage[1], $cats);// REGEX FOR CATS.

//print_r($cats[1]);	//DEBUGING.

	$echo .= xmlbaslik($site); // ADDS XML HEADERS TO XML.

	$echo .= playlist('ANASAYFA',$pic,$desc,$script.'?films='.urlencode($site).'&before='.urlencode($script.'&xml'),'playlist');// ADD 'NEW ADDED' CAT TO XML AS PLAYLIST.

	$echo .= playlist('YENİ EKLENENLER',$pic,$desc,$script.'?films='.urlencode($referer.'latest-updates/').'&before='.urlencode($script.'&xml'),'playlist');// ADD 'NEW ADDED' CAT TO XML AS PLAYLIST.

	$echo .= playlist('EN ÇOK OYLANANLAR',$pic,$desc,$script.'?films='.urlencode($referer.'top-rated/').'&before='.urlencode($script.'&xml'),'playlist');// ADD 'NEW ADDED' CAT TO XML AS PLAYLIST.

	$echo .= playlist('EN POPÜLER VIDEOLAR',$pic,$desc,$script.'?films='.urlencode($referer.'most-popular/').'&before='.urlencode($script.'&xml'),'playlist');// ADD 'NEW ADDED' CAT TO XML AS PLAYLIST.



$echo.=rabbit($descmain,$rabbit); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			

echo $echo; // XML OUTPUT.

}

//***********************************************FILMS********************************************************

if (isset($_GET['films'])){

	$page=cek(urldecode($_GET['films']));

	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.

///////////////////////PAGINATION//////////////////////////////////////////////////////////////////////

preg_match("/<a href=\"([^\"]*)\" title=.*?<span>d*<\/span>.*?<a href=\"([^\"]*)\" title=.*?<\/a>/", $page, $pages);

///////////////////////////////////////////////////////////////////////////////////////////////////////

	preg_match_all("/<a href=\"([^\"]*)\" class=\"kt_imgrc\" title=\"([^\"]*)\"><img class=\"thumb\" src=\"([^\"]*)\"/", $page, $films);// REGEX FOR CATS.

	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.

	foreach ($films[1] as $key => $film){ // FOREACH LOOP TO PROCESS ELEMENTS OF FILMS ARRAY.

		$name = strtoupper($films[2][$key]); // REARRANGE FILM NAMES.

		$filmlink= $film;  // FILM LINKS.

		//$desc = $films[3][$key]; // FILM DESCRIPTION.

		$pic = $films[3][$key]; // FILM PICTURE.

			$before = '&before='.urlencode($script.'?films='.$_GET['films'].'&xml'); // RETURN URL TO FILMS

			$nexturl = $script.'?filmparts='.urlencode($filmlink); // LINK TO FILMPARTS

			$namedirect = '&name='.urlencode($name); // DIRECTED FILM NAME TO FILMPARTS

			$echo .= playlist($name,$pic,$descmain,$nexturl.$before.$namedirect,'playlist');// ADD ALL FILM TO XML AS PLAYLIST.

	}



			if (strlen($pages[2])<>0){

			$name='Sonraki Sayfa';

			$before = '&before='.urlencode($script.'?films='.$_GET['films'].'&xml'); // RETURN URL TO FILMS FROM PAGES

			$nexturl = $script."?films=".urlencode($pages[2]).'&xml';

			$namedirect = '&name='.urlencode($name);

			$echo .= playlist($name,$pic,$descmain,$nexturl.$before.$namedirect,'playlist');// ADD NEXT AND PREV PAGES TO XML AS PLAYLIST.

		}

			if (strlen($pages[1])<>0){

			$name='Önceki Sayfa';

			$before = '&before='.urlencode($script.'?films='.$_GET['films'].'&xml'); // RETURN URL TO FILMS FROM PAGES

			$nexturl = $script."?films=".urlencode($pages[1]).'&xml';

			$namedirect = '&name='.urlencode($name);

			$echo .= playlist($name,$pic,$descmain,$nexturl.$before.$namedirect,'playlist');// ADD NEXT AND PREV PAGES TO XML AS PLAYLIST.

		}

$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$descmain,$script.'?xml','playlist');

$echo.=rabbit($descmain,$rabbit); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			

echo $echo; // XML OUTPUT.

}

//***********************************************FILMPARTS********************************************************

if (isset($_GET['filmparts'])){

	$page=cek(urldecode($_GET['filmparts']));

	//preg_match("/<iframe.*?src=\"(.*?)\" frameborder/", $frontpage, $prepage);

	//$page=$prepage[1];

	//$page=cek($page);

	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.

	preg_match_all('/video.*?url.*?: \'(.*?)\/\'.*?video.*?text: \'(.*?)\'/', $page, $links);// REGEX FOR filmparts.

       preg_match('/preview_url: \'(.*?)\'/',$page, $pics); 

	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.

	foreach ($links[1] as $key => $film){ // FOREACH LOOP TO PROCESS ELEMENTS OF LINKS ARRAY.

		$name = strtoupper($links[2][$key].'p'); //  FILM NAMES.

		$pic = $pics[1]; // FILM PICTURE.

		$echo .= playlist($name,$pic,$descmain,$links[1][$key],'stream');// ADD ALL FILM TO XML AS PLAYLIST.

	}

$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$descmain,urldecode($_GET['before']).'&xml','playlist');

$echo.=rabbit($descmain,$rabbit); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			

echo $echo; // XML OUTPUT.

}

?>