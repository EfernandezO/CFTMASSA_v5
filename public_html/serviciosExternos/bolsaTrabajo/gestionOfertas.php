<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("bolsaTrabajoV1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>Bolsa de Trabajo</title>

<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/jquery.treeview.css"/>
 <!--INICIO MENU HORIZONTAL-->
 <script type="text/javascript" src="../../libreria_publica/jquery_libreria/jquery.min_1.2.6.js"></script>
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

</script>
<script>
function CONFIRMAR(id){
	c=confirm('Seguro(a) desea Eliminar...?')
	if(c){ window.location="eliminaOferta/eliminaOferta.php?id="+id;}
}
</script>
<!--FIN MENU HORIZONTAL-->
	<style type="text/css">
<!--

#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 153px;
}
-->
    </style>
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">    </head>
	<body>
	
	<h1 id="banner">Administrador - Bolsa de Trabajo</h1>
    
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Gestion</a>
  <ul>
  <li><a href="nvaOferta/nueva1.php">Agregar Oferta</a></li>
  </ul>
</li>
<li><a href="../../Administrador/ADmenu.php">Volver al Menu</a></li>
</ul>
<br style="clear: left" />
</div> 
	<div id="apDiv1">
    <table width="100%">
    <thead>
    	<tr>
        	<th colspan="4">Ofertas Disponibles</th>
        </tr>
    </thead>
    <tbody>
    	<?php
        	 require("../../../funciones/conexion_v2.php");
			 $cons="SELECT * FROM bolsaTrabajo ORDER by id DESC";
			 $sqli=$conexion_mysqli->query($cons);
			 while($BT=$sqli->fetch_assoc()){
				 $auxid=$BT["id"];
				 $auxTitulo=$BT["titulo"];
				 $auxCuerpo=$BT["cuerpo"];
				 $auxFecha=$BT["fechaGeneracion"];
				 
				 echo'<tr>
				 		<td>'.$auxTitulo.'</td>
						<td>'.$auxFecha.'</td>
						<td><a href="#" onClick="CONFIRMAR('.$auxid.')">Eliminar</a></td>
						<td><a href="edicionOferta/edicion1.php?id='.$auxid.'">Editar</a></td>
				 	 </tr>';
				 
			 }
			 $sqli->free();
			 
			 $conexion_mysqli->close();
		?>
        </tbody>
        </table>
        <?php
		$msj="";
        if(isset($_GET["error"])){
			$error=$_GET["error"];
			
			switch($error){
				case"0":
					$msj="Oferta Guardada...";
					break;
				case"1":
					$msj="Error al guardar oferta";
					break;	
			}
			
		}
		echo $msj;
		?>
    </div>
</body>
</html>