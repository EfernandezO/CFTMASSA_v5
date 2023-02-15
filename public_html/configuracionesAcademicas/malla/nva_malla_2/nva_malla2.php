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
	$error="G0";
	require("../../../../funciones/conexion_v2.php");
	
		$carrera=mysqli_real_escape_string($conexion_mysqli, $_POST["carrera"]);
		$numero_ramo=mysqli_real_escape_string($conexion_mysqli, $_POST["numero_ramo"]);
		$id_carrera=mysqli_real_escape_string($conexion_mysqli, $_POST["id_carrera"]);
		$sede=mysqli_real_escape_string($conexion_mysqli, $_POST["sede"]);
		$codigo=$_POST["codigo"];
		$num_posicion=$_POST["num_posicion"];
		$pr1=$_POST["pr1"];
		$pr2=$_POST["pr2"];
		$pr3=$_POST["pr3"];
		$pr4=$_POST["pr4"];
		$pr5=$_POST["pr5"];
		$nivel=$_POST["nivel"];
		$ramo=$_POST["ramo"];
		$numero_horas_teoricas=$_POST["numero_horas_teoricas"];
		$numero_horas_practicas=$_POST["numero_horas_practicas"];
		$es_asignatura=$_POST["es_asignatura"];
		
	if(DEBUG){ var_dump($_POST); echo"<br><br>";}
	
	$cantidad_ramos=count($ramo);
	
	for ($j=0;$j<$cantidad_ramos;$j++)
	{
		$grabar_registro=true;
		
		$aux_codigo=mysqli_real_escape_string($conexion_mysqli, $codigo[$j]);
		$aux_pr1=mysqli_real_escape_string($conexion_mysqli, $pr1[$j]);
		$aux_pr2=mysqli_real_escape_string($conexion_mysqli, $pr2[$j]);
		$aux_pr3=mysqli_real_escape_string($conexion_mysqli, $pr3[$j]);
		$aux_pr4=mysqli_real_escape_string($conexion_mysqli, $pr4[$j]);
		$aux_pr5=mysqli_real_escape_string($conexion_mysqli, $pr5[$j]);
		$aux_ramo=mysqli_real_escape_string($conexion_mysqli, $ramo[$j]);
		$aux_nivel=mysqli_real_escape_string($conexion_mysqli, $nivel[$j]);
		$aux_num_posicion=mysqli_real_escape_string($conexion_mysqli, $num_posicion[$j]);
		
		$aux_numero_horas_teoricas=mysqli_real_escape_string($conexion_mysqli, $numero_horas_teoricas[$j]);
		$aux_numero_horas_practicas=mysqli_real_escape_string($conexion_mysqli, $numero_horas_practicas[$j]);
		$aux_es_asignatura=mysqli_real_escape_string($conexion_mysqli, $es_asignatura[$j]);
		
		if(!is_numeric($aux_numero_horas_teoricas)){$aux_numero_horas_teoricas=0;}
		if(!is_numeric($aux_numero_horas_practicas)){$aux_numero_horas_practicas=0;}
		
		if(!VALIDA_CODIGO($aux_codigo, $id_carrera))
		{ $grabar_registro=false;}
		
		if(!VALIDA_RAMO($aux_ramo, $id_carrera))
		{ $grabar_registro=false;}
		
		if($grabar_registro)
		{
			$cons="INSERT INTO mallas (num_posicion, id_carrera, cod, pr1, pr2, pr3, pr4, pr5, nivel, ramo, horas_teoricas, horas_practicas, es_asignatura) VALUES('$aux_num_posicion', '$id_carrera','$aux_codigo', '$aux_pr1', '$aux_pr2', '$aux_pr3', '$aux_pr4', '$aux_pr5', '$aux_nivel', '$aux_ramo', '$aux_numero_horas_teoricas', '$aux_numero_horas_practicas', '$aux_es_asignatura')";
		
			if(DEBUG){echo"$cons<br><br>";}
			else{ $conexion_mysqli->query($cons)or die("INSERTAR ".$conexion_mysqli->error);}
		}
		else
		{ if(DEBUG){echo"NO GRABAR : J=$j<br>";} $error="G1";}
	}
	
	//@mysql_close($conexion);
	$conexion_mysqli->close();
	
	$url="../ver_malla.php?id_carrera=$id_carrera&sede=$sede&error=$error";
	if(DEBUG){ echo"URL: $url";}
	else{ header("location: $url");}
}	
//////////*****/////////
function VALIDA_CODIGO($codigo, $id_carrera)
{
	$respuesta=true;
	
	if((!is_numeric($codigo))or($codigo<=0))
	{ $respuesta=false; if(DEBUG){ echo"codigo: NO numerico o menor a cero<br>";}}
	else
	{
		$cons_BC="SELECT COUNT(cod) FROM mallas WHERE id_carrera='$id_carrera' AND cod='$codigo'";
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
function VALIDA_RAMO($ramo, $id_carrera)
{
	$respuesta=true;
	if(empty($ramo)){ $respuesta=false; if(DEBUG){ echo"RAMO: Vacio<br>";}}
	else
	{
		$cons_BC="SELECT COUNT(ramo) FROM mallas WHERE id_carrera='$id_carrera' AND ramo='$ramo'";
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