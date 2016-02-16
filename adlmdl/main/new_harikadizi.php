<?php







//tengildet @ turkportal.org, please ask me to use freely :)







include "../../a_cfg_b/cfg.php"; 







$site  = "http://www.harika-dizi.net"; // WEB ADDRESS OFWEBPAGE.







$pic = "http://www.harika-dizi.com/wp-content/themes/vidiiv6/images/logo.png"; // LOGO OF WEBSITE.







$referer =  $site.'/';







//echo $script;







//***********************************************CAT'S********************************************************







if ((!isset($_GET['films'])) and (!isset($_GET['filmparts'])) and (!isset($_GET['altcat']))){   // IF CONDITION TO DECIDE FETCH TO MAIN WEBPAGE.

		$loc = $script.'?altcat='.urlencode("http://www.harika-dizi.net/category/yerli-diziler");
		header('Location: '.$loc);





}







//***********************************************ALTCAT*******************************************************







if (isset($_GET['altcat'])){







	$page=cek(urldecode($_GET['altcat']));



	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.



	preg_match("/694(.*?)footer/", $page, $subpage);//NARROVING THE DATA TO GET MORE ACCURATE RESULTS FOR THE FOLLOWING REGEX.



	preg_match_all("/href=(.*?)\/>.*?url\((.*?)\).*?liste\">(.*?)<\/div>/", $subpage[1], $altcats);// REGEX FOR CATS.







//print_r($altcats);	//DEBUGING.







	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.














	foreach ($altcats[1] as $key => $altcat){ // FOREACH LOOP TO PROCESS ELEMENTS OF CATS ARRAY .







		$name = strtoupper(str_replace($bunu, $degistir,$altcats[3][$key])); // REARRANGE CAT NAMES.







		$altcatlink= $altcat;  // CAT LINKS CORRECTION.







		$before = '&before='.urlencode($script.'&xml'); // RETURN URL TO CATS







		$nexturl = $script.'?films='.urlencode($altcatlink); // LINK TO FILMPS







		$namedirect = '&name='.urlencode($name); // DIRECTED NAME TO FILMS 		



		$pic=$cats[2][$key];







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







//preg_match('/pagess(.*)dle-info/', $page, $subpagina);







preg_match("/href=\'([^\']*)\' class=\'nextpostslink\'>/", $page, $next);



preg_match("/href=\'([^\']*)\' class=\'previouspostslink\'>/", $page, $prev);







///////////////////////////////////////////////////////////////////////////////////////////////////////







	preg_match("/ortablokorta\"(.*?)ortablokalt/", $page, $subpage);//NARROVING THE DATA TO GET MORE ACCURATE RESULTS FOR THE FOLLOWING REGEX.







	preg_match_all("/<img src=\"([^\"]*)\".*?\/><\/a>.*?href=\"([^\"]*)\".*?title=\"([^\"]*)\">/", $page, $films);// REGEX FOR CATS.



//print_r($subpage);



	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.







	foreach ($films[3] as $key => $film){ // FOREACH LOOP TO PROCESS ELEMENTS OF FILMS ARRAY.







		$name = strtoupper(str_replace($bunu, $degistir,$film)); // REARRANGE CAT NAMES.







		$filmlink= $films[2][$key];  // FILM LINKS.







		$pic = $films[1][$key]; // FILM PICTURE.






			$before = '&before='.urlencode($script.'?films='.$_GET['films'].'&xml'); // RETURN URL TO FILMS







			$nexturl = $script.'?filmparts='.urlencode($filmlink); // LINK TO FILMPARTS







			$namedirect = '&name='.urlencode($name); // DIRECTED FILM NAME TO FILMPARTS







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



	$page = preg_replace('~[\r\n\t]+~', '', $page); //REMOVE ALL TABS AND LINE FEEDS FROM FETCHED STRINGS TO GET BEST RESULTS.











		preg_match("/iframe.*?src=\"http:\/\/vk.com(.*?)\".*?<\/iframe>/",$page,$vk); 



		preg_match("/iframe.*?src=\"http:\/\/www\.youtube\.com\/embed\/(.*?)\".*?<\/iframe>/",$page,$yt); 



		preg_match("/iframe.*?src=\"http:\/\/www\.harikadizi\.net(.*?)\".*?<\/iframe>/",$page,$diger); 



		preg_match("/<iframe.*?src=\"http:\/\/api\.video\.mail\.ru(.*?)\"/",$page,$mailru); 



		if ($mailru[1]<>''){



					$t='http://api.video.mail.ru'.str_replace($vkbunu,$vkdegistir,$mailru[1]);



					$name='MAIL.RU TEK PARÇA';



					$pic='adres mailru.png';



			}



		if ($diger[1]<>''){



				$dizianasayfa5=cek('http://www.harikadizi.net'.$diger[1]);



				preg_match("/%3D(.*?)&/",$dizianasayfa5,$other); 



				$yt=$other;



			}



		if (($vk[1]<>'')) {



					$t='http://vk.com'.str_replace($vkbunu,$vkdegistir,$vk[1]);



					$name='VK TEK PARÇA';



					$pic='adres vk.png';



		}



		



		if ($yt[1]<>''){



					$t='http://www.youtube.com/embed/'.$yt[1];



					$name='YOUTUBE TEK PARÇA';



					$pic='adres youtube1.jpg';



		}















	$echo .= xmlbaslik(urldecode($_GET['name'])); // ADDS XML HEADERS TO XML.











		$name = strtoupper(urldecode($_GET['name'])); //  FILM NAMES.







		$echo .= playlist($name,$pic,$descmain,$t,'stream');// ADD ALL FILM TO XML AS PLAYLIST.







$echo.=playlist('ÖNCEKİ MENÜ',$prevmenupic,$descmain,$script.'?xml','playlist');







$echo.=anamenudon($descmain,$otoportal); // ADDING A LINK TO RETURN MAIN XML TO XML and CLOSE ITEMS TAG			







echo $echo; // XML OUTPUT.







}







?>