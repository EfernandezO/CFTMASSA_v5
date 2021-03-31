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
$continuar=false;
if(isset($_GET["id_libro"]))
{ 
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funcion.php");
	$id_libro=mysqli_real_escape_string($conexion_mysqli, $_GET["id_libro"]);
	if(is_numeric($id_libro))
	{
	 $cons_M="SELECT * FROM biblioteca WHERE id_libro='$id_libro' LIMIT 1";
	 $sql_M=$conexion_mysqli->query($cons_M);
	 	$DL=$sql_M->fetch_assoc();
		$LM_titulo=$DL["nombre"];
		$LM_autor=$DL["autor"];
		$LM_editorial=$DL["editorial"];
		$LM_pretado=$DL["prestado"];
		if($LM_pretado=="S")
		{ $LM_prestado_label="Prestado";}
		else
		{ $LM_prestado_label="Disponible";}
		
		$LM_num_presta=$DL["numpresta"];
		//$LM_num_imagen=$DL["numimg"];
		//$LM_num_pdf=$DL["num_pdf"];
		$sql_M->free();
		$continuar=true;
	}
}
else
{ header("location: ../../menu_biblioteca.php");}
?>
<html>
<head>
<title>Historial de Libro</title>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
 
<style type="text/css">
#Layer1 {
	position:absolute;
	width:200px;
	height:270px;
	z-index:42;
	left: 26px;
	top: 133px;
}
.Estilo2 {
	font-size: 18px;
	font-weight: bold;
}
#Layer2 {
	position:absolute;
	width:200px;
	height:33px;
	z-index:44;
	left: 215px;
	top: 108px;
}
#apDiv1 {
	position:absolute;
	width:90%;
	height:70px;
	z-index:44;
	left: 5%;
	top: 84px;
}
-->
</style>
</head>

<body>
<h1 id="banner">Biblioteca - Historial Libro</h1>
<div id="link">
  <div align="right"><br>
<a href="../../menu_biblioteca.php" class="button">Volver a Biblioteca</a></div>
</div>
<div id="apDiv1">
  <table width="50%" border="1">
  <thead>
    <tr>
      <th colspan="2">Libro</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="33%">Titulo</td>
      <td width="67%"><?php echo $LM_titulo;?></td>
    </tr>
    <tr>
      <td>Autor</td>
      <td><?php echo $LM_autor;?></td>
    </tr>
    <tr>
      <td>Editorial</td>
      <td><?php echo $LM_editorial;?></td>
    </tr>
    <tr>
      <td>Estado Actual</td>
      <td><?php echo $LM_prestado_label;?></td>
    </tr>
    <tr>
      <td>Cantidad Prestamos</td>
      <td><?php echo $LM_num_presta;?></td>
    </tr>
    <tr>
      <td>Num img Asociadas</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Num PDF Asociados</td>
      <td>&nbsp;</td>
    </tr>
    </tbody>
  </table>
</div>
<div id="Layer7" style="position:absolute; left:5%; top:355px; width:90%; height:68px; z-index:43;"> 
    <table width="70%" height="29" align="left">
    	<thead>
      <tr>
        <th width="77" height="23" colspan="10">Historial</th>
      </tr>
      <tr>
          <td align="center">N</td>
          <td align="center">Accion</td>
          <td align="center">Fecha Prestamo</td>
          <td align="center">Fecha Devolucion</td>
          <td colspan="4" align="center">Quien pide</td>
          <td colspan="2" align="center">Quien Presta</td>
      </tr>
       </thead>
      <tbody>
      <?php
	  if($continuar)
	  {
		  $cons="SELECT  * FROM biblioteca_registro WHERE id_libro='$id_libro' ORDER by id desc";
		  $sql=mysql_query($cons)or die(mysql_error());
		  $num_registro=mysql_num_rows($sql);
		  if($num_registro>0)
		  {
			  $contador=0;
			  while($L=mysql_fetch_assoc($sql))
			  {
				  $contador++;
				  
				  $L_id_alumno=$L["id_alumno"];
				  //////////////////////////////////
					$cons_A="SELECT rut, nombre, apellido_P, apellido_M, sexo FROM alumno WHERE id='$L_id_alumno' LIMIT 1";
					$sql_A=mysql_query($cons_A)or die("alumno: ".mysql_error());
					$DA=mysql_fetch_assoc($sql_A);
						$A_rut=$DA["rut"];
						$A_nombre=$DA["nombre"];
						$A_apellido_P=$DA["apellido_P"];
						$A_apellido_M=$DA["apellido_M"];
						$A_sexo=$DA["sexo"];
					mysql_free_result($sql_A);	
				  ///////////////////////////////////
				  $L_id_carrera=$L["id_carrera"];
				  $L_sede=$L["sede"];
				  $L_condicion=$L["condicion"];
				  $L_fecha_prestamo=$L["fecha_prestamo"];
				  $L_fecha_devolucion=$L["fecha_devolucion"];
				  $L_fecha_registro=$L["fecha_registro"];
				  $L_cod_user=$L["cod_user"];
				  /////////////////////
					$cons_P="SELECT nombre, apellido FROM personal WHERE id='$L_cod_user' LIMIT 1";
					$sql_P=mysql_query($cons_P)or die(mysql_error());
					$DP=mysql_fetch_assoc($sql_P);
						$P_nombre=$DP["nombre"];
						$P_apellido=$DP["apellido"];
					mysql_free_result($sql_P);	
				  /////////////////////
				  
				  echo'<tr>
							<td>'.$contador.'</td>
							<td>'.$L_condicion.'</td>
							<td>'.fecha_format($L_fecha_prestamo).'</td>
							<td>'.fecha_format($L_fecha_devolucion).'</td>
							<td>'.$A_rut.'</td>
							<td>'.$A_nombre.'</td>
							<td>'.$A_apellido_P.'</td>
							<td>'.$A_apellido_M.'</td>
							<td>'.$P_nombre.'</td>
							<td>'.$P_apellido.'</td>
						</tr>';
			  }
			  mysql_free_result($sql);
		  }
		  else
		  {
			  echo'<tr><td colspan="10">Sin Registros Encontrados :(</td></tr>';
		  }
		  mysql_close($conexion);
	  }
	  else
	  {
		  echo"Sin Datos<br>";
	  }
      ?>
 </tbody>
    </table>
</div>

</body>
</html>