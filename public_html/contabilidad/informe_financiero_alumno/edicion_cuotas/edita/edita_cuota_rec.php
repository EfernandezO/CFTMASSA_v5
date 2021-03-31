<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Impresion_Y_Gestion_de_contratos_v1_editar");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$acceso=false;
$comparador=md5("EDICION_cuota".date("Y-m-d"));
$validador=$_POST["validador"];
if($validador==$comparador)
{
	$acceso=true;
}

/////////---------------------------/////////////
if(($_POST)and($acceso))
{
	$id_cuota=$_POST["id_cuota"];
	$pagada=$_POST["pagada"];
	$tipo_cuota=$_POST["tipo_cuota"];
	$semestre=$_POST["semestre"];
	$year=$_POST["year"];
	$valor_cuota=$_POST["valor_cuota"];
	$deuda_cuota=$_POST["deuda_cuota"];
	$fecha_vence=$_POST["fecha_vence"];
	
	$id_contrato=$_POST["id_contrato"];
	$year=$_POST["year"];
	$semestre=$_POST["semestre"];
	
	require("../../../../../funciones/conexion_v2.php");
	
	$campo_valor="semestre='$semestre', ano='$year', valor='$valor_cuota', deudaXletra='$deuda_cuota', fechavenc='$fecha_vence', pagada='$pagada'";
	$cons_UP="UPDATE letras SET $campo_valor WHERE id='$id_cuota' LIMIT 1";
	
	//echo"$cons_UP<br>";
	if($conexion_mysqli->query($cons_UP))
	{
		//consulta exitosa
		$error=0;
	}
	else
	{
		//falla en consulta
		$error=1;
		$msj=base64_encode("UP ".mysql_error());
	}
	$conexion_mysqli->close();
	header("location: ../../informe_finan1.php?error=$error&id_contrato=$id_contrato&year=$year&semestre=$semestre");
}
else
{
	header("location: ../../index.php");
}
?>