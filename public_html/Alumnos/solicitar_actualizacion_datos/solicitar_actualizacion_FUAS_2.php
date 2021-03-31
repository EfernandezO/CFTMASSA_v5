<?php
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
$fecha_hora_actual=date("Y-m-d H:i:s");

if($_POST)
{
	 $id_alumno=$_SESSION["USUARIO"]["id"];
   	$id_carrera=$_SESSION["USUARIO"]["id_carrera"];
	
	require("../../../funciones/conexion_v2.php");
	$estado_fuas=mysqli_real_escape_string($conexion_mysqli, $_POST["estado_FUAS"]);
	

	//borro si existe registro previo
	$cons_D="DELETE FROM registros_FUAS WHERE id_alumno='$id_alumno' AND year='$year_actual' LIMIT 1";
	if(DEBUG){ echo"-->$cons_D<br>";}
	else{$conexion_mysqli->query($cons_D);}
	///creo registro nuevo
	
	$cons_IN="INSERT INTO registros_FUAS (id_alumno, year, estado_fuas, tipo_usuario, cod_user, fecha_generacion) VALUES ('$id_alumno', '$year_actual', '$estado_fuas', 'alumno', '$id_alumno', '$fecha_hora_actual')";
	if(DEBUG){ echo"-->$cons_IN<br>";}
	else{
			if($conexion_mysqli->query($cons_IN))
			{ 			
				include("../../../funciones/VX.php");
				$descripcion="Alumno Actualiza FUAS";
				REGISTRO_EVENTO_ALUMNO($id_alumno, "actualizacion",$descripcion);
			}
			else{ if(DEBUG){echo $conexion_mysqli->error;}}
		}
	
	$url="../alumno_menu.php";
	if(DEBUG){ echo"URL: $url<br>";}
	else{header("location: $url");}

}
else
{header("location: solicitar_actualizacion_FUAS.php");}

?>