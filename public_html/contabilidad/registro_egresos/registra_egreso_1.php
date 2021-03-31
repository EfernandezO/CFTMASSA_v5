<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("registra_egresos");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//-----------------------------------------//	
 //////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("registra_egresos_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"SELECCION_FORMULARIO");
$xajax->register(XAJAX_FUNCTION,"BUSCAR_FACTURAS");
$xajax->register(XAJAX_FUNCTION,"CARGAR_SALDO_FACTURA");
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");
$xajax->register(XAJAX_FUNCTION,"UTILIZAR_RUT");
$xajax->register(XAJAX_FUNCTION,"COMPROBANTE_EGRESO_ELIGE_TIPO");
$xajax->register(XAJAX_FUNCTION,"BUSCA_PROVEEDOR");
//---------------------------------------------------------------//
$fecha_actual=date("Y-m-d");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Registro Egresos</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">
    <!--INICIO MENU HORIZONTAL-->
    <script type="text/javascript" src="../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/menu_horizontal/ddsmoothmenu.css"/>
 
<script type="text/javascript" src="../../libreria_publica/menu_horizontal/ddsmoothmenu.js">

/***********************************************
* Smooth Navigational Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>

<script type="text/javascript">

ddsmoothmenu.init({
	mainmenuid: "smoothmenu1", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu', //class added to menu's outer DIV
	//customtheme: ["#1c5a80", "#18374a"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

</script>
<!--FIN MENU HORIZONTAL-->
<!--INICIO LIGHTBOX EVOLUTION-->
 
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
 <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox(); 
    });
  </script>
 <!--FIN LIGHTBOX EVOLUTION--> 
 <script language="javascript">
 function CONFIRMAR()
 {
	 c=confirm('Seguro(a) Desea Realizar esta Operacion');
	 if(c){ document.getElementById('frm').submit();}
 }
 </script>
<?php $xajax->printJavascript(); ?> 
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:112px;
	z-index:1;
	left: 5%;
	top: 105px;
}
#div_formulario {
	position:absolute;
	width:90%;
	height:160px;
	z-index:2;
	left: 5%;
	top: 233px;
}
</style>
</head>
<body onload="xajax_SELECCION_FORMULARIO('boleta')">
<h1 id="banner">Administrador- Registro de Egresos</h1>
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Proveedores</a>
	<ul>
    	 <li><a href="../proveedores/nvo_proveedor/nvo_proveedor_1.php?modo=modal&lightbox[iframe]=true&lightbox[width]=610&lightbox[height]=600"  class="lightbox">Nuevo Proveedor</a></li>
         <li><a href="../proveedores/listar_proveedores.php">Ir a Proveedores</a></li>
    </ul>
</li>
    <li><a href="#">Facturas</a>
    <ul>
        <li><a href="#"><a href="../facturas/registro/ver/ver_factura.php">Ir a Facturas</a></a> <li>
        <li><a href="../facturas/registro/nva_factura/nva_factura_1.php?modo=modal&lightbox[iframe]=true&lightbox[width]=800&lightbox[height]=700"  class="lightbox">Agregar Factura</a></li>
    </ul>
    </li>
   <li><a href="#">Comprobantes Egreso</a>
    <ul>
        <li><a href="comprobanteEgreso/listar/listar_comprobantes_egreso.php" target="_blank">Ver Listado</a><li>
 
    </ul>
    </li>
    
    </li>
    <li><a href="../index.php">Menu Finanzas</a></li>
</ul>
<br style="clear: left" />
</div>
<form action="registra_egreso_2.php" method="post" id="frm">
<div id="apDiv1">
  <table width="60%" border="1" align="left">
  <thead>
    <tr>
      <th colspan="2">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="48%">Fecha</td>
      <td width="52%"><input name="fecha" type="text" id="fecha" size="11" maxlength="10" value="<?php echo $fecha_actual;?>" readonly="readonly"/>
        <input type="button" name="boton" id="boton" value="..."/></td>
    </tr>
    <tr>
      <td>Tipo Documento</td>
      <td><label for="tipo_documento"></label>
        <select name="tipo_documento" id="tipo_documento" onchange="xajax_SELECCION_FORMULARIO(this.value)">
          <option value="boleta">boleta</option>
          <option value="factura">factura</option>
          <option value="comprobante_egreso">comprobante_egreso</option>
          <option value="boleta_honorario">boleta_honorario</option>
        </select></td>
    </tr>
    </tbody>
  </table>
</div>
<div id="div_formulario">
</div>
</form>
  <script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton", "fecha", "%Y-%m-%d");
		//cargarMovimientos();
    //]]>
  </script>
</body>
</html>