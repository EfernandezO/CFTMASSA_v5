<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(6000);
ini_set('memory_limit', '-1');
$tiempo_inicio_script = microtime(true);
$fecha_actual=date("Y-m-d");
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Procesos_masivos_excel_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
	$mostrar_no_encontrados_en_sistema=true;
//-----------------------------------------------//	
if($_GET)
{
	$nombre_archivo=base64_decode($_GET["archivo"]);
	if(!empty($nombre_archivo))
	{
		$directorio="../../CONTENEDOR_GLOBAL/trabajos_masivos/";
		if(DEBUG){ echo"Archivo A Procesar: $nombre_archivo<br>";}
		$archivo_a_procesar=$directorio.$nombre_archivo;
////////////////////////////////
//LECTURA DE EXCEL
/** Include path **/
set_include_path(get_include_path() . PATH_SEPARATOR . '../../libreria_publica/PHPExcel-1.7.7/Classes/');
/** PHPExcel_IOFactory */
include('../../libreria_publica/PHPExcel-1.7.7/Classes/PHPExcel/IOFactory.php');

$inputFileName = $archivo_a_procesar;
if(DEBUG){echo 'Cargando Archivo ',pathinfo($inputFileName,PATHINFO_BASENAME),'<br> usando IOFactory para identificar formato<br /><br>';}
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);


$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

$year_actual=date("Y");
$mes_actual=date("m");
	
if($mes_actual>=8)
{$semestre_actual=2;}
else{ $semestre_actual=1;}
	
$semestre_consulta=$semestre_actual;
$year_consulta=$year_actual;

$semestre_consulta=1;
$year_consulta=2019;

//PERIODO A CONSULTAR MANUAL DE MATRICULA
//$semestre_consulta=1;
//$year_consulta=2016;	
///////////////////////////////////////////////////////////////
//fin LECTURA EXCEL
/////////////////////
//comienza escritura archivo
$tabla="<table border=1>
			<thead>
			
			<tr>
				<td bgcolor='#FF0000'>PERIDO CONSULTA</td>
				<td bgcolor='#FF0000'>[".$semestre_consulta." - ".$year_consulta."]</td>
			</tr>
			<tr></tr>
			<tr></tr>
			</thead>
			<tbody>
			<tr>
				<td>Rut</td>
				<td>DV</td>
				<td>Matricula [$semestre_consulta / $year_consulta]</td>
				<td>Sexo</td>
				<td>Nombre</td>
				<td>Apellido P</td>
				<td>Apellido M</td>
				<td>Fono</td>
				<td>Email</td>
				<td>Ciudad</td>
				<td>Carrera</td>
				<td>Jornada</td>
				<td>Nivel</td>
				<td bgcolor='#CCCC33'>Nivel Academico</td>
				<td>Año Ingreso</td>
				<td>Sede</td>
				<td>Situacion</td>
				<td>Liceo</td>
				<td>Liceo Formacion</td>
				<td>Liceo Dependencia</td>
				<td>Pais estudios secundarios</td>
				<td>Edad</td>
			</tr>";
if(isset($sheetData))
{
	if(DEBUG){ echo"Hay Datos<br>";}
	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
	include("../../../funciones/funciones_varias.php");
	require("../../../funciones/funciones_sistema.php");
	require("../../../funciones/class_ALUMNO.php");
	
	
	
	foreach($sheetData as $fila => $array_columnas)
	{
		$continuar=false;
		
		if(isset($array_columnas["A"])){$COLUMNA_A=$array_columnas["A"];}
		else{ $COLUMNA_A=NULL;}
		
		
		if(isset($array_columnas["B"])){$COLUMNA_B=strtoupper($array_columnas["B"]);}
		else{$COLUMNA_B=NULL;}
		
		if(DEBUG){var_dump($COLUMNA_A); var_dump($COLUMNA_B);}
		
		if((empty($COLUMNA_B))and(!empty($COLUMNA_A)))
		{
			if(DEBUG){ echo"Solo Columna A con datos<br>";}
			$aux_rut=strip_tags($COLUMNA_A);
			$continuar=true;
				
				if(strpos($aux_rut,"-")){if(DEBUG){ echo"Rut Con DV<br>";}}
				else
				{ 
					if(DEBUG){ echo"Rut sin DV<br>";}
					$aux_dv=validar_rut($aux_rut);
					$aux_rut.="-".$aux_dv;
				}
			
		}
		else
		{
			if(DEBUG){echo"$fila -> $COLUMNA_A - $COLUMNA_B :";}
			$aux_rut=str_inde($COLUMNA_A,"")."-".str_inde($COLUMNA_B,"");
			
			if((is_numeric($COLUMNA_A))and(is_string($COLUMNA_B)))
			{ $continuar=true;}
			else{ $continuar=false;}
		}
		
		////--------------------------------------------/////
		if($continuar)
		{
			$ya_se_imprimio=false;
			$cons_1="SELECT * FROM alumno WHERE rut='$aux_rut' ORDER by ingreso desc LIMIT 1";
			if(DEBUG){ echo"-->$cons_1<br>";}
			$sql_1=$conexion_mysqli->query($cons_1)or die($conexion_mysqli->error);
			$num_registros_encontrados=$sql_1->num_rows;
			if($num_registros_encontrados>0)
			{
				$D_1=$sql_1->fetch_assoc();
				$ver_registro=true;
				if($ver_registro)
				{
					$RUT=explode("-",$aux_rut);
					//datos alumno
							$A_id=$D_1["id"];
							$ALUMNO=new ALUMNO($A_id);
							
							$A_situacion=$ALUMNO->getUltimaSituacionMat();
							$A_jornada=$ALUMNO->getJornadaActual();
							$A_nombre=$D_1["nombre"];
							$A_apellido_P=$D_1["apellido_P"];
							$A_apellido_M=$D_1["apellido_M"];
							$A_carrera=$D_1["carrera"];
							$A_id_carrera=$ALUMNO->getUltimaIdCarreraMat();
							$A_nivel=$ALUMNO->getNivelAcademicoActual();
							$A_sede=$ALUMNO->getSedeActual();
							$A_ingreso=$ALUMNO->getUltimoYearIngresoMat();
							$A_ciudad=$D_1["ciudad"];
							$A_fono=$D_1["fono"];
							$A_email=$D_1["email"];
							$A_sexo=$D_1["sexo"];
							$A_liceo=$D_1["liceo"];
							$A_liceo_formacion=$D_1["liceo_formacion"];
							$A_liceo_dependencia=$D_1["liceo_dependencia"];
							$A_arrayFechaNacimiento=explode("-",$D_1["fnac"]);
							$A_edad=$year_actual-$A_arrayFechaNacimiento[0];
							$A_liceo_pais=$D_1["liceo_pais"];
							
							
							
							
							if(DEBUG){ echo"<br><strong>id_alumno: $A_id Nombre: $A_nombre $A_apellido_P $A_apellido_M <br>Carrera: $A_carrera id_carrera:[$A_id_carrera] <br>Sede: $A_sede <br>Situacion: $A_situacion Year ingreso: $A_ingreso (nivel: $A_nivel)</strong><br>";}
							
							//----------------------------------------------------//
							//proceso de retiro
			
							////////
							//verifico si tienen matricula vigente
							
							$tiene_matricula_vigente=VERIFICAR_MATRICULA($A_id, $A_id_carrera,$A_ingreso, true, false, $semestre_consulta, false, $year_consulta);
							
							if($tiene_matricula_vigente){$matricula_vigente="si";}
							else{$matricula_vigente="no";}
							
							
							///consulto nivel academico
							//minimo nivel de las notas de los ramos que le faltan
							$cons_3="SELECT MIN(nivel) FROM notas WHERE id_alumno='$A_id' AND id_carrera='$A_id_carrera' AND ramo<>'' AND (nota='' OR nota<4)";
							if(DEBUG){ echo"--->$cons_3<br>";}
							$sqli_3=$conexion_mysqli->query($cons_3)or die($conexion_mysqli->error);
							$DNA=$sqli_3->fetch_row();
								$A_nivel_academico=$DNA[0];
								if(empty($A_nivel_academico)){ $A_nivel_academico=1;}
							$sqli_3->free();
							if(DEBUG){ echo"Nivel Academico: $A_nivel_academico<br>";}
							//-------------------------------------------------------------//
							
							
							$ya_se_imprimio=true;
							$tabla.='<tr>
										<td>'.$RUT[0].'</td>
										<td>'.$RUT[1].'</td>
										<td>'.$matricula_vigente.'</td>
										<td>'.$A_sexo.'</td>
										<td>'.utf8_decode($A_nombre).'</td>
										<td>'.utf8_decode($A_apellido_P).'</td>
										<td>'.utf8_decode($A_apellido_M).'</td>
										<td>'.$A_fono.'</td>
										<td>'.$A_email.'</td>
										<td>'.$A_ciudad.'</td>
										<td>'.utf8_decode($A_carrera).'</td>
										<td>'.$A_jornada.'</td>
										<td>'.$A_nivel.'</td>
										<td bgcolor="#CCCC33">'.$A_nivel_academico.'</td>
										<td>'.$A_ingreso.'</td>
										<td>'.$A_sede.'</td>
										<td>'.$A_situacion.'</td>
										<td>'.$A_liceo.'</td>
										<td>'.$A_liceo_formacion.'</td>
										<td>'.$A_liceo_dependencia.'</td>
										<td>'.$A_edad.'</td>
										<td>'.$A_liceo_pais.'</td>
										</tr>';
		
				}
				$sql_1->free();
			}
			else
			{
				if(DEBUG){echo"$aux_rut -> Alumno No encontrado en el Sistema<br>";}
				if($mostrar_no_encontrados_en_sistema){$tabla.='<tr><td bgcolor="#FFAA00">'.$aux_rut.'</td><td>Alumno No encontrado en el Sistema :(</td></tr>';}
			}
		}//fin si continuar
		else
		{
			if(DEBUG){echo"$aux_rut -> Rut Incorrecto<br>";}
			$tabla.='<tr><td bgcolor="#FFAA00">'.$aux_rut.'</td><td>Rut Incorrecto :(</td></tr>';
		}
		
	}//FIN FOREACH
	$conexion_mysqli->close();
	@mysql_close($conexion);
	$tiempo_fin_script = microtime(true);
	$tiempo_de_ejecucion=round($tiempo_fin_script - $tiempo_inicio_script,4);
	
	$tabla.='<tr><td>Tiempo de Ejecucion de Script '.$tiempo_de_ejecucion.' Segundos</td></tr>';
}
else
{
	if(DEBUG){ echo"Sin Datos<br>";}
	$tabla.='<tr><td>Sin Datos :(</td></tr>';
}
//////////////////////////////////////////////////////
	$tabla.="</tbody></table>";
		if(!DEBUG)
		{
			header('Content-type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=proceso_excel_v2.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		echo $tabla;
	}
	else
	{ echo"Sin Archivo Fuente Enviado...<br>";}
}
else
{
	header("location: index.php");
}
/////////////////////////////////////

?>