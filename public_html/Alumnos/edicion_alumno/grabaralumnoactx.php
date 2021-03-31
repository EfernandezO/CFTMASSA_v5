<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="ALUMNO";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", false);
?>
<html>
<head>
<title>Final - Mis Datos</title>
<?php include("../../../funciones/codificacion.php"); ?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
}
-->
</style></head>

<body>
<h1 id="banner">Alumno - Mis Datos</h1>
<div id="Layer4" style="position:absolute; left:0px; top:76px; width:100%; height:44px; z-index:44"> 
  <div align="center"><font color="#0000CC"><b>Registro de Personal<br>
    C.F.T. Massachusetts Talca - Linares</b></font></div>
</div>
<div id="Layer1" style="position:absolute; left:0px; top:129px; width:100%; height:96px; z-index:1;"> 
  <?php 
    require("../../../funciones/conexion.php");
	include("../../../funciones/funcion.php");
    
    $nombreX=str_inde($_POST["nombre"]);
	$nombreX=ucwords(strtolower($nombreX));
	
	$apellido_P=str_inde($_POST["apellido_P"]);
	$apellido_P=ucwords(strtolower($apellido_P));
	
	$apellido_M=str_inde($_POST["apellido_M"]);
	$apellido_M=ucwords(strtolower($apellido_M));
	
	$passsX=trim(str_inde($_POST["passs"]));
	
	$fonoX=str_inde($_POST["fono"]);
	$direccionX=mysql_real_escape_string($_POST["direccion"]);
	$direccionX=ucwords(strtolower($direccionX));
	
	$ciudadX=str_inde($_POST["ciudad"]);
	$ciudadX=ucwords(strtolower($ciudadX));
	
	$emailX=str_inde($_POST["email"]);
	$liceoX=str_inde($_POST["liceo"]);
	$liceoX=ucwords(strtolower($liceoX));
	
	$apoderadoX=str_inde($_POST["apoderado"]);
    $fonoaX=str_inde($_POST["fonoa"]);
    $fnacX=str_inde($_POST["fnac"]);
	
    $idX=$_POST["id"];			
     $id_alumno=$idX;
	 //echo"fonoa==> $fonoaX";
	 //--------------------------------------------------//
 	 include("../../../funciones/VX.php");
	 $evento="Modifica sus datos personales";
	 REGISTRA_EVENTO($evento);
	 //cambio estado_conexion USER-----------
	 CAMBIA_ESTADO_CONEXION_ALUMNO($id_alumno, "ON");
	//-----------------------------------------------//


$cons_UP="UPDATE  alumno set  nombre='$nombreX', apellido_P='$apellido_P', apellido_M='$apellido_M', clave='$passsX', fono='$fonoX', direccion='$direccionX', ciudad='$ciudadX', email='$emailX', liceo='$liceoX', apoderado='$apoderadoX', fonoa='$fonoaX', fnac='$fnacX'  WHERE id=$idX LIMIT 1";
	if(DEBUG){echo "$cons_UP<br>";}
	else
	{
      mysql_query($cons_UP)or die(mysql_error());
	}
	mysql_close($conexion);
	?>
<div align='center'>
  <p><font color='#0000CC'>Los datos han sido modificados</font><img src="../../BAses/Images/ok.png" width="29" height="26"></p>
  <p>&nbsp;</p>
</div>


 </div>

</body>
</html>