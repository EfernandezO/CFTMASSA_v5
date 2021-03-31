<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("Bibioteca_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css"/>
<title>Listado Libros QR</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:66px;
	z-index:1;
	left: 5%;
	top: 93px;
}
</style>
</head>

<body>
<h1 id="banner">Biblioteca - Listado Libros QR</h1>
<div id="apDiv1">
<table width="100%" border="1" align="center">
<thead>
<tr>
	<th colspan="5">Listado Libros QR</th>
</tr>
  <tr>
    <td>N.</td>
    <td>ID</td>
    <td>Titulo</td>
    <td>Carrera</td>
    <td>QR</td>
  </tr>
</thead>
<tbody>
<?php
if($_GET)
{
	include("../../../../../funciones/conexion_v2.php");
	$sede=$_GET["sede"];
	$id_carrera=$_GET["id_carrera"];
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
		
	if($id_carrera!="0")
	{ $condicion_carrera="AND id_carrera='$id_carrera'";}
	else
	{ $condicion_carrera="";}
	
	$cons="SELECT * FROM biblioteca WHERE sede='$sede' $condicion_carrera ORDER by sede, carrera, id_libro";
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
			$titulo_libro=$L["nombre"];
			$autor_libro=$L["autor"];
			$editorial_libro=$L["editorial"];
			//A,C,B sets
			$url_libro="http://200.28.135.221/CFTMASSA/www/biblioteca/vitrina/ver_libro.php?id_libro=$id_libro";
			$carrera_libro=$L["carrera"];
			$codigo='MassaX'.$id_libro."X".date("dmY")."X".$id_usuario_actual;
			$codigo='Titulo: '.$titulo_libro.' Autor: '.$autor_libro.' Editorial: '.$editorial_libro.' Sede: '.$sede.' Carrera: '.$carrera_libro.' ID LIBRO: '.$id_libro.' Mas informacion: '.$url_libro;
			
			//$codigo=base64_encode($codigo);
			
			echo'<tr>
					<td>'.$aux.'</td>
					<td>'.$id_libro.'</td>
					<td><a href="#" title="'.$codigo.'">'.$titulo_libro.'</a></td>
					<td>'.$carrera_libro.'</td>
					<td><img name="qr" src="../../../../libreria_publica/phpqrcode/imagen_QR.php?qr_info='.$codigo.'" alt="qr_'.$aux.'"></td>
					</tr>';
		
			
		}
	}
	$sql->free();
	$conexion_mysqli->close();
}
else
{ header("location: ../../../menu_biblioteca.php");}
?>
</tbody>
</table>
</div>
</body>
</html>