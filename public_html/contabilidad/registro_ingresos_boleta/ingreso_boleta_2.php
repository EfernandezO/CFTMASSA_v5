<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_otros_pagos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$errorX="";
if(DEBUG){error_reporting(E_ALL); ini_set("display_errors", 1);}

if(($_POST)and($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])and($_SESSION["PAGOS"]["verificador"]))
{
	if(!DEBUG){$_SESSION["PAGOS"]["verificador"]=false;}
		include("../../../funciones/funcion.php");
		require("../../../funciones/conexion_v2.php");
		include("../../../funciones/funciones_sistema.php");
		
		$fnum_documento=$_POST["num_documento"];
		$fglosa=str_inde($_POST["fglosa"]);
		$ffecha=$_POST["fecha_movimiento"];
		$fvalor=trim(str_inde($_POST["fvalor"]));
		$fvalor=str_replace(".","",$fvalor);
		$fvalor=str_replace(",","",$fvalor);
		$ftipo_doc=strtolower($_POST["ftipo_doc"]);
		$forma_pago=$_POST["forma_pago"];
		$fecha_venc_cheque=$_POST["fecha_venc_cheque"];
		$cheque_numero=$_POST["cheque_numero"];
		$cheque_banco=$_POST["cheque_banco"];
		$sede=$_POST["fsede"];
		$cod_user_activo=$_SESSION["USUARIO"]["id"];
		$tipo_movimiento="I";
		$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
		$por_concepto=$_POST["por_conceptoX"];//agregada para filtrar mejor
		
		//para campos agregados
		date_default_timezone_set('America/Santiago');//zona horaria
		$fecha_generacion=date("Y-m-d H:i:s");
		$ip=$_SERVER['REMOTE_ADDR'];
		
		if($ftipo_doc!="letras")
		{
			//codigo para todo menos letras
			switch($sede)
			{
				case"Talca":
					$codigo_item="4101286";
					break;
				case"Linares":	
					$codigo_item="4101294";
					break;
			}
		}
		else
		{
			//codigo para letras
			switch($sede)
			{
				case"Talca":
					$codigo_item="1110047";
					break;
				case"Linares":	
					$codigo_item="1110055";
					break;
			}
		}
		
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
				$sqlLL=$conexion_mysqli->query($cons_B)or die("Repetido .".$conexion_mysqli->error);
				$DD=$sqlLL->fetch_row();
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
				$sqlLL->free();
				$generar_boleta=false;
				//-----------------------------------////////////////////-------------///
				break;
			default:
				$no_repetida=true;
				$generar_boleta=true;
		}
		///////////////////
		
		if(abs($mes)>6)
		{
			$semestre=2;
		}
		else
		{
			$semestre=1;
		}
		//////////-----------------------------------------------/////////////
		
		if(is_numeric($fvalor))
		{
			$continuar=true;
			
		}
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
							case"efectivo":
								if(DEBUG)
									{echo"Pago -> Efectivo<br>";}
									$fecha_vencimiento_cheque="0000-00-00";
									$glosa_boleta=$fglosa." (efectivo)";
									$id_cheque=0;
								break;
							case"cheque":
								if(DEBUG)
									{echo"Pago -> Cheque<br>";}
								$cheque["numero"]=$cheque_numero;
								$cheque["fecha_vence"]=$fecha_venc_cheque;
								$cheque["banco"]=$cheque_banco;
								$cheque["valor"]=$fvalor;
								$cheque["sede"]=$sede;
								$cheque["glosa"]=$fglosa;
								$cheque["id_alumno"]=$id_alumno;
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
						$id_boleta=GENERA_BOLETA($id_alumno, $fvalor, $sede, $glosa_boleta, $ffecha);
					}
					/////////////////////
					$campos="id_boleta, id_alumno, item, fechapago, valor, tipodoc, glosa, sede, movimiento, forma_pago, fechaV_cheque, id_cheque, por_concepto, semestre, year, cod_user, fecha_generacion, ip, aux_num_documento";
					
					$valores="'$id_boleta', '$id_alumno', '$codigo_item', '$ffecha', '$fvalor', '$ftipo_doc', '$fglosa', '$sede', '$tipo_movimiento', '$forma_pago', '$fecha_vencimiento_cheque', '$id_cheque', '$por_concepto', '$semestre', '$year', '$cod_user_activo', '$fecha_generacion', '$ip', '$fnum_documento'";
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
							echo "----->".$conexion_mysqli->error;
							if($id_boleta>1)
							{
								////////////////////Borro boleta registrada si falla en registrar pago/////////
								$cons_bb="DELETE FROM boleta WHERE id='$id_boleta' AND id_alumno='$id_alumno' LIMIT 1";
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
		$evento="Registro ingreso_con_boleta($ftipo_doc $fnum_documento) ID Alumno: $id_alumno";
		REGISTRA_EVENTO($evento);
		///////////////////////////////////////////////////
		
		$url="ingreso_boleta_final.php?error=$errorX&id_boleta=$id_boleta";
		if(DEBUG){ echo"URL: $url<br>";}
		else{header("location: $url");}
	}
	else
	{ if(DEBUG){}else{header("location: ingresos_boleta.php?error=$errorY");}}	
}
else
{ header("location: ../../buscador_alumno_BETA/HALL/index.php");}
?>