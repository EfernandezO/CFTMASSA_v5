<?php 
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Matriculas_generadas_X_rango_F_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>alumnos matriculados</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<style type="text/css" title="currentStyle">
			@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_page.css";
			@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_table_jui.css";
			@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/examples/examples_support/themes/smoothness/jquery-ui-1.8.4.custom.css";
			
		</style>
		<script type="text/javascript" language="javascript" src="../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				oTable = $('#example').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				});
			} );
		</script>
<style type="text/css">
<!--
.Estilo5 {font-size: 12px}
#apDiv2 {
	position:absolute;
	width:167px;
	height:49px;
	z-index:2;
	left: 780px;
	top: 144px;
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
#apDiv1 {
	position:absolute;
	width:96%;
	height:115px;
	z-index:1;
	left: 2%;
	top: 110px;
}
#link {
	text-align: right;
}
-->
</style>
</head>
<?php
	$fecha_ini=base64_decode($_GET["fecha_ini"]);
	$fecha_fin=base64_decode($_GET["fecha_fin"]);
	
	$sede=base64_decode($_GET["sede"]);
	$nivel=base64_decode($_GET["nivel"]);
	$year_ingreso=base64_decode($_GET["year_ingreso"]);
	$msj="Matriculas Generadas entre ($fecha_ini  y el $fecha_fin) en $sede<br /> Alumnos de Nivel: $nivel<br /> Ańo Ingreso: $year_ingreso";
?>
<body>
<h1 id="banner">Administrador - Alumnos Matriculados</h1>
<div id="link"><br />
<a href="#" class="button" onclick="javascript:window.close()">Cerrar</a><br /><br />
<a href="ver_alumnos_matriculados_xls.php?fecha_ini=<?php echo base64_encode($fecha_ini);?>&fecha_fin=<?php echo base64_encode($fecha_fin);?>&sede=<?php echo base64_encode($sede);?>&nivel=<?php echo base64_encode($nivel);?>&year_ingreso=<?php echo base64_encode($year_ingreso);?>" class="button">XLS</a>
</div>
<div id="apDiv1">
 <?php echo $msj;?>
  <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
    <tr>
     	<th>ID Contrato</th>
      		<th>ID Alumno</th>
            <th>Sexo</th>
			<th>Rut</th>
      		<th>Nombre</th>
			<th>Apellido P</th>
            <th>Apellido M</th>
      		<th>Carrera</th>
	 	 <th>Jornada</th>
      		<th>Ingreso</th>
      		<th>Nivel</th>
            <th>Total a pagar</th>
      		<th>Matricula a Pagar</th>
      		<th>Txt Beca u otro</th>
			<th>Cantidad Desc.</th>
			<th>% Desc.</th>
            <th>Vigencia</th>
            <th>Condici&oacute;n Contrato</th>
    </tr>
    </thead>
    <tbody>
<?php
	define("DEBUG", false);
if($_GET)
{
	if($nivel=="Todos")
	{$condicion_nivel="";}
	else
	{$condicion_nivel="alumno.nivel='$nivel' AND";}
	
	if($year_ingreso=="Todos")
	{$condicion_ingreso="";}
	else
	{$condicion_ingreso="AND alumno.ingreso='$year_ingreso'";}
	
	if($sede=="todas")
	{$condicion_sede="";}
	else
	{$condicion_sede="contratos2.sede='$sede' AND ";}
	
		$consC="SELECT contratos2.id, contratos2.id_alumno, contratos2.sede,  contratos2.matricula_a_pagar, contratos2.txt_beca, contratos2.cantidad_beca, contratos2.porcentaje_beca, contratos2.total, contratos2.vigencia, contratos2.condicion, alumno.nivel, alumno.rut, alumno.nombre, alumno.apellido_P, alumno.apellido_M, alumno.carrera, alumno.jornada, alumno.ingreso, alumno.sexo FROM contratos2 INNER JOIN alumno ON contratos2.id_alumno = alumno.id WHERE $condicion_nivel $condicion_sede contratos2.condicion IN('ok', 'inactivo', 'retiro') $condicion_ingreso AND contratos2.fecha_generacion BETWEEN '$fecha_ini' AND '$fecha_fin' ORDER by carrera, nivel, apellido_P, apellido_M";
	
	if(DEBUG){ echo"$consC<br>";}
	
	include("../../../funciones/conexion.php");
	$sql=mysql_query($consC)or die("1 ".mysql_error());
	$num_matriculados=mysql_num_rows($sql);
	if($num_matriculados>0)
	{
		while($A=mysql_fetch_array($sql))
		{
			$id_contrato=$A[0];
			$id_alumno=$A[1];
			$rut=$A["rut"];
			$nombre=$A["nombre"];
			$apellido_P=$A["apellido_P"];
			$apellido_M=$A["apellido_M"];
			$carrera=$A["carrera"];
			$jornada=$A["jornada"];
			$ingreso=$A["ingreso"];
			$sexo_alumno=$A["sexo"];
			
			$matricula_a_pagar=$A["matricula_a_pagar"];
			$txt_beca=$A["txt_beca"];
			$cantidad_desc=$A["cantidad_beca"];
			$porcentaje_dec=$A["porcentaje_beca"];
			$total_contrato=$A["total"];
			$vigencia_contrato=$A["vigencia"];
			$contrato_condicion=$A["condicion"];
			
			$nivel_alumno=$A["nivel"];
			
			$SUMA_X_SEXO[$sexo_alumno]+=1;
			
			echo'<tr>
      		<td>'.$id_contrato.'</td>
			<td>'.$id_alumno.'</td>
			<td>'.$sexo_alumno.'</td>
			<td>'.$rut.'</td>
      		<td>'.$nombre.'</td>
			<td>'.$apellido_P.'</td>
			<td>'.$apellido_M.'</td>
      		<td>'.$carrera.'</td>
			<td>'.$jornada.'</td>
      		<td>'.$ingreso.'</td>
			<td>'.$nivel_alumno.'</td>
			<td>'.$total_contrato.'</td>
			<td>'.$matricula_a_pagar.'</td>
			<td>'.$txt_beca.'</td>
			<td>'.$cantidad_desc.'</td>
			<td>'.$porcentaje_dec.'</td>
			<td>'.$vigencia_contrato.'</td>
			<td>'.$contrato_condicion.'</td>
    		</tr>';
		}	
	}
	else
	{
		echo' <tr>
      		<td>&nbsp;</td>
    		</tr>';
	}
	mysql_free_result($sql);
	mysql_close($conexion);
		
}
else
{
	echo"Sin Datos...";
}
?>
</tbody>
<tfoot>
<tr>
<td colspan="18"><span class="Estilo5">(<?php echo $num_matriculados;?>) Alumnos con Contrato</span></td>
</tr>
<tr>
<td colspan="18">[<?php echo $SUMA_X_SEXO["F"];?>] Mujeres</td>
</tr>
<tr>
<td colspan="18">[<?php echo $SUMA_X_SEXO["M"];?>] Hombres</td>
</tr>
<tr>
<td colspan="18">[<?php echo $SUMA_X_SEXO[""];?>] N/I</td>
</tr>
</tfoot>
  </table>
</div>
</body>
</html>