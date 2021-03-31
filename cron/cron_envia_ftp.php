<?php
//El Script debe ir donde se encuentra el archivo a ENVIAR, es decir en el SERVER ORIGEN
$nombreArchivoLocal = 'prueba.txt';  //Archivo local a ENVIAR
$nombreArchivoRemoto = 'prueba_envio.txt'; //Nombre con el que se grabará remotamente, por si desean cambiarle
$directorio_remoto="RESPALDOS_BBDD/";

// Conexion Basica remota del servidor remoto, es decir, donde se va a colocar el archivo
require("../funciones/conexion_ftp_Tchile.php");

 echo "El directorio actual es: " . ftp_pwd($conexion_ftp) . "<br>";
ftp_chdir($conexion_ftp, $directorio_remoto);
// subir Archivo al servidor Remoto
if (ftp_put($conexion_ftp, $nombreArchivoRemoto, $nombreArchivoLocal, FTP_BINARY)) {
 echo "Enviado Correctamente <br>";
} else {
 echo "Problemas con la subida <br>";
}

ftp_close($conexion_ftp);
?>