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

$continuar=false;
if($_POST)
{
	$sede=$_POST["fsede"];
	$array_carrera=$_POST["carrera"];
	$array_carrera=explode("_",$array_carrera);
		$id_carrera=$array_carrera[0];
		$carrera=$array_carrera[1];
	$nivel=$_POST["nivel"];
	$year=$_POST["year"];
	$continuar=true;
	
	if(DEBUG){var_export($_POST);}
		if($id_carrera>0)
		{ $condicion_carrera="AND id_carrera='$id_carrera'";}
		else
		{ $condicion_carrera="";}
		
		if($year!="Todos")
		{ $condicion_ingreso="AND ingreso='$year'";}
		else
		{ $condicion_ingreso="";}
		
		$inicio_ciclo=true;
		$niveles="";
		if(is_array($nivel))
		{
			foreach($nivel as $nn=>$valornn)
			{
				if($inicio_ciclo)
				{ 
					$niveles.="'$valornn'";
					$inicio_ciclo=false;
				}
				else
				{ $niveles.=", '$valornn'";}
				//echo"--> $niveles<br>";
			}
			$condicion_nivel="AND nivel IN($niveles)";
		}
		else{ $condicion_nivel="";}
}
else
{ header("location: index.php");}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funcines/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>Alumno X nivel e ingreso</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:81px;
	z-index:1;
	left: 5%;
	top: 110px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Alumnos X Nivel y A&ntilde;o Ingreso</h1>
<div id="link">
  <div align="right"><br />
<a href="index.php" class="button">Volver a Seleccion</a></div></div><div id="apDiv1">
  <table width="90%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="11">Listado Alumnos Carrera: (<?php echo $carrera;?>) año Ingreso:<?php echo $year;?> </br>Sede:<?php echo $sede;?> - Nivel(es):<?php echo $niveles;?></th>
    </tr>
    <tr>
      <td>N.</td>
      <td>ID</td>
      <td>sede</td>
      <td>Carrera</td>
      <td>a&ntilde;o Ingreso</td>
      <td>Nivel</td>
      <td>Rut</td>
      <td>Nombre</td>
      <td>Apellido P</td>
      <td>Apellido M</td>
      <td>F. Nacimineto</td>
    </tr>
     </thead>
    <tbody>
    <?php
    if($continuar)
	{
		include("../../../funciones/conexion.php");
			$cons="SELECT * FROM alumno WHERE sede='$sede' $condicion_ingreso $condicion_nivel $condicion_carrera ORDER by ingreso, carrera, nivel, apellido_P, apellido_M";
			if(DEBUG){ echo"-->$cons<br>";}
			$sql=mysql_query($cons)or die(mysql_error());
			$num_reg=mysql_num_rows($sql);
			if($num_reg>0)
			{
				$contador=0;
				while($A=mysql_fetch_assoc($sql))
				{
					$contador++;
					$A_sede=$A["sede"];
					$A_id=$A["id"];
					$A_rut=$A["rut"];
					$A_nombre=$A["nombre"];
					$A_apellido_P=$A["apellido_P"];
					$A_apellido_M=$A["apellido_M"];
					$A_nivel=$A["nivel"];
					$A_carrera=$A["carrera"];
					$A_year_ingreso=$A["ingreso"];
					$A_fecha_nac=$A["fnac"];
					
					echo'<tr>
							<td>'.$contador.'</td>
							<td>'.$A_id.'</td>
							<td>'.$A_sede.'</td>
							<td>'.$A_carrera.'</td>
							<td>'.$A_year_ingreso.'</td>
							<td>'.$A_nivel.'</td>
							<td>'.$A_rut.'</td>
							<td>'.$A_nombre.'</td>
							<td>'.$A_apellido_P.'</td>
							<td>'.$A_apellido_M.'</td>
							<td>'.$A_fecha_nac.'</td>
							</tr>';
				}
			}
			else
			{ echo'<tr><td colspan="11">Sin Alumnos Encontrados :(</td></tr>';}
		mysql_free_result($sql);	
		mysql_close($conexion);
	}
	else
	{ echo'<tr><td colspan="11">Datos Incorectos</td></tr>';}
	?>
    </tbody>
  </table>
</div>
</body>
</html>