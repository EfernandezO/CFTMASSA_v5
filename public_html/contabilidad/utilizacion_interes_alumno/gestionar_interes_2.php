<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	//$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Gestionar_aplicacion_de_interes_Y_Gasto_cobranza_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
	{$continuar=true;}
	else
	{$continuar=false;}
}
else
{$continuar=false;}

if(isset($_GET["aplicar"]))
{
	$continuar_2=false;
	$aplicar_interes=$_GET["aplicar"];
	if(is_numeric($aplicar_interes))
	{
		if(($aplicar_interes==1)or($aplicar_interes==0))
		{
			$continuar_2=true;
		}
	}
}
else
{$continuar_2=false;}

if(isset($_GET["aplicarGC"]))
{
	$continuar_3=false;
	$aplicar_gastos=$_GET["aplicarGC"];
	if(is_numeric($aplicar_gastos))
	{
		if(($aplicar_gastos==1)or($aplicar_gastos==0))
		{
			$continuar_3=true;
		}
	}
}
else
{$continuar_3=false;}
//--------------------------------------------------------------------//
require("../../../funciones/VX.php");
if($continuar and $continuar_2)
{
	
	if(DEBUG){ echo"ingreso intereses<br>";}
	$error="AI0";
	require("../../../funciones/conexion_v2.php");
	
	
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	
	$cons_UP="UPDATE alumno SET aplicar_intereses='$aplicar_interes' WHERE id='$id_alumno' LIMIT 1";
	if(DEBUG){ echo"---->$cons_UP<br>";}
	else
	{ 
		if($conexion_mysqli->query($cons_UP))
		{
			if($aplicar_interes>0){ $evento="Activa la Aplicacion de intereses a Alumno id_alumno: $id_alumno"; $descipcion="Activa la aplicacion de intereses a sus cuotas";}
			else{ $evento="Desactica la Aplicacion de interes a Alumno id_alumno: $id_alumno"; $descripcion="Desactiva la aplicacion de intereses a sus cuotas";}
			
			REGISTRO_EVENTO_ALUMNO($id_alumno, "notificacion", $descripcion);
			REGISTRA_EVENTO($evento);
		}
		else
		{$error="AI1";}
	}
	
	$conexion_mysqli->close();
}
elseif($continuar and $continuar_3)
{
	
	if(DEBUG){ echo"ingreso GASTOS<br>";}
	$error="AGC0";
	require("../../../funciones/conexion_v2.php");
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	
	$cons_UP="UPDATE alumno SET aplicar_gastos_cobranza='$aplicar_gastos' WHERE id='$id_alumno' LIMIT 1";
	if(DEBUG){ echo"---->$cons_UP<br>";}
	else{ 
		if($conexion_mysqli->query($cons_UP))
		{
			if($aplicar_gastos>0){ $evento="Activa la Aplicacion de Gastos de Cobranza a Alumno id_alumno: $id_alumno"; $descipcion="Activa la aplicacion de Gastos de Cobranza a sus cuotas";}
			else{ $evento="Desactica la Aplicacion de Gastos de Cobranza a Alumno id_alumno: $id_alumno"; $descripcion="Desactiva la aplicacion de Gastos de Cobranza a sus cuotas";}
			
			REGISTRO_EVENTO_ALUMNO($id_alumno, "notificacion", $descripcion);
		
			REGISTRA_EVENTO($evento);
			
		}
		else
		{ $error="AGC1";}
	}
	
	$conexion_mysqli->close();
}
else
{ }

//------------------------------------------------------------//
if(DEBUG){ echo"Final<br>";}

$url="gestionar_interes_final.php?error=$error";
if(DEBUG){ echo"URL: $url<br>";}
else{ header("location: $url");}
?>
