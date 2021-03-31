<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno->Registro Actividades V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
$alumno_seleccionado=false;
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{ 
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
	{ $alumno_seleccionado=true;}
}

if($alumno_seleccionado)
{
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	@$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$carrera=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
	$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
}
else
{ header("location: ../../buscador_alumno_BETA/HALL/index.php");}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>Operaciones Realizadas</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:22px;
	z-index:1;
	left: 5%;
	top: 251px;
}
</style>
<style type="text/css" title="currentStyle">
			@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_page.css";
			@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_table_jui.css";
			@import "../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/examples/examples_support/themes/smoothness/jquery-ui-1.8.4.custom.css";
		#apDiv2 {
	position:absolute;
	width:50%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 82px;
}
</style>
		<script type="text/javascript" language="javascript" src="../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				oTable = $('#example').dataTable({
					"bJQueryUI": true
				});
			} );
		</script>
</head>

<body>
<h1 id="banner">Operaciones Realizadas</h1>
<div id="link"><br />
<a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver</a><br /><br />
<A href="ver_registro_actividades_historial_general.php" class="button_R">Ver Historial General</A>
</div>
<div id="apDiv2">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="2">Datos Alumno</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="20%">ID Alumno</td>
      <td width="80%"><?php echo $_SESSION["SELECTOR_ALUMNO"]["id"];?></td>
    </tr>
    <tr>
      <td>Nombre</td>
      <td><?php echo $_SESSION["SELECTOR_ALUMNO"]["nombre"];?></td>
    </tr>
    <tr>
      <td>Apellido</td>
      <td><?php echo $_SESSION["SELECTOR_ALUMNO"]["apellido"];?></td>
    </tr>
    <tr>
      <td>Carrera</td>
      <td><?php echo $_SESSION["SELECTOR_ALUMNO"]["carrera"];?></td>
    </tr>
    </tbody>
  </table>
</div>
<div id="apDiv1" class="demo_jui">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" class="display" id="example">
  <thead>
    <tr>
      <th>N.</th>
      <th>Tipo Registro</th>
      <th>Descripcion</th>
      <th>Fecha Hora</th>
      <th>Usuario</th>
    </tr>
    </thead>
    <tbody>
   <?php
   if($alumno_seleccionado)
   {
	  require("../../../funciones/conexion_v2.php");
	  include("../../../funciones/funcion.php");
	  include("../../../funciones/funciones_sistema.php");
	   $cons="SELECT * FROM alumno_registros WHERE id_alumno='$id_alumno' ORDER by id";
	   if(DEBUG){ echo"--->$cons<br>";}
	   $sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	   $num_reg=$sql->num_rows;
	   if($num_reg>0)
	   {
		   	$contador=0;
			while($R=$sql->fetch_assoc())
			{
				$contador++;
				
				$tipo_registro=$R["tipo_registro"];
				$descripcion=$R["descripcion"];
				$fecha_hora=$R["fecha_generacion"];
				$cod_user=$R["cod_user"];
				$usuario_nombre=NOMBRE_PERSONAL($cod_user);
					
				echo'<tr class="gradeX">
						<td>'.$contador.'</td>
						<td>'.$tipo_registro.'</td>
						<td>'.$descripcion.'</td>
						<td>'.$fecha_hora.'</td>
						<td><a href="#" title="'.$usuario_nombre.'">'.$cod_user.'</a></td>
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
  <div id="msj">
  <?php
  if(isset($_GET["error"]))
  {
	  $error=$_GET["error"];
	  $img_ok='<img src="../../BAses/Images/ok.png" width="29" height="26" />';
	  $img_error='<img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="X" />';
	  
	  switch($error)
	  {
		  case"I0":
		  	$msj="Registro Realizado...";
			$img=$img_ok;
			break;
		  case"I1":
		  	$msj="Fallo al crear Registro...";
			$img=$img_error;
			break;	
		case"E0":
		  	$msj="Registro Eliminado";
			$img=$img_ok;
			break;
		  case"E1":
		  	$msj="Fallo al Eliminar Registro";
			$img=$img_error;
			break;		
	  }
	  
	  echo"$msj $img";
  }
  ?>
  </div>
</div>
</body>
</html>