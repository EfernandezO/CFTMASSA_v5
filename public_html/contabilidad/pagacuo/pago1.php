<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumnos_Pago_Mensualidades_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	$aplicar_gastos_cobranza=true;
	$aplicar_interes_x_atraso=true;
//-------------------------------------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Pago de Letra</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:840px;
	height:346px;
	z-index:1;
	left: 57px;
	top: 74px;
}
.Estilo2 {color: #FF0000}
#Layer2 {
	position:absolute;
	width:135px;
	height:23px;
	z-index:2;
	left: 248px;
	top: 427px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
.Estilo4 {color: #0080C0}
#Layer3 {
	position:absolute;
	width:392px;
	height:54px;
	z-index:3;
	left: 78px;
	top: 18px;
}
.Estilo6 {font-size: 10px}
.Estilo7 {font-size: 12px}
-->
</style>
<script src="../../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../SpryAssets/SpryTabbedPanels.css">
 <script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
 <script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">
<script language="javascript">
function Verificar()
{
	continuar=true;
	opcion_pago=document.getElementById("opcion_pago").value;
	numero_cheque=document.getElementById("cheque_numero").value;
	fecha_vence=document.getElementById("cheque_fecha_vence").value;
	numeroComprobante=document.getElementById("deposito_numero").value;
	
	if(opcion_pago=="cheque")
	{
		if((numero_cheque=="")||(numero_cheque==" "))
		{
			continuar=false;
			alert('Falta numero de Cheque');
		}
		if((fecha_vence=="")||(fecha_vence==" "))
		{
			continuar=false;
			alert('Falta Fecha Vencimiento de Cheque');
		}
	}
	
	if(opcion_pago=="deposito"){
		if((numeroComprobante=="")||(numeroComprobante==" ")){
			continuar=false;
			alert('Falta numero de comprobante deposito/tranferencia');
		}
	}
	
	if(((numero_cheque!="")&&(numero_cheque!=" "))&&((fecha_vence!="")&&(fecha_vence!=" ")))
	{
		if(opcion_pago!="cheque")
		{
			ch=confirm('Ingreso los Datos de cheque, pero no selecciono este tipo de pago\n �Desea que se seleccione automaticamente ?');
			if(ch)
			{
				document.getElementById("opcion_pago").value="cheque";
			}
		}
	}
	
	if(continuar)
	{
		c=confirm('�Seguro(a) Desea Realizar este PAGO?');
		if(c)
		{
			document.frm.submit();
		}	
	}
}
</script>
<style type="text/css">
<!--
#link {	text-align: right;
	padding-right: 10px;
}
-->
</style>
</head>

<body>
<h1 id="banner">Finanzas - Pago Mensualidad Linea de Credito.</h1>
<div id="link"><br />
<a href="cuota1.php" class="button">Volver a Cuotas</a></div>
<?php
$ARRAY_CTA_CTE=array();
    if($_GET)
	{
		require("../../../funciones/conexion_v2.php");
		require("../../../funciones/funciones_sistema.php");
		
	    $id_cuota=base64_decode($_GET["id_cuota"]);
		$valor_letra=base64_decode($_GET["ocultoval"]);
		$deuda_actual=base64_decode($_GET["ocultodeuda_ac"]);
		$id_alumno=base64_decode($_GET["oculto_id_alumno"]);
		$semestre=base64_decode($_GET["semestre"]);
		$year=base64_decode($_GET["year"]);
		
		$fecha_actual=date("Y-m-d");
		
		$TOTAL_A_PAGAR=$deuda_actual;
		$glosa_pago="Pago total Cuota";
		
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
		//----------------------------------------------------//
		
		
		//echo"$numero_letra<br>";
		$array_bancos=array("Estado","Chile","Santander","Internacional","Scotiabank","Credito e Inversiones","Corpbanca","Bice","BBVA","HSBC Bank","Itau","Security","Falabella","Ripley","Consorcio","Penta","Paris");
 sort($array_bancos);
		//----------------------------------------//
		//INTERESES
		$total_interes_x_atraso=0;
		if(($aplicar_interes_x_atraso)and($aplicar_intereses_alumno))
		{
			if(DEBUG){ echo"Aplicar interes x atraso: si<br>";}
			$total_interes_x_atraso=INTERES_X_ATRASO_V2($id_cuota);
			$TOTAL_A_PAGAR+=$total_interes_x_atraso;
			$aplicar_intereses_final=true;
		}
		else
		{ $aplicar_intereses_final=false;}
		//GASTOS DE COBRANZA
		$total_gastos_cobranza=0;
		if(($aplicar_gastos_cobranza)and($aplicar_gastos_cobranza_alumno))
		{
			if(DEBUG){ echo"Aplicar Gastos cobranza: si<br>";}
			$total_gastos_cobranza=GASTOS_COBRANZA_V2($id_cuota);
			$TOTAL_A_PAGAR+=$total_gastos_cobranza;
			$aplicar_gastos_cobranza_final=true;
		}
		else{$aplicar_gastos_cobranza_final=false;}
		//--------------------------------------------------//
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
		?>
<div id="Layer1">
<form action="pago2.php" method="post" name="frm" id="frm">
  
  <table width="300" border="0">
  <tr>
    <td colspan="2" bgcolor="#e5e5e5"><strong>Pago de Mensualidad
      <input name="validador" type="hidden" id="validador" value="<?php echo md5("PAGO".date("Y-m-d"));?>" />
    </strong></td>
    </tr>
  <tr>
    <td><span class="Estilo7"><em>ID Cuota </em></span></td>
    <td><span class="Estilo7 Estilo7"><span class="Estilo2"><em><?php echo"$id_cuota";?></em></span></span></td>
  </tr>
  <tr>
    <td><span class="Estilo7 Estilo7"><em>Valor: </em></span></td>
    <td><span class="Estilo7 Estilo7"><em>$ <?php echo number_format($valor_letra,0,",",".");?></em></span></td>
  </tr>
  <tr>
    <td><span class="Estilo7 Estilo7"><em>Deuda Actual por Cuota : </em></span></td>
    <td><span class="Estilo7 Estilo7"><em>$ <?php echo number_format($deuda_actual,0,",",".");?></em></span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Intereses por atraso</td>
    <td>$<?php echo number_format($total_interes_x_atraso,0,",",".")?>
      <input name="aplica_intereses_x_atraso" type="hidden" id="aplica_intereses_x_atraso" value="<?php echo $aplicar_intereses_final;?>" />
      <input name="intereses_x_atraso" type="hidden" id="intereses_x_atraso" value="<?php echo $total_interes_x_atraso;?>" /></td>
  </tr>
  <tr>
    <td>Gastos de Cobranza</td>
    <td>$<?php echo number_format($total_gastos_cobranza,0,",",".")?>
      <input type="hidden" name="aplicar_gastos_cobranza" id="aplicar_gastos_cobranza" value="<?php echo $aplicar_gastos_cobranza_final;?>"/>
      <input name="gastos_cobranza" type="hidden" id="gastos_cobranza" value="<?php echo $total_gastos_cobranza;?>" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>TOTAL A PAGAR</strong></td>
    <td><strong>$<?php echo number_format($TOTAL_A_PAGAR,0,",",".");?></strong></td>
  </tr>
  <tr>
    <td>Semestre</td>
    <td><?php echo $semestre;?>
      <input type="hidden" name="semestre" id="semestre"  value="<?php echo $semestre;?>"/></td>
  </tr>
  <tr>
    <td>A&ntilde;o</td>
    <td><?php echo $year;?>
      <input type="hidden" name="year" id="year"  value="<?php echo $year;?>"/></td>
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
    </select>    </td>
  </tr>
  <tr>
    <td>Fecha de Pago</td>
    <td><input  name="fecha_pagoX" id="fecha_pagoX" size="10" maxlength="10" value="<?php echo $fecha_actual;?>" readonly="true"/>
      <input type="button" name="boton_P" id="boton_P" value="..." /></td>
  </tr>
</table>

  <div id="TabbedPanels1" class="TabbedPanels">
    <ul class="TabbedPanelsTabGroup">
      <li class="TabbedPanelsTab" tabindex="0">Efectivo</li>
      <li class="TabbedPanelsTab" tabindex="0">Cheque</li>
      <li class="TabbedPanelsTab" tabindex="0">Deposito/tranferencia</li>
    </ul>
    <div class="TabbedPanelsContentGroup">
      <div class="TabbedPanelsContent">
      <table summary="informacion">
      </span>
    <tr>
      <td height="56"><span class="Estilo7 Estilo7"><strong>Comentario</strong></span></td>
      <td width="167">        <span class="Estilo7 Estilo7">
          <textarea name="comentario" id="comentario" ><?php echo $glosa_pago;?></textarea>
          </span>      </td>
    </tr>
  </table>
      </div>
      <div class="TabbedPanelsContent">
        <table width="99%" height="103" border="0">
          <tr>
            <td colspan="2" bgcolor="#f5f5f5">&nbsp;</td>
          </tr>
          <tr>
            <td bgcolor="#f5f5f5">N&deg; Cheque</td>
            <td bgcolor="#f5f5f5"><input name="cheque_numero" type="text" id="cheque_numero" /></td>
          </tr>
          <tr>
            <td width="11%" bgcolor="#f5f5f5">Banco</td>
            <td width="21%" bgcolor="#f5f5f5"><select name="cheque_banco" id="cheque_banco">
              <?php 
		 foreach($array_bancos as $n)
		 {echo'<option value="'.$n.'">'.$n.'</option>';}
		 ?>
            </select></td>
          </tr>
          <tr>
            <td bgcolor="#f5f5f5">Fecha Vence</td>
            <td bgcolor="#f5f5f5"><input  name="cheque_fecha_vence" id="cheque_fecha_vence" size="10" maxlength="10"
	   <?php echo'value="'.date("Y-m-d").'"';?>
	    readonly="true"/>
              <input type="button" name="boton2" id="boton2" value="..." />
              <em>*Utilice solo Dias Habiles*</em></td>
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
    </div>
  </div>
  
  <div id="botones"><span class="Estilo6 Estilo7">
    </span>
    &iquest;Est&aacute; Seguro(a) que desea Pagar est&aacute; Cuota?</span></td>  
     <input type="checkbox" name="checkbox2" value="checkbox" onclick="document.frm.Submit.disabled=!document.frm.Submit.disabled" />
            Si. Seguro(a).
    <span class="Estilo6 Estilo7">
    <input name="id_cuota" type="hidden" id="id_cuota" value="<?php echo"$id_cuota";?>" />
    <input type="button" name="Submit" value="Pagar" disabled="disabled"  onclick="Verificar();"/>
&nbsp;
<input type="reset" name="Submit2" value="Borrar" />
<input name="oculto_valor" type="hidden" id="oculto_valor" value="<?php echo"$valor_letra";?>" />
<input name="ocultodeu_act" type="hidden" id="ocultodeu_act" value="<?php echo"$deuda_actual";?>" />
<input name="oculto_id_alumno" type="hidden" id="oculto_id_alumno" value="<?php echo"$id_alumno";?>" />
      </span></div>
</form>
</div>

<?php	
	}
?>
<script type="text/javascript">
<!--
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
//-->
</script>
<script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton2", "cheque_fecha_vence", "%Y-%m-%d");
	  cal.manageFields("boton_P", "fecha_pagoX", "%Y-%m-%d");

    //]]></script>
</body>
</html>