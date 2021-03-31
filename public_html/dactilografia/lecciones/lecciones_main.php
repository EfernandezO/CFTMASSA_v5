<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>Lecciones</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 116px;
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
</head>

<body>
<h1 id="banner">Dactilografia - Administraci&oacute;n de Lecciones</h1>
<br />
<div id="link"><a href="../Lecciones_disponibles.php" class="button">Volver</a></div>
<div id="apDiv1">
  <table width="75%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="7">Lecciones</th>
    </tr>
     <tr>
      <td>N&deg;</td>
      <td>Titulo</td>
      <td>Clasificacion</td>
      <td>Exigencia</td>
      <td>Duracion </td>
      <td colspan="2">Opciones</td>
    </tr>
    </thead>
    <tbody>
   <?php
   include("../../../funciones/conexion_v2.php");
   $cons_L="SELECT * FROM dactilografia_lecciones ORDER by fecha_generacion";
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
		   $clasificacion=$L["clasificacion"];
		   $exigencia=$L["nivel_exigencia"];
		   $duracion=$L["duracion_seg"];
		   
		   echo' <tr>
				  <td>'.$aux.'</td>
				  <td>'.$titulo.'</td>
				  <td>'.$clasificacion.'</td>
				  <td>'.$exigencia.'</td>
				  <td>'.$duracion.'</td>
				  <td><a href="editar/edita_leccion_1.php?id_leccion='.$id_leccion.'" title="Editar"><img src="../../BAses/Images/b_edit.png" width="16" height="16" alt="Editar" /></a></td>
				  <td><a href="#" onclick="ELIMINAR(\'eliminar/elimina_leccion.php?id_leccion='.$id_leccion.'\');"><img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="Eliminar" /></a></td>
				</tr>';
		   
	   }
	}
   else
   {
	   echo'<tr><td colspan="7">No Hay Lecciones Creadas...</td></tr>';
	}
	$sql_L->free();
    $conexion_mysqli->close();
   ?>
    </tbody>
    <tfoot>
    <tr>
    <td colspan="7"><a href="nueva/nueva_leccion_1.php" title="Agregar Leccion"><img src="../../BAses/Images/add.png" width="23" height="19" /></a></td>
    </tr>
    </table>
  </table>
</div>
</body>
</html>