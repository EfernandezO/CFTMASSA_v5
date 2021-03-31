<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno->asignacion de Becas V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(!$_GET)
{ header("location: asignar_beca_1.php");}

if(isset($_SESSION["FINANZASX"])){unset($_SESSION["FINANZASX"]);}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_3.css">
<title>Asignar Cuota - Alumno</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:1;
	left: 461px;
	top: 103px;
}
#apDiv2 {
	position:absolute;
	width:511px;
	height:43px;
	z-index:1;
	left: 138px;
	top: 103px;
}
#apDiv3 {
	position:absolute;
	width:90%;
	height:29px;
	z-index:1;
	left: 0px;
	top: 129px;
}
a:link {
	color: #006699;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #006699;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #006699;
}
-->
</style>
</head>
<?php
$error=$_GET["error"];

	
	$img_ok='<img src="../../BAses/Images/ok.png" />';
	$img_error='<img src="../../BAses/Images/b_drop.png" />';
	switch($error)
	{
		case"0":
				$msj="Se ha Generado un Nuevo contrato</br> y cuotas exitosamente";
			break;
		case"1":
			   $msj="ERROR, No se Generado un Nuevo contrato y cuotas ";
			
			break;	
	}
	
?>
<body>
<h1 id="banner">Administrador - Asignacion Beca</h1>
<div id="link"><a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver al Menu</a></div>
<div id="apDiv3">
  <div align="center"><?php echo $msj;?> <?php echo $IMG;?></div>
  
  <div id="super_link" >
    <div align="center">
      <p>&nbsp;</p>
      <p><a href="../informe_financiero_alumno/index.php" class="button_R">Ir a Contratos</a></p>
    </div>
  </div>
</div>
</body>
</html>