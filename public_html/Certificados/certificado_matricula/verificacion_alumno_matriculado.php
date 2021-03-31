<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("certificado->certificado_de_matricula_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
require("../../../funciones/conexion_v2.php");	
////obtencion de variables
	$firma=$_POST["firma"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$carrera=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
	$sede=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	$presentado=$_POST["presentado"];
	$rut=$_SESSION["SELECTOR_ALUMNO"]["rut"];
	$year=$_POST["year"];
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$ver_logo=$_POST["ver_logo"];
	

/////
////busco si tiene matricula el alumno
////id de alumno

	$situacion="'V', 'EG'";
	$cons_alu="SELECT * FROM alumno WHERE id='$id_alumno' AND situacion IN ($situacion) LIMIT 1";
	if(DEBUG)
	{ echo "-> $cons_alu<br>";	}
	$sql_alu=$conexion_mysqli->query($cons_alu);
	$DA=$sql_alu->fetch_assoc();
	$nombre=$DA["nombre"];

	$apellido=$DA["apellido_P"]." ".$DA["apellido_M"];
	$alumno=$nombre." ".$apellido;
	
	
	
$sql_alu->free();
//////tiene matricula
	if((is_numeric($id_alumno))and($id_alumno>0))
	{
		$cons_mat="SELECT * FROM contratos2 WHERE id_alumno='$id_alumno' AND ano='$year' ORDER by id desc LIMIT 1";
		if(DEBUG)
		{ echo "--> $cons_mat<br>";	}
		$sql_mat=$conexion_mysqli->query($cons_mat);
		$num_contratos=$sql_mat->num_rows;
		
		if($num_contratos>0)
		{
			$DC=$sql_mat->fetch_assoc();
			$ano_contrato=$DC["ano"];
			$semestre_contrato=$DC["semestre"];
			$vigencia_contrato=$DC["vigencia"];
			$_SESSION["AUX_CERTIFICADO"]["ano_contrato"]=$ano_contrato;
			$_SESSION["AUX_CERTIFICADO"]["semestre_contrato"]=$semestre_contrato;
			$_SESSION["AUX_CERTIFICADO"]["vigencia_contrato"]=$vigencia_contrato;
			$_SESSION["AUX_CERTIFICADO"]["rut_alumno"]=$rut;
			$_SESSION["AUX_CERTIFICADO"]["sede_alumno"]=$sede;
			$_SESSION["AUX_CERTIFICADO"]["id_alumno"]=$id_alumno;
			$_SESSION["AUX_CERTIFICADO"]["alumno"]=$alumno;
			$_SESSION["AUX_CERTIFICADO"]["carrera_alumno"]=$carrera;
			$_SESSION["AUX_CERTIFICADO"]["presentado_a"]=$presentado;
			$_SESSION["AUX_CERTIFICADO"]["firma"]=$firma;
			//DECRETO DE CARRERA//
			
			$cons_de="SELECT decreto FROM certificados WHERE id_carrera='$id_carrera' and sede='$sede'";
			if(DEBUG)
			{echo"---> $cons_de";}
			$sql_de=$conexion_mysqli->query($cons_de);

			$DD=$sql_de->fetch_assoc();
			$decretoX=$DD["decreto"];
			$_SESSION["AUX_CERTIFICADO"]["decreto_carrera"]=$decretoX;
			
			$sql_de->free();
			$sql_mat->free();
			$conexion_mysqli->close();
			
			if($ver_logo=="si"){$url="de_matricula.php?ver_logo=si";}
			else{$url="de_matricula.php?ver_logo=no";}
			
			header("location: $url");
			
		}
		else
		{
			if(isset($_SESSION["AUX_CERTIFICADO"]))
			{
				unset($_SESSION["AUX_CERTIFICADO"]);
			}
			//sin contrato
			$sql_mat->free();
			$conexion_mysqli->close();
			header("location: index.php?error=2");
		}
	}
	else
	{
		if(isset($_SESSION["AUX_CERTIFICADO"]))
			{
				unset($_SESSION["AUX_CERTIFICADO"]);
			}
		//alumno no encontrado
		$sql_alu->free();
		$conexion_mysqli->close();
		header("location: index.php?error=1&errorusuario=si");
	}
?>