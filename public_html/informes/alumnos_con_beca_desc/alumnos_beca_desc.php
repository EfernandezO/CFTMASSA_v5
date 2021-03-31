<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define(DEBUG, false);?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../../CSS/tabla.css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<title>Alumnos beca desc</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 96px;
}
.Estilo1 {font-size: 12px}
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
</head>
<?php
$sede=$_POST["fsede"];
$carrera=$_POST["carrera"];
$year=$_POST["year"];
$semestre=$_POST["semestre"];
$nivel=$_POST["nivel"];
$ver_boleta=$_POST["ver_boleta"];

?>
<body>
<h1 id="banner">Administrador - Alumnos Con Beca y Desc.</h1>

<div id="link"><br />
<a href="index.php" class="button">Volver a Seleccion</a></div>
<div id="apDiv1">
  <table width="85%" border="1" align="center">
  <thead>
    <tr>
      <td><span class="Estilo1">Carrera: </span></td>
      <td colspan="11"><span class="Estilo1"><?php echo $carrera;?> - <?php echo"$semestre Semestre $year. Nivel: $nivel";?></span></td>
      <td><span class="Estilo1">Sede</span></td>
      <td><span class="Estilo1"><?php echo $sede;?></span></td>
    </tr>
    <tr>
      <td ><span class="Estilo1">ID</span></td>
      <td><span class="Estilo1">Rut</span></td>
      <td><span class="Estilo1">Nombre </span></td>
      <td ><span class="Estilo1">Apellido</span></td>
      <td ><span class="Estilo1">Carrera</span></td>
      <td ><span class="Estilo1">Ingreso</span></td>
      <td ><span class="Estilo1">Nivel</span></td>
      <td ><span class="Estilo1">Jornada</span></td>
       <td ><span class="Estilo1">VALOR</span></td>
        <td ><span class="Estilo1">DEUDA</span></td>
      <td ><span class="Estilo1">Vigencia</span></td>
      <td><span class="Estilo1">% beca o desc.</span></td>
      <td><span class="Estilo1">Cantidad Beca o Desc</span></td>
      <td><span class="Estilo1">comentario</span></td>
    </tr>
    </thead>
    <tbody>
<?php
if($_POST)
{	
	set_time_limit(160);
	
	if($ver_boleta=="si")
	{
		$ver_boletas=true;
	}
	else
	{
		$ver_boletas=false;
	}
	
include("../../../funciones/conexion.php");
include("../../../funciones/funcion.php");
	//carrera
	if($carrera=="todas")
	{
		$condicion_carrera="";
	}
	else
	{
	 	$condicion_carrera="AND carrera='$carrera'";
	}
	//semestre
	if($semestre!="ambos")
	{
		$condicion_semestre="AND semestre='$semestre'";
	}
	else
	{ $condicion_semestre="";}
	//nivel
	if($nivel!="todos")
	{
		$condicion_nivel="AND nivel='$nivel'";
	}
	else{ $condicion_nivel="";}
	
	$cons="SELECT * FROM alumno  WHERE sede='$sede' $condicion_carrera $condicion_nivel  ORDER by carrera, nivel, apellido_P";
	
	if(DEBUG){echo"$cons<br>";}
	$sql=mysql_query($cons)or die("MAIN ALUMNOS: ".mysql_error());
	$num_reg=mysql_num_rows($sql);
	if($num_reg>0)
	{
	while($AD=mysql_fetch_assoc($sql))
		{
			$id_alumno=$AD["id"];
			$rut=$AD["rut"];
			$nombre=ucwords(strtolower($AD["nombre"]));
			$apellido_old=$AD["apellido"];
			$apellido_new=$AD["apellido_P"]." ".$AD["apellido_M"];;
			$carrera_alumno=$AD["carrera"];
			$year_ingreso=$AD["ingreso"];
			$nivel_academico=$AD["nivel"];
			$jornada=$AD["jornada"];
			
			if($apellido_new==" ")
			{ $apellido_label=$apellido_old;}
			else
			{ $apellido_label=$apellido_new;}
			
			$apellido_label=ucwords(strtolower($apellido_label));
		
		
		///consulta contrato
		$cons_CON="SELECT * FROM contratos2 WHERE id_alumno='$id_alumno' AND ano='$year' $condicion_semestre order by id  desc LIMIT 1";
		if(DEBUG){ echo"<b>$id_alumno</b>--->$cons_CON<br>";}
			$sql_CON=mysql_query($cons_CON)or die(mysql_error());
			$num_contratos=mysql_num_rows($sql_CON);
			$DCC=mysql_fetch_assoc($sql_CON);
				$porcentaje_beca=$DCC["porcentaje_beca"];
				$cantidad_beca=$DCC["cantidad_beca"];
			if(($num_contratos>0)and(($cantidad_beca>0)or($porcentaje_beca>0)))
			{ 
				$mostrar=true;
				$id_contrato=$DCC["id"];
				$porcentaje_beca=$DCC["porcentaje_beca"];
				$cantidad_beca=$DCC["cantidad_beca"];
				$txt_beca=$DCC["txt_beca"];
				$vigencia_contrato=$DCC["vigencia"];
					if(DEBUG){ echo" CONTRATO ENCONTRADO...($num_contratos)<br><br>";}
			}
			else
			{ 
				$mostrar=false;
				if(DEBUG){ echo"SIN CONTRATO...<br><br>";}
			}
			
			
			
			if($mostrar)
				{
					$aux++;
					unset($array_boleta);
						
						
						//adeudado y pagado
						
						$cons_CUO="SELECT * FROM letras WHERE idalumn='$id_alumno' AND ano='$year' ORDER by id";
						if(DEBUG){ echo"<br>CUO--> $cons_CUO<br>";}
						$sql_CUO=mysql_query($cons_CUO)or die("CUOTAS: ".mysql_error());
						$num_cuotas=mysql_num_rows($sql_CUO);
						
						$SUMA_VALOR=0;
						$SUMA_DEUDA=0;
						if($num_cuotas>0)
						{
							if(DEBUG){ echo"<b>HAY CUOTAS</b><br>";}
							while($DC=mysql_fetch_assoc($sql_CUO))
							{
								$id_cuota=$DC["id"];
								$aux_valor=$DC["valor"];
								$aux_deuda=$DC["deudaXletra"];
								$aux_condicion=$DC["pagada"];
								
								$SUMA_DEUDA+=$aux_deuda;
								$SUMA_VALOR+=$aux_valor;
								
								if(DEBUG){ echo"VALOR: $aux_valor DEUDA: $aux_deuda<br>";}
								//busco pagos
								
								$cons_P="SELECT * FROM pagos WHERE id_alumno='$id_alumno' AND id_cuota='$id_cuota' ORDER by idpago";
								if(DEBUG){ echo"PAGOS--> $cons_P<br>";}
								$sql_P=mysql_query($cons_P)or die("PAGOS: ".mysql_error());
								$num_pagos=mysql_num_rows($sql_P);
								if($num_pagos>0)
								{
									while($P=mysql_fetch_assoc($sql_P))
									{
										$id_pago=$P["idpago"];
										$id_boleta=$P["id_boleta"];
										
										
										//busco folio boleta
										
										$cons_BO="SELECT * FROM boleta WHERE id='$id_boleta' LIMIT 1";
										if(DEBUG){ echo"BOLETA--> $cons_BO<br>";}
										$sql_BO=mysql_query($cons_BO)or die("BOLETAS: ".mysql_error());
										$B=mysql_fetch_assoc($sql_BO);
										
											$aux_folio=$B["folio"];
											$aux_array_folio=$array_boleta["folio"];
											
											if(!@in_array($aux_folio,$aux_array_folio))
											{
												$array_boleta["folio"][]=$aux_folio;
												$array_boleta["valor"][]=$B["valor"];
												$array_boleta["fecha"][]=$B["fecha"];
											}
										
										mysql_free_result($sql_BO);	
										
									}
								}
								else
								{}
								mysql_free_result($sql_P);
							}
						}
						else
						{
							if(DEBUG){ echo"<b>NO HAY CUOTAS</b><br>";}
						}
						
						mysql_free_result($sql_CUO);
						
						if(DEBUG){ echo"<br>//-----------------------------//<br>";}
						if(DEBUG){ var_export($array_boleta);}
						if(DEBUG){ echo"<br>//-----------------------------//<br>";}
						echo'<tr>
						  <td class="Estilo1">'.$id_alumno.'</td>
						  <td class="Estilo1">'.$rut.'</td>
						  <td class="Estilo1">'.$nombre.'</td>
						  <td class="Estilo1">'.$apellido_label.'</td>
						  <td class="Estilo1">'.$carrera_alumno.'</td>
						  <td class="Estilo1">'.$year_ingreso.'</td>
						  <td class="Estilo1">'.$nivel_academico.'</td>
						  <td class="Estilo1">'.$jornada.'</td>
						  <td class="Estilo1">$'.$SUMA_VALOR.'</td>
						  <td class="Estilo1">$'.$SUMA_DEUDA.'</td>
						  <td class="Estilo1">'.$vigencia_contrato.'</td>
						  <td class="Estilo1">'.$porcentaje_beca.'%</td>
						  <td class="Estilo1">'.number_format($cantidad_beca,0,",",".").'</td>
						  <td class="Estilo1">'.$txt_beca.'</td>
						</tr>';
						
						if($ver_boletas)
						{
							$aux_valores_b=0;
							if(!empty($array_boleta))
							{	
								echo'<tr>
										<td colspan="10">&nbsp;</td>
										<td><strong>N.</strong></td>
										<td><strong>Folio</strong></td>
										<td><strong>Fecha</strong></td>
										<td><strong>Valor</strong></td>
										</tr>';
								foreach($array_boleta["folio"] as $nf =>$foliof)
								{
									echo'<tr>
										<td colspan="10">&nbsp;</td>
										<td>'.($nf+1).'</td>
										<td>'.$foliof.'</td>
										<td>'.fecha_format($array_boleta["fecha"][$nf]).'</td>
										<td>$'.$array_boleta["valor"][$nf].'</td>
										</tr>';
										$aux_valores_b+=$array_boleta["valor"][$nf];
								}
								echo'<tr>
										<td colspan="10">&nbsp;</td>
										<td><strong>Total</strong></td>
										<td colspan="3" align="right"><strong>$'.$aux_valores_b.'</strong></td>
										</tr>';
							}
						}
					
				}
			}
	}
	}
	else
	{
		if(DEBUG){ echo"SIN ALUMNOS...";}
	 }	
?>
</tbody>
<tfoot>
<tr>
<td colspan="14"><span class="Estilo1">(<?php echo $aux;?>)Alumnos Encontrados</span></td>
</tr>
</tfoot>
 </table> 

 <br />
<a href="export_excel.php?year=<?php echo base64_encode($year);?>&carrera=<?php echo base64_encode($carrera);?>&sede=<?php echo base64_encode($sede);?>&semestre=<?php echo base64_encode($semestre);?>" title="Exportar"><img src="../../BAses/Images/excel_icon.png" width="31" height="31" /></a></div>
</body>
</html>
