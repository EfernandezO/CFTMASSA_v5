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
$privilegio=$_SESSION["USUARIO"]["privilegio"];
switch($privilegio)
{
	case"admi_total":
		$mostrar_edicion=true;
		break;
	default:
		$mostrar_edicion=true;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edita Factura</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:64px;
	z-index:1;
	left: 5%;
	top: 109px;
}
-->
</style>
<script src="../../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
 <script src="../../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/steel/steel.css">
<script language="javascript">
function CONFIRMAR()
{
	codigo=document.getElementById('cod_factura').value;
	valor=document.getElementById('valor').value;
	continuar=true;
	c=confirm('Seguro(a) Desea Modificar esta Factura?');
	
	if(c)
	{
		if((codigo=="")||(codigo==" "))	
		{
			continuar=false;
			alert('ingrese Codigo o Folio de la Factura');
		}
		if((valor=="")||(valor==" "))	
		{
			continuar=false;
			alert('ingrese Valor de la Factura');
		}
		if(continuar)
		{
			document.frm.submit();
		}
	}
}
</script>
<style type="text/css">
<!--
.Estilo1 {font-size: 12px}
.Estilo3 {font-size: 12px; font-weight: bold; }
-->
</style>
<?php
if($_GET)
{
	$id_factura=base64_decode($_GET["id"]);
	$array_condicion=array("pendiente", "cancelada", "abonada");
	if(is_numeric($id_factura))
	{
		require("../../../../../funciones/conexion_v2.php");
		$cons="SELECT * FROM facturas WHERE id='$id_factura' LIMIT 1";
		$sqli=$conexion_mysqli->query($cons);
		$F=$sqli->fetch_assoc();
		
			$cod_factura=$F["cod_factura"];
			$proveedor=$F["proveedor"];
			$id_proveedor=$F["id_proveedor"];
			$comentario=$F["comentario"];
			$fecha_ingreso=$F["fecha_ingreso"];
			$fecha_vencimiento=$F["fecha_vencimiento"];
			$condicion=$F["condicion"];
			$valor_factura=$F["valor"];
			$saldo=$F["saldo"];
			$abono=$F["abono"];
		$sqli->free();
		//-----------------------------------------------//
		$cons_P="SELECT * FROM proveedores ORDER by razon_social";
		$sqli_P=$conexion_mysqli->query($cons_P);
		$num_proveedores=$sqli_P->num_rows;
		$array_proveedores=array();
		if($num_proveedores>0)
		{
			while($P=$sqli_P->fetch_assoc())
			{
				$P_id=$P["id_proveedor"];
				$P_rut=$P["rut"];
				$P_razon_social=$P["razon_social"];
				$array_proveedores[$P_id]=$P_rut." ".$P_razon_social;
			}
		}
		$sqli_P->free();
		
		$conexion_mysqli->close();
	}
}
else
{ echo"sin datos";}
?>
</head>

<body>
<h1 id="banner">Administrador -Edicion Factura</h1>

<div id="link"><br />
<a href="../ver/ver_factura.php" class="button">Volver a Seleccion</a></div>
<div id="apDiv1">
	<form action="edit_factura_2.php" method="post" name="frm" id="frm">
  <table width="60%" border="0" align="center">
  <thead>
    <tr>
      <th colspan="2" ><span class="Estilo3">Modificar Factura
        <input name="id_factura" type="hidden" id="id_factura" value="<?php echo $id_factura;?>" />
      </span></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td ><span class="Estilo1">Sede</span></td>
      <td ><?php
	  include("../../../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?></td>
    </tr>
    <tr>
      <td width="34%" ><span class="Estilo1">Numero Factura</span></td>
      <td width="66%" ><input type="text" name="cod_factura" id="cod_factura"  value="<?php echo $cod_factura;?>"/></td>
    </tr>
    <tr>
      <td ><span class="Estilo1">Proveedor</span></td>
      <td ><label for="id_proveedor"></label>
        <select name="id_proveedor" id="id_proveedor">
        <?php
        	if(count($array_proveedores)>0)
			{
				foreach($array_proveedores as $n => $valor)
				{
					if($id_proveedor==$n)
					{ $selectX='selected="selected"';}
					else
					{ $selectX='';}
					echo'<option value="'.$n.'" '.$selectX.'>'.$valor.'</option>';
				}
			}
		?>
        </select></td>
    </tr>
    <tr>
      <td >Comentario</td>
      <td ><textarea name="comentario" id="comentario"><?php echo $comentario;?></textarea></td>
    </tr>
    <tr>
      <td ><span class="Estilo1">Fecha Ingreso</span></td>
      <td ><input  name="fecha_ingreso" id="fecha_ingreso" size="15" maxlength="10" readonly="true" value="<?php echo $fecha_ingreso;?>"/>
        <input type="button" name="boton1" id="boton1" value="..." /></td>
    </tr>
    <tr>
      <td ><span class="Estilo1">Fecha Vencimiento</span></td>
      <td ><input  name="fecha_vencimiento" id="fecha_vencimiento" size="15" maxlength="10" readonly="true" value="<?php echo $fecha_vencimiento;?>"/>
        <input type="button" name="boton2" id="boton2" value="..." /></td>
    </tr>
    <tr>
      <td ><span class="Estilo1">Condicion</span></td>
      <td ><select name="condicion" id="condicion">
       <?php
       foreach($array_condicion as $n => $valor)
	   {
	   		if($valor==$condicion)
			{ echo'<option value="'.$valor.'" selected="selected">'.$valor.'</option>';}
			else
			{ echo'<option value="'.$valor.'">'.$valor.'</option>';}
	   }
	   ?>
      </select>      </td>
    </tr>
    <tr>
      <td ><span class="Estilo1">Valor</span></td>
      <td ><input name="valor" type="text" id="valor" value="<?php echo $valor_factura;?>" <?php if(!$mostrar_edicion){?>readonly="readonly"; <?php }?>/></td>
    </tr>
    <tr>
      <td >Saldo</td>
      <td ><label for="saldo"></label>
        <input type="text" name="saldo" id="saldo"  value="<?php echo $saldo;?>"/></td>
    </tr>
    <tr>
      <td >Abono</td>
      <td ><label for="abono"></label>
        <input type="text" name="abono" id="abono" value="<?php echo $abono;?>"/></td>
    </tr>
    </tbody>
    <tfoot>
    <tr>
      <td >&nbsp;</td>
      <td ><input type="button" name="Grabar" id="Grabar" value="modificar"  onclick="CONFIRMAR();"/></td>
    </tr>
    </tfoot>
  </table>
  </form>
</div>
</body>
<script type="text/javascript">
//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton1", "fecha_ingreso", "%Y-%m-%d");
	   cal.manageFields("boton2", "fecha_vencimiento", "%Y-%m-%d");

    //]]>
</script>
</html>