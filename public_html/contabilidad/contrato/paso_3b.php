<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Matriculas_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
require("../../../funciones/conexion_v2.php");
 
 //////////////////////XAJAX/////////////////

@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("paso_3b_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"ACTUALIZA_CANTIDAD");
$xajax->register(XAJAX_FUNCTION,"VALOR_RESTANTE");
$xajax->register(XAJAX_FUNCTION,"ACTUALIZA_TOTAL");
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Paso 3b</title>
<?php $xajax->printJavascript(); ?> 
<script language="javascript" type="text/javascript">
function Verificar(tipo)
{
	if(tipo=="normal")
	{xajax_VERIFICAR(xajax.getFormValues('frm'));}
	else{document.getElementById('frm').submit();}
}
function Volver()
{
	window.location="paso2.php";
}
function CARGA_INICIAL()
{
	xajax_ACTUALIZA_TOTAL(document.getElementById('aux_total').value);
	xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'), 'LINEA_CREDITO', document.getElementById('aux_total').value);
}
</script>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:43%;
	height:91px;
	z-index:1;
	left: 2%;
	top: 50px;
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
	width:48%;
	height:29px;
	z-index:3;
	left: 50%;
	top: 50px;
}
#apDiv3 {
	position:absolute;
	width:50%;
	height:115px;
	z-index:4;
	left: 2%;
	top: 274px;
}
#apDiv3 #frm #botonera {
	width: 105px;
	float: right;
}
-->
</style>
<script src="../../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../SpryAssets/SpryTabbedPanels.css">
<script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">
<style type="text/css">
<!--
.Estilo1 {
	font-size: 16px;
	font-weight: bold;
	color: #FF0000;
}
-->
</style>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
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
.Estilo2 {
	font-size: 10px;
	font-style: italic;
}
-->
</style>
</head>
<?php
////variables a utilizar
if(DEBUG)
{//var_export($_SESSION["FINANZAS"]);
}

	$max_avance_mes=6;
	$vigencia_cuotas=$_SESSION["FINANZAS"]["vigencia_cuotas"];
	switch($vigencia_cuotas)
	{
		case"semestral":
			$max_num_cuotas=7;
			$arancel=$_SESSION["FINANZAS"]["arancel"];
			break;
		case"anual":
			$max_num_cuotas=12;
			$arancel=$_SESSION["FINANZAS"]["arancel_anual"];
			break;	
	}
	
	/////////////////////-becas--/////////////////////
	
	if(isset($_SESSION["FINANZAS"]["cantidad_beca"])){$cantidad_beca=$_SESSION["FINANZAS"]["cantidad_beca"];}
	else{ $cantidad_beca=0;}
	if(isset($_SESSION["FINANZAS"]["porcentaje_beca"])){$porcentaje_beca=$_SESSION["FINANZAS"]["porcentaje_beca"];}
	else{ $porcentaje_beca=0;}
	if(isset($_SESSION["FINANZAS"]["aporte_beca_nuevo_milenio"])){$aporte_beca_nuevo_milenio=$_SESSION["FINANZAS"]["aporte_beca_nuevo_milenio"];}
	else{ $aporte_beca_nuevo_milenio=0;}
	if(isset($_SESSION["FINANZAS"]["aporte_beca_excelencia_academica"])){$aporte_beca_excelencia_academica=$_SESSION["FINANZAS"]["aporte_beca_excelencia_academica"];}
	else{ $aporte_beca_excelencia_academica=0;}
	//saldo a favor
	if(isset($_SESSION["FINANZAS"]["EX_nuevo_excedente"]))
	{$saldo_a_favor=$_SESSION["FINANZAS"]["EX_nuevo_excedente"];}
	else
	{$saldo_a_favor=$_SESSION["FINANZAS"]["excedente"];}
	/////////////////////calculo descuento
	$aux_total=((($arancel-$cantidad_beca)-$saldo_a_favor)-$aporte_beca_nuevo_milenio)-$aporte_beca_excelencia_academica;//resto cantidad
	
	if($porcentaje_beca>0)
	{$descuentoXbeca=(($porcentaje_beca*$arancel)/100);}
	else
	{$descuentoXbeca=0;}
	///////////////////////----/////////////////////////////
	//echo"----> $cantidad_beca<br>";
	$total=($aux_total-$descuentoXbeca);
	$_SESSION["FINANZAS"]["total_a_pagar_arancel"]=$total;
/////////////////-----------------------------///////////////////////////	
	$array_bancos=array("Estado","Chile","Santander","Internacional","Scotiabank","Credito e Inversiones","Corpbanca","Bice","BBVA","HSBC Bank","Itau","Security","Falabella","Ripley","Consorcio","Penta","Paris");
 sort($array_bancos);
 	
	
	
	$max_dia_mes=30;
	$array_meses=array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
 /////////////////-----------------------------///////////////////////////
 
 		if(isset($_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["cantidad"])){$linea_credito_cantidad=$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["cantidad"];}
		else{ $linea_credito_cantidad=0;}
		if(empty($linea_credito_cantidad))
		{$linea_credito_cantidad=0;}
		if(isset($_SESSION["FINANZAS"]["METODO_PAGO"]["CONTADO"]["cantidad"])){$contado_cantidad=$_SESSION["FINANZAS"]["METODO_PAGO"]["CONTADO"]["cantidad"];}
		else{ $contado_cantidad=0;}
		if(empty($contado_cantidad))
		{$contado_cantidad=0;}
		if(isset($_SESSION["FINANZAS"]["METODO_PAGO"]["CHEQUE"]["cantidad"])){$cheque_cantidad=$_SESSION["FINANZAS"]["METODO_PAGO"]["CHEQUE"]["cantidad"];}
		else{ $cheque_cantidad=0;}
		if(empty($cheque_cantidad))
		{$cheque_cantidad=0;}
		
		
		$total_sin_pactar=$total-($linea_credito_cantidad + $contado_cantidad + $cheque_cantidad);
		
		$html_resumen="LINEA CREDITO -> $linea_credito_cantidad<br>CONTADO -> $contado_cantidad<br>CHEQUE -> $cheque_cantidad<br>____________________<br>Sin Pactar ==> $total_sin_pactar<br>";
	
	
	////////////////si se pago matricula con cheque doy opcion de utilizar el mismo cheque/////////////////
	$opcion_pago_matY=$_SESSION["FINANZAS"]["opcion_matricula"];
	$mostrar_opcion=false;
	if($opcion_pago_matY=="CHEQUE")
	{$mostrar_opcion=true;}
	//echo"--> $opcion_pago_matY -> $mostrar_opcion<br>";
	
	/////////////////////////////////
	$ańo_actual=date("Y");
if(isset($_SESSION["FINANZAS"]["paso3"]))
{
	if($_SESSION["FINANZAS"]["paso3"])
	{ $paso_3_OK=true;}
	else
	{ $paso_3_OK=false;}
}
else
{ $paso_3_OK=false;}

if($paso_3_OK)
{
	if(DEBUG)
	{
		echo"---->hay session <br>";
	}
	  $linea_credito_cantidad_cuotas=$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["cantidad_cuotas"];  
	  $linea_credito_mes_ini=$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["mes_ini_cuota"];
	  $linea_credito_dia_vencimiento=$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["dia_vence_cuota"];
	  
	  $linea_credito_year=$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["year"];
	  $linea_credito_meses_avance=$_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["meses_avance"];//agregado
	  //contado
	  $contado_descuento=$_SESSION["FINANZAS"]["METODO_PAGO"]["CONTADO"]["cantidad"];
	  $porcentaje_desc_contado=$_SESSION["FINANZAS"]["METODO_PAGO"]["CONTADO"]["descuento"];
	  //cheque
	  $cheque_banco=$_SESSION["FINANZAS"]["METODO_PAGO"]["CHEQUE"]["banco"];
	  $cheque_fecha_vence=$_SESSION["FINANZAS"]["METODO_PAGO"]["CHEQUE"]["fecha_vencimiento"];
	  $cheque_numero=$_SESSION["FINANZAS"]["METODO_PAGO"]["CHEQUE"]["numero"];
	  $cheque_matricula_arancel=$_SESSION["FINANZAS"]["METODO_PAGO"]["CHEQUE"]["matricula_arancel"];
	  $session_Z=true;
	 //echo"$cheque_matricula_arancel<br>";
}
else
{
	$linea_credito_meses_avance=1;//agregado
	$linea_credito_cantidad=$total;//////para que aparesca inicialmente
	$porcentaje_desc_contado=0;
	$session_Z=false;
	if(DEBUG){echo"---->NO hay session <br>";}
	if(isset($_SESSION["FINANZAS"]["year_estudio"])){$linea_credito_year=$_SESSION["FINANZAS"]["year_estudio"];}
	else{$linea_credito_year=$ańo_actual;}
}		
?>
<body onload="CARGA_INICIAL();">
<h1 id="banner">Contrato - Paso 3b/3</h1>
<div id="apDiv1">
  <table width="100%" border="0">
    <tr>
      <td colspan="2" bgcolor="#e5e5e5"><strong>>Informaci&oacute;n</strong></td>
    </tr>
    <tr>
      <td width="182">Arancel </td>
      <td width="215"><input name="textfield" type="text" id="textfield" value="<?php echo $arancel;?>"  readonly="readonly"/></td>
    </tr>
    <tr>
      <td>Saldo a Favor</td>
      <td><input name="saldo_a_favor" type="text" id="saldo_a_favor" value="<?php echo $saldo_a_favor;?>" readonly="readonly" /></td>
    </tr>
    <tr>
      <td>Otros Descuento</td>
      <td><input name="cantidad_beca" type="text" id="cantidad_beca" value="<?php echo $cantidad_beca;?>"  readonly="readonly"/></td>
    </tr>
    <tr>
      <td>% beca <span class="Estilo2">(aplica a Arancel)</span></td>
      <td><input name="textfield2" type="text" id="textfield2" value="<?php echo $porcentaje_beca;?>"  readonly="readonly"/></td>
    </tr>
    <tr>
      <td>Beca Nuevo Milenio</td>
      <td><label for="textfield3"></label>
      <input name="textfield3" type="text" id="textfield3"  value="<?php echo $aporte_beca_nuevo_milenio;?>" readonly="readonly"/></td>
    </tr>
    <tr>
      <td>Beca Excelencia Academica</td>
      <td><label for="textfield4"></label>
      <input name="textfield4" type="text" id="textfield4" value="<?php echo $aporte_beca_excelencia_academica?>" readonly="readonly"/></td>
    </tr>
    <tr>
      <td>Total</td>
      <td><input name="aux_total" type="text" id="aux_total" value="<?php echo $total;?>"  readonly="readonly"/> 
      <a href="#" onclick="xajax_ACTUALIZA_TOTAL(document.getElementById('aux_total').value);return false;" title="ACTUALIZAR CANTIDAD">Actualizar</a></td>
    </tr>
  </table>
</div>
<div id="div_pagos">
 <div class="Estilo1" id="pago_0"><?php echo $html_resumen;?></div>
  <div id="pago_1"></div>
   <div id="pago_2"></div>
    <div id="pago_3"></div>
</div>
<div id="apDiv3">
<form action="paso_3b_X.php" method="post" name="frm" id="frm">
  <div id="TabbedPanels1" class="TabbedPanels">
    <ul class="TabbedPanelsTabGroup">
      <li class="TabbedPanelsTab" tabindex="0">Linea Credito</li>
      <li class="TabbedPanelsTab" tabindex="1">Contado</li>
      <li class="TabbedPanelsTab" tabindex="2">Cheque</li>
    </ul>
    <div class="TabbedPanelsContentGroup">
      <div class="TabbedPanelsContent">
        <table width="100%" border="0">
          <tr>
            <td colspan="4" bgcolor="#e5e5e5"><input name="validador" type="hidden" id="validador" value="<?php echo md5("PASO3".date("Y-m-d"));?>" />
              <strong>>Linea Credito</strong></td>
          </tr>
          <tr>
            <td width="136">Cantidad</td>
            <td colspan="3"><input type="text" name="linea_credito_cantidad" id="linea_credito_cantidad"  value="<?php echo $linea_credito_cantidad;?>" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'), 'LINEA_CREDITO', document.getElementById('aux_total').value);return false;"/></td>
          </tr>
          <tr>
            <td>Numero de Cuotas</td>
            <td colspan="3"><select name="linea_credito_cantidad_cuotas" id="linea_credito_cantidad_cuotas"  onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'), 'LINEA_CREDITO', document.getElementById('aux_total').value);return false;">
              <?php
	  for($c=1;$c<=$max_num_cuotas;$c++)
	  {
	  	if(($session_Z)and($linea_credito_cantidad_cuotas==$c))
		{
			echo'<option value="'.$c.'" selected="selected">'.$c.'</option>';	
		}
		else
		{
	  		echo'<option value="'.$c.'">'.$c.'</option>';
		}	
	  }
	  ?>
                        </select></td>
          </tr>
          <tr>
            <td>Mes Inicio</td>
            <td width="117"><select name="linea_credito_mes_ini" id="linea_credito_mes_ini" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'), 'LINEA_CREDITO', document.getElementById('aux_total').value);return false;">
              <?php
	  		foreach($array_meses as $n => $valor)
			{
			  if($session_Z)
			  {
			  	if($n+1==$linea_credito_mes_ini)
				{
					echo'<option value="'.($n + 1).'" selected="selected">'.$valor.'</option>';
				}
				else
				{
					echo'<option value="'.($n + 1).'">'.$valor.'</option>';
				}
			  }
			  else
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
			}
	   ?>
                        </select></td>
            <td width="118">Meses Avance</td>
            <td width="97"><select name="meses_avance" id="meses_avance" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'), 'LINEA_CREDITO', document.getElementById('aux_total').value);return false;"> 
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
            </select>
            </td>
          </tr>
          <tr>
            <td>Dia Vencimiento</td>
            <td colspan="3"><select name="linea_credito_dia_vencimiento" id="linea_credito_dia_vencimiento" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'), 'LINEA_CREDITO', document.getElementById('aux_total').value);return false;">
              <?php
			  $array_dias_disponibles=array(5,15,30);
			  
	  foreach($array_dias_disponibles as $n => $valor)
	  {
	  	if(($session_Z)and($linea_credito_dia_vencimiento==$valor))
		{
			echo'<option value="'.$valor.'" selected="selected">'.$valor.'</option>';	
		}
	  	echo'<option value="'.$valor.'">'.$valor.'</option>';
	  }
	  ?>
            </select></td>
          </tr>
          <tr>
            <td><strong>A&ntilde;o</strong></td>
            <td colspan="3">
            <select name="linea_credito_year" id="linea_credito_year" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'), 'LINEA_CREDITO', document.getElementById('aux_total').value);return false;">
            <?php
				$ańo_ini=$ańo_actual-10;
				$ańo_fin=$ańo_actual+1;
				
            	for($a=$ańo_ini;$a<=$ańo_fin;$a++)
				{
					if($a==$linea_credito_year)
					{echo'<option value="'.$a.'" selected="selected">'.$a.'</option>';}
					else
					{echo'<option value="'.$a.'" >'.$a.'</option>';}
				}
			?>
            </select>            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td colspan="3">&nbsp;</td>
          </tr>
        </table>
        <div id="resultado_linea_credito">
          <div align="center"><a href="#" onclick="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'), 'LINEA_CREDITO', document.getElementById('aux_total').value);return false;">Actualizar</a></div>
        </div>
      </div>
      <div class="TabbedPanelsContent">
        <table width="100%" border="0">
          <tr>
            <td colspan="2" bgcolor="#e5e5e5"><strong>>Efectivo</strong></td>
          </tr>
          <tr>
            <td width="66">Cantidad</td>
            <td width="411"><input type="text" name="contado_cantidad" id="contado_cantidad" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'), 'CONTADO', document.getElementById('aux_total').value);return false;" value="<?php echo $contado_cantidad;?>"/></td>
          </tr>
          <tr>
            <td>Descuento</td>
            <td><input name="contado_descuento" type="text" id="contado_descuento" value="<?php echo $porcentaje_desc_contado;?>"  onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'), 'CONTADO', document.getElementById('aux_total').value);return false;"/></td>
          </tr>
          <tr>
            <td>Total</td>
            <td><div id="resultado_contado">0</div></td>
          </tr>
        </table>
      </div>
      <div class="TabbedPanelsContent">
        <table width="100%" border="0">
          <tr>
            <td colspan="3" bgcolor="#f5f5f5"><strong>>Cheque</strong></td>
          </tr>
          <tr>
            <td width="194">Cantidad</td>
            <td colspan="2"><input type="text" name="cheque_cantidad" id="cheque_cantidad"  value="<?php echo $cheque_cantidad;?>" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'), 'CHEQUE', document.getElementById('aux_total').value);return false;"/></td>
          </tr>
          <tr>
            <td>Banco</td>
            <td colspan="2"><select name="cheque_banco" id="cheque_banco" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'), 'CHEQUE', document.getElementById('aux_total').value);return false;">
              <?php 
		 foreach($array_bancos as $n)
		 {
			 if($session_Z)
			 {
				if(($n==$cheque_banco))
				{echo'<option value="'.$n.'" selected="selected">'.$n.'</option>';}
				else
				{echo'<option value="'.$n.'">'.$n.'</option>';}	
			 }
			 else
			 { echo'<option value="'.$n.'">'.$n.'</option>';}
		 }
		 ?>
            </select></td>
          </tr>
          <tr>
            <td>Fecha Vencimiento</td>
            <td colspan="2">
            <input  name="cheque_fecha_vence" id="cheque_fecha_vence" size="10" maxlength="10"
	   <?php
	    if($session_Z)
		{
			echo'value="'.$cheque_fecha_vence.'"';
		}
		?>
	    readonly="true"/>
              <input type="button" name="boton2" id="boton2" value="..." /></td>
          </tr>
          <tr>
            <td>N&deg; Cheque</td>
            <td colspan="2"><input type="text" name="cheque_numero" id="cheque_numero"  value="<?php if(isset($cheque_numero)){echo $cheque_numero;}?>" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'), 'CHEQUE', document.getElementById('aux_total').value);return false;"/></td>
          </tr>
          <?php if($mostrar_opcion){?>
          <tr>
            <td>Utilizar los mismo datos del cheque de Matricula<br />
(Si utiliza esta opcion el sistema Genera solo un cheque con los datos del documento ingresados en el paso 2 y por un valor de [matricula + cantidad ])</td>
            <td width="102"><input type="radio" name="cheque_matricula_arancel" id="radio" value="ON" 
            <?php
            if(($session_Z)and($cheque_matricula_arancel=="ON"))
			{
				echo'checked="checked"';
			}
			?>
            onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'), 'CHEQUE', document.getElementById('aux_total').value);return false;" />
              Si</td>
            <td width="176"><input name="cheque_matricula_arancel" type="radio" id="radio2" value="OFF" 
            <?php
				if($session_Z)
				{
					if($cheque_matricula_arancel=="OFF")
					{
						echo'checked="checked"';
					}
				}
				else
				{
					echo'checked="checked"';
				}	
            ?>
            onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'), 'CHEQUE', document.getElementById('aux_total').value);return false;" />
              No</td>
          </tr>
          <?php }?>
        </table>
      </div>
    </div>
  </div>
  <input name="btn_ant" type="button" value="<< Anterior"  onclick="Volver();"/>
  <div id="botonera">
  <?php
  if($total_sin_pactar==0)
  {
  	echo'<input name="btn" type="button" value="continuar >>" onclick="Verificar();"/>';
  }
  ?>
  </div>
  
  <div id="fin">  
    <div align="center">
      <?php
if($paso_3_OK)
{
	?>
        <a href="resumen.php" class="button">Volver al Resumen</a>
     <?php
}
@mysql_close($conexion);
$conexion_mysqli->close();
?>
    </div>
  </div>
</form>
</div>
<script type="text/javascript">
<!--
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
//-->
</script>
</body>
<script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
	   cal.manageFields("boton2", "cheque_fecha_vence", "%Y-%m-%d");

    //]]></script>
</html>