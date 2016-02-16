<?php

//tengildet @ turkportal.org, please ask me to use freely :)

include "../../a_cfg_b/cfg.php"; 

$site  = "http://www.yabancidiziizle1.com"; // WEB ADDRESS OFWEBPAGE.

$pic = "http://b.webutation.net/b/5/yabancidiziizle.com.jpg"; // LOGO OF WEBSITE.

$referer =  $site.'/';

//echo $script;

//***********************************************CAT'S********************************************************

if ((!isset($_GET['films'])) and (!isset($_GET['filmparts'])) and (!isset($_GET['filmalts'])) and (!isset($_GET['film']))){   // IF CONDITION TO DECIDE FETCH TO MAIN WEBPAGE.

	$page = cek($site);     //FETCING WEBPAGE USING CURL DEFINED IN CONFIG.PHP.

//echo $page;

	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.

	preg_match("/DİZİ LİSTESİ(.*?)sag-alt/", $page, $subpage);//NARROVING THE DATA TO GET MORE ACCURATE RESULTS FOR THE FOLLOWING REGEX.

//print_r($subpage[1]);

	preg_match_all("/href=\"(.*?)\".*?title.*?\">(.*?)<\/a>/", $subpage[1], $cats);// REGEX FOR CATS.

//print_r($cats);	//DEBUGING.

	$echo .= xmlbaslik($site); // ADDS XML HEADERS TO XML.

	$echo .= playlist('YENİ EKLENENLER',$pic,$desc,$script.'?filmparts='.$site.'&before='.urlencode($script).'&xml','playlist');// ADD 'NEW ADDED' CAT TO XML AS PLAYLIST.

	$echo .= playlist('EN ÇOK İZLENENLER',$pic,$desc,$script.'?filmparts='.urlencode($referer.'en-cok-izlenen-diziler').'&before='.urlencode($script).'&xml','playlist');// ADD 'NEW ADDED' CAT TO XML AS PLAYLIST.



	foreach ($cats[1] as $key => $cat){ // FOREACH LOOP TO PROCESS ELEMENTS OF CATS ARRAY .

		$name = strtoupper(str_replace($bunu,$degistir,$cats[2][$key])); // REARRANGE CAT NAMES.

		$catlink= $cat;  // CAT LINKS CORRECTION.

		$before = '&before='.urlencode($script.'&xml'); // RETURN URL TO CATS-this line defins self bölüm

		$nexturl = $script.'?films='.urlencode($catlink); // LINK TO FILMS

		$namedirect = '&name='.urlencode($name); // DIRECTED NAME TO FILMS 	

	if ((strpos($name, 'FRAGMAN')===False) and (strpos($name, 'DİĞER')===False)){

		$echo .= playlist($name,$pic,$desc,$nexturl.$before.$namedirect.'&xml','playlist');// ADD ALL CAT TO XML AS PLAYLIST.

	}

	}

$echo.=anamenudon($descmain,$otoportal); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			

echo $echo; // XML OUTPUT.

}

//***********************************************FILMS********************************************************

if (isset($_GET['films'])){

	$page=cek($site.urldecode($_GET['films']));
	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.

//echo $page;

///////////////////////PAGINATION//////////////////////////////////////////////////////////////////////

preg_match('/rel=\'prev\' href=\'([^\']*)\' \/>/', $page, $prev);

preg_match('/rel=\'next\' href=\'([^\']*)\' \/>/', $page, $next);

///////////////////////////////////////////////////////////////////////////////////////////////////////

	preg_match("/\"content\">(.*?)\"right\"/",$page,$subpage);

	preg_match_all("/<a href=\"([^\"]*)\".*?title=\"([^\"]*)\".*?class=\"img\"><img src=\"([^\"]*)\"/", $subpage[1], $films);// REGEX FOR CATS.

//print_r($subpage[1]);



	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.

	foreach ($films[1] as $key => $film){ // FOREACH LOOP TO PROCESS ELEMENTS OF FILMS ARRAY.

		$name = strtoupper(str_replace($bunu,$degistir,$films[2][$key])); // REARRANGE FILM NAMES.

		$filmlink= $film;  // FILM LINKS.

		$pic = $site.$films[3][$key]; // FILM PICTURE.

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

//	preg_match("/vid-alt(.*?)\"right\"/",$page,$subpage);
	preg_match("/\"main\"(.*?)\"right\"/",$page,$subpage);

	preg_match_all("/<a href=\"([^\"]*)\" title=\"([^\"]*)\" class=\"img\"><img src=\"([^\"]*)\"/", $subpage[1], $filmparts);// REGEX FOR CATS.

	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.

	foreach ($filmparts[1] as $key => $filmpart){

		$name=$filmparts[2][$key];
		$pic=$site.$filmparts[3][$key];

		$before = '&before='.urlencode($script.'?filmparts='.$_GET['filmparts'].'&xml'); // RETURN URL TO FILMS

		$nexturl = $script.'?filmalts='.urlencode($site.$filmpart); // LINK TO FILMPARTS

		$namedirect = '&name='.urlencode($_GET['name'].' | '.$name); // DIRECTED FILM NAME TO FILMPARTS



		$echo.=playlist($name,$pic,$desc,$nexturl.$before.$namedirect.'&xml','playlist');

	}

		

$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$descmain,urldecode($_GET['before']).'&xml','playlist');

$echo.=anamenudon($descmain,$otoportal); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			

echo $echo; // XML OUTPUT.

}

//***********************************************FILMALTS********************************************************

if (isset($_GET['filmalts'])){

	$page=cek(urldecode($_GET['filmalts']));

	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.

	preg_match("/Favorilere Ekle(.*?)faceizle/",$page,$subpage);

	preg_match_all("/href=\"([^\"]*)\" title=\"([^\"]*)\">(.*?)<\/a>/",$subpage[1],$filmalts);



	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.

	foreach ($filmalts[1] as $key => $filmalt){

		$name=$filmalts[3][$key];

		//$before = '&before='.urlencode($script.'?filmalts='.$_GET['filmalts'].'&xml'); // RETURN URL TO FILMS

		$nexturl = $script.'?film='.urlencode($filmalt); // LINK TO FILMPARTS

		$namedirect = '&name='.urlencode($name); // DIRECTED FILM NAME TO FILMPARTS

		$desc = $filmalts[2][$key];

		$echo.=playlist($name,$pic,$desc,$nexturl.$before.$namedirect.'&xml','playlist');

	}

		

$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$descmain,urldecode($_GET['before']).'&xml','playlist');

$echo.=anamenudon($descmain,$otoportal); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			

echo $echo; // XML OUTPUT.

}

//***********************************************FILM********************************************************

if (isset($_GET['film'])){

	$page=cek($site.urldecode($_GET['film']));

	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.

	preg_match("/value\=\"movieSrc=(.*?)&/",$page,$mailru);

	$streamlink='http://api.video.mail.ru/videos/embed/'.$mailru[1].'.html';

	if ($mailru[1]==''){

		preg_match("/<iframe src=\"http:\/\/vk\.com(.*?)\"/",$page,$vk);

		$streamlink = 'http://vk.com'.$vk[1];

	}



	if (($mailru[1]=='') and ($vk[1]=='')){

		preg_match("/streamer: \"(.*?)\"/",$page,$streamer);
		if ($streamer[1]<>''){

		$streamlink = $site.urldecode($_GET['film']).'?yabancidiziizle.com';
}
}



	if (($mailru[1]=='') and ($vk[1]=='') and ($streamer[1]=='')){

		preg_match("/http:\/\/v\.kiwi\.kz(.*?)\"/",$page,$kiwi);

		//$page = cek('http://v.kiwi.kz/v2/4fjgta9g4kwl/');

		$page1 = cek('http://v.kiwi.kz'.$kiwi[1]);

		//echo $page;

		preg_match ("/url=(.*?)%26/",$page1,$link);

		$page2 = cek(urldecode($link[1]));

		preg_match("/Location:(.*?==)/",$page2,$streaml);

		$streamlink = $streaml[1];

	}



	            



	//preg_match_all("/<a href=\"([^\"]*)\" title=\"([^\"]*)\" class=\"img\"><img src=\"([^\"]*)\"/", $subpage[1], $filmparts);// REGEX FOR CATS.

	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.

	//foreach ($filmparts[1] as $key => $filmpart){

		$name='İZLE';

	//	$pic=$site.$filmparts[3];

	//	$before = '&before='.urlencode($script.'?filmparts='.$_GET['filmparts'].'&xml'); // RETURN URL TO FILMS

	//	$nexturl = $script.'?filmparts='.urlencode($site.$filmpart); // LINK TO FILMPARTS

	//	$namedirect = '&name='.urlencode($_GET['name'].' | '.$name); // DIRECTED FILM NAME TO FILMPARTS

//

		$echo.=playlist($name,$pic,$desc,$streamlink,'stream');

//	}

		

$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$descmain,urldecode($_GET['before']).'&xml','playlist');

$echo.=anamenudon($descmain,$otoportal); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			

echo $echo; // XML OUTPUT.

}

?>