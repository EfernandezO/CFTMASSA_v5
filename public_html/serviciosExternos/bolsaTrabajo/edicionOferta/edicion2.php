<?php
//---------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("bolsaTrabajoV1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

	//verifico los valores de llegada
	
	$idOferta=$_POST["idOferta"];
	$ftitulo=$_POST["ftitulo"];
	$txt_noticia=$_POST["txt_noticia"];
	$fechaActual=date("Y-m-d H:i:s");
	$idUsuarioActual=$_SESSION["USUARIO"]["id"];
	
	$error="BT";
	$continuar=true;
	if($continuar)
	  {
	     //si datos correctos
		 if(DEBUG){ echo"Sin Error, Comienza Carga....<br>"; var_dump($_POST);}
		 require("../../../../funciones/conexion_v2.php");
		 
		$cons="UPDATE  bolsaTrabajo SET titulo='$ftitulo', fechaGeneracion='$fechaActual', cuerpo='$txt_noticia', codUser='$idUsuarioActual' WHERE id='$idOferta' LIMIT 1";
		if(DEBUG){ echo"--> $cons<br>";}
		
		if($conexion_mysqli->query($cons))
		{$error="BT2";}
		else
		{$error="BT3"; echo $conexion_mysqli->error;}
	  }
	  $conexion_mysqli->close();
	  if(DEBUG){ echo"error: $error<br>";}
	  else{header("location: ../gestionOfertas.php?error=$error");}
?>