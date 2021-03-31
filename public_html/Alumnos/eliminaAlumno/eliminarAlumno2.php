<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Eliminacion_registro_Alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_POST)
{
	if(DEBUG){ var_dump($_POST);}
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/VX.php");
	
	$error="";
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
	{
		$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
		$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
		$id_usuario_actual=$_SESSION["USUARIO"]["id"];
		$yearIngresoCarrera=$_SESSION["SELECTOR_ALUMNO"]["yearIngresoCarrera"];
		$fecha_hora_actual=date("Y-m-d H:i:s");
		
		$observacion=mysqli_real_escape_string($conexion_mysqli, $_POST["observacion"]);
		$metodoEliminacion=mysqli_real_escape_string($conexion_mysqli, $_POST["metodoEliminacion"]);
		$evento="alumno id_alumno: $id_alumno ELIMINADO del sistema...[".$observacion."] metodo Eliminacion: $metodoEliminacion yearIngresoCarrera: $yearIngresoCarrera id_carrera: $id_carrera";
		
		
		
		switch($metodoEliminacion){
			case"todo":
					//eliminar alumno, consulta en BBDD
					$cons_D="DELETE FROM alumno WHERE id='$id_alumno' AND id_carrera='$id_carrera' LIMIT 1";
					break;
			case"solo_contrato":
				$cons_D="DELETE FROM contratos2 WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' LIMIT 1";
				break;
		}
		
		if(DEBUG){ echo"<br>-->$cons_D<br>";}
		else{
				if($conexion_mysqli->query($cons_D))
				{ 
					$error="RC0";
					REGISTRA_EVENTO($evento);
				}
				else{$error="RC1"; echo"ERROR".$conexion_mysqli->error;}
			}
		
	}//fin si hay alumno seleccionado

		

	$conexion_mysqli->close();	
	
	$url="eliminarAlumno3.php?error=$error";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
	
}
else
{ echo"Sin Datos...<br>";}
?>