<?php
@session_start();
if($_SESSION){@session_destroy(); @session_start();}

$codigo_aleatorio=md5("cftmassa".microtime()); 
 //-------------------------------------------------//
 	require("../funciones/conexion_v2.php");
	require("../funciones/funciones_sistema.php");
	require("../funciones/VX.php");
//-------------------------------------------------//	
 $id_usuario=325;///usuario con el que se ejecutara el script
 $_SESSION["autentificado"]= "SI";
 ///////Cambio forma de sesion y agrego mas datos 09-2010 by acx////
  $consulta="SELECT id, rut, nick, nombre, apellido_P, apellido_M, clave, sede, nivel, organizacion FROM personal WHERE con_acceso='ON' AND id='$id_usuario' LIMIT 1";
   $resultado=$conexion_mysqli->query($consulta)or die($conexion_mysqli->error);
   $num_reg=$resultado->num_rows;
   if(DEBUG){ echo"Autentificando Usuario <br> \n-->$consulta <br> \n N. $num_reg <br> \n";}
   if($num_reg>0)
   {
		  $col=$resultado->fetch_assoc();  
			
			   $idX=$col["id"];
			   $rutX = $col["rut"];
			   $nickX=$col["nick"];
			   $nombreX=$col["nombre"];
			   $apellidoX=$col["apellido_P"]." ".$col["apellido_M"];
			   $passX = $col["clave"];
			   $nivelX= $col["nivel"];
			   $sedeX=$col["sede"];
			   $organizacionX=$col["organizacion"];
			   
		$resultado->free();	
			   
		  $tipocuenta="admi_total";
		 $_SESSION["USUARIO"]["id"]=$idX;
		 $_SESSION["USUARIO"]["rut"]=$rutX;
		 $_SESSION["USUARIO"]["nick"]=$nickX;
		 $_SESSION["USUARIO"]["nombre"]=$nombreX;
		 $_SESSION["USUARIO"]["apellido"]=$apellidoX;
		 $_SESSION["USUARIO"]["privilegio"]=$tipocuenta;
		 $_SESSION["USUARIO"]["autentificado"]=true;
		 $_SESSION["USUARIO"]["sede"]=$sedeX;
		 $_SESSION["USUARIO"]["tipo"]="funcionario";//los que cargo que la tabla personal
		 $_SESSION["USUARIO"]["organizacion"]=$organizacionX;//agregado util externos
		 $_SESSION["USUARIO"]["session_autorizacion"]=$codigo_aleatorio;
		 $_SESSION["SISTEMA"][$codigo_aleatorio]=md5("CFTMASSA");
		 ///////////////////////////////////////////////////////////////////
		 /////Registro ingreso///
		
		 $evento="inicio sesion";
		 REGISTRA_EVENTO($evento);
		 ///////////////////////
		 //cambio estado_conexion USER-----------
		 CAMBIA_ESTADO_CONEXION($idX, "on");
		 
		 //var_dump($_SESSION);
   }
   else
   {
	   $resultado->free();	
	  echo"Error Autentificacion Usuario...<br> \n ";
	   exit();
	}
 

?>