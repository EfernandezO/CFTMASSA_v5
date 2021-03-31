<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Impresion_Y_Gestion_de_contratos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

$pago_registrado_OK=false;

if(DEBUG){var_dump($_POST);}
if(DEBUG){var_dump($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]);}
if(DEBUG){var_dump($_SESSION["DEVOLUCION"]["verificador"]);}

if(($_POST)and($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])and($_SESSION["DEVOLUCION"]["verificador"]))
{
	if(!DEBUG){$_SESSION["DEVOLUCION"]["verificador"]=false;}
		require("../../../../funciones/funcion.php");
		require("../../../../funciones/funciones_sistema.php");
		require("../../../../funciones/conexion_v2.php");
		include("../../../../funciones/VX.php");
		
		$id_pago=0;
		$exe=false;
		$errorX="DG";
		$id_boleta=0;
		$id_cheque=0;
		$id_contrato=$_POST["id_contrato"];
		$valor_total_excedente=$_POST["valor_total_excedente"];
		
		$num_documento=$_POST["num_documento"];
		$glosa=str_inde($_POST["glosa"]);
		$fecha=$_POST["fecha_movimiento"];
		$valor=trim(str_inde($_POST["valor"]));
		$tipo_documento=strtolower($_POST["tipo_documento"]);
		$tipoDevolucion=$_POST["tipoDevolucion"];
		$forma_pago=$_POST["forma_pago"];
		$fecha_venc_cheque=$_POST["fecha_venc_cheque"];
		$cheque_numero=$_POST["cheque_numero"];
		$cheque_banco=$_POST["cheque_banco"];
		
		$cod_user_activo=$_SESSION["USUARIO"]["id"];
		$tipo_movimiento="E";
		$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
		if(DEBUG){ echo"---> id_alumno: $id_alumno<br>";}
		$por_concepto="devolucion_excedente";//agregada para filtrar mejor
		$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
		
		//para campos agregados
		date_default_timezone_set('America/Santiago');//zona horaria
		$fecha_generacion=date("Y-m-d H:i:s");
		$ip=$_SERVER['REMOTE_ADDR'];
			switch($sede_alumno)
			{
				case"Talca":
					$codigo_item="1000001";
					break;
				case"Linares":	
					$codigo_item="1000010";
					break;
			}
		
		////////////obtengo semestre y año del la fecha ingresada/////////////
		$DFI=explode("-",$fecha);
		$year=$DFI[0];
		$mes=$DFI[1];
		$dia=$DFI[2];
		
		///////////////////
		$no_repetida=true;
		$generar_boleta=true;
		
		///////////////////
		
		if(abs($mes)>6)
		{$semestre=2;}
		else
		{$semestre=1;}
		//////////-----------------------------------------------/////////////
		
		if(is_numeric($valor))
		{$continuar=true;}
		else
		{
			$continuar=false;
			$errorX="Datos_Incorrectos";
			$errorY="Datos_Incorrectos";
		}
		//------------------------------------------------------------------------------//
		if(($continuar)and($no_repetida))
		{
				//arma consulta
				switch($tipo_movimiento)
				{
					case"E":
						//$por_concepto="otro_ingreso_2";
						$exe=true;
						switch($forma_pago)
						{
							case"efectivo":
								if(DEBUG)
									{echo"Pago -> Efectivo<br>";}
									$fecha_vencimiento_cheque="0000-00-00";
									$id_cta_cte00;
								break;
							case"transferencia":
								if(DEBUG)
									{echo"Pago -> rtranferencia<br>";}
									$fecha_vencimiento_cheque="0000-00-00";
									$id_cta_cte=$_POST["id_cta_cte"];
								break;	
							case"cheque":
								if(DEBUG)
									{echo"Pago -> Cheque<br>";}
									$cheque["emisor"]="empresa";
									$cheque["id_alumno"]=$id_alumno;	
									$cheque["id_empresa"]=10;///id_proveedor de CFT MASSACHUSETTS	
									$cheque["numero"]=$cheque_numero;
									$cheque["fecha_vence"]=$fecha_venc_cheque;
									$cheque["banco"]=$cheque_banco;
									$cheque["valor"]=$valor;
									$cheque["sede"]=$sede_alumno;
									$cheque["glosa"]=$glosa;
									$cheque["movimiento"]="E";
									$id_cheque=REGISTRA_CHEQUE($cheque);
									$fecha_vencimiento_cheque=$fecha_venc_cheque;
									$id_cta_cte=0;
								break;	
						}
						////////////
						//////////////
						break;
				
				}
				//---------------------------------------------------------------------//
				//---------------------------------------------------------------------//
				if($exe)
				{
					if(DEBUG){ echo"GENERAR el EGRESO<br><br>";}
					/////////////////////
					$campos="id_boleta, id_alumno, item, fechapago, valor, tipodoc, glosa, sede, movimiento, forma_pago, fechaV_cheque, id_cheque, id_cta_cte,  por_concepto, semestre, year, cod_user, fecha_generacion, ip, aux_num_documento";
					
					$valores="'$id_boleta', '$id_alumno', '$codigo_item', '$fecha', '$valor', '$tipo_documento', '$glosa', '$sede_alumno', '$tipo_movimiento', '$forma_pago', '$fecha_vencimiento_cheque', '$id_cheque', '$id_cta_cte', '$por_concepto', '$semestre', '$year', '$cod_user_activo', '$fecha_generacion', '$ip', '$num_documento'";
					///-----------------------------------------------------------------////
					$cons="INSERT INTO pagos ($campos) VALUES ($valores)";
					if(DEBUG){echo"---> $cons<br>";}
					else
					{
						if($conexion_mysqli->query($cons))
						{
								$errorX=0; 
								$id_pago=$conexion_mysqli->insert_id;
								$pago_registrado_OK=true;
						}
						else
						{$errorX=1; $id_pago=0; echo "Error al Insertar Pago: ".$conexion_mysqli->error;}
					}
					
					switch($forma_pago)
					{
						case"efectivo":
							$comentario="Devolucion de Excedente Valor: $valor. ($forma_pago)";
							break;
						case"cheque":
							$comentario="Devolucion de Excedente Valor: $valor. ($forma_pago N.$num_documento F.".fecha_format($fecha_vencimiento_cheque)." B. $cheque_banco)";
							break;
					}
					
					if($pago_registrado_OK)
					{
						if($tipoDevolucion=="excedente"){
							ACTUALIZAR_CONTRATO($valor_total_excedente, $valor, $id_contrato, $comentario);
						}
						
						$descripcion="devolucion de Excedente a Alumno de $".$valor;
						REGISTRO_EVENTO_ALUMNO($id_alumno,"notificacion",$descripcion);
						///////////////registr evento/////////////////////
						$evento="Devolucion Excedente a Alumno($tipo_documento $num_documento) ID Alumno: $id_alumno";
						REGISTRA_EVENTO($evento);
						///////////////////////////////////////////////////
					}
				}
				else
				{echo"No habilitado para este tipo de transaccion($tipo_movimiento)<br>";}		
				if(DEBUG){ echo"Error: $errorX<br>";}

		
		
		$conexion_mysqli->close();
		
		if(DEBUG){ echo"FIN EXE<br>";}
		else{header("location: devolucion_excedente3.php?error=$errorX&id_contrato=$id_contrato&excedente=$valor&id_pago=".base64_encode($id_pago));}
	}
	else
	{ if(DEBUG){ echo"No continuar o repetida<br>";}else{header("location: devolucion_excedente3.php?error=$errorY");}}	
}
else
{ 
	if(DEBUG){ echo"Incorrecto los datos<br>";}
	else{ header("location: ../index.php?error=5");}
}


//--------------------> Actualizo Contrato<------------------------//
function ACTUALIZAR_CONTRATO($excedente_total, $excedente_a_devolver, $id_contrato, $comentario)
{
	require("../../../../funciones/conexion_v2.php");
	if(DEBUG){ echo"<br><strong>Actualizando Contrato...</strong><br>";}
	
	$nuevo_excedente=($excedente_total-$excedente_a_devolver);
	
	$cons_UP="UPDATE contratos2 SET excedente='$nuevo_excedente', txt_beca=CONCAT(txt_beca,'$comentario') WHERE id='$id_contrato' LIMIT 1";
	if(DEBUG){ echo"$cons_UP<br>";}
	else{ $conexion_mysqli->query($cons_UP)or die("UP contrato".$conexion_mysqli->error);}
	$conexion_mysqli->close();
}
?>