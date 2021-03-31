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
    require('../../../../funciones/conexion_v2.php');
	include('../../../../funciones/funcion.php');
	
	$id_libro_generado="NO-ID";
	$error="DEBUG";
	
   $nombres=strtolower(str_inde($_POST['nombres'])); 
   $autor=strtolower(str_inde($_POST['autor']));
   $editorial=strtolower(str_inde($_POST['editorial']));
   $array_carrera=$_POST['carrera'];
   $array_carrera=explode("_",$array_carrera);
   $id_carrera=$array_carrera[0];
   $carrera=$array_carrera[1];
   $ano=str_inde($_POST['ano']);  
   $estado=str_inde($_POST['estado']);
   $sede=str_inde($_POST['sede']); 
   
$cons_INB="INSERT INTO biblioteca (nombre,autor,editorial,id_carrera, year, prestado, estado,sede) VALUES('$nombres','$autor','$editorial','$id_carrera','$ano','N', '$estado','$sede')";
	if(DEBUG){echo"-> $cons_INB<br>";}
	else
	{
 	 if($conexion_mysqli->query($cons_INB))
	 {$error=0; $id_libro_generado=$conexion_mysqli->insert_id;}
	 else
	 {$error=1;}
	}
   $conexion_mysqli->close();
   
	$url="graba_libro_final.php?id_libro=$id_libro_generado&error=$error";
	if(DEBUG){ echo"URL: $url<br>";}
	else
	{header("location: $url");}
}
else
{ header("location: ../../menubiblio.php");}
?> 