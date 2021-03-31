<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Genera_honorario_1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(isset($_GET["sede"])){$sede=$_GET["sede"];}
else{ $sede=0;}

if(isset($_GET["year"])){$year=$_GET["year"];}
else{ $year=0;}

if(isset($_GET["mes"])){$mes=$_GET["mes"];}
else{ $mes=0;}

if(isset($_GET["year_generacion"])){$year_generacion=$_GET["year_generacion"];}
else{ $year_generacion=0;}

if(isset($_SESSION["HONORARIO"])){ unset($_SESSION["HONORARIO"]);}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Honorario Docente | Generacion Final</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 149px;
}
</style>
</head>

<body>
<h1 id="banner">Funcionarios - Generacion de Honorario</h1>
<div id="link"><br>
<a href="../../lista_funcionarios.php" class="button">Volver al Menu</a></div>
<div id="apDiv1">
  <table width="50%" border="1" align="center">
  <thead>
    <tr>
      <th>INFORMACION</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td height="81" align="center">Honorarios Docentes Generados Correctamentes<br />        <img src="../../../BAses/Images/ok.png" width="29" height="26" alt="ok" /><br /></td>
    </tr>
    <tr>
      <td align="center" ><a href="../ver_resumen_mensual/honorario_docente_resumen_pdf.php?sede=<?php echo $sede;?>&mes=<?php echo $mes;?>&year_generacion=<?php echo $year_generacion;?>" target="_blank" class="button_R">Ver Resumen Honorarios Generados</a></td>
    </tr>
    </tbody>
  </table>
</div>
</body>
</html>