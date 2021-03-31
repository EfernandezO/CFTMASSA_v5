<?php
//-----------------------------------------//
	require("../../../../OKALIS/seguridad.php");
	require("../../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
//-----------------------------------------//	

require_once ("../../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("item_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CARGA_ITEM");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>seleccion de parametros</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_3.css">
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:553px;
	height:115px;
	z-index:1;
	left: 149px;
	top: 68px;
}
-->
</style>
<?php $xajax->printJavascript(); ?> 
<script src="../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/steel/steel.css">
<script language="javascript">
function VERIFICAR()
{
	valor=document.getElementById('valor').value;
	glosa=document.getElementById('glosa').value;
	
	continuar=true;
	
	if((valor=="")||(valor==" "))
	{
		continuar=false;
		alert("Ingrese El Valor");
	}
	if((glosa=="")||(glosa==" "))
	{
		continuar=false;
		alert("ingrese la Glosa");
	}
	
	if(continuar)
	{
		c=confirm('Seguro(a) Desea Agregar este Registro al Presupuesto de este dia');
		if(c)
		{
			document.frm.submit();
		}
	}
}
</script>
<style type="text/css">
<!--
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
-->
</style></head>
<?php
	$sede=$_SESSION["PRESUPUESTO"]["sede"];
	$fecha_presupuesto=$_SESSION["PRESUPUESTO"]["fecha"];
	include("../../../../../funciones/funcion.php");
	$array_forma_pago=array("efectivo", "cheque", "deposito_bancario");
?>
<body>

<h1 id="banner">Presupuesto - Registro</h1>

<div id="link"><a href="../presupuesto_main.php" class="Estilo2">Volver al Menu</a></div>
<div id="apDiv1">
  <form action="nuevo_registro_presupuesto2.php" method="post" name="frm" id="frm">
    <table width="100%" border="1">
    <thead>
      <tr>
        <th colspan="2">Nvo Registro</th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td width="53%">Dia</td>
        <td width="47%"><?php echo fecha_format($fecha_presupuesto);?></td>
      </tr>
      <tr>
        <td>Sede</td>
        <td><?php echo $sede;?></td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td>Movimiento</td>
        <td><select name="movimiento" id="movimiento" onchange="xajax_CARGA_ITEM(this.value); return false;">
          <option value="I" selected="selected">Ingreso</option>
          <option value="E">Egreso</option>
        </select></td>
      </tr>
      <tr>
        <td>Item</td>
        <td><div id="div_item">
          <select name="item" id="item">
          <?php
		  	include("../../../../../funciones/conexion.php");
		  	$cons="SELECT codigo, nombre FROM presupuesto_parametros WHERE movimiento='I' AND sede='$sede'";
			$sql=mysql_query($cons) or die("item".mysql_error());
			$num_reg=mysql_num_rows($sql);
			if($num_reg>0)
			{
				while($I=mysql_fetch_assoc($sql))
				{
					$codigo=$I["codigo"];
					$nombre=$I["nombre"];
					
					echo'<option value="'.$codigo.'">'.$nombre.'('.$codigo.')</option>';
				}
			}
			else
			{
				echo'<option value="SI">Sin Item</option>';
			}
			mysql_free_result($sql);
			mysql_close($conexion);
          ?>
          </select>
        </div></td>
      </tr>
      <tr>
        <td>Valor</td>
        <td><input type="text" name="valor" id="valor" /></td>
      </tr>
      <tr>
        <td>Forma Pago</td>
        <td><select name="forma_pago" id="forma_pago">
          <?php
          foreach($array_forma_pago as $n => $valorfp)
		  {
		  	echo'<option value="'.$valorfp.'">'.$valorfp.'</option>';
		  }
		  ?>
        </select>
        </td>
      </tr>
      <tr>
        <td>Glosa</td>
        <td><textarea name="glosa" id="glosa"></textarea></td>
      </tr>
      <tr>
        <td colspan="2"><div align="right">
          <input type="button" name="button" id="button" value="Grabar"  onclick="VERIFICAR();"/>
        </div></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
</body>
</html>