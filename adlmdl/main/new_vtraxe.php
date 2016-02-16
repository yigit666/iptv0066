<?php
//tengildet @ turkportal.org, please ask me to use freely :)
include "../../a_cfg_b/cfg.php"; 
$site  = "http://vtraxe.com"; // WEB ADDRESS OFWEBPAGE.
$pic = "http://vtraxe.com/templates/vtraxe/images/logo.png"; // LOGO OF WEBSITE.
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
	$echo .= playlist('YENİ EKLENENLER',$pic,$desc,$script.'?films='.urlencode($site).'&before='.urlencode($script.'&xml'),'playlist');// ADD 'NEW ADDED' CAT TO XML AS PLAYLIST.
	foreach ($cats[1] as $cat){ // FOREACH LOOP TO PROCESS ELEMENTS OF CATS ARRAY .
		$name = strtoupper(substr(str_replace('-',' ',$cat),1)); // REARRANGE CAT NAMES.
		$catlink= $site.$cat;  // CAT LINKS CORRECTION.
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
	$page=cek(urldecode($_GET['films']));
	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.
///////////////////////PAGINATION//////////////////////////////////////////////////////////////////////
preg_match('/pagess(.*)dle-info/', $page, $subpagina);
preg_match_all("/<a href=\"(.*?)\">(\d*)<\/a>/", $subpagina[1], $pages);
///////////////////////////////////////////////////////////////////////////////////////////////////////
	preg_match("/dle-content(.*?)dle-info/", $page, $subpage);//NARROVING THE DATA TO GET MORE ACCURATE RESULTS FOR THE FOLLOWING REGEX.
	preg_match_all("/<a href=\"(.*?)\"><div.*?><img alt=.*?src=\"(.*?)\".*?>(.*?)<\/div><\/a>/", $subpage[1], $films);// REGEX FOR CATS.
	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.
	foreach ($films[1] as $key => $film){ // FOREACH LOOP TO PROCESS ELEMENTS OF FILMS ARRAY.
		$name = strtoupper(substr(str_replace('-',' ',$film),29,-5)); // REARRANGE FILM NAMES.
		$filmlink= $film;  // FILM LINKS.
		//$desc = $films[3][$key]; // FILM DESCRIPTION.
		$pic = $films[2][$key]; // FILM PICTURE.
		if (strpos($films[2][$key],'html')==false){
			if (strpos($films[2][$key],'http')===false){$pic=$site.$films[2][$key];}
			$before = '&before='.urlencode($script.'?films='.$_GET['films'].'&xml'); // RETURN URL TO FILMS
			$nexturl = $script.'?filmparts='.urlencode($filmlink); // LINK TO FILMPARTS
			$namedirect = '&name='.urlencode($name); // DIRECTED FILM NAME TO FILMPARTS
			$echo .= playlist($name,$pic,$descmain,$nexturl.$before.$namedirect,'playlist');// ADD ALL FILM TO XML AS PLAYLIST.
		}
	}

	foreach($pages[1] as $key => $pa){
		//if ($key<10){
			$name='Sayfa-'.$pages[2][$key];
			$before = '&before='.urlencode($script.'?films='.$_GET['films'].'&xml'); // RETURN URL TO FILMS FROM PAGES
			$nexturl = $script."?films=".urlencode($pages[1][$key]).'&xml';
			$namedirect = '&name='.urlencode($name);
			$echo .= playlist($name,$pic,$descmain,$nexturl.$before.$namedirect,'playlist');// ADD NEXT AND PREV PAGES TO XML AS PLAYLIST.
		//}
	}
$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$descmain,$script.'?xml','playlist');
$echo.=rabbit($descmain,$rabbit); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			
echo $echo; // XML OUTPUT.
}
//***********************************************FILMPARTS********************************************************
if (isset($_GET['filmparts'])){
	$frontpage=cek(urldecode($_GET['filmparts']));
	preg_match("/<iframe.*?src=\"(.*?)\" frameborder/", $frontpage, $prepage);
	$page=$prepage[1];
	$page=cek($page);
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