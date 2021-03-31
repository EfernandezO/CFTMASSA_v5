<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG",false);
//-----------------------------------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("busca_docentes_periodo_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCAR_RESULTADOS");
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Resultados evalucion Docente</title>
<?php $xajax->printJavascript(); ?> 
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 105px;
}
#div_boton {
	text-align:center;
	position:absolute;
	width:40%;
	height:33px;
	z-index:2;
	left: 30%;
	top: 290px;
}
#div_resultados {
	position:absolute;
	width:40%;
	height:115px;
	z-index:3;
	left: 30%;
	top: 350px;
}
</style>
<script language="javascript" type="text/javascript">
function enviar_formulario()
{
	document.getElementById('frm').submit();
}
</script>
</head>

<body>
<h1 id="banner">Resultados Evalucion Docente</h1>
<div id="link"><br />
<a href="../okdocente.php" class="button">Volver a Seleccion</a></div>
<div id="apDiv1">
<?php 
	require("../../../funciones/funciones_sistema.php");
	require("../../../funciones/conexion_v2.php");
	$year_actual=date("Y");
	$mes_actual=date("m");
	if($mes_actual>=8){ $semestre_actual=2;}
	else{ $semestre_actual=1;}
	$ARRAY_ENCUESTAS=array();
	
	$cons_E="SELECT id_encuesta, nombre FROM encuestas_main WHERE utilizar_para_evaluacion_docente='1' ORDER BY id_encuesta";
	$sqli_E=$conexion_mysqli->query($cons_E)or die($conexion_mysqli->error);
	$num_encuestas=$sqli_E->num_rows;
	if($num_encuestas>0)
	{
		while($E=$sqli_E->fetch_assoc())
		{
			$id_encuesta=$E["id_encuesta"];
			$nombre_encuesta=$E["nombre"];
			
			$ARRAY_ENCUESTAS[$id_encuesta]=$nombre_encuesta;
		}
	}
	$sqli_E->free();
?>
<form action="resultados_evaluacion_docente_2.php" method="post" id="frm">
  <table width="60%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="3">Parametros de filtrado</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="42%">Seleccione la encuesta a revisar</td>
      <td width="58" colspan="2">
      <select name="id_encuesta" id="id_encuesta" onchange="xajax_BUSCAR_RESULTADOS(document.getElementById('id_encuesta').value, document.getElementById('sede').value, document.getElementById('semestre').value, document.getElementById('year').value);">
      <option value="0">Seleccione</option>
      	<?php
		if(count($ARRAY_ENCUESTAS)>0)
		{
			foreach($ARRAY_ENCUESTAS as $aux_id_encuesta => $aux_nombre_encuesta)
			{echo'<option value="'.$aux_id_encuesta.'">'.$aux_nombre_encuesta.'</option>';}
		}
		else
		{echo'<option value="0">Sin Encuestas Disponibles</option>';}
        ?>
      </select>
      </td>
    </tr>
    <tr>
      <td>Sede</td>
      <td colspan="2"><?php echo CAMPO_SELECCION("sede","sede","",false,'onchange="xajax_BUSCAR_RESULTADOS(document.getElementById(\'id_encuesta\').value, document.getElementById(\'sede\').value, document.getElementById(\'id_carrera\').value, document.getElementById(\'semestre\').value, document.getElementById(\'year\').value);"');?></td>
      </tr>
    <tr>
      <td>Perido</td>
      <td>semestre <?php echo CAMPO_SELECCION("semestre","semestre",$semestre_actual,false, 'onchange="xajax_BUSCAR_RESULTADOS(document.getElementById(\'id_encuesta\').value, document.getElementById(\'sede\').value, document.getElementById(\'semestre\').value, document.getElementById(\'year\').value);"');?></td>
      <td>year <?php echo CAMPO_SELECCION("year","year",$year_actual,false, 'onchange="xajax_BUSCAR_RESULTADOS(document.getElementById(\'id_encuesta\').value, document.getElementById(\'sede\').value, document.getElementById(\'semestre\').value, document.getElementById(\'year\').value);"');?></td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="div_resultados"></div>
</body>
</html>