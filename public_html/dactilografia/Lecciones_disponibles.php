<?php
//-----------------------------------------//
	require("../OKALIS/seguridad.php");
	require("../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="ALUMNO";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", false);
$_SESSION["DACTILOGRAFIA"]["verificador"]=true;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../libreria_publica/jquery_treeview/demo/screen.css">

<title>Lecciones</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:55px;
	z-index:1;
	left: 5%;
	top: 132px;
}
</style>
<script language="javascript">
function ELIMINAR(url)
{
	c=confirm('Seguro(a) Desea Eliminar esta Leccion');
	if(c)
	{
		window.location=url;
	}
}
</script>
<style type="text/css" title="currentStyle">
			@import "../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_page.css";
			@import "../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_table_jui.css";
			@import "../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/examples/examples_support/themes/smoothness/jquery-ui-1.8.4.custom.css";
#apDiv2 {
	position:absolute;
	width:90%;
	height:20px;
	z-index:2;
	left: 5%;
	top: 105px;
	text-align: center;
	font-weight: bold;
	font-size: large;
}
</style>
<script type="text/javascript" language="javascript" src="../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="ISO-8859-1">
			$(document).ready(function() {
				oTable = $('#example').dataTable({
					"bJQueryUI": true
				});
			} );
		</script>
</head>
<?php
  include("../../funciones/conexion_v2.php");
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	switch($privilegio)
	{
		case"ALUMNO":
			$id_alumno=$_SESSION["USUARIO"]["id"];
			 //--------------------------------------------------//
			 include("../../funciones/VX.php");
			 //cambio estado_conexion USER-----------
			 CAMBIA_ESTADO_CONEXION_ALUMNO($id_alumno, "ON");
			//-----------------------------------------------//
			$url="../Alumnos/alumno_menu.php";
			break;
		case"Docente":
			$url="../Docentes/okdocente.php";
			break;	
		case"jefe_carrera":
			$url="../Docentes/okdocente.php";
			break;		
		default:
			$url="../Administrador/ADmenu.php";
	}
?>
<body>
<h1 id="banner">Dactilografia - Lecciones</h1>
<br />
<div id="link"><a href="lecciones/lecciones_main.php" class="button">Administrar Lecciones</a><br /><br />

<a href="<?php echo $url;?>" class="button">Volver al Menu</a>
</div>
<div id="apDiv1" class="demo_jui">
  <table width="75%" class="display" id="example">
  <thead>
     <tr>
      <th>N&deg;</th>
      <th>Titulo</th>
      <th>Descripcion</th>
      <th>Clasificacion</th>
      <th>Exigencia</th>
      <th>Duracion </th>
      <th>Participantes</th>
      <th>Opciones</th>
    </tr>
    </thead>
    <tbody>
   <?php
   $cons_L="SELECT * FROM dactilografia_lecciones ORDER by fecha_generacion desc";
   $sql_L=$conexion_mysqli->query($cons_L);
   $num_lecciones=$sql_L->num_rows;
   if($num_lecciones>0)
   {
	   $aux=0;
	   while($L=$sql_L->fetch_assoc())
	   {
		   
		   $aux++;
		   $id_leccion=$L["id"];
		   $titulo=$L["titulo"];
		   $descripcion=$L["descripcion"];
		   $clasificacion=$L["clasificacion"];
		   $exigencia=$L["nivel_exigencia"];
		   $duracion=$L["duracion_seg"];
		   
		   //////////
		   //usuario realizaron Leccion
		   $cons_RU="SELECT DISTINCT(id_usuario) FROM dactilografia_registros WHERE id_leccion='$id_leccion'";
		   if(DEBUG){ echo "$cons_RU ";}
		   $sql_RU=$conexion_mysqli->query($cons_RU);
		   	$num_ejercitados=$sql_RU->num_rows;
			if(empty($num_ejercitados)){ $num_ejercitados=0;}
			$sql_RU->free();
			if(DEBUG){ echo"NUM: $num_ejercitados<br>";}
		   //////
		   
		   echo'<tr>
				  <td height="34">'.$aux.'</td>
				  <td>'.$titulo.'</td>
				  <td>'.$descripcion.'</td>
				  <td>'.$clasificacion.'</td>
				  <td>'.$exigencia.'</td>
				  <td>'.$duracion.'</td>
				  <td>'.$num_ejercitados.'</td>
				  <td align="center"><a href="ejercitador/Ejercitador.php?id_leccion='.$id_leccion.'" class="button">Test</a></td>
	</tr>';
		   
	   }
	}
   else
   {
	   echo'<tr><td colspan="8">No Hay Lecciones Creadas...</td></tr>';
	}
	$sql_L->free();
   	$conexion_mysqli->close();
   ?>
    </tbody>
    </table>
</div>
<div id="apDiv2">Lecciones</div>
</body>
</html>