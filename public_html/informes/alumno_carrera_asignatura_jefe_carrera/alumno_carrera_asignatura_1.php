<?php
//--------------CLASS_okalis------------------//
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="inspeccion";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
//-----------------------------------------//	
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
<?php include("../../../funciones/codificacion.php");?>
<title>Jefe de Carrera - informe</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<?php
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("server_carrera_asignatura.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"GENERA_INFORME");
$xajax->register(XAJAX_FUNCTION,"BUSCA_SEDE");
$xajax->register(XAJAX_FUNCTION,"BUSCA_CARRERA");

 require("../../../funciones/funciones_sistema.php");
 $id_usuario_actual=$_SESSION["USUARIO"]["id"];
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
	top: 338px;
}
#apDiv1 {
	position:absolute;
	width:40%;
	height:115px;
	z-index:3;
	left: 5%;
}
#div_boton {
	position:absolute;
	width:30%;
	height:40px;
	z-index:4;
	left: 50%;
	top: 254px;
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

<body onload="xajax_BUSCA_SEDE(<?php echo $id_usuario_actual;?>,document.getElementById('semestre').value, document.getElementById('year').value);">
<div id="div_boton"></div>
<h1 id="banner">Jefe de Carrera - Alumno carrera, asignatura</h1>
<div id="link"><br />
<a href="../../Docentes/okdocente.php" class="button">Volver al menu Principal </a></div>
<div id="apDiv1">
<form action="#" method="post" name="frm" id="frm">
  <div id="div_cargando"><img src="../../BAses/Images/BarraProgreso.gif" width="82" height="13" alt="Cargando..." /><br />
    Espere...
</div>
  <table width="100%" border="1" align="left">
  <caption></caption>
  <thead>
    <tr>
      <th colspan="3"><span class="Estilo1">Busqueda de Cursos</span></th>
    </tr>
    <tr class="odd">
      <td>Semestre</td>
      <td colspan="2"><?php echo CAMPO_SELECCION("semestre","semestre", $semestre_actual,false,'onChange="xajax_BUSCA_SEDE('.$id_usuario_actual.',document.getElementById(\'semestre\').value, document.getElementById(\'year\').value);"');?></td>
    </tr>
    <tr class="odd">
      <td>A&ntilde;o </td>
      <td colspan="2"><?php echo CAMPO_SELECCION("year","year", $year_actual,false,'onChange="xajax_BUSCA_SEDE('.$id_usuario_actual.',document.getElementById(\'semestre\').value, document.getElementById(\'year\').value);"');?></td>
    </tr>
	</thead>
	<tbody>
    <tr class="odd">
      <td width="193"><span class="Estilo1">Sede</span></td>
      <td width="198" colspan="2">
      <div id="div_sede">...</div></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Carrera</span></td>
      <td colspan="2">
        <div id="div_carrera">...</div></td>
    </tr>
    <tr class="odd">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr class="odd">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
	</tbody>
	<tfoot>
    <tr>
      <td colspan="3"><div align="right"></div></td>
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