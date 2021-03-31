<?php
//-----------------------------------------//
	require("../../../../OKALIS/seguridad.php");
	require("../../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG" , false);

if($_SESSION["PRESUPUESTO"]["verificador"])
{ $continuar=true;}
else
{ $continuar=false;}

if(($_POST)and($continuar))
{
	if(DEBUG){ var_export($_POST);}
	if(!DEBUG){ $_SESSION["PRESUPUESTO"]["verificador"]=false;}
	include("../../../../../funciones/conexion.php");
	$sede=$_SESSION["PRESUPUESTO"]["sede"];
	$fecha_presupuesto=$_SESSION["PRESUPUESTO"]["fecha"];
	$id_usuario_activo=$_SESSION["USUARIO"]["id"];
	
	$movimiento=$_POST["movimiento"];
	$item=$_POST["item"];
	$valor=$_POST["valor"];
	$glosa=mysql_real_escape_string($_POST["glosa"]);
	$forma_pago=$_POST["forma_pago"];
	
		date_default_timezone_set('America/Santiago');//zona horaria
		$fecha_generacion=date("Y-m-d H:i:s");
	
	$campos="movimiento, item, valor, forma_pago, fecha, glosa, sede, fecha_generacion, cod_user";
	$valores="'$movimiento', '$item', '$valor', '$forma_pago', '$fecha_presupuesto', '$glosa', '$sede', '$fecha_generacion', '$id_usuario_activo'";
	$cons_IN="INSERT INTO presupuesto ($campos) VALUES($valores)";
	if(DEBUG){ echo"--> $cons_IN<br>";}
	else
	{
		if(mysql_query($cons_IN)) 
		{ $error=0;}
		else
		{ $error=1;}
	}
	mysql_close($conexion);
	$url="../presupuesto_main.php?error=$error";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
	
}
else
{ header("location: ../presupuesto_main.php");}
?>