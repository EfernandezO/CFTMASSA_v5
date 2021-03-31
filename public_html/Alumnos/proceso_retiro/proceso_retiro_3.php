<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Proceso_Retiro_alumno_v1");
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
		$id_contrato=mysqli_real_escape_string($conexion_mysqli, $_POST["id_contrato"]);
		$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
		$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
		$yearIngresoCarrera=$_SESSION["SELECTOR_ALUMNO"]["yearIngresoCarrera"];
		$id_usuario_actual=$_SESSION["USUARIO"]["id"];
		$fecha_hora_actual=date("Y-m-d H:i:s");
		
		$retiro_semestre=mysqli_real_escape_string($conexion_mysqli, $_POST["semestre"]);
		$retiro_year=mysqli_real_escape_string($conexion_mysqli, $_POST["year"]);
		
		//$proceso=mysqli_real_escape_string($conexion_mysqli, $_POST["proceso"]);
		
		$retiro_id=mysqli_real_escape_string($conexion_mysqli, $_POST["retiro_id"]);
		$retiro_motivo=mysqli_real_escape_string($conexion_mysqli, $_POST["retiro_motivo"]);
		$retiro_descripcion=strtolower(mysqli_real_escape_string($conexion_mysqli, $_POST["retiro_descripcion"]));
		$retiro_presenta_carta=mysqli_real_escape_string($conexion_mysqli, $_POST["retiro_presenta_carta"]);
		$retiro_posible_reincorporacion=mysqli_real_escape_string($conexion_mysqli, $_POST["retiro_posible_reincorporacion"]);
		
		if(isset($_POST["ELIMINAR_CUOTA"])){$ARRAY_CUOTAS_ELIMINAR=$_POST["ELIMINAR_CUOTA"];}
		else{$ARRAY_CUOTAS_ELIMINAR=array();}
		
		if(is_numeric($retiro_id))
		{
			if($retiro_id>0)
			{$existe_proceso_retiro=true;}
			else
			{$existe_proceso_retiro=false; if(DEBUG){ echo"retiro_id <0<br>";}}
		}
		else
		{$existe_proceso_retiro=false;}
	}
		
	if($existe_proceso_retiro)	
	{$cons_1="UPDATE proceso_retiro SET motivo='$retiro_motivo', observacion='$retiro_descripcion', semestre_retiro='$retiro_semestre', year_retiro='$retiro_year', presento_carta_retiro='$retiro_presenta_carta', posible_reincorporacion='$retiro_posible_reincorporacion', fecha_generacion='$fecha_hora_actual', cod_user='$id_usuario_actual' WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' AND id_retiro='$retiro_id' LIMIT 1"; if(DEBUG){ echo"Actualiza Registro Retiro<br>";}
	$error="RA";
	$evento="Actualiza Proceso de Retiro Alumno $id_alumno carrera $id_carrera";
	}
	else
	{ $cons_1="INSERT INTO proceso_retiro (id_alumno, id_carrera, yearIngresoCarrera, motivo, observacion, semestre_retiro, year_retiro, presento_carta_retiro, posible_reincorporacion, fecha_generacion, cod_user) VALUES ('$id_alumno', '$id_carrera', '$yearIngresoCarrera', '$retiro_motivo', '$retiro_descripcion', '$retiro_semestre', '$retiro_year', '$retiro_presenta_carta', '$retiro_posible_reincorporacion', '$fecha_hora_actual', '$id_usuario_actual')"; if(DEBUG){ echo"CREA REGISTRO RETIRO<br>";} $error="RC"; $evento="Crea Proceso Retiro Alumno $id_alumno carrera $id_carrera";}
	
	if(DEBUG){ echo"--->$cons_1<br>";}
	else
	{			
		if($conexion_mysqli->query($cons_1))
		{
			if(DEBUG){ echo"Consulta ejecutada bien<br>";}
			$error.="0";
			REGISTRA_EVENTO($evento);
			$tipo_registro="Retiro";
			$descripcion="Creacion Proceso Retiro, Cambio condicion Alumno a Retirado id_carrera($id_carrera) yearIngresoCarrera: $yearIngresoCarrera)";
			REGISTRO_EVENTO_ALUMNO($id_alumno, $tipo_registro, $descripcion);
			///cambio de situacion a alumno
			$cons_A="UPDATE alumno SET situacion='R' WHERE id='$id_alumno' LIMIT 1";
			$conexion_mysqli->query($cons_A);
			///---------------------------------------------------------------------------------------------//

		}
		else
		{
			if(DEBUG){ echo"Consulta NO ejecutada<br>";}
			$error.="1";
		}
	}
	
	
	/////////////////////////////////////////////////////////////
	//elimina cuotas
	$num_cuotas_eliminar=count($ARRAY_CUOTAS_ELIMINAR);
	if(DEBUG){ echo"Num cuotas Eliminar: $num_cuotas_eliminar<br>";}
	if($num_cuotas_eliminar>0)	
	{
		foreach($ARRAY_CUOTAS_ELIMINAR as $aux_id_cuota => $aux_condicion)
		{
			if($aux_condicion=="true")
			{
				$cons_IN="DELETE FROM letras WHERE id='$aux_id_cuota' LIMIT 1";
				
				if(DEBUG){ echo"--->$cons_IN<br>";}
				else{ $conexion_mysqli->query($cons_IN)or die($conexion_mysqli->error);}
			}
			
		}
	}
	//----------------------------------------------------------------------------------------//
	
	//actualizo valor de cuota al ya cancelado
	$cons_UP_letra="UPDATE letras SET valor=valor-deudaXletra, deudaXletra='0', pagada='S' WHERE id_contrato='$id_contrato' AND pagada='A'";
	if(DEBUG){ echo"----> $cons_UP_letra<br>";}
	else{$conexion_mysqli->query($cons_UP_letra)or die("Letra UP ".$conexion_mysqli->error);}
	
	///actualizacion de contratos y cuotas
	$cons_L="SELECT SUM(valor) FROM letras WHERE id_contrato='$id_contrato' AND tipo='cuota'";
	if(DEBUG){ echo"---> $cons_L<br>";}
	$sqli_L=$conexion_mysqli->query($cons_L)or die($conexion_mysqli->error);
	$DL=$sqli_L->fetch_row();
		$valor_actualizado_LC=$DL[0];
		if(empty($valor_actualizado_LC)){$valor_actualizado_LC=0;}
	$sqli_L->free();	
	
	//actualizo contrato
	$porcentaje_desc_C=0;
	$linea_credito=$valor_actualizado_LC;
	$cantidad_beca="'0'";
	$porcentaje_beca="'0'";
	$BNM="'sin_beca'";
	$aporte_BNM="aporte_beca_nuevo_milenio*-1";
	$BET="'sin_beca'";
	$aporte_BET="aporte_beca_excelencia*-1";
	$total_a_pagar=0;
	$condicion_contrato="RETIRO";
	
	$cons_C="UPDATE contratos2 SET porcentaje_desc_contado='$porcentaje_desc_C', linea_credito_paga='$linea_credito', cantidad_beca=$cantidad_beca, porcentaje_beca=$porcentaje_beca, beca_nuevo_milenio=$BNM, aporte_beca_nuevo_milenio=$aporte_BNM, beca_excelencia=$BET, aporte_beca_excelencia=$aporte_BET, total='$total_a_pagar', condicion='$condicion_contrato' WHERE id='$id_contrato' LIMIT 1";
	
	if(DEBUG){ echo"---> $cons_C<br>";}
	else{ $conexion_mysqli->query($cons_C)or die("contratos ".$conexion_mysqli->error);}
	
	
	//-------------------------------------------------------------------------------------//	
	$conexion_mysqli->close();	
	
	$url="proceso_retiro_4.php?error=$error";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
	
}
else
{ echo"Sin Datos...<br>";}
?>