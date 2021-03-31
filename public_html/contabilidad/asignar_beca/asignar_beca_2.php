<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno->asignacion de Becas V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
 require("../../../funciones/conexion_v2.php");
 //////////////////////XAJAX/////////////////

@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("asignar_beca_2_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"ACTUALIZA_CANTIDAD");
$xajax->register(XAJAX_FUNCTION,"RECALCULAR");
$xajax->register(XAJAX_FUNCTION,"ARANCEL_X_SEMESTRE");
$xajax->register(XAJAX_FUNCTION,"ASIGNAR_BENEFICIO");
$xajax->register(XAJAX_FUNCTION,"QUITAR_BENEFICIO");
$xajax->register(XAJAX_FUNCTION,"ACTUALIZAR_TABLA_BENEFICIOS");
$xajax->register(XAJAX_FUNCTION,"ACTUALIZAR_BENEFICIO");
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");
$xajax->register(XAJAX_FUNCTION,"ARMAR_CUOTAS");
//////////DEBUG////////////////
//busqueda de beneficios ya asignados


$continuar=false;

if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])){
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]==true){
		$continuar=true;
	}
}

if($continuar){
if(isset($_SESSION["FINANZASX"])){unset($_SESSION["FINANZASX"]);}
	require("../../../funciones/funciones_sistema.php");
	require("../../../funciones/conexion_v2.php");
	
	$totalbeneficiosEstudiantiles=0;
	
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$sedeAlumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	$id_contrato=base64_decode($_GET["ID"]);
	$linea_credito=base64_decode($_GET["LC"]);
	$arancel_contrato=base64_decode($_GET["AC"]);
	$arancel_anual=base64_decode($_GET["A"]);
	$ya_cancelado=base64_decode($_GET["YC"]);
	$saldo_a_favor_anterior=base64_decode($_GET["SA"]);
	$cantidad_desc=base64_decode($_GET["CD"]);
	$semestre_restantes_BNM=base64_decode($_GET["SRBNM"]);///para saber cuanta beca asignar
		
	$year=base64_decode($_GET["year"]);
	$semestre=base64_decode($_GET["semestre"]);
		
		
		
	$consA="SELECT arancel FROM contratos2 WHERE id='$id_contrato' LIMIT 1";
	$sqliA=$conexion_mysqli->query($consA);
	$DC=$sqliA->fetch_assoc();
		$arancel=$DC["arancel"];
	$sqliA->free();	
	
	$cons="SELECT id_beneficio, valor FROM beneficiosEstudiantiles_asignaciones WHERE id_contrato='$id_contrato' AND id_alumno='$id_alumno'";
	$sqliBE=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_beneficios=$sqliBE->num_rows;
	if(DEBUG){ echo"$cons<br>num: $num_beneficios<br>";}
	$hayBeneficioPrevios=false;
	if($num_beneficios>0){
		$hayBeneficioPrevios=true;
		while($BE=$sqliBE->fetch_assoc()){
			$auxIdBeneficio=$BE["id_beneficio"];
			$auxValor=number_format($BE["valor"],0,"","");
			
			$totalbeneficiosEstudiantiles+=$auxValor;
			
			  $cons_BEX="SELECT * FROM beneficiosEstudiantiles WHERE id='$auxIdBeneficio' LIMIT 1";
			  $sqli_BEX=$conexion_mysqli->query($cons_BEX);
			  $DBE=$sqli_BEX->fetch_assoc();
				$_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$auxIdBeneficio]["nombre"]=$DBE["beca_nombre"];
				$_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$auxIdBeneficio]["tipo"]=$DBE["beca_tipo_aporte"];
				$_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$auxIdBeneficio]["forma"]=$DBE["formaAporte"];
				$_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$auxIdBeneficio]["aporteValor"]=$auxValor;
				$_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$auxIdBeneficio]["aportePorcentaje"]=$DBE["beca_aporte_porcentaje"];
				$_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$auxIdBeneficio]["familiaBeneficio"]=$DBE["familiaBeneficio"];
				$_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$auxIdBeneficio]["duracion"]=$DBE["duracion"];
			}
	}
	$sqliBE->free();
	
	
	$cantidad_a_pactar=((($arancel_anual-$ya_cancelado)-$saldo_a_favor_anterior));
	
	$total=$cantidad_a_pactar-$totalbeneficiosEstudiantiles;
	
}
//////////////////////////////
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Asignar Beca</title>
<?php $xajax->printJavascript(); ?> 
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:50%;
	height:91px;
	z-index:1;
	left: 5%;
	top: 230px;
}
#apDiv2 {
	position:absolute;
	width:50%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 83px;
}
#div_pagos {
	position:absolute;
	width:441px;
	height:29px;
	z-index:3;
	left: 352px;
	top: 50px;
}
#apDiv3 {
	position:absolute;
	width:40%;
	height:115px;
	z-index:4;
	left: 55%;
	top: 266px;
}
#apDiv3 #frm #botonera {
	width: 105px;
	float: right;
}
-->
</style>

<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
#apDiv4 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:5;
	left: 599px;
	top: 163px;
}
#Layer3 {
	position:absolute;
	width:170px;
	height:17px;
	z-index:2;
	left: 558px;
	top: 297px;
}
.Estilo2 {font-weight: bold}
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
#DEBUG_1 {
	position:absolute;
	width:232px;
	height:68px;
	z-index:5;
	left: 665px;
	top: -61px;
}
#apDiv5 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:5;
	left: 643px;
	top: 215px;
}
.Estilo3 {
	font-size: 10px;
	font-style: italic;
}
#apDiv6 {
	position:absolute;
	width:231px;
	height:118px;
	z-index:5;
	left: 427px;
	top: 96px;
}
#div_beneficiosEstudiantilesAsignados {
	position:absolute;
	width:40%;
	height:150px;
	z-index:2;
	left: 55%;
	top: 84px;
	overflow:auto;
}
#div_botonera {
	position:absolute;
	width:50%;
	height:37px;
	z-index:5;
	left: 5%;
	top: 590px;
	text-align:center;
}
-->
</style>
</head>
<?php
	$vigenciaX="anual";//para indicar que arancel utilizar

	
	if($vigenciaX=="semestral")
	{ $arancel_anual=$arancel_contrato;}
	
	$saldo_a_favor_new=($ya_cancelado+$saldo_a_favor_anterior);
	$cantidad_a_pactar=($arancel_anual-$ya_cancelado)-$saldo_a_favor_anterior;
	
	$max_avance_mes=6;
	$max_num_cuotas=11;
	
	
	
	$max_dia_mes=30;
	$array_meses=array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
 /////////////////-----------------------------///////////////////////////
 
	
	/////////////////////////////////
	$linea_credito_meses_avance=1;//agregado
	$linea_credito_cantidad=$total;//////para que aparesca inicialmente
?>
<body onload="xajax_ACTUALIZAR_TABLA_BENEFICIOS(<?php echo $arancel;?>);">
<h1 id="banner">Asignar Beca V2</h1>

<div id="link"><br />
<a href="asignar_beca_1.php" class="button">Volver a Seleccion</a></div>


 <form action="asignar_beca_3.php" method="post" name="frm" id="frm">
<div id="apDiv2">
  <table width="80%" border="0" align="left">
  <thead>
    <tr>
      <th colspan="2"><strong>Agrega 
          <input name="id_alumno" type="hidden" id="id_alumno" value="<?php echo $id_alumno;?>" />
        <input name="year" type="hidden" id="year" value="<?php echo $year;?>" />
        <input type="hidden" name="semestre" id="semestre"  value="<?php echo $semestre;?>"/>
       
      </strong></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="25%"><em>Beneficio Estudiantil</em></td>
      <td width="75%"><select name="beneficiosDisponibles" id="beneficiosDisponibles">
              <?php
	 
      	$cons_BE="SELECT * FROM beneficiosEstudiantiles WHERE beca_condicion='activa' ORDER by beca_nombre";
		$sqli_BE=$conexion_mysqli->query($cons_BE);
		$numBeneficios=$sqli_BE->num_rows;
		if($numBeneficios>0){
			while($DBE=$sqli_BE->fetch_assoc()){
				$BE_id=$DBE["id"];
				$BE_nombre=$DBE["beca_nombre"];
				$BE_tipoAporte=$DBE["beca_tipo_aporte"];
				$BE_aporteValor=$DBE["beca_aporte_valor"];
				$BE_aportePorcentaje=$DBE["beca_aporte_porcentaje"];
				
				echo'<option value="'.$BE_id.'">'.$BE_nombre.'</option>';
			}
		}
		$sqli_BE->free();
		$conexion_mysqli->close();
	  ?>
              </select>
        <input name="validador" type="hidden" id="validador" value="<?php echo md5("AGREGA_cuota".date("Y-m-d"));?>" />
        <a href="#" class="button_R" onclick="xajax_ASIGNAR_BENEFICIO(document.getElementById('beneficiosDisponibles').value, <?php echo $arancel;?>);"> Asignar</a></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    </tbody>
  </table>

</div>
<div id="div_beneficiosEstudiantilesAsignados">....</div>

<div id="apDiv1">

  <table width="80%" height="261" border="0">
  <thead>
    <tr>
      <th colspan="2"><strong>>Información
        <input name="id_contrato" type="hidden" id="id_contrato" value="<?php echo $id_contrato;?>" />
      </strong></th>
    </tr>
    </thead>
    <tr>
      <td>Vigencia</td>
      <td><label for="vigencia_contrato_manual"></label>
        <select name="vigencia_contrato_manual" id="vigencia_contrato_manual" onchange="xajax_ARANCEL_X_SEMESTRE(this.value); return false;">
          <option value="anual">anual</option>
          <option value="1_semestral" <?php if($semestre==1){echo'selected="selected"';}?>>1_semestral</option>
          <option value="2_semestral" <?php if($semestre==2){echo'selected="selected"';}?>>2_semestral</option>
        </select>
        </td>
    </tr>
    <tr>
      <td width="137">Arancel (Anual/Semestral)</td>
      <td width="153"><input name="arancel" type="text" id="arancel" value="<?php echo $arancel_contrato;?>" readonly="readonly"/></td>
    </tr>
    <tr>
      <td>Excedente Anterior</td>
      <td><input name="excedente_anterior" type="text" id="excedente_anterior" value="<?php echo $saldo_a_favor_anterior;?>" /></td>
    </tr>
    <tr>
      <td>Total Cancelado</td>
      <td><input type="text" name="total_cancelado" id="total_cancelado" value="<?php echo $ya_cancelado;?>" readonly="readonly"/>
        <div id="DEBUG_1"></div></td>
    </tr>
    <tr>
      <td>Total Deuda</td>
      <td >
        <label for="totalDeuda"></label>
        <input type="text" name="campo_totalDeuda" id="campo_totalDeuda" value="<?php echo $cantidad_a_pactar;?>"  readonly="readonly"/></td>
    </tr>
    <tr>
      <td>Total Beneficios Estudiantiles</td>
      <td><label for="totalBeneficiosEstudiantiles"></label>
        <input name="totalBeneficiosEstudiantiles" type="text" id="campo_totalBeneficiosEstudiantiles" value="<?php echo $totalbeneficiosEstudiantiles;?>" readonly="readonly"/></td>
    </tr>
    <tr>
      <td height="24" bgcolor="#f5f5f5">Total</td>
      <td bgcolor="#f5f5f5"><input name="total_saldar" type="text" id="campo_total_saldar" value="<?php echo $total;?>"  readonly="readonly"/></td>
    </tr>
    <tr>
      <td height="35" colspan="2"><div align="center">
        <p align="right"><a href="#"  class="button_R" onclick="xajax_RECALCULAR(xajax.getFormValues('frm_info'));return false;">Recalcular</a></p>
        </div></td>
      </tr>
  </table>

</div>
<div id="apDiv3">

</div>
<div id="div_botonera"></div>
  </form>
</body>
</html>