<?php
header("Content-type: text/xml");
$server="http://$_SERVER[HTTP_HOST]/";
$mdldzn=$server."adlmdl/mdl/";
$script = "http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']; // THIS SCRIPT ADDRESS.
$otoportal ='';
$rabbit='';
$echo='';
//**************************************************************
$bunu=array('izle', '&#8211;','&#8217;','ğ','ı','ö','ü','ş','ç','i','-');
$degistir=array('', ':' ,"'",'Ğ','I','Ö','Ü','Ş','Ç','İ',' ');
$vkbunu=array('&amp;','&#038;');
$vkdegistir=array('&','&');
$desc1='turkportal.org Buluşma Noktamız...';
//**************************************************************
$prevpic='';
$nextpic='';
$prevmenupic='';
////////////////////////////////////////////////////////////////////////////////
//***************************************************************************************************************************************************************
//XML basligi ekleyen fonksyon
function xmlbaslik($name){
$echo='<?xml version="1.0" encoding="utf-8" ?>
	<items>
	<playlist_name><![CDATA['.$name.']]></playlist_name>
	';
return $echo;
}
//*****************************************************************************************************
//Rabbit INN e donme ekleme fonksyonu
function rabbit($desc='turkportal.org Buluşma Noktamız...',$rabbit=''){
$echo.=playlist("Rabbit INN","",$desc,$rabbit,"playlist");
$echo.='
	</items>';
return $echo;
}
//anamenuye donme ekleme fonksyonu
function anamenudon($desc='turkportal.org Buluşma Noktamız...',$otoportal='buraya otoportal adresini yazın'){
$echo.=playlist("WE{b}Ot","buraya otoportl resmini yazın",$desc,$otoportal,"playlist");
$echo.='
	</items>';
return $echo;
}
//playlist ekleyen fonksyon
function playlist($name,$pic,$desc,$link,$type,$protect="None"){
$pro ="";
if ((strtolower($protect)=="false") or (strtolower($protect)=="true")){$pro='<protected>'.$protect.'</protected>';}
if (strtolower($protect)=="arama"){$pro='<search_on text="Search on">search</search_on>';}

$echo='
	<channel>
	<title><![CDATA['.strtoupper($name).']]></title>
	<logo_30x30><![CDATA['.$pic.']]></logo_30x30>
	<description><![CDATA[<center><table  border="1"><tr><td   align= "center"><img src="'.$pic.'"height="240" width="180"/></td></tr><tr><td style="vertical-align: top">'.$desc.'</td></tr></table><br>]]></description>
	<'.$type.'_url><![CDATA['.$link.']]></'.$type.'_url>'.$pro.'
	</channel>
	';
return $echo;
}
//smart icin arama ekleyen fonksyon
function smartarama($name,$pic,$desc,$link,$type){
return playlist($name,$pic,$desc,$link,$type,"Arama");
}
//nextprev ekleyen fonksyon
function nextprev($nextname,$nextlink,$prevname,$prevlink){
$echo='
    <prev_page_url text="'.$prevname.'"><![CDATA['.$prevlink.']]></prev_page_url>
    <next_page_url text="'.$nextname.'"><![CDATA['.$nextlink.']]></next_page_url> 
	';
return $echo;
}
//url ceken fonksiyon
function cek($link, $curlheader="None") {
$curl = curl_init(); 
curl_setopt($curl, CURLOPT_URL, $link);  
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
if ($curlheader=="None"){
	curl_setopt( $curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.22 (KHTML, like Gecko)" );
	curl_setopt($curl, CURLOPT_HEADER, 0);
}
if ($curlheader=="main"){
	curl_setopt( $curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; seyirTURKVOD WOW64) AppleWebKit/537.22 (KHTML, like Gecko)" );
	curl_setopt($curl, CURLOPT_HEADER, 1);
}
$cookie_file = "cookie1.txt";
curl_setopt($curl, CURLOPT_COOKIESESSION, 1);
curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_file);
curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_file); 
$str = curl_exec($curl);  
curl_close($curl); 
//$str=iconv("UTF-8", "ISO-8859-1//TRANSLIT", $stro);
return $str;
 } 

//****************************************************************************************************
function curl_get_contents($url)
{
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.22 (KHTML, like Gecko)" );
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}
//***************************************************************************
//redirected page sayfaları bulan fonksyon
function izle($url){
$a=get_headers($url,1);
$location=$a["Location"];
	if ($location<>''){
		$url=$location;
		izle($url);
}
return $url;
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function bak($url){
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_exec($ch);
$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
// $retcode >= 400 -> not found, $retcode = 200, found.
curl_close($ch);
return $retcode;
}
?>