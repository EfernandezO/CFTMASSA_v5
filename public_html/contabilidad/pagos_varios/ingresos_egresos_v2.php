<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
//-----------------------------------------//	
 //////////////////////XAJAX/////////////////

require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("ingreso_egreso_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CARGA_CONTENIDO");
$xajax->register(XAJAX_FUNCTION,"CARGA_MOVIMIENTO");
$xajax->register(XAJAX_FUNCTION,"CARGA_X_CONCEPTO");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Formulario de  Pagos</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">

<script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:596px;
	height:279px;
	z-index:3;
	left: 23px;
	top: 82px;
}
#Layer2 {
	position:absolute;
	width:393px;
	height:276px;
	z-index:2;
	left: 35px;
	top: 96px;
}
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
#Layer3 {
	position:absolute;
	width:175px;
	height:10px;
	z-index:4;
	left: 620px;
	top: 177px;
}
a:link {
	text-decoration: none;
	color: #6699CC;
}
a:visited {
	text-decoration: none;
	color: #6699CC;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #6699CC;
}
.Estilo2 {color: #0080C0}
#Layer4 {
	position:absolute;
	width:207px;
	height:39px;
	z-index:5;
	left: 169px;
	top: 76px;
}
#Layer5 {
	position:absolute;
	width:150px;
	height:17px;
	z-index:6;
	left: 517px;
	top: 54px;
}
.Estilo3 {font-size: 12px}
#Layer6 {
	position:absolute;
	width:200px;
	height:98px;
	z-index:7;
	left: 425px;
	top: 120px;
}
#registros_anteriores {
	position:absolute;
	width:833px;
	height:48px;
	z-index:7;
	left: 19px;
	top: 523px;
}
.Estilo5 {font-size: 16px}
#msj {
	position:absolute;
	width:649px;
	height:26px;
	z-index:8;
	left: 20px;
	top: 492px;
}
#link {
	text-align: right;
}
#link {
	padding-right: 10px;
}
-->
</style>
<?php $xajax->printJavascript(); ?> 
<script language="javascript">
function Confirmar()
{
	continuar=true;
	
	valor=document.getElementById('fvalor').value;
	glosa=document.getElementById('fglosa').value;
	num_doc=document.getElementById('fnum_doc').value;
	tipo_documento=document.getElementById('ftipo_doc').value;
	
	if(valor=="")
	{
		continuar=false;
		alert('Ingrese Valor');
	}
	if(glosa=="")
	{
		continuar=false;
		alert('Ingrese Glosa');
	}
	if((num_doc=="")||(num_doc=="0"))
	{
		continuar=false;
		alert('ingrese Numero de Documento');
	}
	if(tipo_documento=="SS")
	{
		continuar=false;
		alert('Seleccine tipo de Documento que paga');
	}
	if(continuar)
	{
		c=confirm('Seguro desea Ingresar este registro');
		if(c)
		{
			document.frm.submit();
		}
	}	
}
</script>
<?php
$array_bancos=array("Estado","Chile","Santander","Internacional","Scotiabank","Credito e Inversiones","Corpbanca","Bice","HSBC Bank","Itau","Security","Falabella","Ripley","Consorcio","Penta","Paris", "BBVA");
 sort($array_bancos);
?>
</head>
<body onload="xajax_CARGA_MOVIMIENTO(document.getElementById('fecha_movimiento').value, document.getElementById('fsede').value);return false">
<h1 id="banner">Administrador- Registro de Ingresos/Egresos </h1>

<div id="link"><a href="../index.php" class="Estilo2">Volver al Menu de Finanzas</a></div>
<?php if($_SESSION["privilegio"]=="admi_total"){?>
<div class="Estilo3" id="Layer5">
  <div align="right"><a href="borra_pago.php">Eliminar Pago</a> <br />
    <a href="categoria_select/nva_categoria/nvo_categoria.php">nvo. tipo documento</a></div>
</div>
<?php }?>
<div id="Layer1">
<form action="pago_vario2.php" method="post" name="frm" id="frm" >
  <table width="646" height="328" border="0">
    <tr>
      <td colspan="5" bgcolor="#EBE5D9"><div align="center" class="Estilo1 Estilo5">Formulario Ingreso - Egreso </div></td>
    </tr>
    <tr bgcolor="#F7F4EE">
      <td width="145" height="35"><strong>Fecha</strong></td>
      <td colspan="4"> &nbsp;&nbsp;
	  <?php
	  $fecha_actual=date("Y-m-d");
	  if($_GET)
	  { $ultima_fecha=$_GET["ultima_fecha"];}
	  else
	  { $ultima_fecha=$fecha_actual;}
	  	
	  ?>
        <input name="fecha_movimiento" type="text" id="fecha_movimiento" size="11" maxlength="10" value="<?php echo"$ultima_fecha";?>" onchange="cargarMovimientos();" readonly="true"/>
		
<input type="button" name="boton" id="boton" value="..."/>
<input type="button" name="Submit3" value="-&gt;"   title="Previsualizar" onclick="xajax_CARGA_MOVIMIENTO(document.getElementById('fecha_movimiento').value, document.getElementById('fsede').value);return false"/></td>
    </tr>
	<tr bgcolor="#F7F4EE">
	<td><strong>Valor</strong></td>
	<td colspan="4">
	  $
	  <input name="fvalor" type="text" id="fvalor" size="11" maxlength="10"/></td>
	</tr>
	<tr bgcolor="#F7F4EE">
	<td><strong>N&deg; de documento </strong></td>
	<td colspan="4">
	   &nbsp;&nbsp;
	   <input name="fnum_doc" type="text" id="fnum_doc" size="11" maxlength="10" />	</td>
	</tr>
    <tr bgcolor="#F7F4EE">
      <td height="37"><strong>Tipo de Movimiento</strong></td>
      <td colspan="4">
        &nbsp;&nbsp;
        <select name="ftipo_mov" id="ftipo_mov" onchange="xajax_CARGA_CONTENIDO(this.value, 'contiene_select');return false;" >
          <option value="I">Ingreso</option>
         <!-- <option value="E" selected="selected">Egreso</option>-->
        </select>     </td>
    </tr>
	<tr bgcolor="#F7F4EE">
	<td><strong> Tipo de Documento </strong></td>
	<td colspan="4"><div id="contiene_select">
	  &nbsp;&nbsp;
	  <select name="ftipo_doc" id="ftipo_doc" onchange="xajax_CARGA_X_CONCEPTO(this.value, document.getElementById('ftipo_mov').value); return false">
     	<option value="SS">Seleccione</option>
        <?php
		 	 include("../../../funciones/conexion.php");
		  	$cons="SELECT contenido FROM parametros WHERE seccion='finanzas' AND tipo='I'";
			//echo"---> $cons<br>";
			$sql=mysql_query($cons)or die(mysql_error());
			$num_reg=mysql_num_rows($sql);
			//echo"$num_reg<br>";
			if($num_reg>0)
			{
				while($A=mysql_fetch_assoc($sql))
				{
					$contenido=$A["contenido"];
					//echo"$contenido<br>";
					echo'<option value="'.$contenido.'">'.$contenido.'</option>';
				}
			}
			else
			{
				echo'<option>Sin Elementos</option>';
			}
			mysql_free_result($sql);
			mysql_close($conexion);
		  ?>
      </select>
	</div>	  </td>
	</tr>
    <tr bgcolor="#F7F4EE">
      <td height="24"><strong>Concepto</strong></td>
      <td colspan="4"><div id="por_concepto"><em>seleccione</em></div></td>
    </tr>
    <tr bgcolor="#F7F4EE">
      <td height="25"><strong>Foma de Pago</strong></td>
      <td width="100"><input name="forma_pago" type="radio" id="radio" value="efectivo" checked="checked" />
        Efectivo</td>
      <td width="148" bgcolor="#F7F4EE"><input type="radio" name="forma_pago" id="radio2" value="cheque" />
        Cheque</td>
      <td width="124" bgcolor="#F7F4EE">&nbsp;</td>
      <td width="107" bgcolor="#F7F4EE">&nbsp;</td>
    </tr>
    <tr bgcolor="#F7F4EE">
      <td height="26">&nbsp;</td>
      <td>&nbsp;&nbsp;</td>
      <td>
        <div align="left">Fecha Vencimiento<br />
          <input name="fecha_venc_cheque" type="text" id="fecha_venc_cheque" size="11" maxlength="10" value="<?php echo"$fecha_actual";?>" onchange="cargarMovimientos();" readonly="true"/>
          <input type="button" name="boton2" id="boton2" value="..."/>
          </div></td>
      <td>Numero
        <input name="cheque_numero" type="text" id="cheque_numero" size="15" /></td>
      <td>Banco<br />
        <select name="cheque_banco" id="cheque_banco" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'), 'CHEQUE');return false;">
        <?php 
		 foreach($array_bancos as $n)
		 {
		 	if(($n==$cheque_banco)and($session_Z))
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
    <tr bgcolor="#F7F4EE">
      <td height="43"><strong>Glosa</strong></td>
      <td colspan="4">
        &nbsp;&nbsp;
        <textarea name="fglosa" id="fglosa"></textarea>      </td>
    </tr>
	<tr bgcolor="#F7F4EE">
	<td><strong>Sede</strong></td>
	<td colspan="4">
	  &nbsp;&nbsp;<?
	  include("../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?>	</td>
	</tr >
    <tr bgcolor="#EBE5D9">
      <td height="21" colspan="5"><div align="center">
      &nbsp;
  
      <input type="reset" name="Submit2" value="Restablecer" />
     
      <input type="button" name="Submit" value="Continuar&gt;&gt;"  onclick="Confirmar();"/>
      </div></td>
    </tr>
  </table>
  </form>
  <script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton", "fecha_movimiento", "%Y-%m-%d");
	  cal.manageFields("boton2", "fecha_venc_cheque", "%Y-%m-%d");
		//cargarMovimientos();
    //]]></script>
</div>
<div id="registros_anteriores"></div>
<div id="msj">
  <div align="center"><em><?php
   $msj=str_replace("_"," ",$_GET["error"]);
   echo"*$msj*";
   ?></em></div>
</div>
</body>
</html>