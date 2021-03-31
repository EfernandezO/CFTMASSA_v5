<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_duracionCarrera_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$year_actual=date("Y");
$mes_actual=date("m");
if($mes_actual>=8){$semestre_actual=2;}
else{ $semestre_actual=1;}
$sede_actual=$_SESSION["USUARIO"]["sede"];
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
#div_carga {
	width:100%;
	height:59px;
	z-index:1;
	top: 373px;
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
#div_contenedor_carga {
	position:absolute;
	width:798px;
	height:115px;
	z-index:2;
	left: 5%;
	top: 335px;
}
#apDiv1 {
	position:absolute;
	width:40%;
	height:115px;
	z-index:3;
	left: 5%;
	top: 228px;
}
-->
</style>
</head>

<body onload="MM_preloadImages('../../BAses/Images/BarraProgreso.gif')">
<h1 id="banner">Administrador - Duración Carrera</h1>
<div id="link"><br />
<a href="../../Alumnos/menualumnos.php" class="button">Volver al menu Principal </a></div>
<div id="apDiv1">
<form action="duracionCarrera2.php" method="post" name="frm" id="frm">
  <div id="div_cargando"><img src="../../BAses/Images/BarraProgreso.gif" width="82" height="13" alt="Cargando..." /><br />
    Espere...
</div>
  <table width="100%" border="1" align="left">
  <caption></caption>
  <thead>
    <tr>
      <th colspan="3"><span class="Estilo1">Busqueda de Alumnos </span></th>
    </tr>
	</thead>
	<tbody>
    <tr class="odd">
      <td width="193"><span class="Estilo1">Sede</span></td>
      <td width="198" colspan="2">
	  <?php
	  require("../../../funciones/funciones_sistema.php");
	  echo CAMPO_SELECCION("fsede","sede",$sede_actual, false);
	  ?>
      </td>
    </tr>
    <tr class="odd">
      <td>A&ntilde;o Ingreso</td>
      <td colspan="2"><?php echo CAMPO_SELECCION("year","year", $year_actual,true);?></td>
    </tr>
	</tbody>
	<tfoot>
    <tr>
      <td colspan="3"><div align="right">
        <input name="boton" type="submit" id="boton" value="Generar Informe"/>
      </div></td>
      </tr>
	</tfoot>
  </table>
 </form> 
</div>
</body>
</html>