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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Menu Presupuestos</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_3.css">
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:293px;
	height:67px;
	z-index:1;
	left: 295px;
	top: 148px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
}
-->
</style>
<?php
$privilegio=$_SESSION["USUARIO"]["privilegio"];
switch($privilegio)
{
	case"admi_total":
		$url_menu="../index.php";
		break;
	default:
		$url_menu="../../Administrador/menu_inspeccion/index.php";
}
?>
</head>
<body>
<h1 id="banner">Administrador - Presupuestos V1.0</h1>
  <div id="apDiv1">
    <table width="100%" border="1">
      <tr>
        <td><div align="center"><a href="item_presupuesto/item_actuales.php">Configurar Item de Presupuesto</a></div></td>
      </tr>
      <tr>
        <td><div align="center"><a href="ingreso_datos/presupuesto_seleccion.php">Agregar Datos a Presupuesto</a></div></td>
      </tr>
      <tr>
        <td><div align="center"><a href="informes/selector_informe.php">Informes</a></div></td>
      </tr>
      <tr>
        <td><div align="center"><a href="<?php echo $url_menu;?>" class="Estilo2">Volver al Menu</a></div></td>
      </tr>
    </table>
  </div>
</body>
</html>
