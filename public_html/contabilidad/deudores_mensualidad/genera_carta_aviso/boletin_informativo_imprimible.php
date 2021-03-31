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
if($_POST)
{
	if(DEBUG){ var_dump($_POST);}
require('../../../libreria_publica/fpdf/fpdf.php');

class PDF extends FPDF
{
var $B;
var $I;
var $U;
var $HREF;

function PDF($orientation='P',$unit='mm',$format='Letter')
{
    //Llama al constructor de la clase padre
    $this->FPDF($orientation,$unit,$format);
    //Iniciación de variables
    $this->B=0;
    $this->I=0;
    $this->U=0;
    $this->HREF='';
}

function WriteHTML($html)
{
    //Intérprete de HTML
    $html=str_replace("\n",' ',$html);
    $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
    foreach($a as $i=>$e)
    {
        if($i%2==0)
        {
            //Text
            if($this->HREF)
                $this->PutLink($this->HREF,$e);
            else
                $this->Write(5,$e);
        }
        else
        {
            //Etiqueta
            if($e[0]=='/')
                $this->CloseTag(strtoupper(substr($e,1)));
            else
            {
                //Extraer atributos
                $a2=explode(' ',$e);
                $tag=strtoupper(array_shift($a2));
                $attr=array();
                foreach($a2 as $v)
                {
                    if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                        $attr[strtoupper($a3[1])]=$a3[2];
                }
                $this->OpenTag($tag,$attr);
            }
        }
    }
}

function OpenTag($tag,$attr)
{
    //Etiqueta de apertura
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,true);
    if($tag=='A')
        $this->HREF=$attr['HREF'];
    if($tag=='BR')
        $this->Ln(5);
}

function CloseTag($tag)
{
    //Etiqueta de cierre
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,false);
    if($tag=='A')
        $this->HREF='';
}

function SetStyle($tag,$enable)
{
    //Modificar estilo y escoger la fuente correspondiente
    $this->$tag+=($enable ? 1 : -1);
    $style='';
    foreach(array('B','I','U') as $s)
    {
        if($this->$s>0)
            $style.=$s;
    }
    $this->SetFont('',$style);
}

function PutLink($URL,$txt)
{
    //Escribir un hiper-enlace
    $this->SetTextColor(0,0,255);
    $this->SetStyle('U',true);
    $this->Write(5,$txt,$URL);
    $this->SetStyle('U',false);
    $this->SetTextColor(0);
}
}

if($_POST)
{
	
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funciones_sistema.php");
	require("../../../../funciones/class_LISTADOR_ALUMNOS.php");
	require("../../../../funciones/VX.php");

	
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

	
	$dias_plazo=$_POST["dias_plazo"];
	
	$fecha_limite=date("Y-m-d", strtotime("$fecha_corte +$dias_plazo days"));///fecha limite =fecha corte +15 dias
	$year_cuotas=$_POST["year_cuotas"];
	/////////////
	$fecha_actual=date("Y-m-d");
	
	
	
	if($year_cuotas!="0")
	{ $condicion_year_cuota="AND letras.ano='$year_cuotas'";}
	else{ $condicion_year_cuota="";}
	
	

		$mes_actual=abs(date("m"));
		$mes_actual_label=$mes_actual;
		$fecha=date("d")." de ".$mes_actual_label." del ".date("Y");
		$DIRECCION["Talca"]="3 Sur #1068";
		$DIRECCION["Linares"]="O'Higgins #313";
		$pdf=new PDF();
		$autor="ACX";
		$titulo='Boletin Informativo';
		$zoom=50;
		$pdf->SetAuthor($autor);
		$pdf->SetTitle($titulo);
		$pdf->SetDisplayMode($zoom);
		
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
		
		if($totalAlumnos>0)
		{
			$cuenta_alumno=0;
			
			foreach($LISTA->getListaAlumnos() as $n => $auxAlumno)
			{
				
				$id_alumno=$auxAlumno->getIdAlumno();
				$rut_alumno=$auxAlumno->getRut();
				$nombre=$auxAlumno->getNombre();
				$apellidos=$auxAlumno->getApellido_P()." ".$auxAlumno->getApellido_M();
				$emailAlumno=$auxAlumno->getEmail();
				$direccion=$auxAlumno->getDireccion();
				$ciudad=$auxAlumno->getCiudad();
				
				
				$id_carrera_alumno=$auxAlumno->getIdCarreraPeriodo();
				$nivel_alumno=$auxAlumno->getNivelAlumnoPeriodo();
				$jornada_alumno=$auxAlumno->getJornadaPeriodo();
				$situacion_alumno=$auxAlumno->getSituacionAlumnoPeriodo();
				
				//////------------------------/INICIO PDF/------------------//////////
				$html_inicial='Sr(ita) <b>'.$nombre.' '.$apellidos.'</b><br>Carrera: '.NOMBRE_CARRERA($id_carrera_alumno).'<br>'.$direccion.'<br>'.$ciudad.'<br>';
				$html_texto_1='Estimado(a) '.$nombre.' '.$apellidos;
				$html_texto_1b='Junto con saludar, nos ponemos en contacto con Ud. para recordarle las fechas de vencimiento de las mensualidades establecidas en su contrato de '.utf8_decode("prestación").' de servicios con CFT Massachusetts. Las cuales se detallan a continuacion.';
				
				
				
				
				$html_texto_2='';
				
				$html_texto_3=utf8_decode('Recuerde que el atraso en las cuotas genera intereses y gastos de cobranza. Evite inconvenientes cancelando a tiempo su mensualidad.');
				
				$html_texto_4=utf8_decode('Para conocer más detalles debe acercarse a nuestro Departamento de Finanzas o comunicarse al (071) 2225921 en Talca o al (073) 2213880 En Linares.');
				
				
				$html_texto_nota=utf8_decode('*si al recibo de la presente ud a regularizado esta situación, rogamos hacer caso omiso de la misma');
				
				//Primera página
				
					$cons_cuotas1="SELECT COUNT(id) FROM letras WHERE idalumn='$id_alumno' AND pagada <>'S' $condicion_year_cuota ORDER by id";
					$sqli_cuo=$conexion_mysqli->query($cons_cuotas1)or die($conexion_mysqli->error);
					$DC=$sqli_cuo->fetch_row();
						$num_cuotas=$DC[0];
						if(empty($num_cuotas)){$num_cuotas=0;}
					$sqli_cuo->free();	
						
						
						if($num_cuotas>0)
						{
							$pdf->AddPage();
							$pdf->SetFont('Arial','',12);
							$pdf->Cell(160,6,"",0,0,"C");
							$logo='../../../BAses/Images/logo_cft.jpg';
							$pdf->image($logo,14,14,30,24,'jpg'); //este es el logo
							$pdf->SetY(30);
							$pdf->SetLeftMargin(30);
							$pdf->SetFontSize(20);
							$pdf->Cell(165,10,$titulo,0,1,"C");
							$pdf->SetFontSize(12);
							$pdf->Ln();
							$pdf->WriteHTML(utf8_decode($html_inicial));
							$Y_actual=$pdf->GetY();
							$pdf->SetY($Y_actual+15);
							$pdf->WriteHTML(utf8_decode($html_texto_1));
							$Y_actual=$pdf->GetY();
							$pdf->SetY($Y_actual+10);
							$pdf->MultiCell(160,6,"".$html_texto_1b,0,1);
							$pdf->Ln(5);
							//-----------------------------------------------------------------//
							$cons_cuotas="SELECT * FROM letras WHERE idalumn='$id_alumno' AND pagada <>'S' $condicion_year_cuota ORDER by id";
						
						
							if(DEBUG){ echo"cuotas-> $cons_cuotas<br>";}
							
							$sql_cuotas=$conexion_mysqli->query($cons_cuotas)or die($conexion_mysqli->error);
							$num_cuotas=$sql_cuotas->num_rows;
							
							if(empty($num_cuotas)){ $num_cuotas=0;}
							if($num_cuotas>0){$mostrar_alumno=true;}
							else{ $mostrar_alumno=false;}
							
							$aux=0;
	
							$pdf->Cell(10,6,"N.",1,0,"C");
							$pdf->Cell(40,6,"Vencimiento",1,0,"C");
							$pdf->Cell(40,6,"Deuda Actual",1,0,"C");
							$pdf->Cell(20,6,utf8_decode("Año"),1,0,"C");
							$pdf->Cell(50,6,"Condicion",1,1,"C");
							
							while($C=$sql_cuotas->fetch_assoc())
							{
								$aux++;
								$C_vencimiento=$C["fechavenc"];
								$C_deudaXletra=$C["deudaXletra"];
								$C_ano=$C["ano"];
								
								$time_fecha_actual=strtotime($fecha_actual);
								$time_vencimiento=strtotime($C_vencimiento);
								
								if($time_fecha_actual>$time_vencimiento)
								{ $C_condicion="Morosa";}
								else{ $C_condicion="Pendiente";}
								
								$pdf->Cell(10,6,$aux,1,0,"C");
								$pdf->Cell(40,6,$C_vencimiento,1,0,"C");
								$pdf->Cell(40,6,"$".number_format($C_deudaXletra,0,",","."),1,0,"C");
								$pdf->Cell(20,6,$C_ano,1,0,"C");
								$pdf->Cell(50,6,$C_condicion,1,1,"C");
								
							}
							
							$sql_cuotas->free();
							
							
							//************************************************************//
							
							
							$pdf->MultiCell(160,6,$html_texto_2,0,1);
							
							$pdf->Ln();
							$pdf->MultiCell(160,6,$html_texto_3,0,1);
							
							$pdf->Ln();
							$pdf->MultiCell(160,6,$html_texto_4,0,1);
							
							$pdf->Ln();
							$pdf->MultiCell(160,6,"Sin otro particular, le envia un cordial saludo.",0,1,"C");
							
							
							$Y_actual=$pdf->GetY();
							$pdf->SetY($Y_actual+15);
							$pdf->Cell(160,6,"Depto de Admin y Finanzas",0,1,"R");
							$pdf->Cell(160,6,$sede.", ".$fecha,0,1,"L");
							
							$pdf->SetFont('Arial','',8);
							$pdf->Ln();
							$pdf->MultiCell(160,6,$html_texto_nota,0,1,"C");
							
						}
				
				//////------------------------/FIN PDF/------------------//////////
			}//fin while

		$pdf->Output();
	}
	else
	{
		echo $num_reg.' Alumnos En Condicion De Moroso...<br>';
	}
}
}
?>