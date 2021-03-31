<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(6000);
ini_set('memory_limit', '-1');
$tiempo_inicio_script = microtime(true);
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_notas_semestrales_X_toma_ramo_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	
	$year=base64_decode($_GET["year"]);
	$sede=base64_decode($_GET["sede"]);
	$semestre=base64_decode($_GET["semestre"]);
	$id_carrera_consulta=base64_decode($_GET["id_carrera"]);
	$mostrar_solo_alumnos_con_matricula=true;
	if(DEBUG){echo"id_carrera : $id_carrera_consulta";}
	
	if($id_carrera_consulta=="0")
	{ $condicion_carrera="";}
	else
	{ $condicion_carrera=" AND toma_ramos.id_carrera='$id_carrera_consulta' AND alumno.id_carrera='$id_carrera_consulta'";}
	
	$div="div_carga";
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	$html="";
	if(DEBUG){echo("año: $year semestre: $semestre sede: $sede");}
	//-------------------------------------------------------------------------------//
	
	$cons_MAIN="SELECT toma_ramos.id_alumno, toma_ramos.id_carrera, toma_ramos.cod_asignatura, alumno.sede, toma_ramos.jornada, toma_ramos.nivel, alumno.grupo FROM toma_ramos INNER JOIN alumno ON toma_ramos.id_alumno=alumno.id WHERE toma_ramos.semestre='$semestre' AND toma_ramos.year='$year' AND alumno.sede='$sede'  $condicion_carrera ORDER by alumno.sede, toma_ramos.id_carrera, toma_ramos.nivel, alumno.jornada, toma_ramos.cod_asignatura";
	if(DEBUG){echo"$cons_MAIN<br>";}
	
	$sqli=$conexion_mysqli->query($cons_MAIN);
	$num_registros=$sqli->num_rows;
	
	$ARRAY_RESULTADOS=array();
	
	if($num_registros>0)
	{
		$html.="<strong>Periodo: $semestre Semestre - $year</strong><br>";
		if(DEBUG){$html.="$num_registros Registros<br>";}
		while($TM=$sqli->fetch_assoc())
		{
			$id_alumno=$TM["id_alumno"];
			$id_carrera_alumno=$TM["id_carrera"];
			$cod_asignatura=$TM["cod_asignatura"];
			$sede_alumno=$TM["sede"];
			$jornada_alumno=$TM["jornada"];///jornada de toma de ramos
			$nivel_alumno=$TM["nivel"];
			$grupo_alumno=$TM["grupo"];
			
			$cons_ramo="SELECT nivel FROM mallas WHERE id_carrera='$id_carrera_alumno' AND cod='$cod_asignatura' LIMIT 1";
				$sql_ramo=$conexion_mysqli->query($cons_ramo)or die($conexion_mysqli->error());
					$D=$sql_ramo->fetch_assoc();
					$R_nivel=$D["nivel"];
				$sql_ramo->free();
			//-----------------------------------------------------------//
			
			
			///consulta profesor de asignatura
			$aux_id_funcionario=0;
			if(!isset($ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["profesor"]))
			{
				if(DEBUG){ echo"Buscar a Profesor<br>";}
				$cons_D="SELECT id_funcionario FROM toma_ramo_docente WHERE id_carrera='$id_carrera_alumno' AND cod_asignatura='$cod_asignatura' AND jornada='$jornada_alumno' AND sede='$sede_alumno' AND semestre='$semestre' AND year='$year' AND grupo='$grupo_alumno'";
				if(DEBUG){ echo"--->$cons_D<br>";}
				$sqli_D=$conexion_mysqli->query($cons_D)or die($conexion_mysqli->error);
				$num_regp=$sqli_D->num_rows;
				if($num_regp>0)
				{
					while($DF=$sqli_D->fetch_assoc())
					{
						$aux_id_funcionario=$DF["id_funcionario"];
						if(DEBUG){ echo"id_funcionario: $aux_id_funcionario<br>";}
					}
					///
				}
				else
				{
					if(DEBUG){ echo"--------------->Profesor de esta asignatura NO encontrado<br>";}
				}
				$sqli_D->free();
				$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["profesor"]=$aux_id_funcionario;
			}
			else
			{
				if(DEBUG){ echo"No buscar Profesor ya definido<br>";}
			}
			//-------------------------------------------------------------------------//	
			$A_situacion=ESTADO_ALUMNO_PERIODO($id_alumno, $id_carrera_alumno, $semestre, $year);
			$alumno_con_matricula=VERIFICAR_MATRICULA($id_alumno, $id_carrera_alumno, true, false, $semestre, false, $year);
			if($mostrar_solo_alumnos_con_matricula)
			{
				if($alumno_con_matricula){$mostrar_alumno=true;}
				else{$mostrar_alumno=false;}
			}
			else
			{
				if(($A_situacion=="V")or($A_situacion=="EG"))
				{ $mostrar_alumno=true;}
				else
				{ $mostrar_alumno=false;}
			}
			//----------------------------------------------------//
			if($A_situacion=="V"){ $mostrar_alumno_2=true;}
			else{ $mostrar_alumno_2=false;}
			//------------------------------------------------------------------------------------------//
			
			if($mostrar_alumno and $mostrar_alumno_2)
			{
				////reviso notas semestrales de asignatura	
				$cons_N="SELECT semestre, ano, nota FROM notas WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera_alumno' AND cod='$cod_asignatura' LIMIT 1";
				if(DEBUG){ echo"---->$cons_N<br>";}
					$sqli_N=$conexion_mysqli->query($cons_N)or die($conexion_mysqli->error);
					$N=$sqli_N->fetch_assoc();
						$N_nota=$N["nota"];
						$N_semestre=$N["semestre"];
						$N_year=$N["ano"];
					$sqli_N->free();	
					
					if($N_nota>=4)
					{ if(isset($ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["aprobados"])){$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["aprobados"]+=1;}else{$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["aprobados"]=1;}
					}	
					elseif((empty($N_nota))or($N_nota==0)){ if(isset($ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["desertores"])){$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["desertores"]+=1;}else{$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["desertores"]=1;}}
					else
					{
						if(isset($ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["reprobados"]))
						{$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["reprobados"]+=1;}
						else
						{$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["reprobados"]=1;}
					}	
				
				//----------------------------------///
	
				if(isset($ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["inscritos"]))
				{$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["inscritos"]+=1;}
				else
				{$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["inscritos"]=1;}
			}//fin si utilizar alumno
		}
		
	}
	else
	{
		$html.="Sin Registros... :(<br>";
		
	}
	
	
	$tabla='<table border="1">
			<thead>
			<tr>
				<th colspan="10">'.$sede.' Periodo ['.$semestre.' - '.$year.']</th>
			</tr>
			<tr>
				<td>Sede</td>
				<td>Carrera</td>
				<td>Nivel</td>
				<td>Jornada</td>
				<td>Grupo</td>
				<td>Profesor</td>
				<td>Asignatura</td>
				<td>N. Inscritos</td>
				<td>N. Aprobados</td>
				<td>N. Reprobados</td>
				<td>N. Desertores</td>
			</tr>
			</thead>
			<tbody>';
	foreach($ARRAY_RESULTADOS as $aux_sede=>$array_1)
	{
		if(DEBUG){$html.="SEDE: $aux_sede ";}
		
		foreach($array_1 as $aux_id_carrera =>$array_2)
		{
			if(DEBUG){$html.="CARRERA: $aux_id_carrera ->  ";}
			$cons_c="SELECT carrera FROM carrera WHERE id='$aux_id_carrera' LIMIT 1";
			$sqli_c=$conexion_mysqli->query($cons_c);
				$C=$sqli_c->fetch_row();
				$aux_nombre_carrera=$C[0];
			$sqli_c->free();	
			foreach($array_2 as $aux_nivel => $array_3)
			{
				if(DEBUG){$html.="Nivel: $aux_nivel ->  <br>";}
				foreach($array_3 as $aux_jornada =>$array_4)
				{
					if(DEBUG){$html.="Jornada: $aux_jornada -> <br>";}
					foreach($array_4 as $aux_grupo => $array_5)
					{
						if(DEBUG){$html.="Grupo: $aux_grupo -> <br>";}
		
						foreach($array_5 as $aux_cod_asignatura => $array_6)
						{
							
							if(isset($array_6["inscritos"])){$aux_inscritos=$array_6["inscritos"];}else{ $aux_inscritos=0;}
							if(isset($array_6["aprobados"])){$aux_aprobados=$array_6["aprobados"];}else{ $aux_aprobados=0;}
							if(isset($array_6["reprobados"])){$aux_reprobados=$array_6["reprobados"];}else{ $aux_reprobados=0;}
							if(isset($array_6["desertores"])){$aux_desertores=$array_6["desertores"];}else{ $aux_desertores=0;}
							if(isset($array_6["profesor"])){$aux_profesor=NOMBRE_PERSONAL($array_6["profesor"]);}else{ $aux_profesor="";}

							if(DEBUG){$html.="->Cod Asignatura: $aux_cod_asignatura -> [inscritos] $aux_inscritos<br>";}
								$cons_a="SELECT ramo FROM mallas WHERE id_carrera='$aux_id_carrera' AND cod='$aux_cod_asignatura' LIMIT 1";
								$sqli_a=$conexion_mysqli->query($cons_a);
									$A=$sqli_a->fetch_row();
									$aux_nombre_asignatura=$A[0];
								$sqli_a->free();	
								if($aux_inscritos>0)
								{
									$tabla.='<tr>
												<td>'.$aux_sede.'</td>
												<td bgcolor="'.COLOR_CARRERA($aux_id_carrera).'">'.utf8_decode($aux_nombre_carrera).'</td>
												<td>'.$aux_nivel.'</td>
												<td>'.$aux_jornada.'</td>
												<td>'.$aux_grupo.'</td>
												<td>'.utf8_decode($aux_profesor).'</td>
												<td>'.utf8_decode($aux_nombre_asignatura).'</td>
												<td>'.$aux_inscritos.'</td>
												<td>'.$aux_aprobados.'</td>
												<td>'.$aux_reprobados.'</td>
												<td>'.$aux_desertores.'</td>
											</tr>';
								}
						}
						
					}
				}
			}
		}
		
	}
	$tabla.='</tbody></table><br>';
	
	$html.=$tabla;
	//-------------------------------------------------------------------------------//
	if(!DEBUG)
		{
			header('Content-type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=".$sede."_RESUMEN_ASIGNATURAS[".$semestre."_".$year."].xls");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
	echo $tabla;
	//--------------------------------------------------------------------------------//
	$sqli->free();
	$conexion_mysqli->close();
	@mysql_close($conexion);
?>