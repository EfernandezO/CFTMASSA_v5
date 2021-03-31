<?php
//--------------CLASS_okalis------------------//
	require("../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->MAIN_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
 //////////////////////XAJAX/////////////////
@require_once ("../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("actualiza_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"ACTUALIZA_VALORES");
$xajax->register(XAJAX_FUNCTION,"PERMITE_EDITAR");
///////////////////////////////////////////////////////
$privilegio=$_SESSION["USUARIO"]["privilegio"];
switch($privilegio)
{
	case"admi_total":
		$permite_editar="si";
		$url_menu="../Administrador/ADmenu.php";
		break;
	case"admi":
		$permite_editar="no";
		$url_menu="../Administrador/ADmenu.php";
		break;
	case"finan":
		$permite_editar=true;
		$url_menu="../contabilidad/index.php";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../funciones/codificacion.php");?>
<title>Configuraciones Academicas</title>
	<link rel="stylesheet" type="text/css" href="../libreria_publica/jquery_treeview/demo/screen.css">
	<style type="text/css" title="currentStyle">
			@import "../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_page.css";
			@import "../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_table_jui.css";
			@import "../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/examples/examples_support/themes/smoothness/jquery-ui-1.8.4.custom.css";
</style>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:52px;
	z-index:1;
	left: 5%;
	top: 155px;
}
-->
</style>
<?php $xajax->printJavascript(); ?> 
<script type="text/javascript" language="javascript" src="../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="ISO-8859-1">
			$(document).ready(function() {
				oTable = $('#example').dataTable({
					"bJQueryUI": true,
					"bPaginate": false
				});
			} );
function CONFIRMAR(url)
{
	c=confirm('Seguro(a) Desea Eliminar esta Carrera');
	if(c)
	{
		window.location=url;
	}
}
</script>
    <!--INICIO MENU HORIZONTAL-->
  <link rel="stylesheet" type="text/css" href="../libreria_publica/menu_horizontal/ddsmoothmenu.css"/>
 
<script type="text/javascript" src="../libreria_publica/menu_horizontal/ddsmoothmenu.js">

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
</head>

<body>
<h1 id="banner">Administrador - Configuraciones Academicas</h1>
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Carreras</a>
  <ul>
  <li><a href="nva_carrera/ingreso_carrera_1.php">Agregar Nueva</a></li>
  </ul>
</li>
<li><a href="#">Fechas Academicas</a>
      <ul>
      <li><a href="fechasAcademicas/index.php">Revisar</a> </li>
      </ul>
  </li>
<li><a href="#">Beneficios Estudiantiles</a>
      <ul>
      <li><a href="beneficiosEstudiantiles/gestion/index.php">Revisar</a> </li>
      </ul>
  </li>  
  <li><a href="#">Liceos Procedencia</a>
      <ul>
      <li><a href="liceosProcedencia/gestion/index.php">Revisar</a> </li>
      </ul>
  </li>  
  <li> <a href="<?php echo $url_menu;?>">Volver al Menu</a></li>
  </ul>
 
<br style="clear: left" />
</div>
<h3>Administre las Carreras y sus Relacionados </h3>
<div id="apDiv1" class="demo_jui">
 
    <table width="50%" border="1" align="center" class="display" id="example">
        <thead>
          <tr>
            <th>N</th>
            <th>id</th>
            <th>Nombre</th>
            <th>Sede</th>
            <th>ultimo año arancel<th>
            <th colspan="5">Opciones</th>
          </tr>
          </thead>
        <tbody>
          <?php
		  $year_actual=date("Y");
	   require("../../funciones/conexion_v2.php");
	   $cons="SELECT carrera.id AS id_carrera, carrera.carrera, hija_carrera_valores.* FROM carrera INNER JOIN hija_carrera_valores ON carrera.id=hija_carrera_valores.id_madre_carrera ORDER by hija_carrera_valores.sede";
	   
	   $cons="SELECT carrera.id AS id_carrera, carrera.carrera, hija_carrera_valores.* FROM carrera INNER JOIN hija_carrera_valores ON carrera.id=hija_carrera_valores.id_madre_carrera GROUP by hija_carrera_valores.id_madre_carrera, hija_carrera_valores.sede ORDER by hija_carrera_valores.sede";
	   $sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	   $num_carreras=$sql->num_rows;
	   if($num_carreras>0)
	   {
		   $contador=0;
			while($C=$sql->fetch_assoc())
			{
				$contador++;
				
				$id_carrera_hija=$C["id"];
				$id_carrera=$C["id_carrera"];
				$carrera=$C["carrera"];
				$sede=$C["sede"];
			
				$fecha=$C["fecha"];
				//$permite_matriculas=$C["permite_matricula"];
				$cons_1="SELECT MAX(year) FROM hija_carrera_valores WHERE id_madre_carrera='$id_carrera' AND sede='$sede'";
				$sqli_1=$conexion_mysqli->query($cons_1)or die($conexion_mysqli->error);
				$DLA=$sqli_1->fetch_row();
				$ultimo_year_arancel=$DLA[0];
				$sqli_1->free();
				
				
			
				
				echo'<tr>
						<td>'.$contador.'</td>
						<td>'.$id_carrera.'</td>
						<td>'.$carrera.'</td>
						<td>'.$sede.'</td>
						<td>'.$ultimo_year_arancel.'</td>
						<td><a href="editar_carrera/editar_carrera_1.php?id_carrera='.$id_carrera.'">Editar</a></td>
						<td><a href="valores_carreraXyear/index.php?id_carrera='.$id_carrera.'&sede='.$sede.'">Aranceles</a></td>
						<td><a href="malla/ver_malla.php?id_carrera='.$id_carrera.'&sede='.$sede.'">Malla</a></td>
						<td><a href="asignaturas_individuales/lista_asignaturas_individuales.php?id_carrera='.$id_carrera.'&sede='.$sede.'">Asig. ind.</a></td>
						<td><a href="nvo_decreto/ingreso_decreto.php?id_carrera='.$id_carrera.'&sede='.$sede.'">Decreto</a></td>
					 </tr>';
			}
		}
		$sql->free();
	  
	   $conexion_mysqli->close();
       ?>
          </tbody>
        </table>
    <div id="msj">
    <?php
    if(isset($_GET["error"]))
	{
		$error=$_GET["error"];
		$img="";
		$msj="";
		$img_ok='<img src="../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
		$img_error='<img src="../BAses/Images/b_drop.png" width="16" height="16" alt="X" />';
		switch($error)
		{
			case"D0":
				$msj="Decreto Modificado Correctamente...";
				$img=$img_ok;
				break;
			case"CC1":
				$msj="Fallo al Grabar Carrera...";
				$img=$img_error;
				break;
			case"EC1":
				$msj="Fallo al Modificar Carrera...";
				$img=$img_error;
				break;
			case"EC0":
				$msj="Carrera Modificada Exitosamente...";
				$img=$img_ok;
				break;	
			case"CC0":
				$msj="Carrera Agregada...";
				$img=$img_ok;
				break;		
		}
		echo"$msj $img";
	}
	?>
</div>
</div>
</body>
</html>