<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="ALUMNO";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", false);
$error="debug";
    require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
	
$id_alumno=$_SESSION["USUARIO"]["id"];	
$id_alumno_formulario=str_inde($_POST["id_alumno"]);
	
	if($id_alumno==$id_alumno_formulario)
	{ $continuar=true;}
	else{ $continuar=false;}

	if(DEBUG){ var_dump($_POST);}
	
	//--------------------------------------------------------//
	if($continuar)
	{
		$clave_actual=mysqli_real_escape_string($conexion_mysqli, str_inde($_POST["clave_actual"],"vacia1"));
		$nueva_clave=mysqli_real_escape_string($conexion_mysqli, str_inde($_POST["nueva_clave"],"vacia2"));
		$nueva_clave_2=mysqli_real_escape_string($conexion_mysqli, str_inde($_POST["nueva_clave_2"],"vacia3"));
		
		//verifico si clave nueva es igual a clave nueva 
		if($nueva_clave==$nueva_clave_2){ $nueva_clave_OK_1=true; if(DEBUG){ echo"Nuevas Claves OK coinciden<br>";}}
		else{ $nueva_clave_OK_1=false; if(DEBUG){ echo"Nuevas Claves Error NO coinciden<br>";} $error="MC4";}
		
		///verifico clave actual sea igual a la ingresada
		
		$cons_C="SELECT clave FROM alumno WHERE id='$id_alumno' LIMIT 1";
		$sqli_C=$conexion_mysqli->query($cons_C)or die($conexion_mysqli->error);
		$D=$sqli_C->fetch_assoc();
		$clave_BBDD=$D["clave"];
		$sqli_C->free();
		
		if($clave_actual==$clave_BBDD){ $clave_actual_OK_1=true; if(DEBUG){ echo" Clave Actual OK <br>";}}
		else{ $clave_actual_OK_1=false; if(DEBUG){ echo" Clave Actual Error <br>";} $error="MC5";}
		
		if(($nueva_clave_OK_1)and($clave_actual_OK_1))
		{
			$cons_UP="UPDATE alumno SET clave='$nueva_clave' WHERE id='$id_alumno' LIMIT 1"; 
			if(DEBUG){ echo"-->$cons_UP<br>";}
			else
			{
				if($conexion_mysqli->query($cons_UP))
				{ 
					$error="MC2";
					 //--------------------------------------------------//
					 include("../../../funciones/VX.php");
					 $evento="Modifica Mi Clave";
					 REGISTRA_EVENTO($evento);
					 //cambio estado_conexion USER-----------
					 CAMBIA_ESTADO_CONEXION_ALUMNO($id_alumno, "ON");
					//-----------------------------------------------//
				}
				else
				{ $error="MC3";}
			}
		}
		
	}
	else
	{ $error="MC1";}
	$conexion_mysqli->close();
	$url="mis_datos.php?error=$error";
	
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
	
?>