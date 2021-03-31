<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Gestion de Encuestas_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(isset($_GET["id_encuesta"]))
{
	$id_encuesta=$_GET["id_encuesta"];
	if(is_numeric($id_encuesta))
	{$continuar=true;}
	else{ $continuar=false;}
}
else
{ $continuar=false;}

//////////////////////+++++++/////////////////////////
if($continuar)
{ if(DEBUG){ echo"Continuar: OK<br>";}}
else
{ if(DEBUG){ echo"Continuar: NO<br>";}else{header("location: ../index.php");}}
//////////////////////+++++++/////////////////////////
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Preguntas | Encuesta</title>
	<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
	<style type="text/css" title="currentStyle">
			@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_page.css";
			@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_table_jui.css";
			@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/examples/examples_support/themes/smoothness/jquery-ui-1.8.4.custom.css";
    </style>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:55px;
	z-index:1;
	left: 5%;
	top: 176px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:44px;
	z-index:2;
	left: 30%;
	top: 107px;
	text-align: center;
	font-size: medium;
	font-weight: bold;
	border: thin dashed #1E78C3;
}
-->
</style>
<script type="text/javascript" language="javascript" src="../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="ISO-8859-1">
			$(document).ready(function() {
				oTable = $('#example').dataTable({
					"bJQueryUI": true,
					"bPaginate": false
				});
			} );
function CONFIRMAR(url)
{
	c=confirm('Seguro(a) Desea Eliminar este Registro\n si algun otro ramo lo tiene como pre-requisito este sera\nReestablecido...');
	if(c)
	{
		d=confirm('Realmente seguro(a) que desea continuar?')
		if(d){window.location=url;}
	}
}
</script>
</head>
<body>
<h1 id="banner">Administrador - Preguntas de Encuesta</h1>
<div id="link"><br />
<a href="../index.php" class="button">Volver a Encuestas</a><br />
<br />
<a href="nva_pregunta/nva_pregunta.php?id_encuesta=<?php echo $id_encuesta;?>" class="button">Nueva Pregunta</a>
</div>
<div id="apDiv1" class="demo_jui">
  <table width="50%" border="1" align="center" class="display" id="example">
      <thead>
	    <tr>
	      <th>N</th>
          <th>Posicion</th>
          <th>id</th>
	      <th>Pregunta</th>
          <th>Tipo</th>
          <th>Num Alternativas</th>
          <th colspan="3">Opciones</th>
        </tr>
    </thead>
        <tbody>
	   <?php
	   if($continuar)
	   {
	 require("../../../funciones/conexion_v2.php");
	 ///////////////////////////
	 //datos de encuesta
	 $cons_E="SELECT nombre FROM encuestas_main WHERE id_encuesta='$id_encuesta' LIMIT 1";
	 $sql_E=$conexion_mysqli->query($cons_E)or die($conexion_mysqli->error);
	 $DE=$sql_E->fetch_assoc();
	 	$encuesta_nombre=$DE["nombre"];
	$sql_E->free();
	 ////////////////////////////
	 
	   $cons="SELECT * FROM encuestas_pregunta WHERE id_encuesta='$id_encuesta' ORDER by posicion, id_pregunta";
	   if(DEBUG){ echo"-->$cons<br>";}
	   $sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$num_registros=$sql->num_rows;
	   if($num_registros>0)
	   {
		   $contador=0;
			while($M=$sql->fetch_assoc())
			{
				$contador++;
				
				$posicion=$M["posicion"];
				$id_pregunta=$M["id_pregunta"];
				$pregunta=$M["pregunta"];
				$tipo=$M["tipo"];
				///////////////////////////////////////
				///busco numero alternativas
				$cons_A="SELECT COUNT(id_pregunta_hija) FROM encuestas_pregunta_hija WHERE id_encuesta='$id_encuesta' AND id_pregunta='$id_pregunta'";
				$sql_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
					$DA=$sql_A->fetch_row();
					$num_preguntas_hijas=$DA[0];
				$sql_A->free();	
				///////////////////////////////////////
				
				echo'<tr class="gradeX">
						<td>'.$contador.'</td>
						<td>'.$posicion.'</td>
						<td>'.$id_pregunta.'</td>
						<td>'.$pregunta.'</td>
						<td>'.$tipo.'</td>
						<td>'.$num_preguntas_hijas.'</td>
						<td><a href="../preguntas_hija/ver_preguntas_hijas.php?id_encuesta='.$id_encuesta.'&id_pregunta='.$id_pregunta.'">Desarrollar</a></td>
						<td><a href="editar_pregunta/edita_pregunta1.php?id_encuesta='.$id_encuesta.'&id_pregunta='.$id_pregunta.'">Editar</a></td>
						<td><a href="elimina_pregunta/elimina_pregunta.php?id_encuesta='.$id_encuesta.'&id_pregunta='.$id_pregunta.'">Eliminar</a></td>
					 </tr>';
			}
		}
		$sql->free();
	   @mysql_close($conexion);
	   $conexion_mysqli->close();
	   }
       ?>
        </tbody>
  </table>
  <div id="error">
  <?php
  if(isset($_GET["error"]))
  {
	  $error=$_GET["error"];
	  $msj="";
	  switch($error)
	  {
		  case"M0":
		  	$msj="Pregunta Modificada Exitosamente...:D";
		  	break;
		  case"E0":
		  	$msj="Pregunta Eliminada Exitosamente...:D";
		  	break;	
		  case"G0":
		  	$msj="Pregunta Agregada Exitosamente...:D";
		  	break;
	  }
	  echo" $msj";
  }
  ?>
  </div>
</div>
<div id="apDiv2">Encuesta cod:<?php echo $id_encuesta?><br> 
  &quot;<?php echo $encuesta_nombre; ?>&quot;</div>
</body>
</html>