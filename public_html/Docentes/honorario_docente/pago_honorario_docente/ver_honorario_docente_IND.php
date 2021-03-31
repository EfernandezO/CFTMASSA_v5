<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("pago_honorario_docente_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
	if(isset($_GET["id_funcionario"]))
	{
		$id_funcionario=$_GET["id_funcionario"];
		if(is_numeric($id_funcionario))
		{ $continuar=true;}
		else
		{$continuar=false;}
	}
	else
	{$continuar=false;}
}
else{$continuar=false;}

if($continuar)
{
	require("../../../../funciones/conexion_v2.php");
		$cons_F="SELECT * FROM personal WHERE id='$id_funcionario' LIMIT 1";
	//datos funcionario
		$sqli_F=$conexion_mysqli->query($cons_F)or die($conexion_mysqli->error);
		$DF=$sqli_F->fetch_assoc();
		$F_nombre=$DF["nombre"];
		$F_apellido=$DF["apellido_P"]." ".$DF["apellido_M"];
		$F_rut=$DF["rut"];
		$sqli_F->free();
	//busco honorarios
		$ARRAY_HONORARIO=array();
		$cons_H="SELECT * FROM honorario_docente WHERE id_funcionario='$id_funcionario' AND generado_contabilidad='ok' ORDER by year_generacion DESC, mes_generacion DESC";
		$sqli_H=$conexion_mysqli->query($cons_H)or die($conexion_mysqli->error);
		$num_honorarios_pendientes=$sqli_H->num_rows;
		
		//--------------------------------------------//
		include("../../../../funciones/VX.php");
		$evento="Revisa Honorarios Docente Individual(pagos)  Rut: $F_rut";
		@REGISTRA_EVENTO($evento);
		//----------------------------------------------//
		if($num_honorarios_pendientes>0)
		{
			$aux=0;
			while($H=$sqli_H->fetch_assoc())
			{
				$id_honorario=$H["id_honorario"];
				$H_mes=$H["mes_generacion"];
				$H_valor=$H["total"];
				$H_sede=$H["sede"];
				$H_estado=$H["estado"];
				$H_fecha_estado=$H["fecha_estado"];
				$H_year_generacion=$H["year_generacion"];
				
				
				$ARRAY_HONORARIO["id"][]=$id_honorario;
				$ARRAY_HONORARIO["total"][]=$H_valor;
				$ARRAY_HONORARIO["sede"][]=$H_sede;
				$ARRAY_HONORARIO["mes"][]=$H_mes;
				$ARRAY_HONORARIO["estado"][]=$H_estado;
				$ARRAY_HONORARIO["year_generacion"][]=$H_year_generacion;
				$ARRAY_HONORARIO["fecha_estado"][]=$H_fecha_estado;
				$aux++;
			}
		}
		else
		{}
		$sqli_H->free();
		
		
	$conexion_mysqli->close();
}
else
{header("location: ../lista_funcionarios.php");}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Pago Honorario | Funcionario</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:40%;
	height:115px;
	z-index:1;
	left: 10%;
	top: 101px;
}
#apDiv2 {
	position:absolute;
	width:60%;
	height:70px;
	z-index:2;
	left: 10%;
	top: 247px;
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
<h1 id="banner">Administrador - Honorario Docente</h1>
<div id="link"><br><a href="../../lista_funcionarios.php" class="button">Volver al Menu </a></div>
<div id="apDiv1">
  <table width="100%" border="1" align="left">
  <thead>
    <tr>
      <th colspan="2">Datos Funcionario</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="20%">Id</td>
      <td width="80%"><?php echo $id_funcionario?></td>
    </tr>
    <tr>
      <td width="20%">Rut</td>
      <td width="80%"><?php echo $F_rut;?></td>
    </tr>
    <tr>
      <td>Nombre</td>
      <td><?php echo $F_nombre;?></td>
    </tr>
    <tr>
      <td>Apellido</td>
      <td><?php echo $F_apellido;?></td>
    </tr>
    </tbody>
  </table>
</div>
<div id="apDiv2">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="11">Honorarios</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>N</td>
      <td>IdHonorario</td>
      <td>Sede</td>
      <td>A&ntilde;o</td>
      <td>Mes</td>
      <td>Estado</td>
      <td>Fecha Pago</td>
      <td>Valor</td>
      <td>Adeuda</td>
      <td colspan="2">opciones</td>
    </tr>
    <?php
    if(count($ARRAY_HONORARIO))
	{
		//var_dump($ARRAY_HONORARIO);
		$contador=0;
		require("../../../../funciones/conexion_v2.php");
		foreach($ARRAY_HONORARIO["id"] as $indice => $aux_id)
		{
			$contador++;
			$aux_total=$ARRAY_HONORARIO["total"][$indice];
			$aux_sede=$ARRAY_HONORARIO["sede"][$indice];
			$aux_mes=$ARRAY_HONORARIO["mes"][$indice];
			$aux_estado=$ARRAY_HONORARIO["estado"][$indice];
			$aux_year_generacion=$ARRAY_HONORARIO["year_generacion"][$indice];
			$aux_fecha_estado=$ARRAY_HONORARIO["fecha_estado"][$indice];
			
			if($aux_fecha_estado=="0000-00-00"){ $aux_fecha_estado="---";}
			
			
			if($aux_estado!=="cancelado")
			{ $boton='<a href="pago_honorario/pago_honorario_docente_1.php?H_id='.base64_encode($aux_id).'&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=530"  class="lightbox button_R" title="click para dar pago a Funcionario">Pagar</a>';}
			else
			{ $boton="-";}
			//busco pagos previo al honorario
			if(DEBUG){echo"Busco Pagos previos a Cuota Honorario:<br>";}
			$consPP="SELECT SUM(valor) FROM honorario_docente_pagos WHERE id_honorario='$aux_id'";
			if(DEBUG){echo"-->$consPP<br>";}
			$sqliPP=$conexion_mysqli->query($consPP)or die($conexion_mysqli->error);
			$PP=$sqliPP->fetch_row();
			$pagosPrevios=$PP[0];
			if(empty($pagosPrevios)){$pagosPrevios=0;}
			$sqliPP->free();
			if(DEBUG){echo"Pagos previos realizados sumado: $pagosPrevios<br>";}
			
			//deuda actual x cuota honorario
			$deudaActual=($aux_total-$pagosPrevios);
			if(DEBUG){echo"Deuda Actual cuota: $deudaActual<br>";}
			//---------------------------------------------------------------
			echo'<tr height="35">
					<td>'.$contador.'</td>
					<td>'.$aux_id.'</td>
					<td>'.$aux_sede.'</td>
					<td>'.$aux_year_generacion.'</td>
					<td>'.$aux_mes.'</td>
					<td align="center">'.$aux_estado.'</td>
					<td align="center">'.$aux_fecha_estado.'</td>
					<td align="right">'.number_format($aux_total,0,",",".").'</td>
					<td align="right">'.number_format($deudaActual,0,",",".").'</td>
					<td align="center"><a href="ver_pago_honorario/ver_pago_honorario.php?H_id='.base64_encode($aux_id).'&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=300" class="lightbox button" title="Ver Detalles">Ver</a></td>
					<td align="center">'.$boton.'</td>
				</tr>';
		}
		$conexion_mysqli->close();
	}
	else
	{ echo'<tr><td colspan="10">Sin Honorario Registrados o autorizados</td></tr>';}
	?>
    </tbody>
  </table>
</div>
</body>
</html>