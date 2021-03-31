<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_caja_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Detalle Pagos</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">

<?php
//var_dump($_SESSION);
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("procesa_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"busca_pagos");
$xajax->register(XAJAX_FUNCTION,"BUSCA_EGRESOS");
////////////////////////////////////////////
?>
<?php $xajax->printJavascript(); ?> 
 <script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
 <script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">
<link rel="stylesheet" type="text/css" href="../../CSS/estilos_sistema.css"/>
<script type="text/javascript">
<!--
function muestra_cargando(){
      xajax.dom.create("capa_actualiza","div", "cargando");
      xajax.$('cargando').innerHTML='<img src="../../BAses/Images/massa_loading.gif" />';
   }
   function oculta_cargando(){
      //alert("cargado");
   }
   
   xajax.callback.global.onResponseDelay = muestra_cargando;
   xajax.callback.global.onComplete = oculta_cargando;

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function exportar_excel(valor)
{
	//alert(valor);
	sede=document.getElementById('fsede').value;
	fecha_inicio=document.getElementById('fecha_inicio').value;
	window.location="genera_excel_v2.php?html="+valor+"&sede="+sede+"&fecha_ini="+fecha_inicio;
}
function exportar_pdf(valor)
{
	//alert(valor);
	url="genera_pdf.php?html="+valor;
	//window.location="genera_pdf.php?html="+valor;
	window.open(url,'genera PDF');
}
function resetiar()
{
	document.getElementById('fecha_inicio').value='<?php echo date("Y-m-d");?>';
	document.getElementById('fecha_fin').value='<?php echo date("Y-m-d");?>';
}
function confirmar_saldo_anterior()
{
	c=confirm('Seguro(a) Desea Registrar este valor como -> saldo anterior');
	fecha_ini=document.getElementById('fecha_inicio').value;
	valor_saldo=document.getElementById('saldo').value;
	sede=document.getElementById('fsede').value;
	if(c)
	{ 
		alert('registrando...');
		xajax_REGISTRA_SALDO_ANTERIOR(fecha_ini, valor_saldo, sede);
		//xajax_busca_pagos(document.getElementById('fecha_inicio').value,document.getElementById('fecha_fin').value, document.getElementById('fsede').value, document.getElementById('tipo_doc').value);
	}
}
//-->
</script>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:555px;
	height:103px;
	z-index:2;
	left: 2%;
	top: 68px;
}
#Layer2 {
	position:absolute;
	width:103px;
	height:15px;
	z-index:3;
	left: 556px;
	top: 74px;
}
#saldo_anterior {
	position:absolute;
	width:207px;
	height:88px;
	z-index:3;
	left: 588px;
	top: 68px;
	visibility: hidden;
}
#cheques {
	position:absolute;
	width:382px;
	height:115px;
	z-index:4;
	left: 568px;
	top: 123px;
}
.Estilo1 {
	font-size: 10px
}
#apDiv1 {
	position:absolute;
	width:96%;
	height:43px;
	z-index:5;
	left: 2%;
	top: 204px;
}
#apDiv1 #cheque_detalle {
	border: thin solid #FF0000;
}
#apDiv1 #div_resultados {
}
#apDiv1 #div_item {
	border: thin solid #0000FF;
	margin-top: 20px;
}
#apDiv1 #div_item_egreso {
	border: thin solid #00FF00;
	margin-top: 20px;
}
-->
</style>
</head>

<body onload="MM_preloadImages('../../BAses/Images/massa_loading.gif')">
<h1 id="banner">Administrador - Informe Caja </h1>
 <?php
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	switch($privilegio)
	{
		case"inspeccion":
			$url="../../Administrador/menu_inspeccion/index.php";
			break;
		default:
			$url="../index.php";	
	}
?>
<div id="link">
  <div id="saldo_anterior"></div><br />
<a href="<?php echo $url;?>" class="button">Volver al Men&uacute;</a><br />
<br />
<a href="#" class="button" onclick="window.print();">Imprimir </a></div>
<div id="Layer1">
  <table width="552" border="0">
    <tr>
      <td colspan="4" bgcolor="#e5e5e5">-<strong>&gt;Resumen Pagos Reales </strong></td>
    </tr>
    <tr>
      <td width="78">Fecha inicio </td>
      <td width="176"><input  name="fecha_inicio" id="fecha_inicio" size="15" maxlength="10" readonly="true" value="<?php echo date("Y-m-d");?>"/>
        <input type="button" name="boton1" id="boton1" value="..." /></td>
      <td width="105">&nbsp;</td>
      <td width="175">&nbsp;</td>
    </tr>
    <tr>
      <td>Sede</td>
      <td><?php
	  require("../../../funciones/funciones_sistema.php");
	  echo CAMPO_SELECCION("fsede","sedexprivilegio");
	  ?></td>
      <td>Documento</td>
      <td><select name="tipo_doc" id="tipo_doc">
	  <?php echo'<option value="todos">Todos</option>'; ?>
   </select>      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><a href="genera_excel.php"></a></td>
      <td><input name="btn_resetiar" type="button" id="btn_resetiar"  onclick="resetiar();" value="Reset"/>
      <input name="boton" type="button" id="boton" onclick="xajax_busca_pagos(document.getElementById('fecha_inicio').value,document.getElementById('fecha_inicio').value, document.getElementById('fsede').value, document.getElementById('tipo_doc').value);return false;" value="Ver"/></td>
    </tr>
    <tr>
      <td colspan="4" bgcolor="#f5f5f5">&nbsp;</td>
    </tr>
  </table>
</div>

<div id="apDiv1">
  
    <table width="100%" border="0">
      <tr>
        <td><div id="div_resultados">
          <div align="center">--->  </div>
    <div id="capa_actualiza"></div>
  </div></td>
      </tr>
      <tr>
        <td><div class="Estilo1" id="cheque_detalle"></div></td>
      </tr>
      <tr>
        <td><div id="div_total_2"></div></td>
      </tr>
      <tr>
        <td><div id="div_item"></div></td>
      </tr>
    </table>
    <div class="saltopagina"></div>
    <table width="100%" border="0">
      <tr>
        <td>
        	<div id="div_egresos"></div>
        </td>
      </tr>
      <tr>
        <td><div class="Estilo1" id="div_item_egreso"></div></td>
      </tr>
      <tr>
        <td><div class="Estilo1" id="cheque_egreso_detalle"></div></td>
      </tr>
      <tr>
        <td></td>
      </tr>
    </table>
  </div>

<script type="text/javascript">
//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton1", "fecha_inicio", "%Y-%m-%d");
	   cal.manageFields("boton2", "fecha_fin", "%Y-%m-%d");

    //]]>
</script>
</body>
</html>