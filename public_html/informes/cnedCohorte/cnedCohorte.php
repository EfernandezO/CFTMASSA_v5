<?php
set_time_limit(600);
ini_set('memory_limit', '-1');
$tiempo_inicio_script = microtime(true);
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_PAC_SIES_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Cned Cohortes</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
}
</style>
<body>
<h1 id="banner">Administrador - Cned Cohortes</h1>
<div id="link">
  <div align="right"><br />
<a href="../../Alumnos/menualumnos.php" class="button">Volver al Menu</a></div>
</div>

<div id="apDiv1">
<?php
	require("../../../funciones/funciones_sistema.php");
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/class_ALUMNO.php");
	//buscando todas las carreras
	$ARRAY_CARRERAS=array(19, 21, 11, 18, 1, 4);
	$ARRAY_OPCIONES=array(0=>"arancelAnual", 1=>"cantidadAlumnos", 2=>"pagosArancel");
	$ARRAY_YEAR_COHORTE=array(2014, 2015, 2016, 2017, 2018, 2019, 2020, 2021);
	$ARRAY_YEAR_CONSULTA=array(2017, 2018, 2019, 2020, 2021);
	//---------------------------------------------------------------------//
	
	
		foreach($ARRAY_CARRERAS as $nc=>$idCarrera){
			$nombreCarrera=NOMBRE_CARRERA($idCarrera);
			echo "<strong>Nombre Programa: $idCarrera</strong>".$nombreCarrera."<br>";
			$ARRAY_PAGOS_ARANCEL=array();
			foreach($ARRAY_OPCIONES as $i => $opcion)
				{
					echo"opcion: ".$opcion."<br>";
						echo'<table border="1" width="60%">';
						
						echo'<tr>';
						echo'<td>cohorte/año</td>';
						foreach($ARRAY_YEAR_CONSULTA as $xx=>$yearConsultaL){
							echo'<td>'.$yearConsultaL.'</td>';
						}
						echo'</tr>';
						
						foreach($ARRAY_YEAR_COHORTE as $n => $yearCohorte){
							echo'<tr>
									<td>'.$yearCohorte.'</td>';
							foreach($ARRAY_YEAR_CONSULTA as $x => $yearConsulta){
								
									$valorMostrar=0;
									switch($opcion){
										//arancel anual
										case"arancelAnual":
											$consAA="SELECT (arancel_1 + arancel_2) as arancelAnual FROM hija_carrera_valores WHERE id_madre_carrera='$idCarrera' AND year='$yearConsulta'";
											if(DEBUG){ echo"ARANCEL ANUAL: $consAA<br>";}
											$sqliAA=$conexion_mysqli->query($consAA) or die($conexion_mysqli->error);
											$AA=$sqliAA->fetch_assoc();
											$valorMostrar=$AA["arancelAnual"];
											break;
											
											//cantidad de alumnos
										case"cantidadAlumnos":
											
											$consCA="SELECT DISTINCT(id_alumno) FROM contratos2 WHERE id_carrera='$idCarrera' AND ano='$yearConsulta' AND yearIngresoCarrera='$yearCohorte'";		
											$sqliCA=$conexion_mysqli->query($consCA) or die($conexion_mysqli->error);
											$numAlumnos=$sqliCA->num_rows;
											if(DEBUG){ echo"CANTIDAD ALUMNOS: $consCA<br>num Alumnos: $numAlumnos<br>";}
											$auxCantidadAlumnos=0;
											$auxTotalCancelado=0;
											while($CA=$sqliCA->fetch_row()){
												$auxIdAlumno=$CA[0];
												$ALUMNO=new ALUMNO($auxIdAlumno);
												$ALUMNO->SetDebug(DEBUG);
												$ALUMNO->IR_A_PERIODO(1,$yearConsulta);
												$auxSituacionAlumno=$ALUMNO->getSituacionAlumnoPeriodo();
												if(DEBUG){ echo"--Situacion alumno periodo: $auxSituacionAlumno<br>";}
												
												$utilizarAlumno=true;
												
												if($auxSituacionAlumno=="R"){
													$utilizarAlumno=false;
												}
												
												if($utilizarAlumno){
													$auxCantidadAlumnos++;
												//busco Pagos realizados por estos alumnos
												
													$consPA="SELECT SUM(valor) FROM pagos WHERE id_alumno='$auxIdAlumno' AND YEAR(fechapago)='$yearConsulta' AND por_concepto='arancel'";
													$sqliPA=$conexion_mysqli->query($consPA) or die($conexion_mysqli->error);
													$PA=$sqliPA->fetch_row();
													$pagoAlumno=$PA[0];
													$sqliPA->free();
													if(empty($pagoAlumno)){$pagoAlumno=0;}
													if(DEBUG){ echo"---PAGO ALUMNO: $consPA<BR>---CANTIDAD PAGADA	: $pagoAlumno<br>";}
													
													$auxTotalCancelado+=$pagoAlumno;
												}
	
											}
											$ARRAY_PAGOS_ARANCEL[$yearCohorte][$yearConsulta]=$auxTotalCancelado;
											if(DEBUG){ echo"<strong>---TOTAL PAGADO: $auxTotalCancelado </strong><br>";}
											$valorMostrar=$auxCantidadAlumnos;
											$sqliCA->free();
											
											break;	
										
										case"pagosArancel":
											
											if(isset($ARRAY_PAGOS_ARANCEL[$yearCohorte][$yearConsulta])){$valorMostrar=$ARRAY_PAGOS_ARANCEL[$yearCohorte][$yearConsulta];}
											else{$valorMostrar=0;}
											break;		
											
											
									}
								
								echo'<td align="right">'.$valorMostrar.'</td>';
							}
							echo'</tr>';
						}
						echo'</table><br><br>';
				}
			
		}
	

$tiempo_fin_script = microtime(true);
$tiempo_de_ejecucion=round($tiempo_fin_script - $tiempo_inicio_script,4);
echo'Tiempo de Ejecucion de Script '.$tiempo_de_ejecucion.' Segundos';
	
?>
</div>
</body>
</html>