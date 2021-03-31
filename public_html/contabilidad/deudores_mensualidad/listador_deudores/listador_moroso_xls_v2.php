<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG",false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("deudores_mensualidad_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	set_time_limit(600);
//-----------------------------------------//	
if(DEBUG)
	{ var_dump($_GET);}
	else
	{
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=listador_morosos.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
	$sede=base64_decode($_GET["sede"]);
	$id_carrera=base64_decode($_GET["id_carrera"]);
	$nivel=unserialize(base64_decode($_GET["nivel"]));
	$jornada=base64_decode($_GET["jornada"]);
	$grupo=base64_decode($_GET["grupo"]);
	$fecha_corte=base64_decode($_GET["fecha_corte"]);
	$year_cuotas=base64_decode($_GET["year_cuotas"]);
	$opcion=base64_decode($_GET["opcion"]);
	
	$yearIngresoCarrera=base64_decode($_GET["yearIngresoCarrera"]);
	$semestreConsulta=base64_decode($_GET["semestreConsulta"]);
	$yearConsulta=base64_decode($_GET["yearConsulta"]);

if($_GET)
{
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funciones_sistema.php");
	require("../../../../funciones/class_LISTADOR_ALUMNOS.php");
	
	$num_morosos=0;
	$lineas_detalle_cuota="";
	
	switch($opcion)
	{
		case"listado_ext":
			$mostrarSoloMorosos=false;
			$mostrar_cuotas=true;
			$condicion_fila=' colspan="2"';
			$total_columnas="10";
			$mostrar_total=true;
			break;
		case"listado_total":
			$mostrarSoloMorosos=false;
			$mostrar_cuotas=false;
			$condicion_fila=' colspan="2"';
			$total_columnas="12";
			$mostrar_total=true;
			break;	
		case"listado_para_profesores":
			$mostrarSoloMorosos=true;
			$mostrar_cuotas=false;
			$condicion_fila=' colspan="2"';
			$total_columnas="7";
			$mostrar_total=false;
			break;		
		default:
			$mostrarSoloMorosos=false;
			$mostrar_cuotas=false;	
			$condicion_fila="";
			$total_columnas="9";
			$mostrar_total=false;
	}
	
	/////////////
	$SUMA_TOTAL=0;
	//$fecha_corte=date("Y-m-d");
	////////////
	
	////////////////
	$tabla='Listado tipo: '.$opcion.'<br>
	<table width="100%" border="1" align="center">
	<thead>
  	<tr>
    <th colspan="'.$total_columnas.'"><div align="center" class="Estilo1">Alumnos de Carrera: '.NOMBRE_CARRERA($id_carrera).' - '.$yearIngresoCarrera.' Jornada '.$jornada.' Grupo '.$grupo.' - '.$sede.' Cuotas: '.$year_cuotas.' Fecha Generacion: '.date("d-m-Y").'<br>Fecha Corte: '.$fecha_corte.'</div></th>
    </tr>
	</thead>
	<tbody>
    <tr>
	  <td><strong>N.</strong></td>
	  <td><strong>Carrera</strong></td>
	  <td>Estado Financiero</td>';
	  
	  if($opcion!="listado_para_profesores")
	  {$tabla.='<td><strong>Nivel</strong></td>
	  <td><strong>Jornada</strong></td>';}
	  
      $tabla.='<td><strong>ID alumno</strong></td>
      <td><strong>Rut</strong></td>
      <td><strong>Nombre</strong></td>
      <td '.$condicion_fila.'><strong>Apellido</strong></td>';
	  if($opcion=="listado_total")
	  {
		  $tabla.='<td><strong>N. Cuotas Pendientes</strong></td>
      			<td><strong>Total Deuda</strong></td>';
		}
    $tabla.='</tr>';
	//////////////////
	
	
	
	if($year_cuotas!="0")
	{ $condicion_year_cuota="AND letras.ano='$year_cuotas'";}
	else{ $condicion_year_cuota="";}
	
	
	
	
	$LISTA = new LISTADOR_ALUMNOS();
	
	$LISTA->setDebug(DEBUG);
	
	$LISTA->setGrupo($grupo);
	$LISTA->setId_carrera($id_carrera);
	$LISTA->setJornada($jornada);
	$LISTA->setNiveles($nivel);
	$LISTA->setSede($sede);
	$LISTA->setYearIngressoCarrera($yearIngresoCarrera);
	$LISTA->setSituacionAcademica("A");
	
	$LISTA->setSemestreVigencia($semestreConsulta);
	$LISTA->setYearVigencia($yearConsulta);
	
	
	if(DEBUG){echo "Total Alumnos ".$LISTA->getTotalAlumno()."<br>";}
	
	$totalAlumnos=$LISTA->getTotalAlumno();
	if($totalAlumnos>0){
	
		$contadorAlumnos=0;
		foreach($LISTA->getListaAlumnos() as $n => $auxAlumno)
		{
			
			
			$id_alumno=$auxAlumno->getIdAlumno();
			$rut_alumno=$auxAlumno->getRut();
			$nombre=$auxAlumno->getNombre();
			$apellidos=$auxAlumno->getApellido_P()." ".$auxAlumno->getApellido_M();
			
			$id_carrera_alumno=$auxAlumno->getIdCarreraPeriodo();
			$nivel_alumno=$auxAlumno->getNivelAlumnoPeriodo();
			$jornada_alumno=$auxAlumno->getJornadaPeriodo();
			$situacion_alumno=$auxAlumno->getSituacionAlumnoPeriodo();
			
			
				$num_cuotas=0;
				$num_cuotas_pendientes=0;
				$TOTAL_DEUDA_ALUMNO=0;
					//////////////
					$cons_cuotas="SELECT * FROM letras WHERE idalumn='$id_alumno' AND  deudaXletra>0 AND fechavenc <= '$fecha_corte'  $condicion_year_cuota ORDER BY fechavenc";
					
					$sql_cuota=$conexion_mysqli->query($cons_cuotas)or die($conexion_mysqli->error);
					$num_cuotas=$sql_cuota->num_rows;
					if(DEBUG){ echo"$cons_cuotas<br>N. Cuotas: $num_cuotas<br>";}
					if(empty($num_cuotas)){ $num_cuotas=0;}
					
					if($num_cuotas>0)
					{ $mostrar_alumnos=true; $num_morosos++; $estadoFinanciero="moroso"; if(DEBUG){ echo"Mostrar alumno Moroso<br>";}}
					else{ $mostrar_alumnos=false; $estadoFinanciero="al_dia"; if(DEBUG){ echo"No mostrar Alumno<br>";}}
					////////////////
					$indice_cuota_D=0;
					$lineas_detalle_cuota="";
					
					if(!$mostrarSoloMorosos){$mostrar_alumnos=true;}else{$totalAlumnos=$num_morosos;}
					
					while($CA=$sql_cuota->fetch_assoc())
						{
							$indice_cuota_D++;
							$id_cuota=$CA["id"];
							$valor=$CA["valor"];
							$deudaXcuota=$CA["deudaXletra"];
							
							$vencimiento=$CA["fechavenc"];
							$condicion=strtoupper($CA["pagada"]);
							if(DEBUG){ echo "----> $indice_cuota_D -$valor $deudaXcuota $vencimiento $condicion<br>";}
							switch($condicion)
							{
								case"N":
									$condicion_label="pendiente";
									$TOTAL_DEUDA_ALUMNO+=$deudaXcuota;
									$num_cuotas_pendientes++;
									break;
								case"S":
									$condicion_label="pagada";
									break;
								case"A":
									$condicion_label="abonada";
									$TOTAL_DEUDA_ALUMNO+=$deudaXcuota;
									$num_cuotas_pendientes++;
									break;		
							}
							
							$lineas_detalle_cuota.='<tr align="center">
								<td colspan="5">---></td>
								<td><em>'.$indice_cuota_D.'</em></td>
								<td><em> $'.number_format($valor,0,",",".").'</em></td>
								<td><em> $'.number_format($deudaXcuota,0,",",".").'</em></td>
								<td><em>'.$condicion_label.'</em></td>
								<td><em>'.$vencimiento.'</em></td>
								</tr>';
						}
						$sql_cuota->free();
						
					///////////////
					
					if($mostrar_alumnos)
					{
						$contadorAlumnos++;
						$SUMA_TOTAL+=$TOTAL_DEUDA_ALUMNO;
						$tabla.='<tr>
						<td>'.$contadorAlumnos.'</td>
						<td>'.NOMBRE_CARRERA($id_carrera).'</td>
						<td>'.$estadoFinanciero.'</td>';
						
						if($opcion!="listado_para_profesores")
						{$tabla.='<td>'.$nivel_alumno.'</td>
						<td>'.$jornada_alumno.'</td>';}
						
						$tabla.='<td>'.$id_alumno.'</td>
						<td>'.$rut_alumno.'</td>
						<td>'.$nombre.'</td>
						<td '.$condicion_fila.'>'.$apellidos.'</td>';
						if($opcion=="listado_total")
						{
							$tabla.='<td align="center">'.$num_cuotas_pendientes.'</td>
							     <td align="right">$'.number_format($TOTAL_DEUDA_ALUMNO,0,",",".").'</td>';
						}
						$tabla.='</tr>';
					}
					
					if($mostrar_cuotas)
					{
						$tabla.= $lineas_detalle_cuota;
					}
				///////////////
		}
		$conexion_mysqli->close();
	}
	else{ $tabla.="Sin Alumnos Encontrados";}

}
else
{$tabla.="sin datos";}
	if($mostrar_total){
		$tabla.='<tr>
			<td colspan="'.($total_columnas-1).'">Total</td>
			<td align="right"><strong>'."$ ".number_format($SUMA_TOTAL,0,",",".").'</strong></td>
		</tr>';
		}
	$tabla.='<tr>
      <td colspan="'.$total_columnas.'"><em>('.$totalAlumnos.') Alumnos Encontrados, '.$num_morosos.' de ellos Morosos...</em></td>
    </tr>
    </tbody>
  </table>';	
	
echo $tabla;	
echo"Fecha/hora generacion: ".date("d-m-Y H:i:s");
?>