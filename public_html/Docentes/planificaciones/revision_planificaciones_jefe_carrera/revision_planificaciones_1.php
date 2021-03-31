<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//
//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("proceso_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_PLANIFICACIONES");
$xajax->register(XAJAX_FUNCTION,"BUSCA_SEDE");
$xajax->register(XAJAX_FUNCTION,"BUSCA_CARRERAS");
//---------------------------------------------------------///
	$id_funcionario=$_SESSION["USUARIO"]["id"];
	$year_actual=date("Y");
	$mes_actual=date("m");
	if($mes_actual>8){$semestre_actual=2;}
	else{ $semestre_actual=1;}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<title>Revision Planificacion</title>
<?php $xajax->printJavascript(); ?> 
<style type="text/css">
#div_planificaciones {
	position:absolute;
	width:90%;
	height:75px;
	z-index:1;
	left: 5%;
	top: 287px;
}
#apDiv2 {
	position:absolute;
	width:50%;
	height:66px;
	z-index:2;
	left: 5%;
	top: 72px;
}
</style>
<body onload="xajax_BUSCA_SEDE(document.getElementById('year').value, document.getElementById('semestre').value, document.getElementById('id_funcionario').value); return false;">
<h1 id="banner">Administrador - Revision Planificaciones X Carrera</h1>
<div id="link"><br />
<a href="../../okdocente.php" class="button">Volver al Menu</a></div>
<div id="div_planificaciones">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="12">Planificaciones Existentes <?php echo"[$semestre_actual - $year_actual]";?></th>
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
    <tr>
      <td colspan="12">Seleccione los parametros para revisar las planificaciones...</td>
      </tr>
    </tbody>
  </table>
</div>
<div id="apDiv2">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th width="100" colspan="2">Informacion</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td colspan="2">Periodo de Planificaciones
        <input name="id_funcionario" type="hidden" id="id_funcionario" value="<?php echo $id_funcionario;?>" /></td>
    </tr>
    <tr>
      <td>A&ntilde;o</td>
      <td><select name="year" id="year" onchange="xajax_BUSCA_SEDE(document.getElementById('year').value, document.getElementById('semestre').value, document.getElementById('id_funcionario').value); return false;">
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
        </select></td>
    </tr>
    <tr>
      <td>Semestre</td>
      <td> <select name="semestre" id="semestre" onchange="xajax_BUSCA_SEDE(document.getElementById('year').value, document.getElementById('semestre').value, document.getElementById('id_funcionario').value); return false;">
          <option value="1" <?php if($semestre_actual==1){ echo'selected="selected"';}?>>1</option>
          <option value="2" <?php if($semestre_actual==2){ echo'selected="selected"';}?>>2</option>
          </select>
        </td>
    </tr>
    <tr>
      <td>Sede</td>
      <td><div id="div_sede">...</div></td>
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