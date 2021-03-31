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
?>
<html>
<head>
<title>Prestamo de Libros</title>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:332px;
	z-index:1;
	left: 5%;
	top: 103px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:32px;
	z-index:2;
	left: 30%;
	top: 472px;
	text-align: center;
}
</style>
</head>
<body>
<h1 id="banner">Administrador -Biblioteca</h1>
<?php 

  if(!isset($_SESSION["BIBLIOTECA"]["prestar"]))
  { $continuar=false;}
  else{ $continuar=$_SESSION["BIBLIOTECA"]["prestar"];}
  
  if(DEBUG){
	  		var_dump($_SESSION["BIBLIOTECA"]);
	  		 echo"CONTINUAR: $continuar<br>";
			}
  
$id_alumno=$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["id"];
$rut_alumno=$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["rut"];
$id_libro=$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["id_libro_a_prestar"];
$id_carrera=$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["id_carrera"];
$carrera=$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["carrera"];
$sede=$_SESSION["SELECTOR_ALUMNO_BIBLIOTECA"]["sede"];
$fecha_devolucion=$_POST["fecha_devolucion"];
$id_usuario_activo=$_SESSION["USUARIO"]["id"];
$fecha_actual=date("Y-m-d");

date_default_timezone_set('America/Santiago');//zona horaria
$fecha_generacion=date("Y-m-d H:i:s");

require("../../../../funciones/conexion_v2.php");

$cons_2="SELECT nombre, editorial, numpresta FROM biblioteca WHERE id_libro='$id_libro' LIMIT 1";
if(DEBUG){ echo"->$cons_2<br>";}
$result2=$conexion_mysqli->query($cons_2);
 while($rown = $result2->fetch_assoc()) 
 { 
    $nomL=$rown["nombre"];
    $editorial=$rown["editorial"];
	$num_presta=$rown["numpresta"];
	$num_presta++;
} 
if($nomL=="")
{
 $nomL="No Registrado";
}
if($editorial=="")
{
 $editorial="No Registrado";
}

if($continuar)
{
	if(!DEBUG){$_SESSION["BIBLIOTECA"]["prestar"]=false;}
$res="UPDATE  biblioteca SET prestado='S', id_alumno='$id_alumno', numpresta='$num_presta' WHERE id_libro=$id_libro LIMIT 1";
if(DEBUG){ echo"---> $res";}
else
{ $conexion_mysqli->query($res);}

 /////Registro ingreso///
		 include("../../../../funciones/VX.php");
		 $evento="Prestamo de Libro($id_libro) a id_alumno: $id_alumno Rut:$rut_alumno";
		 REGISTRA_EVENTO($evento);
		 /////////////////////// 
//mysql_free_result($result);

	//inserta registro nuevo
		$condicion="prestado";
		$campos="id_libro, id_alumno, rut, id_carrera, sede, condicion, fecha_registro, fecha_prestamo, fecha_devolucion, cod_user";
		$valores="'$id_libro', '$id_alumno', '$rut_alumno', '$id_carrera', '$sede', '$condicion', '$fecha_generacion', '$fecha_actual', '$fecha_devolucion', '$id_usuario_activo'";
		$cons_in="INSERT INTO biblioteca_registro ($campos) VALUES($valores)";
		if(DEBUG){ echo"<br>---> $cons_in<br>";}
		else{ $conexion_mysqli->query($cons_in)or die($conexion_mysqli->error);}
	//
$result2->free();
$conexion_mysqli->close();
?>
<div id="apDiv1">
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
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" ><div align="center" class="Estilo2">Libro Prestado <img src="../../../BAses/Images/ok.png"></div></td>
      </tr>
    </tbody>
    </table>
<?php
}
else
{ echo"Ya Fue Prestado el Libro...(Repeticion de Accion)";}
?> 
</div>
<div id="apDiv2"><a href="../enrutador_main.php?destino=biblioteca" class="button_G">Volver A Biblioteca</a></div>
</body>
</html>