<?php
///03/04/2017
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
//////////////////////---INICIO Estado_financiero alumnos----/////////////////////////////
/////////////////---------------------------//////////////////////////
echo"<br>-------------------INICIO REVISION Estado financiero---------------------<br> \n";
	$EXE=true;
	require("cron_autentificacion.php");
	
	//--------------CLASS_okalis------------------//
	require("../public_html/OKALIS/class_OKALIS_v1.php");
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../funciones/";
	$O->clave_del_archivo=md5("cron_revisa_estado_financiero_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

		//busco -.- alumnos
		$cons="SELECT id, id_carrera, sede, situacion_financiera FROM alumno WHERE situacion IN('EG', 'V') ORDER BY id";
		$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$num_alumnos=$sqli->num_rows;
		echo"$cons<br>num: $num_alumnos<br>";
		$num_OK=0;
		$num_ERROR=0;
		$num_OK1=0;
		$num_ERROR1=0;
		$aux=0;
		$diferencias=0;
		
		$cuenta_morosos=0;
		$cuenta_vigentes=0;
		
		$year_actual=date("Y");
		$mes_actual=date("m");
		
		if($mes_actual>=8){ $semestre_actual=2;}
		else{ $semestre_actual=1;}
		
		while($A=$sqli->fetch_assoc())
		{
			$aux++;
			
			$A_id=$A["id"];
			$A_id_carrera=$A["id_carrera"];
			$A_sede=$A["sede"];
			$A_situacion_financiera=$A["situacion_financiera"];
			
			if(DEBUG){ echo"\n <br>---> $aux id_alumno: $A_id id_carrera: $A_id_carrera <br> \n";}
			$deuda_actual=DEUDA_ACTUAL($A_id, $fecha_actual);
			if(empty($deuda_actual)){$deuda_actual=0;}
			
			if($deuda_actual>0){ $situacion_financiera_new="M"; $cuenta_morosos++;}
			else{ $situacion_financiera_new="V"; $cuenta_vigentes++;}
			
			/////////////////////////////////////////
			
		
				if($A_situacion_financiera==$situacion_financiera_new)
				{if(DEBUG){ echo"Situacion  Financiera OK<br> \n";}}
				else
				{
					$diferencias++;
					if(DEBUG){ echo"Situacion Financiera Diferente [$A_situacion_financiera -> $situacion_financiera_new]<br> \n";}
					$cons_UP="UPDATE alumno SET situacion_financiera='$situacion_financiera_new' WHERE id='$A_id' LIMIT 1";
					if(DEBUG){ echo"---->$cons_UP<br> \n";}
					else
					{
						if($conexion_mysqli->query($cons_UP))
						{
							$num_OK++;
							$descripcion="Cambio automatico de situacion Financiera a [$situacion_financiera_new]";
							REGISTRO_EVENTO_ALUMNO($A_id,"notificacion",$descripcion);
						}
						else
						{$num_ERROR++;}
					}
				}
		}
	
		
		$sqli->free();
		
		
		$tiempo_fin_script = microtime(true);
$tiempo_de_ejecucion=round($tiempo_fin_script - $tiempo_inicio_script,4);			
		$msj_final="cron_revisa_estado_financiero.php<br> Total: $num_alumnos<br>Diferencias: $diferencias Errores: $num_ERROR<br>OK: $num_OK Total Morosos Detectados: $cuenta_morosos Total Vigentes Detectados: $cuenta_vigentes<br><br> Tiempo: $tiempo_de_ejecucion";
		echo $msj_final."<br>--------------------FIN REVISION situacion financiera---------------------------<br> \n";
	
	//-----------------------------------------------//
	
	$evento="$msj_final";
	REGISTRA_EVENTO($evento);
	
		///registro el fin de sesion
	//----------------------------------------------------------//
	CAMBIA_ESTADO_CONEXION($_SESSION["USUARIO"]["id"], "OFF");
	REGISTRA_EVENTO("cierra sesion");
	//------------------------------------------------------------//
	@session_destroy();
	$conexion_mysqli->close();
	@mysql_close($conexion);
?>