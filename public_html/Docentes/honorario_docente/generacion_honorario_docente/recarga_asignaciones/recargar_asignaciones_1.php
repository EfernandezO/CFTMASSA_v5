<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("Genera_honorario_1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	require("../../../../../funciones/conexion_v2.php");
	require("../../../../../funciones/funciones_sistema.php");
	if(isset($_SESSION["HONORARIO"]))
	{ $hay_sesion=true;}
	else
	{ $hay_sesion=false;}

if($_GET)
{
	$sede=mysqli_real_escape_string($conexion_mysqli, $_GET["sede"]);
	$mes_actual=date("m");
	if($mes_actual>8)
	{$semestre_actual=2;}
	else{$semestre_actual=1;}
	
	$semestre_asignacion_consulta=mysqli_real_escape_string($conexion_mysqli, $_GET["semestre"]);
	$year=mysqli_real_escape_string($conexion_mysqli, $_GET["year"]);
	$mes=mysqli_real_escape_string($conexion_mysqli, $_GET["mes"]);
	$year_generacion=mysqli_real_escape_string($conexion_mysqli, $_GET["year_generacion"]);
	
	if(($hay_sesion)or(DEBUG))
	{	
		if(DEBUG){var_dump($_GET);}
		$TOTAL_HONORARIOS=0;
		$fecha_actual=date("Y-m-d H:i:s");
		$id_usuario_actual=$_SESSION["USUARIO"]["id"];
		
		$cons_MAIN="SELECT DISTINCT(id_funcionario) FROM toma_ramo_docente INNER JOIN personal ON toma_ramo_docente.id_funcionario=personal.id WHERE toma_ramo_docente.sede='$sede'AND toma_ramo_docente.condicion='pendiente' AND toma_ramo_docente.semestre='$semestre_asignacion_consulta' AND toma_ramo_docente.year='$year' ORDER by personal.apellido_P, personal.apellido_M";
		if(DEBUG){ echo"-->$cons_MAIN<br>";}
		$sqli_F=$conexion_mysqli->query($cons_MAIN)or die($conexion_mysqli->error);
		$num_total_funcionarios=$sqli_F->num_rows;
		if(DEBUG){echo"Total de Funcionarios con asignaciones: $num_total_funcionarios<br>";}
		$tabla=' <table width="90%" border="1" id="report" align="center">
	  <thead>
		<tr>
		  <th colspan="6">Honorarios a Generar</th>
		<tbody>';
		if($num_total_funcionarios>0)
		{
			$contador_1=0;
			while($F=$sqli_F->fetch_row())
			{
				$contador_1++;
				$id_funcionario=$F[0];
				
				//Datos funcionarios
				$cons_DF="SELECT * FROM personal WHERE id='$id_funcionario' LIMIT 1";
				$sqli_DF=$conexion_mysqli->query($cons_DF)or die($conexion_mysqli->error);
					$DF=$sqli_DF->fetch_assoc();
					$F_rut=$DF["rut"];
					$F_nombre=$DF["nombre"];
					$F_apellido_P=$DF["apellido_P"];
					$F_apellido_M=$DF["apellido_M"];
				$sqli_DF->free();
				//--------------------------------------------------------------------//	
				//datos asignatuta
				$contador=0;
				$cons_asignaciones="SELECT * FROM toma_ramo_docente WHERE id_funcionario='$id_funcionario' AND semestre='$semestre_asignacion_consulta' AND year='$year' AND sede='$sede' order by cod_asignatura";
				$sqli=$conexion_mysqli->query($cons_asignaciones)or die($conexion_mysqli->error);
				$num_asignaciones=$sqli->num_rows;
				$ARRAY_ITEM_DETALLE=array();
				if($num_asignaciones>0)
				{
					$tabla_1='<tr>
								<td colspan="6">
								<h4>Asignaciones Realizadas</h4>';
					$mostrar_resumen_funcionario=false;		
					$SUMA_VALOR_CUOTAS=0;	
					
					while($AS=$sqli->fetch_assoc())
					{
						$contador++;
						$glosa_detalle="";
						$AS_sede=$AS["sede"];
						$AS_id=$AS["id"];
						$AS_id_carrera=$AS["id_carrera"];
						$AS_jornada=$AS["jornada"];
						$AS_grupo=$AS["grupo"];
						$AS_valor_hora=$AS["valor_hora"];
						$AS_cod_asignatura=$AS["cod_asignatura"];
						$AS_numero_horas=$AS["numero_horas"];
						$AS_numero_cuotas=$AS["numero_cuotas"];
						
						$AS_numero_horas_mensuales=($AS_numero_horas/$AS_numero_cuotas);//obtengo numero horas semanales
						
						$AS_total=$AS["total"];
						$AS_semestre=$AS["semestre"];
						$AS_year=$AS["year"];
						//carrera
						$nombre_carrera=NOMBRE_CARRERA($AS_id_carrera);
						//----------------------------//
						//asignatura
							
						list($nombre_asignatura,$nivel_asignatura)=NOMBRE_ASIGNACION($AS_id_carrera, $AS_cod_asignatura);
							
						$valor_cuota_actual=$AS_total/$AS_numero_cuotas;	
						$valor_cuota_actual_2=$valor_cuota_actual;
						
						$valor_cuota_actual_v2=($AS_numero_horas_mensuales*$AS_valor_hora);
						
						//----------------------------------------------------------------------------------------//
						//busco numero de registros previos de esta asignacion
						$cons_honorario_detalle="SELECT * FROM honorario_docente_detalle WHERE sede='$AS_sede' AND id_carrera='$AS_id_carrera' AND cod_asignatura='$AS_cod_asignatura' AND id_funcionario='$id_funcionario' AND semestre='$AS_semestre' AND year='$AS_year' AND jornada='$AS_jornada' AND grupo='$AS_grupo'";
						$sqli_HD=$conexion_mysqli->query($cons_honorario_detalle)or die($conexion_mysqli->error);
						$num_registros_honorario_detalle=$sqli_HD->num_rows;
						if(DEBUG){ echo"---->$cons_honorario_detalle<br>Numero detalles honorario: $num_registros_honorario_detalle<br>";}
						
						$TOTAL_ya_cancelado=0;
						if($num_registros_honorario_detalle>0)
						{
							$num_cuotas_previas=0;
							while($HD=$sqli_HD->fetch_assoc())
							{
								$num_cuotas_previas++;
								$HD_total_base=$HD["total_base"];
								$HD_cargo=$HD["cargo"];
								$HD_abono=$HD["abono"];
								
								$aux_total_x_asignatura=($HD_total_base+$HD_abono)-$HD_cargo;
								
								$TOTAL_ya_cancelado+=$aux_total_x_asignatura;
							}
							$valor_ultima_cuota_detalle=$HD_total_base;
							$num_cuota_actual_a_pagar=$num_cuotas_previas+1;
							
							
						}
						else
						{
							$valor_ultima_cuota_detalle=$valor_cuota_actual;
							$num_cuotas_previas=0;
							$num_cuota_actual_a_pagar=1;
						}
						$sqli_HD->free();
						//--------------------------------------------------------------//
						if(DEBUG){ echo"Valor Cuota Actual: $valor_cuota_actual  Valor Ultima Cuota: $valor_ultima_cuota_detalle<br>Valor Cuota V2: $valor_cuota_actual_v2<br>Cantidad Horas mensuales: $AS_numero_horas_mensuales<br>";}
	
						//---------------------------------------------------------------//
						//determino si debe ser pagada o no	
						if($num_cuota_actual_a_pagar>$AS_numero_cuotas)
						{$utilizar_para_honorario=false; if(DEBUG){ echo"Numero de Cuota Actual Supera al Maximo de cuotas Generadas NO utiliza para honorario<br><br>";} $cambiar_condicion_asignacion=true;}
						elseif($num_cuota_actual_a_pagar==$AS_numero_cuotas)
						{$utilizar_para_honorario=true;if(DEBUG){ echo"Numero de Cuota Actuales igual al Maximo de cuotas Generadas utiliza para honorario<br><br>";} $cambiar_condicion_asignacion=true;}
						else{$utilizar_para_honorario=true; if(DEBUG){ echo"Numero de Cuota Actuales menor al Maximo de cuotas Generadas utiliza para honorario<br><br>";} $cambiar_condicion_asignacion=false;}
						//----------------------------------------------------------------------//	
						
						
						if($utilizar_para_honorario)
						{
							if(DEBUG){ echo"Utilizar para Honorario<br>";}
							$mostrar_resumen_funcionario=true;
							$SUMA_VALOR_CUOTAS+=$valor_cuota_actual;	
								
							/////////////////////////////////////////////////////	
							//grabo	en arreglo
							$ARRAY_ITEM_DETALLE[$contador]["sede"]=$AS_sede;
							$ARRAY_ITEM_DETALLE[$contador]["year"]=$AS_year;
							$ARRAY_ITEM_DETALLE[$contador]["semestre"]=$AS_semestre;
							$ARRAY_ITEM_DETALLE[$contador]["id_carrera"]=$AS_id_carrera;
							$ARRAY_ITEM_DETALLE[$contador]["cod_asignatura"]=$AS_cod_asignatura;
							$ARRAY_ITEM_DETALLE[$contador]["jornada"]=$AS_jornada;
							$ARRAY_ITEM_DETALLE[$contador]["grupo"]=$AS_grupo;
							//-----------------------------------------------------//
							
							$recalcular_total=false;
							
							$cuenta_asignaciones_old=count($_SESSION["HONORARIO"][$id_funcionario]["asignaciones"]);
							if(DEBUG){ echo"->Inicio Busqueda sesion anterior ($cuenta_asignaciones_old)<br>";}
							foreach($_SESSION["HONORARIO"][$id_funcionario]["asignaciones"] as $nx => $aux_array)
							{
								$aux_sede=$aux_array["sede"];
								$aux_year=$aux_array["year"];
								$aux_semestre=$aux_array["semestre"];
								$aux_id_carrera=$aux_array["id_carrera"];
								$aux_cod_asignatura=$aux_array["cod_asignatura"];
								$aux_jornada=$aux_array["jornada"];
								$aux_grupo=$aux_array["grupo"];
								
								if(DEBUG){ echo"-->id_carrera: $aux_id_carrera cod_asignatura: $aux_cod_asignatura Jornada: $aux_jornada Grupo: $aux_grupo<br>";}
								
								if(($AS_sede==$aux_sede)and($AS_year==$aux_year)and($AS_semestre==$aux_semestre)and($AS_id_carrera==$aux_id_carrera)and($AS_cod_asignatura==$aux_cod_asignatura)and($AS_jornada==$aux_jornada)and($AS_grupo==$aux_grupo))
								{$guardar_valores_de_sesion_old=true; if(DEBUG){echo"==>Encontrado<br>";} break;}
								else
								{ $guardar_valores_de_sesion_old=false; if(DEBUG){echo"==>NO Encontrado<br>";}}
									
							}	
							if(DEBUG){ echo"->FIN Busqueda sesion anterior<br><br>";}
							if($guardar_valores_de_sesion_old)
							{
								if(DEBUG){ echo"--->Guardo Valores de Sesion OLD<br>";}
								$aux_cargo=$aux_array["cargo"];
								$aux_abono=$aux_array["abono"];
								if(empty($aux_cargo)){ $aux_cargo=0;}
								if(empty($aux_abono)){ $aux_abono=0;}
								$aux_glosa_cargo=$aux_array["glosa_cargo"];
								$aux_glosa_abono=$aux_array["glosa_abono"];
								
								if(DEBUG){ echo"cargo:$aux_cargo<br>abono: $aux_abono<br>";}
								
								$ARRAY_ITEM_DETALLE[$contador]["cargo"]=$aux_cargo;
								$ARRAY_ITEM_DETALLE[$contador]["abono"]=$aux_abono;
								$ARRAY_ITEM_DETALLE[$contador]["glosa_cargo"]=$aux_glosa_cargo;
								$ARRAY_ITEM_DETALLE[$contador]["glosa_abono"]=$aux_glosa_abono;
								$recalcular_total=true;
							}
							else
							{
								if(DEBUG){ echo"--->NO Guardo Valores de Sesion OLD<br>";}
								$ARRAY_ITEM_DETALLE[$contador]["cargo"]=0;
								$ARRAY_ITEM_DETALLE[$contador]["abono"]=0;
								$ARRAY_ITEM_DETALLE[$contador]["glosa_cargo"]="";
								$ARRAY_ITEM_DETALLE[$contador]["glosa_abono"]="";
							}
							
							
							$ARRAY_ITEM_DETALLE[$contador]["total_base"]=$valor_cuota_actual;
							$ARRAY_ITEM_DETALLE[$contador]["horas_mensuales"]=$AS_numero_horas_mensuales;
							$ARRAY_ITEM_DETALLE[$contador]["condicion"]="on";
							$ARRAY_ITEM_DETALLE[$contador]["num_cuota_actual"]=$num_cuota_actual_a_pagar;
							$ARRAY_ITEM_DETALLE[$contador]["num_cuotas_totales"]=$AS_numero_cuotas;
							$ARRAY_ITEM_DETALLE[$contador]["valor_hora"]=$AS_valor_hora;
							
							
						}
						else
						{ if(DEBUG){ echo"NO Utilizar para Honorario<br>";}}
						
					}
				
					
				}
				else
				{
					//sin asignaciones
				}
				
					if($mostrar_resumen_funcionario)
					{
						
						if($SUMA_VALOR_CUOTAS>0)
						{ $honorario_a_pagar_docente=$SUMA_VALOR_CUOTAS; $estado_honorario="pendiente";}
						else
						{ $honorario_a_pagar_docente=0; $estado_honorario="cancelado";}
						
						//GRABO HONORARIO
						if(DEBUG){ echo"<strong>INICIO GUARDO HONORARIO</strong><br>";}
						$_SESSION["HONORARIO"][$id_funcionario]["total_a_pagar"]=$honorario_a_pagar_docente;
						$_SESSION["HONORARIO"][$id_funcionario]["asignaciones"]=$ARRAY_ITEM_DETALLE;
						if(DEBUG){ echo"<strong>FIN GUARDO HONORARIO</strong><br>";}
						///////////////////////////////
						
							//recalcular total
							if($recalcular_total)
							{
								if(DEBUG){ echo"Recalcular Total de Sesion<br>";}
								$XD_total_a_pagar=0;
								foreach($_SESSION["HONORARIO"][$id_funcionario]["asignaciones"] as $x => $aux_array)
								{
									$XD_aux_condicion=$aux_array["condicion"];
									$XD_aux_total_base=$aux_array["total_base"];
									$XD_aux_cargo=$aux_array["cargo"];
									$XD_aux_abono=$aux_array["abono"];
									$XD_aux_horas_mensuales=$aux_array["horas_mensuales"];
									$XD_aux_valor_hora=$aux_array["valor_hora"];
									
									$XD_total_base=($XD_aux_horas_mensuales*$XD_aux_valor_hora);
									$XD_total_cargo=($XD_aux_cargo*$XD_aux_valor_hora);
									$XD_total_abono=($XD_aux_abono*$XD_aux_valor_hora);
									
									$XD_aux_total_asignatura=($XD_total_base-$XD_total_cargo)+$XD_total_abono;
									
									if($XD_aux_condicion=="on")
									{ $XD_total_a_pagar+=$XD_aux_total_asignatura;}
								}
								
								if(DEBUG){ echo"TOTAL ASIGNACIONES: $XD_total_a_pagar<br>";}
								$_SESSION["HONORARIO"][$id_funcionario]["total_a_pagar"]=$XD_total_a_pagar;
							}
							else
							{
								if(DEBUG){ echo"NO Recalcular Total de Sesion<br>";}
							}
							//--------------------------------------------------------------------------//
	
						///grabo item
						
					}
				
			}
			$mostrar_boton=true;
		}
		else{$mostrar_boton=false;}
			
		$sqli_F->free();
		
	
	}//fin si no hay session
	else{ if(DEBUG){ echo"Ya hay session<br>";}}
	
	$url="../Generacion_honorario_2.php?fsede=$sede&year=$year&semestre=$semestre_asignacion_consulta&mes=$mes&year_generacion=$year_generacion";
	if(DEBUG){ echo"URL: $url<br>";}
	else{ header("location: $url");}
}
else
{if(DEBUG){echo"Sin Datos GET...<br>";}}
?>