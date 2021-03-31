<?php
//////////////////////XAJAX/////////////////
@require_once ("../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("search_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCAR_LIBRO");
////////////////////////////////////////////

function BUSCAR_LIBRO($palabra_clave)
{
	$palabra_clave=strtoupper($palabra_clave);
	include('../../funciones/conexion_v2.php');
	$max_caracter=50;
	$div="display";
	$img="../imagenes/libro.jpg";
	$objResponse = new xajaxResponse();
	$sql_res=$conexion_mysqli->query("select nombre, id_carrera, carrera from biblio where upper(nombre) like '%$palabra_clave%' order by nombre LIMIT 5");
	$num_resultados=$sql_res->num_rows;
if($num_resultados>0)
{	
while($row=$sql_res->fetch_assoc())
{

	$titulo=strtoupper($row['nombre']);
	$libro_carrera=$row["carrera"];
	$libro_id_carrera=$row["id_carrera"];
	$largo_titulo=strlen($titulo);
	if($largo_titulo>$max_caracter)
	{
		$titulo=substr($titulo,0,$max_caracter);
		$concatenar=true;
	}
	
	$destaca_titulo='<b>'.$palabra_clave.'</b>';
	$final_titulo = str_replace($palabra_clave, $destaca_titulo, $titulo);
	$final_titulo=ucwords(strtolower($final_titulo));
	if($concatenar)
	{
		$final_titulo.="...";
	}
	$html.='<div class="display_box" align="left">
<img src="'.$img.'" style="width:25px; float:left; margin-right:6px" /><a href="#" onclick="sobrescribir(\''.$titulo.'\',\''. $libro_id_carrera.'_'.$libro_carrera.'\');">'.$final_titulo.'&nbsp;</a><br/>
<span style="font-size:9px; color:#999999"></span></div>';
}//fin while
}
else
{
	$html.='<div class="display_box" align="left">
<img src="'.$img.'" style="width:25px; float:left; margin-right:6px" /><a href="#">Sin Resultados...</a><br/>
<span style="font-size:9px; color:#999999"></span></div>';
}
		$sql_res->free();
		
		$objResponse->Assign($div,"style.visibility","visible");
	    $objResponse->Assign($div,"innerHTML",$html);
	return $objResponse;
}
////////////////
$xajax->processRequest();
?>