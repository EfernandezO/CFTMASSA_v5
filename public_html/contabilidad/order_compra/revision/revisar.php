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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Revision Orden de Compra</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">

 <!--INICIO MENU HORIZONTAL-->
  <link rel="stylesheet" type="text/css" href="../../../libreria_publica/menu_horizontal/ddsmoothmenu.css"/>
<script type="text/javascript" src="../../../libreria_publica/jquery_libreria/jquery.min_1.2.6.js"></script> 
<script type="text/javascript" src="../../../libreria_publica/menu_horizontal/ddsmoothmenu.js">

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
  <script type="text/javascript" src="../../../libreria_publica/sexy_lightbox/jQuery/jquery.easing.1.3.js"></script>
  <script type="text/javascript" src="../../../libreria_publica/sexy_lightbox/jQuery/sexylightbox.v2.3.jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/sexy_lightbox/jQuery/sexylightbox.css"/>
  <script type="text/javascript">
    $(document).ready(function(){
      SexyLightbox.initialize({color:'black', dir: '../../../libreria_publica/sexy_lightbox/jQuery/sexyimages'});
    });
  </script>
  
  <style type="text/css" title="currentStyle">
	@import "../../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_table_jui.css";
	@import "../../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/examples/examples_support/themes/smoothness/jquery-ui-1.8.4.custom.css";
</style>
<script type="text/javascript" language="javascript" src="../../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		oTable = $('#example').dataTable({
			"aaSorting": [[ 0, "desc" ]],
			"bJQueryUI": true});
	} );
</script>
  
<script language="javascript">
function ELIMINAR(id_oc)
{
	url="../eliminar/elimiar_oc.php?id_oc="+id_oc;
	c=confirm('Seguro(a) Desea Elimiar esta Orden de compra...?');
	if(c){window.location=url;}
}
</script>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 78px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:68px;
	z-index:1;
	left: 5%;
	top: 140px;
}
#apDiv3 {
	position:absolute;
	width:25%;
	height:37px;
	z-index:2;
	left: 70%;
	top: 100px;
}
</style>
</head>
<body>
<h1 id="banner">Finanzas - Ordenes de Compra</h1>

<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
	<li><a href="#">Orden Compra</a>
      <ul>
     	 <li><a href="../nueva/orden_compra_1.php">Nueva</a></li>
      </ul>
    </li>
	<li><a href="#">.</a></li>
	<li><a href="../../index.php">Volver al Menu</a></li>
</ul>
</li>
</ul>
<br style="clear: left" />
</div>

<div id="apDiv2">
<div class="demo_jui">
<table width="80%" align="center" class="display" id="example">
	<thead>
        <tr>
        	<th><strong>id</strong></th>
            <th><strong>Fecha</strong></th>
            <th><strong>Rut</strong></th>
             <th><strong>proveedor</strong></th>
             <th><strong>sede</strong></th>
             <th><strong>solicitante</strong></th>
             <th><strong>descripcion</strong></th>
             <th><strong>autorizado</strong></th>
             <th colspan="2"><strong>opciones</strong></th>
        </tr>
	</thead>
	<tbody>
        <?php
		$privilegio=$_SESSION["USUARIO"]["privilegio"];
		$id_usuario_actual=$_SESSION["USUARIO"]["id"];
		
        require("../../../../funciones/conexion_v2.php");
		$cons="SELECT * FROM orden_compra ORDER by id_oc desc";
		$sql = $conexion_mysqli->query($cons);
		$num_registros=$sql->num_rows;
		if(DEBUG){ echo"--> $cons<br>Num: $num_registros<br>";}
		$clase_boton="";
		if($num_registros>0)
		{
			while($OC=$sql->fetch_assoc())
			{
				$OC_id=$OC["id_oc"];
				$OC_id_proveedor=$OC["id_proveedor"];
				$OC_sede=$OC["sede"];
				$OC_id_solicitante=$OC["id_solicitante"];
				$OC_descripcion=$OC["descripcion"];
				$OC_fecha_creacion=$OC["fecha_creacion"];
				///----------------------------------------//
				///determino que mostrar
				switch($privilegio)
				{
					case"matricula":
						///muestra solo oc del mismo usuario
						if($id_usuario_actual==$OC_id_solicitante)
						{ $mostrar_oc=true;}
						else
						{ $mostrar_oc=true;}
						$permite_autorizar=false;
						break;
					case"admi_total":
					//mustra todas oc
						$mostrar_oc=true;
						$permite_autorizar=true;
						break;
					case"finan":
						///muestra todas oc
						$mostrar_oc=true;
						$permite_autorizar=false;
						break;
					default:
						$mostrar_oc=false;	
						$permite_autorizar=false;
				}
				
				//-----------------------------------------------//
				//datos proveedor
					$cons_P="SELECT * FROM proveedores WHERE id_proveedor='$OC_id_proveedor' LIMIT 1";
					$sql_P=$conexion_mysqli->query($cons_P);
					$DP=$sql_P->fetch_assoc();
						$proveedor_razon_social=$DP["razon_social"];
						$proveedor_rut=$DP["rut"];
						$proveedor_direccion=$DP["direccion"];
						$proveedor_ciudad=$DP["ciudad"];
						if(DEBUG){ echo"--->$cons_P<br> $proveedor_rut-$proveedor_razon_social<br>";}
					$sql_P->free();	
					//-----------------------------------------------------//
					//datos presonal
					$cons_PERS="SELECT nombre, apellido_P, apellido_M FROM personal WHERE id='$OC_id_solicitante' LIMIT 1";
					$sql_PERS=$conexion_mysqli->query($cons_PERS);
					$DPERS=$sql_PERS->fetch_assoc();
						$personal_nombre=$DPERS["nombre"]." ".$DPERS["apellido_P"]." ".$DPERS["apellido_M"];
					$sql_PERS->free();	
					//------------------------------------------------------//
				//buscar autorizacion
				$cons_autorizacion="SELECT * FROM autorizaciones WHERE tipo_autorizado='orden_compra' AND id_autorizado='$OC_id' LIMIT 1";
				$sql_autorizacion=$conexion_mysqli->query($cons_autorizacion);
				$num_autorizaciones=$sql_autorizacion->num_rows;
				if($num_autorizaciones>0)
				{
					$A=$sql_autorizacion->fetch_assoc();
						$A_autorizado=strtolower($A["autorizado"]);
						$A_id_usuario=$A["id_usuario"];
						$A_fecha_generacion=$A["fecha_generacion"];
						
						if($A_autorizado=="si")
						{ $OC_autorizada="Si"; $clase_boton='class="button"'; $url_autorizada="#"; $rel=""; $boton_eliminar='#';}
						else
						{ 
							$OC_autorizada="No"; 
							$clase_boton='class="button_R"'; 
							$rel='rel="sexylightbox"'; 
							$boton_eliminar='<a href="#" onclick="ELIMINAR(\''.base64_encode($OC_id).'\');" title="Eliminar"><img src="../../../BAses/Images/b_drop.png" width="16" height="16" alt="eliminar" /></a>';}
							if($permite_autorizar)
							{$url_autorizada='../operaciones/autorizar_oc_1.php?id_oc='.base64_encode($OC_id).'TB_iframe=true&height=300&width=450'; }
							else{$url_autorizada="#";}
				}
				else
				{ 
					$OC_autorizada="No"; 
					$clase_boton='class="button_R"'; 
					$rel='rel="sexylightbox"'; 
					$boton_eliminar='<a href="#" onclick="ELIMINAR(\''.base64_encode($OC_id).'\');" title="Eliminar"><img src="../../../BAses/Images/b_drop.png" width="16" height="16" alt="eliminar" /></a>';
					if($permite_autorizar)
					{$url_autorizada='../operaciones/autorizar_oc_1.php?id_oc='.base64_encode($OC_id).'TB_iframe=true&height=300&width=450'; }
					else{$url_autorizada="#";}
					
				}
				$sql_autorizacion->free();
				//------------------------------------------------------------------------------------------------------------//
				
				if($mostrar_oc)
				{
					echo'<tr>
							<td>'.$OC_id.'</td>
							<td>'.$OC_fecha_creacion.'</td>
							<td>'.$proveedor_rut.'</td>
							<td>'.$proveedor_razon_social.'</td>
							<td>'.$OC_sede.'</td>
							<td>'.$personal_nombre.'</td>
							<td>'.$OC_descripcion.'</td>
							<td align="center"><a href="'.$url_autorizada.'" '.$rel.' '.$clase_boton.'>'.$OC_autorizada.'</a></td>
							<td><a href="../ver/ver_oc.php?id_oc='.$OC_id.'" target="_black" title="Ver"><img src="../../../BAses/Images/pdf_icon.png" width="31" height="31" alt="pdf" /></a></td>
							<td>'.$boton_eliminar.'</td>
						 </tr>';
				}
			}
		}
		else
		{echo'<tr><td colspan="8">Sin Registros</td></tr>';}
		$sql->free();
		$conexion_mysqli->close();
		?>
     </tbody>
    </table>
</div>
</div>
<div id="apDiv3">
<?php
if(isset($_GET["error"]))
{
	$error=$_GET["error"];
	$img_ok='<img src="../../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
	$img_error='<img src="../../../BAses/Images/b_drop.png" width="16" height="16" alt="X" />';
	$img="";
	$msj="";
	switch($error)
	{
		case"OC_E0":
			$msj="Orden de Compra Eliminada...";
			$img=$img_ok;
			break;
		case"OC_E1":
			$msj="Error al Eliminar la Orden de Compra...";
			$img=$img_error;
			break;
	}
	echo $img.$msj;
}
?>
</div>
</body>
</html>