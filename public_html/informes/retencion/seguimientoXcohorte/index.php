<?php
//--------------CLASS_okalis------------------//
	require("../../....//OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("informe_seguimientoXCohorte_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php");?>
<title>Administrador - informe</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css"/>
<?php
//////////////////////XAJAX/////////////////
@require_once ("../../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("server_cohorte.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"GENERA_INFORME");
////////////////////////////////////////////
?>
<?php $xajax->printJavascript(); ?> 

<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:186px;
	z-index:1;
	left: 5%;
	top: 86px;
}
#Layer3 {
	width:100%;
	height:59px;
	z-index:1;
	top: 373px;
}
.Estilo1 {font-size: 12px}
#Layer2 {
	position:absolute;
	width:168px;
	height:16px;
	z-index:2;
	left: 420px;
	top: 49px;
}
#link {
	text-align: right;
	padding-right: 10px;
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
#apDiv1 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:2;
	left: 60%;
	top: 3px;

}
#apDiv2 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:3;
	left: 5%;
	top: 358px;
}
#div_ex {
	position:absolute;
	width:40%;
	height:22px;
	z-index:4;
	left: 60%;
	top: 127px;
	text-align: center;
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

<body onload="MM_preloadImages('../../../BAses/Images/BarraProgreso.gif')">
<h1 id="banner">Administrador -Seguimiento X Cohorte V.1</h1>
<?php
$privilegio=$_SESSION["USUARIO"]["privilegio"];
switch($privilegio)
{
	case"inspeccion":
		$url="../../Administrador/menu_inspeccion/index.php";
		break;
	case"jefe_carrera":
		$url="../../Docentes/okdocente.php";
		break;	
	default:
		$url="../../Alumnos/menualumnos.php";
}
?>
<div id="link"><br />
<a href="<?php echo $url;?>" class="button">Volver al menu Principal </a><br />
</div>
<div id="Layer1">
<form action="#" method="post" name="frm" id="frm">
  <div id="apDiv1"><em>*Considera a los Alumnos con el &quot;A&ntilde;o de ingreso&quot; seleccionado y busca sus contratos en los siguientes semestres para verificar o no su continuidad en la instituci&oacute;n*</em></div>
  <div id="div_ex">...</div>
  <div id="div_cargando"><img src="../../../BAses/Images/BarraProgreso.gif" width="82" height="13" alt="Cargando..." /><br />
    Espere...
</div>
  <table width="50%" border="1" align="left">
  <caption></caption>
  <thead>
    <tr>
      <th colspan="3"><span class="Estilo1">Busqueda de Alumnos </span></th>
    </tr>
	</thead>
	<tbody>
    <tr class="odd">
      <td width="193"><span class="Estilo1">Sede</span></td>
      <td colspan="2"><?php
	  include("../../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Carrera</span></td>
      <td colspan="2"><?php 

   require("../../../../funciones/conexion_v2.php");
   
   $res="SELECT id, carrera FROM carrera where id >= 0";
   $result=$conexion_mysqli->query($res);
   ?>
        <select name="carrera" id="carrera" onchange="CARGA_ASIGNATURA()">
          <?php
   while($row = $result->fetch_assoc()) 
   {
    	$nomcar=$row["carrera"];
		$id_carrera=$row["id"];
    	echo'<option value="'.$id_carrera.'">'.$id_carrera.'_'.$nomcar.'</option>';
   }
    $result->free();
    @mysql_close($conexion);
	$conexion_mysqli->close();
	 ?>
        </select></td>
    </tr>
    <tr class="odd">
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr class="odd">
      <td>A&ntilde;o Ingreso</td>
      <td colspan="2"><select name="year_ingreso" id="year_ingreso">
        <?php
	  	$anos_anteriores=10;
		$anos_siguientes=0;
	  	$ano_actual=date("Y");
		
		$ano_ini=$ano_actual-$anos_anteriores;
		$ano_fin=$ano_actual+$anos_siguientes;
		
		for($a=$ano_ini;$a<=$ano_fin;$a++)
		{
			if($a==$ano_actual)
			{
				echo'<option value="'.$a.'" selected="selected">'.$a.'</option>';	
			}
			else
			{
				echo'<option value="'.$a.'">'.$a.'</option>';
			}	
		}
	  ?>
        </select></td>
    </tr>
     <tr class="odd">
      <td>Ver Alumno que inician</td>
      <td width="126"><input type="radio" name="ver_alumno" id="radio" value="si" />
        <label for="ver_alumno">Si</label></td>
      <td width="72"><input name="ver_alumno" type="radio" id="radio2" value="no" checked="checked" />
        No</td>
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

<div id="apDiv2">
<div id="Layer3">
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