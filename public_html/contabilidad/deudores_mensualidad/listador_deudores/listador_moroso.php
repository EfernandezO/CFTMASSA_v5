<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("deudores_mensualidad_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	set_time_limit(600);
//-----------------------------------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion_2.php");?>
<title>Listado de Morosos</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:96%;
	height:115px;
	z-index:1;
	left: 2%;
	top: 119px;
}
#link {
	text-align: right;
	padding-right: 10px;
}
.Estilo1 {font-size: 14px}
a:link {
	color: #006699;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #006699;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #FFFFFF;
}
-->
</style>
</head>
<?php
	$sede=$_POST["sede"];
	$id_carrera=$_POST["id_carrera"];
	$nivel=$_POST["nivel"];
	$jornada=$_POST["jornada"];
	$grupo=$_POST["grupo"];
	$fecha_corte=$_POST["fecha_corte"];
	$yearIngresoCarrera=$_POST["yearIngresoCarrera"];
	$year_cuotas=$_POST["year_cuotas"];
	$opcion=$_POST["opcion"];
	
	$mesActual=date("m");
	$yearActual=date("Y");
	
	$semestreActual=1;
	if($mesActual>=8){$semestreActual=2;}
?>
<body>
<h1 id="banner">Administrador - Listado Morosos</h1>
<div id="link"><br />
<a href="index.php" class="button">Volver a Seleccion</a><br /><br />
<a href="listador_moroso_xls_v2.php?sede=<?php echo base64_encode($sede);?>&id_carrera=<?php echo base64_encode($id_carrera);?>&nivel=<?php echo base64_encode(serialize($nivel));?>&jornada=<?php echo base64_encode($jornada);?>&grupo=<?php echo base64_encode($grupo);?>&fecha_corte=<?php echo base64_encode($fecha_corte);?>&year_cuotas=<?php echo base64_encode($year_cuotas);?>&opcion=<?php echo base64_encode($opcion);?>&yearIngresoCarrera=<?php echo base64_encode($yearIngresoCarrera);?>&semestreConsulta=<?php echo base64_encode($semestreActual);?>&yearConsulta=<?php echo base64_encode($yearActual);?>" class="button" target="_blank">.XLS</a>
</div>
<div id="apDiv1">
  
<?php
if($_POST)
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
	echo"Listado tipo: $opcion<br>"; 
	echo'<table width="100%" border="1" align="center">
	<thead>
  	<tr>
    <th colspan="'.$total_columnas.'"><div align="center" class="Estilo1">Alumnos de Carrera: '.NOMBRE_CARRERA($id_carrera).' - '.$yearIngresoCarrera.' Nivel '.$nivel.' Jornada '.$jornada.' Grupo '.$grupo.' - '.$sede.' Cuotas: '.$year_cuotas.' Fecha Generacion: '.date("d-m-Y").'<br>Fecha Corte: '.$fecha_corte.'</div></th>
    </tr>
	</thead>
	<tbody>
    <tr>
	  <td><strong>N.</strong></td>
	  <td><strong>Carrera</strong></td>
	  <td>Estado Financiero</td>';
	  
	  if($opcion!="listado_para_profesores")
	  {echo'<td><strong>Nivel</strong></td>
	  <td><strong>Jornada</strong></td>';}
	  
      echo'<td><strong>ID alumno</strong></td>
      <td><strong>Rut</strong></td>
      <td><strong>Nombre</strong></td>
      <td '.$condicion_fila.'><strong>Apellido</strong></td>';
	  if($opcion=="listado_total")
	  {
		  echo'<td><strong>N. Cuotas Pendientes</strong></td>
      			<td><strong>Total Deuda</strong></td>';
		}
    echo'</tr>';
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
	
	$LISTA->setSemestreVigencia($semestreActual);
	$LISTA->setYearVigencia($yearActual);
	
	
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
						echo'<tr>
						<td>'.$contadorAlumnos.'</td>
						<td>'.NOMBRE_CARRERA($id_carrera).'</td>
						<td>'.$estadoFinanciero.'</td>';
						
						if($opcion!="listado_para_profesores")
						{echo'<td>'.$nivel_alumno.'</td>
						<td>'.$jornada_alumno.'</td>';}
						
						echo'<td>'.$id_alumno.'</td>
						<td>'.$rut_alumno.'</td>
						<td>'.$nombre.'</td>
						<td '.$condicion_fila.'>'.$apellidos.'</td>';
						if($opcion=="listado_total")
						{
							echo'<td align="center">'.$num_cuotas_pendientes.'</td>
							     <td align="right">$'.number_format($TOTAL_DEUDA_ALUMNO,0,",",".").'</td>';
						}
						echo'</tr>';
					}
					
					if($mostrar_cuotas)
					{
						echo $lineas_detalle_cuota;
					}
				///////////////
		}
		$conexion_mysqli->close();
	}
	else{ echo"Sin Alumnos Encontrados";}

}
else
{echo"sin datos";}
?>

<?php if($mostrar_total){?>
	<tr>
    	<td colspan="<?php echo ($total_columnas-1);?>">Total</td>
        <td align="right"><strong><?php echo "$ ".number_format($SUMA_TOTAL,0,",",".");?></strong></td>
    </tr>
    <?php }?>
    <tr>
      <td colspan="<?php echo $total_columnas;?>"><em>(<?php echo $totalAlumnos;?>) Alumnos Encontrados, <?php echo $num_morosos;?> de ellos Morosos...</em></td>
    </tr>
    </tbody>
  </table>
</div>
</body>
</html>
