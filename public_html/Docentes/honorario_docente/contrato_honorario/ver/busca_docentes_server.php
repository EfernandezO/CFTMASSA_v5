<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("ver_contrato_docente");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	
//////////////////////XAJAX/////////////////
@require_once ("../../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("busca_docentes_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_DOCENTES_CON_ASIGNACION");

function BUSCA_DOCENTES_CON_ASIGNACION($sede, $semestre, $year)
{
	$objResponse = new xajaxResponse();
	$div='div_funcionarios';
	$mostrar_boton=false;
	include("../../../../../funciones/conexion_v2.php");
	
	$html='<select name="funcionario" id="funcionario">';
	$cons="SELECT DISTINCT(id_funcionario) FROM toma_ramo_docente INNER JOIN personal ON toma_ramo_docente.id_funcionario=personal.id WHERE toma_ramo_docente.sede='$sede' AND toma_ramo_docente.semestre='$semestre' AND toma_ramo_docente.year='$year' ORDER by personal.apellido_P, personal.apellido_M, personal.nombre";
	$sqli=$conexion_mysqli->query($cons);
	$num_registros=$sqli->num_rows;
	
	if($num_registros>0)
	{
		$mostrar_boton=true;
		while($F=$sqli->fetch_row())
		{
			$id_funcionario=$F[0];
			$cons_F="SELECT nombre, apellido_P, apellido_M FROM personal WHERE id='$id_funcionario' LIMIT 1";
			$sqli_F=$conexion_mysqli->query($cons_F);
				$P=$sqli_F->fetch_assoc();
				$nombre_funcionario=$P["nombre"];
				$apellido_funcionario=$P["apellido_P"]." ".$P["apellido_M"];
			$sqli_F->free();
			$html.='<option value="'.$id_funcionario.'">'.$nombre_funcionario.' '.$apellido_funcionario.'</option>';
		}
		$html.='<option value="todos">Todos</option>';
	}
	else
	{ $html.='<option value="0">Sin Registros de funcionarios</option>';}
	$sqli->free();
	 
	 $html.='</select>';
	$conexion_mysqli->close();
	
	
	$objResponse->Assign($div,"innerHTML",$html);
	$boton='<a href="#" class="button_G" onclick="CONFIRMAR();">Continuar</a>';
	
	if($mostrar_boton)
	{$objResponse->Assign('apDiv2',"innerHTML",$boton);}
	else
	{$objResponse->Assign('apDiv2',"innerHTML","");}
	return $objResponse;
}

$xajax->processRequest();
?>