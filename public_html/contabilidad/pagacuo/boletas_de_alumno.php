<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumnos_Pago_Mensualidades_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$continuar=false;
require("../../../funciones/conexion_v2.php");
if(isset($_GET["id_alumno"]))
{
	$id_alumno=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["id_alumno"]));
	if(is_numeric($id_alumno))
	{
		 $continuar=true;
		 $cons_A="SELECT nombre, apellido_P, apellido_M, id_carrera, carrera FROM alumno WHERE id='$id_alumno' LIMIT 1";
		 $sqli_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
		 $DA=$sqli_A->fetch_assoc();
		 	$A_nombre=$DA["nombre"];
			$A_apellido_P=$DA["apellido_P"];
			$A_apellido_M=$DA["apellido_M"];
			$A_id_carrera=$DA["id_carrera"];
			$A_carrera=$DA["carrera"];
		$sqli_A->free();	
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Boletas de Alumno</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 171px;
}
</style>
</head>

<body>
<h1 id="banner">Finanzas - Detalle de Boletas de Alumno</h1>
<div id="apDiv1">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="7">Boletas de Alumno -><?php echo"$A_nombre $A_apellido_P $A_apellido_M <br>Carrera: [$A_id_carrera] $A_carrera";?></th>
    </tr>
    <tr>
    	<td>ID</td>
        <td>Fecha</td>
        <td>Folio</td>
        <td>Valor</td>
        <td>Caja</td>
        <td>Glosa</td>
        <td>cod_user</td>
    </tr>
    </thead>
    <tbody>
  <?php
  if($continuar)
  {
	  $cons_B="SELECT * FROM boleta WHERE id_alumno='$id_alumno' ORDER by fecha";
	  $sqli=$conexion_mysqli->query($cons_B)or die($conexion_mysqli->error);
	  $num_boletas=$sqli->num_rows;
	  if($num_boletas>0)
	  {
		  while($DI=$sqli->fetch_assoc())
		  {
			  $B_id=$DI["id"];
			  $B_fecha=$DI["fecha"];
			  $B_folio=$DI["folio"];
			  $B_valor=$DI["valor"];
			  $B_glosa=$DI["glosa"];
			  $B_caja=$DI["caja"];
			  $B_cod_user=$DI["cod_user"];
			  
			  echo'<tr>
			  			<td>'.$B_id.'</td>
						<td>'.$B_fecha.'</td>
						<td>'.$B_folio.'</td>
						<td align="right">$'.number_format($B_valor,0,",",".").'</td>
						<td>'.$B_caja.'</td>
						<td>'.$B_glosa.'</td>
						<td>'.$B_cod_user.'</td>
			  	   </tr>';
		  }
	  }
	  else
	  {  echo'<tr><td colspan="7">Sin Boletas...</td></tr>';}
	  $sqli->free();
  }
  ?>
    <tr>
      <td colspan="7"><?php echo"($num_boletas) registradas...";?></td>
    </tr>
    </tbody>
  </table>
</div>
</body>
</html>