<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("gestion_cobranza_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>info pagos</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 166px;
}
</style>
</head>
<body>
<h1 id="banner">Finanzas - informacion de pagos</h1>

<?php
if($_GET)
{
	$continuar=true;
	$id_alumno=base64_decode($_GET["id_alumno"]);
	$id_carrera=base64_decode($_GET["id_carrera"]);
	$fecha_corte=base64_decode($_GET["fecha_corte"]);
	$year_cuota=base64_decode($_GET["year_cuota"]);
}
else{ $continuar=false;}
?>
<div id="apDiv1">
<?php if($continuar){?>
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="6">Cuotas</th>
    </tr>
    <tr>
      <td>N</td>
      <td>Tipo</td>	
      <td>Fecha Vencimiento</td>
       <td>Deuda X cuota</td>
       <td>fecha ultimo pago</td>
      <td>Condicion</td>
    </tr>
    </thead>
    <tbody>
   <?php
   require("../../../../funciones/conexion_v2.php");
   require("../../../../funciones/funciones_sistema.php");
   
   $cons="SELECT * FROM letras WHERE idalumn='$id_alumno' ORDER by fechavenc";
   if(DEBUG){ echo"---->$cons<br>";}
   $sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
   $num_cuotas=$sqli->num_rows;
   
   if($num_cuotas>0)
   {
	   $aux=0;
		while($C=$sqli->fetch_assoc())
		{
			$aux++;
			$C_id=$C["id"];	
			$C_deudaXletra=$C["deudaXletra"];
			$C_fecha_ultimo_pago=$C["fecha_ultimo_pago"];
			$C_fecha_vencimiento=$C["fechavenc"];
			$C_condicion=strtoupper($C["pagada"]);
			$C_tipo=$C["tipo"];
			
			switch($C_condicion)
			{
				case"S":
					$C_condicion_label="pagada";
					break;
				case"A":
					$C_condicion_label="abonada";
					break;
				case"N":
					$C_condicion_label="pendiente";
					break;		
			}
			echo' <tr>
					  <td>'.$aux.'</td>
					   <td>'.$C_tipo.'</td>	
					  <td>'.$C_fecha_vencimiento.'</td>
					   <td align="right">$'.number_format($C_deudaXletra,0,",",".").'</td>
					   <td>'.$C_fecha_ultimo_pago.'</td>
					  <td>'.$C_condicion_label.'</td>
					</tr>';
			
		}
   }
	$sqli->free();
	$conexion_mysqli->close();
   ?>
    </tbody>
  </table>
 <?php }?> 
</div>
</body>
</html>