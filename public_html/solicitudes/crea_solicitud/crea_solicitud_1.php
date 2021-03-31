<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("SOLICITUDES->verCertificados");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$continuar=false;
$privilegio=$_SESSION["USUARIO"]["privilegio"];

switch($privilegio)
{
	case"ALUMNO":
		break;
	default:	
		if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
		{
			$continuar=true;
			$tipo_receptor="alumno";
			
			$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
			$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
			$rut=$_SESSION["SELECTOR_ALUMNO"]["rut"];
			$nombre=$_SESSION["SELECTOR_ALUMNO"]["nombre"];
			$apellido=$_SESSION["SELECTOR_ALUMNO"]["apellido"];
			$carrera=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
			$sede=$_SESSION["SELECTOR_ALUMNO"]["sede"];
			$jornada_alumno=$_SESSION["SELECTOR_ALUMNO"]["jornada"];
			$nivel_alumno=$_SESSION["SELECTOR_ALUMNO"]["nivel"];
			$situacion_financiera_real="";
		}
		else
		{ $continuar=false;}
}

$array_certificados=array("alumno_regular", "copia_titulo", "certificado_titulo", "concentracion_notas", "concentracion_notas_HRS", "egreso", "plan_curricular", "titulo_en_tramite");

 //////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("crea_solicitud_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"VERIFICA_SOLICITUD");
//////////DEBUG////////////////
require("../../../funciones/funciones_sistema.php");
$year_actual=date("Y");
$mes_actual=date("m");
if($mes_actual>=8){ $semestre_actual=2;}
else{ $semestre_actual=1;}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/hint.css-master/hint.css"/>
<title>Creacion Solicitud</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:52px;
	z-index:1;
	left: 5%;
	top: 44px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:115px;
	z-index:2;
	left: 5%;
}
#apDiv3 {
	position:absolute;
	width:40%;
	height:24px;
	z-index:2;
	left: 30%;
	top: 272px;
}
#apDiv4 {
	position:absolute;
	width:90%;
	height:48px;
	z-index:3;
	left: 5%;
	top: 310px;
}
</style>
<?php $xajax->printJavascript(); ?> 
</head>

<body>
<h1 id="banner">Administrador - Crea Solicitudes</h1>
<div id="apDiv1">
  <form action="crea_solicitud_2.php" method="post" enctype="multipart/form-data" name="frm" id="frm">
  <table width="90%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="4">Solicitud</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="29%">Tipo Solicitud</td>
      <td colspan="3">
        <select name="tipo" id="tipo">
          <option value="certificado">certificado</option>
        </select></td>
    </tr>
    <tr>
      <td>Categoria</td>
      <td colspan="3">
        <select name="categoria" id="categoria">
        <?php
        foreach($array_certificados as $n => $valor)
		{echo'<option value="'.$valor.'">'.$valor.'</option>';}
		?>
        </select></td>
    </tr>
    <tr>
      <td>Semestre</td>
      <td width="3%"><?php echo CAMPO_SELECCION("semestre", "semestre", $semestre_actual, false);?></td>
      <td width="4%">Año</td>
      <td width="64%"><?php echo CAMPO_SELECCION("year", "year", $year_actual, false);?></td>
    </tr>
    <tr>
      <td>Para Presentar a:</td>
      <td colspan="3"><label for="observacion"></label>
        <input name="observacion" type="text" id="observacion" size="60" /></td>
    </tr>
    <tr>
      <td>Adjuntar comprobante pago</td>
      <td colspan="3"><label for="archivo"></label>
        <input type="file" name="archivo" id="archivo" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="3"></td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="apDiv3"><a href="#"  class="button_R hint--top  hint--error" data-hint="Para que un Certificado pueda ser Emitido debe ser previamente pagado" onclick="xajax_VERIFICA_SOLICITUD(xajax.getFormValues('frm')); return false;">Crear Solicitud</a></div>
<div id="apDiv4"><strong>Importante</strong><br /><ul>
  <li>Si desea un Certificado, debe realizar una solicitud, esta debe ser autorizada para emitir el Certificado</li>
  <li>Para autorizar una solicitud esta debe ser pagada, lo cual puede realizar directamente en nuestras oficinas o depositando en nuestra cuenta corriente del <strong>Banco Santander 65-41007-9</strong>.</li>
  <li>Si ud. realiza el dep&oacute;sito debe adjuntar el comprobante de pago para autorizar la solicitud.</li>
  <li>Para cualquier consulta escribanos a secretaria@cftmass.cl</li>
</ul>
</div>
</body>
</html>