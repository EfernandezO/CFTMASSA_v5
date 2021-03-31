<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("gestion_cobranza_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

if($_POST)
{
	$error="debug";
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	if(DEBUG){ var_dump($_POST);}
	require("../../../../../funciones/conexion_v2.php");
	require("../../../../../funciones/funciones_sistema.php");	
	require("../../../../../funciones/VX.php");	
	
	$fecha_compromiso=mysqli_real_escape_string($conexion_mysqli, $_POST["fecha_compromiso"]);
	$id_cobranza=mysqli_real_escape_string($conexion_mysqli, $_POST["id_cobranza"]);
	$tipo_cobranza=mysqli_real_escape_string($conexion_mysqli, $_POST["tipo_cobranza"]);
	$hay_respuesta=mysqli_real_escape_string($conexion_mysqli, $_POST["hay_respuesta"]);
	if($hay_respuesta=="si"){ $hay_respuesta_label=1;}
	else{ $hay_respuesta_label=0;}
	
	$observacion=mysqli_real_escape_string($conexion_mysqli, $_POST["observacion"]);
	$fecha_hora_actual=date("Y-m-d H:i:s");
	$fecha_actual=date("Y-m-d");
	//$deuda_actual_alumno=DEUDA_ACTUAL($id_alumno, $fecha_actual);
	//-----------------------------------------------------------//
	
	$cons_UP="UPDATE cobranza SET tipo='$tipo_cobranza', hay_respuesta='$hay_respuesta_label', observacion='$observacion', fecha_compromiso='$fecha_compromiso' WHERE id_cobranza='$id_cobranza' LIMIT 1";
	
	if(DEBUG){ echo"---> $cons_UP<br>";}
	else
	{
		if($conexion_mysqli->query($cons_UP))
		{ 
			$error="CU1";
			//--------------------------------------------//
			$evento="Edita Cobranza ($tipo_cobranza) id_cobranza: $id_cobranza";
			REGISTRA_EVENTO($evento);
			//-----------------------------------------////
		}
		else
		{ $error="CU2"; echo $conexion_mysqli->error;}
	}
	
	$conexion_mysqli->close();
	$url="edita_cobranza_3.php?error=$error";
	if(DEBUG){ echo"URL: $url<br>";}
	else{header("location: $url");}
}
?>