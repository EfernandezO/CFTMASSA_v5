<?php
session_start();
define("DEBUG", false);
$acceso=false;
sleep(2.1);
$encontrado=0;
///------------------------------------//
$desde_url_permitida=true;
$bloqueo_sitio=false;
$filtrar_URL=true;
$usar_token=false;
//--------------------------------------//
$validador=$_POST["validador"];
$comparador=md5("adminX".date("d-m-Y"));
$puerto="";
//////////////////
//origen
$array_url_permitidas=array("http://186.10.233.98".$puerto."/~cftmassa/Administrador/index.php",
							"http://186.10.233.98".$puerto."/~cftmassa/Administrador/",
							"http://186.10.233.98".$puerto."/~cftmassa/Administrador/index.php",
							"http://186.10.233.98".$puerto."/~cftmassa/Administrador/",
							"http://localhost".$puerto."/CFTMASSA_V4/public_html/Administrador/index.php",
							"http://localhost".$puerto."/CFTMASSA_V4/public_html/Administrador/",
							"http://www.cftmass.cl".$puerto."/version_2/acceso.php",
							"http://cftmass.cl".$puerto."/version_2/acceso.php?error=AD1",
							"http://cftmassachusetts.cl".$puerto."/~cftmassa/Administrador/index.php",
							"https://cftmassachusetts.cl".$puerto."/~cftmassa/Administrador/index.php",
							"http://intranet.cftmassachusetts.cl".$puerto."/Administrador/index.php",
							"https://intranet.cftmassachusetts.cl".$puerto."/Administrador/index.php",
							"http://www.cftmass.cl/");

$url_origen=$_SERVER['HTTP_REFERER'];
$url_actual=$_SERVER['PHP_SELF'];

if(DEBUG){ var_dump($_POST); echo"<br>URL origen: $url_origen<br>"; }
$array_url=explode("?",$url_origen);
$url_origen_1=$array_url[0];
if(DEBUG){ echo"URL BASE: $url_origen_1<br>Url_actual: $url_actual<br>";}
//////////////////
//-----------------------------------------//
if($filtrar_URL)
{
	if(DEBUG){ echo"<strong>Filtrar URL origen</strong> ($url_origen_1)<br>";}
	if(in_array($url_origen_1,$array_url_permitidas))
	{ $desde_url_permitida=true; if(DEBUG){ echo"URL PERMITIDA<br>";}}
	else{ $desde_url_permitida=false; if(DEBUG){ echo"URL NO permitida<br>";}}
}
else
{ if(DEBUG){ echo"NO Filtrar URL origen<br>";}$desde_url_permitida=true;}
//-----------------------------------------//
if($usar_token)
{
	$sesion_token=$_SESSION["SISTEMA"]["token"];
	$token=$_POST["token"];
	if(DEBUG){ echo"Usar Token<br>Sesion Token: $sesion_token<br>token: $token<br>";}
	
	if($sesion_token==$token)
	{
		if(DEBUG){ echo"Token Correcto<br>";}
		$acceso_token=true;
	}
	else
	{
		if(DEBUG){ echo"Token Incorrecto<br>";}
		$acceso_token=false;
	}
}
else
{ if(DEBUG){ echo"No usar Token<br>";} $acceso_token=true;}



//---------------------------------------------------//
if($validador==$comparador)
{ $acceso=true;}
//-------------------------------------------------///
if(($acceso)and($desde_url_permitida)and($acceso_token))
{
	
	//var_dump($_POST);
	$ventana_nueva=true;
	$frut=strtoupper($_POST["frut"]);
	$fclave=$_POST["fclave"];
  
   require('../../funciones/conexion_v2.php');
   
   //-------------------------------------------//
   if(DEBUG){ echo"datos entrada: RUT: |$frut|  clave: |$fclave|<br>";}
		$frut=mysqli_real_escape_string($conexion_mysqli, $frut);
		//$fclave=mysqli_real_escape_string($conexion_mysqli, $fclave);
		$fclave=md5($fclave);
	  if(DEBUG){ echo"codificada clave: |$fclave|<br>";}	
   //-------------------------------------------//
   $consulta="SELECT id, rut, nick, nombre, apellido_P, apellido_M, clave, sede, nivel, organizacion FROM personal WHERE con_acceso='ON'";
   $resultado=$conexion_mysqli->query($consulta);
	$num_usuarios=$resultado->num_rows;
	if(DEBUG){ echo"Num usuario: $num_usuarios<br>";}
	
    while($col=$resultado->fetch_assoc())   
    {
	   $idX=$col["id"];
       $rutX = $col["rut"];
	   $nickX=$col["nick"];
	   $nombreX=$col["nombre"];
	   $apellidoX=$col["apellido_P"]." ".$col["apellido_M"];
       $passX = $col["clave"];
	   $nivelX= $col["nivel"];
	   $sedeX=$col["sede"];
  	   $organizacionX=$col["organizacion"];
	  
  
  		
     if(((($frut==$rutX)and($fclave==$passX))and($frut!=""))and($nivelX >= 2))
      {
	  	if(DEBUG){echo"Cumple Condicion -<br>";}
	     switch($nivelX)
		 {
		      case 2:
			         $tipocuenta="admi";
					 $id_rol=2;
					 break;
			  case 3:
			       	 $tipocuenta="finan";
					  $id_rol=3;
					 break;
			  case 4:
			         $tipocuenta="admi_total";
					  $id_rol=4;
					 break;	
			  case 5:
					 $tipocuenta="matricula";	 
					  $id_rol=5;
					 break;
			  case 6:
			 		$tipocuenta="inspeccion";
					 $id_rol=6;		 
					break;
			  case 7:
			  		$tipocuenta="externo";	
					 $id_rol=7;	 
					break;		
				default:
				$url_destino=$url_origen_1."?error=AD1";
				if(DEBUG){ echo"privilegio incorrecto $url_destino<br>";}
				else{header("location: $url_destino");}
		}
		
         $encontrado=1;
		 break;
		 
      }
	 }
	$resultado->free();
	// @mysql_close($conexion);
	 $conexion_mysqli->close();
	if(DEBUG){echo"----> $encontrado<br>";}
	  if($encontrado>=1)
	  {
		  if($bloqueo_sitio)
		  {header("location: ../OKALIS/msj_error/sitio_bloqueado.php");}
		  else
		  {
			 $codigo_aleatorio=md5("cftmassa".microtime()); 
			// session_start();
			 
			 //-------------------------------------------------//
			 $_SESSION["autentificado"]= "SI";
			 ///////Cambio forma de sesion y agrego mas datos 09-2010 by acx////
			 $_SESSION["USUARIO"]["id"]=$idX;
			 $_SESSION["USUARIO"]["rut"]=$frut;
			 $_SESSION["USUARIO"]["nick"]=$nickX;
			 $_SESSION["USUARIO"]["nombre"]=$nombreX;
			 $_SESSION["USUARIO"]["apellido"]=$apellidoX;
			 $_SESSION["USUARIO"]["privilegio"]=$tipocuenta;
			 $_SESSION["USUARIO"]["id_rol"]=$id_rol;
			 $_SESSION["USUARIO"]["autentificado"]=true;
			 $_SESSION["USUARIO"]["sede"]=$sedeX;
			 $_SESSION["USUARIO"]["tipo"]="funcionario";//los que cargo que la tabla personal
			 $_SESSION["USUARIO"]["organizacion"]=$organizacionX;//agregado util externos
			 $_SESSION["USUARIO"]["session_autorizacion"]=$codigo_aleatorio;
			 $_SESSION["SISTEMA"][$codigo_aleatorio]=md5("CFTMASSA");
			 ///////////////////////////////////////////////////////////////////
			 /////Registro ingreso///
			 include("../../funciones/VX.php");
			 $evento="inicio sesion";
			 REGISTRA_EVENTO($evento);
			 ///////////////////////
			 //cambio estado_conexion USER-----------
			 CAMBIA_ESTADO_CONEXION($idX, "on");
			 /////----------------------------------
			 switch($tipocuenta)
			 {
				  case "admi":
					   //nivel de administrador 2
					   $_SESSION["privilegio"]="admi";
					   header("Location: ADmenu.php");
					   break;
				  case "finan":	
					   $_SESSION["privilegio"]="finan";   
					   header ("Location: ../contabilidad/index.php");
					   break;
				  case "admi_total":
					   $_SESSION["privilegio"]="admi_total";
					   header("Location: ADmenu.php");
					   break;
				  case "matricula":
						$_SESSION["privilegio"]="matricula";
						header("location: menu_matricula/index.php");
						break;
				 case "inspeccion":
						$_SESSION["privilegio"]="inspeccion";
						header("location: menu_inspeccion/index.php");
						break;	
				case "externo":
						$_SESSION["privilegio"]="externo";
						header("location: menu_externos/index.php");
						break;	
				 default:
					session_destroy();
					$url_destino=$url_origen_1."?error=AD1";
					if(DEBUG){ echo"tipo cuenta incorrecto $url_destino<br>";}
					else{header("location: $url_destino");}		   
			}
		  }//fin else bloqueo sitio
	  
	  }
	  else
	  {
		  if(($url_actual==$url_origen)or(empty($url_origen_1)))
		  {$url_destino="../index.php";}
		  else
		  {$url_destino=$url_origen_1."?error=AD1";}
		  if(DEBUG){ echo"usuario No encontrado -> $url_destino<br>";}
			else{header("location: $url_destino");}
	  }
	}	
else
{
	 if(($url_actual==$url_origen)or(empty($url_origen_1)))
	  {$url_destino="../index.php";}
	  else
	  {$url_destino=$url_origen_1."?error=AD1";}
	if(DEBUG){ echo"Sin Acceso<br>URL: $url_destino<br>";}
	else{header("location: $url_destino");}
}
?>