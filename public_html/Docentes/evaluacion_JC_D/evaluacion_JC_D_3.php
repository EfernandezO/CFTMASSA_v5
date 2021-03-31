<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//
require("../../../funciones/conexion_v2.php");
//-------------------------------------------------//
if(isset($_SESSION["ENCUESTA"]["contestada"]))
{
	if(DEBUG){ echo"Sesion encuesta definida<br>";}
	if(!$_SESSION["ENCUESTA"]["contestada"])
	{
		if(DEBUG){ echo"Encuesta aun no contestada<br>";}
		if($_POST)
		{ $continuar=true; if(DEBUG){ echo"Hay post<br>";}}
		else
		{ $continuar=false; if(DEBUG){ echo"NO POST<br>";}}
	}
	else
	{ $continuar=true; if(DEBUG){ echo"Ya Contestada...<br>";}}
}
else
{ $continuar=false;}



//*******************************************************//
if($continuar)
{	
	if(!DEBUG){$_SESSION["ENCUESTA"]["contestada"]=true;}
	$id_encuesta=mysqli_real_escape_string($conexion_mysqli, $_POST["id_encuesta"]);
	//-------------------------------//
	
	//$array_preguntas=$_POST["pregunta"];
	$ARRAY_RESPUESTAS=$_POST["RESPUESTA"];
	
	if(isset($_POST["RESPUESTA_ANEXA"]))
	{$ARRAY_RESPUESTA_ANEXA=$_POST["RESPUESTA_ANEXA"];}
	else{ $ARRAY_RESPUESTA_ANEXA=array();}
	
	$fecha_hora_actual=date("Y-m-d H:i:s");
	$id_usuario=mysqli_real_escape_string($conexion_mysqli, $_POST["id_usuario"]);
	$tipo_usuario=mysqli_real_escape_string($conexion_mysqli, $_POST["tipo_usuario"]);
	
	$sede_usuario=mysqli_real_escape_string($conexion_mysqli, $_POST["sede_evaluar"]);
	$id_usuario_evaluar=mysqli_real_escape_string($conexion_mysqli, $_POST["id_usuario_evaluar"]);
	$semestre_evaluar=mysqli_real_escape_string($conexion_mysqli, $_POST["semestre_evaluar"]);
	$year_evaluar=mysqli_real_escape_string($conexion_mysqli, $_POST["year_evaluar"]);
	$id_carrera_evaluar=mysqli_real_escape_string($conexion_mysqli, $_POST["id_carrera_evaluar"]);
	//$cod_asignatura_evaluar=mysqli_real_escape_string($conexion_mysqli, $_POST["cod_asignatura_evaluar"]);
	
	if(DEBUG){ var_dump($_POST); echo"<br><br>";}
	
	
	$id_usuario_encuesta=$id_usuario;
	$tipo_usuario_encuesta=$tipo_usuario;
	
	//-----------------------------------------//	
 	 include("../../../funciones/VX.php");
	//-----------------------------------------------//
	
	if(isset($ARRAY_RESPUESTAS))
	{
		
		foreach($ARRAY_RESPUESTAS as $aux_id_pregunta => $array_valores)
		{
			if(DEBUG){ echo"id_pregunta: $aux_id_pregunta ";}
			foreach($array_valores as $aux_tipo_pregunta => $aux_respuesta)
			{
				$aux_respuesta=mysqli_real_escape_string($conexion_mysqli, $aux_respuesta);
				$aux_tipo_pregunta=mysqli_real_escape_string($conexion_mysqli, $aux_tipo_pregunta);
				$aux_id_pregunta=mysqli_real_escape_string($conexion_mysqli, $aux_id_pregunta);
				if(DEBUG){var_dump($aux_respuesta);}
				if(DEBUG){ echo"tipo pregunta: $aux_tipo_pregunta _> Respuesta: $aux_respuesta<br>";}
				
				switch($aux_tipo_pregunta)
				{
					case"alternativa":
						$guardar=true;
						
						if(isset($ARRAY_RESPUESTA_ANEXA[$aux_respuesta]))
						{
							if(DEBUG){ echo"Hay respuesta Anexa<br>";}
							$aux_respuesta_anexa=mysqli_real_escape_string($conexion_mysqli, $ARRAY_RESPUESTA_ANEXA[$aux_respuesta]);
							$campos="id_usuario, tipo_usuario, id_usuario_evaluar, semestre_evaluar, year_evaluar, sede_evaluar, id_carrera_evaluar, fecha_generacion, id_encuesta, id_pregunta, id_alternativa, respuesta_directa";
							$valores="'$id_usuario_encuesta', '$tipo_usuario_encuesta', '$id_usuario_evaluar', '$semestre_evaluar', '$year_evaluar', '$sede_usuario', '$id_carrera_evaluar', $fecha_hora_actual', '$id_encuesta', '$aux_id_pregunta', '$aux_respuesta', '$aux_respuesta_anexa'";
						}
						else{
							if(DEBUG){ echo"NO hay respuesta Anexa<br>";}
							$campos="id_usuario, tipo_usuario, id_usuario_evaluar, semestre_evaluar, year_evaluar, sede_evaluar, id_carrera_evaluar,  fecha_generacion, id_encuesta, id_pregunta, id_alternativa";
							$valores="'$id_usuario_encuesta', '$tipo_usuario_encuesta', '$id_usuario_evaluar', '$semestre_evaluar', '$year_evaluar',  '$sede_usuario', '$id_carrera_evaluar', '$fecha_hora_actual', '$id_encuesta', '$aux_id_pregunta', '$aux_respuesta'";	
							}
						
						
						break;
					case"directa":
						$guardar=true;
						$campos="id_usuario, tipo_usuario, id_usuario_evaluar, semestre_evaluar, year_evaluar, sede_evaluar, id_carrera_evaluar,  fecha_generacion, id_encuesta, id_pregunta, respuesta_directa";
						$valores="'$id_usuario_encuesta', '$tipo_usuario_encuesta', '$id_usuario_evaluar', '$semestre_evaluar', '$year_evaluar', '$sede_usuario', '$id_carrera_evaluar', '$fecha_hora_actual', '$id_encuesta', '$aux_id_pregunta', '$aux_respuesta'";
						break;
					default:
						$guardar=false;	
				}
				
				if($guardar)
				{
					$cons_INR="INSERT INTO encuestas_resultados ($campos) VALUES ($valores)";
					if(DEBUG){ echo"-->$cons_INR<br><br>";}
					else{ $conexion_mysqli->query($cons_INR)or die("Guardando_resultados ".$conexion_mysqli->error."-> ".$cons_INR);}
				}
				else
				{ if(DEBUG){ echo"No guardar tipo incorrecto de pregunta<br>";}}
			}
		}
		 ///////////////registr evento/////////////////////
		 $evento="Contesta Encuesta id_encuesta: $id_encuesta para tipo_usuario: $tipo_usuario_encuesta id_usuario: $id_usuario_encuesta";
		 REGISTRA_EVENTO($evento);
		 ///////////////////////////////////////////////////
	}
	else
	{
		if(DEBUG){ echo"Sin Respuestas...<br>";}
	}
	
	@mysql_close($conexion);
	$conexion_mysqli->close();
	$url="evaluacion_JC_D_4.php?error=C0";
	
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
else
{
	if(DEBUG){ echo"NO continuar<br>";}
	else{echo"No se puede continuar<br>";}
}

?>