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
/////////////////////////////
$_SESSION["FACTURA"]["verificador"]=true;

$privilegio=$_SESSION["USUARIO"]["privilegio"];
$sede_usuario=$_SESSION["USUARIO"]["sede"];
switch($privilegio)
{
	case"admi_total":
		$mostrar_edicion=true;
		$condicion_sede="";
		break;
	default:
		$mostrar_edicion=true;
		$condicion_sede="WHERE sede='$sede_usuario'";	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Facturas</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">


	  <script type="text/javascript" src="../../../../libreria_publica/mootools_libreria/mootools-yui-compressed.js"></script>
  <script type="text/javascript" src="../../../../libreria_publica/sexy_lightbox/Mootools/sexylightbox.v2.3.mootools.min.js"></script>
 <link rel="stylesheet" type="text/css" href="../../../../libreria_publica/sexy_lightbox/Mootools/sexylightbox.css"/>

  <script type="text/javascript">
    window.addEvent('domready', function(){
      SexyLightbox = new SexyLightBox({color:'black', dir: '../../../../libreria_publica/sexy_lightbox/Mootools/sexyimages/'});
    });
  </script>

    <!--INICIO MENU HORIZONTAL-->
    <script type="text/javascript" src="../../../../libreria_publica/jquery_libreria/jquery.min_1.2.6.js"></script>
    
 <link rel="stylesheet" type="text/css" href="../../../../libreria_publica/menu_horizontal/ddsmoothmenu-v.css"/>  
  <link rel="stylesheet" type="text/css" href="../../../../libreria_publica/menu_horizontal/ddsmoothmenu.css"/>
 
<script type="text/javascript" src="../../../../libreria_publica/menu_horizontal/ddsmoothmenu.js">

/***********************************************
* Smooth Navigational Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>

<script type="text/javascript">

ddsmoothmenu.init({
	mainmenuid: "smoothmenu1", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu', //class added to menu's outer DIV
	//customtheme: ["#1c5a80", "#18374a"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

ddsmoothmenu.init({
	mainmenuid: "smoothmenu2", //Menu DIV id
	orientation: 'v', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu-v', //class added to menu's outer DIV
	//customtheme: ["#804000", "#482400"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

</script>
<!--FIN MENU HORIZONTAL-->
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:22px;
	z-index:1;
	left: 5%;
	top: 123px;
}
-->
</style>
<style type="text/css" title="currentStyle">
@import "../../../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_table.css";
@import "../../../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/examples/examples_support/themes/smoothness/jquery-ui-1.8.4.custom.css";
.Estilo1 {
	font-size: 12px;
	font-weight: bold;
}
#apDiv1 #frm #cancelaciones {
	border: thin solid #0000FF;
	padding: 5px;
}
#apDiv1 #frm #msj {
	padding: 5px;
	border: thin solid #FFFF00;
	margin-top: 10px;
	margin-right: 0px;
	margin-bottom: 0px;
	margin-left: 0px;
}
</style>

<script type="text/javascript" language="javascript" src="../../../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../../../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="ISO-8859-1">
			$(document).ready(function() {
				oTable = $('#example').dataTable({
					"bJQueryUI": true,
					"bPaginate": false
				});
			} );
		</script>
<script language="javascript">
function VERIFICAR()
{
	c=confirm('Seguro(a) Que desea Pagar o dar por pagada(s) la(s) Factura(s) Seleccionada(s)?');
	if(c)
	{
		document.frm.submit();
	}
}
function ELIMINAR(codigo)
{
	d=confirm('Seguro(a) desea Eliminar esta Factura');
	if(d)
	{
		window.location="../eliminar/elimina_factura.php?id="+codigo;
	}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Facturas</h1>

<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Facturas</a>
  <ul>
 	 <li><a href="../nva_factura/nva_factura_1.php">Nueva</a></li>
     <li><a href="#" onclick="VERIFICAR();">Pagar</a></li>
     <li><a href="ver_factura.php?ver=todas">Ver lista Completa</a></li>
  </ul>
</li>
<li><a href="#">Informes</a>
  <ul>
 	 <li><a href="../../listadores/lista_factura.php"><strong>Listado</strong></a></li>
   </ul>
</li>
<li><a href="../../../index.php">Volver al Menu</a></li>
</ul>
<br style="clear: left" />
</div> 
<div id="apDiv1" class="demo_jui">
<form action="../cancelaciones/multi_pago_fact_1.php" method="post" name="frm" id="frm">
 <table width="87%" border="0" cellpadding="0" cellspacing="0" class="display" id="example">
 <summary>Facturas Ingresadas</summary>
  <thead>
    <tr>
    <th>-</th>	
      <th>N</th>
      <th>Sede</th>
      <th>Fecha Ingreso</th>
      <th>Proveedor</th>
      <th>Numero</th>
      <th>Fecha Vencimiento</th>
      <th>Movimiento</th>
      <th>Condicion</th>
      <th>Valor</th>
      <th>Saldo</th>
      <th>Abono</th>
      <th colspan="3">Opciones</th>
    </tr>
    </thead>
    <tbody>
    <?php

    require("../../../../../funciones/conexion_v2.php");
	include("../../../../../funciones/funcion.php");
	
	if(isset($_GET["ver"])){$ver=mysqli_real_escape_string($conexion_mysqli, $_GET["ver"]);}
	else{$ver="";}
	
	if($ver=="todas"){$limite="";}
	else
	{$limite="LIMIT 0, 50";}
	
	$cons="SELECT * FROM facturas $condicion_sede ORDER by id desc $limite";
	if(DEBUG){ echo"$cons<br>";}
	$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_reg=$sql->num_rows;
	if($num_reg>0)
	{
		$aux=0;
		while($F=$sql->fetch_assoc())
		{
			$aux++;
			$id_factura=$F["id"];
			$cod_factura=$F["cod_factura"];
			$fecha_ingreso=$F["fecha_ingreso"];
			$proveedor=$F["proveedor"];
			$id_proveedor=$F["id_proveedor"];
			$fecha_vencimiento=$F["fecha_vencimiento"];
			$condicion=$F["condicion"];
			$valor=$F["valor"];
			$saldo=$F["saldo"];
			$abono=$F["abono"];
			$sede=$F["sede"];
			$movimiento=$F["movimiento"];
			
			/////////////////////////////////////////
			if($id_proveedor>0)
			{
				$cons_P="SELECT razon_social FROM proveedores WHERE id_proveedor='$id_proveedor' LIMIT 1";
				$sql_P=$conexion_mysqli->query($cons_P)or die($conexion_mysqli->error);
					$DP=$sql_P->fetch_assoc();
					$P_razon_social=$DP["razon_social"];
					$proveedor=$P_razon_social;
				$sql_P->free();	
			}
		
			
			if($movimiento=="I")
			{ $clase="gradeA";}
			else
			{ $clase="gradeX";}
			
			echo'<tr class="'.$clase.'">
					<td height="30">';
					if(($condicion!="cancelada")and($movimiento=="I"))
					{ echo'<input name="id_F[]" type="checkbox" value="'.$id_factura.'" />';}
					else
					{ echo'-';}	
					
					switch($condicion)
					{
						case"cancelada":
							if($movimiento=="I")
							{$boton_condicion='<a href="#" class="button_R">'.$condicion.'</a>';}
							else
							{$boton_condicion='<a href="#" class="button_R">'.$condicion.'</a>';}
							break;
						default:
							if($movimiento=="I")
							{$boton_condicion='<a href="../cancelaciones/multi_pago_fact_1.php?id_factura='.$id_factura.'" class="button" title="click para cambiar condicion">'.$condicion.'</a>';}
							else{$boton_condicion='<a href="../edicion/cambio_condicion/cambio_condicion_1.php?id_factura='.$id_factura.'&TB_iframe=true&height=400&width=500" rel="sexylightbox" class="button" title="click para cambiar condicion">'.$condicion.'</a>';}
							break;
					}
					
					echo'</td>
					<td>'.$aux.'</td>
					<td>'.$sede.'</td>
					<td align="center">'.fecha_format($fecha_ingreso).'</td>
					<td>'.$proveedor.'</td>
					<td align="right">'.$cod_factura.'</td>
					<td align="center">'.fecha_format($fecha_vencimiento).'</td>
					<td align="center">'.$movimiento.'</td>
					<td>'.$boton_condicion.'</td>
					<td align="right">'.number_format($valor,0,",",".").'</td>
					<td align="right">'.number_format($saldo,0,",",".").'</td>
					<td align="right">'.number_format($abono,0,",",".").'</td>';
					if($mostrar_edicion)
					{
					echo'<td>
					<a href="../edicion/edit_factura_1.php?id='.base64_encode($id_factura).'" title="Editar"><img src="../../../../BAses/Images/b_edit.png" /></a></td>
					<td><a href="#" onclick="ELIMINAR(\''.base64_encode($id_factura).'\')" title="Eliminar"><img src="../../../../BAses/Images/b_drop.png" /></a></td>';
					}
					else
					{ 
						echo'<td>&nbsp;</td>
						<td>&nbsp;</td>';
					}
					echo'
					<td><a href="../ver_pagos/ver_pago_factura.php?id='.base64_encode($id_factura).'" title="Ver"><img src="../../../../BAses/Images/ojo2.png" alt="ver" width="25" height="18" /></a></td>
	  </tr>';
		}
	}
	else
	{
		//sin datos
	}
	?>
    </tbody>
    <tfoot>
    	<tr bgcolor="#e5e5e5">
        	<td colspan="8">&nbsp;</td>
        	<td colspan="5" align="right">&nbsp;</td>
        </tr>
    </tfoot>
  </table>
  
  <div id="cancelaciones"> </div>
  <?php
  
  $conexion_mysqli->close();
  $msj="";
  $img=array(""=>"");
  $tipo_img="";
  	if($_GET)
	{
		$img["buena"]='<img src="../../../../BAses/Images/ok.png" alt="ok" />';
		$img["mala"]='<img src="../../../../BAses/Images/b_drop.png" alt="X" />';
		$error=$_GET["error"];
		switch($error)
		{
			case"0":
				$msj="Factura Registrada...";
				$tipo_img="buena";
				break;
			case"1":
				$msj="Error al Registrar Factura...";
				$tipo_img="mala";
				break;
			case"2":
				$msj="Factura Eliminada Correctamente...";
				$tipo_img="buena";
				break;
			case"3":
				$msj="Error al Intentar Eliminar la Factura";
				$tipo_img="mala";
				break;
			case"4":
				$msj="Factura Modificada Correctamente...";
				$tipo_img="buena";
				break;
			case"5":
				$msj="Error al Intentar Modificar la Factura";
				$tipo_img="mala";
				break;					
			case"6":
				$msj="Transaccion Registrada Exitosamente";
				$tipo_img="buena";
				break;	
		}
	}
  ?>
  <div id="msj">*<?php echo $msj;?> <?php echo $img[$tipo_img];?>*</div>
</form>
</div>
</body>
</html>