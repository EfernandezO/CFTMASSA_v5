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

if(isset($_GET["id_cuota"]))
{
	$id_cuota=base64_decode($_GET["id_cuota"]);
	if(is_numeric($id_cuota)){ $continuar=true;}
	else{ $continuar=false;}
}
else
{ $continuar=false;}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<?php include("../../../funciones/codificacion.php");?>
<title>Detalle de Mensualidad</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:291px;
	height:171px;
	z-index:1;
	left: 174px;
	top: 179px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
#Layer2 {
	position:absolute;
	width:90%;
	height:243px;
	z-index:2;
	left: 5%;
	top: 78px;
}
.Estilo3 {
	color: #FF0000;
	font-weight: bold;
	font-size: 12px;
}
.Estilo5 {
	font-weight: bold;
	color: #0080C0;
}
#Layer3 {
	position:absolute;
	width:397px;
	height:49px;
	z-index:1;
	left: 65px;
	top: 34px;
}
-->
</style>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<style type="text/css">
<!--
.Estilo6 {
	font-size: 12px;
	font-weight: bold;
}
.Estilo7 {font-size: 12px}
.Estilo9 {font-size: 12px; color: #0000FF; }
-->
</style>
</head>

<body>
<h1 id="banner">Finanzas -Detalle Mensualidad Linea de Credito</h1>
<div id="link"><br />
<a href="cuota1.php" class="button">Volver a Seleccion</a></div>
<div id="Layer2">
  <table width="100%" sumary="">
  <caption>Detalle de Pagos por Cuota</caption>
  <thead>
    <tr>
      <th colspan="2" scope="col"><span class="Estilo6">informacion de Mensualidad </span></th>
    </tr>
	<thead>
	<tbody>
    <tr class="odd">
      <td width="21%" ><span class="Estilo6">ID Cuota: </span></td>
      <td width="79%" scope="col"><span class="Estilo7"><?php echo"$id_cuota";?></span></td>
    </tr>
	</tbody>
  </table>
  
  <table width="100%" Sumary="">
  <thead>
    <tr>
      <th>id_pago</th>
      <th>Monto Abono</th>
      <th>Fecha Transaccion</th>
      <th>Forma Pago</th>
      <th>Comentario</th>
      </tr>
    </thead>
	<tbody>
	<?php
 if($continuar)
 {
	$sedeZ=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funcion.php");
	$consP="SELECT * FROM pagos WHERE id_cuota='$id_cuota' and id_alumno='$id_alumno' and tipodoc='cuota' ORDER BY idpago";
	$sqli=$conexion_mysqli->query($consP);
	$num_pagos=$sqli->num_rows;
	if($num_pagos>0)
	{
	   $aux=0;
	   $array_id_boleta=array();
	   while($C=$sqli->fetch_assoc())
	   { 
	   	  $id_pago=$C["idpago"];
	   	  $id_boleta=$C["id_boleta"];
	      $fechapago=fecha_format($C["fechapago"]);
	      $valor=$C["valor"];
		  $comentario=$C["glosa"];
		  $forma_pago=$C["forma_pago"];
		  
		  if(in_array($id_boleta, $array_id_boleta))
		  {}
		  else{$array_id_boleta[$aux]=$id_boleta;  $aux++;}
	     
			///////////////////---------------------------/////////////////////
			echo'
            <tr>
				<td align="center">'.$id_pago.'</td>
				<td align="center">$'.number_format($valor,0,",",".").'</td>
				<td align="center">'.$fechapago.'</td>
				<td align="center">'.$forma_pago.'</td>
				<td align="center"><em>'.$comentario.'</em></td>
			</tr>';
			
	    }
		
	}
	else
	{
	
		echo' <tr>
	   <td colspan="6"><div align="center" class="Estilo3">Cuota No pagada y sin Abonos</div></td>
      </tr>';
	
	}
	$sqli->free();
 }
 else
 {
	 
		echo' <tr>
	   <td colspan="6"><div align="center" class="Estilo3">Sin Datos...</div></td>
      </tr>';
 }	
?>
  </tbody>
</table>
<br />

<table width="100%" border="1">
<thead>
  <tr>
    <th colspan="6">Boletas Relacionadas</th>
  </tr>
  <tr>
  	<td>id_boleta</td>
  	<td>Folio</td>
    <td>Fecha</td>
    <td>Caja</td>
    <td>Valor</td>
    <td>Glosa</td>
  </tr>
 </thead>
 <tbody>
<?php
if(count($array_id_boleta)>0)
{
	foreach($array_id_boleta as $i => $aux_id_boleta)
	{
		$cons_B="SELECT * FROM boleta WHERE id='$aux_id_boleta' LIMIT 1";
		$sqli_B=$conexion_mysqli->query($cons_B);
		$B=$sqli_B->fetch_assoc();
			$B_valor=$B["valor"];
			$B_glosa=$B["glosa"];
			$B_glosa=str_replace("[br]","<br>",$B_glosa);
			$B_fecha=$B["fecha"];
			$B_folio=$B["folio"];
			$B_caja=$B["caja"];
		$sqli_B->free();	
		
		echo'<tr>
				<td>'.$aux_id_boleta.'</td>
				<td>'.$B_folio.'</td>
				<td>'.fecha_format($B_fecha).'</td>
				<td>'.$B_caja.'</td>
				<td>$'.number_format($B_valor,0,",",".").'</td>
				<td>'.$B_glosa.'</td>
			 </tr>';
	}
	$conexion_mysqli->close();
}
else
{
	echo'<tr><td colspan="6">Sin Boletas Registradas...</td></tr>';
}
?>
 </tbody> 
</table>

</div>
</body>
</html>