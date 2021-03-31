<?php
$ftp_server="cftmass.cl";
$ftp_user_name= "cft414cl";
$ftp_user_pass= "rectoriax7";

$conexion_ftp = ftp_connect($ftp_server);
$login_result = ftp_login($conexion_ftp, $ftp_user_name, $ftp_user_pass);

ftp_pasv ($conexion_ftp, true) ;
?>