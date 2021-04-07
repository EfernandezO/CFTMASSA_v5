<?php
//--------------CLASS_okalis------------------//
require("../../../OKALIS/class_OKALIS_v1.php");
define("DEBUG", false);
$O=new OKALIS();
$O->DEBUG=DEBUG;
$O->setDisplayErrors(false);
$O->ruta_conexion="../../../../funciones/";
$O->clave_del_archivo=md5("Notas_parcialesV3->verCalificador");
$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$crearForzosamente=false;
$year_actual=date("Y");
$mes_actual=date("m");
if($mes_actual>8){ $semestre_actual=2;}
else{ $semestre_actual=1;}

if($_GET)
{
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funcion.php");
	if(DEBUG){ var_dump($_GET);}
	
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$fecha_actual=date("Y-m-d");
	
	$error="debug";
	$sede=base64_decode($_GET["sede"]);
	$id_carrera=base64_decode($_GET["id_carrera"]);
	$jornada=base64_decode($_GET["jornada"]);
	$grupo=base64_decode($_GET["grupo"]);
	$cod_asignatura=base64_decode($_GET["asignatura"]);
	$semestre=base64_decode($_GET["semestre"]);
	$year=base64_decode($_GET["year"]);
	
	$metodo_evaluacion="normal";
	$n_numero=3; //numero notas parciales
	


	//reviso si tiene evaluaciones creadas
	
	$cons="SELECT COUNT(id) FROM notas_parciales_evaluaciones WHERE sede='$sede' AND semestre='$semestre' AND year='$year' AND id_carrera='$id_carrera' AND jornada='$jornada' AND grupo='$grupo' AND cod_asignatura='$cod_asignatura'";
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$E=$sqli->fetch_row();
	$numeroRegistros=$E[0];
	if(empty($numeroRegistros)){$numeroRegistros=0;}
	$sqli->free();
	
	if(DEBUG){echo"Numero registros previos: $numeroRegistros<br>";}
	
	if($numeroRegistros>0){ if(DEBUG){echo"Evaluaciones ya creadas NO continuar<br>";}}
	else{
		if(($year==$year_actual)and($semestre==$semestre_actual)){$crearForzosamente=true;}
	}
	
	
	if($semestre_actual==1){
		$array_fechaEvaluaciones=array($year."-04-30",$year."-05-30",$year."-06-30",$year."-07-30",$year."-08-15");
	}
	else{
		$array_fechaEvaluaciones=array($year."-09-30",$year."-10-30",$year."-11-30",$year."-12-30",($year+1)."-01-15");
	}
	
	
	if($crearForzosamente)
	{
		if(DEBUG){echo"Sin Evaluaciones creadas, procedemos a crearlas<br>";}
	
		$campos_tabla="id_carrera, cod_asignatura, jornada, grupo, semestre, year, fecha_generacion, fecha_evaluacion, nombre_evaluacion, metodo_evaluacion, tipo_evaluacion, sede, cod_user";
		
	
		if(is_numeric($n_numero))
		{
			$grabar_evaluacion=true;
			for($n=1;$n<=$n_numero;$n++)
			{
				if(DEBUG){ echo"$n-><br>";}
				
				
				$aux_nombre_evaluacion="nota parcial $n";
				
				//$dias=($n+1)*30;
				//$aux_fecha_evaluacion=date("Y-m-d",strtotime("+$dias days"));
				
				$aux_fecha_evaluacion=$array_fechaEvaluaciones[$n-1];
				$aux_metodo_evaluacion="normal";
				$aux_tipo_evaluacion="parcial";
				$aux_porcentaje=0;
				
				
				if($grabar_evaluacion)
				{
					$valores="'$id_carrera', '$cod_asignatura', '$jornada', '$grupo', '$semestre', '$year', '$fecha_actual', '$aux_fecha_evaluacion', '$aux_nombre_evaluacion', '$metodo_evaluacion', '$aux_tipo_evaluacion','$sede', '$id_usuario_actual'";
					
					$cons_IN="INSERT INTO notas_parciales_evaluaciones ($campos_tabla) VALUES($valores)";
					if(DEBUG){ echo"--->$cons_IN<br>";}
					else{  $conexion_mysqli->query($cons_IN)or die($conexion_mysqli->error);}
				}
				else
				{if(DEBUG){ echo"NO graba Evaluacion<br>";}}
			}
			
			//agrego global 
			$aux_nombre_evaluacion="prueba Global";
			//$dias+=10;
		
			//$aux_fecha_evaluacion=date("Y-m-d",strtotime("+$dias days"));
			$aux_fecha_evaluacion=$array_fechaEvaluaciones[$n-1];
			
			
			$aux_metodo_evaluacion="normal";
			$aux_tipo_evaluacion="global";
			$valores="'$id_carrera', '$cod_asignatura', '$jornada', '$grupo', '$semestre', '$year', '$fecha_actual', '$aux_fecha_evaluacion', '$aux_nombre_evaluacion', '$metodo_evaluacion', '$aux_tipo_evaluacion', '$sede', '$id_usuario_actual'";
					
			$cons_ING="INSERT INTO notas_parciales_evaluaciones ($campos_tabla) VALUES($valores)";
			//repeticion
			$aux_nombre_evaluacion="Prueba de Repeticion";
			//$dias+=10;
	
			//$aux_fecha_evaluacion=date("Y-m-d",strtotime("+$dias days"));
			$n+=1;
			$aux_fecha_evaluacion=$array_fechaEvaluaciones[$n-1];
			
			$aux_metodo_evaluacion="normal";
			$aux_tipo_evaluacion="repeticion";
			$valores="'$id_carrera', '$cod_asignatura', '$jornada', '$grupo', '$semestre', '$year', '$fecha_actual', '$aux_fecha_evaluacion', '$aux_nombre_evaluacion', '$metodo_evaluacion', '$aux_tipo_evaluacion', '$sede', '$id_usuario_actual'";
					
			$cons_INR="INSERT INTO notas_parciales_evaluaciones ($campos_tabla) VALUES($valores)";
			if(DEBUG){ echo"<br>--->$cons_ING<br><br>$cons_INR<br>";}
			else{  $conexion_mysqli->query($cons_ING)or die($conexion_mysqli->error);$conexion_mysqli->query($cons_INR)or die($conexion_mysqli->error);}
			
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
		 $evento="Agrega ($n_numero) Evaluaciones parciales -> $sede $id_carrera $cod_asignatura $jornada $grupo";
		REGISTRA_EVENTO($evento);
		///////////////////////
	}//fin forzosamente
	
	$url="../ver_evaluaciones.php?error=$error&sede=".base64_encode($sede)."&id_carrera=".base64_encode($id_carrera)."&jornada=".base64_encode($jornada)."&grupo_curso=".base64_encode($grupo)."&cod_asignatura=".base64_encode($cod_asignatura)."&semestre=".base64_encode($semestre)."&year=".base64_encode($year);
	if(DEBUG){ echo"<br>URL: $url<br>";}
	else{ header("location: $url");}
}
else
{
	//enviar a error
	if(DEBUG){ echo"Sin Datos <br>";}
	else{ header("location: ../index.php");}
}
///////////////////////////
?>