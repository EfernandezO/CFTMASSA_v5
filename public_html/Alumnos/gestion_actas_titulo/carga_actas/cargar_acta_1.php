<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("gestion_actas_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	require("../../../../funciones/funciones_sistema.php"); 
	
	
	$sede_actual=$_SESSION["USUARIO"]["sede"];
	$year_actual=date("Y");
	$mes_actual=date("m");
	
	if($mes_actual>=8){ $semestre_actual=2;}
	else{ $semestre_actual=1;}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php"); ?>
<title>Carga Actas</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
 
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 97px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:44px;
	z-index:2;
	left: 5%;
	top: 442px;
	text-align: center;
}
</style>
<script language="javascript" type="text/javascript">
function CONFIRMAR()
{
	continuar=true;
	 extensiones_permitidas = new Array(".jpg", ".pdf"); 
	archivo=document.getElementById('archivo').value;
	extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase(); 
	if((archivo=="")||(archivo==" "))
	{
		continuar=false;
		alert("Seleccione un archivo antes de continuar");
	}
	else
	{
		 archivo_permitido = false;
		  for (var i = 0; i < extensiones_permitidas.length; i++) {
			 if (extensiones_permitidas[i] == extension) {
			 archivo_permitido = true;
			 break;
			 }
		  } 
	}
	
	
	
	if(continuar)
	{
		if(archivo_permitido)
		{document.getElementById('frm').submit();}
		else{ alert("el tipo de archivo "+extension+" No esta permitido");}
	}
	
}
</script>
</head>

<body>

<h1 id="banner">Administrador - Actas</h1>
<div id="apDiv1">
	<form action="cargar_acta_2.php" method="post" enctype="multipart/form-data" id="frm">
    <br>
    <table width="50%" align="center">
    <thead>
    	<tr>
        	<th colspan="5">Datos del Acta</th>
        </tr>
    </thead>
    <tbody>
    	<tr>
        	<td colspan="5"></td>
        </tr>
    	<tr>
    	  <td>Sede</td>
    	  <td><?php echo CAMPO_SELECCION("sede","sede",$sede_actual,false);?></td>
    	  <td colspan="3">&nbsp;</td>
  	  </tr>
    	<tr>
    	  <td>Carrera</td>
    	  <td colspan="4"><?php echo CAMPO_SELECCION("id_carrera","carreras","",false);?></td>
   	    </tr>
    	<tr>
    	  <td>Jornada</td>
    	  <td colspan="2"><?php echo CAMPO_SELECCION("jornada","jornada","",false);?></td>
    	  <td>Nivel</td>
    	  <td><?php echo CAMPO_SELECCION("nivel", "niveles_academicos","",false);?></td>
  	    </tr>
    	<tr>
    	  <td width="21%">Semestre</td>
    	  <td width="15%"><?php echo CAMPO_SELECCION("semestre","semestre",$semestre_actual,false);?></td>
    	  <td width="9%">Año</td>
    	  <td width="55%" colspan="2"><?php echo CAMPO_SELECCION("year","year",$year_actual,false);?></td>
  	    </tr>
    	<tr>
    	  <td colspan="2">Tipo Acta</td>
    	  <td colspan="3"><label for="tipo_acta"></label>
    	    <select name="tipo_acta" id="tipo_acta">
    	      <option value="semestral" selected="selected">semestral</option>
    	      <option value="titulo">titulo</option>
          </select></td>
  	  </tr>
    	<tr>
    	  <td colspan="2">&nbsp;</td>
    	  <td colspan="3">&nbsp;</td>
  	  </tr>
    	<tr>
    	  <td colspan="2">Archivo</td>
    	  <td colspan="3"><label for="archivo"></label>
   	      <input type="file" name="archivo" id="archivo" /></td>
  	  </tr>
    	<tr>
    	  <td colspan="2">Observacion</td>
    	  <td colspan="3"><label for="observacion"></label>
   	      <input name="observacion" type="text" id="observacion" size="50" /></td>
  	  </tr>
    </tbody>
    </table>
    </form>
</div>
<div id="apDiv2"><a href="#" class="button_G" onclick="CONFIRMAR();">GRABAR ACTA</a></div>
</body>
</html>