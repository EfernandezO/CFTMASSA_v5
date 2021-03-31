<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->MAIN_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_POST)
{
	include("../../../funciones/funcion.php");
	require("../../../funciones/conexion_v2.php");	
	$fecha_actual=date("Y-m-d");

	$fnom_carrera=mysqli_real_escape_string($conexion_mysqli, $_POST["fnom_carrera"]);
	$nombre_titulo=mysqli_real_escape_string($conexion_mysqli, $_POST["nombre_titulo"]);
	$version=mysqli_real_escape_string($conexion_mysqli, $_POST["version"]);
	$yearInicio=mysqli_real_escape_string($conexion_mysqli, $_POST["yearInicio"]);
	
	$continuar=true;
	$year_actual=date("Y");
	$error="";
	$faran_a=0;
	$faran_b=0;
	$fvalor_mat=0;
	
	
	//veo si acepto nuevo nombre
	if(($fnom_carrera=="Sin Registro")or($fnom_carrera==""))
	{$continuar=false;}
	else
	{
		//busco si carrera no existe
		$consB="SELECT carrera FROM carrera";
		$sqlB=$conexion_mysqli->query($consB)or die($conexion_mysqli->error);
		while($C=$sqlB->fetch_assoc())
		{
			$carreraX=$C["carrera"];
			$versionX=$C["version"];
			if(($fnom_carrera==$carreraX)and($version==$versionX))
			{
				if(DEBUG){ echo"-->Carrera ya Existe en esta version...<br>";}
				$continuar=false;
				break;
			}
		}
	}
	
	//------------------------------------------------------------------------------------------------//
	
		if($continuar)
		{
			$error="CC0";
			$consXX="INSERT INTO carrera (carrera, nombre_titulo, version, yearInicio) VALUES('$fnom_carrera', '$nombre_titulo', '$version', '$yearInicio')";
			if(DEBUG){ echo"->$consXX<br>"; $id_carrera_new="D";}
			else
			{
				$conexion_mysqli->query($consXX)or die($conexion_mysqli->error);
				$id_carrera_new=$conexion_mysqli->insert_id;
			}
			
			/*
				$campos="year, id_madre_carrera, sede, arancel_1, arancel_2, matricula, fecha, permite_matriculas";
				$cons_1="INSERT INTO hija_carrera_valores($campos) VALUES('$year_actual', '$id_carrera_new', 'Talca', '$faran_a', '$faran_b', '$fvalor_mat', '$fecha_actual', 'si')";
				$cons_2="INSERT INTO hija_carrera_valores($campos) VALUES('$year_actual', '$id_carrera_new', 'Linares', '$faran_a', '$faran_b', '$fvalor_mat', '$fecha_actual', 'si')";
				
				if(DEBUG)
				{
					echo"--->$cons_1<br>--->$cons_2<br>";
				}
				else
				{
					$conexion_mysqli->query($cons_1)or die("1".$conexion_mysqli->error);
					$conexion_mysqli->query($cons_2)or die("2".$conexion_mysqli->error);
				}
				*/	
					include("../../../funciones/VX.php");
					$evento="Agrega Carrera a Sistema id_carrera: $id_carrera_new Nombre Carrera: $fnom_carrera";
					REGISTRA_EVENTO($evento);
				
			
		}
		else
		{
			if(DEBUG){ echo"No se puede Continuar...<br>";}
			$error="CC1";
		}
		
		$conexion_mysqli->close();
		if(DEBUG){ echo"Error: $error<br>";}
		else{header("location: ../index.php?error=$error");}
}
else
{ header("location: ../index.php");}
?>