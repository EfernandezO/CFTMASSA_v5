<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Repactar_cuotas_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//--------------//	
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");
 //////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("repactar_cuota_2_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"ACTUALIZA_CANTIDAD");
$xajax->register(XAJAX_FUNCTION,"RECALCULAR");
$xajax->register(XAJAX_FUNCTION,"ARANCEL_X_SEMESTRE");
//////////////////////
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Repactar cuotas</title>
<?php $xajax->printJavascript(); ?> 
<script language="javascript" type="text/javascript">
function Verificar()
{
	c=confirm('Seguro(a) Desea Continuar\n Se Generaran Nuevas mensualidades(Las Antiguas se Eliminaran)\n El Contrato no se modificara');
	if(c)
	{
		document.getElementById('frm').submit();
	}
}

function FORZAR_ACTUALIZAR()
{
	xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'));
}
</script>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:30%;
	height:91px;
	z-index:1;
	left: 5%;
	top: 105px;
}
#apDiv2 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:2;
	left: 354px;
	top: 136px;
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
	width:492px;
	height:115px;
	z-index:4;
	left: 5%;
	top: 357px;
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

#DEBUG_1 {
	position:absolute;
	width:232px;
	height:68px;
	z-index:5;
	left: 50%;
	top: 213px;
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
	left: 50%;
	top: 148px;
}
#apDiv7 {
	position:absolute;
	width:200px;
	height:30px;
	z-index:6;
	left: 50%;
	top: 137px;
}
#div_botonera {
	position:absolute;
	width:200px;
	height:115px;
	z-index:7;
	left: 50%;
	top: 374px;
}
-->
</style>
</head>
<?php
	$id_contrato=base64_decode($_GET["ID"]);
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$id_carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	//datos alumno
	$consA="SELECT apellido, nombre, apellido_P, apellido_M, situacion, situacion_financiera, jornada, nivel, aplicar_intereses, aplicar_gastos_cobranza FROM alumno WHERE id='$id_alumno' LIMIT 1";
	$sqli_A=$conexion_mysqli->query($consA);
	$A=$sqli_A->fetch_assoc();
	
		$apellido_old=$A["apellido"];
		$apellido_new=$A["apellido_P"]." ".$A["apellido_M"];
		
		$aplicar_intereses=$A["aplicar_intereses"];
		$aplicar_gastos_cobranza=$A["aplicar_gastos_cobranza"];
		
		if($aplicar_intereses==1){$aplicar_intereses=true;}
		else{ $aplicar_intereses=false;}
		
		if($aplicar_gastos_cobranza==1){$aplicar_gastos_cobranza=true;}
		else{ $aplicar_gastos_cobranza=false;}
		
		if($aplicar_intereses){ $info_interes=" Intereses: Si<br>";}
		else{ $info_interes="Intereses: No<br>";}
		
		if($aplicar_gastos_cobranza){ $info_interes.=" Gastos: Si";}
		else{ $info_interes.="Gastos: NO";}
	$sqli_A->free();	
	
//total ya pagado//////////////////////////////////
	$cons_yc="SELECT id, valor, deudaXletra FROM letras WHERE idalumn='$id_alumno' AND id_contrato='$id_contrato' AND tipo='cuota'";
	if(DEBUG){ echo"---> $cons_yc<br>";}
	$sql_yc=$conexion_mysqli->query($cons_yc)or die($conexion_mysqli->error);
	$num_cuotas=$sql_yc->num_rows;
	
	$total_valor_cuota=0;
	$total_deuda_cuota=0;
	$numero_cuotas=0;
	$total_ya_cancelado=0;
	$TOTAL_INTERES=0;
	$TOTAL_GASTOS_COBRANZA=0;
	if($num_cuotas>0)
	{
		while($M=$sql_yc->fetch_assoc())
		{
			$id_cuota=$M["id"];
			$numero_cuotas++;
			$valor_cuota=$M["valor"];
			$deudaXcuota=$M["deudaXletra"];
			
			if($aplicar_intereses)
			{$aux_interes=INTERES_X_ATRASO_V2($id_cuota);}
			else{$aux_interes=0;}
			
			if($aplicar_gastos_cobranza)
			{$aux_gastos_cobranza=GASTOS_COBRANZA_V2($id_cuota);}
			else{$aux_gastos_cobranza=0;}
			
			$TOTAL_INTERES+=$aux_interes;
			$TOTAL_GASTOS_COBRANZA+=$aux_gastos_cobranza;
			
			$total_valor_cuota+=$valor_cuota;
			$total_deuda_cuota+=$deudaXcuota;
			
			$pagado_X_cuota=($valor_cuota-$deudaXcuota);
			if(DEBUG){ echo"--->$pagado_X_cuota<br>";}
			$total_ya_cancelado+=$pagado_X_cuota;
		}	
	}
	$sql_yc->free();
	
	$DEUDA_ARANCEL_ACTUAL=($total_valor_cuota-$total_ya_cancelado);
	$TOTAL_DEUDA=($DEUDA_ARANCEL_ACTUAL+$TOTAL_INTERES+$TOTAL_GASTOS_COBRANZA);
/////////////////-----------------------------///////////////////////////	
	$max_dia_mes=30;
	$array_meses=array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
	$max_avance_mes=3;
	$max_num_cuotas=10;
 /////////////////-----------------------------///////////////////////////
 
	
	/////////////////////////////////
	$linea_credito_meses_avance=1;//agregado
?>
<body>
<h1 id="banner">Repactar Cuotas</h1>

<div id="link"><br />
<a href="repactar_cuota_1.php" class="button">Volver a Seleccion</a></div>
<div id="apDiv1">
<form name="frm_info" id="frm_info">
  <table width="100%" border="0">
  <thead>
    <tr>
      <th colspan="2" bgcolor="#e5e5e5"><strong>>Información
        <input name="id_contratoZ" type="hidden" id="id_contratoZ" value="<?php echo $id_contrato;?>" />
        <input name="max_avance_mes" type="hidden" id="max_avance_mes" value="<?php echo $max_avance_mes;?>" />
        <input name="max_numero_cuotas" type="hidden" id="max_numero_cuotas" value="<?php echo $max_num_cuotas;?>" />
        <input name="max_dia_mes" type="hidden" id="max_dia_mes" value="<?php echo $max_dia_mes;?>" />
        <input name="linea_credito_meses_avance" type="hidden" id="linea_credito_meses_avance" value="<?php echo $linea_credito_meses_avance;?>" />
      </strong></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td >Total deuda Arancel</td>
      <td ><input name="deuda_cuotas" type="text" id="deuda_cuotas" value="<?php echo $total_valor_cuota;?>"/></td>
    </tr>
    <tr>
      <td>Total ya Cancelado</td>
      <td><label for="total_ya_cancelado"></label>
        <input name="total_ya_cancelado" type="text" id="total_ya_cancelado" value="<?php echo $total_ya_cancelado;?>" /></td>
    </tr>
    <tr>
      <td>Subtotal</td>
      <td><div id="div_subtotal"><?php echo $DEUDA_ARANCEL_ACTUAL; ?></div></td>
    </tr>
    <tr>
      <td>intereses</td>
      <td><input name="intereses" type="text" id="intereses" value="<?php echo $TOTAL_INTERES;?>" readonly="readonly" /></td>
    </tr>
    <tr>
      <td>Gastos Cobranza</td>
      <td><input type="text" name="gastos_cobranza" id="gastos_cobranza" value="<?php echo $TOTAL_GASTOS_COBRANZA;?>" readonly="readonly"/>
       </td>
    </tr>
    <tr>
      <td>Total</td>
      <td ><input name="total_saldar" type="text" id="total_saldar" value="<?php echo $TOTAL_DEUDA;?>"  readonly="readonly"/></td>
    </tr>
      </tbody>
  </table>
  </form>
</div>
<div id="DEBUG_1"></div>
<div id="apDiv3">
  <form action="repactar_cuota_3.php" method="post" name="frm" id="frm">
    <table width="100%" border="0">
        <thead>
          <tr>
            <th colspan="4"><input name="validador" type="hidden" id="validador" value="<?php echo md5("reasignacion_c".date("Y-m-d"));?>" />
              <strong>>Linea Credito
              <input name="id_contratoX" type="hidden" id="id_contratoX" value="<?php echo $id_contrato;?>" />
              </strong></th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td width="136">Cantidad</td>
            <td colspan="3"><input type="text" name="linea_credito_cantidad" id="linea_credito_cantidad"  value="<?php echo $TOTAL_DEUDA;?>" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'));return false;" readonly="readonly"/></td>
          </tr>
          <tr>
            <td>Numero de Cuotas</td>
            <td colspan="3"><select name="linea_credito_cantidad_cuotas" id="linea_credito_cantidad_cuotas"  onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'));return false;">
              <?php
	  for($c=1;$c<=$max_num_cuotas;$c++)
	  {
	  		echo'<option value="'.$c.'">'.$c.'</option>';	
	  }
	  ?>
            </select></td>
          </tr>
          <tr>
            <td>Mes Inicio</td>
            <td width="117"><select name="linea_credito_mes_ini" id="linea_credito_mes_ini" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'));return false;">
              <?php
	  		foreach($array_meses as $n => $valor)
			{
			 
				if($n+1==date("m"))
				{
					echo'<option value="'.($n + 1).'" selected="selected">'.$valor.'</option>';
				}
				else
				{
					echo'<option value="'.($n + 1).'">'.$valor.'</option>';
				}	
			}	
	   ?>
                        </select></td>
            <td width="118">Meses Avance</td>
            <td width="97"><select name="meses_avance" id="meses_avance" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'));return false;"> 
              <?php
              for($ma=1;$ma<=$max_avance_mes;$ma++)
			  {
			  	if($linea_credito_meses_avance==$ma)
				{
					echo'<option value="'.$ma.'" selected="selected">'.$ma.'</option>';
				}
				else
				{
					echo'<option value="'.$ma.'">'.$ma.'</option>';
				}
			  }
			  ?>
            </select>            </td>
          </tr>
          <tr>
            <td>Dia Vencimiento</td>
            <td colspan="3"><select name="linea_credito_dia_vencimiento" id="linea_credito_dia_vencimiento" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'));return false;">
              <?php
			  $array_dias_disponibles=array(5,10,15,20,25,30);
			  
	  foreach($array_dias_disponibles as $n => $valor)
	  {
	  	echo'<option value="'.$valor.'">'.$valor.'</option>';
	  }
	  ?>
            </select></td>
          </tr>
          <tr>
            <td>Año</td>
            <td colspan="3">
            <select name="linea_credito_year" id="linea_credito_year" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'));return false;">
            <?php
				$año_actual=date("Y");
				$año_ini=$año_actual-10;
				$año_fin=$año_actual+1;
            	for($a=$año_ini;$a<=$año_fin;$a++)
				{
						if($a==$año_actual)
						{
							echo'<option value="'.$a.'" selected="selected">'.$a.'</option>';
						}
						else
						{
							echo'<option value="'.$a.'" >'.$a.'</option>';
						}
						
				}
			?>
            </select>            </td>
          </tr>
          </tbody>
        </table>
        <div id="resultado_linea_credito">
          <div align="center"><a href="#" onclick="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'));return false;">Actualizar</a></div>
    </div>
</form>
</div>
<div id="apDiv7"><a href="#"  class="button_G" onclick="xajax_RECALCULAR(xajax.getFormValues('frm_info'));return false;">Recalcular</a></div>
</body>
</html>