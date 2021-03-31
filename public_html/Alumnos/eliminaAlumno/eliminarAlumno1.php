<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Eliminacion_registro_Alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
$observacionE="Retiro antes del inicio de clases";						   
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");

$deuda_actual_alumno=DEUDA_ACTUAL($id_alumno);
if(empty($deuda_actual_alumno)){$deuda_actual_alumno=0;}


$action="eliminarAlumno2.php"; 

$js_script='<script language="javascript">
function CONFIRMAR()
{
	var codigo_aleatorio='.date("YmdHi").';';
	
	if($deuda_actual_alumno>0){
		$js_script.='alert(\'alumno Con Deuda $ '.$deuda_actual_alumno.' \n Considere Regularizar antes de Continuar... \');';
	}
	$js_script.='c=confirm("Seguro(a) desea ELIMINAR a este Alumno?");
	if(c){
		CX=prompt("Ingrese el siguiente codigo para poder continuar con la ELIMINACION del alumno\n CODIGO: "+codigo_aleatorio+"\n ");
		if(CX==codigo_aleatorio){
		 	document.getElementById(\'frm\').submit();
		 	}
	}
}
</script>';						   

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php"); ?>
<title>Eliminar Alumno</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 62px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:30px;
	z-index:2;
	left: 5%;
	top: 230px;
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
	top: 311px;
	text-align: center;
}
</style>
<?php echo $js_script;?>
</head>

<body>
<h1 id="banner">Administrador - Eliminar Alumno</h1>
<div id="apDiv2">

</div>

<div id="apDiv1">
<form action="<?php echo $action;?>" method="post" enctype="multipart/form-data" id="frm">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="3">Proceso Eliminacion Alumno
        <input name="pendiente_id" type="hidden" id="pendeinte_id" value="<?php echo $pendiente_id;?>" /></th>
    </tr>
    </thead>
    <tbody>
   
    <tr>
      <td>Observacion para evento</td>
      <td colspan="2"><label for="observacion"></label>
        <textarea name="observacion" cols="50" id="observacion"><?php echo $observacionE;?></textarea></td>
    </tr>
    <tr>
      <td rowspan="2">Como Eliminar</td>
      <td colspan="2"><input name="metodoEliminacion" type="radio" id="eliminar1" value="solo_contrato" checked="checked" />
        solo contrato y registros academicos</td>
    </tr>
    <tr>
      <td colspan="2"><input type="radio" name="metodoEliminacion" id="eliminar2" value="todo" /> 
        Todos</td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="apDiv3"><a href="#" class="button_G" onclick="CONFIRMAR();">ELIMINAR REGISTROS de Alumno</a></div>
</body>
</html>