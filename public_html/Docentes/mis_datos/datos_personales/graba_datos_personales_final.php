<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
//-----------------------------------------//	
$img="";
$msj="";
if(isset($_GET["error"]))
{
	$error=$_GET["error"];
	$img_ok='<img src="../../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
	$img_error='<img src="../../../BAses/Images/b_drop.png" width="16" height="16" alt="X" />';
	switch($error)
	{
		case"D0":
			$msj="Datos Modificados Exitosamente";
			$img=$img_ok;
			break;
		case"D1":
			$msj="Error al Intentar Modificar los Datos";
			$img=$img_error;
			break;
		default:
			$msj="";
			$img="";		
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
 <link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<title>Documento sin título</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:40%;
	height:115px;
	z-index:1;
	left: 30%;
	top: 124px;
}
</style>
</head>

<body>
<h1 id="banner">Docentes - Mis Datos </h1>
<div id="link"><br>
<a href="../../okdocente.php" class="button">Volver al Menu</a></div>
 <div id="apDiv1">
   <table width="100%" border="1">
   <thead>
     <tr>
       <th>Informacion</th>
     </tr>
     </thead>
     <tbody>
     <tr>
       <td align="center"><?php echo $img.$msj;?></td>
       </tr>
     <tr>
       <td>&nbsp;</td>
       </tr>
     </tbody>
   </table>
 </div>
</body>
</html>