<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_alumnos_nivel_Y_year_v1");
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
	top: 322px;
	text-align: center;
}
-->
</style>
</head>

<body>
<h1 id="banner">Administrador - Alumnos ingreso X A&ntilde;o FORMATO SIES-2012.XLS</h1>
<div id="link">
  <div align="right"><br />
<a href="../../Alumnos/menualumnos.php" class="button">Volver al Menu</a></div>
</div>
<div id="apDiv1">
<form action="alumno_X_nivel_year_ingreso.php" method="post" name="frm" id="frm">
  <table width="50%" border="0" align="center">
  <thead>
    <tr>
      <th colspan="6">Parametros para Busqueda</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="42%">Sede</td>
      <td width="58%" colspan="5"><?php
	  include("../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?></td>
    </tr>
    <tr>
      <td>Carrera</td>
      <td colspan="5">
       <select name="carrera" id="carrera">
       		<option value="0_todas">Todas</option>
		   <?php
		    include("../../../funciones/conexion.php");
		   $res="SELECT id, carrera FROM carrera where id >= 0";
		   $result=mysql_query($res);
           while($row = mysql_fetch_array($result)) 
           {
			   $id_carrera=$row["id"];
            	$nomcar=$row["carrera"];
                echo'<option value="'.$id_carrera.'_'.$nomcar.'">'.$nomcar.'</option>';	
           }
            mysql_free_result($result); 
            mysql_close($conexion);
             ?>
        </select>
      </td>
      </tr>
    <tr>
      <td>Nivel(es)</td>
      <td>1.
        <input name="nivel[]" type="checkbox" id="nivel[]" value="1" checked="checked" />
        <label for="nivel[]"></label></td>
      <td>2.
        <input name="nivel[]" type="checkbox" id="nivel[]" value="2" /></td>
      <td>3.
        <input name="nivel[]" type="checkbox" id="nivel[]" value="3" /></td>
      <td>4.
        <input name="nivel[]" type="checkbox" id="nivel[]" value="4" /></td>
      <td>5.
        <input name="nivel[]" type="checkbox" id="nivel[]" value="5" /></td>
    </tr>
    <tr>
      <td>A&ntilde;o ingreso</td>
      <td colspan="5">
      <select name="year" id="year">
       <option value="Todos">Todos</option>
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
      </select></td>
    </tr>
    <tr>
      <td colspan="6"><div align="right">
        <input type="submit" name="button" id="button" value="Consultar" />
      </div></td>
      </tr>
      </tbody>
  </table>
  </form>
</div>
<div id="apDiv2">Genera Archivo .XLS(excel) Con los Datos de Todos Los Alumnos<br />
  que ingresaron en un a&ntilde;o determinado y por Sede, en base al Formato SIES 2012
</div>
</body>
</html>