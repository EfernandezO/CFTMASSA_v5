<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_alumnos_titulados_formato_sies_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Sies Formato</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:91px;
	z-index:1;
	left: 5%;
	top: 109px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:38px;
	z-index:2;
	left: 5%;
	top: 287px;
	text-align: center;
}
-->
</style>
</head>
<body>
<h1 id="banner">Administrador - Alumnos Titulados FORMATO SIES.XLS</h1>
<div id="link">
  <div align="right"><br />
<a href="../../Alumnos/menualumnos.php" class="button">Volver al Menu</a></div>
</div>
<div id="apDiv1">
<form action="alumnos_titulados_formato_sies.php" method="post" name="frm" id="frm">
  <table width="50%" border="0" align="center">
  <thead>
    <tr>
      <th colspan="4">Parametros para Busqueda</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="34%">Sede</td>
      <td colspan="3">
	  <?php
	  include("../../../funciones/funciones_sistema.php");
	  echo CAMPO_SELECCION("sede","sede","",true);
	  ?>
      </td>
    </tr>
    <tr>
      <td>A&ntilde;o Obtencion titulo (entre)</td>
      <td width="33%"><input  name="fecha_inicio" id="fecha_inicio" size="15" maxlength="10" readonly="readonly" value="<?php echo date("Y-m-d");?>"/>
        <input type="button" name="boton1" id="boton1" value="..." /></td>
      <td width="4%">Y</td>
      <td width="29%"><input  name="fecha_fin" id="fecha_fin" size="15" maxlength="10" readonly="readonly" value="<?php echo date("Y-m-d");?>"/>
        <input type="button" name="boton2" id="boton2" value="..." /></td>
    </tr>
    <tr>
      <td colspan="4"><div align="right">
        <input type="submit" name="button" id="button" value="Consultar" />
      </div></td>
      </tr>
      </tbody>
  </table>
  </form>
</div>
<div id="apDiv2">Genera Archivo .XLS(excel) Con los Datos de Todos Los Alumnos<br />
Titulados por Sede, en base al Formato SIES</div>
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