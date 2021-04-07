<?php
//--------------CLASS_okalis------------------//
require("../../../OKALIS/class_OKALIS_v1.php");
define("DEBUG", false);
$O=new OKALIS();
$O->DEBUG=DEBUG;
$O->setDisplayErrors(false);
$O->ruta_conexion="../../../../funciones/";
$O->clave_del_archivo=md5("Notas_parcialesV3->verCalificador");
$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
	$sede=base64_decode($_GET["sede"]);
	$id_carrera=base64_decode($_GET["id_carrera"]);
	$jornada=base64_decode($_GET["jornada"]);
	$grupo_curso=base64_decode($_GET["grupo_curso"]);
	$cod_asignatura=base64_decode($_GET["cod_asignatura"]);
	$semestre=base64_decode($_GET["semestre"]);
	$year=base64_decode($_GET["year"]);
	
	//------------------------------------//
		require("../../../../funciones/VX.php");
		$evento="Resumen Notas Parciales V3 pdf en Notas Parciales V3 id_carrera:$id_carrera cod_asignatura: $cod_asignatura sede: $sede jornada: $jornada grupo: $grupo_curso [$semestre - $year]";
		REGISTRA_EVENTO($evento);
		//----------------------------------//
	
	if(isset($_GET["id_alumno"]))
	{
		$id_alumno_destacado=base64_decode($_GET["id_alumno"]);
		
		if(is_numeric($id_alumno_destacado))
		{ $destacar_alumno=true;}
		else
		{ $destacar_alumno=false;}
	}
	else
	{ $destacar_alumno=false;}
	////----------------------------------------------------------------///
	
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funciones_sistema.php");
	include("../../../../funciones/funcion.php");
	include("../../../libreria_publica/fpdf/fpdf.php");
		
	//nombre asignatura
	list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);
	///carrera
	$nombre_carrera=NOMBRE_CARRERA($id_carrera);
	
	////definicion de parametros
			$logo="../../../BAses/Images/logo_cft.jpg";
			$fecha_actual_palabra=fecha();
			$fecha_actual=date("d-m-Y");
			$salto_Y=15;//separacion entre parrafos
			
			
			$borde=0;
			
			$letra_1=14;
			$autor="Elias";
			$sub_titulo=utf8_decode($nombre_carrera)." - ".utf8_decode($nombre_asignatura)." Jornada: $jornada Grupo: $grupo_curso ($semestre / $year)";
			$zoom=50;	
			//inicializacion de pdf
			$pdf=new FPDF('L','mm','letter');
			$pdf->AddPage();
			$pdf->SetAuthor($autor);
			$pdf->SetTitle($sub_titulo);
			$pdf->SetDisplayMode($zoom);
			
				//---------INICIO ESCRITURA---------//
		///logo
		$pdf->image($logo,14,10,30,24,'jpg'); //este es el logo
		//titulo
		$pdf->SetFont('Times','',12);
		$pdf->MultiCell(260,6,$sede.", ".fecha().".-",$borde,'R');
		$Y_actual=$pdf->GetY();
		$pdf->SetY($Y_actual + $salto_Y);
		$pdf->SetFont('Times','',24);
		$pdf->MultiCell(260,12,"Informe Notas Parciales",$borde,'C');
		$pdf->SetFont('Times','',16);
		$pdf->MultiCell(260,10,$sub_titulo,$borde,'C');
		$pdf->SetFont('Times','B',14);
		//parrafo 1
		
		///EVALUACIONES
	 	$cons_e="SELECT * FROM notas_parciales_evaluaciones WHERE sede='$sede' AND id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND jornada='$jornada' AND grupo='$grupo_curso' AND semestre='$semestre' AND year='$year'";
		$sql_e=$conexion_mysqli->query($cons_e)or die($conexion_mysqli->error);
		$num_evaluaciones=$sql_e->num_rows;
		if(DEBUG){ echo"$cons_e<br>num evaluaciones: $num_evaluaciones<br>";}
		$array_evaluaciones=array();
		if($num_evaluaciones>0)
		{
			$aux=0;
			while($E=$sql_e->fetch_assoc())
			{
				$aux++;
				$id_evaluacion=$E["id"];
				$nombre_evaluacion=$E["nombre_evaluacion"];
				$fecha_generacion=$E["fecha_generacion"];
				$fecha_evaluacion=$E["fecha_evaluacion"];
				$metodo_evaluacion=$E["metodo_evaluacion"];
				$porcentaje=$E["porcentaje"];
				$tipo_evaluacion=$E["tipo_evaluacion"];
				
				$array_evaluaciones[$id_evaluacion]=$porcentaje;
				$array_evaluaciones_metodo[$id_evaluacion]=$metodo_evaluacion;
				$array_evaluaciones_tipo[$id_evaluacion]=$tipo_evaluacion;
			}
		}
		else
		{
			$pdf->SetFont('Times','B',14);
			$pdf->SetTextColor(255,0,0);
			$pdf->Cell(260,7,"Sin Evaluaciones Creadas",1,1,"C");
			$pdf->SetFont('Times','',12);
			$pdf->SetTextColor(0,0,0);
		}
		$sql_e->free();
		//FIN EVALUACIONES
		
		$ancho_X_evaluacion=(100/($num_evaluaciones+1));
		
		
		$pdf->Cell(10,7,"N",1,0,"C");
		$pdf->Cell(30,7,"Rut",1,0,"C");
		$pdf->Cell(40,7,"Apellido P",1,0,"C");
		$pdf->Cell(40,7,"Apellido M",1,0,"C");
		$pdf->Cell(40,7,"Nombre",1,1,"C");
		
		
		
			$cons_A="SELECT toma_ramos.*, alumno.* FROM toma_ramos INNER JOIN alumno ON toma_ramos.id_alumno = alumno.id WHERE toma_ramos.id_carrera='$id_carrera' AND alumno.sede='$sede' AND toma_ramos.jornada='$jornada' AND alumno.grupo='$grupo_curso' AND toma_ramos.semestre='$semestre' AND toma_ramos.year='$year' AND toma_ramos.cod_asignatura='$cod_asignatura' AND toma_ramos.id_carrera='$id_carrera' ORDER by alumno.apellido_P, alumno.apellido_M";
	$sql_A=$conexion_mysqli->query($cons_A)or die("Alumnos ".$conexion_mysqli->error);
	$num_alumnos=$sql_A->num_rows;
	if(DEBUG){ echo"$cons_A<br>NUM alumnos: $num_alumnos<br>";}
	
	if($num_alumnos>0)
	{
		$cuenta_alumnos=0;
		while($A=$sql_A->fetch_assoc())
		{
			$pdf->SetFont('Times','',12);
			$cuenta_alumnos++;
			
			$A_id=$A["id"];
			$A_rut=$A["rut"];
			$A_nombre=$A["nombre"];
			$A_apellido_P=$A["apellido_P"];
			$A_apellido_M=$A["apellido_M"];
			
			
			
					$pdf->Cell(10,7,$cuenta_alumnos,1,0,"C");
					$pdf->Cell(30,7,$A_rut,1,0,"C");
					$pdf->Cell(40,7,utf8_decode($A_apellido_P),1,0,"L");
					$pdf->Cell(40,7,utf8_decode($A_apellido_M),1,0,"L");
					$pdf->Cell(40,7,utf8_decode($A_nombre),1,0,"L");
					
			///espacio segun numero evaluaciones existentes		
			$PROMEDIO_ALUMNO=0;
			$cuenta_evaluacion=0;
			$cuenta_notas_puestas=0;
			
			if(count($array_evaluaciones)>0)
			{
				foreach($array_evaluaciones as $id_evaluacionx =>$porcentajex)
				{
					$cuenta_evaluacion++;
					$metodo_evaluacionx=$array_evaluaciones_metodo[$id_evaluacionx];
					$tipoEvaluacion=$array_evaluaciones_tipo[$id_evaluacionx];
					
					//echo"$metodo_evaluacionx<br>";
					
					$cons_BN="SELECT * FROM notas_parciales_registros WHERE id_alumno='$A_id' AND id_evaluacion='$id_evaluacionx' AND id_carrera='$id_carrera' LIMIT 1";
					$sql_BN=$conexion_mysqli->query($cons_BN)or die("Notas".$conexion_mysqli->error);
						$DN=$sql_BN->fetch_assoc();
						$aux_nota_parcial=$DN["nota"];
						$sql_BN->free();
					
					switch($metodo_evaluacionx)
					{
						case"ponderado":
							if($aux_nota_parcial>0){$PROMEDIO_ALUMNO+=(($aux_nota_parcial*$porcentajex)/100); $cuenta_notas_puestas++; }
							$title_nota="Evaluacion ponderada $cuenta_evaluacion [ $porcentajex %]";
							break;
						default:
							if($aux_nota_parcial>0){$PROMEDIO_ALUMNO+=$aux_nota_parcial; $cuenta_notas_puestas++;}
							$title_nota="Evaluacion Promediada $cuenta_evaluacion";
					}
					
					
					//seleccion de color
					switch($tipoEvaluacion){
						case"parcial":
							$pdf->SetFillColor(122,215,143);
							break;
						case"global":
							$pdf->SetFillColor(133,193,233);
							break;
						case"repeticion":
							$pdf->SetFillColor(215,143,122);
							break;	
					}
					
					
					$pdf->Cell($ancho_X_evaluacion,7,$aux_nota_parcial,1,0,"C",1);
				}
				if(isset($metodo_evaluacionx))
				{
					switch($metodo_evaluacionx)
						{
							case"ponderado":
								break;
							default:
								if($cuenta_notas_puestas)
								{ $PROMEDIO_ALUMNO=($PROMEDIO_ALUMNO/$cuenta_notas_puestas);}
								else{ $PROMEDIO_ALUMNO=0;}
						}
						
					list($array_notas, $PROMEDIO_ALUMNO_V2)=NOTAS_PARCIALES_V3($A_id,$id_carrera, $cod_asignatura, $jornada, $semestre, $year);
					$pdf->SetFont('Times','B',12);	
					$pdf->Cell($ancho_X_evaluacion,7,number_format($PROMEDIO_ALUMNO_V2,1,".",","),1,1,"C");
				}
			}
			else
			{
				$pdf->Cell($ancho_X_evaluacion,7,number_format("",1,".",","),1,1,"C");
			}
				
		}
		$sql_A->free();
	}
	else
	{ $pdf->Cell(15,7,"Sin Registros...",1,1,"C");}
		
	
		$conexion_mysqli->close();
		$pdf->Output();
	
	
}
else{ echo"Sin Datos";}
?>