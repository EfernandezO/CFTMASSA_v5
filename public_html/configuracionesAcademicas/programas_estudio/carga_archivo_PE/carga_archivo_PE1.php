<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->PROGRAMAS_ESTUDIO_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

if(isset($_GET["id_carrera"]))
{
	$id_carrera=$_GET["id_carrera"];
	if(is_numeric($id_carrera))
	{ $continuar=true;}
	else
	{ $continuar=false;}
}
else
{ $continuar=false;}

if(isset($_GET["cod_asignatura"]))
{
	$cod_asignatura=$_GET["cod_asignatura"];
	if(is_numeric($cod_asignatura))
	{ $continuar_2=true;}
	else
	{ $continuar_2=false;}
}
else
{ $continuar_2=false;}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php require("../../../../funciones/codificacion.php");?>
<title>Programa de Estudios</title>
	<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
    <link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:103px;
	z-index:1;
	left: 5%;
	top: 75px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 244px;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	continuar=true;
	archivo=document.getElementById('archivo').value;
	
	if((archivo=="")||(archivo==" "))
	{
		continuar=false;
		alert('Seleccione un Archivo');
	}
	if(continuar)
	{document.getElementById('frm').submit();}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Programa de Estudios</h1>
<div id="apDiv1">
<?php if($continuar and $continuar_2){?>
<form action="carga_archivo_PE2.php" method="post" enctype="multipart/form-data" id="frm">
  <table width="70%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="2">Carga de Archivo
        <input name="id_carrera" type="hidden" id="id_carrera" value="<?php echo $id_carrera;?>" />
        <input name="cod_asignatura" type="hidden" id="cod_asignatura" value="<?php echo $cod_asignatura;?>" /></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="38%">Archivo</td>
      <td width="62%"><label for="archivo"></label>
        <input type="file" name="archivo" id="archivo" /></td>
    </tr>
    <tr>
      <td colspan="2"><a href="#" class="button_R" onclick="CONFIRMAR();">Cargar Archivo</a></td>
    </tr>
    </tbody>
  </table>
 </form> 
<?php }?> 
</div>
<div id="apDiv2">
<?php
if($continuar and $continuar_2)
{
	require("../../../../funciones/conexion_v2.php");
	
	$cons="SELECT * FROM programa_estudio_archivo WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura'";
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_archivos=$sqli->num_rows;
	
	$ruta="../../../CONTENEDOR_GLOBAL/programa_estudios/";
	if($num_archivos>0)
	{
		$aux=0;
		while($A=$sqli->fetch_assoc())
		{
			$aux++;
			$A_id_programa_archivo=$A["id_programa_archivo"];
			$A_archivo=$A["archivo"];
			
			echo $aux.'-> <a href="'.$ruta.$A_archivo.'" target="_blank">'.$A_archivo.'</a> [<a href="../elimina_archivo_PE/elimina_archivo_PE.php?id_programa_archivo='.$A_id_programa_archivo.'&id_carrera='.$id_carrera.'&cod_asignatura='.$cod_asignatura.'"> Eliminar</a>]<br>';
		}
	}
	else
	{echo"Sin Archivos Cargados...";}
	
	$sqli->free();
	
	$conexion_mysqli->close();
	mysql_close($conexion);
}
else
{
	echo"Sin Archivos Cargados...";
}
?>
</div>
</body>
</html>