<?php
session_start();
define("DEBUG", false);
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("autoriza_solicitud_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CARGA_ASISTENCIA");
$xajax->register(XAJAX_FUNCTION,"CARGA_LISTA_ASISTENCIA");
////////////////////////////////////////////

function CARGA_ASISTENCIA($sede, $semestre, $year, $id_carrera, $jornada)
{
	require("../../../funciones/funciones_sistema.php");
	require("../../../funciones/conexion_v2.php");
	
	$objResponse = new xajaxResponse();
	if(DEBUG){$objResponse->Alert("Sede: $sede Periodo [$semestre - $year] carrera: $id_carrera Jornada: $jornada");}
	
	$cons_TR="SELECT DISTINCT(toma_ramos.cod_asignatura) FROM toma_ramos WHERE id_carrera='$id_carrera' AND semestre='$semestre' AND year='$year' AND jornada='$jornada'";
	
	$sql_TR=$conexion_mysqli->query($cons_TR)or die($conexion_mysqli->error);
	$num_asignatura=$sql_TR->num_rows;
	
	//---------------------------------------------------------------//
	$mostrar_boton=false;
	$boton='<a href="#" class="button_G" onclick="xajax_CARGA_LISTA_ASISTENCIA(document.getElementById(\'sede\').value, document.getElementById(\'semestre\').value, document.getElementById(\'year\').value, document.getElementById(\'id_carrera\').value, document.getElementById(\'jornada\').value, document.getElementById(\'grupo\').value, document.getElementById(\'asignatura\').value);">Ver Asistencia</a>';
	
	$campo_asignaturas='<select name="asignatura" id="asignatura">
						<option>seleccione</option>';
	if($num_asignatura>0)
	{
		while($A=$sql_TR->fetch_row())
		{
			$A_cod=$A[0];
			list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $A_cod);
			$campo_asignaturas.='<option value="'.$A_cod.'">'.$A_cod.'_'.utf8_decode($nombre_asignatura).'</option>';
			$mostrar_boton=true;
		}
	}
	else
	{}
	$campo_asignaturas.='</select>';
	
	//--------------------------------------------------------------//
	if($mostrar_boton)
	{$objResponse->Assign("div_boton","innerHTML",$boton);}
	$objResponse->Assign("div_asignatura","innerHTML",$campo_asignaturas);
	
	
	$sql_TR->free();
	$conexion_mysqli->close();
	return $objResponse;
}
function CARGA_LISTA_ASISTENCIA($sede, $semestre, $year, $id_carrera, $jornada,$grupo, $cod_asignatura)
{
	require("../../../funciones/funciones_sistema.php");
	require("../../../funciones/conexion_v2.php");
	
	$objResponse = new xajaxResponse();
	
	$cons_TR="SELECT toma_ramos.id_alumno, alumno.rut, alumno.nombre, alumno.apellido_P, alumno.apellido_M FROM toma_ramos INNER JOIN alumno ON toma_ramos.id_alumno=alumno.id WHERE toma_ramos.id_carrera='$id_carrera' AND toma_ramos.jornada='$jornada' AND toma_ramos.cod_asignatura='$cod_asignatura' AND toma_ramos.semestre='$semestre' AND toma_ramos.year='$year' AND alumno.sede='$sede'";
	$sqli_TR=$conexion_mysqli->query($cons_TR)or die($conexion_mysqli->error);
	$num_alumnos=$sqli_TR->num_rows;
	
	
	$objResponse->Alert("Sede: $sede Periodo [$semestre - $year] carrera: $id_carrera Jornada: $jornada Asignatura: $cod_asignatura");
	
	$html_tabla='<table width="100%">
					<thead>
						<tr>
							<th colspan="5">Lista de Alumnos</th>
						</tr>
						<tr>
							<td>N</td>
							<td>Rut</td>
							<td>Nombre</td>
							<td>Apellido_P</td>
							<td>Apellido_M</td>
							
						</tr>
					</thead>
					<tbody>';
	if($num_alumnos>0)
	{
		$aux=0;
		while($A=$sqli_TR->fetch_assoc())
		{
			$aux++;
			$A_rut=$A["rut"];
			$A_nombre=$A["nombre"];
			$A_apellido_M=$A["apellido_M"];
			$A_apellido_P=$A["apellido_P"];
			
			$html_tabla.='<tr>
							<td>'.$aux.'</td>
							<td>'.$A_rut.'</td>
							<td>'.utf8_decode($A_nombre).'</td>
							<td>'.utf8_decode($A_apellido_P).'</td>
							<td>'.utf8_decode($A_apellido_M).'</td>
						  </tr>';
		}
	}
	else
	{}
	$sqli_TR->free();
	
	$html_tabla.='</tbody></table>';
	
	$objResponse->Assign("div_lista","innerHTML",$html_tabla);
	return $objResponse;
}
////////////////
$xajax->processRequest();
?>
