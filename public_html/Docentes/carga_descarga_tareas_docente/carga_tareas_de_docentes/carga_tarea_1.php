<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG", true);
//-----------------------------------------//

 require("../../../../funciones/funciones_sistema.php");
 require("../../../../funciones/conexion_v2.php");
 
 $continuar=false;
 $id_trabajo=0;
 
 if(isset($_GET["id_trabajo"]))
 { $id_trabajo=mysqli_real_escape_string($conexion_mysqli, $_GET["id_trabajo"]); $continuar=true;}
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
<h1 id="banner">Carga Tareas Docente</h1>
<div id="apDiv1">
<?php if($continuar){?>
<form action="carga_tarea_2.php" method="post" enctype="multipart/form-data" id="frm">
  <table width="90%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="2">Carga Trabajo<input name="id_trabajo" type="hidden" value="<?php echo $id_trabajo;?>" /></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>Nombre</td>
      <td><label for="nombre"></label>
        <input type="text" name="nombre" id="nombre" /> 
        (opcional)</td>
      </tr>
    <tr>
      <td>Descripcion</td>
      <td><input type="text" name="descripcion" id="descripcion" />
      (opcional)</td>
    </tr>
    <tr>
      <td>Archivo</td>
      <td><label for="archivo"></label>
        <input type="file" name="archivo" id="archivo" />
        <br />
        (&quot;pdf&quot;, &quot;zip&quot;, &quot;rar&quot;, &quot;doc&quot;, &quot;docx&quot;, &quot;xls&quot;, &quot;xlsx&quot;)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><a href="#" class="button_R" onclick="CONFIRMAR();">Cargar Tarea</a></td>
      </tr>
    </tbody>
  </table>
 
  </form>
  <?php }else{ echo"Falta datos del trabajo de origen...";}?>
</div>
</body>
</html>