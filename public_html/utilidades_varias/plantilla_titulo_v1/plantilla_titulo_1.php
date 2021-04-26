<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	

if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	$alumno_activo=$_SESSION["SELECTOR_ALUMNO"]["ACTIVO"];
}
else
{ $alumno_activo=false;}

$continuar_1=false;
$continuar_2=false;

if($alumno_activo)
{
	require("../../../funciones/conexion_v2.php");
	$situacion_academica=strtoupper($_SESSION["SELECTOR_ALUMNO"]["situacion"]);
	
	if($situacion_academica=="T")
	{ $continuar_1=true; if(DEBUG){ echo"Alumno Titulado, cumple condicion 1<br>";}}

	
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$alumno=$_SESSION["SELECTOR_ALUMNO"]["nombre"]." ".$_SESSION["SELECTOR_ALUMNO"]["apellido"];
	$carrera=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
	
	$cons="SELECT * FROM proceso_titulacion WHERE id_alumno='$id_alumno' LIMIT 1";
	$sqli=$conexion_mysqli->query($cons);
	$num_registros=$sqli->num_rows;
	if($num_registros>0){ $continuar_2=true;}
	
	$PT=$sqli->fetch_assoc();
	
		$titulo_fecha_emision=$PT["titulo_fecha_emision"];
		$nombre_titulo=$PT["nombre_titulo"];
	
	$sqli->free();
	mysql_close($conexion);
	$conexion_mysqli->close();
	
}

$fecha_actual=date("Y-m-d");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>Plantillas de Impresion | titulo</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 76px;
}
a:link {
	color: #069;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #069;
}
a:hover {
	text-decoration: underline;
	color: #F00;
}
a:active {
	text-decoration: none;
	color: #069;
}
</style>
<script language="javascript">
function CONTINUAR()
{
	<?php if($continuar_1 and $continuar_2){?>
	document.getElementById('frm').submit();
	<?php }else{?>
	alert('Alumno no es titulado o no cuenta con proceso de titulacion, verificar para continuar...');
	<?php }?>
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Plantillas de Impresi&oacute;n Titulo</h1>
<div id="link"><br />
<a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver al Menu</a>
  <div id="apDiv1">
  <form action="plantilla_titulo_2.php" method="post" id="frm" target="_blank">
    <table width="55%" border="1" align="center">
    <thead>
      <tr>
        <th colspan="2">Texto para Lomos de Archivadores</th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td>Alumno</td>
        <td><label for="alumno"></label>
          <input name="alumno" type="text" id="alumno" value="<?php echo $alumno?>" size="60" />          <br /></td>
      </tr>
      <tr>
        <td>fecha emision de titulo</td>
        <td><label for="fecha_emision_titulo"></label>
          <input type="text" name="fecha_emision_titulo" id="fecha_emision_titulo" value="<?php echo $titulo_fecha_emision;?>"/></td>
      </tr>
      <tr>
        <td>Carrera</td>
        <td><label for="carrera"></label>
          <input name="carrera" type="text" id="carrera" value="<?php echo $nombre_titulo;?>" size="60"/></td>
      </tr>
      <tr>
        <td>Fecha titulacion</td>
        <td><label for="fecha_titulacion"></label>
          <input type="text" name="fecha_titulacion" id="fecha_titulacion" value="<?php echo "2014-05-05";?>"/></td>
      </tr>
      <tr>
        <td>sube/baja textos mm.(Y auxiliar)</td>
        <td><label for="Y"></label>
          <select name="Y" id="Y">
            <option value="10">10</option>
            <option value="9">9</option>
            <option value="8">8</option>
            <option value="7">7</option>
            <option value="6">6</option>
            <option value="5">5</option>
            <option value="4">4</option>
            <option value="3">3</option>
            <option value="2">2</option>
            <option value="1">1</option>
            <option value="0" selected="selected">0</option>
            <option value="-1">-1</option>
            <option value="-2">-2</option>
            <option value="-3">-3</option>
            <option value="-4">-4</option>
            <option value="-5">-5</option>
            <option value="-6">-6</option>
            <option value="-7">-7</option>
            <option value="-8">-8</option>
            <option value="-9">-9</option>
            <option value="-10">-10</option>
          </select></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="button" name="button" id="button" value="continuar"  onclick="CONTINUAR();"/></td>
      </tr>
      </tbody>
    </table>
    </form>
  </div>
</div>
</body>
</html>