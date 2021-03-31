<?php
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno->asignacion de Becas V1");
	$O->PERMITIR_ACCESO_USUARIO();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<title>Asignar Cuota - Alumno</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:1;
	left: 461px;
	top: 103px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:43px;
	z-index:1;
	left: 5%;
	top: 340px;
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
#apDiv3 {
	position:absolute;
	width:40%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 74px;
}
#apDiv4 {
	position:absolute;
	width:45%;
	height:31px;
	z-index:3;
	left: 50%;
	top: 251px;
}
-->
</style>
</head>

<body>
<h1 id="banner">Administrador - Asignacion Beca V2</h1>
<div id="link"><br />
<a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver al Menu</a></div>
<div id="apDiv2">
  <table width="80%" border="1" align="left">
  <caption>
  Seleccione el Contrato a Re-asignar
  </caption>
  <thead>
  <tr>
    <th>N&deg;</th>
    <th>COD.</th>
    <th>N Cuotas</th>
    <th>Linea credito</th>
    <th>Arancel Anual</th>
     <th>Total Ya Cancelado</th>
    <th>Vigencia</th>
    <th>Periodo</th>
    <th>Opcion</th>
  </tr>
  </thead>
<tbody>
<?php

$msj_BNM="";
$msj_EXC="";
$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
$carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
$id_carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
$nivel_alumno=$_SESSION["SELECTOR_ALUMNO"]["nivel"];
$yearIngresoCarrera=$_SESSION["SELECTOR_ALUMNO"]["yearIngresoCarrera"];

$semestres_dura_carrera=5;////

require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");

	///////////semestres ranscurridos
	
	//BECA BNM + BET
	$semestres_con_beca_NM=SEMESTRES_CON_BECA_V2($id_alumno, 1, $id_carrera_alumno, $yearIngresoCarrera) +SEMESTRES_CON_BECA_V2($id_alumno, 2, $id_carrera_alumno, $yearIngresoCarrera);
	
	$cons="SELECT * FROM contratos2 WHERE id_alumno='$id_alumno' AND condicion='ok' AND NOT(reasignado='si')";
	if(DEBUG){ echo"--> $cons<br>";}
	$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_reg=$sql->num_rows;
	if($num_reg>0)
	{
		$aux=0;
		$_SESSION["REASIGNAR"]["verificador"]=true;
		while($C=$sql->fetch_assoc())
		{
			$aux++;
			$id_contrato=$C["id"];
			$arancel=$C["arancel"];
			$vigencia=$C["vigencia"];
			$linea_credito_paga=$C["linea_credito_paga"];
			$contado_paga=$C["contado_paga"];
			$cheque_paga=$C["cheque_paga"];
			$opcion_paga_matricula=$C["opcion_pag_matricula"];
			$numero_cuotas=$C["numero_cuotas"];
			$saldo_a_favor=$C["saldo_a_favor"];
			$cantidad_desc=$C["cantidad_beca"];
			$year_contrato=$C["ano"];
			$C_semestre=$C["semestre"];
			$C_year=$C["ano"];
			
			
			//total ya pagado//////////////////////////////////
			$cons_yc="SELECT valor, deudaXletra FROM letras WHERE idalumn='$id_alumno' AND id_contrato='$id_contrato' AND tipo='cuota'";
			if(DEBUG){ echo"---> $cons_yc<br>";}
			$sql_yc=$conexion_mysqli->query($cons_yc)or die($conexion_mysqli->error);
			$num_cuotas=$sql_yc->num_rows;
			if($num_cuotas>0)
			{
				$total_ya_cancelado=0;
				while($M=$sql_yc->fetch_assoc())
				{
					$valor_cuota=$M["valor"];
					$deudaXcuota=$M["deudaXletra"];
					
					$pagado_X_cuota=($valor_cuota-$deudaXcuota);
					if(DEBUG){ echo"--->$pagado_X_cuota<br>";}
					$total_ya_cancelado+=$pagado_X_cuota;
				}	
			}
			else
			{ $total_ya_cancelado=0;}
			////Sumo pago contado y con cheque registrado en contrato
			$total_ya_cancelado+=($contado_paga+$cheque_paga);
			
			
			$sql_yc->free();
			//////valor carrera anual//////////////////////////////
			
			$cons_vc="SELECT * FROM hija_carrera_valores WHERE id_madre_carrera='$id_carrera_alumno' AND sede='$sede_alumno' AND year='$year_contrato' LIMIT 1";
			$sql_vc=$conexion_mysqli->query($cons_vc)or die($conexion_mysqli->error);
			$Dvc=$sql_vc->fetch_assoc();
				///valor de matricula para descontar a saldo anterior si pago con excedente matricula
				$valor_matricula=$Dvc["matricula"];
				$msj_EXC="";
				///
				if($opcion_paga_matricula=="EXCEDENTE")
				{ 
					$saldo_a_favor-=$valor_matricula;
					$msj_EXC="Matricula ($valor_matricula) Descontada del saldo a favor...!";
				}
				if($saldo_a_favor<0){ $saldo_a_favor=0;}
				
				
				if(empty($valor_matricula)){ $valor_matricula=0;}
			$semestres_restantes=($semestres_dura_carrera-$semestres_con_beca_NM);
			if(($nivel_alumno>=5)or($semestres_restantes<2))
			{
					 $arancel_anual=$Dvc["arancel_1"];
					 if(DEBUG){ echo" Medio Arancel...<br>";}
					 $arancel_semestre_1=$Dvc["arancel_1"];
			}
			else
			{ 
				$arancel_semestre_1=$Dvc["arancel_1"];
				$arancel_semestre_2=$Dvc["arancel_2"];
				$arancel_anual=($arancel_semestre_1+$arancel_semestre_2);
				if(DEBUG){ echo"Arancel Completo...<br>";}
			}
			
			
			$sql_vc->free();
			if(DEBUG){ echo"-----> $cons_vc<br>ARANCEL ANUAL: $arancel_anual<br>";}
			$sql_vc=$conexion_mysqli->query($cons_vc)or die($conexion_mysqli->error);
			
			////////////////////////////////////////////////////////
			echo'<tr>
			<td>'.$aux.'</td>
			<td>'.$id_contrato.'</td>
			<td>'.$numero_cuotas.'</td>
			<td>'.number_format($linea_credito_paga,0,",",".").'</td>
			<td>'.number_format($arancel_anual,0,",",".").'</td>
			<td>'.number_format($total_ya_cancelado,0,",",".").'</td>
			<td>'.$vigencia.'</td>
			<td>'.$C_semestre.' - '.$C_year.'</td>
			<td>';
			if($semestres_restantes>=1)
			{$msj_BNM="";}
			else
			{$msj_BNM="<strong>Duracion Maxima de Beca Cumplida [$semestres_con_beca_NM]....</strong>";}
			
			$url_1='asignar_beca_2.php?ID='.base64_encode($id_contrato).'&LC='.base64_encode($linea_credito_paga).'&A='.base64_encode($arancel_semestre_1).'&YC='.base64_encode($total_ya_cancelado).'&SA='.base64_encode($saldo_a_favor).'&AC='.base64_encode($arancel).'&CD='.base64_encode($cantidad_desc).'&SRBNM='.base64_encode($semestres_restantes);
			
			$url_2='asignar_beca_2.php?ID='.base64_encode($id_contrato).'&LC='.base64_encode($linea_credito_paga).'&A='.base64_encode($arancel_anual).'&YC='.base64_encode($total_ya_cancelado).'&SA='.base64_encode($saldo_a_favor).'&AC='.base64_encode($arancel).'&CD='.base64_encode($cantidad_desc).'&SRBNM='.base64_encode($semestres_restantes).'&year='.base64_encode($C_year).'&semestre='.base64_encode($C_semestre);
			
			echo'<a href="'.$url_2.'">Seleccionar</a></td>
			</tr>';
		}
	}
	else
	{ echo'<tr><td colspan="6">Sin Contratos Generados, o contrato ya reasignado</td></tr>';}
$conexion_mysqli->close();
////////////////////////

?>
</tbody>
</table></div>
<div id="apDiv3">
  <table width="100%" border="1">
  	<thead>
    <tr>
      <th colspan="2">Alumno:<?php echo $_SESSION["SELECTOR_ALUMNO"]["id"];?></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>Nombre</td>
      <td><?php echo $_SESSION["SELECTOR_ALUMNO"]["nombre"];?></td>
    </tr>
    <tr>
      <td>Apellido</td>
      <td><?php echo $_SESSION["SELECTOR_ALUMNO"]["apellido"];?></td>
    </tr>
    <tr>
      <td>Nivel</td>
      <td><?php echo $_SESSION["SELECTOR_ALUMNO"]["nivel"];?></td>
    </tr>
    <tr>
      <td>Carrera</td>
      <td><?php echo $_SESSION["SELECTOR_ALUMNO"]["carrera"];?></td>
    </tr>
    <tr>
      <td>Duraci&oacute;n Carrera</td>
      <td><?php echo $semestres_dura_carrera; ?></td>
    </tr>
    <tr>
      <td>semestre con BNM</td>
      <td><?php echo "$semestres_con_beca_NM/$semestres_dura_carrera"; ?></td>
    </tr>
    </tbody>
  </table>
</div>
<div id="apDiv4"><?php echo "$msj_BNM <br> $msj_EXC"; ?></div>
</body>
</html>