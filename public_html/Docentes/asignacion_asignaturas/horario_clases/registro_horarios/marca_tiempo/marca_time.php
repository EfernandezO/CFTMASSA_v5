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
<title>marca tiempo</title>
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
	top: 520px;
}
</style>
<script language="javascript" type="text/javascript">
function MARCAR_INASISTENCIA(H_id, H_fecha)
{
	c=confirm('Marcar al Docente Como Inasistente este Dia..¿?');
	if(c)
	{
		xajax_CONTROL_HORARIO('inasistencia', document.getElementById('IN_horas').value, document.getElementById('IN_minutos').value, document.getElementById('IN_segundos').value, H_id, H_fecha); 
	}
}
</script>
</head>

<body>
<div id="div_informacion"></div>
<h1 id="banner">Administrador - Revisi&oacute;n Horario Docente</h1>
<div id="apDiv1">
<?php
if($_GET)
{
	$H_id=base64_decode($_GET["H_id"]);
	$H_fecha=base64_decode($_GET["fecha"]);
	if(is_numeric($H_id))
	{ $continuar=true;}
	else
	{ $continuar=false;}
	
	if(empty($H_fecha)){ $continuar_2=false;}
	else{ $continuar_2=true;}
	
	
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
	
}
?>
<table width="50%" border="1">
<thead>
  <tr>
    <th colspan="2">Informacion</th>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td>Fecha</td>
    <td><?php echo $H_fecha;?></td>
  </tr>
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
  </tbody>
</table>
<br />
<table width="100%" border="1">
  <thead>
  <tr>
    <th colspan="3">Control Horario</th>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td width="49%">Hora Ingreso</td>
    <td colspan="2"><?php echo $H_hora_inicio;?></td>
    </tr>
  <tr>
    <td>Marca Hora Ingreso</td>
    <td width="44%">
      <select name="I_horas" id="I_horas">
      <?php
      for($h=0;$h<24;$h++)
	  {
		  if($h<10){ $h_label="0".$h;}
		  else{$h_label=$h;}
		  
		  if($array_hora_ingreso[0]==$h_label)
		  { echo'<option value="'.$h_label.'" selected="selected">'.$h_label.'</option>';}
		  else{echo'<option value="'.$h_label.'">'.$h_label.'</option>';}
	  }
	  ?>
      </select>
      :
      <select name="I_minutos" id="I_minutos">
       <?php
      for($m=0;$m<60;$m++)
	  {
		  if($m<10){$m_label="0".$m;}
		  else{$m_label=$m;}
		  
		  if($array_hora_ingreso[1]==$m_label)
		  {echo'<option value="'.$m_label.'" selected="selected">'.$m_label.'</option>';}
		  else{echo'<option value="'.$m_label.'">'.$m_label.'</option>';}
	  }
	  ?>
      </select>
      :
      <select name="I_segundos" id="I_segundos">
       <?php
      for($s=0;$s<60;$s++)
	  {
		  if($s<10){$s_label="0".$s;}
		  else{$s_label=$s;}
		  
		  if($array_hora_ingreso[2]==$s_label)
		  {echo'<option value="'.$s_label.'" selected="selected">'.$s_label.'</option>';}
		  else{echo'<option value="'.$s_label.'">'.$s_label.'</option>';}
	  }
	  ?>
      </select></td>
    <td width="7%"><a href="#" title="Marcar Llegada" onclick="xajax_CONTROL_HORARIO('llegada', document.getElementById('I_horas').value, document.getElementById('I_minutos').value, document.getElementById('I_segundos').value, '<?php echo $H_id;?>', '<?php echo $H_fecha;?>'); return false;"><img src="../../../../../BAses/Images/icono_cronometro.png" width="26" height="26" alt="ingreso" /></a></td>
  </tr>
  <tr>
    <td>Hora Salida</td>
    <td colspan="2"><?php echo $H_hora_fin;?></td>
  </tr>
  <tr>
    <td>Marca Hora Salida</td>
    <td><select name="S_horas" id="S_horas">
      <?php
      for($h=0;$h<24;$h++)
	  {
		  if($h<10){ $h_label="0".$h;}
		  else{$h_label=$h;}
		  
		  if($array_hora_salida[0]==$h_label)
		  { echo'<option value="'.$h_label.'" selected="selected">'.$h_label.'</option>';}
		  else{echo'<option value="'.$h_label.'">'.$h_label.'</option>';}
	  }
	  ?>
    </select>
      :
      <select name="S_minutos" id="S_minutos">
        <?php
      for($m=0;$m<60;$m++)
	  {
		  if($m<10){$m_label="0".$m;}
		  else{$m_label=$m;}
		  
		  if($array_hora_salida[1]==$m_label)
		  {echo'<option value="'.$m_label.'" selected="selected">'.$m_label.'</option>';}
		  else{echo'<option value="'.$m_label.'">'.$m_label.'</option>';}
	  }
	  ?>
      </select>
      :
      <select name="S_segundos" id="S_segundos">
        <?php
      for($s=0;$s<60;$s++)
	  {
		  if($s<10){$s_label="0".$s;}
		  else{$s_label=$s;}
		  
		  if($array_hora_salida[2]==$s_label)
		  {echo'<option value="'.$s_label.'" selected="selected">'.$s_label.'</option>';}
		  else{echo'<option value="'.$s_label.'">'.$s_label.'</option>';}
	  }
	  ?>
      </select></td>
    <td><a href="#" title="Marcar Salida" onclick="xajax_CONTROL_HORARIO('salida', document.getElementById('S_horas').value, document.getElementById('S_minutos').value, document.getElementById('S_segundos').value, '<?php echo $H_id;?>', '<?php echo $H_fecha;?>'); return false;"><img src="../../../../../BAses/Images/icono_cronometro.png" width="26" height="26" alt="ingreso" /></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Marcar Inasistencia del Docente</strong></td>
    <td><select name="IN_horas" id="IN_horas">
      <?php
      for($h=0;$h<24;$h++)
	  {
		  if($h<10){ $h_label="0".$h;}
		  else{$h_label=$h;}
		  
		  if($array_hora_ingreso[0]==$h_label)
		  { echo'<option value="'.$h_label.'" selected="selected">'.$h_label.'</option>';}
		  else{echo'<option value="'.$h_label.'">'.$h_label.'</option>';}
	  }
	  ?>
    </select>
:
<select name="IN_minutos" id="IN_minutos">
  <?php
      for($m=0;$m<60;$m++)
	  {
		  if($m<10){$m_label="0".$m;}
		  else{$m_label=$m;}
		  
		  if($array_hora_ingreso[1]==$m_label)
		  {echo'<option value="'.$m_label.'" selected="selected">'.$m_label.'</option>';}
		  else{echo'<option value="'.$m_label.'">'.$m_label.'</option>';}
	  }
	  ?>
</select>
:
<select name="IN_segundos" id="IN_segundos">
  <?php
      for($s=0;$s<60;$s++)
	  {
		  if($s<10){$s_label="0".$s;}
		  else{$s_label=$s;}
		  
		  if($array_hora_ingreso[2]==$s_label)
		  {echo'<option value="'.$s_label.'" selected="selected">'.$s_label.'</option>';}
		  else{echo'<option value="'.$s_label.'">'.$s_label.'</option>';}
	  }
	  ?>
</select></td>
    <td><a href="#" title="Marcar Inasistencia" onclick="MARCAR_INASISTENCIA(<?php echo $H_id?>, '<?php echo $H_fecha;?>');"><img src="../../../../../BAses/Images/icono_cronometro.png" width="26" height="26" alt="Inasistencia" /></a></td>
  </tr>
  </tbody>
</table>
</div>
</body>
</html>