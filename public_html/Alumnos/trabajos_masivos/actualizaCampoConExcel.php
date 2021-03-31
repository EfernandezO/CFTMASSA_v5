<?php 
//-----------------------------------------//
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG",true);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Procesos_masivos_excel_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

	$continuar=false;
	
	$nombre_archivo=base64_decode($_GET["archivo"]);
	if(!empty($nombre_archivo))
	{
		$directorio="../../CONTENEDOR_GLOBAL/trabajos_masivos/";
		if(DEBUG){ echo"Archivo A Procesar: $nombre_archivo<br>";}
		$archivo_a_procesar=$directorio.$nombre_archivo;
////////////////////////////////
//LECTURA DE EXCEL
/** Include path **/
set_include_path(get_include_path() . PATH_SEPARATOR . '../../libreria_publica/PHPExcel-1.7.7/Classes/');
/** PHPExcel_IOFactory */
require('../../libreria_publica/PHPExcel-1.7.7/Classes/PHPExcel/IOFactory.php');

$inputFileName = $archivo_a_procesar;
if(DEBUG){echo 'Cargando Archivo ',pathinfo($inputFileName,PATHINFO_BASENAME),'<br> usando IOFactory para identificar formato<br /><br>';}
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);


$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

$TOTAL_REGISTROS_ARCHIVO=count($sheetData);
///////////////////////////////////////////////////////////////
//fin LECTURA EXCEL
/////////////////////
//comienza escritura archivo

		if(isset($sheetData))
		{ $continuar=true;}
	}

if($continuar)	
{
	require("../../../funciones/conexion_v2.php");	
	require("../../../funciones/class_ALUMNO.php");
	require("../../../funciones/funcion.php");
	require("../../../funciones/funciones_varias.php");
	
	$cantidad_elementos_archivo=count($sheetData);
	$cantidad_datos_invalidos_en_archivo=0;
	if(DEBUG){ echo"Cantidad elementos en archivo: $cantidad_elementos_archivo<br>";}
	if($cantidad_elementos_archivo>0)
	{
		foreach($sheetData as $fila => $array_columnas)
		{
			$COLUMNA_A=$array_columnas["A"];
			$COLUMNA_B=strtoupper($array_columnas["B"]);
			$COLUMNA_C=$array_columnas["C"];
			
			echo"$fila -> $COLUMNA_A - $COLUMNA_B - $COLUMNA_C: ";
			$aux_rut=str_inde($COLUMNA_A)."-".str_inde($COLUMNA_B);
			
			if((is_numeric($COLUMNA_A))and(is_string($COLUMNA_B)))
			{ $dato_archivo_valido=true;}
			else{ $dato_archivo_valido=false;}
			
			if($dato_archivo_valido)
			{
				echo"Identificador en Archivo Valido - ";
				//compruebo valor de la columna C
				$datosUpOk=false;
				if(comprobar_email(trim($COLUMNA_C))){
					echo"Valor de Columna C, VAlido<br>";
					$datosUpOk=true;
				}else{echo"Valor de Columna C, Invalido<br>";}
				
				//actualizo valor de BBDD con campo de excel
				
				$aux_rut=mysqli_real_escape_string($conexion_mysqli, $aux_rut);
				$COLUMNA_C=mysqli_real_escape_string($conexion_mysqli, $COLUMNA_C);
				
				if($datosUpOk){
					$consUp="UPDATE alumno SET email='$COLUMNA_C' WHERE rut='$aux_rut' LIMIT 1";
					echo "--->$consUp<br>";
					
					if(!DEBUG){
						if($conexion_mysqli->query($consUp)){ echo"<strong>HECHO</strong><br>";}
						else{echo"<strong>ERROR </strong>".$conexion_mysqli->error."<br>";}
					}
				}else{
					echo"Datos de Columna C Invalidos, No Actualizar<br>";
				}
			}
			else
			{
				echo"Identificador en Archivo No validos<br>";
				$cantidad_datos_invalidos_en_archivo++;
			}
		}
	}
	else
	{
		//sin elementos en archivo
		if(DEBUG){ echo"Sin Elementos en Archivo<br>";}
	}
				//-------------------------------------------------------------------------------------//


@mysql_close($conexion);
$conexion_mysqli->close();
}
else
{ echo"No se puede continuar...<br>";}
?>