<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//-----------------------------------------//
	define("DEBUG", false);
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="ALUMNO";
	OKALIS($lista_invitados);
	require("../../OKALIS/class_OKALIS_v1.php");
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->anti2LoggAlumno();
	////////////////////////
//-----------------------------------------//	
$year_actual=date("Y");

if($_POST)
{
	 $id_alumno=$_SESSION["USUARIO"]["id"];
   	$id_carrera=$_SESSION["USUARIO"]["id_carrera"];
   
	$continuar_1=false;
	$continuar_2=false;
	$continuar_3=false;
	$error="";
	
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_varias.php");
	$email=mysqli_real_escape_string($conexion_mysqli, $_POST["email"]);
	$fono=mysqli_real_escape_string($conexion_mysqli, $_POST["fono"]);
	$fonoa=mysqli_real_escape_string($conexion_mysqli, $_POST["fonoa"]);
	
	
	if(comprobar_email($email))
	{
		if(DEBUG){ echo"Email valido<br>";}
		$continuar_1=true;
		$error.="0";
	}
	else
	{if(DEBUG){ echo"Email NO valido<br>";} $error.="1";}
	
	if(empty($fono))
	{if(DEBUG){ echo"FONO NO valido vacio<br>";} $error.="1";}
	else
	{if(DEBUG){ echo"FONO valido<br>";} $continuar_2=true; $error.="0";}
	
	if(empty($fonoa))
	{if(DEBUG){ echo"FONOa NO valido vacio<br>";} $error.="1";}
	else
	{if(DEBUG){ echo"FONOa valido<br>";} $continuar_3=true; $error.="0";}
	
	
	if($continuar_1 and $continuar_2 and $continuar_3)
	{
		$cons_UP="UPDATE alumno SET email='$email', fono='$fono', fonoa='$fonoa' WHERE id='$id_alumno' AND id_carrera='$id_carrera' LIMIT 1";
		
		if(DEBUG){ echo"--->$cons_UP<br>";}
		else
		{
			if($conexion_mysqli->query($cons_UP))
			{
				$error="A0";
				include("../../../funciones/VX.php");
				$descripcion="Alumno Actualiza datos contacto";
				REGISTRO_EVENTO_ALUMNO($id_alumno, "actualizacion",$descripcion);
			}
			else{ $error="A1";}
		}
		
		
		//--------------------------------------------------//
		if(DEBUG){ echo"<br><strong>Verifico si alumno tiene que actualizar condicion de FUAS</strong><br>";}
		$cons_REA="SELECT MAX(YEAR(fecha_generacion)) FROM alumno_registros WHERE id_alumno='$id_alumno' AND descripcion='Alumno Actualiza FUAS'";
	
		$sql_REA=$conexion_mysqli->query($cons_REA);
		$REA=$sql_REA->fetch_row();
		$ultimo_year_actualizo_FUAS=$REA[0];
		$sql_REA->free();
		if(empty($ultimo_year_actualizo_FUAS)){ $ultimo_year_actualizo_FUAS=0;}
		
		if($ultimo_year_actualizo_FUAS<$year_actual)
		{
			if(DEBUG){ echo"Debe actualizar FUAS<br>dirigir a Actualizacion de datos de FUAS<br>";}
			$url="solicitar_actualizacion_FUAS.php";
		}
		else
		{
			if(DEBUG){ echo"Todo OK<br>dirigir a Menu<br>";}
			$url="../alumno_menu.php";
		}
		//--------------------------------------------------//
		
		
		
		
		
		if(DEBUG){ echo"URL: $url<br>";}
		else{header("location: $url");}
	}
	else
	{
		$url="solicitar_1.php?error=$error;";
		if(DEBUG){ echo"URL: $url<br>";}
		else{header("location: $url");}
	}

}
else
{
	header("location: solicitar_1.php");
}

?>