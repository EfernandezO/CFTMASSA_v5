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
//////////////////////XAJAX/////////////////
$yearActual=date("Y");
require("../../../funciones/funciones_sistema.php");
$continuar=false;
$archivo_cargado=false;
if($_POST)
{
	
	$continuar=true;
	$archivo_cargado=false;
	$asunto_mensaje=$_POST["asunto_mensaje"];
	$cuerpo_mensaje=$_POST["cuerpo_mensaje"];
	
	
	if($_FILES)
	{
		if(DEBUG){ echo"Archivo Enviado<br>";}
		$destino="../../CONTENEDOR_GLOBAL/archivos_temporales";
		if(DEBUG){ echo"RUTA: $destino<br>";}
		
		list($archivo_cargado, $archivo_adjunto)=CARGAR_ARCHIVO($_FILES['archivo_adjunto'], $destino, "TMP_");
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion_2.php");?>
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
	top: 101px;
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
function CONFIRMAR()
{
	<?php if($continuar){?>
	c=confirm('Seguro(a) Desea Enviar Email a alumnos Seleccionados..?');
	if(c){document.getElementById('frm').submit();}
	<?php }else{?>
	alert('no se puede continuar, sin datos del email');
	<?php }?>
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
<div id="link"><br><a href="mail_masivo_0.php" class="button">Volver al menu Principal </a>
  </div>
<div id="Layer1">
<form action="mail_masivo_2_alumno.php" method="post" enctype="multipart/form-data" name="frm" id="frm">
  <table width="50%" border="1" align="center">
  <caption></caption>
  <thead>
    <tr>
      <th colspan="6"><span class="Estilo1">Busqueda de Alumnos 
        <input name="asunto_mensaje" type="hidden" id="asunto_mensaje" value="<?php echo $asunto_mensaje;?>" />
        <input type="hidden" name="cuerpo_mensaje" id="cuerpo_mensaje" value="<?php echo $cuerpo_mensaje;?>"/>
        <input name="archivo_adjunto" type="hidden" id="archivo_adjunto" value="<?php echo $archivo_adjunto;?>" />
      </span></th>
    </tr>
	</thead>
	<tbody>
    <tr class="odd">
      <td width="160"><span class="Estilo1">Sede</span></td>
      <td colspan="5"><?php
	  echo CAMPO_SELECCION("sede","sede");
	  ?></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Carrera</span></td>
      <td colspan="5">
      <div id="div_carrera">
	<?php echo CAMPO_SELECCION("id_carrera","carreras","",true);?>
      </div>  
        </td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">A&ntilde;o Ingreso </span></td>
      <td colspan="5"><?php echo CAMPO_SELECCION("ingreso","year",$yearActual,true);?></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Jornada</span></td>
      <td colspan="5"><?php echo CAMPO_SELECCION("jornada","jornada","",true);?></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Grupo</span></td>
      <td colspan="5"><?php echo CAMPO_SELECCION("grupo","grupo",$yearActual);?></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Nivel Actual</span></td>
      <td width="42">1
        <br />
        <input name="nivel[]" type="checkbox" id="nivel1" value="1" checked="checked" />
        <label for="nivel1"></label></td>
      <td width="36">2
        <br />        <input name="nivel[]" type="checkbox" id="nivel2" value="2" checked="checked" /></td>
      <td width="32">3<br />        <input name="nivel[]" type="checkbox" id="nivel3" value="3" checked="checked" /></td>
      <td width="28">4<br />        <input name="nivel[]" type="checkbox" id="nivel4" value="4" /></td>
      <td width="33">5<br />        <input name="nivel[]" type="checkbox" id="nivel5" value="5" /></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Estado Academico</span></td>
      <td colspan="5"><select name="estado" id="estado">
        <option value="V" selected="selected">Vigente</option>
        <option value="T">Titulados</option>
        <option value="A">Todos</option>
        <option value="E">Egresado</option>
        <option value="P">Postergado</option>
        <option value="R">Retirado</option>
        <option value="E">Eliminado</option>
        </select></td>
    </tr>
    <tr class="odd">
      <td>Estado Financiero</td>
      <td colspan="5"><label for="estado_financiero"></label>
        <select name="estado_financiero" id="estado_financiero">
          <option value="morosos">morosos</option>
          <option value="al_dia">al dia</option>
          <option value="todos" selected="selected">Todos</option>
          </select></td>
    </tr>
    </tbody>
	<tfoot>
	  <tr>
	    <td colspan="6"><div align="right">
	      <input type="button" name="Submit" value="Continuar"  onclick="CONFIRMAR();"/>
	      </div></td>
	    </tr>
	  </tfoot>
  </table>
 </form> 
</div>
</body>
</html>
