<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Registros_ingresos_empresa_boleta_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$sede_actual=$_SESSION["USUARIO"]["sede"];
$fecha_actual=date("Y-m-d");
$_SESSION["PAGOS"]["verificador"]=true;

//////////////////////////////AJAX//////////////////////////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("ingreso_egreso_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CONFIRMAR");
///////////////---------_____________-------////////////////////////////
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
	width:50%;
	height:279px;
	z-index:3;
	left: 5%;
	top: 74px;
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
	width:90%;
	height:48px;
	z-index:7;
	left: 5%;
	top: 597px;
	text-align: left;
}
.Estilo5 {font-size: 16px}
#msj {
	position:absolute;
	width:90%;
	height:26px;
	z-index:8;
	left: 5%;
	top: 566px;
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
#apDiv1 {
	position:absolute;
	width:18%;
	height:115px;
	z-index:9;
	left: 60%;
	top: 84px;
}
-->
</style>
<?php $xajax->printJavascript(); ?> 
</head>
<body>
<h1 id="banner">Administrador- Registro de Ingresos Por empresa</h1>

<div id="link"><br />
<a href="../index.php" class="button">Volver al Menu</a></div>
<div id="Layer1">
<form action="ingreso_boleta_2.php" method="post" name="frm" id="frm" >
  <table width="100%" height="344" border="0" align="left">
  <thead>
    <tr>
      <th colspan="5">Formulario Ingreso</th>
    </tr>
    </thead>
    <tr >
      <td height="35"><strong>Sede</strong></td>
      <td colspan="4"><?php
	  require("../../../funciones/funciones_sistema.php");
	  echo CAMPO_SELECCION("fsede","sede",$sede_actual,false);
	  ?></td>
    </tr>
    <tr >
      <td width="122" height="35"><strong>Fecha</strong></td>
      <td colspan="4"> &nbsp;&nbsp;
        <input name="fecha_movimiento" type="text" id="fecha_movimiento" size="11" maxlength="10" value="<?php echo"$fecha_actual";?>" readonly="true"/>
		
<input type="button" name="boton" id="boton" value="..."/></td>
    </tr>
	<tr >
	  <td><strong>Empresa</strong></td>
	  <td colspan="4">
	    <select name="empresa" id="empresa">
        <?php
		 require("../../../funciones/conexion_v2.php");
        $consE="SELECT id, nombre_fantasia FROM empresa";
		$sqli=$conexion_mysqli->query($consE)or die($conexion_mysqli->error);
		$num_empresas=$sqli->num_rows;
		if($num_empresas>0)
		{
			while($E=$sqli->fetch_assoc())
			{
				$E_id=$E["id"];
				$E_nombre_fantasia=$E["nombre_fantasia"];
				echo'<option value="'.$E_id.'">'.$E_nombre_fantasia.'</option>';
			}
		}
		else
		{echo'<option value="0">Sin empresas</option>';}
		$sqli->free();
		?>
        </select></td>
	  </tr>
	<tr >
	<td><strong>Valor</strong></td>
	<td colspan="4">
	  $
	  <input name="fvalor" type="text" id="fvalor" size="11" maxlength="10"/></td>
	</tr>
    <tr >
      <td height="24"><strong>Concepto</strong></td>
      <td colspan="4"><?php echo CAMPO_SELECCION("por_conceptoX","conceptos_financieros","",false);?></td>
    </tr>
    <tr >
      <td height="43"><strong>Glosa</strong></td>
      <td colspan="4">
        &nbsp;&nbsp;
        <textarea name="fglosa" id="fglosa"></textarea>      </td>
    </tr>
    <tr >
      <td height="25"><strong>Foma de Pago</strong></td>
      <td width="108"><input name="forma_pago" type="radio" id="radio" value="efectivo" checked="checked" />
        Efectivo</td>
      <td width="140">&nbsp;</td>
      <td width="99">&nbsp;</td>
      <td width="83">&nbsp;</td>
    </tr>
    <tr >
      <td height="26">&nbsp;</td>
      <td>
        <input type="radio" name="forma_pago" id="radio2" value="cheque" />
        Cheque</td>
      <td><div align="left">Fecha Vencimiento<br />
              <input name="fecha_venc_cheque" type="text" id="fecha_venc_cheque" size="11" maxlength="10" value="<?php echo"$fecha_actual";?>" onchange="cargarMovimientos();" readonly="true"/>
              <input type="button" name="boton2" id="boton2" value="..."/>
      </div></td>
      <td>Numero
        <input name="cheque_numero" type="text" id="cheque_numero" size="15" /></td>
      <td>Banco<br /> <?php echo CAMPO_SELECCION("cheque_banco", "bancos","",false); ?></td>
    </tr>
	<tr >
	  <td >&nbsp;</td>
	  <td >
        <input type="radio" name="forma_pago" id="radio3" value="deposito" /> 
        Deposito
</td>
	  <td ><label for="id_cta_cte"></label>
	    <select name="id_cta_cte" id="id_cta_cte">
        <?php
        $cons="SELECT id, banco, num_cuenta FROM cuenta_corriente";
		$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$num_cuentas=$sqli->num_rows;
		if($num_cuentas>0)
		{
			while($C=$sqli->fetch_assoc())
			{
				$C_id=$C["id"];
				$C_banco=$C["banco"];
				$C_num_cuenta=$C["num_cuenta"];
				
				echo'<option value="'.$C_id.'">'.$C_banco.'-'.$C_num_cuenta.'</option>';
			}
		}
		else
		{ echo'<option value="0">Sin Cta. Cte.</option>';}
		$sqli->free();
		$conexion_mysqli->close();
		?>
        </select></td>
	  <td >&nbsp;</td>
	  <td >&nbsp;</td>
	  </tr >
	<tr >
	<td >&nbsp;</td>
	<td colspan="4" >
	  &nbsp;&nbsp;	</td>
	</tr >
    <tr>
      <td height="21" colspan="5"><div align="center">
      &nbsp;
      Seguro(a) Desea Realizar este Ingreso 
      <input type="checkbox" name="checkbox2" value="checkbox" onclick="document.frm.Submit.disabled=!document.frm.Submit.disabled" />
      Si, Seguro(a) <span class="Estilo6 Estilo7">
      <input type="button" name="Submit" value="Registrar" disabled="disabled"  onclick="xajax_CONFIRMAR(xajax.getFormValues('frm')); return false;"/>
      </span></div></td>
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
  <div align="left"><em><?php
  if(isset($_GET["error"]))
  {
   $msj=str_replace("_"," ",$_GET["error"]);
   echo"*$msj*";
  }
   ?></em></div>
</div>
<div id="apDiv1"><strong>Nota:</strong> Utilice para registrar pagos realizados por parte de empresas o intituciones. Estas deben ser previamente registradas en el sistema... <br />
  Importante el registro de Este Pago Generar&aacute; una Boleta.
</div>
</body>
</html>