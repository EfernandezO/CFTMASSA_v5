<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Modificacion_datos_de_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<title>Edicion de Alumnos - Final</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:309px;
	height:183px;
	z-index:1;
	left: 267px;
	top: 119px;
}
a:link {
	color: #6699CC;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #6699CC;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #6699CC;
}
-->
</style>
</head>

<body>
<h1 id="banner">Administrador - Edici&oacute;n de Alumnos</h1>
<div id="link"><br />
<a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver al Menu</a></div>
<?php
if($_GET)
{
	$error=$_GET["error"];
	$img_ok='<img src="../../BAses/Images/ok.png" />';
	$img_error='<img src="../../BAses/Images/b_drop.png" />';
	switch($error)
	{
		case"0":
			$msj="Datos de Alumno Modificados Correctamente...";
			$img=$img_ok;
			break;
		case"1":
			$msj="Error Al modificar Los datos del Alumno... Rut ya presente en sistema";
			$img=$img_error;
			break;	
		case"2":
			$msj="Error Al modificar el Registro del Alumno";
			$img=$img_error;
			break;				
	}
}
?>
<div id="mensaje_error">
  <div align="center">
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <table width="40%" border="1">
    <thead>
      <tr>
        <th><div align="center"><strong>INFORMACION</strong></div></th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td><div align="center"><?php echo "$msj $img<br>";?></div>        </td>
      </tr>
      <tr>
        <td height="79"><div align="center">&iquest;Desea Imprimir ficha Recepcion Documentos?<br />
          <br />
            <a href="../../Certificados/recepcion_documentos/documentos_recepcionados_pdf.php" target="_blank" class="button_R">click Aqui </a></div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
