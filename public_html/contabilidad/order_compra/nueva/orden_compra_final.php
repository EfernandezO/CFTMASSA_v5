
<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG", false);
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin título</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:40%;
	height:104px;
	z-index:1;
	left: 30%;
	top: 115px;
}
</style>
</head>
<?php
if(isset($_GET["error"]))
{
	$error=$_GET["error"];
	$img_ok='<img src="../../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
	$img_error='<img src="../../../BAses/Images/b_drop.png" width="16" height="16" alt="x" />';
	
	switch($error)
	{
		case"OC1":
			$msj="Orden de Compra Agregada Correctamente";
			$img=$img_ok;
			break;
		default:
			$msj="";
			$img="";	
	}
}
else
{ $msj=""; $img="";}
?>
<body>
<h1 id="banner">Finanzas - Orden de Compra</h1>
<div id="link"><br />
<a href="../revision/revisar.php" class="button">Ver Ordenes Creadas</a><br />
<br />
<a href="orden_compra_1.php" class="button">Nueva Orden de Compra</a>
</div>
<div id="apDiv1">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th>INFORMACION</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td height="57" align="center"><?php echo $img.$msj;?></td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      </tr>
    </tbody>
  </table>
</div>
</body>
</html>