<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("registro_horario_clases");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("horario_clase_server.php");
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CONSULTA_HORARIO");
$xajax->register(XAJAX_FUNCTION,"GRABA_HORARIO");
$xajax->register(XAJAX_FUNCTION,"ELIMINA_HORARIO");
//-------------------------------------------------------//
if($_GET)
{
	$id_asignacion=base64_decode($_GET["AS_id"]);
	if(is_numeric($id_asignacion)){ $contiuar_1=true;}else{ $contiuar_1=false;}
}
else
{ $contiuar_1=false;}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Horario Clases Asignacion</title>
<?php $xajax->printJavascript(); ?> 
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:70%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 45px;
}
#div_horario {
	position:absolute;
	width:90%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 495px;
}
#apDiv4 {
	position:absolute;
	width:40%;
	height:115px;
	z-index:3;
	left: 45px;
	top: 260px;
}
</style>
<script language="javascript">
function AUTO_CONSULTA()
{
	id_asignacion=document.getElementById('AS_id').value;
	xajax_CONSULTA_HORARIO(id_asignacion);
}
function CONFIRMAR_ELIMINACION(id_horario)
{
	c=confirm('Seguro Desea Eliminar este Registro...?');
	if(c){ xajax_ELIMINA_HORARIO(id_horario);}
}
</script>
</head>
<body onload="AUTO_CONSULTA();">
<h1 id="banner">Administrador - Horario Clases Asignaci&oacute;n</h1>
<div id="apDiv1">
<?php if($contiuar_1)
	{
		require("../../../../funciones/conexion_v2.php");
		require("../../../../funciones/funciones_sistema.php");
		$cons="SELECT * FROM toma_ramo_docente WHERE id='$id_asignacion' LIMIT 1";
		if(DEBUG){ echo"--->$cons<br>";}
		$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$AS=$sqli->fetch_assoc();
			$AS_sede=$AS["sede"];
			$AS_id_carrera=$AS["id_carrera"];
			$AS_jornada=$AS["jornada"];
			$AS_grupo=$AS["grupo"];
			$AS_valor_hora=$AS["valor_hora"];
			$AS_cod_asignatura=$AS["cod_asignatura"];
			$AS_id_funcionario=$AS["id_funcionario"];
		$sqli->free();
			$nombre_carrera=NOMBRE_CARRERA($AS_id_carrera);
			list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($AS_id_carrera, $AS_cod_asignatura);	
			$nombre_funcionario=NOMBRE_PERSONAL($AS_id_funcionario);
			
			$array_dia=array(0 =>"Domingo",
				 1=>"Lunes",
				 2=>"Martes",
				 3=>"Miercoles",
				 4=>"Jueves",
				 5=>"Viernes",
				 6=>"Sabado");
			$dia_actual=date("w");	
			$array_salas=array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12","Aula Virtual");	
	?>
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="4">Informacion
        <input name="AS_id" type="hidden" id="AS_id" value="<?php echo $id_asignacion;?>" /></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="15%">Sede</td>
      <td colspan="3"><?php echo $AS_sede;?></td>
    </tr>
    <tr>
      <td>Carrera</td>
      <td colspan="3"><?php echo $AS_id_carrera."_".$nombre_carrera;?></td>
    </tr>
    <tr>
      <td>Asignatura</td>
      <td colspan="3"><?php echo $AS_cod_asignatura."_".$nombre_asignatura;?></td>
    </tr>
    <tr>
      <td>Jornada</td>
      <td width="16%"><?php echo $AS_jornada;?></td>
      <td width="11%">Grupo</td>
      <td width="58%"><?php echo $AS_grupo;?></td>
    </tr>
    <tr>
      <td>Funcionario</td>
      <td colspan="3"><?php echo $nombre_funcionario;?></td>
      </tr>
    </tbody>
  </table><br />
<table width="100%" border="1">
    <thead>
      <tr>
        <th colspan="3">Agregar Nuevo Registro</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Periodo de realizacion del curso (n. semanas)</td>
        <td width="32%"><select name="semanaInicio" id="semanaInicio">
          <?php
        $semana_i=1;
		$semana_f=18;
		for($s=$semana_i;$s<=$semana_f;$s++)
		{echo'<option value="'.$s.'">'.$s.'</option>';}
		?>
        </select></td>
        <td width="36%"><select name="semanaFin" id="semanaFin">
          <?php
        $semana_i=1;
		$semana_f=18;
		for($s=$semana_f;$s>=$semana_i;$s--)
		{echo'<option value="'.$s.'">'.$s.'</option>';}
		?>
        </select></td>
      </tr>
      <tr>
        <td width="32%">Dia</td>
        <td colspan="2"><select name="dia_semana" id="dia_semana">
          <?php
        foreach($array_dia as $n => $valor)
		{
			if($dia_actual==$n)
			{echo'<option value="'.$n.'" selected="selected">'.$n.'_'.$valor.'</option>';}
			else{echo'<option value="'.$n.'">'.$n.'_'.$valor.'</option>';}
		}
		?>
        </select></td>
      </tr>
      <tr>
        <td>Hora Inicio</td>
        <td colspan="2"><select name="hora_inicio" id="hora_inicio">
          <?php
        $hora_i=0;
		$hora_f=23;
		for($h=$hora_i;$h<=$hora_f;$h++)
		{
			if($h<10){$h_label="0".$h;}
			else{ $h_label=$h;}
			echo'<option value="'.$h_label.'">'.$h_label.'</option>';
		}
		?>
        </select>
          :
          <select name="minuto_inicio" id="minuto_inicio">
            <?php
        $min_i=0;
		$min_f=59;
		for($m=$min_i;$m<=$min_f;$m+=5)
		{
			if($m<10){$m_label="0".$m;}
			else{$m_label=$m;}
			
			echo'<option value="'.$m_label.'">'.$m_label.'</option>';
		}
		?>
          </select></td>
      </tr>
      <tr>
        <td>Hora Fin</td>
        <td colspan="2"><select name="hora_fin" id="hora_fin">
          <?php
        $hora_i=0;
		$hora_f=23;
		for($h=$hora_i;$h<=$hora_f;$h++)
		{
			if($h<10){$h_label="0".$h;}
			else{ $h_label=$h;}
			echo'<option value="'.$h_label.'">'.$h_label.'</option>';
		}
		?>
        </select>
          :
          <select name="minuto_fin" id="minuto_fin">
            <?php
        $min_i=0;
		$min_f=59;
		for($m=$min_i;$m<=$min_f;$m+=5)
		{
			if($m<10){$m_label="0".$m;}
			else{$m_label=$m;}
			
			echo'<option value="'.$m_label.'">'.$m_label.'</option>';
		}
		?>
          </select></td>
      </tr>
      <tr>
        <td>Sala</td>
        <td colspan="2"><select name="salas" id="salas">
          <?php
        foreach($array_salas as $ns => $valors)
		{
			echo'<option value="'.$valors.'">'.$valors.'</option>';
		}
		?>
        </select></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2" align="right"><a href="#" class="button_R" onclick="xajax_GRABA_HORARIO(document.getElementById('AS_id').value, document.getElementById('dia_semana').value, document.getElementById('hora_inicio').value, document.getElementById('minuto_inicio').value, document.getElementById('hora_fin').value, document.getElementById('minuto_fin').value, document.getElementById('salas').value, document.getElementById('semanaInicio').value, document.getElementById('semanaFin').value) ;return false;">Grabar</a></td>
      </tr>
    </tbody>
  </table>
<?php }?> 
</div>
<div id="div_horario">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="6">Horarios de Clases</th>
    </tr>
     <tr>
      <td>N</td>
      <td>Dia</td>
      <td>Hora Inicio</td>
      <td>Hora Fin</td>
      <td>Sala</td>
      <td>Opc</td>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td colspan="6">...</td>
    </tr>
    </tbody>
  </table>
</div>
</body>
</html>