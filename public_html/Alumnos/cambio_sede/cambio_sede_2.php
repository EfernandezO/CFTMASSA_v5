<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Cambio_sede_alumno");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$error="";
if($_POST)
{
	if(DEBUG){ var_dump($_POST);}
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/VX.php");
	
	$id_carrera=mysqli_real_escape_string($conexion_mysqli, $_POST["id_carrera"]);
	$id_alumno=mysqli_real_escape_string($conexion_mysqli, $_POST["id_alumno"]);
	$semestre_cambio=mysqli_real_escape_string($conexion_mysqli, $_POST["semestre"]);
	$year_cambio=mysqli_real_escape_string($conexion_mysqli, $_POST["year"]);
	$sede_nueva=mysqli_real_escape_string($conexion_mysqli, $_POST["sede_nueva"]);
	$sede_old=mysqli_real_escape_string($conexion_mysqli, $_POST["sede_old"]);
	
	
	$cons_A="UPDATE alumno SET sede='$sede_nueva' WHERE id='$id_alumno' AND id_carrera='$id_carrera' LIMIT 1;";
	
	
	$ARRAY_ID_CONTRATOS=array();
	$hay_contratos=false;
	///busco contratos
	if(DEBUG){ echo"Busco Contrato...<br>";}
	$cons_BC="SELECT id FROM contratos2 WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND semestre='$semestre_cambio' AND ano='$year_cambio' AND vigencia='semestral'";
	$sqli_BC=$conexion_mysqli->query($cons_BC)or die($conexion_mysqli->error);
	while($BC=$sqli_BC->fetch_assoc())
	{
		$hay_contratos=true;
		$aux_id_contrato=$BC["id"];
		if(DEBUG){ echo"semestral: id_contrato $aux_id_contrato<br>";}
		$ARRAY_ID_CONTRATOS[]=$aux_id_contrato;
	}
	$sqli_BC->free();	
	
	$cons_BC2="SELECT id FROM contratos2 WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND ano='$year_cambio' AND vigencia='anual'";
	$sqli_BC2=$conexion_mysqli->query($cons_BC2)or die($conexion_mysqli->error);
	while($BC2=$sqli_BC2->fetch_assoc())
	{
		$hay_contratos=true;
		$aux_id_contrato=$BC2["id"];
		if(DEBUG){ echo"Anual: id_contrato $aux_id_contrato<br>";}
		$ARRAY_ID_CONTRATOS[]=$aux_id_contrato;
	}
	$sqli_BC2->free();	
	///---------------------------------------------------------------------------------------------------------------//
	
	if($hay_contratos)
	{
		if(DEBUG){ var_dump($ARRAY_ID_CONTRATOS);}
		$id_contratos_label="";
		$primera_vuelta=true;
		foreach($ARRAY_ID_CONTRATOS as $n => $valor)
		{
			if($primera_vuelta){ $primera_vuelta=false; $id_contratos_label="'$valor'";}
			else{$id_contratos_label.=", '$valor'";}
		}
		
		$cons_C="UPDATE contratos2 SET sede='$sede_nueva' WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND id IN($id_contratos_label)";
		
		$cons_L="UPDATE letras SET sede='$sede_nueva' WHERE id_contrato IN($id_contratos_label)";
		if(DEBUG){ echo"Hay contratos: <br> C: $cons_C <br> Letras: $cons_L<br>";}
	}
	else{if(DEBUG){ echo"No hay contratos<br>";}}
	//no actualizo las sede de las cuotas es muy ambiguo el tema del semestre por existir contratos anuales
	
	if(DEBUG){ echo"<br>A: $cons_A<br>";}
	else
	{
		if($conexion_mysqli->query($cons_A))
		{
			if($hay_contratos)
			{
				if(($conexion_mysqli->query($cons_C))and($conexion_mysqli->query($cons_L)))
				{$error="S0";}
				else
				{$error="S2";}
			}
			$_SESSION["SELECTOR_ALUMNO"]["sede"]=$jornada_nueva;
			 $evento="Cambio de Sede desde [$sede_old] hacia [$sede_nueva] en periodo [$semestre_cambio - $year_cambio]";
			 REGISTRA_EVENTO($evento);
			 $descripcion="Cambio de Sede desde [$sede_old] hacia [$sede_nueva] en periodo [$semestre_cambio - $year_cambio]";
			 REGISTRO_EVENTO_ALUMNO($id_alumno, 'notificacion',$descripcion);
			 ///actualizo condicion a T en alumno
		}
		else
		{$error="S1";}
	}
	
	
}
  $url="cambio_sede_3.php?error=$error";
	if(DEBUG){ echo"URL: $url<br>";}
	else{header("location: $url");}  