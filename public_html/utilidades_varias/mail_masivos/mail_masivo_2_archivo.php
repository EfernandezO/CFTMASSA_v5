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
if(DEBUG){error_reporting(E_ALL); ini_set("display_errors", 1);}
//////////////////////XAJAX/////////////////

if($_POST)
{
	require("../../../funciones/funciones_sistema.php");
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funcion.php");
	require("../../../funciones/funciones_varias.php");
	$archivo_adjunto=$_POST["archivo_adjunto"];
	$asunto_mensaje=$_POST["asunto_mensaje"];
	$cuerpo_mensaje=$_POST["cuerpo_mensaje"];
	
	
	if($_FILES)
	{
		if(DEBUG){ echo"Archivo Enviado<br>";}
		$destino="../../CONTENEDOR_GLOBAL/archivos_temporales";
		if(DEBUG){ echo"RUTA: $destino<br>";}
		
		list($archivo_cargado, $archivo_destinatarios)=CARGAR_ARCHIVO($_FILES['archivo'], $destino, "TMP_destinatarios_");
	}
}
else
{
	$continuar=false;
}
$mes_actual=date("m");
if($mes_actual>8)
{ $semestre_actual=2;}
else
{ $semestre_actual=1;}

$year_actual=date("Y");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Email Masivo 2</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:186px;
	z-index:1;
	left: 5%;
	top: 155px;
}
.Estilo1 {font-size: 12px}
#Layer2 {
	position:absolute;
	width:168px;
	height:16px;
	z-index:2;
	left: 420px;
	top: 49px;
}
-->
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
<h1 id="banner">Administrador - Envio Masivo Email 2/4</h1>
<?php
$privilegio=$_SESSION["USUARIO"]["privilegio"];
switch($privilegio)
{
	case"matricula":
		$url="../../Administrador/menu_matricula/index.php";
		break;
	case"inspeccion":
		$url="../../Administrador/menu_inspeccion/index.php";
		break;
	default:
		$url="../../Administrador/ADmenu.php";	
}
?>
<div id="link"><br><a href="mail_masivo_0.php" class="button">Volver a Seleccion</a><br />
<br />
<a href="#" class="button" onclick="ENVIAR_MAIL();">Enviar Correos </a><br />
<br />
  <a href="#" class="button_R" onclick="seleccionar_todo();">Marcar Todo</a>  <a href="#" class="button_R" onclick="deseleccionar_todo();">Desmarcar Todo</a>
  </div>
<div id="Layer1">
<form action="mail_masivo_3_archivo.php" method="post" enctype="multipart/form-data" name="frm" id="frm">
  <table width="100%" border="1" align="center">
  <caption></caption>
  <thead>
    <tr>
      <th colspan="6"><span class="Estilo1">Carga de Archivo
          <input name="asunto_mensaje" type="hidden" id="asunto_mensaje" value="<?php echo $asunto_mensaje;?>" />
        <input type="hidden" name="cuerpo_mensaje" id="cuerpo_mensaje" value="<?php echo $cuerpo_mensaje;?>"/>
        <input name="archivo_adjunto" type="hidden" id="archivo_adjunto" value="<?php echo $archivo_adjunto;?>" />
        <input name="archivo_destinatarios" type="hidden" id="archivo_destinatarios" value="<?php echo $archivo_destinatarios;?>" />
      </span></th>
    </tr>
	</thead>
	<tbody>
   <?php
   $extencions_permitidas=array("xls", "xlsx");
   
   $extencion_archivo=explode(".",$archivo_destinatarios);
   $extencion_archivo=end($extencion_archivo);
   
   
   if(in_array($extencion_archivo, $extencions_permitidas)){ $archivo_permitido=true; if(DEBUG){ echo"Archivo ($extencion_archivo) permitido<br>";}}
   else{ $archivo_permitido=false; if(DEBUG){ echo"Archivo ($extencion_archivo) NO permitido<br>";}}
   
   	$archivo_a_procesar=$destino."/".$archivo_destinatarios;
	if(DEBUG){ echo"archivo a procesar: $archivo_a_procesar<br>";}
////////////////////////////////
//LECTURA DE EXCEL
/** Include path **/
set_include_path(get_include_path() . PATH_SEPARATOR . '../../libreria_publica/PHPExcel-1.7.7/Classes/');
/** PHPExcel_IOFactory */
include('../../libreria_publica/PHPExcel-1.7.7/Classes/PHPExcel/IOFactory.php');

$inputFileName = $archivo_a_procesar;
if(DEBUG){echo 'Cargando Archivo ',pathinfo($inputFileName,PATHINFO_BASENAME),'<br> usando IOFactory para identificar formato<br /><br>';}
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

//if(DEBUG){ var_dump($sheetData);}
$contador=0;
if((isset($sheetData))and($archivo_permitido))
{
	
	if(DEBUG){ echo"Hay Datos en archivo<br>";}
	foreach($sheetData as $fila => $array_columnas)
	{
		$continuar=false;
		if(isset($array_columnas["A"])){$COLUMNA_A=$array_columnas["A"];}
		else{ $COLUMNA_A=NULL;}
		
		
		if(isset($array_columnas["B"])){$COLUMNA_B=strtoupper($array_columnas["B"]);}
		else{$COLUMNA_B=NULL;}
		
		if(DEBUG){var_dump($COLUMNA_A); var_dump($COLUMNA_B); echo"<br>";}
		
		if((empty($COLUMNA_B))and(!empty($COLUMNA_A)))
		{
			if(DEBUG){ echo"Solo Columna A con datos<br>";}
			$aux_rut=strip_tags($COLUMNA_A);
			$continuar=true;
			//veo ai tiene o no -
			
			if(strpos($aux_rut,"-")){if(DEBUG){ echo"Rut Con DV<br>";}}
			else
			{ 
				if(DEBUG){ echo"Rut sin DV<br>";}
				$aux_dv=validar_rut($aux_rut);
				$aux_rut.="-".$aux_dv;
			}
		}
		else
		{
			if(DEBUG){echo"$fila -> $COLUMNA_A - $COLUMNA_B :";}
			$aux_rut=str_inde($COLUMNA_A,"")."-".str_inde($COLUMNA_B,"");
			
			if((is_numeric($COLUMNA_A))and(is_string($COLUMNA_B)))
			{ $continuar=true;}
			else{ $continuar=false;}
		}
		
		////--------------------------------------------/////
		if($continuar)
		{
			$ya_se_imprimio=false;
			$ver_registro=false;
			$cons_1="SELECT * FROM alumno WHERE rut='$aux_rut' ORDER by id desc LIMIT 1";
			if(DEBUG){ echo"Busqueda como Alumno -->$cons_1<br>";}
			$sql_1=$conexion_mysqli->query($cons_1)or die($conexion_mysqli->error);
			$num_registros_encontrados=$sql_1->num_rows;
			if($num_registros_encontrados>0)
			{
				$D_1=$sql_1->fetch_assoc();
				$ver_registro=true;
				if($ver_registro)
				{
					//datos alumno
					$tipo_usuario="alumno";
					$A_id=$D_1["id"];
					
					$A_nombre=$D_1["nombre"];
					$A_apellido_P=$D_1["apellido_P"];
					$A_apellido_M=$D_1["apellido_M"];
					$A_sede=$D_1["sede"];
					$A_email=$D_1["email"];
					
					$U_nombre=$A_nombre;
					$U_id=$A_id;
					$U_apellido_P=$A_apellido_P;
					$U_apellido_M=$A_apellido_M;
					$U_sede=$A_sede;
					$U_email=$A_email;
					$U_tipo=$tipo_usuario;
					if(DEBUG){ echo"<br><strong>id_alumno: $A_id Nombre: $A_nombre $A_apellido_P $A_apellido_M <br>Sede: $A_sede </strong><br>";}
				}//fin si ver registro
				$sql_1->free();
			}//fin si num registros
			else
			{
				$cons_2="SELECT * FROM personal WHERE rut='$aux_rut' ORDER by id desc LIMIT 1";
				if(DEBUG){ echo"Busqueda como funcionario -->$cons_2<br>";}
				$sql_2=$conexion_mysqli->query($cons_2)or die($conexion_mysqli->error);
				$num_registros_encontrados_2=$sql_2->num_rows;
				if($num_registros_encontrados_2>0)
				{
					$D_2=$sql_2->fetch_assoc();
					$ver_registro=true;
					if($ver_registro)
					{
						//datos funcionario
						$tipo_usuario="funcionario";
						$P_id=$D_1["id"];
						
						$P_nombre=$D_1["nombre"];
						$P_apellido_P=$D_1["apellido_P"];
						$P_apellido_M=$D_1["apellido_M"];
						$P_sede=$D_1["sede"];
						$P_email=$D_1["email"];
						
						$U_nombre=$P_nombre;
						$U_id=$P_id;
						$U_apellido_P=$P_apellido_P;
						$U_apellido_M=$P_apellido_M;
						$U_sede=$P_sede;
						$U_email=$P_email;
						$U_tipo=$tipo_usuario;
					if(DEBUG){ echo"<br><strong>id_funcionario: $P_id Nombre: $P_nombre $AP_apellido_P $P_apellido_M <br><br>Sede: $A_sede</strong><br>";}
					}
					$sql_2->free();
				}
					
			}
			
			if($ver_registro)
			{
				$contador++;
				echo'<tr>
						<td>'.$contador.'</td>
						<td>'.$U_nombre.'</td>
						<td>'.$U_apellido_P.' '.$U_apellido_M.'</td>
						<td>'.$U_sede.'</td>
						<td>'.$U_email.'</td>
						<td><input id="id_usuario" name="array_id_usuario['.$U_tipo.'][]" type="checkbox" value="'.$U_id.'" /></td>
					</tr>';
			}
		}//fin si continuar
	}//fin foreach
}
else
{
	if(DEBUG){ echo"Sin Datos<br>";}
	echo'<tr><td>Sin Datos :(</td></tr>';
}
   ?>
	
  </table>
 </form> 
</div>
</body>
</html>
