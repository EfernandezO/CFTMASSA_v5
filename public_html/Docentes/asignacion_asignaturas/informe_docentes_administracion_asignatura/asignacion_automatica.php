<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("informe_asignacion_asignatura_docente_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

if($_POST)
{
	 require("../../../../funciones/conexion_v2.php");
	 include("../../../../funciones/VX.php");
	 
	 $id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$fecha_actual=date("Y-m-d");
	if(DEBUG){ var_dump($_POST);}
	$sede=mysqli_real_escape_string($conexion_mysqli, $_POST["sede"]);
	$semestre=mysqli_real_escape_string($conexion_mysqli, $_POST["semestre"]);
	$year=mysqli_real_escape_string($conexion_mysqli, $_POST["year"]);
	
	$DOCENTE=$_POST["DOCENTE"];
	$valor_hora=$_POST["valor_hora"];
	$numero_asignaturas=$_POST["numero_asignaturas"];
	if(DEBUG){ echo"<br>Listado docentes<br><br>";}
	
	
	$cod_asignatura=96;
	$id_carrera="7";
	$grupo="A";
	$jornada="D";
	
	$error="ADA1";//todos no
	
	foreach($DOCENTE as $id_docente => $considerar)
	{
			$aux_total=0;
			$numero_cuotas=1;
			$valor_hora_docente=$valor_hora[$id_docente];
			$numero_asignaturas_docente=$numero_asignaturas[$id_docente];
			if(DEBUG){ echo"id_docente: $id_docente  valor hora: $valor_hora_docente numero_asignaturas: $numero_asignaturas_docente (considerar)-> $considerar<br>";}
		if($considerar=="si")
		{	
			$error="ADA0";//todo ok a menos que exista error
			$aux_total=($valor_hora_docente*$numero_asignaturas_docente);
			
			///----------------------------------------------------------------------//
			if((is_numeric($numero_asignaturas_docente))and($numero_asignaturas_docente>0))
			{$continuar_1=true;}
			else{ $continuar_1=false; if(DEBUG){echo"indique Numero de Horas Correcto...";}}
			
			if((is_numeric($valor_hora_docente))and($valor_hora_docente>0))
			{$continuar_2=true;}
			else{ $continuar_2=false; if(DEBUG){echo"indique Valor de Hora Correcto...";}}
	
			
		
		if(($continuar_1)and($continuar_2))
		{
			///busca asignaciones previas
			$cons="SELECT COUNT(id) FROM toma_ramo_docente WHERE id_funcionario='$id_docente' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND semestre='$semestre' AND year='$year' AND sede='$sede' AND condicion='pendiente'";
			
			$sqli=$conexion_mysqli->query($cons)or die("ERROR: ".$conexion_mysqli->error);
			
				$Dx=$sqli->fetch_row();
				$coincidencias=$Dx[0];
				if(DEBUG){echo"|---->".$cons."<br> Coincidencias:".$coincidencias." ERROR: ".$conexion_mysqli->error."<br>";}
				
				if($coincidencias>0){ $grabar=false;  if(DEBUG){echo"Asignacion de Hora Ya Existe como pendiente...Verificar";}}
				else{ $grabar=true;}
			$sqli->free();
		}
		else{$grabar=false;}
		
		//----------------------------------------------------///
			if($grabar)
			{
				$condicion="pendiente";
				$campos="id_funcionario, id_carrera, jornada, grupo, cod_asignatura, numero_horas, valor_hora, total, numero_cuotas, semestre, year, sede, condicion, fecha_generacion, cod_user";
				$valores="'$id_docente', '$id_carrera', '$jornada', '$grupo', '$cod_asignatura', '$numero_asignaturas_docente', '$valor_hora_docente', '$aux_total', '$numero_cuotas', '$semestre', '$year', '$sede', '$condicion', '$fecha_actual', '$id_usuario_actual'";
				$cons_IN="INSERT INTO toma_ramo_docente ($campos) VALUES ($valores)";
				
				if(DEBUG){ echo"---->$cons_IN <br>";}
				else
				{
					
					$conexion_mysqli->query($cons_IN)or die("graba_asignaciones ".$conexion_mysqli->error);
					//------------------------------------------------//
					
					$evento="Agrega Asignacion (Automatica) a Docente id_funcionario: $id_docente id_carrera: $id_carrera cod_asignatura: $cod_asignatura sede: $sede [$semestre - $year] n. cuotas: $numero_cuotas";
					REGISTRA_EVENTO($evento);
					
					$descripcion="Agrega Asignacion (Automatica)id_carrera: $id_carrera jornada: $jornada Grupo: $grupo cod_asignatura: $cod_asignatura sede: $sede Semestre: $semestre Year: $year";
					REGISTRO_EVENTO_FUNCIONARIO($id_docente, "notificacion", $descripcion);
					//---------------------------------------//
					
					
				}
			}
			else
			{ 
				$error="ADA2";//error en algun caso
				if(DEBUG){echo"No se Puede Grabar esta Asignacion";};
			}
			//-----------------------------------------------------------------------//
		}//fin si considerar
	}
}
else{ $error="ADA3";}


$url="asignacion_automatica_2.php?error=$error";
if(DEBUG){ echo"URL: $url<br>";}
else{ header("location: $url");}
?>