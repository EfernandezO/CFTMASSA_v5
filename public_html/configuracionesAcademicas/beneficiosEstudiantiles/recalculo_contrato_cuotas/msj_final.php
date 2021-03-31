<?php
//-----------------------------------------//
	require("../../../Edicion_carreras/OKALIS/seguridad.php");
	require("../../../Edicion_carreras/OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", true);
if(!$_GET)
{ header("location: recalculo_contrato_1.php");}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../../Edicion_carreras/libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../Edicion_carreras/CSS/tabla_2.css">
<title>Asignar Cuota - Alumno</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:1;
	left: 461px;
	top: 103px;
}
#apDiv2 {
	position:absolute;
	width:511px;
	height:43px;
	z-index:1;
	left: 138px;
	top: 103px;
}
#apDiv3 {
	position:absolute;
	width:90%;
	height:29px;
	z-index:1;
	left: 5%;
	top: 129px;
}
a:link {
	color: #006699;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #006699;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #006699;
}
-->
</style>
</head>
<?php
$error=$_GET["error"];
$tipo=$_GET["tipo"];	
	
	$img_ok='<img src="../../BAses/Images/ok.png" />';
	$img_error='<img src="../../BAses/Images/b_drop.png" />';
	switch($error)
	{
		case"0":
			$IMG=$img_ok;
			if($tipo=="cuota")
			{$msj="Se ha Generado un Nuevo contrato</br> y cuotas exitosamente";}
			elseif($tipo=="sin_excedente")
			{ $msj="Se ha Generado un Nuevo Contrato </br>y guardado sin excedentes exitosamente (sin necesidad de generar cuotas)";}
			else
			{$msj="Se ha Generado un Nuevo Contrato </br>y guardado un excedente exitosamente (sin necesidad de generar cuotas)";}
			break;
		case"1":
			$IMG=img_error;
			if($tipo=="cuota")
			{$msj="No se Generado un Nuevo contrato y cuotas ";}
			else
			{$msj="NO se ha Generado un Nuevo Contrato </br>y guardado un excedente (sin necesidad de generar cuotas)";}
			break;	
	}
	
?>
<body>
<h1 id="banner">Administrador - Asignacion Beca</h1>
<div id="apDiv3">
  <div align="center">
    <table width="50%" border="1" align="center">
    <thead>
      <tr>
        <th>INFORMACION</th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td><?php echo $msj;?> <?php echo $IMG;?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      </tbody>
    </table>
  </div>
  
  <div id="super_link" >
    <div align="center">
      <p>&nbsp;</p>
      <p><a href="../../../Edicion_carreras/buscador_alumno_BETA/HALL/index.php" class="button_G">Volver al Menu</a></p>
    </div>
  </div>
</div>
</body>
</html>