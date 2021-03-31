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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion_2.php");?>
<title>Matricula - Opciones Finales</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
<!--

.Estilo1 {font-weight: bold}
.Estilo3 {font-size: 12px}
.Estilo4 {
	font-size: 12px;
	font-weight: bold;
}
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 139px;
}
-->
</style></head>

<body>
<h1 id="banner">Administrador - Matricula Opciones Finales 
<?php 
if($_GET)
{
	$error=$_GET["error"];
	$aux_carrera="";
	switch ($error)
	{
		case"0":
			$msj="<strong>Alumno Registrado Exitosamente</strong>,<br />
Seleccione Volver al Menu, desde Ahi podra Imprimir su Ficha de Matricula y generar Contratos Necesarios...<br> Si Desea Solicitar Pase Escolar Dirijase a Modificar Datos/Documentos Obligatorios/";
			/////////////////////////////////////
			$_SESSION["SELECTOR_ALUMNO"]["id"]=$_SESSION["MATRICULA"]["id_alumno"];
			$_SESSION["SELECTOR_ALUMNO"]["rut"]=$_SESSION["MATRICULA"]["rut_alumno"];
			$_SESSION["SELECTOR_ALUMNO"]["nombre"]=$_SESSION["MATRICULA"]["nombres_alumno"];
			$aux_carrera=$_SESSION["MATRICULA"]["carrera"];
	
			$array_carrera=explode("_",$aux_carrera);///para separar carrera y utilizar su id
			$id_carrera=$array_carrera[0];
			$carrera=$array_carrera[1];
			
			$_SESSION["SELECTOR_ALUMNO"]["id_carrera"]=$id_carrera;
			$_SESSION["SELECTOR_ALUMNO"]["carrera"]=$carrera;
			
			$_SESSION["SELECTOR_ALUMNO"]["situacion"]="V";
			$_SESSION["SELECTOR_ALUMNO"]["sede"]=$_SESSION["MATRICULA"]["sede"];
			$_SESSION["SELECTOR_ALUMNO"]["jornada"]=$_SESSION["MATRICULA"]["jornada"];
			$_SESSION["SELECTOR_ALUMNO"]["nivel"]=$_SESSION["MATRICULA"]["nivel_academico"];
			$_SESSION["SELECTOR_ALUMNO"]["sexo"]=$_SESSION["MATRICULA"]["sexo_alumno"];
		
			$_SESSION["SELECTOR_ALUMNO"]["apellido"]=$_SESSION["MATRICULA"]["apellido_P_alumno"]." ".$_SESSION["MATRICULA"]["apellido_M_alumno"];
			$_SESSION["SELECTOR_ALUMNO"]["ingreso"]=$_SESSION["MATRICULA"]["year_ingreso"];
			$_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]=true;
			///////////////////////////////////////
			break;
	}
}

?>
</h1>
<div id="apDiv1">
  <table width="45%" border="1" align="center">
  <thead>
    <tr>
      <th>&#9658;<strong>Informaci&oacute;n</strong></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td height="83"><?php echo $msj;?></td>
    </tr>
    <tr>
      <td height="47" align="center"><a href="Intranet instructivoV2_1(ALUMNOS).pdf" target="_blank" class="button">IMPRIMIR instructivo INTRANET</a></td>
    </tr>
    <?php if($_SESSION["SELECTOR_ALUMNO"]["id_carrera"]==4){?>
    <tr>
      <td height="40" align="center"><a href="../../Certificados/requerimientos_alumnos_TENS/requerimiento_alumnos_TENS.php" target="_blank" class="button_R">Imprimir Compromiso TENS</a></td>
    </tr>
    <?php }?>
    <tr>
      <td height="40" align="center"><a href="destructor_session_matricula.php?url=modificacion_alumno" class="button_R">Modificacion de datos /Registro de Documentacion</a></td>
    </tr>
    <tr>
      <td align="center"><a href="destructor_session_matricula.php?url=HALL" class="button">Volver al Menu</a></td>
    </tr>
    </tbody>
  </table>
</div>
</body>
</html>