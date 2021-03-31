<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Agrega_alumno_nuevo_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("carga_carreras_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CARGA_CARRERAS");
////////////////////////////////////////////

function CARGA_CARRERAS($sede)
{
	$objResponse = new xajaxResponse();
	$div_resultado="div_carreras";
	$html="";
	$num_carreras=0;
		////////////carrera
		$array_carrera=array();
		include("../../../funciones/conexion.php");
		   $res="SELECT carrera.* FROM carrera INNER JOIN hija_carrera_valores ON carrera.id=hija_carrera_valores.id_madre_carrera WHERE hija_carrera_valores.sede='$sede' AND permite_matriculas='si'";
		   $result=mysql_query($res)or die("carreras: ".mysql_error());
		   while($row = mysql_fetch_array($result)) 
		   {
				$id_carrera=$row["id"];
				$nomcar=$row["carrera"];
				
				$array_carrera[$id_carrera]=$nomcar;
			
			}
		mysql_free_result($result); 
		mysql_close($conexion); 
		$num_carreras=count($array_carrera);
	///////////////////////////////////
	$html='<select name="carrera">';
	if($num_carreras>0)
	{
        foreach($array_carrera as $idcx => $carrerax)
		{
			$html.='<option value="'.$idcx.'_'.$carrerax.'">'.$carrerax.'</option>';
		}
	}
	else
	{ $html.='<option value="0">Sin Carreras</option>';}
      $html.='</select>';
		$objResponse->Assign($div_resultado,"innerHTML",$html);
	return $objResponse;
}
////////////////
$xajax->processRequest();
?>