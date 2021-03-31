<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_notas_semestrales_X_toma_ramo_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("informe_notas_X_toma_ramo_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"GENERA_INFORME");
$xajax->register(XAJAX_FUNCTION,"EXCEL_GENERAL");
define("DEBUG", false);
////////////////////////////////////////////
function GENERA_INFORME($FORMULARIO)
{
	$objResponse = new xajaxResponse();
	$mostrar_solo_alumnos_con_matricula=true;
	$year=$FORMULARIO["year"];
	$sede=$FORMULARIO["fsede"];
	$semestre=$FORMULARIO["semestre"];
	$id_carrera_consulta=$FORMULARIO["id_carrera"];
	if(DEBUG){$objResponse->alert("id_carrera : $id_carrera_consulta");}
	
	if($id_carrera_consulta=="0")
	{ $condicion_carrera="";}
	else
	{ $condicion_carrera=" AND toma_ramos.id_carrera='$id_carrera_consulta' AND alumno.id_carrera='$id_carrera_consulta'";}
	
	$div="div_carga";
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	
	$html="";
	if(DEBUG){$objResponse->Alert("año: $year semestre: $semestre sede: $sede");}
	//-------------------------------------------------------------------------------//
	
	$cons_MAIN="SELECT toma_ramos.id_alumno, toma_ramos.id_carrera, toma_ramos.cod_asignatura, alumno.sede, toma_ramos.jornada, toma_ramos.nivel, alumno.grupo FROM toma_ramos INNER JOIN alumno ON toma_ramos.id_alumno=alumno.id WHERE toma_ramos.semestre='$semestre' AND toma_ramos.year='$year' AND alumno.sede='$sede' $condicion_carrera ORDER by alumno.sede, toma_ramos.id_carrera, toma_ramos.nivel, alumno.jornada, toma_ramos.cod_asignatura";
	if(DEBUG){$objResponse->alert($cons_MAIN);}
	
	//$html.=$cons_MAIN;
	
	$sqli=$conexion_mysqli->query($cons_MAIN);
	$num_registros=$sqli->num_rows;
	
	$ARRAY_RESULTADOS=array();
	
	if($num_registros>0)
	{
		$html.="<strong>Periodo: $semestre Semestre - $year</strong><br>";
		if(DEBUG){$html.="$num_registros Registros<br>";}
		while($TM=$sqli->fetch_assoc())
		{
			$id_alumno=$TM["id_alumno"];
			$id_carrera_alumno=$TM["id_carrera"];
			$cod_asignatura=$TM["cod_asignatura"];
			$sede_alumno=$TM["sede"];
			$jornada_alumno=$TM["jornada"];
			$nivel_alumno=$TM["nivel"];
			$grupo_alumno=$TM["grupo"];
			
			$cons_ramo="SELECT nivel FROM mallas WHERE id_carrera='$id_carrera_alumno' AND cod='$cod_asignatura' LIMIT 1";
				$sql_ramo=$conexion_mysqli->query($cons_ramo)or die($conexion_mysqli->error());
					$D=$sql_ramo->fetch_assoc();
					$R_nivel=$D["nivel"];
				$sql_ramo->free();
			
			
			$situacion_alumno_en_periodo=ESTADO_ALUMNO_PERIODO($id_alumno, $id_carrera_alumno, $semestre, $year);
			$alumno_con_matricula=VERIFICAR_MATRICULA($id_alumno, $id_carrera_alumno, true, false, $semestre, false, $year);
			
			//$html.="id_alumno: $id_alumno id_carrera: $id_carrera_alumno cod_asignatura: $cod_asignatura jornada: $jornada_alumno situacion: $situacion_alumno_en_periodo Matricula: $alumno_con_matricula<br>";
			
			//----------------------------------------------------//
			if($mostrar_solo_alumnos_con_matricula)
			{
				if($alumno_con_matricula){$utilizar_alumno=true;}
				else{$utilizar_alumno=false;}
			}
			else{ $utilizar_alumno=true;}
			//-----------------------------------------------------//
			if(($situacion_alumno_en_periodo=="V")or($situacion_alumno_en_periodo=="EG"))
			{ $utilizar_alumno_2=true;}
			else
			{ $utilizar_alumno_2=false;}
			//-----------------------------------------------------///
			if($utilizar_alumno and $utilizar_alumno_2)
			{
				if(isset($ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]))
				{$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]+=1;}
				else
				{$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]=1;}
			}
		}
		
	}
	else
	{
		$html.="Sin Registros... :(<br>";
		
	}
	
	
	$tabla='';
	foreach($ARRAY_RESULTADOS as $aux_sede=>$array_1)
	{
		if(DEBUG){$html.="SEDE: $aux_sede ";}
		
		foreach($array_1 as $aux_id_carrera =>$array_2)
		{
			if(DEBUG){$html.="CARRERA: $aux_id_carrera ->  ";}
			$cons_c="SELECT carrera FROM carrera WHERE id='$aux_id_carrera' LIMIT 1";
			$sqli_c=$conexion_mysqli->query($cons_c);
				$C=$sqli_c->fetch_row();
				$aux_nombre_carrera=$C[0];
			$sqli_c->free();	
			foreach($array_2 as $aux_nivel => $array_3)
			{
				if(DEBUG){$html.="Nivel: $aux_nivel ->  <br>";}
				foreach($array_3 as $aux_jornada =>$array_4)
				{
					if(DEBUG){$html.="Jornada: $aux_jornada -> <br>";}
					foreach($array_4 as $aux_grupo => $array_5)
					{
						if(DEBUG){$html.="Grupo: $aux_grupo -> <br>";}
						$tabla.='<table border="1" width="100%">
								<thead>
									<tr>
										<th colspan="2">'.$aux_sede.' '.$aux_id_carrera.'_'.$aux_nombre_carrera.' Nivel['.$aux_nivel.'] Jornada['.$aux_jornada.'] Grupo ['.$aux_grupo.']</th>
									</tr>
								</thead>
								<tbody>
								<tr>
									<td>Asignaturas</td>
									<td>N. Alumnos</td>
								</tr>';
						foreach($array_5 as $aux_cod_asignatura => $valor)
						{
							if(DEBUG){$html.="->Cod Asignatura: $aux_cod_asignatura -> $valor<br>";}
								$cons_a="SELECT ramo FROM mallas WHERE id_carrera='$aux_id_carrera' AND cod='$aux_cod_asignatura' LIMIT 1";
								$sqli_a=$conexion_mysqli->query($cons_a);
									$A=$sqli_a->fetch_row();
									$aux_nombre_asignatura=$A[0];
								$sqli_a->free();	
							$tabla.='<tr>
										<td>'.$aux_cod_asignatura.' '.$aux_nombre_asignatura.'</td>
										<td align="center"><a href="informe_notas_X_toma_ramo_lista.php?sede='.base64_encode($aux_sede).'&id_carrera='.base64_encode($aux_id_carrera).'&nive='.base64_encode($aux_nivel).'&jornada='.base64_encode($aux_jornada).'&grupo='.base64_encode($aux_grupo).'&cod_asignatura='.base64_encode($aux_cod_asignatura).'&semestre='.base64_encode($semestre).'&year='.base64_encode($year).'" target="_blank" title="click para ver lista de alumnos" class="button_R">'.$valor.'</a></td>
									</tr>';
						}
						$tabla.='</tbody></table><br>';
					}
				}
			}
			
		}
		$tabla.='<a href="general_excel_resumen.php?year='.base64_encode($year).'&sede='.base64_encode($sede).'&semestre='.base64_encode($semestre).'&id_carrera='.base64_encode($id_carrera_consulta).'" target="_blank">EXPORTAR RESUMEN A EXCEL...</a>';
	}
	
	
	$html.=$tabla;
	//-------------------------------------------------------------------------------//
	$objResponse->Assign($div,"innerHTML",$html);
	//--------------------------------------------------------------------------------//
	$sqli->free();
	$conexion_mysqli->close();
	@mysql_close($conexion);
	return $objResponse;
}
$xajax->processRequest();		
?>