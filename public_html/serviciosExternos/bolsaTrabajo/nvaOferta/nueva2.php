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
	
	$ftitulo=$_POST["ftitulo"];
	$txt_noticia=$_POST["txt_noticia"];
	$fechaActual=date("Y-m-d H:i:s");
	$idUsuarioActual=$_SESSION["USUARIO"]["id"];
	
	$error=0;
	$continuar=true;
	if($continuar)
	  {
	     //si datos correctos
		 if(DEBUG){ echo"Sin Error, Comienza Carga....<br>"; var_dump($_POST);}
		 require("../../../../funciones/conexion_v2.php");
		 
		$cons="INSERT INTO bolsaTrabajo (titulo, fechaGeneracion, cuerpo, codUser)  Values('$ftitulo', '$fechaActual', '$txt_noticia', '$idUsuarioActual')";
		if(DEBUG){ echo"--> $cons<br>";}
		
		if($conexion_mysqli->query($cons))
		{$error=0;}
		else
		{$error=1; echo"ERROR.: ". $conexion_mysqli->error;}
	  }
	  $conexion_mysqli->close();
	  if(DEBUG){ echo"error: $error<br>";}
	  else{header("location: ../gestionOfertas.php?error=$error");}
?>