<?php include ("../../SC/seguridad.php");?>
<?php include ("../../SC/privilegio.php");?>
<?php
define("DEBUG",false);
if($_GET)
{
		if(!DEBUG)
		{
			header('Content-type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=Alumnos_descuentos_y_becas.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		else
		{var_export($_GET);}
	$carrera=base64_decode($_GET["carrera"]);
	$sede=base64_decode($_GET["sede"]);
	$semestre=base64_decode($_GET["semestre"]);
	$year=base64_decode($_GET["year"]);
	
	
	if($carrera=="todas")
	{
		$condicon_carrera="";
	}
	else
	{
	 	$condicon_carrera="alumno.carrera='$carrera' AND";
	}

	$cons="SELECT alumno.id, alumno.rut, alumno.nombre, alumno.apellido, alumno.apellido_P, alumno.apellido_M, alumno.carrera, alumno.ingreso, alumno.sede, alumno.nivel, alumno.jornada, contratos2.id, contratos2.porcentaje_beca, contratos2.cantidad_beca, contratos2.txt_beca FROM alumno INNER JOIN contratos2 ON alumno.id = contratos2.id_alumno WHERE  $condicon_carrera alumno.sede='$sede' AND contratos2.condicion='ok' AND contratos2.ano='$year' AND contratos2.semestre='$semestre' AND (porcentaje_beca>0 OR cantidad_beca>0)";
	
	if(DEBUG){echo"$cons<br>";}
	echo'<table width="100%" border="1">
    <tr>
      <td>Carrera: </td>
      <td colspan="7">'.$carrera.' - '.$semestre.' Semestre '. $year.'</td>
      <td>Sede</td>
      <td>'.$sede.'</td>
    </tr>
    <tr>
      <td width="8%">ID</td>
      <td width="6%">Rut</td>
      <td width="15%">Nombre </td>
      <td width="14%">Apellido</td>
      <td width="7%">Ingreso</td>
	  <td width="7%">Nivel</td>
	  <td width="7%">Jornada</td>
      <td width="13%">% beca o desc.</td>
      <td width="19%">Cantidad Beca o Desc</td>
      <td width="18%">comentario</td>
    </tr>';
	include("../../../funciones/conexion.php");
	$sql=mysql_query($cons)or die(mysql_error());
	$num_reg=mysql_num_rows($sql);
	if($num_reg>0)
	{
		$aux=0;
		while($AD=mysql_fetch_assoc($sql))
		{
			$id_alumno=$AD["id"];
			$rut=$AD["rut"];
			$nombre=ucwords(strtolower($AD["nombre"]));
			$apellido_old=$AD["apellido"];
			$apellido_new=$AD["apellido_P"]." ".$AD["apellido_M"];;
			$carrera=$AD["carrera"];
			$year_ingreso=$AD["ingreso"];
			$nivel=$AD["nivel"];
			$jornada=$AD["jornada"];
			$porcentaje_beca=$AD["porcentaje_beca"];
			$cantidad_beca=$AD["cantidad_beca"];
			$txt_beca=$AD["txt_beca"];
			if($apellido_new==" ")
			{ $apellido_label=$apellido_old;}
			else
			{ $apellido_label=$apellido_new;}
			
			$apellido_label=ucwords(strtolower($apellido_label));
			echo'<tr>
			  <td>'.$id_alumno.'</td>
			  <td>'.$rut.'</td>
			  <td>'.$nombre.'</td>
			  <td>'.$apellido_label.'</td>
			  <td>'.$year_ingreso.'</td>
			  <td>'.$nivel.'</td>
			  <td>'.$jornada.'</td>
			  <td>'.$porcentaje_beca.'%</td>
			  <td>'.number_format($cantidad_beca,0,",",".").'</td>
			  <td>'.$txt_beca.'</td>
			</tr>';
		
			$aux++;
			
		}
	}
	else
	{
		echo'<tr><td colspan="8">Sin registros</td></tr>';
		
	}
	echo'<tr>
<td colspan="8">('.$aux.')Alumnos Encontrados</td>
</tr>
 </table> ';
}
else
{}
?>