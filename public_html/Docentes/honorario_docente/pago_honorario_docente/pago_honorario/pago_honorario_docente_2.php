<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("pago_honorario_docente_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(DEBUG){ var_dump($_POST); echo"<br><br>";}
if(DEBUG){ var_dump($_FILES); echo"<br><br>";}
$cargar_archivo=false;

if($_POST)
{
	$error="HD0";
	$fecha_actual=date("Y-m-d");
	$hora_actual=date("H:i:s");
	$fecha_hora_actual=date("Y-m-d H:i:s");
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	
	require("../../../../../funciones/conexion_v2.php");
	require("../../../../../funciones/funciones_sistema.php");
	$id_honorario=mysqli_real_escape_string($conexion_mysqli, $_POST["id_honorario"]);
	$forma_pago=mysqli_real_escape_string($conexion_mysqli,$_POST["forma_pago"]);
	$fecha_pago=mysqli_real_escape_string($conexion_mysqli,$_POST["fecha_pago"]);
	$pagoActual=mysqli_real_escape_string($conexion_mysqli,$_POST["total"]);
	$fecha_hora_pago=$fecha_pago." ".$hora_actual;
	
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
	//busco pagos previo al honorario
	if(DEBUG){echo"Busco Pagos previos a Cuota Honorario:<br>";}
	$consPP="SELECT SUM(valor) FROM honorario_docente_pagos WHERE id_honorario='$id_honorario'";
	if(DEBUG){echo"-->$consPP<br>";}
	$sqliPP=$conexion_mysqli->query($consPP)or die($conexion_mysqli->error);
	$PP=$sqliPP->fetch_row();
	$pagosPrevios=$PP[0];
	if(empty($pagosPrevios)){$pagosPrevios=0;}
	$sqliPP->free();
	if(DEBUG){echo"Pagos previos realizados sumado: $pagosPrevios<br>";}
	
	//deuda actual x cuota honorario
	$deudaActual=($H_total-$pagosPrevios);
	if(DEBUG){echo"Deuda Actual cuota: $deudaActual<br>";}
	
	$continuar2=false;
	if(($deudaActual-$pagoActual)>0){
		$nuevo_estado="abonado";
		$continuar2=true;
	}elseif(($deudaActual-$pagoActual)==0){
		$nuevo_estado="cancelado";
		$continuar2=true;
	}
	else{
		if(DEBUG){ echo"pago excede DeudaActual, No se puede continuar<br>";}
		}
	
	$continuar1=false;
	if($deudaActual>0){$continuar1=true;}
	//--------------------------------------------------//
	if(($H_estado=="pendiente")or($H_estado=="abonado"))
	{ $continuar=true;}
	else{ $continuar=false;}
	//----------------------------------------------------//
	if($continuar and $continuar1 and $continuar2)
	{
		if(isset($_FILES["archivo"]))
		{
			//-------------------------------------------//
			///carga archivo
			//-------------------------------------------------------//
			$ruta="../../../../CONTENEDOR_GLOBAL/boleta_honorario_docente";//ruta guarda archivos
			//-------------------------------------------------------//
			$prefijo="BHD_".$H_id_funcionario."_".$id_honorario;
			$array_archivos_permitidos=array("pdf");
			list($archivo_cargado, $nombre_archivo_new)=CARGAR_ARCHIVO($_FILES["archivo"], $ruta, $prefijo, $array_archivos_permitidos);
			//-------------------------------------------//
		}
		else
		{
			if(DEBUG){ echo"No cargar archivo";}
			$nombre_archivo_new="";
			$archivo_cargado=false;
		}
	}
	else
	{
		if(DEBUG){ echo"Archivo No Enviado<br>";}
	}
			
	//--------------------------------------------------------------------------//
		
	if($continuar and $continuar1 and $continuar2)	
	{
		switch($forma_pago)
		{
			case"cheque":
				$numero_cheque=mysqli_real_escape_string($conexion_mysqli,$_POST["numero_cheque"]);
				$banco_cheque=mysqli_real_escape_string($conexion_mysqli,$_POST["cheque_banco"]);
				
				$campos="emisor, id_alumno, id_empresa, numero, fecha_vencimiento, banco, valor, condicion, sede, fecha, glosa, movimiento, fecha_condicion, cod_user";
				$valores="'massachusetts', '0', '0', '$numero_cheque', '$fecha_pago', '$banco_cheque', '$H_total', 'OK', '$H_sede', '$fecha_actual', 'Pago Honorario Docente id_funcionario: $H_id_funcionario Perido[mes: $H_mes - year: $H_year_generacion]', 'E', '0000-00-00', '$id_usuario_actual'";
				$cons_IN_cheque="INSERT INTO registro_cheques ($campos) VALUES ($valores)";
				if(DEBUG){ echo"----->$cons_IN_cheque<br>"; $id_cheque_generado="CH0";}
				else{ $conexion_mysqli->query($cons_IN_cheque)or die($conexion_mysqli->error); $id_cheque_generado=$conexion_mysqli->insert_id;}
				$id_transferencia_generado="0";
				break;
			case"efectivo":
				$id_cheque_generado="0";
				$id_transferencia_generado="0";
				break;
			case"transferencia":
				$numero_cuenta=mysqli_real_escape_string($conexion_mysqli,$_POST["numero_cuenta"]);
				$banco_cuenta=mysqli_real_escape_string($conexion_mysqli,$_POST["banco_cuenta"]);
				
				$campos="emisor, id_alumno, id_empresa, numero, banco, valor, sede, fecha, glosa, movimiento, cod_user";
				$valores="'massachusetts', '0', '0', '$numero_cuenta', '$banco_cuenta', '$H_total', '$H_sede', '$fecha_actual', 'Pago Honorario Docente id_funcionario: $H_id_funcionario Periodo[mes: $H_mes - year: $H_year_generacion]', 'E', '$id_usuario_actual'";
				$cons_IN_transferencia="INSERT INTO registroTransferencias ($campos) VALUES ($valores)";
				if(DEBUG){ echo"----->$cons_IN_transferencia<br>"; $id_transferencia_generado="T0";}
				else{ $conexion_mysqli->query($cons_IN_transferencia)or die($conexion_mysqli->error); $id_transferencia_generado=$conexion_mysqli->insert_id;}
				$id_cheque_generado="0";
				break;
		}
		//-----------------------------------------------------------//
		include("../../../../../funciones/VX.php");
		$evento="Paga Honorario a Docente id honorario: $id_honorario";
		REGISTRA_EVENTO($evento);
		
		$cons_UP="UPDATE honorario_docente SET estado='$nuevo_estado', fecha_estado='$fecha_pago' WHERE id_honorario='$id_honorario' LIMIT 1";
		
		$cons_IN_pago="INSERT INTO honorario_docente_pagos (id_honorario, id_funcionario, sede, forma_pago, fecha_pago, fecha_generacion, id_cheque, idTransferencia, valor, cod_user, archivo) VALUES ('$id_honorario', '$H_id_funcionario', '$H_sede', '$forma_pago', '$fecha_hora_pago', '$fecha_hora_actual', '$id_cheque_generado', '$id_transferencia_generado', '$pagoActual', '$id_usuario_actual', '$nombre_archivo_new')";
		if(DEBUG){ echo"--->$cons_UP<br>--->$cons_IN_pago<br>";}
		else{
				 if(($conexion_mysqli->query($cons_UP))and($conexion_mysqli->query($cons_IN_pago)))
				 { $error="HD0"; $id_honorario_docente_pago=$conexion_mysqli->insert_id;}
				 else
				 { $error="HD1"; $id_honorario_docente_pago=0;}
			 }
		///-----------------------------------------------------------///
	}
	else
	{
		$error="HD3";
	}
	
	
	$url="pago_honorario_docente_3.php?error=$error&id_funcionario=$H_id_funcionario&id_honorario=$id_honorario&id_honorario_docente_pago=$id_honorario_docente_pago";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
	
	$conexion_mysqli->close();
}
?>
