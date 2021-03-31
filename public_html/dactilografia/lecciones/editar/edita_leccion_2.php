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
	require("../../../../funciones/conexion_v2.php");
	$id_usuario_activo=$_SESSION["USUARIO"]["id"];
	$fecha_generacion=date("Y-m-d");
	$visibilidad="si";
	
	if(DEBUG){ var_export($_POST);}
	$id_leccion=$_POST["id_leccion"];
	$titulo=mysqli_real_escape_string($conexion_mysqli, $_POST["titulo"]);
	$descripcion=mysqli_real_escape_string($conexion_mysqli, $_POST["descripcion"]);
	$clasificacion=$_POST["clasificacion"];
	$minutos=$_POST["minutos"];
	$segundos=$_POST["segundos"];
	$nivel_exigencia=$_POST["nivel_exigencia"];
	$texto=($_POST["texto"]);
	
	$texto=FORMATEO($texto);
	
	$duracion=($minutos*60)+$segundos;
	
	$campo_valor="titulo='$titulo', descripcion='$descripcion', clasificacion='$clasificacion', duracion_seg='$duracion', nivel_exigencia='$nivel_exigencia', texto='$texto', visible='$visibilidad', fecha_generacion='$fecha_generacion', cod_user='$id_usuario_activo'";
	
	$cons_UP="UPDATE dactilografia_lecciones SET $campo_valor WHERE id='$id_leccion' LIMIT 1";
	if(DEBUG){ echo"$cons_UP<br>";}
	else
	{
		$conexion_mysqli->query($cons_UP);	
	}
	$conexion_mysqli->close();
	
	if(!DEBUG)
	{
		header("location: edita_leccion_final.php?error=0");
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
	//$texto=mysql_real_escape_string($texto);
	//$texto=mysql_real_escape_string($texto);
	
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
	//$texto=str_replace('"',"&quot;",$texto);
	//$texto=str_replace("'","&#039;",$texto);
	
	
	return($texto);
}
?>