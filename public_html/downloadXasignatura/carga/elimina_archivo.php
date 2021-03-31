<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
$continuar=true;
$error="debug";
if($_GET)
{$id_archivo=$_GET["id_recurso"];}
else
{$id_archivo="";}

if(!is_numeric($id_archivo)){ $continuar=false;}


if($continuar)
{
	$url="index.php";
	if(DEBUG){ echo"datos validos<br>";}
		include("../../../funciones/conexion_v2.php");
		$path="../../CONTENEDOR_GLOBAL/cargaXasignatura/";
		
		$cons="SELECT archivo FROM contenedor_archivos WHERE id='$id_archivo' LIMIT 1";
		$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$D=$sql->fetch_assoc();
			$nombre_archivo=$D["archivo"];
		$sql->free();	
		if(DEBUG){ echo"ARCHIVO: $nombre_archivo<br>";}
		if(!empty($nombre_archivo))
		{
			$ruta_archivo=$path.$nombre_archivo;
			if(DEBUG){ echo"BORRAR: $ruta_archivo<br>";}
			else{
					if(unlink($ruta_archivo))
					{ $error="5";}
					else
					{ $error="4";}
				}
		}
		$CONS_DEL="DELETE FROM contenedor_archivos WHERE id='$id_archivo' LIMIT 1";
		if(DEBUG){ echo"-->$CONS_DEL<br>";}
		else{ $conexion_mysqli->query($CONS_DEL);}
	$conexion_mysqli->close();
	
	$url.="?error=$error";
	if(DEBUG){ echo"URL: $url<br>";}
	else{header("location: $url");}
}
else
{
	if(DEBUG){ echo"datos invalidos<br>";}
	else{header("location: index.php");}
}
?>