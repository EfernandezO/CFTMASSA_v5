<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
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
	define("YEAR_CONSULTA",2020);/////////////establecer para notas
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

//$semestre_consulta=2;
//$year_consulta=2020;

//PERIODO A CONSULTAR MANUAL DE MATRICULA
	require("../../../funciones/conexion_v2.php");
//lleno array con beneficios actuales
	$ARRAY_BENEFICIOS_ESTUDIANTILES=array();
	$cons_BE="SELECT * FROM beneficiosEstudiantiles ORDER by id";
		$sqli_BE=$conexion_mysqli->query($cons_BE);
		$numBeneficios=$sqli_BE->num_rows;
		if($numBeneficios>0){
			while($DBE=$sqli_BE->fetch_assoc()){
				$BE_id=$DBE["id"];
				$BE_nombre=$DBE["beca_nombre"];
				$BE_tipoAporte=$DBE["beca_tipo_aporte"];
				$BE_aporteValor=$DBE["beca_aporte_valor"];
				$BE_aportePorcentaje=$DBE["beca_aporte_porcentaje"];
				$ARRAY_BENEFICIOS_ESTUDIANTILES[$BE_id]["nombre"]=$BE_nombre;
			}
		}
		$sqli_BE->free();

///////////////////////////////////////////////////////////////
//fin LECTURA EXCEL
/////////////////////
//comienza escritura archivo
$tabla="<table border=1>
			<thead>
			<tr>
				<td bgcolor='#FF0000'>Year para Notas</td>
				<td bgcolor='#FF0000'>".YEAR_CONSULTA."</td>
			</tr>
			<tr>
				<td bgcolor='#FF0000'>PERIODO CONSULTA</td>
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
				<td>Nombre</td>
				<td>Apellido P</td>
				<td>Apellido M</td>
				<td>Fecha Nac</td>
				<td>Pais origen</td>
				<td>Pais Estudios Secundarios</td>
				<td>Sexo</td>
				<td>Fono</td>
				<td>Email</td>
				<td>Email Institucional</td>
				<td>Ciudad</td>
				<td>Direccion</td>
				<td>NEM</td>
				<td>id_carrera</td>
				<td>Carrera</td>
				<td>Carrera SIES</td>
				<td>Jornada</td>
				<td>Nivel</td>
				<td bgcolor='#CCCC33'>Nivel Academico</td>
				<td>Año Ingreso</td>
				<td>Sede</td>
				<td>Situacion</td>
				<td>Semestre Egreso</td>
				<td>Year Egreso</td>
				<td>Proceso de Retiro</td>
				<td>Condicion Contrato</td>
				<td>Vigencia Contrato</td>
				<td>nivel Alumno Realiza Contrato</td>
				<td>Semestre Contrato</td>
				<td>Ano Contrato</td>
				<td>Fecha Contrato</td>
				<td bgcolor='#66CC66'>Matricula a Pagar</td>
				<td bgcolor='#66CC66'>Forma pago Matricula</td>";
				
				$tabla.="<td bgcolor='#66CC66'>SEMESTRE CON BECA BNM o BET</td>
				<td bgcolor='#66CC66'>BNM</td>
				<td bgcolor='#66CC66'>Aporte BNM</td>
				<td bgcolor='#66CCaa'>BET</td>
				<td bgcolor='#66CCaa'>Aporte BET</td>
				<td bgcolor='#66CCaa'>cantidad_desc</td>
				<td bgcolor='#66CCaa'>%desc</td>";
				
				foreach($ARRAY_BENEFICIOS_ESTUDIANTILES as $id =>$auxArray){
					$tabla.="<td bgcolor=\"#66FFCC\">".$auxArray["nombre"]."</td>";	
				}
				
	   $tabla.="<td bgcolor='#66CC66'>totalbeneficiosEstudiantiles</td>
	   			<td bgcolor='#66CC66'>L_Credito</td>
				<td bgcolor='#66CC66'>Arancel</td>
				<td bgcolor='#66CC66'>Excedente</td>
				<td bgcolor='#ff55aa'>Deuda Actual al (".$fecha_actual.")</td>
				<td bgcolor='#AA66bb'>Deuda TOTAL</td>
				<td bgcolor='#F5ACA9'>Num. Ramos Inscritos(toma ramo)[1 - ".YEAR_CONSULTA."]</td>
				<td bgcolor='#F5ACA9'>Num. Ramos Aprobados(toma ramo)[1 - ".YEAR_CONSULTA."]</td>
				<td bgcolor='#F5ACA9'>Num. Ramos Reprobados(toma ramo)[1 - ".YEAR_CONSULTA."]</td>
				
				<td bgcolor='#E5ACA9'>Num. Ramos Inscritos(toma ramo)[2 - ".YEAR_CONSULTA."]</td>
				<td bgcolor='#E5ACA9'>Num. Ramos Aprobados(toma ramo)[2 - ".YEAR_CONSULTA."]</td>
				<td bgcolor='#E5ACA9'>Num. Ramos Reprobados(toma ramo)[2 - ".YEAR_CONSULTA."]</td>
				
				
				<td bgcolor='#F5BCA9'>Num. Ramos Inscritos(toma ramo)".YEAR_CONSULTA."</td>
				<td bgcolor='#F79F81'>Num. Ramos Aprobados(toma ramo)".YEAR_CONSULTA."</td>
				<td bgcolor='#FAAC58'>Ultimo periodo TOMA_RAMO</td>
				<td bgcolor='#FE9A2E'>Num Ramos Inscritos Historico(toma ramo hasta ".YEAR_CONSULTA.")</td>
				<td bgcolor='#FACC2E'>Num Ramos Aprobados Historico(toma ramo hasta ".YEAR_CONSULTA.")</td>
				<td bgcolor='#66CC66'>Promedio Nota asignaturas Tomadas(Año ".YEAR_CONSULTA.")</td>
				
				<td bgcolor='#66CC66'>Promedio Nota asignaturas Tomadas(1 - Año ".YEAR_CONSULTA.")</td>
				<td bgcolor='#66CC66'>Promedio Nota asignaturas Tomadas(2 - Año ".YEAR_CONSULTA.")</td>
			</tr>";
if(isset($sheetData))
{
	if(DEBUG){ echo"Hay Datos<br>";}

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
			$cons_1="SELECT * FROM alumno WHERE rut='$aux_rut' LIMIT 1";
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
							$ALUMNO->SetDebug(DEBUG);
							$ALUMNO->IR_A_PERIODO($semestre_consulta, $year_consulta);
							$id_carrera=$ALUMNO->getIdCarreraPeriodo();
							$yearIngresoCarrera=$ALUMNO->getYearIngresoCarreraPeriodo();
							
							
							if(DEBUG){ echo"yearIngresoCarrera: $yearIngresoCarrera id_carrera: $id_carrera<br>";}
							
							$A_nombre=$D_1["nombre"];
							$A_apellido_P=$D_1["apellido_P"];
							$A_apellido_M=$D_1["apellido_M"];
			
							$A_ciudad=$D_1["ciudad"];
							$A_direccion=$D_1["direccion"];
							$A_fecha_nac=$D_1["fnac"];
							$A_liceo_nem=$D_1["liceo_nem"];
							
							
							
							$A_nivel=$ALUMNO->getNivelAlumnoPeriodo();
							$A_nivel_academico=$ALUMNO->getNivelAcademicoActual();
							$A_sede=$D_1["sede"];
					
							$A_jornada=$ALUMNO->getJornadaPeriodo();
							
							$A_fono=$D_1["fono"];
							$A_email=$D_1["email"];
							$A_emailInstitucional=$ALUMNO->getEmailInstitucional();
							$A_sexo=$D_1["sexo"];
							$A_pais_origen=$D_1["pais_origen"];
							$A_liceo_pais=$D_1["liceo_pais"];
							
							$condicion_alumno_este_year=$ALUMNO->getSituacionAlumnoPeriodo();
							
							$C_id=$ALUMNO->getidContratoPeriodo();
							
							$arrayDAtosContrato=DATOS_CONTRATO_ESPECIFICO($C_id);
							
							$arrayBeneficiosEstudiantiles=BENEFICIOS_ESTUDIANTILES_ASIGNADOS($C_id);
							
							
							if(DEBUG){ echo"<br><strong>id_alumno: $A_id Nombre: $A_nombre $A_apellido_P $A_apellido_M <br>Carrera: $id_carrera  yearIngresoCarrera: $yearIngresoCarrera <br>Sede: $A_sede <br>Situacion: $condicion_alumno_este_year (nivel: $A_nivel)</strong><br>";}
							

							
							$deuda_actual=DEUDA_ACTUAL($A_id, $fecha_actual);
							$deuda_total_actual=DEUDA_ACTUAL($A_id);
							////////
							//verifico si tienen matricula vigente
							
							
							$tiene_matricula_vigenteX=$ALUMNO->VERIFICAR_MATRICULA($id_carrera, $yearIngresoCarrera, true,false, $semestre_consulta, false, $year_consulta);
							//fecha Matricula
							

							if($tiene_matricula_vigenteX){$matricula_vigenteX="si";}
							else{$matricula_vigenteX="no";}
							
							
							$array_ramos_inscritos_periodo_1=RAMOS_INSCRITOS_TOMA_RAMO($A_id, $id_carrera, $yearIngresoCarrera, YEAR_CONSULTA, "1");
							$numero_ramos_inscritos_periodo_aprobados_1=count($array_ramos_inscritos_periodo_1["aprobado"]);
							$numero_ramos_inscritos_periodo_reprobados_1=count($array_ramos_inscritos_periodo_1["reprobado"]);
							$numero_ramos_inscritos_periodo_total_1=($numero_ramos_inscritos_periodo_aprobados_1+$numero_ramos_inscritos_periodo_reprobados_1);
							
							$array_ramos_inscritos_periodo_2=RAMOS_INSCRITOS_TOMA_RAMO($A_id, $id_carrera, $yearIngresoCarrera, YEAR_CONSULTA, "2");
							$numero_ramos_inscritos_periodo_aprobados_2=count($array_ramos_inscritos_periodo_2["aprobado"]);
							$numero_ramos_inscritos_periodo_reprobados_2=count($array_ramos_inscritos_periodo_2["reprobado"]);
							$numero_ramos_inscritos_periodo_total_2=($numero_ramos_inscritos_periodo_aprobados_2+$numero_ramos_inscritos_periodo_reprobados_2);
							
							$numero_ramos_inscritos_periodo_aprobados=($numero_ramos_inscritos_periodo_aprobados_1+$numero_ramos_inscritos_periodo_aprobados_2);
							$numero_ramos_inscritos_periodo_reprobados=($numero_ramos_inscritos_periodo_reprobados_1+$numero_ramos_inscritos_periodo_reprobados_2);
							
							$numero_ramos_inscritos_periodo_total=($numero_ramos_inscritos_periodo_total_1+$numero_ramos_inscritos_periodo_total_2);
							
							$ARRAY_ANUAL=array();
							//-----------------------------------------------------------------
							if(DEBUG){echo "calculo notas 1 semestre ".YEAR_CONSULTA."<br>";} 
							$acumula_promedio_1=0;
							$cuenta_promedio_1=0;
							foreach($array_ramos_inscritos_periodo_1 as $x_condicion => $x_array_cod_asignatura)
							{
								foreach($x_array_cod_asignatura as $x_cod => $aux_nota)
								{
									if(DEBUG){echo "$x_cod -> $aux_nota<br>";} 
									$acumula_promedio_1+=$aux_nota;
									$cuenta_promedio_1++;
									
									$ARRAY_ANUAL[1][$x_condicion][$x_cod]=$aux_nota;
								}
							}
							if($cuenta_promedio_1>0){$promedio_notas_1=($acumula_promedio_1/$cuenta_promedio_1);}
							else{ $promedio_notas_1=0;}
							if(DEBUG){echo "Promedio: $promedio_notas_1<br>";} 
							//-------------------------------------------------------------------------------
							if(DEBUG){echo "calculo notas 2 semestre ".YEAR_CONSULTA."<br>";} 
							$acumula_promedio_2=0;
							$cuenta_promedio_2=0;
							foreach($array_ramos_inscritos_periodo_2 as $x_condicion => $x_array_cod_asignatura)
							{
								foreach($x_array_cod_asignatura as $x_cod => $aux_nota)
								{
									if(DEBUG){echo "$x_cod -> $aux_nota<br>";} 
									$acumula_promedio_2+=$aux_nota;
									$cuenta_promedio_2++;
									$ARRAY_ANUAL[2][$x_condicion][$x_cod]=$aux_nota;
								}
							}
							if($cuenta_promedio_2>0){$promedio_notas_2=($acumula_promedio_2/$cuenta_promedio_2);}
							else{ $promedio_notas_2=0;}
							if(DEBUG){echo "Promedio: $promedio_notas_2<br>";} 
							
							if(DEBUG){echo"<br>";var_dump($ARRAY_ANUAL); echo"<br>";}
							
							//----------------------------------------------------------------//
							if(DEBUG){ echo"<br><strong>Promedio Anual</strong><br>";}
							$acumula_promedio=0;
							$cuenta_promedio=0;
							
							foreach($ARRAY_ANUAL as $aux_semestre => $array_ramos_inscritos_periodo)
							{
								foreach($array_ramos_inscritos_periodo as $x_condicion => $x_array_cod_asignatura)
								{
									foreach($x_array_cod_asignatura as $x_cod => $aux_nota)
									{
										if(DEBUG){ echo"->codigo nota $x_cod ->nota: $aux_nota<br>";}
										$acumula_promedio+=$aux_nota;
										$cuenta_promedio++;
									}
								}
							}
							if($cuenta_promedio>0){$promedio_notas=($acumula_promedio/$cuenta_promedio);}
							else{ $promedio_notas=0;}
							
							
							$promedio_notas=(number_format($promedio_notas,1)*10);
							$promedio_notas_1=(number_format($promedio_notas_1,1)*10);
							$promedio_notas_2=(number_format($promedio_notas_2,1)*10);
							if(DEBUG){ echo"Acumula promedio: $acumula_promedio - cuenta promedio: $cuenta_promedio: PROMEDIO: $promedio_notas<br>";}

							///Resumen de tomas de ramos historicos
							$array_ramos_inscritos_historico=RAMOS_INSCRITOS_TOMA_RAMO($A_id, $id_carrera,$yearIngresoCarrera,YEAR_CONSULTA,"","",'<=');
							$numero_ramos_inscritos_historico_aprobados=count($array_ramos_inscritos_historico["aprobado"]);
							$numero_ramos_inscritos_historico_reprobados=count($array_ramos_inscritos_historico["reprobado"]);
							$numero_ramos_inscritos_historico_total=($numero_ramos_inscritos_historico_aprobados+$numero_ramos_inscritos_historico_reprobados);
							//$escritura_notas=ESCRIBE_NOTAS($A_id, $A_id_carrera);
							/////
							list($TM_semestre, $TM_year)=PERIODO_TOMA_RAMO($A_id, $id_carrera,$yearIngresoCarrera);
							
							
							//EGRESO
							list($alumno_es_egresado, $egreso_semestre, $egreso_year)= ES_EGRESADO_V2($A_id, $id_carrera,$yearIngresoCarrera);
							if(!$alumno_es_egresado){$egreso_semestre=''; $egreso_year='';}
							
							//----------------------------------------------------------
							//BNM=1 BET=2
							$semestres_con_becaV2=SEMESTRES_CON_BECA_V2($A_id, 1) + SEMESTRES_CON_BECA_V2($A_id, 2);
							
							
							
							//--------------------------------------------------------------
							
							$ya_se_imprimio=true;
							$tabla.='<tr>
										<td>'.$RUT[0].'</td>
										<td>'.$RUT[1].'</td>
										<td>'.$matricula_vigenteX.'</td>
										<td>'.utf8_decode($A_nombre).'</td>
										<td>'.utf8_decode($A_apellido_P).'</td>
										<td>'.utf8_decode($A_apellido_M).'</td>
										<td>'.$A_fecha_nac.'</td>
										<td>'.$A_pais_origen.'</td>
										<td>'.$A_liceo_pais.'</td>
										<td>'.$A_sexo.'</td>
										<td>'.$A_fono.'</td>
										<td>'.$A_email.'</td>
										<td>'.$A_emailInstitucional.'</td>
										<td>'.$A_ciudad.'</td>
										<td>'.utf8_decode($A_direccion).'</td>
										<td>'.($A_liceo_nem*10).'</td>
										<td>'.$id_carrera.'</td>
										<td>'.utf8_decode(NOMBRE_CARRERA($id_carrera)).'</td>
										<td>'.CODIGO_CARRERA_SIES($A_sede, $A_jornada, NOMBRE_CARRERA($id_carrera), $id_carrera).'</td>
										<td>'.$A_jornada.'</td>
										<td>'.$A_nivel.'</td>
										<td bgcolor="#CCCC33">'.$A_nivel_academico.'</td>
										<td>'.$yearIngresoCarrera.'</td>
										<td>'.$A_sede.'</td>
										<td>'.$condicion_alumno_este_year.'</td>
										<td>'.$egreso_semestre.'</td>
										<td>'.$egreso_year.'</td>
										<td>-</td>
										<td>'.strtolower($arrayDAtosContrato["condicion"]).'</td>
										<td>'.$arrayDAtosContrato["vigencia"].'</td>
										<td>'.$arrayDAtosContrato["nivel_alumno"].'</td>
										<td>'.$arrayDAtosContrato["semestre"].'</td>
										<td>'.$arrayDAtosContrato["year"].'</td>
										<td>'.$arrayDAtosContrato["fecha_generacion"].'</td>
										<td>'.$arrayDAtosContrato["matricula_a_pagar"].'</td>
										<td>'.$arrayDAtosContrato["matricula_forma_pago"].'</td>';
										
							  $tabla.='<td>'.$semestres_con_becaV2.'</td>
										<td>'.$arrayDAtosContrato["BNM"].'</td>
										<td>'.$arrayDAtosContrato["aporte_BNM"].'</td>
										<td>'.$arrayDAtosContrato["BET"].'</td>
										<td>'.$arrayDAtosContrato["aporte_BET"].'</td>
										<td>'.$arrayDAtosContrato["cantidad_desc"].'</td>
										<td>'.$arrayDAtosContrato["porcentaje_desc"].'</td>';
										
										foreach($ARRAY_BENEFICIOS_ESTUDIANTILES as $AuxId =>$auxArray){
											
											$tabla.="<td bgcolor=\"#66FFCC\">".$arrayBeneficiosEstudiantiles[$AuxId]["valorAsignado"]."</td>";	
										}
										
							   $tabla.='<td>'.$arrayDAtosContrato["totalBeneficiosEstudiantiles"].'</td>
							   			<td>'.$arrayDAtosContrato["linea_credito"].'</td>
										<td>'.$arrayDAtosContrato["arancel"].'</td>
										<td>'.$arrayDAtosContrato["excedentes"].'</td>
										<td bgcolor="#FF55AA">'.$deuda_actual.'</td>
										<td bgcolor="#AA66bb">'.$deuda_total_actual.'</td>
										
										<td bgcolor="#FF55AA">'.$numero_ramos_inscritos_periodo_total_1.'</td>
										<td bgcolor="#FF55AA">'.$numero_ramos_inscritos_periodo_aprobados_1.'</td>
										<td bgcolor="#FF55AA">'.$numero_ramos_inscritos_periodo_reprobados_1.'</td>
										
										<td bgcolor="#FE9A2E">'.$numero_ramos_inscritos_periodo_total_2.'</td>
										<td bgcolor="#FE9A2E">'.$numero_ramos_inscritos_periodo_aprobados_2.'</td>
										<td bgcolor="#FE9A2E">'.$numero_ramos_inscritos_periodo_reprobados_2.'</td>
										
										<td bgcolor="#F5BCA9">'.$numero_ramos_inscritos_periodo_total.'</td>
										<td bgcolor="#F79F81">'.$numero_ramos_inscritos_periodo_aprobados.'</td>
										<td bgcolor="#FAAC58">'.$TM_semestre.'  '.$TM_year.'</td>
										<td bgcolor="#FE9A2E">'.$numero_ramos_inscritos_historico_total.'</td>
										<td bgcolor="#FACC2E">'.$numero_ramos_inscritos_historico_aprobados.'</td>
										<td bgcolor="#66CC66">'.$promedio_notas.'</td>
										
										<td bgcolor="#66CC66">'.($promedio_notas_1*10).'</td>
										<td bgcolor="#66CC66">'.($promedio_notas_2*10).'</td>
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

function BUSCA_NOTAS($id_alumno, $nivel_actual, $id_carrera)
{
	require("../../../funciones/conexion_v2.php");
	$cons_N="SELECT * FROM notas WHERE id_alumno='$id_alumno' AND ramo<>'' ORDER by nivel, cod";
	
	$sql_N=$conexion_mysqli->query($cons_N);
	$num_notas=$sql_N->num_rows;
	$aux=0;
	
	$num_total_ramos_tomados=0;
	$num_total_ramos_aprobados=0;
	
	$num_total_ramos_tomados_year_consulta=0;
	$num_total_ramos_aprobados_year_consulta=0;
	if(DEBUG){ echo"NOTAS: $cons_N<br>N. notas encontradas:$num_notas<br>";}
	if($num_notas>0)
	{
		while($N=$sql_N->fetch_assoc())
		{
			
			$aux++;
			
			$aux_codigo=$N["cod"];
			$aux_nivel=$N["nivel"];
			$aux_nota=$N["nota"];
			$aux_year=$N["ano"];
			
			if($aux_year==YEAR_CONSULTA)
			{
				if($aux_nota>0)
				{ $num_total_ramos_tomados_year_consulta++;}
				if($aux_nota>=4){ $num_total_ramos_aprobados_year_consulta++;}
			}
			
			if($aux_nota>0)
			{ $num_total_ramos_tomados++;}
			if($aux_nota>=4){ $num_total_ramos_aprobados++;}
			
		}
	}
	$sql_N->free();
	$array_respuesta=array($num_total_ramos_tomados, $num_total_ramos_aprobados, $num_total_ramos_tomados_year_consulta, $num_total_ramos_aprobados_year_consulta);
	$conexion_mysqli->close();
	return($array_respuesta);
}
function ESCRIBE_NOTAS($id_alumno, $id_carrera)
{
	require("../../../funciones/conexion_v2.php");
	$cons="SELECT nota FROM notas WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' and ramo<>'' ORDER by cod";
	$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_notas=$sql->num_rows;
	$respuesta="";
	if($num_notas>0)
	{
		while($N=$sql->fetch_assoc())
		{
			$aux_nota=$N["nota"];
			if(empty($aux_nota)){ $aux_nota="---";}
			$respuesta.='<td>&nbsp;'.$aux_nota.'</td>';
		}
	}
	else
	{
		$respuesta="";
	}
	$sql->free();
	$conexion_mysqli->close();
	return($respuesta);
}
?>