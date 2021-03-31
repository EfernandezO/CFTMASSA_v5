<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Crea_funcionario_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<html>
<head>
<title>Guardando Docente</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
}
.Estilo1 {color: #0080C0}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
.Estilo2 {
	font-size: 18px;
	color: #FFFFFF;
}
.Estilo3 {color: #FFFFFF}
#link {
	text-align: right;
	padding-right: 10px;
}
-->
</style>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
</head>

<body>
<h1 id="banner">Administrador - Men&uacute; Docentes </h1>
<div id="link">
  <p><br>
    <span class="Estilo1"><a href="../lista_funcionarios.php" class="button">Volver al Menu </a></span></p>
</div>
<div id="Layer7" style="position:absolute; left:5%; top:144px; width:90%; height:23px; z-index:43"> 
  <?php 
if($_POST)
{  
    require("../../../funciones/conexion_v2.php");
    require("../../../funciones/funcion.php");
	require("../../../funciones/funciones_varias.php");
	
	$id_usuario_activo=$_SESSION["USUARIO"]["id"];
	$fecha_actual=date("Y-m_d");
	
	$coincidencias=0;
	
	$sexo=mysqli_real_escape_string($conexion_mysqli, $_POST["sexo"]);
   $nombres=str_inde($_POST['nombres']); 
   $nombres=ucwords(strtolower($nombres));
   $apellido_P=str_inde($_POST['apellido_P']);
   $apellido_P=ucwords(strtolower($apellido_P));
    $apellido_M=str_inde($_POST['apellido_M']);
   $apellido_M=ucwords(strtolower($apellido_M));
   
   
   $fecha_nacimiento=$_POST["fecha_nacimiento"];
   $fecha_ingreso_institucion=$_POST["fecha_ingreso_institucion"];

   $rut=str_inde($_POST['rut']); 
   $rut=strtolower($rut);
   //---------------------------------------------------//
   
   $email=str_inde($_POST['correo'], "");
   $email_personal=str_inde($_POST['email_personal'],"");
   $fono=str_inde($_POST['fono']);
   $direccion=str_inde($_POST['direccion']);
   $sede=str_inde($_POST['fsede']);
   $ciudad=str_inde($_POST['ciudad']);
   
   $clave="Ma_".$rut;
   $claveCodificada=md5($clave);
   $nivel=str_inde($_POST['nivel']);
   $cuenta_docente=$_POST["cuenta_docente"];
   $organizacion=strtolower($_POST["organizacion"]);//agregado
 	///////////verificar que no exista en la BBDD////////////
	
	$continuar=false;
	if(RUT_OK($rut))
	{
		$rut_disponible=RUT_DISPONIBLE($rut, "personal");
		if($rut_disponible){$continuar=true;}
	}

	
	if($continuar){
		$result="INSERT INTO personal (rut, nombre, apellido_P, apellido_M, sexo, email, email_personal, fono, direccion, ciudad, clave, nivel, fecha_nacimiento, fecha_ingreso_institucion, sede, cuenta_docente, organizacion, fecha_generacion, cod_user) VALUES ('$rut', '$nombres', '$apellido_P', '$apellido_M', '$sexo', '$email', '$email_personal', '$fono', '$direccion', '$ciudad', '$claveCodificada', '$nivel', '$fecha_nacimiento', '$fecha_ingreso_institucion', '$sede', '$cuenta_docente', '$organizacion', '$fecha_actual', '$id_usuario_activo')";
   
  if(DEBUG){echo"--->$result<br>";}
  else
  {
	   if($conexion_mysqli->query($result))
	   {
			 /////Registro ingreso///
			 include("../../../funciones/VX.php");
			 $evento="Crea Nuevo Funcionario Rut($rut) Privilegio($nivel)";
			 REGISTRA_EVENTO($evento);
			 ///////////////////////
	   } 
	   else
	   {echo $conexion_mysqli->error;}
  }
 
echo'<table width="60%" border="0" align="center">
	<thead>
      <tr>
        <th colspan="2"><div align="center" class="Estilo2">Informacion</div></th>
      </tr>
    </thead>
	  <tbody>
      <tr>
        <td ><strong>Nombre:</strong></td>
        <td >'.$nombres.'</td>
      </tr>
      <tr>
        <td ><strong>Apellido:</strong></td>
        <td >'.$apellido_P.' '.$apellido_M.'</td>
      <tr>
        <td colspan="2">Fue Grabado Exitosamente como Docente<img src="../../BAses/Images/ok.png" width="29" height="26" alt="ok"><br>Su clave por defecto para intranet es: '.$clave.'</td>
      </tr>
	  </tbody>
    </table>
 </div>';
  }
  else{ echo"No se puede continuar, problemas con Rut...<br>";}
   $conexion_mysqli->close();
}
else
{
	echo"Sin Datos<br>";
}
?>
 </div>
</body>
</html>