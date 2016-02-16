<?php

//tengildet @ turkportal.org, please ask me to use freely :)

include "../../a_cfg_b/cfg.php"; 

$site  = "http://m.webteizle.org"; // WEB ADDRESS OFWEBPAGE.

$pic = "http://webteizle.org/images/WebteizleLogo.png"; // LOGO OF WEBSITE.

$referer =  $site.'/';

//echo $script;

//***********************************************CAT'S********************************************************

if ((!isset($_GET['films'])) and (!isset($_GET['filmparts'])) and (!isset($_GET['altcat']))){   // IF CONDITION TO DECIDE FETCH TO MAIN WEBPAGE.

	$page = cek($site);     //FETCING WEBPAGE USING CURL DEFINED IN CONFIG.PHP.

	$page=  iconv("windows-1254" , "utf8" , $page);

	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.

	//preg_match("/mainnav(.*?)erotic_story/", $page, $subpage);//NARROVING THE DATA TO GET MORE ACCURATE RESULTS FOR THE FOLLOWING REGEX.

	preg_match_all("/<li><a rel=.*?href=\"([^\"]*)\">(.*?)<\/a><\/li>/", $page, $cats);// REGEX FOR CATS.

//print_r($cats);	//DEBUGING.

	$echo .= xmlbaslik($site); // ADDS XML HEADERS TO XML.

	//$echo .= playlist('YENİ EKLENENLER',$pic,$desc,$script.'?films='.urlencode($site).'&before='.urlencode($script.'&xml'),'playlist');// ADD 'NEW ADDED' CAT TO XML AS PLAYLIST.

	foreach ($cats[1] as $key => $cat){ // FOREACH LOOP TO PROCESS ELEMENTS OF CATS ARRAY .

		$name = strtoupper(str_replace($bunu, $degistir,$cats[2][$key])); // REARRANGE CAT NAMES.

		$catlink= $referer.$cat;  // CAT LINKS CORRECTION.

		$before = '&before='.urlencode($script); // RETURN URL TO CATS

		$nexturl = $script.'?altcat='.urlencode($catlink); // LINK TO FILMPS

		$namedirect = '&name='.urlencode($name); // DIRECTED NAME TO FILMS 		

		$echo .= playlist($name,$pic,$desc,$nexturl.$before.$namedirect.'&xml','playlist');// ADD ALL CAT TO XML AS PLAYLIST.

	}

$echo.=anamenudon($descmain,$otoportal); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			

echo $echo; // XML OUTPUT.

}

//***********************************************ALTCAT*******************************************************

if (isset($_GET['altcat'])){

	$page=cek(urldecode($_GET['altcat']));

	$page=  iconv("windows-1254" , "utf8" , $page);

	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.

	preg_match_all("/<li><a rel=.*?href=\"([^\"]*)\">(.*?)<\/a><\/li>/", $page, $altcats);// REGEX FOR CATS.

//print_r($altcats);	//DEBUGING.

	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.

	//$echo .= playlist('YENİ EKLENENLER',$pic,$desc,$script.'?films='.urlencode($site).'&before='.urlencode($script.'&xml'),'playlist');// ADD 'NEW ADDED' CAT TO XML AS PLAYLIST.

	foreach ($altcats[1] as $key => $altcat){ // FOREACH LOOP TO PROCESS ELEMENTS OF CATS ARRAY .

		$name = strtoupper(str_replace($bunu, $degistir,$altcats[2][$key])); // REARRANGE CAT NAMES.

		$altcatlink= $referer.$altcat;  // CAT LINKS CORRECTION.

		$before = '&before='.urlencode($script); // RETURN URL TO CATS

		$nexturl = $script.'?films='.urlencode($altcatlink); // LINK TO FILMPS

		$namedirect = '&name='.urlencode($name); // DIRECTED NAME TO FILMS 		

		$echo .= playlist($name,$pic,$desc,$nexturl.$before.$namedirect.'&xml','playlist');// ADD ALL CAT TO XML AS PLAYLIST.

	}

$echo.=anamenudon($descmain,$otoportal); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			

echo $echo; // XML OUTPUT.

}

//***********************************************FILMS********************************************************

if (isset($_GET['films'])){

	$page=cek(urldecode($_GET['films']));

	$page=  iconv("windows-1254" , "utf8" , $page);

	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.

///////////////////////PAGINATION//////////////////////////////////////////////////////////////////////

//preg_match('/pagess(.*)dle-info/', $page, $subpagina);

preg_match("/<a rel=.*?href=\"([^\"]*)\">.*?\"/", $page, $next);

///////////////////////////////////////////////////////////////////////////////////////////////////////

	//preg_match("/dle-content(.*?)dle-info/", $page, $subpage);//NARROVING THE DATA TO GET MORE ACCURATE RESULTS FOR THE FOLLOWING REGEX.

	preg_match_all("/<li.*?title=\"([^\"]*)\" rel=.*?href=\"([^\"]*)\">.*?<img.*?src=\"([^\"]*)\"\/>.*?<p><b>Tür: <\/b>(.*?)<\/p>.*?<p><b>Yıl: <\/b>(.*?)<b>İzlenme: <\/b>(.*?)<\/p><p><b>IMDB: <\/b>.(.*?)<font.*?>(.*?)<\/font><\/p>/", $page, $films);// REGEX FOR CATS.

	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.

	foreach ($films[1] as $key => $film){ // FOREACH LOOP TO PROCESS ELEMENTS OF FILMS ARRAY.

		$name = strtoupper(str_replace($bunu, $degistir,$film)); // REARRANGE CAT NAMES.

		$filmlink= $referer.$films[2][$key];  // FILM LINKS.

		$pic = $films[3][$key]; // FILM PICTURE.

		$tur = $films[4][$key];

		$yil = $films[5][$key];

		$izlenme = $films[6][$key];

		$imdb = $films[7][$key];

		$dil = $films[8][$key];

		$desc = 'IMDB: '.$imdb.'<br>'.'Dil: '.$dil.'<br>'.'Tür: '.$tur.'<br>'.'İzlenme: '.$izlenme; // FILM DESCRIPTION.



			$before = '&before='.urlencode($script.'?films='.$_GET['films']); // RETURN URL TO FILMS

			$nexturl = $script.'?filmparts='.urlencode($filmlink); // LINK TO FILMPARTS

			$namedirect = '&name='.urlencode($name); // DIRECTED FILM NAME TO FILMPARTS

			$echo .= playlist($name,$pic,$desc,$nexturl.$before.$namedirect.'&xml','playlist');// ADD ALL FILM TO XML AS PLAYLIST.

	}



			$name='Sonraki Sayfa';

			$before = '&before='.urlencode($script.'?films='.$_GET['films'].'&xml'); // RETURN URL TO FILMS FROM PAGES

			$nexturl = $script."?films=".urlencode($referer.$next[1]).'&xml';

			$namedirect = '&name='.urlencode($name);

			$echo .= playlist($name,$pic,$descmain,$nexturl.$before.$namedirect.'&xml','playlist');// ADD NEXT AND PREV PAGES TO XML AS PLAYLIST.

$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$descmain,$script.'?xml','playlist');

$echo.=anamenudon($descmain,$otoportal); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			

echo $echo; // XML OUTPUT.

}

//***********************************************FILMPARTS********************************************************

if (isset($_GET['filmparts'])){

	$page=cek(urldecode($_GET['filmparts']));

	$page=  iconv("windows-1254" , "utf8" , $page);

	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.

       preg_match('/<iframe src=\"([^\"]*)\"/',$page, $link); 

	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.

	preg_match("/<br><br>(.*?)<\/small>/", $page, $desc);





		$name = strtoupper(urldecode($_GET['name'])); //  FILM NAMES.

		$echo .= playlist($name,$pic,str_replace('<br>','',$desc[1]),$link[1],'stream');// ADD ALL FILM TO XML AS PLAYLIST.

$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$descmain,$script.'?xml','playlist');

$echo.=anamenudon($descmain,$otoportal); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			

echo $echo; // XML OUTPUT.

}

?>