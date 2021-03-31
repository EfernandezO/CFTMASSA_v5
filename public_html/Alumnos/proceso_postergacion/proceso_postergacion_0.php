<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno->proceso_postergacion_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
$array_motivo_postergacion=array("1"=>"Dificultades Economicas",
						   "2"=>"No obtener beca ni financiamiento",
						   "3"=>"Excluido por Motivos Diciplinarios",
						   "4"=>"Retiro por aplazamiento del semestre",
						   "5"=>"Excluido por bajo rendimiento academico",
						   "6"=>"No cumplimiento con expectativas academicas",
						   "7"=>"No cumplimiento con expectativas de equipamiento",
						   "8"=>"Erronea eleccion de carrera a estudiar",
						   "9"=>"Cambio a otra institucion",
						   "10"=>"Dificultades familiares",
						   "11"=>"Problemas de Salud",
						   "12"=>"Cambio Domicilio personal a otra ciudad",
						   "13"=>"Cambio de ubicacion o condicion Laboral",
						   "14"=>"No se imparte la carrera");
						   
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");					   
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php"); ?>
<title>Proceso de Postergacion</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 62px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:22px;
	z-index:2;
	left: 5%;
	top: 263px;
}
#apDiv2 {
	border: medium solid #39C;
}
#apDiv3 {
	position:absolute;
	width:60%;
	height:28px;
	z-index:3;
	left: 20%;
	top: 323px;
	text-align: center;
}
</style>

</head>

<body>
<h1 id="banner">Administrador - Proceso Postergacion</h1>
<div id="apDiv1">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="7">Procesos Postergacion
       </th>
    </tr>
    <tr>
      <td>N</td>
      <td>perido</td>
      <td>semestres suspencion</td>
      <td>Observacion</td>
      <td>Fecha generacion</td>
      <td>Usuario</td>
      <td>Opc</td>
    </tr>
    </thead>
    <tbody>
  <?php
  $cons="SELECT * FROM proceso_postergacion WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' ORDER by id_postergacion DESC";
  $sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
  $num_postergaciones=$sqli->num_rows;
  if($num_postergaciones>0)
  {
	  $aux=0;
	  while($P=$sqli->fetch_assoc())
	  {
		  $aux++;
		  $P_id=$P["id_postergacion"];
		  $P_semeste=$P["semestre_postergacion"];
		  $P_year=$P["year_postergacion"];
		  $P_semestre_suspencion=$P["semestres_suspencion"];
		  $P_observacion=$P["observacion"];
		  $P_fecha_generacion=$P["fecha_generacion"];
		  $P_cod_user=$P["cod_user"];
		  
		  echo'<tr>
		  			<td>'.$aux.'</td>
					<td>'.$P_semeste.' - '.$P_year.'</td>
					<td>'.$P_semestre_suspencion.'</td>
					<td>'.$P_observacion.'</td>
					<td>'.$P_fecha_generacion.'</td>
					<td>'.$P_cod_user.'</td>
					<td><a href="proceso_postergacion_1.php?P_id='.base64_encode($P_id).'">Ver</a></td>
		  	   </tr>';
	  }
  }
  else
  {  echo'<tr><td>Sin Postergaciones</td></tr>';}
  $sqli->free();
  $conexion_mysqli->close();
  ?>
    </tbody>
  </table>
</div>
<div id="apDiv3"><a href="proceso_postergacion_1.php" class="button_G" >Crear Nueva Postergacion</a></div>
</body>
</html>