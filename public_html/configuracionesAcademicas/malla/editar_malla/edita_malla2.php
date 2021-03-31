<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->MALLAS_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_POST)
{
	$continuar=true;
	require("../../../../funciones/conexion_v2.php");
	if(DEBUG){ var_export($_POST);}
		$id_carrera=$_POST["id_carrera"];
		$id_ramo=$_POST["id_ramo"];
		$sede=$_POST["sede"];
		
		$codigo=mysql_real_escape_string($_POST["codigo"]);
		$num_posicion=mysql_real_escape_string($_POST["num_posicion"]);
		$ramo=mysql_real_escape_string($_POST["ramo"]);
		$nivel=mysql_real_escape_string($_POST["nivel"]);
		$prerequisito_1=mysql_real_escape_string($_POST["prerequisito_1"]);
		$prerequisito_2=mysql_real_escape_string($_POST["prerequisito_2"]);
		$prerequisito_3=mysql_real_escape_string($_POST["prerequisito_3"]);
		$prerequisito_4=mysql_real_escape_string($_POST["prerequisito_4"]);
		$prerequisito_5=mysql_real_escape_string($_POST["prerequisito_5"]);
		$prerequisito_6=mysql_real_escape_string($_POST["prerequisito_6"]);
		$prerequisito_7=mysql_real_escape_string($_POST["prerequisito_7"]);
		$prerequisito_8=mysql_real_escape_string($_POST["prerequisito_8"]);
		$prerequisito_9=mysql_real_escape_string($_POST["prerequisito_9"]);
		$prerequisito_10=mysql_real_escape_string($_POST["prerequisito_10"]);
		//------------------------------//
		$es_asignatura=mysql_real_escape_string($_POST["es_asignatura"]);
		
		$numero_horas_teoricas=mysql_real_escape_string($_POST["numero_horas_teoricas"]);
		$numero_horas_practicas=mysql_real_escape_string($_POST["numero_horas_practicas"]);
		
		if(!is_numeric($numero_horas_teoricas)){$numero_horas_teoricas=0;}
		if(!is_numeric($numero_horas_practicas)){$numero_horas_practicas=0;}
		//--------------------------------------//
		if(!VALIDA_CODIGO($codigo, $id_carrera, $id_ramo))
		{ $continuar=false; $error="M1";}
		if(!VALIDA_RAMO($ramo, $id_carrera, $id_ramo))
		{ $continuar=false; $error="M2";}
		
		if(!is_numeric($num_posicion)){$num_posicion=0;}
		
		if($continuar)
		{
			$cons_UP="UPDATE mallas SET num_posicion='$num_posicion', cod='$codigo', ramo='$ramo', nivel='$nivel', pr1='$prerequisito_1', pr2='$prerequisito_2', pr3='$prerequisito_3', pr4='$prerequisito_4', pr5='$prerequisito_5', pr6='$prerequisito_6', pr7='$prerequisito_7', pr8='$prerequisito_8', pr9='$prerequisito_9', pr10='$prerequisito_10', horas_teoricas='$numero_horas_teoricas', horas_practicas='$numero_horas_practicas', es_asignatura='$es_asignatura' WHERE id_carrera='$id_carrera' AND id='$id_ramo' LIMIT 1";
			if(DEBUG){ echo"UP --->$cons_UP<br>";}
			else{ mysql_query($cons_UP)or die("UP". mysql_error());}
			
			
			
			$url="../ver_malla.php?id_carrera=$id_carrera&sede=$sede&error=M0";
			if(DEBUG){ echo"FIN<br>URL: $url<br>";}
			else{ header("location: $url");}
		}
		else
		{
			$url="edita_malla1.php?id_carrera=$id_carrera&id_ramo=$id_ramo&error=$error";
			if(DEBUG){ echo"ERROR: $error<br>URL: $url<br>";}
			else{ header("location: $url");}
		}
		
		
		
		
	mysql_close($conexion);	
}
else
{ header("location: ../ver_malla.php");}
//////////*****/////////
function VALIDA_CODIGO($codigo, $id_carrera, $id_ramo)
{
	$respuesta=true;
	
	if((!is_numeric($codigo))or($codigo<=0))
	{ $respuesta=false; if(DEBUG){ echo"codigo: NO numerico o menor a cero<br>";}}
	else
	{
		$cons_BC="SELECT COUNT(cod) FROM mallas WHERE id_carrera='$id_carrera' AND id<>'$id_ramo' AND cod='$codigo'";
		$sql_BC=mysql_query($cons_BC)or die(mysql_error());
			$DX=mysql_fetch_row($sql_BC);
			$coincidencias=$DX[0];
			if(empty($coincidencias)){ $coincidencias=0;}
		if(DEBUG){ echo"--->$cons_BC<br>COINCIDENCIAS: $coincidencias<br>";}
		mysql_free_result($sql_BC);
		if($coincidencias>0)
		{ $respuesta=false;}
	}

	return($respuesta);
}
/////////***//////////
function VALIDA_RAMO($ramo, $id_carrera, $id_ramo)
{
	$respuesta=true;
	if(empty($ramo)){ $respuesta=false;}
	else
	{
		$cons_BC="SELECT COUNT(ramo) FROM mallas WHERE id_carrera='$id_carrera' AND id<>'$id_ramo' AND ramo='$ramo'";
		$sql_BC=mysql_query($cons_BC)or die(mysql_error());
			$DX=mysql_fetch_row($sql_BC);
			$coincidencias=$DX[0];
			if(empty($coincidencias)){ $coincidencias=0;}
		if(DEBUG){ echo"--->$cons_BC<br>COINCIDENCIAS: $coincidencias<br>";}
		mysql_free_result($sql_BC);
		if($coincidencias>0)
		{ $respuesta=false;}
	}
	return($respuesta);
}
?>