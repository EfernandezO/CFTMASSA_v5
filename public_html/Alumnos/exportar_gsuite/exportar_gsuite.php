<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("exportarAlumnosGsuite");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
set_time_limit(30);
if(!DEBUG){ 
				header("Content-Type: plain/text");
				header("Content-Disposition: Attachment; filename=exportar_gsuite_".date("d-m-Y_His")."_".rand(1111,9999).".txt");
				header("Pragma: no-cache");
			}

if($_POST)
{
	$separador=',';
	$campos_obligatorios="First Name".$separador."Last Name".$separador."Email Address".$separador."Password".$separador."Org Unit Path".$separador."Employee ID".$separador."New Primary Email";
	require("../../../funciones/funciones_sistema.php");
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/VX.php");
	require("../../../funciones/class_ALUMNO.php");
	if(DEBUG){var_dump($_POST);}
	
	$fecha_actual=date("Y-m-d");
	
	
	$yearContrato=$_POST["yearContrato"];
	$marcar_cargado=$_POST["marcar_cargado"];
	
	
	$ARRAY_EMAIL=array();
	
	$cons_B="SELECT DISTINCT(id_alumno) FROM contratos2 WHERE ano='$yearContrato' ORDER by id";
	
	$sql_B=$conexion_mysqli->query($cons_B)or die($conexion_mysqli->error);
	$num_alumno=$sql_B->num_rows;
	if(DEBUG){ echo"$cons_B<br> NUM ALUMNOS: $num_alumno Encontratos(posibles)<br>";}
	
	if($num_alumno>0)
	{
		$rutConErrores="";
		if($marcar_cargado=="si")
			{
				$evento="Exportar Alumnos a gsuite -> yearContrato: $yearContrato";
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
			$cons_AR="SELECT * FROM alumno_registros WHERE id_alumno='$A_id' AND descripcion='Exportar a Gsuite'";
			if(DEBUG){ echo"$cons_AR<br>";}
			$sqli_AR=$conexion_mysqli->query($cons_AR)or die($conexion_mysqli->error);
			$num_reg_AR=$sqli_AR->num_rows;
			if(DEBUG){ echo"-> num registros: $num_reg_AR<br>";}
			if($num_reg_AR>0)
			{
				$AR=$sqli_AR->fetch_assoc();
				$AR_fecha_generacion=$AR["fecha_generacion"];
				if(DEBUG){ echo"-> fecha Generacion: $AR_fecha_generacion<br>";}
				$estaCargado=true;
			}
			
			
			if(!$estaCargado){
			
				$ALUMNO=new ALUMNO($A_id);
				$situacionActual=$ALUMNO->getUltimaSituacionMat();
				$A_email=strtolower($ALUMNO->getEmail());
				$A_sede=$ALUMNO->getSedeActual();
				$A_emailInstitucional=$ALUMNO->getEmailInstitucional();
				$A_emailInstitucional="";
				
				$tieneEmailInstitucional=true;
				if(empty($A_emailInstitucional)){$tieneEmailInstitucional=false;}
				
				$A_rut=strtolower($ALUMNO->getRut());
				
				if(DEBUG){ echo"situacion Actual: $situacionActual tiene EmailInstitucional: $tieneEmailInstitucional<br>";}
				
				$cumpleSituacionAcademica=false;
				if(($situacionActual=="V")or($situacionActual=="EG")){$cumpleSituacionAcademica=true;}
				
				if(($cumpleSituacionAcademica)and($tieneEmailInstitucional)){$rutConErrores.=$A_rut." -> ya tiene email institucional [$A_emailInstitucional]\r\n";}
				
				if(($cumpleSituacionAcademica)and(!$tieneEmailInstitucional)){
					
					$A_nombre=strtolower($ALUMNO->getNombre());
					$A_nombre=str_replace("�","n",$A_nombre);
					$A_nombre=str_replace("ñ","n",$A_nombre);
					$A_nombre=str_replace("�","n",$A_nombre);
					$A_nombre=str_replace("�","a",$A_nombre);
					$A_nombre=str_replace("�","e",$A_nombre);
					$A_nombre=str_replace("�","i",$A_nombre);
					$A_nombre=str_replace("�","o",$A_nombre);
					$A_nombre=str_replace("�","u",$A_nombre);

					$A_nombre=str_replace("á","a",$A_nombre);
					$A_nombre=str_replace("é","e",$A_nombre);
					$A_nombre=str_replace("í","i",$A_nombre);
					$A_nombre=str_replace("ó","o",$A_nombre);
					$A_nombre=str_replace("ú","u",$A_nombre);

					$A_nombre=str_replace("Á","a",$A_nombre);
					$A_nombre=str_replace("É","e",$A_nombre);
					$A_nombre=str_replace("Í","i",$A_nombre);
					$A_nombre=str_replace("Ó","o",$A_nombre);
					$A_nombre=str_replace("Ú","u",$A_nombre);
					$A_nombre=utf8_decode($A_nombre);
					
					$A_apellido_P=strtolower($ALUMNO->getApellido_P());
					$A_apellido_P=str_replace("�","n",$A_apellido_P);
					$A_apellido_P=str_replace("�","n",$A_apellido_P);
					$A_apellido_P=str_replace("�","a",$A_apellido_P);
					$A_apellido_P=str_replace("�","e",$A_apellido_P);
					$A_apellido_P=str_replace("�","i",$A_apellido_P);
					$A_apellido_P=str_replace("�","o",$A_apellido_P);
					$A_apellido_P=str_replace("�","u",$A_apellido_P);

					$A_apellido_P=str_replace("á","a",$A_apellido_P);
					$A_apellido_P=str_replace("é","e",$A_apellido_P);
					$A_apellido_P=str_replace("í","i",$A_apellido_P);
					$A_apellido_P=str_replace("ó","o",$A_apellido_P);
					$A_apellido_P=str_replace("ú","u",$A_apellido_P);

					$A_apellido_P=str_replace("Á","a",$A_apellido_P);
					$A_apellido_P=str_replace("É","e",$A_apellido_P);
					$A_apellido_P=str_replace("Í","i",$A_apellido_P);
					$A_apellido_P=str_replace("Ó","o",$A_apellido_P);
					$A_apellido_P=str_replace("Ú","u",$A_apellido_P);
					//$A_apellido_P=str_replace("&ntilde;","n",$A_apellido_P);
					$A_apellido_P=str_replace("ñ","n",$A_apellido_P);
					$A_apellido_P=utf8_decode($A_apellido_P);

					//$A_apellido_P = preg_replace('([^A-Za-z0-9])', '', $A_apellido_P);
					
					
					
					$A_apellido_M=strtolower($ALUMNO->getApellido_M());
					$A_apellido_M=str_replace("�","n",$A_apellido_M);
					$A_apellido_M=str_replace("�","n",$A_apellido_M);
					$A_apellido_M=str_replace("�","a",$A_apellido_M);
					$A_apellido_M=str_replace("�","e",$A_apellido_M);
					$A_apellido_M=str_replace("�","i",$A_apellido_M);
					$A_apellido_M=str_replace("�","o",$A_apellido_M);
					$A_apellido_M=str_replace("�","u",$A_apellido_M);

					$A_apellido_M=str_replace("á","a",$A_apellido_M);
					$A_apellido_M=str_replace("é","e",$A_apellido_M);
					$A_apellido_M=str_replace("í","i",$A_apellido_M);
					$A_apellido_M=str_replace("ó","o",$A_apellido_M);
					$A_apellido_M=str_replace("ú","u",$A_apellido_M);

					$A_apellido_M=str_replace("Á","a",$A_apellido_M);
					$A_apellido_M=str_replace("É","e",$A_apellido_M);
					$A_apellido_M=str_replace("Í","i",$A_apellido_M);
					$A_apellido_M=str_replace("Ó","o",$A_apellido_M);
					$A_apellido_M=str_replace("Ú","u",$A_apellido_M);

					$A_apellido_M=str_replace("ñ","n",$A_apellido_M);
					$A_apellido_M=utf8_decode($A_apellido_M);
					
					//--------------------------------------------------------------77
					$clave_predeterminada="Ma_".strtolower($A_rut);
					$A_unidadOrganizacion="";
					
					if($A_sede=="Linares"){$A_unidadOrganizacion="/Sede Linares/alumno";}
					if($A_sede=="Talca"){$A_unidadOrganizacion="/Sede Talca/alumno";}
					
					$A_nuevoEmailInstitucional="";
					
					
					//busco correo nuevo para el
					$encontrarMail=true;
					$emailOK=false;
					$x=1;
					while($encontrarMail){
						$emailCandidato=substr(strtolower($A_nombre),0,$x).strtolower($A_apellido_P).substr(strtolower($A_apellido_M),0,$x-1)."@cftmassachusetts.cl";
						$emailCandidato=str_replace(" ","",$emailCandidato);
						
						if(in_array($emailCandidato,$ARRAY_EMAIL)){ $disponible1=false; if(DEBUG){ echo"Disponible en contexto: No<br>";}}
						else{ $disponible1=true; if(DEBUG){ echo"Disponible en contexto: Si<br>";}}
						
						if($disponible1){
							if(DEBUG){echo"[x: $x] Email candidato: $emailCandidato, disponible en funcion: ";}
							if(EMAIL_INSTITUCIONAL_DISPONIBLE($emailCandidato)){$encontrarMail=false; $emailOK=true; if(DEBUG){ echo"SI<br>";}}
							else{ if(DEBUG){ echo"NO<br>";}}
						}
						
						$x++;
						
						if($x>=10){ $encontrarMail=false; if(DEBUG){ echo"Forzar Termino<br>";}}
						
					}
					if($emailOK){ array_push($ARRAY_EMAIL, $emailCandidato); $A_nuevoEmailInstitucional=$emailCandidato;}
					else{ $rutConErrores.=$A_rut." imposible encontrar un correo para el :(, revisar manualmente \r\n";}
					//--------------------------------------------------------------------//
					
				//verifico
					if($marcar_cargado=="si")
					{
						///registro alumno_registros
						$tipo_registro_001="notificacion";
						$descripcion_registro_001="Exportar a Gsuite";
						REGISTRO_EVENTO_ALUMNO($A_id, $tipo_registro_001, $descripcion_registro_001);
						
						$consUP="UPDATE alumno SET emailInstitucional='$A_nuevoEmailInstitucional' WHERE id='$A_id' LIMIT 1";
						$conexion_mysqli->query($consUP)or die($conexion_mysqli->error);
						
					}
				
				//if($emailOK){ echo"OK".$separador;}
				//else{ echo"ERROR".$separador;}
				
				echo ucwords($A_nombre).$separador.ucwords($A_apellido_P)." ".ucwords($A_apellido_M).$separador.$A_nuevoEmailInstitucional.$separador.$clave_predeterminada.$separador.$A_unidadOrganizacion.$separador.$A_rut.$separador.$A_email."\r\n";
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
}
else
{
	echo"Sin Datos Para Generar<br>";
}


?>