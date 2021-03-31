<?php
//--------------CLASS_okalis------------------//
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//
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
		$id_trabajo=mysqli_real_escape_string($conexion_mysqli, $_POST["id_trabajo"]);
		
	}
	
	if(isset($_FILES["archivo"]))
	{$continuar_2=true;}
	
	
	if($continuar_1 and $continuar_2)
	{
		$fecha_hora_actual=date("Y-m-d H:i:s");
		$id_usuario_actual=$_SESSION["USUARIO"]["id"];
		
		$carpeta_destino="../../../CONTENEDOR_GLOBAL/tareas_trabajos_docentes";
		$prefijo="TAREA_";
		$array_extenciones_permitidas=array("pdf", "zip", "rar", "doc", "docx", "xls", "xlsx");
		list($archivo_cargado, $nombre_archivo_new)=CARGAR_ARCHIVO($_FILES["archivo"],$carpeta_destino,$prefijo, $array_extenciones_permitidas);
		
		if($archivo_cargado)
		{
			
			$campos="tipo, id_trabajo, id_docente, nombre, descripcion, archivo, fecha_generacion, cod_user";
			$valores="'tarea', '$id_trabajo', '$id_usuario_actual', '$nombre', '$descripcion', '$nombre_archivo_new', '$fecha_hora_actual', '$id_usuario_actual'";
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
						$evento="Carga Tarea Docente (tarea) id_trabajo: $id_trabajo periodo [$semestre - $year] nombre $nombre archivo: $nombre_archivo_new";
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