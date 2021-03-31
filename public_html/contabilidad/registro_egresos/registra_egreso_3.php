<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("registra_egresos");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Registro Egresos</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>

<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:112px;
	z-index:1;
	left: 5%;
	top: 105px;
}
#div_formulario {
	position:absolute;
	width:90%;
	height:160px;
	z-index:2;
	left: 5%;
	top: 246px;
}
</style>
<?php
$msj="";
$img="";
if($_GET)
{
	$error=$_GET["error"];
	$img_ok='<img src="../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
	$img_error='<img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="X" />';
	
	$ver_comprobante_egreso="";
	if(isset($_GET["id_comprobante_egreso"])){
		$id_comprobante_egreso=$_GET["id_comprobante_egreso"];
		$ver_comprobante_egreso='<a href="comprobanteEgreso/ver/comprobante_egreso_pdf.php?id_comprobante_egreso='.$id_comprobante_egreso.'" target="_blank">Ver Comprobante Egreso</a>';
		
	}
	switch($error)
	{
		case"RE0":
			$msj="Egreso Registrado Exitosamente<br>".$ver_comprobante_egreso;
			$img=$img_ok;
			break;
		case"RE1":
			$msj="Fallo al Registrar Egreso";
			$img=$img_error;
			break;	
	}
}
?>
</head>
<body>
<h1 id="banner">Administrador- Registro de Egresos</h1>
<div id="link"><br />
<a href="../index.php" class="button">Volver al Menu</a><br />
<a href="registra_egreso_1.php" class="button_R">Registrar Otro Egreso</a><br />
</div>
<div id="apDiv1">
  <table width="60%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="2">Informacion</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="48%" height="66" align="center"><?php echo $msj.$img;?></td>
      
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  
    </tbody>
  </table>
</div>

</body>
</html>