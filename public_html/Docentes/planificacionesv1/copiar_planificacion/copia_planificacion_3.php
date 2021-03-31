<?php
//-----------------------------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Planificaciones->importar");
	$O->PERMITIR_ACCESO_USUARIO();
//-----------------------------------------//	
$id_carrera=$_GET["id_carrera"];
$cod_asignatura=$_GET["cod_asignatura"];
$sede=$_GET["sede"];
$semestre=$_GET["semestre"];
$year=$_GET["year"];
$jornada=$_GET["jornada"];
$grupo_curso=$_GET["grupo_curso"];
$error=$_GET["error"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Importar planificacion</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 142px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:25px;
	z-index:2;
	left: 5%;
	top: 340px;
	text-align:center;
}
#apDiv3 {
	position:absolute;
	width:90%;
	height:19px;
	z-index:3;
	left: 5%;
	top: 380px;
	text-align: center;
}
</style>
<!--CIERRA LIGHT BOX :D-->
<script language="javascript">
function BATIR()
{
	window.parent.jQuery.lightbox().shake();
	setTimeout("CERRAR()",1500);
}
function CERRAR()
{
	//alert("Se va a cerrar SexyLightbox");
	// Función necesaria para cerrar la ventana modal
	//window.parent.lightbox.close();
	
	window.parent.jQuery.lightbox().close();
	// Función necesaria para actualizar la ventana padre
	window.parent.document.location="../ver_planificaciones.php?id_carrera=<?php echo $id_carrera;?>&semestre=<?php echo $semestre;?>&year=<?php echo $year;?>&sede=<?php echo $sede;?>&cod_asignatura=<?php echo $cod_asignatura;?>&jornada=<?php echo $jornada;?>&grupo_curso=<?php echo $grupo_curso;?>";
}
setTimeout("BATIR()",1500);
</script>
<!--FIN CIERRE-->
</head>
<?php 

$img_ok='<img src="../../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
$img_error='<img src="../../../BAses/Images/b_drop.png" width="16" height="16" alt="error" />';
switch($error)
{
	case"CP1":
		$msj="Planificacion Importada Exitosamente";
		$img=$img_ok;
		break;
	default:
		$msj="";
		$img="";	
}

?>
<body>
<h1 id="banner">Administrador -Importar PlanificacionV1.0</h1>
<div id="apDiv1">
  <table width="70%" border="1" align="center">
  <thead>
    <tr>
      <th>INFORMACION</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td height="93" align="center"><?php echo "$img </br> $msj";?></td>
    </tr>
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
    </tbody>
  </table>
</div>
<div id="apDiv3"><a href="#" class="button_R" onclick="CERRAR();">Cerrar</a></div>
</body>
</html>