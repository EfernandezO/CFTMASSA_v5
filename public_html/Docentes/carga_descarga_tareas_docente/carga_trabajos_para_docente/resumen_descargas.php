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

 
</head>

<body>
<h1 id="banner">Ver Quien Descarga</h1>
<div id="apDiv1">
  <table width="100%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="5">Resumen descargas</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>N.</td>
      <td>Docente</td>
      <td>Fecha Hora Descarga</td>
      <td>IP</td>
      <td>Evento</td>
      
    </tr>
    <?php
	 require("../../../../funciones/conexion_v2.php");
	  require("../../../../funciones/funciones_sistema.php");
	
	if(isset($_GET["id_trabajo"]))
	{$id_trabajo=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["id_trabajo"]));}
	else{ $id_trabajo=0;}
	if(isset($_GET["T_archivo"]))
	{$T_archivo=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["T_archivo"]));}
	else{ $T_archivo=0;}
	
	
	
	
	if(DEBUG){ echo"id_trabajo: $id_trabajo -> T_archivo: $T_archivo<br>";}
	if($id_trabajo>0)
	{
   
		 require("../../../../funciones/VX.php");
		 $evento="Revisa quien descarga trabajo id_trabajo: $id_trabajo";
		 REGISTRA_EVENTO($evento);
   		
		
		$evento="descarga archivo ($T_archivo) T_id [$id_trabajo] carga_descarga_tarea_docente";
		$cons3="SELECT * FROM historial WHERE evento='$evento' ORDER by fecha_hora";
		$sqli=$conexion_mysqli->query($cons3)or die($conexion_mysqli->error);
		$num_eventos=$sqli->num_rows;
			if(DEBUG){ echo"--->$cons3<br>num descargas. $num_eventos<br>";}
		$path="../enrutador_v2.php";
		if($num_eventos>0)
		{
			$aux=0;
			while($H=$sqli->fetch_assoc())
			{
				$aux++;
				$H_id=$H["id"];
				$H_tipo_usuario=$H["tipo_usuario"];
				$H_id_user=$H["id_user"];
				$H_ip=$H["ip"];
				$H_fecha_hora=$H["fecha_hora"];
				$H_evento=$H["evento"];

				$docente=NOMBRE_PERSONAL($H_id_user);
				
				echo'<tr>
					  <td>'.$aux.'</td>
					  <td>'.$docente.'</td>
					  <td>'.$H_fecha_hora.'</td>
					  <td>'.$H_ip.'</td>
					  <td>'.$H_evento.'</td>
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