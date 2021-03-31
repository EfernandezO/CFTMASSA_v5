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
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<?php include("../../../../funciones/codificacion.php");?>
<title>Listador de Libros</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 93px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador -Biblioteca</h1>
<div id="link"><br />
<a href="formBL.php" class="button">Volver a Seleccion</a></div>
<div id="Layer1">
  <?php
if($_POST)
{
	include("../../../../funciones/funcion.php");
	require("../../../../funciones/conexion_v2.php");
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
			$nom_carrera="Todas";
		}
		$opcion=$_POST["opcion"];
	
	
	if(($id_carrera>0)and($sede!="NO")and($opcion!="NO"))
	{
		if($opcion=="N")
		{
			echo'
  			<table width="546" border="0" align="center">
			<thead>
    		<tr>
      		<th colspan="2">Numero de Prestamos por Libro</th>
    		</tr>
			</thead>
			<tbody>
    		<tr>
      		<td width="268"><div align="center">Nombre </div></td>
      		<td width="187"><div align="center">Numero de Prestamos </div></td>
    		</tr>';
			
			$cons="SELECT nombre,carrera,numpresta FROM biblioteca WHERE id_carrera='$id_carrera' and sede='$sede' ORDER BY numpresta desc";
			if(DEBUG){echo"N-> $cons<br>";}
			echo"<b>Carrera: $nom_carrera<br>Sede: $sede</b><br>";
			$sql=$conexion_mysqli->query($cons);
			$num_libros=$sql->num_rows;
			$c=0;
			$num_mayor=0;
			$x=0;
		if($num_libros>0)
		{	
			while($L=$sql->fetch_assoc())
			{
				$nombre_l=ucwords(strtolower($L["nombre"]));
				$carrera=$L["carrera"];
				$num_presta=$L["numpresta"];
				
				if(($c==0)and($num_presta!=0))
				{
					$num_menor=$num_presta;
					$nombre_menor=$nombre_l;
					$c++;
				}
				if($num_presta > $num_mayor)
				{
					$num_mayor=$num_presta;
					$nombre_mayor=$nombre_l;
				}
				if(($num_presta !=0)and($num_presta < $num_menor))
				{
					$num_menor=$num_presta;
					$nombre_menor=$nombre_l;
				}
				$x++;
				echo'<tr>
      				<td>'.$nombre_l.'</td>
      				<td><div align="center">'.$num_presta.'</div></td>
    				</tr>';
			}
			if($num_mayor!=0)
			{
			echo'<tr><td>&nbsp;</td></tr><tr><td><strong>El Libro Mas Prestado es:</strong></td><td colspan="2"><span class="Estilo2">'.$nombre_mayor.', '.$num_mayor.' Veces Prestado</span></td>
			</tr><tr><td>&nbsp;</td></tr><tr ><td><strong>El Libro Menos Prestado es:</strong></td><td colspan="2"><span class="Estilo3">'.$nombre_menor.', '.$num_menor.' Veces Prestado</span></td></tr>';
			}
			else
			{
				echo'<tr><td>&nbsp;</td></tr><tr align="center"><td colspan="2"><b>Estos Libros No Tienen Prestamos Registrados...</b></td></td>';
			}
		}
		else
		{
			echo'<tr><td>&nbsp;</td></tr><tr align="center"><td colspan="2"><b>Esta Carrera No Tiene Libros Registrados en esta Sede...</b></td></td>';
		}	
			echo'<tr><td> Total Libros en esta Carrera '.$num_libros.'</td></tr></tbody></table>';
		}
		if($opcion=="C")
		{
			$cons="SELECT nombre,numpresta FROM biblioteca WHERE id_carrera='$id_carrera' and sede='$sede' and prestado='S' ORDER BY numpresta desc";
			if(DEBUG){echo"C-> $cons<br>";}
			echo"<b>Carrera: $nom_carrera<br>Sede: $sede</b><br>";
			$sql=$conexion_mysqli->query($cons);
			echo'
  			<table width="546" border="0" align="center">
			<thead>
    		<tr>
      		<th colspan="2">Cantidad de Libros Prestados</th>
    		</tr>
			</thead>
			<tbody>
    		<tr>
      		<td width="268"><div align="center">Nombre </div></td>
      		<td width="187"><div align="center">Numero de Prestamos </div></td>
    		</tr>';
			$num_prestados=$sql->num_rows;;
			
			if($num_prestados > 0)
			{
				while($L=$sql->fetch_assoc())
				{
					$nombre_l=ucwords(strtolower($L["nombre"]));
					$num_presta=$L["numpresta"];
				
					$x++;
					echo'<tr>
      				<td>'.$nombre_l.'</td>
      				<td><div align="center">'.$num_presta.'</div></td>
    				</tr>';
				}
				echo'<tr><td></td><tr><tr><td><b>Cantidad de Libros Prestados</b></td><td>'.$num_prestados.'</td></tr>';
			}
			else
			{
				echo'<tr align="center"><td colspan="2"><b>No Hay Libros Prestados...</b></td></tr>';
			}	
echo'</tbody></table>';
		}
	}
	else
	{
		echo"<b>Datos Incorrectos...</b><br>";
	}
}	
?>
</div>
</body>
</html>
