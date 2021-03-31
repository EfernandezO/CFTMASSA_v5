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
<html>
<head>
<title>Confirme Devolucion</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:126px;
	height:22px;
	z-index:1;
	left: 537px;
	top: 51px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:8;
	left: 5%;
	top: 113px;
}
#apDiv3 {
	position:absolute;
	width:40%;
	height:25px;
	z-index:9;
	left: 30%;
	top: 459px;
	text-align: center;
}
-->
</style>
<script language="javascript">
function CONFIRMAR()
{
	c=confirm('Seguro(a) Desea Devolver este Libro...?');
	if(c)
	{
		document.getElementById('frm').submit();
	}
}
</script>
</head>
<body>
<h1 id="banner">Administrador -Biblioteca</h1>

<div id="link"><br>
<a href="../enrutador_main.php?destino=biblioteca" class="button">Volver a biblioteca</a></div>
<p>
<?php 
if(isset($_GET["id_libro"])and isset($_GET["id_alumno"]))
{
	
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funcion.php");
	
	$id_libro=mysqli_real_escape_string($conexion_mysqli, $_GET["id_libro"]);
	$id_alumno=mysqli_real_escape_string($conexion_mysqli, $_GET["id_alumno"]);
	
	$cons="SELECT * FROM biblioteca WHERE biblioteca.id_libro='$id_libro' LIMIT 1";
	if(DEBUG){ echo"MAIN: $cons<br>";}
	$sql_1=$conexion_mysqli->query($cons);
	$A=$sql_1->fetch_assoc();
		
		$condicion_actual_libro=$A["prestado"];
		if(empty($condicion_actual_libro))
		{ $condicion_actual_libro="N";}

		//si esta prestado
		if($condicion_actual_libro=="S")
		{ $_SESSION["BIBLIOTECA"]["devolver"]=true;}
		else
		{ $_SESSION["BIBLIOTECA"]["devolver"]=false;}
		
	  $nombrel=$A["nombre"];
	  $editorial=$A["editorial"];
	  
	  $sql_1->free();
	  
	  $cons_RB="SELECT * FROM biblioteca_registro WHERE id_alumno='$id_alumno' AND id_libro='$id_libro' AND condicion='prestado' ORDER by id DESC LIMIT 1";
	  if(DEBUG){ echo"--->$cons_RB<br>";}
	  $sqli_RB=$conexion_mysqli->query($cons_RB);
	  $num_registros=$sqli_RB->num_rows;
	   if(DEBUG){ echo"Numeros Registros: $num_registros<br>";}
	  if($num_registros>0)
	  {
		  $RB=$sqli_RB->fetch_assoc();
		  
		  $fecha_prestamo=$RB["fecha_prestamo"];
		  $fecha_devolucion=$RB["fecha_devolucion"];

	  }
	  else
	  {
		  echo"Sin Registros";
	  }
	  $sqli_RB->free();
	
	
	$cons="SELECT rut, nombre,apellido_P, apellido_M, id_carrera, carrera, sede FROM alumno WHERE id='$id_alumno' LIMIT 1";
	$sqliA=$conexion_mysqli->query($cons);
	$B=$sqliA->fetch_assoc();
	  $rut_alumno=$B["rut"];
	  $nombreA=$B["nombre"];
	  $apellidoA=$B["apellido_P"]." ".$B["apellido_M"];
	  $id_carrera=$B["id_carrera"];
	  $carrera=$B["carrera"];
	  $sede=$B["sede"];

	if($nombrel=="")
	{
	  $nombrel="No Registrado";
	}
	if($editorial=="")
	{
	 $editorial="No Registrada";
	}
	$sqliA->free();
	@mysql_close($conexion);
	$conexion_mysqli->close();
}
else
{ header("location: ../../menu_biblioteca.php");}
?>
<div id="apDiv2">
<form name="frm" id="frm" method="post" action="devolver2.php">
<table width="60%" border="0" align="center">
  <thead>
		<tr>
		  <th colspan="3">Datos del Libro Prestado
		    <input type="hidden" name="id_libro" id_libro="id_libro" value="<?php echo $id_libro;?>">
            <input name="id_alumno" type="hidden" id="id_alumno" value="<?php echo $id_alumno;?>">
            <input name="rut_alumno" id="rut_alumno" type="hidden" value="<?php echo $rut_alumno;?>">
            <input name="carrera" type="hidden" id="carrera" value="<?php echo $carrera;?>">
            <input name="id_carrera" type="hidden" id="id_carrera" value="<?php echo $id_carrera;?>">
          <input name="sede" type="hidden" id="sede" value="<?php echo $sede;?>">
          (<?php echo $condicion_actual_libro;?>)</th>
		</tr>
  </thead>
    <tbody>
      <td colspan="3">-&iquest;Que Libro?</td>
		</tr>
		<tr>
		  <td width="47%"><strong>Titulo:</strong></td>
		  <td width="53%" colspan="2" ><?php echo $nombrel;?></td>
		</tr>
		<tr>
		  <td ><strong>Editorial:</strong></td>
		  <td colspan="2" ><?php echo $editorial;?></td>
		</tr>
		<tr>
		  <td colspan="3">&nbsp;</td>
    </tr>
		<tr>
		  <td colspan="3">-&iquest;A Quien?</td>
		</tr>
		<tr>
		  <td ><strong>Rut del alumno </strong></td>
		  <td colspan="2" ><?php echo $rut_alumno;?></td>
    </tr>
		<tr>
		  <td ><strong>Nombre del Alumno </strong></td>
		  <td colspan="2" ><?php echo $nombreA;?></td>
		</tr>
		<tr>
		  <td ><strong>Apellido del Alumno </strong></td>
		  <td colspan="2" ><?php echo $apellidoA;?></td>
		</tr>
		<tr>
		  <td ><strong>F.Prestamo</strong></td>
		  <td colspan="2" ><?php echo fecha_format($fecha_prestamo);?></td>
    </tr>
		<tr>
		  <td ><strong>F.Devolucion</strong></td>
		  <td colspan="2" ><?php echo fecha_format($fecha_devolucion);?></td>
		</tr>
        </tbody>
</table>
</form>
</div>
<div id="apDiv3"><a href="#" class="button_G" onClick="CONFIRMAR();">DEVOLVER</a></div>
</body>
</html>