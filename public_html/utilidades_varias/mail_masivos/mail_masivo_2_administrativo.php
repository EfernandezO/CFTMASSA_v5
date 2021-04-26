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
$num_email_enviados=0;
//-------------------------------------------///
if(DEBUG){ var_dump($_POST);}

$sede=$_POST["sede"];

$mes_actual=date("m");
if($mes_actual>8)
{ $semestre_actual=2;}
else
{ $semestre_actual=1;}

$year_actual=date("Y");

/////////////////////////////
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
	top: 150px;
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
<form action="mail_masivo_3_administrativo.php" method="post" id="frm" name="frm">
  <table width="70%" align="center">
<thead>
    <tr>
   	 <th colspan="9">Listado Administrativos<br />Usuarios con acceso marcados automaticamente <?php echo $sede;?>
   	   <input name="sede" type="hidden" id="sede" value="<?php echo $sede;?>" />
       
       <input name="asunto" type="hidden" value="<?php echo $asunto_mensaje;?>" />
       <input name="cuerpo" type="hidden" value="<?php echo $cuerpo_mensaje;?>" />
       <input name="archivo_adjunto" type="hidden" value="<?php echo $archivo_adjunto;?>" />
       </th>
    </tr>
</thead>
<tbody>
<tr>
<td>N</td>
<td>Nombre</td>
<td>Enviar ¿?</td>
</tr>
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
	$cuenta_funcionarios_con_acceso=0;
	
	if($sede!="0"){ $condicion_sede="AND sede='$sede'";}
	else{ $condicion_sede="";}
	
	$cons_main_1="SELECT DISTINCT(personal.id) FROM personal WHERE nivel IN('2', '3', '4', '5') $condicion_sede ORDER by personal.apellido_P, personal.apellido_M";
	
		$sql_main_1=$conexion_mysqli->query($cons_main_1)or die($conexion_mysqli->error);
		$num_reg_M=$sql_main_1->num_rows;
		if(DEBUG){ echo"<br><br>$cons_main_1<br>NUM.$num_reg_M<br>";}
		if($num_reg_M>0)
		{
			while($DID=$sql_main_1->fetch_row())
			{
				$id_administrativo=$DID[0];
					
					$cons_A="SELECT con_acceso FROM personal WHERE id='$id_administrativo' LIMIT 1";
					$sqli_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
					$DA=$sqli_A->fetch_assoc();
						$AD_acceso=$DA["con_acceso"];
					$sqli_A->free();	
					
					$nombre_personal=NOMBRE_PERSONAL($id_administrativo);
					
					$mostrar_administrativo=true;
					
					if($AD_acceso=="ON"){ $check='checked="checked"'; $cuenta_funcionarios_con_acceso++;}
					else{ $check="";}
					
//------------------------------------------------------------------------------------------------------------------------------//		
					if($mostrar_administrativo)
					{
						
						////////////////////////////////////////
						if(DEBUG){ echo"<strong>Mostrar Administrativo</strong><br>";}
						$cuenta_funcionarios++;
						echo'<tr>
								<td>'.$cuenta_funcionarios.'</td>
								<td>'.$nombre_personal.'</td>
								<td><input id="id_administrativo" name="array_id_administrativo[]" type="checkbox" value="'.$id_administrativo.'" '.$check.'/></td>
								</tr>';
						
					}
					else
					{
						if(DEBUG){ echo"<strong>NO Mostrar Administrativo</strong><br>";}
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
<td colspan="9">(<?php echo"$cuenta_funcionarios_con_acceso / $cuenta_funcionarios";?>) Administrativos con Acceso</td>
</tr>
</tbody>
</table>
</form>
<br />
</div>
</body>
</html>