<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_otros_pagos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//-----------------------------------------//	
 //////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("ingreso_egreso_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CARGA_MOVIMIENTO");
$xajax->register(XAJAX_FUNCTION,"CARGA_X_CONCEPTO");
$xajax->register(XAJAX_FUNCTION,"CARGA_GLOSA");
$_SESSION["PAGOS"]["verificador"]=true;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Formulario de  Pagos</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>

<script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:726px;
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
	top: 633px;
}
.Estilo5 {font-size: 16px}
#msj {
	position:absolute;
	width:649px;
	height:26px;
	z-index:8;
	left: 20px;
	top: 602px;
}
#link {
	text-align: right;
}
#link {
	padding-right: 10px;
}
.Estilo6 {font-weight: bold}
.Estilo7 {font-weight: bold}
#Layer1 #frm #por_concepto {
	padding-left: 13px;
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
	num_documento=document.getElementById('num_documento').value;
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
	if(num_documento=="")
	{
		continuar=false;
		alert('Ingrese Numero Documento');
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
$array_bancos=array("Estado","Chile","Santander","Internacional","Scotiabank","Credito e Inversiones","Corpbanca","Bice","HSBC Bank","Itau","Security","Falabella","Ripley","Consorcio","Penta","Paris","BBVA","Flow");
 sort($array_bancos);
?>
</head>
<body onload="xajax_CARGA_MOVIMIENTO(document.getElementById('fecha_movimiento').value, document.getElementById('fsede').value);return false">
<h1 id="banner">Administrador- Registro de Ingresos</h1>

<div id="link"><br />
<a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver al Menu</a></div>
<?php if($_SESSION["privilegio"]=="admi_total"){?>
<?php }?>
<div id="Layer1">
<form action="ingreso_boleta_2.php" method="post" name="frm" id="frm" >
  <table width="100%" height="344" border="0">
  <thead>
    <tr>
      <th colspan="5"><div align="center" class="Estilo1 Estilo5">Formulario Ingreso</div></th>
    </tr>
    </thead>
    <tbody>
    <tr >
      <td height="35" ><strong>Sede</strong></td>
      <td colspan="4" ><?php
	  include("../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?></td>
    </tr>
    <tr >
      <td width="175" height="35" ><strong>Fecha</strong></td>
      <td colspan="4" > &nbsp;&nbsp;
	  <?php
	  $fecha_actual=date("Y-m-d");
	  if(isset($_GET["ultima_fecha"]))
	  { $ultima_fecha=$_GET["ultima_fecha"];}
	  else
	  { $ultima_fecha=$fecha_actual;}
	  	
	  ?>
        <input name="fecha_movimiento" type="text" id="fecha_movimiento" size="11" maxlength="10" value="<?php echo"$ultima_fecha";?>" onchange="cargarMovimientos();" readonly="true"/>
		
<input type="button" name="boton" id="boton" value="..."/>
<input type="button" name="Submit3" value="-&gt;"   title="Previsualizar" onclick="xajax_CARGA_MOVIMIENTO(document.getElementById('fecha_movimiento').value, document.getElementById('fsede').value);return false"/></td>
    </tr>
	<tr >
	  <td ><strong>N&deg; Documento</strong></td>
	  <td colspan="4" >
	  <input name="num_documento" type="text" id="num_documento" value="0" size="11" />
	  <em>(Letras) </em></td>
	  </tr>
	<tr >
	<td ><strong>Valor</strong></td>
	<td colspan="4" >
	  $
	  <input name="fvalor" type="text" id="fvalor" size="11" maxlength="10"/></td>
	</tr>
	<tr >
	<td ><strong> Tipo de Documento </strong></td>
	<td colspan="2" ><div id="contiene_select">
	  &nbsp;&nbsp;
	  <select name="ftipo_doc" id="ftipo_doc" onchange="xajax_CARGA_X_CONCEPTO(this.value); return false">
     	<option value="SS">Seleccione</option>
        <?php
		 	 include("../../../funciones/conexion_v2.php");
		  	$cons="SELECT contenido FROM parametros WHERE seccion='finanzas' AND tipo='I' AND permite_genera_boleta='ON'";
			//echo"---> $cons<br>";
			$sql=$conexion_mysqli->query($cons) or die($conexion_mysqli->error);
			$num_reg=$sql->num_rows;
			//echo"$num_reg<br>";
			if($num_reg>0)
			{
				while($A=$sql->fetch_assoc())
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
			$sql->free();
			$conexion_mysqli->close();
		  ?>
      </select>
	</div>	  </td>
	<td colspan="2" ><div align="right"></div></td>
	</tr>
    <tr >
      <td height="24" ><strong>Concepto</strong></td>
      <td colspan="4" ><div id="por_concepto"><em>seleccione</em></div></td>
    </tr>
    <tr >
      <td height="43" ><strong>Glosa</strong></td>
      <td colspan="4" ><div id="div_glosa">
          <textarea name="fglosa" id="fglosa"></textarea>
        </div></td>
    </tr>
    <tr >
      <td height="25" ><strong>Foma de Pago</strong></td>
      <td width="61" ><input name="forma_pago" type="radio" id="radio" value="efectivo" checked="checked" />
        Efectivo</td>
      <td width="166" ><input type="radio" name="forma_pago" id="radio2" value="cheque" />
        Deposito</td>
      <td width="158" >&nbsp;</td>
      <td width="144" >&nbsp;</td>
    </tr>
    <tr >
      <td height="26" >&nbsp;</td>
      <td >&nbsp;&nbsp;</td>
      <td ><div align="left">Fecha Vencimiento<br />
              <input name="fecha_venc_cheque" type="text" id="fecha_venc_cheque" size="11" maxlength="10" value="<?php echo"$fecha_actual";?>" onchange="cargarMovimientos();" readonly="true"/>
              <input type="button" name="boton2" id="boton2" value="..."/>
      </div></td>
      <td >Numero
        <input name="cheque_numero" type="text" id="cheque_numero" size="15" /></td>
      <td >Banco<br />
          <select name="cheque_banco" id="cheque_banco" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'), 'CHEQUE');return false;">
            <?php 
		 foreach($array_bancos as $n)
		 {echo'<option value="'.$n.'">'.$n.'</option>'; }
		 ?>
        </select></td>
    </tr>
	<tr >
	<td >&nbsp;</td>
	<td colspan="4" >
	  &nbsp;&nbsp;	</td>
	</tr >
    <tr >
      <td height="21" colspan="5"><div align="center">
      &nbsp;
      Seguro(a) Desea Realizar este Ingreso 
      <input type="checkbox" name="checkbox2" value="checkbox" onclick="document.frm.Submit.disabled=!document.frm.Submit.disabled" />
      Si, Seguro(a) <span class="Estilo6 Estilo7">
      <input type="button" name="Submit" value="Registrar" disabled="disabled"  onclick="Confirmar();"/>
      </span></div></td>
    </tr>
    </tbody>
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
  <div align="center"><em>
  <?php
  if(isset($_GET["error"]))
  {
   $msj=str_replace("_"," ",$_GET["error"]);
   echo"*$msj*";
  }
   ?>
   </em></div>
</div>
</body>
</html>