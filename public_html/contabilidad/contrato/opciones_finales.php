<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Matriculas_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
$msj="";
$id_contrato=$_SESSION["FINANZAS"]["id_contrato"];	
$semestre=$_SESSION["FINANZAS"]["semestre"];	
$yearEstudio=$_SESSION["FINANZAS"]["year_estudio"];	
if($_GET)
{
	$error=$_GET["error"];
	switch($error)
	{
		case"0":
			$msj='<br>* Alumno Exitosamente Matriculado <img src="../../BAses/Images/ok.png" width="29" height="26" alt="ok" />*';
			$_SESSION["FINANZAS"]["GRABADO"]=true;
			break;
		case"1":
			$msj='<br>* Error al Grabar el Contrato...<img src="../../BAses/Images/b_drop.png" width="29" height="26" alt="ok" />*';
			$_SESSION["FINANZAS"]["GRABADO"]=false;	
			break;
	}
}

if(isset($_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["cantidad_cuotas"]))
{
	if($_SESSION["FINANZAS"]["METODO_PAGO"]["LINEA_CREDITO"]["cantidad_cuotas"]>0)
	{ $ver_pagare=true;}
	else
	{ $ver_pagare=false;}
}
else
{ $ver_pagare=false;}

if(isset($_SESSION["FINANZAS"]["BOLETA"]["id_boleta_pagare"]))
{$id_boleta_pagare=$_SESSION["FINANZAS"]["BOLETA"]["id_boleta_pagare"];}
else{$id_boleta_pagare=0;}
if(isset($_SESSION["FINANZAS"]["BOLETA"]["matricula"]))
{$id_boleta_matricula=$_SESSION["FINANZAS"]["BOLETA"]["matricula"];}
else{$id_boleta_matricula=0;}
///hacer boleta pagare
if(isset($_SESSION["FINANZAS"]["BOLETA"]["hacer_boleta_pagare"]))
{
	if($_SESSION["FINANZAS"]["BOLETA"]["hacer_boleta_pagare"])
	{ $hacer_boleta_pagare=true;}
	else
	{ $hacer_boleta_pagare=false;}
}
else
{ $hacer_boleta_pagare=false;}
///se hizo boleta pagare

if(isset($_SESSION["FINANZAS"]["impresion"]["boleta_pagare"]))
{
	if($_SESSION["FINANZAS"]["impresion"]["boleta_pagare"])
	{ $impresa_boleta_pagare=true;}
	else
	{ $impresa_boleta_pagare=false;}
}
else
{ $impresa_boleta_pagare=false;}


if(DEBUG){ echo"id boleta pagare:$id_boleta_pagare<br>id boleta matricula: $id_boleta_matricula<br>";}
//----------------------------------------------------//
 //BOLETA
//-------------------------------------------------------------------------//
if(isset($_SESSION["FINANZAS"]["BOLETA"]["hacer_boleta"]))
{
  if($_SESSION["FINANZAS"]["BOLETA"]["hacer_boleta"])
  { $hay_que_hacer_boleta=true;}
  else
  { $hay_que_hacer_boleta=false;}
}
else
{ $hay_que_hacer_boleta=false;}

//-------------------------------------------------------------------------//
if(isset($_SESSION["FINANZAS"]["impresion"]["boleta"]))
{
  if($_SESSION["FINANZAS"]["impresion"]["boleta"])
  { $hay_que_imprimir_boleta=false;}
  else
  { $hay_que_imprimir_boleta=true;}
}
else
{$hay_que_imprimir_boleta=true;}
//-------------------------------------------------------------------------//  
//------------------------------------------------------------------------//
//pagare
if(isset($_SESSION["FINANZAS"]["impresion"]["pagare"]))
{
	if($_SESSION["FINANZAS"]["impresion"]["pagare"])
	{$hay_que_imprimir_pagare=false;}
	else
	{$hay_que_imprimir_pagare=true;}
}
else
{$hay_que_imprimir_pagare=true;}
//-------------------------------------------------------------------------//
//CONTRATO ACADEMICO
//-------------------------------------------------------------------------//
if(isset($_SESSION["FINANZAS"]["impresion"]["contrato_academico"]))
{
	if($_SESSION["FINANZAS"]["impresion"]["contrato_academico"])
	{$hay_que_imprimir_contrato_academico=false;}
	else
	{$hay_que_imprimir_contrato_academico=true;}
}
else
{$hay_que_imprimir_contrato_academico=true;}			
?>
<?php include("../../../funciones/codificacion.php");?>
<title>Contrato - Final</title>
<script type="text/javascript" src="../../libreria_publica/jquery_libreria/jquery.min_1.2.6.js"></script>

  <script type="text/javascript" src="../../libreria_publica/sexy_lightbox/jQuery/jquery.easing.1.3.js"></script>
  <script type="text/javascript" src="../../libreria_publica/sexy_lightbox/jQuery/sexylightbox.v2.3.jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/sexy_lightbox/jQuery/sexylightbox.css">
  <script type="text/javascript">
    $(document).ready(function(){
      SexyLightbox.initialize({color:'black', dir: '../../libreria_publica/sexy_lightbox/jQuery/sexyimages'});
    });
  </script>

<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/jquery.treeview.css">
	<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
	
	<script src="../../libreria_publica/jquery_treeview/lib/jquery.cookie.js" type="text/javascript"></script>
	<script src="../../libreria_publica/jquery_treeview/jquery.treeview.js" type="text/javascript"></script>
	
	<script type="text/javascript">
		$(function() {
			$("#browser").treeview();
		});
	</script>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:1;
	left: 246px;
	top: 276px;
}
a:link {
	color: #3399FF;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #3399FF;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #3399FF;
}
.Estilo1 {font-weight: bold}
.Estilo2 {font-weight: bold}
#apDiv1 {
	position:absolute;
	width:321px;
	height:115px;
	z-index:1;
	left: 516px;
	top: 102px;
	border: thin groove #FF0000;
}
-->
</style>
<script language="javascript">
function ACTUALIZAR()
{location.reload();}

function SALIR(url)
{
<?php 
	if($hay_que_hacer_boleta)
	{
		if($hay_que_imprimir_boleta){echo'continuar=false;';}
		else{echo'continuar=true;';}
	}
	else{echo'continuar=true;';}
	
	/*if($hacer_boleta_pagare)
	{
		if($impresa_boleta_pagare){echo'continuar_P=true;';}
		else{echo'continuar_P=false;';}
	}
	else{echo'continuar_P=true;';}*/

?>
	if(continuar)
	{
		c=confirm('Volver al Menu y Finalizar Matriculas...?');
		if(c){window.location=url;}
	}
	else
	{ 
	alert("Primero Registre la Boleta y luego presione Actualizar...");
	}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Men&uacute; Finanzas </h1>
<h3>&iquest;Que Desea Hacer Ahora?  </h3>
<div id="main">
  <ul id="browser" class="filetree">
    <li class="Estilo1"><strong><img src="../../libreria_publica/jquery_treeview/images/folder.gif" alt="a" width="16" height="14" />Imprimir Documentos</strong>
      <ul>
        <?php if($hay_que_hacer_boleta){?>
        <li><a href="enruta_boleta.php?id_boleta=<?php echo $id_boleta_matricula;?>&tipo=matricula&TB_iframe=true&height=500&width=450" rel="sexylightbox">1.-Boletas Matricula</a>
          <?php }?>
          <div id="apDiv1"> Tareas Pendientes
            <ul>
              <?php
			  $listo=0;
			 
		  	if($hay_que_hacer_boleta and $hay_que_imprimir_boleta)
			{echo"<li>Imprimir Boleta Matricula</li>";}
			else
			{$listo++;}
			
		/*	if($hacer_boleta_pagare and (!$impresa_boleta_pagare))
			{echo"<li>Imprimir Boleta Pagare</li>";}
			else
			{$listo++;}*/
			
			
			if($hay_que_imprimir_contrato_academico)
			{echo"<li>Imprimir Contrato Academico</li>";}
			else
			{$listo++;}
			
			if($hay_que_imprimir_pagare)
			{echo"<li>Imprimir Pagare</li>";}
			else
			{$listo++;}
			//---------------------------------------------------------------------------/
			if($listo>=3)
			{echo"<li>Sin Tareas Pendientes</li>";}
		  	//var_export($_SESSION["FINANZAS"]["BOLETA"]);
          ?>
            </ul>
            <div align="center"><a href="#" onclick="ACTUALIZAR();">actualizar </a></div>
          </div>
        </li>
        <li><a href="../contratos_old/contrato_old.php?id_contrato=<?php echo $id_contrato;?>&amp;semestre=<?php echo $semestre;?>&amp;year=<?php echo $yearEstudio;?>&amp;tipo_contrato=academico" target="_blank">2.- Contrato Prestacion servicios</a></li>
        
    	<li><a href="../contratos_old/contrato_old.php?tipo_contrato=academico&id_contrato=<?php echo $id_contrato;?>" target="_blank">Mandato Pagare</a></li>
		<?php if($ver_pagare){?>
        <li><a href="folio_pagare/folio_pagare_1.php?id_contrato=<?php echo $id_contrato;?>&TB_iframe=true&height=500&width=450" rel="sexylightbox">3.-Pagare</a></li>
		<?php }?>
      </ul>
    <li class="Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="a" width="15" height="14" /><a href="#" onclick="SALIR('destructor_sesion_finanzas.php?url=enrutador');"><strong>Volver al Men&uacute; </strong></a></li>
  </ul>
</div>
<div id="msj"><?php echo $msj;?></div>
</body>
</html>