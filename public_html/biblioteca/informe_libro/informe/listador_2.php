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
<title>Listador - Libros</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:28px;
	z-index:1;
	left: 5%;
	top: 103px;
}
-->
</style>
</head>
<?php
	$sede=$_POST["fsede"];
	$array_carrera=$_POST["fcarrera"];
	if($array_carrera!="todas")
	{
		$array_carrera=explode("_",$array_carrera);
		$id_carrera=$array_carrera[0];
		$carrera=$array_carrera[1];
	}
	else
	{
		$id_carrera="0";
		$carrera="todas";
	}
	
?>
<body>
<h1 id="banner">Administrador -Biblioteca</h1>
<div id="link"><br />
<a href="listador_libro.php" class="button">Volver a Seleccion</a><br />
<br />
<a href="listado_con_barras/listado_barras.php?sede=<?php echo $sede;?>&id_carrera=<?php echo $id_carrera;?>" target="_blank" class="button">Listado Barras</a>&nbsp;&nbsp;<a href="listado_con_QR/listado_qr.php?sede=<?php echo $sede;?>&id_carrera=<?php echo $id_carrera;?>" class="button_R" target="_blank">Listado QR</a>
</div>
<div id="apDiv1">
  <table width="95%" border="1" align="center">
  <caption>
  Listador Libros<br />
  carrera: <?php echo $carrera;?><br />
  Sede: <?php echo $sede;?>
  </caption>
  <thead>
    <tr>
      <th width="6%">N&deg;</th>
      <th width="11%">ID libro</th>
      <th width="33%">Titulo</th>
      <th width="39%">carrera</th>
      <th width="11%">sede</th>
    </tr>
    </thead>
    <tbody>
<?php
if($_POST)
{
	include("../../../../funciones/conexion_v2.php");
	
	if(DEBUG){ var_dump($_POST);}
	
	if($id_carrera!="0")
	{ $condicion_carrera="AND id_carrera='$id_carrera'";}
	else
	{ $condicion_carrera="";}
	
	$cons="SELECT * FROM biblioteca WHERE sede='$sede' $condicion_carrera ORDER by sede, carrera, id_libro";
	if(DEBUG){ echo "$cons<br>";}
	$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_reg=$sql->num_rows;
	if($num_reg>0)
	{
		$aux=0;
		while($L=$sql->fetch_assoc())
		{
			$aux++;
			$id_libro=$L["id_libro"];
			$titulo=$L["nombre"];
			$carrera=$L["carrera"];
			$sede_libro=$L["sede"];
			echo'<tr>
			  <td>'.$aux.'</td>	
			  <td><strong>'.$id_libro.'</strong></td> 
			  <td>'.$titulo.'</td>
			  <td>'.$carrera.'</td>
			  <td>'.$sede.'</td>
			</tr>';
		}
	}
	else
	{
		echo'<tr><td colspan="8">No hay Libros prestados</td></tr>';
	}
	$sql->free();
	$conexion_mysqli->close();
}	
?>    
   </tbody> 
   <tfoot>
   <tr>
   <td colspan="5">(<?php echo $num_reg;?>) Libros Registrados</td>
   </tr>
   </tfoot>
  </table>
</div>
</body>
</html>