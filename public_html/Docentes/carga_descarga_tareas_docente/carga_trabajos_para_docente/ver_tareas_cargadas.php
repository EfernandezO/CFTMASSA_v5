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
	top: 85px;
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
function ELIMINAR(id_tarea)
{
	url="elimina_tarea.php?id_tarea="+id_tarea;
	c=confirm('Seguro(a) Desea Eliminar esta tarea...')
	if(c){window.location=url;}
}
</script>
 
</head>

<body>
<h1 id="banner">Ver Tareas Cargadas</h1>
<div id="apDiv1">
  <table width="100%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="5">Tareas Cargadas</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>N.</td>
      <td>Docente</td>
      <td>Fecha Carga</td>
      <td colspan="2">Opciones</td>
      
    </tr>
    <?php
	 require("../../../../funciones/conexion_v2.php");
	  require("../../../../funciones/funciones_sistema.php");
	
	if(isset($_GET["id_trabajo"]))
	{$id_trabajo=mysqli_real_escape_string($conexion_mysqli, $_GET["id_trabajo"]);}
	else{ $id_trabajo=0;}
	
	if($id_trabajo>0)
	{
   
		 require("../../../../funciones/VX.php");
		 $evento="Revisa Tareas cargadas al trabajo id_trabajo: $id_trabajo";
		 REGISTRA_EVENTO($evento);
   		
		$cons="SELECT * FROM tareas_docente WHERE tipo='tarea' AND id_trabajo='$id_trabajo' ORDER by fecha_generacion";
	
		$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$num_trabajos=$sqli->num_rows;
			if(DEBUG){ echo"--->$cons<br>num tareas cargadas. $num_trabajos<br>";}
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
				
				$ruta_archivo_tarea=$path."?file=".base64_encode($T_archivo);

				$docente=NOMBRE_PERSONAL($T_cod_user);
				echo'<tr>
					  <td>'.$aux.'</td>
					  <td>'.$docente.'</td>
					  <td>'.$T_fecha_generacion.'</td>
					  <td><a href="'.$ruta_archivo_tarea.'">ver</a></td>
					  <td><a href="#" onclick="ELIMINAR(\''.base64_encode($T_id).'\');">Eliminar</a></td>
					  </tr>';	
			}
		}
		else
		{echo'<tr><td colspan="5">Sin Tareas Cargadas..</td></tr>';}
		$sqli->free();
	}
	$conexion_mysqli->close();
	
	?>
    </tbody>
  </table>
</div>
</body>
</html>