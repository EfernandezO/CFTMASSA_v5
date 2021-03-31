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
if($_POST)
{
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/VX.php");
	if(DEBUG){ var_dump($_POST);}
	$id_oc=$_POST["oc_id"];
	
	$autorizado="si";
	$tipo_autorizado="orden_compra";
	$id_autorizado=$id_oc;
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$fecha_hora_actual=date("Y-m-d H:i:s");
	
	$campos="autorizado, tipo_autorizado, id_autorizado, id_usuario, fecha_generacion";
	$valores="'$autorizado', '$tipo_autorizado', '$id_autorizado', '$id_usuario_actual', '$fecha_hora_actual'";
	
	$cons_IN="INSERT INTO autorizaciones ($campos) VALUES ($valores)";
	
	if(DEBUG){ echo"--->$cons_IN<br>"; $error="debug";}
	else
	{
		if($conexion_mysqli->query($cons_IN))
		{ $error="OC_A0";}
		else
		{ $error="OC_A1"; if(DEBUG){echo $conexion_mysqli->error;}}
	}
	
	$evento="Autoriza Orden de Compra id $id_oc";
	REGISTRA_EVENTO($evento);
	
	mysql_close($conexion);
	$conexion_mysqli->close();
	
	$url="autorizar_oc_3.php?error=$error";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
	
}
else
{
	if(DEBUG){ echo"Sin Datos<br>";}
}
?>