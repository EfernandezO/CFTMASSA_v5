<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_comprobar_egresados_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
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
	top: 86px;
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
#link {
	text-align: right;
	padding-right: 10px;
}
#apDiv1 {
	position:absolute;
	width:40%;
	height:45px;
	z-index:2;
	left: 30%;
	top: 371px;
}
-->
</style>
</head>

<body>
<h1 id="banner">Administrador - Informe Busca titulados/egresados</h1>
<?php
$privilegio=$_SESSION["USUARIO"]["privilegio"];
require("../../../funciones/funciones_sistema.php");
switch($privilegio)
{
	case"inspeccion":
		$url="../../Administrador/menu_inspeccion/index.php";
		break;
	default:
		$url="../../Alumnos/menualumnos.php";	
}
?>
<div id="link"><br />
<a href="<?php echo $url;?>" class="button">Volver al menu </a></div>
<div id="Layer1">
<form action="egresados_y_titulados.php" method="post" name="frm" target="_blank" id="frm">
  <table width="40%" border="1" align="center">
  <caption></caption>
  <thead>
    <tr>
      <th colspan="2"><span class="Estilo1">Busqueda de Alumnos </span></th>
    </tr>
	</thead>
	<tbody>
    <tr class="odd">
      <td width="187"><span class="Estilo1">Sede</span></td>
      <td width="197">
	  <?php echo CAMPO_SELECCION("sede", "sede","",true);?></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Carrera</span></td>
      <td><?php echo CAMPO_SELECCION("id_carrera", "carreras","",true);?></td>
    </tr>
    <tr class="odd">
      <td>Año ingreso Alumno</td>
      <td><?php echo CAMPO_SELECCION("year_ingreso", "year","0",true);?></td>
    </tr>
    <tr class="odd">
      <td>Año de Egreso</td>
      <td><?php echo CAMPO_SELECCION("year_egreso", "year","",true);?></td>
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
<div id="apDiv1">Busca Alumnos que esten en condicion de egresado(EG) o en condicion titulado(T)</div>
</body>
</html>
