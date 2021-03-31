<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("carga_descarga_tareas_docente_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
$year_actual=date("Y");
$mes_actual=date("m");
if($mes_actual>=8){$semestre_actual=2;}
else{ $semestre_actual=1;}

$sede_actual=$_SESSION["USUARIO"]["sede"];
 require("../../../../funciones/funciones_sistema.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>carga trabajo</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 86px;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	continuar=true;
	
	nombre=document.getElementById('nombre').value;
	descripcion=document.getElementById('descripcion').value;
	archivo=document.getElementById('archivo').value;
	
	if((nombre=="")||(nombre==" "))
	{
		continuar=false;
		alert('Ingrese Nombre');
	}
	
	if((descripcion=="")||(descripcion==" "))
	{
		continuar=false;
		alert('Ingrese descripcion');
	}
	
	if((archivo=="")||(archivo==" "))
	{
		continuar=false;
		alert('Ingrese Archivo');
	}
	
	
	if(continuar)
	{
		c=confirm('Seguro(a) desea cargar este archivo...?');
		if(c)
		{
			document.getElementById('frm').submit();
		}
	}
}
</script>
</head>

<body>
<h1 id="banner">Carga-Descarga Tareas Docente</h1>
<div id="apDiv1">
<form action="carga_trabajo_2.php" method="post" enctype="multipart/form-data" id="frm">
  <table width="90%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="2">Carga Trabajo</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>Nombre</td>
      <td><label for="nombre"></label>
        <input type="text" name="nombre" id="nombre" /></td>
      </tr>
    <tr>
      <td>Descripcion</td>
      <td><textarea name="descripcion" cols="50" rows="5" id="descripcion"></textarea></td>
    </tr>
    <tr>
      <td>Archivo</td>
      <td><label for="archivo"></label>
        <input type="file" name="archivo" id="archivo" />
        <br />
        (&quot;pdf&quot;, &quot;zip&quot;, &quot;rar&quot;, &quot;doc&quot;, &quot;docx&quot;, &quot;xls&quot;, &quot;xlsx&quot;)</td>
    </tr>
    <tr>
      <td>Sede</td>
      <td><?php echo CAMPO_SELECCION("sede","sede", $sede_actual,false);?></td>
    </tr>
    <tr>
      <td>Semestre</td>
      <td><?php echo CAMPO_SELECCION("semestre","semestre", $semestre_actual,false);?></td>
    </tr>
    <tr>
      <td>Year</td>
      <td><?php echo CAMPO_SELECCION("year","year", $year_actual,false);?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><a href="#" class="button_R" onclick="CONFIRMAR();">Cargar Trabajo</a></td>
      </tr>
    </tbody>
  </table>
 
  </form>
</div>
</body>
</html>