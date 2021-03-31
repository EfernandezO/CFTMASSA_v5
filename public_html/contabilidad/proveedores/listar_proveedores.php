<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_proveedores_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
/////////////////////////////

$privilegio=$_SESSION["USUARIO"]["privilegio"];
$sede_usuario=$_SESSION["USUARIO"]["sede"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Proveedores</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
    <!--INICIO MENU HORIZONTAL-->
    <script type="text/javascript" src="../../libreria_publica/jquery_libreria/jquery.min_1.2.6.js"></script>
 <link rel="stylesheet" type="text/css" href="../../libreria_publica/menu_horizontal/ddsmoothmenu-v.css"/>  
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/menu_horizontal/ddsmoothmenu.css"/>
<script type="text/javascript" src="../../libreria_publica/menu_horizontal/ddsmoothmenu.js">

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
@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_table.css";
@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/examples/examples_support/themes/smoothness/jquery-ui-1.8.4.custom.css";
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

<script type="text/javascript" language="javascript" src="../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="ISO-8859-1">
			$(document).ready(function() {
				oTable = $('#example').dataTable({
					"bJQueryUI": true,
					"bPaginate": true
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
function ELIMINAR(id_proveedor)
{
	url="elimina/elimina_proveedor.php?id_proveedor="+id_proveedor;
	c=confirm('Seguro(a) Desea Eliminar este Proveedor');
	if(c)
	{
		d=confirm("Realmente seguro(a) que desea Eliminar este Proveedor\n si hay facturas o pagos relacionados a este proveedor se generará un problema\n realice esta accion con precaucion...");
		if(d)
		{window.location=url;}
	}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Proveedores</h1>

<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Proveedor</a>
  <ul>
 	 <li><a href="nvo_proveedor/nvo_proveedor_1.php">Nueva</a></li>
  </ul>
</li>
<li><a href="../index.php">Volver al Menu</a></li>
</ul>
<br style="clear: left" />
</div> 
<div id="apDiv1" class="demo_jui">
 <table width="87%" border="0" cellpadding="0" cellspacing="0" class="display" id="example">
 <summary>Proveedor</summary>
  <thead>
    <tr>	
      <th >N</th>
      <th >id Proveedor</th>
      <th >Rut</th>
      <th>Razon Social</th>
      <th>Direccion</th>
      <th>Ciudad</th>
      <th>Telefono</th>
      <th colspan="3">Opciones</th>
    </tr>
    </thead>
    <tbody>
    <?php
    require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funcion.php");
	$cons_P="SELECT * FROM proveedores ORDER by id_proveedor desc";
	$sql_P=$conexion_mysqli->query($cons_P);
	if(DEBUG){ echo"<strong>mysqli error: </strong>".$conexion_mysqli->error;}
	$aux=0;
	while($P=$sql_P->fetch_assoc())
	{
		$aux++;
		$P_id=$P["id_proveedor"];
		$P_razon_social=$P["razon_social"];
		$P_rut=$P["rut"];
		$P_direccion=$P["direccion"];
		$P_ciudad=$P["ciudad"];
		$P_telefono=$P["telefono"];
		
		echo'<tr>
				<td>'.$aux.'</td>
				<td>'.$P_id.'</td>
				<td align="right">'.$P_rut.'</td>
				<td>'.$P_razon_social.'</td>
				<td>'.$P_direccion.'</td>
				<td>'.$P_ciudad.'</td>
				<td>'.$P_telefono.'</td>
				<td><a href="edita_proveedor/edita_proveedor_1.php?id_proveedor='.$P_id.'" title="Editar"><img src="../../BAses/Images/b_edit.png" width="16" height="16" /></a></td>
				<td><a href="#" onclick="ELIMINAR('.$P_id.')">Eliminar</a></td>
				<td>-</td>
			 </tr>';
	}
	?>
    </tbody>
    <tfoot>
    	<tr bgcolor="#e5e5e5">
        	<td colspan="8">&nbsp;</td>
        	<td colspan="3" align="right">&nbsp;</td>
        </tr>
    </tfoot>
  </table>
  
  <div id="cancelaciones"> </div>
  <?php
  $msj="";
  $img=array(""=>"");
  $tipo_img="";
  	if($_GET)
	{
		$img["buena"]='<img src="../../BAses/Images/ok.png" alt="ok" />';
		$img["mala"]='<img src="../../BAses/Images/b_drop.png" alt="X" />';
		$error=$_GET["error"];
		switch($error)
		{
			case"PG0":
				$msj="Proveedor Registrado...";
				$tipo_img="buena";
				break;
			case"PG1":
				$msj="Error al Registrar Proveedor...";
				$tipo_img="mala";
				break;
			case"PG2":
				$msj="Proveedor Ya registrado en sistema...";
				$tipo_img="mala";
				break;	
			case"PG3":
				$msj="Proveedor Eliminado del sistema...";
				$tipo_img="mala";
				break;		
		}
	}
  ?>
  <div id="msj">*<?php echo $msj;?> <?php echo $img[$tipo_img];?>*</div>
</div>
</body>
</html>