<?php
//-----------------------------------------//
	require("../../seguridad.php");
	require("../../okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
if(DEBUG){ var_dump($_POST);}
$error="OAE1";
if($_POST)
{
	
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/VX.php");
	
	$id_archivo=mysqli_real_escape_string($conexion_mysqli, $_POST["id_archivo"]);
	$nombre_modulo=mysqli_real_escape_string($conexion_mysqli, $_POST["nombre_modulo"]);
	$categoria=mysqli_real_escape_string($conexion_mysqli, $_POST["categoria"]);
	
	if(is_numeric($id_archivo))
	{
		$cons_UP="UPDATE okalis_archivos SET nombre_modulo='$nombre_modulo', categoria='$categoria' WHERE id_archivo='$id_archivo' LIMIT 1";
		if(DEBUG){ echo"---> $cons_UP<br>";}
		else
		{ 
			if($conexion_mysqli->query($cons_UP))
			{
				$error="OAE0";
				$evento="Modifica Datos de Modulo Okalis";
				REGISTRA_EVENTO($evento);
			}

		}
	}
	
}

if(DEBUG){ echo"FIN<br>";}
else{header("location: edicion_modulo_3.php?error=$error");}
?>