<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../../funciones/";
	$O->clave_del_archivo=md5("registro_horario_clases");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
@require_once ("../../../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("informe_horario_server.php");
$xajax->configure('javascript URI','../../../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_GRUPOS_ASISTENCIA");
$xajax->register(XAJAX_FUNCTION,"CARGA_INFORME_ASISTENCIA");
////////////////////////////////////////////
function BUSCA_GRUPOS_ASISTENCIA($sede, $id_carrera, $jornada, $grupo_curso, $cod_asignatura, $semestre, $year)
{
	$objResponse = new xajaxResponse();
	require("../../../../../../funciones/conexion_v2.php");
	$div='div_grupo';
	$html_tabla='<table width="100%" align="left">
  <thead>
  	<tr><th colspan="2">Seleccion de Grupo</th></tr>
    </thead>
    <tbody>
	<td>Grupo Asistencia</td>';

	//busco distintas fechas en las que se paso lista
	$select='<select id="grupo_asistencia" name="grupo_asistencia">';	
	$ARRAY_GRUPO_ASISTENCIA=array();	
	$cons_G="SELECT DISTINCT(participantes_curso) FROM asistencia_alumnos WHERE sede='$sede' AND semestre='$semestre' AND year='$year' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND jornada='$jornada' AND grupo='$grupo_curso' ORDER by fecha_clase";
	$sqli_G=$conexion_mysqli->query($cons_G)or die($conexion_mysqli->error);
	$num_grupos=$sqli_G->num_rows;
	if(DEBUG){ echo"-->$cons_G<br>NUM Grupos Asistencia: $num_grupos<br>";}
	if($num_grupos)
	{
		while($G=$sqli_G->fetch_row())
		{
			$grupo_asistencia=$G[0];
			$ARRAY_GRUPO_ASISTENCIA[]=$grupo_asistencia;
			if($grupo_asistencia=="0"){ $grupo_asistencia_label="Todo el curso";}
			else{ $grupo_asistencia_label=$grupo_asistencia;}
			$select.='<option value="'.$grupo_asistencia.'">'.$grupo_asistencia_label.'</option>';
		}
	}
	$select.='</select>';
	$sqli_G->free();		  
	$conexion_mysqli->close();
	@mysql_close($conexion);
	
	$html_tabla.='<td>'.$select.'</td>
	</tr>
	<tr>
		<td colspan="2" align="right"><a href="#" onclick="xajax_CARGA_INFORME_ASISTENCIA(\''.$sede.'\', \''.$id_carrera.'\', \''.$jornada.'\', \''.$grupo_curso.'\', \''.$cod_asignatura.'\', \''.$semestre.'\', \''.$year.'\', document.getElementById(\'grupo_asistencia\').value)" class="button_R">Buscar Alumnos</a></td>
	</tr>
	</table>';
	
	$objResponse->Assign($div,"innerHTML",$html_tabla);
	//---------------------------------------------//
	return $objResponse;
}
//-------------------------------------------------------------------------------------------//
function CARGA_INFORME_ASISTENCIA($sede, $id_carrera, $jornada, $grupo_curso, $cod_asignatura, $semestre, $year)
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
	
	
	require("../../../../../../funciones/conexion_v2.php");
	require("../../../../../../funciones/funciones_sistema.php");
	$div='apDiv2';
	
	list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);
	
	$html='<table  align="left" width="100%">
    <thead>
   <tr>
    	<th colspan="50">Lista Alumnos '.$sede.' carrera:'.NOMBRE_CARRERA($id_carrera).' Jornada: '.$jornada.' <br />
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
	
	$cons_MAIN="SELECT DISTINCT(toma_ramos.id_alumno) FROM toma_ramos INNER JOIN alumno ON toma_ramos.id_alumno=alumno.id WHERE alumno.sede='$sede' AND toma_ramos.id_carrera='$id_carrera' AND alumno.id_carrera='$id_carrera' AND toma_ramos.jornada='$jornada' AND alumno.grupo='$grupo_curso' AND toma_ramos.cod_asignatura='$cod_asignatura' AND toma_ramos.semestre='$semestre' AND toma_ramos.year='$year' ORDER by alumno. apellido_P, alumno.apellido_M";
	if(DEBUG){ echo"--->$cons_MAIN<br>";}
	$sqli=$conexion_mysqli->query($cons_MAIN)or die($conexion_mysqli->error);
	$num_registros=$sqli->num_rows;
	if(DEBUG){ echo"N. $num_registros<br>";}
	
	
	if($num_registros>0)
	{
		
		$aux=0;
		while($IA=$sqli->fetch_row())
		{
			$id_alumno=$IA[0];
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
				
		}
	}
	$sqli->free();
	$conexion_mysqli->close();

$html.='</tbody>
</table>';
$objResponse->Assign($div,"innerHTML",$html);
	return $objResponse;
}
$xajax->processRequest();
?>