<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
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
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	
	$objResponse = new xajaxResponse();
	$div='div_resultados';
	$id_docente=$_SESSION["USUARIO"]["id"];
	
	$tiene_asignaciones_en_este_periodo=TIENE_ASIGNACIONES($id_docente, $sede, $semestre, $year);
	
	if($tiene_asignaciones_en_este_periodo)
	{
		$html_boton="";
		//busco encuesta
		$cons_E="SELECT id_encuesta FROM encuestas_main WHERE utilizar_para_autoevaluacion_docente='1'";
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
		
		if($E_id>0)
		{
		
		
			$cons="SELECT COUNT(id_resultados) FROM encuestas_resultados WHERE id_usuario='$id_docente' AND semestre_evaluar='$semestre' AND year_evaluar='$year' AND sede_evaluar='$sede' AND id_encuesta='$E_id'";
			
			$sqli_R=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$R=$sqli_R->fetch_row();
			$cantidad_resultados_docente=$R[0];
			if(empty($cantidad_resultados_docente)){$cantidad_resultados_docente=0;}
			$sqli_R->free();
			
			if($cantidad_resultados_docente>0){ $encuesta_contestada=true;}
			else{ $encuesta_contestada=false;}
			
			
			if($encuesta_contestada)
			{$html_boton='La encuesta para '.$sede.' Periodo['.$semestre.'-'.$year.'] ya esta contestada...';}
			else
			{$html_boton='<a href="autoevaluacion_docente_2.php?id_docente='.base64_encode($id_docente).'&semestre='.base64_encode($semestre).'&year='.base64_encode($year).'&sede='.base64_encode($sede).'&id_encuesta='.base64_encode($E_id).'&lightbox[iframe]=true&lightbox[width]=800&lightbox[height]=600" class="lightbox button_R">Ir a Encuesta de autoevaluacion -> '.$sede.' ['.$semestre.' - '.$year.']</a>';}
			
			
			$objResponse->Assign($div,"innerHTML",$html_boton);
		
		}
		else
		{
			$objResponse->Alert("No se ha definido encuesta de autoevaluacion actualmente...!!! No se puede continuar.");
		}
	}
	else
	{
		$html_boton='No es necesario realizar su autoevaluacion para '.$sede.' Periodo['.$semestre.'-'.$year.'] ya que no tiene asignaciones en este perido...';
		$objResponse->Assign($div,"innerHTML",$html_boton);
	}
	
	$conexion_mysqli->close();
	return $objResponse;
}
$xajax->processRequest();
?>