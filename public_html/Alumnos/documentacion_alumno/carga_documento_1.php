<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Documentacion_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

$continuar=false;
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	if(isset($_SESSION["SELECTOR_ALUMNO"]["id"]))
	{
		$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
		if(is_numeric($id_alumno))
		{ $continuar=true;}
	}
}

$array_tipo_documento=array("carne", "licencia_educacion_media", "certificado_nacimiento", "comprobante_domicilio");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Documentacion de Alumno</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 163px;
}
#apDiv2 {
	position:absolute;
	width:200px;
	height:36px;
	z-index:2;
	left: 199px;
	top: 158px;
}
</style>
<script language="javascript">
function VERIFICAR()
{
	continuar=true;
	archivo=document.getElementById('archivo').value;
	
	if((archivo=="")||(archivo==" "))
	{
		continuar=false;
		alert("Debe Cargar un Archivo");
	}
	
	if(continuar)
	{
		c=confirm("¿Seguro(a) Desea Cargar este Documento...?");
		
		if(c){document.getElementById('frm').submit();}
	}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Carga Documentación Alumno 1</h1>
<div id="apDiv1">
<form action="carga_documento_2.php" method="post" enctype="multipart/form-data" name="frm" id="frm">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="2">Datos del Documento</th>
    </tr>
    </thead>
    <tr>
      <td width="16%">Tipo</td>
      <td width="84%"><label for="tipo"></label>
        <select name="tipo" id="tipo">
        <?php
        foreach($array_tipo_documento as $n=> $valor)
		{echo'<option value="'.$valor.'">'.$valor.'</option>';}
		?>
      </select></td>
    </tr>
    <tr>
      <td>Archivo</td>
      <td><div id="apDiv2"><a href="#" class="button_R" onclick="VERIFICAR();">Cargar</a></div>
        <label for="archivo"></label>
      <input type="file" name="archivo" id="archivo" /></td>
    </tr>
  </table>
</form>  
</div>
</body>
</html>