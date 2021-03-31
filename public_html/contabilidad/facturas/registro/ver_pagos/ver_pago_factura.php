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

if(isset($_GET["id"]))
{ $acceso=true;}
else
{ $acceso=false;}

if($acceso)
{
 require("../../../../../funciones/conexion_v2.php");
	$id_factura=mysql_real_escape_string(base64_decode($_GET["id"]));
	//datos factura
	$cons="SELECT * FROM facturas WHERE id='$id_factura' LIMIT 1";
	if(DEBUG){ echo"$cons<br>";}
	$sql=mysql_query($cons)or die(mysql_error());
		$F=mysql_fetch_assoc($sql);
			$cod_factura=$F["cod_factura"];
			$fecha_ingreso=$F["fecha_ingreso"];
			$proveedor=$F["proveedor"];
			$proveedor_id=$F["id_proveedor"];
			$fecha_vencimiento=$F["fecha_vencimiento"];
			$condicion=$F["condicion"];
			$valor=$F["valor"];
			$sede=$F["sede"];
			$movimiento=$F["movimiento"];
			$F_id_oc=$F["id_oc"];
			if($F_id_oc>0){ $hay_oc=true;}
			else{ $hay_oc=false;}
	mysql_free_result($sql);
	/////////////////////////////////////////
			if($proveedor_id>0)
			{
				$cons_P="SELECT razon_social FROM proveedores WHERE id_proveedor='$proveedor_id' LIMIT 1";
				$sql_P=mysql_query($cons_P)or die(mysql_error());
					$DP=mysql_fetch_assoc($sql_P);
					$P_razon_social=$DP["razon_social"];
					$proveedor=$P_razon_social;
				mysql_free_result($sql_P);	
			}
	//datos orden compra
	if($hay_oc)
	{
		$cons_OC="SELECT * FROM orden_compra WHERE id_oc='$F_id_oc' LIMIT 1";
		$sql_OC=$conexion_mysqli->query($cons_OC);
		$DOC=$sql_OC->fetch_assoc();
			$OC_fecha_creacion=$DOC["fecha_creacion"];
			$OC_unidad_solicitante=$DOC["unidad_solicitante"];
			$OC_descripcion=$DOC["descripcion"];
		$msj_oc="Tiene orden de Compra relacionada... ";
		$link='<a href="../../../order_compra/ver/ver_oc.php?id_oc='.$F_id_oc.'" target="_blank" title="Ver Orden de Compra" class="button_R">Ver Orden Compra</a>';
		$msj_oc.=$link;
		$sql_OC->free();
		//-------------------------------------------//
		//item
		$cons_item="SELECT * FROM orden_compra_item WHERE id_oc='$F_id_oc'";
		$sql_item=$conexion_mysqli->query($cons_item);
		$num_item=$sql_item->num_rows;
		if($num_item>0)
		{
			$TOTAL=0;
			while($I=$sql_item->fetch_assoc())
			{
				$I_cantidad=$I["cantidad"];
				$I_valor_unitario=$I["valor_unitario"];
				$aux_total=($I_cantidad*$I_valor_unitario);
				$TOTAL+=$aux_total;
			}
		}
		else
		{ $TOTAL=0;}
		$sql_item->free();
	}
	else
	{ $msj_oc="Sin Orden de Compra Relacionada"; $num_item=0; $TOTAL=0;}
	
}
else
{ header("location: ../ver/ver_factura.php");}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php");?>
<title>detalle pagos de factura</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css">
<script type="text/javascript" src="../../../../libreria_publica/jquery_libreria/jquery.min_1.2.6.js"></script>
<style type="text/css">
<!--
#Layer2 {
	position:absolute;
	width:90%;
	height:141px;
	z-index:2;
	left: 5%;
	top: 413px;
}
#apDiv1 {
	position:absolute;
	width:35%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 95px;
}
#apDiv2 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:3;
	left: 54px;
	top: 435px;
}
#apDiv3 {
	position:absolute;
	width:90%;
	height:58px;
	z-index:3;
	left: 5%;
	top: 320px;
}
.ex1 img{
    border: 5px solid #ccc;
    float: left;
    margin: 15px;
    -webkit-transition: margin 0.5s ease-out;
    -moz-transition: margin 0.5s ease-out;
    -o-transition: margin 0.5s ease-out;
}

.ex1 img:hover {
    margin-top: 2px;
}
#apDiv4 {
	position:absolute;
	width:40%;
	height:115px;
	z-index:4;
	left: 42%;
	top: 102px;
}
-->
</style>
 <!--INICIO MENU HORIZONTAL-->
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

</script>
<!--FIN MENU HORIZONTAL-->
<!--INICIO LIGHTBOX EVOLUTION-->
  <script type="text/javascript" src="../../../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
 <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox(); 
    });
  </script>
 <!--FIN LIGHTBOX EVOLUTION--> 
</head>

<body>
<h1 id="banner">Administrador -Facturas</h1>
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Opciones</a>
  <ul>
  <li><a href="../carga_img_factura/index.php?id_factura=<?php echo $id_factura;?>&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=510"  class="lightbox">Cargar Archivos</a></li>
  </ul>
</li>
</li>
<li><a href="../ver/ver_factura.php">Volver al Menu</a></li>
</ul>
<br style="clear: left" />
</div> 
<div id="apDiv1"><table width="100%" align="left">

    <thead>
      <tr>
        <th colspan="2" ><span class="Estilo6">informacion de Factura</span></th>
      </tr>
    </thead>
    <thead>
    </thead>
    <tbody>
      <tr>
        <td width="35%" ><span class="Estilo6">ID Factura: </span></td>
        <td width="65%" ><span class="Estilo7"><?php echo"$id_factura";?></span></td>
      </tr>
      <tr >
        <td width="35%" ><span class="Estilo6">COD Factura: </span></td>
        <td width="65%" ><span class="Estilo7"><?php echo $cod_factura;?></span></td>
      </tr>
      <tr >
        <td >Proveedor</td>
        <td ><span class="Estilo7"><?php echo $proveedor;?></span></td>
      </tr>
      <tr>
        <td >Movimiento</td>
        <td ><span class="Estilo7"><?php echo $movimiento;?></span></td>
      </tr>
      <tr >
        <td >Condicion</td>
        <td ><span class="Estilo7"><?php echo $condicion;?></span></td>
      </tr>
      <tr>
        <td >Valor</td>
        <td ><span class="Estilo7"><?php echo $valor;?></span></td>
      </tr>
      
    </tbody>
  </table></div>
<div id="Layer2"> 
         <?php
		 $preview=array();
		 $cons_I="SELECT * FROM facturas_imagenes WHERE id_factura='$id_factura'";
	$sql_I=mysql_query($cons_I)or die(mysql_error());
	$num_imagenes=mysql_num_rows($sql_I);
	$path='../../../../CONTENEDOR_GLOBAL/facturas/';

	if($num_imagenes>0)
	{
		$aux=0;
		while($I=mysql_fetch_assoc($sql_I))
		{
			$aux++;
			
			$archivo=$I["archivo"];
			if(DEBUG){ echo"$archivo<br>";}
			
			$ruta=$path.$archivo;
			$preview[$aux-1]=$ruta;
		}
	}
	else
	{
		if(DEBUG){ echo"Sin Archivos...<br>";}
	}
?>
<table width="100%" align="left" sumary="">
    <thead>
      <tr>
        <th width="13%" rowspan="2" ><div align="center" class="Estilo6">Monto Pagado</div></th>
        <th width="15%" rowspan="2" ><div align="center" class="Estilo6">Fecha Transaccion </div></th>
        <th width="13%" rowspan="2" ><div align="center" class="Estilo6">Comentario</div></th>
        <th width="13%" rowspan="2" ><div align="center" class="Estilo6">Movimiento</div></th>
        <th height="23" colspan="5" ><div align="center" class="Estilo6">Forma Pago</div></th>
      </tr>
      <tr>
        <th width="13%" class="Estilo7" ><div align="center">Forma pago</div></th>
        <th width="8%" class="Estilo7" ><div align="center">Numero</div></th>
        <th width="6%" class="Estilo7" ><div align="center">Banco</div></th>
        <th width="11%" class="Estilo7" ><div align="center">Vencimiento</div></th>
        <th width="8%" class="Estilo7" ><div align="center">Valor</div></th>
      </tr>
    </thead>
    <tbody>
      <?php
	  include("../../../../../funciones/funcion.php");
	  $cons_p="SELECT * FROM pagos WHERE id_factura='$id_factura' AND tipodoc='factura' ORDER by fechapago";
	  $sql=mysql_query($cons_p)or die(mysql_error());
	  $num_reg=mysql_num_rows($sql);
	  if($num_reg>0)
	  {
	  	$aux=0;
	   while($C=mysql_fetch_array($sql))
	   {
	   		
			$aux++;
			
			$valor_pago=$C["valor"];
			$fecha_pago=$C["fechapago"];
			$glosa=$C["glosa"];
			$opcion_pago=$C["forma_pago"];
			$id_cheque=$C["id_cheque"];
			$movimiento=$C["movimiento"];
			switch($opcion_pago)
			{
				case"cheque":
					$cons_ch="SELECT * FROM registro_cheques WHERE id='$id_cheque' LIMIT 1";
					$sql_ch=mysql_query($cons_ch)or die(mysql_error());
					$D_CH=mysql_fetch_assoc($sql_ch);
						$cheque_numero=$D_CH["numero"];
						$cheque_banco=$D_CH["banco"];
						$cheque_vencimiento=fecha_format($D_CH["fecha_vencimiento"]);
						$cheque_valor=number_format($D_CH["valor"],0,",",".");
					break;
				case"efectivo":
						$cheque_numero="---";
						$cheque_banco="---";
						$cheque_vencimiento="---";
						$cheque_valor=$valor_pago;
					break;
			}
			echo'<tr>
				<td><span class="Estilo7">$'.number_format($valor_pago,0,",",".").'</span></td>
				<td><span class="Estilo7">'.fecha_format($fecha_pago).'</span></td>
				<td><span class="Estilo7">'.$glosa.'</span></td>
				<td><span class="Estilo7">'.$movimiento.'</span></td>
				<td><span class="Estilo9">'.$opcion_pago.'</span></td>
				<td><span class="Estilo9">'.$cheque_numero.'</span></td>
				<td><span class="Estilo9">'.$cheque_banco.'</span></td>
				<td><span class="Estilo9">'.$cheque_vencimiento.'</span></td>
				<td><span class="Estilo9">$'.$cheque_valor.'</span></td>
				</tr>';
			
	   }
	  }
	  else
	  { ?>
      <tr>
        <td colspan="9"><div align="center" class="Estilo3">Factura sin Movimientos asociados...</div></td>
      </tr>
      <?php }
	  mysql_free_result($sql);
	  mysql_close($conexion);
	   ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="9">&nbsp;</td>
      </tr>
    </tfoot>
  </table>
</div>
<div id="apDiv3" class="ex1">
<?php
$num_imagenes=count($preview);
if($num_imagenes>0)
{
	echo"<strong>Archivos Relacionados...</strong><br>";
	foreach($preview as $n => $valor)
	{
		@$extencion=end(explode(".",$valor));
		
		if(DEBUG){echo"$n -> $extencion<br>";}
		
		
		echo'<a href="'.$valor.'?lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=510"  class="lightbox" ><img src="../../../../BAses/Images/pdf_icon.png" width="31" height="31" alt="pdf" /></a> ';
		
	}
}
else
{
	echo"Sin Imagenes Relacionadas :(<br>";
}
?>
</div>
<div id="apDiv4">
  <table width="100%" border="1">
  <THEAD>	
    <tr>
      <th colspan="2">Orden de Compra</th>
    </tr>
    </THEAD>
    <Tbody>
    <tr>
      <td width="40%">ID</td>
      <td width="60%"><?php echo $F_id_oc;?></td>
    </tr>
    <tr>
      <td>Cantidad de item</td>
      <td><?php echo $num_item;?></td>
    </tr>
    <tr>
      <td>Total</td>
      <td><?php echo $TOTAL;?></td>
      </tr>
    <tr>
      <td height="40" colspan="2"><?php echo $msj_oc;?></td>
      </tr>
    </Tbody>
  </table>
</div>
</body>
</html>