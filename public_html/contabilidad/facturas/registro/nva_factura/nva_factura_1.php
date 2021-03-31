<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("gestion_facturas_v1.2");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
////////////////////necesario para Xajax///////////////////////////
@require_once ("../../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("factura_server.php");
$xajax->configure('javascript URI','../../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_PROVEEDOR");
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");
$xajax->register(XAJAX_FUNCTION,"REVISAR_OC");
////////////////-------------------*********************---------//
if(isset($_GET["modo"])){ $modo=$_GET["modo"];}
else{$modo="normal";}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php");?>
<title>Factura | creacion</title>
<?php $xajax->printJavascript(); ?> 
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css">

<script src="../../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
 <script src="../../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/steel/steel.css">
<style type="text/css">
#div_proveedor {
	position:absolute;
	width:30%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 62px;
}
#div_debug {
	position:absolute;
	width:30%;
	height:47px;
	z-index:2;
	left: 5%;
	top: 237px;
	overflow: auto;
}
#div_item {
	position:absolute;
	width:90%;
	height:115px;
	z-index:3;
	left: 5%;
	top: 287px;
}
#div_solicitante {
	position:absolute;
	width:30%;
	height:66px;
	z-index:4;
	left: 40%;
	top: 62px;
}
</style>
<script language="javascript">
function ACTUALIZAR_TOTAL()
{
	valores=document.getElementsByName('item_valor[]');
	numero_elementos=valores.length;
	//alert(numero_elementos);
	
	total=0;
	for(i=1;i<=numero_elementos;i++)
	{
		
		id_valor="valor_"+i;
		id_cantidad="cantidad_"+i;
		aux_valor=document.getElementById(id_valor).value;
		aux_cantidad=document.getElementById(id_cantidad).value;
		
		total=total+((parseFloat(aux_valor))*(parseFloat(aux_cantidad)));
	}
	document.getElementById('TOTAL').innerHTML="<strong>$"+total+"</strong>";
}
</script>
</head>
<body>
<h1 id="banner">Finanzas - Factura</h1>
<div id="link"><br />
<a href="../ver/ver_factura.php" class="button">Volver a Facturas</a></div>
<form action="nva_factura_2.php" method="post" enctype="multipart/form-data" id="frm">
  <div id="div_proveedor">
  <table width="100%" border="1" id="proveedor">
  <thead>
  	<tr>
      <tH colspan="2">Proveedores
        <input name="proveedor_id" type="hidden" id="proveedor_id" value="0" /></tH	>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td width="41%">Rut</td>
      <td width="59%"><label for="proveedor_rut"></label>
        <input type="text" name="proveedor_rut" id="proveedor_rut"  onblur="xajax_BUSCA_PROVEEDOR(this.value, document.getElementById('proveedor_razon_social').value, document.getElementById('proveedor_direccion').value, document.getElementById('proveedor_ciudad').value);return false;"/></td>
    </tr>
    <tr>
      <td>Razon Social</td>
      <td><label for="proveedor_razon_social"></label>
        <input type="text" name="proveedor_razon_social" id="proveedor_razon_social" /></td>
    </tr>
    <tr>
      <td>Direccion</td>
      <td><label for="proveedor_direccion"></label>
        <input type="text" name="proveedor_direccion" id="proveedor_direccion" /></td>
    </tr>
    <tr>
      <td>Ciudad</td>
      <td><label for="proveedor_ciudad"></label>
        <input type="text" name="proveedor_ciudad" id="proveedor_ciudad" /></td>
    </tr>
    </tbody>
  </table>
</div>
<div id="div_debug">....</div>
<div id="div_item">
   <table width="100%" border="0" align="center">
  <thead>
    <tr>
      <th colspan="4" ><span class="Estilo3">Agregar Factura</span></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td ><span class="Estilo1">Sede</span></td>
      <td colspan="3" ><?php
	  include("../../../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?></td>
    </tr>
    <tr>
      <td width="34%" ><span class="Estilo1">Numero Factura</span></td>
      <td colspan="3" ><input type="text" name="cod_factura" id="cod_factura" /></td>
    </tr>
    <tr>
      <td >Comentario</td>
      <td colspan="3" ><input name="comentario" type="text" id="comentario" value="" size="40" /></td>
    </tr>
    <tr>
      <td ><span class="Estilo1">Fecha Ingreso</span></td>
      <td width="25%" ><input  name="fecha_ingreso" id="fecha_ingreso" size="15" maxlength="10" readonly="true" value="<?php echo date("Y-m-d");?>"/>
        <input type="button" name="boton1" id="boton1" value="..." /></td>
      <td width="20%" ><span class="Estilo1">Fecha Vencimiento</span></td>
      <td width="21%" ><input  name="fecha_vencimiento" id="fecha_vencimiento" size="15" maxlength="10" readonly="true" value="<?php echo date("Y-m-d");?>"/>
        <input type="button" name="boton2" id="boton2" value="..." /></td>
    </tr>
    <tr>
      <td >&nbsp;</td>
      <td colspan="3" >&nbsp;</td>
    </tr>
    <tr>
      <td >Movimiento</td>
      <td colspan="3" ><label for="movimiento"></label>
        <select name="movimiento" id="movimiento">
          <option value="I">Ingreso</option>
          <option value="E" selected="selected">Egreso</option>
        </select></td>
    </tr>
    <tr>
      <td ><span class="Estilo1">Condicion
        <input name="modo" type="hidden" id="modo" value="<?php echo $modo;?>" />
      </span></td>
      <td colspan="3" ><select name="condicion" id="condicion">
        <option value="pendiente" selected="selected">Pendiente</option>
      </select>      </td>
    </tr>
    </tbody>
  </table>
  <div id="div_total"><table width="100%" border="1">
  <tr>
    <td width="84%"><strong>TOTAL</strong></td>
    <td width="16%" id="TOTAL"><input type="text" name="valor" id="valor" /></td>
  </tr>
</table>
</div>
  <input name="Continuar" type="button" value="Grabar Factura...?"  onclick="xajax_VERIFICAR(xajax.getFormValues('frm')); return false;"/>
</div>

<div id="div_solicitante">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="2">Utilizar Orden de Compra</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="45%">Orden de Compra</td>
      <td width="55%"><label for="orden_compra"></label>
        <div id="oc_id">
        ...
          <input name="orden_compra" type="hidden" id="orden_compra" value="0" />
        </div>
        </td>
    </tr>
    <tr>
      <td>Descripcion</td>
      <td><div id="oc_descripcion">...</div></td>
    </tr>
    </tbody>
  </table>
</div>
</form>
<script type="text/javascript">
//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton1", "fecha_ingreso", "%Y-%m-%d");
	   cal.manageFields("boton2", "fecha_vencimiento", "%Y-%m-%d");

    //]]>
</script>
</body>
</html>