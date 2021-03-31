<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
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
	
	$year_cuota=mysqli_real_escape_string($conexion_mysqli, $_POST["year_cuota"]);
	$fecha_compromiso=mysqli_real_escape_string($conexion_mysqli, $_POST["fecha_compromiso"]);
	$fecha_corte=mysqli_real_escape_string($conexion_mysqli, $_POST["fecha_corte"]);
	$id_alumno=mysqli_real_escape_string($conexion_mysqli, $_POST["id_alumno"]);
	$id_carrera=mysqli_real_escape_string($conexion_mysqli, $_POST["id_carrera"]);
	$tipo_cobranza=mysqli_real_escape_string($conexion_mysqli, $_POST["tipo_cobranza"]);
	$hay_respuesta=mysqli_real_escape_string($conexion_mysqli, $_POST["hay_respuesta"]);
	if($hay_respuesta=="si"){ $hay_respuesta_label=1;}
	else{ $hay_respuesta_label=0;}
	
	$observacion=mysqli_real_escape_string($conexion_mysqli, $_POST["observacion"]);
	$fecha_hora_actual=date("Y-m-d H:i:s");
	$fecha_actual=date("Y-m-d");
	//$deuda_actual_alumno=DEUDA_ACTUAL($id_alumno, $fecha_actual);
	list($A_deuda_actual, $A_intereses, $A_gastos_cobranza)=DEUDA_ACTUAL_V2($id_alumno, $fecha_actual);
	$deuda_actual_alumno=($A_deuda_actual +$A_intereses+$A_gastos_cobranza);
	//-----------------------------------------------------------//
	$cons_A="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";
	$sqli_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
	$DA=$sqli_A->fetch_assoc();
		$A_sede=$DA["sede"];
		$A_id_carrera=$DA["id_carrera"];
	$sqli_A->free();	
	//---------------------------------------------------------------//
	
	$campos="id_alumno, id_carrera, sede, tipo, fecha, fecha_corte, fecha_compromiso, hay_respuesta, observacion, deuda_actual, year_cuota, cod_user";
	$valores="'$id_alumno', '$A_id_carrera', '$A_sede', '$tipo_cobranza', '$fecha_hora_actual', '$fecha_corte', '$fecha_compromiso', '$hay_respuesta_label', '$observacion', '$deuda_actual_alumno', '$year_cuota', '$id_usuario_actual'";
	$cons_IN="INSERT INTO cobranza ($campos) VALUES ($valores)";
	
	if(DEBUG){ echo"---> $cons_IN<br>";}
	else
	{
		if($conexion_mysqli->query($cons_IN))
		{ 
			$error="C1";
			//--------------------------------------------//
			$evento="Realiza Cobranza ($tipo_cobranza) a Alumno id_alumno: $id_alumno Por deuda: $deuda_actual_alumno";
			REGISTRA_EVENTO($evento);
			REGISTRO_EVENTO_ALUMNO($id_alumno, "cobranza", "Realizacion de Cobranza ($tipo_cobranza) por deuda actual de: $deuda_actual_alumno");
			//-----------------------------------------////
		}
		else
		{ $error="C2"; echo $conexion_mysqli->error;}
	}
	
	$conexion_mysqli->close();
	$url="nueva_cobranza_3.php?error=$error";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
?>