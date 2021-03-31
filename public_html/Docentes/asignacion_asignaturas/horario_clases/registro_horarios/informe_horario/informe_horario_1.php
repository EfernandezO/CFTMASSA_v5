<?php
//--------------CLASS_okalis------------------//
	require("../../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../../funciones/";
	$O->clave_del_archivo=md5("control_horario_docente");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$year_actual=date("Y");
$mes_actual=date("m");
if($mes_actual>=8){$semestre_actual=2;}
else{ $semestre_actual=1;}
$sede_actual=$_SESSION["USUARIO"]["sede"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../../funciones/codificacion.php");?>
<title>Administrador - informe</title>
<link rel="stylesheet" type="text/css" href="../../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../../CSS/tabla_2.css"/>
<?php
//////////////////////XAJAX/////////////////
@require_once ("../../../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("server_carrera_asignatura.php");
$xajax->configure('javascript URI','../../../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"GENERA_INFORME");
////////////////////////////////////////////
?>
<?php $xajax->printJavascript(); ?> 

<style type="text/css">
<!--
#div_carga {
	width:100%;
	height:59px;
	z-index:1;
	top: 373px;
}

#div_cargando {
	position:absolute;
	width:102px;
	height:31px;
	z-index:2;
	left: 60%;
	top: 248px;
	display:none;
}
#div_contenedor_carga {
	position:absolute;
	width:798px;
	height:115px;
	z-index:2;
	left: 5%;
	top: 328px;
}
#apDiv1 {
	position:absolute;
	width:40%;
	height:115px;
	z-index:3;
	left: 5%;
}
-->
</style>
<script type="text/javascript">
<!--
function muestra_cargando()
	{
      xajax.$('div_cargando').style.display='block';
   }
   function oculta_cargando(){
      xajax.$('div_cargando').style.display='none';
   }
   
   xajax.callback.global.onResponseDelay = muestra_cargando;
   xajax.callback.global.onComplete = oculta_cargando;
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
</script> 
</head>

<body onload="MM_preloadImages('../../../../../BAses/Images/BarraProgreso.gif')">
<h1 id="banner">Administrador - Horario Informe 1</h1>
<div id="link"><br />
<a href="../registro_horario_1.php" class="button">Volver al menu  </a></div>
<div id="apDiv1">
<form action="#" method="post" name="frm" id="frm">
  <div id="div_cargando"><img src="../../../../../BAses/Images/BarraProgreso.gif" width="82" height="13" alt="Cargando..." /><br />
    Espere...
</div>
  <table width="100%" border="1" align="left">
  <caption></caption>
  <thead>
    <tr>
      <th colspan="3"><span class="Estilo1">Busqueda de Alumnos </span></th>
    </tr>
	</thead>
	<tbody>
    <tr class="odd">
      <td width="193"><span class="Estilo1">Sede</span></td>
      <td width="198" colspan="2">
	  <?php
	  require("../../../../../../funciones/funciones_sistema.php");
	  echo CAMPO_SELECCION("fsede","sede",$sede_actual, false);
	  ?>
      </td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Carrera</span></td>
      <td colspan="2"><?php echo CAMPO_SELECCION("id_carrera", "carreras","",true);?></td>
    </tr>
    <tr class="odd">
      <td>Semestre</td>
      <td colspan="2"><?php echo CAMPO_SELECCION("semestre","semestre", $semestre_actual,false);?></td>
    </tr>
    <tr class="odd">
      <td>A&ntilde;o </td>
      <td colspan="2"><?php echo CAMPO_SELECCION("year","year", $year_actual,false);?></td>
    </tr>
	</tbody>
	<tfoot>
    <tr>
      <td colspan="3"><div align="right">
        <input name="boton" type="button" id="boton" onclick="xajax_GENERA_INFORME(xajax.getFormValues('frm'));return false;" value="Generar Informe"/>
      </div></td>
      </tr>
	</tfoot>
  </table>
 </form> 
</div>

<div id="div_contenedor_carga">
<div id="div_carga">
  <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" class="display" id="example">
<thead>
    <th>I</th>
</thead>
<tbody>
<tr>
	<td colspan="6"><p>seleccione Parametros y Luego click a "Generar Informe"</p>
	  <p>&nbsp;</p></td>
</tr>
</tbody>
</table>
</div>
</div>

</body>
</html>