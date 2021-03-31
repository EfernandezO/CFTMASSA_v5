<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG",false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Modificacion_datos_de_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_POST)
{  
	$actualizar_session=true;
	//header('content-type: text/html; charset=utf-8');
	if(DEBUG){var_dump($_POST);}
   require("../../../funciones/conexion_v2.php");
   include("../../../funciones/funcion.php");
    include("../../../funciones/funciones_sistema.php");
	include("../../../funciones/VX.php");
    $idb=str_inde($_POST["id"]);
    $rut=str_inde(strtoupper($_POST["rut"]));
    $nombre=str_inde($_POST["nombre"]);
	//$nombre=ucwords(strtolower($nombre));
	
    //$apellido=str_inde($_POST["apellido"]);
	//$apellido=ucwords(strtolower($apellido));
	
	$apellido_P=ucwords(strtolower($_POST["apellido_P"]));
	$apellido_M=ucwords(strtolower($_POST["apellido_M"]));
	
	$apellido_P=str_inde($apellido_P,"");
	$apellido_M=str_inde($apellido_M,"");
	
    $array_carrera=explode("_",$_POST["carrera"]);
	//var_export($array_carrera);
	$carrera=mysqli_real_escape_string($conexion_mysqli, $array_carrera[1]);
	$id_carrera=mysqli_real_escape_string($conexion_mysqli, $array_carrera[0]);

    $direccion=mysqli_real_escape_string($conexion_mysqli, $_POST["direccion"]);
	$direccion=ucwords(strtolower($direccion));
	
    $ciudad=str_inde($_POST["ciudad"]);
	$ciudad=ucwords(strtolower($ciudad));
	$pais_origen=strip_tags($_POST["pais_origen"]);
	
	$sexo=str_inde($_POST["sexo"],"");
	
    $fono=str_inde($_POST["fono"]);
    $jornada=str_inde($_POST["jornada"],0);
   
    $apoderado=str_inde($_POST["apoderado"]);
	$apoderado=ucwords(strtolower($apoderado));
	
    $fonoa=str_inde($_POST["fonoa"]);
    $clave=str_inde($_POST["clave"]);
    $email=str_inde($_POST["email"]);
    $fnac=str_inde($_POST["fnac"]);
    $situacion=str_inde($_POST["situacion"]);
    $sede=str_inde($_POST["sede"]);
    $ingreso=str_inde($_POST["ingreso"],0);
	$year_egreso=str_inde($_POST["year_egreso"],"0");
	
	$nivell=str_inde($_POST["nivel"]);
	$nivel_situacion=str_inde($_POST["nivel_situacion"]);
	
	$rut_apoderado=str_inde($_POST["rut_apoderado"],"");
	$direccion_apoderado=str_inde($_POST["direccion_apoderado"]);
	$direccion_apoderado=ucwords(strtolower($direccion_apoderado));
	
	$ciudad_apoderado=str_inde($_POST["ciudad_apoderado"]);
	$ciudad_apoderado=ucwords(strtolower($ciudad_apoderado));
	
	$idLiceo=mysqli_real_escape_string($conexion_mysqli, $_POST["idLiceo"]);
	$liceo=str_inde($_POST["liceo"],0);
	$liceo_nem=str_inde($_POST["liceo_nem"]);
	$liceo_pais=$_POST["pais_liceo"];
	$liceo_egreso=str_inde($_POST["liceo_egreso"]);
	$liceo_formacion=str_inde($_POST["formacion_liceo"]);
	
	$otro_estudio_U=str_inde($_POST["otro_estudio_U"]);
	$otro_estudio_T=str_inde($_POST["otro_estudio_T"]);
	$otro_estudio_P=str_inde($_POST["otro_estudio_P"]);
	$grupo_carrera=str_inde($_POST["grupo"]);
	
	$fechaRegistro=$_POST["fechaRegistro"];
	$situacion=str_inde($_POST["situacion"]);
	
	///---------------------------------------------------------------------------//
	if($jornada==0){$campoJornada='';}else{ $campoJornada=", jornada='$jornada'";}
	if($liceo==0){$campoLiceo='';}else{$campoLiceo=", liceo='$liceo'";}
	if($ingreso==0){$campoIngreso='';}else{$campoIngreso=", ingreso='$ingreso'";}
	
	$campoFechaRegistro="";
	if(!empty($fechaRegistro)){$campoFechaRegistro=", fecha_registro='$fechaRegistro'";}
	//----------------------------------------------------------------------------//
	
	
	///////////////////
	//datos antecedentes
	@$A_licencia_media=str_inde($_POST["A_licencia_media"],"0");
	@$A_certificado_nacimiento=str_inde($_POST["A_certificado_nacimiento"],"0");
	@$A_foto_carnet=str_inde($_POST["A_foto_carnet"],"0");
	@$A_pase_escolar=str_inde($_POST["A_pase_escolar"],"0");
	@$A_certificado_residencia=str_inde($_POST["A_certificado_residencia"],"0");
		if(empty($A_licencia_media)){ $A_licencia_media=0;}
		if(empty($A_certificado_nacimiento)){ $A_fcertificado_nacimiento=0;}
		if(empty($A_foto_carnet)){ $A_foto_carnet=0;}
		if(empty($A_pase_escolar)){ $A_pase_escolar=0;}
		if(empty($A_certificado_residencia)){ $A_certificado_residencia=0;}
	///////////////////////
	REGISTAR_DOCUMENTOS($idb, $A_licencia_media, $A_certificado_nacimiento, $A_foto_carnet, $A_pase_escolar, $A_certificado_residencia);
	
	
	////////actualiza carrera de notas
	$actualiza_notas_carrera=$_POST["actualiza_notas_carrera"];
	//if($actualiza_notas_carrera=="si"){ ACTUALIZA_CARRERA_NOTAS($idb, $id_carrera);}
	////////////////
	//eliminA REG ACADEMICO
	$eliminar_registro_academico=str_inde($_POST["borrar_registro_academico"],"no");
	if($eliminar_registro_academico=="si"){ ELIMINAR_REGISTRO_ACADEMICO($idb);}
	///////////////////
	$cons_B="SELECT COUNT(id) FROM alumno WHERE NOT id='$idb' AND rut='$rut' AND carrera='$carrera'";
	//echo"$cons_B<br>";
	$sql=$conexion_mysqli->query($cons_B)or die($conexion_mysqli->error);
	$DA=$sql->fetch_row();
	$coincidencias=$DA[0];
	//echo"coincidencias $coincidencias<br>";
	if($coincidencias>1)
	{
		//rut repetido
		$error=1;
		//echo"Rut Repetido<br>";
		header("location: buscaalumno2_tab.php?error=$error");
	}
	else
	{
		
		$campo_nem='';
		if(is_numeric($liceo_nem)){$campo_nem=", liceo_nem='$liceo_nem'";}
		
		//echo"Rut Correcto<br>";
		$cons_UP="UPDATE  alumno set rut='$rut', nombre='$nombre', apellido_P='$apellido_P', apellido_M='$apellido_M', id_carrera='$id_carrera',  carrera='$carrera', grupo='$grupo_carrera', direccion='$direccion', ciudad='$ciudad', pais_origen='$pais_origen', fono='$fono' $campoJornada $campoLiceo , idLiceo='$idLiceo' $campo_nem, liceo_pais='$liceo_pais', liceo_egreso='$liceo_egreso', liceo_formacion='$liceo_formacion', otro_estudio_U='$otro_estudio_U', otro_estudio_T='$otro_estudio_T', otro_estudio_P='$otro_estudio_P', apoderado='$apoderado', fonoa='$fonoa', clave='$clave', email='$email', fnac='$fnac', situacion='$situacion', sede='$sede' $campoIngreso , year_egreso='$year_egreso', nivel='$nivell', nivel_condicion='$nivel_situacion', rut_apoderado='$rut_apoderado', direccion_apoderado='$direccion_apoderado', ciudad_apoderado='$ciudad_apoderado', sexo='$sexo' $campoFechaRegistro where id='$idb' LIMIT 1";
	 
	 	if(DEBUG){echo"$cons_UP<br>";}
	 
    	if($conexion_mysqli->query($cons_UP))
		{
			//ejecuto consulta correctamente
			//echo'datos modificados<br>';
			$error=0;
			 /////Registro ingreso///
		 $evento="Modifica Datos de Alumno -> ID.$idb";
		 REGISTRA_EVENTO($evento);
		 ///////////////////////
		}
		else
		{
			//consulta no ejecutada
			//echo'datos NO modificados<br>'.mysql_error();
			echo $cons_UP." <br >Error actualiza alumno ".$conexion_mysqli->error;
			$error=2;
		}	
		////////////////
		if($actualizar_session)
		{
			if($error==0)
			{
				///////actualizo datos de session
				$_SESSION["SELECTOR_ALUMNO"]["id_carrera"]=$id_carrera;
				$_SESSION["SELECTOR_ALUMNO"]["carrera"]=$carrera;
				$_SESSION["SELECTOR_ALUMNO"]["nivel"]=$nivell;
				$_SESSION["SELECTOR_ALUMNO"]["sexo"]=$sexo;
				$_SESSION["SELECTOR_ALUMNO"]["jornada"]=$jornada;
				$_SESSION["SELECTOR_ALUMNO"]["sede"]=$sede;
				
			}
		}
		//////////////////
		$conexion_mysqli->close();
		
		//redireccion final
		$url="msj_final.php?error=$error";
		if(DEBUG){ echo"Error: $error<br>";}
		else{header("location: $url");}
	}
}
else
{
	header("location: modificaalumno.php");
}
///////////////////////
function ACTUALIZA_CARRERA_NOTAS($id_alumno, $id_carrera_new)
{
	require("../../../funciones/conexion_v2.php");
	$cons_UP="UPDATE notas SET id_carrera='$id_carrera_new' WHERE id_alumno='$id_alumno'";
	if(DEBUG){ echo"ACTUALIZA_CARRERA_NOTAS: $cons_UP<br>";}
	else
	{ $conexion_mysqli->query($cons_UP)or die("Notas :".$conexion_mysqli->error);}
	$conexion_mysqli->close();
}
/////////////////////////////////////////////
function REGISTAR_DOCUMENTOS($id_alumno, $doc_1, $doc_2, $doc_3, $doc_4, $doc_5)
{
	require("../../../funciones/conexion_v2.php");
	$cons="SELECT COUNT(id) FROM alumno_antecedentes WHERE id_alumno='$id_alumno'";
	if(DEBUG){ echo"<br>-->$cons<br>";}
	$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$D=$sql->fetch_row();
	$registros_encontrados=$D[0];
	
	$fecha_actual=date("Y-m-d");
	$usuario_actual=$_SESSION["USUARIO"]["id"];
	
	$sql->free();
	if(empty($registros_encontrados)){ $registros_encontrados=0;}
	
	if(DEBUG){ echo"Registros encontrados: $registros_encontrados<br>";}
	if($registros_encontrados>0)
	{
		//actualizamos
		$campo_valor="licencia_media='$doc_1', certificado_nacimiento='$doc_2', foto_carnet='$doc_3', pase_escolar='$doc_4', certificado_residencia='$doc_5', fecha='$fecha_actual', user='$usuario_actual'";
		$cons_X="UPDATE alumno_antecedentes SET $campo_valor WHERE id_alumno='$id_alumno' LIMIT 1";
	}
	else
	{
		//insertamos
		$campos="id_alumno, licencia_media, certificado_nacimiento, foto_carnet, pase_escolar, certificado_residencia, fecha, user";
		$valores="'$id_alumno', '$doc_1', '$doc_2', '$doc_3', '$doc_4', '$doc_5', '$fecha_actual', '$usuario_actual'";
		$cons_X="INSERT INTO alumno_antecedentes ($campos) VALUES ($valores)";
	}
	
	if(DEBUG){ echo"REGISTRAR_DOCUMENTOS: $cons_X<br><br>";}
	else{ $conexion_mysqli->query($cons_X)or die("Funcion :".$conexion_mysqli->error);}
	$conexion_mysqli->close();
}
///////////
function ELIMINAR_REGISTRO_ACADEMICO($id_alumno)
{
	require("../../../funciones/conexion_v2.php");
	$consX="DELETE FROM notas WHERE id_alumno='$id_alumno'";
	if(DEBUG){ echo"ELIMINA REGISTRO ACADEMICO: $consX<br>";}
	else{ $conexion_mysqli->query($consX)or die(mysql_error());}
	$conexion_mysqli->close();
}
?>