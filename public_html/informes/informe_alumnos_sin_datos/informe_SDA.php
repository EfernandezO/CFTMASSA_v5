<?php require ("../../SC/seguridad.php");?>
<?php require ("../../SC/privilegio.php");?>
<?php
if(!$_POST)
{
	header("location: index.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Alumnos sin Datos</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<style>
#link {
	text-align: right;
	padding-right: 10px;
}
a:link {
	color: #006699;
	text-decoration: none;
}
a:visited {
	color: #006699;
	text-decoration: none;
}
a:hover {
	color: #FF0000;
	text-decoration: underline;
}
a:active {
	color: #006699;
	text-decoration: none;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - informe Alumnos </h1>
<div id="link"><a href="index.php">Volver a Seleccion</a></div>
<p>
<table width="505" border="0">
<tr>
	<td colspan="4"><em>Alumnos Encontrados que cumplen condición</em></td>
</tr>
<tr>
<td><strong>N°</strong></td>
<td><strong>Rut</strong></td>
<td><strong>Nombre</strong></td>
<td><strong>Apellido Paterno</strong></td>
<td><strong>Apellido Materno</strong></td>
</tr>
<?php
	$carrera=$_POST["carrera"];
	$sede=$_POST["sede"];
	$campos=$_POST["campo"];
	$año_ingreso=$_POST["ano"];
	$condicion="";
	$tipo_condicion=$_POST["tipo_condicion"];
	$n_campos=count($campos);
	$aux=true;
	if($n_campos>0)
	{
		echo"Campos sin Datos<br>";
		foreach($campos as $n => $valor)
		{
			echo"$valor <br>";
			if($aux)
			{
				$condicion.=" $valor=''";
				$aux=false;
			}
			else
			{
				$condicion.=" $tipo_condicion $valor=''";
			}	
		
		}
		echo"<br>";
		//var_export($_POST);
		include("../../../funciones/conexion.php");
		$cons="SELECT * FROM alumno WHERE carrera='$carrera' AND sede='$sede' AND ingreso='$año_ingreso' AND ($condicion)";
		//echo"<br>--> $cons<br>";
		$sql=mysql_query($cons)or die("B ".mysql_error());
		$num_reg=mysql_num_rows($sql);
		
		if($num_reg>0)
		{
			$contador=1;
			while($SD=mysql_fetch_assoc($sql))
			{
				$contador++;
				$rut=$SD["rut"];
				$nombre=$SD["nombre"];
				$apellido_P=$SD["apellido_P"];
				$apellido_M=$SD["apellido_M"];
					$carrera=$SD["carrera"];
				$aux_apellido=explode(" ",$SD["apellido"]);
				//var_export($aux_apellido);
				$aux_apellido_P=$aux_apellido[0];
				$aux_apellido_M=$aux_apellido[1];
				
				//echo"$aux_apellido_M - $aux_apellido_P<br>";
				
				if(empty($apellido_P))
				{
					$apellido_P=$aux_apellido_P;
				}
				if(empty($apellido_M))
				{
					$apellido_M=$aux_apellido_M;
				}
				
				
				echo'
					<tr>
					<td>'.$contador.'</td>
					<td>'.$rut.'</td>
					<td>'.$nombre.'</td>
					<td>'.$apellido_P.'</td>
					<td>'.$apellido_M.'</td>
					</tr>';
				
			}
		}
		else
		{
			echo'<tr>
				<td colspan="5" align="center">Sin Alumnos encontrados en esta Busqueda</td>
				</tr>';
		}
		mysql_close($conexion);
	}
	else
	{
		echo"seleccione algun campo para iniciar la Busqueda<br>";
	}	
?>
</table>
</p>
<p>&nbsp; </p>
</body>
</html>
