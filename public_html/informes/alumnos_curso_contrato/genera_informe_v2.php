<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_alumnos_X_curso_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	set_time_limit(300);
//-----------------------------------------//	
//var_dump($_POST);
//////////////////////////
$mostrar_cuotas=true;
$mostrar_cuotas_html=false;
$fechaCortePagoReal='2019-06-30';


$sede=$_POST["fsede"];
$id_carrera=$_POST["id_carrera"];
$year_ingreso_consulta=$_POST["year_ingreso"];
$jornada=$_POST["jornada"];
$situacion=$_POST["estado"];
$grupo=$_POST["grupo"];
if(isset($_POST["nivel"])){$nivel=$_POST["nivel"];}
else{ $nivel="";}

$semestre_vigencia=$_POST["semestre_vigencia_contrato"];
$year_vigencia=$_POST["year_vigencia_contrato"];

$verificar_contrato=true;
$no_mostrar_retirados=false;
$tipo_documento=$_POST["tipo_documento"];
$formato_salida=$_POST["formato_salida"];
//----------------------------------------------------------------------//
$array_cuenta_alumnos=array();
if(DEBUG){ var_export($_POST);}

if($sede=="")
{$sede="Talca";}
$condicion=" contratos2.sede='$sede' AND contratos2.condicion<>'inactivo'";

if($id_carrera>0){ $condicion.=" AND contratos2.id_carrera='$id_carrera'";}
if($year_ingreso_consulta!="0"){$condicion.=" AND contratos2.yearIngresoCarrera='$year_ingreso_consulta'"; $year_ingreso_label=$year_ingreso_consulta;}else{$year_ingreso_label="Todos";}
if($jornada!="0"){$condicion.=" AND contratos2.jornada='$jornada'"; $jornada_label=$jornada;}else{ $jornada_label="Todas";}
if($situacion!="A"){$condicion.="";}
if($grupo!="Todos"){$condicion.=" AND alumno.grupo='$grupo'";}
$condicion.=" AND contratos2.ano='$year_vigencia'";


$inicio_ciclio=true;
$niveles="";
if(is_array($nivel))
{
	foreach($nivel as $nn=>$valornn)
	{
		if($inicio_ciclio)
		{ 
			$niveles.="'$valornn'";
			$inicio_ciclio=false;
		}
		else
		{ $niveles.=", '$valornn'";}
		//echo"--> $niveles<br>";
	}
}
else{ $niveles="'sin nivel'";}

///////////////////////////
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");
require("../../../funciones/class_ALUMNO.php");

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

///////////////////////////////////
						
							$borde=1;
							$letra_1=10;
							$letra_2=12;
							$autor="ACX";
							$titulo="Listado Alumnos $semestre_vigencia Semestre - $year_vigencia";
							$descripcion=utf8_decode(NOMBRE_CARRERA($id_carrera))." - ".utf8_decode("Año")." $year_ingreso_label - Jornada $jornada_label";
							$descripcion_more="Nivel ".str_replace("'","",$niveles)." - Grupo $grupo";
							$zoom=75;
							$msj_sin_reg="No hay resultados en esta Busqueda";
							
							switch($formato_salida)
							{
								case"pdf":
									if(DEBUG){ echo"INICIO GENERACION PDF";}
									require("../../libreria_publica/fpdf/fpdf.php");
									$pdf=new FPDF('P','mm','Letter');
									$pdf->AddPage();
									$pdf->SetAuthor($autor);
									$pdf->SetTitle($titulo);
									$pdf->SetDisplayMode($zoom);
									//titulo
									$pdf->SetFont('Arial','B',$letra_2);
									$pdf->Cell(195,6,$titulo,0,1,'C');	
									$pdf->Cell(195,6,$descripcion,0,1,'C');	
									$pdf->Cell(195,6,$descripcion_more,0,1,'C');	
									$pdf->Cell(195,6,$sede,0,1,'C');	
									$pdf->Ln();
									break;
								case"xls":
										echo'<table border="1">';
										if(DEBUG){ echo"INICIO GENERACION XLS";}
										else
										{
											header('Content-type: application/vnd.ms-excel');
											header("Content-Disposition: attachment; filename=lista_alumnos.xls");
											header("Pragma: no-cache");
											header("Expires: 0");
										}
										break;
								case"html":
										$titulo="Listado Alumnos $semestre_vigencia Semestre - $year_vigencia";
										$descripcion=NOMBRE_CARRERA($id_carrera)." - Year: $year_ingreso_label - Jornada $jornada_label";
										$descripcion_more="Nivel ".str_replace("'","",$niveles)." - Grupo $grupo";
										echo'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
											<html xmlns="http://www.w3.org/1999/xhtml">
											<head>
											<title>Administrador - informe</title>
											<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
											<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
											<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
											<style type="text/css">
											<!--
											#Layer1 {
												position:absolute;
												width:90%;
												height:186px;
												z-index:1;
												left: 5%;
												top: 100px;
											}
											</style>
											</head>
											<body>
											<h1 id="banner">Administrador - Informe de Cursos V3.1</h1>
											<div id="Layer1">
											  <table width="100%" border="1" align="center">
											  <caption>'.$titulo.'<br>'.$descripcion.'<br>'.$descripcion_more.'</caption>
											  <thead>
												<tr>
												  <th colspan="35"><span class="Estilo1">Listado Alumnos</span></th>
												</tr>
												</thead>
												<tbody>';
									break;		
							}
 							/////Registro ingreso///
								 include("../../../funciones/VX.php");
								 $evento="Ve Informe(alumnosXcurso V3)->".$id_carrera."-".$year_ingreso_consulta."-".$sede."-".$jornada."-".$situacion;
								 REGISTRA_EVENTO($evento);
								 switch($formato_salida)
								 {
									 case"pdf":
										switch($tipo_documento)
										{
											case"normal":
												////////cabecera
												$pdf->SetFont('Arial','B',$letra_1);
												$pdf->Cell(6,6,"N.",$borde,0,'C');	
												$pdf->Cell(22,6,"Rut",$borde,0,'L');	
												$pdf->Cell(64,6,"Nombre",$borde,0,'L');	
												$pdf->Cell(63,6,"Apellido",$borde,0,'L');
												$pdf->Cell(10,6,"Nivel",$borde,0,'C');
												$pdf->Cell(15,6,"Estado",$borde,0,'C');
												$pdf->Cell(15,6,"Ingreso",$borde,1,'C');
												$pdf->SetFont('Arial','',$letra_1);
												break;
											case"asistencia":
												////////cabecera
												$pdf->SetFont('Arial','B',$letra_1);
												$pdf->Cell(6,6,"N.",$borde,0,'C');	
												$pdf->Cell(22,6,"Rut",$borde,0,'L');	
												$pdf->Cell(64,6,"Nombre",$borde,0,'L');	
												$pdf->Cell(63,6,"Apellido",$borde,0,'L');
												$pdf->Cell(40,6,"Firma",$borde,1,'C');
												$pdf->SetFont('Arial','',$letra_1);
												break;	
											default:
													////////cabecera
												$pdf->SetFont('Arial','B',$letra_1);
												$pdf->Cell(6,6,"N.",$borde,0,'C');	
												$pdf->Cell(22,6,"Rut",$borde,0,'L');	
												$pdf->Cell(64,6,"Nombre",$borde,0,'L');	
												$pdf->Cell(63,6,"Apellido",$borde,0,'L');
												$pdf->Cell(10,6,"Nivel",$borde,0,'C');
												$pdf->Cell(15,6,"Estado",$borde,0,'C');
												$pdf->Cell(15,6,"Ingreso",$borde,1,'C');
												$pdf->SetFont('Arial','',$letra_1);
												break;	
										}	
										break;
									case"xls":
										switch($tipo_documento)
										{
											case"normal":
												////////cabecera
												echo'<tr>
														<td>N.</td>
														<td>Carrera</td>
														<td>Jornada</td>
														<td>Rut</td>
														<td>Nombre</td>
														<td>Apellido</td>
														<td>Nivel</td>
														<td>Estado '.utf8_decode("año").'('.$year_vigencia.')</td>
														
														<td>Ingreso</td>
													</tr>';
												break;
											case"asistencia":
												////////cabecera
												echo'<tr>
														<td>N.</td>
														<td>Carrera</td>
														<td>Jornada</td>
														<td>Rut</td>
														<td>Nombre</td>
														<td>Apellido</td>
														<td>Firma</td>
													</tr>';
												break;	
											case"full":
												////////cabecera
												echo'<tr>
														<td>N.</td>
														<td>id_contrato</td>
														<td>fecha Matricula</td>
														<td>Sexo</td>
														<td>Sede</td>
														<td>Carrera</td>
														<td>Nivel</td>
														<td>Ingreso</td>
														<td>Jornada</td>
														<td>Rut</td>
														<td>DV</td>
														<td>Nombre</td>
														<td>Apellido P</td>
														<td>Apellido P</td>
														<td>Fecha Nacimiento</td>
														<td>Direccion</td>
														<td>Ciudad</td>
														<td>Pais Origen</td>
														<td>Pais Estudios Ed. Media</td>
														<td>Fono</td>
														<td>Estado Civil</td>
														<td>E-mail</td>
														<td>E-mail Institucional</td>
														<td>Estado year('.$year_vigencia.')</td>
														<td>Matricula valor</td>
														<td>Matricula a pagar</td>
														<td>Matricula forma Pago</td>
														<td>saldo a Favor</td>';
														
												   echo'<td>Aporte BNM</td>
														<td>Aporte BET</td>
														<td>Cantidad desc</td>
														<td>% desc.</td>';
													foreach($ARRAY_BENEFICIOS_ESTUDIANTILES as $id =>$auxArray){
														echo"<td bgcolor=\"#66FFCC\">".$auxArray["nombre"]."</td>";	
													}	
														
												   echo'
												   		<td>totalBeneficiosEstudiantiles</td>
												   		<td>Linea Credito</td>
														<td>Vigencia</td>
														<td>Arancel</td>
														<td>Excedente</td>
														<td>Valor Enero '.$year_vigencia.'</td>
														<td>Deuda Actual Enero '.$year_vigencia.'</td>
														<td>Fecha ultimo pago Enero'.$year_vigencia.'</td>
														<td>Pago real al  '.$fechaCortePagoReal.'</td>
														
														<td>Valor Febrero '.$year_vigencia.'</td>
														<td>Deuda Actual Febrero '.$year_vigencia.'</td>
														<td>Fecha ultimo pago Febrero'.$year_vigencia.'</td>
														<td>Pago real al  '.$fechaCortePagoReal.'</td>
														
														<td>Valor Marzo '.$year_vigencia.'</td>
														<td>DeudaActual  Marzo '.$year_vigencia.'</td>
														<td>Fecha ultimo pago Marzo'.$year_vigencia.'</td>
														<td>Pago real al  '.$fechaCortePagoReal.'</td>
														
														<td>Valor Abril '.$year_vigencia.'</td>
														<td>Deuda Actual Abril '.$year_vigencia.'</td>
														<td>Fecha ultimo pago Abril'.$year_vigencia.'</td>
														<td>Pago real al  '.$fechaCortePagoReal.'</td>
														
														<td>Valor Mayo '.$year_vigencia.'</td>
														<td>Deuda Actual Mayo '.$year_vigencia.'</td>
														<td>Fecha ultimo pago Mayo'.$year_vigencia.'</td>
														<td>Pago real al  '.$fechaCortePagoReal.'</td>
														
														<td>Valor Junio '.$year_vigencia.'</td>
														<td>Deuda Actual Junio '.$year_vigencia.'</td>
														<td>Fecha ultimo pago Junio'.$year_vigencia.'</td>
														<td>Pago real al  '.$fechaCortePagoReal.'</td>
														
														<td>Valor Julio '.$year_vigencia.'</td>
														<td>Deuda Actual Julio '.$year_vigencia.'</td>
														<td>Fecha ultimo pago Julio'.$year_vigencia.'</td>
														<td>Pago real al  '.$fechaCortePagoReal.'</td>
														
														<td>Valor Agosto '.$year_vigencia.'</td>
														<td>Deuda Actual Agosto '.$year_vigencia.'</td>
														<td>Fecha ultimo pago Agosto'.$year_vigencia.'</td>
														<td>Pago real al  '.$fechaCortePagoReal.'</td>
														
														<td>Valor Septiembre '.$year_vigencia.'</td>
														<td>Deuda Actual Septiembre '.$year_vigencia.'</td>
														<td>Fecha ultimo pago Septiembre'.$year_vigencia.'</td>
														<td>Pago real al  '.$fechaCortePagoReal.'</td>
														
														<td>Valor Octubre '.$year_vigencia.'</td>
														<td>Deuda Actual Octubre '.$year_vigencia.'</td>
														<td>Fecha ultimo pago Octubre'.$year_vigencia.'</td>
														<td>Pago real al  '.$fechaCortePagoReal.'</td>
														
														<td>Valor Noviembre '.$year_vigencia.'</td>
														<td>Deuda Actual Noviembre '.$year_vigencia.'</td>
														<td>Fecha ultimo pago Noviembre'.$year_vigencia.'</td>
														<td>Pago real al  '.$fechaCortePagoReal.'</td>
														
														<td>Valor Diciembre '.$year_vigencia.'</td>
														<td>Deuda Actual Diciembre '.$year_vigencia.'</td>
														<td>Fecha ultimo pago Diciembre'.$year_vigencia.'</td>
														<td>Pago real al  '.$fechaCortePagoReal.'</td>
														
														<td>Valor Enero '.($year_vigencia+1).'</td>
														<td>Deuda Actual Enero '.($year_vigencia+1).'</td>
														<td>Fecha ultimo pago Enero'.$year_vigencia.'</td>
														<td>Pago real al  '.$fechaCortePagoReal.'</td>
														
														<td>Valor Febrero '.($year_vigencia+1).'</td>
														<td>Deuda Actual Febrero '.($year_vigencia+1).'</td>
														<td>Fecha ultimo pago Febrero'.$year_vigencia.'</td>
														<td>Pago real al  '.$fechaCortePagoReal.'</td>
													</tr>';
												break;		
										}	
										break;
									case"html":
										switch($tipo_documento)
										{
											case"normal":
												////////cabecera
												echo'<tr>
														<td>N.</td>
														<td>Carrera</td>
														<td>Jornada</td>
														<td>Rut</td>
														<td>Nombre</td>
														<td>Apellido</td>
														<td>Nivel Actual</td>
														<td>Estado Year('.$year_vigencia.')</td>
														
														<td>Ingreso</td>
													</tr>';
												break;
											case"asistencia":
												////////cabecera
												echo'<tr>
														<td>N.</td>
														<td>Carrera</td>
														<td>Jornada</td>
														<td>Rut</td>
														<td>Nombre</td>
														<td>Apellido</td>
														<td>Firma</td>
													</tr>';
												break;	
											case"full":
												////////cabecera
												echo'<tr>
														<td>N.</td>
														<td>id_contrato</td>
														<td>Sede</td>
														<td>Sexo</td>
														<td>Carrera</td>
														<td>Nivel Actual</td>
														<td>Ingreso</td>
														<td>Jornada</td>
														<td>Rut</td>
														<td>DV</td>
														<td>Nombre</td>
														<td>Apellido P</td>
														<td>Apellido P</td>
														<td>Fecha Nacimiento</td>
														<td>Direccion</td>
														<td>Ciudad</td>
														<td>Fono</td>
														<td>Estado Civil</td>
														<td>E-mail</td>
														<td>E-mail Institucional</td>
														<td>Estado Año('.$year_vigencia.')</td>';
														
														if($mostrar_cuotas_html){echo'<td colspan="28">Cuotas (Valor)'.$year_vigencia.'</td>';}
													echo'</tr>';
												break;		
										}	
										break;
								 }
								$aux=0;	 
	
	if(($tipo_documento=="full")and($formato_salida=="xls"))
	{ $ordenar="id_alumno";}
	else{ $ordenar="alumno.apellido_P, alumno.apellido_M";}
	
								
	$cons_main_1="SELECT DISTINCT(id_alumno), contratos2.id_carrera, contratos2.yearIngresoCarrera FROM contratos2 INNER JOIN alumno ON contratos2.id_alumno = alumno.id WHERE $condicion ORDER by $ordenar";
		
		$sql_main_1=$conexion_mysqli->query($cons_main_1)or die("MAIN 1".$conexion_mysqli->error);
		$num_reg_M=$sql_main_1->num_rows;
		if(DEBUG){ echo"<br><br><strong>$cons_main_1<br>NUM.$num_reg_M</strong><br>";}
		if($num_reg_M>0)
		{
			$x=0;
			$aux=0;
			while($DID=$sql_main_1->fetch_row())
			{
				$x++;
				$cumple_condicion_para_ser_mostrado=false;
				$id_alumno=$DID[0];
				$id_carrera_alumno=$DID[1];
				$yearIngresoCarrera_alumno=$DID[2];
				if(DEBUG){ echo"[$x] id_alumno: $id_alumno id_carrera_alumno: $id_carrera_alumno yearIngresoCarrera: $yearIngresoCarrera_alumno<br>";}
				//list($hay_contrato, $array_datos_contrato)=CONDICION_DE_ALUMNO_PERIODO($id_alumno, $id_carrera_alumno, $yearIngresoCarrera_alumno, $semestre_vigencia,$year_vigencia);
				//-------------------------------------------------------------------------------------------------------------------------------------//
				
					$ALUMNO=new ALUMNO($id_alumno);
					$ALUMNO->SetDebug(DEBUG);
					$ALUMNO->IR_A_PERIODO($semestre_vigencia,$year_vigencia);
					

						$A_rut=$ALUMNO->getRut();
						$A_nombre=$ALUMNO->getNombre();
						$A_apellido_P=$ALUMNO->getApellido_P();
						$A_apellido_M=$ALUMNO->getApellido_M();
						$A_sexo=$ALUMNO->getSexo();
						
						
						$A_fecha_nacimiento=$ALUMNO->getFechaNacimiento();
						$A_ciudad=$ALUMNO->getCiudad();
						$A_telefono=$ALUMNO->getFono();
						$A_direccion=$ALUMNO->getDireccion();
						$A_email=$ALUMNO->getEmail();
						$A_emaiInstitucional=$ALUMNO->getEmailInstitucional();
						$A_estadoCivil=$ALUMNO->getEstadoCivil();
						
						$A_paisOrigen=$ALUMNO->getPaisOrigen();
						$A_paisEstudiosMedios=$ALUMNO->getPaisEstudiosMedios();
						
					
					//------------------------------------//
					
					$C_nivel_alumno_contrato=$ALUMNO->getNivelAlumnoPeriodo();
					$C_jornada_contrato=$ALUMNO->getJornadaPeriodo();
					$C_sede=$ALUMNO->getSedeAlumnoPeriodo();
					$C_idContratoPeriodo=$ALUMNO->getidContratoPeriodo();
					
					
					$ARRAY_CONTRATO=DATOS_CONTRATO_ESPECIFICO($C_idContratoPeriodo);
					$C_fechaMatricula=$ARRAY_CONTRATO["fecha_generacion"];
					$C_presenteEnPeriodo=$ALUMNO->getPresenteEnPeriodo();
					
					$arrayBeneficiosEstudiantiles=BENEFICIOS_ESTUDIANTILES_ASIGNADOS($C_idContratoPeriodo);
					
					if(empty($C_jornada_contrato)){if(DEBUG){echo"Jornada de cotrato vacia....<br>";}}
					if(DEBUG){ echo"Jornada Periodo: $C_jornada_contrato<br> Nivel de Alumno segun contrato: $C_nivel_alumno_contrato<br>id_contrato seleccionado: $C_idContratoPeriodo<br>";}
					//-------------------------------//
					//jornada
					if($jornada=='0')
					{ $cumple_condicion_jornada=true; if(DEBUG){ echo"--->cumple condicion de Jornada (todas)(buscada[$jornada] encontrada[$C_jornada_contrato])<br>";}}
					else
					{
						if($jornada==$C_jornada_contrato)
						{$cumple_condicion_jornada=true; if(DEBUG){ echo"--->cumple condicion de Jornada (buscada[$jornada] encontrada[$C_jornada_contrato])<br>";}}
						else
						{$cumple_condicion_jornada=false; if(DEBUG){ echo"--->NO cumple condicion de Jornada (buscada[$jornada] encontrada[$C_jornada_contrato])<br>";}}
					}
					//-----------------------------------//
					//nivel
					if(in_array($C_nivel_alumno_contrato, $nivel))
					{ $cumple_condicion_nivel=true; if(DEBUG){ echo"--->cumple condicion de Nivel (encontrado [$C_nivel_alumno_contrato])<br>";}}
					else
					{ $cumple_condicion_nivel=false; if(DEBUG){ echo"--->NO cumple condicion de Nivel<br>";}}
					//-------------------------------------------//
					//condicion del alumno en el semestre-a�o
					
					$condicion_alumno_este_year=$ALUMNO->getSituacionAlumnoPeriodo();
					
						
					//condicion de situacion
					if($situacion!="A"){ if($condicion_alumno_este_year==$situacion){$cumple_condicion_situacion=true;}else{$cumple_condicion_situacion=false;}}
					else{$cumple_condicion_situacion=true;}
						
					if($cumple_condicion_jornada and $cumple_condicion_nivel and $cumple_condicion_situacion){$cumple_condicion_para_ser_mostrado=true;}
					
				
//---------------------------------------------------------------------------------------------------------------------------------------------------------//					
				if(($cumple_condicion_para_ser_mostrado)and($C_presenteEnPeriodo))
				{
					$aux++;
					if(isset($array_cuenta_alumnos[$condicion_alumno_este_year])){$array_cuenta_alumnos[$condicion_alumno_este_year]+=1;}
					else{ $array_cuenta_alumnos[$condicion_alumno_este_year]=1;}
					
					if(DEBUG){ echo"Mostrar alumno....<br><br>Formato salida: $formato_salida<br>";}
					switch($formato_salida)
					{
						case"pdf":
							switch($tipo_documento)
							{
								case"normal":
									$pdf->Cell(6,6,$aux,$borde,0,'C');	
									$pdf->Cell(22,6,$A_rut,$borde,0,'L');	
									$pdf->Cell(64,6,utf8_decode(ucwords(strtolower($A_nombre))),$borde,0,'L');	
									$pdf->Cell(63,6,utf8_decode(ucwords(strtolower($A_apellido_P." ".$A_apellido_M))),$borde,0,'L');
									$pdf->Cell(10,6,$C_nivel_alumno_contrato,$borde,0,'C');
									$pdf->Cell(15,6,$condicion_alumno_este_year,$borde,0,'C');
									$pdf->Cell(15,6,$yearIngresoCarrera_alumno,$borde,1,'C');
									break;
								case"asistencia":
									$pdf->Cell(6,6,$aux,$borde,0,'C');	
									$pdf->Cell(22,6,$A_rut,$borde,0,'L');	
									$pdf->Cell(64,6,utf8_decode(ucwords(strtolower($A_nombre))),$borde,0,'L');	
									$pdf->Cell(63,6,utf8_decode(ucwords(strtolower($A_apellido_P." ".$A_apellido_M))),$borde,0,'L');
									$pdf->Cell(40,6,"",$borde,1,'C');
									break;	
							}
							break;
						case"xls":
							switch($tipo_documento)
							{
								case"normal":
									$aux_rut=explode("-",$A_rut);
									echo'<tr>
											<td>'.$aux.'</td>
											<td>'.utf8_decode(NOMBRE_CARRERA($id_carrera_alumno)).'</td>
											<td>'.$C_jornada_contrato.'</td>
											<td>'.$A_rut.'</td>
											<td>'.utf8_decode(ucwords(strtolower($A_nombre))).'</td>
											<td>'.utf8_decode(ucwords(strtolower($A_apellido_P." ".$A_apellido_M))).'</td>
											<td>'.$C_nivel_alumno_contrato.'</td>
											<td>'.$condicion_alumno_este_year.'</td>
											
											<td>'.$yearIngresoCarrera_alumno.'</td>
										 </tr>';
									break;
								case"asistencia":
									echo'<tr>
											<td>'.$aux.'</td>
											<td>'.utf8_decode(NOMBRE_CARRERA($id_carrera_alumno)).'</td>
											<td>'.$C_jornada_contrato.'</td>
											<td>'.$A_rut.'</td>
											<td>'.utf8_decode(ucwords(strtolower($A_nombre))).'</td>
											<td>'.utf8_decode(ucwords(strtolower($A_apellido_P." ".$A_apellido_M))).'</td>
											<td>&nbsp;</td>
										 </tr>';
									break;	
								case"full":
									$array_rut=explode("-",$A_rut);
									//$array_datos_contrato=DATOS_CONTRATO($id_alumno, $id_carrera_alumno, $semestre_vigencia, $year_vigencia);
									$array_datos_contrato=DATOS_CONTRATO_ESPECIFICO($C_idContratoPeriodo);
									echo'<tr>
											<td>'.$aux.'</td>
											<td>'.$C_idContratoPeriodo.'</td>
											<td>'.$C_fechaMatricula.'</td>
											<td>'.$A_sexo.'</td>
											<td>'.$C_sede.'</td>
											
											<td>'.utf8_decode(NOMBRE_CARRERA($id_carrera_alumno)).'</td>
											<td>'.$C_nivel_alumno_contrato.'</td>
											<td>'.$yearIngresoCarrera_alumno.'</td>
											<td>'.$C_jornada_contrato.'</td>
											<td>'.$array_rut[0].'</td>
											<td>'.$array_rut[1].'</td>
											<td>'.utf8_decode(ucwords(strtolower($A_nombre))).'</td>
											<td>'.utf8_decode(ucwords(strtolower($A_apellido_P))).'</td>
											<td>'.utf8_decode(ucwords(strtolower($A_apellido_M))).'</td>
											<td>'.$A_fecha_nacimiento.'</td>
											<td>'.utf8_decode($A_direccion).'</td>
											<td>'.$A_ciudad.'</td>
											<td>'.$A_paisOrigen.'</td>
											<td>'.$A_paisEstudiosMedios.'</td>
											<td>'.$A_telefono.'</td>
											<td>'.$A_estadoCivil.'</td>
											<td>'.$A_email.'</td>
											<td>'.$A_emaiInstitucional.'</td>
											<td>'.$condicion_alumno_este_year.'</td>
											
											<td>'.$array_datos_contrato["matricula_valor"].'</td>
											<td>'.$array_datos_contrato["matricula_a_pagar"].'</td>
											<td>'.$array_datos_contrato["matricula_forma_pago"].'</td>
											<td>'.$array_datos_contrato["saldo_a_favor"].'</td>';
											
									  echo'<td>'.$array_datos_contrato["aporte_BNM"].'</td>
											<td>'.$array_datos_contrato["aporte_BET"].'</td>
											<td>'.$array_datos_contrato["cantidad_desc"].'</td>
											<td>'.$array_datos_contrato["porcentaje_desc"].'</td>';
											
											foreach($ARRAY_BENEFICIOS_ESTUDIANTILES as $AuxId =>$auxArray){
											echo"<td bgcolor=\"#66FFCC\">".$arrayBeneficiosEstudiantiles[$AuxId]["valorAsignado"]."</td>";	
										}
										echo'<td>'.$array_datos_contrato["totalBeneficiosEstudiantiles"].'</td>
											<td>'.$array_datos_contrato["linea_credito"].'</td>
											<td>'.$array_datos_contrato["vigencia"].'</td>
											<td>'.$array_datos_contrato["arancel"].'</td>
											<td>'.$array_datos_contrato["excedentes"].'</td>';
											
										if($mostrar_cuotas)
										{
											$meses_proyectado=14;
											$year_proyectado=$year_vigencia;
											$mes=0;
											for($m=1;$m<=$meses_proyectado;$m++)
											{
												
												if($m>12){$mes=1; $year_proyectado++;}
												else{$mes++;}
												
												if($mes<10){$mes_label="0".$mes;}
												else{$mes_label=$mes;}
												
												//listo cuotas solo del año en cuestion
												$cons_C="SELECT id, valor, deudaXletra, fecha_ultimo_pago FROM letras WHERE idalumn='$id_alumno' AND MONTH(fechavenc)='$mes_label' AND YEAR(fechavenc)='$year_proyectado' AND ano='$year_vigencia' AND tipo='cuota' AND id_contrato='$C_idContratoPeriodo' order by fechavenc";
												if(DEBUG){ echo"----> $cons_C<br>";}
												$sqli_C=$conexion_mysqli->query($cons_C)or die($conexion_mysqli->error);
												$valor_cuota=0;
												$deuda_cuota=0;
												$pago_real=0;
												$fechaUltimopago="";
												while($C=$sqli_C->fetch_assoc()){
													$idCuota=$C["id"];
													$valor_cuota+=$C["valor"];
													$deuda_cuota+=$C["deudaXletra"];
													$fechaUltimopago=$C["fecha_ultimo_pago"];
													
													$cons_P="SELECT SUM(valor) FROM pagos WHERE id_alumno='$id_alumno' AND id_cuota='$idCuota' AND fechapago<='$fechaCortePagoReal'";
													$sqliP=$conexion_mysqli->query($cons_P)or die($conexion_mysqli->error);
													$PR=$sqliP->fetch_row();
													$pago_real+=$PR[0];
													if(empty($pago_real)){$pago_real=0;}
													$sqliP->free();
													
													
												}
												echo'<td bgcolor="#FFAAAA">'.$valor_cuota.'</td>';
												echo'<td bgcolor="#AAFFAA">'.$deuda_cuota.'</td>';
												echo'<td bgcolor="#AAFFAA">'.$fechaUltimopago.'</td>';
												echo'<td bgcolor="#FFFFAA">'.$pago_real.'</td>';
												
												
													$sqli_C->free();
												
											}
										}	
										echo'</tr>';
								break;		
							}
							break;
						case"html":
							$validador=md5("GDXT".date("d-m-Y"));
							$url_destino='../../buscador_alumno_BETA/enrutador.php?validador='.$validador.'&id_alumno='.$id_alumno;
							switch($tipo_documento)
							{
								case"normal":
									$aux_rut=explode("-",$A_rut);
									echo'<tr>
											<td>'.$aux.'</td>
											<td>'.NOMBRE_CARRERA($id_carrera_alumno).'</td>
											<td>'.$C_jornada_contrato.'</td>
											<td><a href="'.$url_destino.'" title="Revisar este Alumno" target="_blank">'.$A_rut.'</a></td>
											<td>'.ucwords(strtolower($A_nombre)).'</td>
											<td>'.ucwords(strtolower($A_apellido_P." ".$A_apellido_M)).'</td>
											<td>'.$C_nivel_alumno_contrato.'</td>
											<td>'.$condicion_alumno_este_year.'</td>
											
											<td>'.$yearIngresoCarrera_alumno.'</td>
										 </tr>';
									break;
								case"asistencia":
									echo'<tr>
											<td>'.$aux.'</td>
											<td>'.NOMBRE_CARRERA($id_carrera_alumno).'</td>
											<td>'.$C_jornada_contrato.'</td>
											<td>'.$A_rut.'</td>
											<td>'.ucwords(strtolower($A_nombre)).'</td>
											<td>'.ucwords(strtolower($A_apellido_P." ".$A_apellido_M)).'</td>
											<td>&nbsp;</td>
										 </tr>';
									break;	
								case"full":
									$array_rut=explode("-",$A_rut);
									echo'<tr>
											<td>'.$aux.'</td>
											<td>'.$C_idContratoPeriodo.'</td>
											<td>'.$A_sexo.'</td>
											<td>'.$C_sede.'</td>
											<td>'.NOMBRE_CARRERA($id_carrera_alumno).'</td>
											<td>'.$C_nivel_alumno_contrato.'</td>
											<td>'.$yearIngresoCarrera_alumno.'</td>
											<td>'.$C_jornada_contrato.'</td>
											<td>'.$array_rut[0].'</td>
											<td>'.$array_rut[1].'</td>
											<td>'.ucwords(strtolower($A_nombre)).'</td>
											<td>'.ucwords(strtolower($A_apellido_P)).'</td>
											<td>'.ucwords(strtolower($A_apellido_M)).'</td>
											<td>'.$A_fecha_nacimiento.'</td>
											<td>'.$A_direccion.'</td>
											<td>'.$A_ciudad.'</td>
											<td>'.$A_telefono.'</td>
											<td>'.$A_email.'</td>
											<td>'.$A_emaiInstitucional.'</td>
											<td>'.$condicion_alumno_este_year.'</td>';
											
										if($mostrar_cuotas_html)
										{
											$meses_proyectado=14;
											$year_proyectado=$year_vigencia;
											$mes=0;
											for($m=1;$m<=$meses_proyectado;$m++)
											{
												
												if($m>12){$mes=1; $year_proyectado++;}
												else{$mes++;}
												
												if($mes<10){$mes_label="0".$mes;}
												else{$mes_label=$mes;}
												
												//listo cuotas solo del año en cuestion
												$cons_C="SELECT SUM(valor) FROM letras WHERE idalumn='$id_alumno' AND MONTH(fechavenc)='$mes_label' AND YEAR(fechavenc)='$year_proyectado' AND ano='$year_vigencia' AND tipo='cuota' order by fechavenc";
												if(DEBUG){ echo"----> $cons_C<br>";}
												$sqli_C=$conexion_mysqli->query($cons_C)or die($conexion_mysqli->error);
												$C=$sqli_C->fetch_row();
												$valor_cuota=$C[0];
												echo'<td bgcolor="#FFAAAA">'.$valor_cuota.'</td>';
												$sqli_C->free();
												$cons_C="SELECT SUM(deudaXletra) FROM letras WHERE idalumn='$id_alumno' AND MONTH(fechavenc)='$mes_label' AND YEAR(fechavenc)='$year_proyectado' AND ano='$year_vigencia' AND tipo='cuota' AND id_contrato='$C_idContratoPeriodo' order by fechavenc";
												if(DEBUG){ echo"----> $cons_C<br>";}
												$sqli_C=$conexion_mysqli->query($cons_C)or die($conexion_mysqli->error);
												$C=$sqli_C->fetch_row();
												$deuda_cuota=$C[0];
												echo'<td bgcolor="#AAFFAA">'.$deuda_cuota.'</td>';
												$sqli_C->free();
											}
										}	
										echo'</tr>';
									break;		
							}
							break;	
					}
				}
				else
				{if(DEBUG){ echo"No mostrar alumno...<br><br>";}}
								
								
			}///fin while alumnos

		}
		else
		{	switch($formato_salida)
			{
				case "pdf":
					$pdf->Cell(195,6,$msj_sin_reg,$borde,1,'L');
					break;
				case "xls":
					echo'<tr>
							<td>'.$msj_sin_reg.'</td>
						</tr>';
					break;
				case "html":
					echo'<tr>
							<td>'.$msj_sin_reg.'</td>
						</tr>';
					break;	
			}
		}
		//fin documento
		
		$label="Fecha Generacion: (".date("d-m-Y H:i:s").") ";
		if(isset($array_cuenta_alumnos["V"])){ $label.=$array_cuenta_alumnos["V"]." Alumnos Vigentes -"; }
		if(isset($array_cuenta_alumnos["EG"])){ $label.=$array_cuenta_alumnos["EG"]." Alumnos Egresados -"; }
		if(isset($array_cuenta_alumnos["R"])){ $label.=$array_cuenta_alumnos["R"]." Alumnos Retirados -";}
		if(isset($array_cuenta_alumnos["T"])){ $label.=$array_cuenta_alumnos["T"]." Alumnos Titulados -";}
		
		
		switch($formato_salida)
			{
				case "pdf":
						$pdf->MultiCell(195,6,$label,$borde,'R');
						if(!DEBUG){ $pdf->Output();}
						break;
			    case "xls":
						echo'<tr>
							<td colspan="3">'.$label.'</td>
						</tr>
						</table>';
					break;
				case "html":
						echo'<tr>
							<td colspan="30">'.$label.'</td>
						</tr>
						</table>';
					break;	
			}
	$sql_main_1->free();
	
	$conexion_mysqli->close();
?>