<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("asignaciones_v1_EDICION");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("proceso_serverX.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCAR_ASIGNATURAS");
$xajax->register(XAJAX_FUNCTION,"ACTUALIZA_TOTAL");

function BUSCAR_ASIGNATURAS($id_carrera, $AS_cod_asignatura)
{
	
	
	$objResponse = new xajaxResponse();
	$div='div_asignaturas';
	$div_boton='div_boton';
	$campo_select='<select name="asignatura" id="asignatura"><optgroup label="Asignaturas">';
	require("../../../../funciones/conexion_v2.php");
	 $array_ramos_extra=array("0"=>"[00] *JEFATURA",
		 						  "99"=>"[99] *Toma Examen",
								  "98"=>"[98] *Revision Informe",
								  "97"=>"[97] *Supervision de Practica",
								  "96"=>"[96] *Administracion Asignatura",
								  "95"=>"[95] *Taller Complementario",
								  "94"=>"[94] *Asistencia Reunion",
								  "93"=>"[93] *Bono Responsabilidad",
								  "92"=>"[92] *Prestacion de Servicios Profesionales",
								  "91"=>"[91] *Toma de Pruebas Pendientes",
								  "90"=>"[90] *Asesoria Centro de Alumnos",
								  "89"=>"[89] *Movilizacion",
								  "88"=>"[88] Proceso Examen Conocimiento Relevante",
								  "87"=>"[87] Tutorias");
	 
	 if($id_carrera>0)
	 {						   
		 $cons="SELECT * FROM mallas WHERE id_carrera='$id_carrera' AND ramo<>'' ORDER by num_posicion, cod";
		 $sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		 $num_asignaturas=$sql->num_rows;
		 if($num_asignaturas>0)
		 {
			 
			 ///recorro ramos de malla
			 while($A=$sql->fetch_assoc())
			 {
				 $ASIG_cod=$A["cod"];
				 $ASIG_ramo=$A["ramo"];
				 $ASIG_nivel=$A["nivel"];
				 
				 if($ASIG_cod==$AS_cod_asignatura){ $select='selected="selected"';}
				 else{ $select='';}
				 
				 $campo_select.='<option value="'.$ASIG_cod.'" '.$select.'>['.$ASIG_nivel.'] '.$ASIG_ramo.'</option>';
			 }
		}
		 else
		 { $campo_select.='<option value="0">Sin Asignaturas</option>';}
		 
		 ///recorro array de ramos extra
		  $campo_select.='</optgroup><optgroup label="Otros">';
		 foreach($array_ramos_extra as $x_cod => $x_ramo)
		 {
			 if(DEBUG){	$objResponse->Alert("cod_array_extra: $x_cod  Ramo_extra: $x_ramo\n cod buscado: $AS_cod_asignatura \n");}
			  if($x_cod==$AS_cod_asignatura){ $select='selected="selected"';}
			 else{ $select='';}
			 
			  $campo_select.='<option value="'.$x_cod.'" '.$select.'>'.$x_ramo.'</option>';
		 }
		$campo_select.='</optgroup>';
		
		$sql->free();
		 $campo_select.='</select>';
		 $mostrar_boton=true;
	 }
	 else{ $campo_select='...<input name="asignatura" type="hidden" value="0" />'; $mostrar_boton=false;}
	
	
	$objResponse->Assign($div,"innerHTML",$campo_select);
	$conexion_mysqli->close();
	return $objResponse;
}


function ACTUALIZA_TOTAL($numero_horas, $valor_hora, $total)
{
	$objResponse = new xajaxResponse();
	
	if((is_numeric($numero_horas))and($numero_horas>=0))
	{ $continuar_A=true;}
	else{ $continuar_A=false;}
	
	if((is_numeric($valor_hora))and($valor_hora>=0))
	{ $continuar_B=true;}
	else{ $continuar_B=false;}
	
	if((is_numeric($total))and($total>0))
	{ $continuar_C=true;}
	else{ $continuar_C=false;}
	
	if($continuar_A and $continuar_B)
	{
		$aux_total=($valor_hora*$numero_horas);
		$aux_numero_hora=$numero_horas;
		$aux_valor_hora=$valor_hora;
	}
	elseif($continuar_B and $continuar_C)
	{
		$aux_total=$total;
		$aux_valor_hora=$valor_hora;
		$aux_numero_hora=($aux_total/$aux_valor_hora);
		
	}
	else
	{
		$aux_numero_hora=$numero_horas;
		$aux_valor_hora=$valor_hora;
		$aux_total=$total;
	}
	
	$objResponse->Assign('numero_horas',"value",$aux_numero_hora);
	$objResponse->Assign('valor_hora',"value",$aux_valor_hora);
	$objResponse->Assign('total',"value",$aux_total);

	return $objResponse;
}
$xajax->processRequest();
?>