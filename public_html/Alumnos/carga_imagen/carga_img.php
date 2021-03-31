<?php
 //-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="matricula";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//

if(DEBUG){var_dump($_FILES);}
if($_FILES)
{
	$volver_a_index=false;
	$array_formatos_compatibles=array("jpg", "jpeg", "png", "gif");
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$peso_maximo=10000000;///peso maximo archivo cargado
	
	 $nombre_archivo = strtolower($_FILES['archivo']['name']);
	 $peso_archivo=$_FILES["archivo"]["size"];
     $tipo_archivo = $_FILES['archivo']['type'];
     $tmp_nombre=$_FILES['archivo']['tmp_name'];
	 $extencion_img=end(explode(".",$nombre_archivo));

	 $nombre_imagen_new="alumno_".$id_alumno.".$extencion_img";
	 $destino="../../CONTENEDOR_GLOBAL/img_alumnos/$nombre_imagen_new";
    if(DEBUG){ echo"Extencion: $extencion_img PESO: $peso_archivo (MAXIMO: $peso_maximo)<br>";}
	
	if((in_array($extencion_img,$array_formatos_compatibles))and($peso_archivo<=$peso_maximo))
	{
		if(move_uploaded_file($tmp_nombre, $destino))
		{
			require("../../../funciones/conexion_v2.php");
			include("../../../funciones/VX.php");
			$cons_UP="UPDATE alumno SET imagen='$nombre_imagen_new' WHERE id='$id_alumno' LIMIT 1";
			if(DEBUG){ echo" $cons_UP<br>";}
			else{ 
					$conexion_mysqli->query($cons_UP)or die($conexion_mysqli->error);
					$evento="Carga imagen de Perfil a Alumno id $id_alumno";
					REGISTRA_EVENTO($evento);
				}
			@mysql_close($conexion);
			$conexion_mysqli->close();
			$error="C0";
		}
		else{ $volver_a_index=true; $error="C1";}
	}
	else
	{$volver_a_index=true; $error="C2";}
	/////////////////////////////////////////////////////////
	
	if($volver_a_index)
	{
		if(DEBUG){ echo"PRESENCIA DE ERROR volver a Index<br>ERROR: $error<br>";}
		else{ header("location: index.php?error=$error");}
	}
	else
	{
		if(DEBUG){ echo"Ir a Mensaje Final Error:$error<br>";}
		else{ $_SESSION["SELECTOR_ALUMNO"]["imagen"]=$nombre_imagen_new; header("location: carga_final.php?error=$error");}
	}
	
}
else
{ if(DEBUG){ echo"NO FILE<br>";} else{header("location: index.php");}}
?>