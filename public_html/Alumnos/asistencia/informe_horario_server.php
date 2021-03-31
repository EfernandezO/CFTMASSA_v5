<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("registro_horario_clases");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("informe_horario_server.php");
$xajax->configure('javascript URI','/../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_PERIODOS");
$xajax->register(XAJAX_FUNCTION,"CARGA_INFORME_ASISTENCIA");
$xajax->register(XAJAX_FUNCTION,"BUSCA_ASIGNATURAS");
////////////////////////////////////////////
function BUSCA_ASIGNATURAS($id_alumno, $id_carrera, $semestre, $year, $sede)
{
	$objResponse = new xajaxResponse();
	$div='div_asignaturas';
	$html_tabla='<table width="100%">
				 <thead>
				 <tr>
				 	<th colspan="4">Seleccione Asignatura</th>
				 </tr>
				 <tr>
				 	<td>Asignatura</td>
					<td>Jornada</td>
					<td>Grupo</td>
					<td>Opc</td>
				 </tr>
				 </thead>
				 <tbody>';
	 require("../../../funciones/conexion_v2.php");
	 require("../../../funciones/funciones_sistema.php");
	
	 
      $cons_TR="SELECT `cod_asignatura`, `jornada`, grupo FROM `asistencia_alumnos` WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND semestre='$semestre' AND year='$year' AND sede='$sede' GROUP BY `cod_asignatura`, `jornada`, grupo ORDER by `cod_asignatura`, `jornada`";
		
		$sql_TR=$conexion_mysqli->query($cons_TR)or die($conexion_mysqli->error);
		$num_periodos=$sql_TR->num_rows;
		
		$msj="";
		if($num_periodos>0)
		{
			while($PTR=$sql_TR->fetch_assoc())
			{
				$periodo_cod_asignatura=$PTR["cod_asignatura"];
				$periodo_jornada=$PTR["jornada"];
				$periodo_grupo_curso=$PTR["grupo"];
				
				 list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $periodo_cod_asignatura);
				 
				$html_tabla.='<tr>
								<td>'.$periodo_cod_asignatura.'_'.$nombre_asignatura.'</td>
								<td>'.$periodo_jornada.'</td>
								<td>'.$periodo_grupo_curso.'</td>
								<td><a href="#" class="button_R" onclick="xajax_CARGA_INFORME_ASISTENCIA(\''.$id_alumno.'\', \''.$id_carrera.'\', \''.$semestre.'\', \''.$year.'\', \''.$sede.'\', \''.$periodo_cod_asignatura.'\', \''.$periodo_jornada.'\', \''.$periodo_grupo_curso.'\');">Revisar ↓</a></td>
								</tr>';
			}
		}
		else
		{ $msj="<tr><td>Sin Registros...</td></tr>";}
		$sql_TR->free();
		
		$html_tabla.='</tbody></table>';
 	  
	$conexion_mysqli->close();
	@mysql_close($conexion);
	
	$objResponse->Assign($div,"innerHTML",$html_tabla);
	//---------------------------------------------//
	return $objResponse;
}
function BUSCA_PERIODOS($id_alumno, $id_carrera)
{
	$objResponse = new xajaxResponse();
	$div='div_periodos';
	$html_tabla='<table width="100%">
				 <thead>
				 <tr>
				 	<th colspan="3">Seleccione Periodo a consulta</th>
				 </tr>
				 <tr>
				 	<td>Semestre</td>
					<td>Year</td>
					<td>Opc</td>
				 </tr>
				 </thead>
				 <tbody>';
	 require("../../../funciones/conexion_v2.php");
      $cons_TR="SELECT `semestre`, `year`, sede FROM `asistencia_alumnos` WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' GROUP BY `semestre`, `year`, sede ORDER by `year`, `semestre`";
		
		$sql_TR=$conexion_mysqli->query($cons_TR);
		$num_periodos=$sql_TR->num_rows;
		
		$msj="";
		if($num_periodos>0)
		{
			while($PTR=$sql_TR->fetch_assoc())
			{
				$periodo_semestre=$PTR["semestre"];
				$periodo_year=$PTR["year"];
				$periodo_sede=$PTR["sede"];
				
				$html_tabla.='<tr>
								<td>'.$periodo_semestre.'</td>
								<td>'.$periodo_year.'</td>
								<td><a href="#" class="button_R" onclick="xajax_BUSCA_ASIGNATURAS(\''.$id_alumno.'\', \''.$id_carrera.'\', \''.$periodo_semestre.'\', \''.$periodo_year.'\', \''.$periodo_sede.'\');">Revisar -></a></td>
								</tr>';
			}
		}
		else
		{ $msj="<tr><td>Sin Registros...</td></tr>";}
		$sql_TR->free();
		
		$html_tabla.='</tbody></table>';
 	  
	$conexion_mysqli->close();
	@mysql_close($conexion);
	
	$objResponse->Assign($div,"innerHTML",$html_tabla);
	//---------------------------------------------//
	return $objResponse;
}

//-------------------------------------------------------------------------------------------//
function CARGA_INFORME_ASISTENCIA($id_alumno, $id_carrera, $semestre, $year, $sede, $cod_asignatura, $jornada, $grupo_curso)
{
	$objResponse = new xajaxResponse();
	$ARRAY_MESES=array("01"=>"Enero",
						"02"=>"Febrero",
						"03"=>"Marzo",
						"04"=>"Abril",
						"05"=>"Mayo",
						"06"=>"Junio",
						"07"=>"Julio",
						"08"=>"Agosto",
						"09"=>"Septiembre",
						"10"=>"Octubre",
						"11"=>"Noviembre",
						"12"=>"Diciembre");
	
	
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	$div='div_resultados';
	$aux=0;
	 list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);
	
	$html='<table  align="left" width="100%">
    <thead>
   <tr>
    	<th colspan="28">Lista Alumnos '.$sede.' carrera:'.NOMBRE_CARRERA($id_carrera).' Jornada: '.$jornada.' <br />
		'.$nombre_asignatura.' Periodo['.$semestre.' - '.$year.']
		</th>
    </tr>
	<tr>
    	<td rowspan="2">N.</td>
        <td rowspan="2">Rut</td>
        <td rowspan="2">Nombre</td>
        <td rowspan="2">Apellido P</td>
        <td rowspan="2">Apellido M</td>';
		
	   //-------------------------------------------------------------------------------//
	//busco distintas fechas en las que se paso lista
	$cons_F="SELECT DISTINCT(fecha_clase) FROM asistencia_alumnos WHERE sede='$sede' AND semestre='$semestre' AND year='$year' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND jornada='$jornada' AND grupo='$grupo_curso' ORDER by fecha_clase";
	
	$sqli_F=$conexion_mysqli->query($cons_F)or die($conexion_mysqli->error);
	$num_fechas=$sqli_F->num_rows;
	$ARRAY_FECHAS=array();
	$aux_array_fecha=array();
	if(DEBUG){ echo"-->$cons_F<br>NUM FECHAS: $num_fechas<br>";}
	if($num_fechas)
	{
		while($F=$sqli_F->fetch_row())
		{
			$F_fecha_clase=$F[0];
			$ARRAY_FECHAS[]=$F_fecha_clase;
			list($aux_year, $aux_mes, $aux_dia)=explode("-",$F_fecha_clase);
			$aux_array_fecha[$aux_year][$aux_mes][$aux_dia]=true;
			//$html.='<td>'.$F_fecha_clase.'</td>';
		}
		foreach($aux_array_fecha as $aux_year => $array_1)
		{
			foreach($array_1 as $aux_mes => $array_2)
			{
				$n_dias_mes=count($array_2);
				$html.='<td colspan="'.$n_dias_mes.'" align="center">'.$ARRAY_MESES[$aux_mes].' - '.$aux_year.'</td>';
			}
		}
		
	}
	else
	{
		$html.='<td>Sin Registros</td>';
	}
	$sqli_F->free();
	//var_dump($aux_array_fecha);
	//-------------------------------------------------------------------------------//
       $html.='
	   <td rowspan="2">Total Hrs Clase</td>
       <td rowspan="2">Total Hrs Asistida</td>
	   <td rowspan="2">% Asistencia clase realizada</td>
       <td rowspan="2">Total Hrs Programa</td>
       <td rowspan="2">% Asistencia</td>
    </tr>
	<tr>';
		foreach($aux_array_fecha as $aux_year => $array_1)
		{
			foreach($array_1 as $aux_mes => $array_2)
			{
				foreach($array_2 as $aux_dia => $valor)
				{
					$html.='<td align="center">'.$aux_dia.'</td>';
				}
			}
		}
	$html.='</tr>
</thead>
    <tbody>';

$TOTAL_HORAS_PROGRAMA=HORAS_PROGRAMA($id_carrera, $cod_asignatura,"semestral","teorico");
	
		$cons_A="SELECT rut, nombre, apellido_P, apellido_M, ingreso, situacion FROM alumno WHERE id='$id_alumno' LIMIT 1";
		$sqli_a=$conexion_mysqli->query($cons_A);
		$A=$sqli_a->fetch_assoc();
		$A_rut=$A["rut"];
		$A_nombre=$A["nombre"];
		$A_apellido_P=$A["apellido_P"];
		$A_apellido_M=$A["apellido_M"];
		$A_ingreso=$A["ingreso"];
	
		$sqli_a->free();
		if(DEBUG){ echo"--->ID alumno: $id_alumno $A_rut $A_nombre $A_apellido_P $A_apellido_M<br>";}
		
		//--------------------------------------------------------------------------------------//
		//verificacion de matricula
		$A_situacion=ESTADO_ALUMNO_PERIODO($id_alumno, $id_carrera,$semestre, $year);
		$alumno_con_matricula=VERIFICAR_MATRICULA($id_alumno, $id_carrera, true, false, $semestre, false, $year);
		
		if($alumno_con_matricula){$mostrar_alumno=true;}
		else{$mostrar_alumno=false;}
		
		if($A_situacion=="V"){ $mostrar_alumno_2=true;}
		else{ $mostrar_alumno_2=false;}
		
		if(($mostrar_alumno)and($mostrar_alumno_2))	
		{
			$aux++;
			$html.='<tr>
					<td>'.$aux.'</td>
					<td>'.$A_rut.'</td>
					<td>'.$A_nombre.'</td>
					<td>'.$A_apellido_P.'</td>
					<td>'.$A_apellido_M.'</td>';
					$hrs_asistencia_alumno=0;
					$porcentaje_asistencia_alumno=0;
					$porcentaje_asistencia_clase_realizada=0;
					$TOTAL_HORAS_ALUMNO=0;
					$hrs_clase_realizada=0;
					foreach($ARRAY_FECHAS as $i => $aux_fecha_clase)		
					{
						$cons_AF="SELECT asistencia,  horas_pedagogicas FROM asistencia_alumnos WHERE sede='$sede' AND semestre='$semestre' AND year='$year' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND jornada='$jornada' AND id_alumno='$id_alumno' AND grupo='$grupo_curso'  AND fecha_clase='$aux_fecha_clase' LIMIT 1";
						$sqli_AF=$conexion_mysqli->query($cons_AF)or die($conexion_mysqli->error);
						$DAF=$sqli_AF->fetch_assoc();
							$A_asistencia_clase=$DAF["asistencia"];
							$A_hrs_pedagogicas_clase=$DAF["horas_pedagogicas"];
						$sqli_AF->free();
						
						$considerar=false;
						switch($A_asistencia_clase)
						{
							case"presente":
								$A_asistencia_clase_label="P";
								$considerar=true;
								$factor=1;
								$color="#0A0";
								break;
							case"ausente":
								$A_asistencia_clase_label="A";
								$considerar=true;
								$factor=0;
								$color="#F77";
								break;
							case"justificado":
								$A_asistencia_clase_label="J";
								$considerar=true;
								$factor=0.7;
								$color="#0AA";
								break;	
							default:
								$A_asistencia_clase_label="";
								$color="";	
						}
						if($considerar)
						{
							$A_hrs_asistencia_alumno=($A_hrs_pedagogicas_clase*$factor);
							$TOTAL_HORAS_ALUMNO+=$A_hrs_asistencia_alumno;
							$hrs_clase_realizada+=$A_hrs_pedagogicas_clase;

						}
						$html.='<td bgcolor="'.$color.'" align="center"><a title="'.$A_hrs_asistencia_alumno.'/'.$A_hrs_pedagogicas_clase.'">'.$A_asistencia_clase_label.'</a></td>';
					}	
					if($TOTAL_HORAS_PROGRAMA>0)
					{$porcentaje_asistencia_alumno=(($TOTAL_HORAS_ALUMNO*100)/ $TOTAL_HORAS_PROGRAMA);	}
					else{$porcentaje_asistencia_alumno=0;}
					
					if($hrs_clase_realizada >0)
					{$porcentaje_asistencia_clase_realizada=(($TOTAL_HORAS_ALUMNO*100)/$hrs_clase_realizada);}
					else{$porcentaje_asistencia_clase_realizada=0;}
					$html.='
						<td align="center">'.$hrs_clase_realizada.'</td>				
						<td align="center">'.$TOTAL_HORAS_ALUMNO.'</td>
						<td align="center">'.number_format($porcentaje_asistencia_clase_realizada,1).'</td>
						<td align="center">'.$TOTAL_HORAS_PROGRAMA.'</td>
						<td align="center">'.number_format($porcentaje_asistencia_alumno,1).'</td>
					</tr>';	
		}
				
	$conexion_mysqli->close();

$html.='</tbody>
</table>';
$objResponse->Assign($div,"innerHTML",$html);
	return $objResponse;
}
$xajax->processRequest();
?>