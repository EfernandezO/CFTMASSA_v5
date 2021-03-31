<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Flujo_de_caja->tipo_1_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
require("../../../funciones/funciones_sistema.php");
$year_actual=date("Y");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Flujo de Caja</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>

<style type="text/css">
.Estilo1 {font-size: 12px}
#Layer1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 100px;
}
#apDiv1 {
	position:absolute;
	width:40%;
	height:115px;
	z-index:2;
	left: 30%;
	top: 359px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:33px;
	z-index:2;
	left: 30%;
	top: 245px;
	text-align:center;
}
#apDiv3 {
	position:absolute;
	width:40%;
	height:36px;
	z-index:3;
	left: 30%;
	top: 326px;
	text-align: center;
}
</style>
<script language="javascript" type="text/javascript">
function CONFIRMAR()
{
	c=confirm('Seguro(a) desea generar este informa..?');
	if(c){document.getElementById('frm').submit();}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Flujo de Caja</h1>
<div id="link"><br />
<a href="../index.php" class="button">Volver al Menu</a></div>
<div id="Layer1">
  <form action="flujo_caja_2.php" method="post" name="frm" id="frm">
    <table width="50%" border="1" align="center">
      <caption>
      </caption>
      <thead>
        <tr>
          <th colspan="6"><span class="Estilo1">Busqueda de Alumnos </span></th>
        </tr>
      </thead>
      <tbody>
        <tr class="odd">
          <td><span class="Estilo1">A&ntilde;o</span></td>
          <td colspan="5"><?php  echo CAMPO_SELECCION("year","year",$year_actual,false);?></td>
        </tr>
        <tr class="odd">
          <td>&nbsp;</td>
          <td colspan="5">&nbsp;</td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<div id="apDiv2"><a href="#" class="button_G" onclick="CONFIRMAR();">Generar Informe</a></div>
<div id="apDiv3">genera un Flujo de Caja consolidado y por sede<br />
  considerando ingresos y cuotas x cobrar
</div>
</body>
</html>