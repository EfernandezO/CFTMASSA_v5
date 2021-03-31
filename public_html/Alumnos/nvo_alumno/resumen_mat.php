<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Agrega_alumno_nuevo_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

$paso_A_ok=false;
$paso_B_ok=false;
if(isset($_SESSION["MATRICULA"]))
{
	if(isset($_SESSION["MATRICULA"]["PASO_A"]))
	{ $paso_A_ok=$_SESSION["MATRICULA"]["PASO_A"];}
	
	if(isset($_SESSION["MATRICULA"]["PASO_B"]))
	{ $paso_B_ok=$_SESSION["MATRICULA"]["PASO_B"];}
}

if($paso_A_ok and $paso_B_ok)
{
	$_SESSION["MATRICULA"]["RESUMEN"]=true;
	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
	include("../../../funciones/funciones_sistema.php");
///extraccion de variables para comodidad//////// no me gusta extract()//////////////////
	
	//var_export($_SESSION["MATRICULA"]);
	$rut_alumno=$_SESSION["MATRICULA"]["rut_alumno"];
	$nombre_alumno=$_SESSION["MATRICULA"]["nombres_alumno"];
	$apellido_alumno=$_SESSION["MATRICULA"]["apellido_P_alumno"]." ".$_SESSION["MATRICULA"]["apellido_M_alumno"];
	//$sede_alumno=$_SESSION["MATRICULA"]["sede"];
	$direccion_alumno=$_SESSION["MATRICULA"]["direccion_alumno"];
	$pais_origen=$_SESSION["MATRICULA"]["pais_origen"];
	$ciudad_alumno=$_SESSION["MATRICULA"]["ciudad_alumno"];
	$sexo_alumno=$_SESSION["MATRICULA"]["sexo_alumno"];
	$estado_civil_alumno=$_SESSION["MATRICULA"]["estado_civil"];///agregado 12/11/2013
	if($sexo_alumno=="M")
	{$sexo_alumno="Masculino";}
	elseif($sexo_alumno=="F")
	{$sexo_alumno="Femenino";}
	
	$fnac_alumno=$_SESSION["MATRICULA"]["fnac_alumno"];
	$fono_alumno=$_SESSION["MATRICULA"]["fono_alumno"];
	$direccion_alumno=$_SESSION["MATRICULA"]["direccion_alumno"];
	$ciudad_alumno=$_SESSION["MATRICULA"]["ciudad_alumno"];
	$correo_alumno=$_SESSION["MATRICULA"]["correo_alumno"];
	$nombreC_apoderado=$_SESSION["MATRICULA"]["nombreC_apoderado"];
	$fono_apoderado=$_SESSION["MATRICULA"]["fono_apoderado"];
	$rut_apoderado=$_SESSION["MATRICULA"]["rut_apoderado"];
	$direccion_apoderado=$_SESSION["MATRICULA"]["direccion_apoderado"];
	$ciudad_apoderado=$_SESSION["MATRICULA"]["ciudad_apoderado"];

	
	$liceo_pais=$_SESSION["MATRICULA"]["liceo_pais"];
	$liceo_egreso=$_SESSION["MATRICULA"]["liceo_egreso"];
	$liceo=$_SESSION["MATRICULA"]["liceo"];
	$idLiceo=$_SESSION["MATRICULA"]["idLiceo"];
	$liceo_dependencia=$_SESSION["MATRICULA"]["liceo_dependencia"];
	
	list($L_nombre_establecimiento, $L_region, $L_ciudad, $L_dependencia)=DATOS_LICEO($idLiceo);
	
	/*
	$year_ingreso=$_SESSION["MATRICULA"]["year_ingreso"];
	$sede=$_SESSION["MATRICULA"]["sede"];
	$jornada=$_SESSION["MATRICULA"]["jornada"];
	$nivel_academico=$_SESSION["MATRICULA"]["nivel_academico"];///agregado
	$grupo_curso=$_SESSION["MATRICULA"]["grupo_curso"];///agregado
	if($_SESSION["MATRICULA"]["carrera"]==0)
	{
		$id_carrera=0;
		$carrera_alumno="Sin Carrera";
	}
	else
	{
		$array_carrera=explode("_",$_SESSION["MATRICULA"]["carrera"]);
		$id_carrera=$array_carrera[0];
		$carrera_alumno=$array_carrera[1];
	}
	
	if(isset($_SESSION["MATRICULA"]["reg_academico"])){$reg_academico=$_SESSION["MATRICULA"]["reg_academico"];}
	else{ $reg_academico="";}
	
	if($jornada=="D")
	{$jornada="Diurno";}
	elseif($jornada=="V")
	{$jornada="Vespertino";}
	*/
	
	
	//campos agregados 
	$formacion_liceo=$_SESSION["MATRICULA"]["liceo_formacion"];
	if(DEBUG){ echo "--->".$_SESSION["MATRICULA"]["liceo_formacion"];}
	$formacion_liceo_label=ucwords(strtolower($formacion_liceo));
	$otro_estudio_U=$_SESSION["MATRICULA"]["otro_estudio_U"];
	$otro_estudio_T=$_SESSION["MATRICULA"]["otro_estudio_T"];
	$otro_estudio_P=$_SESSION["MATRICULA"]["otro_estudio_P"];
	
	
	
	
	
	
		////////////CONSULTO SI EXISTE registro del alumno//////////////////////////////////
		$consB="SELECT COUNT(id) FROM alumno WHERE rut='$rut_alumno'";
		if(DEBUG){echo"$consB<br>"; }
		$sqlB=$conexion_mysqli->query($consB)or die($conexion_mysqli->error);
		$D=$sqlB->fetch_row();
		$coincidencias=$D[0];
		$sqlB->free();
		if(DEBUG){ echo"COINCIDENCIA DE alumno en el Sistema: $coincidencias<br>";}
		if($coincidencias>0)
		{
			//NO permito grabar creo funcion js
			//echo"hay registro de alumno con estos valores-> detener";
			$funcion_js="alert('Este Alumno ya tiene registro Creado con los datos Anteriores modifique para Continuar');";
			
		}
		else
		{
			//echo"no hay registro-> continuar";
			//permito grabar -> funcion js para enviar form
			$funcion_js="c=confirm('zSeguro(a) Desea registrar este Alumno?');
			if(c==true)
			{
				window.location='graba_alumno.php';
			}";
		}
	
}
else
{
	if(DEBUG){echo "faltan pasos<br>";}
	$funcion_js="alert('Falto Pasos para COntinuar... :D');";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion_2.php");?>
<title>Nuevo Alumno - Resumen</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<script language="javascript">
function Confirmar()
{
	c=false;
	<?php echo $funcion_js;?>
}
function Salir()
{
	s=false;
	s=confirm('Desea Volver al Menu\n Este Alumno no sera Registrado');
	if(s)
	{
		window.location="destructor_session_matricula.php?url=menu_principal";
	}
}
</script>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 45px;
}
-->
</style>
</head>

<body>
<h1 id="banner">Administrador - Nuevo Alumno Resum&eacute;n </h1>
<div id="Layer1">
  <table width="90%" border="0" align="center">
  <thead>
    <tr>
      <th colspan="7" ><strong>Alumno</strong></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="19%">Rut</td>
      <td colspan="2"><em><?php echo $rut_alumno;?></em></td>
      <td width="19%">Sexo</td>
      <td width="28%" colspan="3"><em><?php echo $sexo_alumno;?></em></td>
    </tr>
    <tr>
      <td>Nombre</td>
      <td colspan="2"><em><?php echo $nombre_alumno;?></em></td>
      <td>Fecha nacimiento </td>
      <td colspan="3"><em><?php echo fecha_format($fnac_alumno);?></em></td>
    </tr>
    <tr>
      <td>Apellido</td>
      <td colspan="2"><em><?php echo $apellido_alumno;?></em></td>
      <td>Telefono</td>
      <td colspan="3"><em><?php echo $fono_alumno;?></em></td>
    </tr>
    <tr>
      <td>Direccion</td>
      <td colspan="2"><em><?php echo $direccion_alumno;?></em></td>
      <td>Ciudad</td>
      <td colspan="3"><em><?php echo $ciudad_alumno;?></em></td>
    </tr>
    <tr>
      <td>E-mail</td>
      <td colspan="2"><em><?php echo $correo_alumno;?></em></td>
      <td>Estado Civil</td>
      <td colspan="3"><?php echo $estado_civil_alumno;?></td>
    </tr>
    <tr>
      <th colspan="7" align="left" bgcolor="#458FCD"><strong>Apoderado</strong></th>
    </tr>
    <tr>
      <td>Rut</td>
      <td colspan="2"><em><?php echo $rut_apoderado;?></em></td>
      <td>Direccion</td>
      <td colspan="3"><em><?php echo $direccion_apoderado;?></em></td>
    </tr>
    <tr>
      <td>Nombre Completo </td>
      <td colspan="2"><em><?php echo $nombreC_apoderado;?></em></td>
      <td>Ciudad</td>
      <td colspan="3"><em><?php echo $ciudad_apoderado;?></em></td>
    </tr>
    <tr>
      <td>telefono</td>
      <td colspan="2"><em><?php echo $fono_apoderado;?></em></td>
      <td>Pais Origen</td>
      <td colspan="3"><em><?php echo $pais_origen;?></em></td>
    </tr>
   
    <tr>
      <th colspan="7" align="left" bgcolor="#458FCD"><strong>Procedencia</strong></th>
    </tr>
    <tr>
      <td>idLiceo</td>
      <td colspan="2"><em><?php echo $idLiceo." - ".$L_nombre_establecimiento;?></em></td>
      <td>A&ntilde;o Egreso </td>
      <td colspan="3"><?php echo $liceo_egreso;?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>Ciudad</td>
      <td colspan="3"><?php echo $L_ciudad;?> - <?php echo $liceo_pais;?></td>
    </tr>
    <tr>
      <td>Formaci&oacute;n</td>
      <td colspan="2"><em><?php echo $formacion_liceo_label;?></em></td>
      <td>Otros Estudios</td>
      <td colspan="3">
        <em>
        <?php
	  if($otro_estudio_U=="si")
	  {echo"Universitario*"; }
      ?>
        </em>  <em>
        <?php
	  if($otro_estudio_T=="si")
	  {echo"Tecnico*";}
      ?>
        </em> <em>
        <?php
	  if($otro_estudio_P=="si")
	  {echo"Profecional*";}
      ?>
        </em></td>
    </tr>
    </tbody>
  </table>
    <table width="90%" align="center">
    <thead>
    <tr>
      <th colspan="7" ><strong>&iquest;Finalizar el Proceso de Inscripcion?</strong></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>Volver a </td>
      <td width="21%"><a href="paso_A.php" class="button">Paso A </a></td>
      <td width="13%"><a href="paso_B.php" class="button">Paso B </a></td>
      <td><a href="destructor_session_matricula.php?url=menu_principal" class="button">Menu Alumno </a></td>
      <td colspan="3"><div align="right">
        <input type="button" name="Submit" value="Si"  onclick="Confirmar();"/>
        <input type="button" name="Submit2" value="No"  onclick="Salir();"/>
      </div></td>
    </tr>
    </tbody>
  </table>
</div>
</body>
<?php $conexion_mysqli->close();?>
</html>