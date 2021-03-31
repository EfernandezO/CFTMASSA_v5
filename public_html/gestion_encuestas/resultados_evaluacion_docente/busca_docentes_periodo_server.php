<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Gestion de Encuestas_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("busca_docentes_periodo_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCAR_DOCENTES");
$xajax->register(XAJAX_FUNCTION,"BUSCAR_CARRERAS");
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");

function BUSCAR_CARRERAS($id_encuesta, $sede, $semestre, $year){
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	$objResponse = new xajaxResponse();
	$div='div_carrera';
	$campo_select='<select name="id_carrera" id="id_carrera" onchange="xajax_BUSCAR_DOCENTES('.$id_encuesta.', \''.$sede.'\', this.value, '.$semestre.', '.$year.')">
					 <optgroup label="Carreras">
					<option value="0">Seleccione</option>';
	$cons_D="SELECT DISTINCT(id_carrera_evaluar) FROM encuestas_resultados WHERE encuestas_resultados.id_encuesta='$id_encuesta' AND encuestas_resultados.semestre_evaluar='$semestre' AND encuestas_resultados.year_evaluar='$year' AND encuestas_resultados.sede_evaluar='$sede'";
	
	$sqli_D=$conexion_mysqli->query($cons_D)or die($conexion_mysqli->error);
	$num_carreras=$sqli_D->num_rows;
	
	if(DEBUG){$objResponse->Alert("-->$cons_D\n num carreras: $num_carreras");}
	
	if($num_carreras>0)
	{
		while($D=$sqli_D->fetch_row())
		{
			$aux_id_carrera=$D[0];
			$campo_select.='<option value="'.$aux_id_carrera.'">'.NOMBRE_CARRERA($aux_id_carrera).'</option>';
		}
	}
	else
	{$campo_select.='<option value="0">Sin Carreras evaluadas en este periodo</option>';}
	
	$campo_select.='</optgroup>';

	
	$campo_select.='</select>';
	$objResponse->Assign($div,"innerHTML",$campo_select);
	$objResponse->Assign('div_boton',"innerHTML",'');
	$conexion_mysqli->close();
	return $objResponse;
}

function BUSCAR_DOCENTES($id_encuesta, $sede, $id_carrera, $semestre, $year)
{
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	$objResponse = new xajaxResponse();
	$div='div_docentes';
	
	$campo_select='<select name="id_docente" id="id_docente" onchange="xajax_VERIFICAR(this.value)">
					 <optgroup label="Docentes">
					<option value="0">Seleccione</option>';
	$cons_D="SELECT DISTINCT(id_usuario_evaluar) FROM encuestas_resultados WHERE encuestas_resultados.id_encuesta='$id_encuesta' AND encuestas_resultados.id_carrera_evaluar='$id_carrera' AND encuestas_resultados.semestre_evaluar='$semestre' AND encuestas_resultados.year_evaluar='$year' AND encuestas_resultados.sede_evaluar='$sede'";
	
	$sqli_D=$conexion_mysqli->query($cons_D)or die($conexion_mysqli->error);
	$num_docentes_evaluados=$sqli_D->num_rows;
	
	//$objResponse->Alert("-->$cons_D\n num docentes: $num_docentes_evaluados");
	
	if($num_docentes_evaluados>0)
	{
		while($D=$sqli_D->fetch_row())
		{
			$aux_id_docente_evaluado=$D[0];
			$campo_select.='<option value="'.$aux_id_docente_evaluado.'">'.NOMBRE_PERSONAL($aux_id_docente_evaluado).'</option>';
		}
	}
	else
	{$campo_select.='<option value="0">Sin Docentes Evaluados en este periodo</option>';}
	
	$campo_select.='</optgroup>';
	if($num_docentes_evaluados>0){ $campo_select.='<optgroup label="Operaciones"><option value="exportar_xls">Exportar xls</option> </optgroup>';}
	
	$campo_select.='</select>';
	$objResponse->Assign($div,"innerHTML",$campo_select);
	$objResponse->Assign('div_boton',"innerHTML",'');
	$conexion_mysqli->close();
	return $objResponse;
}
//-------------------------------------------------------------------------------------------//
function VERIFICAR($id_docente)
{
	$objResponse = new xajaxResponse();
	$div='div_boton';
	
	$html_boton='';
	
	if(is_numeric($id_docente))
	{
		if($id_docente>0)
		{$mostrar_boton=true;}
		else
		{$mostrar_boton=false;}
		
		if($mostrar_boton)
		{
			$html_boton='<a href="#" onclick="enviar_formulario();" class="button_G">Continuar</a>';
		}
	}
	else
	{
		switch($id_docente)
		{
			case"exportar_xls":
				$html_boton='<a href="#" onclick="enviar_formulario_xls();" class="button_G">Continuar con Exportacion</a>';
				break;
		}
		
	}
	
	$objResponse->Assign($div,"innerHTML",$html_boton);
	return $objResponse;
}
$xajax->processRequest();
?>