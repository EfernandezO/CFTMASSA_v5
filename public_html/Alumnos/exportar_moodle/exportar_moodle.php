<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("exportarAlumnosMoodle");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(!DEBUG){ 
				header("Content-Type: plain/text");
				header("Content-Disposition: Attachment; filename=exportar_moodle_".date("d-m-Y_His")."_".rand(1111,9999).".txt");
				header("Pragma: no-cache");
			}

if($_POST)
{
	$separador=';';
	$campos_obligatorios="username".$separador."password".$separador."firstname".$separador."lastname".$separador."email";
	
	  require("../../../funciones/conexion_v2.php");
	  require("../../../funciones/VX.php");
	  require("../../../funciones/class_ALUMNO.php");
	if(DEBUG){var_dump($_POST);}
	
	$fecha_actual=date("Y-m-d");
	
	
	$yearContrato=$_POST["yearContrato"];
	$marcar_cargado=$_POST["marcar_cargado"];
	
	
	
	
	$cons_B="SELECT DISTINCT(id_alumno) FROM contratos2 WHERE ano='$yearContrato' ORDER by id";
	
	$sql_B=$conexion_mysqli->query($cons_B)or die($conexion_mysqli->error);
	$num_alumno=$sql_B->num_rows;
	if(DEBUG){ echo"$cons_B<br> NUM ALUMNOS: $num_alumno Encontratos(posibles)<br>";}
	
	if($num_alumno>0)
	{
		$rutConErrores="";
		if($marcar_cargado=="si")
			{
				$evento="Exportar Alumnos a Moodle -> yearContrato: $yearContrato";
				REGISTRA_EVENTO($evento);
			}
		
		echo $campos_obligatorios."\r\n";
		while($A=$sql_B->fetch_row())
		{
			$A_id=$A[0];
			if(DEBUG){ echo"<br><strong>id_alumno: $A_id </strong><br>";}
			
			//verifico si ya fue cargado
			$AR_fecha_generacion="";
			$estaCargado=false;
			$cons_AR="SELECT * FROM alumno_registros WHERE id_alumno='$A_id' AND descripcion='Exportar a Moodle'";
			if(DEBUG){ echo"$cons_AR<br>";}
			$sqli_AR=$conexion_mysqli->query($cons_AR)or die($conexion_mysqli->error);
			$num_reg_AR=$sqli_AR->num_rows;
			if(DEBUG){ echo"=== $num_reg_AR<br>";}
			if($num_reg_AR>0)
			{
				$AR=$sqli_AR->fetch_assoc();
				$AR_fecha_generacion=$AR["fecha_generacion"];
				if(DEBUG){ echo"=== $AR_fecha_generacion<br>";}
				$estaCargado=true;
			}
			
			
			if(!$estaCargado){
			
				$ALUMNO=new ALUMNO($A_id);
				$situacionActual=$ALUMNO->getUltimaSituacionMat();
				$A_email=$ALUMNO->getEmail();
				$A_emailInstitucional=$ALUMNO->getEmailInstitucional();
				
				$A_emailFinal=$A_emailInstitucional; //por defecto
				
				$tieneEmail=true;
				$tieneEmailPersonal=true;
				$tieneEmailInstitucional=true;
				
				if(empty($A_emailInstitucional)){ $tieneEmailInstitucional=false;}
				if(empty($A_email)or($A_email=="Sin Registro") or($A_email==" ")){$tieneEmailPersonal=false;}
				
				if(!$tieneEmailInstitucional){if($tieneEmailPersonal){$A_emailFinal=$A_email;}}
				
				if((!$tieneEmailPersonal)and(!$tieneEmailInstitucional)){$tieneEmail=false;}
				
				
				$A_rut=strtolower($ALUMNO->getRut());
				
				if(DEBUG){ echo"situacion Actual: $situacionActual tiene Email: $tieneEmail<br>";}
				
				$cumpleSituacionAcademica=false;
				if(($situacionActual=="V")or($situacionActual=="EG")){$cumpleSituacionAcademica=true;}
				
				if(($cumpleSituacionAcademica)and(!$tieneEmail)){$rutConErrores.=$A_rut."\r\n";}
				
				if(($cumpleSituacionAcademica)and($tieneEmail)){
					
					$A_nombre=ucwords(strtolower($ALUMNO->getNombre()));
					$A_apellido_P=ucwords(strtolower($ALUMNO->getApellido_P()));
					$A_apellido_P=str_replace("ñ","n",$A_apellido_P);
					$A_apellido_P=str_replace("Ñ","N",$A_apellido_P);
					
					$A_apellido_M=ucwords(strtolower($ALUMNO->getApellido_M()));
					$A_apellido_M=str_replace("ñ","n",$A_apellido_M);
					$A_apellido_M=str_replace("Ñ","N",$A_apellido_M);
					
					
					$clave_predeterminada="Ma_".strtolower($A_rut);
				//verifico
					if($marcar_cargado=="si")
					{
						///registro alumno_registros
						$tipo_registro_001="notificacion";
						$descripcion_registro_001="Exportar a Moodle";
						REGISTRO_EVENTO_ALUMNO($A_id, $tipo_registro_001, $descripcion_registro_001);
					}
				
				echo $A_rut.$separador.$clave_predeterminada.$separador.$A_nombre.$separador.$A_apellido_P." ".$A_apellido_M.$separador.$A_emailFinal."\r\n";
				}else{if(DEBUG){ echo"Alumno no cumple con situacion o faltan datos...<br>";}}
			}
			else{ if(DEBUG){ echo"--->Alumno ya cargado el $AR_fecha_generacion<br>";}}
		}
		echo"\r\n";
		echo"___________________SECCION INFORMATIVA BORRAR PARA CARGAR_________________________________\r\n";
		echo"$rutConErrores";
		echo"_____________________FIN SECCION INFORMATIVA_______________________________________________\r\n";
	}
	else{echo"No se encontraron Alumnos<br>";}
	
	$sql_B->free();
	$conexion_mysqli->close();
	@mysql_close($conexion); 
}
else
{
	echo"Sin Datos Para Generar<br>";
}
?>