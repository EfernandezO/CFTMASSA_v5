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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Email Masivo 1</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 88px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:27px;
	z-index:2;
	left: 30%;
	top: 462px;
	text-align: center;
}
</style>
<script language="javascript">
function VERIFICAR()
{
	continuar=true;
	asunto=document.getElementById('asunto_mensaje').value;
	cuerpo_mensaje=document.getElementById('cuerpo_mensaje').value;
	
	if((asunto=="")||(asunto==" "))
	{
		alert("Ingrese Asunto de Mensaje");
		continuar=false;
	}
	if((cuerpo_mensaje=="")||(cuerpo_mensaje==" "))
	{
		alert("Ingrese Cuerpo de Mensaje");
		continuar=false;
	}
	
	if(continuar)
	{
		document.getElementById('frm').submit();
	}
}
function CAMBIAR_DESTINO(tipo_destinatario)
{
	switch(tipo_destinatario)
	{
		case "alumnos":
			document.getElementById('frm').action="mail_masivo_1_alumno.php";
			break;
		case "docentes":
			document.getElementById('frm').action="mail_masivo_1_docente.php";
			break;
		case "administrativos":
			document.getElementById('frm').action="mail_masivo_1_administrativo.php";
			break;
		case "archivo":
			document.getElementById('frm').action="mail_masivo_1_archivo.php";
			break;
	}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Envio Masivo Email 1/4</h1>
<div id="link"><br><a href="../../Administrador/ADmenu.php" class="button">Volver al menu</a>
</div>
<div id="apDiv1">
<form action="mail_masivo_1_alumno.php" method="post" enctype="multipart/form-data" id="frm">
  <table width="60%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="2">Redaccion de Mensaje</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td align="center">Destinatarios</td>
      <td align="center"><label for="tipo_destinatario"></label>
        <select name="tipo_destinatario" id="tipo_destinatario" onchange="CAMBIAR_DESTINO(this.value);">
          <option value="alumnos" selected="selected">Alumnos</option>
          <option value="docentes">Docentes</option>
          <option value="administrativos">Administrativos</option>
           <option value="archivo">archivo</option>
        </select></td>
    </tr>
    <tr>
      <td align="center">Asunto</td>
      <td align="center"><label for="asunto_mensaje"></label>
        <input name="asunto_mensaje" type="text" id="asunto_mensaje" size="45" /></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><label for="cuerpo_mensaje"></label>
        <textarea name="cuerpo_mensaje" cols="50" rows="10" id="cuerpo_mensaje"></textarea></td>
    </tr>
    <tr>
      <td colspan="2"><label for="archivo_adjunto"></label>
        <input type="file" name="archivo_adjunto" id="archivo_adjunto" /></td>
    </tr>
    <tr>
      <td colspan="2">*si envia a mas de 30 usuarios a la vez, realice el proceso cuando tenga baja actividad en el sistema. proceso lento.</td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="apDiv2"><a href="#" class="button_G" onclick="VERIFICAR();">Continuar Paso 2</a></div>
</body>
</html>