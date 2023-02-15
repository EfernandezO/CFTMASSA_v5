<?php
//////////////////////XAJAX/////////////////
require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("buscador_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_ALUMNO");
////////////////////////////////////////////
function BUSCA_ALUMNO($sede, $carrera, $nivel)
{
	$objResponse = new xajaxResponse();
	
		$div="ajax_resultado";
		include("../../../funciones/conexion.php");
		$carrera=utf8_decode($carrera);
		$cons="SELECT * FROM alumno WHERE carrera='$carrera' AND nivel='$nivel' AND sede='$sede' AND situacion IN('V', 'M') order by apellido_P, apellido_M, apellido";
		$sql_BC=mysql_query($cons)or die(mysql_error());
		$num_registros=mysql_num_rows($sql_BC);
		$html='<table border="1"><tbody>';
		if($num_registros>0)
		{
			$contador=1;
			$aux_primera=false;
			while($DC=mysql_fetch_assoc($sql_BC))
			{
				$contador++;
				$id_alumno=$DC["id"];
				$nombre=$DC["nombre"];
				$apellido_old=$DC["apellido"];
				$apellido_new=$DC["apellido_P"]." ".$DC["apellido_M"];
				
				if($apellido_new==" ")
				{ $apellido_label=$apellido_old; }
				else
				{ $apellido_label=$apellido_new; }
				
				$nombre_alumno=ucwords(strtolower($nombre." ".$apellido_label));
				
				
					if($contador%2==0)
					{$html.='<tr>';}
				
				$url="hoja_vida.php?id_alumno=$id_alumno";
				$html.='
						<td><span class="Estilo2">'.$id_alumno.'</span></td>
						<td><span class="Estilo2"><a href="'.$url.'">'.$nombre_alumno.'</a></span></td>';
			}//fin while	
		}//fin si
		else
		{ 
			$html="<tr><td>Sin Alumnos Registrados en este Nivel, Carrera y Sede</td></tr>"; 
		}//fin else
		$html.="</tbody></table>";
		$objResponse->Assign($div,"innerHTML",$html);
		mysql_free_result($sql_BC);
		mysql_close($conexion);
		//$objResponse->alert("ingrese Rut y Carrera");
		return $objResponse;
}//fin funcion
$xajax->processRequest();
?>