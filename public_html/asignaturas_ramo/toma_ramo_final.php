<?php
//--------------CLASS_okalis------------------//
	require("../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Toma_de_ramos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

$mostrar_boton=false;
$url_imprimir="#";
if(isset($_GET["error"]))
{
	$semestre=$_GET["semestre"];
	$year=$_GET["year"];
	$yearIngresoCarrera=$_GET["yearIngresoCarrera"];
	$error=$_GET["error"];
	$img_ok='<img src="../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
	$img_error='<img src="../BAses/Images/b_drop.png" width="16" height="16" alt="X" />';
	
	switch($error)
	{
		case"T0":
			$msj="Toma de Ramo Realizada Correctamente...";
			$img=$img_ok;
			$mostrar_boton=true;
			$url_imprimir="ver_toma_ramo/ver_tomaramo_individual.php?semestre=$semestre&year=$year&yearIngresoCarrera=$yearIngresoCarrera";
			break;
		case"T1":
			$msj="Error al Crear Toma de Ramos intentelo mas Tarde...";
			$img=$img_error;
			break;	
	}
	
}
else
{
	$msj="";
	$img="";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../funciones/codificacion.php");?>
<title>Toma de Ramo Final</title>
<link rel="stylesheet" type="text/css" href="../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 174px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Toma de Ramo v1.1</h1>
<div id="link"><br>
  <a href="../buscador_alumno_BETA/HALL/index.php" class="button">
Volver al Menu</a><br />
<br />
<a href="tomaramo_individual.php" class="button">Volver a Toma de Ramos</a>
</div>
<div id="apDiv1"> 
  <table width="50%" border="1" align="center">
  <thead>
    <tr>
      <th>INFORMACION</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td height="80" align="center"><?php echo" $img $msj";?></td>
    </tr>
    <tr>
      <td align="center"><?php if($mostrar_boton){?>
 			<a href="<?php echo $url_imprimir;?>" class="button_R" target="_blank">Imprimir Toma de Ramo</a>
          <?php }?></td>
    </tr>
    </tbody>
  </table>
</div>
</body>
</html>