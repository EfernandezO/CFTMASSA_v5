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
<title>quien tiene libros</title>
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
	top: 78px;
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
		$nom_carrera=$array_carrera[1];
	}
	else
	{
		$id_carrera=0;
		$nom_carrera="todas";
	}
?>
<body>
<h1 id="banner">Administrador -Biblioteca</h1>
<div id="link"><br />
<a href="quien_tiene_libro.php" class="button">Volver a Seleccion</a></div>
<div id="apDiv1">
  <table width="100%" border="1">
  <caption>
  Libros actualmente Prestados<br />
  carrera: <?php echo $nom_carrera;?><br />
  Sede: <?php echo $sede;?>
  </caption>
  <thead>
    <tr>
      <th>N&deg;</th>
      <th>ID libro</th>
      <th>Titulo</th>
      <th>carrera</th>
      <th>sede</th>
      <th>rut</th>
      <th>nombre</th>
      <th>apellido</th>
      <th>Carrera Alumno</th>
    </tr>
    </thead>
    <tbody>
<?php
if($_POST)
{
	require("../../../../funciones/conexion_v2.php");
	
	if(DEBUG){ var_export($_POST);}
	if($id_carrera>0)
	{ $condicion_carrera="AND id_carrera='$id_carrera'";}
	else
	{ $condicion_carrera="";}
	
	$cons="SELECT * FROM biblioteca WHERE prestado='S' AND sede='$sede' $condicion_carrera";
	if(DEBUG){ echo "$cons<br>";}
	$sql=$conexion_mysqli->query($cons);
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
			$id_prestado=$L["id_alumno"];
			////////////////////////
			$cons_a="SELECT rut, nombre, apellido_P, apellido_M, carrera FROM alumno WHERE id='$id_prestado' LIMIT 1";
			$sql_a=$conexion_mysqli->query($cons_a);
			$DA=$sql_a->fetch_assoc();
				$rut_alumno=$DA["rut"];
				$nombre=$DA["nombre"];
				$apellido_P=$DA["apellido_P"];
				$apellido_M=$DA["apellido_M"];
				$apellido_label=$apellido_P." ".$apellido_M;
				$carrera_alumno=$DA["carrera"];
			$sql_a->free();
			/////////////////////////
			echo'<tr>
			  <td>'.$aux.'</td>	
			  <td><strong>'.$id_libro.'</strong></td> 
			  <td>'.$titulo.'</td>
			  <td>'.$carrera.'</td>
			  <td>'.$sede.'</td>
			  <td>'.$rut_alumno.'</td>
			  <td>'.$nombre.'</td>
			  <td>'.$apellido_label.'</td>
			  <td>'.$carrera_alumno.'</td>
			</tr>';
		}
	}
	else
	{
		echo'<tr><td colspan="9">No hay Libros prestados</td></tr>';
	}
	$sql->free();
	$conexion_mysqli->close();
}	
?>    
   </tbody> 
   <tfoot>
   <tr>
   <td colspan="9">(<?php echo $num_reg;?>) Libros Prestados</td>
   </tr>
   </tfoot>
  </table>
</div>
</body>
</html>