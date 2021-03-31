<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Impresion_Y_Gestion_de_contratos_v1_editar");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	$id_cuota=base64_decode($_GET["id_cuota"]);
	$id_alumno=base64_decode($_GET["id_alumno"]);
	
	
	$id_contrato=base64_decode($_GET["id_contrato"]);
	$year=base64_decode($_GET["year"]);
	$semestre=$_GET["semestre"];
	
	if((is_numeric($id_cuota))and(is_numeric($id_alumno)))
	{
		require("../../../../../funciones/conexion_v2.php");
		$cons_E="DELETE FROM letras WHERE id='$id_cuota' AND idalumn='$id_alumno' LIMIT 1";
		//echo"$cons_E<br>";
		if($conexion_mysqli->query($cons_E))
		{$error=2;}//eliminada correctamente
		else
		{ 
			$error=3;
			$msj=base64_encode("DEL ".$conexion_mysqli->error);	
		}
		$conexion_mysqli->close();
		header("location: ../../informe_finan1.php?error=$error&id_contrato=$id_contrato&year=$year&semestre=$semestre");
	}	
	else
	{
		header("location: ../../index.php");
	}
	
?>