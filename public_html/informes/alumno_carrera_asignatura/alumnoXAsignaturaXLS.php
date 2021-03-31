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
	
	$html="";
	if(DEBUG){echo"año: $year semestre: $semestre sede: $sede<br>";}
	//-------------------------------------------------------------------------------//
	
	$cons_MAIN="SELECT toma_ramos.id_alumno, toma_ramos.id_carrera, toma_ramos.yearIngresoCarrera, toma_ramos.cod_asignatura, alumno.sede, toma_ramos.jornada, toma_ramos.nivel, alumno.grupo FROM toma_ramos INNER JOIN alumno ON toma_ramos.id_alumno=alumno.id WHERE toma_ramos.semestre='$semestre' AND toma_ramos.year='$year' AND alumno.sede='$sede' $condicion_carrera ORDER by alumno.sede, toma_ramos.id_carrera, toma_ramos.nivel, alumno.jornada, toma_ramos.cod_asignatura";
	if(DEBUG){echo "<br>$cons_MAIN<br>";}
	
	$sqli=$conexion_mysqli->query($cons_MAIN)or die($conexion_mysqli->error);
	$num_registros=$sqli->num_rows;
	
	$ARRAY_RESULTADOS=array();
	
	if($num_registros>0)
	{
		$html.='<strong>Periodo: '.$semestre.' Semestre - '.$year.'</strong> <a href="alumnoXAsignaturaXLS.php?semestre='.$semestre.'&year='.$year.'&sede='.$sede.'&id_carrera='.$id_carrera_consulta.'">Exportar a XLS</a><br>';
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
			
			//$cons_ramo="SELECT nivel FROM mallas WHERE id_carrera='$id_carrera_alumno' AND cod='$cod_asignatura' LIMIT 1";
			//	$sql_ramo=$conexion_mysqli->query($cons_ramo)or die($conexion_mysqli->error());
			//		$D=$sql_ramo->fetch_assoc();
			//		$R_nivel=$D["nivel"];
			//	$sql_ramo->free();

			list($aux_nombre_asignatura, $R_nivel)=NOMBRE_ASIGNACION($id_carrera_alumno, $cod_asignatura);
				
			$situacion_alumno_en_periodo=ESTADO_ALUMNO_PERIODO($id_alumno, $id_carrera_alumno, $yearIngresoCarrera,$semestre, $year);
			$alumno_con_matricula=VERIFICAR_MATRICULA($id_alumno, $id_carrera_alumno,$yearIngresoCarrera, true, false, $semestre, false, $year);
			
			$cons_nota="SELECT nota FROM notas WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera_alumno' AND yearIngresoCarrera='$yearIngresoCarrera' AND cod='$cod_asignatura' LIMIT 1";
			$sqli_N=$conexion_mysqli->query($cons_nota)or die($conexion_mysqli->error);
				$DNO=$sqli_N->fetch_assoc();
			$auxNotaFinal=$DNO["nota"];
			if(empty($auxNotaFinal)){$auxNotaFinal=0;}
			$sqli_N->free();
			
			if(DEBUG){echo $cons_nota." \n nota: $auxNotaFinal<br>";}
			
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
				//contador de alumnos toman el ramos
				if(isset($ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["cantidadTR"]))
				{$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["cantidadTR"]+=1;}
				else
				{$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["cantidadTR"]=1;}
				
				
				
				
				
				
				
				//contador aprueban
				if($auxNotaFinal>=4){
						if(isset($ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["cantidadAprueba"]))
					{$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["cantidadAprueba"]+=1;}
					else
					{$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["cantidadAprueba"]=1;}
				}
				else
				{
					//contador reprobados
					if(isset($ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["cantidadReprueba"]))
					{$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["cantidadReprueba"]+=1;}
					else
					{$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["cantidadReprueba"]=1;}
				}
			}
		}
		
	}
	else
	{
		$html.="Sin Registros... :(<br>";
		
	}
	
	
	$tabla='';
	$primeraVuelta=true;
	foreach($ARRAY_RESULTADOS as $aux_sede=>$array_1)
	{
		if(DEBUG){$html.="SEDE: $aux_sede ";}
		
		foreach($array_1 as $aux_id_carrera =>$array_2)
		{	
			$aux_nombre_carrera=NOMBRE_CARRERA($aux_id_carrera);
			foreach($array_2 as $aux_nivel => $array_3)
			{
				if(DEBUG){$html.="Nivel: $aux_nivel ->  <br>";}
				foreach($array_3 as $aux_jornada =>$array_4)
				{
					if(DEBUG){$html.="Jornada: $aux_jornada -> <br>";}
					foreach($array_4 as $aux_grupo => $array_5)
					{
						if(DEBUG){$html.="Grupo: $aux_grupo -> <br>";}
						if($primeraVuelta){
							$tabla.='<table border="1" width="100%">
									<thead>
										<tr>
											<th colspan="12">'.$aux_sede.' '.$aux_id_carrera.'_'.utf8_decode($aux_nombre_carrera).' Nivel['.$aux_nivel.'] Jornada['.$aux_jornada.'] Grupo ['.$aux_grupo.']</th>
										</tr>
									</thead>
									<tbody>
									<tr>
										<td>Semestre</td>
										<td>Año</td>
										<td>Sede</td>
										<td>Carrera</td>
										<td>Jornada</td>
										<td>Nivel</td>
										<td>Grupo</td>
										<td>Asignaturas</td>
										<td>Hrs Semestrales</td>
										<td>Profesor</td>
										<td>N. Alumnos toma Ramo</td>
										<td>N. Aprueba</td>
										<td>N. Alumnos Reprueba</td>
										<td>% Aprobacion</td>
									</tr>';
									$primeraVuelta=false;
						}
						foreach($array_5 as $aux_cod_asignatura => $arrayValor)
						{
							$valor=$arrayValor["cantidadTR"];
							
							
							if(DEBUG){ echo"Profesor NO registrado, buscar...<br>";}
							//busco profesor ultimo asignado al ramo
							$consP="SELECT id_funcionario FROM toma_ramo_docente WHERE year='$year' AND semestre='$semestre' AND sede='$aux_sede' AND id_carrera='$aux_id_carrera' AND jornada='$aux_jornada' AND grupo='$aux_grupo' AND cod_asignatura='$aux_cod_asignatura' ORDER by id DESC LIMIT 1";						
							if(DEBUG){ echo"BUSCO PROFESOR: $consP<br>";}
							$sqli_P=$conexion_mysqli->query($consP)or die($conexion_mysqli->error);
							$P=$sqli_P->fetch_assoc();
							$aux_id_funcionario=$P["id_funcionario"];
							$sqli_P->free();
							//----------------------------------------------------//
						//guardo profesor
						
							
							if(isset($arrayValor["cantidadAprueba"])){$cantidadAprueba=$arrayValor["cantidadAprueba"];}
							else{$cantidadAprueba=0;}
							
							if(isset($arrayValor["cantidadReprueba"])){$cantidadReprueba=$arrayValor["cantidadReprueba"];}
							else{$cantidadReprueba=0;}
							
							$porcentajeAprobacion=($cantidadAprueba*100)/$valor;
							
							if($porcentajeAprobacion>=55){$colorPorcentaje=" #58d68d ";}
							elseif($porcentajeAprobacion>=50){$colorPorcentaje="#f7dc6f";}
							else{$colorPorcentaje=" #f1948a ";}
							
							if(DEBUG){$html.="->Cod Asignatura: $aux_cod_asignatura -> $valor<br>";}
								
							//$cons_a="SELECT ramo FROM mallas WHERE id_carrera='$aux_id_carrera' AND cod='$aux_cod_asignatura' LIMIT 1";
							//	$sqli_a=$conexion_mysqli->query($cons_a);
							//		$A=$sqli_a->fetch_row();
							//		$aux_nombre_asignatura=$A[0];
							//	$sqli_a->free();
							list($aux_nombre_asignatura, $R_nivel)=NOMBRE_ASIGNACION($aux_id_carrera, $aux_cod_asignatura);

								$hrsSemestrales=HORAS_PROGRAMA($aux_id_carrera, $aux_cod_asignatura, "semestral", "teorico"	);
							$tabla.='<tr>
										<td>'.$semestre.'</td>
										<td>'.$year.'</td>
										<td>'.$sede.'</td>
										<td>'.utf8_decode(NOMBRE_CARRERA($aux_id_carrera)).'</td>
										<td>'.$aux_jornada.'</td>
										<td>'.$aux_nivel.'</td>
										<td>'.$aux_grupo.'</td>
										<td>'.utf8_decode($aux_nombre_asignatura).'</td>
										<td>'.$hrsSemestrales.'</td>
										<td>'.utf8_decode(NOMBRE_PERSONAL($aux_id_funcionario)).'</td>
										<td align="center"><a href="alumnos_carrera_asignatura_lista.php?sede='.base64_encode($aux_sede).'&id_carrera='.base64_encode($aux_id_carrera).'&nive='.base64_encode($aux_nivel).'&jornada='.base64_encode($aux_jornada).'&grupo='.base64_encode($aux_grupo).'&cod_asignatura='.base64_encode($aux_cod_asignatura).'&semestre='.base64_encode($semestre).'&year='.base64_encode($year).'" target="_blank" title="click para ver lista de alumnos" class="button_R">'.$valor.'</a></td>
										<td>'.$cantidadAprueba.'</td>
										<td>'.$cantidadReprueba.'</td>
										<td bgcolor="'.$colorPorcentaje.'">'.number_format($porcentajeAprobacion,1).'</td>
									</tr>';
						}
						//$tabla.='</tbody></table><br>';
					}
				}
			}
		}
		
	}
	$tabla.='</tbody></table><br>';
	
	$html.=$tabla;
	$sqli->free();
	$conexion_mysqli->close();
	//-------------------------------------------------------------------------------//
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=alumnosXAsignatura_".$semestre."_".$year."".$sede."-idCarrera_".$id_carrera_consulta.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	echo $tabla."<br>Generado el ". date("d-m-Y H:i:s");
	

	//--------------------------------------------------------------------------------//
	
}
?>