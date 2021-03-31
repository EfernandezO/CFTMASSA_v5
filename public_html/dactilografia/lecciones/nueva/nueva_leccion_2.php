<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", false);
if($_POST)
{
	include("../../../../funciones/conexion_v2.php");
	$id_usuario_activo=$_SESSION["USUARIO"]["id"];
	$fecha_generacion=date("Y-m-d");
	$visibilidad="si";
	
	if(DEBUG){ var_export($_POST);}
	$titulo=mysqli_real_escape_string($conexion_mysqli, $_POST["titulo"]);
	$descripcion=mysqli_real_escape_string($conexion_mysqli, $_POST["descripcion"]);
	$clasificacion=$_POST["clasificacion"];
	$minutos=$_POST["minutos"];
	$segundos=$_POST["segundos"];
	$nivel_exigencia=$_POST["nivel_exigencia"];
	$texto=($_POST["texto"]);
	
	$texto=FORMATEO($texto);
	
	$duracion=($minutos*60)+$segundos;
	
	$campos="titulo, descripcion, clasificacion, duracion_seg, nivel_exigencia, texto, visible, fecha_generacion, cod_user";
	$valores="'$titulo', '$descripcion', '$clasificacion', '$duracion', '$nivel_exigencia', '$texto', '$visibilidad', '$fecha_generacion', '$id_usuario_activo'";
	
	$cons_IN="INSERT INTO dactilografia_lecciones ($campos) VALUES ($valores)";
	if(DEBUG){ echo"$cons_IN<br>";}
	else
	{
		$conexion_mysqli->query($cons_IN);	
	}
	$conexion_mysqli->close();
	
	if(!DEBUG)
	{
		header("location: nueva_leccion_final.php?error=0");
	}
}
else
{header("location: nueva_leccion_1.php");}
//---------------------------------------//
function FORMATEO($texto)
{
		$texto=substr($texto,3,-4);
	$patron='\n';
	$texto=nl2br($texto);
	$texto=mysql_real_escape_string($texto);
	$texto=mysql_real_escape_string($texto);
	
	$texto=str_replace($patron,"<br>",$texto);
	$texto=str_replace("<p>","<br>",$texto);
	$texto=str_replace("</p>","<br>",$texto);
	$texto=str_replace("<br />","<br>",$texto);
	$texto=str_replace("<br /><br />","<br>",$texto);
	$texto=str_replace("<br /><br />","<br>",$texto);
	$texto=str_replace("<br /> <br />","<br>",$texto);
	$texto=str_replace("<br><br>","<br>",$texto);
	$texto=str_replace("<br> <br>","<br>",$texto);
	$texto=str_replace("&nbsp;"," ",$texto);
	//$texto=str_replace('\"','\\"',$texto);
	//$texto=str_replace("'","&#039;",$texto);
	return($texto);
}
?>