<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Alumnos_Pago_Mensualidades_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
 //////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("multi_pago_server.php");
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"MULTI_CHEQUE");
//////////DEBUG////////////////


$aplica_gastos_cobranza=true;
$aplicar_intereses=true;

if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
{
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
	{ $continuar_1=true;}
	else
	{ $continuar_1=false;}
}
else
{ $continuar_1=false;}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Multi pagos</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<script src="../../../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../../SpryAssets/SpryTabbedPanels.css">
 <script src="../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
 <script src="../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/steel/steel.css">
<script language="javascript">
function VERIFICAR(TOTAL_A_PAGAR)
{
	continuar=true;
	opcion_pago=document.getElementById("opcion_pago").value;
	cantidad_cheques=document.getElementById("cantidad_cheques").value;
	acumula_valor_cheques=0;
	deuda_actual=Math.abs(document.getElementById('ocultodeu_act').value);
	cantidad_a_pagar=Math.abs(document.getElementById('cantidad_pagar').value);
	
	//alert('deuda'+deuda_actual);
	
	if(cantidad_a_pagar>deuda_actual)
	{
		continuar=true;
		alert("Valor a Pagar Excede la Deuda...");
	}
	
	if(opcion_pago=="cheque")
	{
		for(x=1;x<=cantidad_cheques;x++)
		{
			aux='cheque_numero_'+x;
			aux_2='cheque_valor_'+x;
			
			string="document.getElementById('"+aux+"').value;";
			string_2="document.getElementById('"+aux_2+"').value;";
			aux_numero_cheque=eval(string);
			aux_valor_cheque=eval(string_2);
			//alert(aux_numero_cheque);
			//alert(aux_valor_cheque);
			
			if((aux_numero_cheque=="")||(aux_numero_cheque==" "))
			{
				continuar=false;
				alert('Falta numero de Cheque->'+x);
			}
			if((aux_valor_cheque=="")||(aux_valor_cheque==" "))
			{
				continuar=false;
				alert('Falta Valor de Cheque->'+x);
			}
			acumula_valor_cheques=(acumula_valor_cheques+parseInt(aux_valor_cheque));
			
		}
		//alert('T->'+acumula_valor_cheques);
		if(acumula_valor_cheques<TOTAL_A_PAGAR)
		{
			continuar=false;
			alert('La Suma de los Cheques No Iguala la Deuda...');
		}
	}
	
	
	if(continuar)
	{
		c=confirm('żSeguro(a) Desea Realizar este Pago multiple...?\n FORMA DE PAGO :'+opcion_pago);
		if(c)
		{
			document.frm.submit();
		}	
	}
}
function ACTUALIZA_VALOR(valor_new)
{
	cantidad_cheques=document.getElementById("cantidad_cheques").value;
	if(cantidad_cheques==1)
	{
		document.getElementById('cheque_valor_1').value=valor_new;
	}
	else
	{ alert("cambio la cantidad a Pagar verifique valores de cheques...");}	
}
</script>
<style type="text/css">
<!--
#link {	text-align: right;
	padding-right: 10px;
}
-->
</style>

<style type="text/css">
<!--
.Estilo2 {color: #FF0000}
.Estilo6 {font-size: 10px}
.Estilo7 {font-size: 12px}
#Layer1 {	position:absolute;
	width:840px;
	height:346px;
	z-index:1;
	left: 57px;
	top: 74px;
}
#apDiv1 {
	position:absolute;
	width:350px;
	height:24px;
	z-index:2;
	left: 490px;
	top: 28px;
}
-->
</style>
<?php $xajax->printJavascript(); ?> 
</head>
<?php
if(($_POST)and($continuar_1))
{
	
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$fecha_actual=date("Y-m-d");
	/////////////
	$array_bancos=array("Estado","Chile","Santander","Internacional","Scotiabank","Credito e Inversiones","Corpbanca","Bice","BBVA","HSBC Bank","Itau","Security","Falabella","Ripley","Consorcio","Penta","Paris");
 sort($array_bancos);
	///////////////
	$id_cuotas_checkbox=$_POST["id_cuota"];
	$aux=true;
	$maxima_cantidad_cheques=6;
	
	if(!empty($id_cuotas_checkbox))
	{
		$hay_cuotas=true;
		foreach($id_cuotas_checkbox as $n => $valor)
		{
			if(DEBUG){echo"$n -> $valor <br>";}
			if($aux)
			{
				$concatena_id_cuota=$valor;
				$aux=false;
			}
			else
			{ $concatena_id_cuota.=", $valor";}
		}
	}
	else
	{
		$hay_cuotas=false;
		if(DEBUG){echo"Sin Cuotas...<br>";}
	}
	/////////////////////////
	if($hay_cuotas)
	{
		require("../../../../funciones/conexion_v2.php");
		require("../../../../funciones/funciones_sistema.php");
		
		//si aplico interes a alumno
		$cons="SELECT aplicar_intereses, aplicar_gastos_cobranza FROM alumno WHERE id='$id_alumno' LIMIT 1";
		$sqli=$conexion_mysqli->query($cons);
		$A=$sqli->fetch_assoc();
		$aplicar_intereses_alumno=$A["aplicar_intereses"];
		$aplicar_gastos_cobranza_alumno=$A["aplicar_gastos_cobranza"];
		
		if($aplicar_intereses_alumno==1){$aplicar_intereses_alumno=true; if(DEBUG){ echo"Aplica Interes Alumno<br>";}}
		else{$aplicar_intereses_alumno=false; if(DEBUG){ echo"NO Aplica Interes Alumno<br>";}}
		
		if($aplicar_gastos_cobranza_alumno==1){$aplicar_gastos_cobranza_alumno=true; if(DEBUG){ echo"Aplica Gastos Cobranza Alumno<br>";}}
		else{$aplicar_gastos_cobranza_alumno=false; if(DEBUG){ echo"NO Aplica Gastos Cobranza Alumno<br>";}}
		$sqli->free();
		
		$cons_cuo="SELECT * FROM letras WHERE id IN($concatena_id_cuota)";
		$sql_cuo=$conexion_mysqli->query($cons_cuo)or die($conexion_mysqli->error);
		$cuenta_cuotas=0;
		$acumula_valor=0;
		$acumula_deuda=0;
		$TOTAL_A_PAGAR=0;
		$gastos_cobranza=0;
		$intereses_x_atraso=0;
		while($DC=$sql_cuo->fetch_assoc())
		{
			$cuenta_cuotas++;
			$aux_id_cuota=$DC["id"];
			$valor=$DC["valor"];
			$deudaXcuota=$DC["deudaXletra"];
			$id_alumno=$DC["idalumn"];
	
			if(($aplica_gastos_cobranza)and($aplicar_gastos_cobranza_alumno))
			{ $aux_gastos_cobranza=GASTOS_COBRANZA_V2($aux_id_cuota);$aplicar_gastos_cobranza_final=true; if(DEBUG){ echo" -->Aplicar Gastos Cobranza<br>";}}
			else{$aplicar_gastos_cobranza_final=false; $aux_gastos_cobranza=0;}
			
			if(($aplicar_intereses)and($aplicar_intereses_alumno))
			{$aux_intereses_x_atraso=INTERES_X_ATRASO_V2($aux_id_cuota);  $aplicar_intereses_final=true; if(DEBUG){ echo" -->Aplicar interese x Atraso<br>";}}
			else
			{$aplicar_intereses_final=false; $aux_intereses_x_atraso=0;}
			
			
			$acumula_valor+=$valor;
			$acumula_deuda+=$deudaXcuota;
			
			$TOTAL_A_PAGAR+=$deudaXcuota;
			$gastos_cobranza+=$aux_gastos_cobranza;
			$intereses_x_atraso+=$aux_intereses_x_atraso;
			
			if(DEBUG){ echo"$cuenta_cuotas DeudaXcuota: $deudaXcuota Intereses: $aux_intereses_x_atraso Cobranza: $aux_gastos_cobranza<br>";}
		}
		
		if(($aplica_gastos_cobranza)and($aplicar_gastos_cobranza_alumno)){ $TOTAL_A_PAGAR+=$gastos_cobranza;}
		if(($aplicar_intereses)and($aplicar_intereses_alumno)){ $TOTAL_A_PAGAR+=$intereses_x_atraso;}	
		$sql_cuo->free();	
		
		if(DEBUG){ echo"---->TOTAL a PAGAR: $TOTAL_A_PAGAR<br>";}
		
		//busca cta cte para deposito
	
	$cons_cta="SELECT * FROM cuenta_corriente ORDER by id desc";
	$sql_cta=$conexion_mysqli->query($cons_cta);

	$num_cta=$sql_cta->num_rows;
	if($num_cta>0)
	{
		while($CTA=$sql_cta->fetch_assoc())
		{
			$C_id=$CTA["id"];
			$C_titular=$CTA["titular"];
			$C_banco=$CTA["banco"];
			$C_num_cuenta=$CTA["num_cuenta"];
			
			$ARRAY_CTA_CTE[$C_id]=$C_banco." ".$C_num_cuenta;
		}
	}
	
	$sql_cta->free();
	$conexion_mysqli->close();
	}
}
else
{
	if(DEBUG){echo"<strong>Sin Datos...XD</strong>";}
	$hay_cuotas=false;
}
?>
<body>
<h1 id="banner">Finanzas - Multi-Pago Mensualidad Linea de Credito</h1>
<div id="link"><br />
<a href="../cuota1.php" class="button">Volver a Cuotas</a></div>
<div id="Layer1">
<?php if($hay_cuotas){?>
  <form action="multi_pago2.php" method="post" name="frm" id="frm">
    <table width="406" border="0">
      <tr>
        <td colspan="2" bgcolor="#e5e5e5"><strong>Pago de Mensualidad
          <input name="validador" type="hidden" id="validador" value="<?php echo md5("MPAGO".date("Y-m-d"));?>" />
          <input name="id_alumno" type="hidden" id="id_alumno" value="<?php echo $id_alumno;?>" />
        </strong></td>
      </tr>
      <tr>
        <td><span class="Estilo7"><em>ID Cuota(s) </em></span></td>
        <td><span class="Estilo7 Estilo7"><span class="Estilo2"><em><?php echo $concatena_id_cuota;?>
          <input name="id_cuota" type="hidden" id="id_cuota" value="<?php echo str_replace(" ","",$concatena_id_cuota);?>" />
        </em></span></span></td>
      </tr>
      <tr>
        <td><span class="Estilo7 Estilo7"><em>Valor Total: </em></span></td>
        <td><span class="Estilo7 Estilo7"><em>$ <?php echo number_format($acumula_valor,0,",",".");?></em></span></td>
      </tr>
      <tr>
        <td><span class="Estilo7 Estilo7"><em>Deuda Actual por Cuotas : </em></span></td>
        <td><span class="Estilo7 Estilo7"><em>$ <?php echo number_format($acumula_deuda,0,",",".");?></em></span></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Intereses por atraso</td>
        <td>$<?php echo number_format($intereses_x_atraso,0,",",".");?>
          <input name="interes_x_atraso" type="hidden" id="interes_x_atraso" value="<?php echo $intereses_x_atraso;?>" />
          <input type="hidden" name="aplicar_interes_x_atraso" id="aplicar_interes_x_atraso"  value="<?php echo $aplicar_intereses_final;?>"/></td>
      </tr>
      <tr>
        <td>Gastos Cobranza</td>
        <td>$<?php echo number_format($gastos_cobranza,0,",",".");?>
          <input name="gastos_cobranza" type="hidden" id="gastos_cobranza" value="<?php echo $gastos_cobranza;?>" />
          <input name="aplicar_gastos_cobranza" type="hidden" id="aplicar_gastos_cobranza" value="<?php echo $aplicar_gastos_cobranza_final;?>" /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Total a Pagar</td>
        <td><?php echo $TOTAL_A_PAGAR;?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Cantidad a Pagar</td>
        <td>$
        <input name="cantidad_pagar" type="text" id="cantidad_pagar"  value="<?php echo $TOTAL_A_PAGAR;?>" size="8" onchange="ACTUALIZA_VALOR(this.value);"/></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Opcion de Pago</td>
        <td><select name="opcion_pago" id="opcion_pago">
          <option value="cheque">Cheque</option>
          <option value="efectivo" selected="selected">Efectivo</option>
          <option value="deposito">deposito</option>
        </select>        </td>
      </tr>
      <tr>
        <td>Fecha Pago</td>
        <td><input  name="fecha_pagoX" id="fecha_pagoX" size="10" maxlength="10" value="<?php echo $fecha_actual;?>" readonly="true"/>
          <input type="button" name="boton_P" id="boton_P" value="..." /></td>
      </tr>
    </table>
    <div id="apDiv1">
      <div align="right"><em><strong>Nota</strong>:*Utilice solo para el pago de varias cuotas a la vez*</em></div>
    </div>
    <div id="TabbedPanels1" class="TabbedPanels">
      <ul class="TabbedPanelsTabGroup">
        <li class="TabbedPanelsTab" tabindex="0">Efectivo</li>
        <li class="TabbedPanelsTab" tabindex="0">Deposito</li>
        <li class="TabbedPanelsTab" tabindex="1">Cheque</li>
      </ul>
      <div class="TabbedPanelsContentGroup">
        <div class="TabbedPanelsContent">
          <table summary="informacion">
            <tr>
              <td></td>
            </tr>
            <tr>
              <td height="56"><span class="Estilo7 Estilo7"><strong>Comentario</strong></span></td>
              <td width="167"><span class="Estilo7 Estilo7">
                <textarea name="comentario" id="comentario" >Pago Total de Cuota
                </textarea>
              </span></td>
            </tr>
          </table>
        </div>
        <div class="TabbedPanelsContent"><table width="100%" border="0">
          <tr>
            <td colspan="2">Deposito/transferencia</td>
            </tr>
          <tr>
            <td>Numero Comprobante</td>
            <td><input name="deposito_numero" type="text" id="deposito_numero" size="15" /></td>
          </tr>
          <tr>
            <td>Cta Cte.</td>
            <td><select name="id_cta_cte" id="id_cta_cte" >
              <?php 
		if(count($ARRAY_CTA_CTE)>0)
		{
		 foreach($ARRAY_CTA_CTE as $ncta =>$valorcta)
		 {echo'<option value="'.$ncta.'">'.$valorcta.'</option>'; }
		}
		else{ echo'<option value="0">Sin cta cte Registradas...</option>';}
		 ?>
            </select></td>
          </tr>
        </table></div>
        <div class="TabbedPanelsContent">
          <table width="100%">
          <tr>
          	<td width="45%">Cantidad de Cheques</td>
            <td width="55%">
                <select name="cantidad_cheques" id="cantidad_cheques" onchange="xajax_MULTI_CHEQUE(this.value, document.getElementById('cantidad_pagar').value);return false;">
                	<?php
						for($ch=1;$ch<=$maxima_cantidad_cheques;$ch++)
						{
							echo'<option value="'.$ch.'">'.$ch.'</option>';
						}
                    ?>
                </select>
            </td>
          </tr>
          <tr>
          	<td colspan="2">
            	<div id="div_cheque">
               	  <table width="100%" height="103" border="0">
            <tr>
              <td colspan="2" bgcolor="#f5f5f5">&nbsp;</td>
            </tr>
            <tr>
              <td bgcolor="#f5f5f5">N&deg; Cheque</td>
              <td bgcolor="#f5f5f5"><input name="cheque_numero[]" type="text" id="cheque_numero_1" /></td>
            </tr>
            <tr>
              <td width="11%" bgcolor="#f5f5f5">Banco</td>
              <td width="21%" bgcolor="#f5f5f5"><select name="cheque_banco[]" id="cheque_banco_1">
                <?php 
		 foreach($array_bancos as $n)
		 {
			 echo'<option value="'.$n.'">'.$n.'</option>';	
		 }
		 ?>
              </select></td>
            </tr>
            <tr>
              <td bgcolor="#f5f5f5">Fecha Vence</td>
              <td bgcolor="#f5f5f5">
                <select name="dia[]" id="dia_1">
                	<?php
						$dia_actual=date("d");
						for($d=1;$d<=30;$d++)
						{
							if($d==$dia_actual)
							{ echo'<option value="'.$d.'" selected="selected">'.$d.'</option>';}
							else
							{ echo'<option value="'.$d.'">'.$d.'</option>';}
						}
                    ?>
                </select>
                /
                <select name="mes[]" id="mes_1">
                	<?php
						$mes_actual=date("m");
						for($m=1;$m<=12;$m++)
						{
							if($m==$mes_actual)
							{ echo'<option value="'.$m.'" selected="selected">'.$m.'</option>';}
							else
							{ echo'<option value="'.$m.'">'.$m.'</option>';}
						}
                    ?>
                </select>
                /
                <select name="year[]" id="year_1">
                <?php
						$year_actual=date("Y");
						for($y=2010;$y<=2050;$y++)
						{
							if($y==$year_actual)
							{ echo'<option value="'.$y.'" selected="selected">'.$y.'</option>';}
							else
							{ echo'<option value="'.$y.'">'.$y.'</option>';}
						}
                    ?>
                </select>
                <em>
                *Utilice solo Dias Habiles*</em></td>
            </tr>
            <tr>
              <td bgcolor="#f5f5f5">Valor</td>
              <td bgcolor="#f5f5f5"><input name="cheque_valor[]" type="text" id="cheque_valor_1"  value="<?php echo $TOTAL_A_PAGAR;?>" size="10" readonly="readonly"/></td>
            </tr>
          </table>
                </div>            
             </td>
          </tr>
          </table>
          
        </div>
      </div>
    </div>
<div id="botones"><span class="Estilo6 Estilo7"> </span> &iquest;Est&aacute; Seguro(a) que desea Pagar est&aacute;(s) Cuota(s)?</span>
        </td>
        <?php if($hay_cuotas){?>
        <input type="checkbox" name="checkbox2" value="checkbox" onclick="document.frm.Submit.disabled=!document.frm.Submit.disabled" />
      Si. Seguro(a). <span class="Estilo6 Estilo7">
    <input type="button" name="Submit" value="Pagar" disabled="disabled"  onclick="VERIFICAR(document.getElementById('cantidad_pagar').value);"/>
    <?php }?>
        &nbsp;
        <input type="reset" name="Submit2" value="Borrar" />
        <input name="oculto_valor" type="hidden" id="oculto_valor" value="<?php echo"$acumula_valor";?>" />
        <input name="ocultodeu_act" type="hidden" id="ocultodeu_act" value="<?php echo"$TOTAL_A_PAGAR";?>" />
      </span></div>
  </form>
<?php }else{ echo"Sin Cuotas Seleccionadas";} ?>  
</div>
<script type="text/javascript">
<!--
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
//-->
</script>
<div id="script_calendar">
<script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
	  
	  cal.manageFields("boton_P", "fecha_pagoX", "%Y-%m-%d"); 

    //]]></script>
 </div>   
</body>
</html>
