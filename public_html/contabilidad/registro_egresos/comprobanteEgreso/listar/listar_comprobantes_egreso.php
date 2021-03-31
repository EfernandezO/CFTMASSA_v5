<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("registra_egresos");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
/////////////////////////////

$privilegio=$_SESSION["USUARIO"]["privilegio"];
$sede_usuario=$_SESSION["USUARIO"]["sede"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php");?>
<title>Comprobantes Egreso</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">

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
<h1 id="banner">Administrador - Comprobantes Egreso</h1>

<div id="apDiv1" class="demo_jui">
 <table width="87%" border="0" cellpadding="0" cellspacing="0" class="display" id="example">
 <summary>Comprobantes de Ingreso</summary>
  <thead>
    <tr>	
      <th >N</th>
      <th>ID Comprobante</th>
      <th >Tipo Proveedor</th>
      <th>Nombre</th>
      <th >Sede</th>
      <th>Valor</th>
      <th>Fecha</th>
      <th>Opciones</th>
    </tr>
    </thead>
    <tbody>
    <?php
    require("../../../../../funciones/conexion_v2.php");
	require("../../../../../funciones/funcion.php");
	$cons_A="SELECT * FROM comprobante_egreso ORDER by id_comprobante DESC";
	$sql_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
	$num_reg=$sql_A->num_rows;
		$aux=0;
		while($CE=$sql_A->fetch_assoc()){
			$aux++;
			$CE_id=$CE["id_comprobante"];
			$CE_id_proveedor=$CE["id_proveedor"];
			$CE_tipo_proveedor=$CE["tipo_proveedor"];
			$CE_sede=$CE["sede"];
			$CE_valor=$CE["valor"];
			$CE_glosa=$CE["glosa"];
			$CE_cod_user=$CE["cod_user"];
			$CE_fecha_generacion=$CE["fecha_generacion"];
			$CE_fecha=$CE["fecha"];
		
		
		switch($CE_tipo_proveedor)
		{
			case"proveedor":
				$cons_x="SELECT * FROM proveedores WHERE id_proveedor='$CE_id_proveedor' LIMIT 1";
				$sqli_x=$conexion_mysqli->query($cons_x);
					$DP=$sqli_x->fetch_assoc();
						$rut_proveedor=$DP["rut"];
						$razon_social=$DP["razon_social"];
				$sqli_x->free();		
				break;
			case"personal":
				$cons_x="SELECT * FROM personal WHERE id='$CE_id_proveedor' LIMIT 1";
				$sqli_x=$conexion_mysqli->query($cons_x);
					$DPE=$sqli_x->fetch_assoc();
						$rut_personal=$DPE["rut"];
						$nombre_personal=$DPE["nombre"];
						$apellido_paterno=$DPE["apellido_P"];
						$apellido_materno=$DPE["apellido_M"];
						
						$rut_proveedor=$rut_personal;
						$razon_social=$nombre_personal." ".$apellido_paterno." ".$apellido_materno;
				$sqli_x->free();		
				break;	
		}
		
		echo'<tr>
				<td>'.$aux.'</td>
				<td>'.$CE_id.'</td>
				<td>'.$CE_tipo_proveedor.'</td>
				<td>'.$razon_social.'</td>
				<td>'.$CE_sede.'</td>
				<td align="right">$'.$CE_valor.'</td>
				<td>'.$CE_fecha.'</td>
				<td><a href="../ver/comprobante_egreso_pdf.php?id_comprobante_egreso='.$CE_id.'" target="_blank">Ver</a></td>
			 </tr>';
	}
	$sql_A->free();
	$conexion_mysqli->close();
	?>
    </tbody>
  </table>
  
  <div id="cancelaciones"> </div>
  <?php
  $msj="";
  $img=array(""=>"");
  $tipo_img="";
  	if(isset($_GET["error"]))
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
  <div id="msj">**</div>
</div>
</body>
</html>