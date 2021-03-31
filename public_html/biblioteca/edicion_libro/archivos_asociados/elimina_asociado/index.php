<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("Bibioteca_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
  if($_GET)
  {
  	$id_libro=$_GET["id_libro"];
	$id_asociado=$_GET["id_asociado"];
	if((is_numeric($id_libro))&&(is_numeric($id_asociado)))
	{
		include("../../../../../funciones/conexion.php");
		$cons_S="SELECT archivo, tipo_archivo FROM biblioteca_asociados WHERE id='$id_asociado' AND id_libro='$id_libro'";
		//echo"$cons_S<br>";
		$sql=mysql_query($cons_S)or die(mysql_error());
		$A=mysql_fetch_assoc($sql);
		$archivo=$A["archivo"];
		$tipo_archivo=$A["tipo_archivo"];
		if(ELIMINA_ARCHIVOS($archivo, $tipo_archivo))
		{
			if(ELIMINA_REG($id_libro, $id_asociado))
			{
				$error=5;
				//echo"$error -> todo good<br>";
				 /////Registro ingreso///
		 		include("../../../../../funciones/VX.php");
		 		$evento="Elimina archivo Asociado Libro -> $id_libro ($id_asociado)";
				 REGISTRA_EVENTO($evento);
			 	///////////////////////
			}
			else
			{
				$error=6;
			}
			
		}
		else
		{
			$error=4;
		}	
		mysql_close($conexion);
		header("location: ../carga_asociados/index.php?id_libro=$id_libro&error=$error");
	}
	else
	{
		header("location: ../seleccion_libro.php");
	}
  }
  else
  {
  	header("location: ../seleccion_libro.php");
  }
?>
<?php
//////////////////////////////////////////////////////

function ELIMINA_REG($id_libro, $id_asociado)
{
	$cons_E="DELETE FROM biblioteca_asociados WHERE id='$id_asociado' AND id_libro='$id_libro'";
	//echo"$cons_E<br>";
	if(mysql_query($cons_E))
	{
		return(true);
	}
	else
	{
		return(false);
	}
}
//////////////////////////////////////////////////

function ELIMINA_ARCHIVOS($archivo, $tipo_archivo)
{
	$path_pdf="../../../../CONTENEDOR_GLOBAL/biblioteca_pdf/";
	$path_img="../../../../CONTENEDOR_GLOBAL/biblioteca_img/";

	switch ($tipo_archivo)
	{
		case "pdf":
			$ruta=$path_pdf.$archivo;
			break;
		case "imagen":
			$ruta=$path_img.$archivo;
			break;			
	}
	//echo"$ruta<br>";
	if(file_exists($ruta))
	{
		unlink($ruta);
		
	}
	return(true);
}
?>