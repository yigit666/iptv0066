<?php

//tengildet @ turkportal.org, please ask me to use freely :)

include "../../a_cfg_b/cfg.php"; 

$site  = "http://www.avsitesi.com"; // WEB ADDRESS OFWEBPAGE.

$pic = "logo adresi"; // LOGO OF WEBSITE.

$referer =  $site.'/';

//echo $script;

//***********************************************CAT'S********************************************************

if ((!isset($_GET['films'])) and (!isset($_GET['filmparts']))){   // IF CONDITION TO DECIDE FETCH TO MAIN WEBPAGE.

	$page = cek($site);     //FETCING WEBPAGE USING CURL DEFINED IN CONFIG.PHP.

//echo $page;

$page = iconv("ISO-8859-9","UTF-8",$page);

	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.

	preg_match("/left-menu-top\">Kategoriler(.*?)left-menu-bottom/", $page, $subpage);//NARROVING THE DATA TO GET MORE ACCURATE RESULTS FOR THE FOLLOWING REGEX.

//print_r($subpage[1]);

	preg_match_all("/href=\"(.*?)\".*?title=\"(.*?)\"/", $subpage[1], $cats);// REGEX FOR CATS.

//print_r($cats);	//DEBUGING.

	$echo .= xmlbaslik($site); // ADDS XML HEADERS TO XML.

	$echo .= playlist('YENİ EKLENENLER',$pic,$desc,$script.'?films='.urlencode($site).'&before='.urlencode($script.'&xml').'&xml','playlist');// ADD 'NEW ADDED' CAT TO XML AS PLAYLIST.

	$echo .= playlist('POPULER VIDEOLAR',$pic,$desc,$script.'?films='.urlencode($referer.'populer.html').'&before='.urlencode($script.'&xml').'&xml','playlist');// ADD 'NEW ADDED' CAT TO XML AS PLAYLIST.

	$echo .= playlist('EN İYİ VIDEOLAR',$pic,$desc,$script.'?films='.urlencode($referer.'iyiler.html').'&before='.urlencode($script.'&xml').'&xml','playlist');// ADD 'NEW ADDED' CAT TO XML AS PLAYLIST.



	foreach ($cats[1] as $key => $cat){ // FOREACH LOOP TO PROCESS ELEMENTS OF CATS ARRAY .

		$name = strtoupper(str_replace($bunu,$degistir,$cats[2][$key])); // REARRANGE CAT NAMES.

		$catlink= $referer.$cat;  // CAT LINKS CORRECTION.

		$before = '&before='.urlencode($script.'&xml'); // RETURN URL TO CATS-this line defins self bölüm

		$nexturl = $script.'?films='.urlencode($catlink); // LINK TO FILMS

		$namedirect = '&name='.urlencode($name); // DIRECTED NAME TO FILMS 		

		if ((strpos($name,'BEREKET')===False) and (strpos($name,'KURZHAAR')===False)and(strpos($name,'YABAN TV')===False)){

		$echo .= playlist($name,$pic,$desc,$nexturl.$before.$namedirect.'&xml','playlist');// ADD ALL CAT TO XML AS PLAYLIST.

	}}

$echo.=anamenudon($descmain,$otoportal); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			

echo $echo; // XML OUTPUT.

}

//***********************************************FILMS********************************************************

if (isset($_GET['films'])){

	$page=cek(urldecode($_GET['films']));

	$page = iconv("ISO-8859-9","UTF-8",$page);

	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.

///////////////////////PAGINATION//////////////////////////////////////////////////////////////////////

//preg_match('/bnnavi(.*)dle-info/', $page, $subpagina);

//preg_match_all("/<a href=\"(.*?)\">(\d*)<\/a>/", $subpagina[1], $pages);

preg_match('/<a href=\"([^\"]*)\">.*?<\/a>\&nbsp;.*?<b>\&nbsp/', $page, $prev);

preg_match('/nbsp;<\/b>.*?<a href=\"([^\"]*)\">/', $page, $next);

///////////////////////////////////////////////////////////////////////////////////////////////////////

	preg_match("/featur-product-box(.*?)Sayfalar/", $page, $subpage);//NARROVING THE DATA TO GET MORE ACCURATE RESULTS FOR THE FOLLOWING REGEX.

	preg_match_all("/<div class=\"product-image\"><a href=\"([^\"]*)\".*?title=\"([^\"]*)\"><img src=\"([^\"]*)\"/", $subpage[1], $films);// REGEX FOR CATS.

	preg_match_all("/zlenme: <strong>(.*?)<\/strong><br>/", $subpage[1], $watched);// REGEX FOR CATS.

	preg_match_all("/re: <strong>(.*?)<\/strong><br>/", $subpage[1], $times);// REGEX FOR CATS.

	preg_match_all("/Puan: <strong>(.*?)<\/strong><br>/", $subpage[1], $ratings);// REGEX FOR CATS.

//print_r($films);

	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.

	foreach ($films[1] as $key => $film){ // FOREACH LOOP TO PROCESS ELEMENTS OF FILMS ARRAY.

		$name = strtoupper(str_replace($bunu,$degistir,$films[2][$key])); // REARRANGE FILM NAMES.

		$filmlink= $referer.$film;  // FILM LINKS.

		$pic = $referer.$films[3][$key]; // FILM PICTURE.

		$izlenme = $watched[1][$key]; // FILM IMDB RATING.

		$süre = $times[1][$key];

		$rating=$ratings[1][$key];

		$desc = 'İzlenme: '.$izlenme.'<br>Süre: '.$süre.'<br>Puan: '.$rating; // FILM DESCRIPTION.



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

	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.

		$name='İZLE';

		preg_match("/http:\/\/www\.youtube\.com\/watch\?v=(.*?)\'/",$page,$flink);

		$link='http://www.youtube.com/embed/'.$flink[1];

		$echo.=playlist($name,$pic,$descmain,$link,'stream');

		

		

$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$descmain,urldecode($_GET['before']).'&xml','playlist');

$echo.=anamenudon($descmain,$otoportal); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			

echo $echo; // XML OUTPUT.





}

?>