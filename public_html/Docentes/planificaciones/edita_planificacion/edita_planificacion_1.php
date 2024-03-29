<?php
//-----------------------------------------//
//-----------------------------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(false);
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Planificaciones->Editar");
	$O->PERMITIR_ACCESO_USUARIO();
//-----------------------------------------//
//-----------------------------------------//
//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("comprueba_server.php");
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"COMPRUEBA");
//---------------------------------------------------------///
if(DEBUG){var_dump($_POST);}
	require("../../../../funciones/funciones_sistema.php");
	require("../../../../funciones/conexion_v2.php");
	
	$id_planificacion=base64_decode($_GET["id_planificacion"]);
	$id_planificacionMain=base64_decode($_GET["id_planificacionMain"]);
	
	$consPM="SELECT * FROM planificacionesMain WHERE idPlanificacionMain='$id_planificacionMain'";
	$sqliPM=$conexion_mysqli->query($consPM)or die($conexion_mysqli->error);
	$DPM=$sqliPM->fetch_assoc();
		$id_carrera=$DPM["id_carrera"];
		$cod_asignatura=$DPM["cod_asignatura"];
		$numeroSemanas=$DPM["numeroSemanas"];
	$sqliPM->free();
	
	$cons="SELECT * FROM planificaciones WHERE id_planificacion='$id_planificacion' LIMIT 1";
	$sqli=$conexion_mysqli->query($cons);
		$P=$sqli->fetch_assoc();
	
		$numero_semana=$P["numero_semana"];
		$horas_semana=$P["horas_semana"];
		
		$contenido_tematico=$P["contenido_tematico_opcional"];
		$actividad=$P["actividad"];
		$implemento=$P["implemento"];
		$evaluacion=$P["evaluacion"];
		$bibliografia=$P["bibliografia"];
		
	$sqli->free();
	//----------------------------------------------------------------------------------------///
	$nombre_carrera=NOMBRE_CARRERA($id_carrera);
	list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);
	
	
	//------------------------------------------------------------------//
	///horas de programa total
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
	//----------------------------------------------------//
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<title>Agrega Registro a Planificacion</title>
<?php $xajax->printJavascript(); ?> 
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:50%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 53px;
}
#apDiv2 {
	position:absolute;
	width:60%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 263px;
}
#div_boton {
	position:absolute;
	width:25%;
	height:33px;
	z-index:3;
	left: 70%;
	top: 419px;
	text-align:center;
}
#div_info {
	position:absolute;
	width:25%;
	height:115px;
	z-index:4;
	left: 70%;
	top: 283px;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	continuar=true;
	actividad=document.getElementById('actividad').value;
	implemento=document.getElementById('implemento').value;
	evaluacion=document.getElementById('evaluacion').value;
	bibliografia=document.getElementById('bibliografia').value;
	<?php if($id_programa==0){?>
		contenido_tematico=document.getElementById('contenido_tematico').value;
		
		if((contenido_tematico=="")||(contenido_tematico==" "))
		{
			continuar=false;
			alert("Ingrese Contenido Tematico");
		}
	<?php }?>
	
	if((actividad=="")||(actividad==" "))
	{
		continuar=false;
		alert("Ingrese Una Actividad");
	}
	
	if(continuar)
	{
		c=confirm('Desea Modificar este Registro ¿?');
		if(c){ document.getElementById('frm').submit();}
	}
}
</script>
</head>

<body onload="xajax_COMPRUEBA(<?php echo $numero_semana;?>, <?php echo $id_planificacion;?>, <?php echo $id_planificacionMain;?>); return false;">
<h1 id="banner">Administrador -  Edita Planificaciones V1.0</h1>
<div id="apDiv1">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="2">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="25%">Carrera</td>
      <td><?php echo $nombre_carrera;?></td>
    </tr>
    <tr>
      <td>Asinatura</td>
      <td><?php echo $nombre_asignatura;?></td>
    </tr>
    </tbody>
  </table>
</div>
<div id="apDiv2">
<form action="edita_planificacion_2.php" method="post" id="frm">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="2"><input name="id_planificacion" type="hidden" id="id_planificacion" value="<?php echo $id_planificacion;?>" />
        Ingrese los Datos
        
        <input type="hidden" name="id_planificacionMain" id="id_planificacionMain" value="<?php echo $id_planificacionMain;?>"/></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>Contenido Tematico</td>
      <td><label for="contenido_tematico"></label>
        <input type="text" name="contenido_tematico" id="contenido_tematico"  value="<?php echo $contenido_tematico;?>"/></td>
    </tr>

    <tr>
      <td width="43%">N. Semana</td>
      <td width="57%">
        <select name="numero_semana" id="numero_semana" onchange="xajax_COMPRUEBA(this.value, <?php echo $id_planificacion;?>, <?php echo $id_planificacionMain?>); return false;">
        <option value="0" selected="selected">Seleccione</option>
        <?php
        for($x=1;$x<=$numeroSemanas;$x++)
		{
			if($numero_semana==$x)
			{ echo'<option value="'.$x.'" selected="selected">'.$x.'</option>';}
			else
			{echo'<option value="'.$x.'">'.$x.'</option>';}
		}
		?>
        </select>
        </td>
    </tr>
    <tr>
      <td>Horas X Semana</td>
      <td><div id="div_horas_semana">...</div>
        
        </td>
    </tr>
    <tr>
      <td>Actividad/Metodologia</td>
      <td><label for="actividad"></label>
        <input type="text" name="actividad" id="actividad"  value="<?php echo $actividad;?>"/></td>
    </tr>
    <tr>
      <td>Implemento Apoyo a la Docencia</td>
      <td><label for="implemento"></label>
        <input type="text" name="implemento" id="implemento" value="<?php echo $implemento;?>"/></td>
    </tr>
    <tr>
      <td>Evaluacion(Tipo)</td>
      <td><label for="evaluacion"></label>
        <input type="text" name="evaluacion" id="evaluacion" value="<?php echo $evaluacion;?>"/></td>
    </tr>
    <tr>
      <td>Bibliografia</td>
      <td><label for="bibliografia"></label>
        <input type="text" name="bibliografia" id="bibliografia" value="<?php echo $bibliografia;?>"/></td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="div_boton"></div>
<div id="div_info"></div>
</body>
</html>
<?php
$conexion_mysqli->close();
@mysql_close($conexion);	
?>