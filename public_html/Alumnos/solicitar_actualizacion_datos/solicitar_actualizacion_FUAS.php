<?php
//-----------------------------------------//
	define("DEBUG", false);
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="ALUMNO";
	OKALIS($lista_invitados);
	require("../../OKALIS/class_OKALIS_v1.php");
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->anti2LoggAlumno();
	////////////////////////
//-----------------------------------------//	
   require('../../../funciones/conexion_v2.php');
   require("../../../funciones/class_ALUMNO.php");
   
   $id_alumno=$_SESSION["USUARIO"]["id"];
   $ALUMNO=new ALUMNO($id_alumno);
   $id_carrera=$_SESSION["USUARIO"]["id_carrera"];
   
   	$A_email=$ALUMNO->getEmail();
	$A_fono=$ALUMNO->getFono();
	$A_fonoa=$ALUMNO->getFonoApoderado();
	
	if($A_email=="Sin Registro"){ $A_email="";}
	if($A_fono=="Sin Registro"){ $A_fono="";}
	if($A_fonoa=="Sin Registro"){ $A_fonoa="";}

	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Actualiza tus datos</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/bootstrap-4.4.1-dist/css/bootstrap.min.css"/>
<style>
html,
body {
  height: 100%;
}

body {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-align: center;
  align-items: center;
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #f5f5f5;
}

.form-signin {
  width: 100%;
  max-width: 330px;
  padding: 15px;
  margin: auto;
}
.form-signin .checkbox {
  font-weight: 400;
}
.form-signin .form-control {
  position: relative;
  box-sizing: border-box;
  height: auto;
  padding: 10px;
  font-size: 16px;
}
.form-signin .form-control:focus {
  z-index: 2;
}
.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
</style>
</head>


<body class="text-center">
<form class="form-signin" action="solicitar_actualizacion_FUAS_2.php" id="frm" method="post" role="form">
  <img class="mb-4" src="../../BAses/Images/logo_pagina.png" alt="" width="320" height="80">
  <h1 class="h3 mb-3 font-weight-normal">Actualiza tus datos</h1>
   <p class="mt-5 mb-3 text-muted">Con repecto a las becas del ministerio de educacion (Beca nuevo milenio, beca excelencia tecnica), este año ud..</p>

  <select class="custom-select d-block w-100" id="estadoFuas" required="" name="estado_FUAS">
              <option value="">Seleccione...</option>
              <option value="postula">Postula</option>
              <option value="renueva">Renueva</option>
              <option value="NPR">No postula Ni renueva</option>
            </select>
  <button class="btn btn-lg btn-primary btn-block" type="submit">Continuar</button>
  <p class="mt-5 mb-3 text-muted">CFT Massachusetts - <?php echo date("Y");?> </p>
</form>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>