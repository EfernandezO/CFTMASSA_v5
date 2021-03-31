<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_alumnos_retirados_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion_2.php"); ?>
<title>Alumnos Retirados</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:653px;
	height:115px;
	z-index:1;
	left: 20px;
	top: 78px;
}
#link {
	text-align: right;
	padding-right: 10px;
}
a:link {
	color: #006699;
	text-decoration: none;
}
a:visited {
	color: #006699;
	text-decoration: none;
}
a:hover {
	color: #FF0000;
	text-decoration: underline;
}
a:active {
	color: #006699;
	text-decoration: none;
}
#apDiv2 {
	position:absolute;
	width:100%;
	height:115px;
	z-index:1;
	left: 0px;
	top: 118px;
}
#apDiv3 {
	position:absolute;
	width:40%;
	height:45px;
	z-index:2;
	left: 30%;
	top: 335px;
	text-align: center;
}
-->
</style>
</head>
<?php
require("../../../funciones/funciones_sistema.php");
?>
<body>
<h1 id="banner">Administrador - informe Alumnos Retirados</h1>
<div id="link"><br />
<a href="../../Alumnos/menualumnos.php" class="button">Volver al Menu</a></div>
  <div id="apDiv2">
    <div align="center">
      <form action="alumnos_retirados.php" method="post" name="frm" id="frm">
      <table width="50%" border="0">
      <thead>
        <tr>
          <th colspan="2" >Seleccione, para Buscar</th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td>Sede</td>
          <td><em>
            <?php echo CAMPO_SELECCION("sede", "sede");?>
          </em></td>
        </tr>
        <tr>
          <td width="69%">A&ntilde;o contrato</td>
          <td width="31%"><?php echo CAMPO_SELECCION("year", "year");?></td>
        </tr>
        <tr>
          <td>Tipo</td>
          <td><label for="tipo_alumno"></label>
            <select name="tipo_alumno" id="tipo_alumno">
              <option value="R" selected="selected">retirados</option>
              <option value="P">postergados</option>
            </select></td>
        </tr>
        <tr>
          <td>Carrera</td>
          <td><?php echo CAMPO_SELECCION("carrera","carreras","",true);?></td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
          <td colspan="2" ><div align="right">
              <input type="submit" name="button" id="button" value="Continuar" />
          </div></td>
        </tr>
        </tfoot>
      </table>
      </form>
    </div>
  </div>
  <div id="apDiv3">Lista Alumnos seg&uacute;n parametros<br />
    seleccionados que se encuentren con contratos cuya condicion<br />
    sea 'retiro'
  o 'postergacion'</div>
</body>
</html>