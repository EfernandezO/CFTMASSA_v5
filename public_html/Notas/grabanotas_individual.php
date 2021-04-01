<?php
//--------------CLASS_okalis------------------//
	require("../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../funciones/";
	$O->clave_del_archivo=md5("Alumno->Notas_Semestrales_v1->grabar");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	

$id_alumno=$_POST["id_alumno"];
$id_alumno_actual=$_SESSION["SELECTOR_ALUMNO"]["id"];
if($id_alumno==$id_alumno_actual){ $acceso=true;}
else{$acceso=false;}

if((($_POST)and($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])and($acceso)))
{
	require("../../funciones/conexion_v2.php");
	require("../../funciones/funciones_sistema.php");
	include("../../funciones/VX.php");
	if(DEBUG){ var_dump($_POST);}
	$error="N0";
	
	$registrar_hija=false;
	$editar_hija=false;
	
	$id_usuario_activo=$_SESSION["USUARIO"]["id"];
	$fecha_actual=date("Y-m-d");
	$fecha_hora_actual=date("Y-m-d H:i:s");
	
	
	$id_alumno=$_POST["id_alumno"];
	$id_carrera=$_POST["id_carrera"];
	$yearIngresoCarrera=$_POST["yearIngresoCarrera"];
	
	$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	
	$ARRAY_COD=$_POST["cod"];
	$ARRAY_NOTA=$_POST["nota"];
	$ARRAY_SEMESTRE=$_POST["semestre"];
	$ARRAY_YEAR=$_POST["ano"];
	$ARRAY_ID_NOTA_MADRE=$_POST["id_nota_madre"];
	$ARRAY_ID_ACCION=$_POST["accion"];
	$ARRAY_CONDICION=$_POST["condicion"];
	$ARRAY_OBSERVACION=$_POST["observacion"];
	//$ARRAY_RECEPCION=$_POST["fecha_recepcion"];
	foreach($ARRAY_COD as $n => $codigo)
	{ 
	   if(DEBUG){ echo"N: $n-> codigo: $codigo<br>";}
	   
	   $aux_nota=$ARRAY_NOTA[$n];
	   $aux_nota=str_replace(",",".",$aux_nota);
	   $aux_semestre=$ARRAY_SEMESTRE[$n];
	   $aux_year=$ARRAY_YEAR[$n];
	   $aux_condicion=$ARRAY_CONDICION[$n];
	   $aux_observacion=$ARRAY_OBSERVACION[$n];
	  // $aux_fecha_recepcion=$ARRAY_RECEPCION[$n];
	   //agregado
	   $aux_id_nota_madre=$ARRAY_ID_NOTA_MADRE[$n];
	   $aux_id_nota_hija=$ARRAY_ID_ACCION[$n];
	  
	  if($aux_condicion=="eliminar")
	  {
		  if(DEBUG){ echo"<b>ACCION: Eliminar</b><br>";}
		  
		   $restablecer_madre=ES_ULTIMA_NOTA($id_alumno, $aux_id_nota_hija, $aux_id_nota_madre);
		  
		  $cons_D1="DELETE FROM notas_hija WHERE id='$aux_id_nota_hija' LIMIT 1";
		  if(DEBUG){ echo"-->$cons_D1<br>";}
		  else{ $conexion_mysqli->query($cons_D1)or die("Eliminar ".$conexion_mysqli->error);}
		  
		  
		  
		   
		   if($restablecer_madre)
		   {
			   if(DEBUG){ echo"La nota eliminada es la ultima por tanto actualizar nota madre<br>";}
			   $cons_D0="SELECT * FROM notas_hija WHERE id_nota='$aux_id_nota_madre' ORDER by id ASC";
			   $sqli_0=$conexion_mysqli->query($cons_D0)or die("Busco hijas ".$conexion_mysqli->error);
			   $num_otras_hijas=$sqli_0->num_rows;
			   if(DEBUG){ echo"-->$cons_D0<br>num otros hijos: $num_otras_hijas<br>";}
			   if($num_otras_hijas>0)
			   {
				   if(DEBUG){ echo"---> Hay Hijas Relacionadas buscando valor de ultima hija y actualizando madre con esos valores<br>";}
				   while($NH=$sqli_0->fetch_assoc())
				   {
					   $aux_nota_R=$NH["nota"];
					   $aux_year_R=$NH["year"];
					   $aux_semestre_R=$NH["semestre"];
					   $aux_condicion_R=$NH["condicion"];
					   
					   if(DEBUG){ echo"--->nota: $aux_nota_R year: $aux_year_R semestre: $aux_semestre_R<br>";}
				   }
			   }
			   else
			   {
				    if(DEBUG){ echo"---> NO Hay Hijas Relacionadas restablecer nota madre<br><br>";}
				   $aux_nota_R="";
				   $aux_year_R="0";
				   $aux_semestre_R="0";
				   $aux_condicion_R="";
			   }
			   $sqli_0->free();
			   
		  		$cons_D2="UPDATE notas SET nota='$aux_nota_R', ano='$aux_year_R', semestre='$aux_semestre_R', condicion='$aux_condicion_R' WHERE id='$aux_id_nota_madre' LIMIT 1";
				if(DEBUG){ echo"--->Actualizo nota Madre: $cons_D2<br>";}
				else{ $conexion_mysqli->query($cons_D2)or die("Update Nota madre".$conexion_mysqli->error);}
				
				//------------------------------------//
					$evento="Elimino Nota id_nota_hija: $aux_id_nota_hija id_nota: $aux_id_nota_madre  id_alumno $id_alumno periodo[$aux_semestre - $aux_year]";
					REGISTRA_EVENTO($evento);
					//--------------------------------------//
		   }
		   else{ if(DEBUG){ echo"No Restablecer nota Madre<br>";}}
	  }
	  else
	  {	   
		   $GRABAR=VALIDA_NOTA($aux_nota);
		   ///----------------------------------------------------///
		   ///segun condiciones de nota correcta y recepcion edito o inserto en tabla notas_hija
			if(($GRABAR)and(!empty($aux_nota)))
			{
				if(DEBUG){ echo"<b>GRABAR: $GRABAR</b><br>";}
				if($aux_id_nota_hija>0)
				{
				   if(DEBUG){ echo"ACCION: Editar<br>";}
				   $editar_hija=true;
				}
				elseif($aux_id_nota_hija==0)
				 {
				   if(DEBUG){ echo"ACCION Insertar<br>";}
				   $registrar_hija=true;
				}
				else
				{
					if(DEBUG){ echo"Sin Accion<br>";}
				}
			}
		   //----------------------------------------------------///
		  if($GRABAR)
		  {
			  
			  if(($registrar_hija)or($editar_hija))
			  {
				 
				 if($registrar_hija)
				 {
					 //actualizo madre
					 $res="UPDATE  notas SET  nota='$aux_nota', ano='$aux_year', semestre='$aux_semestre', condicion='$aux_condicion' WHERE id='$aux_id_nota_madre' LIMIT 1";
				 if(DEBUG){echo"-->$res<br>";}
				else{$result=$conexion_mysqli->query($res)or die("(1) NOTA :".$conexion_mysqli->error);} 
					  //inserto registro en notas_hija
					 $registrar_hija=false;
					$cons_IN_NH="INSERT INTO notas_hija (id_nota, id_alumno, codigo, semestre, year, sede, id_carrera, nota, observacion, fecha_generacion, condicion, cod_user) VALUES('$aux_id_nota_madre', '$id_alumno', '$codigo', '$aux_semestre', '$aux_year', '$sede_alumno', '$id_carrera', '$aux_nota', '$aux_observacion', '$fecha_actual', '$aux_condicion', '$id_usuario_activo')";
					if(DEBUG){ echo"----> REGISTRAR: $cons_IN_NH<br>";}
					else{ $conexion_mysqli->query($cons_IN_NH)or die("insert hija  $cons_IN_NH".$conexion_mysqli->error);}
					//------------------------------------//
					$evento="Ingresa Nueva Calificacion Semestral Nota [$aux_nota] id_alumno: $id_alumno id_carrera:$id_carrera cod_asignatura:$codigo periodo[$aux_semestre - $aux_year]";
					REGISTRA_EVENTO($evento);
					//--------------------------------------//
				 }
				 //consulta para edicion de hija relacionada
				 if($editar_hija)
				 {
					 $editar_hija=false;
					 $cons_UP_NH="UPDATE notas_hija SET semestre='$aux_semestre', year='$aux_year', nota='$aux_nota', observacion='$aux_observacion', condicion='$aux_condicion', fecha_generacion='$fecha_actual', cod_user='$id_usuario_activo' WHERE id='$aux_id_nota_hija' AND id_alumno='$id_alumno' AND id_carrera='$id_carrera' LIMIT 1";
					 if(DEBUG){ echo"----> EDITAR: $cons_UP_NH<br>";}
					 else{ $conexion_mysqli->query($cons_UP_NH)or die("UP hija ".$conexion_mysqli->error);}
					 
					 $editar_madre=ES_ULTIMA_NOTA($id_alumno, $aux_id_nota_hija, $aux_id_nota_madre);
					 
					 if($editar_madre)
					 {
						//actualizo madre
							 $aux_nota=number_format($aux_nota,1,".","");
							 $res="UPDATE  notas SET id_alumno='$id_alumno', nota='$aux_nota', ano='$aux_year', semestre='$aux_semestre', condicion='$aux_condicion' WHERE id='$aux_id_nota_madre' LIMIT 1";
						 if(DEBUG){echo"-->$res<br>";}
						else{
								$result=$conexion_mysqli->query($res)or die("(2) NOTA :".$conexion_mysqli->error);
								 /////Registro ingreso///
								$evento="Modifica Calificaciones Semestrales Nota[$aux_nota] id_alumno: $id_alumno id_carrera:$id_carrera cod_asignatura:$codigo yearINgresoCarrera: $yearIngresoCarrera periodo[$aux_semestre - $aux_year]";
								REGISTRA_EVENTO($evento);
					//--------------------------------------//
								
							}  
					 }
				 }
			  }
			  else
				{
				 if(DEBUG){ echo"--->No Grabar ni actualizar<br>";}
				}
				 //////
			
		  }
		  else
		  {
			  echo"Codigo $codigo, Nota invalida($aux_nota)<br>"; 
		  }
	  }
	}//fin for
	
		
		list($alumno_es_egresado, $semestre_egreso, $year_egreso)=ES_EGRESADO($id_alumno, $id_carrera, $yearIngresoCarrera);
		if($alumno_es_egresado)
		{
			if(DEBUG){ echo"Alumno cumple condiciones para egreso<br>";}
			$comprobar_proceso_egreso=true;
			
			if($comprobar_proceso_egreso)
			{
				//agrego registro al proceso de egreso si es que no lo tiene
				list($es_egresado_con_registro, $semestre_egreso_con_registro, $year_egreso_con_registro)=ES_EGRESADO_V2($id_alumno, $id_carrera, $yearIngresoCarrera);
				if(!$es_egresado_con_registro)
				{
					if(DEBUG){ echo"Creo registro de egreso a alumno EGRESADO<br>";}
					$cons_IN="INSERT INTO proceso_egreso (id_alumno, id_carrera, yearIngresoCarrera, sede, semestre_egreso, year_egreso, fecha_generacion, cod_user) VALUES ('$id_alumno', '$id_carrera', '$yearIngresoCarrera', '$sede_alumno', '$semestre_egreso', '$year_egreso', '$fecha_hora_actual', '$id_usuario_activo')";
					
					if(DEBUG){echo"--->$cons_IN<br>";}
					else					
					{
						if($conexion_mysqli->query($cons_IN))
						{ echo"INSERTADO OK<br><br>";}
						else
						{ echo"INSERTADO ERROR<br><br>";}
					}
				}
				else
				{
					if(DEBUG){echo"Ya tiene proceso de egreso, actualizar si hay diferencia.<br>";}
					if(($semestre_egreso==$semestre_egreso_con_registro)and($year_egreso==$year_egreso_con_registro))
					{ if(DEBUG){echo"Registros sin Diferencia, mantener igual<br>";}}
					else
					{
						if(DEBUG){echo"Diferencias detectadas [$semestre_egreso - $year_egreso] V/s [$semestre_egreso_con_registro - $year_egreso_con_registro]<br>";}
						$cons_UP_PE="UPDATE proceso_egreso SET semestre_egreso='$semestre_egreso', year_egreso='$year_egreso' WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' LIMIT 1";
						
						if(DEBUG){ echo"--->$cons_UP_PE<br>";}
						else
						{
							if($conexion_mysqli->query($cons_UP_PE))
							{
								$descripcion="Actualizacion de Periodo del Proceso de Egreso de [$semestre_egreso_con_registro - $year_egreso_con_registro] a [$semestre_egreso - $year_egreso] y Año egreso de Alumno a [$year_egreso]";
								REGISTRO_EVENTO_ALUMNO($id_alumno_actual, "notificacion",$descripcion);
								$_SESSION["SELECTOR_ALUMNO"]["egreso"]=$year_egreso;
							}
						}
					}
					
				}
					
				$descripcion="Proceso de egreso creado/actualizado (Todos los ramos Aprobados) periodo egreso [$semestre_egreso - $year_egreso]";
				REGISTRO_EVENTO_ALUMNO($id_alumno_actual, "notificacion",$descripcion);
				$_SESSION["SELECTOR_ALUMNO"]["situacion"]="EG";
	
			}//fin si comprobar proceso egreso
			
		}//fin si es egresado por notas
		else
		{
			//no es egresado elimino registro de egreso si es que lo tiene
			list($es_egresado_con_registro, $semestre_egreso_con_registro, $year_egreso_con_registro)=ES_EGRESADO_V2($id_alumno, $id_carrera, $yearIngresoCarrera);
			if($es_egresado_con_registro)
			{
				if(DEBUG){ echo"Alumno NO Egresado, pero tiene registro de proceso de egreso, Eliminandolo<br>";}
				$cons_D="DELETE FROM proceso_egreso WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' LIMIT 1";
				if(DEBUG){ echo"---> $cons_D<br>";}
				else{ $conexion_mysqli->query($cons_D)or die($conexion_mysqli->error);}
				
				$descripcion="Eliminacion de proceso de egreso a alumno a Alumno (No todos los Ramos Aprobados).";
				REGISTRO_EVENTO_ALUMNO($id_alumno_actual, "Advertencia",$descripcion);
				$_SESSION["SELECTOR_ALUMNO"]["situacion"]="V";
			
			}
		}
		

		$conexion_mysqli->close();
		 
		 
		 //vuelvo a seleccionar al alumno antes de continuar, para actualizar
		$urlDestino="../Notas/graba_nota_individual_final.php?error=$error";
		$url="../buscador_alumno_BETA/enrutador.php?id_alumno=$id_alumno&validador=".md5("GDXT".date("d-m-Y"))."&url=".base64_encode($urlDestino);
		
		if(DEBUG){ echo"URL: $url<br>";}
		else{ header("location: $url");}
			
}
//--------------------------------------------------------------------------//
function VALIDA_NOTA($nota)
{
	if(is_numeric($nota))
	{	
		if(DEBUG){ echo"EN FUNCION: $nota<br>";}
		if (($nota<0)or($nota>7))
		{ return false;}
		else
		{ return true;}
	}
	else
	{ 
		if(empty($nota))
		{ return true;}
		else
		{ return false;}
	}
}

function ES_ULTIMA_NOTA($id_alumno, $id_nota_hija, $id_nota_madre)
{
	require("../../funciones/conexion_v2.php");
	$cons="SELECT MAX(id) FROM notas_hija WHERE id_alumno='$id_alumno' AND id_nota='$id_nota_madre'";
	$sql=$conexion_mysqli->query($cons)or die("ULTIMA:");
	$DX=$sql->fetch_row();
	$max_id_nota_hija=$DX[0];
	$sql->free();
	if(empty($max_id_nota_hija)){ $max_id_nota_hija=0;}
	if(DEBUG){echo"===><strong>FUNCION ES_ULTIMA_NOTA</strong><br>===>$cons <br>===>Comparar: MAX id hija: $max_id_nota_hija -> id nota hija actual: $id_nota_hija<br>";}
	
	if($max_id_nota_hija==$id_nota_hija)
	{ $respuesta=true; if(DEBUG){ echo"Nota hija es la ultima registrada<br>";}}
	else
	{ $respuesta=false; if(DEBUG){ echo"Nota hija NO es la ultima registrada<br>";}}
	$conexion_mysqli->close();
	return($respuesta);
}
?>