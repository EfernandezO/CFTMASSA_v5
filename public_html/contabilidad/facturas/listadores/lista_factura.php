<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("gestion_facturas_v1.2");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<title>Listador - facturas</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 119px;
}
-->
</style>
<script src="../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/steel/steel.css">
<style type="text/css">
#apDiv2 {
	position:absolute;
	width:40%;
	height:39px;
	z-index:2;
	left: 30%;
	top: 373px;
	text-align: center;
}
</style>
</head>
<body>
<h1 id="banner">Administrador - Listador Facturas</h1>

<div id="link">
  <div id="apDiv1">
  <form action="listador_factura_2.php" method="post" name="frm" id="frm">
    <table width="50%" border="0" align="center">
    <thead>
      <tr>
        <th colspan="3" ><div align="left"><strong>Parametros de Busqueda</strong></div></th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td >SEDE</td>
        <td colspan="2" ><?php
	  include("../../../../funciones/funcion.php");
	  echo selector_sede("fsede","",true); 
	  ?></td>
      </tr>
      <tr>
        <td ><div align="left">Movimiento</div></td>
        <td colspan="2" ><label for="movimiento"></label>
          <select name="movimiento" id="movimiento">
            <option value="T">Todos</option>
            <option value="I">Ingreso</option>
            <option value="E" selected="selected">Egreso</option>
          </select></td>
      </tr>
      <tr>
        <td width="32%" ><div align="left">CONDICION</div></td>
        <td colspan="2" ><select name="condicion" id="condicion">
        
          <option value="pendiente" selected="selected">Pendiente</option>
          <option value="cancelada">Cancelada</option>
          <option value="abonada">abonada</option>
          <option value="todas">Todas</option>
        </select></td>
      </tr>
      <tr>
        <td rowspan="2" ><div align="left">PERIODO</div></td>
        <td width="22%" ><em>Fecha inicio</em></td>
        <td width="46%" ><input  name="fecha_inicio" id="fecha_inicio" size="15" maxlength="10" readonly="true" value="<?php echo date("Y-m-d");?>"/>
          <input type="button" name="boton1" id="boton1" value="..." /></td>
      </tr>
      <tr>
        <td ><em>Fecha Fin</em></td>
        <td ><input  name="fecha_fin" id="fecha_fin" size="15" maxlength="10" readonly="true" value="<?php echo date("Y-m-d");?>"/>
          <input type="button" name="boton2" id="boton2" value="..." /></td>
      </tr>
      <tr>
        <td colspan="3" ><div align="right">
          <input type="submit" name="continuar" id="continuar" value="continuar" />
        </div></td>
      </tr>
      </tbody>
    </table>
    </form>
  </div><br />

<a href="../registro/ver/ver_factura.php" class="button">Volver a Facturas</a></div>
<div id="apDiv2">Genera Listado pdf de facturas seg&uacute;n los <br />
  parametros especificados en el formulario.
</div>
</body>
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
</html>