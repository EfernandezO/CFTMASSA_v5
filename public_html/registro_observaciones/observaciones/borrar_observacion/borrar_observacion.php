<?php require("../../../SC/seguridad.php");?>
<?php require("../../../SC/privilegio4.php");?>
<?php
if($_GET)
{
	
	$id_alumno=$_GET["id_alumno"];
	$id_observacion=$_GET["id_observacion"];
	include("../../../../funciones/conexion.php");
	$consX="DELETE FROM hoja_vida WHERE id='$id_observacion' AND id_alumno='$id_alumno' LIMIT 1";
	//echo"$consX<br>";
	if(mysql_query($consX))
	{ $error=4;}
	else
	{ $error=5;}
	header("location: ../hoja_vida.php?id_alumno=$id_alumno&error=$error");
	mysql_close($conexion);
}
else
{
	echo"No Get<br>";
}	
?>