<?php
//--------------CLASS_okalis------------------//
require("../../OKALIS/class_OKALIS_v1.php");
define("DEBUG", false);
$O=new OKALIS();
$O->DEBUG=DEBUG;
$O->setDisplayErrors(false);
$O->ruta_conexion="../../../funciones/";
$O->clave_del_archivo=md5("Notas_parcialesV3->creacionManualEvaluaciones");
$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("n_evaluaciones_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"N_EVALUACIONES");
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");
$xajax->register(XAJAX_FUNCTION,"NOMBRE_EVALUACION");


if($_GET)
{
	if(DEBUG){ var_dump($_GET);}
	$sede=base64_decode($_GET["sede"]);
	$id_carrera=base64_decode($_GET["id_carrera"]);
	$jornada=base64_decode($_GET["jornada"]);
	$grupo_curso=base64_decode($_GET["grupo_curso"]);
	$cod_asignatura=base64_decode($_GET["cod_asignatura"]);
	$semestre=base64_decode($_GET["semestre"]);
	$year=base64_decode($_GET["year"]);
	
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>ingresar nueva Nota</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
	<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
	<link rel="stylesheet" type="text/css" href="../../../CSS/estilos_mass.css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:189px;
	z-index:3;
	left: 5%;
	top: 99px;
}
.Estilo1 {
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 12px;
}
#Layer2 {
	position:absolute;
	width:200px;
	height:85px;
	z-index:2;
	left: 93px;
	top: 98px;
}
#Layer3 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:1;
	left: 73px;
	top: 291px;
}
#Layer4 {
	position:absolute;
	width:37px;
	height:14px;
	z-index:4;
	left: 472px;
	top: 74px;
}
.Estilo8 {font-size: 12px}
#apDiv1 {
	position:absolute;
	width:50%;
	height:115px;
	z-index:4;
	left: 5%;
	top: 73px;
}
#apDiv2 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:5;
	left: 600px;
	top: 107px;
}
#apDiv3 {
	position:absolute;
	width:43%;
	height:20px;
	z-index:5;
	left: 52%;
	top: 277px;
	text-align: center;
}
#div_debug {
	position:absolute;
	width:30%;
	height:115px;
	z-index:6;
	left: 55%;
	top: 145px;
}
-->
</style>
<?php $xajax->printJavascript(); ?> 
<script language="javascript">
//script de verificacion simple
function CONFIRMAR()
{
	c=confirm('Seguro(a) Desea Grabar estas Evaluaciones...?');
	if(c){ document.getElementById('frmN').submit();}
}
</script>
</head>
<body>
<h1 id="banner">Administrador -  Notas Parciales, Evaluaciones</h1>

<div id="link"><br />
<a href="../ver_evaluaciones.php?sede=<?php echo base64_encode($sede);?>&id_carrera=<?php echo  base64_encode($id_carrera);?>&jornada=<?php echo  base64_encode($jornada);?>&grupo_curso=<?php echo  base64_encode($grupo_curso);?>&cod_asignatura=<?php echo  base64_encode($cod_asignatura);?>&semestre=<?php echo  base64_encode($semestre);?>&year=<?php echo  base64_encode($year);?>" class="button">Volver</a></div>
<div id="Layer1">
  <form action="nva_evaluacion_2.php" method="post" name="frmN" id="frmN" onsubmit="Comprobar();">
  <table width="50%" border="0" sumary="selector">
  <caption></caption>
  <thead>
    <tr>
      <th scope="col" colspan="4" bgcolor="#CCFF66"><div align="center" >Creacion de Evaluaciones</div></th>
    </tr>
	</thead>
	<tbody>
        <tr class="odd">
          <td colspan="2" >Sede</td>
          <td colspan="2" ><?php echo $sede;?><input name="sede" type="hidden" id="sede" value="<?php echo $sede;?>" /></td>
        </tr>
        <tr class="odd">
          <td colspan="2" >Carrera</td>
          <td colspan="2" ><?php echo $id_carrera;?><input name="id_carrera" type="hidden" id="id_carrera" value="<?php echo $id_carrera;?>" /></td>
        </tr>
        <tr class="odd">
          <td colspan="2" >Asignatura</td>
          <td colspan="2" ><?php echo $cod_asignatura; ?>
          <input name="fasignatura" id="fasignatura" type="hidden" value="<?php echo $cod_asignatura; ?>" /></td>
        </tr>
        <tr class="odd">
          <td width="98" >Jornada</td>
          <td width="53" ><?php echo $jornada;?><input name="jornada" type="hidden" id="jornada" value="<?php echo $jornada;?>" /></td>
          <td width="203" >Grupo</td>
          <td width="61" ><?php echo $grupo_curso;?><input name="grupo" type="hidden" id="grupo" value="<?php echo $grupo_curso;?>" /></td>
        </tr>
        <tr class="odd">
          <td >Semestre</td>
          <td ><?php echo $semestre;?><input name="semestre" type="hidden" id="semestre" value="<?php echo $semestre;?>" /></td>
          <td >A&ntilde;o</td>
          <td ><?php echo $year;?><input type="hidden" name="year" id="year" value="<?php echo $year;?>" /></td>
        </tr>
        <tr>
      <td colspan="2" ><span class="Estilo8">Cantidad de evaluaciones: </span></td>
      <td colspan="2" >
        <select name="fn_notas" id="fn_notas" onchange="xajax_N_EVALUACIONES(this.value, document.getElementById('metodo_evaluacion').value, document.getElementById('fasignatura').value, '<?php echo $sede;?>', '<?php echo $id_carrera;?>', '<?php echo $semestre;?>', '<?php echo $year;?>', '<?php echo $jornada;?>', '<?php echo $grupo_curso;?>');return false;">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="NN" selected="selected">Seleccione numero de Notas</option>
        </select>      </td>
    </tr>
     <tr>
          <td colspan="2" >Metodo Evaluacion</td>
          <td colspan="2" ><select name="metodo_evaluacion" id="metodo_evaluacion" onchange="xajax_N_EVALUACIONES(document.getElementById('fn_notas').value, document.getElementById('metodo_evaluacion').value, document.getElementById('fasignatura').value, '<?php echo $sede;?>', '<?php echo $id_carrera;?>', '<?php echo $semestre;?>', '<?php echo $year;?>', '<?php echo $jornada;?>', '<?php echo $grupo_curso;?>');return false;">
            <option value="normal" selected="selected">normal</option>
            <option value="ponderado">ponderado</option>
          </select></td>
        </tr>
    </tbody>
  </table>
<br />
  <div id="notas">* cantidad de Notas y metodo evaluacion</div>
</form>  
<div align="center"></div>
<?php
if($_GET)
{
	if(isset($_GET["error"]))
	{
		$error=$_GET["error"];
		switch($error)
		{
			case"1":
				$msj='ERROR.: Ingrese Correctamente las Notas...';
				break;
		}
	}
	else
	{ $msj="";}
}
else
{ $msj="";}
?>
<div align="center"><?php echo $msj;?></div>

</div>
<div id="apDiv3">...</div>
<div id="div_debug"></div>
</body>
</html>