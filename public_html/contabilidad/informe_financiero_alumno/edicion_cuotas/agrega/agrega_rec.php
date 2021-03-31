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
///////////////---------------///////////////
	$acceso=false;
	$comparador=md5("AGREGA_cuota".date("Y-m-d"));
	$validador=$_POST["validador"];

	if($comparador==$validador)
	{
		$acceso=true;
	}
///////////////---------------///////////////	
if(($_POST)and($acceso))	
{
	if(DEBUG){ var_dump($_POST);}
	require("../../../../../funciones/conexion_v2.php");
	$id_alumno=$_POST["id_alumno"];
	
	$id_contrato=$_POST["id_contrato"];
	$year=mysqli_real_escape_string($conexion_mysqli, $_POST["year"]);
	$semestre=mysqli_real_escape_string($conexion_mysqli, $_POST["semestre"]);
	
	$fecha_vence=mysqli_real_escape_string($conexion_mysqli, $_POST["fecha_vence"]);
	$valor_cuota=mysqli_real_escape_string($conexion_mysqli, $_POST["valor_cuota"]);
	$deudaXcuota=mysqli_real_escape_string($conexion_mysqli, $_POST["deudaXcuota"]);
	
	$select=$_POST["select"];
	$pagada=mysqli_real_escape_string($conexion_mysqli, $_POST["pagada"]);
	$sede=mysqli_real_escape_string($conexion_mysqli, $_POST["sede"]);
	$tipo_cuota=mysqli_real_escape_string($conexion_mysqli, $_POST["tipo_cuota"]);
	
	$num_cuota=NUMERO_CUOTA($id_alumno, $id_contrato);

	$fecha_actual=date("Y-m-d");
	
	$campos="idalumn, id_contrato, numcuota, fechavenc, valor, deudaXletra, ano, semestre, fechemision, sede, tipo";
	$valores="'$id_alumno', '$id_contrato', '$num_cuota', '$fecha_vence', '$valor_cuota', '$deudaXcuota', '$year', '$semestre', '$fecha_actual', '$sede', '$tipo_cuota'";
	
	$cons_IN="INSERT INTO letras ($campos)VALUES($valores)";
	if(DEBUG){ echo"----->$cons_IN<br>";}
	else
	{
	
		if($conexion_mysqli->query($cons_IN))
		{
			$error=4;//insercion correcta
		}
		else
		{
			$error=5;//falla al insertar
			$msj=base64_encode("IN ".$conexion_mysqli->error);
		}
	}
	
	$conexion_mysqli->close();
	
	$url="../../informe_finan1.php?error=$error&id_contrato=$id_contrato&year=$year&semestre=$semestre";
	if(DEBUG){ echo"URL: $url<br>";}
	else{header("location: $url");}
}
else
{
	if(DEBUG){ echo"sin acceso<br>";}
	else{header("location: ../../index.php");}
}

function NUMERO_CUOTA($id_alumno, $id_contrato)
{
	require("../../../../../funciones/conexion_v2.php");
	$cons="SELECT MAX(numcuota) FROM letras WHERE idalumn='$id_alumno' AND id_contrato='$id_contrato'";
	$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$D=$sql->fetch_row();
	$num_cuota_existente=$D[0];
	if(empty($num_cuota_existente)){ $num_cuota_existente=0;}
	
	$num_cuota_new=($num_cuota_existente+1);
	$sql->free();
	if(DEBUG){ echo"-->$cons<br>Num OLD: $num_cuota_existente NEW: $num_cuota_new<br>";}
	$conexion_mysqli->close();
	return($num_cuota_new);
}
?>
