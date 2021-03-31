<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Subir _de_nivel_A_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$continuar=false;
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
	{ $continuar=true;}
}
///***************************************//
if(($continuar)and($_POST))
{
	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/VX.php");
	if(DEBUG){ var_dump($_POST);}
	$rut_alumno=$_SESSION["SELECTOR_ALUMNO"]["rut"];
	$alumno=$_SESSION["SELECTOR_ALUMNO"]["nombre"]." ".$_SESSION["SELECTOR_ALUMNO"]["apellido"];;
	$carrera=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
	$nivel_actual=$_SESSION["SELECTOR_ALUMNO"]["nivel"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$nuevo_nivel=mysqli_real_escape_string($conexion_mysqli, $_POST["nuevo_nivel"]);
	$nivel_condicion=mysqli_real_escape_string($conexion_mysqli, $_POST["nivel_condicion"]);
	$ramos_reprobados=mysqli_real_escape_string($conexion_mysqli, $_POST["ramos_reprobados"]);
	//------------------------------------------------------------------------------------------//
	
	$cons_UP="UPDATE alumno SET nivel='$nuevo_nivel', nivel_condicion='$nivel_condicion' WHERE id='$id_alumno' LIMIT 1";
	if(DEBUG){ echo "$cons_UP<br>"; $mensaje="DEBUG";}
	else
	{
		if($conexion_mysqli->query($cons_UP))
		{
			$evento="Sube de nivel a alumno id_alumno: $id_alumno nuevo nivel_actual: $nivel_actual -> nuevo_nivel: $nuevo_nivel";
			
			if($ramos_reprobados>=3)
			{$descripcion="Sube a Nivel [$nuevo_nivel], con la Aprobacion de SOLICITUD DE GRACIA ACADEMICA, por tener [$ramos_reprobados] Ramos Reprobados";}
			elseif($ramos_reprobados>=1)
			{ $descripcion="Sube a Nivel [$nuevo_nivel], pero queda en condicion PENDIENTE, por tener [$ramos_reprobados] Ramos Reprobados";}
			else
			{ $descripcion="Sube a Nivel [$nuevo_nivel]";}
			
			
			REGISTRA_EVENTO($evento);
			REGISTRO_EVENTO_ALUMNO($id_alumno, "Notificacion",$descripcion);
			
			$mensaje=base64_encode("Datos de Alumno Actualizado ".'<img src="../../BAses/Images/ok.png" width="29" height="26" alt="ok" />');
			$_SESSION["SELECTOR_ALUMNO"]["nivel"]=$nuevo_nivel;
		}
		else
		{
			$mensaje="Error ".$conexion_mysqli->error.'<img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="X" />';
		}
	}
	$conexion_mysqli->close();
	@mysql_close($conexion);
}
else
{ $mensaje="Sin Datos";}
header("location: subir_nivel_4.php?msj=$mensaje");
?>