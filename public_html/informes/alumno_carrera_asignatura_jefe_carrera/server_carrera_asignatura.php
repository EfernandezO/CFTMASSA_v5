<?php
//--------------CLASS_okalis------------------//
error_reporting(E_ALL);
ini_set("display_errors", 1);
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="inspeccion";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("server_carrera_asignatura.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"GENERA_INFORME");
$xajax->register(XAJAX_FUNCTION,"BUSCA_SEDE");
$xajax->register(XAJAX_FUNCTION,"BUSCA_CARRERA");

////////////////////////////////////////////
function BUSCA_SEDE($id_funcionario, $semestre, $year)
{
	$objResponse = new xajaxResponse();
	$div='div_sede';
	$div2='div_carrera';
	$div_3='div_boton';
	if(DEBUG){ $objResponse->Alert("id_funcionario: $id_funcionario [$semestre - $year]");}
	$campo_sede='<select id="sede" name="sede" onchange="xajax_BUSCA_CARRERA('.$id_funcionario.',document.getElementById(\'semestre\').value, document.getElementById(\'year\').value, document.getElementById(\'sede\').value)">';
	$campo_sede.='<option value="SS" selected="selected">seleccione</option>';
	
	require("../../../funciones/conexion_v2.php");
	$cons="SELECT DISTINCT(sede) FROM toma_ramo_docente WHERE semestre='$semestre' AND year='$year' AND cod_asignatura='0' AND id_funcionario='$id_funcionario'";
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_sedes=$sqli->num_rows;
	if($num_sedes>0)
	{
		while($S=$sqli->fetch_row())
		{
			$aux_sede=$S[0];
			$campo_sede.='<option value="'.$aux_sede.'">'.$aux_sede.'</option>';
		}
	}
	else
	{$campo_sede.='<option value="NN">Sin Jefatura este Periodo</option>';}
	$sqli->free();
	
	$campo_sede.='</select>';
	///restableco campo carrera
	$campo_carrera='<select id="id_carrera" name="id_carrera">';
	$campo_carrera.='<option value="0">seleccione</option>';
	$campo_carrera.='</select>';
	$objResponse->Assign($div2,"innerHTML",$campo_carrera);
	//---------------------------------------------------//
	//restableco boton
	$objResponse->Assign($div_3,"innerHTML",'');
	//----------------------------------------**
	
	$objResponse->Assign($div,"innerHTML",$campo_sede);
	$conexion_mysqli->close();
	return $objResponse;
}
//////////////////////////////////////
function BUSCA_CARRERA($id_funcionario, $semestre, $year, $sede)
{
	$objResponse = new xajaxResponse();
	$div='div_carrera';
	$div_3='div_boton';
	$MOSTRAR_BOTON=FALSE;
	$boton='<a href="#" class="button_G" onclick="xajax_GENERA_INFORME(xajax.getFormValues(\'frm\'));return false;">Revisar Cursos de este Periodo</a>';
	
	if(DEBUG){ $objResponse->Alert("id_funcionario: $id_funcionario $sede [$semestre - $year]");}
	$campo_carrera='<select id="id_carrera" name="id_carrera">';
	$campo_carrera.='<option value="0">seleccione</option>';
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	$cons="SELECT DISTINCT(id_carrera) FROM toma_ramo_docente WHERE semestre='$semestre' AND year='$year' AND cod_asignatura='0' AND id_funcionario='$id_funcionario' AND sede='$sede'";
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_carreras=$sqli->num_rows;
	if($num_carreras>0)
	{
		$MOSTRAR_BOTON=true;
		$primera_vuelta=true;
		while($C=$sqli->fetch_row())
		{
			$aux_id_carrera=$C[0];
			if($primera_vuelta){ $selected='selected="selected"';}else{ $selected='';}
			$campo_carrera.='<option value="'.$aux_id_carrera.'" '.$selected.'>'.$aux_id_carrera.'_'.NOMBRE_CARRERA($aux_id_carrera).'</option>';
		}
	}
	else
	{ $campo_carrera.='<option value="0">Sin Carreras</option>';}
	$sqli->free();
	
	$campo_carrera.='</select>';
	
	if($MOSTRAR_BOTON){$objResponse->Assign($div_3,"innerHTML",$boton);}
	
	$objResponse->Assign($div,"innerHTML",$campo_carrera);
	
	
	
	$conexion_mysqli->close();
	return $objResponse;
}
//-------------------------------------------//
function GENERA_INFORME($FORMULARIO)
{
	$objResponse = new xajaxResponse();
	$year=$FORMULARIO["year"];
	$sede=$FORMULARIO["sede"];
	$semestre=$FORMULARIO["semestre"];
	$id_carrera_consulta=$FORMULARIO["id_carrera"];
	if(DEBUG){$objResponse->alert("id_carrera : $id_carrera_consulta");}
	
	if($id_carrera_consulta=="0")
	{ $condicion_carrera="";}
	else
	{ $condicion_carrera=" AND toma_ramos.id_carrera='$id_carrera_consulta' AND alumno.id_carrera='$id_carrera_consulta'";}
	
	$div="div_carga";
	require("../../../funciones/conexion_v2.php");
	
	$html="";
	if(DEBUG){$objResponse->Alert("año: $year semestre: $semestre sede: $sede");}
	//-------------------------------------------------------------------------------//
	
	$cons_MAIN="SELECT toma_ramos.id_alumno, toma_ramos.id_carrera, toma_ramos.cod_asignatura, alumno.sede, alumno.jornada, toma_ramos.nivel, alumno.grupo FROM toma_ramos INNER JOIN alumno ON toma_ramos.id_alumno=alumno.id WHERE toma_ramos.semestre='$semestre' AND toma_ramos.year='$year' AND alumno.sede='$sede' AND alumno.situacion IN('V','EG') $condicion_carrera ORDER by alumno.sede, toma_ramos.id_carrera, toma_ramos.nivel, alumno.jornada, toma_ramos.cod_asignatura";
	if(DEBUG){$objResponse->alert($cons_MAIN);}
	
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
			
			
			if(isset($ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]))
			{$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]+=1;}
			else
			{$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]=1;}
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
										<td align="center"><a href="alumnos_carrera_asignatura_lista.php?sede='.base64_encode($aux_sede).'&id_carrera='.base64_encode($aux_id_carrera).'&nive='.base64_encode($aux_nivel).'&jornada='.base64_encode($aux_jornada).'&grupo='.base64_encode($aux_grupo).'&cod_asignatura='.base64_encode($aux_cod_asignatura).'&semestre='.base64_encode($semestre).'&year='.base64_encode($year).'" target="_blank" title="click para ver lista de alumnos" class="button_R">'.$valor.'</a></td>
									</tr>';
						}
						$tabla.='</tbody></table><br>';
					}
				}
			}
		}
		
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