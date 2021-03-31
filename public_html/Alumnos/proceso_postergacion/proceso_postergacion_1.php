<?php

//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno->proceso_postergacion_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

$hay_id=false;
if(isset($_GET["P_id"]))
{
	$P_id=base64_decode($_GET["P_id"]);
	if(is_numeric($P_id)){$hay_id=true;}
}


$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
$array_motivo_postergacion=array("1"=>"Dificultades Economicas",
						   "2"=>"No obtener beca ni financiamiento",
						   "3"=>"Excluido por Motivos Diciplinarios",
						   "4"=>"Retiro por aplazamiento del semestre",
						   "5"=>"Excluido por bajo rendimiento academico",
						   "6"=>"No cumplimiento con expectativas academicas",
						   "7"=>"No cumplimiento con expectativas de equipamiento",
						   "8"=>"Erronea eleccion de carrera a estudiar",
						   "9"=>"Cambio a otra institucion",
						   "10"=>"Dificultades familiares",
						   "11"=>"Problemas de Salud",
						   "12"=>"Cambio Domicilio personal a otra ciudad",
						   "13"=>"Cambio de ubicacion o condicion Laboral",
						   "14"=>"No se imparte la carrera");
						   
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");

$deuda_actual_alumno=DEUDA_ACTUAL($id_alumno);
if(empty($deuda_actual_alumno)){$deuda_actual_alumno=0;}

if(($deuda_actual_alumno>0)and(1==2)){$action=""; $js_script='<script language="javascript">
function CONFIRMAR()
{
	alert(\'Imposible Realizar Proceso de Postergacion \n alumno Con Deuda $ '.$deuda_actual_alumno.' \n Regularizar antes de Continuar... \');
}
</script>';}
else{ $action="proceso_postergacion_2.php"; $js_script='<script language="javascript">
function CONFIRMAR()
{
	c=confirm("Seguro(a) desea Realizar el proceso de Postergacion a este Alumno..?");
	if(c){ document.getElementById(\'frm\').submit();}
}
</script>';}						   
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php"); ?>
<title>Proceso de Postergacion</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 84px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:22px;
	z-index:2;
	left: 5%;
	top: 285px;
}
#apDiv2 {
	border: medium solid #39C;
}
#apDiv3 {
	position:absolute;
	width:60%;
	height:28px;
	z-index:3;
	left: 20%;
	top: 345px;
	text-align: center;
}
</style>
<?php echo $js_script;?>
</head>

<body>
<h1 id="banner">Administrador - Proceso Postergacion</h1>
<div id="link"><a class="button" href="proceso_postergacion_0.php">Volver</a></div>
<div id="apDiv2">
  <?php
$hay_proceso_postergacion=false;

if($hay_id)
{
	$cons_PR="SELECT * FROM proceso_postergacion WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND id_postergacion='$P_id' LIMIT 1";
	$sql_PR=$conexion_mysqli->query($cons_PR);
	$num_registro=$sql_PR->num_rows;
		$hay_proceso_postergacion=true;
		$DPR=$sql_PR->fetch_assoc();
			$postergacion_id=$DPR["id_postergacion"];
			$postergacion_motivo=$DPR["motivo"];
			$postergacion_observacion=$DPR["observacion"];
			$postergacion_fecha_generacion=$DPR["fecha_generacion"];
			$postergacion_year=$DPR["year_postergacion"];
			$postergacion_semestre=$DPR["semestre_postergacion"];
			$postergacion_cod_user=$DPR["cod_user"];
			$postergacion_semestres_suspencion=$DPR["semestres_suspencion"];
				////////////////////
		$nombre_usuario=NOMBRE_PERSONAL($postergacion_cod_user);	
			//////////////////////
			$sql_PR->free();	
}
else
{
		$postergacion_id=0;
		$postergacion_motivo=0;
		$postergacion_observacion="";
		$postergacion_fecha_generacion="";
		$postergacion_cod_user="";
		$postergacion_semestre="";
		$postergacion_year="";
		$postergacion_semestres_suspencion=0;
}
	
$conexion_mysqli->close();

if($hay_proceso_postergacion){ echo"Proceso Postergacion ya iniciado el <strong>$postergacion_fecha_generacion</strong> por <strong>[$postergacion_cod_user] $nombre_usuario</strong>";}
else{ echo"Sin Proceso Postergacion creado...";}
?>
</div>

<div id="apDiv1">
<form action="<?php echo $action;?>" method="post" enctype="multipart/form-data" id="frm">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="3">Proceso Postergacion
        <input name="postergacion_id" type="hidden" id="postergacion_id" value="<?php echo $postergacion_id;?>" /></th>
    </tr>
    <tr>
      <td>Periodo Postergacion</td>
      <td><?php echo CAMPO_SELECCION("semestre", "semestre", $postergacion_semestre, false); ?> semestre</td>
      <td><?php echo CAMPO_SELECCION("year", "year", $postergacion_year, false); ?> año</td>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>Semestres de suspencion</td>
      <td colspan="2"><label for="semestres_suspencion"></label>
        <select name="semestres_suspencion" id="semestres_suspencion">
          <?php
		for($x=1;$x<=10;$x++)
		{ echo'<option value="'.$x.'">'.$x.'</option>';}
        ?>
          </select></td>
    </tr>
    <tr>
      <td width="41%">Motivo del Postergacion</td>
      <td width="59" colspan="2"><label for="postergacion_motivo"></label>
        <select name="postergacion_motivo" id="postergacion_motivo">
          <?php foreach($array_motivo_postergacion as $n => $valor)
				{ if($n==$postergacion_motivo){echo'<option value="'.$n.'" selected="selected">['.$n.'] '.$valor.'</option>';}else{echo'<option value="'.$n.'">['.$n.'] '.$valor.'</option>';}}?>
          </select></td>
    </tr>
    <tr>
      <td>Observacion</td>
      <td colspan="2"><label for="postergacion_descripcion"></label>
        <textarea name="postergacion_descripcion" cols="50" id="postergacion_descripcion"><?php echo $postergacion_observacion?></textarea></td>
      </tr>
  

    </tbody>
  </table>
  </form>
</div>
<div id="apDiv3"><a href="#" class="button_G" onclick="CONFIRMAR();">Postergar este Alumno</a></div>
</body>
</html>