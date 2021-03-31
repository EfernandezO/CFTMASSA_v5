<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("registra_egresos");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_POST)
{
	$error="debug";
	$url="registra_egreso_3.php?";
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	require("../../../funciones/VX.php");
	if(DEBUG){ var_dump($_POST);}
	$fecha=mysqli_real_escape_string($conexion_mysqli, $_POST["fecha"]);
	$tipo_documento=mysqli_real_escape_string($conexion_mysqli, $_POST["tipo_documento"]);
	
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$fecha_hora_actual=date("Y-m-d H:i:s");
	$mes_actual=date("m");
	if($mes_actual>=8){ $semeste_actual=2;}
	else{ $semeste_actual=1;}
	$year_actual=date("Y");
	$ip=$_SERVER['REMOTE_ADDR'];
	$sede_actual_usuario=$_SESSION["USUARIO"]["sede"];
	
	if(DEBUG){ echo"Tipo Documento: $tipo_documento<br>";}
	
	switch($tipo_documento)
	{
		case"boleta":
				$grabar_pago=true;
				$proveedor=mysqli_real_escape_string($conexion_mysqli, $_POST["proveedor"]);
				$concepto=mysqli_real_escape_string($conexion_mysqli, $_POST["concepto"]);
				$numero_boleta=mysqli_real_escape_string($conexion_mysqli, $_POST["numero_boleta"]);
				$glosa=mysqli_real_escape_string($conexion_mysqli, $_POST["glosa"]);
				$valor=mysqli_real_escape_string($conexion_mysqli, $_POST["valor"]);
				$forma_pago=mysqli_real_escape_string($conexion_mysqli, $_POST["forma_pago"]);
				$dia=mysqli_real_escape_string($conexion_mysqli, $_POST["dia"]);
				$mes=mysqli_real_escape_string($conexion_mysqli, $_POST["mes"]);
				$year=mysqli_real_escape_string($conexion_mysqli, $_POST["year"]);
				$cheque_numero=mysqli_real_escape_string($conexion_mysqli, $_POST["cheque_numero"]);
				$cheque_banco=mysqli_real_escape_string($conexion_mysqli, $_POST["cheque_banco"]);
				$responsable_gasto=mysqli_real_escape_string($conexion_mysqli, $_POST["responsable_gasto"]);
				
				$id_boleta_recibida=GRABA_BOLETA_RECIBIDA($sede_actual_usuario, "normal", $numero_boleta, $valor, $glosa, $proveedor);
				
				$campos="tipo_receptor, id_proveedor, id_boleta_R, fechapago, valor, tipodoc, glosa, sede, movimiento, rut_responsable_gasto, forma_pago, por_concepto, semestre, year, cod_user, ip, aux_num_documento, fecha_generacion";
				$valores="'proveedor', '$proveedor', '$id_boleta_recibida', '$fecha', '$valor', '$tipo_documento', '$glosa', '$sede_actual_usuario', 'E', '$responsable_gasto', '$forma_pago', '$concepto', '$semeste_actual', '$year_actual', '$id_usuario_actual', '$ip', '$numero_boleta', '$fecha_hora_actual'";
				
				switch($forma_pago)
				{
					case"cheque":
						$array_cheque["fecha_vence"]="$year-$mes-$dia";
						$array_cheque["emisor"]="massachusetts";
						$array_cheque["numero"]=$cheque_numero;
						$array_cheque["banco"]=$cheque_banco;
						$array_cheque["valor"]=$valor;
						$array_cheque["sede"]=$sede_actual_usuario;
						$array_cheque["glosa"]="pago de boleta $numero_boleta";
						$array_cheque["movimiento"]="E";
						$id_cheque=REGISTRA_CHEQUE($array_cheque, false);
						
						$campos.=", fechaV_cheque, id_cheque";
						$valores.=", '$year-$mes-$dia', '$id_cheque'";
						break;
					case"deposito":
						$id_cta_cte=mysqli_real_escape_string($conexion_mysqli, $_POST["id_cta_cte"]);
						$campos.=", id_cta_cte";
						$valores.=", '$id_cta_cte'";
						break;
					case"transferencia":
						$T_id_cta_cte=mysqli_real_escape_string($conexion_mysqli, $_POST["T_id_cta_cte"]);
						$campos.=", id_cta_cte";
						$valores.=", '$T_id_cta_cte'";
						break;	
				}
				$cons_IN="INSERT INTO pagos ($campos) VALUES ($valores)";
			break;
		case"factura":	
				$grabar_pago=true;
				$proveedor=mysqli_real_escape_string($conexion_mysqli, $_POST["proveedor"]);
				$factura=mysqli_real_escape_string($conexion_mysqli, $_POST["factura"]);
				$concepto=mysqli_real_escape_string($conexion_mysqli, $_POST["concepto"]);
				$glosa=mysqli_real_escape_string($conexion_mysqli, $_POST["glosa"]);
				$valor=mysqli_real_escape_string($conexion_mysqli, $_POST["valor"]);
				$forma_pago=mysqli_real_escape_string($conexion_mysqli, $_POST["forma_pago"]);
				$dia=mysqli_real_escape_string($conexion_mysqli, $_POST["dia"]);
				$mes=mysqli_real_escape_string($conexion_mysqli, $_POST["mes"]);
				$year=mysqli_real_escape_string($conexion_mysqli, $_POST["year"]);
				$cheque_numero=mysqli_real_escape_string($conexion_mysqli, $_POST["cheque_numero"]);
				$cheque_banco=mysqli_real_escape_string($conexion_mysqli, $_POST["cheque_banco"]);
				$responsable_gasto=mysqli_real_escape_string($conexion_mysqli, $_POST["responsable_gasto"]);
				if(DEBUG){ echo"Cancela Factura<br>";}
				
				//--------------------------------------------------------------------//
				//datos factura
				$cons_F="SELECT valor, saldo, abono FROM facturas WHERE id='$factura' LIMIT 1";
				$sqli_F=$conexion_mysqli->query($cons_F)or die($conexion_mysqli->error);
					$F=$sqli_F->fetch_assoc();
					$F_saldo=$F["saldo"];
					$F_abono=$F["abono"];
					$F_valor=$F["valor"];
				$sqli_F->free();	
				//-----------------------------------------------------------------------//
				
				$F_saldo_new=($F_saldo-$valor);
				$F_abono_new=($F_abono+$valor);
				
				//---------------------------------------------------------//
				$cons_UPF="UPDATE facturas SET saldo='$F_saldo_new', abono='$F_abono_new' ";
				if(($F_abono_new==$F_valor)and($F_saldo_new==0)){ if(DEBUG){ echo"Actualizar condicion de Factura<br>";} $cons_UPF.=", condicion='cancelada'";}
				else{if(DEBUG){ echo"No actualizar Condicion de Factura";}}
				$cons_UPF.=" WHERE id='$factura' LIMIT 1";
				
				if(DEBUG){ echo"---->$cons_UPF<br>";}
				else{ $conexion_mysqli->query($cons_UPF)or die("Factura ".$conexion_mysqli->error);}

				//-----------------------------------------------------------------------///
				$campos="tipo_receptor, id_proveedor, id_factura, fechapago, valor, tipodoc, glosa, sede, movimiento, rut_responsable_gasto, forma_pago, por_concepto, semestre, year, cod_user, ip, fecha_generacion";
				$valores="'proveedor', '$proveedor', '$factura', '$fecha', '$valor', '$tipo_documento', '$glosa', '$sede_actual_usuario', 'E', '$responsable_gasto', '$forma_pago', '$concepto', '$semeste_actual', '$year_actual', '$id_usuario_actual', '$ip', '$fecha_hora_actual'";
				
				switch($forma_pago)
				{
					case"cheque":
						$array_cheque["fecha_vence"]="$year-$mes-$dia";
						$array_cheque["emisor"]="massachusetts";
						$array_cheque["numero"]=$cheque_numero;
						$array_cheque["banco"]=$cheque_banco;
						$array_cheque["valor"]=$valor;
						$array_cheque["sede"]=$sede_actual_usuario;
						$array_cheque["glosa"]="pago de factura id_factura: $factura";
						$array_cheque["movimiento"]="E";
						$id_cheque=REGISTRA_CHEQUE($array_cheque, false);
						
						$campos.=", fechaV_cheque, id_cheque";
						$valores.=", '$year-$mes-$dia', '$id_cheque'";
						break;
					case"deposito":
						$id_cta_cte=mysqli_real_escape_string($conexion_mysqli, $_POST["id_cta_cte"]);
						$campos.=", id_cta_cte";
						$valores.=", '$id_cta_cte'";
						break;
					case"transferencia":
						$T_id_cta_cte=mysqli_real_escape_string($conexion_mysqli, $_POST["T_id_cta_cte"]);
						$campos.=", id_cta_cte";
						$valores.=", '$T_id_cta_cte'";
						break;		
				}
				$cons_IN="INSERT INTO pagos ($campos) VALUES ($valores)";
			break;
		case"boleta_honorario":
				$grabar_pago=true;
				$proveedor=mysqli_real_escape_string($conexion_mysqli, $_POST["proveedor"]);
				$concepto=mysqli_real_escape_string($conexion_mysqli, $_POST["concepto"]);
				$numero_boleta=mysqli_real_escape_string($conexion_mysqli, $_POST["numero_boleta"]);
				$glosa=mysqli_real_escape_string($conexion_mysqli, $_POST["glosa"]);
				$valor=mysqli_real_escape_string($conexion_mysqli, $_POST["valor"]);
				$forma_pago=mysqli_real_escape_string($conexion_mysqli, $_POST["forma_pago"]);
				$responsable_gasto=mysqli_real_escape_string($conexion_mysqli, $_POST["responsable_gasto"]);
				if(DEBUG){ echo"Grabar Boleta Honorario Recibida<br>";}
				
				$id_boleta_recibida=GRABA_BOLETA_RECIBIDA($sede_actual_usuario, "honorario", $numero_boleta, $valor, $glosa, $proveedor);
				
				$campos="tipo_receptor, id_proveedor, id_boleta_R, fechapago, valor, tipodoc, glosa, sede, movimiento, rut_responsable_gasto, forma_pago, por_concepto, semestre, year, cod_user, ip, aux_num_documento, fecha_generacion";
				$valores="'proveedor', '$proveedor', '$id_boleta_recibida', '$fecha', '$valor', '$tipo_documento', '$glosa', '$sede_actual_usuario', 'E', '$responsable_gasto', '$forma_pago', '$concepto', '$semeste_actual', '$year_actual', '$id_usuario_actual', '$ip', '$numero_boleta', '$fecha_hora_actual'";
				
				switch($forma_pago)
				{
					case"cheque":
						$array_cheque["fecha_vence"]="$year-$mes-$dia";
						$array_cheque["emisor"]="massachusetts";
						$array_cheque["numero"]=$cheque_numero;
						$array_cheque["banco"]=$cheque_banco;
						$array_cheque["valor"]=$valor;
						$array_cheque["sede"]=$sede_actual_usuario;
						$array_cheque["glosa"]="pago de factura id_factura: $factura";
						$array_cheque["movimiento"]="E";
						$id_cheque=REGISTRA_CHEQUE($array_cheque, false);
						
						$campos.=", fechaV_cheque, id_cheque";
						$valores.=", '$year-$mes-$dia', '$id_cheque'";
						break;
					case"deposito":
						$id_cta_cte=mysqli_real_escape_string($conexion_mysqli, $_POST["id_cta_cte"]);
						$campos.=", id_cta_cte";
						$valores.=", '$id_cta_cte'";
						break;
					case"transferencia":
						$T_id_cta_cte=mysqli_real_escape_string($conexion_mysqli, $_POST["T_id_cta_cte"]);
						$campos.=", id_cta_cte";
						$valores.=", '$T_id_cta_cte'";
						break;		
				}
				$cons_IN="INSERT INTO pagos ($campos) VALUES ($valores)";
			break;
			
		case"comprobante_egreso":
				$grabar_pago=true;
				$tipo_proveedor=mysqli_real_escape_string($conexion_mysqli, $_POST["tipo_proveedor"]);
				$concepto=mysqli_real_escape_string($conexion_mysqli, $_POST["concepto"]);
				
				if(isset($_POST["numero_comprobante_E"])){$numero_comprobante_E=mysqli_real_escape_string($conexion_mysqli, $_POST["numero_comprobante_E"]);}
				$numero_comprobante_E=0;
				$glosa=mysqli_real_escape_string($conexion_mysqli, $_POST["glosa"]);
				$valor=mysqli_real_escape_string($conexion_mysqli, $_POST["valor"]);
				$forma_pago=mysqli_real_escape_string($conexion_mysqli, $_POST["forma_pago"]);
				$responsable_gasto=mysqli_real_escape_string($conexion_mysqli, $_POST["responsable_gasto"]);
				
				
				$campos="tipo_receptor, ";
				
				if(DEBUG){ echo "TIPO PROVEEDOR: $tipo_proveedor<br>";}
				
				switch($tipo_proveedor)
				{
					case"proveedor":
						$id_proveedor=mysqli_real_escape_string($conexion_mysqli, $_POST["proveedor"]);
						$campos.="id_proveedor, ";
						$valores="'proveedor', '$id_proveedor', ";
						$id_comprobante_egreso=GRABA_COMPROBANTE_EGRESO($fecha, $sede_actual_usuario, $numero_comprobante_E, $valor, $glosa, $id_proveedor, 'proveedor', $forma_pago);
						break;
					case"personal":
						$rut_personal=mysqli_real_escape_string($conexion_mysqli, $_POST["rut_personal"]);
						$id_personal=ID_PERSONAL($rut_personal);
						$campos.="id_personal, ";
						$valores="'personal', '$id_personal', ";
						$id_comprobante_egreso=GRABA_COMPROBANTE_EGRESO($fecha, $sede_actual_usuario, $numero_comprobante_E, $valor, $glosa, $id_personal, 'personal', $forma_pago);
						break;
				}
				
				$url.="id_comprobante_egreso=$id_comprobante_egreso";
					
				 $campos.="id_comprobante_egreso, fechapago, valor, tipodoc, glosa, sede, movimiento, rut_responsable_gasto, forma_pago, por_concepto, semestre, year, cod_user, ip, fecha_generacion";
				 
				$valores.="'$id_comprobante_egreso', '$fecha', '$valor', '$tipo_documento', '$glosa', '$sede_actual_usuario', 'E', '$responsable_gasto' ,'$forma_pago', '$concepto', '$semeste_actual', '$year_actual', '$id_usuario_actual', '$ip', '$fecha_hora_actual'";
				
				switch($forma_pago){
					case"cheque":
						$array_cheque["fecha_vence"]="$year-$mes-$dia";
						$array_cheque["emisor"]="massachusetts";
						$array_cheque["numero"]=$cheque_numero;
						$array_cheque["banco"]=$cheque_banco;
						$array_cheque["valor"]=$valor;
						$array_cheque["sede"]=$sede_actual_usuario;
						$array_cheque["glosa"]="pago de factura id_factura: $factura";
						$array_cheque["movimiento"]="E";
						$id_cheque=REGISTRA_CHEQUE($array_cheque, false);
						
						$campos.=", fechaV_cheque, id_cheque";
						$valores.=", '$year-$mes-$dia', '$id_cheque'";
						break;
					case"deposito":
						$id_cta_cte=mysqli_real_escape_string($conexion_mysqli, $_POST["id_cta_cte"]);
						$campos.=", id_cta_cte";
						$valores.=", '$id_cta_cte'";
						break;
					case"transferencia":
						$T_id_cta_cte=mysqli_real_escape_string($conexion_mysqli, $_POST["T_id_cta_cte"]);
						$campos.=", id_cta_cte";
						$valores.=", '$T_id_cta_cte'";
						break;		
				}
				$cons_IN="INSERT INTO pagos ($campos) VALUES ($valores)";
			break;	
		default:
			$grabar_pago=false;		
	}
	
	if($grabar_pago)
	{
		if(DEBUG){ echo"---->$cons_IN<br>";}
		else
		{
			$conexion_mysqli->query($cons_IN)or die("Registrando Pago: ".$conexion_mysqli->error);
			$evento="Registra Egreso con $tipo_documento dia: $fecha Sede: $sede_actual_usuario";
			REGISTRA_EVENTO($evento);
			$error="RE0";
		}
	}
	else
	{if(DEBUG){ echo"No se puede Grabar el pago<br>";} $error="RE1";}
	
	$url.="&error=$error";
	if(DEBUG){ echo"URL: $url";}
	else{ header("location: $url");}
}
else{ echo"Sin Datos :(<br>";}
?>