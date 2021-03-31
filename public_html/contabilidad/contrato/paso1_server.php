<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Matriculas_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("paso1_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_VALORES_CARRERA");
$xajax->register(XAJAX_FUNCTION,"ACTUALIZA_FECHAS");
$xajax->register(XAJAX_FUNCTION,"CARGA_CARRERAS");
$xajax->register(XAJAX_FUNCTION,"FULL_INFO_CARRERA");
////////////////////////////////////////////

function FULL_INFO_CARRERA($id_carrera, $year, $sede, $semestre, $jornada, $yearIngresoCarrera){
	$objResponse = new xajaxResponse();
	$objResponse->call('xajax_ACTUALIZA_FECHAS',$semestre, $year);	
	$objResponse->call('xajax_CARGA_CARRERAS',$year, $yearIngresoCarrera, $jornada, $sede);	
	$objResponse->call('xajax_BUSCA_VALORES_CARRERA',$id_carrera, $year, $sede, $semestre);	
	return $objResponse;
}

function BUSCA_VALORES_CARRERA($id_carrera, $year, $sede, $semestre)
{
	$campo_arancel='<select name="arancel" id="arancel">';
	$year_actual=date("Y");
	$year_next=($year_actual+1);
	require("../../../funciones/conexion_v2.php");
	$div_arancel='div_arancel';
	$objResponse = new xajaxResponse();
	

	
	$cons="SELECT * FROM hija_carrera_valores WHERE id_madre_carrera='$id_carrera' AND sede='$sede' AND year='$year' LIMIT 1";	
	$sqli=$conexion_mysqli->query($cons);
	$DC=$sqli->fetch_assoc();
		$aux_matricula=$DC["matricula"];
		$aux_arancel_1=$DC["arancel_1"];
		$aux_arancel_2=$DC["arancel_2"];
		if(empty($aux_arancel_1)){$aux_arancel_1=0;}
		if(empty($aux_arancel_2)){$aux_arancel_2=0;}
		if(empty($aux_matricula)){$aux_matricula=0;}
	$sqli->free();	
	if(DEBUG){ $objResponse->Alert("CONS: $cons\n matricula= $aux_matricula \n Semestre: $semestre");}
	//------------------------------------------------------------------//
	if($semestre==1)
	{ $select_1='selected="selected"'; $select_2=""; $expira="30-08-$year_actual";}
	else
	{ $select_1=""; $select_2='selected="selected"'; $expira="31-01-$year_next";}
	//-------------------------------------------------------------------//
	$campo_arancel.='<option value="'.$aux_arancel_1.'" '.$select_1.'>'.$aux_arancel_1.'</option>
					 <option value="'.$aux_arancel_2.'" '.$select_2.'>'.$aux_arancel_2.'</option>';
					 
	$campo_arancel.='</select>';
	//---------------------------------------------------------------------------------------//
	
	
	
		
		$objResponse->Assign('matricula',"value",$aux_matricula);
		$objResponse->Assign('div_arancel',"innerHTML",$campo_arancel." Arancel Anual: $".($aux_arancel_1+$aux_arancel_2));
		//$objResponse->Assign("fecha_fin","value", $expira);
		$objResponse->Assign("arancel_anual","value", ($aux_arancel_1+$aux_arancel_2));
		//$objResponse->replace("fecha_fin","Value","xajax", $expira);
	$conexion_mysqli->close();
	return $objResponse;
}
function ACTUALIZA_FECHAS($semestre, $year)
{
	$objResponse = new xajaxResponse();
	$mes_actual=date("m");
	if($mes_actual>=8){$semestre_actual=2;}
	else{ $semestre_actual=1;}
	
	if($semestre==$semestre_actual)
	{
		$fecha_inicio=date("d-m-Y");
		
		if($semestre==1){$fecha_fin="30-08-$year";}
		else{$fecha_fin="30-12-$year";}
	}
	else
	{
		if($semestre==1){$fecha_inicio="01-03-$year"; $fecha_fin="30-08-$year";}
		else{$fecha_inicio="01-08-$year"; $fecha_fin="30-12-$year";}
	}
	
	//$objResponse->Alert("fecha inicio:".$fecha_inicio."\n Fecha Fin: ".$fecha_fin);
	$objResponse->Assign("fecha_inicio","value", $fecha_inicio);
	$objResponse->Assign("fecha_fin","value", $fecha_fin);
	return $objResponse;
}

function CARGA_CARRERAS($yearContrato, $yearIngresoCarrera, $jornada, $sede)
{
	$objResponse = new xajaxResponse();
	$FORZARmostrarTodasLasCarreras=false;//muestra todas las carreras sin importar si tiene vacantes o acepta matricula de nuevos
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
		
		$campoCarrera='<select name="carrera" id="carrera" onchange="xajax_BUSCA_VALORES_CARRERA(document.getElementById(\'carrera\').value, document.getElementById(\'year_estudio\').value, document.getElementById(\'lugar_contrato\').value, document.getElementById(\'semestre\').value)">
						<option>Seleccione</option>';
		
		$esAlumnoNuevo=false;
		if($yearContrato==$yearIngresoCarrera){$esAlumnoNuevo=true;}
		
		if($yearContrato>=2021){
			$consC="SELECT DISTINCT (id_madre_carrera), permite_matricula_nuevos, vacantesDiurno, vacantesVespertino FROM hija_carrera_valores WHERE year='$yearContrato' AND sede='$sede'";	
			
			//$objResponse->alert("$consC");
			$sqliC=$conexion_mysqli->query($consC)or die($conexion_mysqli->error);
			while($C=$sqliC->fetch_assoc()){
				
				$aux_idCarrera=$C["id_madre_carrera"];
				$auxPermiteMatriculaNuevos=$C["permite_matricula_nuevos"];
				$aux_vacantesDiurno=$C["vacantesDiurno"];
				$aux_vacantesVespertino=$C["vacantesVespertino"];
				
				$mostrarCarrera=false;
				if($esAlumnoNuevo){
					if($auxPermiteMatriculaNuevos=="1"){
						if(($jornada=="D")and($aux_vacantesDiurno>0)){$mostrarCarrera=true;}
						elseif(($jornada=="V")and($aux_vacantesVespertino>0)){$mostrarCarrera=true;}
					}
				}
				else{$mostrarCarrera=true;}
			
				
				if($mostrarCarrera){
					$campoCarrera.='<option value="'.$aux_idCarrera.'">'.$aux_idCarrera.'_'.NOMBRE_CARRERA($aux_idCarrera,true).'</option>';	
				}
				
			}
			
			
			$campoCarrera.='</select>';
		}else{$FORZARmostrarTodasLasCarreras=true;}
		
		if($FORZARmostrarTodasLasCarreras){$campoCarrera=CAMPO_SELECCION("carrera","carreras", $id_carrera, false,'onchange="xajax_BUSCA_VALORES_CARRERA(document.getElementById(\'carrera\').value, document.getElementById(\'year_estudio\').value, document.getElementById(\'lugar_contrato\').value, document.getElementById(\'semestre\').value); return false;"','carrera');}
		$sqliC->free();
		
	$objResponse->Assign('div_carreras',"innerHTML",$campoCarrera);
	
	$conexion_mysqli->close();
	return $objResponse;
}
////////////////
$xajax->processRequest();
?>