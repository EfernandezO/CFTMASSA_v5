<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Bibioteca_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_POST)
{
require("../../../../funciones/conexion_v2.php");
include("../../../../funciones/funcion.php");
	$id_libro=str_inde($_POST['id']);
	$nombres=str_inde($_POST['nombres']); 
	$nombres=strtolower($nombres);
	$autor=str_inde($_POST['autor']);
	$autor=strtolower($autor);
	$editorial=str_inde($_POST['editorial']);
	$editorial=strtolower($editorial);
	$array_carrera=mysql_real_escape_string(($_POST['carrera']));
	$array_carrera=explode("_",$array_carrera);
	$id_carrera=$array_carrera[0];
	$nombre_carrera=$array_carrera[1];


$ano=str_inde($_POST['ano']);
$estado=str_inde($_POST['estado']);
$sede=str_inde($_POST['sede']);

$error="DEBUG";

$res="UPDATE  biblioteca SET nombre='$nombres', autor='$autor', editorial='$editorial', id_carrera='$id_carrera', carrera='$nombre_carrera', year='$ano', estado='$estado', sede='$sede' WHERE id_libro='$id_libro' LIMIT 1";
	if(DEBUG){echo"<br>->$res<br>";}
	else
	{
		  if($conexion_mysqli->query($res))
		  {$error="UP1";}
		  else
		  {$error="UP2";}
	}
$conexion_mysqli->close();

	if(DEBUG){ echo"Error: $error<br>";}
	else{ header("location: ../../menu_biblioteca.php?error=$error");}
}
else
{ header("location: ../../menu_biblioteca.php");}
?> 
