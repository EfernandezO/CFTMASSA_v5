<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Gestion de Encuestas_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<title>Ingreso de Nueva Carrera</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 115px;
}
#Layer2 {
	position:absolute;
	width:437px;
	height:115px;
	z-index:2;
	left: 40px;
	top: 288px;
}
#Layer3 {
	position:absolute;
	width:97px;
	height:25px;
	z-index:2;
	left: 387px;
	top: 79px;
}
.Estilo2 {color: #0080C0}
#Layer4 {
	position:absolute;
	width:281px;
	height:67px;
	z-index:3;
	top: 15px;
	left: 47px;
}
#Layer5 {
	position:absolute;
	width:94px;
	height:20px;
	z-index:4;
	left: 286px;
	top: 81px;
}
-->
</style>
<script language="javascript">
function Confirmar()
{
	c=confirm('¿Esta Seguro(a) que Desea Guardar está encuesta?');
	if(c==true)
	{
		document.frm.submit();
	}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Gesti&oacute;n Encuesta</h1>
<div id="link"><br />
<a href="../../gestion_encuesta.php" class="button">Volver</a></div>
<div id="Layer1">
  <form action="ingreso_encuesta_2.php" method="post" name="frm" id="frm">
  <table width="50%" border="0" align="center">
  <thead>
    <tr>
      <th colspan="2" ><div align="center"><strong>Ingreso de Nueva Encuesta</strong></div></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="149" ><strong>Nombre de Encuesta </strong></td>
      <td width="307" >
        <input name="nombre_encuesta" type="text" id="nombre_encuesta" size="40" /></td>
    </tr>
    <tr>
      <td ><strong> Descripcion</strong></td>
      <td ><textarea name="descripcion_encuesta" cols="30" id="descripcion_encuesta"></textarea></td>
      </tr>
    <tr>
      <td colspan="2" align="center">&nbsp;</td>
      </tr>
    <tr>
      <td colspan="2" ><div align="center">
        <input type="button" name="Submit" value="Guardar" onclick="Confirmar();" />
        </div></td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
</body>
</html>
