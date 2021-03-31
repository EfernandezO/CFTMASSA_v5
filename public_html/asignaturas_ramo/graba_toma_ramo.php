<?php
//--------------CLASS_okalis------------------//
	require("../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Toma_de_ramos_v1->GRABAR");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

  if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
  {
	  $fecha_actual=date("Y-m-d");
	  $id_usuario_activo=$_SESSION["USUARIO"]["id"];
	  
	  if(DEBUG){var_dump($_POST);}
		$id_carrera=$_POST["id_carrera"];
		$id_alumno=$_POST["id_alumno"];
		$yearIngresoCarrera=$_POST["yearIngresoCarrera"];
		
		$semestre=$_POST["semestre"];
		$year=$_POST["year"];

		$metodo=$_POST["metodo"];
		$nivel_alumno=$_POST["nivel_alumno"];
		$tomar_ramo=$_POST["tomar_ramo"];
		$array_jornada=$_POST["jornada"];
		$num_ramos_a_tomar=count($tomar_ramo);
		
			require("../../funciones/conexion_v2.php");
			$hay_registros=TIENE_REGISTRO($id_alumno, $id_carrera, $yearIngresoCarrera, $semestre, $year);
			////---------------------------------------------------------------////
			if($hay_registros)
			{
				if(DEBUG){ echo"Existen Registros Previos <br>";}
				if($metodo=="crear"){
					ELIMINA_REGISTROS($id_alumno, $id_carrera, $yearIngresoCarrera, $semestre, $year);
				}
			}
			else
			{
				if(DEBUG){ echo"No existen Registros Previos...<br>";}
			}
			////---------------------------------------------------------------////
			if($num_ramos_a_tomar>0)
			{
				//-------------------------------------------//
				require("../../funciones/VX.php");
				$evento="Registrando Toma de Ramos a alumno id_alumno: $id_alumno id_carrera: $id_carrera [$semestre - $year]";
				REGISTRA_EVENTO($evento);
				$descripcion="Realizacion de Toma de Ramos para periodo [$semestre - $year] en carrera id_carrera: $id_carrera Numero ramos a tomar: $num_ramos_a_tomar";
				REGISTRO_EVENTO_ALUMNO($id_alumno,"Notificacion",$descripcion);
				//------------------------------------------//
				if(DEBUG) { echo"Num ramos a Tomar: $num_ramos_a_tomar<br>";}
				$campos="id_alumno, id_carrera, yearIngresoCarrera, jornada, nivel, semestre, year, cod_asignatura, condicion, fecha_generacion, cod_user";
				$error="T0";
				foreach($tomar_ramo as $codigo =>$estado)
				{
					$aux_jornada=$array_jornada[$codigo];
					if(DEBUG){ echo"$codigo -> $estado<br>Jornada: $aux_jornada<br>";}
					if($estado=="si")
					{
						$condicion="ok";
						$valores="'$id_alumno', '$id_carrera', '$yearIngresoCarrera', '$aux_jornada', '$nivel_alumno', '$semestre', '$year', '$codigo', '$condicion', '$fecha_actual', '$id_usuario_activo'";
						$cons_IN="INSERT INTO toma_ramos ($campos) VALUES($valores)";
						if(DEBUG){ echo"----> $cons_IN<br>";}
						else{$conexion_mysqli->query($cons_IN)or die($conexion_mysqli->error);}
					}
				}
			}
			else
			{
				$error="T1";
				if(DEBUG){ echo"Sin Ramos que tomar<br>";}
			}
			////---------------------------------------------------------------////
		$conexion_mysqli->close();	

		
		$url="toma_ramo_final.php?error=$error&id_carrera=".base64_encode($id_carrera)."&yearIngresoCarrera=".base64_encode($yearIngresoCarrera)."&semestre=".base64_encode($semestre)."&year=".base64_encode($year)."&yearIngresoCarrera=".base64_encode($yearIngresoCarrera);
		if(DEBUG){ echo"URL: $url<br>";}
		else{ header("location: $url");}
  }
  else
  {
	  $url="tomaramo_individual.php";
	  if(DEBUG){ echo"No hay Alumno Activo<br>";}
	  else{ header("location: $url");}
  }
  
/////////////////////////////////////////////////////////////////////  
 function TIENE_REGISTRO($id_alumno, $id_carrera, $yearIngresoCarrera, $semestre, $year)
 {
	require("../../funciones/conexion_v2.php");
	 $cons="SELECT COUNT(id) FROM toma_ramos WHERE id_alumno='$id_alumno' AND semestre='$semestre' AND year='$year'";
	 $sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	 $D=$sql->fetch_row();
	 $coincidencias=$D[0];
	 if(empty($coincidencias)){ $coincidencias=0;}
	 if(DEBUG){ echo"-->$cons<br>Num: $coincidencias<br>";}
	 
	 if($coincidencias>0)
	 { $respuesta=true;}
	 else
	 { $respuesta=false;}
	 
	 $sql->free();
	 $conexion_mysqli->close();
	 return($respuesta);
 }
 ////////////////////////
 function ELIMINA_REGISTROS($id_alumno, $id_carrera, $yearIngresoCarrera, $semestre, $year)
 {
	require("../../funciones/conexion_v2.php");
	 if(DEBUG){ echo"xxxxxxxxxxxxxELIMINA REGISTROSxxxxxxxxxx<br>";}
	 $cons_D="DELETE FROM toma_ramos WHERE id_alumno='$id_alumno' AND semestre='$semestre' AND year='$year'";
	 
	 if(DEBUG){ echo"X-> $cons_D<br>";}
	 else{ $conexion_mysqli->query($cons_D)or die("ELIMINA REGISTROS ".$conexion_mysqli->error);}
	  if(DEBUG){ echo"xxxxxxxxxxxxxxxxFINxxxxxxxxxxxxxxx<br>";}
	  $conexion_mysqli->close();
 }
?>