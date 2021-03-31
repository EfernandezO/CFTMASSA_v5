<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Proyecciones_v3");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$year_actual=date("Y");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Proyecciones Anuales</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style>
#link {
	text-align: right;
	padding-right: 10px;
}
#Layer1 {
	position:absolute;
	width:90%;
	height:106px;
	z-index:2;
	left: 5%;
	top: 94px;
}
.Estilo1 {font-size: 12px}
</style>
</head>

<body>
<h1 id="banner">Proyecciones - Ingresos </h1>
<?php
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	switch($privilegio)
	{
		case"inspeccion":
			$url="../../../Administrador/menu_inspeccion/index.php";
			break;
		case"matricula":
			$url="../../../Administrador/menu_matricula/index.php";
			break;	
		default:
			$url="../../index.php";	
	}
?>
<div id="link"><br />
<a href="<?php echo $url;?>" class="button">Volver al Menu</a></div>
<div id="Layer1">
    <form action="proyeccion_2.php" method="post" name="frm" target="_blank" id="frm">
      <table width="50%" border="0" align="center">
      <thead>
        <tr>
          <th colspan="3"><div align="center"><strong>Selecciones</strong> <strong>Parametros de Busqueda</strong></div></th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td>Año (ingreso alumno)</td>
          <td colspan="2"><?php
	  require("../../../../funciones/funciones_sistema.php");
	  echo CAMPO_SELECCION("year_ingreso", "year", "0",true);
	 ?></td>
        </tr>
        <tr>
          <td width="49%"><strong>A&ntilde;o (cuotas)</strong></td>
          <td colspan="2"><?php
	  echo CAMPO_SELECCION("year", "year",$year_actual,false);
	 ?></td>
        </tr>
        <tr>
          <td ><strong>Sede</strong></td>
          <td colspan="2" ><?php
	  echo CAMPO_SELECCION("fsede", "sede","",false);
	 ?></td>
        </tr>
        <tr>
          <td >Nivel</td>
          <td colspan="2" ><?php echo CAMPO_SELECCION("nivel", "niveles_academicos","0", true); ?></td>
        </tr>
        <tr>
          <td >Jornada</td>
          <td colspan="2" ><?php echo CAMPO_SELECCION("jornada", "jornada","0", true); ?></td>
        </tr>
        <tr>
          <td rowspan="6" >Tipo Morosidad</td>
          <td ><input name="array_morosidad[]" type="checkbox" id="array_morosidad[]" value="0" checked="checked" /></td>
          <td >Tipo 0 (sin morosidad)</td>
        </tr>
        <tr>
          <td width="11%" ><label for="array_morosidad[]">
            <input name="array_morosidad[]" type="checkbox" id="array_morosidad[]" value="1" checked="checked" />
          </label></td>
          <td width="40%" >Tipo 1 (hasta 30 dias)</td>
        </tr>
        <tr>
          <td ><input name="array_morosidad[]" type="checkbox" id="array_morosidad[]" value="2" checked="checked" /></td>
          <td >Tipo 2 (entre 30-60 dias)</td>
        </tr>
        <tr>
          <td ><input name="array_morosidad[]" type="checkbox" id="array_morosidad[]" value="3" checked="checked" /></td>
          <td >Tipo 3 (entre 60-90 dias)</td>
        </tr>
        <tr>
          <td ><input name="array_morosidad[]" type="checkbox" id="array_morosidad[]" value="4" checked="checked" /></td>
          <td >Tipo 4 (entre 90-120 dias)</td>
        </tr>
        <tr>
          <td ><input name="array_morosidad[]" type="checkbox" id="array_morosidad[]" value="5" checked="checked" /></td>
          <td >Tipo 5 (120+ dias)</td>
        </tr>
        <tr>
          <td ><span class="Estilo1">Estado Academico</span></td>
          <td colspan="2" ><?php echo CAMPO_SELECCION("situacion_academica", "situaciones_academicas","0", true); ?></td>
        </tr>
        <tr>
          <td colspan="3" ><div align="center">
              <input type="submit" name="Submit" value="Consultar&gt;&gt;" />
          </div></td>
        </tr>
        </tbody>
      </table>
    </form>
  </div>

</body>
</html>
