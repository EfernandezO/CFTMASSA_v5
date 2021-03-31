<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Seleccion_de_alumno_para_realizarle_cobranza_v1");
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
	require("../../../../funciones/conexion_v2.php");
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	
	$cons="SELECT realizar_cobranza FROM alumno WHERE id='$id_alumno' LIMIT 1";
	$sqli=$conexion_mysqli->query($cons);
		$A=$sqli->fetch_assoc();
		$realizar_cobranza=$A["realizar_cobranza"];
	if(DEBUG){ echo"-->$cons <br> Realizar_Cobranza: $realizar_cobranza<br>"; }
		
		if($realizar_cobranza==1){$realizar_cobranza=true; $msj_info="Actualmente este Alumno esta seleccionado para Realizarle Cobranza"; $boton='Presione <a href="utilizar_para_cobranza_2.php?aplicar=0">Aqui</a> Para liberarlo de la Cobranza';}
		else{$realizar_cobranza=false; $msj_info="Actualmente No se ha seleccionado a este Alumno Para realizarle Cobranza"; $boton='Presione <a href="utilizar_para_cobranza_2.php?aplicar=1">Aqui</a> Para permitir que se le realice cobranza';}
		
	$sqli->free();
	$conexion_mysqli->close();
	@mysql_close($conexion);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Seleccionar para Cobranza</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
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
<h1 id="banner">Administrador - Seleccionar para Cobranza</h1>
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
        <td>&nbsp;</td>
      </tr>
      </tr>
    </tbody>
  </table>
  <?php }else{ echo"Sin datos<br>";}?>
</div>
</body>
</html>