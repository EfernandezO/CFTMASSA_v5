<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("revision_mensual_honorario_Docente");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	//////////////////////XAJAX/////////////////
@require_once ("../../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("proceso_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"APRUEBA_CONTABILIDAD");
$xajax->register(XAJAX_FUNCTION,"DESAPRUEBA_CONTABILIDAD");
//---------------------------------------------------------///	
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php");?>
<title>Revision Honorario Docente</title>
<?php $xajax->printJavascript(); ?> 
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css"/>

<!--INICIO LIGHTBOX EVOLUTION-->
  <script type="text/javascript" src="../../../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
 <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox(); 
    });
  </script>
 <!--FIN LIGHTBOX EVOLUTION--> 
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 131px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Revisi&oacute;n Honorario Docente</h1>
<div id="link"><br />
<a href="../index.php" class="button">Volver al Menu</a></div>
<div id="apDiv1">
<?php
if($_GET)
{
	require("../../../../../funciones/conexion_v2.php");
		
	$sede=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["sede"]));
	$year=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["year_generacion"]));
	$mes=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["mes"]));
?>

  <table width="85%" border="1" align="center">
    <thead>
      <tr>
        <th colspan="12">Honorario Docentes <?php echo $sede;?> Periodo [<?php echo"$mes - $year";?>]</th>
        </tr>
      </thead>
    <tbody>
      <tr>
        <td rowspan="2">N</td>
        <td rowspan="2">Rut</td>
        <td rowspan="2">Nombre</td>
        <td rowspan="2">Apellido</td>
        <td rowspan="2">estado</td>
        <td rowspan="2">cuotas totales<br />
          (segun asignacion)</td>
         <td rowspan="2">Total</td>
        <td colspan="5">Contabilidad</td>
        </tr>
      <tr>
        <td>Estado</td>
        <td>Usuario</td>
        <td>Fecha</td>
        <td>ver</td>
        <td>Boleta</td>
      </tr>
      <?php	
	$cons_H="SELECT honorario_docente.* FROM honorario_docente INNER JOIN personal ON honorario_docente.id_funcionario=personal.id WHERE honorario_docente.sede='$sede' AND honorario_docente.year_generacion='$year' AND honorario_docente.mes_generacion='$mes' AND honorario_docente.total >'0' ORDER by personal.apellido_P, personal.apellido_M";
	$sqli_H=$conexion_mysqli->query($cons_H)or die($conexion_mysqli->error);
	$num_honorarios=$sqli_H->num_rows;
	$SUMA_TOTAL_HONORARIOS=0;
	if($num_honorarios>0)
	{
		$aux=0;
		
		//--------------------------------------------//
		include("../../../../../funciones/VX.php");
		$evento="Revisa para Aprobacion de contabilidad, Honorario Docente General $sede [$mes - $year]";
		@REGISTRA_EVENTO($evento);
		//----------------------------------------------//
		while($H=$sqli_H->fetch_assoc())
		{
			$aux++;
			$H_id=$H["id_honorario"];
			$H_sede=$H["sede"];
			$H_mes=$H["mes_generacion"];
			$H_semestre=$H["semestre"];
			$H_year=$H["year"];
			$H_id_funcionario=$H["id_funcionario"];
			$H_total=$H["total"];
			$H_estado=$H["estado"];
			$H_generado_contabilidad=$H["generado_contabilidad"];
			$H_id_user_generado_contabilidad=$H["id_user_generado_contabilidad"];
			$H_fecha_generado_contabilidad=$H["fecha_generado_contabilidad"];
			$H_fecha_generacion=$H["fecha_generacion"];
			$H_cod_user=$H["cod_user"];
			
			///busco si esta cancela su boleta de honorarios
			
			$boton_boleta='';
			if($H_estado=="cancelado")
			{
				$cons_P="SELECT id, archivo FROM honorario_docente_pagos WHERE id_honorario='$H_id' AND id_funcionario='$H_id_funcionario' LIMIT 1";
				$sqli_P=$conexion_mysqli->query($cons_P)or die("Pago Honorario".$conexion_mysqli->error);
				$num_pagos_honorario=$sqli_P->num_rows;
				if(DEBUG){ echo"-->$cons_P<br> num_pagos: $num_pagos_honorario<br>";}
				if($num_honorarios>0)
				{
					$PH=$sqli_P->fetch_assoc();
					$PH_archivo=$PH["archivo"];
					$PH_id=$PH["id"];
					if(DEBUG){ echo"id_pago_honorario: $PH_id archivo_pago(boleta): $PH_archivo<br>";}
				}
				$sqli_P->free();
				if((empty($PH_archivo))or($PH_archivo=="NULL"))
				{
					if(DEBUG){ echo"Archivo NULL o Vacio<br>";}
					$boton_boleta='<a href="carga_boleta/carga_boleta_1.php?H_id='.base64_encode($H_id).'&PH_id='.base64_encode($PH_id).' &lightbox[iframe]=true&lightbox[width]=550&lightbox[height]=400" class="lightbox"">Cargar</a>';
				}
				else
				{
					if(DEBUG){ echo"Archivo con datos<br>";}
					$ruta_boleta="../../../../CONTENEDOR_GLOBAL/boleta_honorario_docente/".$PH_archivo;
					$boton_boleta='<a href="'.$ruta_boleta.'?lightbox[iframe]=true&lightbox[width]=550&lightbox[height]=400" class="lightbox"">Mostrar</a>';
				}
			}
			else
			{
				if(DEBUG){ echo"Pago aun pendiente<br>";}
			}
			
			
			//Datos funcionarios
			$cons_DF="SELECT * FROM personal WHERE id='$H_id_funcionario' LIMIT 1";
			$sqli_DF=$conexion_mysqli->query($cons_DF)or die($conexion_mysqli->error);
				$DF=$sqli_DF->fetch_assoc();
				$F_rut=$DF["rut"];
				$F_nombre=$DF["nombre"];
				$F_apellido=$DF["apellido_P"]." ".$DF["apellido_M"];
			$sqli_DF->free();
			//--------------------------------------------------------------------//	
			
			$cons_HD="SELECT numero_cuotas FROM toma_ramo_docente WHERE id_funcionario='$H_id_funcionario' AND year='$H_year' AND semestre='$H_semestre' AND sede='$H_sede'";
			$sqli_HD=$conexion_mysqli->query($cons_HD);
			$msj_informacion="";
			while($HD=$sqli_HD->fetch_assoc())
			{$aux_cuota=$HD["numero_cuotas"];}
			$msj_informacion=$aux_cuota." ";
			$sqli_HD->free();
			//---------------------------------------------------------------------------///
			$SUMA_TOTAL_HONORARIOS+=$H_total;
			/////////////////////////////////////////////////////////
			
				if($H_generado_contabilidad=="ok")
				{ 
					if($H_estado=="pendiente")
					{
						$funcion_boton='onclick="xajax_DESAPRUEBA_CONTABILIDAD('.$aux.', '.$H_id.'); return false;"';
						$msj_boton='title="click para indicar que NO esta disponible para pago"';
					}
					else
					{
						$funcion_boton='';
						$msj_boton='';
					}
					$boton_generado_contabilidad='<a href="#" class="button" '.$funcion_boton.' '.$msj_boton.'>ok</a>';
				}
				else
				{
					if($H_estado=="pendiente")
					{
						$funcion_boton='onclick="xajax_APRUEBA_CONTABILIDAD('.$aux.', '.$H_id.'); return false;"';
						$msj_boton='title="click para indicar que esta disponible para pago"';
					}
					else
					{
						$funcion_boton='';
						$msj_boton='';
					}
					$boton_generado_contabilidad='<a href="#" class="button_R" '.$funcion_boton.' '.$msj_boton.'>Aprobar</a>';
				}
			///////////////////////////////////////////////////////////
			echo'<tr height="35">
					<td>'.$aux.'</td>
					<td>'.$F_rut.'</td>
					<td>'.$F_nombre.'</td>
					<td>'.$F_apellido.'</td>
					<td>'.$H_estado.'</td>
					<td align="center">'.$msj_informacion.'</td>
					<td align="right">'.number_format($H_total,0,",",".").'</td>
					<td align="center"><div id="div_estado_'.$aux.'">'.$boton_generado_contabilidad.'</div></td>
					<td><div id="div_user_'.$aux.'">'.$H_id_user_generado_contabilidad.'</div></td>
					<td><div id="div_fecha_'.$aux.'">'.$H_fecha_generado_contabilidad.'</div></td>
					
					<td><a href="revisa_detalle_honorario_IND.php?H_id='.base64_encode($H_id).'&lightbox[iframe]=true&lightbox[width]=550&lightbox[height]=400" class="lightbox">Ver</a></td>
					<td>'.$boton_boleta.'</td>
				</tr>';
		}
		echo'<tr>
			<td colspan="6"><strong>TOTAL</strong></td>
			<td align="right"><strong>'.number_format($SUMA_TOTAL_HONORARIOS,0,",",".").'</strong></td>
			<td colspan="5">&nbsp;</td>
			</tr>';
	}
	else
	{}
	$sqli_H->free();
		
	$conexion_mysqli->close();
	mysql_close($conexion);
}
?>
      </tbody>
  </table>
</div>
</body>
</html>