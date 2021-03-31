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
<title>Beneficios Estudiantiless - CFTMASSA</title>
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
					"bPaginate": false});
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
<h1 id="banner">Administrador - Gesti&oacute;n de Beneficios</h1>
<div id="smoothmenu1" class="ddsmoothmenu">
  <ul>
<li><a href="#">Beneficios Estudiantiles</a>
	<ul>
  		<li><a href="nueva/nva_beca1.php">Nueva</a></li>
     </ul>
 </li>
</li>
<li><a href="#">Operaciones</a>
	<ul>
  		<li><a href="../asignar_beca_v2/index.php">Asignacion de Beca</a></li>
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
        	<th>-</th>
             <th>Condicion</th>
             <th>Familia Beneficio</th>
        	<th>Patrocinador</th>
            <th>Vigencia</th>
            <th>Duracion</th>
             <th>Procedencia</th>
			<th>Nombre</th>
			<th>Tipo Aporte</th>
			<th>Aporte Valor</th>
            <th>Aporte %</th>
            <th>Opcion</th>
		</tr>
	</thead>
	<tbody>
			<?php
            require("../../../../funciones/conexion_v2.php");
				$cons="SELECT * FROM beneficiosEstudiantiles";
				if(DEBUG){ echo"$cons<br>";}
				$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
				$num_reg=$sql->num_rows;
				if($num_reg>0)
				{
					while($B=$sql->fetch_assoc())
					{
						$B_id=$B["id"];
						$B_patrocinador=$B["patrocinador"];
						$B_vigencia=$B["vigencia"];
						$B_procedencia=$B["procedencia"];
						$B_nombre=$B["beca_nombre"];
						$B_tipo_aporte=$B["beca_tipo_aporte"];
						$B_aporte_valor=$B["beca_aporte_valor"];
						$B_aporte_porcentaje=$B["beca_aporte_porcentaje"];
						$B_condicion=$B["beca_condicion"];
						$B_familia=$B["familiaBeneficio"];
						$B_duracion=$B["duracion"];
						
						if($B_condicion=="activa")
						{ $class='class="gradeA"'; $boton='class="button"'; $aux_condicion="inactiva";}
						else
						{ $class='class="gradeX"'; $boton='class="button_R"'; $aux_condicion="activa";}
						
						echo'<tr '.$class.' height="34">
								<td>'.$B_id.'</td>
								<td><a href="#" '.$boton.' title="Click para pasar a '.$aux_condicion.'">'.$B_condicion.'</a></td>
								<td>'.$B_familia.'</td>
								<td>'.$B_patrocinador.'</td>
								<td>'.$B_vigencia.'</td>
								<td>'.$B_duracion.'</td>
								<td>'.$B_procedencia.'</td>
								<td>'.$B_nombre.'</td>
								<td>'.$B_tipo_aporte.'</td>
								<td>'.$B_aporte_valor.'</td>
								<td>'.$B_aporte_porcentaje.'</td>
								<td><a href="edicion/edit_beca1.php?id_beca='.base64_encode($B_id).'"><img src="../../../BAses/Images/b_edit.png" width="16" height="16" alt="Editar" title="Editar"></a>
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
			$msj="Beca modificada...";
			break;
	}
	
	echo $msj;
}
?>
</div>
</body>
</html>