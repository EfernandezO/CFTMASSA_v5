<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="ALUMNO";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", false);
$error="debug";

	$id_alumno=$_SESSION["USUARIO"]["id"];
	if(DEBUG){ var_dump($_POST);}
    require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
    
	
	$id_alumno_formulario=str_inde($_POST["id_alumno"]);
	
	if($id_alumno==$id_alumno_formulario)
	{ $continuar=true;}
	else{ $continuar=false;}
	
	
    $nombreX=str_inde($_POST["nombre"]);
	$nombreX=ucwords(strtolower($nombreX));
	
	$apellido_P=str_inde($_POST["apellido_P"]);
	$apellido_P=ucwords(strtolower($apellido_P));
	
	$apellido_M=str_inde($_POST["apellido_M"]);
	$apellido_M=ucwords(strtolower($apellido_M));
	

	
	$fonoX=str_inde($_POST["fono"]);
	$direccionX=mysqli_real_escape_string($conexion_mysqli, $_POST["direccion"]);
	$direccionX=ucwords(strtolower($direccionX));
	
	$ciudadX=str_inde($_POST["ciudad"]);
	$ciudadX=ucwords(strtolower($ciudadX));
	
	$emailX=str_inde($_POST["email"]);
	
	
	if((empty($nombreX))or(empty($apellido_P))or(empty($apellido_M))or(empty($direccionX))or( empty($ciudadX))or(empty($fonoX))){ $campos_vacios=true;}
	else{ $campos_vacios=false;}
	
	if(DEBUG){ echo"<br>continuar: $continuar<br>campos_vacios: $campos_vacios<br>";}
	
	if(($continuar)and(!$campos_vacios))
	{
		if(DEBUG){ "inicion modificacion<br>";}
		 //--------------------------------------------------//
		 include("../../../funciones/VX.php");
		 $evento="Modifica Mis Datos";
		 REGISTRA_EVENTO($evento);
		 //cambio estado_conexion USER-----------
		 CAMBIA_ESTADO_CONEXION_ALUMNO($id_alumno, "ON");
		//-----------------------------------------------//
	
	
		$cons_UP="UPDATE  alumno set  nombre='$nombreX', apellido_P='$apellido_P', apellido_M='$apellido_M', fono='$fonoX', direccion='$direccionX', ciudad='$ciudadX', email='$emailX' WHERE id=$id_alumno LIMIT 1";
		if(DEBUG){echo "$cons_UP<br>";}
		else
		{
			if($conexion_mysqli->query($cons_UP))
			{ $error="MD1";}
			else
			{ $error="MD2";}
		}
	}
	else
	{ $error="MD3";}
	
	
	$url="mis_datos.php?error=$error";
	if(DEBUG){ echo"URL: $url";}
	else{ header("location: $url");}
?>