<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("ver_resumen_pagos_docente");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

if(isset($_GET["id_honorario"])){$id_honorario=base64_decode($_GET["id_honorario"]); $continuar=true;}
else{ $id_honorario=0; $continuar=false;}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Detalle Honorario</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:98%;
	height:115px;
	z-index:1;
	left: 1%;
	top: 100px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Detalle Honorario</h1>
<div id="apDiv1">
<table width="100%" align="center" border="1">
	<thead>
      <tr>
        <th colspan="16">Detallle Cuota</th>
        </tr>
   </thead> 
   <tbody>
   <tr>
   		<td>N.</td>
        <td>Sede</td>
        <td>Periodo</td>
        <td>Carrera</td>
        <td>Asignatura</td>
        <td>Jornada/<br />
        Grupo</td>
        <td>N.Cuota</td>
        <td>Total Base</td>
        <td>Cargo</td>
        <td>Glosa <br />
        Cargo</td>
        <td>Abono</td>
        <td>Glosa<br />
Abono</td>
        <td>Valor Hr.</td>
        <td>Total a<br />
Pagar</td>
        <td>Fecha <br />
        generacion</td>
        <td>id_usuario</td>
   </tr>
<?php if($continuar)
{
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funciones_sistema.php");
	
	$cons="SELECT * FROM honorario_docente_detalle WHERE id_honorario='$id_honorario'";
	$sqli=$conexion_mysqli->query($cons);
	$num_detalle=$sqli->num_rows;
	if($num_detalle>0)
	{
		$aux=0;
		while($HD=$sqli->fetch_assoc())
		{
			$aux++;
			$HD_semestre=$HD["semestre"];
			$HD_year=$HD["year"];
			$HD_sede=$HD["sede"];
			$HD_id_carrera=$HD["id_carrera"];
			$HD_cod_asignatura=$HD["cod_asignatura"];
			$HD_jornada=$HD["jornada"];
			$HD_grupo=$HD["grupo"];
			
			$HD_cuota=$HD["cuota"];
			$HD_total_base=$HD["total_base"];
			$HD_cargo=$HD["cargo"];
			$HD_abono=$HD["abono"];
			$HD_valor_hora=$HD["valor_hora"];
			$HD_glosa_cargo=$HD["glosa_cargo"];
			$HD_glosa_abono=$HD["glosa_abono"];
			$HD_total_a_pagar=$HD["total_a_pagar"];
			
			$HD_fecha_generacion=$HD["fecha_generacion"];
			$HD_cod_user=$HD["cod_user"];
			
			
			list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($HD_id_carrera, $HD_cod_asignatura);
			echo' <tr>
					<td>'.$aux.'</td>
					<td>'.$HD_sede.'</td>
					<td>['.$HD_semestre.'/'.$HD_year.']</td>
					<td>'.NOMBRE_CARRERA($HD_id_carrera).'</td>
					<td>'.$HD_cod_asignatura.'_'.$nombre_asignatura.'</td>
					<td>'.$HD_jornada.'/'.$HD_grupo.'</td>
					<td>'.$HD_cuota.'</td>
					<td>'.$HD_total_base.'</td>
					<td>'.$HD_cargo.'</td>
					<td>'.$HD_glosa_cargo.'</td>
					<td>'.$HD_abono.'</td>
					<td>'.$HD_glosa_abono.'</td>
					<td>'.$HD_valor_hora.'</td>
					<td>'.$HD_total_a_pagar.'</td>
					<td>'.$HD_fecha_generacion.'</td>
					<td>'.$HD_cod_user.'</td>
			   </tr>';
		}
	}
	else
	{}
	$sqli->free();
	$conexion_mysqli->close();
}
else{ echo"Sin Datos...<br>";}?>
</tbody>
</table>

<table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="5">Informacion de Pago de Honorario </th>
    </tr>
    <tr>
    	<td>N.</td>
        <td>Sede</td>
        <td>Fecha Pago</td>
        <td>Forma de Pago</td>
     
        <td>Valor</td>
    </tr>
    </thead>
    <tbody>

<?php
if($continuar)
{
	require("../../../../funciones/conexion_v2.php");
	
	$cons="SELECT * FROM honorario_docente_pagos WHERE id_honorario='$id_honorario'";
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_registros=$sqli->num_rows;
	$ruta="../../../CONTENEDOR_GLOBAL/boleta_honorario_docente/";
	
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
					
					<td>$'.number_format($PH_valor,0,",",".").'</td>
					</tr>';
		}
	}
	else
	{ echo'<tr><td colspan="5">Sin Pagos Registrados</td></tr>';}
	
	$sqli->free();
	mysql_close($conexion);
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