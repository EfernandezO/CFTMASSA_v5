<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("pago_honorario_docente_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(isset($_GET["H_id"]))
{
	$id_honorario=base64_decode($_GET["H_id"]);
	if(is_numeric($id_honorario)){ $continuar=true;}
	else{ $continuar=false;}
}
else
{ $continuar=false;}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php"); ?>
<title>Detalle Honorario Docente</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:65px;
	z-index:1;
	left: 5%;
	top: 118px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Ver Detalle Pago Honorario Docente</h1>
<div id="apDiv1">
  <table width="80%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="6">Informacion de Pago de Honorario </th>
    </tr>
    <tr>
    	<td>N.</td>
        <td>Sede</td>
        <td>Fecha Pago</td>
        <td>Forma de Pago</td>
        <td>Boleta Honorario</td>
        <td>Valor</td>
    </tr>
    </thead>
    <tbody>

<?php
if($continuar)
{
	require("../../../../../funciones/conexion_v2.php");
	require("../../../../../funciones/funciones_sistema.php");
	
	$cons="SELECT * FROM honorario_docente_pagos WHERE id_honorario='$id_honorario'";
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_registros=$sqli->num_rows;
	$ruta="../../../../CONTENEDOR_GLOBAL/boleta_honorario_docente/";
	
	if($num_registros>0)
	{
		$aux=0;
		while($PH=$sqli->fetch_assoc())
		{
			$aux++;
			
			$PH_id=$PH["id"];
			$PH_id_funcionario=$PH["id_funcionario"];
			$PH_sede=$PH["sede"];
			$PH_forma_pago=$PH["forma_pago"];
			$PH_fecha_pago=$PH["fecha_pago"];
			$PH_id_cheque=$PH["id_cheque"];
			$PH_valor=$PH["valor"];
			$PH_cod_user=$PH["cod_user"];
			$informacion_pago=$PH_forma_pago;
			$PH_archivo=$PH["archivo"];
			
			if(empty($PH_archivo))
			{
				$link_boleta='<a href="cargar_boleta_honorario_pendiente_1.php?id_funcionario='.base64_encode($PH_id_funcionario).'&id_honorario='.base64_encode($id_honorario).'&id_pago_honorario='.base64_encode($PH_id).'">Cargar Boleta</a>';
			}
			else
			{
				$link_boleta='<a href="'.$ruta.''.$PH_archivo.'" target="_blank" title="click para ver Boleta">'.$PH_archivo.'</a>';
			}
			
			if($PH_id_cheque>0)
			{
				$cons_CH="SELECT * FROM registro_cheques WHERE id='$PH_id_cheque' LIMIT 1";
				$sqli_ch=$conexion_mysqli->query($cons_CH);
				$CH=$sqli_ch->fetch_assoc();
					$CH_numero=$CH["numero"];
					$CH_banco=$CH["banco"];
				$sqli_ch->free();	
				$informacion_pago.=" [Numero: $CH_numero Banco: $CH_banco]";
			}
			
			echo'<tr>
					<td>'.$aux.'</td>
					<td>'.$PH_sede.'</td>
					<td>'.$PH_fecha_pago.' por ['.nombre_personal($PH_cod_user). ']</td>
					<td>'.$informacion_pago.'</td>
					<td>'.$link_boleta.'</td>
					<td>$'.number_format($PH_valor,0,",",".").'  <a href="../pago_honorario/comprobante_pago_docente_pdf.php?id_funcionario='.base64_encode($PH_id_funcionario).'&id_honorario='.base64_encode($id_honorario).'&id_honorario_docente_pago='.base64_encode($PH_id).'" target="_blank"></br>Ver Comprobante</a></td>
					</tr>';
		}
	}
	else
	{ echo'<tr><td colspan="5">Sin Pagos Registrados</td></tr>';}
	
	$sqli->free();
	$conexion_mysqli->close();
}
else
{ echo'<tr><td colspan="5">Datos Incorrectos :(</td></tr>';}
?>
    </tbody>
  </table>
</div>
</body>
</html>