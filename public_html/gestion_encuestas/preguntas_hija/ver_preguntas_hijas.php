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
if($_GET)
{
	if((isset($_GET["id_encuesta"]))and(isset($_GET["id_pregunta"])))
	{
		$id_encuesta=$_GET["id_encuesta"];
		$id_pregunta=$_GET["id_pregunta"];
		
		if((is_numeric($id_encuesta))and(is_numeric($id_pregunta)))
		{ $continuar=true;}
		else
		{ $continuar=false;}
	}
}
else
{ $continuar=false;}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Preguntas hija | Encuesta</title>
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
	height:60px;
	z-index:1;
	left: 5%;
	top: 224px;
}
#apDiv2 {
	position:absolute;
	width:70%;
	height:69px;
	z-index:2;
	left: 15%;
	top: 137px;
	text-align: center;
	font-size: medium;
	font-weight: bold;
	border: thin dashed #1E78C3;
}
-->
</style>
<!--INICIO LIGHTBOX EVOLUTION-->
  <script type="text/javascript" src="../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
 <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox(); 
    });
  </script>
 <!--FIN LIGHTBOX EVOLUTION--> 
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
<h1 id="banner">Administrador - Alternativas, Encuesta</h1>
<div id="link"><br />
<a href="../preguntas/ver_preguntas.php?id_encuesta=<?php echo $id_encuesta;?>" class="button">Volver a Preguntas</a><br />
<br />
<a href="nva_pregunta_hija/nva_pregunta_hija.php?id_encuesta=<?php echo $id_encuesta;?>&id_pregunta=<?php echo $id_pregunta;?>" class="button">Nueva Alternativa</a><br><br />
<a href="importar_alternativas/importar_alternativas_1.php?id_encuesta=<?php echo $id_encuesta;?>&id_pregunta=<?php echo $id_pregunta;?>&lightbox[iframe]=true&lightbox[width]=850&lightbox[height]=500"  class="lightbox button_R">Importar Alternativas</a>
</div>
<div id="apDiv1" class="demo_jui">
  <table width="50%" border="1" align="center" class="display" id="example">
      <thead>
	    <tr>
	      <th>N</th>
          <th>Posicion</th>
          <th>Contenido</th>
          <th colspan="2">Opciones</th>
        </tr>
    </thead>
        <tbody>
	   <?php
	   if($continuar)
	   {
	require("../../../funciones/conexion_v2.php");
	 //////////////////////
	 //pregunta
	 $cons_P="SELECT pregunta FROM encuestas_pregunta WHERE id_encuesta='$id_encuesta' AND id_pregunta='$id_pregunta' LIMIT 1";
	 $sql_P=$conexion_mysqli->query($cons_P)or die($conexion_mysqli->error);
	 $DP=$sql_P->fetch_row();
		$pregunta=$DP[0];
	$sql_P->free();
	 /////////////////////
	 
	   $cons="SELECT * FROM encuestas_pregunta_hija WHERE id_encuesta='$id_encuesta' AND id_pregunta='$id_pregunta' ORDER by posicion";
	   if(DEBUG){ echo"-->$cons<br>";}
	   $sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	 	$num_registros=$sql->num_rows;
	   if($num_registros>0)
	   {
		   $contador=0;
			while($M=$sql->fetch_assoc())
			{
				$contador++;
				$id_pregunta_hija=$M["id_pregunta_hija"];
				$contenido=$M["contenido"];
				$posicion=$M["posicion"];
				
				echo'<tr class="gradeC">
						<td>'.$contador.'</td>
						<td>'.$posicion.'</td>
						<td>'.$contenido.'</td>
						<td><a href="editar_pregunta_hija/edita_pregunta_hija1.php?id_encuesta='.$id_encuesta.'&id_pregunta='.$id_pregunta.'&id_pregunta_hija='.$id_pregunta_hija.'">Editar</a></td>
						<td><a href="elimina_pregunta_hija/elimina_pregunta_hija.php?id_encuesta='.$id_encuesta.'&id_pregunta='.$id_pregunta.'&id_pregunta_hija='.$id_pregunta_hija.'">Eliminar</a></td>
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
		  	$msj="Pregunta Hija Modificada Exitosamente...:D";
		  	break;
		  case"E0":
		  	$msj="Pregunta Hija Eliminada Exitosamente...:D";
		  	break;	
		  case"G0":
		  	$msj="Pregunta Hija Agregada Exitosamente...:D";
		  	break;
	  }
	  echo" $msj";
  }
  ?>
  </div>
</div>
<div id="apDiv2">Pregunta cod:<?php echo $id_pregunta;?><br />
&quot;<?php echo substr($pregunta,0,50);?>&quot;</div>
</body>
</html>