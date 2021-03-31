<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("contenidos->ver");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//


if(DEBUG){echo"Inicio configuracion inicial contenidos<br>";}
require("../../../../funciones/conexion_v2.php");
if($_GET){
	
	$semestre=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["semestre"]));
	$year=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["year"]));
	$sede=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["sede"]));
	$id_carrera=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["id_carrera"]));
	$cod_asignatura=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["cod_asignatura"]));
	$jornada=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["jornada"]));
	$grupo=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["grupo"]));
	$id_funcionario=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["id_funcionario"]));
}

$conexion_mysqli->close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<title>configuracion Inicial</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 166px;
}
#botonera {
	text-align:center;
	padding-top: 30px;
}
</style>
<script language="javascript">
function CONFIRMAR(){
	c=confirm('Seguro desea establecer este numero de semanas');
	if(c){document.getElementById('frm').submit();}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Contenidos configuracion Inicial</h1>
<div id="apDiv1">
<form action="configuracionInicial2.php" method="post" id="frm">
  <table width="50%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="2">Indique el numero de semanas en que realizara la asignatura</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="56%">Numero de Semanas</td>
      <td width="44%"><label for="numeroSemanas"></label>
        <select name="numeroSemanas" id="numeroSemanas">
        <?php
			for($x=1;$x<=18;$x++){
				
				echo'<option value="'.$x.'" selected="selected">'.$x.'</option>';
			}
        ?>
      </select></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">
        <input name="semestre" type="hidden" id="semestre" value="<?php echo $semestre;?>" />
        <input type="hidden" name="year" id="year" value="<?php echo $year;?>"/>
        <input type="hidden" name="sede" id="sede" value="<?php echo $sede;?>"/>
        <input type="hidden" name="id_carrera" id="id_carrera" value="<?php echo $id_carrera;?>"/>
        <input type="hidden" name="cod_asignatura" id="cod_asignatura" value="<?php echo $cod_asignatura;?>"/>
        <input type="hidden" name="jornada" id="jornada" value="<?php echo $jornada;?>"/>
        <input type="hidden" name="grupo" id="grupo" value="<?php echo $grupo;?>"/>
        <input type="hidden" name="id_funcionario" id="id_funcionario" value="<?php echo $id_funcionario;?>"/>
        
        </td>
    </tr>
    </tbody>
  </table>
 </form> 
  <div id="botonera"><a href="#" class="button_G" onclick="CONFIRMAR();">Continuar</a></div>
  <p>&nbsp;</p>
</div>
</body>
</html>