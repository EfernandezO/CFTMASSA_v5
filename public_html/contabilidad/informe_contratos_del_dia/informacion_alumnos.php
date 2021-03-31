<?php 
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Matriculas_generadas_X_rango_F_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

$continuar_1=false;
$continuar_2=false;
$continuar_3=false;
if($_GET)
{
	if(isset($_GET["id_carrera"]))
	{
		$id_carrera=$_GET["id_carrera"];
		if(is_numeric($id_carrera))
		{$continuar_1=true;}
	}
	
	if(isset($_GET["nivel"]))
	{
		$nivel=$_GET["nivel"];
		if(is_numeric($nivel))
		{$continuar_2=true;}
	}
	
	if(isset($_GET["jornada"]))
	{
		$jornada=$_GET["jornada"];
		if(($jornada=="V")or($jornada=="D"))
		{ $continuar_3=true;}
	}
	
	if(isset($_GET["fecha_ini"]))
	{
		$fecha_ini=base64_decode($_GET["fecha_ini"]);
	}
	
	if(isset($_GET["fecha_fin"]))
	{
		$fecha_fin=base64_decode($_GET["fecha_fin"]);
	}
	
	if(isset($_GET["sede"]))
	{
		$sede=base64_decode($_GET["sede"]);
	}
	
	if(isset($_GET["niveles_consultados"]))
	{
		$niveles_consultados=base64_decode($_GET["niveles_consultados"]);
	}
	
	if(isset($_GET["year_ingreso"]))
	{
		$year_ingreso=base64_decode($_GET["year_ingreso"]);
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>Detalle de Alumnos</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:60%;
	height:115px;
	z-index:1;
	left: 2%;
	top: 55px;
}
#apDiv2 {
	position:absolute;
	width:96%;
	height:115px;
	z-index:2;
	left: 2%;
	top: 296px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Detalle de Alumnos</h1>
<?php if($continuar_1 and $continuar_2 and $continuar_3){
if(DEBUG){ var_dump($_GET);}
?>
<div id="apDiv1">
<table width="100%" border="1">
<thead>
  <tr>
    <th colspan="2">Informacion</th>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td width="18%">Periodo</td>
    <td width="82%"><?php echo "$fecha_ini - $fecha_fin";?></td>
  </tr>
  <tr>
    <td>Sede</td>
    <td><?php echo $sede;?></td>
  </tr>
  <tr>
    <td>carrera</td>
    <td><?php echo $id_carrera;?></td>
  </tr>
  <tr>
    <td>Jornada</td>
    <td><?php echo $jornada;?></td>
  </tr>
  <tr>
    <td>Nivel</td>
    <td><?php echo $nivel;?></td>
  </tr>
  <tr>
    <td>A&ntilde;o ingreso</td>
    <td><?php echo $year_ingreso;?></td>
  </tr>
  </tbody>
</table>
</div>

<div id="apDiv2">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="19">Alumnos</th>
    </tr>
    <tr>
    		<td>N</td>
      		<td>id_contrato</td>
			<td>id_alumno</td>
			<td>Sexo</td>
			<td>Rut</td>
      		<td>Nombre</td>
			<td>Apellido_P</td>
			<td>Apellido_M</td>
      		<td>Carrera</td>
			<td>Jornada</td>
      		<td>Año ingreso</td>
			<td>Nivel</td>
			<td>Total contrato</td>
			<td>Matricula</td>
			<td>txt beca</td>
			<td>descuento</td>
			<td>% descuento</td>
			<td>Vigencia contrato</td>
			<td>condicion</td>
    		</tr>
    </thead>
    <tbody>
   <?php
   if($nivel=="Todos")
	{$condicion_nivel="";}
	else
	{$condicion_nivel="alumno.nivel='$nivel' AND";}
	
	if($year_ingreso=="Todos")
	{$condicion_ingreso="";}
	else
	{$condicion_ingreso="AND alumno.ingreso='$year_ingreso'";}
	
	if($sede=="todas")
	{$condicion_sede="";}
	else
	{$condicion_sede="contratos2.sede='$sede' AND ";}
	
	if($id_carrera>0){ $condicion_carrera="contratos2.id_carrera='$id_carrera' AND ";}
	else{ $condicion_carrera="";}
	
	if($jornada=="todas"){ $condicion_jornada="";}
	else{ $condicon_jornada="alumno.jornada='$jornada' AND  ";}
	
		$consC="SELECT contratos2.id AS id_contrato, contratos2.id_alumno AS id_alumno, contratos2.sede,  contratos2.matricula_a_pagar, contratos2.txt_beca, contratos2.cantidad_beca, contratos2.porcentaje_beca, contratos2.total, contratos2.vigencia, contratos2.condicion, alumno.nivel, alumno.rut, alumno.nombre, alumno.apellido_P, alumno.apellido_M, alumno.carrera, alumno.jornada, alumno.ingreso, alumno.sexo FROM contratos2 INNER JOIN alumno ON contratos2.id_alumno = alumno.id WHERE $condicion_carrera $condicon_jornada $condicion_nivel $condicion_sede contratos2.condicion IN('ok', 'retiro') $condicion_ingreso AND contratos2.fecha_generacion BETWEEN '$fecha_ini' AND '$fecha_fin' ORDER by carrera, nivel, apellido_P, apellido_M";
	
	if(DEBUG){ echo"$consC<br>";}
	
	require("../../../funciones/conexion_v2.php");
	$sqli=$conexion_mysqli->query($consC)or die($conexion_mysqli->error);
	$num_matriculados=$sqli->num_rows;
	if($num_matriculados>0)
	{
		$aux=0;
		while($A=$sqli->fetch_assoc())
		{
			$aux++;
			
			$id_contrato=$A["id_contrato"];
			$id_alumno=$A["id_alumno"];
			$rut=$A["rut"];
			$nombre=$A["nombre"];
			$apellido_P=$A["apellido_P"];
			$apellido_M=$A["apellido_M"];
			$carrera=$A["carrera"];
			$jornada=$A["jornada"];
			$ingreso=$A["ingreso"];
			$sexo_alumno=$A["sexo"];
			
			$matricula_a_pagar=$A["matricula_a_pagar"];
			$txt_beca=$A["txt_beca"];
			$cantidad_desc=$A["cantidad_beca"];
			$porcentaje_dec=$A["porcentaje_beca"];
			$total_contrato=$A["total"];
			$vigencia_contrato=$A["vigencia"];
			$contrato_condicion=$A["condicion"];
			
			$nivel_alumno=$A["nivel"];
			
			$SUMA_X_SEXO[$sexo_alumno]+=1;
			
			echo'<tr>
			<td>'.$aux.'</td>
      		<td>'.$id_contrato.'</td>
			<td>'.$id_alumno.'</td>
			<td>'.$sexo_alumno.'</td>
			<td>'.$rut.'</td>
      		<td>'.$nombre.'</td>
			<td>'.$apellido_P.'</td>
			<td>'.$apellido_M.'</td>
      		<td>'.$carrera.'</td>
			<td>'.$jornada.'</td>
      		<td>'.$ingreso.'</td>
			<td>'.$nivel_alumno.'</td>
			<td>'.$total_contrato.'</td>
			<td>'.$matricula_a_pagar.'</td>
			<td>'.$txt_beca.'</td>
			<td>'.$cantidad_desc.'</td>
			<td>'.$porcentaje_dec.'</td>
			<td>'.$vigencia_contrato.'</td>
			<td>'.$contrato_condicion.'</td>
    		</tr>';
		}	
		echo'<tr><td colspan="19">Total Matriculas Generadas '.$num_matriculados.'</td></tr>';
	}
	else
	{
		echo'<tr>
      		<td colspan="19">Sin Registros :(</td>
    		</tr>';
	}
	$sqli->free();
	$conexion_mysqli->close();
   ?>
    </tbody>
  </table>

</div>
<?php }?>
</body>
</html>