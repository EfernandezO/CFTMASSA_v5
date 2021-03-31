<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Restablecer_clave_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<title>Restablecer Clave</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 75px;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	c=confirm('Seguro desea Restabler la clave de acceso...¿?');
	if(c){window.location="restablece_clave_2.php";}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Restablece Clave Acceso</h1>
<div id="apDiv1">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th>Restablecer Clave de Alumno</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td align="center"><a href="#" class="button_R" onclick="CONFIRMAR();">Seguro desea Restablecer clave de acceso ¿?</a></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    </tbody>
  </table>
</div>
</body>
</html>