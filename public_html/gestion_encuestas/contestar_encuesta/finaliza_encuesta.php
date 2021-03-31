<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="ALUMNO";
	$lista_invitados["privilegio"][]="jefe_carrera";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="ex_alumno";
	$lista_invitados["privilegio"][]="matricula";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", false);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Contestar | Encuesta</title>
	<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
    <link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style>
#apDiv4 {
	position:absolute;
	width:40%;
	height:64px;
	z-index:1;
	left: 30%;
	top: 138px;
}
</style>
</head>
<?php
if(isset($_GET["error"]))
{
	$error=$_GET["error"];
	$img_error='<img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="X" />';
	$img_ok='<img src="../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
	
	switch($error)
	{
		case"C0":
			$msj="La Encuesta fue Correctamente Contestada<br>Muchas Gracias por su Colaboracion";
			$img=$img_ok;
			break;
	}
	
}
else
{
	$msj="";
	$img="";
}

?>
<body>
<h1 id="banner">Contestar Encuesta</h1>
<div id="link"><br />
<a href="../index.php" class="button">Volver a Encuestas</a><br />
<br />
</div>
<div id="apDiv4">
  <table width="100%" border="1" align="center">
  <thead>
    <tr>
      <th>INFORMACION</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td height="91" align="center"><?php echo $msj.$img;?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    </tbody>
  </table>
</div>
</body>
</html>