<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("SOLICITUDES->verCertificados");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("crea_solicitud_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"VERIFICA_SOLICITUD");
////////////////////////////////////////////

function VERIFICA_SOLICITUD($FORMULARIO)
{
	require("../../../funciones/funciones_sistema.php");
	require("../../../funciones/conexion_v2.php");
	
	$continuar=false;
	$msj_error="";
	
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	$tipoUsuario=$_SESSION["USUARIO"]["tipo"];

	switch($tipoUsuario)
	{
		case"alumno":
			$id_alumno=$_SESSION["USUARIO"]["id"];
			$id_carrera=$_SESSION["USUARIO"]["id_carrera"];
			$yearIngresoCarrera=$_SESSION["USUARIO"]["yearIngresoCarrera"];
			$situacion_academica=$_SESSION["USUARIO"]["situacion"];
			//$situacion_academica="V";
			
			break;
		default:	
			$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
			$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
			$yearIngresoCarrera=$_SESSION["SELECTOR_ALUMNO"]["yearIngresoCarrera"];
			$situacion_academica=strtoupper($_SESSION["SELECTOR_ALUMNO"]["situacion"]);
	}
	
	$S_tipo=$FORMULARIO["tipo"];
	$S_categoria=$FORMULARIO["categoria"];
	if(isset($FORMULARIO["observacion"])){$S_observacion=$FORMULARIO["observacion"];}
	else{$S_observacion="";}
	$S_semestre=$FORMULARIO["semestre"];
	$S_year=$FORMULARIO["year"];
	
	$objResponse = new xajaxResponse();

	//$objResponse->Alert("INICIA VERIFICACION $S_tipo -> $S_categoria => $S_observacion [$S_semestre - $S_year]");
	//var_dump($FORMULARIO);
	switch($S_tipo)
	{
		case"certificado":
			$dias_morosidad_maximo=30;
			$condicionar_situacion_financiera_alumno=false;
			switch($S_categoria)
			{
				case"alumno_regular":
					$msjMoroso='';
					$alumno_vigente=VERIFICAR_MATRICULA($id_alumno, $id_carrera,$yearIngresoCarrera, true, false, $S_semestre, false, $S_year, true);
					$alumno_vigente=true;
					$situacion_academica="V";
					$dias_morosidad=DIAS_MOROSIDAD($id_alumno);
					if($condicionar_situacion_financiera_alumno){
						if($dias_morosidad>$dias_morosidad_maximo){ $es_moroso=true; $msjMoroso='Alumno con Situacion Financiera Pendiente';}
						else{$es_moroso=false;}
					}else{$es_moroso=false;}
					
						
					if(($alumno_vigente)and(!$es_moroso)and(($situacion_academica=="V")or($situacion_academica=="EG")))
					 { $continuar=true;}
					 else
					 { $msj_error="No Se puede Realizar esta Solicitud \n por alguna de las siguientes Razones\n -Este Alumno No tiene Contrato Vigente en el periodo [$S_semestre - $S_year]...\n- No esta Academicamente Vigente...\n-".$msjMoroso;}
					

					break;
				case"certificado_titulo":
					$cons_pt="SELECT * FROM proceso_titulacion WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' ORDER by id desc LIMIT 1";
					if(DEBUG){ echo $cons_pt;}
					$sql_pt=$conexion_mysqli->query($cons_pt)or die($conexion_mysqli->error);
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
				case"titulo_en_tramite":
					$cons_pt="SELECT * FROM proceso_titulacion WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' ORDER by id desc LIMIT 1";
					if(DEBUG){ echo $cons_pt;}
					$sql_pt=$conexion_mysqli->query($cons_pt)or die($conexion_mysqli->error);
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
				case"copia_titulo":
					$cons_pt="SELECT * FROM proceso_titulacion WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' ORDER by id desc LIMIT 1";
					if(DEBUG){ echo $cons_pt;}
					$sql_pt=$conexion_mysqli->query($cons_pt)or die($conexion_mysqli->error);
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
				case"concentracion_notas_HRS":
					//echo"INICIO CONCENTRACION NOTAS<br>";
					$cons_ra="SELECT COUNT(id) FROM notas WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera'";
					$sql_ra=$conexion_mysqli->query($cons_ra)or die($conexion_mysqli->error);
						$D_ra=$sql_ra->fetch_row();
						$num_registros_academicos=$D_ra[0];
						if(empty($num_registros_academicos)){ $num_registros_academicos=0;}
						if(DEBUG){ echo"$cons_ra<br>Num Registros academicos: $num_registros_academicos<br>";}
					$sql_ra->free();
					if($num_registros_academicos>0)
					{ $continuar=true;}
					else
					{ $msj_error="No Se puede Realizar esta Solicitud este Alumno No tiene Registro Academico Creado...";}
					break;
				case"concentracion_notas":
					//echo"INICIO CONCENTRACION NOTAS<br>";
					$cons_ra="SELECT COUNT(id) FROM notas WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera'";
					$sql_ra=$conexion_mysqli->query($cons_ra)or die($conexion_mysqli->error);
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
					list($es_egresado, $semestreEgreso, $yearEgreso)=ES_EGRESADO_V2($id_alumno, $id_carrera, $yearIngresoCarrera);
					if(($es_egresado)and ($situacion_academica=="EG")){
						if(DEBUG){$objResponse->alert( "Situacion Actual Alumno");}
						$continuar=true;
					}
					else{ $msj_error="Condicion de Academica de Alumno no es EGRESADO";}
					
					break;
				case"hola":
						$objResponse->alert("Va a continuar si o si");
						$continuar=true;
				
					break;	
				case"plan_curricular":
					$cons_pt="SELECT proceso_titulacion.*, alumno.situacion FROM proceso_titulacion INNER JOIN alumno ON proceso_titulacion.id_alumno=alumno.id WHERE proceso_titulacion.id_alumno='$id_alumno' AND proceso_titulacion.id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' ORDER by id desc LIMIT 1";
					if(DEBUG){$objResponse->alert( $cons_pt);}
					$sql_pt=$conexion_mysqli->query($cons_pt)or die($conexion_mysqli->error);
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
	}
	
	$conexion_mysqli->close();
	
	
	if($continuar)
	{

		if(!DEBUG){$objResponse->script("c=confirm('Seguro(a) desea Continuar Generando esta Solicitud de $S_tipo -> $S_categoria...?'); if(c){document.getElementById('frm').submit();}");}
	}
	else
	{$objResponse->Alert($msj_error);}
	
	return $objResponse;
}
////////////////
$xajax->processRequest();
?>