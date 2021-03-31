<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("revisionNotasparcialesV1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("proceso_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_NOTAS");
//---------------------------------------------------------///
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

if(DEBUG){ echo"datos de llegada...<br>Sede: $sede_actual<br>Semestre: $semestre_actual<br>Year: $year_actual<br>";}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<title>Revision Notas Parciales</title>
<?php $xajax->printJavascript(); ?> 
<!--INICIO LIGHTBOX EVOLUTION-->
   <script type="text/javascript" src="../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
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
	top: 246px;
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
<body onload="xajax_BUSCA_NOTAS(document.getElementById('fsede').value, document.getElementById('year').value, document.getElementById('semestre').value); return false;">
<h1 id="banner">Administrador - Revision Notas Parciales</h1>
<div id="link"><br />
  <div id="div_superior"></div>
  <a href="../../Alumnos/menualumnos.php" class="button">Volver</a>
</div>

<div id="div_planificaciones">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="12">Notas Existentes <?php echo"$sede_actual [$semestre_actual - $year_actual]";?></th>
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
        <td>N° Evaluaciones</td>
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
      <th width="100%" colspan="2">Informacion</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td colspan="2">Notas Parciales</td>
    </tr>
    <tr>
      <td>Sede</td>
      <td><?php include("../../../funciones/funciones_sistema.php");
	  			
				
	  			echo CAMPO_SELECCION("fsede","sede",$sede_actual,false,'onchange="xajax_BUSCA_PLANIFICACIONES(document.getElementById(\'fsede\').value, document.getElementById(\'year\').value, document.getElementById(\'semestre\').value)"; return false;');
			?></td>
    </tr>
    <tr>
      <td>A&ntilde;o</td>
      <td><select name="year" id="year" onchange="xajax_BUSCA_NOTAS(document.getElementById('fsede').value, document.getElementById('year').value, document.getElementById('semestre').value); return false;">
        <?php
	  	$anos_anteriores=10;
		$anos_siguientes=1;
	  	$year_actual_real=date("Y");
		
		$ano_ini=$year_actual_real-$anos_anteriores;
		$ano_fin=$year_actual_real+$anos_siguientes;
		
		for($a=$ano_ini;$a<=$ano_fin;$a++)
		{
			if($a==$year_actual)
			{ $select='selected="selected"';}
			else
			{ $select='';}	

			echo'<option value="'.$a.'" '.$select.'>'.$a.'</option>';
		}
	  ?>
        </select></td>
    </tr>
    <tr>
      <td>Semestre</td>
      <td> <select name="semestre" id="semestre" onchange="xajax_BUSCA_NOTAS(document.getElementById('fsede').value, document.getElementById('year').value, document.getElementById('semestre').value); return false;">
      
          <option value="1" <?php if($semestre_actual==1){ echo'selected="selected"';}?>>1</option>
          <option value="2" <?php if($semestre_actual==2){ echo'selected="selected"';}?>>2</option>
          </select>
        </td>
    </tr>
    </tbody>
  </table>
</div>
</body>
</html>