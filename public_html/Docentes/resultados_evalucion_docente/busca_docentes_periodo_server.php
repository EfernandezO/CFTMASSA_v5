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
$xajax->register(XAJAX_FUNCTION,"BUSCAR_RESULTADOS");
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");

function BUSCAR_RESULTADOS($id_encuesta, $sede, $semestre, $year)
{
	
	$id_funcionario=$_SESSION["USUARIO"]["id"];
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	$objResponse = new xajaxResponse();
	$div='div_resultados';
	$html='<table align="center" width="100%">
			<thead>
			<tr>
				<th colspan="2">Seleccione una carrera para ver sus Resultados</th>
			</tr>
			<thead>
			<tbody>';

	$cons_D="SELECT DISTINCT(id_carrera_evaluar) FROM encuestas_resultados WHERE encuestas_resultados.id_encuesta='$id_encuesta' AND encuestas_resultados.semestre_evaluar='$semestre' AND encuestas_resultados.year_evaluar='$year' AND encuestas_resultados.sede_evaluar='$sede' AND id_usuario_evaluar='$id_funcionario'";
	
	$sqli_D=$conexion_mysqli->query($cons_D)or die($conexion_mysqli->error);
	$num_carreras_evaluadas=$sqli_D->num_rows;
	
	if(DEBUG){$objResponse->Alert("-->$cons_D\n num carreras: $num_carreras_evaluadas");}
	
	if($num_carreras_evaluadas>0)
	{
		while($D=$sqli_D->fetch_row())
		{
			$aux_id_carrera=$D[0];
			$html.='<tr>
						<td bgcolor="'.COLOR_CARRERA($aux_id_carrera).'"><img src="../../BAses/Images/ok.png" width="29" height="26" alt="ok" /></td>
						<td bgcolor="'.COLOR_CARRERA($aux_id_carrera).'"><a href="resultado_evaluacion_docente_2.php?id_encuesta='.base64_encode($id_encuesta).'&sede='.base64_encode($sede).'&id_carrera='.base64_encode($aux_id_carrera).'&semestre='.base64_encode($semestre).'&year='.base64_encode($year).'&id_docente='.base64_encode($id_funcionario).'" target="_blank" title="click para revisar">'.NOMBRE_CARRERA($aux_id_carrera)."</a></td>
					</tr>";
		}
	}
	else
	{ $html.='<tr><td>Sin Carreras Encontradas</td></tr>';}
	
	
	$html.="</tbody></table>";
	$objResponse->Assign($div,"innerHTML",$html);
	$conexion_mysqli->close();
	return $objResponse;
}
//-------------------------------------------------------------------------------------------//
function VERIFICAR($id_docente)
{
	$objResponse = new xajaxResponse();
	$div='div_boton';
	
	$html_boton='';
	
	if($id_docente>0)
	{$mostrar_boton=true;}
	else
	{$mostrar_boton=false;}
	
	if($mostrar_boton)
	{
		$html_boton='<a href="#" onclick="enviar_formulario();" class="button_G">Continuar</a>';
	}
	
	$objResponse->Assign($div,"innerHTML",$html_boton);
	return $objResponse;
}
$xajax->processRequest();
?>