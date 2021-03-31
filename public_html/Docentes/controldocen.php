<?php
define("DEBUG", false);
//-----------------------------------//
$year_actual=date("Y");
$mes_actual=date("m");
if($mes_actual>=8){ $semestre_actual=2;}
else{ $semestre_actual=1;}


$acceso=false;
$desde_url_permitida=false;
$validador=$_POST["validador"];
sleep(2.1);
$comparador=md5("docenteX".date("d-m-Y"));
if(DEBUG){ echo"V--->$validador<br>C--->$comparador<br>";}
////////----------------------------------------------//////////
$rolJefeCarreraActivo=true; //se permite autentificar como jefe de carrera, sino solo como docente
$filtrar_url_origen=true; //filtrar o no url origen
//origen
$array_url_permitidas=array("http://localhost/CFTMASSA_V3/public_html/Administrador/index.php",
							"http://186.10.233.98/~cftmassa/Docentes/index.php",
							"http://186.10.233.98/~cftmassa/Docentes/",
							"http://www.cftmass.cl/version_2/acceso.php",
							"http://www.cftmass.cl/version_2/acceso.php?error=D1",
							"http://186.10.233.98/~cftmassa/Administrador/index.php",
							"http://186.10.233.98/~cftmassa/Administrador/",
							"https://186.10.233.98/~cftmassa/Administrador/",
							"https://186.10.233.98/~cftmassa/Administrador/index.php",
							"http://cftmassachusetts.cl/~cftmassa/Administrador/index.php",
							"http://www.cftmassachusetts.cl/~cftmassa/Administrador/index.php",
							"https://cftmassachusetts.cl/~cftmassa/Administrador/index.php",
							"https://www.cftmassachusetts.cl/~cftmassa/Administrador/index.php",
							"http://cftmassachusetts.cl/Administrador/index.php",
							"http://www.cftmassachusetts.cl/Administrador/index.php",
							"https://intranet.cftmassachusetts.cl/Administrador/index.php",
							"http://www.cftmass.cl/",
							"http://intranet.cftmassachusetts.cl/Administrador/index.php",
							"https://intranet.cftmassachusetts.cl/Administrador/index.php");
							
//------------------------------------------------------------///
$url_origen = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'http://intranet.cftmassachusetts.cl/Administrador/index.php';
if(DEBUG){ echo"URL origen: $url_origen<br>"; }
$array_url=explode("?",$url_origen);
$url_origen_1=$array_url[0];
if(DEBUG){ echo"URL BASE: $url_origen_1<br>";}
//////////////////

if($validador==$comparador)
{ $acceso=true;}

if($filtrar_url_origen)
{
	if(in_array($url_origen_1,$array_url_permitidas))
	{ $desde_url_permitida=true; if(DEBUG){ echo"URL PERMITIDA<br>";}}
	else{ $desde_url_permitida=false; if(DEBUG){ echo"URL NO permitida<br>";}}
}
else{ $desde_url_permitida=true; if(DEBUG){ echo"No filtrar URL origen<br>";}}

$array_url=explode("?",$url_origen);
$url_origen_1=$array_url[0];
if(DEBUG){ echo"URL BASE: $url_origen_1<br>";}

if(($acceso)and($desde_url_permitida))
{
  require('../../funciones/conexion_v2.php');
  require('../../funciones/funciones_sistema.php');
  
  
  $frut=strtoupper($_POST["frut"]);
  $frut=str_replace(".","",$frut);
  
  $fclave=$_POST["fclave"];
  $fclave=str_replace(".","",$fclave);
  
  $fclave=trim(mysqli_real_escape_string($conexion_mysqli, $fclave));
  $frut=trim(mysqli_real_escape_string($conexion_mysqli, $frut));
  
  $fclave=md5($fclave);
   
   $consulta="SELECT id, rut, nombre, apellido, apellido_P, apellido_M, clave, sede, nivel, nick FROM personal WHERE cuenta_docente='ACTIVA' AND con_acceso='ON'";
   $resultado=$conexion_mysqli->query($consulta);
	 $encontrado=0;
    while($col=$resultado->fetch_assoc())   
    {
	   $idX=$col["id"];
       $rutX = strtoupper($col["rut"]);
       $passX = $col["clave"];
	   $nombreX= $col["nombre"];
	   $apellidoX= $col["apellido"];
	   $apellido_PX=$col["apellido_P"];
	   $apellido_MX=$col["apellido_M"];
	   $sedeX=$col["sede"];
  		$nivelX=$col["nivel"];
		$nickX=$col["nick"];
  
     if((($frut==$rutX)and($fclave==$passX))and($frut!=""))
      {
         $encontrado=1;
		 break;
		 
      }
	 }
	 $resultado->free(); 
	 //--------------------------------------------------------------// 
	  if($encontrado>=1)
	  {
		   if(DEBUG){ echo "--->$nivelX<br>";}
		  
	     session_start();
		 $codigo_aleatorio=md5("cftmassa".microtime()); 
		 $_SESSION["USUARIO"]["id"]=$idX;
		 $_SESSION["USUARIO"]["rut"]=$frut;
		 $_SESSION["USUARIO"]["clave"]=$fclave;
	     $_SESSION["USUARIO"]["autentificado"]= true;
		 $_SESSION["USUARIO"]["nombre"]=$nombreX;
		 $_SESSION["USUARIO"]["apellido"]=$apellido_PX." ".$apellido_MX;
		 $_SESSION["USUARIO"]["sede"]=$sedeX;
		 $_SESSION["USUARIO"]["tipo"]="funcionario";
		 $_SESSION["USUARIO"]["nick"]=$nickX;
		 $_SESSION["USUARIO"]["session_autorizacion"]=$codigo_aleatorio;
		 $_SESSION["SISTEMA"][$codigo_aleatorio]=md5("CFTMASSA");//para seguridad
		 
		 //verifico si ahora tien jefatura
		 //doy privilegio segun asignaciones del periodo semestre year
		 $tiene_jefatura=false;
		 if($rolJefeCarreraActivo){
			 list($tiene_jefatura, $array_carrera_jefatura)=ES_JEFE_DE_CARRERA($idX, $semestre_actual, $year_actual, $sedeX);
		 }
				
				if($tiene_jefatura){$aux_privilegio="jefe_carrera"; $id_rol=8;}
				else{ $aux_privilegio="Docente"; $id_rol=1;}
				 $_SESSION["USUARIO"]["privilegio"]=$aux_privilegio;
				 $_SESSION["USUARIO"]["id_rol"]=$id_rol;
			//------------------------------------------------------------//
		
		  require('../../funciones/VX.php');
		  $evento="Ingreso Cuenta Docente:[$idX] $nombreX $apellido_PX";
		  REGISTRA_EVENTO($evento);
		 $conexion_mysqli->close(); 
		 
		 
	    if(DEBUG){ echo"FIN<br>"; var_dump($_SESSION["USUARIO"]);}
		else{ header ("Location: okdocente.php");}
	  
	  }
	  else
	  {
		 $conexion_mysqli->close(); 
	  	if(isset($_SESSION["USUARIO"])){unset($_SESSION["USUARIO"]);}
	    $url_destino=$url_origen_1."?error=D1";
		if(DEBUG){ echo"usuario No Encontrado<br>$url_destino<br>";}
		else{header("Location: $url_destino");}
	  }
}
else
{
	if(DEBUG){ echo"sin Acceso...<br>$url_origen_1";}
	else{header("location: $url_origen_1");}
}
?>