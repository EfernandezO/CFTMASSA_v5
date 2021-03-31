<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Gestionar_aplicacion_de_interes_Y_Gasto_cobranza_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
	{$continuar=true;}
	else
	{$continuar=false;}
}
else
{$continuar=false;}

if($continuar)
{
	require("../../../funciones/conexion_v2.php");
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	
	$cons="SELECT aplicar_intereses, aplicar_gastos_cobranza FROM alumno WHERE id='$id_alumno' LIMIT 1";
	$sqli=$conexion_mysqli->query($cons);
		$A=$sqli->fetch_assoc();
		$aplicar_intereses=$A["aplicar_intereses"];
		$aplicar_gastos_cobranza=$A["aplicar_gastos_cobranza"];
		
		if($aplicar_intereses==1){$aplicar_intereses=true; $msj_info="Actualmente se aplican intereses a este Alumno"; $boton='Presione <a href="gestionar_interes_2.php?aplicar=0">Aqui</a> Para dejar de Aplicarlos';}
		else{$aplicar_intereses=false; $msj_info="Actualmente No se aplican intereses a este Alumno"; $boton='Presione <a href="gestionar_interes_2.php?aplicar=1">Aqui</a> Para Aplicarlos';}
		
		if($aplicar_gastos_cobranza==1){$aplicar_gastos_cobranza=true; $msj_info_2="Actualmente se aplican Gastos Cobranza a este Alumno"; $boton_2='Presione <a href="gestionar_interes_2.php?aplicarGC=0">Aqui</a> Para dejar de Aplicarlos';}
		else{$aplicar_gastos_cobranza=false; $msj_info_2="Actualmente No se aplican Gastos de Cobranza a este Alumno"; $boton_2='Presione <a href="gestionar_interes_2.php?aplicarGC=1">Aqui</a> Para Aplicarlos';}
	
	$sqli->free();
	$conexion_mysqli->close();
	@mysql_close($conexion);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Interes Alumno</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 88px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Interes Alumno</h1>
<div id="apDiv1">
<?php if($continuar){?>
  <table width="70%" border="1" align="center">
  <thead>
    <tr>
      <th>Aplicar Intereses a Alumno</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td><?php echo $msj_info;?></td>
    </tr>
    <tr>
      <td><?php echo $boton;?></td>
    </tr>
    <tr>
      <td><?php echo $msj_info_2;?></td>
    </tr>
    <tr>
      <td><?php echo $boton_2;?></td>
    </tr>
    </tbody>
  </table>
  <?php }else{ echo"Sin datos<br>";}?>
</div>
</body>
</html>