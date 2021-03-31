<?php
session_start();
define("DEBUG", false);
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("autoriza_solicitud_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"VERIFICA_SOLICITUD");
////////////////////////////////////////////

function VERIFICA_SOLICITUD($FORMULARIO)
{
	include("../../../funciones/funciones_sistema.php");
	require("../../../funciones/conexion_v2.php");
	
	$continuar=false;
	$msj_error="";
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	
	//var_dump($FORMULARIO);
	if((isset($FORMULARIO["tipo"]))and(isset($FORMULARIO["categoria"])))
	{
		$realizar_verificacion=true;
		
		$S_tipo=$FORMULARIO["tipo"];
		$S_categoria=$FORMULARIO["categoria"];
	
		if(isset($FORMULARIO["observacion"]))
		{$S_observacion=$FORMULARIO["observacion"];}
		else{$S_observacion="";}
	}
	else
	{
		$realizar_verificacion=false;
	}
	
	$objResponse = new xajaxResponse();

	///realizar verificacion
	if($realizar_verificacion)
	{
		if(DEBUG){$objResponse->Alert("INICIA VERIFICACION $S_tipo -> $S_categoria => $S_observacion");}
		switch($S_tipo)
		{
			case"certificado":
				switch($S_categoria)
				{
					case"alumno_regular":
						 $alumno_vigente=VERIFICAR_MATRICULA($id_alumno, $id_carrera, true);
						 if($alumno_vigente)
						 { $continuar=true;}
						 else
						 { $msj_error="No Se puede Realizar esta Solicitud Este Alumno No tiene Contrato Vigente...";}
						break;
					case"titulo":
						$cons_pt="SELECT * FROM proceso_titulacion WHERE id_alumno='$id_alumno' ORDER by id desc LIMIT 1";
						if(DEBUG){ echo $cons_pt;}
						
						$sql_pt=$conexion_mysqli->query($cons_pt);
						$num_reg_pt=$sql_pt->num_rows;
						if($num_reg_pt>0)
						{
							while($PT=$sql_pt->fetch_assoc())
							{
								$aux_examen_condicion=$PT["examen_condicion"];
								$aux_examen_fecha=$PT["examen_fecha"];
								$aux_nombre_titulo=$PT["nombre_titulo"];
								if($aux_examen_condicion=="aprobado")
								{ $continuar=true;}
								else
								{ $msj_error="Examen Registrado Como Pendiente en Proceso Titulacion...";}
							}
						}
						else
						{ $msj_error="No Se puede Realizar esta Solicitud este Alumno No tiene Proceso de Titulacion...";}
						$sql_pt->free();
						break;
					case"concentracion_notas":
						$cons_ra="SELECT COUNT(id) FROM notas WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera'";
						$sql_ra=$conexion_mysqli->query($cons_ra);
							$D_ra=$sql_ra->fetch_row();
							$num_registros_academicos=$D_ra[0];
							if(empty($num_registros_academicos)){ $num_registros_academicos=0;}
							if(DEBUG){ echo"$cons_ra<br>NUm Registros academicos: $num_registros_academicos<br>";}
						$sql_ra->free();	
						if($num_registros_academicos>0)
						{ $continuar=true;}
						else
						{ $msj_error="No Se puede Realizar esta Solicitud este Alumno No tiene Registro Academico Creado...";}
						break;
					case"egreso":
						$cons_pt="SELECT proceso_titulacion.*, alumno.situacion FROM proceso_titulacion INNER JOIN alumno ON proceso_titulacion.id_alumno=alumno.id WHERE proceso_titulacion.id_alumno='$id_alumno' ORDER by id desc LIMIT 1";
						if(DEBUG){$objResponse->alert( $cons_pt);}
						$sql_pt=$conexion_mysqli->query($cons_pt);
						$num_reg_pt=$sql_pt->num_rows;
						if($num_reg_pt>0)
						{
							$PT=$sql_pt->fetch_assoc();
							$alumno_situacion=strtoupper($PT["situacion"]);
							if(DEBUG){$objResponse->alert( "Situacion Actual Alumno: $alumno_situacion");}
							if(($alumno_situacion=="EG")or($alumno_situacion=="T"))
							{ $continuar=true;}
							else
							{ $msj_error="Condicion de Academica de Alumno no es egresado ni titulado...";}	
						}
						else
						{ $msj_error="No Se puede Realizar esta Solicitud este Alumno No tiene Proceso de Titulacion...";}
						$sql_pt->free();	
						break;
					case"plan_curricular":
						$cons_pt="SELECT proceso_titulacion.*, alumno.situacion FROM proceso_titulacion INNER JOIN alumno ON proceso_titulacion.id_alumno=alumno.id WHERE proceso_titulacion.id_alumno='$id_alumno' ORDER by id desc LIMIT 1";
						if(DEBUG){$objResponse->alert( $cons_pt);}
						
						$sql_pt=$conexion_mysqli->query($cons_pt);
						$num_reg_pt=$sql_pt->num_rows;
						if($num_reg_pt>0)
						{
							$PT=$sql_pt->fetch_assoc();
							$alumno_situacion=strtoupper($PT["situacion"]);
							if(DEBUG){$objResponse->alert( "Situacion Actual Alumno: $alumno_situacion");}
							if(($alumno_situacion=="EG")or($alumno_situacion=="T"))
							{ $continuar=true;}
							else
							{ $msj_error="Condicion de Academica de Alumno no es egresado ni titulado...";}	
						}
						else
						{ $msj_error="No Se puede Realizar esta Solicitud este Alumno No tiene Proceso de Titulacion...";}
						$sql_pt->free();	
						break;	
						
				}
				break;
			default:	
				$continuar=false;
				$msj_error="Tipo no compatible";
		}
	}//fin si realizar verificacion
	else
	{ $continuar=true; if(DEBUG){$objResponse->Alert("No Verificar");}}
	//----------------------------------------------//
	$conexion_mysqli->close();
	
	if($continuar)
	{
		if(!DEBUG){$objResponse->script("c=confirm('Seguro(a) desea Continuar Generando esta Solicitud...?'); if(c){document.getElementById('frm').submit();}");}
	}
	else
	{$objResponse->Alert($msj_error);}
	
	return $objResponse;
}
////////////////
$xajax->processRequest();
?>