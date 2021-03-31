<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
	//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_alumnos_X_Asignatura_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

if($_POST)
{
	
	
	$verDetalleAlumnos=true;//muestra una tabla con el detalle de los alumnos considerados.
	$auxNumAL=0;
	$htmlDetalle="";
		
	$year=$_POST["year"];
	$sede=$_POST["fsede"];
	$semestre=$_POST["semestre"];
	$id_carrera_consulta=$_POST["id_carrera"];

	$mostrar_solo_alumnos_con_matricula=true;
	
	
	if(!DEBUG)
		{
			header('Content-type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=SeguimientoSemestreAsignatura_".$semestre."_".$year."_".$sede.".xlsx");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		
		
	
	if(DEBUG){echo "id_carrera : $id_carrera_consulta";}
	
	if($id_carrera_consulta=="0"){ $condicion_carrera="";}
	else{ $condicion_carrera=" AND toma_ramos.id_carrera='$id_carrera_consulta' AND alumno.id_carrera='$id_carrera_consulta'";}
	
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	
	$html="";
	if(DEBUG){echo "año: $year semestre: $semestre sede: $sede<br>";}
	//-------------------------------------------------------------------------------//
	
	$cons_MAIN="SELECT toma_ramos.id_alumno, toma_ramos.id_carrera, toma_ramos.cod_asignatura, alumno.sede, toma_ramos.jornada, toma_ramos.nivel, alumno.grupo FROM toma_ramos INNER JOIN alumno ON toma_ramos.id_alumno=alumno.id WHERE toma_ramos.semestre='$semestre' AND toma_ramos.year='$year' AND alumno.sede='$sede' $condicion_carrera ORDER by alumno.sede, toma_ramos.id_carrera, toma_ramos.nivel, alumno.jornada, toma_ramos.cod_asignatura";
	if(DEBUG){echo $cons_MAIN;}
	
	$sqli=$conexion_mysqli->query($cons_MAIN)or die($conexion_mysqli->error);
	$num_registros=$sqli->num_rows;
	
	$ARRAY_RESULTADOS=array();
	
	if($num_registros>0)
	{
		$html.="<strong>$sede , Periodo: $semestre Semestre - $year</strong><br>";
		if(DEBUG){$html.="$num_registros Registros<br>";}
		while($TM=$sqli->fetch_assoc())
		{
			$id_alumno=$TM["id_alumno"];
			$id_carrera_alumno=$TM["id_carrera"];
			$cod_asignatura=$TM["cod_asignatura"];
			$sede_alumno=$TM["sede"];
			$jornada_alumno=$TM["jornada"];//actualizado toma de ramos
			$nivel_alumno=$TM["nivel"];
			$grupo_alumno=$TM["grupo"];
			
			$cons_ramo="SELECT nivel FROM mallas WHERE id_carrera='$id_carrera_alumno' AND cod='$cod_asignatura' LIMIT 1";
				$sql_ramo=$conexion_mysqli->query($cons_ramo)or die($conexion_mysqli->error());
					$D=$sql_ramo->fetch_assoc();
					$R_nivel=$D["nivel"];
				$sql_ramo->free();
				
			$situacion_alumno_en_periodo=ESTADO_ALUMNO_PERIODO($id_alumno, $id_carrera_alumno,$semestre, $year);
			$alumno_con_matricula=VERIFICAR_MATRICULA($id_alumno, $id_carrera_alumno, true, false, $semestre, false, $year);
			
			//----------------------------------------------------//
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
				if(isset($ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["TOTAL"]))
				{$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["TOTAL"]+=1;}
				else
				{$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["TOTAL"]=1;}
				
				$condicionRamo=RAMO_CONDICION($id_alumno, $id_carrera_alumno, $cod_asignatura, $semestre, $year);
					
				//----------------------------------------------------------------------------------------------
				switch($condicionRamo){
					
					case 1:	
						if(isset($ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["aprobados"]))
				{$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["aprobados"]+=1;}
				else
				{$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["aprobados"]=1;}
						$condicionRamoLabel='aprobado';
					break;
					
					case 2:
						if(isset($ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["reprobados"]))
				{$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["reprobados"]+=1;}
				else
				{$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["reprobados"]=1;}
						$condicionRamoLabel='reprobado';
					break;
					
					case 3:
						if(isset($ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["NN"]))
						{$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["NN"]+=1;}
						else
						{$ARRAY_RESULTADOS[$sede_alumno][$id_carrera_alumno][$R_nivel][$jornada_alumno][$grupo_alumno][$cod_asignatura]["NN"]=1;}
						$condicionRamoLabel='desertor';
					break;
					
				}//fin switch
				
				
				if($verDetalleAlumnos){
					$auxNumAL++;
					$consAL="SELECT nombre, apellido_P, apellido_M, rut FROM alumno WHERE id='$id_alumno' LIMIT 1";
					$sqliAL=$conexion_mysqli->query($consAL);
					$auxAL=$sqliAL->fetch_assoc();
						$AL_nombre=$auxAL["nombre"];
						$AL_apellido_P=$auxAL["apellido_P"];
						$AL_apellido_M=$auxAL["apellido_M"];
						$AL_rut=$auxAL["rut"];
					$sqliAL->free();	
					
					list($auxNombreAsignatura, $auxNivelAsignatura)=NOMBRE_ASIGNACION($id_carrera_alumno, $cod_asignatura);
					
					$htmlDetalle.='<tr>
									  <td>'.$auxNumAL.'</td>
									  <td>'.$id_alumno.'</td>
									  <td>'.$AL_rut.'</td>
									  <td>'.utf8_decode($AL_nombre).'</td>
									  <td>'.utf8_decode($AL_apellido_P).'</td>
									  <td>'.utf8_decode($AL_apellido_M).'</td>
					                  <td>'.$sede_alumno.'</td>
									  <td bgcolor="'.COLOR_CARRERA($id_carrera_alumno).'">'.utf8_decode(NOMBRE_CARRERA($id_carrera_alumno)).'</td>
									  <td>'.$R_nivel.'</td>
									  <td>'.$jornada_alumno.'</td>
									  <td>'.$grupo_alumno.'</td>
									  <td>'.utf8_decode($auxNombreAsignatura).'</td>
									  <td>'.$condicionRamoLabel.'</td>	  		
									</tr>';	
				}
				
				//----------------------------------------------------------------------------------------------
				
			}//fin si utilizar
		}//fin while
		
	}//fin si hay registros
	else
	{
		$html.="Sin Registros... :(<br>";
		
	}
	
	//escritura de detalle
	//--------------------------------------------------------------//
	$htmlDetalle='Sede: '.$sede.' Periodo ['.$semestre.'-'.$year.']<table border=1>
					<tr bgcolor="#CCCC33">
						<td>N.</td>
						<td>id_alumno</td>
						<td>rut</td>
						<td>nombre</td>
						<td>apellido_P</td>
						<td>apellido_M</td>
						<td>Sede</td>
						<td>carrera</td>
						<td>nivel</td>
						<td>jornada</td>
						<td>grupo</td>
						<td>asignatura</td>
						<td>condicion</td>
					</tr>'.$htmlDetalle.'</table>';
	if($verDetalleAlumnos){ echo $htmlDetalle;}
	//-----------------------------------------------------------------//
	
	
	$tabla='<br><br><table border=1>
			<tr bgcolor="#00FF00">
				<td>num</td>
				<td>Carrera</td>
				<td>Sede</td>
				<td>Asignatura</td>
				<td>Jornada</td>
				<td>Semestre</td>
				<td>Profesor</td>
				<td>Grupo</td>
				<td>N. Alumnos</td>
				<td>N. Alumnos eximidos examen Final</td>
				<td>N. Alumnos rinden examen Final</td>
				<td>N. Alumnos aprobados</td>
				<td>N. Alumnos reprobados</td>
				<td>N. Alumnos desertores</td>
				<td>Modalidad examen final</td>
				
			</tr>';
			
	$n=0;		
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
							$numAlumnosAsignatura=$array_6["TOTAL"];
							if(isset($array_6["aprobados"])){$numAlumnosAprobados=$array_6["aprobados"];}
							else{$numAlumnosAprobados=0;}
							
							if(isset($array_6["reprobados"])){$numAlumnosReprobados=$array_6["reprobados"];}
							else{$numAlumnosReprobados=0;}
							
							if(isset($array_6["NN"])){$numAlumnosSinNota=$array_6["NN"];}
							else{$numAlumnosSinNota=0;}
							
							
							if(DEBUG){$html.="->Cod Asignatura: $aux_cod_asignatura -> $numAlumnosAsignatura<br>";}
							list($aux_nombre_asignatura, $aux_nivel)=NOMBRE_ASIGNACION($aux_id_carrera, $aux_cod_asignatura);
							
							//nombre de profesor
							$cons_P="SELECT id_funcionario FROM toma_ramo_docente WHERE semestre='$semestre' AND year='$year' AND sede='$aux_sede' AND cod_asignatura='$aux_cod_asignatura' AND id_carrera='$aux_id_carrera' AND jornada='$aux_jornada' AND grupo='$aux_grupo'";
							
							$sqli=$conexion_mysqli->query($cons_P)or die($conexion_mysqli->error);
							$nProfesoresAsociados=$sqli->num_rows;
							
							$P=$sqli->fetch_assoc();
							$auxIdProfesor=$P["id_funcionario"];
							$sqli->free();
							if(DEBUG){ echo"$cons_P <br> Num profesores asociados: $nProfesoresAsociados<br>id_profesor: $auxIdProfesor<br>";}
							$auxNombreProfesor=NOMBRE_PERSONAL($auxIdProfesor);
							//-------------------------------------------------------------------------------------------//
							
							$n++;	
							$tabla.='<tr>
										<td>'.$n.'</td>
										<td>'.utf8_decode($aux_nombre_carrera).'</td>
										<td>'.$aux_sede.'</td>
										<td>'.utf8_decode($aux_nombre_asignatura).'</td>
										<td>'.$aux_jornada.'</td>
										<td>'.$semestre.'</td>
										<td>'.utf8_decode($auxNombreProfesor).'</td>
										<td>'.$aux_grupo.'</td>
										<td>'.$numAlumnosAsignatura.'</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>'.$numAlumnosAprobados.'</td>
										<td>'.$numAlumnosReprobados.'</td>
										<td>'.$numAlumnosSinNota.'</td>
										<td>&nbsp;</td>
									</tr>';
						}
						
					}
				}
			}
		}
		$tabla.='</tbody></table>';
	}
	
	
	$html.=$tabla;
	//-------------------------------------------------------------------------------//
	echo $html;
	//--------------------------------------------------------------------------------//
}
?>