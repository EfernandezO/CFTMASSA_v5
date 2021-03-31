<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_alumnos_proceso_titulacion_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Alumnos</title>
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
	height:40px;
	z-index:2;
	left: 30%;
	top: 388px;
	text-align: center;
}
-->
</style>
</head>
<?php
$privilegio=$_SESSION["USUARIO"]["privilegio"];
switch($privilegio)
{
	case"externo":
		$url_destino="../../Administrador/menu_externos/index.php";
		break;
	default:
		$url_destino="../menualumnos.php";	
}
  include("../../../funciones/funciones_sistema.php");
  $year_actual=date("Y");
?>
<body>
<h1 id="banner">Administrador - informe Alumnos</h1>
<div id="link"><br />
<a href="../../Alumnos/menualumnos.php" class="button">Volver al Menu</a></div>
  <div id="apDiv2">
    <div align="center">
      <form action="genera_informe.php" method="post" name="frm" id="frm">
      <table width="50%" border="0">
      <thead>
        <tr>
          <th colspan="3" >Seleccione, para Buscar</th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td >Sede</td>
          <td colspan="2" ><em>
            <?php  echo CAMPO_SELECCION("sede", "sede", "",true);?>
          </em></td>
        </tr>
        <tr>
          <td width="34%" >A&ntilde;o Ingreso</td>
          <td colspan="2" > <?php  echo CAMPO_SELECCION("year_ingreso", "year", "0",true);?></td>
        </tr>
        <tr>
          <td >A&ntilde;o Acta</td>
          <td colspan="2" >
          <?php  echo CAMPO_SELECCION("year_titulacion", "year", "0",true);?>
          </td>
        </tr>
        <tr>
          <td >A&ntilde;o Emision Titulo</td>
          <td colspan="2" >
          <?php  echo CAMPO_SELECCION("year_emision_titulo", "year", "0",true);?>
          </td>
        </tr>
        <tr>
          <td >Carrera</td>
          <td colspan="2" >
          <?php  echo CAMPO_SELECCION("carrera", "carreras", "",true);?>
           
   </td>
        </tr>
        <tr>
          <td >Buscar</td>
          <td width="10%" ><input name="opcion" type="radio" id="opcion2" value="con_proceso" checked="checked" /></td>
          <td width="56%" >Solo los Alumnos Con Proceso de Titulacion</td>
        </tr>
        <tr>
          <td rowspan="2" >tipo informe</td>
          <td ><input type="radio" name="tipo_informe" id="tipo_informe" value="datos_contacto" />
            <div id="apDiv3">Busca Alumnos con procesos de titulacion.</div>
  <label for="tipo_informe"></label></td>
          <td >datos contacto</td>
        </tr>
          <tr>
            <td ><input name="tipo_informe" type="radio" id="tipo_informe2" value="datos_proceso_titulacion" checked="checked" /></td>
            <td >datos proceso titulacion</td>
          </tr>
        </tbody>
        <tfoot>
        <tr>
          <td colspan="3" ><div align="right">
              <input type="submit" name="button" id="button" value="Continuar" />
          </div></td>
        </tr>
        </tfoot>
      </table>
      </form>
    </div>
  </div>
</body>
</html>