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
	
	if(DEBUG){ var_export($_POST);}
	
	$sede=$_POST["sede"];
	$movimiento=$_POST["movimiento"];
	
	$codigo=mysql_real_escape_string($_POST["codigo"]);
	$nombre=mysql_real_escape_string($_POST["nombre"]);
	$descripcion=mysql_real_escape_string($_POST["descripcion"]);
	
	
	$cons_IN="INSERT INTO presupuesto_parametros (codigo, nombre, descripcion, movimiento, sede) VALUES('$codigo', '$nombre', '$descripcion', '$movimiento', '$sede')";
	
	if(DEBUG){ echo "$cons_IN<br>";}
	else{
		if(mysql_query($cons_IN))
		{ $error=0;}
		else
		{ $error=1;}
	}
	
	if(DEBUG){ 
		echo"E: $error <br>". mysql_error();
		mysql_close($conexion);
		}
	else{ 
	mysql_close($conexion);
	header("location: ../item_actuales.php?error=$error");}
}
else
{ header("location: ../item_actuales.php");}
?>