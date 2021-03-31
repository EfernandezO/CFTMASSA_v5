<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("informe_docente_Asig_tit_antiguedad");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
	if(DEBUG){ var_dump($_GET);}
	$semestre=$_GET["semestre"];
	$year=$_GET["year"];
	$sede=$_GET["sede"];
	$continuar=true;
}
else{ $continuar=false;}
if(!DEBUG)
	{
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=informe_docente.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
$tabla='<table width="100%" border="1" align="center">
<thead>
  <tr>
    <th colspan="12">Resumen Docentes '.$sede.' ['.$semestre.'-'.$year.']</th>
  </tr>
  <tr>
  	<td>N</td>
    <td>Rut</td>
    <td>Nombre</td>
    <td>Apellido</td>
    <td>Titulo</td>
    <td>Institucion donde Obtuvo</td>
     <td>Años CFT</td>
    <td>Total Horas Contrato</td>
    <td>Tipo Contrato</td>
    <td>Sede</td>
    <td>Carrera</td>
    <td>Asignatura</td>
  </tr>
  </thead>
  <tbody>';

if($continuar)
{
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funciones_sistema.php");
	require("../../../../funciones/VX.php");
	
	$sede=mysqli_real_escape_string($conexion_mysqli, $sede);
    $semestre=mysqli_real_escape_string($conexion_mysqli, $semestre);
    $year=mysqli_real_escape_string($conexion_mysqli, $year);
	
	$evento="Exporta -> XLS Informe Docentes - Asignaturas - antiguedad - titulo para sede: $sede periodo [$semestre - $year]";
	REGISTRA_EVENTO($evento);
	
$condicionSede="";
	$condicionSede2="";
	if($sede!=="todas"){$condicionSede="toma_ramo_docente.sede='$sede' AND"; $condicionSede2="AND sede='$sede'";}
	
$cons="SELECT DISTINCT(id_funcionario) FROM toma_ramo_docente INNER JOIN personal ON toma_ramo_docente.id_funcionario=personal.id WHERE $condicionSede toma_ramo_docente.semestre='$semestre' AND toma_ramo_docente.year='$year' ORDER by personal.apellido_P, personal.nombre";
	if(DEBUG){ echo"---> $cons<br>";}
	
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_registros=$sqli->num_rows;
	$aux=0;
	if($num_registros>0)
	{
		while($F=$sqli->fetch_row())
		{
			$F_id=$F[0];
			$TOTAL_HORAS_CONTRATO=0;
			//---------------------------------------------//
			//grado academico
			$cons_GA="SELECT MIN(cod_grado_academico) FROM personal_registro_estudios WHERE id_funcionario='$F_id' AND cod_grado_academico<>'NULL' AND cod_grado_academico<>''";
			$sqli_ga=$conexion_mysqli->query($cons_GA)or die($conexion_mysqli->error);
			$GA=$sqli_ga->fetch_row();
				$F_cod_grado_academico=$GA[0];
			$sqli_ga->free();
			//---------------------------------------//
			$cons_T="SELECT * FROM personal_registro_estudios WHERE id_funcionario='$F_id' AND cod_grado_academico='$F_cod_grado_academico' LIMIT 1";
			$sqli_T=$conexion_mysqli->query($cons_T);
			$T=$sqli_T->fetch_assoc();
				$T_titulo=$T["titulo"];
				$T_nombre_institucion=$T["nombre_institucion"];
			$sqli_T->free();	
			//--------------------------------------------//
			//asignaciones docente
			$cons_A="SELECT * FROM toma_ramo_docente WHERE id_funcionario='$F_id' AND semestre='$semestre' AND year='$year' $condicionSede2";
			$sqli_A=$conexion_mysqli->query($cons_A);
			$array_asignaciones=array();
			$F_tipo_contrato="Honorario";
			while($AS=$sqli_A->fetch_assoc())
			{
				$AS_cod_asignatura=$AS["cod_asignatura"];
				$AS_id_carrera=$AS["id_carrera"];
				$AS_horas=$AS["numero_horas"];	
				$AS_jornada=$AS["jornada"];
				$AS_grupo=$AS["grupo"];
				$AS_sede=$AS["sede"];
	
				//asignatura
					list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($AS_id_carrera, $AS_cod_asignatura);
				//----------------------------------------------------------------//
				
				if($AS_cod_asignatura==0)
				{$array_asignaciones[$AS_sede][$AS_id_carrera][]=$nombre_asignatura;}
				else
				{$array_asignaciones[$AS_sede][$AS_id_carrera][]=$nombre_asignatura." ".$AS_jornada."-".$AS_grupo;}
				
				$TOTAL_HORAS_CONTRATO+=$AS_horas;
			}
			$sqli_A->free();
			if(DEBUG){var_dump($array_asignaciones);}
			//---------------------------------------------------------//
			//Datos funcionarios
			$cons_DF="SELECT * FROM personal WHERE id='$F_id' LIMIT 1";
			$sqli_DF=$conexion_mysqli->query($cons_DF)or die($conexion_mysqli->error);
				$DF=$sqli_DF->fetch_assoc();
				$F_rut=$DF["rut"];
				$F_nombre=$DF["nombre"];
				$F_apellido=$DF["apellido_P"]." ".$DF["apellido_M"];
				$F_fecha_ingreso_institucion=$DF["fecha_ingreso_institucion"];
							
				if(DEBUG){ echo"id_funcionario: $F_id<br>nombre: $F_nombre $F_apellido<br> FECHA ingreso Institucion: $F_fecha_ingreso_institucion<br>";}
				
				if($F_fecha_ingreso_institucion!="0000-00-00")
				{
					$fecha_ingreso_institucion = new DateTime($F_fecha_ingreso_institucion);
					$fecha_actual = new DateTime();
					$diferencia = $fecha_ingreso_institucion->diff($fecha_actual);
					$year_en_institucion=$diferencia->format('%y');
				}
				else
				{
					$year_en_institucion=0;
				}
				if(DEBUG){ echo"Anos en istitucion: $year_en_institucion<br>";}
			$sqli_DF->free();
			//--------------------------------------------------------------------//	
				
				
				//---------------------------------------------//
				//grado academico
				$cons_GA="SELECT MIN(cod_grado_academico) FROM personal_registro_estudios WHERE id_funcionario='$F_id' AND cod_grado_academico<>'NULL' AND cod_grado_academico<>''";
				$sqli_ga=$conexion_mysqli->query($cons_GA)or die($conexion_mysqli->error);
				$GA=$sqli_ga->fetch_row();
					$F_cod_grado_academico=$GA[0];
				$sqli_ga->free();
				//--------------------------------------------//
				
				$aux++;
				
				if($aux%2==0){ $color='#FFAAAA';}
				else{ $color='#00FF00';}
				
				$celdasDatosDocente='<tr>
						<td bgcolor="'.$color.'">'.$aux.'</td>
						<td bgcolor="'.$color.'">'.$F_rut.'</td>
						<td bgcolor="'.$color.'">'.utf8_decode($F_nombre).'</td>
						<td bgcolor="'.$color.'">'.utf8_decode($F_apellido).' </td>
						<td bgcolor="'.$color.'">'.utf8_decode($T_titulo).'</td>
						<td bgcolor="'.$color.'">'.utf8_decode($T_nombre_institucion).'</td>
						<td bgcolor="'.$color.'">'.$year_en_institucion.'</td>
						<td bgcolor="'.$color.'">'.$TOTAL_HORAS_CONTRATO.'</td>
						<td bgcolor="'.$color.'">'.$F_tipo_contrato.'</td>';	
				
				$tabla.=$celdasDatosDocente;
				
				$primera_vuelta=true;
				foreach($array_asignaciones as $aux_sede => $aux_array_carreras){
					foreach($aux_array_carreras as $aux_id_carrera => $aux_array_cod_asignatura)	
					{
						foreach($aux_array_cod_asignatura as $i=>$aux_nombre_asignatura)
						{
							//carrera
							$nombre_carrera=NOMBRE_CARRERA($aux_id_carrera);
							//----------------------------//
							if($primera_vuelta)
							{ $primera_vuelta=false; $relleno='';}
							else{ $relleno='<tr><td colspan="9" bgcolor="'.$color.'">&nbsp;</td>'; $relleno=$celdasDatosDocente;}
							
							$tabla.=$relleno.'<td bgcolor="'.$color.'">'.$AS_sede.'</td>
							<td bgcolor="'.$color.'">'.utf8_decode($nombre_carrera).'</td>
										 <td bgcolor="'.$color.'">'.utf8_decode($aux_nombre_asignatura).'</td>
										 </tr>';
									 
						}
					}
				}
				//-----------------------------------------------//		
			}
	}
	$sqli->free();
	$conexion_mysqli->close();
}

$tabla.='</tbody>
</table>';

echo $tabla;
?>
