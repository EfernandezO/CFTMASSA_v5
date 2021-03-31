<?php require ("../../SC/seguridad.php");?>
<?php require ("../../SC/privilegio2.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Visor de Boleta</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<style type="text/css">
<!--
#link {
	text-align: right;
	padding-right: 10px;
}
#apDiv1 {
	position:absolute;
	width:450px;
	height:115px;
	z-index:1;
	left: 5px;
	top: 59px;
}
.Estilo1 {font-size: 12px}
-->
</style>
</head>


<body>
<h1 id="banner">Administrador - Ver Boleta</h1>
<div id="link"></div>
<?php
if($_GET)
{
	$id_boleta=base64_decode($_GET["id_boleta"]);
	include("../../../funciones/conexion.php");
	$cons="SELECT * FROM boleta WHERE id='$id_boleta'";
	
	//echo"--> $cons<br>";
	$sql=mysql_query($cons)or die(mysql_error());
	$DB=mysql_fetch_assoc($sql);
	
	$id_alumno=$DB["id_alumno"];
	////////////-------------------------------------///////////
		$cons_A="SELECT * FROM alumno WHERE id='$id_alumno'";
		//echo"x-> $cons_A<br>";
		$sqlA=mysql_query($cons_A)or die("alumno :".mysql_error());
		$DA=mysql_fetch_assoc($sqlA);
		$nombre=$DA["nombre"];
		$alumno=$nombre." ";
		$apellido_P=$DA["apellido_P"];
		$apellido_M=$DA["apellido_M"];
		
		$apellido_new=$apellido_P." ".$apellido_M;
		if($apellido_new==" ")
		{
			$alumno.=$DA["apellido"];
		}
		else
		{
			$alumno.=$apellido_new;
		}
		
		$carrera_alumno=$DA["carrera"];
		mysql_free_result($sqlA);
	//////////------------------------------------/////////////
	$valor=$DB["valor"];
	$glosa=$DB["glosa"];
	$fecha=$DB["fecha"];
	$folio=$DB["folio"];
	$sede=$DB["sede"];

	$glosa=str_replace("[br]","<br>",$glosa);

	mysql_free_result($sql);
	mysql_close($conexion);
}
else
{
	echo"Sin datos para consultar<br>";
}
?>
<div id="apDiv1">
  <table width="100%" border="0">
    <tr>
      <td colspan="2" bgcolor="#e5e5e5"><strong>?Datos de Boleta</strong></td>
    </tr>
    <tr>
      <td bgcolor="#f5f5f5"><span class="Estilo1">Alumno</span></td>
      <td bgcolor="#f5f5f5"><span class="Estilo1"><em><?php echo $alumno;?></em></span></td>
    </tr>
    <tr>
      <td width="29%" bgcolor="#f5f5f5"><span class="Estilo1">ID</span></td>
      <td width="71%" bgcolor="#f5f5f5"><span class="Estilo1"><em><?php echo $id_boleta;?></em></span></td>
    </tr>
    <tr>
      <td bgcolor="#f5f5f5">Carrera</td>
      <td bgcolor="#f5f5f5"><?php echo $carrera_alumno;?></td>
    </tr>
    <tr>
      <td bgcolor="#f5f5f5"><span class="Estilo1">Folio</span></td>
      <td bgcolor="#f5f5f5"><span class="Estilo1"><em><?php echo $folio;?></em></span></td>
    </tr>
    <tr>
      <td bgcolor="#f5f5f5"><span class="Estilo1">Fecha</span></td>
      <td bgcolor="#f5f5f5"><span class="Estilo1"><em><?php echo $fecha;?></em></span></td>
    </tr>
    <tr>
      <td bgcolor="#f5f5f5"><span class="Estilo1">Sede</span></td>
      <td bgcolor="#f5f5f5"><em><?php echo $sede;?></em></td>
    </tr>
    <tr>
      <td bgcolor="#f5f5f5"><span class="Estilo1">Glosa</span></td>
      <td bgcolor="#f5f5f5"><span class="Estilo1"><em><?php echo $glosa;?></em></span></td>
    </tr>
    <tr>
      <td bgcolor="#f5f5f5">Valor</td>
      <td bgcolor="#f5f5f5"><em><?php echo number_format($valor,0,",",".");?></em></td>
    </tr>
  </table>
</div>
</body>
</html>
