<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Cambio_jornada_alumno");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
{
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	
							   
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	
	$deuda_actual_alumno=DEUDA_ACTUAL($id_alumno);
	if(empty($deuda_actual_alumno)){$deuda_actual_alumno=0;}
	
	if(($deuda_actual_alumno>0)and(1==2)){$action=""; $js_script='<script language="javascript">
	function CONFIRMAR()
	{
		alert(\'Imposible cambiar de Jornada al alumno \n alumno Con Deuda $ '.$deuda_actual_alumno.' \n Regularizar antes de Continuar... \');
	}
	</script>';}
	else{$action="cambio_jornada_2.php"; $js_script='<script language="javascript">
	function CONFIRMAR()
	{
		c=confirm("Seguro(a) desea Realizar el Cambio de Jornada de este Alumno..?");
		if(c){ document.getElementById(\'frm\').submit();}
	}
	</script>';}			
}
else
{
	$js_script='<script language="javascript">
	function CONFIRMAR()
	{
		alert(\'Sin Alumno seleccionado, No se puede continuar \');
	}
	</script>';
}			   
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php"); ?>
<title>Cambio de Jornada</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 82px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:22px;
	z-index:2;
	left: 5%;
	top: 283px;
}
#apDiv2 {
	border: medium solid #39C;
}
#apDiv3 {
	position:absolute;
	width:90%;
	height:28px;
	z-index:3;
	left: 5%;
	top: 350px;
	text-align: center;
}
</style>
<?php echo $js_script;?>
</head>

<body>
<h1 id="banner">Administrador - Proceso Cambio Jornada</h1>
<div id="apDiv2">
<?php

if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
{
	$year_actual=date("Y");
	$mes_actual=date("M");
	
	if($mes_actual>=8){$semestre_actual=2;}
	else{$semestre_actual=1;}
	
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$jornada_actual_alumno=$_SESSION["SELECTOR_ALUMNO"]["jornada"];
	
	if($jornada_actual_alumno=="V")
	{$jornada_nueva_alumno="D";}
	else
	{$jornada_nueva_alumno="V";}
	
	$array_jornadas=array("V"=>"Vespertino", "D"=>"Diurno");
	
}
else
{echo"sin alumno seleccionado"; }
?>
</div>

<div id="apDiv1">
<form action="<?php echo $action;?>" method="post" enctype="multipart/form-data" id="frm">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="3">Cambio Jornada
        <input name="id_alumno" type="hidden" id="id_alumno" value="<?php echo $id_alumno;?>" />
        <input name="id_carrera" type="hidden" id="id_carrera" value="<?php echo $id_carrera;?>" /></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>Periodo Cambio</td>
      <td><?php echo CAMPO_SELECCION("semestre", "semestre", $semestre_actual, false); ?>semestre</td>
      <td><?php echo CAMPO_SELECCION("year", "year", $year_actual, false); ?>
        año</td>
    </tr>
    <tr>
      <td>Jornada Actual</td>
      <td colspan="2"><?php echo $array_jornadas[$jornada_actual_alumno];?>
        <input name="jornada_old" type="hidden" id="jornada_old" value="<?php echo $jornada_actual_alumno;?>" /></td>
    </tr>
    <tr>
      <td width="41%">Nueva Jornada</td>
      <td width="59" colspan="2"><?php echo $array_jornadas[$jornada_nueva_alumno];?>
        <input name="jornada_nueva" type="hidden" id="jornada_nueva" value="<?php echo $jornada_nueva_alumno;?>" /></td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="apDiv3"><a href="#" class="button_G" onclick="CONFIRMAR();">Cambiar Jornada de este Alumno</a></div>
</body>
</html>