<?php
/*
 *  código para obtener la dirección IP del usuario
 */
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
/*
 * código para obtener detalles de la ubicación de los usuarios utilizando esta dirección IP
 * 
*/
//$user_ip = $ip;
$user_ip = '192.168.1.17'; 
$array_user_ip = explode(".", $user_ip);
for($i=0;$i<count($array_user_ip);$i++){
	echo $array_user_ip[$i]."<br>";
}
if(intval($array_user_ip[0]) == 192 && intval($array_user_ip[1]) == 168 && intval($array_user_ip[2]) == 1){
	$user_ip = '181.43.150.90';
}

$url = "http://ipinfo.io/".$user_ip;
$ip_info = json_decode(file_get_contents($url));
 
$ip = $ip_info->ip;
$host = $ip_info->hostname;
$city = $ip_info->city;
$region = $ip_info->region;
$country = $ip_info->country;
$loc = $ip_info->loc;
$loc_array = explode(',',$loc);
$lat = $loc_array[0];
$long = $loc_array[1];
$org = $ip_info->org;
$postal = $ip_info->postal;
 
		echo '<strong>Dirección IP   </strong>'.$ip.'<br>';
		echo '<strong>Host Name   </strong>'.$host.'<br>';
		echo '<strong>Ciudad    </strong>'.$city.'<br>';
		echo '<strong>Region    </strong>'.$region.'<br>';
		echo '<strong>Codigo País  </strong>'.$country.'<br>';
		echo '<strong>Localización   </strong>'.'Lat'.$lat.''.'Long'.$long.'<br>';
		echo '<strong>Org   </strong>'.$org.'<br>';
		echo '<strong>Portal Code    </strong>'.$postal.'<br>';
?>