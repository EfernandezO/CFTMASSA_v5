<?php
require("../../../SC/seguridad.php");
require("../../../SC/privilegio2.php");
require('../../../../librerias/fpdf/fpdf.php');
define(SIZE_LETRA_titulo,14);
define(SIZE_LETRA,10);
define(SIZE_LETRA_pie,8);

////////////////////////////////////////////
class FPDFX extends FPDF
{
//Pie de página
	function Footer()
	{
		//Posición: a 1,5 cm del final
		$this->SetY(-15);
		//Arial italic 8
		$this->SetFont('Arial','I',8);
		//Número de página
		$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
	}
}

///////////////////////////////////////////
include("../../../../funciones/funcion.php");
$alumno=$_SESSION["FINANZAS"]["nombre_alu"]." ".$_SESSION["FINANZAS"]["apellido_alu"];
$alumno=ucwords(strtolower($alumno));
$direccion_alumno=$_SESSION["FINANZAS"]["direccion_alu"];
$año=end(explode("-",$_SESSION["FINANZAS"]["fecha_inicio"]));
$rut_alumno=$_SESSION["FINANZAS"]["rut_alumno"];
$sostenedor=$_SESSION["FINANZAS"]["sostenedor"];
////-----Arreglo de Variables

$ciudad_dir_alumno=$_SESSION["FINANZAS"]["ciudad_alu"];
$ciudad_dir_apo=$_SESSION["FINANZAS"]["ciudad_apo"];
switch($sostenedor)
{
	case"alumno":
		$titular_credito_nombre=$alumno;
		$titular_credito_rut=$rut_alumno;
		$titular_credito_direccion=$direccion_alumno.", ".$ciudad_dir_alumno;
		break;
	case"apoderado":
		$titular_credito_nombre=$_SESSION["FINANZAS"]["nombreC_apo"];
		$titular_credito_rut=$_SESSION["FINANZAS"]["rut_apo"];
		$titular_credito_direccion=$_SESSION["FINANZAS"]["direccion_apo"].", ".$ciudad_dir_apo;
		break;
	case"otro":
		$titular_credito_nombre=$_SESSION["FINANZAS"]["sostenedor_nombre"];
		$titular_credito_rut=$_SESSION["FINANZAS"]["sostenedor_rut"];
		$titular_credito_direccion=$direccion_alumno.", ".$ciudad_dir_alumno;
		break;
}
///////////////saco de lista de pendientes al contrato academico//////
	$_SESSION["FINANZAS"]["impresion"]["contrato_credito"]=true;
//////////////////////
$sede_alumno=$_SESSION["FINANZAS"]["sede_alumno"];
$fecha_actual_palabra=fecha();
$direccion_sede["Talca"]="en 3 Sur N° 1066, Talca";
$direccion_sede["Linares"]="O'Higgins N°313, Linares";
///////////////////////////////////////////
$hoja_oficio[0]=217;
$hoja_oficio[1]=330;
$ancho_max_celda=195;
$logo="../../../BAses/Images/logoX.jpg";
$mostrar_logo=true;
$zoom=75;
$borde=0;
$pdf=new FPDFX('P','mm',$hoja_oficio);
$pdf->AliasNbPages();
//var_export($_SESSION["FINANZAS"]);
$title='CONTRATO DE APERTURA DE CREDITO';

$intro=utf8_decode("En ".$sede_alumno." de Chile, con fecha ".$fecha_actual_palabra." entre CENTRO DE FORMACIÓN TÉCNICA MASSACHUSETTS LIMITADA, en adelante CFT MASSACHUSETTS LTDA., o CFT, persona juridica, domiciliada ".$direccion_sede[$sede_alumno].", por una parte, y don(ña) ").$titular_credito_nombre.utf8_decode(" en adelante el alumno o el titular domiciliado en ".$titular_credito_direccion." con cédula nacional de identidad Nº ".$titular_credito_rut." por la otra. han acordado celebrar el siguiente Contrato de Apertura de Crédito. el cual regira las relaciones entre los alumnos y CFT MASSACHUSSETTS LTDA durante el periodo que se mantenga vigente dicho Contrató, en las condiciones que a continuación se indican.");

$cuerpo_txt="PRIMERO: CFT MASSACHUSETTS LTDA. abre para el alumno una línea de crédito, que permitirá a este último obtener la calidad de alumno regular en cualquiera de las carreras que imparta esta institución y, en cualquier sede y, que tendrá vigencia por el tiempo que el alumno tenga tal calidad.
SEGUNDO: Con la suscripción de este contrato por parte del alumno con CFT MASSACHUSETTS LTDA, este tendrá la calidad de alumno regular del CFT, accediendo de esta forma a las distintas carreras que se impartan una vez cumplidos los requisitos de admisión.
TERCERO: La línea de crédito indicada en las cláusulas anteriores, se mantendrá vigente mientras el alumno tenga la calidad de alumno regular. Sin perjuicio de lo anterior, CFT MASSACHUSETTS LTDA podrá poner término a dicha línea de crédito en cualquier momento y sin expresión de causa, si las normas aquí establecidas no fuesen respetadas por parte del alumno, bastando para ello una simple comunicación escrita dirigida al domicilio de este último.
CUARTO: La calidad de alumno regular del CFT MASSACHUSETTS LTDA. da origen, a voluntad del mismo, a un plazo para el pago con un costo para el usuario que será fijado por CFT MASSACHUSETTS LTDA, en conformidad a la Ley Nº 19496.
QUINTO: Al solicitar la inscripción como alumno regular del CFT MASSACHUSETTS LTDA., éste deberá identificarse con su Cédula Nacional de Identidad con el fin de verificar sus datos, la que será devuelta luego de comprobar la identidad.
SEXTO: Mensualmente CFT MASSACHUSETTS LTDA., liquidara opcionalmente mediante un Estado de Cuenta interno que incluirá el valor de las cuotas adeudadas y por vencer,  intereses, impuestos y/o cargos y/o  gastos que correspondan. 
	Los pagos deberá hacerlos en las cajas habilitadas tanto en la Casa Matriz como en las sede(s) del CFT MASSACHUSETTS LTDA en donde el estudiante este cursando su carrera de acuerdo a los plazos y condiciones fijadas y aranceles que se establezcan anualmente y que se respaldaran con las respectivas  Boletas.  El alumno o estudiante deberá informarse en las oficinas de CFT MASSACHUSETTS LTDA acerca de su estado de cuenta, saldo, vencimiento de cuotas y proceder a su pago.
SEPTIMO: El alumno no podrá excederse de las limitaciones pactadas o fijadas por el CFT MASSACHUSETTS LTDA. en cuanto a los plazos generales de pago, en base a los planes vigentes en ese momento y que se consignará en las respectivas Boletas. En caso de mora o simple retardo en el pago de todo o parte del capital y/o intereses, el CFT tendrá la facultad de poner término al contrato y además, hacer exigible el total de lo adeudado a la fecha, el que en este evento se considerará de plazo vencido para todos los efectos legales, además de los intereses penales que fueren procedentes.
	Sin perjuicio de lo anterior, CFT MASSACHUSETTS LTDA podrá aceptar abonos parciales a la obligación en las épocas y por los montos que decida aceptar, quedando facultado además de renunciar al cobro de intereses y/o reajustes o bien para solicitar intereses corrientes y/o penales.
OCTAVO: Respecto de intereses moratorios, el alumno acepta las tasas y montos que para estos efectos establezca CFT MASSACHUSETTS LTDA, los cuales serán los que legalmente procedan en su oportunidad.
NOVENO: Cualquier entrega de antecedente erróneo y/o falso, y/o inductivo a conceder un plazo para el pago o bien el no pago de las cuotas pactadas y/o intereses moratorios y/o cargos y/o gastos, dará derecho a CFT MASSACHUSETTS LTDA. a iniciar las acciones legales que estime pertinentes, estando facultado para negar la ampliación y/o concesión de un plazo para el pago.
DECIMO: El alumno adquiere la obligación de registrar su domicilio y dirección y notificar por escrito a las oficinas del CFT MASSACHUSETTS LTDA, todo cambio al respecto. El titular será responsable de los eventuales perjuicios causados a terceros debido al incumplimiento de esta obligación
DECIMO PRIMERO: Todas las obligaciones derivadas de este contrato se considerarán indivisibles para el alumno, sus herederos y/o sucesores, para todos los efectos legales y en especial aquellos contemplados en los artículos 1526 Nº 4 y 1528 del Código Civil.
DECIMO SEGUNDO:CFT MASSACHUSETTS LTDA.  se reserva el derecho de efectuar cobros, ya sea estos en forma mensual o anual, por el costo efectivo del plazo otorgado al alumno.
DECIMO TERCERO: El alumno faculta expresamente a CFT MASSACHUSETTS LTDA para que en caso de incumplimiento de sus obligaciones, pueda informar a terceros, ejercer acciones de cobro, tanto en su domicilio comercial como residencial, a través de notificaciones abiertas o cerradas, cartas, por vía telefónica o verbal.
DECIMO CUARTO: Por el presente contrato el alumno otorga a INVERSIONES F Y R LIMITADA mandato  irrevocable de acuerdo al artículo 241 de Código de Comercio, para que en su representación acepte una Letra de Cambio o Pagaré a la orden de CENTRO DE FORMACIÓN TÉCNICA MASSACHUSETTS LIMITADA, en adelante CFT MASSACHUSETTS LTDA. El alumno autoriza que dicha Letra de Cambio o Pagaré sea girada y llenada por CFT MASSACHUSETTS LTDA  de acuerdo a las instrucciones que se indican en la cláusula siguiente. La fecha de giro de la letra de cambio o pagaré será aquella en que esta sea aceptada por el respectivo mandatario. Dicha aceptación deberá efectuarse ante Notario, con el propósito que el instrumento tenga mérito ejecutivo conforme a lo señalado en el Artículo 434 Nº 4 del Código de Procedimiento Civil. El alumno faculta irrevocablemente a INVERSIONES F Y R LIMITADA  para delegar el presente mandato. Este mandato será gratuito, liberando expresamente el alumno a  INVERSIONES F Y R LIMITADA de la obligación de rendir cuenta.
DECIMO QUINTO: Por este acto, el alumno autoriza y confiere mandato a CFT MASSACHUSETTS LTDA., para que en el evento de que el alumno incurriese en alguno de los casos de incumplimiento de pago de sus obligaciones para con CFT MASSACHUSETTS LTDA,  gire y llene según sea el caso, la letra de cambio y pagaré de la forma que pasa a expresarse de acuerdo a lo establecido en el Artículo 11 de la Ley Nº 18092.
- La fecha de vencimiento de la letra o pagaré, será una fecha que no podrá ser inferior a 10 días hábiles después de la fecha en que se produzca la falta, mora o retardo en el pago.
- El monto a llenar será la suma del monto correspondiente a todas las obligaciones que el alumno registre como impagas con CFT MASSACHUSETTS LTDA, más los intereses moratorios, sin incluirse los gastos de cobranza.
- Por el presente contrato el alumno otorga a la sociedad INVERSIONES F Y R LIMITADA mandato para que ésta, actuando a su nombre, sea notificada y acepte las notificaciones judiciales de las demandas que sean presentadas con motivo u ocasión de incumplimiento de sus obligaciones para con CFT MASSACHUSETTS LTDA. 
- Los mandatos que se otorgan en este contrato, específicamente los señalados en la presente cláusula y en la cláusula  décimo cuarta, de conformidad con el Art. 11 de la Ley  18092 y 241 del Código de Comercio son irrevocables, en el sentido de que el cliente no podrá otorgar instrucciones en sentido contrario, ni revocarlos, ni dejarlos sin efecto.
- La aceptación de la letra de cambio y pagaré no constituirá novación de las obligaciones en ellos documentadas, pues solo tiene como objeto documentar en título ejecutivo tales obligaciones y así facilitar su eventual cobro judicial.
- El presente mandato podrá también ser ejecutado por INVERSIONES F Y R LIMITADA, después de la muerte del mandante y en consecuencia no se extinguirá por la muerte de éste, pudiendo por tanto ser  ejercido en todas sus partes por la sociedad mandataria, en conformidad a lo previsto en el Art. 2169 del Código Civil.
DECIMO SEXTO: El alumno acepta que es condición esencial del contrato de apertura de crédito, pagaré o letra, suscrito con CFT MASSACHUSETTS LTDA. que en el caso de mora o simple retardo en el cumplimiento de las obligaciones que asume en virtud del contrato de apertura de crédito, pagaré o letra, que su nombre y antecedentes sean incluidos en los listados que se remiten a los Servicios de Información Comercial o de Riesgo de Crédito para los efectos de poner en conocimiento público de tales incumplimientos. ";
///////////////////////////
$pdf->AddPage();
$pdf->SetTitle($title);
$pdf->SetDisplayMode($zoom);
$pdf->SetMargins(10,10,0);
$pdf->SetAuthor('CFT Massachusetts');
//////////////
    
    $pdf->image($logo,14,5,30,24,'jpg'); //este es el logo
	//titulo
	$pdf->SetFont('Arial','B',SIZE_LETRA_titulo);
	$pdf->Cell($ancho_max_celda,20,$title,$borde,1,"C");
	//intro
	$pdf->SetFont('Arial','',SIZE_LETRA);
	$pdf->MultiCell($ancho_max_celda,6,$intro,$borde);
	
	//cuerpo
	
	$cuerpo_txt= utf8_decode($cuerpo_txt);
    $pdf->MultiCell($ancho_max_celda,6,$cuerpo_txt,$borde);

    $ciudad_alumno=$_SESSION["FINANZAS"]["ciudad_alu"];
	$sede_alumno=$_SESSION["FINANZAS"]["sede_alumno"];
	if($sede_alumno!=$ciudad_alumno)
	{
		$ciudades_contrato="en las ciudades de ".$sede_alumno." y/o ".$ciudad_alumno;
	}
	else
	{
		$ciudades_contrato="en la ciudad de ".$sede_alumno;
	}
	
	$ultimo_parrafo="DECIMO SEPTIMO: Para todos los efectos del presente instrumento las partes fijan domicilio convencional ".$ciudades_contrato." indistintamente. y se someten a la competencia y jurisdiccion de los Tribunales de Justicia, domicilios que tambien serán habiles para las diligencias de protestos en caso de practicarse, siendo facultad de CFT MASSACHUSETTS LTDA. optar por el domicilio en el cual se hará efectiva la acción judicial.";
	$pdf->MultiCell(190,6,utf8_decode($ultimo_parrafo),$borde);


//$pdf->Line(108,0,108,310);
//$pdf->Line(108/2,0,108/2,310);
//$pdf->Line(108*1.5,0,108*1.5,310);
$pdf->SetFont('Arial','',SIZE_LETRA_pie);
$pdf->Ln();
$pdf->Ln();
$aux_Y=$pdf->GetY();
$pdf->SetXY(33,$aux_Y);
$pdf->MultiCell(40,5,'_______________________ Alumno',$borde,'C');
$pdf->SetXY(130,$aux_Y);
$pdf->MultiCell(40,5,'_______________________ CFT Massachusetts LTDA',$borde,'C');
$pdf->Output();
?>