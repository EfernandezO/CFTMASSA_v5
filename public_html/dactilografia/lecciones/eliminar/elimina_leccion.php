<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="jefe_carrera";
	$lista_invitados["privilegio"][]="Docente";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", false);
if($_GET)
{
	require("../../../../funciones/conexion_v2.php");
	$id_leccion=$_GET["id_leccion"];
	$cons_d="DELETE FROM dactilografia_lecciones WHERE id='$id_leccion' LIMIT 1";
	if(DEBUG){ echo"X-> $cons_d<br>";}
	else
	{
		$conexion_mysqli->query($cons_d)or die(mysql_error());
	}
	$conexion_mysqli->close();
	if(!DEBUG){ header("location: elimina_leccion_final.php?error=0");}
}
else
{ header("location: ../lecciones_main.php");}
?>