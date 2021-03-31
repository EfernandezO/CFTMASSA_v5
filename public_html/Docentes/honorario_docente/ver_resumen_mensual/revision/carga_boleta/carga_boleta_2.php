<?php
//--------------CLASS_okalis------------------//
	require("../../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../../funciones/";
	$O->clave_del_archivo=md5("pago_honorario_docente_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(DEBUG){ var_dump($_POST); echo"<br><br>";}
if(DEBUG){ var_dump($_FILES); echo"<br><br>";}
$cargar_archivo=false;

if($_POST)
{
	$error="CBH0";
	$fecha_actual=date("Y-m-d");
	$fecha_hora_actual=date("Y-m-d H:i:s");
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	
	require("../../../../../../funciones/conexion_v2.php");
	require("../../../../../../funciones/funciones_sistema.php");
	$id_honorario=mysqli_real_escape_string($conexion_mysqli, $_POST["H_id"]);
	$PH_id=mysqli_real_escape_string($conexion_mysqli,$_POST["PH_id"]);
	
	$cons="SELECT * FROM honorario_docente WHERE id_honorario='$id_honorario' LIMIT 1";
	$sqli=$conexion_mysqli->query($cons);
	$D=$sqli->fetch_assoc();
		$H_estado=$D["estado"];
		$H_total=number_format($D["total"],0,".","");
		$H_sede=$D["sede"];
		$H_id_funcionario=$D["id_funcionario"];
		$H_mes=$D["mes_generacion"];
		$H_year=$D["year"];
		$H_year_generacion=$D["year_generacion"];
	$sqli->free();	
	//--------------------------------------------------//
	if($H_estado=="pendiente")
	{ $continuar=false;}
	else{ $continuar=true;}
	//----------------------------------------------------//
	
	$nombre_archivo_new="";
	$archivo_cargado=false;
	if($continuar)
	{
		if(isset($_FILES["archivo"]))
		{
			//-------------------------------------------//
			///carga archivo
			//-------------------------------------------------------//
			$ruta="../../../../../CONTENEDOR_GLOBAL/boleta_honorario_docente";//ruta guarda archivos
			//-------------------------------------------------------//
			$prefijo="BHD_".$H_id_funcionario."_".$id_honorario;
			$array_archivos_permitidos=array("pdf");
			list($archivo_cargado, $nombre_archivo_new)=CARGAR_ARCHIVO($_FILES["archivo"], $ruta, $prefijo, $array_archivos_permitidos);
			//-------------------------------------------//
		}
	}	
	//--------------------------------------------------------------------------//
		
	
	if($archivo_cargado)
	{
		//-----------------------------------------------------------//
		$cons_UP_pago="UPDATE honorario_docente_pagos SET archivo='$nombre_archivo_new' WHERE id='$PH_id' AND id_honorario='$id_honorario'";
		if(DEBUG){ echo"--->$cons_UP_pago<br>";}
		else
		{
			 if($conexion_mysqli->query($cons_UP_pago))
			 { 
			 	$error="CBH0";
				include("../../../../../../funciones/VX.php");
				$evento="Carga Boleta Honorario desde revision id honorario: $id_honorario";
				REGISTRA_EVENTO($evento);
			 }
			 else
			 { $error="CBH1"; echo"ERROR:".$conexion_mysqli->error;}
		}
		///-----------------------------------------------------------///
	}
	else
	{
		if(DEBUG){ echo"Archivo No Cargado..<br>";}
		$error="CBH2";
	}
	
	$url="carga_boleta_3.php?error=$error";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
	$conexion_mysqli->close();
	@mysql_close($conexion);
}
?>
