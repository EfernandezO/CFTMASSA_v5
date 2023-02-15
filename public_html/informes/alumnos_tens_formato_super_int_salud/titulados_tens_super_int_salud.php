<?php 
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Exportar_alumno_SuperdeSalud_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////////
set_time_limit(90);

$sede=$_POST["fsede"];
$titulo_year=$_POST["year"];
$marcar_alumnos=$_POST["marcar_alumnos"];

$nombre_archivo="Carga_masiva_T_superdesalud_".$sede."_".$titulo_year;
$formato="txt";


if(!DEBUG)
	{
		switch($formato)
		{
			case"txt":
				 header("Content-Type: text/plain");
				 header("Content-Disposition: attachment; filename=$nombre_archivo.txt"); 
				 header("Pragma: no-cache");
				 header("Expires: 0");
				 break;
			case"xlsx":
				header('Content-type: application/vnd.ms-excel');
				header("Content-Disposition: attachment; filename=$nombre_archivo.xlsx");
				header("Pragma: no-cache");
				header("Expires: 0");
				
				echo'<table border="1">
					 <thead>
					 	<tr>
							<td>RUT</td>
							<td>DV</td>
							<td>APELLIDO PATERNO</td>
							<td>APELLIDO MATERNO</td>
							<td>NOMBRES</td>
							<td>Sexo</td>
							<td>Fecha de Nacimiento</td>
							<td>Nacionalidad</td>
							<td>TITULO OTORGADO</td>
						</tr>
					 </thead>
					 <tbody>';
				break; 
		}
	}
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funcion.php");
	require("../../../funciones/VX.php");
	
	$evento="Genera archivo para carga masiva de alumnos en SUPERINTENDENCIA DE SALUD para $sede year titulacion: $titulo_year";
	REGISTRA_EVENTO($evento);
		//$contrato_semestre=$_POST["semestre_vigencia_contrato"];
		$situacion_alumno="T";
		$id_carrera_tens=4;
		$hoja="";
		$separador='|';
		if(DEBUG){ var_export($_POST);}
		$hay_condiciones=true;
				
		if($sede!="todas")
		{
			 $condicion_sede="AND alumno.sede='$sede'";
			 $hay_condiciones=true;
		}
		else
		{ $condicion_sede="";}
		
		if($titulo_year=="todos")
		{ $condicion_year="";}
		else
		{ $condicion_year="proceso_titulacion.year_titulo='$titulo_year' AND";}
		
		$cons_main_1="SELECT DISTINCT(id_alumno) FROM proceso_titulacion INNER JOIN alumno ON proceso_titulacion.id_alumno = alumno.id WHERE $condicion_year proceso_titulacion.sede='$sede' AND alumno.situacion='$situacion_alumno' AND alumno.id_carrera='$id_carrera_tens' ORDER by alumno.carrera, alumno.apellido_P, apellido_M";
		
		if(DEBUG){ echo"<br><br><b>$cons_main_1</b><br>";}
		$sql_main_1=$conexion_mysqli->query($cons_main_1)or die($conexion_mysqli->error);
		$num_reg_M=$sql_main_1->num_rows;
		if(DEBUG){ echo"NUM GLOBAL: $num_reg_M<br>";}
		
		if($num_reg_M>0)
		{
			$primera_vuelta=true;
			while($DID=$sql_main_1->fetch_row())
			{
				$mostrar_alumno=false;
				$id_alumno=$DID[0];
				//if(DEBUG){ echo"<br><br>UID:$id_alumno<br>";}
					 
					$cons_A="SELECT alumno.*, proceso_titulacion.* FROM alumno INNER JOIN proceso_titulacion ON  alumno.id =proceso_titulacion.id_alumno WHERE alumno.id='$id_alumno' LIMIT 1";
					
					$sqli_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
						$A=$sqli_A->fetch_assoc();
					$sqli_A->free();	
					
							$A_id=$A["id"];
							
							
							
							
							$A_nombre=utf8_decode($A["nombre"]);
							$A_apellido_P=utf8_decode($A["apellido_P"]);
							$A_apellido_M=utf8_decode($A["apellido_M"]);
							$A_rut=$A["rut"];		
							$array_rut=explode("-",$A_rut);
								$aux_rut_sin_guion=$array_rut[0];
								$aux_dv=$array_rut[1];
									
							$A_carrera=$A["carrera"];
							$A_sexo=substr(str_inde($A["sexo"]),0,1);
							
							
							$A_fecha_nac=$A["fnac"];
							$A_nacionalidad="chilena";
							$A_sede=$A["sede"];
							$A_jornada=$A["jornada"];
	
							/////////////////////
							$A_fecha_emision_titulo=$A["titulo_fecha_emision"];	
							$A_nombre_titulo=$A["nombre_titulo"];
							if(empty($A_nombre_titulo)){ $A_nombre_titulo="-";}
							
							
							if((is_numeric($A_id))and($A_id>0))
							{ 
								if(($A_fecha_emision_titulo=='')or($A_fecha_emision_titulo=='0000-00-00'))
								{ $mostrar_alumno=false;}
								else
								{ $mostrar_alumno=true;}
							}
							else{$mostrar_alumno=false;}
							
							
							//busco si ya fue cargado
							$descripcion_X='Alumno Cargado Sistema Superintendencia de Salud';
							$cons_2="SELECT COUNT(id) FROM alumno_registros WHERE id_alumno='$id_alumno' AND descripcion='$descripcion_X'";
							$sqli_2=$conexion_mysqli->query($cons_2)or die($conexion_mysqli->error);
							$D_2=$sqli_2->fetch_row();
								$coincidencias=$D_2[0];
								if(empty($coincidencias)){ $coincidencias=0;}
								
								if(DEBUG){ echo"<br>--->$cons_2<br>coincidencias: $coincidencias<br>";}
								
								if($coincidencias>0){ $alumno_ya_registrado=true; if(DEBUG){ echo"Alumno ya cargado Anteriormente...<br>";}}
								else{ $alumno_ya_registrado=false; if(DEBUG){ echo"Alumno No cargado Anteriormente...<br>";}}
								
							$sqli_2->free();	
							
							
							
							if(($mostrar_alumno)and(!$alumno_ya_registrado))
							{
								if($A_nombre_titulo=="tecnico de nivel superior en enfermeria"){$A_nombre_titulo_label='T�cnico de Nivel Superior en Enfermer�a';}
								else{ $A_nombre_titulo_label=$A_nombre_titulo;}
								
								
								switch($formato)
								{
									case"txt":
										$linea_registro="T".$separador.$aux_rut_sin_guion.$separador.$aux_dv.$separador.$A_apellido_P.$separador.$A_apellido_M.$separador.$A_nombre.$separador.$A_sexo.$separador.fecha_format($A_fecha_nac,"").$separador.$A_nacionalidad."".$separador."".$separador."".$separador.fecha_format($A_fecha_emision_titulo,"").$separador.utf8_decode($A_nombre_titulo_label)."\r\n";
										echo $linea_registro;
										break;
									case"xlsx":
										echo'<tr>
												<td>'.$aux_rut_sin_guion.'</td>
												<td>'.$aux_dv.'</td>
												<td>'.$A_apellido_P.'</td>
												<td>'.$A_apellido_M.'</td>
												<td>'.$A_nombre.'</td>
												<td>'.$A_sexo.'</td>
												<td>'.fecha_format($A_fecha_nac,"").'</td>
												<td>'.$A_nacionalidad.'</td>
												<td>'.$A_nombre_titulo_label.'</td>
											 </tr>';
										break;
								
								}
								if($marcar_alumnos=="si")
								{
									if(DEBUG){ echo"<br>Registra evento Alumno<br>";}
									$descripcion="Alumno Cargado Sistema Superintendencia de Salud";
									REGISTRO_EVENTO_ALUMNO($id_alumno, "notificacion",$descripcion);
								}
							}
							else{ if(DEBUG){ echo"Sin DATOS.....<br>";}}
			}
		}
		else
		{
			if(DEBUG){ echo"SIN REGISTROS<br>";}
		}
	//--------------------------------------------------//
	
	
	switch($formato)
	{
		case"xlsx":
			echo'</tbody>
				 </table>';
			break;
	}
	$sql_main_1->free();
	$conexion_mysqli->close();
	//echo $hoja;
//--------------------------------------//
?>