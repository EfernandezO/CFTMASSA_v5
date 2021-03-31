<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("ver_resumen_pagos_docente");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_POST)
{
	require("../../../../funciones/conexion_v2.php");
	$sede=mysqli_real_escape_string($conexion_mysqli, $_POST["sede"]);
	$semestre=mysqli_real_escape_string($conexion_mysqli, $_POST["semestre"]);
	$year=mysqli_real_escape_string($conexion_mysqli, $_POST["year"]);	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>resumen pago asignaciones</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:60px;
	z-index:1;
	left: 5%;
	top: 127px;
}
</style>
<!--INICIO LIGHTBOX EVOLUTION-->
  <script type="text/javascript" src="../../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
 <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox(); 
    });
  </script>
 <!--FIN LIGHTBOX EVOLUTION--> 
</head>

<body>
<h1 id="banner">Administrador - Estado de Asignaciones</h1>
<div id="link"><br />
<a href="../../lista_funcionarios.php" class="button">Volver al Menu</a><br />
<br />
<a href="resumen_pagos_asignaciones_xls.php?sede=<?php echo base64_encode($sede);?>&semestre=<?php echo base64_encode($semestre);?>&year=<?php echo base64_encode($year);?>" class="button_R">.XLS</a></div>
<div id="apDiv1">
<table width="100%" align="center">
<thead>
	<tr>
		<th colspan="15"> Estado pagos docente <?php echo $sede?> Periodo <?php echo '['.$semestre.' - '.$year.']';?></th>
	</tr>
	<tr>
    	<th>N.</th>
        <th>id funcionario</th>
        <th>Funcionario</th>
       
<?php
	if(DEBUG){ var_dump($_POST);}

	require("../../../../funciones/funciones_sistema.php");
	$ARRAY_HONORARIO=array();
if($_POST)	
{
	
	$cons="SELECT DISTINCT(id_funcionario) FROM toma_ramo_docente INNER JOIN personal ON toma_ramo_docente.id_funcionario=personal.id WHERE toma_ramo_docente.sede='$sede' AND toma_ramo_docente.semestre='$semestre' AND toma_ramo_docente.year='$year' ORDER by personal.apellido_P, personal.apellido_M, personal.nombre";
	$sqli=$conexion_mysqli->query($cons);
	$num_registros=$sqli->num_rows;
	
	$aux=0;
	$mes_menor=9999;
	$mes_mayor=0;
	$year_menor=9999;
	$max_num_registros=0;
	
	
	if($num_registros>0)
	{
		while($F=$sqli->fetch_row())
		{
			
			$id_funcionario=$F[0];
			$primera_vuelta=true;
			$cons_H="SELECT * FROM honorario_docente WHERE id_funcionario='$id_funcionario' AND sede='$sede' AND semestre='$semestre' AND year='$year' ORDER by year_generacion, mes_generacion";
			if(DEBUG){ echo"-->$cons_H<br>";}
			$sqli_H=$conexion_mysqli->query($cons_H)or die($conexion_mysqli->error);
			$num_honorarios=$sqli_H->num_rows;
			if($num_honorarios>0)
			{
				if($num_honorarios>$max_num_registros){$max_num_registros=$num_honorarios;}
				while($H=$sqli_H->fetch_assoc())
				{
					
					$id_honorario=$H["id_honorario"];
					$H_mes=$H["mes_generacion"];
					$H_valor=$H["total"];
					$H_estado=$H["estado"];
					$H_fecha_estado=$H["fecha_estado"];
					$H_year_generacion=$H["year_generacion"];
					if(DEBUG){ echo"$H_year_generacion -  $H_mes<br>";}
					//busco pagos previo al honorario
					if(DEBUG){echo"Busco Pagos previos a Cuota Honorario:<br>";}
					$consPP="SELECT SUM(valor) FROM honorario_docente_pagos WHERE id_honorario='$id_honorario'";
					if(DEBUG){echo"-->$consPP<br>";}
					$sqliPP=$conexion_mysqli->query($consPP)or die($conexion_mysqli->error);
					$PP=$sqliPP->fetch_row();
					$pagosPrevios=$PP[0];
					if(empty($pagosPrevios)){$pagosPrevios=0;}
					$sqliPP->free();
					if(DEBUG){echo"Pagos previos realizados sumado: $pagosPrevios<br>";}
					
					if($primera_vuelta){ 
					
							if($H_year_generacion<$year_menor){$year_menor=$H_year_generacion;}
							if($H_mes<$mes_menor){$mes_menor=$H_mes;}
							if($H_mes>$mes_mayor){$mes_mayor=$H_mes;}
							$primera_vuelta=false;
						}
					
					

					$ARRAY_HONORARIO[$id_funcionario][$H_year_generacion][$H_mes]["estado"]=$H_estado;
					$ARRAY_HONORARIO[$id_funcionario][$H_year_generacion][$H_mes]["valor"]=$H_valor;
					$ARRAY_HONORARIO[$id_funcionario][$H_year_generacion][$H_mes]["pagosPrevios"]=$pagosPrevios;
					$ARRAY_HONORARIO[$id_funcionario][$H_year_generacion][$H_mes]["id_honorario"]=$id_honorario;

					
				}
			}

		}
	}
	else
	{}
	$sqli->free();
}

if(DEBUG){echo"Mes menor: $mes_menor year menor: $year_menor num max registros: $max_num_registros<br>";}

$year_ini=$year_menor;	
$mes_ini=$mes_menor;

$mesx=$mes_ini;
$year_inix=$year_ini;
for($j=0;$j<$max_num_registros;$j++)
{	
		
		echo'<th>'.$mesx.'-'.$year_inix.'</th>';	
	
	$mesx++;
	if($mesx>12){$mesx=1; $year_inix++;}
}
echo'</tr>
	</thead>
	<tbody>';



$ARRAY_TOTAL=array();
	
foreach($ARRAY_HONORARIO as $aux_id_funcionario => $array_1)
{
	
	
	$y=$year_menor;
	$aux++;
	echo'<tr>
			<td align="center">'.$aux.'</td>
			<td align="center">'.$aux_id_funcionario.'</td>
			<td align="center">'.NOMBRE_PERSONAL($aux_id_funcionario).'</td>';
		
	if(DEBUG){ echo"<strong>--->id_funcionario: $aux_id_funcionario</strong><br>";}
	
	$mes=$mes_ini;
	for($i=0;$i<$max_num_registros;$i++)
	{
		
		if($mes>12){ $mes=1; $y++;}
		
		
		if(DEBUG){ echo"consultando: $mes - $y<br>";}
		if(isset($array_1[$y][$mes]))
		{
			$aux_estado=$array_1[$y][$mes]["estado"]; 
			$aux_id_honorario=$array_1[$y][$mes]["id_honorario"]; 
			$aux_valor=number_format($array_1[$y][$mes]["valor"],0,"",""); 
			$aux_pagosPrevios=number_format($array_1[$y][$mes]["pagosPrevios"],0,"",""); 
			
			//reemplazo de valor x pagosprevios
				if($aux_estado!=="pendiente"){$valorAguardar=$aux_pagosPrevios;}
				else{$valorAguardar=$aux_valor;}
			
				if(isset($ARRAY_TOTAL[$y][$mes][$aux_estado]))
				{$ARRAY_TOTAL[$y][$mes][$aux_estado]+=$valorAguardar;}
				else{$ARRAY_TOTAL[$y][$mes][$aux_estado]=$valorAguardar;}
			
			if($aux_estado=="cancelado"){ $color='#AAFFAA';}
			elseif($aux_estado=="abonado"){$color=" #e9efa4 ";}
			else{  $color='#FFAAAA';}
			
		}
		else
		{ $aux_estado=""; $aux_valor=""; $color="";}
		
		echo'<td align="center" bgcolor="'.$color.'"><a href="detalle_honorario.php?id_honorario='.base64_encode($aux_id_honorario).'&lightbox[iframe]=true&lightbox[width]=950&lightbox[height]=350" class="lightbox" title="Estado('.$aux_estado.') -> click para ver detalle">'.@number_format($aux_valor,0,",",".").'</a></td>';
		
		$mes++;
	}
	echo'</tr>';
}

$conexion_mysqli->close();

echo'<tr>
		<td colspan="3"><strong>Total Cancelado</strong></td>';
foreach($ARRAY_TOTAL as $n => $array_2)
{
	foreach($array_2 as $n => $array_3)
	{
		if(isset($array_3["cancelado"]))
		{$valor=$array_3["cancelado"];}
		else{$valor=0;}
		echo'<td align="center"><strong>'.number_format($valor,0,",",".").'</strong></td>';
		
	}
}
echo'</tr>';
echo'<tr>
		<td colspan="3"><strong>Total Abonado</strong></td>';
foreach($ARRAY_TOTAL as $n => $array_2)
{
	foreach($array_2 as $n => $array_3)
	{
		if(isset($array_3["abonado"]))
		{$valor=$array_3["abonado"];}
		else{$valor=0;}
		echo'<td align="center"><strong>'.number_format($valor,0,",",".").'</strong></td>';
		
	}
}
echo'</tr>';
echo'<tr>
		<td colspan="3"><strong>Total Pendiente</strong></td>';
foreach($ARRAY_TOTAL as $n => $array_2)
{
	foreach($array_2 as $n => $array_3)
	{
		if(isset($array_3["pendiente"]))
		{$valor=$array_3["pendiente"];}
		else{$valor=0;}
		echo'<td align="center"><strong>'.number_format($valor,0,",",".").'</strong></td>';
		
	}
}
echo'</tr>';

?>
</table>
</div>
</body>
</html>