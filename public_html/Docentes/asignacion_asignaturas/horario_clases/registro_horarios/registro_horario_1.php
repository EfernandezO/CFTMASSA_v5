<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("control_horario_docente");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$mes_actual=date("m");
if($mes_actual>8)
{ $semestre_actual=2;}
else
{ $semestre_actual=1;}

$array_semestre=array(1=>"1",2=>"2");
$array_dia=array(0 =>"Domingo",
				 1=>"Lunes",
				 2=>"Martes",
				 3=>"Miercoles",
				 4=>"Jueves",
				 5=>"Viernes",
				 6=>"Sabado");
$dia_actual=date("w");	

//////////////////////XAJAX/////////////////
@require_once ("../../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("registro_horario_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CLASES_DE_HOY");
//-------------------------------------------------------//
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php");?>
<title>Control Horario</title>
<?php $xajax->printJavascript(); ?>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:40%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 88px;
}
#div_clases_hoy {
	position:absolute;
	width:90%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 276px;
}
</style>
<!--INICIO LIGHTBOX EVOLUTION-->
  <script type="text/javascript" src="../../../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
 <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox({ 'onClose'  : function() { xajax_CLASES_DE_HOY(document.getElementById('fecha').value, document.getElementById('semestre').value, document.getElementById('year').value, document.getElementById('sede').value);}}); 
    });
  </script>
 <!--FIN LIGHTBOX EVOLUTION--> 
  <script src="../../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
 <script src="../../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/steel/steel.css">
<!--INICIO MENU HORIZONTAL-->
 <link rel="stylesheet" type="text/css" href="../../../../libreria_publica/menu_horizontal/ddsmoothmenu-v.css"/>  
  <link rel="stylesheet" type="text/css" href="../../../../libreria_publica/menu_horizontal/ddsmoothmenu.css"/>
 
<script type="text/javascript" src="../../../../libreria_publica/menu_horizontal/ddsmoothmenu.js">

/***********************************************
* Smooth Navigational Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>

<script type="text/javascript">

ddsmoothmenu.init({
	mainmenuid: "smoothmenu1", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu', //class added to menu's outer DIV
	//customtheme: ["#1c5a80", "#18374a"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

ddsmoothmenu.init({
	mainmenuid: "smoothmenu2", //Menu DIV id
	orientation: 'v', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu-v', //class added to menu's outer DIV
	//customtheme: ["#804000", "#482400"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

</script>
<!--FIN MENU HORIZONTAL-->
</head>

<body onload="xajax_CLASES_DE_HOY(document.getElementById('fecha').value, document.getElementById('semestre').value, document.getElementById('year').value, document.getElementById('sede').value); return false;">
<h1 id="banner">Administrador - Revisi&oacute;n Horario Docente</h1>
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">informes</a>
  <ul>
  <li><a href="informe_horario/informe_horario_1.php">informe General</a></li>
  </ul>
</li>
<li><a href="../../../lista_funcionarios.php">Volver al Menu</a></li>
</ul>
<br style="clear: left" />
</div> 
<div id="apDiv1">
  <table width="90%" border="1">
  <thead>
    <tr>
      <th colspan="2">Seleccion</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="22%">Fecha</td>
      <td width="78%"><input  name="fecha" id="fecha" size="15" maxlength="10" readonly="readonly" value="<?php echo date("Y-m-d");?>" onchange="xajax_CLASES_DE_HOY(document.getElementById('fecha').value, document.getElementById('semestre').value, document.getElementById('year').value, document.getElementById('sede').value); return false;"/>
        <input type="button" name="boton1" id="boton1" value="..." /></td>
    </tr>
    <tr>
      <td>Semestre </td>
      <td>
        <select name="semestre" id="semestre" onchange="xajax_CLASES_DE_HOY(document.getElementById('fecha').value, document.getElementById('semestre').value, document.getElementById('year').value, document.getElementById('sede').value); return false;">
          <?php
			foreach($array_semestre as $n=>$valor)
			{
				if($valor==$semestre_actual)
				{echo'<option value="'.$valor.'" selected="selected">'.$valor.'</option>';}
				else{echo'<option value="'.$valor.'">'.$valor.'</option>';}
			}
        ?>
        </select></td>
    </tr>
    <tr>
      <td>A&ntilde;o</td>
      <td>
      <select name="year" id="year" onchange="xajax_CLASES_DE_HOY(document.getElementById('fecha').value, document.getElementById('semestre').value, document.getElementById('year').value, document.getElementById('sede').value); return false;">
	  <?php
	  	$anos_anteriores=10;
		$anos_siguientes=1;
	  	$year_actual=date("Y");
		
		$ano_ini=$year_actual-$anos_anteriores;
		$ano_fin=$year_actual+$anos_siguientes;
		
		for($a=$ano_ini;$a<=$ano_fin;$a++)
		{
			if($a==$year_actual)
			{echo'<option value="'.$a.'" selected="selected">'.$a.'</option>';	}
			else
			{echo'<option value="'.$a.'">'.$a.'</option>';}	
		}
	  ?>
      </select>
      </td>
    </tr>
    <tr>
      <td>Sede</td>
      <td>
	  <?php require("../../../../../funciones/funcion.php"); echo selector_sede("sede",'onchange="xajax_CLASES_DE_HOY(document.getElementById(\'fecha\').value, document.getElementById(\'semestre\').value, document.getElementById(\'year\').value, document.getElementById(\'sede\').value); return false;"');?>
      </td>
    </tr>
    </tbody>
  </table>
</div>
<div id="div_clases_hoy"></div>
</body>
<script type="text/javascript">
//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false,
		  onSelect     : actualizar,
          onTimeChange : actualizar
      });
	  
	  function actualizar() 
	  {
           xajax_CLASES_DE_HOY(document.getElementById('fecha').value, document.getElementById('semestre').value, document.getElementById('year').value, document.getElementById('sede').value);
		    return false;  
      };
      cal.manageFields("boton1", "fecha", "%Y-%m-%d");

    //]]>
</script>
</html>