<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("SOLICITUDES->verCertificados");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
$continuar=false;
//---------------------------------------------//
$privilegio=$_SESSION["USUARIO"]["privilegio"];
$tipoUsuario=$_SESSION["USUARIO"]["tipo"];

switch($tipoUsuario)
{
	case"alumno":
		$tipo_receptor="alumno";
		$id_alumno=$_SESSION["USUARIO"]["id"];
		$id_carrera=$_SESSION["USUARIO"]["id_carrera"];
		$yearIngresoCarrera=$_SESSION["USUARIO"]["yearIngresoCarrera"];
		$sede_alumno=$_SESSION["USUARIO"]["sede"];
		$continuar=true;
		$urlOrigen="../../Alumnos/certificadosAlumno/certificadoAlumno1_v1.php";
		$urlDestino="../../Alumnos/certificadosAlumno/certificadoAlumno1_v1.php";
		break;
	default:	
		if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
		{$continuar=true;}
		else
		{ $continuar=false;}	
		
		$tipo_receptor="alumno";
		$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
		$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
		$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];	
		$yearIngresoCarrera=$_SESSION["SELECTOR_ALUMNO"]["yearIngresoCarrera"];	
		$urlOrigen="../../buscador_alumno_BETA/HALL/index.php";
		$urlDestino="msj_final_creacion.php";
}
//---------------------------------------------//
if($continuar)
{
	$error="debug";
	$array_formatos_compatibles=array("jpg", "jpeg", "png", "gif");
	$path="../../CONTENEDOR_GLOBAL/solicitudes_comprobantes/";
	
	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
	if(DEBUG){ var_dump($_POST);}
	
	$tipo=str_inde($_POST["tipo"]);
	$categoria=str_inde($_POST["categoria"]);
	$observacion=str_inde($_POST["observacion"],"");
	
	$semestre_consulta=str_inde($_POST["semestre"]);
	$year_consulta=str_inde($_POST["year"]);
	
	if($_FILES["archivo"])
	{
		if(DEBUG){echo"Viene Archivo<br>";}
		if(DEBUG){ var_dump($_FILES); echo"<br>";}
		 $nombre_archivo=$_FILES["archivo"]['name'];
		 $tipo_archivo=$_FILES["archivo"]['type'];
		 $tmp_nombre_archivo=$_FILES["archivo"]['tmp_name'];
		 $error_archivo=$_FILES["archivo"]['error'];
		 $size_archivo=$_FILES["archivo"]['size'];
		 $extencion=explode(".",$nombre_archivo);
		 $extencion=end($extencion);
		 
		 if(($error_archivo==4)or($nombre_archivo==""))
		 { $hay_archivo=false;}
		 else
		 { $hay_archivo=true;}
		 
	}
	else
	{ $hay_archivo=false; if(DEBUG){echo"NO viene Archivo<br>";}}
	//-------------------------------------------------------------------///
	if($hay_archivo)
	{
		if(DEBUG){ echo"Hay Archivo Cargar<br>";}
		if(in_array($extencion, $array_formatos_compatibles))
		  {
			  if(DEBUG){ echo"Archivo Compatible<br>";}
			  $nombre_archivo_new="CS_".$id_alumno."_".$id_carrera."_[".rand(1111,9999)."-".date("YmdHis")."].".$extencion;
			  if(move_uploaded_file($tmp_nombre_archivo, $path.$nombre_archivo_new))
			  { $archivo_cargado=true; if(DEBUG){ echo"Archivo Cargado<br>";}}
			  else
			  { $archivo_cargado=false; if(DEBUG){ echo"Archivo NO Cargado<br>";}}
		  }
		  else
		  { if(DEBUG){ echo"Archivo No Compatible<br>";}}
	}
	else
	{
		if(DEBUG){ echo"NO hay Archivo<br>";}
		$archivo_cargado=false;
		$nombre_archivo_new="";
	}
	if(!$archivo_cargado)
	{if(!empty($nombre_archivo_new)){ $nombre_archivo_new="";}}
	
	if(DEBUG){ echo"Nombre Archivo: $nombre_archivo_new<br>";}
	//------------------------------------------------------------------------//
	
	$tipo_solicitante=$_SESSION["USUARIO"]["privilegio"];
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$fecha_hora_actual=date("Y-m-d H:i:s");
	
	$autorizado="no";
	$estado="pendiente";
	
	$crear_consulta=true;
	//////////////////////////////////////////////////////
	$crear_consulta=TIENE_OTRA_SOLICITUD("alumno", $id_alumno, $id_carrera, $tipo, $categoria);
	$numero_solicitudes_tipo=NUM_TOTAL_SOLICITUDES("alumno", $id_alumno, $id_carrera, $tipo, $categoria);
	//////////////////////////////////////////////////////
	
	
	if($crear_consulta)
	{
		$campos="tipo, semestre, year, categoria, observacion, tipo_solicitante, id_solicitante, id_carrera_solicitante, fecha_hora_solicitud, tipo_receptor, id_receptor, id_carrera_receptor, yearIngresoCarrera_receptor, sede_receptor, id_autorizador, autorizado, tipo_autorizador, fecha_hora_autorizacion, archivo_autorizacion, tipo_creador, id_creador, fecha_hora_creacion, estado";
		
		$valores="'$tipo', '$semestre_consulta', '$year_consulta', '$categoria', '$observacion', '$tipo_solicitante', '$id_usuario_actual', '0', '$fecha_hora_actual', '$tipo_receptor', '$id_alumno', '$id_carrera', '$yearIngresoCarrera', '$sede_alumno', '0', '$autorizado', '', '0000-00-00 00:00:00', '$nombre_archivo_new', '', '0', '0000-00-00 00:00:00', '$estado'";

		$cons_IN="INSERT INTO solicitudes ($campos) VALUES ($valores)";
		if(DEBUG){ echo"--->$cons_IN<br><br>"; $id_solicitud_generada="S0";}
		else
		{
			 $conexion_mysqli->query($cons_IN)or die($conexion_mysqli->error);
			 $id_solicitud_generada=$conexion_mysqli->insert_id;
			 $error="S0";
			 
		/////Registro evento///
		 include("../../../funciones/VX.php");
		 if($privilegio=="ALUMNO")
		 { $evento="Alumno ($id_alumno) Realiza Solicitud de $tipo ($categoria)";}
		 else
		 { $evento="crea solicitud de $tipo ($categoria) para alumno ($id_alumno)";}
		 REGISTRA_EVENTO($evento);
		 ///////////////////////
		}
		//-------------------------------------------------------------------------------//
		//determinar gratuidad para cada caso
		if(DEBUG){ echo"<strong>VERIFICAR GRATUIDAD</strong><br>";}
		$permitir_gratuidad_1=false;
		$permitir_gratuidad_2=false;
		$permitir_gratuidad_3=false;
		$A_ingreso=$yearIngresoCarrera;
		switch($tipo)
		{
			case"certificado":
				switch($categoria)
				{
					case"alumno_regular":
						$permitir_gratuidad_1=true;
						if(DEBUG){ echo"Certificado Tipo Alumno_regular, Apto para evaluar gratuidad<br>";}
						if($numero_solicitudes_tipo>0){
							if(DEBUG){ echo"$numero_solicitudes_tipo solicitudes ya existentes NO apto para gratuidad<br>";}
							}else{
								$permitir_gratuidad_2=true; 
								if(DEBUG){ echo"$numero_solicitudes_tipo solicitud previas, apto para gratuidad<br>";}
							}
						if($yearIngresoCarrera==date("Y")){ 
							$permitir_gratuidad_3=true; 
							if(DEBUG){ echo"Año ingreso Alumno ($A_ingreso) apto para gratuidad <br>";}}
							else{ if(DEBUG){ echo"Año ingreso Alumno ($A_ingreso) no apto para gratuidad <br>";}
							}	
						break;
					case"titulo_en_tramite":
						$permitir_gratuidad_1=true;
						$permitir_gratuidad_2=true;
						$permitir_gratuidad_3=true;
						break;
					default:	
						if(DEBUG){ echo"Tipo Certificado NO corresponde para Gratuidad<br>";}
				}
				break;
			default:
				if(DEBUG){ echo"Tipo Solicitud  NO corresponde para Gratuidad<br>";}
		}
		
		
		//---------------------------------------------------------------------------------------------//
		
		if($permitir_gratuidad_1 and $permitir_gratuidad_2 and $permitir_gratuidad_3)
		{
			if(DEBUG){ echo"----> brindar GRATUIDAD<br>";}
			$fecha_generacion=date("Y-m-d H:i:s");
			if(DEBUG){ echo"Otorgando Gratuidad a Solicitud...<br>";}
			$cons_solicitud_gratuidad="UPDATE solicitudes SET id_autorizador='0', autorizado='si', tipo_autorizador='sistema', fecha_hora_autorizacion='$fecha_generacion', metodo_autorizacion='gratuita', id_pago='0' WHERE id='$id_solicitud_generada' LIMIT 1";
		
			if(DEBUG){ echo"--->$cons_solicitud_gratuidad<br>";}
			else
			{ 
			
				if($id_solicitud_generada>0)
				{
					$conexion_mysqli->query($cons_solicitud_gratuidad)or die($conexion_mysqli->error);
					$error="S3";
				
				 /////Registro evento///
				 $evento="Autoriza Gratuitamente Solicitud: ($id_solicitud_generada)";
				 REGISTRA_EVENTO($evento);
				 ///////////////////////
				}
			}
		}else{if(DEBUG){ echo"---->NO brindar GRATUIDAD<br>";}}
		
		
	}
	else{ $error="S1";}
	
	$conexion_mysqli->close();
	
	if(DEBUG){ echo"Error: $error<br>urlDestino: $urlDestino<br>";}
	else{ header("location: $urlDestino?error=$error");}
	
}
else
{
	
	if(DEBUG) { echo"URL: $urlOrigen<br>";}
	else{ header("location: $url");}
}
//////////////////////////////////////////////////////////
function TIENE_OTRA_SOLICITUD($tipo_receptor, $id_receptor, $id_carrera_receptor, $tipo, $categoria)
{
	require("../../../funciones/conexion_v2.php");
	$fecha_actual=date("Y-m-d");
	$fecha_limite=date("Y-m-d", strtotime("$fecha_actual -10 days"));///fecha limite =fecha corte +10 dias
	switch($tipo_receptor)
	{
		case"alumno":
			$cons="SELECT COUNT(id) FROM solicitudes WHERE tipo_receptor='$tipo_receptor' AND id_receptor='$id_receptor' AND id_carrera_receptor='$id_carrera_receptor' AND autorizado='no' AND tipo='$tipo' AND categoria='$categoria' AND fecha_hora_solicitud>='$fecha_limite'";
			$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
				$D=$sql->fetch_row();
				$coincidencias=$D[0];
				$sql->free();
				if(DEBUG){ echo"--->$cons<br>coincidencias:$coincidencias<br><br>";}
			break;
		default:
				$coincidencias=1;
	}
	
	if($coincidencias>0)
	{ $crear_consulta=false;}
	else{ $crear_consulta=true;}
	$conexion_mysqli->close();
	return($crear_consulta);
}
function NUM_TOTAL_SOLICITUDES($tipo_receptor, $id_receptor, $id_carrera_receptor, $tipo, $categoria)
{
	require("../../../funciones/conexion_v2.php");
	if(DEBUG){ echo"<strong>NUM_TOTAL_SOLICITUDES</strong><br>";}
	switch($tipo_receptor)
	{
		case"alumno":
			$cons="SELECT COUNT(id) FROM solicitudes WHERE tipo_receptor='$tipo_receptor' AND id_receptor='$id_receptor' AND id_carrera_receptor='$id_carrera_receptor' AND tipo='$tipo' AND categoria='$categoria'";
			$sql=$conexion_mysqli->query($cons);
				$D=$sql->fetch_row();
				$num_solicitudes_tipo=$D[0];
				$sql->free();
			break;
		default:
				$num_solicitudes_tipo=1;
	}
	if(DEBUG){ echo"--->$cons<br>Num Total Solicitudes Tipo:$num_solicitudes_tipo<br><br>";}
	$conexion_mysqli->close();
	return($num_solicitudes_tipo);
}
?>