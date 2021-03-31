<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Bibioteca_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
 if(isset($_GET["id_libro"]))  
 {
	require('../../../../funciones/conexion_v2.php');
   $id_libro=$_GET["id_libro"]; 
   $continuar=false;
   $error="X";
   
   if(is_numeric($id_libro))
   {
   		$cons_b="SELECT id, archivo, tipo_archivo FROM biblioteca_asociados WHERE id_libro='$id_libro' LIMIT 1";
		
		$sql=$conexion_mysqli->query($cons_b);
		$num_asociados=$sql->num_rows;
		if(DEBUG){ echo"$cons_b<br>N. Asociados: $num_asociados<br>";}
		if($num_asociados>0)
		{
			$n=0;
			while($L=$sql->fetch_assoc())
			{
				$array_id_asociado[$n]=$L["id"];
				$array_archivos_asociados[$n]=$L["archivo"];
				$array_tipos_asociados[$n]=$L["tipo_archivo"];
				$n++;
			}
			
			if(ELIMINA_ARCHIVOS($array_archivos_asociados, $array_tipos_asociados))
			{
				if(ELIMINA_REG_ASOCIADOS($array_id_asociado))
				{ $continuar=true;}
				else
				{ $error="E2";}
			}
			else
			{$error="E3";}
			
		}
		else
		{ $continuar=true;}	
		//borar reg biblio por aqui
		if($continuar)
		{
			if(ELIMINA_REG_MADRE($id_libro))
			{$error="E0";}
			else
			{ $error="E4";}
		}
		$sql->free();
		$conexion_mysqli->close();
	}

   if(DEBUG){ echo"Error: $error<br>";}
   else
   {header("location: ../../menu_biblioteca.php?error=$error");}
}
else
{
	echo"<tt>Sin Datos :(...</tt>";
}
function ELIMINA_ARCHIVOS($array_archivos, $array_tipos)
{
	echo"<tt>- Archivos Asociados</tt><br>";
	$path_img="../../../CONTENEDOR_GLOBAL/biblioteca_img/";
	$path_pdf="../../../CONTENEDOR_GLOBAL/biblioteca_pdf/";

	$cantidad_archivos=count($array_archivos);
	if($cantidad_archivos>0)
	{
		for($i=0;$i<$cantidad_archivos;$i++)
		{
			$archivo=$array_archivos[$i];
			$tipo=$array_tipos[$i];
			//segun tipo armo ruta
			switch ($tipo)
			{
				case "pdf":
					$ruta=$path_pdf.$archivo;
					break;
				case "imagen":	
					$ruta=$path_img.$archivo;
					break;
			}
			//veo si existe sino pake borrarlo 
			if(DEBUG){echo"<tt>- $ruta</tt>  -> ";}
			if(file_exists($ruta))
			{
				if(DEBUG){ echo"DEBUG:Eliminando <br>";}
				else
				{ @unlink($ruta);}
			}
			else
			{
				if(DEBUG){echo"<tt>No Existe</tt><br>";}
			}
			
		}
	}
	return(true);
}
//////////////////////////////////////////////////////////////////////
function ELIMINA_REG_ASOCIADOS($array_id)
{
	$aux=false;
	$num_assocciados=count($array_id);
	if($num_assocciados>0)
	{
		foreach($array_id as $n =>$valor)
		{
			$id_asociado=$valor;
			if($aux)
			{
				$condicion.="OR id='$id_asociado' ";
			}
			else
			{
				$condicion.="id='$id_asociado' ";
				$aux=true;
				
			}	
		}
		$cons_Elimina="DELETE FROM biblioteca_asociados WHERE $condicion LIMIT 1";
		if(DEBUG){echo"<tt>$cons_Elimina</tt><br>"; $error=true;}
		else
		{
			if(mysql_query($cons_Elimina))
			{$error=true;}
			else
			{$error=false;}
		}
	}
	else
	{ $error=true;}
	return($error);
}
//////////////////////////////////////////////

function ELIMINA_REG_MADRE($id_libro)
{
	$cons_Elimina_madre="DELETE FROM biblioteca WHERE id_libro='$id_libro' LIMIT 1";
	if(DEBUG){ echo"<tt>$cons_Elimina_madre</tt><br>"; $error=true;}
	else
	{
		if(mysql_query($cons_Elimina_madre))
		{$error=true;}
		else
		{$error=false;}
	}
	return($error);
}
?>