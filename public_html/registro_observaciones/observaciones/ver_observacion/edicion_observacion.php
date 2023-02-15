<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
//-----------------------------------------//	
?>
<?php
if($_POST)
{
	//var_export($_POST);
	$id_user_activo=$_SESSION["USUARIO"]["id"];
	$fecha_actual=date("Y-m-d");
	/////////////////////////////////
	$id_alumno=$_POST["id_alumno"];
	$id_observacion=$_POST["id_observacion"];
	$observacion=$_POST["observacion"];
	
	include("../../../../funciones/conexion.php");
	$campo_valor="observacion='$observacion', id_user='$id_user_activo'";
	$cons_UP="UPDATE hoja_vida SET $campo_valor WHERE id='$id_observacion'";
	
	//echo"-- > $cons_UP<br>";
	if(mysql_query($cons_UP))
	{ $error=2;}
	else
	{ $error=3;}
	mysql_close($conexion);
	header("location: ../hoja_vida.php?id_alumno=$id_alumno&error=$error");
}
else
{
	header("location: ../seleccion_alumno.php");
}
?>