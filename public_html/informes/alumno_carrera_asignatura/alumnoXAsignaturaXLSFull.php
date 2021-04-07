<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_alumnos_X_Asignatura_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
	$tabla="";

	$year=$_GET["year"];
	$sede=$_GET["sede"];
	$semestre=$_GET["semestre"];
	$id_carrera_consulta=$_GET["id_carrera"];
	$mostrar_solo_alumnos_con_matricula=true;
	
	
	if($id_carrera_consulta=="0")
	{ $condicion_carrera="";}
	else
	{ $condicion_carrera=" AND toma_ramos.id_carrera='$id_carrera_consulta' AND alumno.id_carrera='$id_carrera_consulta'";}
	
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	require("../../../funciones/class_ALUMNO.php");
	
	$html="";
	if(DEBUG){echo"año: $year semestre: $semestre sede: $sede<br>";}
	//-------------------------------------------------------------------------------//
	
	$cons_MAIN="SELECT toma_ramos.id_alumno, toma_ramos.id_carrera, toma_ramos.yearIngresoCarrera, toma_ramos.cod_asignatura, alumno.sede, toma_ramos.jornada, toma_ramos.nivel, alumno.grupo FROM toma_ramos INNER JOIN alumno ON toma_ramos.id_alumno=alumno.id WHERE toma_ramos.semestre='$semestre' AND toma_ramos.year='$year' AND alumno.sede='$sede' $condicion_carrera ORDER by alumno.sede, toma_ramos.id_carrera, toma_ramos.nivel, alumno.jornada, toma_ramos.cod_asignatura";
	if(DEBUG){echo "<br>$cons_MAIN<br>";}
	
	$sqli=$conexion_mysqli->query($cons_MAIN)or die($conexion_mysqli->error);
	$num_registros=$sqli->num_rows;
	
	
	$tabla.='<table border="1" width="100%">
	<thead>
		<tr>
			<th colspan="12">Listado de Alumnos X Asignatura periodo '.$semestre.' - '.$year.'</th>
		</tr>
	</thead>
	<tbody>
	<tr>
		<td>Semestre</td>
		<td>Año</td>
		<td>Sede</td>
		<td>Carrera</td>
		<td>yearIngresoCarrera</td>
		<td>Jornada</td>
		<td>Nivel Asignatura</td>
		<td>Grupo</td>
		<td>Cod Asignaturas (interno)</td>
		<td>Asignaturas</td>
		<td>Rut</td>
		<td>Nombre</td>
		<td>apellido P</td>
		<td>Apellido M</td>
		<td>Correo institucional</td>
	</tr>';

	if($num_registros>0)
	{
		$html.='<strong>Periodo: '.$semestre.' Semestre - '.$year.'</strong><br>';
		if(DEBUG){$html.="$num_registros Registros<br>";}
		while($TM=$sqli->fetch_assoc())
		{
			$id_alumno=$TM["id_alumno"];
			$id_carrera_alumno=$TM["id_carrera"];
			$yearIngresoCarrera=$TM["yearIngresoCarrera"];
			
			$cod_asignatura=$TM["cod_asignatura"];
			$sede_alumno=$TM["sede"];
			$jornada_alumno=$TM["jornada"];//actualizado toma de ramos
			$nivel_alumno=$TM["nivel"];
			$grupo_alumno=$TM["grupo"];
			
			

			list($aux_nombre_asignatura, $R_nivel)=NOMBRE_ASIGNACION($id_carrera_alumno, $cod_asignatura);
				
			$situacion_alumno_en_periodo=ESTADO_ALUMNO_PERIODO($id_alumno, $id_carrera_alumno, $yearIngresoCarrera,$semestre, $year);
			$alumno_con_matricula=VERIFICAR_MATRICULA($id_alumno, $id_carrera_alumno,$yearIngresoCarrera, true, false, $semestre, false, $year);
			
			
			
			if($mostrar_solo_alumnos_con_matricula)
			{
				if($alumno_con_matricula){$utilizar_alumno=true;}
				else{$utilizar_alumno=false;}
			}
			else{ $utilizar_alumno=true;}
			//-----------------------------------------------------//
			if(($situacion_alumno_en_periodo=="V")or($situacion_alumno_en_periodo=="EG"))
			{ $utilizar_alumno_2=true;}
			else
			{ $utilizar_alumno_2=false;}
			//-----------------------------------------------------///
			if($utilizar_alumno and $utilizar_alumno_2)
			{
				$Alumno=new ALUMNO($id_alumno);

				$tabla.='
				<tr>
					<td>'.$semestre.'</td>
					<td>'.$year.'</td>
					<td>'.$sede.'</td>
					<td bgcolor="'.COLOR_CARRERA($id_carrera_alumno).'">'.utf8_decode(NOMBRE_CARRERA($id_carrera_alumno)).'</td>
					<td>'.$yearIngresoCarrera.'</td>
					<td>'.$jornada_alumno.'</td>
					<td>'.$R_nivel.'</td>
					<td>'.$grupo_alumno.'</td>
					<td>'.$cod_asignatura.'</td>
					<td>'.utf8_decode($aux_nombre_asignatura).'</td>
					<td>'.$Alumno->getRut().'</td>
					<td>'.$Alumno->getNombre().'</td>
					<td>'.$Alumno->getApellido_P().'</td>
					<td>'.$Alumno->getApellido_M().'</td>
					<td>'.$Alumno->getEmailInstitucional().'</td>
				</tr>';
			}
		}
		
	}
	else
	{
		$html.="Sin Registros... :(<br>";
		
	}
	
	
	$tabla.='</tbody></table><br>';
	$html.=$tabla;
	$sqli->free();
	$conexion_mysqli->close();

	if(DEBUG){}
	else{//-------------------------------------------------------------------------------//
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=ListadoAlumnosXAsignatura_".$semestre."_".$year."".$sede.".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		//--------------------------------------------------------------------------------//
	}
	echo $html."<br>Generado el ". date("d-m-Y H:i:s");
	
	
}
?>