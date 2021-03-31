<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Resumen General -> Alumnos MAtriculados nivel 1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Detalle de Alumnos</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 90px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Resum&eacute;n General Alumnos Matriculados nivel 1</h1>
<div id="apDiv1">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="9">Alumnos</th>
    </tr>
    <tr>
      <td>N</td>
      <td>Rut</td>
      <td>Alumno</td>
      <td>Carrera</td>
      <td>Jor-Grp</td>
      <td>Year Ingreso</td>
	  <td>Situacion</td>     
      <td>Fecha Matricula</td>
      <td>usuario matricula</td>
    </tr>
    </thead>
    <tbody>
   <?php
  	 require("../../../funciones/conexion_v2.php");
	 require("../../../funciones/funciones_sistema.php");
	 if($_GET)
	 {
		 $sede=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["sede"]));
		 $jornada=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["jornada"]));
		 $id_carrera=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["id_carrera"]));
		 $year_consulta=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["year_consulta"]));
		 
		 
		 
		 $cons_C="SELECT contratos2.yearIngresoCarrera, contratos2.ano, contratos2.id_carrera, contratos2.jornada, contratos2.id_alumno, alumno.rut, alumno.nombre, alumno.apellido_P, alumno.apellido_M, alumno.situacion, contratos2.fecha_generacion, contratos2.cod_user, alumno.cod_user AS cod_user_alumno, alumno.grupo, alumno.sexo  FROM contratos2 INNER JOIN alumno ON contratos2.id_alumno = alumno.id WHERE contratos2.nivel_alumno='1' AND contratos2.ano='$year_consulta' AND contratos2.id_carrera='$id_carrera' AND contratos2.sede='$sede' AND contratos2.jornada='$jornada' AND condicion<>'inactivo' AND contratos2.yearIngresoCarrera='$year_consulta'";
		 
		 if(DEBUG){ echo"---> $cons_C<br>";}
		 $sqli_C=$conexion_mysqli->query($cons_C)or die($conexion_mysqli->error);
		 $num_contratos=$sqli_C->num_rows;
		 if($num_contratos>0)
		 {
			 $aux=0;
			 $numero_hombre=0;
			 $numero_mujeres=0;
			 while($C=$sqli_C->fetch_assoc())
			 {
				 $aux++;
				 $C_id_alumno=$C["id_alumno"];
				 $A_rut=$C["rut"];
				 $A_nombre=$C["nombre"];
				 $A_apellido_P=$C["apellido_P"];
				 $A_apellido_M=$C["apellido_M"];
				 $C_id_carrera=$C["id_carrera"];
				 $A_jornada=$C["jornada"];
				 $A_grupo=$C["grupo"];
				 
				 $yearIngresoCarrera=$C["yearIngresoCarrera"];
				 $A_situacion=$C["situacion"];
				 $C_fecha_generacion=$C["fecha_generacion"];
				 $C_cod_user=$C["cod_user"];
				 $A_cod_user=$C["cod_user_alumno"];
				 $A_sexo=$C["sexo"];
				 
				 if($A_sexo=="M"){ $numero_hombre++;}
				 else{ $numero_mujeres++;}
				 
				 if(DEBUG){ echo"$aux -> $C_id_alumno<br>";}
				 
				 echo'<tr>
				 		<td>'.$aux.'</td>
						<td>'.$A_rut.'</td>
						<td>'.$A_nombre.' '.$A_apellido_P.' '.$A_apellido_M.'</td>
						<td>'.NOMBRE_CARRERA($C_id_carrera).'</td>
						<td>'.$A_jornada.'-'.$A_grupo.'</td>
						<td>'.$yearIngresoCarrera.'</td>
						<td>'.$A_situacion.'</td>
						<td>'.$C_fecha_generacion.'</td>
						<td>'.NOMBRE_PERSONAL($A_cod_user).' -> '.NOMBRE_PERSONAL($C_cod_user).'</td>
				 	  </tr>';
				 
			 }
			 
			 echo'<tr>
			 		<td colspan="9">['.$numero_hombre.'] Hombres y ['.$numero_mujeres.'] Mujeres</td>
			
			 	  </tr>';
		 }
		 else
		 {
			 if(DEBUG){ echo"<tr><td>Sin Contratos encontrados :(</td></tr>";}
		 }
	 }
	 else{if(DEBUG){ echo"Sin Datos<br>";}}
	 $conexion_mysqli->close();
	 @mysql_close($conexion);
   ?>
    </tbody>
  </table>
</div>
</body>
</html>