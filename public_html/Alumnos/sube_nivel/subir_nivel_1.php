<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Subir _de_nivel_A_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$continuar=false;
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
	{ $continuar=true;}
}
///***************************************//
if($continuar)
{
	$rut_alumno=$_SESSION["SELECTOR_ALUMNO"]["rut"];
	$alumno=$_SESSION["SELECTOR_ALUMNO"]["nombre"]." ".$_SESSION["SELECTOR_ALUMNO"]["apellido"];;
	$carrera=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
	$nivel_actual=$_SESSION["SELECTOR_ALUMNO"]["nivel"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	
	$nuevo_nivel=$nivel_actual+1;
	
	if(($nuevo_nivel>0)and($nuevo_nivel<=5))
	{
		$action="subir_nivel_2.php";
		$funcion_js='function CONFIRMAR()
						{
							c=confirm("Seguro(a) Quiere Subir de Nivel a este Alumno\n se Verificaran sus Notas");
							if(c)
							{ document.getElementById(\'frm\').submit();}
						}';
	}
	else
	{
		$action="";
		$funcion_js='function CONFIRMAR()
						{
							alert("No se puede Subir de Nivel, Fuera de Rango");
						}';
	}
}
else
{ header("location: ../../buscador_alumno_BETA/HALL/index.php");}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>Alumno | Subir Nivel</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 102px;
	text-align: center;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:35px;
	z-index:2;
	left: 5%;
	top: 364px;
	text-align: center;
}
</style>
<script language="javascript">
<?php echo $funcion_js;?>
</script>
</head>

<body>
<h1 id="banner">Administrador - Subir Nivel Alumno</h1>
<div id="link"><br>
<a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver a Seleccion </a></div>
<div id="apDiv1">
<form action="<?php echo $action;?>" method="post" id="frm">
  <table width="50%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="2">Datos Alumno</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="31%">Rut</td>
      <td width="69%"><?php echo $rut_alumno;?></td>
    </tr>
    <tr>
      <td>Alumno</td>
      <td><?php echo $alumno;?></td>
    </tr>
    <tr>
      <td>Carrera</td>
      <td><?php echo "$carrera ($id_carrera)";?></td>
    </tr>
    <tr>
      <td>Nivel Actual</td>
      <td><?php echo $nivel_actual;?></td>
    </tr>
    </tbody>
  </table>
  <p>&nbsp;</p>
  <p><a href="#" class="button_G" onclick="CONFIRMAR();">Subir a Nivel <?php echo $nuevo_nivel;?></a><br />
  </p>
  </form>
</div>
<div id="apDiv2">Sube de nivel al alumno seleccionado<br />
  verificando previamente, que no tenga mas de 3 ramos del <br />
  nivel actual REPROBADOS.
</div>
</body>
</html>