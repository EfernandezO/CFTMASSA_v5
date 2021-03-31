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
if($_GET)
{
	$id_carrera=$_GET["id_carrera"];
	$sede=$_GET["sede"];
	  require("../../../funciones/conexion_v2.php");
	  require("../../../funciones/funciones_sistema.php");
	$array_ramos=array();
	 $cons="SELECT * FROM mallas WHERE id_carrera='$id_carrera'";
	 $nombre_carrera=NOMBRE_CARRERA($id_carrera);
	  if(DEBUG){ echo"-->$cons<br>";}
	   $sqlX=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	   $num_registros=$sqlX->num_rows;
	   if($num_registros>0)
	   {
		   $contador=0;
		   $array_ramos[0]="sin pre-requisito";
			while($MX=$sqlX->fetch_assoc())
			{
				//echo"->$nombre_carrera<br>";
				$codigoX=$MX["cod"];
				$ramoX=$MX["ramo"];
				
				if(!empty($ramoX))
				{$array_ramos[$codigoX]=$ramoX;}
				else{ $array_ramos[$codigoX]="sin pre-requisito";}
			}
	   }
	   $sqlX->free();
	   if(DEBUG){ var_export($array_ramos);}
}
else
{header("location: ../index.php");}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Malla de Carrera</title>
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
<h1 id="banner">Administrador - Malla de Carrera</h1>
<div id="link"><br />
<a href="../index.php" class="button">Volver a Malla</a><br />
<br />
<a href="nva_malla_2/nva_malla.php?id_carrera=<?php echo $id_carrera;?>&sede=<?php echo $sede;?>&nombre_carrera=<?php echo base64_encode($nombre_carrera)?>" class="button">Nuevo Registro</a>
</div>
<h3>Administre la Malla Curricula de la Carrera - <?php echo $nombre_carrera;?></h3>
<div id="apDiv1" class="demo_jui">
  <table width="50%" border="1" align="center" class="display" id="example">
      <thead>
	    <tr>
	      <th>N</th>
          <th>id</th>
          <th>Posicion</th>
	      <th>COD.</th>
          <th>pr1</th>
          <th>pr2</th>
	      <th>pr3</th>
	      <th>pr4</th>
	      <th>pr5</th>
	      <th>pr6</th>
	      <th>pr7</th>
	      <th>pr8</th>
	      <th>pr9</th>
	      <th>pr10</th>
	      <th>nivel</th>
	      <th>Ramo</th>
	      <th>es_asignatura</th>
          <th colspan="3">Opciones</th>
        </tr>
    </thead>
        <tbody>
	   <?php
	 
	   $cons="SELECT * FROM mallas WHERE id_carrera='$id_carrera' order by num_posicion, cod";
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
				$num_posicion=$M["num_posicion"];
				$carrera=$M["carrera"];
				$codigo=$M["cod"];
				$pre_1=$M["pr1"];
				$pre_2=$M["pr2"];
				$pre_3=$M["pr3"];
				$pre_4=$M["pr4"];
				$pre_5=$M["pr5"];
				$pre_6=$M["pr6"];
				$pre_7=$M["pr7"];
				$pre_8=$M["pr8"];
				$pre_9=$M["pr9"];
				$pre_10=$M["pr10"];
				$nivel=$M["nivel"];
				$ramo=$M["ramo"];
				$es_asignatura=$M["es_asignatura"];
				
				echo'<tr class="gradeA">
						<td>'.$contador.'</td>
						<td>'.$id_registro.'</td>
						<td>'.$num_posicion.'</td>
						<td>'.$codigo.'</td>
						<td><a href="#" title="'.$array_ramos[$pre_1].'">'.$pre_1.'</a></td>
						<td><a href="#" title="'.$array_ramos[$pre_2].'">'.$pre_2.'</a></td>
						<td><a href="#" title="'.$array_ramos[$pre_3].'">'.$pre_3.'</a></td>
						<td><a href="#" title="'.$array_ramos[$pre_4].'">'.$pre_4.'</a></td>
						<td><a href="#" title="'.$array_ramos[$pre_5].'">'.$pre_5.'</a></td>
						<td><a href="#" title="'.$array_ramos[$pre_6].'">'.$pre_6.'</a></td>
						<td><a href="#" title="'.$array_ramos[$pre_7].'">'.$pre_7.'</a></td>
						<td><a href="#" title="'.$array_ramos[$pre_8].'">'.$pre_8.'</a></td>
						<td><a href="#" title="'.$array_ramos[$pre_9].'">'.$pre_9.'</a></td>
						<td><a href="#" title="'.$array_ramos[$pre_10].'">'.$pre_10.'</a></td>
						<td>'.$nivel.'</td>
						<td>'.$ramo.'</td>
						<td align="center">'.$es_asignatura.'</td>
						<td><a href="../programas_estudio/ver_programa_estudio.php?id_carrera='.$id_carrera.'&cod_asignatura='.$codigo.'&sede='.$sede.'" title="Programa de Estudio">PE</a></td>
						<td><a href="editar_malla/edita_malla1.php?id_ramo='.$id_registro.'&id_carrera='.$id_carrera.'&sede='.$sede.'">Editar</a></td>
						<td><a href="#" onclick="CONFIRMAR(\'elimina_malla/elimina_malla.php?id_carrera='.$id_carrera.'&id_ramo='.$id_registro.'&sede='.$sede.'&codigo='.$codigo.'\')">Eliminar</a></td>
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
		  case"M0":
		  	$msj="Malla Actualizada, ramo guardado Exitosamente...:D";
		  	break;
		  case"E0":
		  	$msj="Malla Actualizada, ramo Eliminado Exitosamente...:D";
		  	break;	
		  case"G0":
		  	$msj="Malla Actualizada, ramos grabados Exitosamente...:D";
		  	break;
		case"G1":
		  	$msj="Malla Actualizada, sin embargo no se grabaron algunos ramos, revisar...:D";
		  	break;			
	  }
	  echo" $msj";
  }
  ?>
  </div>
</div>
</body>
</html>