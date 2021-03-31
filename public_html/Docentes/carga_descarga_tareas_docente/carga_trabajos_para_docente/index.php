<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("carga_descarga_tareas_docente_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Carga Trabajos Docente</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 203px;
}
</style>
<!--INICIO LIGHTBOX EVOLUTION-->
  <script type="text/javascript" src="../../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
 <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox(); 
    });
  </script>
 <!--FIN LIGHTBOX EVOLUTION--> 
 <script language="javascript">
function ELIMINAR_TRABAJO(id_trabajo)
{
	url="elimina_trabajo_1.php?id_trabajo="+id_trabajo;
	c=confirm('Seguro(a) Desea Eliminar este Trabajo...')
	if(c){
			d=confirm('Realmente Seguro...')
			if(d){window.location=url;}
		}
}
</script>
</head>

<body>
<h1 id="banner">Carga Trabajos para Docente</h1>
<div id="link"><br />
<a href="../../lista_funcionarios.php" class="button">Volver al Menu</a><br />
<br />
<a href="carga_trabajo_1.php?lightbox[iframe]=true&amp;lightbox[width]=600&amp;lightbox[height]=530"  class="lightbox button_R" title="cargar trabajo">Cargar Trabajo</a></div>

<div id="apDiv1">
  <table width="80%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="8">Trabajos Disponibles</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>N.</td>
      <td>Nombre</td>
      <td>Descripcion</td>
      <td colspan="3">Opciones</td>
      <td>N. Tareas Cargadas</td>
      <td>N. Descargas</td>
    </tr>
    <?php
	
	$id_docente=$_SESSION["USUARIO"]["id"];
    require("../../../../funciones/conexion_v2.php");
	$cons="SELECT * FROM tareas_docente WHERE tipo='trabajo' ORDER by fecha_generacion";
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_trabajos=$sqli->num_rows;
	$path="../enrutador_v2.php";
	if($num_trabajos>0)
	{
		$aux=0;
		while($T=$sqli->fetch_assoc())
		{
			$aux++;
			$T_id=$T["id"];
			$T_nombre=$T["nombre"];
			$T_descripcion=$T["descripcion"];
			$T_archivo=$T["archivo"];
			$T_fecha_generacion=$T["fecha_generacion"];
			$T_cod_user=$T["cod_user"];
			
			$ruta_archivo=$path."?file=".base64_encode($T_archivo);
			
			///busco si tiene tareas cargadas a este trabajo
			$cons2="SELECT COUNT(id) FROM tareas_docente WHERE tipo='tarea' AND id_trabajo='$T_id' ORDER by fecha_generacion";
			$sqli2=$conexion_mysqli->query($cons2)or die($conexion_mysqli->error);
			$TA=$sqli2->fetch_row();
			$num_tareas_cargadas=$TA[0];	
			if(empty($num_tareas_cargadas)){ $num_tareas_cargadas=0;}
			$sqli2->free();
			$url_tareas="#";
			$url_num_descargas="";
			$class='';
			/////
			//revisar N. descargas.
			$evento="descarga archivo ($T_archivo) T_id [$T_id] carga_descarga_tarea_docente";
			
			$cons3="SELECT COUNT(id) FROM historial WHERE evento='$evento' ORDER by fecha_hora";
			$sqli3=$conexion_mysqli->query($cons3)or die($conexion_mysqli->error);
			$H=$sqli3->fetch_row();
			$num_descargas=$H[0];	
			if(empty($num_descargas)){ $num_descargas=0;}
			$sqli3->free();
			///----------------------------------------------------------/
			
			
			if($num_tareas_cargadas>0){$url_tareas='ver_tareas_cargadas.php?id_trabajo='.$T_id.'&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=530"'; $class='class="lightbox"';}
			
			if($num_descargas>0){$url_num_descargas='resumen_descargas.php?id_trabajo='.base64_encode($T_id).'&T_archivo='.base64_encode($T_archivo).'&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=530"'; $class='class="lightbox"';}
			
			echo'<tr>
				  <td>'.$aux.'</td>
				  <td>'.$T_nombre.'</td>
				  <td>'.$T_descripcion.'</td>
				  <td><a href="'.$ruta_archivo.'" target="_blanck">Descargar</a></td>
				  <td><a href="#" onclick="ELIMINAR_TRABAJO(\''.base64_encode($T_id).'\');">Eliminar</a></td>
				   <td align="center"><a href="comprobar_permisos.php?id_trabajo='.base64_encode($T_id).'&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=530" class="lightbox" title="Permisos">Permisos</a></td>
				  <td align="center"><a href="'.$url_tareas.'" '.$class.' title="Ver tareas Cargadas">'.$num_tareas_cargadas.'</a></td>
				  <td align="center"><a href="'.$url_num_descargas.'" '.$class.' title="Ver quien descarga">'.$num_descargas.'</a></td>
				  </tr>';	
		}
	}
	else
	{echo'<tr><td colspan="8">Sin Trabajos Disponibles...</td></tr>';}
	$sqli->free();
	
	$conexion_mysqli->close();
	
	?>
    </tbody>
  </table>
</div>
</body>
</html>