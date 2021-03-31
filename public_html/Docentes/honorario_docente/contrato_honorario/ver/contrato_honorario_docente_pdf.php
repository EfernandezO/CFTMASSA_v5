<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("ver_contrato_docente");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	include("../../../../../funciones/conexion_v2.php");
	require("../../../../../funciones/funcion.php");
	require("../../../../../funciones/funciones_varias.php");
	require("../../../../libreria_publica/fpdf/flowing_block.php");
	require("../../../../../funciones/funciones_sistema.php");
	require("../../../../libreria_publica/PHPMailer_v5.1/class.phpmailer.php");
	include("../../../../../funciones/VX.php");
	if(DEBUG){error_reporting(E_ALL); ini_set("display_errors", 1);}

	$html='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Envio de Contrato de Honorario Docente</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 250px;
}
</style></head>
	<body>
	<h1 id="banner">Administrador - Envio Contrato de  Honorario Docente</h1>
	<div id="link"><br />
	<a href="index.php" class="button">Volver a seleccion</a></div>
	<div id="apDiv1">
	<table align="center" width="100%">
	<thead>
		<tr>
			<th colspan="5">Lista de Envios</th>
		</tr>
	</thead>
	<tbody>';

if($_POST)
{
	$semestre=mysqli_real_escape_string($conexion_mysqli, $_POST["semestre"]);
	$year=mysqli_real_escape_string($conexion_mysqli, $_POST["year"]);
	$sede=mysqli_real_escape_string($conexion_mysqli, $_POST["sede"]);
	$id_funcionario=mysqli_real_escape_string($conexion_mysqli, $_POST["funcionario"]);
	$continuar=true;
	$que_hacer=mysqli_real_escape_string($conexion_mysqli, $_POST["que_hacer"]);
	$tipo=$que_hacer;
}
else
{ $continuar=false;}
//-----------------------------------------------------------------------//

if($continuar)
{

	if($id_funcionario=="todos"){ $condicion_funcionario="";}
	else{ $condicion_funcionario="AND toma_ramo_docente.id_funcionario='$id_funcionario'";}

		$cons="SELECT DISTINCT(id_funcionario) FROM toma_ramo_docente INNER JOIN personal ON toma_ramo_docente.id_funcionario=personal.id WHERE toma_ramo_docente.sede='$sede' AND toma_ramo_docente.semestre='$semestre' AND toma_ramo_docente.year='$year' $condicion_funcionario ORDER by personal.apellido_P, personal.apellido_M";
	
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_registros=$sqli->num_rows;
	if(DEBUG){ echo"$cons<br>numero: $num_registros<br>";}
	
	if($num_registros>0)
	{
		$mostrar_boton=true;
		
			$logo="../../../../BAses/Images/logo_cft.jpg";
			$fecha_actual_palabra=fecha();
			$fecha_actual=date("d-m-Y");
			$y_firmas=250;
			
			$alto_celda=5;
			$borde=0;
			$borde_p=1;
			$letra_1=10;
			$letra_2=10;
			$letra_pie=8;
			$fecha=fecha();
			$autor="ACX";
			$titulo="Contrato de ".utf8_decode("Prestación")." de Servicios a Honorarios";
			$zoom=75;
			$largo_folio=5;///agrega "0" antes del folio hasta dejarlo del largo
			
			
			if($tipo!="mail")
			{
				$hoja_oficio[0]=217;
				$hoja_oficio[1]=330;
				$pdf=new PDF_FlowingBlock();
				
				$pdf->SetAuthor($autor);
				$pdf->SetTitle($titulo);
				$pdf->SetDisplayMode($zoom);
				$pdf->SetAutoPageBreak(true, 5);
			}
			else{ echo $html; $contador_envios=0; $contador_envio_general=0;}
			
		$aux_X=0;	
		while($F=$sqli->fetch_row())
		{
			$aux_X++;
			if($tipo=="mail")
			{
				$enviar_copia_oculta=false;
				$email_BCC="dat@cftmass.cl";
				$user_correo="no_responder@cftmass.cl";
				$pass_correo="15_xXCo37";
				$nombre_envio="Robot CFT Massachusetts";
				$asunto="Contrato Honorario - CFT Massachusetts $sede [$semestre - $year]";
				$hoja_oficio[0]=217;
				$hoja_oficio[1]=330;
				$pdf=new PDF_FlowingBlock();
				$pdf->SetAuthor($autor);
				$pdf->SetTitle($titulo);
				$pdf->SetDisplayMode($zoom);
				$pdf->SetAutoPageBreak(true, 5);
			}
			//datos de docente
			$aux_id_funcionario=$F[0];
			$cons_F="SELECT rut, nombre, apellido_P, apellido_M, email, email_personal FROM personal WHERE id='$aux_id_funcionario' LIMIT 1";
			$sqli_F=$conexion_mysqli->query($cons_F);
				$P=$sqli_F->fetch_assoc();
				$nombre_funcionario=ucwords(strtolower($P["nombre"]));
				$apellido_funcionario=ucwords(strtolower($P["apellido_P"]." ".$P["apellido_M"]));
				$rut_funcionario=$P["rut"];
				$email_funcionario=$P["email"];
				//$email_funcionario="informatica@cftmass.cl";
				$email_personal_funcionario=$P["email_personal"];
				
				if((((empty($email_funcionario))or($email_funcionario=="Sin Registro"))and(!empty($email_personal_funcionario)))){ $email_funcionario=$email_personal_funcionario;}
			$sqli_F->free();
			if(DEBUG){echo"<br>[$aux_X]_____________________________________________________________________<br>id_funcionario: $aux_id_funcionario nombre: $nombre_funcionario $apellido_funcionario<br>email: $email_funcionario email personal: $email_personal_funcionario<br>";}
			
			//consulta asignaciones del docente
			$lugar_contrato=$sede;
			$fecha_actual_palabra=fecha();
			
			if($sede=="Talca")
			{$direccion_cft="3 Sur Nª 1068";}
			else
			{$direccion_cft="O'Higgins Nª 313";}	
			
			//-----------------------------------------------------------------------------///
			//consulto las horas y datos relativos
			$ARRAY_ASIGNACIONES=array();
			
			
			//modificado 14/05/2018
			//busco solo asignaturas sin importar si estan pagadas
			$cons_AS="SELECT * FROM toma_ramo_docente WHERE id_funcionario='$aux_id_funcionario' AND sede='$sede' AND semestre='$semestre' AND year='$year' AND (cod_asignatura BETWEEN '1' AND '86')";
			
			$sqli_AS=$conexion_mysqli->query($cons_AS);
			$numero_asignacion_DOCENCIA=$sqli_AS->num_rows;
			if(DEBUG){ echo"$cons_AS <br>numero asignaciones: $numero_asignacion_DOCENCIA";}
			$SUMA_TOTAL_FUNCIONARIO=0;
			$SUMA_TOTAL_HORAS=0;
			$VALOR_HORA_FUNCIONARIO=0;
			$NUMERO_CUOTAS_FUNCIONARIO=0;
			$VALOR_CUOTA_FUNCIONARIO=0;
			$sqli_ASV=$conexion_mysqli->query($cons_AS)or die($conexion_mysqli->error);
			
			$ARRAY_TOTAL_VALOR_HORA=array();
			while($ASV=$sqli_ASV->fetch_assoc())
			{
				$aux_numero_horas=$ASV["numero_horas"];
				$aux_valor_hora=$ASV["valor_hora"];
				$aux_total=$ASV["total"];
				$aux_numero_cuotas=$ASV["numero_cuotas"];
				$aux_id_carrera=$ASV["id_carrera"];
				
				$ARRAY_TOTAL_VALOR_HORA[$aux_id_carrera]["valor_hora"]=$aux_valor_hora;
				//diferencia valor hora por carrera
				if(isset($ARRAY_TOTAL_VALOR_HORA[$aux_id_carrera]["numero_horas"]))
				{$ARRAY_TOTAL_VALOR_HORA[$aux_id_carrera]["numero_horas"]+=$aux_numero_horas;}
				else{$ARRAY_TOTAL_VALOR_HORA[$aux_id_carrera]["numero_horas"]=$aux_numero_horas;}
				
				$SUMA_TOTAL_FUNCIONARIO+=$aux_total;
				$SUMA_TOTAL_HORAS+=$aux_numero_horas;
				
				if($aux_valor_hora>$VALOR_HORA_FUNCIONARIO){ $VALOR_HORA_FUNCIONARIO=$aux_valor_hora;}
				if($aux_numero_cuotas>$NUMERO_CUOTAS_FUNCIONARIO){ $NUMERO_CUOTAS_FUNCIONARIO=$aux_numero_cuotas;}
			}
			
			if($NUMERO_CUOTAS_FUNCIONARIO>0){$VALOR_CUOTA_FUNCIONARIO=($SUMA_TOTAL_FUNCIONARIO/$NUMERO_CUOTAS_FUNCIONARIO);}else{ $VALOR_CUOTA_FUNCIONARIO="XXXX".$SUMA_TOTAL_FUNCIONARIO;}
			$sqli_ASV->free();
			//-------------------------------------------------------------------------------///
			//consulto fecha de año academico
			
			$consFA="SELECT fechaInicio, fechaFin FROM fechasAcademicas WHERE semestre='$semestre' AND year='$year' LIMIT 1";
			$sqliFA=$conexion_mysqli->query($consFA)or die($conexion_mysqli->error);
			$DFA=$sqliFA->fetch_assoc();
				$fechaAcademicaInicio=$DFA["fechaInicio"];
				$fechaAcademicaFin=$DFA["fechaFin"];
				
				$fechaAcademicaInicio=date("d-m-Y",strtotime($fechaAcademicaInicio));
				$fechaAcademicaFin=date("d-m-Y",strtotime($fechaAcademicaFin));
			$sqliFA->free();	
			//------------------------------------------------------------------//
			switch($sede){
				case"Talca":
					if($semestre==1){$fecha_inicio_cuota="30-04-$year"; $mesCuotaJC="Marzo a Agosto";}
					else{$fecha_inicio_cuota="30-09-$year"; $mesCuotaJC="Septiembre a Febrero";}
					break;
				case"Linares":
					if($semestre==1){$fecha_inicio_cuota="30-03-$year"; $mesCuotaJC="Marzo  a Agosto";}
					else{$fecha_inicio_cuota="30-08-$year"; $mesCuotaJC="Septiembre a Febrero";}
					break;
			}
			
			
			//consulto si es jefe de carrera
			list($es_jefe_de_carrera, $array_id_carrera)=ES_JEFE_DE_CARRERA($aux_id_funcionario, $semestre, $year, $sede);
			//determino si imprimir o no contrato
			$verAnexoDocente=false;
			$verContratoDocente=false;
			$verContratoJefeCarrera=false;
			if($numero_asignacion_DOCENCIA>0){$verContratoDocente=true; $verAnexoDocente=true;}
			if($es_jefe_de_carrera){$verContratoJefeCarrera=true;}
			
			//contrato docente
			if($verContratoDocente){
				
				//variable x parrafo parrafo
			$parrafo_1_a="En ".$lugar_contrato." a ".$fechaAcademicaInicio.", entre el Centro de ".utf8_decode("Fomación")." ".utf8_decode("Técnica")." Massachusetts Ltda. RUT.:89.921.100-6, Representada para estos efectos por el ".utf8_decode("Señor")." Juan Carlos Figueroa U., RUT: 6.015.058-3, ambos con domicilio en la ciudad de ".utf8_decode($lugar_contrato).", ".utf8_decode($direccion_cft).", por una parte se ha convenido el siguiente Contrato de ".utf8_decode("Prestación")." de Servicios Docentes a Honorarios.";
			//*************
			$parrafo_2="El C.F.T Massachusetts Ltda, en virtud de los antecedentes profesionales, contrata los servicios de ".utf8_decode("señor")." ".utf8_decode($nombre_funcionario)." ".utf8_decode($apellido_funcionario).", RUT:".$rut_funcionario.", para efectuar la labor de Docente en este establecimiento de ".utf8_decode("Educación");
			
			
			
			$parrafo_3="";
			foreach($ARRAY_TOTAL_VALOR_HORA as $id_carreraX => $arrayX){
				$nombre_carreraX=NOMBRE_CARRERA($id_carreraX);
				$valor_horaX=$arrayX["valor_hora"];
				$numero_horasX=$arrayX["numero_horas"];
				$valor_X_docencia=$valor_horaX*$numero_horasX;
				$parrafo_3.=utf8_decode("El valor de la hora líquida pedagógica para la carrera ".$nombre_carreraX.", asciende a la suma de $".number_format($valor_horaX,0,",",".")." y la jornada será de un total de ".number_format($numero_horasX,0,",",".").", horas pedagógicas semestrales, cuyos honorarios a percibir en este periodo equivaldrá, a la suma de $".number_format($valor_X_docencia,0,",",".").", ");
			}
			
			
			//old
			/*$parrafo_3.=utf8_decode("por acuerdo mutuo de las partes, se cancelará en anticipos mensuales en virtud a la docencia realizada, considerando para esto un semestre de cinco meses, valor ponderado de cada anticipo es de $".number_format($VALOR_CUOTA_FUNCIONARIO,0,",",".").", a partir del ".$fecha_inicio_cuota.", en ".$NUMERO_CUOTAS_FUNCIONARIO." Cuota(s). El C.F.T. Massachusetts Ltda., retendrá el impuesto legal de segunda categoría correspondiente al 10%.");*/
			
			$parrafo_3.=utf8_decode("por acuerdo mutuo de las partes, se cancelará en una cuota semestral en virtud a la docencia realizada, considerando para esto que el semestre finaliza el $fechaAcademicaFin, en esta fecha el valor a cancelar es de $".number_format($VALOR_CUOTA_FUNCIONARIO,0,",",".").", en ".$NUMERO_CUOTAS_FUNCIONARIO." Cuota(s). El C.F.T. Massachusetts Ltda., retendrá el impuesto legal de segunda categoría correspondiente al 10.75%.");
			
			$parrafo_4=utf8_decode("El Presente contrato durará hasta que se complete el total de las horas pedagógicas pactadas, señaladas en el punto segundo inmediatamente anterior y además se podrá poner término cuando concurra para ello causales justificadas que, en conformidad a la ley, puedan producir caducidad.");
			
			$parrafo_5a=utf8_decode("a)	Desarrollar su labor docente, para el logro de los objetivos de los programas de estudio de las asignaturas que imparte, contribuyendo eficazmente en el afiatamiento y formación de hábitos, valores destacables en los alumnos.");
			
			$parrafo_5b=utf8_decode("b)	Entregar al inicio de semestre o cuando la dirección lo requiera la Planificación de la, o las Asignaturas que imparte, según protocolo establecido.");
			
			$parrafo_5c=utf8_decode("c) Cumplir con el calendario académico de la forma más eficaz posible.");
			$parrafo_5d=utf8_decode("d) Llevar el libro de clases, contemplando la asistencia de los alumnos, las firmas de las horas realizadas, la anotación de los objetivos y los contenidos desarrollados, las calificaciones de los alumnos y el resumen de asistencia semestral");
			$parrafo_5e=utf8_decode("e) Tomar las evaluaciones establecidas de acuerdo al reglamento académico.");
			$parrafo_5f=utf8_decode("f) Entregar al Director Académico y/o jefe de carrera, al término del semestre toda la información estadística que le sea solicitada.");
			$parrafo_5g=utf8_decode("g) Concurrir a las reuniones de carrera en las que sirva asignaturas en cada semestre y aportar toda la información estadística que le sea solicitada.");
			$parrafo_5h=utf8_decode("h) Participar en las actividades extra programaticas, organizadas por las carreras o la institución.");
			$parrafo_5i=utf8_decode("i) Asistir a los perfeccionamientos programados por la institución, se considerará como política de contratación y reajustes de sueldos futuros.");
			
			$parrafo_6=utf8_decode("El Presente contrato se firma en dos ejemplares quedando uno en poder del CFT Massachusetts Ltda. y otro en poder del docente");
			$pdf->AddPage("P", "Letter");	
			$pdf->image($logo,14,10,30,24,'jpg');
			//titulo
			//titulo
			$pdf->Ln();
			$pdf->SetFont('Arial','U',14);
			$pdf->Cell(190,20,$titulo,$borde,1,"C");
			$pdf->Ln(10);
			//***************************************************
			//
			$pdf->SetFont('Arial','',$letra_1);
			//folio
			///primer parrafo
			$pdf->newFlowingBlock(195, $alto_celda,0,'J',0);
			$pdf->SetFont( 'Arial', '', $letra_1 );
			$pdf->WriteFlowingBlock($parrafo_1_a);
			$pdf->SetFont( 'Arial', 'B', $letra_1 );
			$pdf->finishFlowingBlock();
			
			$pdf->Ln();
			$pdf->SetFont('Arial','B',$letra_1);
			$pdf->Cell(23,$alto_celda,"PRIMERO",$borde,0,'L');	
			$pdf->SetFont('Arial','',$letra_1);
			$pdf->MultiCell(172,$alto_celda,$parrafo_2,$borde,1,'L');
			
			$pdf->Ln();
			$pdf->SetFont('Arial','B',$letra_1);
			$pdf->Cell(23,$alto_celda,"SEGUNDO",$borde,0,'L');	
			$pdf->SetFont('Arial','',$letra_1);
			$pdf->MultiCell(172,$alto_celda,$parrafo_3,$borde,1,'L');
			
			$pdf->Ln();
			$pdf->SetFont('Arial','B',$letra_1);
			$pdf->Cell(23,$alto_celda,"TERCERO",$borde,0,'L');	
			$pdf->SetFont('Arial','',$letra_1);
			$pdf->MultiCell(172,$alto_celda,$parrafo_4,$borde,1,'L');
			
			$pdf->Ln();
			$pdf->SetFont('Arial','B',$letra_1);
			$pdf->Cell(23,$alto_celda,"CUARTO",$borde,0,'L');	
			$pdf->SetFont('Arial','',$letra_1);
			$pdf->Cell(172,$alto_celda,"Se consideran dentro de las labores de docencia los siguientes puntos",$borde,1,'L');	
			
			$pdf->Ln();
			$pdf->MultiCell(195,$alto_celda,$parrafo_5a,$borde,1,'L');
			$pdf->MultiCell(195,$alto_celda,$parrafo_5b,$borde,1,'L');
			$pdf->MultiCell(185,$alto_celda,$parrafo_5c,$borde,1,'L');
			$pdf->MultiCell(185,$alto_celda,$parrafo_5d,$borde,1,'L');
			$pdf->MultiCell(185,$alto_celda,$parrafo_5e,$borde,1,'L');
			$pdf->MultiCell(185,$alto_celda,$parrafo_5f,$borde,1,'L');
			$pdf->MultiCell(185,$alto_celda,$parrafo_5g,$borde,1,'L');
			$pdf->MultiCell(185,$alto_celda,$parrafo_5h,$borde,1,'L');
			$pdf->MultiCell(185,$alto_celda,$parrafo_5i,$borde,1,'L');
			
		
			$pdf->Ln();
			$pdf->SetFont('Arial','',$letra_1);
			$pdf->MultiCell(190,$alto_celda,$parrafo_6,$borde,1,'L');
			
			
			$pdf->Ln(10);
			$pdf->Cell(98,4,"_________________________",$borde,0,'C');	
			$pdf->Cell(98,4,"_________________________",$borde,1,'C');	
			
			$pdf->SetFont('Arial','',$letra_pie);
			$pdf->Cell(98,3,"Fima Docente",$borde,0,'C');	
			$pdf->Cell(98,3,"Juan Carlos Figueroa U",$borde,1,'C');
				
			$pdf->Cell(98,3,"Rut.:".$rut_funcionario,$borde,0,'C');	
			$pdf->Cell(98,3,"Representante Legal",$borde,1,'C');	
			}else{ if(DEBUG){ echo"No mostrar contrato docente<br>";}}
			
			//-------------------------------------------------------------//
			if($verAnexoDocente){
				if(DEBUG){ echo"Ver ANEXO ASIGNACIONES<br>";}
				$margen=20;
				$titulo_2="Anexo - Asignaciones";
				$pdf->AddPage("L", "Letter");
				
				$pdf->SetFont('Arial','',10);
				$pdf->Cell(276,6,$fechaAcademicaInicio,$borde_p*0,1,'R');
				$pdf->image($logo,20,10,30,24,'jpg'); //este es el logo
				
				
				$pdf->SetFont('Arial','B',16);
				$pdf->SetX($margen);
				$pdf->Cell(276,20,$titulo_2,$borde_p*0,1,'C');
				//parrafo 1
				$pdf->Ln();
				$pdf->SetFont('Arial','B',$letra_1);
				$pdf->SetX($margen);
				$pdf->Cell(135,6,"Datos del Docente ",$borde_p,1,"L");
				$pdf->SetFont('Arial','',$letra_1);
				$pdf->SetX($margen);
				$pdf->Cell(30,6,"Rut",$borde_p,0,"L");
				$pdf->Cell(105,6,$rut_funcionario,$borde_p,1,"L");
				$pdf->SetX($margen);
				$pdf->Cell(30,6,"Nombre",$borde_p,0,"L");
				$pdf->Cell(105,6,utf8_decode($nombre_funcionario),$borde_p,1,"L");
				$pdf->SetX($margen);
				$pdf->Cell(30,6,"Apellido",$borde_p,0,"L");
				$pdf->Cell(105,6,utf8_decode($apellido_funcionario),$borde_p,1,"L");
				
				$pdf->Ln();
				$pdf->Ln();
				$pdf->SetFont('Arial','B',$letra_1);
				$pdf->SetX($margen);
				$pdf->Cell(270,6,"Lista de Ramos Semestre $semestre - ".utf8_decode("Año")." $year",$borde_p,1,"L");
				$pdf->SetFont('Arial','',$letra_1);
				
				$pdf->SetX($margen);
				$pdf->Cell(10,6,"-",$borde_p,0,"C");
				$pdf->Cell(15,6,"Sede",$borde_p,0,"C");
				$pdf->Cell(80,6,"Carrera",$borde_p,0,"L");
				$pdf->Cell(8,6,"Jor",$borde_p,0,"L");
				$pdf->Cell(10,6,"Grp",$borde_p,0,"C");
				$pdf->Cell(10,6,"Nivel",$borde_p,0,"C");
				$pdf->Cell(90,6,"Ramo",$borde_p,0,"L");
				$pdf->Cell(14,6,"$.Hr",$borde_p,0,"L");
				$pdf->Cell(14,6,"N.Hrs",$borde_p,0,"L");
				$pdf->Cell(19,6,"Total",$borde_p,1,"L");
				
				//consulto asignaciones del docente
				if(DEBUG){ echo"Consulto asignaciones de Docente<br>";}
				
				$cons="SELECT toma_ramo_docente.* FROM toma_ramo_docente WHERE toma_ramo_docente.id_funcionario='$aux_id_funcionario' AND toma_ramo_docente.semestre='$semestre' AND toma_ramo_docente.year='$year' AND sede='$sede' AND (cod_asignatura BETWEEN '1' AND '86')";
				
				
				if(DEBUG){ echo"--->$cons<br>";}
				$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
				$enviar_mail=true;
				$aux=0;
				$SUMA_TOTAL=0;
				$SUMA_HORAS=0;
				while($R=$sql->fetch_assoc())
				{
					$aux++;
					$pdf->SetFont('Arial','',$letra_2);
					$R_numero_horas=$R["numero_horas"];
					$R_codigo=$R["cod_asignatura"];
					$R_fecha_generacion=fecha_format($R["fecha_generacion"]);
					$R_id_carrera=$R["id_carrera"];
					$R_sede=$R["sede"];
					$R_valor_hora=$R["valor_hora"];
					$R_total=$R["total"];
					$R_jornada=$R["jornada"];
					$R_grupo=$R["grupo"];
					
					$SUMA_TOTAL+=$R_total;
					$SUMA_HORAS+=$R_numero_horas;
					
					list($R_ramo, $R_nivel)=NOMBRE_ASIGNACION($R_id_carrera, $R_codigo);
					
					$cons_carrera="SELECT carrera FROM carrera WHERE id='$R_id_carrera' LIMIT 1";
					$sql_carrera=$conexion_mysqli->query($cons_carrera)or die($conexion_mysqli->error);
						$DC=$sql_carrera->fetch_assoc();
						$R_carrera=$DC["carrera"];
					$sql_carrera->free();	
					
					if(DEBUG){ echo"$R_codigo - $R_ramo<br>";}
					$pdf->SetX($margen);
					$pdf->Cell(10,5,$aux,$borde_p,0,"C");
					$pdf->Cell(15,5,$R_sede,$borde_p,0,"C");
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(80,5,utf8_decode($R_carrera),$borde_p,0,"L");
					$pdf->SetFont('Arial','',$letra_2);
					$pdf->Cell(8,5,$R_jornada,$borde_p,0,"C");
					$pdf->Cell(10,5,$R_grupo,$borde_p,0,"C");
					$pdf->Cell(10,5,$R_nivel,$borde_p,0,"C");
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(90,5,utf8_decode($R_ramo),$borde_p,0,"L");
					$pdf->SetFont('Arial','',$letra_2);
					$pdf->Cell(14,5,number_format($R_valor_hora,0,",","."),$borde_p,0,"R");
					$pdf->Cell(14,5,$R_numero_horas,$borde_p,0,"R");
					$pdf->Cell(19,5,number_format($R_total,0,",","."),$borde_p,1,"R");
	
				}
				$pdf->SetX($margen);
				$pdf->Cell(25,5,"TOTAL",$borde_p,0,"L");
				$pdf->Cell(198,5,"",$borde_p,0,"R");
				$pdf->Cell(14,5,"",$borde_p,0,"R");
				$pdf->Cell(14,5,$SUMA_HORAS,$borde_p,0,"R");
				$pdf->Cell(19,5,number_format($SUMA_TOTAL,0,",","."),$borde_p,1,"R");
			}else{ if(DEBUG){ echo"No mostrar ANEXO contrato docente<br>";}}
				
			//-------------------------------------------------------------------------///
			if($verContratoJefeCarrera)
			{
				$cons_JF="SELECT * FROM toma_ramo_docente WHERE id_funcionario='$aux_id_funcionario' AND sede='$sede' AND semestre='$semestre' AND year='$year' AND cod_asignatura ='0'";
					$sqlJF=$conexion_mysqli->query($cons_JF);
					$SUMA_TOTAL_JF=0;
					$SUMA_HORAS_JF=0;
					$aux=0;
					while($RJF=$sqlJF->fetch_assoc())
					{
						$aux++;
						
						$R_numero_horas_JF=$RJF["numero_horas"];
						$R_codigo_JF=$RJF["cod_asignatura"];
						$R_fecha_generacion_JF=fecha_format($RJF["fecha_generacion"]);
						$R_id_carrera_JF=$RJF["id_carrera"];
						
						$numeroHorasSemanaJC=8;
						if($R_id_carrera_JF==4){$numeroHorasSemanaJC=10;}
						
						$R_sede_JF=$RJF["sede"];
						$R_valor_hora_JF=$RJF["valor_hora"];
						$R_total_JF=$RJF["total"];
						$R_jornada_JF=$RJF["jornada"];
						$R_grupo_JF=$RJF["grupo"];
						$R_numero_cuotas_JF=$RJF["numero_cuotas"];
						
						$SUMA_TOTAL_JF+=$R_total_JF;
						$SUMA_HORAS_JF+=$R_numero_horas_JF;
						
						list($R_ramo, $R_nivel)=NOMBRE_ASIGNACION($R_id_carrera_JF, $R_codigo_JF);
					}
					
					$valorCuotaJefeCarrera=($SUMA_TOTAL_JF/$R_numero_cuotas_JF);
					$sqlJF->free();
					
					
					$pdf->AddPage("P", "Letter");
					$pdf->ln();
					//$pdf->SetX($margen);
					$pdf->SetFont('Arial','B',$letra_1);
					
					$parrafo_J1="\t\t\t\t\t\t\t\tEn ".$lugar_contrato." a ".$fechaAcademicaInicio.", entre el Centro de ".utf8_decode("Fomación")." ".utf8_decode("Técnica")." Massachusetts Ltda. RUT.:89.921.100-6, Representada para estos efectos por el ".utf8_decode("Señor")." Juan Carlos Figueroa U., RUT: 6.015.058-3, ambos con domicilio en la ciudad de ".utf8_decode($lugar_contrato).", ".utf8_decode($direccion_cft).", por una parte se ha convenido el siguiente Contrato de ".utf8_decode("Prestación")." de Servicios 'Jefe de Carrera' a Honorarios.";
			//*************
					$parrafo_J2="El C.F.T Massachusetts Ltda, en virtud de los antecedentes profesionales, contrata los servicios de ".utf8_decode("señor (a)")." ".utf8_decode($nombre_funcionario)." ".utf8_decode($apellido_funcionario).", RUT:".$rut_funcionario.", para efectuar en la carrera de ".utf8_decode(NOMBRE_CARRERA($R_id_carrera_JF)).", la labor de Jefe(a) de Carrera en este establecimiento de ".utf8_decode("Educación").".";
					
					//old 23/04/2020
					/*$parrafo_3=utf8_decode("El Valor de los honorarios a percibir en el periodo, $semestre Semestre del $year,  equivaldra, a la suma de $ ".number_format($SUMA_TOTAL_JF,0, "",".").", por mutuo acuerdo de las partes , se cancelará este valor en $R_numero_cuotas_JF anticipos mensuales desde $mesCuotaJC del $year, el valor ponderado de cada anticipo es de $".number_format($valorCuotaJefeCarrera,0,"",".").". El Centro del Formacion Técnica Massachusetts Ltda., retendrá el impuesto legal de segunda categoria, correspondiente al 10%. ");*/
					
						$parrafo_3=utf8_decode("El Valor de los honorarios a percibir en el periodo, $semestre Semestre del $year,  equivaldra, a la suma de $ ".number_format($SUMA_TOTAL_JF,0, "",".").", por mutuo acuerdo de las partes , se cancelará este valor en $R_numero_cuotas_JF cuota(s) a partir del $fechaAcademicaFin. ");
						
						if($R_numero_cuotas_JF>1){
						$parrafo_3.=utf8_decode("El valor ponderado de cada cuota es de $".number_format($valorCuotaJefeCarrera,0,"",".").".");
						}
						$parrafo_3.=utf8_decode("El Centro del Formacion Técnica Massachusetts Ltda., retendrá el impuesto legal de segunda categoria, correspondiente al 10.75%. ");
					
					
					$parrafo_4=utf8_decode("El profesional deberá cumplir ".$numeroHorasSemanaJC." horas a la semana de jefatura de acuerdo al periodo señalado en el punto  segundo");
					
					$parrafo_5=utf8_decode("Se considera dentro de las labores de jefe de carrera las siguientes responsabilidades:\n1.- Supervisar la correcta elaboración de las planificaciones de los docentes de su carrera.\n2.- El desarrollo normal de las clases programadas. \n3.- El correcto registro en los libros de clases, de firma, contenido y asistencia del alumnado. \n4.- Informar a dirección académica, sobre el cumplimiento o incumplimiento de los requeriminetos académicos administrativos. \n5.- Desarrollar atención de alumnos en horarios establecidos. \n6.- Responder a consultas ya sea verbal o por escrito sobre temas de docencia, académicos y administrativos, ante la dirección académica.");
					$parrafo_6="El presente contrato se firma en dos ejemplares quedando uno en poder del C.F.T. Massachusetts LTDA.  y otro en poder de jefe de carrera.";
					
					$pdf->image($logo,14,10,30,24,'jpg');
					//titulo
					//titulo
					$pdf->Ln();
					$pdf->SetFont('Arial','U',14);
					$pdf->Cell(190,20,$titulo,$borde,1,"C");
					$pdf->Ln(10);
					//***************************************************
					//
					$pdf->SetFont('Arial','',$letra_1);
					//folio
					///primer parrafo
					$pdf->newFlowingBlock( 195, $alto_celda, 0, 'L');
					$pdf->SetFont( 'Arial', '', $letra_1 );
					$pdf->WriteFlowingBlock( $parrafo_J1 );
					$pdf->SetFont( 'Arial', 'B', $letra_1 );
					$pdf->finishFlowingBlock();
					
					$pdf->Ln();
					$pdf->SetFont('Arial','B',$letra_1);
					$pdf->Cell(23,$alto_celda,"PRIMERO",$borde,0,'L');	
					$pdf->SetFont('Arial','',$letra_1);
					$pdf->MultiCell(172,$alto_celda,$parrafo_J2,$borde,1,'L');
					
					$pdf->Ln();
					$pdf->SetFont('Arial','B',$letra_1);
					$pdf->Cell(23,$alto_celda,"SEGUNDO",$borde,0,'L');	
					$pdf->SetFont('Arial','',$letra_1);
					$pdf->MultiCell(172,$alto_celda,$parrafo_3,$borde,1,'L');
					
					$pdf->Ln();
					$pdf->SetFont('Arial','B',$letra_1);
					$pdf->Cell(23,$alto_celda,"TERCERO",$borde,0,'L');	
					$pdf->SetFont('Arial','',$letra_1);
					$pdf->MultiCell(172,$alto_celda,$parrafo_4,$borde,1,'L');
					
					$pdf->Ln();
					$pdf->SetFont('Arial','B',$letra_1);
					$pdf->Cell(23,$alto_celda,"CUARTO",$borde,0,'L');	
					$pdf->SetFont('Arial','',$letra_1);
					$pdf->MultiCell(172,$alto_celda,$parrafo_5,$borde,1,'L');
					
					$pdf->Ln();
					$pdf->SetFont('Arial','',$letra_1);
					$pdf->MultiCell(172,$alto_celda,$parrafo_6,$borde,1,'L');
					
					$pdf->Ln(10);
					$pdf->Cell(98,4,"_________________________",$borde,0,'C');	
					$pdf->Cell(98,4,"_________________________",$borde,1,'C');	
					
					$pdf->SetFont('Arial','',$letra_pie);
					$pdf->Cell(98,3,utf8_decode($nombre_funcionario." ".$apellido_funcionario),$borde,0,'C');	
					$pdf->Cell(98,3,"Juan Carlos Figueroa U",$borde,1,'C');
						
					$pdf->Cell(98,3,"Rut.:".$rut_funcionario,$borde,0,'C');	
					$pdf->Cell(98,3,"Representante Legal",$borde,1,'C');	
			}else{ if(DEBUG){ echo"No mostrar contrato Jefe de Carrera<br>";}}
			///-------------------------------------------------------------------------//
			
					
			if($tipo=="mail")
			{
				//////////////////////////////////////////////////
						//para envio de email
						$nombre_archivo_adjunto="contrato_honorario_".$sede."_(".$semestre."_".$year.").pdf";
						$adjunto=$pdf->Output($nombre_archivo_adjunto, 'S');
						$body='<img src="http://cftmassachusetts.cl/~cftmassa/BAses/Images/logo.png" alt="logo_largo" /><br><br>';
						$body.="<strong>Contrato Honorario Docente</strong><br>";
						$body.="<strong>Docente</strong> $nombre_funcionario $apellido_funcionario<br>";
						$body.="<strong>Fecha:</strong> ".date("d-m-Y")."<br><br>";
						
						$body.='Junto con Saludarle, le adjuntamos su contrato de Honorarios del CFT Massachusetts sede '.$sede.' para el periodo '.$semestre.' semestre del '.$year.'.<br><br>';
						$body.='Por favor revisar sus datos y notificar cualquier diferencia al correo dat@cftmass.cl<br><br>';
						
						$body.="Cualquier consulta dirigirla a nuestro correo electrónico dat@cftmass.cl o bien al Fono 071-2-225713.<br><br>";
						$body.='<img src="http://200.28.135.221/~cftmassa/BAses/Images/login_logo.png"  alt="logo" /><br>';
						$body.='Cordialmente<br>CFT Massachusetts<br>';
						$body.='<font color="red"><a href="http://www.cftmass.cl">cftmass.cl</a></font><br><br>';
						$body.='<hr size="1" width="100%" color="#CCCCCC">';
						$body.='<tt>Este Correo es generado de forma automatica por favor no lo responda<br>© CFT MASSACHUSETTS '.date("Y").'</tt>';
						
				if($enviar_mail)
				{
					if(DEBUG){ echo"Inicio de Intento de Envio de Email<br>";}
						$mail = new PHPMailer();
						// Datos del servidor SMTP
						if(DEBUG){$mail->SMTPDebug  = 2;}
						$mail->Host = "smtp.gmail.com";  // Servidor de Salida, se recomienda poner el nombre del servidor de correo junto con el puerto de salida que es 25
						$mail->IsSMTP(); 
						$mail->Port = 587; 
						$mail->SMTPSecure = "tls";
						$mail->SMTPAuth = true; 
						$mail->Username =$user_correo;  // Nombre de usuario del correo
						$mail->Password = $pass_correo; // Contraseña
						
						
						$mail->From = $user_correo;
						$mail->WordWrap = 50; 
						$mail->IsHTML(true); 
						$mail->AltBody ="Por favor active la vista HTML para visualizar correctamente el mensaje"; // optional, comment out and test
						$mail->FromName = $nombre_envio;
						$mail->Subject = $asunto;	
						$mail->Body = $body;
						$mail->AltBody ="Contrato Honorario<br>";
						$mail->IsHTML(true);
						//$email_funcionario="informatica@cftmass.cl";
						$email_correcto=comprobar_email($email_funcionario);
						if($email_correcto)
						{
							$mail->AddAddress($email_funcionario);
							if($enviar_copia_oculta){$mail->AddBCC($email_BCC, $email_BCC);}
							$mail->AddStringAttachment($adjunto, $nombre_archivo_adjunto,'base64','application/pdf');
							$contador_envio_general++;
						
							if(DEBUG){echo"Enviando Mail a -> $email_funcionario<br>"; $condicion_email="debug";}
							else
							{
								if($mail->Send())
								{ 
									if(DEBUG){ echo"<br><strong>Email Enviado :)</strong><br>";}
									$condicion_email="Enviado";
									$evento="Envio de Contrato de Honorario Docente  $sede [$semestre - $year] id_funcionario: $aux_id_funcionario [$nombre_funcionario $apellido_funcionario]";
									$contador_envios++;
									$descripcion="Envio de Contrato de Honorario del periodo [$semestre - $year] sede: $sede";
								}
								else
								{ 
									if(DEBUG){ echo"<br><strong>Error al Enviar Email :(</strong> ".$mail->ErrorInfo."<br>";}
									$condicion_email="No Enviado";
									$evento="Error al Enviar Contrato de  Honorario Docente  $sede [$semestre - $year] id_funcionario: $aux_id_funcionario [$nombre_funcionario $apellido_funcionario]";
									$descripcion="Falla en intento de Envio de Contrato de Honorario del periodo [$semestre - $year] sede: $sede";
								}
								REGISTRA_EVENTO($evento);
								REGISTRO_EVENTO_FUNCIONARIO($aux_id_funcionario, "notificacion", $descripcion);
								
							}
							
						}
						else
						{ if(DEBUG){ echo"E-mail[$email_funcionario] incorrecto no envia<br>";} $condicion_email="email incorrecto";}
						$mail->ClearAddresses(); 
				//--------------------------------------------------------------------------------------------------------//
				}
				else
				{
					if(DEBUG){ echo"No enviar mail<br>";}
					$condicion_email="email no enviado (sin asignaciones)";
				}
			
				echo'<tr>
					<td>'.$aux_X.'</td>
					<td>'.$nombre_funcionario.'</td>
					<td>'.$apellido_funcionario.'</td>
					<td>'.$email_funcionario.'</td>
					<td>'.$condicion_email.'</td>
				</tr>';
			}//fin si tipo email	
					
		}
	
		@mysql_close($conexion);
		$conexion_mysqli->close();
		
		if($tipo!="mail")
		{$pdf->Output();}
		else{echo'</body><tfoot><tr><td colspan="5">'.$contador_envios.' de '.$contador_envio_general.' Correos Enviados Exitosamente</td></tr></tfoot></table>
					</html>';}
				
	}
	else
	{
		echo"Sin Registros";
	}
}
else
{
	echo"No continuar<br>";
}	

?>	