<?php
	//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Docentes->estudioTrabajo");
	$O->PERMITIR_ACCESO_USUARIO();
//-----------------------------------------//	
if($_POST)	
{
	if(DEBUG){ var_dump($_POST);}
	require("../../../../funciones/conexion_v2.php");
	
	  $id_funcionario=mysqli_real_escape_string($conexion_mysqli,$_POST["id_funcionario"]);
	  $E_id=mysqli_real_escape_string($conexion_mysqli,$_POST["E_id"]);
	  $nombre_institucion=mysqli_real_escape_string($conexion_mysqli,$_POST["nombre_institucion"]);
	  $year_inicio=mysqli_real_escape_string($conexion_mysqli,$_POST["year_inicio"]);
	  $year_fin=mysqli_real_escape_string($conexion_mysqli,$_POST["year_fin"]);
	  $titulo=mysqli_real_escape_string($conexion_mysqli,$_POST["titulo"]);
	  $grado_academico=mysqli_real_escape_string($conexion_mysqli,$_POST["grado_academico"]);
	  $pais=mysqli_real_escape_string($conexion_mysqli,$_POST["pais"]);
	  $fecha_obtencion_titulo=mysqli_real_escape_string($conexion_mysqli,$_POST["fecha_obtencion_titulo"]);
	  $descripcion=mysqli_real_escape_string($conexion_mysqli,$_POST["descripcion"]);
	  
	  $cons_UP="UPDATE personal_registro_estudios SET nombre_institucion='$nombre_institucion', year_inicio='$year_inicio', year_fin='$year_fin', titulo='$titulo', cod_grado_academico='$grado_academico', pais_titulo='$pais', fecha_titulo='$fecha_obtencion_titulo', descripcion='$descripcion' WHERE id='$E_id' AND id_funcionario='$id_funcionario' LIMIT 1";
	  
	  if(DEBUG){ echo"---> $cons_UP<br>";}
	  else{ $conexion_mysqli->query($cons_UP);}
  
  if(DEBUG){}
  else{ header("location: editar_estudios_3.php?error=C0");}
  

  $conexion_mysqli->close();
  
}
else
{}
?>