<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Proceso_titulacion_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

$error=0;
if(($_POST)and($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{	
	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$sede=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	$yearIngresoCarrera=$_SESSION["SELECTOR_ALUMNO"]["yearIngresoCarrera"];
	
	$id_usuario_activo=$_SESSION["USUARIO"]["id"];
	$fecha_actual=date("Y-m-d");
	if(DEBUG){ var_export($_POST);}
	
		$codigo_registro=str_inde($_POST["codigo_registro"],"");
		$practica_condicion=$_POST["practica_condicion"];
		$practica_fecha_inicio=str_inde($_POST["practica_fecha_inicio"],"0000-00-00");
		$practica_lugar=str_inde($_POST["practica_lugar"],"");
		$informe_fecha_recepcion=str_inde($_POST["informe_fecha_recepcion"],"0000-00-00");
		$examen_condicion=$_POST["examen_condicion"];
		$examen_fecha=str_inde($_POST["examen_fecha"],"0000-00-00");
		$titulo_emision=str_inde($_POST["titulo_emision"],"0000-00-00");
		
		$notaInformePractica=$_POST["notaInformePractica"];
		$notaEvaluacionEmpresa=$_POST["notaEvaluacionEmpresa"];
		
		$notaSupervisionPractica=$_POST["notaSupervision"];
		$notaExamenTitulo=$_POST["notaExamen"];

		$year_titulo=$_POST["year_titulo"];
		$semestre_titulo=$_POST["semestre_titulo"];
		
		if(empty($notaInformePractica)){$notaInformePractica=0;}
		if(empty($notaEvaluacionEmpresa)){$notaEvaluacionEmpresa=0;}
		if(empty($notaSupervisionPractica)){$notaSupervisionPractica=0;}
		if(empty($notaExamenTitulo)){$notaExamenTitulo=0;}
		
		if($titulo_emision!=="0000-00-00")
		{
			$array_titulo_emision=explode("-",$titulo_emision);
			$year_titulo_emision=$array_titulo_emision[0];
			$mes_titulo_emision=$array_titulo_emision[1];
			$dia_titulo_emision=$array_titulo_emision[2];
			
			//if($mes_titulo_emision>=8){$semestre_titulo=2;}else{$semestre_titulo=1;}
			//$year_titulo=$year_titulo_emision;
			
		}
		else
		{
			$semestre_titulo=$_POST["semestre_titulo"];
			$year_titulo=mysqli_real_escape_string($conexion_mysqli, $_POST["year_titulo"]);
		}
		
		
		$nombre_titulo=str_inde($_POST["nombre_titulo"],"");
		$numero_inscripcion_titulo=mysqli_real_escape_string($conexion_mysqli, $_POST["numero_inscripcion_titulo"]);
		
		if($codigo_registro>0)
		{ $accion="actualizar";}
		else
		{ $accion="insertar";}
		
		switch($accion)
		{
			case"actualizar":
				$consX="UPDATE proceso_titulacion SET practica_condicion='$practica_condicion', practica_fecha_inicio='$practica_fecha_inicio', practica_lugar='$practica_lugar', informe_fecha_recepcion='$informe_fecha_recepcion', examen_condicion='$examen_condicion', examen_fecha='$examen_fecha', titulo_fecha_emision='$titulo_emision', cod_user='$id_usuario_activo', nombre_titulo='$nombre_titulo', year_titulo='$year_titulo', semestre_titulo='$semestre_titulo', numero_inscripcion_titulo='$numero_inscripcion_titulo', notaInformePractica='$notaInformePractica', notaEvaluacionEmpresa='$notaEvaluacionEmpresa', notaSupervisionPractica='$notaSupervisionPractica', notaExamen='$notaExamenTitulo' WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' LIMIT 1";
				break;
			case"insertar":
				
				$campos="id_alumno, id_carrera, yearIngresoCarrera, sede, practica_condicion, practica_fecha_inicio, practica_lugar, informe_fecha_recepcion, examen_condicion, examen_fecha, titulo_fecha_emision, fecha_generacion,  cod_user, nombre_titulo, year_titulo, semestre_titulo, numero_inscripcion_titulo, notaInformePractica, notaEvaluacionEmpresa, notaSupervisionPractica, notaExamen";
				
				$valores="'$id_alumno', '$id_carrera','$yearIngresoCarrera', '$sede', '$practica_condicion', '$practica_fecha_inicio', '$practica_lugar', '$informe_fecha_recepcion', '$examen_condicion', '$examen_fecha', '$titulo_emision', '$fecha_actual', '$id_usuario_activo', '$nombre_titulo', '$year_titulo', '$semestre_titulo', '$numero_inscripcion_titulo', '$notaInformePractica', '$notaEvaluacionEmpresa', '$notaSupervisionPractica', '$notaExamenTitulo'";
				
				
				
				$consX="INSERT INTO proceso_titulacion ($campos) VALUES ($valores)";
				break;	
		}
		
		//echo"--->$consX<br>";
		if(DEBUG){ echo"<br><br>-> $consX<br>";}
		else
		{
			$busca_old_registro=BUSCA_OLD_REGISTRO($id_alumno, $id_carrera, $yearIngresoCarrera);
			if($accion=="actualizar")
			{
				if($conexion_mysqli->query($consX))
				{
					 $error=2;
					 include("../../../funciones/VX.php");
					 $evento="PROCESO TITULACION-> actualiza_registro ID_alumno($id_alumno)";
					 REGISTRA_EVENTO($evento);
					 $descripcion="Proceso de Titulacion Actualizado";
					 REGISTRO_EVENTO_ALUMNO($id_alumno, 'notificacion',$descripcion);
					 ///actualizo condicion a T en alumno
					 $cons_UP_A="UPDATE alumno SET situacion='T' WHERE id='$id_alumno' AND id_carrera='$id_carrera' LIMIT 1";
					 $conexion_mysqli->query($cons_UP_A)or die($conexion_mysqli->error);
					 
				}
				else
				{ $error=3; echo"ERROR: ".$conexion_mysqli->error;}
			}
			elseif(($accion=="insertar")and($busca_old_registro))
			{
				if($conexion_mysqli->query($consX))
				{ 
					$error=4;
					 include("../../../funciones/VX.php");
					 $evento="PROCESO TITULACION-> crea_registro ID_alumno($id_alumno)";
					 REGISTRA_EVENTO($evento);
					 $descripcion="Proceso de Titulacion Creado";
					 REGISTRO_EVENTO_ALUMNO($id_alumno, 'notificacion',$descripcion);
					  ///actualizo condicion a T en alumno
					 $cons_UP_A="UPDATE alumno SET situacion='T' WHERE id='$id_alumno' AND id_carrera='$id_carrera' LIMIT 1";
					 $conexion_mysqli->query($cons_UP_A)or die($conexion_mysqli->error);
				}
				else
				{ $error=5; }
			}
			else
			{ $error=6;}
		}
		

	
	$url="proceso_titulacion_final.php?error=$error";
	
	if(DEBUG){ echo"location: $url"; }
	else{header("location: $url");}
}
else
{ header("location: ../../buscador_alumno_BETA/HALL/index.php");}
//////////////////////////////////
function BUSCA_OLD_REGISTRO($id_alumno, $id_carrera, $yearIngresoCarrera)
{
	require("../../../funciones/conexion_v2.php");
	$cons="SELECT COUNT(id) FROM proceso_titulacion WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera'";
	$sql=$conexion_mysqli->query($cons)or die("Error");
	if(DEBUG){ echo"--_>FUNCION $cons<br>";}
	$D=$sql->fetch_row();
	$coincidencias=$D[0];
	if($coincidencias>0)
	{ $exe=false;}
	else
	{ $exe=true;}
	$sql->free();
	$conexion_mysqli->close();
	return($exe);
}
$conexion_mysqli->close();
?>