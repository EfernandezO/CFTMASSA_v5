<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG",false);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_3.css"/>
<title>Estado Financiero Alumno X fecha</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 132px;
}
a:link {
	color: #069;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #069;
}
a:hover {
	text-decoration: underline;
	color: #F00;
}
a:active {
	text-decoration: none;
	color: #069;
}
</style>
</head>
<?php
if($_POST)
{
	if(DEBUG){ var_export($_POST);}
	$sede=$_POST["fsede"];
	$carrera=$_POST["carrera"];
	$nivel=$_POST["nivel"];
	$jornada=$_POST["jornada"];
	$fecha_corte=$_POST["fecha_corte"];
	$continuar=true;
	$ver_detalle=false;
	include("../../../funciones/funcion.php");
}
else
{ 
	echo"Sin Datos<br>";
	$continuar=false;
}
?>
<body>
<h1 id="banner">Administrador - Estado financiero X Fecha</h1>
<div id="link"><br />
<a href="index.php" class="button">Volver a Seleccion</a>
  <div id="apDiv1">
  
  <table width="80%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="11" align="center">Condicion Financiera al <?php echo fecha_format($fecha_corte)?> - <?php echo $carrera?>
        <br />
        Nivel <?php echo $nivel;?> - Jornada <?php echo $jornada;?><br />
		<?php echo $sede;?>
        </th>
    </tr>
    <tr>
    	<td>N°</td>
        <td>ID</td>
        <td>Ingreso</td>
        <td>Rut</td>
        <td>Nombre</td>
        <td>Apellido P</td>
        <td>Apellido M</td>
        <td>Total Deuda</td>
        <td>Total Pagado</td>
        <td>Diferencia</td>
        <td>Condicion</td>
    </tr>
    </thead>
    <tbody>
  <?php
  if($continuar)
  {
	  include("../../../funciones/conexion.php");
	  if($carrera!="todas")
	  { $condicion_carrera=" AND carrera='$carrera'";}
	  if($nivel!="todos")
	  { $condicion_nivel=" AND nivel='$nivel'";}
	  if($jornada!="todas")
	  { $condicion_jornada=" AND jornada='$jornada'";}
	  
	  $cons_A="SELECT * FROM alumno WHERE sede='$sede' AND situacion='V' $condicion_carrera $condicion_nivel $condicion_jornada ORDER by carrera, nivel, jornada, apellido_P, apellido_M";
	  
	  $cons_A="SELECT alumno.*, contratos2.id as id_contrato, contratos2.semestre, contratos2.ano, contratos2.vigencia FROM alumno INNER JOIN contratos2 ON alumno.id = contratos2.id_alumno WHERE alumno.sede='$sede' AND situacion='V' $condicion_carrera $condicion_nivel $condicion_jornada ORDER by carrera, nivel, jornada, apellido_P, apellido_M";

	  if(DEBUG){ echo"A->$cons_A<br>";}
	  $sql_A=mysql_query($cons_A)or die("ALUMNO ".mysql_error());
	  $num_alumnos=mysql_num_rows($sql_A);
	  if($num_alumnos>0)
	  {
		  $A_contador=0;
		  $contador_al_dia=0;
		  $contador_moroso=0;
		  $DIFERENCIA_A_LA_FECHA=0;
		  while($A=mysql_fetch_assoc($sql_A))
		  {
			  $A_contador++;
			  //datos de alumnos seleccionados
			  $A_id=$A["id"];
			  $A_carrera=$A["carrera"];
			  $A_rut=$A["rut"];
			  $A_nombre=$A["nombre"];
			  $A_apellido_P=$A["apellido_P"];
			  $A_apellido_M=$A["apellido_M"];
			  $A_ingreso=$A["ingreso"];
			  
			  
			  $A_DEUDA=DEUDA_ALUMNO($A_id, $fecha_corte);
			  $A_PAGOS=PAGOS_ALUMNO($A_id, $fecha_corte);
			  
			  $TOTAL_DEUDA_A_LA_FECHA=$A_DEUDA["TOTAL"];
			  $TOTAL_PAGADO_A_LA_FECHA=$A_PAGOS["TOTAL"];
			  
			  $DIFERENCIA_A_LA_FECHA=($TOTAL_DEUDA_A_LA_FECHA-$TOTAL_PAGADO_A_LA_FECHA);
			  
			  
			  if(empty($TOTAL_DEUDA_A_LA_FECHA))
			  { $TOTAL_DEUDA_A_LA_FECHA=0;}
			  if(empty($TOTAL_PAGADO_A_LA_FECHA))
			  { $TOTAL_PAGADO_A_LA_FECHA=0;}
			  
			  if($TOTAL_DEUDA_A_LA_FECHA<=$TOTAL_PAGADO_A_LA_FECHA)
			  { 
			  	$A_condicion_financiera="al dia";
				$contador_al_dia++;
			  }
			  else
			  { 
			  	$A_condicion_financiera="moroso";
				$contador_moroso++;
			   }
			  if(DEBUG)
			  {
				  echo"<br>---------------DEUDA-----------------------------<br>";
				  var_export($A_DEUDA);
				  echo"<br>----------------PAGOS----------------------------<br>";
				  var_export($A_PAGOS);
				  echo"<br>--------------------------------------------<br>";
			  }
			  $DATOS='<tr>
			  		<td>'.$A_contador.'</td>
			  		<td>'.$A_id.'</td>
					<td>'.$A_ingreso.'</td>
					<td>'.$A_rut.'</td>
					<td>'.$A_nombre.'</td>
					<td>'.$A_apellido_P.'</td>
					<td>'.$A_apellido_M.'</td>
					<td>'.$TOTAL_DEUDA_A_LA_FECHA.'</td>
					<td>'.$TOTAL_PAGADO_A_LA_FECHA.'</td>
					<td>'.$DIFERENCIA_A_LA_FECHA.'</td>
					<td>'.$A_condicion_financiera.'</td>
			  		</tr>';
					
				if($ver_detalle)	
				{
					$DATOS.='<tr>
						<td colspan="9">CUOTAS</td></tr>';
						if(count($A_DEUDA["DETALLE"]["id"])>0)
						{
							foreach($A_DEUDA["DETALLE"]["id"] as $n => $valor)
							{
								$DATOS.='<tr>
										<td>'.$n.'</td>
										 <td>'.$valor.'</td>
										 <td>'.$A_DEUDA["DETALLE"]["valor"][$n].'</td>
										 <td>'.$A_DEUDA["DETALLE"]["deudaXcuota"][$n].'</td>
										 <td>'.$A_DEUDA["DETALLE"]["vencimiento"][$n].'</td>
										 <td>'.$A_DEUDA["DETALLE"]["tipo"][$n].'</td>
										 </tr>';	
							}
						}
						else
						{
							$DATOS.='<tr>
									<td colspan="9">Sin Cuotas</td>
									</tr>';
						}
				}
				
				echo $DATOS;
		  }
	  }
	  else
	  {
		  echo'<tr><td colspan="9"> SIn Alumnos Encontrados en esta busqueda... :-(</td></tr>';
	  }
  }
  else
  {}
  mysql_free_result($sql_A);
  mysql_close($conexion);
  ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="9">(<?php echo $contador_al_dia; ?>)Alumnos al dia y (<?php echo $contador_moroso;?>) Alumnos morosos de un total de (<?php echo $A_contador;?>) Alumnos</td>
    </tr>
    </tfoot>
  </table>
  </div>
</div>
</body>
</html>
<?php
//---------------------------------------------//
function DEUDA_ALUMNO($id_alumno, $fecha_corte)
{
	$cons_DA="SELECT * FROM letras WHERE idalumn='$id_alumno' AND fechavenc<='$fecha_corte' ORDER by fechavenc";
	if(DEBUG){ echo"D-->$cons_DA<br>";}
	$sql_DA=mysql_query($cons_DA)or die("DEUDA ".mysql_error());
	$num_cuotas=mysql_num_rows($sql_DA);
	if(DEBUG){ echo"N cuotas: $num_cuotas<br>";}
	if($num_cuotas>0)
	{
		$TOTAL_DEUDA=0;
		while($C=mysql_fetch_assoc($sql_DA))
		{
			$tipo=$C["tipo"];
			$CUOTA["id"][]=$C["id"];
			$aux_valor=$C["valor"];
			$TOTAL_DEUDA+=$aux_valor;
			$CUOTA["valor"][]=$aux_valor;
			$CUOTA["deudaXcuota"][]=$C["deudaXletra"];
			$CUOTA["vencimiento"][]=$C["fechavenc"];
			$CUOTA["tipo"][]=$tipo;
			if(DEBUG){ echo"T: $tipo -> $TOTAL_DEUDA<br>";}
		}
	}
	mysql_free_result($sql_DA);
	$RESULTADO["TOTAL"]=$TOTAL_DEUDA;
	$RESULTADO["DETALLE"]=$CUOTA;
	return($RESULTADO);
}
//funcion pagos alumno
function PAGOS_ALUMNO($id_alumno, $fecha_corte)
{
	$cons_PA="SELECT * FROM pagos WHERE id_alumno='$id_alumno' AND fechapago<='$fecha_corte' AND por_concepto IN('arancel')";
	if(DEBUG){ echo"P--->$cons_PA<br>";}
	$sql_PA=mysql_query($cons_PA)or die("Pagos ".mysql_error());
	$num_pago=mysql_num_rows($sql_PA);
	if(DEBUG){ echo"N Pagos: $num_pago<br>";}
	if($num_pago>0)
	{
		$TOTAL_PAGADO=0;
		while($P=mysql_fetch_assoc($sql_PA))
		{
			$por_concepto=$P["por_concepto"];
			$PAGO["id"][]=$P["idpago"];
			$aux_valor_pagado=$P["valor"];
			$TOTAL_PAGADO+=$aux_valor_pagado;
			$PAGO["valor"][]=$aux_valor_pagado;
			$PAGO["fecha"][]=$P["fechapago"];
			$PAGO["forma"][]=$P["forma_pago"];
			$PAGO["por_concepto"][]=$por_concepto;
			if(DEBUG){ echo"Xconcepto: $por_concepto -> $$TOTAL_PAGADO<br>";}
		}
	}
	$RESULTADO_2["TOTAL"]=$TOTAL_PAGADO;
	$RESULTADO_2["DETALLE"]=$PAGO;
	return($RESULTADO_2);
}
//---------------------------------------------//
?>