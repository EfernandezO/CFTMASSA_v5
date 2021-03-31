<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="inspeccion";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
//-----------------------------------------//	
//var_dump($_POST);
//////////////////////////
define("DEBUG",false);
set_time_limit(300);
$sede=$_POST["fsede"];
$array_carrera=$_POST["carrera"];
$array_carrera=explode("_",$array_carrera);
$id_carrera=$array_carrera[0];
$carrera=$array_carrera[1];
$año_ingreso=$_POST["ano_ingreso"];
$jornada=$_POST["jornada"];



$semestre_consulta=$_POST["semestre_vigencia_contrato"];
$year_consulta=$_POST["year_vigencia_contrato"];

$verificar_contrato=true;
$no_mostrar_retirados=false;

if(DEBUG){ var_export($_POST);}


$condicion=" AND";
if($sede!="todas"){ $condicion.=" contratos2.sede='$sede' AND contratos2.condicion<>'inactivo'";}
else{ $condicion.=" contratos2.condicion<>'inactivo'";}


if($id_carrera>0)
{ $condicion.=" AND contratos2.id_carrera='$id_carrera'";}

if($año_ingreso!="Todos")
{
	$condicion.=" AND contratos2.yearIngresoCarrera='$año_ingreso'";
}
if($jornada!="T")
{
	$condicion.=" AND contratos2.jornada='$jornada'";
}



$msj="Alumnos Sede: $sede Carrera: $carrera Jornada: $jornada<br> Contratos Periodo ($semestre_consulta - $year_consulta) <br>";

$fecha_actual=date("Y-m-d");
///////////////////////////
//alumno.grupo='A' AND alumno.situacion='V' AND alumno.nivel=1 AND alumno.sede='Talca'
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");
///////////////////////////////////
/////Registro ingreso///
	require("../../../funciones/VX.php");
	 $evento="Ve Informe(porcentaje Morosidad)->".$carrera." Sede: ".$sede." Jornada: ".$jornada;
	 REGISTRA_EVENTO($evento);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<title>informe porcentajes de Morosidad</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 483px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:53px;
	z-index:2;
	left: 5%;
	top: 120px;
}
#apDiv3 {
	position:absolute;
	width:40%;
	height:47px;
	z-index:3;
	left: 30%;
	text-align: center;
}
</style>
</head>
<body>
<h1 id="banner">Administrador - Informe Porcentaje Morosidad</h1>
<div id="link"><br><a href="../index.php" class="button">Volver al menu Principal </a>
</div>
<div id="apDiv1">
<table align="left">
<thead>
	<tr>
    	<th colspan="13">Listado</th>
    </tr>
    <tr>
        <td>N°</td>
        <td>Sede</td>
        <td>Carrera</td>
        <td>Jornada</td>
        <td>Rut</td>
        <td>Nombre</td>
        <td>Apellido</td>
        <td>Nivel Actual</td>
        <td>Estado</td>
        <td>F</td>
        <td>deuda actual</td>
        <td>Tipo Morosidad</td>
        <td>Ingreso</td>
    </tr>
   </thead>
   <tbody> 
<?php
require("../../../funciones/class_ALUMNO.php");
	$aux=0;	 
	$TOTAL_ALUMNOS=0;
	$TOTAL_MOROSOS=0;
	$TOTAL_AL_DIA=0;
	$TOTAL_MOROSOS_TIPO_1=0;
	$TOTAL_MOROSOS_TIPO_2=0;
	$TOTAL_MOROSOS_TIPO_3=0;
	$TOTAL_MOROSOS_TIPO_4=0;
	$TOTAL_MOROSOS_TIPO_5=0;
	
	$cons_main_1="SELECT DISTINCT(id_alumno) FROM contratos2 INNER JOIN alumno ON contratos2.id_alumno = alumno.id WHERE contratos2.ano='$year_consulta' $condicion ORDER by alumno.apellido_P, alumno.apellido_M";
	
	
		$sql_main_1=$conexion_mysqli->query($cons_main_1)or die(" MAIN 1".mysql_error());
		$num_reg_M=$sql_main_1->num_rows;
		if(DEBUG){ echo"<br><br>$cons_main_1<br>NUM.$num_reg_M<br>";}
		if($num_reg_M>0)
		{
			
			$num_alumnos_morosos=0;
			$num_alumno_al_dia=0;
			while($DID=$sql_main_1->fetch_row())
			{
				$id_alumno=$DID[0];
				if(DEBUG){ echo"<strong>-> id_alumno: $id_alumno</strong><br>";}
				$ALUMNO=new ALUMNO($id_alumno);
				//$ALUMNO->SetDebug(DEBUG);	
				$ALUMNO->IR_A_PERIODO($semestre_consulta,$year_consulta);
				$situacionAlumnoPeriodo=$ALUMNO->getSituacionAlumnoPeriodo();
				$yearIngresoCarreraPeriodo=$ALUMNO->getYearIngresoCarreraPeriodo();
				$idCarreraPeriodo=$ALUMNO->getIdCarreraPeriodo();
				$aux_idContrato=$ALUMNO->getIdContratoPeriodo();
				if(DEBUG){ echo"->Periodo [$semestre_consulta $year_consulta]: Situacion: $situacionAlumnoPeriodo yearIngreso: $yearIngresoCarreraPeriodo idCarrera:$idCarreraPeriodo idContrato: $aux_idContrato<br>";}	
							
					
							
								
				///////////////////////////////////////////////////////////////////////
				$A_deuda_actual=DEUDA_ACTUAL($id_alumno,"",$aux_idContrato);
				if($A_deuda_actual>0)
				{ $condicion_financiera_alumno="moroso"; $num_alumnos_morosos++;}
				else
				{ $condicion_financiera_alumno="al_dia"; $num_alumno_al_dia++;}
				////////////////////////////////////////////////////////////////////////
				$dias_morosidad_alumno=DIAS_MOROSIDAD($id_alumno,0, $aux_idContrato);
				if($dias_morosidad_alumno>0)
					{
						if($dias_morosidad_alumno<=30)
						{ $tipo_morosidad=1;}
						elseif($dias_morosidad_alumno<=60)
						{ $tipo_morosidad=2;}
						elseif($dias_morosidad_alumno<=90)
						{ $tipo_morosidad=3;}
						elseif($dias_morosidad_alumno<=120)
						{ $tipo_morosidad=4;}
						else
						{ $tipo_morosidad=5;}
					}
					else
					{ $tipo_morosidad=0;}
					
					if(DEBUG){ echo"Tipo Morosidad: $tipo_morosidad<br>";}
					
				//////////////////////////////////////////////////////////////////////////////
								
								
								
								
					$aux++;
					$TOTAL_ALUMNOS+=1;
					
					switch($tipo_morosidad)
					{
						case"1":
							$TOTAL_MOROSOS_TIPO_1+=1;
							break;
						case"2":
							$TOTAL_MOROSOS_TIPO_2+=1;
							break;
						case"3":
							$TOTAL_MOROSOS_TIPO_3+=1;
							break;		
						case"4":
							$TOTAL_MOROSOS_TIPO_4+=1;
							break;	
						case"5":
							$TOTAL_MOROSOS_TIPO_5+=1;
							break;	
						default:
								
					}
					switch($condicion_financiera_alumno)
					{
						case"moroso":
							$TOTAL_MOROSOS++;
							break;
						case"al_dia":
							$TOTAL_AL_DIA++;
							break;	
					}
					echo'<tr>
							<td>'.$aux.'</td>
							<td>'.$ALUMNO->getSedeAlumnoPeriodo().'</td>
							<td>'.$ALUMNO->getIdCarreraPeriodo().'</td>
							<td>'.$ALUMNO->getJornadaPeriodo().'</td>
							<td>'.$ALUMNO->getRut().'</td>
							<td>'.ucwords(strtolower($ALUMNO->getNombre())).'</td>
							<td>'.ucwords(strtolower($ALUMNO->getApellido_P())).'</td>
							<td>'.$ALUMNO->getNivelAcademicoActual().'</td>
							<td>'.$ALUMNO->getSituacionAlumnoPeriodo().'</td>
							<td>'.$condicion_financiera_alumno.'</>
							<td>'.$A_deuda_actual.'</td>
							<td>'.$tipo_morosidad.'</td>
							<td>'.$yearIngresoCarreraPeriodo.'</td>
							</tr>';
			}
								
								
							
			
			
		}
		else
		{
			echo"Sin registros<br>";	
		}
		//fin documento
	$sql_main_1->free();
	@mysql_close($conexion);
	$conexion_mysqli->close();
/////////////////////////////////////////////

?>
</tbody>
</table>
</div>
<div id="apDiv2">
<table width="100%" border="1" align="left">
	<thead>
  <tr>
    <th colspan="4">Resumen</th>
    </tr>
    </thead>
    <tbody>
  <tr>
    <td colspan="2">Item</td>
    <td>Cantidad</td>
    <td>%</td>
  </tr>
  <tr>
    <td colspan="2">Total Alumnos</td>
    <td><?php echo $TOTAL_ALUMNOS;?></td>
    <td>100</td>
  </tr>
  <tr>
    <td colspan="2">Total Alumno Al dia</td>
    <td><?php echo $TOTAL_AL_DIA;?></td>
    <td><?php echo (($TOTAL_AL_DIA*100)/$TOTAL_ALUMNOS);?></td>
  </tr>
  <tr>
    <td colspan="2">Total Alumnos Morosos</td>
    <td><?php echo $TOTAL_MOROSOS;?></td>
    <td><?php echo (($TOTAL_MOROSOS*100)/$TOTAL_ALUMNOS);?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Morosidad Tipo 1</td>
    <td><?php echo $TOTAL_MOROSOS_TIPO_1;?></td>
    <td><?php echo (($TOTAL_MOROSOS_TIPO_1*100)/$TOTAL_MOROSOS);?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Morosidad Tipo 2</td>
    <td><?php echo $TOTAL_MOROSOS_TIPO_2;?></td>
    <td><?php echo (($TOTAL_MOROSOS_TIPO_2*100)/$TOTAL_MOROSOS);?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Morosidad Tipo 3</td>
     <td><?php echo $TOTAL_MOROSOS_TIPO_3;?></td>
    <td><?php echo (($TOTAL_MOROSOS_TIPO_3*100)/$TOTAL_MOROSOS);?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Morosidad Tipo 4</td>
     <td><?php echo $TOTAL_MOROSOS_TIPO_4;?></td>
    <td><?php echo (($TOTAL_MOROSOS_TIPO_4*100)/$TOTAL_MOROSOS);?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Morosidad Tipo 5</td>
     <td><?php echo $TOTAL_MOROSOS_TIPO_5;?></td>
    <td><?php echo (($TOTAL_MOROSOS_TIPO_5*100)/$TOTAL_MOROSOS);?></td>
  </tr>
    </tbody>
</table>
</div>
<div id="apDiv3"><strong><?php echo $msj;?></strong></div>
</body>
</html>