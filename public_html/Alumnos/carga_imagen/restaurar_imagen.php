<?php
 //-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="matricula";
	OKALIS($lista_invitados);
	define("DEBUG", false);
	
if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
{
	require("../../../funciones/conexion_v2.php");
	
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$cons_UP="UPDATE alumno SET imagen='' WHERE id='$id_alumno' LIMIT 1";
	if(DEBUG){ echo" $cons_UP<br>";}
	else{ $conexion_mysqli->query($cons_UP)or die($conexion_mysqli->error);}
	$conexion_mysqli->close();
	$error="C3";
	
	if(DEBUG){ echo"FIN<br>";}
	else{ $_SESSION["SELECTOR_ALUMNO"]["imagen"]=''; header("location: carga_final.php?error=$error");}
}
else
{
	if(DEBUG){ echo"SIn alumno seleccionado";}
	else{ header("location: index.php");}
}
	
?>