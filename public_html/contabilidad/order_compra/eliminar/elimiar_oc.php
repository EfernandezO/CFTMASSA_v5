<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//
//revisar id correctamente
if(isset($_GET["id_oc"]))
{
	$OC_id=base64_decode($_GET["id_oc"]);
	if(is_numeric($OC_id)){$continuar=true;}
	else{ $continuar=false;}
}
else
{ $continuar=false;}
//------------------------------------------------//
if($continuar)
{
	$error="debug";
	require("../../../../funciones/conexion_v2.php");
		$cons_D="DELETE FROM orden_compra WHERE id_oc='$OC_id' LIMIT 1";
		if(DEBUG){ echo"ELIMINAR: $cons_D<br>";}
		else
		{
			if($conexion_mysqli->query($cons_D))
			{ $error="OC_E0";}
			else
			{ $error="OC_E1";}
		}
	mysql_close($conexion);
	$conexion_mysqli->close();
	$url="../revision/revisar.php?error=$error";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
else
{
	if(DEBUG){ echo"NO continuar<br>";}
	else{ header("location: ../revision/revisar.php");}
}
?>