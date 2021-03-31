<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_otros_pagos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Registro Ingreso Final</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:430px;
	height:115px;
	z-index:1;
	left: 234px;
	top: 162px;
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
<script language="javascript">
function ABRE_VENTANA(url)
{
	//alert(url);
	if(url=="")
	{
		alert('No Hay Boleta');
	}
	else
	{
		window.open(url,'boleta','height=450, width=350');
	}	
}
</script>  
</head>
<?php
if($_GET)
{
	$error=$_GET["error"];
	$id_boleta=$_GET["id_boleta"];
	
	if($id_boleta>0)
	{
			$abrir_ventana=true;
		switch($error)
		{
			case"0":
				$msj="Pago Registrado Exitosamente";
				$url_boleta="../contrato/imprimibles/boleta/boleta_1.php?id_boleta=$id_boleta";
				$img='<img src="../../BAses/Images/ok.png" />';
				break;
			case"1":
				$msj="Error Al intentar Realizar el pago";
				$img='<img src="../../BAses/Images/X.jpg" />';
				break;	
		}
	}
	else
	{
		$abrir_ventana=false;
		switch($error)
		{
			case"0":
				$msj="Pago Registrado Exitosamente";
				$img='<img src="../../BAses/Images/ok.png" />';
				break;
			case"1":
				$msj="Error Al intentar Realizar el pago";
				$img='<img src="../../BAses/Images/X.jpg" />';
				break;	
		}
	}
}
?>
<body <?php if($abrir_ventana){?>onload="ABRE_VENTANA('<?php echo $url_boleta;?>');"<?php }?>>
<h1 id="banner">Administrador- Registro de Ingresos</h1>
<div id="apDiv1">
  <p><strong><?php echo $msj;?></strong>
    <?php echo $img;?>
    <br />
Si la ventano no se abrio automaticamente, haga click aqui para Imprimir la boleta <a href="#" onclick="ABRE_VENTANA('<?php echo $url_boleta;?>');">IMPRIMIR BOLETA</a></p>
  <p align="center"><br />
    <a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver al Menu</a></p>
</div>
</body>
</html>