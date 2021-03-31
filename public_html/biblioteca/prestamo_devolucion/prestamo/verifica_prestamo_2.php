<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Bibioteca_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$_SESSION["BIBLIOTECA"]["prestar"]=true;

$id_alumno=$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["id"];
$rut_alumno=$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["rut"];
$id_libro=$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["id_libro_a_prestar"];
$carrera=$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["carrera"];
$sede=$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["sede"];
$fecha_devolucion=$_GET["devolucion"];
$id_usuario_activo=$_SESSION["USUARIO"]["id"];
$fecha_actual=date("Y-m-d");

require("../../../../funciones/conexion_v2.php");
require("../../../../funciones/funcion.php");

$cons_2="SELECT nombre, editorial, numpresta FROM biblioteca WHERE id_libro='$id_libro' LIMIT 1";
if(DEBUG){ echo"->$cons_2<br>";}
$sqli_2=$conexion_mysqli->query($cons_2)or die($conexion_mysqli->error);
 while($rown = $sqli_2->fetch_assoc()) 
 { 
    $nomL=$rown["nombre"];
    $editorial=$rown["editorial"];
	$num_presta=$rown["numpresta"];
	$num_presta++;
} 
if($nomL=="")
{$nomL="No Registrado";}
if($editorial=="")
{$editorial="No Registrado";}

$sqli_2->free();
$conexion_mysqli->close();
?>
<html>
<head>
<title>Prestamo de Libros</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 110px;
}
#apDiv2 {
	position:absolute;
	width:60%;
	height:33px;
	z-index:2;
	left: 20%;
	top: 460px;
	text-align: center;
}
</style>
<script language="javascript">
function CONFIRMAR_PRESTAMO()
{
	c=confirm('Seguro(a) Desea Realizar Este Prestamo de Libro..');
	if(c)
	{
		document.getElementById('frm').submit();
	}
}
</script>
</head>
<body>
<h1 id="banner">Administrador -Biblioteca</h1>
<div id="link"><br>
<a href="../enrutador_main.php?destino=biblioteca" class="button">Volver a Biblioteca</a><br>
<br>
<a href="buscador_alumno/index.php?id_libro=<?php echo $id_libro;?>" class="button">Cambiar de Alumno</a></div>
<div id="apDiv1">
<form action="verifica_prestamo_3.php" method="post" enctype="multipart/form-data" name="frm" id="frm">
  <table width="50%" border="0" align="center">
  <thead>
	<tr>
        <th colspan="2">INFORMACION PRESTAMO DE LIBRO</th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td colspan="2">-&iquest;A quien?</td>
        </tr>
      <tr>
        <td><strong>Nombre:</strong></td>
        <td><?php echo $_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["nombre"]; ?></td>
    </tr>
      <tr>
        <td><strong>Apellido:</strong></td>
        <td><?php echo $_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["apellido"]; ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2">-&iquest;Que Libro?</td>
        </tr>
      <tr>
        <td><strong>Titulo:</strong></td>
        <td><?php echo $nomL; ?></td>
      </tr>
      <tr>
        <td><strong>Editorial:</strong></td>
        <td><?php echo $editorial; ?></td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2">- &iquest;Hasta Cuando?</td>
        </tr>
      <tr>
        <td><strong>F. Devoluci&oacute;n</strong></td>
        <td><?php echo fecha_format($fecha_devolucion);?><input name="fecha_devolucion" type="hidden" value="<?php echo $fecha_devolucion;?>"></td>
      </tr>
      </tbody>
    </table>
</form>
</div>
<div id="apDiv2"><a href="#" class="button_G" onClick="CONFIRMAR_PRESTAMO();">Prestar el Libro...</a>
</div>
</body>
</html>