<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Notas_parcialesV3->Editar");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<title>Edicion de Evaluaciones</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:80px;
	z-index:1;
	left: 5%;
	top: 190px;
	text-align: center;
}
</style>
<?php
if($_GET)
{
	$sede=base64_decode($_GET["sede"]);
	$id_carrera=base64_decode($_GET["id_carrera"]);
	$jornada=base64_decode($_GET["jornada"]);
	$grupo_curso=base64_decode($_GET["grupo"]);
	$cod_asignatura=base64_decode($_GET["cod_asignatura"]);
	$semestre=base64_decode($_GET["semestre"]);
	$year=base64_decode($_GET["year"]);
	if(isset($_GET["id_alumno"]))
	{
		$id_alumno_destacado=base64_decode($_GET["id_alumno"]);
		$dato_get="&id_alumno=".base64_encode($id_alumno_destacado);
	}
	else{ $dato_get="";}
}
?>
</head>

<body>
<h1 id="banner">Administrador -  Edicion Evaluciones </h1>
<div id="link"><br />
<a href="../ver_evaluaciones.php?sede=<?php echo base64_encode($sede);?>&id_carrera=<?php echo base64_encode($id_carrera);?>&jornada=<?php echo base64_encode($jornada);?>&grupo_curso=<?php echo base64_encode($grupo_curso);?>&cod_asignatura=<?php echo base64_encode($cod_asignatura);?>&semestre=<?php echo base64_encode($semestre);?>&year=<?php echo base64_encode($year).$dato_get;?>" class="button">Volver</a></div>
<div id="apDiv1">
  <table width="50%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="8">Evaluaciones Existentes</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>N</td>
      <td>Nombre Evaluacion</td>
      <td>Fecha Generacion</td>
      <td>Fecha Evaluacion</td>
      <td>Tipo Evaluacion</td>
      <td>Porcentaje</td>
      <td colspan="2">Opciones</td>
    </tr>
     <?php
	 	include("../../../../funciones/conexion_v2.php");
		include("../../../../funciones/funcion.php");
	 	$cons_e="SELECT * FROM notas_parciales_evaluaciones WHERE sede='$sede' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND jornada='$jornada' AND grupo='$grupo_curso' AND semestre='$semestre' AND year='$year'";
		$sql_e=$conexion_mysqli->query($cons_e);
		$num_evaluaciones=$sql_e->num_rows;
		if(DEBUG){ echo"$cons_e<br>num evaluaciones: $num_evaluaciones<br>";}
		$array_evaluaciones=array();
		if($num_evaluaciones>0)
		{
			$aux=0;
			while($E=$sql_e->fetch_assoc())
			{
				$aux++;
				$id_evaluacion=$E["id"];
				$nombre_evaluacion=$E["nombre_evaluacion"];
				$fecha_generacion=$E["fecha_generacion"];
				$fecha_evaluacion=$E["fecha_evaluacion"];
				$metodo_evaluacion=$E["metodo_evaluacion"];
				$porcentaje=$E["porcentaje"];
				
				$array_evaluaciones[$id_evaluacion]=$porcentaje;
				$array_evaluaciones_metodo[$id_evaluacion]=$metodo_evaluacion;
				
				echo'<tr>
						 <td>'.$aux.'</td>
						  <td>'.$nombre_evaluacion.'</td>
						  <td>'.fecha_format($fecha_generacion).'</td>
						  <td>'.fecha_format($fecha_evaluacion).'</td>
						  <td>'.$metodo_evaluacion.'</td>
						  <td>'.$porcentaje.'%</td>
						  <td><a href="edicion_evaluacion/edita_evaluacion.php?sede='.base64_encode($sede).'&id_carrera='.base64_encode($id_carrera).'&jornada='.base64_encode($jornada).'&grupo='.base64_encode($grupo_curso).'&cod_asignatura='.base64_encode($cod_asignatura).'&semestre='.base64_encode($semestre).'&year='.base64_encode($year).$dato_get.'&id_evaluacion='.base64_encode($id_evaluacion).'" title="Editar Evaluacion"><img src="../../../BAses/Images/b_edit.png" width="16" height="16" alt="editar" /></a></td>
						  <td><a href="borrar_evaluacion/borrar_evaluacion.php?sede='.base64_encode($sede).'&id_carrera='.base64_encode($id_carrera).'&jornada='.base64_encode($jornada).'&grupo='.base64_encode($grupo_curso).'&cod_asignatura='.base64_encode($cod_asignatura).'&semestre='.base64_encode($semestre).'&year='.base64_encode($year).$dato_get.'&id_evaluacion='.base64_encode($id_evaluacion).'" title="Borrar Evaluacion"><img src="../../../BAses/Images/b_drop.png" width="16" height="16" alt="borrar" /></a></td>
					 </tr>';
			}
		}
		else
		{
			echo'<tr><td colspan="8">Sin Evaluacion Creadas...</td></tr>';
		}
		$sql_e->free();
		
	 ?>
    </tbody>
  </table>
  <div id="mensaje">
  <?php
  if(isset($_GET["error"]))
  {
	  $img_ok='<img src="../../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
	  $img_error='<img src="../../../BAses/Images/b_drop.png" width="16" height="16" alt="X" />';
	  $error=$_GET["error"];
	  switch($error)
	  {
		  case "0":
		  		$msj="Evaluacion Eliminada Correctamente...";
				$img=$img_ok;
		  	break;
		  case "1":
		  		$msj="Evaluacion modificada Correctamente...";
				$img=$img_ok;
		  	break;
			default:
				$msj="";
				$img="";
	  }
	  
	  echo $msj.$img;
  }
  ?>
  </div>
</div>
</body>
</html>