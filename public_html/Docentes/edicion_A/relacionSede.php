<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Funcionarios->Edicion Datos V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
	require("../../../funciones/conexion_v2.php");
	$F_id=$_GET["id_funcionario"];
	$fechaActual=date("Y-m-d");
	if(is_numeric($F_id))
	{
		$nuevoEstado=$_GET["nuevoEstado"];
		$id_sede=$_GET["id_sede"];
		
		switch($nuevoEstado)
		{
			case"si":
				$cons="INSERT INTO personalSede (id_personal, id_sede, fecha_generacion) VALUES ('$F_id', '$id_sede', '$fechaActual')";
				$evento="Crea relacion con sede id_sede: $id_sede a usuario id_personal: $F_id";
				$hayConsulta=true;
				break;
			case"no":
				$cons="DELETE FROM personalSede WHERE id_personal='$F_id' AND id_sede='$id_sede' LIMIT 1";
				$hayConsulta=true;
				$evento="Elimina relacion con sede id_sede: $id_sede a usuario id_personal: $F_id";
				break;
			default:
			$hayConsulta=false;
				
		}
		
		if($hayConsulta){
			if(DEBUG)
			{ echo"--->$cons<br>";}
			else
			{ 
				if($conexion_mysqli->query($cons))
				{
					include("../../../funciones/VX.php");
					REGISTRA_EVENTO($evento);
	  				REGISTRO_EVENTO_FUNCIONARIO($F_id, "notificacion", $evento);
				}else{
					echo"Error:".$conexion_mysqli->error;
					
				}
			}
		}
		else{if(DEBUG){echo"Sin Consulta<br>";}}
	}
	else{ if(DEBUG){ echo"FID-> Invalido<br>";}}
	
	$conexion_mysqli->close();
}
if(DEBUG){ echo"FIN<br>";}
else
{ header("location: mdocente.php?id_fun=".base64_encode($F_id));}
?>