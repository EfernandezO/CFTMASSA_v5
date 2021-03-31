<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("revision_planificaciones_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("proceso_server.php");
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_PLANIFICACIONES");
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_PLANIFICACIONES");
$xajax->register(XAJAX_FUNCTION,"BUSCA_SEDE");
$xajax->register(XAJAX_FUNCTION,"BUSCA_CARRERAS");
//---------------------------------------------------------///
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	$sede_actual=$_SESSION["USUARIO"]["sede"];
	$year_actual=date("Y");
	$mes_actual=date("m");
	if($mes_actual>8){$semestre_actual=2;}
	else{ $semestre_actual=1;}
	
$hay_get=false;	
if($_GET)	
{
	if(DEBUG){ echo"Hay Get<br>";}
	if(isset($_GET["sede"])){$sede_actual=$_GET["sede"]; $var_get_1=true;}
	if(isset($_GET["semestre"])){$semestre_actual=$_GET["semestre"]; $var_get_2=true;}
	if(isset($_GET["year"])){$year_actual=$_GET["year"]; $var_get_3=true;}
	
	if($var_get_1 and $var_get_2 and $var_get_3){ $hay_get=true;}
	
}

switch($privilegio)
{
	case"jefe_carrera":
		$url="../../okdocente.php";
	break;
	default:
		$url="../../lista_funcionarios.php";
}

if(DEBUG){ echo"datos de llegada...<br>Sede: $sede_actual<br>Semestre: $semestre_actual<br>Year: $year_actual<br>";}
require("../../../../funciones/funciones_sistema.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<title>Revision Planificacion</title>
<?php $xajax->printJavascript(); ?> 
<!--INICIO LIGHTBOX EVOLUTION-->
   <script type="text/javascript" src="../../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
 <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox(); 
    });
  </script>
 <!--FIN LIGHTBOX EVOLUTION--> 
<style type="text/css">
#div_planificaciones {
	position:absolute;
	width:90%;
	height:75px;
	z-index:1;
	left: 5%;
	top: 280px;
}
#apDiv2 {
	position:absolute;
	width:50%;
	height:66px;
	z-index:2;
	left: 5%;
	top: 72px;
}
#div_superior {
	position:absolute;
	width:35%;
	height:36px;
	z-index:3;
	left: 60%;
	top: 164px;
}
</style>
<body onload="xajax_BUSCA_SEDE(document.getElementById('year').value, document.getElementById('semestre').value); return false;">
<h1 id="banner">Administrador - Revision Planificaciones V2</h1>
<div id="link"><br />
  <div id="div_superior"></div>
  <a href="<?php echo $url;?>" class="button">Volver</a>
</div>

<div id="div_planificaciones">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="12">Planificaciones Existentes <?php echo"$sede_actual [$semestre_actual - $year_actual]";?></th>
    </tr>
    </thead>
    <tbody>
    <tr>
    	<td>Sede</td>
    	<td>Año</td>	
        <td>Semestre</td>
        <td>Carrera</td>
        <td>Asignatura</td>
        <td>Nivel</td>
        <td>Jornada</td>
        <td>Grupo</td>
        <td>Docente</td>
        <td>condicion</td>
        <td>Opcion</td>
        <td>-</td>
    </tr>
    </tbody>
  </table>

</div>
<div id="apDiv2">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th width="100" colspan="2">Informacion  <input name="id_funcionario" type="hidden" id="id_funcionario" value="<?php echo $id_funcionario;?>" /></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td colspan="2">Planificaciones ya Existentes</td>
    </tr>
    <tr>
      <td>A&ntilde;o</td>
      <td><?php echo CAMPO_SELECCION("year","year", $year_actual, false, 'onchange="xajax_BUSCA_SEDE(document.getElementById(\'year\').value, document.getElementById(\'semestre\').value); return false;"'); ?></td>
    </tr>
    <tr>
      <td>Semestre</td>
      <td><?php echo CAMPO_SELECCION("semestre","semestre",$semestre_actual, false, 'onchange="xajax_BUSCA_SEDE( document.getElementById(\'year\').value, document.getElementById(\'semestre\').value); return false;"'); ?>
      </td>
    </tr>
    <tr>
      <td>Sede</td>
      <td><div id="div_sede">
       
      </div>
        </td>
    </tr>
    <tr>
      <td>Carrera</td>
      <td><div id="div_carrera">...</div></td>
    </tr>
    </tbody>
  </table>
</div>
</body>
</html>