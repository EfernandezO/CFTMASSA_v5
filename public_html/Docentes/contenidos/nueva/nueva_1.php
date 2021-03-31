<?php
//-----------------------------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Contenidos->nuevoRegistro");
	$O->PERMITIR_ACCESO_USUARIO();
//-----------------------------------------//
//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("comprueba_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"COMPRUEBA");
//---------------------------------------------------------///
if(DEBUG){var_dump($_POST);}
	
	$id_contenidoMain=base64_decode($_GET["id_contenidoMain"]);
	
	require("../../../../funciones/funciones_sistema.php");
	require("../../../../funciones/conexion_v2.php");
	
	
	$consPM="SELECT * FROM contenidosMain WHERE idContenidoMain='$id_contenidoMain'";
	$sqliPM=$conexion_mysqli->query($consPM)or die($conexion_mysqli->error);
	$DPM=$sqliPM->fetch_assoc();
		$id_carrera=$DPM["id_carrera"];
		$cod_asignatura=$DPM["cod_asignatura"];
		$semestre=$DPM["semestre"];
		$year=$DPM["year"];
		$sede=$DPM["sede"];
		$id_funcionario=$DPM["id_funcionario"];
		$jornada=$DPM["jornada"];
		$grupo=$DPM["grupo"];
		
		$numeroSemanas=$DPM["numero_semanas"];
	$sqliPM->free();
	
	$nombre_carrera=NOMBRE_CARRERA($id_carrera);
	list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);
	
	
	//bibliografia de planificaciones
	$ARRAY_BIBLIOGRAFIA_PLANIFICACIONES=array();
	$cons_BP="SELECT DISTINCT(planificaciones.bibliografia) FROM planificaciones INNER JOIN planificacionesMain ON planificacionesMain.idPlanificacionMain= planificaciones.idPlanificacionMain WHERE planificacionesMain.sede='$sede' AND planificacionesMain.year='$year' AND planificacionesMain.semestre='$semestre' AND planificacionesMain.id_carrera='$id_carrera' AND planificacionesMain.cod_asignatura='$cod_asignatura' AND planificacionesMain.jornada='$jornada' AND planificacionesMain.grupo='$grupo' AND planificacionesMain.id_funcionario='$id_funcionario'";
	$sqliBP=$conexion_mysqli->query($cons_BP) or die($conexion_mysqli->error);
	$numBibliografias=$sqliBP->num_rows;
	if($numBibliografias>0){
		while($BP=$sqliBP->fetch_row()){
			$auxBibliografia=$BP[0];
			array_push($ARRAY_BIBLIOGRAFIA_PLANIFICACIONES, $auxBibliografia);
		}
	}
	$sqliBP->free();
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<title>Agrega Contenido</title>
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
	top: 214px;
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
	contenido=document.getElementById('contenido').value;
	bibliografia=document.getElementById('bibliografia').value;
	
	if((contenido=="")||(contenido==" "))
	{
		continuar=false;
		alert("Ingrese el contenido");
	}
	
	
	if(continuar)
	{
		c=confirm('Desea Agregar este Registro ¿?');
		if(c){ document.getElementById('frm').submit();}
	}
}
function TRASPASAR(valor){
	document.getElementById('bibliografia').value=valor;
}
</script>
 <script src="../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
 <script src="../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/steel/steel.css">
</head>

<body>
<h1 id="banner">Administrador -  Nuevo Contenido V1.0</h1>
<div id="link"><br />
<!--<a href="../ver_contenidos.php?id_contenidoMain=<?php echo base64_encode($id_contenidoMain);?>" class="button">Volver</a>--></div>
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
<form action="nueva_2.php" method="post" id="frm">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="3">Ingrese los Datos
        <input name="id_contenidoMain" type="hidden" id="id_contenidoMain" value="<?php echo $id_contenidoMain;?>" />
      </tr>
    </thead>
    <tbody>
    <tr>
      <td width="43%">N. Semana</td>
      <td colspan="2">
        <select name="numero_semana" id="numero_semana" onchange="xajax_COMPRUEBA(this.value, <?php echo $id_contenidoMain?>); return false;">
          <option value="0" selected="selected">Seleccione</option>
          <?php
        for($x=1;$x<=$numeroSemanas;$x++)
		{echo'<option value="'.$x.'">'.$x.'</option>';}
		?>
          </select>
        </td>
    </tr>
    <tr>
      <td>Fecha de Clase</td>
      <td colspan="2"><input  name="fecha_clase" id="fecha_clase" size="15" maxlength="10" readonly="readonly" value="<?php echo date("Y-m-d");?>"/>
        <input type="button" name="boton1" id="boton1" value="..." /></td>
    </tr>
    <tr>
      <td>Hora de Clase</td>
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
      <td>Duracion de clase (hrs.)</td>
      <td colspan="2"><div id="div_horas_semana">...</div>
        
        </td>
    </tr>
    <tr>
      <td>Contenido</td>
      <td colspan="2"><label for="contenido"></label>
        <textarea name="contenido" id="contenido"></textarea></td>
    </tr>
    <tr>
      <td>Tipo Actividad</td>
      <td colspan="2"><label for="tipoActividad"></label>
        <label for="tipoActividad2"></label>
        <select name="tipoActividad" id="tipoActividad2">
          <option value="1">opcion 1</option>
          <option value="2">opcion2</option>
        </select></td>
    </tr>
    <tr>
      <td>Bibliografia</td>
      <td width="33%"><label for="bibliografia"></label>
        <input type="text" name="bibliografia" id="bibliografia" /></td>
      <td width="24%"><label for="bibliografiaPlanificaciones">(bibliografia registrada en planificaciones)<br />
      </label>
        <select name="bibliografiaPlanificaciones" id="bibliografiaPlanificaciones" onchange="TRASPASAR(this.value)">
        <option>Seleccione</option>
        <?php
		foreach($ARRAY_BIBLIOGRAFIA_PLANIFICACIONES as $n => $valor){
			echo'<option value="'.$valor.'">'.$valor.'</option>';
		}
        ?>
        </select> 
        <br /></td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="div_boton"></div>
<div id="div_info"></div>

<script type="text/javascript">
//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton1", "fecha_clase", "%Y-%m-%d");
    //]]>
</script>
</body>
</html>
<?php
$conexion_mysqli->close();
?>