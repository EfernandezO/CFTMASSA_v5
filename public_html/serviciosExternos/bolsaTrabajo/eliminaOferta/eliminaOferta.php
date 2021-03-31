<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("bolsaTrabajoV1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

$continuar=false;
if($_GET)
{
	$id=0;
	if(isset($_GET["id"])){$id=$_GET["id"];}
	if($id>0){$continuar=true;}
}

$error="BT";
if($continuar){
	require("../../../../funciones/conexion_v2.php");
		
		$consD="DELETE from bolsaTrabajo WHERE id='$id' LIMIT 1";
		if(DEBUG){ echo"$consD<br>";}
		else{
			if($conexion_mysqli->query($consD)){$error="BT0";}
			else{ $error="BT1";}
		}
	$conexion_mysqli->close();
}

$url="../gestionOfertas.php?error=$error";

if(DEBUG){ echo"URL: $url<br>";}
else{header("location: $url");}
?>