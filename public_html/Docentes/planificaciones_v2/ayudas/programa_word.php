<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG",false);
//-----------------------------------------//
if(!DEBUG){
	header('Content-type: application/vnd.ms-word');
	header('Content-Disposition: attachment;Filename=plantilla_planificacion_v1.doc');
}
$continuar=false;

if(isset($_GET['id_carrera'])){$id_carrera=base64_decode($_GET['id_carrera']);}
if(isset($_GET['cod_asignatura'])){$cod_asignatura=base64_decode($_GET['cod_asignatura']);}

if(isset($_GET['year'])){$year=base64_decode($_GET['year']);}
if(isset($_GET['semestre'])){$semestre=base64_decode($_GET['semestre']);}
if(isset($_GET['jornada'])){$jornada=base64_decode($_GET['jornada']);}
if(isset($_GET['grupo'])){$grupo=base64_decode($_GET['grupo']);}
if(isset($_GET['sede'])){$sede=base64_decode($_GET['sede']);}
if(isset($_GET['id_funcionario'])){$id_funcionario=base64_decode($_GET['id_funcionario']);}

if((is_numeric($id_carrera))&&(is_numeric($cod_asignatura))){$continuar=true;}

if($continuar)
{
	require("../../../../funciones/conexion_v2.php");	
	require("../../../../funciones/funciones_sistema.php");	
	///horas de programa
	$TOTAL_HORAS_PROGRAMA=0;
	$cons_HT="SELECT DISTINCT(numero_unidad) FROM programa_estudio WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura'";
	$sqli_HT=$conexion_mysqli->query($cons_HT)or die($conexion_mysqli->error);
	$num_programas=$sqli_HT->num_rows;
	if($num_programas>0)
	{
		while($HT=$sqli_HT->fetch_row())
		{
			$aux_numero_unidad=$HT[0];
			$aux_CONS="SELECT cantidad_horas FROM programa_estudio WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND numero_unidad='$aux_numero_unidad' LIMIT 1";
			$sqli_aux=$conexion_mysqli->query($aux_CONS)or die("HP ".$conexion_mysqli->error);
				$Pnh=$sqli_aux->fetch_row();
				$aux_numero_hora_x_unidad=$Pnh[0];
				if(empty($aux_numero_hora_x_unidad)){ $aux_numero_hora_x_unidad=0;}
			$TOTAL_HORAS_PROGRAMA+=$aux_numero_hora_x_unidad;
			$sqli_aux->free();	
		}
	}
	$sqli_HT->free();

$html="<html>";
$html.="<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
$html.="<body>";
$html.='<img src="http://cftmassachusetts.cl/~cftmassa/BAses/Images/logo_cft.jpg" width="100" height="70" alt="logo"><br>';
$html.='<h1 align="center">PLANIFICACION DE CLASE</h1>';


list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);


$html.='<table width="50%" border="1" align="left">
		<thead>
		 <tr>
		 	<td>Sede</td>
			<td>'.$sede.'</td>
		 </tr>
		  <tr>
		 	<td>Carrera</td>
			<td>'.$id_carrera.' '.utf8_decode(NOMBRE_CARRERA($id_carrera)).'</td>
		 </tr>
		  <tr>
		 	<td>Jornada</td>
			<td>'.$jornada.' '.$grupo.'</td>
		 </tr>
		  <tr>
		 	<td>Asignatura</td>
			<td>'.$cod_asignatura.' '.utf8_decode($nombre_asignatura).' NIVEL '.$nivel_asignatura.'</td>
		 </tr>
		  <tr>
		 	<td>Docente</td>
			<td>'.$id_funcionario.' '.NOMBRE_PERSONAL($id_funcionario).'</td>
		 </tr>
		  <tr>
		 	<td>Periodo</td>
			<td>['.$semestre.'-'.$year.']</td>
		 </tr>
		  <tr>
		 	<td>N. Hrs. Programa</td>
			<td>'.$TOTAL_HORAS_PROGRAMA.'</td>
		 </tr>
		 </thead>
		 </table><br></br><br></br><br></br><br></br><br></br><br></br><br></br><br></br><br></br><br></br>';

$html.='<table width="100%" border="1" align="center">
		<thead>
		 <tr>
		 	<td>N. Semana</td>
			<td>Hrs. por semana</td>
			<td>Contenido Tematico</td>
			<td>Actividad</td>
			<td>Implemento de apoyo a la docencia</td>
			<td>Evaluacion</td>
			<td>Bibliografia</td>
		 </tr>
		 </thead>
		 <tbody>';
		 	
			



	
		
$cons_P="SELECT * FROM programa_estudio WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura'";
	$sqli_P=$conexion_mysqli->query($cons_P)or die($conexion_mysqli->error);
	$num_registros=$sqli_P->num_rows;
	if(DEBUG){ echo"<br>--> $cons_P <br>registros: $num_registros<br>";}
	
	if($num_registros>0)
	{
		while($P=$sqli_P->fetch_assoc())
		{
			$id_programa=$P["id_programa"];
			$numero_unidad=$P["numero_unidad"];
			$nombre_unidad=$P["nombre_unidad"];
			$cantidad_horas=$P["cantidad_horas"];
			$contenido=$P["contenido"];
			
			$html.='<tr>
		 	<td></td>
			<td></td>
			<td>'.$contenido.'</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		 </tr>';
	
		}
	}
	
	
	$sqli_P->free();
	
			
			
$html.='</tbody>
		 </table>';


$html.="</body>";
$html.="</html>";
$conexion_mysqli->close();

echo $html;
}else{ echo"ERROR";}
?>