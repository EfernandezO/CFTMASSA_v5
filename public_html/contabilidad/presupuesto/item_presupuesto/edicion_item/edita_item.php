<?php
//-----------------------------------------//
	require("../../../../OKALIS/seguridad.php");
	require("../../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", false);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Edicion de Item</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_3.css">
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:476px;
	height:115px;
	z-index:1;
	left: 203px;
	top: 82px;
}
-->
</style>

<script src="../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/steel/steel.css">
<script language="javascript">
function CONFIRMAR()
{
	codigo=document.getElementById('codigo').value;
	nombre=document.getElementById('nombre').value;
	continuar=true;
	
	if((codigo=="")||(codigo==" "))
	{
		continuar=false;
		alert("ingrese un Codigo para este Item");
	}
	if((nombre=="")||(nombre==" "))
	{
		continuar=false;
		alert("ingrese un Nombre para este Item");
	}
	if(continuar)
	{
		c=confirm('¿Seguro(a) Desea Modificar este Item?\n NOTA: si modifica el codigo o sede, tenga en cuenta que si existen registros creados bajo los datos previos a la modificacion de este item, perdera la vinculacion item-registro.');
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
	$id_item=base64_decode($_GET["ID"]);
	$array_movimiento=array("I"=>"Ingreso","E"=>"Egreso");
	$array_sede=array("Talca", "Linares");
	if(DEBUG){var_export($_GET);}
	if(is_numeric($id_item))
	{	
		include("../../../../../funciones/conexion.php");
		$cons="SELECT * FROM presupuesto_parametros WHERE id='$id_item'";
		if(DEBUG){echo"<br>$cons<br>";}
		$sql=mysql_query($cons)or die(mysql_error());
		$DI=mysql_fetch_assoc($sql);
			$codigo=$DI["codigo"];
			$nombre=$DI["nombre"];
			$descripcion=$DI["descripcion"];
			$movimiento=$DI["movimiento"];
			$sede=$DI["sede"];
		mysql_free_result($sql);
		mysql_close($conexion);
		$action="edita_item_2.php";
	}
?>
<body>

<h1 id="banner">Administrador - Edici&oacute;n Item</h1>

<div id="link"><a href="../item_actuales.php" class="Estilo2">Volver al Seleccion</a></div>
<div id="apDiv1">
  	<form action="<?php echo $action;?>" method="post" name="frm" id="frm">
    <table width="100%" border="1">
      <tr>
        <td colspan="2">&#9658;Datos de Item
        <input name="id_item" type="hidden" id="id_item" value="<?php echo $id_item;?>" /></td>
      </tr>
      <tr>
        <td>Sede</td>
        <td><select name="sede" id="sede">
          <?php
         foreach($array_sede as $c=> $sedeX)
		 {
		 	if($sedeX==$sede)
			{ echo'<option value="'.$sedeX.'" selected="selected">'.$sedeX.'</option>';}
			else
			{ echo'<option value="'.$sedeX.'">'.$sedeX.'</option>';}
		 }
		 ?>
        </select></td>
      </tr>
      <tr>
        <td width="53%">Movimiento</td>
        <td width="47%"><select name="movimiento" id="movimiento">
         <?php
         foreach($array_movimiento as $indice => $label)
		 {
		 	if($indice==$movimiento)
			{ echo'<option value="'.$indice.'" selected="selected">'.$label.'</option>';}
			else
			{ echo'<option value="'.$indice.'">'.$label.'</option>';}
		 }
		 ?>
        </select></td>
      </tr>
      <tr>
        <td>Codigo</td>
        <td><input type="text" name="codigo" id="codigo"  value="<?php echo $codigo;?>"/></td>
      </tr>
      <tr>
        <td>Nombre</td>
        <td><input type="text" name="nombre" id="nombre"  value="<?php echo $nombre;?>"/></td>
      </tr>
      <tr>
        <td>Descripcion</td>
        <td><textarea name="descripcion" id="descripcion"><?php echo $descripcion;?></textarea></td>
      </tr>
      <tr>
        <td colspan="2"><div align="right">
          <input type="button" name="button" id="button" value="Modificar"  onclick="CONFIRMAR();"/>
        </div></td>
      </tr>
    </table>
  </form>
</div>
</body>
</html>