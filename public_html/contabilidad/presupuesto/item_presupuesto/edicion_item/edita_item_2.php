<?php
//-----------------------------------------//
	require("../../../../OKALIS/seguridad.php");
	require("../../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", false);
if($_POST)
{
	include("../../../../../funciones/conexion.php");
	if(DEBUG){var_export($_POST);}
	$id_item=$_POST["id_item"];
	$nombre=$_POST["nombre"];
	$descripcion=mysql_real_escape_string($_POST["descripcion"]);
	$movimiento=$_POST["movimiento"];
	$codigo=mysql_real_escape_string($_POST["codigo"]);
	$sede=$_POST["sede"];
	
	$cons_up="UPDATE presupuesto_parametros SET codigo='$codigo', nombre='$nombre', descripcion='$descripcion', movimiento='$movimiento', sede='$sede' WHERE id='$id_item' LIMIT 1";
	if(DEBUG)
	{ echo"<br>--> $cons_up<br>";}
	else
	{
		if(mysql_query($cons_up))
		{ $error=4;}
		else
		{ $error=5;}
	}
	mysql_close($conexion);
	if(!DEBUG){ header("location: ../item_actuales.php?error=$error");}
}
else
{ header("location: ../item_actuales.php");}
?>