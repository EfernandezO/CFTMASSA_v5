<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//

 if(($_POST)and($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
 { $continuar=true;}
 else
 { $continuar=false;}
 
 if(($continuar)and($_SESSION["ANULA"]["VERIFICADOR"]))
 {
	 require("../../../funciones/conexion_v2.php");
	 
 	if(!DEBUG){$_SESSION["ANULA"]["VERIFICADOR"]=false;}
	else{ var_dump($_POST); echo"<br>";}
	$id_cuota=mysqli_real_escape_string($conexion_mysqli, $_POST["id_cuota"]);
	$id_boleta=mysqli_real_escape_string($conexion_mysqli, $_POST["id_boleta"]);
	$valor=mysqli_real_escape_string($conexion_mysqli, $_POST["valor"]);
	$boleta_impresa=mysqli_real_escape_string($conexion_mysqli, $_POST["boleta_impresa"]);
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	
	
	if(is_numeric($id_cuota)){ $cuota_ok=true;}
	else{ $cuota_ok=false; if(DEBUG){ echo"id_cuota invalido($id_cuota)<br>";}}
	
	if(is_numeric($id_alumno)){$alumno_ok=true;}
	else{ $alumno_ok=false; if(DEBUG){ echo"id_alumno invalido ($id_alumno)<br>";}}
	
	if(is_numeric($id_boleta)){ $boleta_ok=true;}
	else{$boleta_ok=false; if(DEBUG){ echo"id_boleta invalido ($id_boleta)<br>";}}
	
	if($cuota_ok and $boleta_ok and $alumno_ok)
	{
		$total_descuenta_a_cuota=ELIMINA_PAGO($id_alumno, $id_boleta, $id_cuota, $sede_alumno);
		REVIERTE_CUOTA($id_alumno, $id_cuota, $sede_alumno, $total_descuenta_a_cuota);
		ANULA_BOLETA($id_alumno, $id_boleta, $sede_alumno, $boleta_impresa);
		///////////////registr evento/////////////////////
				 include("../../../funciones/VX.php");
				 $evento="ANULA TRANSACCION id_alumno: $id_alumno id_cuota: $id_cuota id_boleta: $id_boleta";
				 REGISTRA_EVENTO($evento);
				 ///////////////////////////////////////////////////
		@mysql_close($conexion);
		$conexion_mysqli->close();
		if(!DEBUG){header("location: ../pagacuo/cuota1.php?error=2");}
	}
	else
	{
		echo"Datos incorrectos no se puede continuar :(<br>";
	}
 }
 else
 {echo"sin datos";}
 
 //////////////////////////////////////////////////////////////////
 function ELIMINA_PAGO($id_alumno, $id_boleta, $id_cuota, $sede)
 {
 	if(DEBUG){ echo"<br>------------------FUNCION: ELIMINA_PAGO-------------<br>";}
 	$cons="SELECT * FROM pagos WHERE id_alumno='$id_alumno' AND id_boleta='$id_boleta' AND sede='$sede' AND id_cuota='$id_cuota'";
	if(DEBUG){ echo"$cons<br>";}
	$sql=mysql_query($cons)or die(mysql_error());
	$num_pagos_relacionados=mysql_num_rows($sql);
	$total_descuenta_a_cuota=0;
	if(DEBUG){ echo"Num Pagos Relacionados: $num_pagos_relacionados<br>";}
	while($DP=mysql_fetch_assoc($sql))
	{
		$forma_pago=$DP["forma_pago"];
		$id_cheque=$DP["id_cheque"];
		$por_concepto=$DP["por_concepto"];
		$aux_valor=$DP["valor"];
		
		switch($por_concepto)
		{
			case"interes_x_atraso":
				$descuenta_cuota=false;
				break;
			case"gastos_cobranza":
				$descuenta_cuota=false;
				break;
			default:
				$descuenta_cuota=true;	
		}
	
		if($descuenta_cuota){ $total_descuenta_a_cuota+=$aux_valor;}
		if(DEBUG){ echo"----> $forma_pago id_cheque: $id_cheque por_concepto: $por_concepto valor: $aux_valor<br>";}
		switch($forma_pago)
		{
			case"cheque":
				$cons_D_ch="DELETE FROM registro_cheques WHERE id='$id_cheque' AND sede='$sede' LIMIT 1";
				if(DEBUG){ echo"->BORRANDO CHEQUE $cons_D_ch<br>";}
				else{mysql_query($cons_D_ch)or die("cheque".mysql_error());}
				break;
		}
		$cons_D_p="DELETE FROM pagos WHERE id_alumno='$id_alumno' AND id_boleta='$id_boleta' AND sede='$sede' AND id_cuota='$id_cuota' LIMIT 1";
		if(DEBUG){echo"-->BORRO PAGO $cons_D_p<br>";}
		else{ mysql_query($cons_D_p)or die("pago".mysql_error());}
	}
	
	if(DEBUG){echo"----------------------FIN FUNCION-------------------------<br>";}
	return($total_descuenta_a_cuota);
 }
 ////////////////////////////////////////////////////////////////////
 function REVIERTE_CUOTA($id_alumno, $id_cuota, $sede, $valor)
 {
 	if(DEBUG){echo"--------------------FUNCION REVIERTE_CUOTA-----------------------<br>";}
	$cons_C="SELECT * FROM letras WHERE id='$id_cuota' AND idalumn='$id_alumno' AND sede='$sede'";
	if(DEBUG){echo"=$cons_C<br>";}
	$condicion_new="";
	$sql_C=mysql_query($cons_C)or die(mysql_error());
	$DC=mysql_fetch_assoc($sql_C);
		$valor_actual_cuota=$DC["valor"];
		$deuda_actualXcuota=$DC["deudaXletra"];
	mysql_free_result($sql_C);	
		$deudaXcuota_new=($deuda_actualXcuota+$valor);
		if($valor_actual_cuota==$deudaXcuota_new)
		{
			if(DEBUG){ echo"Nueva Condicion: Pendiente<br>";}
			$condicion_new="N";
			$campo_ultimo_pagoX="fecha_ultimo_pago= NULL";
		}
		elseif(($valor_actual_cuota>$deudaXcuota_new)and($deudaXcuota_new>0))
		{
			if(DEBUG){ echo"Nueva Condicion: Abonada<br>";}
			$condicion_new="A";
			$cons_last_p="SELECT MAX(fechapago) FROM pagos WHERE id_cuota='$id_cuota' AND id_alumno='$id_alumno' AND sede='$sede'";
			$sql_last_p=mysql_query($cons_last_p)or die("last pago ".mysql_error());
			$DLP=mysql_fetch_row($sql_last_p);
			$ultimo_pagoX=$DLP[0];
			$campo_ultimo_pagoX="fecha_ultimo_pago='$ultimo_pagoX'";
			mysql_free_result($sql_last_p);
			if(DEBUG){echo"==>$cons_last_p <br> fecha pago anterior: $ultimo_pagoX<br>";}
		}
		
		$cons_UP_C="UPDATE letras SET deudaXletra='$deudaXcuota_new', pagada='$condicion_new', $campo_ultimo_pagoX  WHERE id='$id_cuota' AND idalumn='$id_alumno' AND sede='$sede' LIMIT 1";
		if(DEBUG){echo"==> $cons_UP_C<br>";}
		else{ mysql_query($cons_UP_C)or die("CUOTA ".mysql_error());}
		
		if(DEBUG){echo"-------------------------FIN FUNCION-----------------------------<br>";}
 }
 ///////////////////////////////////////////////////////////////////
 function ANULA_BOLETA($id_alumno, $id_boleta, $sede, $boleta_impresa)
 {
 	if(DEBUG){ echo"---------------------FUNCION ANULA_BOLETA--------------------------<br>";}
 	switch($boleta_impresa)
	{
		case"si":
			$cons_boleta="UPDATE boleta SET estado='ANULADA' WHERE id='$id_boleta' AND id_alumno='$id_alumno' AND sede='$sede' LIMIT 1";
			break;
		case"no":
			$cons_boleta="DELETE FROM boleta WHERE id='$id_boleta' AND id_alumno='$id_alumno' AND sede='$sede' LIMIT 1";
			break;	
	}
	if(DEBUG){ echo">> $cons_boleta<br>";}
	else{ mysql_query($cons_boleta)or die("boleta".mysql_error());}
	if(DEBUG){ echo"---------------------FIN FUNCION--------------------------<br>";}
 }
?>