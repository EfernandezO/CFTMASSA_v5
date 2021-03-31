<?php
//--------------CLASS_okalis------------------//
set_time_limit(600);
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(TRUE);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("gestion_cobranza_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$continuar=false;
$ARRAY_ALUMNO=array();
if($_GET)
{
	$array_tipos_cobranza=array("telefonico", "domiciliaria", "carteo","email", "sms");
	require("../../../../../funciones/class_ALUMNO.php");
	require("../../../../../funciones/conexion_v2.php");
	require("../../../../../funciones/funciones_sistema.php");
	require("../../../../../funciones/VX.php");
	require("../../../../libreria_publica/PHPMailer6/class_EMAIL.php");
	require("../../../../../funciones/funciones_varias.php");
	
	$continuar=true;
	if(DEBUG){ var_dump($_GET);}
	$sede=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["sede"]));
	$id_carrera=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["id_carrera"]));
	$year_ingreso=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["year_ingreso"]));
	$year_cuotas=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["year_cuotas"]));
	$jornada=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["jornada"]));
	$grupo=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["grupo"]));
	$array_niveles=base64_decode($_GET["niveles"]);
	
	$array_niveles=unserialize($array_niveles);
	
	$fecha_corte=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["fecha_corte"]));
	
}

	if($continuar)
	{

		$evento="Revisa Listado de Cobranza a XLS de sede: $sede id_carrera: $id_carrera";
		REGISTRA_EVENTO($evento);
		
		$array_niveles_serializado=base64_encode(serialize($array_niveles));
	
		if($id_carrera!=="0"){ $condicion_carrera="alumno.id_carrera='$id_carrera' AND";}
		else{ $condicion_carrera="";}
		
		if($year_ingreso!=="0"){ $condicion_ingreso=" AND alumno.ingreso='$year_ingreso'";}
		else{ $condicion_ingreso="";}
		
		if($year_cuotas!=="0"){ $condicion_year_cuota=" AND letras.ano='$year_cuotas'";}
		else{ $condicion_year_cuota="";}
		
		if($jornada!=="0"){ $condicion_jornada=" AND alumno.jornada='$jornada'";}
		else{ $condicion_jornada="";}
		
		if($grupo!=="0"){ $condicion_grupo=" AND alumno.grupo='$grupo'";}
		else{ $condicion_grupo="";}
		
		$condicion_fecha_corte=" AND letras.fechavenc<='$fecha_corte'";
		
		$inicio_ciclio=true;
		$niveles="";
		if(count($array_niveles)>0)
		{
			if(is_array($array_niveles))
			{
				foreach($array_niveles as $nn=>$valornn)
				{
					$valornn=mysqli_real_escape_string($conexion_mysqli, $valornn);
					if($inicio_ciclio)
					{ 
						$niveles.="'$valornn'";
						$inicio_ciclio=false;
					}
					else
					{ $niveles.=", '$valornn'";}
				}
			}
			else{ $niveles="'sin nivel'";}
			$condicion_nivel="AND alumno.nivel IN($niveles)";
		}
		else{$condicion_nivel="";}
		
		$condicion_cuota=" AND letras.pagada IN('N', 'A')";
		
		
		//----------------------------------SELECCION de Alumnos y llenado de array-----------------------------------------------//
		$cons_MAIN="SELECT DISTINCT(idalumn) FROM letras INNER JOIN alumno ON letras.idalumn=alumno.id WHERE $condicion_carrera alumno.sede='$sede' $condicion_ingreso $condicion_jornada $condicion_nivel $condicion_grupo $condicion_year_cuota $condicion_cuota $condicion_fecha_corte ORDER by alumno.id_carrera, alumno.apellido_P, alumno.apellido_M";
		if(DEBUG){ echo"---> $cons_MAIN<br>";}
		$sqli_M=$conexion_mysqli->query($cons_MAIN)or die("MAIN ".$conexion_mysqli->error);
		$num_alumnos=$sqli_M->num_rows;
		if(DEBUG){ echo"Total alumno encontrados: $num_alumnos<br>";}
		
		if($num_alumnos>0)
		{
			$aux=0;
			while($D=$sqli_M->fetch_row())
			{
				$aux_id_alumno=$D[0];
				$aux++;
				//-----------------------------------//
				$cons_A="SELECT * FROM alumno WHERE id='".$aux_id_alumno."' LIMIT 1";
				$sqli_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
				$DA=$sqli_A->fetch_assoc();
					
					$A_id_alumno=$DA["id"];
					$A_rut=$DA["rut"];
					$A_nombre=$DA["nombre"];
					$A_apellido_P=$DA["apellido_P"];
					$A_apellido_M=$DA["apellido_M"];
					$A_year_ingreso=$DA["ingreso"];
					$A_nivel=$DA["nivel"];
					$A_id_carrera=$DA["id_carrera"];
					$A_nombre_carrera=NOMBRE_CARRERA($A_id_carrera);
					$A_jornada=$DA["jornada"];
					$A_img=$DA["imagen"];
					$A_sede=$DA["sede"];
					$A_fono=$DA["fono"];
					$A_fono_2=$DA["fonoa"];
					$A_email=$DA["email"];
					
					
					$aplicar_intereses=$DA["aplicar_intereses"];
					$aplicar_gastos_cobranza=$DA["aplicar_gastos_cobranza"];
					
					if($aplicar_intereses==1){$aplicar_intereses=true;}
					else{ $aplicar_intereses=false;}
					
					if($aplicar_gastos_cobranza==1){$aplicar_gastos_cobranza=true;}
					else{ $aplicar_gastos_cobranza=false;}
					
					if($aplicar_intereses){ $info_interes=" Intereses: Si";}
					else{ $info_interes="Intereses: No";}
					
					if($aplicar_gastos_cobranza){ $info_interes.=" Gastos: Si";}
					else{ $info_interes.="Gastos: NO";}
					
					
				$sqli_A->free();	
					
					$ARRAY_ALUMNO[$aux_id_alumno]["id_carrera"]=$A_id_carrera;
					$ARRAY_ALUMNO[$aux_id_alumno]["yearIngresoCarrera"]=$A_year_ingreso;
					$ARRAY_ALUMNO[$aux_id_alumno]["sede"]=$A_sede;
					$ARRAY_ALUMNO[$aux_id_alumno]["correoEnviado"]=false;
					


					
			}
		}
		$sqli_M->free();
	}
	else
	{
		if(DEBUG){ echo"No continuar<br>";}
	}
	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php");?>
<title>Carta Aviso cobranza - Alumno</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:68px;
	z-index:1;
	left: 5%;
	top: 152px;
}
</style>
</head>	
<body>
<h1 id="banner">Administrador - Envio Carta Cobranza <?php echo date("d/m/Y H:i:s");?></h1>
<div id="link"><br />
<a href="#" class="button" onclick="window.print();">Imprimir </a></div>
<div id="apDiv1">
  <table width="100%" align="center">
<thead>
    <tr>
   	 <th colspan="8">Listado Alumnos Seleccionados<br /> Carrera: <?php echo NOMBRE_CARRERA($id_carrera);?>
					</th>
    </tr>
</thead>
<tbody>
<tr>
<td>N</td>
<td>Rut</td>
<td>Nombre</td>
<td>Apellidos</td>
<td>Nivel</td>
<td>Carrera</td>
<td>Email</td>
<td>Estado</td>
</tr>
<?php
	
	//genero boletin
	if(count($ARRAY_ALUMNO)>0){
	$mostrar_solo_uno_para_debug=true;
	$fecha_actual=date("Y-m-d");
	$num_email_enviados=0;
	//datos usuario enviar
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$cons_DU="SELECT email FROM personal WHERE id='$id_usuario_actual' LIMIT 1";
	$sqli_DU=$conexion_mysqli->query($cons_DU)or die($conexion_mysqli->error);
		$UA=$sqli_DU->fetch_assoc();
		$UA_email=$UA["email"];
	$sqli_DU->free();	
	//----------------------------------------------------------------//
	///se envia una copia de cada mensaje a
	$enviar_mail_CCO=true;
	$email_destino_copia_oculta="efernandez@cftmassachusetts.cl";
	//----------------------------------------------------------------//
	/////Registro ingreso///	 
	if(DEBUG){ var_dump($_POST);}
	
	//----------------------------------------------//
	
	$remite="no_responder@cftmass.cl";
	$nombre_remite="Robot C.F.T. Massachusetts";
	$asunto="Carta Cobranza - CFT Massachusetts";
	////////////
	
	
	 $evento="Envio de Email Masivo Boletin Informativo a Alumnos sede: $sede id_carrera: $id_carrera ASUNTO: $asunto";
	 REGISTRA_EVENTO($evento);
	 
	 $mes_actual=abs(date("m"));
	$mes_actual_label=$mes_actual;
	$fecha=date("d")." de ".$mes_actual_label." del ".date("Y");
	$DIRECCION["Talca"]="3 Sur #1068";
	$DIRECCION["Linares"]="O'Higgins #313";
	
	$cuenta_alumno=0;
	foreach($ARRAY_ALUMNO as $auxIdAlumno => $auxArraydatos){
		$auxAlumno=new ALUMNO($auxIdAlumno);
		$registrarCobranza=false;
		
		
			$id_alumno=$auxAlumno->getIdAlumno();
			$rut_alumno=$auxAlumno->getRut();
			$nombre=$auxAlumno->getNombre();
			$apellidos=$auxAlumno->getApellido_P()." ".$auxAlumno->getApellido_M();
			$emailAlumno=$auxAlumno->getEmail();
			//$emailAlumno=$email_destino_copia_oculta;
			

			$id_carrera_alumno=$auxArraydatos["id_carrera"];
			$sedeAlumno=$auxArraydatos["sede"];
		
			
			
			
			$cons_cuotas="SELECT * FROM letras WHERE idalumn='$id_alumno' AND anulada='N' AND pagada <>'S' $condicion_year_cuota ORDER by id";

			$sqli_cuotas=$conexion_mysqli->query($cons_cuotas);
			$num_cuotas=$sqli_cuotas->num_rows;
			if(DEBUG){ echo"cuotas-> $cons_cuotas<br>Num Cuotas: $num_cuotas<br>";}
			$deuda_alumno=0;
			$aux1=0;
			while($Cx=$sqli_cuotas->fetch_assoc())
			{
				$aux1++;
				$Cx_deuda=$Cx["deudaXletra"];
				$Cx_fechavenc=$Cx["fechavenc"];
				$Cx_year=$Cx["ano"];
				
				$time_fecha_vencimiento=strtotime($Cx_fechavenc);
				$time_fecha_actual=strtotime($fecha_actual);
				
				
				if($time_fecha_actual>=$time_fecha_vencimiento)
				{ $estado_cuota="Morosa";}
				else{ $estado_cuota="Pendiente";}
				if(DEBUG){ echo"fecha Actual $time_fecha_actual: fecha vencimiento: $time_fecha_vencimiento ---> condicion: $estado_cuota<br>";}
				
				$deuda_alumno+=$Cx_deuda;
	
			}
			$sqli_cuotas->free();
		
			//----------------------------------------------------------------------------//
			
			//////------------------------/correo/------------------//////////
			$cuerpo='<img src="http://intranet.cftmassachusetts.cl/BAses/Images/logo_cft.jpg" width="150" height="120" alt="logo" /><br><br>';
			$cuerpo.=utf8_decode('Sr(ita) <b>'.$nombre.' '.$apellidos.'</b><br>Carrera: '.NOMBRE_CARRERA($id_carrera_alumno).'<br><br>');
			$cuerpo.='Estimado(a) Alumno(a)<br><br>';
			$cuerpo.='Junto con saludar, nos ponemos en contacto con Ud. por las mensualidades establecidas en su contrato de '.utf8_decode("prestación").' de servicios con CFT Massachusetts.<br><br>';
			$cuerpo.=utf8_decode('El Motivo de la comunicacion es que hasta la fecha no hemos recibido el pago de las cuotas pendientes ('.$num_cuotas.' Cuotas), por lo que le solicitamos cancelar lo antes posible')."<br><br>";
			
			$cuerpo.=utf8_decode('El no cumplimiento de los compromisos adquiridos en la fecha señalada, nos faculta para realizar las cobranzas notarial por el monto total del pagaré').", ".utf8_decode("además")." recuerde que el atraso en las cuotas genera intereses y gastos de cobranza.<br>";
			$cuerpo.=utf8_decode('Para conocer más detalles debe acercarse a nuestro Departamento de Finanzas o comunicarse al (071) 2 225713.')."<br><br>";
			$cuerpo.=utf8_decode('*<strong>IMPORTANTE:</strong> si al recibo de la presente ud a regularizado esta situación, rogamos hacer caso omiso de la misma')."<br><br>";
			$cuerpo.='<br> <br>
		Recuerde, para sus consultas puede escribirnos a nuestro correo '.utf8_decode("electrónico").' finanzas_talca@cftmass.cl o llamar al fono 71-2225713<br><br><a href="http://www.cftmass.cl">www.cftmass.cl</a><br><br><tt>Correo generado Automaticamente por favor no responder.<br>Fecha de envio '.date("d-m-Y").' a las '.date('H:i:s').'hrs.</tt></p>';
			
			
			
			
			
					
					if(empty($num_cuotas)){ $num_cuotas=0;}
					if($num_cuotas>0){$mostrar_alumno=true; if(DEBUG){echo"Utilizar Alumno<br>";}}
					else{ $mostrar_alumno=false; if(DEBUG){echo"No utilizar Alumnos<br>";}}
					
					
					
					if($mostrar_alumno)
					{
						
						if($mostrar_solo_uno_para_debug)
						{$mostrar_solo_uno_para_debug=false; if(DEBUG){echo"<br><br>CUERPO: $cuerpo<br><br>";}}
						
						
						$cuenta_alumno++;
						
						echo'<tr>
							<td>'.$cuenta_alumno.'</td>
							<td>'.$rut_alumno.'</td>
							<td>'.$nombre.'</td>
							<td>'.$apellidos.'</td>
							<td></td>
							<td>'.NOMBRE_CARRERA($id_carrera_alumno).'</td>
							<td>'.$emailAlumno.'</td>';
						
						$email=new EMAIL(false, $cuerpo);
    					$email->Subject = $asunto;
						
						////copia oculta 
						
						if($enviar_mail_CCO)
						{
							$email->AddBCC($email_destino_copia_oculta);
							if(comprobar_email($UA_email))
							{$email->AddBCC($UA_email);}
						}
							
						$email_valido=comprobar_email($emailAlumno);
						if(DEBUG){ 
						if($email_valido){ $aux_condicion_email="DEBUG email OK";}
						else{ $aux_condicion_email="DEBUG email ERROR";}
						}
						else
						{
						if($email_valido)
							{
								//agrego direccion
								$email->AddAddress($emailAlumno, $nombre." ".$apellidos);
								if($email->Send())
								{
									$num_email_enviados++;
									$aux_condicion_email="enviado";
									$tipo_registro="Email Masivo";
									$descripcion="Envia carta cobranza Deuda Actual($Cx_deuda)";
									REGISTRO_EVENTO_ALUMNO($id_alumno, $tipo_registro, $descripcion);
									$registrarCobranza=true;
								}
								else
								{ $aux_condicion_email="fallo al enviar";}
							}
							else
							{$aux_condicion_email="email invalido";}
						}
							echo'<td>'.$aux_condicion_email.'</td>
							</tr>';
						$email->ClearAddresses();
						
						$registrarCobranza=true;
						
						$fecha_hora_actual=date("Y-m-d  H:i:s");
						if($registrarCobranza){
							list($A_deuda_actual, $A_intereses, $A_gastos_cobranza)=DEUDA_ACTUAL_V2($id_alumno, $fecha_actual);
	$deuda_actual_alumno=($A_deuda_actual +$A_intereses+$A_gastos_cobranza);
							
							$campos="id_alumno, id_carrera, sede, tipo, fecha, observacion, deuda_actual, year_cuota, cod_user";
							$valores="'$id_alumno', '$A_id_carrera', '$A_sede', 'email', '$fecha_hora_actual', 'envio carta cobranza automatico', '$deuda_actual_alumno', '$Cx_year', '$id_usuario_actual'";
							$cons_IN="INSERT INTO cobranza ($campos) VALUES ($valores)";
							
							if(DEBUG){ echo"---> $cons_IN<br>";}
							else
							{
								if($conexion_mysqli->query($cons_IN))
								{ 
									$error="C1";
									//--------------------------------------------//
									$evento="Realiza Cobranza (carta cobranza, email) a Alumno id_alumno: $id_alumno Por deuda: $deuda_actual_alumno";
									REGISTRA_EVENTO($evento);
									REGISTRO_EVENTO_ALUMNO($id_alumno, "cobranza", "Realizacion de Cobranza (carta cobranza email) por deuda actual de: $deuda_actual_alumno");
									//-----------------------------------------////
								}
							}
						}
					}
		}
		
	}
	

	$conexion_mysqli->close();

?>
<tr>
<td colspan="9">Numero de Email Enviados (<?php echo $num_email_enviados;?>)</td>
</tr>
</tbody>
</table><br />
</div>
</body>
</html>