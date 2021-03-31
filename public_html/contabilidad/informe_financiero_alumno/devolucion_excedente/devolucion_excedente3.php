<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Impresion_Y_Gestion_de_contratos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])and($_GET))
{
	$continuar=true;
	$id_contrato=$_GET["id_contrato"];
	$id_pago=$_GET["id_pago"];
}
else
{ $continuar=false;}

if($continuar)
{ 
	if(DEBUG){echo"Datos Correcto...:D<br>";}
	$_SESSION["DEVOLUCION"]["verificador"]=true;
}
else
{
	if(DEBUG){ echo"Datos incorrectos ...<br>";}
	else
	{ header("location: ../index.php");}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
#Layer1 {
	position:absolute;
	width:90%;
	height:81px;
	z-index:3;
	left: 5%;
	top: 82px;
}
#apDiv1 {
	position:absolute;
	width:90%;
	height:49px;
	z-index:4;
	left: 5%;
	top: 198px;
	text-align: center;
}
</style>
</head>

<body>
<h1 id="banner">Administrador- Devoluci&oacute;n de Excedente Final</h1>
<div id="link"></div>
<div id="Layer1">
    <table width="40%" border="0" align="center">
    <thead>
      <tr>
        <th height="31">Informacion</th>
      </tr>
      </thead>
      <tbody>
      <tr >
        <td>
        <?php 
		if(isset($_GET["error"]))
		{
			$error=$_GET["error"];
			if($error==0)
			{ $msj='Devolucion Realizada Correctamente <img src="../../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';}
			else
			{ $msj=$error.'<img src="../../../BAses/Images/b_drop.png" width="16" height="16" alt="X" />';}
			echo"$msj";
		}
		?>
        </td>
      </tr>
      <tr >
        <td align="center"><a href="ver_comprobante_devolucion.php?id_pago=<?php echo $id_pago;?>" target="_blank" class="button_R">Imprimir Comprobante</a></td>
      </tr>
      </tbody>
    </table>
</div>
<div id="apDiv1"><br />
<a href="../informe_finan1.php?id_contrato=<?php echo $id_contrato;?>" class="button">Volver al Contrato</a></div>
</body>
</html>