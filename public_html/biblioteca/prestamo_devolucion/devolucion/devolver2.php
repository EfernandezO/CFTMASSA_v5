<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG",false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Bibioteca_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

$id_libro=$_POST["id_libro"];
$id_alumno=$_POST["id_alumno"];
$rut_alumno=$_POST["rut_alumno"];
$carrera=$_POST["carrera"];
$id_carrera=$_POST["id_carrera"];
$sede=$_POST["sede"];

if(isset($_SESSION["BIBLIOTECA"]["devolver"]))
{ $continuar=$_SESSION["BIBLIOTECA"]["devolver"];}
else
{ $continuar=false;}
if(DEBUG){ var_dump($_POST); echo"--->$continuar<br> $id_libro<br>";}


if((is_numeric($id_libro))and($continuar))
{
	require("../../../../funciones/conexion_v2.php");
	if(!DEBUG)
	{ $_SESSION["BIBLIOTECA"]["devolver"]=false;}
	//obteniendo datos del alumno
	$cons="UPDATE  biblioteca set prestado='N', id_alumno='0' WHERE id_libro='$id_libro' LIMIT 1";
	if(DEBUG){ echo "UP-> $cons<br>";}
	else
	{ 
		if($conexion_mysqli->query($cons))
		{ $continuar_1=true;}
		else
		{ $continuar_1=false; echo"ERROR: ".$conexion_mysqli->error;}
		
	}
	
	
	$id_usuario_activo=$_SESSION["USUARIO"]["id"];
	$fecha_actual=date("Y-m-d");
	
	date_default_timezone_set('America/Santiago');//zona horaria
	$fecha_generacion=date("Y-m-d H:i:s");
	$fecha_prestamo='0000-00-00';
	//inserta registro nuevo
		$condicion="devuelto";
		$campos="id_libro, id_alumno, rut, id_carrera, sede, condicion, fecha_registro, fecha_prestamo, fecha_devolucion, cod_user";
		$valores="'$id_libro', '$id_alumno','$rut_alumno', '$id_carrera', '$sede', '$condicion', '$fecha_generacion', '$fecha_prestamo', '$fecha_actual', '$id_usuario_activo'";
		$cons_in="INSERT INTO biblioteca_registro ($campos) VALUES($valores)";
		
		if($continuar_1)
		{
			if(DEBUG){ echo"---> $cons_in<br>";}
			else{ $conexion_mysqli->query($cons_in)or die($conexion_mysqli->error);}
			 /////Registro ingreso///
			 include("../../../../funciones/VX.php");
			 $evento="Devolucion de Libro($id_libro) id_alumno: $id_alumno Rut: $rut_alumno";
			 REGISTRA_EVENTO($evento);
		}
		else
		{ if(DEBUG){ echo"No se puede continuar<br>";}}
		 /////////////////////// 
		$conexion_mysqli->close();
		
	$url="devolver3.php?error=D0";	
	if(DEBUG){ echo"URL: $url<br>";}
	else{header ("Location: $url");}
}
else
{
	if(DEBUG){echo"No se puede Continuar...<br>";}
	else{header ("Location: ../../menu_biblioteca.php?error=D1");}
}
?>

