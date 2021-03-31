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
<title>Listador de Libros</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:85px;
	z-index:4;
	left: 5%;
	top: 113px;
}
-->
</style>
</head>

<body>
<h1 id="banner">Administrador -Biblioteca</h1>
<div id="link"><br />
<a href="../../menu_biblioteca.php" class="button">Volver al Biblioteca </a></div>
<div id="apDiv1">
<form action="listadorl1.php" method="post" name="frm" id="frm">
  <table width="60%" border="0" align="center">
  <thead>
    <tr>
      <th colspan="3">Seleccione Criterios de Busqueda</tdh>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="69"><strong>Sede:</strong></td>
      <td colspan="2"><?php
	  include("../../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?></td>
    </tr>
    <tr>
      <td><strong>Carrera:</strong></td>
      <td colspan="2">
      <select name="fcarrera" id="fcarrera">
          <?php
    include("../../../../funciones/conexion_v2.php");
   
   $res="SELECT id, carrera FROM carrera";
   $result=$conexion_mysqli->query($res);
   while($row = $result->fetch_assoc()) 
   {
 		$id_carrera=$row["id"];
		$nom_carrera=$row["carrera"];
		
		echo'<option value="'.$id_carrera.'_'.$nom_carrera.'">'.$id_carrera.'_'.$nom_carrera.'</option>';
    }
    $result->free(); 
    @mysql_close($conexion);
	$conexion_mysqli->close();
	 ?>
        </select>
      </td>
    </tr>
    <tr>
      <td><strong>Tipo:</strong></td>
      <td width="154"><label>
        <input name="opcion" type="radio" value="N" checked="checked" />
      numero de Prestamos 
      X Libro</label></td>
      <td width="166"><input name="opcion" type="radio" value="C" />
Cantidad de Libros prestados </td>
    </tr>
    <tr>
      <td colspan="3" align="right">
        <input type="submit" name="Submit" value="Listar" />
</td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
</body>
</html>
