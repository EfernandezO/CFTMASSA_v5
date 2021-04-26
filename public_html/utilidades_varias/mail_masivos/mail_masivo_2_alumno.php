<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Envio_Email_Masivo_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

//-----------------------------------------//	
$nota_aprobacion=5;
$num_alumnos_morosos=0;
$num_alumno_al_dia=0;
$num_email_enviados=0;
//-------------------------------------------///
if(DEBUG){ var_dump($_POST);}

$sede=$_POST["sede"];
$id_carrera=$_POST["id_carrera"];

$yearIngresoCarrera=$_POST["ingreso"];
$jornada=$_POST["jornada"];
$situacion=$_POST["estado"];
$grupo=$_POST["grupo"];
$nivel=$_POST["nivel"];
$tipo_programa="";

$estado_financiero=$_POST["estado_financiero"];
$vigencia_academica="todas";

$mes_actual=date("m");
if($mes_actual>8)
{ $semestre_actual=2;}
else
{ $semestre_actual=1;}

$semestre_actual=1;

$year_actual=date("Y");

$verificar_contrato=true;
$no_mostrar_retirados=false;
/////////////////////////////
$asunto_mensaje=$_POST["asunto_mensaje"];
$cuerpo_mensaje=$_POST["cuerpo_mensaje"];
$archivo_adjunto=$_POST["archivo_adjunto"];
$ruta_archivo="../../CONTENEDOR_GLOBAL/archivos_temporales/";

$archivo_adjunto_full_src=$ruta_archivo.$archivo_adjunto;
$condicion_tipo_programa="";
$cuenta_alumnos=0;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Email Masivo 3</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:68px;
	z-index:1;
	left: 5%;
	top: 160px;
}
</style>
<script language="javascript">
function ENVIAR_MAIL()
{
	c=confirm('Seguro(a) Desea Enviar los Correos..?');
	if(c){document.getElementById('frm').submit();}
}
function deseleccionar_todo(){
   for (i=0;i<document.frm.elements.length;i++)
      if(document.frm.elements[i].type == "checkbox")
         document.frm.elements[i].checked=0
} 
function seleccionar_todo(){
   for (i=0;i<document.frm.elements.length;i++)
      if(document.frm.elements[i].type == "checkbox")
         document.frm.elements[i].checked=1
} 
</script>
</head>

<body>
<h1 id="banner">Administrador - Envio Masivo Email 3/4</h1>
<div id="link"><br />
<a href="mail_masivo_0.php" class="button">Volver a Seleccion</a><br />
<br />
<a href="#" class="button" onclick="ENVIAR_MAIL();">Enviar Correos </a><br />
<br />
  <a href="#" class="button_R" onclick="seleccionar_todo();">Marcar Todo</a>  <a href="#" class="button_R" onclick="deseleccionar_todo();">Desmarcar Todo</a></div>
<div id="apDiv1">
<form action="mail_masivo_3_alumno.php" method="post" id="frm" name="frm">
  <table width="100%" align="center">
<thead>
    <tr>
   	 <th colspan="11">Listado
   	   <input name="sede" type="hidden" id="sede" value="<?php echo $sede;?>" />
   	   <input type="hidden" name="id_carrera" id="id_carrera" value="<?php echo $id_carrera;?>"/>

   	  
       
       <input name="asunto" type="hidden" value="<?php echo $asunto_mensaje;?>" />
       <input name="cuerpo" type="hidden" value="<?php echo $cuerpo_mensaje;?>" />
       <input name="archivo_adjunto" type="hidden" value="<?php echo $archivo_adjunto;?>" />
       </th>
    </tr>
</thead>
<tbody>
<tr>
<td>N</td>
<td>Rut</td>
<td>Nombre</td>
<td>Apellidos</td>
<td>Promocion</td>
<td>Carrera</td>
<td>Email</td>
<td>Estado Academico</td>
<td>Estado Financiero</td>
<td>Seleccionar</td>
</tr>
<?php
$fecha_actual=date("Y-m-d");
$mesActual=date("m");
$yearActual=date("Y");

$semestreActual=1;
if($mesActual>=8){$semestreActual=2;}

$semestreActual=1;
///////////////////////////
//alumno.grupo='A' AND alumno.situacion='V' AND alumno.nivel=1 AND alumno.sede='Talca'
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");
require("../../../funciones/funciones_varias.php");
require("../../../funciones/class_LISTADOR_ALUMNOS.php");
	/////////////////configuracion inicial/////////////////////
	
								
	$LISTA = new LISTADOR_ALUMNOS();
	
	$LISTA->setDebug(DEBUG);
	
	$LISTA->setGrupo($grupo);
	$LISTA->setId_carrera($id_carrera);
	$LISTA->setJornada($jornada);
	$LISTA->setNiveles($nivel);
	$LISTA->setSede($sede);
	$LISTA->setYearIngressoCarrera($yearIngresoCarrera);
	$LISTA->setSituacionAcademica($situacion);
	
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
			$emailInstitucionalAlumno=$auxAlumno->getEmailInstitucional();
			
			$id_carrera_alumno=$auxAlumno->getIdCarreraPeriodo();
			$nivel_alumno=$auxAlumno->getNivelAlumnoPeriodo();
			$jornada_alumno=$auxAlumno->getJornadaPeriodo();
			$situacion_alumno=$auxAlumno->getSituacionAlumnoPeriodo();
			$yearIngresoCarrera_alumno=$auxAlumno->getYearIngresoCarreraPeriodo();
								
				///////////////////////////////////////////////////////////////////////
			$A_deuda_actual=DEUDA_ACTUAL($id_alumno, $fecha_actual);
			if($A_deuda_actual>0)
			{ $condicion_financiera_alumno="moroso"; $num_alumnos_morosos++;}
			else
			{ $condicion_financiera_alumno="al_dia"; $num_alumno_al_dia++;}
			////////////////////////////////////////////////////////////////////////
			
			if($estado_financiero!="todos")
			{
				switch($estado_financiero)
				{
					case"morosos":
						if($condicion_financiera_alumno=="moroso")
						{ $cumple_condicion_financiera=true;}
						else
						{ $cumple_condicion_financiera=false;}
						break;
					case"al_dia":
						if($condicion_financiera_alumno=="al_dia")
						{ $cumple_condicion_financiera=true;}
						else
						{ $cumple_condicion_financiera=false;}
						break;
					default:
						$cumple_condicion_financiera=false;	
				}
			}
			else
			{$cumple_condicion_financiera=true;}
//---------------------------------------------------------------------------------//								
			///////////////////////////////////////////
			$dias_morosidad_alumno=DIAS_MOROSIDAD($id_alumno);
			$tipo_morosidad=TIPO_MOROSIDAD($dias_morosidad_alumno);
			//-----------------------------------------------------------//
			
			
				$mostrar=true;
				if($mostrar)
				{
					
					////////////////////////////////////////
					if(DEBUG){ echo"<strong>Mostrar Alumno</strong><br>";}
					$cuenta_alumnos++;
					echo'<tr>
							<td>'.$cuenta_alumnos.'</td>
							<td>'.$rut_alumno.'</td>
							<td>'.$nombre.'</td>
							<td>'.$apellidos.'</td>
							<td>'.$nivel_alumno.'-'.$yearIngresoCarrera_alumno.'</td>
							<td>'.$id_carrera_alumno.'</td>
							<td>'.$emailAlumno.'</td>
							<td>'.$situacion_alumno.'</td>
							<td>'.$condicion_financiera_alumno.'</td>
							
							<td><input id="id_alumno" name="array_id_alumno[]" type="checkbox" value="'.$id_alumno.'_'.$id_carrera_alumno.'"  /></td>
							</tr>';
					
				}
				else
				{
					if(DEBUG){ echo"<strong>NO Mostrar Alumno</strong><br>";}
				}
								
							
						
			
			}
		}
		else
		{	
			echo"Sin Alumnos<br>";
		}
		//fin documento
		
	$conexion_mysqli->close();

//////////////////////////////////////////////
?>
<tr>
<td colspan="11">Numero de Alumnos Encontrados (<?php echo $cuenta_alumnos;?>)</td>
</tr>
</tbody>
</table>
</form>
<br />
</div>
</body>
</html>