<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
//-----------------------------------------//	
if($_POST)
{
	include("../../../funciones/funciones_varias.php");
	$rut=$_POST["rut"];
	$array_rut=explode("-",$rut);
	
	$rut_original=$array_rut[0];
	$dv_original=$array_rut[1];
	
	$dv_correcto=validar_rut($rut_original);
	
	if($dv_original==$dv_correcto)
	{ $msj_rut="Rut Correcto...:D";}
	else
	{ $msj_rut="Rut Incorrecto :(";}
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>verificador de Rut</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 168px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Verificador Rut</h1>
<div id="link"><br />
<a href="verificador_rut.php" class="button">Volver a seleccion</a>
  <div id="apDiv1">
    <table width="30%" border="1" align="center">
    <thead>
      <tr>
        <th colspan="2" align="center">Rut</th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td width="59%"><strong>Rut original</strong></td>
        <td width="41%"><strong>Dv original</strong></td>
      </tr>
      <tr>
        <td><?php echo $rut_original;?></td>
        <td><?php echo $dv_original;?></td>
      </tr>
      <tr>
        <td><strong>Rut correcto</strong></td>
        <td><strong>Dv Correcto</strong></td>
      </tr>
      <tr>
        <td><?php echo $rut_original;?></td>
        <td><?php echo $dv_correcto;?></td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;<?php echo $msj_rut; ?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>