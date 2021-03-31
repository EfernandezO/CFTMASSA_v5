<?php
//--------------CLASS_okalis------------------//
	require("../../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../../funciones/";
	$O->clave_del_archivo=md5("control_horario_docente");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	
//////////////////////XAJAX/////////////////
@require_once ("../../../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("marca_time_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CONTROL_HORARIO");
////////////////////////////////////////////
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php $xajax->printJavascript(); ?> 
<?php include("../../../../../../funciones/codificacion.php");?>
<title>Detalle Marca Tiempo</title>
<link rel="stylesheet" type="text/css" href="../../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 41px;
}
#div_informacion {
	position:absolute;
	width:40%;
	height:32px;
	z-index:2;
	left: 30%;
	top: 451px;
}
</style>
</head>

<body>
<div id="div_informacion"></div>
<h1 id="banner">Administrador - Revisi&oacute;n Horario Docente</h1>
<div id="apDiv1">
<?php
if($_GET)
{
	$H_id=base64_decode($_GET["H_id"]);
	$H_estado=$_GET["tipo"];
	if(is_numeric($H_id))
	{ $continuar=true;}
	else
	{ $continuar=false;}
	
	switch($H_estado)
	{
		case"ingreso":
			$continuar_2=true;	
			break;
		case"salida":
			$continuar_2=true;	
			break;
		default:
			$continuar_2=false;	
	}
	
	
}
else
{ $continuar=false;}

if(($continuar)and($continuar_2))
{
	require("../../../../../../funciones/conexion_v2.php");
	require("../../../../../../funciones/funciones_sistema.php");
	
	$cons="SELECT horario_docente.*, toma_ramo_docente.* FROM horario_docente INNER JOIN toma_ramo_docente ON horario_docente.id_asignacion=toma_ramo_docente.id WHERE horario_docente.id_horario='$H_id' LIMIT 1";
	if(DEBUG){ echo"--->$cons<br>";}
	$sqli=$conexion_mysqli->query($cons);
		$H=$sqli->fetch_assoc();
		$H_id_asignacion=$H["id_asignacion"];
		$H_dia_semana=$H["dia_semana"];
		$H_hora_inicio=$H["hora_inicio"];
		$H_hora_fin=$H["hora_fin"];
		$H_sala=$H["sala"];
		
		$AS_id_funcionario=$H["id_funcionario"];
		$AS_cod_asignatura=$H["cod_asignatura"];
		$AS_id_carrera=$H["id_carrera"];
		$AS_sede=$H["sede"];
		$AS_jornada=$H["jornada"];
		$AS_grupo=$H["grupo"];
		
		list($AS_nombre_asignatura, $AS_nivel_asignatura)=NOMBRE_ASIGNACION($AS_id_carrera, $AS_cod_asignatura);
	$sqli->free();	
	
	$array_hora_ingreso=explode(":",$H_hora_inicio);
	$array_hora_salida=explode(":", $H_hora_fin);
	list($diferencia_hora, $diferencia_minutos)=explode(":",date("H:i", strtotime("00:00:00") + strtotime($H_hora_fin) - strtotime($H_hora_inicio)));
	
	$diferencia_minutos_total=($diferencia_hora*60)+$diferencia_minutos;
	
	if($jornada=="D"){$minutos_duracion_hora_pedagogica=45;}
	else{$minutos_duracion_hora_pedagogica=40;}
	
	$horas_pedagogias=(int)($diferencia_minutos_total/$minutos_duracion_hora_pedagogica);
}
?>
<table width="65%" border="1">
<thead>
  <tr>
    <th colspan="2">Informacion</th>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td width="21%">Funcionario</td>
    <td width="79%"><?php echo NOMBRE_PERSONAL($AS_id_funcionario);?></td>
  </tr>
  <tr>
    <td>Sede</td>
    <td><?php echo $AS_sede;?></td>
  </tr>
  <tr>
    <td>Carrera</td>
    <td><?php echo NOMBRE_CARRERA($AS_id_carrera);?></td>
  </tr>
  <tr>
    <td>Asignatura</td>
    <td><?php echo "$AS_nombre_asignatura $AS_jornada - $AS_grupo";?></td>
  </tr>
  <tr>
    <td>Horario Clase</td>
    <td><?php echo "$H_hora_inicio a $H_hora_fin -> horas pedagogicas diarias [$horas_pedagogias]";?></td>
  </tr>
  </tbody>
</table>
<br />
<table width="100%" border="1">
  <thead>
  <tr>
    <th colspan="5">Registros Control Horario</th>
  </tr>
  <tr>
    <td width="8%">N</td>
    <td width="46%">Fecha</td>
    <td width="46%">Tipo</td>
    <td width="92%">Hora</td>
    <td>id</td>
  </tr>
   </thead>
  <tbody>
  <?php
  	if(($continuar)and($continuar_2))
	{
		$SUMA_DIAS_INASISTENCIA=0;
		$SUMA_DIAS_ASISTENCIA=0;
		
		$cons="SELECT * FROM horario_docente_registros WHERE id_horario='$H_id' ORDER by fecha, id_horario_registro";
		$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$num_registros=$sqli->num_rows;
		
		if($num_registros>0)
		{
			$aux=0;
			while($R=$sqli->fetch_assoc())
			{
				$aux++;
				$RH_fecha=$R["fecha"];
				$RH_hora=$R["hora"];
				$RH_tipo_registro=$R["tipo_registro"];
				$RH_id=$R["id_horario_registro"];
				
				switch($RH_tipo_registro)
				{
					case"inasistencia":
						$SUMA_DIAS_INASISTENCIA++;
						break;
					case"llegada":
						$SUMA_DIAS_ASISTENCIA++;
						break;
				}
				
				echo'<tr>
						<td>'.$aux.'</td>
						<td>'.$RH_fecha.'</td>
						<td>'.$RH_tipo_registro.'</td>
						<td>'.$RH_hora.'</td>
						<td>'.$RH_id.'</td>
					 </tr>';
			}
		}
		else
		{
		}
	}
  ?>
  <tr>
  	<td colspan="5">&nbsp;</td>
  </tr>
  <tr>
  	<td colspan="2">DIAS ASISTENCIAS</td>
    <td><?php echo $SUMA_DIAS_ASISTENCIA;?></td>
    <td colspan="2" bgcolor="#00FF00">&nbsp;</td>
  </tr>
   <tr>
     <td colspan="2">Horas Pedagocias Asistidas</td>
     <td><?php echo ($SUMA_DIAS_ASISTENCIA*$horas_pedagogias);?></td>
     <td colspan="2" bgcolor="#00FF00">&nbsp;</td>
   </tr>
   <tr>
     <td colspan="2">DIAS INASISTENCIAS</td>
     <td><?php echo $SUMA_DIAS_INASISTENCIA; ?></td>
     <td colspan="2" bgcolor="#FF0000">&nbsp;</td>
   </tr>
   <tr>
  	<td colspan="2">horas Pedagogicas Inasistidas</td>
    <td><?php echo ($SUMA_DIAS_INASISTENCIA*$horas_pedagogias);?></td>
    <td colspan="2" bgcolor="#FF0000">&nbsp;</td>
  </tr>
  
  </tbody>
</table>
</div>
</body>
</html>