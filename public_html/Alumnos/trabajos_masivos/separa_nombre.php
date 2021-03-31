<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(6000);
ini_set('memory_limit', '-1');
$tiempo_inicio_script = microtime(true);
$fecha_actual=date("Y-m-d");
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Procesos_masivos_excel_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<?php include("../../../funciones/codificacion.php"); ?>
<title>Proceso Masivos Excel</title>
<head>
</head>
<body>
<?php
if($_GET)
{
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
include('../../libreria_publica/PHPExcel-1.7.7/Classes/PHPExcel/IOFactory.php');

$inputFileName = $archivo_a_procesar;
if(DEBUG){echo 'Cargando Archivo ',pathinfo($inputFileName,PATHINFO_BASENAME),'<br> usando IOFactory para identificar formato<br /><br>';}
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);


$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

/////////////////////
//comienza escritura archivo
$tabla='<table border="1">
			<tr>
				<td>N</td>
				<td>Apellido_M</td>
				<td>Apellido_P</td>
				<td>Nombres</td>
				
			</tr>';
			$aux=0;
		if(isset($sheetData))
		{
			if(DEBUG){ echo"Hay Datos<br>";}
			
			
			
			foreach($sheetData as $fila => $array_columnas)
			{
				
				if(isset($array_columnas["A"])){$COLUMNA_A=$array_columnas["A"]; $revisar=true;}
				else{ $COLUMNA_A=NULL; $revisar=false;}
				//if(DEBUG){ echo"$COLUMNA_A<br>";}
				
				if($revisar)
				{
					$aux++;
					$contador_1=0;
					$aux_apellido_M="";
					$aux_apellido_M="";
					$aux_nombres="";
					
					$considerar=true;
					$primera_vuelta=true;
					$ARRAY_A=explode(" ",$COLUMNA_A);
					
					$numero_elementos=count($ARRAY_A);
					
					
					$indice_maximo=$numero_elementos-1;
					$tabla.='<tr><td>'.$aux.'</td>';
					for($i=0;$i<=$indice_maximo;$i++)
					{
						$contador_1++;
						$aux_elemento=$ARRAY_A[$i];
						$aux_elemento=str_replace(",","",$aux_elemento);
						
						if(strpos($aux_elemento, "/")){$considerar=false;}
						else{$considerar=true;}
						
						if($considerar)
						{
							switch($contador_1)
							{
								case 1:
									$aux_apellido_P=$aux_elemento;
									break;
								case 2:
									$aux_apellido_M=$aux_elemento;
									break;
								default:
									$aux_nombres.=$aux_elemento." ";
									break;		
							}
							
						}

					}
					$tabla.='<td>'.$aux_apellido_P.'</td>';
					$tabla.='<td>'.$aux_apellido_M.'</td>';
					$tabla.='<td>'.$aux_nombres.'</td>';
					$tabla.='</tr>';
				}
			}
		}
		
		$tabla.='</table>';
		echo $tabla;
	}
}
?>
</body>
</html>