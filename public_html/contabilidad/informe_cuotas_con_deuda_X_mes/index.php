<?php 
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
//-----------------------------------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion_2.php");?>
<title>Cuotas X Mes</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>

<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:494px;
	height:219px;
	z-index:1;
	left: 52px;
	top: 98px;
}
#link {
	text-align: right;
	padding-right: 10px;
}
#apDiv2 {
	position:absolute;
	left:5%;
	top:107px;
	width:90%;
	height:169px;
	z-index:1;
}
#apDiv3 {
	position:absolute;
	width:200px;
	height:33px;
	z-index:2;
	left: 603px;
	top: 128px;
}
#apDiv4 {
	position:absolute;
	width:40%;
	height:38px;
	z-index:2;
	left: 30%;
	top: 367px;
	text-align: center;
}
-->
</style>
</head>
<?php
$fecha_actual=date("Y-m-d");
require("../../../funciones/funciones_sistema.php");
?>
<body>
<h1 id="banner">Administrador - Cuotas X Mes</h1>
<?php
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	switch($privilegio)
	{
		case"admi_total":
			$url_menu="../index.php";
			break;
		case"inspeccion":
			$url_menu="../../Administrador/menu_inspeccion/index.php";
			break;
			
	}
?>
<div id="link"><br />
<a href="<?php echo $url_menu;?>" class="button">Volver al menu</a></div>
<div id="apDiv2">
<form action="cuota_deuda_X_mes.php" method="post" name="frm" id="frm">
  <table width="60%" border="0" align="center">
  <thead>
    <tr>
      <th colspan="3"><strong>Parametros de Busqueda</strong></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="29%">Sede Alumno</td>
      <td width="71%" colspan="2"><span class="Estilo2 Estilo2">
        <?php echo CAMPO_SELECCION("sede","sede","");?>
      </span></td>
    </tr>
    <tr>
      <td>Carrera Alumno</td>
      <td colspan="2"><?php echo CAMPO_SELECCION("carreras", "carreras", false, true);?></td>
    </tr>
    <tr>
      <td>A&ntilde;o Cuota</td>
      <td colspan="2"><?php echo CAMPO_SELECCION("year","year",date("Y"),true);?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2"><div align="right">
        <input type="submit" name="button" id="button" value="Consultar" />
        </div></td>
    </tr>
    </tbody>
  </table>
</form>  
</div>
<div id="apDiv4">Lista las Cuotas que tengan como fecha de vencimiento un dia dentro del periodo seleccionado.</div>
</body>
</html>
