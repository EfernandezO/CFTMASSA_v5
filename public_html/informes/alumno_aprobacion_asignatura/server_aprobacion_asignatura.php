<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_aprobacion_GENERAL V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

if($_POST)
{
	$yearNotas=$_POST["yearNotas"];
	$yearCohorte=$_POST["yearCohorte"];
	
	if(DEBUG){}
		else
		{
			header('Content-type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=aprobacionGeneral_$yearNotas.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
	
	if($yearCohorte==0){$campoYearIngreso="";}
	else{$campoYearIngreso="yearIngresoCarrera='$yearCohorte' AND";}
	
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	require("../../../funciones/class_ALUMNO.php");
	
	//---------------------------------------------//
	include("../../../funciones/VX.php");
	$evento="Revisa informe Aprobacion General yearNotas $yearNotas yearCohorte $yearCohorte";
	REGISTRA_EVENTO($evento);
	//----------------------------------------------//
	
	$datos_tabla='Aprobacion General Cohorte '.$yearCohorte.' - Notas '.$yearNotas.' <br>Generado el '.date("d-m-Y H:i:s").'<table width="100%" id="example" border="1">
<thead>
	<tr>
		<th>N.</td>
		<th>Carrera</td>
		<th>Año Notas</td>
		<th>Año Ingreso (cohorte)</td>
		<th>Tipo Alumno</th>
		<th>idAlumno</td>
		<th>rut</td>
		<th>Jornada</td>
		<th>Sede</td>
		<th>Situacion Alumno</td>
		<th>ramos inscritos 1 semestre</td>
		<th>ramos aprobados 1 semestre</td>
		<th>ramos reprobados 1 semestre</td>
		<th>% aprobacion 1 semestre</td>
		<th>ramos inscritos 2 semestre</td>
		<th>ramos aprobados 2 semestre</td>
		<th>ramos reprobados 2 semestre</td>
		<th>% aprobacion 2 semestre</td>
		<th>ramos inscritos año</td>
		<th>ramos aprobados año</td>
		<th>ramos reprobados año</td>
		<th>% aprobacion año</td>
	</tr>
</thead>
<tbody>';
		
		$cons="SELECT DISTINCT(id_alumno), id_carrera, yearIngresoCarrera FROM toma_ramos WHERE $campoYearIngreso year='$yearNotas'";
		$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$num_registros=$sqli->num_rows;
		if($num_registros>0)
		{
			$contador=1;
			$SUMA_INSCRITOS=array();
			$SUMA_APROBADOS=array();
			$SUMA_REPROBADOS=array();
			while($N=$sqli->fetch_assoc())
			{
				$tipoAlumno="";
				
				$TR_idAlumno=$N["id_alumno"];
				$TR_idCarrera=$N["id_carrera"];
				$TR_yearIngresoCarrera=$N["yearIngresoCarrera"];
				
				if($TR_yearIngresoCarrera==$yearNotas){$tipoAlumno="Nuevo";}
				else{$tipoAlumno="Antiguo";}
				
				$ALUMNO=new ALUMNO($TR_idAlumno);
				$ALUMNO->SetDebug(DEBUG);
				
				$ALUMNO->IR_A_PERIODO(1,$yearNotas);
				
				$A_presenteEnPeriodo=$ALUMNO->getPresenteEnPeriodo();
				
				
				$array_ramos_inscritos_periodo_1=RAMOS_INSCRITOS_TOMA_RAMO($TR_idAlumno, $TR_idCarrera, $TR_yearIngresoCarrera, $yearNotas, "1");
				$numero_ramos_inscritos_periodo_aprobados_1=count($array_ramos_inscritos_periodo_1["aprobado"]);
				$numero_ramos_inscritos_periodo_reprobados_1=count($array_ramos_inscritos_periodo_1["reprobado"]);
				$numero_ramos_inscritos_periodo_total_1=($numero_ramos_inscritos_periodo_aprobados_1+$numero_ramos_inscritos_periodo_reprobados_1);
				
				$SUMA_INSCRITOS[1]+=$numero_ramos_inscritos_periodo_total_1;
				$SUMA_APROBADOS[1]+=$numero_ramos_inscritos_periodo_aprobados_1;
				$SUMA_REPROBADOS[1]+=$numero_ramos_inscritos_periodo_reprobados_1;
				
				if($numero_ramos_inscritos_periodo_total_1>0)
				{$porcentajeAprobacionPeriodo_1=(($numero_ramos_inscritos_periodo_aprobados_1*100)/$numero_ramos_inscritos_periodo_total_1);}
				else{$porcentajeAprobacionPeriodo_1=0;}
				
				
				
				$array_ramos_inscritos_periodo_2=RAMOS_INSCRITOS_TOMA_RAMO($TR_idAlumno, $TR_idCarrera, $TR_yearIngresoCarrera, $yearNotas, "2");
				$numero_ramos_inscritos_periodo_aprobados_2=count($array_ramos_inscritos_periodo_2["aprobado"]);
				$numero_ramos_inscritos_periodo_reprobados_2=count($array_ramos_inscritos_periodo_2["reprobado"]);
				$numero_ramos_inscritos_periodo_total_2=($numero_ramos_inscritos_periodo_aprobados_2+$numero_ramos_inscritos_periodo_reprobados_2);
				
				$SUMA_INSCRITOS[2]+=$numero_ramos_inscritos_periodo_total_2;
				$SUMA_APROBADOS[2]+=$numero_ramos_inscritos_periodo_aprobados_2;
				$SUMA_REPROBADOS[2]+=$numero_ramos_inscritos_periodo_reprobados_2;
				
				if($numero_ramos_inscritos_periodo_total_2>0)
				{$porcentajeAprobacionPeriodo_2=(($numero_ramos_inscritos_periodo_aprobados_2*100)/$numero_ramos_inscritos_periodo_total_2);}
				else{$porcentajeAprobacionPeriodo_2=0;}
				
				
				$numero_ramos_inscritos_periodo_aprobados=($numero_ramos_inscritos_periodo_aprobados_1+$numero_ramos_inscritos_periodo_aprobados_2);
				$numero_ramos_inscritos_periodo_reprobados=($numero_ramos_inscritos_periodo_reprobados_1+$numero_ramos_inscritos_periodo_reprobados_2);
				$numero_ramos_inscritos_periodo_total=($numero_ramos_inscritos_periodo_total_1+$numero_ramos_inscritos_periodo_total_2);
				
				if($numero_ramos_inscritos_periodo_total>0)
				{$porcentajeAprobacionPeriodo=(($numero_ramos_inscritos_periodo_aprobados*100)/$numero_ramos_inscritos_periodo_total);}
				else{$porcentajeAprobacionPeriodo=0;}
				
				$validador=md5("GDXT".date("d-m-Y"));
				$url_destino='../../buscador_alumno_BETA/enrutador.php?validador='.$validador.'&id_alumno='.$TR_idAlumno;
				
				if($A_presenteEnPeriodo){
					$datos_tabla.='<tr>
									<td>'.$contador.'</td>
									<td>'.utf8_decode(NOMBRE_CARRERA($TR_idCarrera)).'</td>
									<td>'.$yearNotas.'</td>
									<td>'.$TR_yearIngresoCarrera.'</td>
									<td>'.$tipoAlumno.'</td>
									<td><a href="'.$url_destino.'" target="_blank">'.$TR_idAlumno.'</a></td>
									<td>'.$ALUMNO->getRut().'</td>
									<td>'.$ALUMNO->getJornadaPeriodo().'</td>
									<td>'.$ALUMNO->getSedeAlumnoPeriodo().'</td>
									<td>'.$ALUMNO->getSituacionAlumnoPeriodo().'</td>
									
									<td>'.$numero_ramos_inscritos_periodo_total_1.'</td>
									<td>'.$numero_ramos_inscritos_periodo_aprobados_1.'</td>
									<td>'.$numero_ramos_inscritos_periodo_reprobados_1.'</td>
									<td>'.number_format($porcentajeAprobacionPeriodo_1,1).'</td>
									
									<td>'.$numero_ramos_inscritos_periodo_total_2.'</td>
									<td>'.$numero_ramos_inscritos_periodo_aprobados_2.'</td>
									<td>'.$numero_ramos_inscritos_periodo_reprobados_2.'</td>
									<td>'.number_format($porcentajeAprobacionPeriodo_2,1).'</td>
									
									<td>'.$numero_ramos_inscritos_periodo_total.'</td>
									<td>'.$numero_ramos_inscritos_periodo_aprobados.'</td>
									<td>'.$numero_ramos_inscritos_periodo_reprobados.'</td>
									<td>'.number_format($porcentajeAprobacionPeriodo,1).'</td>
									
									</tr>';
									$contador++;	
					}
				}
				
				/*$datos_tabla.='<tr>
									<td>TOTAL</td>
									
									
									<td>'.$SUMA_INSCRITOS[1].'</td>
									<td>'.$SUMA_APROBADOS[1].'</td>
									<td>'.$SUMA_REPROBADOS[1].'</td>
									<td></td>
									
									<td>'.$SUMA_INSCRITOS[2].'</td>
									<td>'.$SUMA_APROBADOS[2].'</td>
									<td>'.$SUMA_REPROBADOS[2].'</td>
									<td></td>
									
									<td>'.$SUMA_INSCRITOS[1]+$SUMA_INSCRITOS[2].'</td>
									<td>'.$SUMA_APROBADOS[1]+$SUMA_APROBADOS[2].'</td>
									<td>'.$SUMA_REPROBADOS[1]+ $SUMA_REPROBADOS[2].'</td>
									<td></td>
								</tr>';
								*/
				
			}
		
	$datos_tabla.='</tbody></table>';

	$sqli->free();
	$conexion_mysqli->close();
	echo $datos_tabla;
}		
?>