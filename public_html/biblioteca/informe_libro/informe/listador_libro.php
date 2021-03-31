<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Bibioteca_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Listador</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 134px;
}
.Estilo1 {font-size: 12px}
-->
</style>
</head>
<body>
<h1 id="banner">Administrador -Biblioteca</h1>
<div id="link"><br />
<a href="../../menu_biblioteca.php" class="button">Volver al Biblioteca </a></div>
<div id="apDiv1">
<form action="listador_2.php" method="post" name="frm" id="frm">
  <table width="50%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="2">Seleccine los parametros para iniciar la busqueda</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td><span class="Estilo1 Estilo1">sede</span></td>
      <td><span class="Estilo1 Estilo1">
        <?php
	  include("../../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?>
      </span></td>
    </tr>
    <tr>
      <td><span class="Estilo1 Estilo1">carrera</span></td>
      <td><span class="Estilo1 Estilo1">
        <select name="fcarrera" id="fcarrera">
            <option value="todas">Todas</option>
          <?php
    require("../../../../funciones/conexion_v2.php");
   
   $res="SELECT id, carrera FROM carrera";
   $result=$conexion_mysqli->query($res);
   while($row = $result->fetch_assoc()) 
   {
 		$id_carrera=$row["id"];
		$nom_carrera=$row["carrera"];
		
		echo'<option value="'.$id_carrera.'_'.$nom_carrera.'">'.$nom_carrera.'</option>';
    }
    $result->free(); 
    @mysql_close($conexion);
	$conexion_mysqli->close();
	 ?>
        </select>
      </span></td>
    </tr>
    <tr>
      <td colspan="2"><div align="right" class="Estilo1">
        <input type="submit" name="button" id="button" value="consultar" />
      </div></td>
    </tr>
      </tbody>
  </table>
  </form>
</div>
</body>
</html>