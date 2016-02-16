<?php

//tengildet @ turkportal.org, please ask me to use freely :)

include "../../a_cfg_b/cfg.php"; 

$site  = "http://www.evrenselfilm.net"; // WEB ADDRESS OFWEBPAGE.

$pic = "http://www.evrenselfilm.net/wp-content/themes/evrenselwp/img/evrenselfilmlogo.png"; // LOGO OF WEBSITE.

$referer =  $site.'/';

//echo $script;



//***********************************************CAT'S********************************************************

if ((!isset($_GET['films'])) and (!isset($_GET['filmparts']))){   // IF CONDITION TO DECIDE FETCH TO MAIN WEBPAGE.

	$page = cek($site);     //FETCING WEBPAGE USING CURL DEFINED IN CONFIG.PHP.

//echo $page;

	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.

	preg_match("/Kategoriler(.*?)Son Yorumlar/", $page, $subpage);//NARROVING THE DATA TO GET MORE ACCURATE RESULTS FOR THE FOLLOWING REGEX.

	//preg_match_all("/href=\"\/(.*?)\">(.*?)<\/a>/", $subpage[1], $cats);// REGEX FOR CATS.

	preg_match_all("/<a href=\"([^\"]*)\">(.*?)<\/a><\/li>/", $subpage[1], $cats);// REGEX FOR CATS.



//print_r($cats[1]);	//DEBUGING.

	$echo .= xmlbaslik($site); // ADDS XML HEADERS TO XML.
	$echo .= playlist('YENİ EKLENENLER',$pic,$desc,$script.'?films='.urlencode($site).'&before='.urlencode($script.'&xml'),'playlist');// ADD 'NEW ADDED' CAT TO XML AS PLAYLIST.

	foreach ($cats[1] as $key => $cat){ // FOREACH LOOP TO PROCESS ELEMENTS OF CATS ARRAY .

		$name = strtoupper(str_replace($bunu,$degistir,str_replace("Seçiniz","YENİ EKLENENLER",$cats[2][$key]))); // REARRANGE CAT NAMES.

		$catlink= $cat;  // CAT LINKS CORRECTION.

		$before = '&before='.urlencode($script.'&xml'); // RETURN URL TO CATS

		$nexturl = $script.'?films='.urlencode($catlink); // LINK TO FILMPS

		$namedirect = '&name='.urlencode($name); // DIRECTED NAME TO FILMS 		

		$echo .= playlist($name,$pic,$desc,$nexturl.$before.$namedirect,'playlist');// ADD ALL CAT TO XML AS PLAYLIST.

	}

$echo.=anamenudon($descmain,$otoportal); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			

echo $echo; // XML OUTPUT.

}

//***********************************************FILMS********************************************************

if (isset($_GET['films'])){

	$page=cek(izle(urldecode($_GET['films'])));

	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.

///////////////////////PAGINATION//////////////////////////////////////////////////////////////////////

//preg_match('/bnnavi(.*)dle-info/', $page, $subpagina);

//preg_match_all("/<a href=\"(.*?)\">(\d*)<\/a>/", $subpagina[1], $pages);

preg_match('/<link rel=\'prev\' href=\'([^\']*)\'/', $page, $prev);

preg_match('/<link rel=\'next\' href=\'([^\']*)\'/', $page, $next);

///////////////////////////////////////////////////////////////////////////////////////////////////////

	preg_match("/solBlok(.*?)sagBlok/", $page, $subpage);//NARROVING THE DATA TO GET MORE ACCURATE RESULTS FOR THE FOLLOWING REGEX.

	//preg_match_all("/gi\">.*?<a href=\"(.*?)\" title=\"(.*?)\"><img src=\"(.*?)\".*?<p>(.*?)<\/p>.*?İmdb Puanı:(.*?)<br>/", $subpage[1], $films);// REGEX FOR CATS.

	preg_match_all("/<img src=\"([^\"]*)\".*?<h2><a href=\"([^\"]*)\".*?title=\"([^\"]*)\"/", $subpage[1], $films);// REGEX FOR CATS.



//print_r($subpage[1]);

	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.

	foreach ($films[2] as $key => $film){ // FOREACH LOOP TO PROCESS ELEMENTS OF FILMS ARRAY.

		$name = strtoupper(str_replace('&#8211;','-',str_replace($bunu,$degistir,$films[3][$key]))); // REARRANGE FILM NAMES.

		$filmlink= $film;  // FILM LINKS.

		$pic = $films[1][$key]; // FILM PICTURE.


			$before = '&before='.urlencode($script.'?films='.$_GET['films'].'&xml'); // RETURN URL TO FILMS

			$nexturl = $script.'?filmparts='.urlencode($filmlink); // LINK TO FILMPARTS

			$namedirect = '&name='.urlencode($_GET['name'].' | '.$name); // DIRECTED FILM NAME TO FILMPARTS

			$echo .= playlist($name,$pic,$descmain,$nexturl.$before.$namedirect,'playlist');// ADD ALL FILM TO XML AS PLAYLIST.

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

$echo.=anamenudon($descmain,$otoportal); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			

echo $echo; // XML OUTPUT.

}

//***********************************************FILMPARTS********************************************************

if (isset($_GET['filmparts'])){

	$page=cek(urldecode($_GET['filmparts']));

	//$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.

	preg_match("/www\.youtube\.com(.*?)\"/", $page,$yt);

	preg_match("/vk\.com(.*?)\"/", $page,$vk);

	preg_match("/video\.mail\.ru(.*?)\"/", $page,$mailru);

	$fragman='http://www.youtube.com'.$yt[1];

	$vkkaynak='http://vk.com'.$vk[1];

	$mailrukaynak='http://api.video.mail.ru'.$mailru[1];

	preg_match("/<img class=\"aligncenter\" src=\"([^\"]*)\".*?title=\"([^\"]*)\".*?\/>(.*?)<\/p>/",$page,$detaylar);





	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.



	if ($yt[1]){ $echo.=playlist('Fragman - '.$detaylar[2],$detaylar[1],$detaylar[3],$fragman,'stream');}

	if ($vk[1]){ $echo.=playlist('VK - '.$detaylar[2],$detaylar[1],$detaylar[3],$vkkaynak,'stream');}

	if ($mailru[1]){ $echo.=playlist('Mail Ru - '.$detaylar[2],$detaylar[1],$detaylar[3],$mailrukaynak,'stream');}





		

$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$descmain,urldecode($_GET['before']).'&xml','playlist');

$echo.=anamenudon($descmain,$otoportal); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			

echo $echo; // XML OUTPUT.





}

?>