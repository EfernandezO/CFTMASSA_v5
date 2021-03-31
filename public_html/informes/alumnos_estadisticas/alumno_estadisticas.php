<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_alumno_estadisticas_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
require("../../../funciones/funciones_sistema.php");
$year_actual=date("Y");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Administrador - informe</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:186px;
	z-index:1;
	left: 5%;
	top: 93px;
}
.Estilo1 {font-size: 12px}
#Layer2 {
	position:absolute;
	width:168px;
	height:16px;
	z-index:2;
	left: 420px;
	top: 49px;
}
#apDiv1 {
	position:absolute;
	width:40%;
	height:43px;
	z-index:2;
	left: 30%;
	top: 486px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Alumnos Estadisticas</h1>
<?php
$privilegio=$_SESSION["USUARIO"]["privilegio"];
switch($privilegio)
{
	case"matricula":
		$url="../../Administrador/menu_matricula/index.php";
		break;
	case"inspeccion":
		$url="../../Administrador/menu_inspeccion/index.php";
		break;
	default:
		$url="../../Alumnos/menualumnos.php";	
}
?>
<div id="link"><br>
<a href="<?php echo $url;?>" class="button">Volver al menu</a><br />
</div>
<div id="Layer1">
<form action="alumno_estadisticas_2.php" method="post" name="frm" id="frm" target="_blank">
  <table width="50%" border="1" align="center">
  <caption></caption>
  <thead>
    <tr>
      <th colspan="2"><span class="Estilo1">Busqueda de Alumnos x Cohorte</span></th>
    </tr>
	</thead>
	<tbody>
    <tr>
      <td width="187"><span class="Estilo1">Sede</span></td>
      <td width="197"><?php echo CAMPO_SELECCION("sede", "sede","",false);?></td>
    </tr>
    <tr >
      <td><span class="Estilo1">Carrera</span></td>
      <td><?php echo CAMPO_SELECCION("id_carrera", "carreras","",true);?></td>
    </tr>
    <tr >
      <td><span class="Estilo1">A&ntilde;o Ingreso </span></td>
      <td><?php echo CAMPO_SELECCION("year_ingreso", "year",$year_actual,false);?> </td>
    </tr>
    <tr>
      <td><span class="Estilo1">Jornada</span></td>
      <td><?php echo CAMPO_SELECCION("jornada", "jornada","",true);?></td>
    </tr>
	</tbody>
	<tfoot>
    <tr>
      <td colspan="2"><div align="right">
        <input type="submit" name="Submit" value="Generar Informe" />
      </div></td>
      </tr>
	</tfoot>
  </table>
 </form> 
</div>
<div id="apDiv1"></div>
</body>
</html>