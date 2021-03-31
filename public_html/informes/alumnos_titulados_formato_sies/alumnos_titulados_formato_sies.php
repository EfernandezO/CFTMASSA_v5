<?php 
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_alumnos_titulados_formato_sies_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

if(!DEBUG)
	{
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=TITULADOS_FORMATO_SIES.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
echo'
  <table border="1">
<thead>
	<th>N.</th>
	<th>Tipo Registro</th>
	<th>Tipo Documento</th>
	<th>Run</th>
    <th>DV</th>
    <th>Apellido Paterno</th>
    <th>Apellido Materno</th>
    <th>Nombres</th>
    <th>Sexo</th>
    <th>Fecha Nacimiento</th>
    <th>Nacionalidad</th>
    <th>COD SIES OBTENCION TITULO</th>
    <th>Cod SIES CARRERA TERMINAL</th>
    <td >Nombre    del t&iacute;tulo obtenido por el estudiante (s&oacute;lo cuando corresponde)</td>
    <td >Nombre del grado obtenido por el    estudiante (s&oacute;lo cuando corresponde)</td>
    <td >Fecha de obtenci&oacute;n del t&iacute;tulo    (s&oacute;lo en caso de no haber t&iacute;tulo colocar fecha de obtenci&oacute;n del grado)</td>
    <td>N&deg; de semestres de suspensi&oacute;n    (llenar s&oacute;lo si hubo suspensi&oacute;n de estudios)</td>
    <td>A&ntilde;o de ingreso al    primer a&ntilde;o de la carrera</td>
    <td>Semestre de    Ingreso a primer a&ntilde;o</td>
    <td>A&ntilde;o de ingreso a    la carrera</td>
    <td>Semestre de    Ingreso a la carrera</td>
    <td>A&ntilde;o de egreso del    plan regular</td>
    <td>Semestre de    Egreso</td>
	<td>Estado</td>
	<td>Carrera</td>
	<td>Sede</td>
</thead>
<tbody>';
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	include("../../../funciones/funcion.php");
	include("../../../funciones/VX.php");
	
	$ARRAY_SEXO["M"]="H";
	$ARRAY_SEXO["F"]="M";
		$sede=mysqli_real_escape_string($conexion_mysqli, $_POST["sede"]);
		//$titulo_year=mysqli_real_escape_string($conexion_mysqli, $_POST["year"]);
		$fecha_inicio=mysqli_real_escape_string($conexion_mysqli, $_POST["fecha_inicio"]);
		$fecha_fin=mysqli_real_escape_string($conexion_mysqli, $_POST["fecha_fin"]);
		//$contrato_semestre=$_POST["semestre_vigencia_contrato"];
		$situacion_alumno="T";
		
		
		$evento="Genera informe SIES TITULADOS periodo [$fecha_inicio - $fecha_fin] sede: $sede";
		REGISTRA_EVENTO($evento);
		
		echo"<strong>Sede:</strong> $sede <strong>PERIODO</strong> [$fecha_inicio - $fecha_fin]";
		if(DEBUG){ var_export($_POST);}
		$hay_condiciones=true;
				
		if($sede!="0")
		{
			 $condicion_sede=" proceso_titulacion.sede='$sede' AND";
			 $hay_condiciones=true;
		}
		else
		{ $condicion_sede="";}
		
		$cons_main_1="SELECT DISTINCT(id_alumno),proceso_titulacion.id_carrera, proceso_titulacion.yearIngresoCarrera FROM proceso_titulacion INNER JOIN alumno ON proceso_titulacion.id_alumno = alumno.id WHERE $condicion_sede titulo_fecha_emision BETWEEN'$fecha_inicio' AND '$fecha_fin' ORDER by proceso_titulacion.id_carrera, alumno.apellido_P, apellido_M";
		
		if(DEBUG){ echo"<br><br><b>$cons_main_1</b><br>";}
		$sql_main_1=$conexion_mysqli->query($cons_main_1)or die($conexion_mysqli->error);
		$num_reg_M=$sql_main_1->num_rows;
		if(DEBUG){ echo"NUM GLOBAL: $num_reg_M<br>";}
		
		$contador=0;
		if($num_reg_M>0)
		{
			$primera_vuelta=true;
			while($DID=$sql_main_1->fetch_row())
			{
				$mostrar_alumno=false;
				$id_alumno=$DID[0];
				$id_carrera_alumno=$DID[1];
				$yearIngresoCarrera=$DID[2];
				
				if(DEBUG){ echo"<br><br>UID:$id_alumno yearIngresoCarrera.$yearIngresoCarrera id_carrera: $id_carrera_alumno<br>";}
					
					//--------------------------------------------------------------------------------------------------------------// 
					$cons_A="SELECT alumno.*, proceso_titulacion.* FROM alumno INNER JOIN proceso_titulacion ON  alumno.id =proceso_titulacion.id_alumno WHERE proceso_titulacion.id_alumno='$id_alumno' AND proceso_titulacion.id_carrera='$id_carrera_alumno' AND proceso_titulacion.yearIngresoCarrera='$yearIngresoCarrera' LIMIT 1";
					$sql_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
						$A=$sql_A->fetch_assoc();
					$sql_A->free();	
					//------------------------------------------------------------------------------------------------------------------/
							$mostrar_alumno=true;
							if(DEBUG){ echo"--->$cons_A<br>";}
							
							$A_nombre=$A["nombre"];
							$A_apellido_P=$A["apellido_P"];
							$A_apellido_M=$A["apellido_M"];
							$A_rut=$A["rut"];	
							
							#echo"RUT: $A_rut<br>";	
							$array_rut=explode("-",$A_rut);
								$aux_rut_sin_guion=$array_rut[0];
								$aux_dv=$array_rut[1];
							
							//$A_year_titulo_proceso_titulacion=$A["year_titulo"];
									
							$A_carrera=$A["carrera"];
							$A_id_carrera=$A["id_carrera"];
							$A_sexo=$A["sexo"];
							$A_fecha_nac=$A["fnac"];
							$A_nacionalidad=$A["pais_origen"];
							$A_sede=$A["sede"];
							$A_jornada=$A["jornada"];
								
							$aux_codigo_carrera=CODIGO_CARRERA_SIES($A_sede, $A_jornada, $A_carrera, $id_carrera_alumno);
							list($alumno_es_egresado, $egreso_semestre, $egreso_year)=ES_EGRESADO_V2($id_alumno, $id_carrera_alumno, $yearIngresoCarrera);
							
							if(DEBUG){ echo"EGRESO year: $egreso_year - EGRESO semestre: $egreso_semestre<br>";}
							
							$A_ingreso_primer_year=$yearIngresoCarrera;
							$A_ingreso_primer_semestre=1;
								$A_ingreso_year=$A_ingreso_primer_year;
								$A_ingreso_semestre=1;
								$A_nivel=$A["nivel"];
								
							/////////////////////
							$A_fecha_emision_titulo=$A["titulo_fecha_emision"];	
							$A_nombre_titulo=$A["nombre_titulo"];
							$A_year_acta=$A["year_titulo"];
							if(empty($A_nombre_titulo)){ $A_nombre_titulo="-";}
							
							$A_semestres_suspencion=0;
							//////////////////////////////////////////	
							$proceso_terminal=1;
								
							
							if($mostrar_alumno)
							{
								if(DEBUG){ echo"Mostrar Alumno: SI<br>";}
								if($primera_vuelta)
								{ 
									$carrera_old=$A_carrera;
									$primera_vuelta=false;
									
								}
								
								if($A_carrera==$carrera_old)
								{ $contador++;}
								else
								{$contador=1;}
								/////////////////////////////////
								//semestres suspencion
								$A_semestres_suspencion=SEMESTRE_SUSPENCION($id_alumno, $A_id_carrera, $yearIngresoCarrera);
								//////////////////////////////////
								
								
								
								
								$carrera_old=$A_carrera;
								
								echo'<tr>
								<td bgcolor="#FF9900">'.$contador.'</td>
								<td>1</td>
								<td>R</td>
								<td>'.$aux_rut_sin_guion.'</td>
								<td>'.$aux_dv.'</td>
								<td>'.utf8_decode($A_apellido_P).'</td>
								<td>'.utf8_decode($A_apellido_M).'</td>
								<td>'.utf8_decode($A_nombre).'</td>
								<td>'.$ARRAY_SEXO[$A_sexo].'</td>
								<td>'.fecha_format($A_fecha_nac).'</td>
								<td>'.$A_nacionalidad.'</td>
								<td>'.$aux_codigo_carrera.'</td>
								<td>'.$aux_codigo_carrera.'</td>
								<td>'.utf8_decode($A_nombre_titulo).'</td>
								<td>&nbsp;</td>
								<td>'.$A_fecha_emision_titulo.'</td>
								<td>'.$A_semestres_suspencion.'</td>
								<td>'.$A_ingreso_year.'</td>
								<td>'.$A_ingreso_primer_semestre.'</td>
								<td>'.$A_ingreso_year.'</td>
								<td>'.$A_ingreso_semestre.'</td>
								<td>'.$egreso_year.'</td>
								<td>'.$egreso_semestre.'</td>
								<td>1</td>
								<td bgcolor="#FF9900">'.utf8_decode($A_carrera).'</td>
								<td bgcolor="#FF9900">'.$A_sede.'</td>
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
	echo'</tbody></table>';

//--------------------------------------------------//
?>