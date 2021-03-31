<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Proceso_Pendiente_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];

$array_motivo=array("1"=>"Dificultades Economicas",
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
	alert(\'Imposible Dejar a Alumno como PENDIENTE \n alumno Con Deuda $ '.$deuda_actual_alumno.' \n Regularizar antes de Continuar... \');
}
</script>';}
else{ $action="proceso_pendiente_2.php"; $js_script='<script language="javascript">
function CONFIRMAR()
{
	c=confirm("Seguro(a) desea Realizar el proceso para dejar este alumno como PENDIENTE..?");
	if(c){ document.getElementById(\'frm\').submit();}
}
</script>';}						   

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php"); ?>
<title>pendiente</title>
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
	top: 374px;
	text-align: center;
}
</style>
<?php echo $js_script;?>
</head>

<body>
<h1 id="banner">Administrador - Alumnos Pendientes</h1>
<div id="apDiv2">
<?php
$hay_proceso_pendiente=false;


	$cons_PR="SELECT * FROM proceso_pendiente WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' LIMIT 1";
	$sql_PR=$conexion_mysqli->query($cons_PR);
	$num_registro=$sql_PR->num_rows;
	if($num_registro>0)
	{ 
		$hay_proceso_pendiente=true;
		$DPR=$sql_PR->fetch_assoc();
			$pendiente_id=$DPR["id_pendiente"];
			$pendiente_motivo=$DPR["motivo"];
			$pendiente_observacion=$DPR["observacion"];
			
			$pendiente_semestre=$DPR["semestre"];
			$pendiente_year=$DPR["year"];
			
			$pendiente_fecha_generacion=$DPR["fecha_generacion"];
			$pendiente_cod_user=$DPR["cod_user"];
			
				////////////////////
			$cons_user="SELECT nombre, apellido FROM personal WHERE id ='$pendiente_cod_user'";
			$sql_user=$conexion_mysqli->query($cons_user);
			$DU=$sql_user->fetch_assoc();
			$nombre=$DU["nombre"];
			$apellido=$DU["apellido"];
			$usuario_nombre=$nombre." ".$apellido;
			$sql_user->free();
			//////////////////////
	}
	else
	{
			$pendiente_id=0;
			$pendiente_motivo=0;
			$pendiente_observacion="";
			
			$pendiente_fecha_generacion="";
			$pendiente_cod_user="";
			$pendiente_semestre="";
			$pendiente_year="";
	}
	$sql_PR->free();	
@mysql_close($conexion);
$conexion_mysqli->close();

if($hay_proceso_pendiente){ echo"Alumno con Proceso de PENDIENTE ya iniciado el <strong>$pendiente_fecha_generacion</strong> por <strong>[$pendiente_cod_user] $usuario_nombre</strong>";}
else{ echo"Alumno sin Proceso de PENDIENTE...";}
?>
</div>

<div id="apDiv1">
<form action="<?php echo $action;?>" method="post" enctype="multipart/form-data" id="frm">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="3">Proceso Pendiente
        <input name="pendiente_id" type="hidden" id="pendeinte_id" value="<?php echo $pendiente_id;?>" /></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>Periodo proceso</td>
      <td><?php echo CAMPO_SELECCION("semestre", "semestre",$pendiente_semestre, false); ?> semestre</td>
      <td><?php echo CAMPO_SELECCION("year", "year", $pendiente_year, false); ?>
        año</td>
    </tr>
    <tr>
      <td width="41%">Motivo del proceso</td>
      <td width="59" colspan="2"><label for="pendiente_motivo"></label>
        <select name="pendiente_motivo" id="pendeinte_motivo">
          <?php foreach($array_motivo as $n => $valor)
				{ if($n==$pendiente_motivo){echo'<option value="'.$n.'" selected="selected">['.$n.'] '.$valor.'</option>';}else{echo'<option value="'.$n.'">['.$n.'] '.$valor.'</option>';}}?>
          </select></td>
    </tr>
    <tr>
      <td>Observacion</td>
      <td colspan="2"><label for="pendiente_descripcion"></label>
        <textarea name="pendiente_descripcion" cols="50" id="pendiente_descripcion"><?php echo $pendiente_observacion?></textarea></td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="apDiv3"><a href="#" class="button_G" onclick="CONFIRMAR();">Dejar como PENDIENTE a este Alumno</a></div>
</body>
</html>