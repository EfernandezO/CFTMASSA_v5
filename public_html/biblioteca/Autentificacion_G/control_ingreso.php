<?php
session_start();
define("DEBUG", false);
$acceso=false;
if(isset($_SESSION["acceso_biblio"]))
{ $acceso_biblioteca=$_SESSION["acceso_biblio"];}
else{ $acceso_biblioteca="";}
//si ya cuenta con acceso a biblio
//var_dump($_POST);
if($acceso_biblioteca=="SI")
{
	//lo redirijo a otro lado
	if(DEBUG){echo "ya autentificado<br>";}
	$url="enrutador.php";
	header("location: $url");	
}
$validador=$_POST["validador"];
$comprobador=md5("Massa_".date("d-m-Y"));

if(($validador==$comprobador))
{
	$acceso=true;
	if(DEBUG){echo"validador OK<br>";}
}
//si hay envio de formulario y el validador coincide proceso
if(($_POST)&&($acceso))
{
	sleep(2);
	include("../../../funciones/conexion.php");
	include("../../../funciones/funcion.php");
	//limpieza datos
	$url_pdf=base64_decode($_POST["url_pdf"]);
	if(DEBUG){echo "------> $url_pdf<br>";}
	$usuario=mysql_real_escape_string(str_inde($_POST["usuario"],""));
	$pass=mysql_real_escape_string(str_inde($_POST["pass"],""));
	$tipo_cuenta=str_inde($_POST["tipo_cuenta"],"");
	
	//verificancion de datos
	if(($usuario=="")or($pass==""))
	{$error=1;}
	else
	{
		//datos procesables
		switch ($tipo_cuenta)
		{
			case "Alumno":
				$tabla="alumno";
				$condicion="rut='$usuario' AND clave='$pass'";
				$consultar=true;
				break;
			case "Docente":
				$tabla="personal";
				$condicion="rut='$usuario' AND clave='".md5($pass)."'";
				$consultar=true;
				break;
			case "Administrativo":		
				$tabla="personal";
				$condicion="rut='$usuario' AND clave='".md5($pass)."'";
				$consultar=true;
				break;
			default:
				$consultar=false;	
		}
		if(DEBUG){echo"--->$tipo_cuenta<br>";}
		
		if($consultar)
		{
			$cons="SELECT  rut, clave FROM $tabla WHERE $condicion";
			if(DEBUG){echo"$cons<br>";}
			$sql=mysql_query($cons)or die(mysql_error());
			$D=mysql_fetch_assoc($sql);
			$num_coincidencias=mysql_num_rows($sql);
			if($num_coincidencias>=1)
			{
				$_SESSION["acceso_biblio"]="SI";
				if(DEBUG){echo "sesion-----> $_SESSION[acceso_biblio]<br>";}
				$error=0;
				if(DEBUG){echo"$num_coincidencias<br>";}
			}
			else
			{
				//no encontrado en tabla
				$error=1;
				if(DEBUG){echo"No encontrado<br>";}
			}
			mysql_free_result($sql);
			mysql_close($conexion);
		//-------***------------
		}
		else
		{ $error=1;}
		
		
	}
		if($error==0)
		{
			//redirijir a pagina anteriormente no visible
				$url_pdf=base64_encode($url_pdf);
				if(DEBUG){echo "Encontrado Redirijir a $url_pdf";}
				else{header("location: enrutador.php?url_pdf=$url_pdf");}
		}
		else
		{
			//redirijir a pagina de autentificacion biblio + error
			
			$url_pdf=str_replace("../visor_pdf/","",$url_pdf);
			$url_pdf=base64_encode($url_pdf);
			if(DEBUG){echo"hay error $error<br>";}
			else{header("location: index.php?url=$url_pdf&error=$error");}
			
		}

}
else
{
	//no post
	if(DEBUG){echo"no post";}
	else{header("location: ../index.php");}
}
?>