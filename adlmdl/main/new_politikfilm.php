<?php

//tengildet @ turkportal.org, please ask me to use freely :)

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);



include "../../a_cfg_b/cfg.php"; 

$site  = "http://politikfilm.net"; // WEB ADDRESS OFWEBPAGE.

$pic = "http://politikfilm.net/templates/portalfilm/images/logo.png"; // LOGO OF WEBSITE.

$referer =  $site.'/';

//echo $script;

$dosya="new_politikfilm.php";

//***********************************************CAT'S********************************************************

if ((!isset($_GET['films'])) and (!isset($_GET['filmparts']))){   // IF CONDITION TO DECIDE FETCH TO MAIN WEBPAGE.

	$page = cek($site);     //FETCING WEBPAGE USING CURL DEFINED IN CONFIG.PHP.

//echo $page;

	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.

	preg_match("/Film Men(.*?)Son Yorumlar/", $page, $subpage);//NARROVING THE DATA TO GET MORE ACCURATE RESULTS FOR THE FOLLOWING REGEX.
	//preg_match_all("/href=\"\/(.*?)\">(.*?)<\/a>/", $subpage[1], $cats);// REGEX FOR CATS.

	preg_match_all("/<a title.*?href=\"([^\"]*)\">(.*?)<\/a>/", $subpage[1], $cats);// REGEX FOR CATS.

//print_r($cats);	//DEBUGING.

	$echo .= xmlbaslik($site); // ADDS XML HEADERS TO XML.
	$echo.=playlist('YENİ EKLENENLER',$pic,$desc1,$mdldzn.$dosya.'?films='.$site.'/&xml','playlist');

	foreach ($cats[1] as $key => $cat){ // FOREACH LOOP TO PROCESS ELEMENTS OF CATS ARRAY .

		$name = strtoupper(str_replace($bunu,$degistir,str_replace("Seçiniz","YENİ EKLENENLER",$cats[2][$key]))); // REARRANGE CAT NAMES.

		$catlink= $site.$cat;  // CAT LINKS CORRECTION.

		$before = '&before='.urlencode($script); // RETURN URL TO CATS

		$nexturl = $script.'?films='.urlencode($catlink); // LINK TO FILMPS

		$namedirect = '&name='.urlencode($name).'&xml'; // DIRECTED NAME TO FILMS 		

		$echo .= playlist($name,$pic,$desc,$nexturl.$before.$namedirect,'playlist');// ADD ALL CAT TO XML AS PLAYLIST.

	}

$echo.=anamenudon($descmain,$otoportal); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			

echo $echo; // XML OUTPUT.

}

//***********************************************FILMS********************************************************

if (isset($_GET['films'])){

$ciplak = izle($_GET['films']);
if (substr( $ciplak, 0, 4 )<> "http"){$ciplak=$site.$ciplak;}

	$page=cek(urldecode($ciplak));

	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.

///////////////////////PAGINATION//////////////////////////////////////////////////////////////////////

//preg_match('/bnnavi(.*)dle-info/', $page, $subpagina);

//preg_match_all("/<a href=\"(.*?)\">(\d*)<\/a>/", $subpagina[1], $pages);

preg_match('/<a href=\"([^\"]*)\"><span.*?class=\"thide pprev nextprev\">(.*?)<\/span>/', $page, $prev);

preg_match('/<a href=\"([^\"]*)\"><span.*?class=\"thide pnext nextprev\">(.*?)<\/span>/', $page, $next);

///////////////////////////////////////////////////////////////////////////////////////////////////////

	preg_match("/dle-content(.*?)<div class=\"content-right\"> /", $page, $subpage);//NARROVING THE DATA TO GET MORE ACCURATE RESULTS FOR THE FOLLOWING REGEX.

	//preg_match_all("/gi\">.*?<a href=\"(.*?)\" title=\"(.*?)\"><img src=\"(.*?)\".*?<p>(.*?)<\/p>.*?İmdb Puanı:(.*?)<br>/", $subpage[1], $films);// REGEX FOR CATS.

preg_match_all("/<h2><a href=\"([^\"]*)\">(.*?)<\/a><\/h2>.*?<img src=\"([^\"]*)\".*?display:inline;\">(.*?)<br.*?imdb:(.*?)<a href/", $subpage[1], $films);// REGEX FOR CATS.




//print_r($films);

	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.

	foreach ($films[1] as $key => $film){ // FOREACH LOOP TO PROCESS ELEMENTS OF FILMS ARRAY.

		$name = strtoupper(str_replace($bunu,$degistir,$films[2][$key])); // REARRANGE FILM NAMES.

		$filmlink= $film;  // FILM LINKS.

		$pic = $films[3][$key]; // FILM PICTURE.

		$imdb = $films[5][$key]; // FILM IMDB RATING.

		$i=(int)$imdb;

		$rating='<center><img src="rating resim öneki'.$i.'.png" height="30" width="180"/></center>';

		$desc = 'Konu: '.$films[4][$key]; // FILM DESCRIPTION.



			if (strpos($films[1][$key],'http')===false){$pic=$site.$films[1][$key];}

			$before = '&before='.urlencode($script.'?films='.$_GET['films']); // RETURN URL TO FILMS

			$nexturl = $script.'?filmparts='.urlencode($filmlink); // LINK TO FILMPARTS

			$namedirect = '&name='.urlencode($_GET['name'].' | '.$name).'&xml'; // DIRECTED FILM NAME TO FILMPARTS

			$echo .= playlist($name,$pic,$desc,$nexturl.$before.$namedirect,'playlist');// ADD ALL FILM TO XML AS PLAYLIST.

	}



			if (strlen($next[1])<>0){

			$name='Sonraki Sayfa';
			$pic=$nextpic;
			$before = '&before='.urlencode($script.'?films='.$_GET['films'].'&xml'); // RETURN URL TO FILMS FROM PAGES

			$nexturl = $script."?films=".urlencode($next[1]);

			$namedirect = '&name='.urlencode($name).'&xml';

			$echo .= playlist($name,$pic,$descmain,$nexturl.$before.$namedirect,'playlist');// ADD NEXT AND PREV PAGES TO XML AS PLAYLIST.

		}

			if (strlen($prev[1])<>0){
			$pic=$prevpic;
			$name='Önceki Sayfa';

			$before = '&before='.urlencode($script.'?films='.$_GET['films'].'&xml'); // RETURN URL TO FILMS FROM PAGES

			$nexturl = $script."?films=".urlencode($prev[1]);

			$namedirect = '&name='.urlencode($name).'&xml';

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

	preg_match_all("/media_begin:(.*?)-/",$page,$filmparts);

	preg_match("/netmen:(.*?)<\/p>/",$page,$yonetmen);

	preg_match("/lke:(.*?)<\/p>/",$page,$ulke);

	preg_match("/imdb(.*?)<\/p>/",$page,$imdb);

	preg_match("/Tarihi:(.*?)<\/p>/",$page,$tarih);

	preg_match("/re:(.*?)<\/p>/",$page,$sure);

	preg_match("/<p>Senaryo:(.*?)<\/p>/",$page,$senaryo);

	preg_match("/<p>Yapımcı:(.*?)<\/p>/",$page,$yapimci);

	preg_match("/<p>Oyuncular:(.*?)<\/p>/",$page,$oyuncular);

	$desc = '<font color="orange">IMDB: </font>'.$imdb[1].'<br><font color="orange">Vizyon Tarihi: </font>'.$tarih[1].'<br><font color="orange">Yönetmen: </font>'.$yonetmen[1].'<br><font color="orange">Ülke: </font>'.$ulke[1].'<br><font color="orange">Senaryo: </font>'.$senaryo[1].'<br><font color="orange">Yapımcı: </font>'.$yapimci[1].'<br><font color="orange">Oyuncular: </font>'.$oyuncular[1];

	if ($filmparts[1][0]==''){

		preg_match_all("/<iframe.*?src=\"([^\"]*)\"/",$page,$filmparts);

	}

       preg_match('/img style=.*?src=\"(.*?)\" alt=/',$page, $pics); 

	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.

	$pic = $pics[1]; // FILM PICTURE.

	foreach($filmparts[1] as $key => $film){

		if ($key==0){$name='İZLE';}

		else { $name=($key+1).' . PARÇA';}

		$link=str_replace($vkbunu,$vkdegistir,$filmparts[1][$key]);

			if (substr($link,7,4)<>'www6'){

				$echo.=playlist($name,$pic,$desc,$link,'stream');

			}

		}

		

$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$descmain,urldecode($_GET['before']).'&xml','playlist');

$echo.=anamenudon($descmain,$otoportal); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			

echo $echo; // XML OUTPUT.





}

?>