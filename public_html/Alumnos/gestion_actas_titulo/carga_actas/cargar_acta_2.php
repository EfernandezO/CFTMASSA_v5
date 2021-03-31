<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG",false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("gestion_actas_v1");
	$O->PERMITIR_ACCESO_USUARIO();
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
		$sede=mysqli_real_escape_string($conexion_mysqli, $_POST["sede"]);
		$semestre=mysqli_real_escape_string($conexion_mysqli, $_POST["semestre"]);
		$year=mysqli_real_escape_string($conexion_mysqli, $_POST["year"]);
		$tipo_acta=mysqli_real_escape_string($conexion_mysqli, $_POST["tipo_acta"]);
		$observacion=mysqli_real_escape_string($conexion_mysqli, $_POST["observacion"]);
		$id_carrera=mysqli_real_escape_string($conexion_mysqli, $_POST["id_carrera"]);
		$jornada=mysqli_real_escape_string($conexion_mysqli, $_POST["jornada"]);
		
		$nivel=mysqli_real_escape_string($conexion_mysqli, $_POST["nivel"]);
	}
	
	if(isset($_FILES["archivo"]))
	{$continuar_2=true;}
	
	
	if($continuar_1 and $continuar_2)
	{
		$fecha_hora_actual=date("Y-m-d H:i:s");
		$id_usuario_actual=$_SESSION["USUARIO"]["id"];
		
		$carpeta_destino="../../../CONTENEDOR_GLOBAL/ACTAS";
		$prefijo=$tipo_acta."_";
		$array_extenciones_permitidas=array("pdf");
		list($archivo_cargado, $nombre_archivo_new)=CARGAR_ARCHIVO($_FILES["archivo"],$carpeta_destino,$prefijo, $array_extenciones_permitidas);
		
		if($archivo_cargado)
		{
			$CONS_IN="INSERT INTO actas (sede, id_carrera, jornada, nivel, semestre, year, tipo, archivo, observacion, fecha_generacion, cod_user) VALUES ('$sede', '$id_carrera', '$jornada', '$nivel', '$semestre', '$year', '$tipo_acta', '$nombre_archivo_new', '$observacion', '$fecha_hora_actual', '$id_usuario_actual')";
			if(DEBUG){ echo"---> $CONS_IN<br>";}
			else
			{
				if($conexion_mysqli->query($CONS_IN))
				{
					$error="CA0";
					$evento="Carga ACTA $tipo_acta en sede: $sede id_carrera: $id_carrera jornada: $jornada periodo [$semestre - $year]";
					REGISTRA_EVENTO($evento);
				}
				else
				{ $error="CA1";}
			}
		}
		else
		{ $error="CA2";}
		
	}
	else
	{$error="CA3";}
	
	$url="cargar_acta_3.php?error=$error";
	
	if(DEBUG){ echo"URL: $url";}
	else{ header("location: $url");}
	
	 
	 
	
?>