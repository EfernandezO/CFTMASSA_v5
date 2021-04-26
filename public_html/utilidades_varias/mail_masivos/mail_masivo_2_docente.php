<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Envio_Email_Masivo_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	set_time_limit(600);
//-----------------------------------------//	
$nota_aprobacion=5;
$num_alumnos_morosos=0;
$num_alumno_al_dia=0;
$num_email_enviados=0;
//-------------------------------------------///
if(DEBUG){ var_dump($_POST);}

$sede=$_POST["fsede"];

$mes_actual=date("m");
if($mes_actual>=8)
{ $semestre_actual=2;}
else
{ $semestre_actual=1;}

$year_actual=date("Y");



$verificar_contrato=true;
$no_mostrar_retirados=false;
/////////////////////////////
$semestre_A=$_POST["semestre_A"];
$year_A=$_POST["year_A"];

$semestre_actual=$semestre_A;
$year_actual=$year_A;

$asunto_mensaje=$_POST["asunto_mensaje"];
$cuerpo_mensaje=$_POST["cuerpo_mensaje"];
$archivo_adjunto=$_POST["archivo_adjunto"];



$con_asignaciones=$_POST["con_asignaciones"];
$ruta_archivo="../../CONTENEDOR_GLOBAL/archivos_temporales/";

$archivo_adjunto_full_src=$ruta_archivo.$archivo_adjunto;



if($sede=="")
{$sede="Talca";}

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
<form action="mail_masivo_3_docente.php" method="post" id="frm" name="frm">
  <table width="60%" align="center">
<thead>
    <tr>
   	 <th colspan="9">Listado de Docentes<?php echo $sede;?>
   	   <input name="sede" type="hidden" id="sede" value="<?php echo $sede;?>" />
       
       <input name="asunto" type="hidden" value="<?php echo $asunto_mensaje;?>" />
       <input name="cuerpo" type="hidden" value="<?php echo $cuerpo_mensaje;?>" />
       <input name="archivo_adjunto" type="hidden" value="<?php echo $archivo_adjunto;?>" />
       </th>
    </tr>
    <tr>
<td>N</td>
<td>Nombre</td>
<td>Enviar ¿?</td>
</tr>
</thead>
<tbody>

<?php
$fecha_actual=date("Y-m-d");
///////////////////////////
//alumno.grupo='A' AND alumno.situacion='V' AND alumno.nivel=1 AND alumno.sede='Talca'
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");
///////////////////////////////////
include("../../../funciones/funciones_varias.php");
	/////////////////configuracion inicial/////////////////////							
	$aux=0;	 
	$cuenta_funcionarios=0;
	$cons_main_1="SELECT DISTINCT(personal.id), con_acceso FROM personal INNER JOIN toma_ramo_docente ON personal.id = toma_ramo_docente.id_funcionario WHERE toma_ramo_docente.sede='$sede' ORDER by personal.apellido_P, personal.apellido_M";
	
		$sql_main_1=$conexion_mysqli->query($cons_main_1)or die($conexion_mysqli->error);
		$num_reg_M=$sql_main_1->num_rows;
		if(DEBUG){ echo"<br><br>$cons_main_1<br>NUM.$num_reg_M<br>";}
		if($num_reg_M>0)
		{
			while($DID=$sql_main_1->fetch_row())
			{
				$es_jefe_carrera=false;
				$array_id_carrera_jefatura=array();
				$id_funcionario=$DID[0];
				$F_con_acceso=strtoupper($DID[1]);
				
					
					$nombre_personal=NOMBRE_PERSONAL($id_funcionario);
					
					switch($con_asignaciones)
					{
						case"solo_actuales":
							$docente_tiene_asignaciones=TIENE_ASIGNACIONES($id_funcionario, $sede, $semestre_actual, $year_actual);
							if($docente_tiene_asignaciones){ $mostrar_funcionario=true;}
							else{ $mostrar_funcionario=false;}
							break;
						case"con_acceso":
							if($F_con_acceso=="ON"){ $mostrar_funcionario=true;}
							else{ $mostrar_funcionario=false;}
							
							break;
						default:	
							$mostrar_funcionario=true;	
					}
					
					
//------------------------------------------------------------------------------------------------------------------------------//		
					if($mostrar_funcionario)
					{
						list($es_jefe_carrera, $array_id_carrera_jefatura)=ES_JEFE_DE_CARRERA($id_funcionario, $semestre_actual, $year_actual, $sede);
						if($es_jefe_carrera){ $color_fila='#33AACC'; $title='title="Jefe de Carrera"';}
						else{ $color_fila=''; $title='title="Docente"';}
						////////////////////////////////////////
						if(DEBUG){ echo"<strong>Mostrar Alumno</strong><br>";}
						$cuenta_funcionarios++;
						echo'<tr>
								<td bgcolor="'.$color_fila.'">'.$cuenta_funcionarios.'</td>
								<td bgcolor="'.$color_fila.'"><a href="#" '.$title.'>'.$nombre_personal.'</a></td>
								<td bgcolor="'.$color_fila.'" align="center"><input id="id_funcionario" name="array_id_funcionario[]" type="checkbox" value="'.$id_funcionario.'"/></td>
								</tr>';
						
					}
					else
					{
						if(DEBUG){ echo"<strong>NO Mostrar Funcionario</strong><br>";}
					}
								
							
						
			
			}
		}
		else
		{	
			echo"Sin funcionario<br>";
		}
		//fin documento
		
	$sql_main_1->free();
	@mysql_close($conexion);
	$conexion_mysqli->close();
/////////////////////////////////////////////

//////////////////////////////////////////////
?>
<tr>
<td colspan="9">Numero de Docentes Encontrados (<?php echo $cuenta_funcionarios;?>)</td>
</tr>
</tbody>
</table>
</form>
<br />
</div>
</body>
</html>