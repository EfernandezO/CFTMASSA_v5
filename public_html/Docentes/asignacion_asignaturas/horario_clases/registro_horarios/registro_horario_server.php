<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("control_horario_docente");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("registro_horario_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CLASES_DE_HOY");
////////////////////////////////////////////
function CLASES_DE_HOY($fecha, $semestre, $year, $sede)
{
	$objResponse = new xajaxResponse();
	$continuar=true;
	
	require("../../../../../funciones/conexion_v2.php");
	require("../../../../../funciones/funciones_sistema.php");
	require("../../../../../funciones/funcion.php");
	
	$fecha_actual=date("Y-m-d");
	
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
						<th colspan="11">Clases Dia '.$array_dia[$dia_semana].' '.fecha_format($fecha).' ['.$semestre.' - '.$year.'] '.$sede.'</th>
					</tr>
					<tr>
						<td>N</td>
						<td>Funcionario</td>
						<td>Carrera</td>
						<td>Asignatura</td>
						<td>Jor - Grup</td>
						<td>Ingreso</td>
						<td>Salida</td>
						<td colspan="2">Control</td>
						<td>Registros</td>
						<td>Sala</td>
					</tr>
					</thead>
					<tbody>';
	
	$div='div_clases_hoy';
	
	
				 
	$cons_H="SELECT toma_ramo_docente.*, horario_docente.id_horario, horario_docente.dia_semana, horario_docente.hora_inicio, horario_docente.hora_fin, horario_docente.sala FROM toma_ramo_docente INNER JOIN horario_docente ON toma_ramo_docente.id=horario_docente.id_asignacion WHERE horario_docente.dia_semana='$dia_semana' AND toma_ramo_docente.semestre='$semestre' AND toma_ramo_docente.year='$year' AND toma_ramo_docente.sede='$sede' ORDER by horario_docente.dia_semana, horario_docente.hora_inicio";
	if(DEBUG){$objResponse->Alert("MAIN: $cons_H");}
	$sqli_H=$conexion_mysqli->query($cons_H)or die($conexion_mysqli->error);
	$num_registros=$sqli_H->num_rows;
	if($num_registros>0)
	{
		$aux=0;
		while($H=$sqli_H->fetch_assoc())
		{
			$aux++;
			$H_id=$H["id_horario"];
			$H_dia_semana=$H["dia_semana"];
			$H_hora_inicio=$H["hora_inicio"];
			$H_hora_fin=$H["hora_fin"];
			$H_sala=$H["sala"];
			
			$AS_id_funcionario=$H["id_funcionario"];
			$AS_id_carrera=$H["id_carrera"];
			$AS_cod_asignatura=$H["cod_asignatura"];
			$AS_jornada=$H["jornada"];
			$AS_grupo=$H["grupo"];
			$AS_sede=$H["sede"];
			$AS_semestre=$H["semestre"];
			$AS_year=$H["year"];
			
			//buscar registro 
			$cons_B="SELECT DISTINCT(tipo_registro) FROM horario_docente_registros WHERE id_horario='$H_id' AND fecha='$fecha'";
			if(DEBUG){$objResponse->Alert("BUSCAR: $cons_B");}
			$sqli_B=$conexion_mysqli->query($cons_B)or die($conexion_mysqli->error);
			$num_registros_tipo=$sqli_B->num_rows;
			if($num_registros_tipo>0)
			{
				while($B=$sqli_B->fetch_row())
				{
					$tipo_registro=$B[0];
					
					switch($tipo_registro)
					{
						case"llegada":
							$color="#FF0";
							break;
						case"salida":
							$color="#0f0";
							break;
						case"inasistencia":
							$color="#f00";
							break;
					}
				}
			}
			else
			{ $color="";}
			$sqli_B->free();
			
			//--------------------------------------------------------------------------------///
			
			list($nombre_asignacion, $nivel_asignacion)=NOMBRE_ASIGNACION($AS_id_carrera, $AS_cod_asignatura);
			if($nivel_asignacion>0){$mostrar=true;}
			else{ $mostrar=false;}
			///--------------------------------------------------------//
			if($mostrar)
			{
				////-----------------------------------------------------//
				$boton_lista='';
				//buscar si se paso lista
				$cons_B="SELECT COUNT(id_asistencia) FROM asistencia_alumnos WHERE semestre='$semestre' AND  year='$year' AND sede='$sede' AND id_carrera='$AS_id_carrera' AND jornada='$AS_jornada' AND cod_asignatura='$AS_cod_asignatura' AND grupo='$AS_grupo' AND fecha_clase='$fecha' AND id_horario='$H_id'";
				
				$sqli_B=$conexion_mysqli->query($cons_B)or die($conexion_mysqli->error);
				$D_B=$sqli_B->fetch_row();
					$coincidencias=$D_B[0];
					if(empty($coincidencias)){$coincidencias=0;}
				$sqli_B->free();
				if($coincidencias>0){ $asistencia_ok=true;}
				else{ $asistencia_ok=false;}
				
				if($asistencia_ok)
				{$boton_lista='<a href="ver_resumen_asistencia/resumen_asistencia_alumno.php?H_id='.base64_encode($H_id).'&fecha_clase='.base64_encode($fecha).'&semestre='.base64_encode($semestre).'&year='.base64_encode($year).'&sede='.base64_encode($sede).'&id_carrera='.base64_encode($AS_id_carrera).'&cod_asignatura='.base64_encode($AS_cod_asignatura).'&jornada='.base64_encode($AS_jornada).'&grupo='.base64_encode($AS_grupo).'&lightbox[iframe]=true&lightbox[width]=850&lightbox[height]=600" class="lightbox" title="Revisar asistencia de Alumnos"><img src="../../../../BAses/Images/color_verde.png" width="26" height="24" /></a>';}
				else{$boton_lista='<a href="registra_horario_personal_ind/lista_alumnos/lista_alumnos_1.php?sede='.base64_encode($AS_sede).'&semestre='.base64_encode($AS_semestre).'&year='.base64_encode($AS_year).'&id_carrera='.base64_encode($AS_id_carrera).'&cod_asignatura='.base64_encode($AS_cod_asignatura).'&jornada='.base64_encode($AS_jornada).'&grupo='.base64_encode($AS_grupo).'&H_id='.base64_encode($H_id).'&fecha_clase='.base64_encode($fecha).'&tipo_usuario='.base64_encode("administrador").'&lightbox[iframe]=true&lightbox[width]=850&lightbox[height]=600" class="lightbox" title="click para ver alumnos"><img src="../../../../BAses/Images/color_rojo.png" width="26" height="24" /></a>';}
				//---------------------------------------------------------//
				
				$html_tabla.='<tr>
								<td>'.$aux.'</td>
								<td>'.NOMBRE_PERSONAL($AS_id_funcionario).'</td>
								<td>'.NOMBRE_CARRERA($AS_id_carrera).'</td>
								<td>'.$nombre_asignacion.'</td>
								<td>'.$AS_jornada.'-'.$AS_grupo.'</td>
								<td>'.$H_hora_inicio.'</td>
								<td>'.$H_hora_fin.'</td>
								<td bgcolor="'.$color.'" align="center"><a href="marca_tiempo/marca_time.php?H_id='.base64_encode($H_id).'&fecha='.base64_encode($fecha).'&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=560" class="lightbox" id="marca_evento"><img src="../../../../BAses/Images/icono_cronometro.png" width="23" height="23" alt="reloj" /></a></td>
								<td>'.$boton_lista.'</td>
								<td align="center"><a href="marca_tiempo/detalle_marca_time.php?H_id='.base64_encode($H_id).'&tipo=salida&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=500" class="lightbox">info</a></td>
								<td>'.$H_sala.'</td>
							 </tr>';
			}
		}
	}
	else
	{ $html_tabla.='<tr><td colspan="10">Sin Registros</td></tr>';}
	$sqli_H->free();
	
	$html_tabla.='</tbody>
				  </table>';
				  
	$conexion_mysqli->close();
	@mysql_close($conexion);
	
	$objResponse->Assign($div,"innerHTML",$html_tabla);
	
	//---------------------------------------------//
	return $objResponse;
}
//----------------------------------------------------------------------------------------------------//
$xajax->processRequest();
?>