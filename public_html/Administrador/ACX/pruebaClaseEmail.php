<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>prueba correo</title>
</head>

<body>
<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
	require("../../libreria_publica/PHPMailer6/class_EMAIL.php");
	
	


//Now creating and sending a message becomes simpler when you use this class in your app code
try {
    //Instantiate your new class, making use of the new `$body` parameter
 	$email=new EMAIL(false, '<strong>Este es el cuerpo</strong> o no ...?');
    // Now you only need to set things that are different from the defaults you defined
    $email->addAddress('informatica@cftmass.cl', 'Elias Fernandez');
    $email->Subject = 'Prueba de Envio';
   // $mail->addAttachment(__FILE__, 'myPHPMailer.php');
    $enviado=$email->send(); //no need to check for errors - the exception handler will do it
	
	var_dump($enviado);
} catch (Exception $e) {
    //Note that this is catching the PHPMailer Exception class, not the global \Exception type!
    echo 'Caught a '. get_class($e) .': '. $e->getMessage();
}
?>
</body>
</html>