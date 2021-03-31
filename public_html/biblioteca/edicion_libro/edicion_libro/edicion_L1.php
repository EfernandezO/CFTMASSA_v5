<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Bibioteca_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(isset($_GET["id_libro"]))
{
	$array_estado_libro=array("Bueno", "Regular", "Malo");
	 include("../../../../funciones/conexion_v2.php");
	$id_libro=mysqli_real_escape_string($conexion_mysqli, $_GET["id_libro"]);
   
	$consL="SELECT * FROM biblioteca WHERE id_libro = '$id_libro' LIMIT 1";
    $result=$conexion_mysqli->query($consL);
   $row = $result->fetch_assoc();
  		$nombres=$row['nombre']; 
   		$autor=$row['autor'];
  		 $editorial=$row['editorial'];
		 $L_id_carrera=$row["id_carrera"];
  		 $L_carrera=$row['carrera'];
 		  $year=$row['year'];
 		  $estado=$row['estado'];
 		  $sede=$row['sede'];
	$result->free(); 
	$conexion_mysqli->close();
}
else
{ header("location: ../../menu_biblioteca.php");}
?> 
<html>
<head>
<title>Edicion de Libros</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:40%;
	height:30px;
	z-index:4;
	left: 30%;
	top: 303px;
	text-align: center;
}
-->
</style>
<script language="javascript">
function CONFIRMAR()
{
	c=confirm('Seguro(a) Desea Modificar Este Libro...?');
	if(c)
	{ document.getElementById('frm').submit();}
}
</script>
</head>

<body>
<h1 id="banner">Biblioteca - Modificacion Libro</h1>

<div id="link"><br>
  <a href="../../menu_biblioteca.php" class="button">Volver Al Biblioteca</a><br><br>
  <a href="../archivos_asociados/carga_asociados/index.php?id_libro=<?php echo"$id_libro";?>" class="button">Ver Archivos Asociados</a>
</div>
<div id="Layer1" style="position:absolute; left:5%; top:111px; width:90%; height:126px; z-index:3"> 

  <form action="edicion_L2.php" method="post" enctype="multipart/form-data" name="frm" id="frm">
    <table width="432" align="center">
    <thead>
      <tr>
        <th colspan="4"><div align="center" class="Estilo4">Edicion de Libros</div></th>
      </tr>
      </thead>
      <tbody>
      <tr class="odd">
        <td width="131"><span class="Estilo3">Nombre:</span></td>
        <td colspan="3"><div align="left">
          <input type="text" name="nombres" size="30" maxlength="255" value="<?php echo $nombres;?>">
          <input type="hidden" name="id" value="<?php echo $id_libro;?>">        
        </div></td>
      </tr>
      <tr class="odd">
        <td width="131" height="34"><span class="Estilo3">Autor:</span></td>
        <td colspan="3" height="34"><div align="left">
          <input type="text" name="autor" size="30" maxlength="50" value="<?php echo $autor;?>">        
        </div></td>
      </tr>
      <tr class="odd">
        <td width="131"><span class="Estilo3">Editorial</span></td>
        <td colspan="3"><div align="left">
          <input type="text" name="editorial" maxlength="20" size="20" value="<?php echo $editorial;?>">        
        </div></td>
      </tr>
      <tr class="odd">
        <td width="131"><span class="Estilo3">Carrera</span></td>
        <td colspan="3"><div align="left">
          <select name="carrera" id="carrera">
            <?php 
   include("../../../../funciones/conexion.php");
   $res="SELECT * FROM carrera";
   $result=mysql_query($res);
   while($row = mysql_fetch_array($result)) 
   {
	   $id_carrera=$row["id"];
	   $nom_carrera=$row["carrera"];
	   if($id_carrera==$L_id_carrera)
	   { echo'<option value="'.$id_carrera.'_'.$nom_carrera.'" selected>'.$id_carrera.'_'.$nom_carrera.'</option>';}
	   else
	   {echo'<option value="'.$id_carrera.'_'.$nom_carrera.'">'.$id_carrera.'_'.$nom_carrera.'</option>';}
	 }
mysql_free_result($result); 
mysql_close($conexion); 
 ?>
          </select>
        </div></td>
      </tr>
      <tr class="odd">
        <td width="131"><span class="Estilo3">A&ntilde;o</span></td>
        <td colspan="3"><div align="left">
          <input type="text" name="ano" size="5" maxlength="4" value="<?php echo $year;?>">        
        </div></td>
      </tr>
      <tr class="odd">
        <td width="131"><span class="Estilo3">Estado</span></td>
        <td colspan="3"><div align="left">
          <select name="estado">
            <?php
			foreach($array_estado_libro as $ne=>$valore)
			{
				if($valore==$estado)
				{ echo'<option value="'.$valore.'" selected>'.$valore.'</option>';}
				else
				{ echo'<option value="'.$valore.'">'.$valore.'</option>';}
			}
            ?>
          </select>
        </div></td>
      </tr>
      <tr class="odd">
        <td width="131"><span class="Estilo3">Sede</span></td>
        <td colspan="3"><div align="left">
          <?php
	  include("../../../../funciones/funcion.php");
	  echo selector_sede("sede"); 
	  echo"Sede Actual: $sede";
	  ?>
        </div></td>
      </tr>
     </tbody>
    </table>
    <div id="apDiv1"><a href="#" class="button_G" onClick="CONFIRMAR();">Modificar</a></div>
  </form>
</div>

</body>
</html>