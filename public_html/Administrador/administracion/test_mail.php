<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	OKALIS($lista_invitados);
	define("DEBUG", true);
//-----------------------------------------//
if(isset($_GET["servidor_correo"]))
{$servidor_correo=$_GET["servidor_correo"];}
if(empty($servidor_correo)){ $servidor_correo="localhost";}
if(DEBUG){ echo"SERVIDOR DE CORREO: $servidor_correo<br>";}
require("../../libreria_publica/PHPMailer_v5.1/class.phpmailer.php");
echo"Inicio de Prueba de Envio de Email desde  Servidor [$servidor_correo]<br><br>";
$continuar=false;
$mail = new PHPMailer();
	
	switch($servidor_correo)
	{
		case"gmail":
			if(DEBUG){$mail->SMTPDebug  = 1;}
				$mail->Host = "smtp.gmail.com";  // Servidor de Salida,
				$mail->IsSMTP(); 
				$mail->Port = 587; 
				$mail->SMTPSecure = "tls";
				$mail->SMTPAuth = true; 
				$direccion_de_envio="no_responder@cftmass.cl";
				$mail->Username =$direccion_de_envio;  // Nombre de usuario del correo
				$mail->Password = "15_xXCo37"; // Contraseña
				$continuar=true;
			break;
		case"localhost":
			$mail->Host = "localhost";
			$direccion_de_envio="no_responder@cftmassachusetts.cl";
			$continuar=true;
			break;
	}
	
	// Datos del servidor SMTP
	
	if($continuar)
	{
	
		$mail->From =$direccion_de_envio;
		$mail->WordWrap = 50; 
		$mail->IsHTML(true); 
		$mail->AddCustomHeader("Precedence: bulk");
		$mail->AltBody ="Por favor active la vista HTML para visualizar correctamente el mensaje"; // optional, comment out and test
		$mail->FromName = "Robot CFT Massachusetts";
		$mail->Subject = "E-mail de Prueba desde servidor -> ".$servidor_correo;	
		$mail->Body = "Prueba de Envio ".date("d-m-Y H:i:s");
		$mail->AltBody ="Prueba de envio<br>";
		$mail->AddAddress("informatica@cftmass.cl");
		//$mail->AddBCC("contabilidad@cftmass.cl", "contabilidad@cftmass.cl");
		//$mail->AddStringAttachment($adjunto, $nombre_archivo_adjunto,'base64','application/pdf');
		var_dump($mail);						
		if($mail->Send())
		{echo"<br><strong>Email Enviado :) con servidor [$servidor_correo]</strong> ".$mail->ErrorInfo."<br>";}
		else
		{echo"<br><strong>Error al Enviar Email :( con servidor [$servidor_correo]</strong> ".$mail->ErrorInfo."<br>";}
		$mail->ClearAddresses(); 
	}
	else
	{ echo"No se puede Continuar :(<br>";}
?>