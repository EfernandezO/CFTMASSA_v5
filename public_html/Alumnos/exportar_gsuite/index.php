<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("exportarAlumnosGsuite");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
require("../../../funciones/funciones_sistema.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion_2.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2v2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:60%;
	height:115px;
	z-index:1;
	left: 20%;
	top: 117px;
}
</style>
<title>Exportar a  Gsuite</title>
</head>

<body>
<h1 id="banner">Administrador - Exportar a Gsuite</h1>
<div id="link"><br />
<a href="../menualumnos.php" class="button">Volver</a></div>
<div id="apDiv1">
<form action="exportar_gsuite.php" method="post" enctype="multipart/form-data" id="frm">
<table width="50%" border="0" align="center" id="rounded-corner" summary="envio de bienvenida" >
<thead>
  <tr>
    <th scope="col" class="rounded-company" colspan="3">Seleccion de Alumno</th>
    </tr>
</thead>
<tbody>
<tr>
  <td>a&ntilde;o contrato</td>
  <td colspan="2"><?php echo CAMPO_SELECCION("yearContrato","year",date("Y"));?></td>
</tr>
<tr>
  <td>Marcar Alumnos como cargados</td>
  <td width="43%"><input type="radio" name="marcar_cargado" id="marcar_cargado" value="si" />
    <label for="marcar_cargado">Si</label></td>
  <td width="20%"><input name="marcar_cargado" type="radio" id="marcar_cargado2" value="no" checked="checked" />
    No</td>
</tr>
  </tbody>
   <tfoot>
    	<tr>
        	<td class="rounded-foot-left"><em>...</em></td>
        	<td colspan="2" class="rounded-foot-right"><input type="submit" name="Enviar" id="enviar" value="Exportar" /></td>
        </tr>
    </tfoot>
</table>
</form>
</div>
</body>
</html>