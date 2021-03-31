<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="externo";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------// 
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("busca_datos_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"DEUDA_Y_MATRICULA");
////////////////////////////////////////////
function DEUDA_Y_MATRICULA($rut_original)
{
	$div='apDiv2';
	$fecha_actual=date("Y-m-d");
	$continuar=true;
	$html="";
	require("../../../funciones/funciones_varias.php");
	$objResponse = new xajaxResponse();
	if(empty($rut_original)){ $continuar=false; $objResponse->Alert("Ingrese Rut...");}
	else
	{
		$rut_original=str_replace(".","",$rut_original);
		$rut_original=strtoupper($rut_original);
		$array_rut=explode("-",$rut_original); 
		$dv_original=$array_rut[1];
		$solo_rut_original=$array_rut[0];
		$dv_correcto=validar_rut($solo_rut_original);
		
		if($dv_original==$dv_correcto)
		{}
		else
		{ $objResponse->Alert("Rut Incorrecto..."); $continuar=false;}
	}
	//------------------------------------------------------------------------------------------//
	if($continuar)
	{
		require("../../../funciones/conexion.php");
		require("../../../funciones/funciones_sistema.php");
		
		$aux_rut=mysql_real_escape_string($solo_rut_original."-".$dv_correcto);
		
		$cons="SELECT alumno.* FROM alumno INNER JOIN contratos2 ON alumno.id=contratos2.id_alumno WHERE alumno.rut='$aux_rut' ORDER by contratos2.id DESC LIMIT 1";
		$sql=mysql_query($cons)or die(mysql_error());
		$num_registros=mysql_num_rows($sql);
		if(DEBUG){$html.="-->$cons<br>num registros: $num_registros<br>";}
		if($num_registros>0)
		{
			$DA=mysql_fetch_assoc($sql);
			$nombre_alumno=$DA["nombre"];
			$apellido_alumno=$DA["apellido_P"]." ".$DA["apellido_M"];
			$carrera_alumno=$DA["carrera"];
			$id_alumno=$DA["id"];
			$id_carrera=$DA["id_carrera"];
			$tiene_matricula_vigente=VERIFICAR_MATRICULA($id_alumno, $id_carrera,true);
			if($tiene_matricula_vigente){ $tiene_matricula_label="Si";}
			else{ $tiene_matricula_label="No";}
			
			if($tiene_matricula_vigente)
			{
				$deuda_actual="$".number_format(DEUDA_ACTUAL($id_alumno, $fecha_actual),0,",",".");
			}
			else
			{
				$deuda_actual="$".number_format(DEUDA_ACTUAL($id_alumno, $fecha_actual),0,",",".");
			}
			$html.="<p><strong>Informacion Alumno </strong></p>";
			$html.="Fecha Consulta: $fecha_actual<br>";
			$html.="RUT: $aux_rut<br>";
			$html.="Alumno: $nombre_alumno $apellido_alumno<br>";
			$html.="Carrera: $carrera_alumno<br>";
			$html.="Matricula Vigente:<strong> $tiene_matricula_label</strong><br><br>";
			$html.="Deuda Actual: <strong>".$deuda_actual."</strong><br>";
		}
		else
		{ $html.="Alumno No encontrado";}
		mysql_free_result($sql);
		mysql_close($conexion);
		$objResponse->Assign($div,"innerHTML",$html);
	}
	
	return $objResponse;
}
////////////////
$xajax->processRequest();
?>