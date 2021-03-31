<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("ver_horario_docente_general");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_POST)
{
	if(DEBUG){ var_dump($_POST);}
	$continuar=true;
	$sede=$_POST["fsede"];
	$year=$_POST["year"];
	$semestre=$_POST["semestre"];
	
	$id_carrera=$_POST["carrera"];
	$nivel=$_POST["nivel"];
	$jornada=$_POST["jornada"];
}
else
{ $continuar=false;}
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php");?>
<title>Revisa Horario</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:70px;
	z-index:1;
	left: 5%;
	top: 152px;
}
#apDiv2 {
	position:absolute;
	width:35%;
	height:31px;
	z-index:2;
	left: 35%;
	top: 198px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Revisi&oacute;n Horario Docente</h1>
<div id="link"><br />
<a href="revisa_horario_1.php" class="button">Volver a Seleccion</a></div>
<div id="apDiv1">

    <table width="100%" border="1" align="center">
      <thead>
        <tr>
          <th colspan="16">Horario Docente <?php echo "$sede Periodo [$semestre - $year]<br> id_carrera: $id_carrera - Nivel: $nivel  Jornada: $jornada";?></th>
        </tr>
      </thead>
      <tr>
      	<td>N.</td>
        <td>N. Semanas</td>
        <td>Dia</td>
        <td>Hr. Inicio</td>
        <td>Hr. Fin</td>
      	<td>Carrera</td>
        <td>Nivel</td>
        <td>Asignatura</td>
         <td>Jor-Grup</td>
         <td>Funcionario</td>
         <td>Sala</td>
      </tr>
      <tbody>
      <?php
      if($continuar)
	  {
		  require("../../../../../funciones/conexion_v2.php");
		  require("../../../../../funciones/funciones_sistema.php");
		  
		  
		  $sede=mysqli_real_escape_string($conexion_mysqli, $sede);
		  $semeste=mysqli_real_escape_string($conexion_mysqli, $semestre);
		  $year=mysqli_real_escape_string($conexion_mysqli, $year);
		  
		  $id_carrera=mysqli_real_escape_string($conexion_mysqli, $id_carrera);
		  $nivel=mysqli_real_escape_string($conexion_mysqli, $nivel);
		  $jornada=mysqli_real_escape_string($conexion_mysqli, $jornada);
		  
		  if($id_carrera>0){ $condicion_carrera="AND toma_ramo_docente.id_carrera='$id_carrera'";}
		  else{ $condicion_carrera="";}
		  
		  if($jornada<>"0"){ $condicion_jornada="AND toma_ramo_docente.jornada='$jornada'";}
		  else{ $condicion_jornada="";}
		  
		  
		  
	  		$cons="SELECT toma_ramo_docente.*, horario_docente.* FROM toma_ramo_docente INNER JOIN horario_docente ON toma_ramo_docente.id=horario_docente.id_asignacion WHERE toma_ramo_docente.sede='$sede' AND toma_ramo_docente.semestre='$semestre' AND toma_ramo_docente.year='$year' $condicion_carrera $condicion_jornada ORDER by horario_docente.dia_semana, horario_docente.hora_inicio";
			if(DEBUG){echo"->$cons<br>";}
			$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$num_registros=$sqli->num_rows;
			$array_dia=array(0 =>"Domingo",
				 1=>"Lunes",
				 2=>"Martes",
				 3=>"Miercoles",
				 4=>"Jueves",
				 5=>"Viernes",
				 6=>"Sabado");
			if($num_registros>0)
			{
				$aux=0;
				while($AS=$sqli->fetch_assoc())
				{
					$aux++;
					
					$AS_id_funcionario=$AS["id_funcionario"];
					$AS_id_carrera=$AS["id_carrera"];
					$AS_jornada=$AS["jornada"];
					$AS_grupo=$AS["grupo"];
					$AS_cod_asignatura=$AS["cod_asignatura"];
					
					$H_semanaInicio=$AS["semanaInicio"];
					$H_semanaFin=$AS["semanaFin"];
					$H_dia_semana=$AS["dia_semana"];
					$H_hora_inicio=$AS["hora_inicio"];
					$H_hora_fin=$AS["hora_fin"];
					$H_sala=$AS["sala"];
					
					//Datos funcionarios
					$nombre_funcionario=NOMBRE_PERSONAL($AS_id_funcionario);
					//--------------------------------------------------------------------//	
					//carrera
					$nombre_carrera=NOMBRE_CARRERA($AS_id_carrera);
					//----------------------------//
					//asignatura
						list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($AS_id_carrera, $AS_cod_asignatura);
					//----------------------------------------------------------------//
					if($nivel==0){ $mostrar=true;}
					else
					{
						if($nivel==$nivel_asignatura){ $mostrar=true;}
						else{ $mostrar=false;}
					}
					if($mostrar)
					{
						echo'<tr>
								<td>'.$aux.'</td>
								<td>'.$H_semanaInicio.' - '.$H_semanaFin.'</td>
								<td>'.$array_dia[$H_dia_semana].'</td>
								<td>'.$H_hora_inicio.'</td>
								<td>'.$H_hora_fin.'</td>
								<td>'.$nombre_carrera.'</td>
								<td>'.$nivel_asignatura.'</td>
								<td>'.$nombre_asignatura.'</td>
								<td align="center">'.$AS_jornada.'-'.$AS_grupo.'</td>
								<td>'.$nombre_funcionario.'</td>
								<td>'.$H_sala.'</td>
								</tr>';	
						//-----------------------------------------------//		
						$primera_vuelta=false;
					}
					
							
					
				}
			}
			else
			{ echo'<tr><td colspan="15">Sin Asignaciones Creadas</td></tr>';}
			$sqli->free();
			
		  mysql_close($conexion);
		  $conexion_mysqli->close();
	  }
	  else
	  { echo'<tr><td colspan="15">Sin datos</td></tr>';}
	  ?>
      </tbody>
    </table><br />
<br />
</div>
</body>
</html>