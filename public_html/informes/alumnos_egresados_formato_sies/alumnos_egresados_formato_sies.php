<?php 
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_alumnos_egresados_formato_sies_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------/
if(!DEBUG)
	{
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=EGRESADOS_FORMATO_SIES.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
echo'
  <table border="1">
<thead>
<tr>
	<th bgcolor="#FF9900">N.</th>
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
    <th>COD SIES CARRERA TERMINAL</th>
	<th>NOMBRE TITULO</th>
    <th>NOMBRE GRADO</th>
    <th>FECHA DE OBTENCIÓN DE EGRESO O LICENCIATURA NO TERMINAL</th>
    <th>N&deg; de semestres de suspensi&oacute;n</th>
    <th>A&ntilde;o de ingreso al    primer a&ntilde;o de la carrera</th>
    <th>Semestre de    Ingreso a primer a&ntilde;o</th>
    <th>A&ntilde;o de ingreso a    la carrera</th>
    <th>Semestre de    Ingreso a la carrera</th>
    <th>A&ntilde;o de egreso del    plan Estudios</th>
    <th>Semestre de    Egreso</th>
	<th>Estado</th>
	<th  bgcolor="#FF9900">Carrera</th>
	<th  bgcolor="#FF9900">Sede</th>
	</tr>
</thead>
<tbody>';
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	include("../../../funciones/funcion.php");
	include("../../../funciones/VX.php");
	$ARRAY_SEXO["M"]="H";
	$ARRAY_SEXO["F"]="M";
	
		$sede=mysqli_real_escape_string($conexion_mysqli, $_POST["sede"]);
		$egreso_year=mysqli_real_escape_string($conexion_mysqli, $_POST["year"]);
		//$contrato_semestre=$_POST["semestre_vigencia_contrato"];
		$situacion_alumno="T";
		
		
		$evento="Genera informe SIES EGRESADOS year: $egreso_year sede: $sede";
		REGISTRA_EVENTO($evento);
		
		echo"<strong>Sede:</strong> $sede <strong>Año Egreso:</strong> $egreso_year";
		if(DEBUG){ var_export($_POST);}
		$hay_condiciones=true;
				
		if($sede!="0")
		{
			 $condicion_sede="AND proceso_titulacion.sede='$sede'";
			 $hay_condiciones=true;
		}
		else
		{ $condicion_sede="";}
		
		$cons_main_1="SELECT alumno.rut, alumno.apellido_P, alumno.apellido_M, alumno.nombre, alumno.sexo, alumno.fnac, alumno.jornada, alumno.pais_origen, proceso_egreso.* FROM proceso_egreso INNER JOIN alumno ON proceso_egreso.id_alumno=alumno.id WHERE  proceso_egreso.year_egreso='$egreso_year' ORDER by apellido_P, apellido_M";
		
		if(DEBUG){ echo"<br><br><b>$cons_main_1</b><br>";}
		$sql_main_1=$conexion_mysqli->query($cons_main_1)or die($conexion_mysqli->error);
		$num_reg_M=$sql_main_1->num_rows;
		if(DEBUG){ echo"NUM GLOBAL: $num_reg_M<br>";}
		
		$contador=0;
		if($num_reg_M>0)
		{
			$primera_vuelta=true;
			while($DID=$sql_main_1->fetch_assoc())
			{
				$mostrar_alumno=false;
				$id_alumno=$DID["id_alumno"];
				$id_carrera=$DID["id_carrera"];
				$yearIngresoCarrera=$DID["yearIngresoCarrera"];
				
				if(DEBUG){ echo"<br><br>UID:$id_alumno id_carrera: $id_carrera yearIngresoCarrera: $yearIngresoCarrera<br>";}		
				$A_nombre=$DID["nombre"];
				$A_apellido_P=$DID["apellido_P"];
				$A_apellido_M=$DID["apellido_M"];
				$A_rut=$DID["rut"];		
				$array_rut=explode("-",$A_rut);
					$aux_rut_sin_guion=$array_rut[0];
					$aux_dv=$array_rut[1];
						
				
				$A_sexo=$DID["sexo"];
				$A_fecha_nac=$DID["fnac"];
				$A_nacionalidad=$DID["pais_origen"];
				$A_sede=$DID["sede"];
				$A_jornada=$DID["jornada"];
					
				$aux_codigo_carrera=CODIGO_CARRERA_SIES($A_sede, $A_jornada, NOMBRE_CARRERA($id_carrera), $id_carrera);
				list($alumno_es_egresado, $egreso_semestreX, $egreso_yearX)=ES_EGRESADO_V2($id_alumno, $id_carrera, $yearIngresoCarrera);
				
				$cons_FE="SELECT fecha_generacion FROM proceso_egreso WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera'";
				$sqli_FE=$conexion_mysqli->query($cons_FE);
				$DFE=$sqli_FE->fetch_assoc();
				$A_fechaEgreso=$DFE["fecha_generacion"];
				$sqli_FE->free();
				
							
				if(DEBUG){ echo"EGRESO year: $egreso_yearX - EGRESO semestre: $egreso_semestreX<br>";}
				
				if($egreso_year>0)
				{
					if($egreso_year==$egreso_yearX)
					{$mostrar_alumno=true;}
					else{ $mostrar_alumno=false; if(DEBUG){ echo"No Mostrar Alumno year Egreso no Concuerda con consulta[$egreso_year - $egreso_yearX]<br>";}}
				}
				else
				{ $mostrar_alumno=true;}
				
				
				$A_ingreso_primer_year=$yearIngresoCarrera;
				$A_ingreso_primer_semestre=1;
					$A_ingreso_year=$A_ingreso_primer_year;
					$A_ingreso_semestre=1;
					$A_nivel=5;
					
				////////////////////
				
				$A_semestres_suspencion=0;
				//////////////////////////////////////////	
					/////////////////////////////////
					//semestres suspencion
					$A_semestres_suspencion=SEMESTRE_SUSPENCION($id_alumno, $id_carrera, $yearIngresoCarrera);
					//////////////////////////////////
					$carrera_old=$id_carrera;
					
					
				if($mostrar_alumno)	
				{
					$contador++;
					echo'<tr>
					<td  bgcolor="#FF9900">'.$contador.'</td>
					<td>4</td>
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
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>'.$A_fechaEgreso.'</td>
					<td>'.$A_semestres_suspencion.'</td>
					<td>'.$A_ingreso_year.'</td>
					<td>'.$A_ingreso_primer_semestre.'</td>
					<td>'.$A_ingreso_year.'</td>
					<td>'.$A_ingreso_semestre.'</td>
					<td>'.$egreso_yearX.'</td>
					<td>'.$egreso_semestreX.'</td>
					<td>1</td>
					<td  bgcolor="#FF9900">'.utf8_decode(NOMBRE_CARRERA($id_carrera)).'</td>
					<td  bgcolor="#FF9900">'.$A_sede.'</td>
					</tr>';
				}
			}
		}
		else
		{
			if(DEBUG){ echo"SIN REGISTROS<br>";}
		}
	//--------------------------------------------------//
		$sql_main_1->free();
	@mysql_close($conexion);
	$conexion_mysqli->close();
	echo'</tbody></table>';

//--------------------------------------------------//
?>