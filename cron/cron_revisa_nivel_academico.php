<?php
///27/03/2017
//by eliasfernandezo@gmail.com
//para ejecutar en cron 
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(6000);
ini_set('memory_limit', '-1');
define("DEBUG", false);

$tiempo_inicio_script = microtime(true);
$fecha_actual=date("Y-m-d");
//////////////////////-----------------------///////////////////////////	
//////////////////////---INICIO NIVEL ACADEMICO----/////////////////////////////
/////////////////---------------------------//////////////////////////
echo"<br>-------------------INICIO REVISION NIVEL ACADEMICO---------------------<br>";
	$EXE=FALSE;
	require("cron_autentificacion.php");
	
	//--------------CLASS_okalis------------------//
	require("../public_html/OKALIS/class_OKALIS_v1.php");
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../funciones/";
	$O->clave_del_archivo=md5("cron_nivel_academico_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

		//busco -.- alumnos
		$cons="SELECT id, id_carrera, sede, nivel_academico FROM alumno WHERE situacion IN('EG', 'V') ORDER BY id";
		$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$num_alumnos=$sqli->num_rows;
		echo"$cons<br>num: $num_alumnos<br>";
		$num_OK=0;
		$num_ERROR=0;
		$num_OK1=0;
		$num_ERROR1=0;
		$aux=0;
		$diferencias=0;
		
		$year_actual=date("Y");
		$mes_actual=date("m");
		
		if($mes_actual>=8){ $semestre_actual=2;}
		else{ $semestre_actual=1;}
		
		while($A=$sqli->fetch_assoc())
		{
			$aux++;
			$nivel_academico_alumno="";
			$A_id=$A["id"];
			$A_id_carrera=$A["id_carrera"];
			$A_sede=$A["sede"];
			$A_nivel_academico=$A["nivel_academico"];
			
			if(DEBUG){ echo"\n <br>---> $aux id_alumno: $A_id id_carrera: $A_id_carrera <br> \n";}
			$nivel_academico_alumno=NIVEL_ACADEMICO_ALUMNO_ACTUAL($A_id, $A_id_carrera);
			
			if($nivel_academico_alumno>0)
			{
				if($A_nivel_academico==$nivel_academico_alumno)
				{if(DEBUG){ echo"nivel academico OK<br>";}}
				else
				{
					$diferencias++;
					if(DEBUG){ echo"nivel academico error [$A_nivel_academico -> $nivel_academico_alumno]<br>";}
					$cons_UP="UPDATE alumno SET nivel_academico='$nivel_academico_alumno' WHERE id='$A_id' LIMIT 1";
					if(DEBUG){ echo"---->$cons_UP<br>";}
					else
					{
						if($conexion_mysqli->query($cons_UP))
						{
							$num_OK++;
							$descripcion="Cambio automatico de nivel academico a [$nivel_academico_alumno]";
							REGISTRO_EVENTO_ALUMNO($id_alumno,"notificacion",$descripcion);
						}
						else
						{$num_ERROR++;}
					}
				}
			}
			else
			{
				if(DEBUG){ echo"Nivel Academico No Numerico<br>";}
			}
		}
		
		$sqli->free();
		
		
		$tiempo_fin_script = microtime(true);
$tiempo_de_ejecucion=round($tiempo_fin_script - $tiempo_inicio_script,4);			
		$msj_final="cron_revisa_nivel_academico<br> Total: $num_alumnos<br>Diferencias: $diferencias Errores: $num_ERROR<br>OK: $num_OK<br><br> Tiempo: $tiempo_de_ejecucion";
		echo $msj_final."<br>--------------------FIN REVISION NIVEL ACADEMICO---------------------------<br>";
	
	//-----------------------------------------------//
	
	$evento="$msj_final";
	REGISTRA_EVENTO($evento);
	
		///registro el fin de sesion
	//----------------------------------------------------------//
	CAMBIA_ESTADO_CONEXION($_SESSION["USUARIO"]["id"], "OFF");
	REGISTRA_EVENTO("cierra sesion");
	//------------------------------------------------------------//
	session_destroy();
	$conexion_mysqli->close();
	@mysql_close($conexion);
?>