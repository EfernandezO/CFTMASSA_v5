<?php
//-----------------------------------------//
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Planificaciones->comparador");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------////
if($_GET)
{
	$error="debug";
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funciones_sistema.php");
	
	if(DEBUG){ var_dump($_GET);}
	$id_carrera=mysqli_real_escape_string($conexion_mysqli, $_GET["id_carrera"]);
	$cod_asignatura=mysqli_real_escape_string($conexion_mysqli, $_GET["asignatura"]);
	$jornada=mysqli_real_escape_string($conexion_mysqli, $_GET["jornada"]);
	$grupo_curso=mysqli_real_escape_string($conexion_mysqli, $_GET["grupo"]);
	$sede=mysqli_real_escape_string($conexion_mysqli, $_GET["sede"]);
	$semestre=mysqli_real_escape_string($conexion_mysqli, $_GET["semestre"]);
	$year=mysqli_real_escape_string($conexion_mysqli, $_GET["year"]);
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	
	list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<title>Compara Planificacion</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:75px;
	z-index:1;
	left: 5%;
	top: 236px;
}
#apDiv2 {
	position:absolute;
	width:50%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 89px;
}
</style>
<body>
<h1 id="banner">Administrador - Compara Planificaciones</h1>
<div id="link"><br />
<a href="../../contenidos/ver_contenidos.php?semestre=<?php echo base64_encode($semestre);?>&amp;year=<?php echo base64_encode($year);?>&amp;sede=<?php echo base64_encode($sede);?>&amp;id_carrera=<?php echo base64_encode($id_carrera);?>&amp;cod_asignatura=<?php echo base64_encode($cod_asignatura);?>&amp;jornada=<?php echo base64_encode($jornada);?>&amp;grupo_curso=<?php echo base64_encode($grupo_curso);?>" class="button">Volver</a></div>
<div id="apDiv1">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="7">Otras Planificaciones</th>
    </tr>
    </thead>
    <tbody>
    <tr>
    	<td>Sede</td>
    	<td>Año</td>	
        <td>Semestre</td>
        <td>Jornada</td>
        <td>Grupo</td>
        <td>Docente</td>
        <td>Opcion</td>
    </tr>
   <?php
  $cons="SELECT `id_funcionario`, jornada, grupo, `semestre`, `year`, `sede` FROM `planificaciones` WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND id_funcionario<>'$id_usuario_actual' GROUP BY `id_funcionario`, jornada, grupo, `semestre`, `year`, `sede` ORDER by `id_funcionario`, `semestre`, `year`, `sede`";
   $sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
   $num_planificaciones=$sqli->num_rows;
   if(DEBUG){ echo"-->$cons<br> N. $num_planificaciones<br>";}
   if($num_planificaciones>0)
   {
	   $aux=0;
	   while($P=$sqli->fetch_assoc())
	   {
		   $aux++;
		   $P_id_funcionario=$P["id_funcionario"];
		   $P_semestre=$P["semestre"];
		   $P_year=$P["year"];
		   $P_sede=$P["sede"];
		   $P_jornada=$P["jornada"];
		   $P_grupo=$P["grupo"];

		   echo'<tr>
		   			<td>'.$P_sede.'</td>
					<td>'.$P_year.'</td>
					<td>'.$P_semestre.'</td>
					<td>'.$P_jornada.'</td>
					<td>'.$P_grupo.'</td>
					<td>'.NOMBRE_PERSONAL($P_id_funcionario).'</td>
					<td><a href="../informe_imprimible/informe_imprimible_1.php?id_funcionario='.base64_encode($P_id_funcionario).'&sede='.base64_encode($P_sede).'&id_carrera='.base64_encode($id_carrera).'&jornada='.base64_encode($P_jornada).'&grupo='.base64_encode($grupo_curso).'&asignatura='.base64_encode($cod_asignatura).'&semestre='.base64_encode($P_semestre).'&year='.base64_encode($P_year).'" target="_blank">Ver Planificacion</a></td>
		   		</tr>';
	   }
   }
   else
   {
	   echo'<tr><td colspan="7">Sin Planificaciones Similares</td></tr>';
	}
   $sqli->free();
   $conexion_mysqli->close();
   ?>
    </tbody>
  </table>

</div>
<div id="apDiv2">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="2">Informacion</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td colspan="2">Planificaciones ya Existentes</td>
    </tr>
    <tr>
      <td width="17%">Carrera</td>
      <td width="83%"><?php echo $id_carrera."_".NOMBRE_CARRERA($id_carrera);?></td>
    </tr>
    <tr>
      <td>Asignatura</td>
      <td><?php echo $cod_asignatura."_".$nombre_asignatura?></td>
    </tr>
    </tbody>
  </table>
</div>
</body>
</html>