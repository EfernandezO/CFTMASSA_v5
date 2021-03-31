<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("Bibioteca_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
///////////////////////////XAJAX////////////////////////////////
@require_once ("../../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("buscador_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_X_RUT");
$xajax->register(XAJAX_FUNCTION,"BUSCA_X_APELLIDO");
$xajax->register(XAJAX_FUNCTION,"BUSCA_X_ID");
$xajax->register(XAJAX_FUNCTION,"BUSCA_X_ID_SEDE");
////////////////////////////////////////////////////////////
if(isset($_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]))//elimino datos de session si es que los hay
{ unset($_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]);}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php");?>
<title>Seleccion Alumnos</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:45%;
	height:115px;
	z-index:1;
	left: 2%;
	top: 74;
}
#xajax_resultado {
	position:absolute;
	width:419px;
	height:141px;
	z-index:2;
	left: 626px;
	top: 82px;
}
a:link {
	color: #006699;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #006699;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #006699;
}
.Estilo3 {
	font-size: 14px;
	color: #0033CC;
}
.Estilo4 {
	color: #999999;
	font-weight: bold;
}
.Estilo6 {
	font-size: 12px
}
#link {
	text-align: right;
	padding-right: 10px;
}
-->
</style>
<?php $xajax->printJavascript(); ?> 
<script language="javascript">
function ASIGNAR(id)
{
	//alert('funcion asignar -> '+id);
	//document.getElementById('rut').value=rut;
	xajax_BUSCA_X_ID(id);
}
function pulsar(e) 
{
  tecla = (document.all) ? e.keyCode : e.which;
  return (tecla != 13);
}
function VERIFICAR()
{
	id_alumno=document.getElementById('hi_id_alumno').value;
	//alert(id_alumno);
	if(id_alumno>0)
	{
		document.frm.submit();
	}
}
function FOCO()
{
	document.getElementById ('apellido_P').focus ();
}
</script>
<script src="../../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/steel/steel.css">
<?php
if(isset($_GET["id_libro"]))
{
	$id_libro=$_GET["id_libro"];
	$action="enrutador.php";
	$dias_plazo=7;
	$fecha_devolucion=date("Y-m-d", strtotime("now +$dias_plazo days"));
}
else
{
	 $action="";
	 $id_libro="";
}
?>
<style type="text/css">
#apDiv2 {	position:absolute;
	width:32%;
	height:550px;
	z-index:2;
	left: 60%;
	top: 74px;
	overflow: auto;
}
</style>
</head>
<body onload="FOCO();">
<h1 id="banner">Biblioteca - Selecci&oacute;n de Alumno (Prestamo libro)</h1>
<div id="link"><br />
<a href="../../../menubiblio.php" class="button">Volver</a></div>
<div id="apDiv1">
<form action="<?php echo $action; ?>" method="post" name="frm" id="frm" onkeypress = "return pulsar(event)">
  <table width="100%" border="0">
  <thead>
    <tr>
      <th colspan="3">Busqueda Alumno
        <input name="id_libro" type="hidden" id="id_libro" value="<?php echo $id_libro;?>" />
     </th>
      </tr>
      </thead>
      <tbody>
    <tr>
      <td>Rut</td>
      <td>ID Alumno</td>
      <td>Sede</td>
    </tr>
    <tr>
      <td><input type="text" name="rut" id="rut"  onblur="xajax_BUSCA_X_RUT(this.value, document.getElementById('fsede').value);return false;"/></td>
      <td><input name="id_alumno" type="text" id="id_alumno" size="11" maxlength="10" onblur="xajax_BUSCA_X_ID_SEDE(this.value, document.getElementById('fsede').value);return false;"/></td>
      <td><?php
	  include("../../../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?></td>
    </tr>
    <tr>
      <td colspan="2">Apellido Paterno</td>
      <td>Apellido Materno</td>
    </tr>
    <tr>
      <td colspan="2"><input name="apellido_P" type="text" id="apellido_P" size="45" onblur="xajax_BUSCA_X_APELLIDO(this.value, document.getElementById('apellido_M').value, document.getElementById('fsede').value);return false;"/></td>
      <td><input name="apellido_M" type="text" id="apellido_M" size="45" onblur="xajax_BUSCA_X_APELLIDO( document.getElementById('apellido_P').value, this.value, document.getElementById('fsede').value);return false;"/></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><strong>&#9658; Fecha Devoluci&oacute;n</strong><br />
        <input  name="fecha_devolucion" id="fecha_devolucion" size="15" maxlength="10" readonly="true" value="<?php echo $fecha_devolucion;?>"/>
        <input type="button" name="boton1" id="boton1" value="..." /></td>
      <td><img src="../../../../BAses/Images/atras.png" width="28" height="29" alt="&lt;-" />*Fecha devolucion del Libro*</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><strong>&#9658;Alumno Seleccionada</strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><span class="Estilo4">RUN</span>
        <input name="hi_id_alumno" type="hidden" id="hi_id_alumno" value="0" />
        <input name="validador" type="hidden" id="validador" value="<?php echo md5("GDXT".date("d-m-Y"));?>" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><div class="Estilo3" id="div_rut">---</div></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><span class="Estilo4">Alumno</span></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3"><div class="Estilo3" id="div_alumno">---</div></td>
      </tr>
    <tr>
      <td colspan="2">A&ntilde;o Ingreso</td>
      <td >Situaci&oacute;n</td>
    </tr>
    <tr>
      <td colspan="2"><div class="Estilo3" id="div_ingreso">---</div></td>
      <td><div class="Estilo3" id="div_situacion">---</div></td>
    </tr>
    <tr>
      <td colspan="2" class="Estilo4">Carrera</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3"><div class="Estilo3" id="div_carreras">---</div></td>
    </tr>
    <tr>
      <td colspan="3">
        <div align="right">
          <input type="reset" name="button2" id="button2" value="Borrar" />
          &nbsp;
          <input type="button" name="button" id="button" value="Seleccionar"  onclick="VERIFICAR();"/>
          </div></td>
    </tr>
    </tbody>
  </table>
</form>
</div>
<div id="apDiv2">
  <div id="div_resultadoX">
    <table width="100%" border="1" align="right">
      <thead>
        <tr>
          <th>Resultado de Busqueda</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
      <tr>
        <td>&lt;----- ingreses datos de Busqueda...</td>
      </tr>
    </table>
  </div>
</div>
<script type="text/javascript">
//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton1", "fecha_devolucion", "%Y-%m-%d");

    //]]>
</script>
</body>
</html>