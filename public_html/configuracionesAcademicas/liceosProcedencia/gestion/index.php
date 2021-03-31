<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
?>
<html>
<head>
<title>Liceos Procedencia - CFTMASSA</title>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/jquery.treeview.css">
	<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
    <style type="text/css">
<!--
.Estilo1 {	font-size: 12px;
	font-weight: bold;
}
#apDiv1 {
	position:absolute;
	width:90%;
	height:98px;
	z-index:1;
	left: 5%;
	top: 111px;
}
-->
    </style>
<style type="text/css" title="currentStyle">
			@import "../../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_table_jui.css";
			@import "../../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/examples/examples_support/themes/smoothness/jquery-ui-1.8.4.custom.css";
		</style>
		<script type="text/javascript" language="javascript" src="../../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				oTable = $('#example').dataTable({
					"bJQueryUI": true,
					"bPaginate": true});
			} );
		</script>
          <!--INICIO MENU HORIZONTAL-->
 <link rel="stylesheet" type="text/css" href="../../../libreria_publica/menu_horizontal/ddsmoothmenu-v.css"/>  
  <link rel="stylesheet" type="text/css" href="../../../libreria_publica/menu_horizontal/ddsmoothmenu.css"/>
 
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

ddsmoothmenu.init({
	mainmenuid: "smoothmenu2", //Menu DIV id
	orientation: 'v', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu-v', //class added to menu's outer DIV
	//customtheme: ["#804000", "#482400"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

</script>
<!--FIN MENU HORIZONTAL-->	
</head>

<body>
<h1 id="banner">Administrador - Liceos procedencia</h1>
<div id="smoothmenu1" class="ddsmoothmenu">
  <ul>
<li><a href="#">Liceos</a>
	<ul>
  		<li><a href="nueva/agregar1.php">Agregar</a></li>
     </ul>
 </li>
</li>
<li><a href="#">Operaciones</a>
	<ul>
  		<li><a href="#">opcion</a></li>
     </ul>
 </li>
</li>

<li><a href="../../index.php">Volver a Menu</a></li>
</ul>
<br style="clear: left" />
</div>
<div id="apDiv1"><div class="demo_jui">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
             <th>idLiceo</th>
        	<th>Region</th>
            <th>Comuna</th>
            <th>Nombre Establecimiento</th>
             <th>Dependencia</th>
			<th>RBD</th>
            <th>Opcion</th>
		</tr>
	</thead>
	<tbody>
			<?php
            require("../../../../funciones/conexion_v2.php");
				$cons="SELECT * FROM liceos";
				if(DEBUG){ echo"$cons<br>";}
				$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
				$num_reg=$sql->num_rows;
				if($num_reg>0)
				{
					while($L=$sql->fetch_assoc())
					{
						$L_idLiceo=$L["idLiceo"];
						$L_region=$L["region"];
						$L_comuna=$L["comuna"];
						$L_nombreEstablecimiento=$L["nombreEstablecimiento"];
						$L_dependencia=$L["dependencia"];
						$L_rbd=$L["rbd"];
						
						
						$class='class="gradeA"';
						
						echo'<tr '.$class.'>
								<td>'.$L_idLiceo.'</td>
								<td>'.$L_region.'</td>
								<td>'.$L_comuna.'</td>
								<td>'.$L_nombreEstablecimiento.'</td>
								<td>'.$L_dependencia.'</td>
								<td>'.$L_rbd.'</td>
								<td><a href="#"><img src="../../../BAses/Images/b_edit.png" width="16" height="16" alt="Editar" title="Editar"></a>
									<img src="../../../BAses/Images/b_drop.png" width="16" height="16" alt="Eliminar"></td>
							</tr>';
					}
				}
			$sql->free();	
			$conexion_mysqli->close();
			?>
	</tbody>
</table>
</div>
<?php
if(isset($_GET["error"]))
{
	$error=$_GET["error"];
	
	switch($error)
	{
		case"R3":
			$msj="modificada...";
			break;
	}
	
	echo $msj;
}
?>
</div>
</body>
</html>