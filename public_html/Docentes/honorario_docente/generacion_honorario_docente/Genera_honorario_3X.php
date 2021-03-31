<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Genera_honorario_1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	
$comparador=md5("massa".date("Ymd"));	

if(isset($_GET["verificador"]))
{ 
	$verificador=$_GET["verificador"];
	if($verificador==$comparador){$continuar_1=true;}
	else{$continuar_1=false;}
}
else
{ $continuar_1=false;}

if(isset($_SESSION["HONORARIO"])){ $continuar_2=true;}
else{ $continuar_2=false;}
//-------------------------------------------------------------------//

if($continuar_1 and $continuar_2)
{
	require("../../../../funciones/conexion_v2.php");
	if(DEBUG){var_dump($_SESSION["HONORARIO"]);}
	$sede=$_GET["sede"];
	$mes=$_GET["mes"];
	$year=$_GET["year"];
	$year_generacion=$_GET["year_generacion"];
	$mes_actual=date("m");
	if($mes_actual>8)
	{$semestre_actual=2;}
	else{$semestre_actual=1;}
	
	$semestre_consulta=$_GET["semestre"];
	if(DEBUG){ echo"<br>-------GET----------<br>"; var_dump($_GET);}
	////
	//-----------------------------------------------//
	//busco registros anteriores de honorarios
	$cons_CH="SELECT COUNT(id_honorario) FROM honorario_docente WHERE mes_generacion='$mes' AND year_generacion='$year_generacion' AND sede='$sede'";
	$sqli_CH=$conexion_mysqli->query($cons_CH) or die($conexion_mysqli->error);
	$RCH=$sqli_CH->fetch_row();
		$num_registros=$RCH[0];
	$sqli_CH->free();	
	
	if($num_registros>0){ $hay_registros_previos=true; if(DEBUG){ echo"hay Registros Previos<br>";}}
	else{ $hay_registros_previos=false;}
	
	if(!$hay_registros_previos)
	{
		//--------------------------------------------//
		include("../../../../funciones/VX.php");
		$evento="Genera Honorario Docente  $sede [$mes - $year_generacion]";
		@REGISTRA_EVENTO($evento);
		//----------------------------------------------//
		$fecha_actual=date("Y-m-d H:i:s");
		$id_usuario_actual=$_SESSION["USUARIO"]["id"];
		//aprobacion automatica al crear
		$condicion_generado_contabilidad="ok";
		$fecha_generado_contabilidad=$fecha_actual;
		$id_usuario_generador=$id_usuario_actual;
		
		$registros_en_session=count($_SESSION["HONORARIO"]);
		if($registros_en_session>0)
		{
			foreach($_SESSION["HONORARIO"] as $X_id_funcionario =>$aux_array) 
			{
				$X_total_a_pagar=$aux_array["total_a_pagar"];
				if($X_total_a_pagar>0)
				{ $estado_honorario="pendiente";}
				else
				{ $estado_honorario="cancelado";}
				
				$CONS_IN_honorario="INSERT INTO honorario_docente (sede, mes_generacion, semestre, year, year_generacion, id_funcionario, total, estado, generado_contabilidad, fecha_generado_contabilidad, id_user_generado_contabilidad, fecha_generacion, cod_user) VALUES ('$sede', '$mes', '$semestre_consulta', '$year', '$year_generacion', '$X_id_funcionario', '$X_total_a_pagar', '$estado_honorario', '$condicion_generado_contabilidad', '$fecha_generado_contabilidad', '$id_usuario_generador', '$fecha_actual', '$id_usuario_actual')";
				
				if(DEBUG){ echo"<strong>$CONS_IN_honorario</strong><br><br>"; $id_honorario_generado="H0";}
				else{ $conexion_mysqli->query($CONS_IN_honorario)or die("Honorario_docente ".$conexion_mysqli->error); $id_honorario_generado=$conexion_mysqli->insert_id;}
				
				foreach($aux_array["asignaciones"] as $indice => $array_asignaciones)
				{
					$X_id_carrera=$array_asignaciones["id_carrera"];
					$X_cod_asignatura=$array_asignaciones["cod_asignatura"];
					$X_jornada=$array_asignaciones["jornada"];
					$X_grupo=$array_asignaciones["grupo"];
					$X_total_base=$array_asignaciones["total_base"];
					$X_cargo=$array_asignaciones["cargo"];
					$X_abono=$array_asignaciones["abono"];
					
					$X_cargo=str_replace(",",",",$X_cargo);
					$X_abono=str_replace(",",".",$X_abono);
					
					$X_condicion=$array_asignaciones["condicion"];
					$X_cuota_actual=$array_asignaciones["num_cuota_actual"];
					$X_total_cuotas=$array_asignaciones["num_cuotas_totales"];
					$X_glosa_cargo=$array_asignaciones["glosa_cargo"];
					$X_glosa_abono=$array_asignaciones["glosa_abono"];
					$X_semestre=$array_asignaciones["semestre"];
					$X_year=$array_asignaciones["year"];
					$X_sede=$array_asignaciones["sede"];
					$X_valor_hora=$array_asignaciones["valor_hora"];
					$X_horas_mensuales=$array_asignaciones["horas_mensuales"];
					
					if($X_cuota_actual>=$X_total_cuotas)
					{ $cambio_condicion_asignacion=true;}
					else
					{ $cambio_condicion_asignacion=false;}
					
					
					if(empty($X_condicion)){$X_condicion="on";}
					
					//----------------------------------------------------------------//
					if($cambio_condicion_asignacion)
					{
						if(DEBUG){echo"Cambiando condicion de asignacion<br><br>";}
						if($X_condicion=="on")
						{
							if(DEBUG){echo"Cambiando condicion de asignacion aprobada por condicion ON<br><br>";}
							$nueva_condicion="cancelada";
							$cons_condicion_asignacion="UPDATE toma_ramo_docente SET condicion='$nueva_condicion' WHERE id_funcionario='$X_id_funcionario' AND id_carrera='$X_id_carrera' AND jornada='$X_jornada' AND grupo='$X_grupo' AND cod_asignatura='$X_cod_asignatura' AND sede='$X_sede' AND semestre='$X_semestre' AND year='$X_year' LIMIT 1";
							if(DEBUG){ echo"--->$cons_condicion_asignacion<br>";}
							else{ $conexion_mysqli->query($cons_condicion_asignacion)or die("Cambio Condicion Asignacion".$conexion_mysqli->error);}	
						}
						else
						{ if(DEBUG){echo"Cambiando condicion de asignacion aprobada por condicion OFF<br><br>";}}
					}
					else{if(DEBUG){ echo"NO Cambiar condicion de asignacion<br><br>";}}
					//---------------------------------------------------------------//
					
					
					
					
					///----------------------------------------------------//
					//calculo de valores
					$total_base=($X_horas_mensuales*$X_valor_hora);
					$total_cargo=($X_cargo*$X_valor_hora);
					$total_abono=($X_abono*$X_valor_hora);
					
					$X_total_asignacion=($total_base-$total_cargo)+$total_abono;
					//--------------------------------------------------------------------------------------------//
					$campos="id_honorario, id_funcionario, sede, id_carrera, cod_asignatura, jornada, grupo, cuota, total_base, cargo, abono, valor_hora, glosa_cargo, glosa_abono, total_a_pagar, semestre, year, fecha_generacion, cod_user";
					
					$valores="'$id_honorario_generado', '$X_id_funcionario', '$X_sede', '$X_id_carrera', '$X_cod_asignatura', '$X_jornada', '$X_grupo', '$X_cuota_actual', '$X_horas_mensuales', '$X_cargo', '$X_abono', '$X_valor_hora', '$X_glosa_cargo', '$X_glosa_abono', '$X_total_asignacion', '$X_semestre', '$X_year', '$fecha_actual', '$id_usuario_actual'";
					
					if($X_condicion=="on")
					{
						$cons_IN_item_detalle="INSERT INTO honorario_docente_detalle ($campos) VALUES ($valores)";
						if(DEBUG){echo"<tt>--->$cons_IN_item_detalle</tt><br><br>";}
						else{ $conexion_mysqli->query($cons_IN_item_detalle)or die("Honorario_detalle ".$conexion_mysqli->error);}
					}
					else
					{
						if(DEBUG){ echo"--->NO considera Detalle OFF<br>";}
					}
				}
			}
		}
		else
		{if(DEBUG){ echo"SESSION sin Datos<br>";}}
	}
	$conexion_mysqli->close();
	
	$url="Generacion_honorario_final.php?sede=".base64_encode($sede)."&mes=".base64_encode($mes)."&year_generacion=".base64_encode($year_generacion);
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
	
}
else
{
	if(DEBUG){ echo"No se puede Continuar<br>";}
}
?>