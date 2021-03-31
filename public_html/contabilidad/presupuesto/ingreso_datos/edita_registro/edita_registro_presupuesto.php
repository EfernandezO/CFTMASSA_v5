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
<title>Edicion de Registro - Presupuesto</title>
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
		c=confirm('Seguro(a) Desea Editar este Registro?');
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
	$id_presupuesto=base64_decode($_GET["id_presupuesto"]);
	include("../../../../../funciones/funcion.php");
	include("../../../../../funciones/conexion.php");
	
	$array_forma_pago=array("efectivo", "cheque", "deposito_bancario");
	$cons="SELECT * FROM presupuesto WHERE id='$id_presupuesto'";
	$sql=mysql_query($cons)or die(mysql_error());
	$DP=mysql_fetch_assoc($sql);
		$movimiento=$DP["movimiento"];
		$codigo_item=$DP["item"];
		$valor_registro=$DP["valor"];
		$forma_pago=$DP["forma_pago"];
		$glosa_registro=$DP["glosa"];
	mysql_free_result($sql);
	mysql_close($conexion);
	
	$array_movimiento=array("I"=>"Ingreso","E"=>"Egreso");
?>
<body>

<h1 id="banner">Presupuesto - Edici&oacute;n de Registro</h1>

<div id="link"><a href="../presupuesto_main.php" class="Estilo2">Volver al Menu</a></div>
<div id="apDiv1">
  <form action="edita_registro_presupuesto_2.php" method="post" name="frm" id="frm">
    <table width="100%" border="1">
    <thead>
      <tr>
        <th colspan="2">Edici&oacute;n de Registro
          <input name="id_presupuesto" type="hidden" id="id_presupuesto" value="<?php echo $id_presupuesto;?>" /></th>
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
          <?php
          foreach($array_movimiento as $n => $valor)
		  {
		  	if($n==$movimiento)
			{ echo'<option value="'.$n.'" selected="selected">'.$valor.'</option>';}
			else
			{ echo'<option value="'.$n.'">'.$valor.'</option>';}
		  }
		  ?>
        </select></td>
      </tr>
      <tr>
        <td>Item</td>
        <td><div id="div_item">
          <select name="item" id="item">
          <?php
		  	include("../../../../../funciones/conexion.php");
		  	$cons="SELECT codigo, nombre FROM presupuesto_parametros WHERE movimiento='$movimiento' AND sede='$sede'";
			$sql=mysql_query($cons) or die("item".mysql_error());
			$num_reg=mysql_num_rows($sql);
			if($num_reg>0)
			{
				while($I=mysql_fetch_assoc($sql))
				{
					$codigo=$I["codigo"];
					$nombre=$I["nombre"];
					
					if($codigo==$codigo_item)
					{ echo'<option value="'.$codigo.'" selected="selected">'.$nombre.'('.$codigo.')</option>';}
					else
					{ echo'<option value="'.$codigo.'">'.$nombre.'('.$codigo.')</option>';}
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
        <td><input type="text" name="valor" id="valor"  value="<?php echo $valor_registro;?>"/></td>
      </tr>
      <tr>
        <td>Forma de Pago</td>
        <td><select name="forma_pago" id="forma_pago">
         <?php
          foreach($array_forma_pago as $n => $valorfp)
		  {
			if($forma_pago==$valorfp)		  	
			{
				echo'<option value="'.$valorfp.'" selected="selected">'.$valorfp.'</option>';
			}
			else
			{
		  		echo'<option value="'.$valorfp.'">'.$valorfp.'</option>';
			}	
		  }
		  ?>
        </select>
        </td>
      </tr>
      <tr>
        <td>Glosa</td>
        <td><textarea name="glosa" id="glosa"><?php echo $glosa_registro;?></textarea></td>
      </tr>
      <tr>
        <td colspan="2"><div align="right">
          <input type="button" name="button" id="button" value="Modificar"  onclick="VERIFICAR();"/>
        </div></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
</body>
</html>