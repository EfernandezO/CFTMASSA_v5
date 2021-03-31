<?php
//-----------------------------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Planificaciones->informeImprimible");
	$O->PERMITIR_ACCESO_USUARIO();
//-----------------------------------------//	
	require("../../../../funciones/conexion_v2.php");
	include("../../../../funciones/funciones_sistema.php");
	include("../../../../funciones/funcion.php");
if($_GET)
{
	$id_planificacionMain=base64_decode($_GET["id_planificacionMain"]);
	
	$consMAIN="SELECT * FROM planificacionesMain WHERE idPlanificacionMain='$id_planificacionMain'";
	if(DEBUG){ echo"-->$consMAIN<br>";}
	$sqliMain=$conexion_mysqli->query($consMAIN)or die("1111:".$conexion_mysqli->error);
	$DMain=$sqliMain->fetch_assoc();
	$id_planificacionMain=$DMain["idPlanificacionMain"];
	$numeroSemanas=$DMain["numeroSemanas"];
	$id_carrera=$DMain["id_carrera"];
	$cod_asignatura=$DMain["cod_asignatura"];
	$sede=$DMain["sede"];
	$semestre=$DMain["semestre"];
	$year=$DMain["year"];
	$jornada=$DMain["jornada"];
	$grupo_curso=$DMain["grupo"];
	$id_funcionario=$DMain["id_funcionario"];
	
	if(empty($id_planificacionMain)){$id_planificacionMain=0;}
$sqliMain->free();
	
	$TOTAL_SEMANA_PLANIFICAR=$numeroSemanas;
	
	////----------------------------------------------------------------///

	
	

	
	$nombre_carrera=NOMBRE_CARRERA($id_carrera);
	list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);
	$nombre_funcionario=NOMBRE_PERSONAL($id_funcionario);
//-------------------------------------------------------------------------------------------------------//	
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
	//---------------------------------------------------------------------------------------//
	
	$cons_NH="SELECT SUM(horas_semana) FROM planificaciones WHERE idPlanificacionMain='$id_planificacionMain'";
	if(DEBUG){ echo"Numero horas: $cons_NH<br>";}
	$sqli_NH=$conexion_mysqli->query($cons_NH)or die($conexion_mysqli->error);
	$NS=$sqli_NH->fetch_row();
		$total_horas_planificadas=$NS[0];
		if(empty($total_horas_planificadas)){ $total_horas_planificadas=0;}
	$sqli_NH->free();
	//-------------------------------------------------------------------------------------------//
	
	//max numero semana
	$cons_NS="SELECT COUNT(DISTINCT(numero_semana)) FROM planificaciones WHERE idPlanificacionMain='$id_planificacionMain'";
	if(DEBUG){ echo"Numero semanaas: $cons_NS<br>";}
	$sqli_NS=$conexion_mysqli->query($cons_NS)or die("NS. ".$conexion_mysqli->error);
	$NS=$sqli_NS->fetch_row();
		$numero_semanas_planificadas=$NS[0];
		if(empty($numero_semanas_planificadas)){$numero_semanas_planificadas=0;}	
	$sqli_NS->free();
	
	//--------------------------------------------------------------------------------------//
	
	if($total_horas_planificadas>=$TOTAL_HORAS_PROGRAMA)
	{ $condicion_horas=true;}
	else
	{ $condicion_horas=false;}
	
	if($numero_semanas_planificadas==$TOTAL_SEMANA_PLANIFICAR)
	{ $condicion_semanas=true;}
	else
	{ $condicion_semanas=false;}
	//-----------------------------------------------------------------------------------------//
		
$conexion_mysqli->close();
	
}
else{ echo"Sin Datos";}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<title>ver Planificaciones</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:77px;
	z-index:1;
	left: 5%;
	top: 306px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 70px;
}
#apDiv3 {
	position:absolute;
	width:40%;
	height:34px;
	z-index:2;
	left: 30%;
	top: 257px;
}
</style>
</head>
<body>
<h1 id="banner">Administrador -  Registro Planificaciones V1.0</h1>
<div id="apDiv2">
  <table width="100%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="3">Resumen de Observaciones</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="13%">N</td>
      <td width="66%">Observacion</td>
      <td width="21%">Condicion</td>
    </tr>
    <tr>
      <td>1</td>
      <td>Numero de Horas Planificadas (<?php echo"$total_horas_planificadas / $TOTAL_HORAS_PROGRAMA";?>)</td>
      <td><?php if($condicion_horas){ echo"OK";}else{ echo"Error";}?></td>
    </tr>
    <tr>
      <td>2</td>
      <td>Numero semanas Planificadas (<?php echo"$numero_semanas_planificadas / $TOTAL_SEMANA_PLANIFICAR";?>)</td>
      <td><?php if($condicion_semanas){ echo"OK";}else{ echo"Error";}?></td>
    </tr>
    </tbody>
  </table>
</div>
<div id="apDiv3">
  <span style="text-align: center">
  <?php if($condicion_horas and $condicion_semanas){ echo"Sin Errores, Puede Imprimir"; echo'<br><br><a href="informe_imprimible_2.php?id_planificacionMain='.base64_encode($id_planificacionMain).'" class="button_R" target="_blank">Click para Imprimir</a>';}else{ echo"Existen Errores, Por favor corregir para poder Imprimir";}?></span>
  <br><br><br>
  <a href="exportar_planificacion_a_xlsx.php?id_planificacionMain=<?php echo base64_encode($id_planificacionMain);?>" class="button_R" target="_blank">Click para XLSX</a>
  </div>
</body>
</html>