<?php 
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Impresion_Y_Gestion_de_contratos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//---///////////////////XAJAX///////////////--//
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("busca_pagos_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_CUOTAS");
$xajax->register(XAJAX_FUNCTION,"OCULTAR_CUOTAS");
$xajax->register(XAJAX_FUNCTION,"ELIMINAR");
$xajax->register(XAJAX_FUNCTION,"ELIMINAR_CONTRATO");
//--///////////////////////////////////////--//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Listador de Contratos</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<?php $xajax->printJavascript(); ?> 
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:23px;
	z-index:1;
	left: 5%;
	top: 85px;
}
.Estilo3 {font-size: 12px; font-weight: bold; }
#div_elimina {
	position:absolute;
	width:119px;
	height:115px;
	z-index:2;
	left: 929px;
	top: 84px;
}
#link {
	text-align: right;
	padding-right: 10px;
}
a:link {
	color: #006699;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #006699;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #006699;
}
-->
</style>
<script language="javascript">
function ACTUALIZAR()
{
	window.location.reload();
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Gesti&oacute;n de Contratos</h1>

<div id="link"><br />
<a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver al Menu</a></div>
<div id="apDiv1">
<table width="103%" border="0">
<thead>
<tr>
  <th width="2%" ><span class="Estilo3">N°</span></th>
  <th width="9%" ><span class="Estilo3">ID Contrato</span></th>
  <th width="7%" ><span class="Estilo3">Semestre</span></th>
  <th width="3%" ><span class="Estilo3">Año</span></th>
  <th width="7%" ><span class="Estilo3">Matricula</span></th>
   <th width="10%" ><span class="Estilo3">Pago Contado</span></th>
   <th width="9%" ><span class="Estilo3">Pago Cheque</span></th>
   <th width="13%" ><span class="Estilo3">Pago Linea Credito</span></th>
   <th width="7%" ><span class="Estilo3">N° Cuotas</span></td>
    <th width="12%" ><span class="Estilo3">Beca o Descuento</span></th>
     <th width="6%" ><span class="Estilo3">txt Beca</span></th>
     <th width="7%" ><span class="Estilo3">Vigencia</span></th>
     <th width="2%" ><strong>Condicion</strong></th>
       <th colspan="2" ><span class="Estilo3">Opcion</span></th>
</tr>
</thead>
<tbody>
<?php
if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
{
	include("../../../funciones/conexion.php");
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	
	$cons_C="SELECT alumno.rut, alumno.carrera, contratos2.* FROM alumno INNER JOIN contratos2 ON alumno.id = contratos2.id_alumno WHERE alumno.id='$id_alumno'";
	if(DEBUG){echo"$cons_C<br>";}
	$sql_C=mysql_query($cons_C)or die("contratos ".mysql_error());
	$num_contratos=mysql_num_rows($sql_C);
	if($num_contratos>0)
	{
		$aux=1;
		while($C=mysql_fetch_assoc($sql_C))
		{
			$id_contrato=$C["id"];
			$ano=$C["ano"];
			$semestre=$C["semestre"];
			$num_cuotas=$C["numero_cuotas"];
			$sede=$C["sede"];
			$arancel=$C["arancel"];
			$total=$C["total"];
			$contado_paga=$C["contado_paga"];
			$cheque_paga=$C["cheque_paga"];
			$linea_credito_paga=$C["linea_credito_paga"];
			$opcion_pag_matricula=$C["opcion_pag_matricula"];
			$matricula_a_pagar=$C["matricula_a_pagar"];
			
			$cantidad_beca=$C["cantidad_beca"];
			$porcentaje_beca=$C["porcentaje_beca"];
			$txt_beca=$C["txt_beca"];
			$vigencia=$C["vigencia"];
			$condicion=$C["condicion"];
			
			echo'<tr>
					<td><span class="Estilo1">'.$aux.'</span></td>
					<td><span class="Estilo1">'.$id_contrato.'</span></td>
					<td><span class="Estilo1">'.$semestre.'</span></td>
					<td><span class="Estilo1">'.$ano.'</span></td>
					<td><span class="Estilo1">'.number_format($matricula_a_pagar,0,",",".").' ('.$opcion_pag_matricula.')</span></td>
						<td><span class="Estilo1">'.number_format($contado_paga,0,",",".").'</span></td>
						<td><span class="Estilo1">'.number_format($cheque_paga,0,",",".").'</span></td>
						<td><span class="Estilo1">'.number_format($linea_credito_paga,0,",",".").'</span></td>
					<td><span class="Estilo1">'.$num_cuotas.'</span></td>
					<td><span class="Estilo1">'.number_format($cantidad_beca,0,",",".").' - '.$porcentaje_beca.'%</span></td>
					<td><span class="Estilo1">'.$txt_beca.'</span></td>
					<td><span class="Estilo1">'.$vigencia.'</span></td>
					<td><span class="Estilo1">'.$condicion.'</span></td>
					<td><span class="Estilo1"><a href="#" onclick="xajax_BUSCA_CUOTAS(\''.$semestre.'\', \''.$ano.'\', \''.$aux.'\', \''.$opcion_pag_matricula.'\',  \''.$id_contrato.'\');return false;">Ver Cuotas</a></span></td>
					
				    <td><a href="#" onclick="xajax_OCULTAR_CUOTAS(\''.$aux.'\')">Ocultar</a></td>
				    <td>&nbsp;</td>
</tr>
                 <tr>
                 <td colspan="15"><div class="Estilo1" id="div_pagos_'.$aux.'"></div></td>
                 </tr>
                 ';
			$aux++;	 
		}
		mysql_free_result($sql_C);
	}
	else
	{
		echo"<tr><td colspan=13>Sin Contratos Registrados a Este Alumno</td></tr>";
	}	
	mysql_close($conexion);
}
else
{
	echo"No hay Alumno Seleccionado<br>";
}
?>
</tbody>
<tfoot>
<tr >
  <td colspan="15">&nbsp;</td>
</tr>
</tfoot>
</table>
</div>
<div id="div_elimina"></div>
</body>
</html>