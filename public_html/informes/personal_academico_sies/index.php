<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_PAC_SIES_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Sies Formato</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:91px;
	z-index:1;
	left: 5%;
	top: 109px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:38px;
	z-index:2;
	left: 5%;
	top: 264px;
	text-align: center;
}
-->
</style>
</head>

<body>
<h1 id="banner">Administrador - Personal Academico formato SIES</h1>
<div id="link">
  <div align="right"><br />
<a href="../../Alumnos/menualumnos.php" class="button">Volver al Menu</a></div>
</div>
<div id="apDiv1">
<form action="personal_academico_sies.php" method="post" name="frm" id="frm">
  <table width="40%" border="0" align="center">
  <thead>
    <tr>
      <th colspan="2">Parametros para Busqueda</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>A&ntilde;o Asignaciones</td>
      <td><select name="year" id="year">
        <?php
	  	$años_anteriores=25;
		$años_siguientes=1;
	  	$año_actual=date("Y");
		
		$año_ini=$año_actual-$años_anteriores;
		$año_fin=$año_actual+$años_siguientes;
		
		for($a=$año_ini;$a<=$año_fin;$a++)
		{
			if($a==$año_actual)
			{
				echo'<option value="'.$a.'" selected="selected">'.$a.'</option>';	
			}
			else
			{
				echo'<option value="'.$a.'">'.$a.'</option>';
			}	
		}
	  ?>
        <option value="Todos">Todos</option>
      </select></td>
    </tr>
    <tr>
      <td colspan="2"><div align="right">
        <input type="submit" name="button" id="button" value="Consultar" />
      </div></td>
      </tr>
      </tbody>
  </table>
  </form>
</div>
<div id="apDiv2">Genera Archivo .XLS(excel) Con los Datos de todo el<br />
Personal academico por Sede, en base al Formato SIES<br />
utilizando las asignaciones y registros de docentes para su generacion
</div>
</body>
</html>