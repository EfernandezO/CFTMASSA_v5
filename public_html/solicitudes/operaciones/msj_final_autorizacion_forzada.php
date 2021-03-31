<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Solicitud->AutorizacionFinanciera");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
$continuar=false;

if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
{$continuar=true;}
else
{ $continuar=false;}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<title>Creacion Solicitud</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:52px;
	z-index:1;
	left: 5%;
	top: 116px;
	text-align: center;
}
</style>

</head>

<body>
<h1 id="banner">Administrador -Autoriza Solicitudes Final</h1>
<div id="link"><br />
<a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver a Alumno Seleccionado</a><br />
<br />
<a href="../revisiones/revisar_solicitudes_global.php" class="button">Revisión de Solicitudes General</a>
</div>
<div id="apDiv1">
  <table width="40%" border="1" align="center">
  <thead>
    <tr>
      <th>Informacion</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td align="center">
       <?php
 if(isset($_GET["error"]))
 {
	 $error=$_GET["error"];
	 $img_error='<img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="x" />';
	 $img_ok='<img src="../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
	 switch($error)
	 {
		 case"SF1":
		 		$msj="Solicitud Autorizada Correctamente...";
				$img=$img_ok;
		 	break;
		 case"SF2":
		 		$msj="No se puede Autorizar esta Solicitud...";
				$img=$img_error;
		 	break;
		default:
			$msj="";	
			$img="";
	 }
	 echo $img." ".$msj;
 }
 ?>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    </tbody>
  </table>
  
</div>
</body>
</html>