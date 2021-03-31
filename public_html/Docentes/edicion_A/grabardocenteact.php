<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Funcionarios->Edicion Datos V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<html>
<head>
<title>Docentes Modificacion</title>
<?php include("../../../funciones/codificacion.php");?>

<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
}
-->
</style>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:69px;
	z-index:46;
	left: 5%;
	top: 162px;
}
-->
</style>
</head>
<body>
<h1 id="banner">Funcionarios - Edici&oacute;n de Datos </h1>
<div id="link"><br></div>
<div id="apDiv1">
  <?php 
  //echo"inicio";
  if($_POST)
  {
  	//var_export($_POST);
 	require('../../../funciones/conexion_v2.php');
	
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$id=mysqli_real_escape_string($conexion_mysqli, $_POST["id_funcionario"]);
	$rut=trim(strtoupper($_POST["rut"]));
	$nombre=ucwords(strtolower($_POST["nombres"]));
		$apellido_P=ucwords(strtolower($_POST["apellido_P"]));
		$apellido_M=ucwords(strtolower($_POST["apellido_M"]));

	$fn_dia=mysqli_real_escape_string($conexion_mysqli, $_POST["fn_dia"]);
	$fn_mes=mysqli_real_escape_string($conexion_mysqli, $_POST["fn_mes"]);
	$fn_year=mysqli_real_escape_string($conexion_mysqli, $_POST["fn_year"]);
	
	$fecha_nacimiento=$fn_year."-".$fn_mes."-".$fn_dia;
	$fecha_ingreso_institucion=$_POST["fecha_ingreso_institucion"];	
	$sexo=mysqli_real_escape_string($conexion_mysqli, $_POST["sexo"]);
		
	$cuenta_docente=$_POST["cuenta_docente"];
	$fono=mysqli_real_escape_string($conexion_mysqli, $_POST["fono"]);
	$direccion=mysqli_real_escape_string($conexion_mysqli, $_POST["direccion"]);
	$ciudad=mysqli_real_escape_string($conexion_mysqli, $_POST["ciudad"]);
	
	$nivel=mysqli_real_escape_string($conexion_mysqli, $_POST["nivel"]);
	$organizacion=mysqli_real_escape_string($conexion_mysqli, $_POST["organizacion"]);//agregado
	$con_acceso=mysqli_real_escape_string($conexion_mysqli, $_POST["con_acceso"]);
	
	$sede=mysqli_real_escape_string($conexion_mysqli, $_POST["fsede"]);
	$correo=trim(mysqli_real_escape_string($conexion_mysqli, $_POST["correo"]));
	$email_personal=trim(mysqli_real_escape_string($conexion_mysqli, $_POST["email_personal"]));
	
	$nick=mysqli_real_escape_string($conexion_mysqli, $_POST["nick"]);
	if(empty($nick)){ $nick="user_".$id;}
	////////////////////
	
	///////////////
	$caja_asignada=mysqli_real_escape_string($conexion_mysqli, $_POST["caja_asignada"]);
	$caja_asignada=strtoupper($caja_asignada);
	
	/////////////////////////////
$Ucons="UPDATE  personal SET con_acceso='$con_acceso', rut='$rut', nombre='$nombre', apellido_P='$apellido_P', apellido_M='$apellido_M', fono='$fono', direccion='$direccion',ciudad='$ciudad', sede='$sede', email='$correo', email_personal='$email_personal', sexo='$sexo', fecha_nacimiento='$fecha_nacimiento', fecha_ingreso_institucion='$fecha_ingreso_institucion', cuenta_docente='$cuenta_docente', nivel='$nivel', organizacion='$organizacion', caja_asignada='$caja_asignada', nick='$nick' WHERE id=$id LIMIT 1";

	if(DEBUG){echo"<br><br>---->$Ucons <br>";}
	
  if($conexion_mysqli->query($Ucons))
  {
	  include("../../../funciones/VX.php");
	  $evento="Modifica Datos Funcionario id_funcionario: $id";
	  REGISTRA_EVENTO($evento);
	  
	  $descripcion="Modificacion de Datos Generales realizada por id_usuario: $id_usuario_actual";
	  REGISTRO_EVENTO_FUNCIONARIO($id, "notificacion", $descripcion);
  }
  else
  {
	  echo"ERROR:".$conexion_mysqli->error;
  }
  $conexion_mysqli->close();
 ?>
<table width="50%" border="1" align="center">
<thead>
  <tr>
    <th>INFORMACION</th>
    </tr>
    </thead>
    <tbody>
  <tr>
    <td height="59">El Funcionario ha Sido Modificado...<img src="../../BAses/Images/ok.png" width="29" height="26"></td>
    </tr>
  <tr>
    <td><a href='../lista_funcionarios.php' class='button'>Regresar</a></td>
    </tr>
    </tbody>
</table>

<?php
}
else
{
	echo"no post <br>";
}
$conexion_mysqli->close();
?> 
</div>
</body>
</html>