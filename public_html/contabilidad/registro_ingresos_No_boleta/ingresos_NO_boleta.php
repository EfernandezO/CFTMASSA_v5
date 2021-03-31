<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG",false);
//-----------------------------------------//	

if($_POST)
{
		include("../../../funciones/funcion.php");
		require("../../../funciones/conexion_v2.php");
		$tabla="pagos";
		$errorX="";
		if(DEBUG){var_dump($_POST);}
		
		$fglosa=strtolower($_POST["fglosa"]);
		$fglosa=mysqli_real_escape_string($conexion_mysqli, $fglosa);
		$ffecha=$_POST["fecha_movimiento"];
		$fvalor=trim(str_inde($_POST["fvalor"]));
		$fnum_doc=trim(str_inde($_POST["fnum_doc"]));
		$ftipo_mov=$_POST["ftipo_mov"];
		$ftipo_doc=strtolower($_POST["ftipo_doc"]);
		$forma_pago=$_POST["forma_pago"];
		$fecha_venc_cheque=$_POST["fecha_venc_cheque"];
		$cheque_numero=$_POST["cheque_numero"];
		$cheque_banco=$_POST["cheque_banco"];
		$sede=$_POST["fsede"];
		$cod_user_activo=$_SESSION["USUARIO"]["id"];
		
		$por_concepto=$_POST["por_concepto"];//agregado
		//para campos agregados
		date_default_timezone_set('America/Santiago');//zona horaria
		$fecha_generacion=date("Y-m-d H:i:s");
		$ip=$_SERVER['REMOTE_ADDR'];
		$id_item="";
		////////////obtengo semestre y año del la fecha ingresada/////////////
		$DFI=explode("-",$ffecha);
		$year=$DFI[0];
		$mes=$DFI[1];
		$dia=$DFI[2];
		
		
		if(abs($mes)>8)
		{$semestre=2;}
		else
		{$semestre=1;}
		//////////-----------------------------------------------/////////////
		//seleccion de item
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
		////////////////////////////
		if((is_numeric($fvalor))and(is_numeric($fnum_doc)))
		{$continuar=true;}
		else
		{
			$continuar=false;
			$errorX="Datos_Incorrectos";
		}
		//-----------------Verifico no este repetida-------------------------////
		
		if($ftipo_doc!="letras")
		{
			if(DEBUG){echo"Verifico movimiento no este ya realizado...";}
			$cons_B="SELECT COUNT(idpago) FROM $tabla WHERE aux_num_documento='$fnum_doc' AND tipodoc='$ftipo_doc' AND sede='$sede' AND movimiento='$ftipo_mov'";
			if(DEBUG)
				{echo"---> $cons_B<br><br>";}
			
			$sql=$conexion_mysqli->query($cons_B) or die("Repetido");
			$DD=$sql->fetch_row();
			$coincidencias=$DD[0];
			
			if($coincidencias>0)
			{
				$repetida=true;
				if(DEBUG)
				{echo"Pago Repetido...XXX<br>";}
			}
			else
			{
				$repetida=false;
				if(DEBUG)
				{echo"Pago NO Repetido...<br>";}
			}
			$sql->free();
		}
		else
		{ 
			if(DEBUG){ echo"Documento -> letra... no verifica repetida...";}
			$repetida=false;
		}
		//-----------------------------------////////////////////-------------///
		//$repetida=es_repetida($tabla,$fnum_doc,$ftipo_doc,$fsede,$ftipo_mov,$campos);
		
		if($continuar)
		{
			if($repetida)
			{
				$errorX="Transaccion_ya_registrada_Anteriormente...";
			}
			else
			{
				//arma consulta
				switch($ftipo_mov)
				{
					case"I":
						//$por_concepto="otro_ingreso";
						$exe=true;
						switch($forma_pago)
						{
							case"efectivo":
								if(DEBUG)
									{echo"Pago -> Efectivo<br>";}
									$id_cheque=0;
									$fecha_vencimiento_cheque="0000-00-00";
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
								$cheque["movimiento"]="I";
								$id_cheque=REGISTRA_CHEQUE($cheque);
								$fecha_vencimiento_cheque=$fecha_venc_cheque;
								break;	
						}
						////////////
						//////////////
						break;
					case"E":
						if(DEBUG)
							{echo"Generando - > Egreso<br>";}
						$exe=true;			
						//$por_concepto="otro_egreso";			
						switch($forma_pago)
						{
							case"efectivo":
								if(DEBUG)
									{echo"Pago -> Efectivo<br>";}
									$fecha_vencimiento_cheque="0000-00-00";
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
								$cheque["movimiento"]="E";
								$id_cheque=REGISTRA_CHEQUE($cheque);
								$fecha_vencimiento_cheque=$fecha_venc_cheque;
								break;	
						}
						break;
				}
				//---------------------------------------------------------------------//
				if($exe)
				{
					$campos="item, fechapago, valor, tipodoc, glosa, sede, movimiento, forma_pago, fechaV_cheque, id_cheque, por_concepto, semestre, year, cod_user, fecha_generacion, ip, aux_num_documento";
					
					$valores="'$codigo_item', '$ffecha', '$fvalor', '$ftipo_doc', '$fglosa', '$sede', '$ftipo_mov', '$forma_pago', '$fecha_vencimiento_cheque', '$id_cheque', '$por_concepto', '$semestre', '$year', '$cod_user_activo', '$fecha_generacion', '$ip', '$fnum_doc'";
					///-----------------------------------------------------------------////
					$cons="INSERT INTO $tabla ($campos) VALUES ($valores)";
					if(DEBUG)
					{echo"---> $cons<br>";}
					else
					{
						if($conexion_mysqli->query($cons))
						{$errorX="Registro Realizado Exitosamente";}
						else
						{$errorX="FAllo al Registrar Movimiento...(".$conexion_mysqli->error.")";}
					}
					
					if(DEBUG)
					{ echo"==> $errorX<br>";}
				}
				else
				{
					echo"No habilitado para este tipo de transaccion($ftipo_mov)<br>";
				}		
			}
		}
		
		$conexion_mysqli->close();
		
		///////////////registr evento/////////////////////
		include("../../../funciones/VX.php");
		$evento="Registro ingreso_NO_boleta_2($ftipo_mov)->$fnum_doc";
		REGISTRA_EVENTO($evento);
		///////////////////////////////////////////////////
		
		$url_destino="index.php";
		
		if(DEBUG){}
		else{header("location: $url_destino?error=$errorX&ultima_fecha=$ffecha");}
}
//--------------------> Registro CHEQUE <------------------------//
function REGISTRA_CHEQUE($cheque, $chequeXmatricula_arancel=false)
{
	require("../../../funciones/conexion_v2.php");
	$fecha_actual=date("Y_m-d");
	$condicion="OK";
	$debug=DEBUG;
	
	///
	$id_alumno=$cheque["id_alumno"];
	$cheque_numero=$cheque["numero"];
	$cheque_vence=$cheque["fecha_vence"];
	$cheque_banco=$cheque["banco"];
	$valor=$cheque["valor"];
	$sede=$cheque["sede"];
	$glosa_cheque=$cheque["glosa"];
	$movimiento=$cheque["movimiento"];
	
	$cons_b="SELECT COUNT(id)FROM registro_cheques WHERE id_alumno='$id_alumno' AND numero='$cheque_numero' AND banco='$cheque_banco'";

	$sql=$conexion_mysqli->query($cons_b)or die("buscando cheque ".$conexion_mysqli->error);
	$D=$sql->fetch_row();
	$coincidencias=$D[0];
	if(!$coincidencia>0)
	{
		$campos="id_alumno, numero, fecha_vencimiento, banco, valor, condicion, sede, fecha, glosa, movimiento";
		$valores="'$id_alumno', '$cheque_numero', '$cheque_vence', '$cheque_banco', '$valor', '$condicion', '$sede', '$fecha_actual', '$glosa_cheque', '$movimiento'";
		
		$cons_cheque="INSERT INTO registro_cheques ($campos) VALUES ($valores)";
		
		if($debug)
		{
			echo"<br><br>REGISTRO CHEQUE X-> $cons_cheque<br><br>";
			$id_cheque=3;
		}
		else
		{
			$conexion_mysqli->query($cons_cheque)or die("Registrando cheque ".$conexion_mysqli->error);
			$id_cheque=$conexion_mysqli->insert_id;
			
			
		}
	}
	else
	{
		echo"<br><br>=====> cheque repetido<br>";
	}	
	$conexion_mysqli->close();
	return($id_cheque);
}
?>