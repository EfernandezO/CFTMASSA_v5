<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//
////////////////////necesario para Xajax///////////////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("orden_compra_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_PROVEEDOR");
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");
////////////////-------------------*********************---------//

$array_estados_cotizacion=array("si", "no");
$array_condiciones_pago=array("efectivo", "cheque");
$array_personal=array();
require("../../../../funciones/conexion_v2.php");
	$cons_P="SELECT id, nombre, apellido_P FROM personal WHERE nivel >'1' AND con_acceso='ON' ORDER by apellido";
	$sql_P=$conexion_mysqli->query($cons_P);
	$num_personal=$sql_P->num_rows;
	if($num_personal>0)
	{
		while($P=$sql_P->fetch_assoc())
		{
			$P_id=$P["id"];
			$P_nombre=$P["nombre"];
			$P_apellido=$P["apellido_P"];
			$array_personal[$P_id]=$P_apellido." ".$P_nombre;
		}
	}
	else
	{ $array_personal[0]="Sin Personal";}
	$sql_P->free();
mysql_close($conexion);
$conexion_mysqli->close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>orden de compra | creacion</title>
<?php $xajax->printJavascript(); ?> 
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<script src="../../../libreria_publica/jquery_libreria/jquery.js" type="text/javascript"></script>
<style type="text/css">
#div_proveedor {
	position:absolute;
	width:30%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 62px;
}
#div_debug {
	position:absolute;
	width:30%;
	height:35px;
	z-index:2;
	left: 5%;
	top: 242px;
	overflow: auto;
}
#div_item {
	position:absolute;
	width:90%;
	height:115px;
	z-index:3;
	left: 5%;
	top: 288px;
}
#div_solicitante {
	position:absolute;
	width:30%;
	height:66px;
	z-index:4;
	left: 40%;
	top: 62px;
}
</style>
<script language="javascript">
function ACTUALIZAR_TOTAL()
{
	valores=document.getElementsByName('item_valor[]');
	numero_elementos=valores.length;
	//alert(numero_elementos);
	
	total=0;
	for(i=1;i<=numero_elementos;i++)
	{
		
		id_valor="valor_"+i;
		id_cantidad="cantidad_"+i;
		aux_valor=document.getElementById(id_valor).value;
		aux_cantidad=document.getElementById(id_cantidad).value;
		
		total=total+((parseFloat(aux_valor))*(parseFloat(aux_cantidad)));
	}
	document.getElementById('TOTAL').innerHTML="<strong>$"+total+"</strong>";
}
</script>
</head>
<body>
<h1 id="banner">Finanzas - Orden de Compra</h1>
<div id="link"><br />
<a href="../revision/revisar.php" class="button">Volver a Orden de Compra</a></div>
<form action="order_compra_2.php" method="post" enctype="multipart/form-data" id="frm">
  <div id="div_proveedor">
  <table width="100%" border="1" id="proveedor">
  <thead>
  	<tr>
      <tH colspan="2">Proveedores
        <input name="proveedor_id" type="hidden" id="proveedor_id" value="0" /></tH	>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td width="41%">Rut</td>
      <td width="59%"><label for="proveedor_rut"></label>
        <input type="text" name="proveedor_rut" id="proveedor_rut"  onblur="xajax_BUSCA_PROVEEDOR(this.value, document.getElementById('proveedor_razon_social').value, document.getElementById('proveedor_direccion').value, document.getElementById('proveedor_ciudad').value);return false;"/></td>
    </tr>
    <tr>
      <td>Razon Social</td>
      <td><label for="proveedor_razon_social"></label>
        <input type="text" name="proveedor_razon_social" id="proveedor_razon_social" /></td>
    </tr>
    <tr>
      <td>Direccion</td>
      <td><label for="proveedor_direccion"></label>
        <input type="text" name="proveedor_direccion" id="proveedor_direccion" /></td>
    </tr>
    <tr>
      <td>Ciudad</td>
      <td><label for="proveedor_ciudad"></label>
        <input type="text" name="proveedor_ciudad" id="proveedor_ciudad" /></td>
    </tr>
    </tbody>
  </table>
</div>
<div id="div_debug">....</div>
<div id="div_item">
  <table width="100%" border="1" id="tabla">
  <thead>
    <tr>
      <th colspan="5">Item</th>
      </tr>
        <tr>
      <td width="7%">Cantidad</td>
      <td width="18%">Unidad de medida</td>
      <td width="59%">Descripcion</td>
      <td width="9%">Valor</td>
      <td width="7%">opcion</td>
    </tr>
      </thead>
      <tbody>
      <tr>
      <td><input name="item_cantidad[]" type="text" id="cantidad_1" value="0" size="5" /></td>
      <td><input type="text" name="item_unidad_medida[]" id="unidad_medida_1" /></td>
      <td><input type="text" name="item_descripcion[]" id="descripcion_1" /></td>
      <td><input name="item_valor[]" type="text" id="valor_1" onchange="ACTUALIZAR_TOTAL();"  value="0" size="11"/></td>
      <td class="eliminar">Eliminar</td>
    </tr>
      </tbody>
  </table>
  <div id="div_total"><table width="100%" border="1">
  <tr>
    <td width="84%"><strong>TOTAL</strong></td>
    <td width="16%" id="TOTAL">&nbsp;</td>
  </tr>
</table>
</div>
  <input type="button" id="agregar" value="Agregar fila" />
  <input name="Continuar" type="button" value="Grabar Order de Compra...?"  onclick="xajax_VERIFICAR(xajax.getFormValues('frm')); return false;"/>
 <script type="text/javascript">
jQuery(function(){
	jQuery("#agregar").on('click', function(){
		//jQuery("#tabla tbody tr:eq(0)").clone().removeClass('fila-base').appendTo("#tabla tbody");
	var numero_filas=jQuery('tr', jQuery("#tabla tbody")).length;	
	<?php if(DEBUG){ ?>alert("numero filas"+numero_filas);<?php }?>
		indice=(numero_filas+1);
		
		fila_html='<tr><td><input name="item_cantidad[]" type="text" id="cantidad_'+indice+'" size="5" value="0"/></td><td><input type="text" name="item_unidad_medida[]" id="unidad_medida_'+indice+'" /></td><td><input type="text" name="item_descripcion[]" id="descripcion_'+indice+'" /></td><td><input type="text" name="item_valor[]" id="valor_'+indice+'" value="0" onchange="ACTUALIZAR_TOTAL();" size="11"/></td><td class="eliminar">Eliminar</td>';
		jQuery("#tabla tbody").append(fila_html);
	});
	jQuery(document).on("click",".eliminar",function(){
		var parent = jQuery(this).parents().get(0);
		jQuery(parent).remove();
		ACTUALIZAR_TOTAL(); 
	});
});
</script>
</div>

<div id="div_solicitante">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="2">Solicitante</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="45%">Unidad Solicitante</td>
      <td width="55%"><label for="solicitante_unidad"></label>
        <input type="text" name="solicitante_unidad" id="solicitante_unidad" /></td>
    </tr>
    <tr>
      <td>Responsable</td>
      <td><label for="solicitante_id_responsable"></label>
        <select name="solicitante_id_responsable" id="solicitante_id_responsable">
         <?php
		foreach($array_personal as $n => $valor)
		{echo'<option value="'.$n.'">'.$valor.'</option>';}
        ?>
        </select>
        </td>
    </tr>
    <tr>
      <td>Cotizacion</td>
      <td><label for="solicitante_cotizacion"></label>
        <select name="solicitante_cotizacion" id="solicitante_cotizacion">
        <?php
		foreach($array_estados_cotizacion as $n => $valor)
		{echo'<option value="'.$valor.'">'.$valor.'</option>';}
        ?>
        </select></td>
    </tr>
    <tr>
      <td>Condiciones de Pago</td>
      <td><label for="solicitante_condicion_pago"></label>
        <select name="solicitante_condicion_pago" id="solicitante_condicion_pago">
         <?php
		foreach($array_condiciones_pago as $n => $valor)
		{echo'<option value="'.$valor.'">'.$valor.'</option>';}
        ?>
        </select></td>
    </tr>
    <tr>
      <td>Descripcion</td>
      <td><input type="text" name="solicitante_descripcion" id="solicitante_descripcion" /></td>
    </tr>
    </tbody>
  </table>
</div>
</form>
</body>
</html>