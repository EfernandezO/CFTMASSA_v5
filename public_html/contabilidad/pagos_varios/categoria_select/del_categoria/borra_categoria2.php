<?php include ("../../../../SC/seguridad.php");?>
<?php include ("../../../../SC/privilegio.php");?>
<?php
$url="borra_elemento.php";
if($_POST)
{
	include("../../../../../funciones/conexion.php");
	extract($_POST);
	$id_D=strip_tags($opc_X);
	$seccion="finanzas";
	
	$cons_D="DELETE FROM parametros WHERE id=$id_D AND seccion='$seccion'";
	//echo"$cons_D<br>";
	if(mysql_query($cons_D))
	{
		$error=0;
	}
	else
	{
		$error=1;
		//echo mysql_error();
	}
	$url.="?error=$error";
	mysql_close($conexion);
	header("location: $url");
}
else
{
	header("location: $url");
}
?>