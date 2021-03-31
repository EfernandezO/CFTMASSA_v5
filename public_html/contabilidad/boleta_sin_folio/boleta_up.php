<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Boletas_pendientes_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
///////-------------------------////////////////
if($_POST)
{
	$indice=$_POST["indice"];
	$id_boleta=$_POST["id_boleta"];
	$folio_new=$_POST["folio_new_".$indice];
	require("../../../funciones/conexion_v2.php");
	$condicion_folio=VERIFICA_FOLIO($folio_new, $id_boleta);//comprueba si esta repetido el folio
	if((is_numeric($folio_new))and ($condicion_folio))
	{
		if(DEBUG){ var_export($_POST);}
		
		$cons_up_boleta="UPDATE boleta SET folio='$folio_new' WHERE id='$id_boleta' LIMIT 1";
		if(DEBUG){ echo"-> $cons_up_boleta";}
		else
		{
			if($conexion_mysqli->query($cons_up_boleta))
			{$error=0;}
			else
			{$error=1;}
			@mysql_close($conexion);
			$conexion_mysqli->close();
			header("location: index.php?error=$error");
		}
		$conexion_mysqli->close();
		@mysql_close($conexion);
	}
	else
	{
		//datos incorrectos
		$conexion_mysqli->close();
		@mysql_close($conexion);
		header("location: index.php?error=2");
	}
	
}
else
{
	header("location: index.php");
}

/////////////////////////////////////////////
function VERIFICA_FOLIO($folio, $id_boleta)
{
	$continuar=true;
$cons_BB="SELECT COUNT(id) FROM boleta WHERE NOT(id='$id_boleta') AND folio='$folio'";
	if(DEBUG)
	{ echo"Funcion: $cons_BB <br>";}
	$sql_B=mysql_query($cons_BB)or die("Busca folio -> ".mysql_error());
	$BX=mysql_fetch_row($sql_B);
	$coincidencias=$BX[0];
	if($coincidencias>0)
	{
		//folio repetido
		$continuar=false;
	}
	return($continuar);
}	
?>
