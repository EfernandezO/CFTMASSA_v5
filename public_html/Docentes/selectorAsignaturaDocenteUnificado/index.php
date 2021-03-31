<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("SelectorAsignaturaDocenteUnificadoV1->ver");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("busca_asignaturas_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCAR_ASIGNATURAS");
$xajax->register(XAJAX_FUNCTION,"SELECCIONAR");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<title>Selector Asignaturas Docente unificado</title>
<?php $xajax->printJavascript(); ?> 
<style type="text/css">
#areaTrabajo {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 169px;
}
#botonera {
	text-align: center;
}
#texto {
	text-align: center;
}
</style>
<script language="javascript">
function VERIFICAR()
{
	continuar=true;
	asignatura=document.getElementById('asignatura').value;
	grupo_curso=document.getElementById('grupo_curso').value;
	jornada=document.getElementById('jornada').value;
	
	if((asignatura==0)||(asignatura=""))
	{ continuar=false; alert('Seleccione Asignatura');}
	
	if((grupo_curso==0)||(grupo_curso=""))
	{ continuar=false; alert('Seleccione Grupo');}
	
	if((jornada==0)||(jornada=""))
	{ continuar=false; alert('Seleccione Jornada');}
	
	if(continuar)
	{document.getElementById('frm').submit();}
}
</script>
</head>

<body onload="xajax_BUSCAR_ASIGNATURAS();">

<h1 id="banner">Administrador -Selector Asignatura Docente</h1>
<div id="link"><br />
<a href="../okdocente.php" class="button">Volver a Menu</a></div>
<div id="areaTrabajo">
  <div id="botonera"></div>
	<div id="areaTrabajo2"></div>
  <div id="texto">Asignaturas actualmente asignados, seleccione para dirigirse a una opcion del libro de clases</div>
</div>
</body>
</html>