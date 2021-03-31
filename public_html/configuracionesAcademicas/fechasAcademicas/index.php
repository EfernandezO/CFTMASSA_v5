<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(false);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->FechasAcademicas_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>fechas Academicas</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:200px;
	height:88px;
	z-index:1;
	left: 51px;
	top: 94px;
}
#Layer3 {	position:absolute;
	width:218px;
	height:48px;
	z-index:2;
	left: 39px;
	top: 16px;
}
#Layer2 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:3;
	left: 5%;
	top: 89px;
}
#Layer4 {
	position:absolute;
	width:113px;
	height:30px;
	z-index:3;
	left: 265px;
	top: 18px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: underline;
}
a:active {
	text-decoration: none;
}
#apDiv1 {
	position:absolute;
	width:60%;
	height:36px;
	z-index:1;
	left: 5%;
	top: 338px;
}
#apDiv2 {
	position:absolute;
	width:30%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 128px;
}
#apDiv3 {
	position:absolute;
	width:23%;
	height:29px;
	z-index:3;
	left: 50%;
	top: 183px;
	text-align: center;
}
-->
</style>
<script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
 <script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">
<script language="javascript">
function CONFIRMAR()
{
	continuar=true;
	
	fechaInicio=document.getElementById('fechaInicio').value;
	fechaFin=document.getElementById('fechaFin').value;
	
	if((fechaInicio=="")||(fechaInicio==" "))
	{
		continuar=false;
		alert('Ingrese fecha Inicio');
	}
	if((fechaFin=="")||(fechaFin==" "))
	{
		continuar=false;
		alert('Ingrese fecha Fin');
	}
	
	if(continuar)
	{
		c=confirm('Seguro(a) desea Guardar los Cambios...?');
		if(c)
		{document.getElementById('frm').submit();}
	}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Fechas Academicas</h1>
<div id="link"><br />
<a href="../index.php" class="button">Volver al Men&uacute; </a>
</div>
<div id="apDiv1">
<table width="60%" align="left">
<thead>
	<tr>
	<th colspan="5">Valores Carrera</th>
    </tr>
    <tr>
    <td>N</td>
    <td>semestre</td>
    <td>año</td>
    <td>Fecha Inicio</td>
    <td>Fecha Fin</td>
</tr>
</thead>
<tbody>
<?php
$continuar=true;
//----------------------------------------------//
require("../../../funciones/funciones_sistema.php");

$mesActual=date("m");
$semestreActual=1;
if($mesActual>=8){$semestreActual=2;}

if($continuar)
{
	require("../../../funciones/conexion_v2.php");
	
	$cons="SELECT * FROM fechasAcademicas";
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_registros=$sqli->num_rows;
	if($num_registros>0)
	{
		$aux=0;
		while($DC=$sqli->fetch_assoc())
		{
			$aux++;
			$aux_semestre=$DC["semestre"];
			$aux_year=$DC["year"];
			$aux_fechaInicio=$DC["fechaInicio"];
			$aux_fechaFin=$DC["fechaFin"];
			echo'<tr>
					<td>'.$aux.'</td>
					<td>'.$aux_semestre.'</td>
					<td>'.$aux_year.'</td>
					<td>'.$aux_fechaInicio.'</td>
					<td>'.$aux_fechaFin.'</td>
				</tr>';
		}
	}
	else
	{ echo'<tr><td colspan="5">Sin Registros</td></tr>';}
	$sqli->free();
	$conexion_mysqli->close();
	@mysql_close($conexion);
}
?>
</tbody>
</table>
</div>
<div id="apDiv2">
<form action="grabaFechasAcademicas.php" method="post" id="frm">
  <table width="100%" border="1" align="left">
  <thead>
    <tr>
      <th colspan="2">Ingrese las Fechas del año academico</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>Semestre</td>
      <td><?php echo CAMPO_SELECCION("semestre","semestre", $semestreActual);?></td>
    </tr>
    <tr>
      <td>A&ntilde;o</td>
      <td><?php echo CAMPO_SELECCION("year","year",date("Y"));?></td>
    </tr>
    <tr>
      <td width="45%">Fecha Inicio</td>
      <td width="55%"><label for="fechaInicio"></label>
        <input  name="fechaInicio" id="fechaInicio" size="15" maxlength="10" readonly="readonly" value="<?php echo date("Y-m-d");?>"/>
        <input type="button" name="boton1" id="boton1" value="..." /></td>
    </tr>
    <tr>
      <td>Fecha Fin</td>
      <td><label for="fechaFin"></label>
        <input  name="fechaFin" id="fechaFin" size="15" maxlength="10" readonly="readonly" value="<?php echo date("Y-m-d");?>"/>
        <input type="button" name="boton2" id="boton2" value="..." /></td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="apDiv3"><a href="#" class="button_G" onclick="CONFIRMAR();">Guardar Cambios</a></div>
<script type="text/javascript">
//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton1", "fechaInicio", "%Y-%m-%d");
	   cal.manageFields("boton2", "fechaFin", "%Y-%m-%d");

    //]]>
</script>
</body>
</html>