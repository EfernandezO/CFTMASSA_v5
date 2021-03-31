<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->MALLAS_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
define("DEBUG", false);
if($_GET)
{
	include("../../../funciones/conexion.php");
	$id_carrera=mysql_real_escape_string($_GET["id_carrera"]);
	$sede=mysql_real_escape_string($_GET["sede"]);
	  
	$array_ramos=array();
	 $cons="SELECT * FROM carrera WHERE id='$id_carrera' LIMIT 1";
	  if(DEBUG){ echo"-->$cons<br>";}
	   $sqlX=mysql_query($cons)or die(mysql_error());
	   $MX=mysql_fetch_assoc($sqlX);	
		$nombre_carrera=$MX["carrera"];
	   mysql_free_result($sqlX);
}
else
{header("location: ../index.php");}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Asignaturas Individuales</title>
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
	height:115px;
	z-index:1;
	left: 5%;
	top: 140px;
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
	c=confirm('Seguro(a) Desea Eliminar esta Asignatura');
	if(c)
	{
		d=confirm('Realmente seguro(a) que desea continuar?')
		if(d){window.location=url;}
	}
}
</script>
</head>
<body>
<h1 id="banner">Administrador - Asignaturas Individuales</h1>
<div id="link"><br />
<a href="../index.php" class="button">Volver al Seleccion</a><br />
<br />
<a href="nva_asig/nva_asig1.php?id_carrera=<?php echo $id_carrera;?>&sede=<?php echo $sede;?>&nombre_carrera=<?php echo base64_encode($nombre_carrera)?>" class="button">Nueva Asignatura</a>
</div>
<h3>Administre las Asignaturas Individuales de la carrera Carrera - <?php echo $nombre_carrera." - ".$sede;?></h3>
<div id="apDiv1" class="demo_jui">
  <table width="50%" border="1" align="center" class="display" id="example">
      <thead>
	    <tr>
	      <th>N</th>
          <th>id</th>
	      <th>nivel</th>
          <th>Asignaturas</th>
          <th colspan="2">Opciones</th>
        </tr>
    </thead>
        <tbody>
	   <?php
	 
	   $cons="SELECT * FROM asignatura WHERE id_carrera='$id_carrera' AND sede='$sede' order by nivel";
	   if(DEBUG){ echo"-->$cons<br>";}
	   $sql=mysql_query($cons)or die(mysql_error());
	   $num_registros=mysql_num_rows($sql);
	   if($num_registros>0)
	   {
		   $contador=0;
			while($M=mysql_fetch_assoc($sql))
			{
				$contador++;
				
				$id_registro=$M["id"];
				$carrera=$M["carrera"];
				$nivel=$M["nivel"];
				$asignatura=$M["asignatura"];
				
				
				echo'<tr>
						<td>'.$contador.'</td>
						<td>'.$id_registro.'</td>
						<td>'.$nivel.'</td>
						<td>'.$asignatura.'</td>
						<td><a href="edita_asig/edita_asig1.php?id_ramo='.$id_registro.'&id_carrera='.$id_carrera.'&sede='.$sede.'">Editar</a></td>
						<td><a href="#" onclick="CONFIRMAR(\'elimina_asig/elimina_asignatura.php?id_carrera='.$id_carrera.'&id_ramo='.$id_registro.'&sede='.$sede.'\')">Eliminar</a></td>
					 </tr>';
			}
		}
		mysql_free_result($sql);
	   mysql_close($conexion);
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
		  case"AI0":
		  	$msj="Asignatura Actualizada Exitosamente...:D";
		  	break;
		  case"AI1":
		  	$msj="Fallo al Intentar Eliminar Asignatura...";
		  	break;	
		  case"AI2":
		  	$msj="Datos incorrectos para Modificar Asignatura...";
		  	break;	
		  case"AI3":
		  	$msj="La Asignatura que intenta ingresar ya existe...";
		  	break;
		  case"AI4":
		  	$msj="Asignatura Agregada Existosamente...";
		  	break;	
		  case"AI5":
		  	$msj="Fallo al intentar Agregar asignatura, intentelo mas tarde...";
		  	break;		
		   case"AI6":
		  	$msj="Asignatura Eliminada Existosamente...";
		  	break;	
		  case"AI7":
		  	$msj="Fallo al intentar Eliminar asignatura, intentelo mas tarde...";
		  	break;			
	  }
	  echo" $msj";
  }
  ?>
  </div>
</div>
</body>
</html>