<?php
//--------------CLASS_okalis------------------//
require("../../OKALIS/class_OKALIS_v1.php");
define("DEBUG", false);
$O=new OKALIS();
$O->DEBUG=DEBUG;
$O->setDisplayErrors(false);
$O->ruta_conexion="../../../funciones/";
$O->clave_del_archivo=md5("Notas_parcialesV3->creacionManualEvaluaciones");
$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	

if($_POST)
{
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funcion.php");
	if(DEBUG){ var_dump($_POST);}
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$fecha_actual=date("Y-m-d");
	
	$error="debug";
	$id_carrera=$_POST["id_carrera"];
	$year=str_inde($_POST["year"]);
	$semestre=str_inde($_POST["semestre"]);
	$fasignatura=$_POST["fasignatura"];
	$fasignatura=str_inde($fasignatura,"");
	$fn_notas=str_inde($_POST["fn_notas"],0);
	$metodo_evaluacion=str_inde($_POST["metodo_evaluacion"]);
	$jornada=$_POST["jornada"];
	$grupo=$_POST["grupo"];
	$sede=$_POST["sede"];

	$array_tipo_prueba=$_POST["tipo_prueba"];
	$array_fecha_evaluacion_dia=$_POST["fecha_evaluacion_dia"];
	$array_fecha_evaluacion_mes=$_POST["fecha_evaluacion_mes"];
	$array_fecha_evaluacion_year=$_POST["fecha_evaluacion_year"];
	$array_nombre_evaluacion=$_POST["nombre_evaluacion"];
	
	if($metodo_evaluacion=="ponderado")
	{ $array_porcentaje=$_POST["porcentaje"];}
	
	$campos_tabla="id_carrera, cod_asignatura, jornada, grupo, semestre, year, fecha_generacion, fecha_evaluacion, nombre_evaluacion, metodo_evaluacion, tipo_evaluacion, porcentaje, sede, cod_user";
	
	$n_numero=str_inde($fn_notas,"");
	if(!is_numeric($year))
	{$year=date("Y");}
	if(is_numeric($n_numero))
	{
		$grabar_evaluacion=true;
		foreach($array_nombre_evaluacion as $indice=>$aux_nombre_evaluacion)
		{
			if(DEBUG){ echo"$indice -><br>";}
			$aux_tipo_evaluacion=$array_tipo_prueba[$indice];
			$aux_fecha_evaluacion_dia=$array_fecha_evaluacion_dia[$indice];
			$aux_fecha_evaluacion_mes=$array_fecha_evaluacion_mes[$indice];
			$aux_fecha_evaluacion_year=$array_fecha_evaluacion_year[$indice];
			
			$aux_nombre_evaluacion=str_inde($aux_nombre_evaluacion,"");
			
			if($metodo_evaluacion=="ponderado"){ $aux_porcentaje=$array_porcentaje[$indice];}
			else{ $aux_porcentaje=0;}
			
			if(checkdate($aux_fecha_evaluacion_mes,$aux_fecha_evaluacion_dia,$aux_fecha_evaluacion_year))
			{ $fecha_evaluacion=$aux_fecha_evaluacion_year."-".$aux_fecha_evaluacion_mes."-".$aux_fecha_evaluacion_dia;}
			else{ $fecha_evaluacion=$fecha_actual;}

			
			if($grabar_evaluacion)
			{
				$valores="'$id_carrera', '$fasignatura', '$jornada', '$grupo', '$semestre', '$year', '$fecha_actual', '$fecha_evaluacion', '$aux_nombre_evaluacion', '$metodo_evaluacion', '$aux_tipo_evaluacion', '$aux_porcentaje', '$sede', '$id_usuario_actual'";
				
				$cons_IN="INSERT INTO notas_parciales_evaluaciones ($campos_tabla) VALUES($valores)";
				if(DEBUG){ echo"--->$cons_IN<br>";}
				else{  $conexion_mysqli->query($cons_IN)or die($conexion_mysqli->error);}
			}
			else
			{if(DEBUG){ echo"NO graba Evaluacion<br>";}}
		}
	}
	else
	{
		//fn_evaluaciones NO numerico
	
		$conexion_mysqli->close();
		$url="";
		if(DEBUG){ echo"URL: $url<br>";}
		else{ header("location: $url");}
	}	
	$conexion_mysqli->close();
	
	
	/////Registro EVENTO///
	include("../../../../funciones/VX.php");
	 $evento="Agrega ($n_numero) Evaluaciones parciales -> $sede $id_carrera $fasignatura $jornada $grupo";
	REGISTRA_EVENTO($evento);
	///////////////////////
	$url="../ver_evaluaciones.php?error=$error&sede=".base64_encode($sede)."&id_carrera=".base64_encode($id_carrera)."&jornada=".base64_encode($jornada)."&grupo_curso=".base64_encode($grupo)."&cod_asignatura=".base64_encode($fasignatura)."&semestre=".base64_encode($semestre)."&year=".base64_encode($year);
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
else
{
	//enviar a error
	if(DEBUG){ echo"No POST <br>";}
	else{ header("location: ../index.php");}
}
///////////////////////////
?>