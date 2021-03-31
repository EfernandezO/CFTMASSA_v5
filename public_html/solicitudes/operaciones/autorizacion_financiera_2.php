<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Solicitud->AutorizacionFinanciera");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(isset($_SESSION["PAGOS"]["verificador"]))
{
	if($_SESSION["PAGOS"]["verificador"])
	{ $acceso=true;}
	else
	{ $acceso=false;}
}
else
{ $acceso=false;}

if(($_POST)and($acceso))
{
		///////////////////
	$continuar=true;
	////////////////////
	if(!DEBUG){ $_SESSION["PAGOS"]["verificador"]=false;}
	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funciones_sistema.php");
	
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	
	$cod_user_activo=$_SESSION["USUARIO"]["id"];
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	
	if(DEBUG){var_dump($_POST);}
	$id_solicitud=$_POST["id_solicitud"];
	$sede=$_POST["fsede"];
	$fecha_movimiento=$_POST["fecha_movimiento"];
	$valor=$_POST["valor"];
	$forma_pago=$_POST["forma_pago"];
	$cheque_numero=$_POST["cheque_numero"];
	$fecha_venc_cheque=$_POST["fecha_venc_cheque"];
	$cheque_banco=$_POST["cheque_banco"];
	$deposito_numero=$_POST["deposito_numero"];
	$id_cta_cte=$_POST["id_cta_cte"];
	
	$fnum_documento="";
	$tipo_documento="boleta";
	$tipo_movimiento="I";
	$fecha_generacion=date("Y-m-d H:i:s");
	$ip=$_SERVER['REMOTE_ADDR'];
	switch($sede)
	{
		case"Talca":
			$codigo_item="4101286";
			break;
		case"Linares":	
			$codigo_item="4101294";
			break;
	}
	////////////obtengo semestre y año del la fecha ingresada/////////////
		$DFI=explode("-",$fecha_movimiento);
		$year=$DFI[0];
		$mes=$DFI[1];
		$dia=$DFI[2];
		if(abs($mes)>6)//----------->
		{$semestre=2;}
		else
		{$semestre=1;}
		
		
	
		if((is_numeric($id_solicitud))and($id_solicitud>0))
		{ $hay_solicitud=true;}
		else
		{ $hay_solicitud=false;}
	
	if($hay_solicitud)
	{
		$cons="SELECT * FROM solicitudes WHERE id='$id_solicitud' LIMIT 1";
		if(DEBUG){ echo"Hay solicitud...<br>$cons<br>";}
		$sql=$conexion_mysqli->query($cons);
			$Ds=$sql->fetch_assoc();
				$S_tipo=$Ds["tipo"];
				$S_categoria=$Ds["categoria"];
			$sql->free();	
			
			$glosa_pago=$S_tipo." ".$S_categoria;
			$por_concepto=$S_tipo;
	}
	else
	{
		$tipo=$_POST["tipo"];
		$categoria=$_POST["categoria"];
		
		if(DEBUG){ echo"No viene con solicitud VERIFICAR<br>";}
		$crear_consulta=true;
		//////////////////////////////////////////////////////
		$crear_consulta=TIENE_OTRA_SOLICITUD("alumno", $id_alumno, $id_carrera, $tipo, $categoria);
		//////////////////////////////////////////////////////
		
		
		if($crear_consulta)
		{
			if(DEBUG){ echo"Creacion Automatica de Solicitud<br>";}
			$observacion="";
			
			$campos="tipo, categoria, observacion, tipo_solicitante, id_solicitante, id_carrera_solicitante, fecha_hora_solicitud, tipo_receptor, id_receptor, id_carrera_receptor, sede_receptor, id_autorizador, autorizado, tipo_autorizador, fecha_hora_autorizacion, tipo_creador, id_creador, fecha_hora_creacion, estado";
			
			$valores="'$tipo', '$categoria', '$observacion', '$privilegio', '$cod_user_activo', '0', '$fecha_generacion', 'alumno', '$id_alumno', '$id_carrera', '$sede_alumno', '0', 'si', '', '0000-00-00 00:00:00', '', '0', '0000-00-00 00:00:00', 'pendiente'";
	
			$cons_IN="INSERT INTO solicitudes ($campos) VALUES ($valores)";
			if(DEBUG){ echo"--->$cons_IN<br>"; $id_solicitud=100;}
			else{ $conexion_mysqli->query($cons_IN); $id_solicitud=$conexion_mysqli->insert_id;}
			
			$glosa_pago=$tipo." ".$categoria;
			$por_concepto=$tipo;
		}
		else
		{ $continuar=false; $errorX="A2"; if(DEBUG){ echo"Ya existe Solicitudes no autorizadas no se puede crear<br>";}}
	}	
		
	if($continuar)
		{
				//arma consulta
				switch($tipo_movimiento)
				{
					case"I":
						$exe=true;
						switch($forma_pago)
						{
							case"efectivo":
								if(DEBUG){echo"Pago -> Efectivo<br>";}
								$fecha_vencimiento_cheque="0000-00-00";
								$glosa_boleta=$glosa_pago." (efectivo)";
								$id_cheque=0;
								$generar_boleta=true;
								break;
							case"cheque":
								if(DEBUG){echo"Pago -> Cheque<br>";}
								$cheque["id_alumno"]=$id_alumno;
								$cheque["numero"]=$cheque_numero;
								$cheque["fecha_vence"]=$fecha_venc_cheque;
								$cheque["banco"]=$cheque_banco;
								$cheque["valor"]=$valor;
								$cheque["sede"]=$sede;
								$cheque["glosa"]=$glosa_pago;
								$cheque["movimiento"]="I";
								$id_cheque=REGISTRA_CHEQUE($cheque);
								$fecha_vencimiento_cheque=$fecha_venc_cheque;
								$glosa_boleta=$glosa_pago." (Cheque N. $cheque_numero Banco. $cheque_banco Vencimiento. $fecha_venc_cheque)";
								$generar_boleta=true;
								break;	
							case"deposito":
								if(DEBUG){echo"Pago -> Deposito<br>";}
								$id_cheque=0;
								$fecha_vencimiento_cheque="0000-00-00";
								$glosa_boleta=$glosa_pago." (Deposito N.$deposito_numero)";
								$fnum_documento=$deposito_numero;
								$generar_boleta=true;
								break;
						}
						break;
				
				}
				//---------------------------------------------------------------------//
				if($exe)
				{
					/////////BOLETA////////////
					if($generar_boleta)
					{$id_boleta=GENERA_BOLETA($id_alumno, $valor, $sede, $glosa_boleta, $fecha_movimiento);}
					/////////////////////
					$campos="id_boleta, id_alumno, item, fechapago, valor, tipodoc, glosa, sede, movimiento, forma_pago, fechaV_cheque, id_cheque, id_cta_cte, por_concepto, semestre, year, cod_user, fecha_generacion, ip, aux_num_documento";
					
					$valores="'$id_boleta', '$id_alumno', '$codigo_item', '$fecha_movimiento', '$valor', '$tipo_documento', '$glosa_pago', '$sede', '$tipo_movimiento', '$forma_pago', '$fecha_vencimiento_cheque', '$id_cheque', '$id_cta_cte', '$por_concepto', '$semestre', '$year', '$cod_user_activo', '$fecha_generacion', '$ip', '$fnum_documento'";
					///-----------------------------------------------------------------////
					//inserta pago
					$cons="INSERT INTO pagos ($campos) VALUES ($valores)";
					if(DEBUG)
					{echo"---> $cons<br><br>"; $errorX="DEBUG.."; $procesar_solicitud=true; $id_pago="id_pago_debug";}
					else
					{
						if($conexion_mysqli->query($cons))
						{$errorX="A0"; $procesar_solicitud=true; $id_pago=$conexion_mysqli->insert_id;}
						else
						{
							$id_pago="";
							$errorX="A1";
							$procesar_solicitud=false;
							if($id_boleta>1)
							{
								////////////////////Borro boleta registrada si falla en registrar pago/////////
								$cons_bb="DELETE FROM boleta WHERE id='$id_boleta' AND id_alumno='$id_alumno' LIMIT 1";
								$conexion_mysqli->query($cons_bb);
								///////////////////////////////////////////////////////////////////////////////
							}
						}
					}
					
					///************************************////
					//verifica solicitud
					if($procesar_solicitud)
					{
						if($id_solicitud>0)
						{
							if(DEBUG){ echo"AUTORIZAR SOLICITUD<br>";}
							$cons_solicitud="UPDATE solicitudes SET id_autorizador='$cod_user_activo', autorizado='si', tipo_autorizador='$privilegio', fecha_hora_autorizacion='$fecha_generacion', metodo_autorizacion='pagada', id_pago='$id_pago' WHERE id='$id_solicitud' LIMIT 1";
							
							if(DEBUG){ echo"--->$cons_solicitud<br>";}
							else
							{ 
								$conexion_mysqli->query($cons_solicitud);
								
								 /////Registro evento///
								 include("../../../funciones/VX.php");
								 $evento="Autoriza Financieramente Solicitud: ($id_solicitud)";
								 REGISTRA_EVENTO($evento);
								 ///////////////////////
							}
						}
						
					}
					
				}
				else
				{echo"No habilitado para este tipo de transaccion<br>";}		
				
				if(DEBUG){ echo"Error: $errorX<br>";}
		}
		
		$conexion_mysqli->close();
		
		$url="msj_final_autorizacion.php?error=$errorX&id_boleta=$id_boleta";
		if(DEBUG){ echo"URL: $url<br>";}
		else{ header("location: $url");}
}
else
{
	$url="";
	
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
//////////////////////////////////////////////////////////
function TIENE_OTRA_SOLICITUD($tipo_receptor, $id_receptor, $id_carrera_receptor, $tipo, $categoria)
{
	require("../../../funciones/conexion_v2.php");
	switch($tipo_receptor)
	{
		case"alumno":
			$cons="SELECT COUNT(id) FROM solicitudes WHERE tipo_receptor='$tipo_receptor' AND id_receptor='$id_receptor' AND id_carrera_receptor='$id_carrera_receptor' AND autorizado='no' AND tipo='$tipo' AND categoria='$categoria'";
			$sql=$conexion_mysqli->query($cons);
				$D=$sql->fetch_row();
				$coincidencias=$D[0];
				$sql->free();
				if(DEBUG){ echo"--->$cons<br>coincidencias:$coincidencias<br>";}
			break;
		default:
				$coincidencias=1;
	}
	
	if($coincidencias>0)
	{ $crear_consulta=false;}
	else{ $crear_consulta=true;}
	$conexion_mysqli->close();
	return($crear_consulta);
}
?>