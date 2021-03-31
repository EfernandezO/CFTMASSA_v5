<?php
//--------------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="ALUMNO";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("carga_notas_parciales_v3_1_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCAR_DOCENTES");


function BUSCAR_DOCENTES($semestre, $year, $id_alumno, $id_carrera)
{
	$hay_toma_ramos=true;
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	
	$TR_semestre=$semestre;
	$TR_year=$year;
	
	 $cons="SELECT sede FROM alumno WHERE id='$id_alumno' LIMIT 1";
		 $sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		 $row = $sqli->fetch_assoc();
		 $sede_alumno=$row["sede"];
	 $sqli->free();
	
	
	$objResponse = new xajaxResponse();
	$div='div_resultado';
	
	$html='<div class="widget orange">
                            <div class="widget-title">
                                <h4><i class="icon-reorder"></i>Evaluacion Docente - Periodo '.$semestre.' Semestre - '.$year.'</h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                            </span>
                            </div>
                            <div class="widget-body">
                                <table class="table table-striped table-bordered table-advance table-hover">
                                    <thead>
                                    <tr>
										<th><i class="icon-sort-by-order-alt"></i>N</th>
                                        <th><i class="icon-bookmark"></i>Docente</th>
										<th><i class="icon-edit"></i>Opcion</th>
                                    </tr>
                                    </thead>
                                    <tbody>';
	
	if(DEBUG){$html.="Periodo $semestre - $year<br>id_alumno: $id_alumno id_carrera: $id_carrera<br>";}
	//-----------------------------------------------//
	 include("../../../funciones/VX.php");
	 $evento="Revisa Evaluacion Docente [$semestre - $year] id_carrera: $id_carrera";
   	 REGISTRA_EVENTO($evento);
	//---------------------------------------------------//
	$alumno_actualmente_matriculado=VERIFICAR_MATRICULA($id_alumno, $id_carrera,true);
   //$alumno_actualmente_matriculado=true;
   $array_semestre=array(1,2);
   $mes_actual=date("m");
   
   if($mes_actual>=8)///utilizo agosto para inicio 2 semestre
   { $semestre_actual=2;}
   else{ $semestre_actual=1;}

	$id_encuesta=0;
	  if(DEBUG){ echo"Buscando si existen encuestas marcadas para evaluacion docente<br>";}
	  $cons_E="SELECT id_encuesta FROM encuestas_main WHERE utilizar_para_evaluacion_docente='1' ORDER by id_encuesta DESC LIMIT 1";
	  $sqli=$conexion_mysqli->query($cons_E)or die($conexion_mysqli->error);
	  $num_encuestas=$sqli->num_rows;
	  if(DEBUG){ echo"->$cons_E<br>numero encuestas encontradas: $num_encuestas<br>";}
	  if($num_encuestas>0)
	  {
		$E=$sqli->fetch_row();
		$id_encuesta=$E[0];
	  }
	  $sqli->free();
	  if(DEBUG){ echo"ID ENCUESTA:$id_encuesta<br>";}
	  
	  if($id_encuesta>0){ $hay_evaluacion_docente=true;}
	  else{ $hay_evaluacion_docente=false;}
	

		if(($hay_toma_ramos)and($hay_evaluacion_docente))
		{
		
			if(DEBUG){ echo"periodo con toma de ramos [$TR_semestre - $TR_year]<br>";}
			///----------------------------------------------------------------------------------------------------------//
			
			if(DEBUG){ echo"Busco ramos que tomo el alumno en periodo anteriormente encontrado<br>";}
			
			$cons_TR="SELECT jornada, id_carrera, cod_asignatura, `semestre`, `year` FROM `toma_ramos` WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND semestre='$TR_semestre' AND year='$TR_year' ORDER by cod_asignatura";
			
			
			$sql_TR=$conexion_mysqli->query($cons_TR)or die($conexion_mysqli->error);
			$num_ramos_tomados=$sql_TR->num_rows;
			if(DEBUG){ echo"--->$cons_TR<br>num ramos tomados: $num_ramos_tomados<br>";}
			
			$periodos_con_notas_parciales="";
			if(DEBUG){echo"Consultando Toma de ramos alumno<br>";}
			$ARRAY_DOCENTES=array();
			if($num_ramos_tomados>0)
			{
				$aux=0;
				while($PTR=$sql_TR->fetch_assoc())
				{
					$aux++;
					if($aux%2==0){$clase_boton='btn btn-large btn-primary';}
					else{$clase_boton='btn btn-large btn-success';}
					$periodo_semestre=$PTR["semestre"];
					$periodo_year=$PTR["year"];
					$TR_jornada=$PTR["jornada"];
					$TR_id_carrera=$PTR["id_carrera"];
					$TR_cod_asignatura=$PTR["cod_asignatura"];
					$ultimo_periodos_con_toma_de_ramos=$periodo_semestre.' Semestre -'.$periodo_year;
					if(DEBUG){ echo"[$aux] Semestre: $periodo_semestre Year: $periodo_year Jornada:$TR_jornada id_carrera: $TR_id_carrera cod_asignatura: $TR_cod_asignatura<br>";}
					
					///busco docente del ramo
					if($TR_cod_asignatura>0)
					{$utilizar_asignatura=true; if(DEBUG){ echo"---->CONSULTAR esta asignatura<br>";}}
					else{ $utilizar_asignatura=false; if(DEBUG){echo"----->NO consultar esta asignatura<br>";}}
					
					if($utilizar_asignatura)
					{
						$cons_TRD="SELECT distinct(id_funcionario) FROM toma_ramo_docente WHERE jornada='$TR_jornada' AND id_carrera='$TR_id_carrera' AND cod_asignatura='$TR_cod_asignatura' AND sede='$sede_alumno' AND semestre='$periodo_semestre' AND year='$periodo_year'";
						$sqli_TRD=$conexion_mysqli->query($cons_TRD)or die($conexion_mysqli->error);
						$num_docentes=$sqli_TRD->num_rows;
						if(DEBUG){ echo"--->$cons_TRD<br>Numero docentes relacionados a asignatura: $num_docentes<br>";}
						if($num_docentes>0)
						{
							while($DTR=$sqli_TRD->fetch_row())
							{
								//guardo docentes en array
								$aux_id_funcionario=$DTR[0];
								if(DEBUG){ echo"---->$aux_id_funcionario<br>";}
								$cons_RE="SELECT COUNT(id_resultados) FROM encuestas_resultados WHERE id_encuesta='$id_encuesta' AND id_usuario='$id_alumno' AND id_usuario_evaluar='$aux_id_funcionario' AND semestre_evaluar='$TR_semestre' AND year_evaluar='$TR_year' AND id_carrera_evaluar='$TR_id_carrera'";
							  $SQLI_r=$conexion_mysqli->query($cons_RE)or die($conexion_mysqli->error);
							  $RE=$SQLI_r->fetch_row();
							  $numero_resultado=$RE[0];
							  if(DEBUG){ echo"--->$cons_RE<br>numero resultados: $numero_resultado<br>";}
							  if(empty($numero_resultado)){ $numero_resultado=0;}
							  if($numero_resultado>0){ $encuesta_contestada=true;   if(DEBUG){ echo"Encuesta ya ha sido contestada<br>";} }
							  else{ $encuesta_contestada=false; if(DEBUG){ echo"Encuesta No ha sido contestada<br>";} $continuar_2=true;}
							  
							  
							  if(isset($ARRAY_DOCENTES[$aux_id_funcionario]))
							  { if(DEBUG){ echo"--->docente ya guardado<br>";}}
							  else
							  {$ARRAY_DOCENTES[$aux_id_funcionario]=$encuesta_contestada;  if(DEBUG){ echo"--->docente guardado<br>";}}
							 
							}
						}
						else
						{
							if(DEBUG){ echo"No hay docente con esta asignatura vinculada<br>";}
						}
						$sqli_TRD->free();
					}
					
				}
				//Busco Jefe de carrera y su encuesta
				
				$id_encuesta_jefe_carrera=15;
				
				$cons_JC="SELECT id_funcionario FROM toma_ramo_docente WHERE sede='$sede_alumno' AND semestre='$TR_semestre' AND year='$TR_year' AND id_carrera='$TR_id_carrera' AND cod_asignatura='0'";
				$sqli_JC=$conexion_mysqli->query($cons_JC)or die($conexion_mysqli->error);
				if(DEBUG){ echo"----> $cons_JC<br>";}
				$JC=$sqli_JC->fetch_assoc();
				$JC_id=$JC["id_funcionario"];
				$sqli_JC->free();
				if($JC_id>0){ $hay_jefe_carrera=true;}
				else{ $hay_jefe_carrera=false;}
				if(DEBUG){ echo"id_jefe_carrera: $JC_id<br>";}
				
				///verifico si encuesta JC se ha contestado
				$cons_RE="SELECT COUNT(id_resultados) FROM encuestas_resultados WHERE id_encuesta='$id_encuesta_jefe_carrera' AND id_usuario='$id_alumno' AND id_usuario_evaluar='$JC_id' AND semestre_evaluar='$TR_semestre' AND year_evaluar='$TR_year' AND id_carrera_evaluar='$TR_id_carrera' AND sede_evaluar='$sede_alumno'";
			  $SQLI_r=$conexion_mysqli->query($cons_RE)or die($conexion_mysqli->error);
			  $RE=$SQLI_r->fetch_row();
			  $numero_resultado=$RE[0];
			  if(DEBUG){ echo"--->$cons_RE<br>numero resultados: $numero_resultado<br>";}
			  
			  if(empty($numero_resultado)){ $numero_resultado=0;}
			  if($numero_resultado>0){ $encuesta_contestada_jefe_carrera=true;   if(DEBUG){ echo"Encuesta ya ha sido contestada<br>";} }
			  else{ $encuesta_contestada_jefe_carrera=false; if(DEBUG){ echo"Encuesta No ha sido contestada<br>";} $continuar_2=true;}
				
			}
			else
			{ $ultimo_periodos_con_toma_de_ramos=""; if(DEBUG){echo"NO hay Toma de Ramos<br>";}}
			
			
			$sql_TR->free();
		}
	//----------------------------------------------------------------------------------//
	
	 if(count($ARRAY_DOCENTES)>0)
	{
		$aux2=0;
		foreach($ARRAY_DOCENTES as $aux_id_docente => $aux_condicion_encuesta)
		{
			
				$aux2++;
				if($aux_condicion_encuesta){$clase_x='label-success'; $condicion="Ok"; $url_EN="#"; $class='';}
				else{ $clase_x='label-important'; $condicion="Evaluar"; $url_EN='evaluacion_docente_2.php?id_docente='.base64_encode($aux_id_docente).'&semestre='.base64_encode($TR_semestre).'&year='.base64_encode($TR_year).'&lightbox[iframe]=true&lightbox[width]=800&lightbox[height]=600'; $class='lightbox';}
				
				 
				
				 
				//list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $aux_cod_asignatura);
				$html.='<tr>
						<td>'.$aux2.'</td>
						<td>'.NOMBRE_PERSONAL($aux_id_docente).'</td>
						<td><a href="'.$url_EN.'" class="'.$class.'"><span class="label '.$clase_x.' label-mini">'.$condicion.'</span></a></td>
					 </tr>';
			
		}
	}
	else
	{
		$html.='<tr><td colspan="3"><i class="icon-bullhorn"></i> Sin Docentes</td></tr>';
	}
	if($hay_jefe_carrera)
	{
		
		if($encuesta_contestada_jefe_carrera){$clase_x='label-success'; $condicion="Ok"; $url_EN="#"; $class='';}
		else{ $clase_x='label-important'; $condicion="Evaluar"; $url_EN='evaluacion_JC_2.php?id_docente='.base64_encode($JC_id).'&semestre='.base64_encode($TR_semestre).'&year='.base64_encode($TR_year).'&sede='.base64_encode($sede_alumno).'&id_encuesta='.base64_encode($id_encuesta_jefe_carrera).'&lightbox[iframe]=true&lightbox[width]=800&lightbox[height]=600'; $class='lightbox';}
		
		$html.='<tr>
				<td>*</td>
				<td><strong>'.NOMBRE_PERSONAL($JC_id).' (Jefe de Carrera)</strong></td>
				<td><a href="'.$url_EN.'" class="'.$class.'"><span class="label '.$clase_x.' label-mini">'.$condicion.'</span></a></td>
			 </tr>';
	}
	
	
	//--------------------------------------------------------//
	$html.='</tbody>
			 </table>
					</div>
					</div>';
	
	$objResponse->Assign($div,"innerHTML",$html);
	
	$conexion_mysqli->close();
	return $objResponse;
}
$xajax->processRequest();
?>