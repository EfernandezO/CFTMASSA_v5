<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Proceso_Retiro_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
$yearIngresoCarrera=$_SESSION["SELECTOR_ALUMNO"]["yearIngresoCarrera"];

$array_motivo_retiro=array("1"=>"Dificultades Economicas",
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
	alert(\'Imposible Realizar Proceso de Retiro \n alumno Con Deuda $ '.$deuda_actual_alumno.' \n Regularizar antes de Continuar... \');
}
</script>';}
else{ $action="proceso_retiro_2.php"; $js_script='<script language="javascript">
function CONFIRMAR()
{
	 document.getElementById(\'frm\').submit();
}
</script>';}						   

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php"); ?>
<title>Proceso de Retiro</title>
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
	width:60%;
	height:28px;
	z-index:3;
	left: 20%;
	top: 350px;
	text-align: center;
}
#apDiv4 {
	position:absolute;
	width:90%;
	height:70px;
	z-index:2;
	left: 5%;
	top: 427px;
}
#apDiv4 {	border: medium solid #39C;
}
</style>
<?php echo $js_script;?>
</head>

<body>
<h1 id="banner">Administrador - Proceso Retiro</h1>
<div id="apDiv2">
<?php
$hay_proceso_retiro=false;
$year_actual=date("Y");
$mes_actual=date("M");

if($mes_actual>=8){$semestre_actual=2;}
else{$semestre_actual=1;}


	$cons_PR="SELECT * FROM proceso_retiro WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' LIMIT 1";
	$sql_PR=$conexion_mysqli->query($cons_PR);
	$num_registro=$sql_PR->num_rows;
	if($num_registro>0)
	{ 
		$hay_proceso_retiro=true;
		$DPR=$sql_PR->fetch_assoc();
			$retiro_id=$DPR["id_retiro"];
			$retiro_motivo=$DPR["motivo"];
			$retiro_observacion=$DPR["observacion"];
			$retiro_presenta_carta=$DPR["presento_carta_retiro"];
			$retiro_posible_reincorporacion=$DPR["posible_reincorporacion"];
			$retiro_fecha_generacion=$DPR["fecha_generacion"];
			$retiro_cod_user=$DPR["cod_user"];
			$retiro_semestre=$DPR["semestre_retiro"];
			$retiro_year=$DPR["year_retiro"];
				////////////////////
			$cons_user="SELECT nombre, apellido_P FROM personal WHERE id ='$retiro_cod_user'";
			$sql_user=$conexion_mysqli->query($cons_user);
			$DU=$sql_user->fetch_assoc();
			$nombre=$DU["nombre"];
			$apellido=$DU["apellido_P"];
			$usuario_nombre=$nombre." ".$apellido;
			$sql_user->free();
			//////////////////////
	}
	else
	{
			$retiro_id=0;
			$retiro_motivo=0;
			$retiro_observacion="";
			$retiro_presenta_carta="no";
			$retiro_posible_reincorporacion="no";
			$retiro_fecha_generacion="";
			$retiro_cod_user="";
			
			
			$retiro_semestre=$semestre_actual;;
			$retiro_year=$year_actual;
	}
	$sql_PR->free();	

if($hay_proceso_retiro){ echo"Proceso retiro ya iniciado el <strong>$retiro_fecha_generacion</strong> por <strong>[$retiro_cod_user] $usuario_nombre</strong>";}
else{ echo"Sin Proceso Retiro creado...";}
?>
</div>

<div id="apDiv1">
<form action="<?php echo $action;?>" method="post" enctype="multipart/form-data" id="frm">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="3">Proceso Retiro
        <input name="retiro_id" type="hidden" id="retiro_id" value="<?php echo $retiro_id;?>" /></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>Periodo Retiro</td>
      <td><?php echo CAMPO_SELECCION("semestre", "semestre", $retiro_semestre, false); ?> semestre</td>
      <td><?php echo CAMPO_SELECCION("year", "year", $retiro_year, false); ?>
        año</td>
    </tr>
    <tr>
      <td width="41%">Motivo del Retiro</td>
      <td width="59" colspan="2"><label for="retiro_motivo"></label>
        <select name="retiro_motivo" id="retiro_motivo">
          <?php foreach($array_motivo_retiro as $n => $valor)
				{ if($n==$retiro_motivo){echo'<option value="'.$n.'" selected="selected">['.$n.'] '.$valor.'</option>';}else{echo'<option value="'.$n.'">['.$n.'] '.$valor.'</option>';}}?>
          </select></td>
    </tr>
    <tr>
      <td>Observacion</td>
      <td colspan="2"><label for="retiro_descripcion"></label>
        <textarea name="retiro_descripcion" cols="50" id="retiro_descripcion"><?php echo $retiro_observacion?></textarea></td>
      </tr>
    <tr>
      <td>Presenta Carta Retiro </td>
      <td><input type="radio" name="retiro_presenta_carta" id="retiro_presenta_carta" value="si" <?php if($retiro_presenta_carta=="si"){ echo'checked="checked"';}?>/>
        <label for="retiro_presenta_carta">Si</label></td>
      <td><input name="retiro_presenta_carta" type="radio" id="retiro_presenta_carta2" value="no" <?php if($retiro_presenta_carta=="no"){ echo'checked="checked"';}?>/>
        No</td>
    </tr>
    <tr>
      <td>Posible reincorporacion &iquest;?</td>
      <td><input type="radio" name="retiro_posible_reincorporacion" id="radio" value="si" <?php if($retiro_posible_reincorporacion=="si"){ echo'checked="checked"';}?>/>
        <label for="retiro_posible_reincorporacion">Si</label></td>
      <td><input name="retiro_posible_reincorporacion" type="radio" id="radio2" value="no"  <?php if($retiro_posible_reincorporacion=="no"){ echo'checked="checked"';}?> />
        No</td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="apDiv3"><a href="#" class="button_G" onclick="CONFIRMAR();">Continuar con el Retiro</a></div>
<div id="apDiv4">Todos los procesos de retiro Registrados:<br>
  <?php
	$cons_PR="SELECT * FROM proceso_retiro WHERE id_alumno='$id_alumno' ";
	$sql_PR=$conexion_mysqli->query($cons_PR);
	$num_registro=$sql_PR->num_rows;
	if($num_registro>0)
	{ 
		while($DPR=$sql_PR->fetch_assoc()){
			$retiro_id=$DPR["id_retiro"];
			$retiro_motivo=$DPR["motivo"];
			$retiro_observacion=$DPR["observacion"];
			$retiro_presenta_carta=$DPR["presento_carta_retiro"];
			$retiro_posible_reincorporacion=$DPR["posible_reincorporacion"];
			$retiro_fecha_generacion=$DPR["fecha_generacion"];
			$retiro_cod_user=$DPR["cod_user"];
			$retiro_semestre=$DPR["semestre_retiro"];
			$retiro_year=$DPR["year_retiro"];
			$retiro_id_carrera=$DPR["id_carrera"];
			$retiro_yearIngresoCarrera=$DPR["yearIngresoCarrera"];
				////////////////////
			$cons_user="SELECT nombre, apellido_P FROM personal WHERE id ='$retiro_cod_user'";
			$sql_user=$conexion_mysqli->query($cons_user);
			$DU=$sql_user->fetch_assoc();
			$nombre=$DU["nombre"];
			$apellido=$DU["apellido_P"];
			$usuario_nombre=$nombre." ".$apellido;
			
			 echo"<strong>$retiro_id $retiro_fecha_generacion</strong> por <strong>[$retiro_cod_user] $usuario_nombre </strong> id_carrera: $retiro_id_carrera yearIngresoCarrera: $retiro_yearIngresoCarrera<br><tt> ".$array_motivo_retiro[$retiro_motivo]." - $retiro_observacion -</tt><br>";
		}
			$sql_user->free();
			//////////////////////
	}
	else
	{
			$retiro_id=0;
			$retiro_motivo=0;
			$retiro_observacion="";
			$retiro_presenta_carta="no";
			$retiro_posible_reincorporacion="no";
			$retiro_fecha_generacion="";
			$retiro_cod_user="";
			
			
			$retiro_semestre=$semestre_actual;;
			$retiro_year=$year_actual;
	}
	$sql_PR->free();	
$conexion_mysqli->close();
?>
</div>
</body>
</html>