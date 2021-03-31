<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="jefe_carrera";
	//$lista_invitados["privilegio"][]="Docente";
	OKALIS($lista_invitados);
	define("DEBUG",false);
//-----------------------------------------//
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("busca_docentes_periodo_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");

//-------------------------------------------------------------------------------------------//
function VERIFICAR($sede, $semestre, $year)
{
	$tabla="";
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	
	$objResponse = new xajaxResponse();
	$div='div_resultados';
	$id_docente=$_SESSION["USUARIO"]["id"];
	
	list($es_jefe_de_carrera, $array_carreras_jefatura)=ES_JEFE_DE_CARRERA($id_docente, $semestre, $year, $sede);
	
	$id_carrera_jefatura=$array_carreras_jefatura[0];
	
	if($es_jefe_de_carrera)
	{
		$html_boton="";
		//busco encuesta
		$cons_E="SELECT id_encuesta FROM encuestas_main WHERE utilizar_para_evaluacion_JC_D='1'";
		$sqli_E=$conexion_mysqli->query($cons_E)or die($conexion_mysqli->error);
		$num_resultados=$sqli_E->num_rows;
		if($num_resultados>0)
		{
			$E=$sqli_E->fetch_assoc();
			$E_id=$E["id_encuesta"];
		}
		else
		{ $E_id=0;}
		$sqli_E->free();
		
		//$objResponse->Alert("id_encuesta: $E_id");
		//------------------------------------------------------------//
		$tabla='<table width="100%" align="center">
				<thead>
					<tr>
						<th>N.</th>
						<th>Docente</th>
						<th>Opcion</th>
					</tr>
				</thead>
				<tbody>';
				
		if($E_id>0)		
		{
			$hay_docentes=false;
			$cons_D="SELECT DISTINCT(id_funcionario) FROM toma_ramo_docente WHERE semestre='$semestre' AND year='$year' AND sede='$sede' AND id_carrera='$id_carrera_jefatura' AND id_funcionario<>'$id_docente'";
			
			
			$sqli_D=$conexion_mysqli->query($cons_D)or die($conexion_mysqli->error);
			$num_docentes=$sqli_D->num_rows;
			if($num_docentes>0)
			{
				$hay_docentes=true;
				$aux=0;
				while($D=$sqli_D->fetch_row())
				{
					$aux++;
					$D_id=$D[0];
					//reviso si este docente ya lo evalue
					$cons_RE="SELECT COUNT(id_resultados) FROM encuestas_resultados WHERE id_encuesta='$E_id' AND id_usuario='$id_docente' AND id_usuario_evaluar='$D_id' AND semestre_evaluar='$semestre' AND year_evaluar='$year' AND id_carrera_evaluar='$id_carrera_jefatura'";
					  $SQLI_r=$conexion_mysqli->query($cons_RE)or die($conexion_mysqli->error);
					  $RE=$SQLI_r->fetch_row();
					  $numero_resultado=$RE[0];
					  if(DEBUG){ echo"--->$cons_RE<br>numero resultados: $numero_resultado<br>";}
					  if(empty($numero_resultado)){ $numero_resultado=0;}
					  if($numero_resultado>0){ $encuesta_contestada=true;   if(DEBUG){ echo"Encuesta ya ha sido contestada<br>";} }
					  else{ $encuesta_contestada=false; if(DEBUG){ echo"Encuesta No ha sido contestada<br>";} $continuar_2=true;}
					///
					$SQLI_r->free();
					
					if($encuesta_contestada)
					{$html_boton='OK';}
					else
					{$html_boton='<a href="evaluacion_JC_D_2.php?id_docente='.base64_encode($D_id).'&semestre='.base64_encode($semestre).'&year='.base64_encode($year).'&sede='.base64_encode($sede).'&id_encuesta='.base64_encode($E_id).'&id_carrera_evaluar='.base64_encode($id_carrera_jefatura).'&lightbox[iframe]=true&lightbox[width]=800&lightbox[height]=600" class="lightbox button_R">Ir a Encuesta</a>';}
					$tabla.='<tr>
								<td>'.$aux.'</td>
								<td>'.$D_id.' - '.NOMBRE_PERSONAL($D_id).'</td>
								<td>'.$html_boton.'</td>
							</tr>';
				}
			}
			
			$sqli_D->free();
			
			
			
		}
		else
		{
			$objResponse->Alert("No hay encuesta Disponible para Coevaluar, No se puede Continuar...!!!");
			$tabla.='<tr><td>No hay encuesta Disponible...</td></tr>';
		}
	}
	else
	{
		$objResponse->Alert("No puede Coevaluar en este periodo... sin asignacion de jefe de carrera");
		$tabla.='<tr><td>No Puede Evaluar en este Periodo...</td></tr>';
	}
	
	$tabla.='</tbody></table>';
	$objResponse->Assign($div,"innerHTML", $tabla);
	$conexion_mysqli->close();
	return $objResponse;
}
$xajax->processRequest();
?>