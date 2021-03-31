<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Impresion_Y_Gestion_de_contratos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
@require_once("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("compruebaServer.php");
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"TIPO_DEVOLUCION");



if(($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])and($_GET))
{
	$continuar=true;
	require("../../../../funciones/conexion_v2.php");
	$id_contrato=mysqli_real_escape_string($conexion_mysqli, $_GET["id_contrato"]);
}
else
{ $continuar=false;}

if($continuar)
{ 
	if(DEBUG){echo"Datos Correcto...:D<br>";}
	
	$_SESSION["DEVOLUCION"]["verificador"]=true;
	$array_bancos=array("Estado","Chile","Santander","Internacional","Scotiabank","Credito e Inversiones","Corpbanca","Bice","HSBC Bank","Itau","Security","Falabella","Ripley","Consorcio","Penta","Paris");
 sort($array_bancos);
 $glosa="";
}
else
{
	if(DEBUG){ echo"Datos incorrectos no se puede Devolver el excedente...<br>";}

}
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<?php $xajax->printJavascript(); ?> 
<script src="../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/steel/steel.css">
<title>Devoluciones</title>
<style type="text/css">
.Estilo1 {	font-size: 18px;
	font-weight: bold;
}
.Estilo5 {font-size: 16px}
.Estilo6 {font-weight: bold}
.Estilo7 {font-weight: bold}
#Layer1 {
	position:absolute;
	width:90%;
	height:279px;
	z-index:3;
	left: 5%;
	top: 82px;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	continuar=true;
	continuar2=true;
	forma_pago=document.getElementById('forma_pago_2');
	numero_cheque=document.getElementById('cheque_numero').value;
	
	maximaDevolucion=document.getElementById('valorTotalDevolucion').value;
	devolucion=document.getElementById('valor').value;
	
	if((devolucion>0)&&(devolucion <= maximaDevolucion)){
		
	}else{ continuar2=false; alert("Monto de devolucion Invalida..."+devolucion+' / '+maximaDevolucion);}
	
	
	if(forma_pago.checked)
	{ 
		if((numero_cheque=="")||(numero_cheque==" "))
		{
			alert("Ingrese Numero de Cheque...");
			continuar=false;
		}
	}
	
	if(continuar && continuar2)
	{
		c=confirm('Seguro(a) Desea Realizar esta devolucion...?');
		if(c){ document.getElementById('frm').submit();}
	}
}
</script>
</head>

<body>
<h1 id="banner">Administrador- Devoluci&oacute;nes</h1>
<div id="link"><br />
<a href="../informe_finan1.php?id_contrato=<?php echo $id_contrato;?>" class="button">Volver al Contrato</a></div>
<div id="Layer1">
  <form action="devolucion_excedente2.php" method="post" name="frm" id="frm" >
    <table width="60%" height="344" border="0" align="center">
    <thead>
      <tr>
        <th colspan="5">Como se Realiza...?
          <input name="id_contrato" type="hidden" id="id_contrato" value="<?php echo $id_contrato;?>" /></th>
      </tr>
      </thead>
      <tbody>
      <tr >
        <td height="35">Tipo Devolucion</td>
        <td colspan="4"><label for="tipoDevolucion"></label>
          <select name="tipoDevolucion" id="tipoDevolucion" onchange="xajax_TIPO_DEVOLUCION(this.value, <?php echo $id_contrato;?>)">
          <option value="seleccione">Seleccione</option>
          <option value="excedente">excedente</option>
          <option value="matricula">matricula</option>
          <option value="derecho_examen">derecho_examen</option>
          </select></td>
      </tr>
      <tr >
        <td width="156" height="35"><strong>Fecha</strong></td>
        <td colspan="4">
          <?php $fecha_actual=date("Y-m-d");?>
          <input name="fecha_movimiento" type="text" id="fecha_movimiento" size="11" maxlength="10" value="<?php echo"$fecha_actual";?>" readonly="readonly"/>
          <input type="button" name="boton" id="boton" value="..."/></td>
      </tr>
      <tr >
        <td>Tipo Documento</td>
        <td colspan="4"><label for="tipo_documento"></label>
          <input name="tipo_documento" type="text" id="tipo_documento" value="contrato" size="15" readonly="readonly" /></td>
      </tr>
      <tr >
        <td><strong>N&deg; Documento</strong></td>
        <td colspan="4"><input name="num_documento" type="text" id="num_documento" value="<?php echo $id_contrato;?>" size="15" readonly="readonly" /></td>
      </tr>
      <tr >
        <td><strong>Valor $</strong></td>
        <td colspan="4"><input name="valor" type="text" id="valor" size="15" maxlength="10" value="<?php echo $C_excedente;?>"/>
          <input name="valorTotalDevolucion" type="hidden" id="valorTotalDevolucion" value="0" /></td>
      </tr>
      <tr >
        <td height="43"><strong>Glosa</strong></td>
        <td colspan="4"><input name="glosa" type="text" id="glosa" value="<?php echo $glosa;?>" /></td>
      </tr>
      <tr >
        <td height="25"><strong>Foma de Pago</strong></td>
        <td colspan="2"><input name="forma_pago" type="radio" id="forma_pago_1" value="efectivo" checked="checked" />
          Efectivo</td>
        <td ><input type="radio" name="forma_pago" id="forma_pago_2" value="cheque" />
          Cheque</td>
        <td ><input type="radio" name="forma_pago" id="forma_pago_3" value="transferencia" />
          transferencia/deposito</td>
        </tr>
      <tr >
        <td height="26">&nbsp;</td>
        <td width="56">&nbsp;&nbsp;</td>
        <td width="150"><div align="left">Fecha Vencimiento<br />
          <input name="fecha_venc_cheque" type="text" id="fecha_venc_cheque" size="11" maxlength="10" value="<?php echo"$fecha_actual";?>" readonly="readonly"/>
          <input type="button" name="boton2" id="boton2" value="..."/>
        </div></td>
        <td width="156">Numero
          <input name="cheque_numero" type="text" id="cheque_numero" size="15" />
          <br />
          Banco<br />
          <select name="cheque_banco" id="cheque_banco">
            <?php 
		 foreach($array_bancos as $n)
		 { echo'<option value="'.$n.'">'.$n.'</option>';}
		 ?>
          </select></td>
        <td width="122"><select name="id_cta_cte" id="id_cta_cte" >
          <?php 
		if(count($ARRAY_CTA_CTE>0))
		{
		 foreach($ARRAY_CTA_CTE as $ncta =>$valorcta)
		 {echo'<option value="'.$ncta.'">'.$valorcta.'</option>'; }
		}
		else{ echo'<option value="0">Sin cta cte Registradas...</option>';}
		 ?>
        </select></td>
      </tr>
      <tr >
        <td>&nbsp;</td>
        <td colspan="4">&nbsp;&nbsp; </td>
      </tr >
      <tr>
        <td height="21" colspan="5"><div align="center"> &nbsp;
          Seguro(a) Desea Realizar este Egreso
              <input type="checkbox" name="checkbox2" value="checkbox" onclick="document.frm.Submit.disabled=!document.frm.Submit.disabled" />
          Si, Seguro(a) <span class="Estilo6 Estilo7">
            <input type="button" name="Submit" value="Registrar" disabled="disabled"  onclick="CONFIRMAR();"/>
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
</body>
</html>