<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("certificado->concentracion_de_notas_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(isset($_SESSION["AUX_CERTIFICADO"]))
{unset($_SESSION["AUX_CERTIFICADO"]);}
?>
<html>
<head>
<title>Concentracion de Notas</title>
<?php include("../../../funciones/codificacion.php");?>

<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
}
.Estilo2 {color: #0080C0}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
#link {
	text-align: right;
	padding-right: 10px;
}
-->
</style>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:24px;
	z-index:8;
	left: 5%;
	top: 291px;
	text-align: center;
	font-style: italic;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Certificado Concentraci&oacute;n de Notas</h1>
<div id="link"><br>
<a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver al Menu</a></div>
<div id="layer" style="position:absolute; left:5%; top:119px; width:90%; height:160px; z-index:7">
  <form action="informe_concentracion_notas_pdf_2.php" method="post"  enctype="multipart/form-data" name="frm" id="frm">
    <table width="380" border="0" align="center" bgcolor="#CCFFFF">
    <thead>
      <tr>
      <th colspan="3"><div align="center"><strong>Datos del Certificado</strong></div></th> 
     </tr>
     </thead>
     <tbody>
	  <tr>
	     <td width="119"><strong>Firma</strong></td>
        <td width="245" colspan="2"> 
          <select name="firma">
            <option value="RENATO CELIS SAAVEDRA">RENATO CELIS SAAVEDRA</option>
            <option value="JAIME A. AULADELL ALDANA">JAIME A. AULADELL ALDANA</option>
            <option value="PAOLA MAUREIRA SANCHEZ">PAOLA MAUREIRA SANCHEZ</option>
        </select>        </td>
	  </tr>
      <tr>
        <td>Logo</td>
        <td><input type="radio" name="ver_logo" id="logo" value="si">
          <label for="logo">Si</label></td>
        <td><input name="ver_logo" type="radio" id="logo2" value="no" checked>
          No</td>
      </tr>
      <tr>
        <td colspan="3"><input type="submit" name="Submit" value="Generar"></td>
      </tr>
      </tbody>
    </table>
  </form>
</div>
<div id="apDiv1"></div>
</body>
</html>