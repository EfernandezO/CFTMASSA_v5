<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Envio_mail_bienvenida_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$comparador=md5("bienvenida".date("d/m/Y"));
if(isset($_POST["verificador"]))
{ $verificador=$_POST["verificador"];}
else{ $verificador="";}

if($verificador==$comparador)
{ $acceso=true;}
else
{ $acceso=false;}

if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	$hay_alumno_activo=$_SESSION["SELECTOR_ALUMNO"]["ACTIVO"];
	if(empty($hay_alumno_activo)){ $hay_alumno_activo=false;}
}
else
{
	if(DEBUG){ echo"Sin ALUMNO Seleccionado NO session:( <br>";}
}
//////////////////////////////////////////////////////////////////////
if($hay_alumno_activo)
{
	if($acceso)
	{
		var_export($_POST);
		$email_alumno=$_POST["email"];
		//$email_alumno="informatica@cftmass.cl";
		$nombre_alumno=ucwords(strtolower($_POST["nombre"]));
		$apellido_P=ucwords(strtolower($_POST["apellido_P"]));
		$apellido_M=ucwords(strtolower($_POST["apellido_M"]));
		$carrera=$_POST["carrera"];
		
		require("../../libreria_publica/PHPMailer_v5.1/class.phpmailer.php");
		/////////////////configuracion inicial/////////////////////
	  	$host="localhost";
	  	$remite="secretaria@cftmass.cl";
	  	$nombre_remite="Robot CFT Massachusetts";
	  	$asunto="Bienvenida  CFT Massachusetts";
	  	$array_destinos=array($email_alumno=>$email_alumno);
		///////////////////////////////////////////////		
			///////////////////////////////////////////
      	$mail = new PHPMailer();
     	$mail->Host = $host;
      	$mail->From = $remite;
      	$mail->FromName = $nombre_remite;
	  	$mail->Subject = $asunto;
		$mail->CharSet='UTF-8';
	  /////////////////////////////////////////////
	  //asigno destinos de mail
	  	foreach($array_destinos as $nombre => $destino)
	 	 {
	  		//echo"$destino -> $nombre <br>";
      		$mail->AddAddress($destino,$nombre);
      	}
		
		
		$body='<img src="http://200.28.135.221/~cftmassa/BAses/Images/logo_cft.jpg" alt="log" /><br><br>';
		$body='';
		$body.="<strong>Bienvenid@  a CFT Massachusetts</strong><br><br>";
      	$body.="<strong>Alumno:</strong> $nombre_alumno $apellido_P $apellido_M<br>";
		$body.="<strong>Fecha:</strong> ".date("d-m-Y")."<br>";
		$body.='<strong>Carrera: </strong>'.$carrera.'<br><br>';
		$body.='Conjuntamente con brindarle nuestra más cordial bienvenida, le adjuntamos instructivos para el ingreso a Intranet.<br><br>';
	  	$body.= "Cualquier consulta dirigirla a nuestro correo electrónico secretaria@cftmass.cl o bien en nuestra página www.cftmass.cl en la Seccion <strong>Contacto</strong>.<br><br>";
		$body.= '<img src="http://200.28.135.221/~cftmassa/BAses/Images/logo_cft.jpg" width="70" height="60" alt="logo" /><br>';
		$body.= 'Cordialmente<br>CFT Massachusetts<br>';
      	$body.= '<font color="red"><a href="http://www.cftmass.cl">cftmass.cl</a></font><br><br>';
		$body.='<hr size="1" width="100%" color="#CCCCCC">';
		$body.='<tt>Este Email es generado de forma automatica por favor no lo responda<br>CFT Massachusetts '.date("Y").'</tt>';
	  
      	$mail->Body = $body;
      	$mail->AltBody ="Bienvenida";
	  	$mail->IsHTML(true);
		
	  $mail->AddAttachment("Intranet_instructivo(alumno)V2_2.pdf", "Intranet.pdf");	
      //$mail->AddAttachment("aula_virtual_ulare_instructivo_POST_V5.pdf", "Aula_Virtual.pdf");
     // $mail->AddAttachment("intranet_instructivo_alumno_ulare_POST_V3.pdf", "Intranet_Alumno.pdf");
	  
	  if(DEBUG)
	  { echo"$email_alumno<br><br>$body<br>"; $error="debug";}
	  else
	  {
      	if($mail->Send())
	  	{
	  		$error=0;
			/////Registro EVENTO///
			include("../../../funciones/VX.php");
			$evento="Envia Mail Bienvenida ->".$_SESSION["SELECTOR_ALUMNO"]["rut"]."-".$_SESSION["SELECTOR_ALUMNO"]["carrera"];
			REGISTRA_EVENTO($evento);
	
			$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
			///registro alumno_registros
			$tipo_registro_001="notificacion";
			$descripcion_registro_001="Envio Mail Bienvenida";
			REGISTRO_EVENTO_ALUMNO($id_alumno, $tipo_registro_001, $descripcion_registro_001);
	  	}
	  	else
	 	{$error=1;}
	  }
	  
	  if(DEBUG){ echo"Error $error<br>";}
	  else
	  { header("location: envio_bienvenida_3.php?error=$error");}
		
	}
	else
	{
		if(DEBUG){ echo"Sin Acceso<br>";}
		else
		{ header("location: ../../buscador_alumno_BETA/HALL/index.php");}
	}
}
else
{
	if(DEBUG){ echo "No alumno Activo<br>";}
	else
	{ header("location: ../../buscador_alumno_BETA/HALL/index.php");}
}
?>