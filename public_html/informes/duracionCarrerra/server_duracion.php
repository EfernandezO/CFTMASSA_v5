<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(6000);
ini_set('memory_limit', '-1');
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_duracionCarrera_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("server_duracion.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"GENERA_INFORME");
////////////////////////////////////////////
function GENERA_INFORME($FORMULARIO)
{
	$objResponse = new xajaxResponse();
	$year=$FORMULARIO["year"];
	$sede=$FORMULARIO["fsede"];
	$mostrar_detalles=true;
	
	
	$div="div_carga";
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	
	$html="";
	if(DEBUG){$objResponse->Alert("año: $year sede: $sede");}
	//-------------------------------------------------------------------------------//
	
	$condicion_year="";
	if($year>0){$condicion_year="AND ingreso='$year'";}
	
	$cons_MAIN="SELECT * FROM alumno WHERE situacion IN('EG', 'T') AND sede='$sede' $condicion_year";
	if(DEBUG){$objResponse->alert($cons_MAIN);}
	
	$sqli=$conexion_mysqli->query($cons_MAIN)or die($conexion_mysqli->error);
	$num_registros=$sqli->num_rows;
	
	$ARRAY_RESULTADOS=array();
	
	if($num_registros>0)
	{
		$html.="<strong>Periodo: $sede - $year</strong><br>";
		if(DEBUG){$html.="$num_registros Registros<br>";}
		while($TM=$sqli->fetch_assoc())
		{
			$id_alumno=$TM["id"];
			$id_carrera_alumno=$TM["id_carrera"];
			$jornada_alumno=$TM["jornada"];//actualizado toma de ramos
			
			$semestresDuracionAlumno=0;	
			$detener=false;
			
			if($year>0){$yearInicio=$year;}
			else{$yearInicio=2011;}
			
			if($mostrar_detalles){ $html.="id_alumno: $id_alumno id_carrera: $id_carrera_alumno $auxYear - $auxSemestre<br>";}
			
			for($auxYear=$year;$auxYear<=date("Y");$auxYear++){
				for($auxSemestre=1;$auxSemestre<=2;$auxSemestre++){
					$estadoAlumnoPeriodo=ESTADO_ALUMNO_PERIODO($id_alumno, $id_carrera_alumno, $auxSemestre, $auxYear);
					if($mostrar_detalles){ $html.="->Periodo[$auxYear - $auxSemestre] situacion: $estadoAlumnoPeriodo<br>";}
					$sumar=true;
					
					if(($estadoAlumnoPeriodo=="R")or($estadoAlumnoPeriodo=="NN")){$sumar=false;}
					if(($estadoAlumnoPeriodo=="EG")or($estadoAlumnoPeriodo=="T")){$detener=true;}
					if($sumar){$semestresDuracionAlumno+=1;}
					
					if($detener){break;}
				}
				if($detener){break;}
			}
			if($mostrar_detalles){ $html.="=>Total Semestre $semestresDuracionAlumno<br>";}
			
			if(isset($ARRAY_RESULTADOS[$id_carrera_alumno]["cantidad"])){$ARRAY_RESULTADOS[$id_carrera_alumno]["cantidad"]+=1;}
			else{$ARRAY_RESULTADOS[$id_carrera_alumno]["cantidad"]=1;}
			
			if(isset($ARRAY_RESULTADOS[$id_carrera_alumno]["acumulado"])){$ARRAY_RESULTADOS[$id_carrera_alumno]["acumulado"]+=$semestresDuracionAlumno;}
			else{$ARRAY_RESULTADOS[$id_carrera_alumno]["acumulado"]=$semestresDuracionAlumno;}
			
			
		}
		
	}
	else
	{
		$html.="Sin Registros... :(<br>";
		
	}
	
	
	$tabla='<table width="50%">
			<tr>
				<td>Carrera</td>
				<td>Duracion Promedio en Semestres</td>
			</tr>';
	
	foreach($ARRAY_RESULTADOS as $aux_id_carrera => $array_1){
		$aux_cantidad=$array_1["cantidad"];
		$aux_acumulado=$array_1["acumulado"];
		$auxPromedio=($aux_acumulado/$aux_cantidad);
		
		$tabla.='<tr>
				<td bgcolor="'.COLOR_CARRERA($aux_id_carrera).'">'.$aux_id_carrera.'</td>
				<td>'.$auxPromedio.'</td>
			</tr>';
		
	}
	$tabla.='</table>';
	
	
	$html.=$tabla;
	
	//-------------------------------------------------------------------------------//
	$objResponse->Assign($div,"innerHTML",$html);
	//--------------------------------------------------------------------------------//
	$sqli->free();
	$conexion_mysqli->close();
	@mysql_close($conexion);
	return $objResponse;
}
$xajax->processRequest();		
?>