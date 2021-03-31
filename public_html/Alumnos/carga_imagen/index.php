<?php
 //-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="matricula";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//
$archivo="";
if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
{
	$action="carga_img.php";
	if(isset($_SESSION["SELECTOR_ALUMNO"]["imagen"]))
	{$nombre_imagen_actual=$_SESSION["SELECTOR_ALUMNO"]["imagen"];}
	else{ $nombre_imagen_actual="";}
	
	if(!empty($nombre_imagen_actual))
	{
		$path="../../CONTENEDOR_GLOBAL/img_alumnos/";
		$archivo=$path.$nombre_imagen_actual;
		
		$imagen_actual='<img src="'.$archivo.'" alt="Imagen Actual" name="actual_img" width="100" height="100" id="actual_img" />';
	}
	else
	{
		$imagen_actual="Sin Imagen";
	}
	
}
else
{ $action="";}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>carga imagen Perfil</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 58px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:26px;
	z-index:2;
	left: 30%;
	top: 359px;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	continuar=true;
	archivo=document.getElementById('archivo').value;
	if((archivo=="")||(archivo==" "))
	{ 
		alert('Primero seleccione un archivo de imagen');
		continuar=false;
	}
	if(continuar)
	{ 
		c=confirm('Seguro(a) Desea Cargar esta Imagen como Perfil');
		if(c){ document.getElementById('frm').submit();}
	}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Foto de Perfil</h1>
<div id="apDiv1">
<form action="<?php echo $action;?>" method="post" enctype="multipart/form-data" id="frm">
  <table width="100%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="2">Carga de Foto de Perfil de Alumno</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="82%">Foto de Perfil Actual</td>
      <td width="18%" rowspan="3"><a href="<?php echo $archivo;?>" title="click para ampliar"><?php echo $imagen_actual;?></a></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td height="32">&nbsp;</td>
      </tr>
    <tr>
      <td colspan="2">Nueva Foto </td>
      </tr>
    <tr>
      <td colspan="2"><label for="archivo"></label>
        <input type="file" name="archivo" id="archivo" /></td>
      </tr>
    <tr>
      <td colspan="2">Para Restaurar Imagen Click <a href="restaurar_imagen.php" class="button">aqui</a> (dejara en blanco la imagen)</td>
    </tr>
    <tr>
      <td colspan="2"><em>Formatos Compatibles*.jpg, *.jpeg, *.gif, *.png (Peso Maximo: 10Mb)</em></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><a href="#" class="button_R" onclick="CONFIRMAR();">Cargar Archivo</a></td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="apDiv2">
<?php
	if(isset($_GET["error"]))
	{
		$error=$_GET["error"];
		$img_ok='<img src="../../BAses/Images/ok.png" width="29" height="26" alt="ok" />';
		$img_error='<img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="X" />';
		switch($error)
		{
			case"C1":
				$msj="Fallo al intentar subir archivo, pruebe mas tarde...";
				$img=$img_error;
				break;	
			case"C2":
				$msj="Formato de archivo incompatible o peso superior a 10Mb...";
				$img=$img_error;
				break;	
			default:
				$msj="";
				$img='';
				break;			
		}
		
		echo $msj." ".$img;
	}
?>
</div>
</body>
</html>