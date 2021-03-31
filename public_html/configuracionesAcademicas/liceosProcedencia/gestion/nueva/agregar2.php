
<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(false);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->LiceosProcedencia_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
if($_POST)
{
	require("../../../../../funciones/conexion_v2.php");
	if(DEBUG){ var_export($_POST);}
	
		$fecha_actual=date("Y-m-d");
		$id_usuario_actual=$_SESSION["USUARIO"]["id"];	
		
		$region=mysqli_real_escape_string($conexion_mysqli, $_POST["region"]);
		$comuna=mysqli_real_escape_string($conexion_mysqli,$_POST["comuna"]);
		$comuna=strtoupper($comuna);
		$nombreEstablecimiento=mysqli_real_escape_string($conexion_mysqli,$_POST["nombreEstablecimiento"]);
		$nombreEstablecimiento=strtoupper($nombreEstablecimiento);
		$dependencia=mysqli_real_escape_string($conexion_mysqli,$_POST["dependencia"]);
		$rbd=mysqli_real_escape_string($conexion_mysqli,$_POST["rbd"]);
		
	
	$error="";
	
		
		$campos="region, comuna, nombreEstablecimiento, dependencia, rbd";
		$valores="'$region', '$comuna', '$nombreEstablecimiento', '$dependencia', '$rbd'";
		$cons_IN="INSERT INTO liceos ($campos) VALUES ($valores)";
		if(DEBUG){ echo"<br>-->$cons_IN<br>";}
		else
		{$conexion_mysqli->query($cons_IN) or die("INSERTAR: ".$conexion_mysqli->error);}
		$url="../index.php?error=R2";
	
	$conexion_mysqli->close();
	
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
else
{ header("location: ../index.php");}
/////////////////////////////////////////////
?>