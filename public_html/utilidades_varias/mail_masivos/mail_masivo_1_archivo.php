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
//////////////////////XAJAX/////////////////

if($_POST)
{
	require("../../../funciones/funciones_sistema.php");
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
	c=confirm('Seguro(a) Desea Enviar Email a destinatarios contenidos en archivo\n a continuacion los debe seleccionar..?');
	if(c){document.getElementById('frm').submit();}
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
<form action="mail_masivo_2_archivo.php" method="post" enctype="multipart/form-data" name="frm" id="frm">
  <table width="50%" border="1" align="center">
  <caption></caption>
  <thead>
    <tr>
      <th colspan="6"><span class="Estilo1">Carga de Archivo de destinatarios
          <input name="asunto_mensaje" type="hidden" id="asunto_mensaje" value="<?php echo $asunto_mensaje;?>" />
        <input type="hidden" name="cuerpo_mensaje" id="cuerpo_mensaje" value="<?php echo $cuerpo_mensaje;?>"/>
        <input name="archivo_adjunto" type="hidden" id="archivo_adjunto" value="<?php echo $archivo_adjunto;?>" />
      </span></th>
    </tr>
	</thead>
	<tbody>
    <tr class="odd">
      <td width="160">&nbsp;</td>
      <td width="171" colspan="5">&nbsp;</td>
    </tr>
    <tr class="odd">
      <td><label for="archivo">Archivo</label></td>
      <td colspan="5"><input type="file" name="archivo" id="archivo" /></td>
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
