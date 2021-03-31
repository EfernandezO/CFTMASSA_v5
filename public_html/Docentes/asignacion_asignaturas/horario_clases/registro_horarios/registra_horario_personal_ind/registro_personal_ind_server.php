<?php
//-----------------------------------------//
	require("../../../../../OKALIS/seguridad.php");
	require("../../../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
//////////////////////XAJAX/////////////////
@require_once ("../../../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("registro_personal_ind_server.php");
$xajax->configure('javascript URI','../../../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CARGA_HORARIO");
$xajax->register(XAJAX_FUNCTION,"MARCA_TIME");
//$xajax->setCharEncoding("ISO-8859-1");
////////////////////////////////////////////
function CARGA_HORARIO($id_funcionario, $fecha)
{
	
	$fecha_hora_actual_time=strtotime(date("Y-m-d H:i:s"));
	//$fecha_hora_actual_time=strtotime("2017-05-04 18:18:10");
	$objResponse = new xajaxResponse();
	$div="div_resultados";
	$continuar=true;
	$continuar_2=false;
	$html_tabla="";
	require("../../../../../../funciones/conexion_v2.php");
	require("../../../../../../funciones/funciones_varias.php");
	require("../../../../../../funciones/funciones_sistema.php");
	
	$nombre_funcionario=NOMBRE_PERSONAL($id_funcionario);	
		
	if((is_numeric($id_funcionario))and($id_funcionario>0)){ $continuar_2=true;}
	else{$continuar_2=false; $objResponse->Alert("Docente No encontrado en sistema");}
	
	$boton_lista='';
	if($continuar_2)
	{
		if(DEBUG){ echo"Continuar OK<br>";}
		
		$array_fecha=explode("-",$fecha);
		$year=$array_fecha[0];
		$mes=$array_fecha[1];
		if($mes>=8){$semestre=2;}
		else{$semestre=1;}
		
		$dia_semana=date("w",strtotime($fecha));
		$array_dia=array(0 =>"Domingo",
				 1=>"Lunes",
				 2=>"Martes",
				 3=>"Miercoles",
				 4=>"Jueves",
				 5=>"Viernes",
				 6=>"Sabado");
				 
				 	
					 $html_tabla='<table width="100%">
						<thead>
						<tr>
							<th colspan="9">'.$nombre_funcionario.'<br>Clases Dia '.$array_dia[$dia_semana].' '.$fecha.' ['.$semestre.' - '.$year.'] '.$sede.'</th>
						</tr>
						<tr>
							<td>N</td>
							<td>Lista</td>
							<td>Carrera</td>
							<td>Asignatura</td>
							<td>Jor - Grup</td>
							<td>Ingreso</td>
							<td>Salida</td>
							<td>Sala</td>
							<td>Control</td>
						</tr>
						</thead>
						<tbody>';

					 
		$cons_H="SELECT toma_ramo_docente.*, horario_docente.id_horario, horario_docente.dia_semana, horario_docente.hora_inicio, horario_docente.hora_fin, horario_docente.sala FROM toma_ramo_docente INNER JOIN horario_docente ON toma_ramo_docente.id=horario_docente.id_asignacion WHERE horario_docente.dia_semana='$dia_semana' AND toma_ramo_docente.semestre='$semestre' AND toma_ramo_docente.year='$year' AND toma_ramo_docente.id_funcionario='$id_funcionario' ORDER by horario_docente.dia_semana, horario_docente.hora_inicio";
		if(DEBUG){$objResponse->Alert("MAIN: $cons_H");}
		$sqli_H=$conexion_mysqli->query($cons_H)or die($conexion_mysqli->error);
		$num_registros=$sqli_H->num_rows;
		if($num_registros>0)
		{
			$aux=0;
			$num_botones_con_funcion=0;
			while($H=$sqli_H->fetch_assoc())
			{
				
				$H_id=$H["id_horario"];
				$H_dia_semana=$H["dia_semana"];
				$H_hora_inicio=$H["hora_inicio"];
				$H_hora_inicio_time=strtotime($fecha.$H_hora_inicio);
				$H_hora_fin=$H["hora_fin"];
				$H_hora_fin_time=strtotime($fecha.$H_hora_fin);
				$H_sala=$H["sala"];
				
				$AS_id_funcionario=$H["id_funcionario"];
				$AS_id_carrera=$H["id_carrera"];
				$AS_cod_asignatura=$H["cod_asignatura"];
				$AS_jornada=$H["jornada"];
				$AS_grupo=$H["grupo"];
				$AS_semestre=$H["semestre"];
				$AS_year=$H["year"];
				$AS_sede=$H["sede"];
				//--------------------------------------------------------------------------------///
				list($nombre_asignacion, $nivel_asignacion)=NOMBRE_ASIGNACION($AS_id_carrera, $AS_cod_asignatura);
				if($nivel_asignacion>0){$mostrar=true;}
				else{ $mostrar=false;}
				///--------------------------------------------------------//
				if($mostrar)
				{
					$aux++;
					$hay_boton_con_funcion=false;
					$boton_marcar="";
					//buscar registro 
						$cons_B="SELECT DISTINCT(tipo_registro), id_horario_registro FROM horario_docente_registros WHERE id_horario='$H_id' AND fecha='$fecha' ORDER by id_horario_registro";
						if(DEBUG){$objResponse->Alert("BUSCAR: $cons_B");}
						$sqli_B=$conexion_mysqli->query($cons_B)or die($conexion_mysqli->error);
						$num_registros_tipo=$sqli_B->num_rows;
						if(DEBUG){ echo"Numero de Registros: $num_registros_tipo<br>";}
						if($num_registros_tipo>0)
						{
							while($B=$sqli_B->fetch_row())
							{
								$tipo_registro=$B[0];
								if(DEBUG){ echo"Tipo registro: $tipo_registro<br>";}
								switch($tipo_registro)
								{
									case"llegada":
										$color="#EA558E";
										$boton_marcar='<a href="#" onclick="xajax_MARCA_TIME(\'salida\', '.$H_id.', \''.$fecha.'\', \''.$id_funcionario.'\');"><i class="icon-signout"></i> <strong>Salida</strong></a>';
										$hay_boton_con_funcion=true;
										$boton_lista='<a href="lista_alumnos/lista_alumnos_1.php?sede='.base64_encode($AS_sede).'&semestre='.base64_encode($AS_semestre).'&year='.base64_encode($AS_year).'&id_carrera='.base64_encode($AS_id_carrera).'&cod_asignatura='.base64_encode($AS_cod_asignatura).'&jornada='.base64_encode($AS_jornada).'&grupo='.base64_encode($AS_grupo).'&H_id='.base64_encode($H_id).'&fecha_clase='.base64_encode($fecha).'&lightbox[iframe]=true&lightbox[width]=650&lightbox[height]=530" class="lightbox" title="click para ver alumnos">Lista Alumnos</a>';
										break;
									case"salida":
										$color="#0f0";
										$boton_marcar='<strong><i class="icon-ok-sign"> OK</strong>';
										$hay_boton_con_funcion=false;
										break;
									case"inasistencia":
										$color="#f00";
										$boton_marcar='<a href="#" onclick="xajax_MARCA_TIME(\'llegada\', '.$H_id.', \''.$fecha.'\', \''.$id_funcionario.'\');"><i class="icon-signin"></i> <strong>Llegada</strong></a>';
										$hay_boton_con_funcion=true;
										break;
								}
							}
						}
						else
						{ 
							$color="#FE2E2E";
						$boton_marcar='<a href="#" onclick="xajax_MARCA_TIME(\'llegada\', '.$H_id.', \''.$fecha.'\', \''.$id_funcionario.'\');"><i class="icon-signin"></i> <strong>Llegada</strong></a>';
							$hay_boton_con_funcion=true;
						}
						$sqli_B->free();
						//-----------------------------------------------------------------------//
						
						/*if($num_botones_con_funcion>0){$boton_marcar='no disponible aun';}
						else{ if($hay_boton_con_funcion){$num_botones_con_funcion++;}}*/
						
						
								 
							//---------------------------------------------------------------------------//
							///busco si hay registros
							$cons_B="SELECT COUNT(id_asistencia) FROM asistencia_alumnos WHERE semestre='$AS_semestre' AND  year='$AS_year' AND sede='$AS_sede' AND id_carrera='$AS_id_carrera' AND jornada='$AS_jornada' AND cod_asignatura='$AS_cod_asignatura' AND grupo='$AS_grupo' AND fecha_clase='$fecha' AND id_horario='$H_id'";
							
							$sqli_B=$conexion_mysqli->query($cons_B)or die($conexion_mysqli->error);
							$D_B=$sqli_B->fetch_row();
								$coincidencias=$D_B[0];
								if(empty($coincidencias)){$coincidencias=0;}
							$sqli_B->free();
							if($coincidencias>0){ $asistencia_ok=false;}
							else{ $asistencia_ok=true;}
							
							//$objResponse->Alert("-->$cons_B\n coincidencias: $coincidencias\n");
							//---------------------------------------------------------------------------//	
							///muestro lista solo si esta entre horario de inicio y fin
							
							if($asistencia_ok)
							{
								if(($fecha_hora_actual_time>=$H_hora_inicio_time)and($fecha_hora_actual_time<=$H_hora_fin_time))
								{ 
									
									$tiempo_ok=true;
									//$objResponse->Alert("Tiempo OK");
								}
								else
								{
									$tiempo_ok=false;
									//$objResponse->Alert("HORA ERROR\n hr. actual $fecha_hora_actual_time\n inicio: $H_hora_inicio_time\n fin: $H_hora_fin_time");
								}
							}
							
							//---------------------------------------------//	
							
							if($asistencia_ok)
							{
								if($tiempo_ok)
								{ $boton_lista=$boton_lista;}
								else{$boton_lista='Fuera de Tiempo';}
							}
							else
							{ $boton_lista='Asistencia ya registrada';}
							
							
										 
							if(DEBUG){ echo"BOTON: $boton_marcar<br>";}
					$html_tabla.='<tr height="50">
									<td>'.$aux.'</td>
									<td>'.$boton_lista.'</td>
									<td>'.NOMBRE_CARRERA($AS_id_carrera).'</td>
									<td>'.$nombre_asignacion.'</td>
									<td>'.$AS_jornada.'-'.$AS_grupo.'</td>
									<td>'.$H_hora_inicio.'</td>
									<td>'.$H_hora_fin.'</td>
									<td>'.$H_sala.'</td>
									<td bgcolor="'.$color.'" align="center" width="15%">'.$boton_marcar.'</td>
								 </tr>';	 
								 
								 
				}//fin si mostrar
				
			}////fin si asignaciones
			//por si las asignaciones no se muestras, pero como tiene no se mostraria el mensaje "sin clases"
			if($aux==0){$html_tabla.='<tr><td colspan="8">Sin Clases el Dia de Hoy :(</td></tr>';}
		}
		else
		{ $html_tabla.='<tr><td colspan="8">Sin Clases el Dia de Hoy :(</td></tr>';}
		$sqli_H->free();
		
		$html_tabla.='</tbody>
					  </table>';
	}
	
	
	
	
	
	$conexion_mysqli->close();
	@mysql_close($conexion);
	$objResponse->Assign($div,"innerHTML",$html_tabla);
	return $objResponse;
}

function MARCA_TIME($tipo, $H_id, $fecha, $id_funcionario)
{
	$tiempo_pre_inicio=600;//en segundos
	$tiempo_post_inicio=600;
	$tiempo_pre_fin=600;
	$tiempo_post_fin=600;
	
	$array_fecha=explode("-",$fecha);
	$year=$array_fecha[0];
	$mes=$array_fecha[1];
	
	$objResponse = new xajaxResponse();
	require("../../../../../../funciones/conexion_v2.php");
	//$objResponse->Alert("MARCA_TIME $tipo");
	$html='<table width="100%" border="0"><tr>';
	$div='div_informacion';
	
	
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$fecha_hora_actual=date("Y-m-d H:i:s");
	
	//$fecha_hora_actual="2017-05-04 18:17:00";
	
	$fecha_hora_actual_time=strtotime($fecha_hora_actual);
	$hora_registro=date("H:i:s");
	
	
	$cons_HIF="SELECT id_carrera, cod_asignatura, jornada, grupo, sede, semestre, year, hora_inicio, hora_fin FROM horario_docente WHERE id_horario='$H_id' LIMIT 1";
	$sqli_HIF=$conexion_mysqli->query($cons_HIF)or die($conexion_mysqli_>error);
	$D_HIF=$sqli_HIF->fetch_assoc();
		$H_id_carrera=$D_HIF["id_carrera"];
		$H_cod_asignatura=$D_HIF["cod_asignatura"];
		$H_jornada=$D_HIF["jornada"];
		$H_grupo=$D_HIF["grupo"];
		$H_sede=$D_HIF["sede"];
		$H_semestre=$D_HIF["semestre"];
		$H_year=$D_HIF["year"];
	
		$H_hora_inicio=$D_HIF["hora_inicio"];
		$H_hora_inicio_time=strtotime($fecha.$H_hora_inicio);
		$H_hora_fin=$D_HIF["hora_fin"];
		$H_hora_fin_time=strtotime($fecha.$H_hora_fin);
	$sqli_HIF->free();	
	//--------------------------------------------------------------------------------//
	//comprobar tiempo de apertura y cierre
	
	switch($tipo)
	{
		case"llegada":
			if(($fecha_hora_actual_time>=($H_hora_inicio_time-$tiempo_pre_inicio))and($fecha_hora_actual_time<=($H_hora_inicio_time+$tiempo_post_inicio)))
			{$horario_ok=true;}
			else{$objResponse->Alert("Solo se permite el registro de llegada con \n".($tiempo_pre_inicio/60)." min de Anticipacion\n o con ".($tiempo_post_inicio/60)." min de Retraso"); $horario_ok=false;}
			break;
		case"salida":
			if(($fecha_hora_actual_time>=($H_hora_fin_time-$tiempo_pre_fin))and($fecha_hora_actual_time<=($H_hora_fin_time+$tiempo_post_fin)))
			{$horario_ok=true;}
			else{$objResponse->Alert("Solo se permite el registro de salida con \n".($tiempo_pre_fin/60)." min de Anticipacion\n o con ".($tiempo_post_fin/60)." min de Retraso"); $horario_ok=false;}
			break;
		default:
			$horario_ok=false;	
	}
	

	//----------------------------------------------------------------------------------//
	//buscar registro 
	$cons_B="SELECT tipo_registro FROM horario_docente_registros WHERE id_horario='$H_id' AND fecha='$fecha'";
	if(DEBUG){$objResponse->Alert("BUSCAR: $cons_B");}
	$sqli_B=$conexion_mysqli->query($cons_B)or die($conexion_mysqli->error);
	$total_registros_diario=$sqli_B->num_rows;
	$coincidencias=0;
	if($total_registros_diario>0)
	{
		while($B=$sqli_B->fetch_row())
		{
			$aux_tipo_registro=$B[0];
			if($aux_tipo_registro==$tipo){$coincidencias++;}
		}
	}
	$sqli_B->free();	
	//--------------------------------------------------------------------------------///
	$color="#ae0";
	//-----------------------------------------------------------------------------------//
	if($coincidencias>0)
	{
		if(DEBUG){$objResponse->Alert("Registro ya realizado, coincidencias: $coincidencias");}
		$html.='<td bgcolor="'.$color.'">Registro de '.$tipo.',  Anteriormente ya Realizado...';
	}
	else
	{ 
		if($tipo!=="inasistencia"){ $grabar=true;}
		elseif($total_registros_diario>0){ $grabar=false;}
		else{$grabar=true;}
		
		if(($grabar)and($horario_ok))
		{
			$cons_IN="INSERT INTO horario_docente_registros(id_horario, fecha, hora, tipo_registro, cod_user, fecha_generacion) VALUES ('$H_id', '$fecha', '$hora_registro', '$tipo', '$id_funcionario', '$fecha_hora_actual')";
			
			if(DEBUG){$objResponse->Alert("INSERTAR: $cons_IN");}
			else
			{
				if($conexion_mysqli->query($cons_IN))
				{
						$html.='<td bgcolor="'.$color.'"><i class="icon-arrow-right"></i> Registro de '.$tipo.',  Correctamente Registrado...';
						$objResponse->call('xajax_CARGA_HORARIO', $id_funcionario, $fecha);
				}
				else{$html.='<td bgcolor="#fa0"><i class="icon-arrow-right"></i> Registro de '.$tipo.',  Error Registrado...'.$conexion_mysqli->error;}
			}
		}
		else
		{
			$html.='<td bgcolor="'.$color.'"><i class="icon-arrow-right"></i> Registro de '.$tipo.',  No se puede grabar ya tiene registros de entrada/salida o fuera de rango horario...';
		}
		
		
	}
	
	$html.='<tr></table>';		  
	$conexion_mysqli->close();
	@mysql_close($conexion);
	
	$objResponse->Assign($div,"innerHTML",$html);
	return $objResponse;
	
}
//----------------------------------------------------------------------------------------------------//
$xajax->processRequest();
?>