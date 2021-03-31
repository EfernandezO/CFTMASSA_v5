<?php 
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("gestion_facturas_v1.2");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cancelacion Facturas</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
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
#link {
	text-align: right;
	padding-right: 10px;
}
-->
</style>
<script src="../../../../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../../../SpryAssets/SpryTabbedPanels.css">
 <script src="../../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
 <script src="../../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/steel/steel.css">
<script language="javascript">
function Verificar()
{
	continuar=true;
	opcion_pago=document.getElementById("opcion_pago").value;
	numero_cheque=document.getElementById("cheque_numero").value;
	fecha_vence=document.getElementById("cheque_fecha_vence").value;
	
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
	if(continuar)
	{
		document.frm.submit();
	}
}
</script>
</head>

<body>
<h1 id="banner">Finanzas - Pago Facturas</h1>

<div id="link"><br />
<a href="../ver/ver_factura.php" class="button">Volver a Seleccion</a></div>
<?php

if($_POST)
{
	if(DEBUG){echo"Hay Datos POST<br>";}
	 $array_id_factura=$_POST["id_F"];
	 if(empty($array_id_factura))
	 { $continuar=false;}
	 else
	 { $continuar=true;}
}
elseif($_GET)
{
	if(DEBUG){echo"Hay Datos GET<br>";}
	if(isset($_GET["id_factura"]))
	{
		if(DEBUG){echo"--> Variable id_factura via GET Recibida<br>";}
		$id_factura=$_GET["id_factura"];
		if(is_numeric($id_factura))
		{
			if(DEBUG){echo"Variable id_factura Valida, Almacenar<br>";}
			$array_id_factura=array($id_factura);
			$continuar=true;
		}
	}
}
else
{ $continuar=false;}


	if(DEBUG){ var_dump($array_id_factura);}
    if($continuar)
	{
	  
		///////
		$fecha_actual=date("Y-m-d");
		//echo"$numero_letra<br>";
		$array_bancos=array("Estado","Chile","Santander","Internacional","Scotiabank","Credito e Inversiones","Corpbanca","Bice","BBVA","HSBC Bank","Itau","Security","Falabella","Ripley","Consorcio","Penta","Paris");
 sort($array_bancos);
 
 		require("../../../../../funciones/conexion_v2.php");
			$aux=true;
			$contador_facturas=0;
			foreach($array_id_factura as $nx => $valorx)
			{
				$contador_facturas++;
				if($aux)
				{ 
					$concatena_id_F="'$valorx'";
					$concatena_id_F2="$valorx";
					$aux=false;
				}
				else
				{ 
					$concatena_id_F.=", '$valorx'";
					$concatena_id_F2.=",$valorx";
				}
				
				
			}
			$cons_F="SELECT valor, movimiento FROM facturas WHERE id IN($concatena_id_F)";
			if(DEBUG){echo"-> $cons_F<br>";}
			$sql_F=mysql_query($cons_F)or die("obteniendo valor".mysql_error());
			
			$primera_vuelta=true;
			$continuar_M=true;
			while($DF=mysql_fetch_assoc($sql_F))
			{
				$TOTAL_FACTURAS+=$DF["valor"];
				$movimiento=$DF["movimiento"];
				if($primera_vuelta)
				{
					$primera_vuelta=false;
					$movimiento_old=$movimiento;
				}
				
				if($movimiento!=$movimiento_old)
				{
					$continuar_M=false;
				}
				$movimiento_old=$movimiento;
			}
			if(empty($TOTAL_FACTURAS))
			{ $TOTAL_FACTURAS=0;}
			
		mysql_close($conexion);
?>
<div id="Layer1">
<form <?php if($continuar_M){?>action="multi_pago_fact_2.php" <?php }?> method="post" name="frm" id="frm">
  
  <table width="478" border="0">
  <tr>
    <td colspan="2" bgcolor="#e5e5e5"><strong>Pago Facturas
        <input name="validador" type="hidden" id="validador" value="<?php echo md5("P_facturas".date("Y-m-d"));?>" />
    </strong></td>
    </tr>
  <tr>
    <td width="175"><span class="Estilo7"><em>ID factura(s)</em></span></td>
    <td width="293"><span class="Estilo7 Estilo7"><span class="Estilo2"><em><?php echo str_replace("'","",$concatena_id_F);?></em></span></span></td>
  </tr>
  <tr>
    <td><span class="Estilo7 Estilo7"><em>Valor: </em></span></td>
    <td><span class="Estilo7 Estilo7"><em>$ <?php echo number_format($TOTAL_FACTURAS,0,",",".");?>
      <input name="valor_facturas" type="hidden" id="valor_facturas" value="<?php echo $TOTAL_FACTURAS;?>" />
    </em></span></td>
  </tr>
  <tr>
    <td><span class="Estilo7 Estilo7"><em>N&deg; Facturas Seleccionadas </em></span></td>
    <td><span class="Estilo7 Estilo7"><em><?php echo $contador_facturas;?></em></span></td>
  </tr>
  <tr>
    <td>Movimiento</td>
    <td>
	<?php
	 if($continuar_M)
	 {
		 echo $movimiento;
		 echo'<input type="hidden" name="movimiento" id="movimiento"  value="'.$movimiento.'"/>';
	 }
	 else
	 {
		 echo"<strong>Distintos tipos de Movimiento, Imposible Continuar...!!!</strong>";
	 }
	 ?>
     </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Opcion de Pago</td>
    <td><select name="opcion_pago" id="opcion_pago">
      <option value="cheque" selected="selected">Cheque</option>
      <option value="efectivo">Efectivo</option>
    </select>    </td>
  </tr>
  <tr>
    <td>Fecha Pago</td>
    <td><input  name="fecha_pagoX" id="fecha_pagoX" size="10" maxlength="10" value="<?php echo $fecha_actual;?>" readonly="true"/>
      <input type="button" name="boton_P" id="boton_P" value="..." /></td>
  </tr>
  </table>

  <div id="TabbedPanels1" class="TabbedPanels">
    <ul class="TabbedPanelsTabGroup">
      <li class="TabbedPanelsTab" tabindex="0">Efectivo</li>
      <li class="TabbedPanelsTab" tabindex="0">Cheque</li>
    </ul>
    <div class="TabbedPanelsContentGroup">
      <div class="TabbedPanelsContent">
      <table summary="informacion">
    <tr>
      <td height="56"><span class="Estilo7"><strong>Comentario</strong></span></td>
      <td width="167">        <span class="Estilo7 Estilo7">
          <textarea name="comentario" id="comentario" >pago de facturas
</textarea>
          </span>      </td>
    </tr>
  </table>
      </div>
      <div class="TabbedPanelsContent">
        <table width="99%" height="180" border="0">
          <tr>
            <td colspan="2" bgcolor="#f5f5f5">&nbsp;</td>
          </tr>
          <tr>
            <td height="21" bgcolor="#f5f5f5">Sede</td>
            <td bgcolor="#f5f5f5"><?php
	  include("../../../../../funciones/funcion.php");
	  echo selector_sede("sede_cheque"); 
	  ?></td>
          </tr>
          <tr>
            <td height="56" bgcolor="#f5f5f5"><span class="Estilo7"><strong>Comentario</strong></span></td>
            <td bgcolor="#f5f5f5"><span class="Estilo7 Estilo7">
              <textarea name="comentario2" id="comentario2" >pago de facturas</textarea>
            </span> </td>
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
		 {
		 	if($n==$banco_cheque)
			{
		 		echo'<option value="'.$n.'" selected="selected">'.$n.'</option>';
			}
			else
			{
				echo'<option value="'.$n.'">'.$n.'</option>';
			}	
		 }
		 ?>
            </select></td>
          </tr>
          <tr>
            <td bgcolor="#f5f5f5">Fecha Vence</td>
            <td bgcolor="#f5f5f5"><input  name="cheque_fecha_vence" id="cheque_fecha_vence" size="10" maxlength="10"
	   <?php
	    if(($session_Y)and($opcion_marcada=="CHEQUE"))
		{
			echo'value="'.$fecha_vence_cheque.'"';
		}
		?>
	    readonly="true"/>
                <input type="button" name="boton2" id="boton2" value="..." />
              <em>*Utilice solo Dias Habiles*</em></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
  
  <div id="botones"><span class="Estilo6 Estilo7">
    </span>
    &iquest;Est&aacute; Seguro(a) que desea cancelar o dar por pagada esta(s) Factura(s)?</span></td>  
     <input type="checkbox" name="checkbox2" value="checkbox" onclick="document.frm.Submit.disabled=!document.frm.Submit.disabled" />
            Si. Seguro(a).
    <span class="Estilo6 Estilo7">
    <input name="id_factura" type="hidden" id="id_factura" value="<?php echo $concatena_id_F2;?>" />
    <input type="button" name="Submit" value="Cancelar Facturas" disabled="disabled"  onclick="Verificar();"/>
&nbsp;
<input type="reset" name="Submit2" value="Borrar" />
    </span></div>
</form>
</div>

<?php	
	}
	else
	{ echo"SELECCIONE FACTURAS Y VUELVA A INTENTARLO<br>";}
?>
<script type="text/javascript">
<!--
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1", {defaultTab:1});
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