<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Registros_ingresos_empresa_boleta_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

if(DEBUG){ var_dump($_POST);}
if(($_POST)and($_SESSION["PAGOS"]["verificador"]))
{
	if(!DEBUG){$_SESSION["PAGOS"]["verificador"]=false;}
		require("../../../funciones/funcion.php");
		require("../../../funciones/funciones_sistema.php");
		require("../../../funciones/conexion_v2.php");
		
		
		$id_cta_cte=$_POST["id_cta_cte"];
		$id_empresa=$_POST["empresa"];
		$fglosa=mysqli_real_escape_string($conexion_mysqli, $_POST["fglosa"]);
		$ffecha=$_POST["fecha_movimiento"];
		$fvalor=mysqli_real_escape_string($conexion_mysqli, trim(str_inde($_POST["fvalor"])));
		$ftipo_doc="boleta";
		$forma_pago=$_POST["forma_pago"];
		$fecha_venc_cheque=$_POST["fecha_venc_cheque"];
		$cheque_numero=mysqli_real_escape_string($conexion_mysqli, $_POST["cheque_numero"]);
		$cheque_banco=$_POST["cheque_banco"];
		$sede=$_POST["fsede"];
		$cod_user_activo=$_SESSION["USUARIO"]["id"];
		$tipo_movimiento="I";
		$por_concepto=$_POST["por_conceptoX"];//agregada para filtrar mejor
		$tipo_receptor="empresa";
		
		
		$errorX="";
		//para campos agregados
		date_default_timezone_set('America/Santiago');//zona horaria
		$fecha_generacion=date("Y-m-d H:i:s");
		$ip=$_SERVER['REMOTE_ADDR'];
		
		$codigo_item="99999";
		
		////////////obtengo semestre y año del la fecha ingresada/////////////
		$DFI=explode("-",$ffecha);
		$year=$DFI[0];
		$mes=$DFI[1];
		$dia=$DFI[2];
		
		///////////////////
		switch($ftipo_doc)
		{
			case"letras":
				$fglosa.="[br][$ftipo_doc $fnum_documento] ";
				//-----------------Verifico no este repetida-------------------------////
				$cons_B="SELECT COUNT(idpago) FROM pagos WHERE aux_num_documento='$fnum_documento' AND tipodoc='$ftipo_doc' AND sede='$sede' AND movimiento='I' AND fechapago='$ffecha'";
				if(DEBUG)
					{echo"---> $cons_B<br><br>";}
				$sqlLL=mysql_query($cons_B)or die("Repetido .".mysql_error());
				$DD=mysql_fetch_row($sqlLL);
				$coincidencias=$DD[0];
				if(empty($coincidencias)){ $coincidencias=0;}
				if($coincidencias>0)
				{
					$no_repetida=false;
					if(DEBUG)
					{echo"Pago Repetido...XXX<br>";}
					$errorY="Transaccion_ya_realizada_anteriormente";
				}
				else
				{
					$no_repetida=true;
					if(DEBUG)
					{echo"Pago NO Repetido...<br>";}
				}
				mysql_free_result($sqlLL);
				$generar_boleta=false;
				//-----------------------------------////////////////////-------------///
				break;
			default:
				$no_repetida=true;
				$generar_boleta=true;
		}
		///////////////////
		
		if(abs($mes)>=8){$semestre=2;}
		else{$semestre=1;}
		//////////-----------------------------------------------/////////////
		
		if(is_numeric($fvalor))
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
					case"I":
						//$por_concepto="otro_ingreso_2";
						$exe=true;
						switch($forma_pago)
						{
							case"deposito":
								$id_cheque=0;
								$fecha_vencimiento_cheque="0000-00-00";
								$glosa_boleta=$fglosa." (deposito)";
								break;
							case"efectivo":
								if(DEBUG){echo"Pago -> Efectivo<br>";}
								$fecha_vencimiento_cheque="0000-00-00";
								$glosa_boleta=$fglosa." (efectivo)";
								$id_cheque=0;
								$id_cta_cte=0;
								break;
							case"cheque":
								$id_cta_cte=0;
								if(DEBUG){echo"Pago -> Cheque<br>";}
								$cheque["emisor"]="empresa";
								$cheque["id_empresa"]=$id_empresa;
								$cheque["numero"]=$cheque_numero;
								$cheque["fecha_vence"]=$fecha_venc_cheque;
								$cheque["banco"]=$cheque_banco;
								$cheque["valor"]=$fvalor;
								$cheque["sede"]=$sede;
								$cheque["glosa"]=$fglosa;
								$cheque["movimiento"]="I";
								$id_cheque=REGISTRA_CHEQUE($cheque);
								$fecha_vencimiento_cheque=$fecha_venc_cheque;
								$glosa_boleta=$fglosa." (Cheque N. $cheque_numero Banco. $cheque_banco Vencimiento. $fecha_venc_cheque)";
								break;	
						}
						////////////
						//////////////
						break;
				
				}
				//---------------------------------------------------------------------//
				if($exe)
				{
					/////////BOLETA////////////
					if($generar_boleta)
					{
						$id_boleta=GENERA_BOLETA($id_empresa, $fvalor, $sede, $glosa_boleta, $ffecha,"empresa");
					}
					/////////////////////
					$campos="tipo_receptor, id_boleta, id_empresa, item, fechapago, valor, tipodoc, glosa, sede, movimiento, forma_pago, fechaV_cheque, id_cheque, id_cta_cte, por_concepto, semestre, year, cod_user, fecha_generacion, ip";
					
					$valores="'$tipo_receptor', '$id_boleta', '$id_empresa', '$codigo_item', '$ffecha', '$fvalor', '$ftipo_doc', '$fglosa', '$sede', '$tipo_movimiento', '$forma_pago', '$fecha_vencimiento_cheque', '$id_cheque', '$id_cta_cte', '$por_concepto', '$semestre', '$year', '$cod_user_activo', '$fecha_generacion', '$ip'";
					///-----------------------------------------------------------------////
					$cons="INSERT INTO pagos ($campos) VALUES ($valores)";
					if(DEBUG)
					{echo"---> $cons<br>";}
					else
					{
						if($conexion_mysqli->query($cons))
						{
							$errorX=0;
						}
						else
						{
							$errorX=1;
							echo"-->".$conexion_mysqli->error;
							if($id_boleta>1)
							{
								////////////////////Borro boleta registrada si falla en registrar pago/////////
								$cons_bb="DELETE FROM boleta WHERE id='$id_boleta' LIMIT 1";
								$conexion_mysqli->query($cons_bb)or die("ELIMINANDO BOLETA ".$conexion_mysqli->error);
								///////////////////////////////////////////////////////////////////////////////
							}
						}
					}
				}
				else
				{
					echo"No habilitado para este tipo de transaccion($ftipo_mov)<br>";
				}		
				if(DEBUG){ echo"Error: $errorX<br>";}
		
		
		$conexion_mysqli->close();
		
		///////////////registr evento/////////////////////
		include("../../../funciones/VX.php");
		$evento="Registro ingreso_con_boleta Empresa($ftipo_doc) ID Empresa: $id_empresa";
		REGISTRA_EVENTO($evento);
		///////////////////////////////////////////////////
		if(DEBUG){}
		else{header("location: ingreso_boleta_final.php?error=$errorX&id_boleta=$id_boleta");}
	}
	else
	{ if(DEBUG){}else{header("location: ingresos_boleta.php?error=$errorY");}}	
}
else
{ header("location: ingresos_boleta.php");}
//--------------------> Registro CHEQUE <------------------------//

?>