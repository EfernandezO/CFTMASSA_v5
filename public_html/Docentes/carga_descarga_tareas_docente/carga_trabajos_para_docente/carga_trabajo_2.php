<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("carga_descarga_tareas_docente_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
//--------------FIN CLASS_okalis---------------//
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funciones_sistema.php");
	require("../../../../funciones/VX.php");
	$continuar_1=false;
	$continuar_2=false;
	$error="debug";
		
	if($_POST)
	{
		$continuar_1=true;
		$nombre=mysqli_real_escape_string($conexion_mysqli, $_POST["nombre"]);
		$descripcion=mysqli_real_escape_string($conexion_mysqli, $_POST["descripcion"]);
		$semestre=mysqli_real_escape_string($conexion_mysqli, $_POST["semestre"]);
		$year=mysqli_real_escape_string($conexion_mysqli, $_POST["year"]);
		$sede=mysqli_real_escape_string($conexion_mysqli, $_POST["sede"]);
		
	}
	
	if(isset($_FILES["archivo"]))
	{$continuar_2=true;}
	
	
	if($continuar_1 and $continuar_2)
	{
		$fecha_hora_actual=date("Y-m-d H:i:s");
		$id_usuario_actual=$_SESSION["USUARIO"]["id"];
		
		$carpeta_destino="../../../CONTENEDOR_GLOBAL/tareas_trabajos_docentes";
		$prefijo="TRABAJO_";
		$array_extenciones_permitidas=array("pdf", "zip", "rar", "doc", "docx", "xls", "xlsx");
		list($archivo_cargado, $nombre_archivo_new)=CARGAR_ARCHIVO($_FILES["archivo"],$carpeta_destino,$prefijo, $array_extenciones_permitidas);
		
		if($archivo_cargado)
		{
			
			$campos="tipo, sede, semestre, year, nombre, descripcion, archivo, fecha_generacion, cod_user";
			$valores="'trabajo', '$sede', '$semestre', '$year', '$nombre', '$descripcion', '$nombre_archivo_new', '$fecha_hora_actual', '$id_usuario_actual'";
			$grabar=true;
				
			
			if($grabar)
			{
				$CONS_IN="INSERT INTO tareas_docente ($campos) VALUES ($valores)";
				if(DEBUG){ echo"---> $CONS_IN<br>";}
				else
				{
					if($conexion_mysqli->query($CONS_IN))
					{
						$error="CT0";
						$evento="Carga Tarea Docente (trabajo) $sede -  periodo [$semestre - $year] nombre $nombre archivo: $nombre_archivo_new";
						REGISTRA_EVENTO($evento);
					}
					else
					{ $error="CT1"; /*echo $conexion_mysqli->error;*/}
				}
			}
			else
			{if(DEBUG){ echo"No se puede Grabar<br>";}}
		}
		else
		{ $error="CT2";}
		
	}
	else
	{$error="CT3";}
	
	$url="carga_trabajo_3.php?error=$error";
	
	if(DEBUG){ echo"URL: $url";}
	else{ header("location: $url");}
?>