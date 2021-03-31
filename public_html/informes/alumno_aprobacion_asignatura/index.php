<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_aprobacion_GENERAL V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Administrador - informe</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>

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
#Layer3 {
	position:absolute;
	width:90%;
	height:186px;
	z-index:1;
	left: 5%;
	top: 377px;
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
#div_cargando {
	position:absolute;
	width:102px;
	height:31px;
	z-index:2;
	left: 60%;
	top: 248px;
	display:none;
}
#apDiv1 {
	position:absolute;
	width:40%;
	height:115px;
	z-index:2;
	left: 60%;
	top: 45px;
	text-align: center;
}
-->
</style>

</head>

<body>
<h1 id="banner">Administrador - Informe de aprobaci&oacute;n de Asignaturas</h1>
<?php
$privilegio=$_SESSION["USUARIO"]["privilegio"];
switch($privilegio)
{
	case"inspeccion":
		$url="../../Administrador/menu_inspeccion/index.php";
		break;
	case"jefe_carrera":
		$url="../../Docentes/okdocente.php";
		break;	
	default:
		$url="../../Alumnos/menualumnos.php";	
}
?>
<div id="link"><br />
<a href="<?php echo $url;?>" class="button">Volver al menu </a></div>
<div id="Layer1">
<form action="server_aprobacion_asignatura.php" method="post" name="frm" id="frm">
  <div id="apDiv1">Nunero de Ramos inscritos, aprobados y reprobados por semestre, segun toma de ramos.</div>
  <div id="div_cargando"><img src="../../BAses/Images/BarraProgreso.gif" width="82" height="13" alt="Cargando..." /><br />
    Espere...
  </div>
  <?php
  	  include("../../../funciones/funcion.php");
	  require("../../../funciones/funciones_sistema.php");
	  
	?>
  <table width="50%" border="1" align="left">
  <caption></caption>
  <thead>
    <tr>
      <th colspan="3"><span class="Estilo1">Busqueda de Alumnos </span></th>
    </tr>
    <tr class="odd">
      <td colspan="3" >Periodo</td>
    </tr>
    <tr class="odd">
      <td>Cohorte A&ntilde;o </td>
      <td colspan="2"><?php echo CAMPO_SELECCION("yearCohorte","year",date("Y"), true);?></td>
    </tr>
	</thead>
	<tbody>
    <tr class="odd">
      <td>Año de notas</td>
      <td colspan="2"><?php echo CAMPO_SELECCION("yearNotas","year",date("Y"),false);?></td>
    </tr>
	</tbody>
	<tfoot>
    <tr>
      <td colspan="3"><div align="right">
        <input name="boton" type="submit" id="boton"  value="Generar Informe"/>
      </div></td>
      </tr>
	</tfoot>
  </table>
 </form> 
</div>
</body>
</html>