<?php
	define("DEBUG", false);
	if(!$_POST)
	{header("LOCATION: index.php");}
?>
<html>
<head>
<title>Listador de Libros</title>
<?php include("../../funciones/codificacion.php");?>

<link rel="stylesheet" type="text/css" href="../libreria_publica/jquery_treeview/demo/screen.css">
<style type="text/css" title="currentStyle">
			@import "../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_table_jui.css";
			@import "../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/examples/examples_support/themes/smoothness/jquery-ui-1.8.4.custom.css";
		</style>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>
		<script type="text/javascript" language="javascript" src="../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				oTable = $('#example').dataTable({
					"bJQueryUI": true,
					"bPaginate": true});
			} );
		</script>
</head>
<body>
<h1 id="banner">Biblioteca - C.F.T. Massachusetts</h1>
<div id="link"><br>
<a href="index.php" class="button">Volver a Selecci&oacute;n </a></div>
<div id="Layer4" style="position:absolute; left:30%; top:114px; width:40%; height:46px; z-index:4"> 
  <div align="center"><font color="#0000CC"><b>Listado de Libros Existentes Biblioteca<br>
    C.F.T. Massachusetts Talca - Linares</b></font></div>
</div>
<div id="Layer1" style="position:absolute; left:5%; top:168px; width:90%; height:67px; z-index:1" class="demo_jui">
  <?php
    
	require('../../funciones/conexion_v2.php'); 
	require("../../funciones/funcion.php"); 
	
 		$condicion="";
   
   	$opcion="con_pdf";
	$sede=mysqli_real_escape_string($conexion_mysqli, $_POST["sede"]);
	$array_carrera=explode("_",mysqli_real_escape_string($conexion_mysqli, $_POST["carrera"]));
	$id_carrera=$array_carrera[0];
	$carrera=$array_carrera[1];
	$titulo=strtoupper(mysqli_real_escape_string($conexion_mysqli, $_POST["searchbox"]));
	$filtro=mysqli_real_escape_string($conexion_mysqli, $_POST["filtro"]);

	
	$condicion="";
	if($sede!="Todas")
	{$condicion.="sede='$sede' AND ";}
	$condicion.= "id_carrera='$id_carrera'";
	//echo"--->$ftitulo<br>";
	if($titulo!="TODOS")
	{
		$condicion.="AND UPPER(nombre) LIKE UPPER('%$titulo%')";//se supone insensible a may o min pero para asegurarse paso todo a mayuscula al buscar
		
	}
	
	$cons="SELECT * FROM biblioteca WHERE $condicion ORDER by sede";
	if(DEBUG){echo"--->".$cons."<br>";}
    $result=$conexion_mysqli->query($cons);
	$num_total_libros=$result->num_rows;
	?>
  <div align="left"><strong>Carrera: <?php echo "$carrera<br>";?></strong></div>
  <div align="left"></div>
  <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
  <thead>
    <tr> 
      <th width="135">Titulo</th>
      <th width="104" >Autor</th>
      <th width="131" >Editorial</th>
      <th width="55" >Año</th>
      <th width="76" >Sede</th>
      <th width="59" >Prestado</th>
	  <th width="88" align="center">Ver</th>
    </tr>
   </thead>
   <tbody> 
<?php 
 $cl=0;
 $aux=0; 
 $num_libros_mostrados=0;  
 while($row = $result->fetch_assoc())
 { 
 			$id_libro=$row["id_libro"];
 			$titulo=$row["nombre"];	
			$autor=$row["autor"];
			$editorial=$row["editorial"];
			$año=$row["year"];
			$sede=$row["sede"];
			$prestado=$row["prestado"];
			switch($filtro)
			{
				case "con_pdf":
					$mostrar_libro=tiene_asociados($id_libro, "pdf");
					break;
				case"ninguno":
					$mostrar_libro=true;
					break;	
			}
			if($mostrar_libro)
			{
				echo'<tr>  
				 <td class="Estilo2">&nbsp;'.ucwords(strtolower($titulo)).'</td>
				 <td class="Estilo2">&nbsp;'.ucwords(strtolower($autor)).'</td>
				 <td class="Estilo2">&nbsp;'.ucwords(strtolower($editorial)).'</td>
				 <td class="Estilo2">&nbsp;'.$año.'</td>
				 <td class="Estilo2">&nbsp;'.$sede.'</td>
				 <td class="Estilo2">&nbsp;'.$prestado.'</td>
				 ';
				echo'<td class="Estilo2">&nbsp;';
				
				 if(tiene_asociados($id_libro, "imagen"))
				 {
					 echo' <a href="img_info/img_info1.php?id='.$id_libro.'"><img src="../BAses/Images/ojo2.png" alt="Ver" width="20" height="15" border="0" title="Ver Imagenes"></a>';
				 }
				 if(tiene_asociados($id_libro, "pdf"))
				 {
				 echo' <a href="visor_pdf/ver_pdf1.php?id='.$id_libro.'"><img src="../imagenes/PDF.jpg" border="0" width="20" height="15" alt="ver pdf" title="Ver PDF"></a>';
				 }
				echo'</td>
					</tr>';
					$num_libros_mostrados++;
			}		
}
  
   $result->free();
  $conexion_mysqli->close();
?> 
</tbody>
<tfoot>
 <?php echo'<tr ><td colspan="2">Total Libros</td><td colspan="5">'.$num_total_libros.' ('.$num_libros_mostrados.' filtrados)</td></tr>';?>
</tfoot>
  </table>
</div>
</body>
</html>
<?php
////////////////para mostrar icono a libro segun numero de archivos asociados que tenga
function tiene_asociados($id, $tipo_archivo)
{
	require('../../funciones/conexion_v2.php'); 
	$tabla="biblioteca_asociados";
	switch($tipo_archivo)
	{
		case"imagen":
			$condicion="id_libro='$id' AND tipo_archivo='imagen'";
			break;
		case"pdf":
			$condicion="id_libro='$id' AND tipo_archivo='pdf'";
			break;
	}
	$cons_C="SELECT COUNT(id) FROM $tabla WHERE $condicion";
	//echo "$cons_C<br>";
	$sqlC=$conexion_mysqli->query($cons_C)or die($conexion_mysqli->error);
	$DC=$sqlC->fetch_row();
	$num_asociados=$DC[0];
	//echo"$num_asociados<br>";
	//segun numero de asociados retorno para mostrar o no los iconos
	$sqlC->free();
	$conexion_mysqli->close();
	
	if($num_asociados>0)
	{return true;}
	else
	{return false;}
}
?>