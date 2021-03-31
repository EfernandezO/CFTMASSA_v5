<?php 
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", true);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_PAC_SIES_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(!DEBUG)
	{
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=PAC_SIES.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
echo'
  <table border="1">
<thead>
	<th bgcolor="#66CC66">N.</th>
	<th bgcolor="#66CC66">Run</th>
    <th bgcolor="#66CC66">DV</th>
    <th bgcolor="#66CC66">Apellido Paterno</th>
    <th bgcolor="#66CC66">Apellido Materno</th>
    <th bgcolor="#66CC66">Nombres</th>
    <th bgcolor="#66CC66">Sexo</th>
    <th bgcolor="#66CC66">Fecha Nacimiento</th>
    <th bgcolor="#66CC66">Nacionalidad</th>
    <th bgcolor="#66CC66">Numero años en institucion</th>
    <th bgcolor="#FFFF33">cargo</th>
	
    <th bgcolor="#FFCC33">Nombre de la carrera (1)</th>
    <th bgcolor="#FFCC33">Total hrs asociadas a carrera (1)</th>
	<th bgcolor="#FFCC33">Ciudad donde se Ubica la carrera (1)</th>
	
    <th bgcolor="#FFCC33">Nombre de la carrera (2)</th>
    <th bgcolor="#FFCC33">Total hrs asociadas a carrera (2)</th>
	<th bgcolor="#FFCC33">Ciudad donde se Ubica la carrera (2)</th>
	
    <th >Nivel de formacion academica del docente</th>
    <th >Nombre de grado y/titulo obtenido</th>
    <th>institucion donde obtuvo su grado/titulo</th>
    <th>pais donde lo obtuvo</th>
    <th>fecha en que lo obtuvo</th>
    <th bgcolor="#FF6600">N° de horas con tontrato de planta (indefinido)</th>
    <th bgcolor="#FF6600">N° de horas con contrato a contrata (plazo fijo)</th>
    <th bgcolor="#FF6600">N° de horas con contrato a Honorarios</th>
    <th bgcolor="#FF6600">Total horas contratadas de academico-docente</th>
	<th>SEDE</th>
</thead>
<tbody>';
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funcion.php");
	require("../../../funciones/funciones_sistema.php");
		
		
		$year_asignacion=$_POST["year"];
		
		echo"<strong>Sede:</strong> $sede <strong>Año Asignacion:</strong> $year_asignacion<br>";
		if(DEBUG){ var_export($_POST);}
		$hay_condiciones=true;
				
		 $condicion_sede="";
		
		$cons_main_1="SELECT DISTINCT(id_funcionario) FROM toma_ramo_docente INNER JOIN personal ON toma_ramo_docente.id_funcionario = personal.id WHERE toma_ramo_docente.year='$year_asignacion' AND toma_ramo_docente.semestre='1' ORDER BY personal.apellido_P, personal.apellido_M";
		
		if(DEBUG){ echo"<br><br><b>$cons_main_1</b><br>";}
		$sql_main_1=$conexion_mysqli->query($cons_main_1)or die("MAIN".$conexion_mysqli->error);
		$num_reg_M=$sql_main_1->num_rows;
		if(DEBUG){ echo"NUM GLOBAL: $num_reg_M<br>";}
		
		if($num_reg_M>0)
		{
			$primera_vuelta=true;
			$contador=0;
			while($DID=$sql_main_1->fetch_row())
			{
				$mostrar_personal=false;
				$id_funcionario=$DID[0];
				if(DEBUG){ echo"<br><br>PID:$id_funcionario<br>";}
					 //------------------------------------------------------------------------------------//
					$cons_A="SELECT personal.* FROM personal WHERE personal.id='$id_funcionario' LIMIT 1";
					
					$sql_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
						$A=$sql_A->fetch_assoc();
					$sql_A->free();	
					//-----------------------------------------------------------------------------------------//
							if(DEBUG){ echo"--->$cons_A<br>";}
							
							$mostrar_personal=true;
							
							$P_nombre=$A["nombre"];
							$P_apellido_P=$A["apellido_P"];
							$P_apellido_M=$A["apellido_M"];
							$P_sede=$A["sede"];
							$P_rut=$A["rut"];		
							$array_rut=explode("-",$P_rut);
							if(isset($array_rut[0])){$aux_rut_sin_guion=$array_rut[0];}
							else{$aux_rut_sin_guion="";}
							
							if(isset($array_rut[1])){$aux_dv=$array_rut[1];}
							else{$aux_dv="";}
							
							$P_sexo=$A["sexo"];
							$P_fecha_nacimiento=$A["fecha_nacimiento"];
							$P_nacionalidad="CHILENA";		
							$P_fecha_ingreso_institucion=$A["fecha_ingreso_institucion"];
							
							if(DEBUG){ echo"FECHA ingreso Institucion: $P_fecha_ingreso_institucion<br>";}
							
							if($P_fecha_ingreso_institucion!="0000-00-00")
							{
								$fecha_ingreso_institucion = new DateTime($P_fecha_ingreso_institucion);
								$fecha_actual = new DateTime();
								$diferencia = $fecha_ingreso_institucion->diff($fecha_actual);
								$year_en_institucion=$diferencia->format('%y');
							}
							else
							{
								$year_en_institucion=0;
							}
							if(DEBUG){ echo"Años en institucion: $year_en_institucion<br>";}
							
							
							//---------------------------------------------------------//
							$P_cargo="DOCENTE";
							/////----------------------------------------------------//
							
							//-------------------------------------------------------------------------------------------------///
							$cons_AS="SELECT * FROM toma_ramo_docente WHERE id_funcionario='$id_funcionario' AND year='$year_asignacion' AND semestre='1'";
							$sqli_AS=$conexion_mysqli->query($cons_AS)or die($conexion_mysqli->error);
							$num_asignaciones=$sqli_AS->num_rows;
							
							if(DEBUG){ echo"--->$cons_AS <br>num_asignaciones: $num_asignaciones<br>";}
							
							$array_horas_carrera=array();
							
							$total_numero_horas_semestrales_honorario=0;
							if($num_asignaciones>0)
							{
								while($AS=$sqli_AS->fetch_assoc())
								{
									$AS_semestre=$AS["semestre"];
									$AS_year=$AS["year"];
									$AS_id_carrera=$AS["id_carrera"];
									$AS_numero_horas=$AS["numero_horas"];
									$AS_sede=$AS["sede"];
									
									list($es_jefe_de_carrera, $id_carrera_jefatura)=ES_JEFE_DE_CARRERA($id_funcionario, $AS_semestre, $AS_year, $AS_sede);
									if($es_jefe_de_carrera){$P_cargo="JEFE DE CARRERA";}
									
									$total_numero_horas_semestrales_honorario+=$AS_numero_horas;
									
									if(isset($array_horas_carrera[$AS_id_carrera]))
									{$array_horas_carrera[$AS_id_carrera]+=$AS_numero_horas;}
									else{$array_horas_carrera[$AS_id_carrera]=$AS_numero_horas;}
									
									if(isset($array_sede_carrera[$AS_id_carrera]))
									{$array_sede_carrera[$AS_id_carrera]=$AS_sede;}
									else{$array_sede_carrera[$AS_id_carrera]=$AS_sede;}
									
								}
							}
							
							if(DEBUG){ echo"HONORARIO Numero horas pedagogicas semestrales total del Docente: $total_numero_horas_semestrales_honorario<br>";}
							
							
							
							//conviento las horas guardadas, pedagogicas, a cronologicas
							$total_min_cronologicos_semestrales=($total_numero_horas_semestrales_honorario*45);
							$total_horas_cronologicas_semestrales=($total_min_cronologicos_semestrales/60);
							$H_horas_cronologicas_semanales=($total_horas_cronologicas_semestrales/18);
							
							if(DEBUG){ echo "HONORARIO Numero horas cronologicas semestrales total: $total_horas_cronologicas_semestrales<br>HONORARIO Numero Horas semanales: $H_horas_cronologicas_semanales<br>";}
							//----------------------------------------------------------//
							
							
							$id_carrera_unidad_principal=0;
							$id_carrera_unidad_secundaria=0;
							if(count($array_horas_carrera)>0)
							{
								
								$aux_mayor_hora=0;
								$aux_mayor_carrera=0;
								arsort($array_horas_carrera);
								$primera_vuelta=true;
								foreach($array_horas_carrera as $x_id_carrera =>$x_numero_horas)
								{
									
									if(DEBUG){echo "id_carrera: $x_id_carrera -> Numero Horas: $x_numero_horas<br>";}
									if($primera_vuelta)
									{
										$primera_vuelta=false;
										$id_carrera_unidad_principal=$x_id_carrera;
									}
									else
									{
										$id_carrera_unidad_secundaria=$x_id_carrera;
										break;
									}
								}
							}
							else
							{}
							
							if(DEBUG){ echo"unidad_principal: $id_carrera_unidad_principal <br>unidad secundaria: $id_carrera_unidad_secundaria<br>";}
							if($id_carrera_unidad_principal>0){ $unidad_principal=NOMBRE_CARRERA($id_carrera_unidad_principal); $region_unidad_primaria=7; $horas_carrera_principal=$array_horas_carrera[$id_carrera_unidad_principal]; $sede_carrera_principal=$array_sede_carrera[$id_carrera_unidad_principal];}
							else{ $unidad_principal=""; $region_unidad_primaria=""; $horas_carrera_principal=""; $sede_carrera_principal="";}
							
							if($id_carrera_unidad_secundaria>0){ $unidad_secundaria=NOMBRE_CARRERA($id_carrera_unidad_secundaria); $region_unidad_secundaria=7; $horas_carrera_secundaria=$array_horas_carrera[$id_carrera_unidad_secundaria]; $sede_carrera_secundaria=$array_sede_carrera[$id_carrera_unidad_secundaria];}
							else{ $unidad_secundaria=""; $region_unidad_secundaria=""; $horas_carrera_secundaria=""; $sede_carrera_secundaria="";}
							//----------------------------------------------------------------------------------//
							$horas_cronologicas_semanales_principal=0;
							$horas_cronologicas_semanales_secundaria=0;
							
							if($horas_carrera_principal>0)
							{
								$total_min_cronologicos_semestrales_principal=($horas_carrera_principal*45);
								$total_horas_cronologicas_semestrales_principal=($total_min_cronologicos_semestrales_principal/60);
								$horas_cronologicas_semanales_principal=($total_horas_cronologicas_semestrales_principal/18);
							}
							
							if($horas_carrera_secundaria>0)
							{
								$total_min_cronologicos_semestrales_secundaria=($horas_carrera_secundaria*45);
								$total_horas_cronologicas_semestrales_secundaria=($total_min_cronologicos_semestrales_secundaria/60);
								$horas_cronologicas_semanales_secundaria=($total_horas_cronologicas_semestrales_secundaria/18);
							}
							//---------------------------------------------------------------------//
							//educacion
							
							$cons_ED="SELECT MIN(cod_grado_academico) FROM personal_registro_estudios WHERE id_funcionario='$id_funcionario' ORDER by fecha_titulo desc";
							$sqli_ED=$conexion_mysqli->query($cons_ED);
							$ED=$sqli_ED->fetch_row();
								$max_cod_grado_academico=$ED[0];
								$sqli_ED->free();
							if(DEBUG){ echo"$cons_ED<br> maximo cod grado academico: $max_cod_grado_academico<br>";}
							
							//----------------------------------//
							$cons_EDM="SELECT * FROM personal_registro_estudios WHERE id_funcionario='$id_funcionario' AND cod_grado_academico='$max_cod_grado_academico' ORDER by id desc";
							$sqli_EDM=$conexion_mysqli->query($cons_EDM);
								$EDM=$sqli_EDM->fetch_assoc();
								
								$P_titulo=$EDM["titulo"];
								$P_nombre_institucion=$EDM["nombre_institucion"];
								$P_pais_titulo=$EDM["pais_titulo"];
								$P_fecha_titulo=$EDM["fecha_titulo"];
							$sqli_EDM->free();	
							//------------------------------------------------------//
							
							if(empty($P_titulo)){$color_x1='#FF0000';}
							else{$color_x1='';}
								
							
								
						if($mostrar_personal)
						{
							$contador++;
								echo'<tr>
								<td>'.$contador.'</td>
								<td>'.$aux_rut_sin_guion.'</td>
								<td>'.$aux_dv.'</td>
								<td>'.utf8_decode(strtoupper($P_apellido_P)).'</td>
								<td>'.utf8_decode(strtoupper($P_apellido_M)).'</td>
								<td>'.utf8_decode(strtoupper($P_nombre)).'</td>
								<td>'.$P_sexo.'</td>
								<td>'.fecha_format($P_fecha_nacimiento,"-").'</td>
								<td>'.$P_nacionalidad.'</td>
								<td>'.$year_en_institucion.'</td>
								<td>'.$P_cargo.'</td>
								
								<td>'.utf8_decode(strtoupper($unidad_principal)).'</td>
								<td>'.number_format($horas_cronologicas_semanales_principal,0).'</td>
								<td>'.$sede_carrera_principal.'</td>
								
								<td>'.utf8_decode(strtoupper($unidad_secundaria)).'&nbsp;</td>
								<td>'.number_format($horas_cronologicas_semanales_secundaria,0).'&nbsp;</td>
								<td>'.$sede_carrera_secundaria.'</td>
								
								<td>'.$max_cod_grado_academico.'&nbsp;</td>
								<td bgcolor="'.$color_x1.'">'.utf8_decode(strtoupper($P_titulo)).'&nbsp;</td>
								<td>'.utf8_decode(strtoupper($P_nombre_institucion)).'&nbsp;</td>
								<td>'.strtoupper($P_pais_titulo).'&nbsp;</td>
								<td>'.fecha_format($P_fecha_titulo,"-").'&nbsp;</td>
								
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>'.number_format($H_horas_cronologicas_semanales,0).'</td>
								<td>&nbsp;</td>
								<td>'.$P_sede.'</td>
								</tr>';
							}
							else{ if(DEBUG){ echo"Sin DATOS.....<br>";}}
			}
		}
		else
		{
			if(DEBUG){ echo"SIN REGISTROS<br>";}
		}
	//--------------------------------------------------//
		$sql_main_1->free();
	$conexion_mysqli->close();	
	@mysql_close($conexion);
	echo'</tbody></table>';
//--------------------------------------//

?>