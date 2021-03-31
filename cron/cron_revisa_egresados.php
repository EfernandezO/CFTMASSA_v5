<?php
///27/03/2017
//by eliasfernandezo@gmail.com
//para ejecutar en cron 
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(6000);
ini_set('memory_limit', '-1');
define("DEBUG", false);
$year_actual=date("Y");
$tiempo_inicio_script = microtime(true);
$fecha_actual=date("Y-m-d");
require("cron_autentificacion.php");

//--------------CLASS_okalis------------------//
	require("../public_html/OKALIS/class_OKALIS_v1.php");
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../funciones/";
	$O->clave_del_archivo=md5("cron_egresados_v1");
	//$O->clave_del_archivo=md5("Gestion_alumnos_BETA_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//


//////////////////////-----------------------///////////////////////////	
//////////////////////---INICIO EGRESADOS----/////////////////////////////
/////////////////---------------------------//////////////////////////
echo"\n <br> -------------------INICIO REVISION EGRESADOS---------------------<br> \n";
	$EXE=false;
	

//busco alumnos

	
		//$cons="SELECT DISTINCT(notas_hija.id_alumno) AS id_alumno, notas_hija.id_carrera, alumno.year_egreso, alumno.sede, alumno.situacion FROM notas_hija INNER JOIN alumno ON notas_hija.id_alumno=alumno.id AND notas_hija.id_carrera=alumno.id_carrera WHERE alumno.situacion IN('EG', 'V') AND YEAR(notas_hija.fecha_generacion)='$year_actual' order by notas_hija.id_alumno";

/*		
		$cons="SELECT id FROM alumno WHERE situacion IN('V','EG') ORDER by id";
		
		$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$num_alumnos=$sqli->num_rows;
		if(DEBUG){echo"$cons <br> \n num: $num_alumnos <br> \n";}
		$num_OK=0;
		$num_ERROR=0;
		$num_OK1=0;
		$num_ERROR1=0;
		$aux=0;
		$egresados_actualizados=0;
		$incongruencias=0;
		$condicion_EG_eliminada=0;
		$fecha_hora_actual=date("Y-m-d H:i:s");
		
		while($D=$sqli->fetch_assoc())
		{
			
			$aux++;
			$id_alumno=$D["id_alumno"];
			$id_carrera=$D["id_carrera"];
			$sede=$D["sede"];
			$A_year_egreso=$D["year_egreso"];
			$A_situacion_actual=$D["situacion"];
			
			if(DEBUG){echo"\n <br> <strong>[$aux]</strong> Comprobando alumno id_alumno: $id_alumno carrera. $id_carrera <br> \n";}
				
			//reviso si es o no egresado segun sus notas semestrales				
			list($alumno_es_egresado, $semestre_egreso, $year_egreso)=ES_EGRESADO($id_alumno, $id_carrera);
			

			if($alumno_es_egresado)
			{
				
				//cuento cuantos procesos de egreso tinee
				
				$cons_S="SELECT COUNT(id_proceso_egreso) FROM proceso_egreso WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera'";
				$sqli_S=$conexion_mysqli->query($cons_S)or die($conexion_mysqli->error);
				$NX=$sqli_S->fetch_row();
				$num_registros=$NX[0];
				if(empty($num_registros)){ $num_registros=0;}
				$sqli_S->free();
				//--------------------------------------------------------//
				if(DEBUG){echo"Numero registros proceso egreso: $num_registros\n";}
				
				//si tiene proceso creado lo reviso
				if($num_registros>0)
				{ 
					if(DEBUG){echo"No volver a registrar\n";}
					
					$cons_1="SELECT id_proceso_egreso, year_egreso FROM proceso_egreso WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera'";
					$sqli_1=$conexion_mysqli->query($cons_1)or die($conexion_mysqli->error);
					$PE=$sqli_1->fetch_assoc();
					$PE_year=$PE["year_egreso"];
					$PE_id_proceso_egreso=$PE["id_proceso_egreso"];
					$sqli_1->free();
					//reviso el año del proceso
					if($year_egreso==$PE_year)
					{if(DEBUG){echo"Year Egreso de Alumno y proceso concuerdan OK\n";}}
					else
					{
						//sino concuenrdan lo actualizo
						if(DEBUG){echo"Year Egreso de Alumno y proceso NO concuerdan ERROR [$A_year_egreso - $PE_year]\n";}
						$incongruencias++;
						$cons_UP2="UPDATE proceso_egreso SET year_egreso='$A_year_egreso' WHERE id_proceso_egreso='$PE_id_proceso_egreso' LIMIT 1";
						if($EXE)
						{
							if($conexion_mysqli->query($cons_UP2))
							{ 
								if(DEBUG){echo"OK, Proceso de egreso Rectificado\n";} 
								$num_OK++; 
								$descripcion="Rectificacion automatica de year en proceso_egreso [$PE_year -> $year_egreso]";
								REGISTRO_EVENTO_ALUMNO($id_alumno,"notificacion",$descripcion);
							}
							else
							{ if(DEBUG){echo"ERROR, Proceso de egreso NO Rectificado\n";} $num_ERROR++;}
						}
						else{ if(DEBUG){echo"---->$cons_UP2\n";}}
					}
				}
				else
				{
					if(DEBUG){echo"Crear registro de egreso\n";}
				
					$cons_IN="INSERT INTO proceso_egreso (id_alumno, id_carrera, sede, semestre_egreso, year_egreso, fecha_generacion, cod_user) VALUES ('$id_alumno', '$id_carrera', '$sede', '$semestre_egreso', '$year_egreso', '$fecha_hora_actual', '$id_usuario')";
					
					if(DEBUG){echo"--->$cons_IN\n";}
					
					if($EXE)
					{
						if($conexion_mysqli->query($cons_IN))
						{ 
							if(DEBUG){echo"INSERTADO OK\n\n";}
							$descripcion="Cambio automatico de situacion de Alumno a EG en periodo [$semestre_egreso - $year_egreso]";
							REGISTRO_EVENTO_ALUMNO($id_alumno,"notificacion",$descripcion);
						}
						else
						{ if(DEBUG){echo"INSERTADO ERROR\n\n";}}
					}
				}
				
				//tambien reviso year egreso del alumno v/s real
					if($A_year_egreso==$year_egreso)
					{ if(DEBUG){echo"Year egreso de alumno OK\n";}}
					else
					{
						if(DEBUG){echo"Error en year egreso de alumno---> corregir\n";}
						
						$cons_UP3="UPDATE alumno SET year_egreso='$year_egreso' WHERE id='$id_alumno' LIMIT 1";
						if($EXE)
						{
							if($conexion_mysqli->query($cons_UP3))
							{ 
								if(DEBUG){echo"OK, Year egreso Alumno Rectificado\n";} 
								$num_OK1++;
								$descripcion="Rectificacion automatica de year_egreso de alumno [$A_year_egreso -> $year_egreso]";
								REGISTRO_EVENTO_ALUMNO($id_alumno,"notificacion",$descripcion);
							}
							else
							{ if(DEBUG){echo"ERROR, Year de egreso ALumno NO Rectificado\n";} $num_ERROR1++;}
						}
						else{ if(DEBUG){echo"---->$cons_UP2\n";}}
					}
				
			}
			else
			{ 		
				if(DEBUG){ echo"Alumno No es Egresado revisando si existe proceso_egreso, si existe <br> \n Cambiar a condicion V y Eliminar Proceso de Egreso si existe <br> \n";}
					//no es egresado elimino registro de egreso si es que lo tiene
					list($es_egresado_con_registro, $semestre_egreso_con_registro, $year_egreso_con_registro)=ES_EGRESADO_V2($id_alumno, $id_carrera);
					if($es_egresado_con_registro)
					{
						if(DEBUG){ echo"Alumno NO Egresado, pero tiene registro de proceso de egreso, Eliminandolo<br>";}
						$cons_D="DELETE FROM proceso_egreso WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' LIMIT 1";
						if(DEBUG){ echo"---> $cons_D<br>";}
						else{ $conexion_mysqli->query($cons_D)or die($conexion_mysqli->error);}
						
						if($A_situacion=="EG")
						{
							$condicion_EG_eliminada++;
							$cons_UP_A="UPDATE alumno SET situacion='V' year_egreso=NULL WHERE id='$id_alumno' AND id_carrera='$id_carrera' LIMIT 1";
							if($conexion_mysqli->query($cons_UP_A))
							{
								$descripcion="Eliminacion de Situacion academica, Egresado, a Alumno (No todos los Ramos Aprobados).";
								REGISTRO_EVENTO_ALUMNO($id_alumno_actual, "Advertencia",$descripcion);
								$_SESSION["SELECTOR_ALUMNO"]["situacion"]="V";
							}
						}
					}
					else
					{ if(DEBUG){ echo"Sin Registro de Proceso_egreso.... Continuar sin Cambios...<br> \n";}}
				
			}
		}
		$sqli->free();

$tiempo_fin_script_egresados = microtime(true);
$tiempo_de_ejecucion=round($tiempo_fin_script_egresados - $tiempo_inicio_script,4);			
		$msj_final="\n cron_revisa_egresados\n Total: $num_alumnos\n Procesos egresos NO corregidos: $num_ERROR \n Procesos de egreso corregidos: $num_OK \n year egreso alumno NO corregidos: $num_ERROR1 \n year egreso alumno corregidos: $num_OK1 \n Num incongruencias: $incongruencias\n Condicion EG Eliminadas:  $condicion_EG_eliminada \n Tiempo: $tiempo_de_ejecucion";
		echo $msj_final."\n--------------------FIN REVISION EGRESADOS---------------------------\n";
	
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
//////////////////////-----------------------///////////////////////////	
//////////////////////---FIN EGRESADOS----/////////////////////////////
/////////////////---------------------------//////////////////////////

*/
?>