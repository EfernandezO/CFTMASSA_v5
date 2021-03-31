<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
//-----------------------------------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
 <link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<title>cambio Clave</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:40%;
	height:115px;
	z-index:1;
	left: 30%;
	top: 135px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:18px;
	z-index:2;
	left: 30%;
	top: 308px;
	text-align: center;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	continuar=true;
	
	if(continuar)
	{
		c=confirm('¿Seguro(a) Desea Actualizar su Clave?');
		if(c){ document.getElementById('frm').submit();}
	}
}
</script>
</head>

<body>
<h1 id="banner">Docentes - Cambio de Clave</h1>
<div id="link"><br>
<a href="../../okdocente.php" class="button">Volver al Menu</a></div>
 <div id="apDiv1">
 <form action="cambio_clave_2.php" method="post" enctype="multipart/form-data" id="frm">
   <table width="80%" border="1" align="center">
   <thead>
     <tr>
       <th colspan="2">Clave</th>
     </tr>
     </thead>
     <tbody>
     <tr>
       <td width="50%">Clave Actual</td>
       <td width="50%"><label for="clave_actual"></label>
        <input type="text" name="clave_actual" id="clave_actual" /></td>
     </tr>
     <tr>
       <td>Nueva Clave</td>
       <td><label for="nueva_clave"></label>
        <input type="text" name="nueva_clave" id="nueva_clave" /></td>
     </tr>
     <tr>
       <td>Confirme Clave</td>
       <td><label for="nueva_clave_2"></label>
        <input type="text" name="nueva_clave_2" id="nueva_clave_2" /></td>
     </tr>
     </tbody>
   </table>
   </form>
 </div>
 <div id="apDiv2"><a href="#" class="button_G" onclick="CONFIRMAR();">Continuar</a></div>
</body>
</html>