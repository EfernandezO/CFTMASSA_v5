<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_alumnos_X_Asignatura_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
  if(DEBUG){ var_dump($_GET);}
  $sede=base64_decode($_GET['sede']);
  $id_carrera=base64_decode($_GET['id_carrera']); 
  $nivel=base64_decode($_GET['nive']);
  $jornada=base64_decode($_GET['jornada']);
  $grupo=base64_decode($_GET['grupo']);
  $cod_asignatura=base64_decode($_GET['cod_asignatura']);
  $year=base64_decode($_GET['year']);
  $semestre=base64_decode($_GET['semestre']);
  $mostrar_solo_alumnos_con_matricula=true;

  
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");
	$cons_MAIN="SELECT DISTINCT(toma_ramos.id_alumno), yearIngresoCarrera FROM toma_ramos INNER JOIN alumno ON toma_ramos.id_alumno=alumno.id WHERE alumno.sede='$sede' AND toma_ramos.id_carrera='$id_carrera' AND toma_ramos.jornada='$jornada' AND alumno.grupo='$grupo' AND toma_ramos.cod_asignatura='$cod_asignatura' AND toma_ramos.semestre='$semestre' AND toma_ramos.year='$year' ORDER by alumno. apellido_P, alumno.apellido_M";
	$sqli=$conexion_mysqli->query($cons_MAIN)or die($conexion_mysqli->error);
	$num_registros=$sqli->num_rows;
	if(DEBUG){ echo"<br>--->$cons_MAIN<br>N. $num_registros<br>";}
	
	
	$html='<table border=1>';

	if($num_registros>0)
	{
		//////////////////////////////////////////////////////////////
		//datos carrera
		$aux_nombre_carrera=NOMBRE_CARRERA($id_carrera);
		//---------------------------------------------------------------------
		//datos asignatura
		list($nombre_asignatura, $nivel_asignacion)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);
		//-------------------------------------------------------------	
		//recorro alumnos
		$borde=1;
		
		////////cabecera
		$html.='<thead>
		<tr>
			<th colspan="10"> '.$aux_nombre_carrera.' - '.$nombre_asignatura.'<br> '.$sede.' periodo[ '.$semestre.' - '.$year.']</th>
		</tr>
		<tr>
					<td>N</td>
					<td>Rut</td>
					<td>Nombre completo</td>
					<td>Nombre</td>
					<td>Apellido</td>
					<td>Estado</td>
					<td>Ingreso</td>
					<td>correo personal</td>
					<td>correo institucional</td>
					<td>fono</td>
				</tr>
				</thead>
				<tbody>';
					
			/////Registro ingreso///
		 include("../../../funciones/VX.php");
		 require("../../../funciones/class_ALUMNO.php");
		 $evento="Ve Informe(alumnosXcurso_asignatura)->id_carrera ".$id_carrera." Sede".$sede."- Jornada".$jornada."COd_asignatura: $cod_asignatura - Periodo[$semestre-$year]";
		  REGISTRA_EVENTO($evento);	
		$aux=0;	
		while($IA=$sqli->fetch_row())
		{
			$id_alumno=$IA[0];
			$yearIngresoCarrera=$IA[1];
			
			$ALUMNO=new ALUMNO($id_alumno);
			
			
			
			//--------------------------------------------------------------------------------------//
			//verificacion de matricula
			$A_situacion=ESTADO_ALUMNO_PERIODO($id_alumno, $id_carrera, $yearIngresoCarrera, $semestre, $year);
			$alumno_con_matricula=VERIFICAR_MATRICULA($id_alumno, $id_carrera, $yearIngresoCarrera, true, false, $semestre, false, $year);
			
			if($mostrar_solo_alumnos_con_matricula)
			{
				if($alumno_con_matricula){$mostrar_alumno=true;}
				else{$mostrar_alumno=false;}
			}
			else
			{
				if($A_situacion=="V")
				{ $mostrar_alumno=true;}
				else
				{ $mostrar_alumno=false;}
			}
			//----------------------------------------------------//
			if(($A_situacion=="V")or($A_situacion=="EG")){ $mostrar_alumno_2=true;}
			else{ $mostrar_alumno_2=false;}
			//------------------------------------------------------------------------------------------//
			
			if($mostrar_alumno and $mostrar_alumno_2){
				$aux++;
				$html.='<tr>
						<td>'.$aux.'</td>
						<td>'.$ALUMNO->getRut().'</td>
						<td>'.utf8_decode(ucwords(strtolower($ALUMNO->getNombre()))).'</td>
						<td>'.utf8_decode(ucwords(strtolower($ALUMNO->getApellido_P()))).'</td>
						<td>'.utf8_decode(ucwords(strtolower($ALUMNO->getApellido_M()))).'</td>
						<td>'.$A_situacion.'</td>
						<td>'.$yearIngresoCarrera.'</td>
						<td>'.$ALUMNO->getEmail().'</td>
						<td>'.$ALUMNO->getEmailInstitucional().'</td>
						<td>'.$ALUMNO->getFono().'</td>
					</tr>';
			}
						
				
			
		}
		
		$html.='</tbody></table>';
		$html.='<br> Alumnos Encontrados al '.date("d/m/Y").' A las '.date("H:i:s");
		
		
	
		if(DEBUG){echo $html;}
		else{
			header('Content-type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=alumnosXAsignatura_".$semestre."_".$year."".$sede."-idCarrera_".$id_carrera.".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			echo $html;
		}
	}
	else
	{
		echo"sin Registros<br>";
	}
	
	
$sqli->free();
$conexion_mysqli->close();
  
}
else
{header("location: alumno_carrera_asignatura_1.php");}
?>