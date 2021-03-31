<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	//$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Agrega_alumno_nuevo_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////

@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("paso_B_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CARGA_LICEOS");
$xajax->register(XAJAX_FUNCTION,"CARGA_COMUNA_LICEO");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion_2.php");?>
<?php $xajax->printJavascript(); ?> 
<title>Nuevo ALUMNO - paso B</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<script language="javascript" type="text/javascript">
function Volver()
{
	window.location="paso_A.php";
}
function isCharDigit(n){
  return !!n.trim() && !isNaN(+n);
}
function Continuar()
{
	error=true;
	nem=document.getElementById('liceo_nem').value;
	liceo=document.getElementById('liceo').value;
	egreso=document.getElementById('liceo_egreso').value;
	
	
	
	if(isCharDigit(nem)){
		if((nem=="")||(nem==" ")||(nem<100))
		{
			alert('Ingrese NEM valido (ej: 5.1=510)');
			error=false
		}
	}else{alert('Ingrese NEM valido');}
	
	
	
	if((liceo=="")||(liceo==" ")||(liceo==0))
	{
		alert('Ingrese Liceo Procedencia');
		error=false
	}
	
	
	if((egreso=="")||(egreso==" "))
	{
		alert('Ingrese ano egreso Liceo');
		error=false
	}
	if(error)
	{
		document.frm.submit();
	}
}
function FOCO()
{
	document.getElementById('liceo').focus();
}
</script>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:452px;
	z-index:1;
	left: 5%;
	top: 52px;
}
-->
</style>
</head>
<?php
require("../../../funciones/funciones_sistema.php");
//var_dump($_SESSION["MATRICULA"]);
/////////////////////////
$sede_usuario=$_SESSION["USUARIO"]["sede"];

$array_dependencia=array("municipal","particular","corporacion", "particular subvencionado");
$array_jornada=array("D"=>"Diurno","V"=>"Vespertino");
$array_formacion=array("cientifico humanista"=>"Cientifico Humanista","tecnico profecional"=>"Tecnico Profesional", "escuela artistica"=>"Escuelas Artisticas");
////////////carrera
$array_carrera=array();
require("../../../funciones/conexion_v2.php");
   
$conexion_mysqli->close();

////comprobar paso ya realizados
///A y B
$paso_A_ok=false;
$paso_B_ok=false;
if(isset($_SESSION["MATRICULA"]))
{
	if(isset($_SESSION["MATRICULA"]["PASO_A"]))
	{ $paso_A_ok=$_SESSION["MATRICULA"]["PASO_A"];}
	
	if(isset($_SESSION["MATRICULA"]["PASO_B"]))
	{ $paso_B_ok=$_SESSION["MATRICULA"]["PASO_B"];}
}
$region_liceo=7;
if($paso_B_ok)
{
	if(DEBUG){ echo"Paso B OK<br>";}
	
	if(isset($_SESSION["MATRICULA"]["liceo_nem"])){$liceo_nem=$_SESSION["MATRICULA"]["liceo_nem"];}
	else{$liceo_nem="";}
	
	$liceo=$_SESSION["MATRICULA"]["liceo"];
	$dependencia=$_SESSION["MATRICULA"]["liceo_dependencia"];
	
	if(isset($_SESSION["MATRICULA"]["year_ingreso"]))
	{ $year_ingreso=$_SESSION["MATRICULA"]["year_ingreso"]; if(DEBUG){ echo"year ingreso ok<br>";}}
	else{ $year_ingreso=date("Y"); if(DEBUG){ echo"year ingreso usa actual<br>";}}
	
	if(isset($_SESSION["MATRICULA"]["sede"]))
	{$sede=$_SESSION["MATRICULA"]["sede"];}
	else{ $sede="";}
	
	if(isset($_SESSION["MATRICULA"]["jornada"]))
	{$jornada=$_SESSION["MATRICULA"]["jornada"];}
	else{ $jornada="D";}
	
	if(isset($_SESSION["MATRICULA"]["carrera"]))
	{$carrera=$_SESSION["MATRICULA"]["carrera"];}
	else{ $carrera="";}
	$liceo_ciudad=$_SESSION["MATRICULA"]["liceo_ciudad"];
	$liceo_pais=$_SESSION["MATRICULA"]["liceo_pais"];
	$liceo_egreso=$_SESSION["MATRICULA"]["liceo_egreso"];
	
	$formacion=$_SESSION["MATRICULA"]["liceo_formacion"];
	$otro_estudio_U=$_SESSION["MATRICULA"]["otro_estudio_U"];
	$otro_estudio_T=$_SESSION["MATRICULA"]["otro_estudio_T"];
	$otro_estudio_P=$_SESSION["MATRICULA"]["otro_estudio_P"];
	
	if(isset($_SESSION["MATRICULA"]["carrera"]))
	{
		if($_SESSION["MATRICULA"]["carrera"]==0)
		{
			$id_carrera_alumno=0;
			$carrera_alumno="";
		}
		else
		{
			$array_carrera_alumno=explode("_",$_SESSION["MATRICULA"]["carrera"]);
			$id_carrera_alumno=$array_carrera_alumno[0];
			$carrera_alumno=$array_carrera_alumno[1];
		}
	}
	else
	{
		$id_carrera_alumno=0;
		$carrera_alumno="";
	}
	
	$session_B=true;
}
else
{
	$liceo_pais="Chile";
	$liceo_nem="";
	$liceo_egreso="";
	
}
?>
<body onload="xajax_CARGA_COMUNA_LICEO(<?php echo $region_liceo;?>);">
<h1 id="banner">Administrador - Nuevo Alumno Paso 2</h1>
<div id="Layer1">
<form action="paso_BX.php" method="post" name="frm" id="frm">
  <table width="65%" border="0" align="center">
  <thead>
    <tr>
      <th height="17" colspan="4" bgcolor="#f5f5f5"><strong>Datos de Procedencia</strong></th>
    </tr>
    </thead>
    <tbody>
    <tr >
      <td height="27" >NEM</td>
      <td height="27" colspan="3" ><input name="liceo_nem" type="text"  id="liceo_nem" size="10" maxlength="3"  value="<?php echo $liceo_nem;?>"/></td>
    </tr>
    <tr >
      <td height="27" >Pais donde realizo estudios (medios - liceo)</td>
      <td height="27" colspan="3" ><?php echo CAMPO_SELECCION("liceo_pais","paises",$liceo_pais,false);?></td>
    </tr>
    <tr >
      <td height="27" colspan="4" >&nbsp;</td>
    </tr>
    <tr >
      <td height="27" colspan="4" ><strong>Ubicacion del Liceo </strong></td>
      </tr>
    <tr >
      <td height="27" >Region</td>
      <td height="27" ><label for="RegionLiceo"></label>
        <select name="RegionLiceo" id="RegionLiceo" onchange="xajax_CARGA_COMUNA_LICEO(this.value);">
        <?php
		for($a=1;$a<=18;$a++){
			$select='';
			if($a==$region_liceo){$select='selected="selected"';}
			echo'<option value="'.$a.'" '.$select.'>'.$a.'</option>';
		}
        ?>
        </select></td>
      <td height="27" >Ciudad del Liceo</td>
      <td height="27" ><div id="div_ciudad">...</div></td>
    </tr>
    <tr >
      <td width="154" height="27" >Liceo</td>
      <td height="27" colspan="3" ><div id="div_liceo">...
        <input name="liceo" type="hidden" id="liceo" value="0" />
      </div></td>
    </tr>
    <tr >
      <td height="27">Formaci&oacute;n</td>
      <td height="27" colspan="3" ><select name="formacion_liceo" id="formacion_liceo">
        <?php
	  	foreach($array_formacion as $n => $valor)
		{
			if(($paso_B_ok)and($formacion==$n))
			{
				echo'<option value="'.$n.'" selected="selected">'.$valor.'</option>';	
			}
			echo'<option value="'.$n.'">'.$valor.'</option>';
		}
	  ?>
        </select></td>
    </tr>
    <tr >
      <td height="27">&nbsp;</td>
      <td height="27" >&nbsp;</td>
      <td height="27" >&nbsp;</td>
      <td height="27" >&nbsp;</td>
    </tr>
    <tr >
      <td height="27">A&ntilde;o Egreso 4to medio</td>
      <td height="27" colspan="3" >
      <?php echo CAMPO_SELECCION("liceo_egreso","year",$liceo_egreso,false);?></td>
    </tr>
    <tr >
      <td height="27" rowspan="2">Otros Estudios</td>
      <td width="152" height="12" >Universitaria</td>
      <td width="135" >Tecnica</td>
      <td height="12" >Profesional</td>
    </tr>
    <tr >
      <td height="13">
      <?php
	   if($paso_B_ok)
	   {
	   		if($otro_estudio_U=="si")
			{
				echo'<input type="radio" name="otro_estudio_U" id="radio" value="si" checked="checked"/>Si
				<input name="otro_estudio_U" type="radio" id="radio2" value="no" />No';
			}
			else
			{
				echo'<input type="radio" name="otro_estudio_U" id="radio" value="si"/>Si
				<input name="otro_estudio_U" type="radio" id="radio2" value="no" checked="checked" />No';
			}
	   }
	   else
	   {
	   			echo'<input type="radio" name="otro_estudio_U" id="radio" value="si"/>Si
				<input name="otro_estudio_U" type="radio" id="radio2" value="no" checked="checked" />No';
	   }
	   ?>        </td>
      <td height="13" ><?php
	   if($paso_B_ok)
	   {
	   		if($otro_estudio_T=="si")
			{
				echo'<input type="radio" name="otro_estudio_T" id="radio" value="si" checked="checked"/>Si
				<input name="otro_estudio_T" type="radio" id="radio2" value="no" />No';
			}
			else
			{
				echo'<input type="radio" name="otro_estudio_T" id="radio" value="si"/>Si
				<input name="otro_estudio_T" type="radio" id="radio2" value="no" checked="checked" />No';
			}
	   }
	   else
	   {
	   			echo'<input type="radio" name="otro_estudio_T" id="radio" value="si"/>Si
				<input name="otro_estudio_T" type="radio" id="radio2" value="no" checked="checked" />No';
	   }
	   ?></td>
      <td height="13" ><?php
	   if($paso_B_ok)
	   {
	   		if($otro_estudio_P=="si")
			{
				echo'<input type="radio" name="otro_estudio_P" id="radio" value="si" checked="checked"/>Si
				<input name="otro_estudio_P" type="radio" id="radio2" value="no" />No';
			}
			else
			{
				echo'<input type="radio" name="otro_estudio_P" id="radio" value="si"/>Si
				<input name="otro_estudio_P" type="radio" id="radio2" value="no" checked="checked" />No';
			}
	   }
	   else
	   {
	   			echo'<input type="radio" name="otro_estudio_P" id="radio" value="si"/>Si
				<input name="otro_estudio_P" type="radio" id="radio2" value="no" checked="checked" />No';
	   }
	   ?></td>
    </tr>
    <tr >
      <td height="15" colspan="4" >&nbsp;</td>
    </tr>
    </tbody>
    </table>
    <table width="65%" align="center">
    <thead>
    <tr>
      <th height="15" colspan="4" >&nbsp;</th>
    </tr>
    </thead>
    <tr>
      <td><a href="#" onclick="Volver();" class="button">Volver</a></td>
      <td colspan="2"><div align="center">
        <?php
	  if($paso_B_ok)
	  {
	  ?>
        <a href="resumen_mat.php">Ir a Resum&eacute;n </a>
        <?php }?>
        </div></td>
      <td width="80"><a href="#" onclick="Continuar();" class="button">Continuar</a></td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
</body>
</html>