<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("SOLICITUDES->verCertificados");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$url=$_SERVER['HTTP_REFERER'];
////////////////////////////////////////////////////
$tipoUsuario=$_SESSION["USUARIO"]["tipo"];

if($tipoUsuario=="funcionario"){
	$array_firmas=array(1, 118, 403, 411); 
	$urlVolver="../../../buscador_alumno_BETA/HALL/index.php";}
else{
	$array_firmas=array(1);
	$arrayObservaciones=array("Los tramites que este pertinentes", 
							  "Caja de Compensación",
							  "Cantón de Reclutamiento",
							  "Fondo Nacional de Salud (Fonasa)",
							  "Instituto de Normalización Previsional (INP)",
							  "Fetram",
							  "Juaneb");
	$urlVolver="../../../Alumnos/certificadosAlumno/certificadoAlumno1_v1.php";
}

//verificar id_solicitud

if(isset($_GET["id_solicitud"]))
{
	$id_solicitud=$_GET["id_solicitud"];
	if($id_solicitud>0)
	{ $hay_solicitud=true;}
	else
	{ $hay_solicitud=false;}
}
else
{ $hay_solicitud=false;}

////-***************************************************************************-//
$redirigir=false;
////-***************************************************************************-//
if($hay_solicitud)
{
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funciones_sistema.php");
	
	
	$cons_c="SELECT COUNT(id) FROM registro_certificados WHERE id_solicitud='$id_solicitud'";
	$sql_c=$conexion_mysqli->query($cons_c)or die($conexion_mysqli->error);
		$Dc=$sql_c->fetch_row();
		$num_certificados=$Dc[0];
		if(empty($num_certificados)){ $num_certificados=0;}
		if(DEBUG){ echo"$cons_c<br>NUM: $num_certificados<br>";}
	$sql_c->free();	
		//////////////////////////////////////////////
	
		$cons_s="SELECT * FROM solicitudes WHERE id='$id_solicitud' LIMIT 1";
		$sql_s=$conexion_mysqli->query($cons_s)or die($conexion_mysqli->error);
			$Ds=$sql_s->fetch_assoc();
			$S_observacion=$Ds["observacion"];
			$S_firma=$Ds["id_firma"];
			$S_sedeReceptor=$Ds["sede_receptor"];
			
			if(DEBUG){ echo"firma en solicitud: $S_firma<br>observacion en solicitud: $S_observacion<br>";}
		$sql_s->free();	
		
		if($num_certificados>0)
		{
			$redirigir=true;
			if(DEBUG){ echo"YA EXISTE CERTIFICADO REDIRIGIR<br>";}
			
		}
		else
		{

			if(empty($S_observacion)or empty($S_firma))
			{ $redirigir=false; if(DEBUG){ echo"NO Redirigir Automaticamente, solicita info<br>";}}
			else
			{ $redirigir=true; if(DEBUG){ echo"Redirige Automaticamente...<br>";}}
			
		}
		
		$conexion_mysqli->close();
		
		if($redirigir)
		{
			$action="";
			$url="alumno_egreso_v1_2.php?id_solicitud=$id_solicitud";
			if(DEBUG){ echo"URL: $url";}
			else{ header("location: $url");}
		}
		else
		{$action="alumno_egreso_v1_2.php";}
}
else
{ if(DEBUG){ echo"Sin Acceso<br>";}}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Confirmacion - Certificado Alumno Regular</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/bootstrap-4.4.1-dist/css/bootstrap.min.css"/>
<script language="javascript">
function VOLVER(){
<?php echo "url='".$urlVolver."';";?>
	window.location=url;
}
</script>
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
<form class="form-signin" action="<?php echo $action;?>" id="frm" method="post" role="form">
  <img class="mb-4" src="../../../BAses/Images/logo_pagina.png" alt="" width="320" height="80">
  <h1 class="h3 mb-3 font-weight-normal">Confirma los datos del Certificado</h1>
   <p class="mt-5 mb-3 text-muted">Una vez que presione continuar, se inicia la descarga del certificado. Presione volver para revisar las solicitudes creadas o volver al menu.</p>
    <div class="form-group">
<input name="id_solicitud" type="hidden" id="id_solicitud" value="<?php echo $id_solicitud;?>">
<label for="firmas" class="sr-only">Firma</label>
  <select class="custom-select d-block w-100" id="firmas" required="" name="firma_certificado">
				<?php
                  foreach($array_firmas as $id_firma)
                  {
                      if($S_firma==$id_firma){ $select='selected="selected"';}
                      else{ $select='';}
					  if($id_firma==1){$labelFirma="CFT Massachusetts - Internet";}
					  else{$labelFirma=NOMBRE_PERSONAL($id_firma);}
					  
                      echo'<option value="'.$id_firma.'" '.$select.'>'.$labelFirma.'</option>';
                  }
                  ?>
            </select>
          <label for="presentado_a" class="sr-only">Presentado a:</label>  
          
            
             
             <?php if($tipoUsuario=="alumno"){?>
              <select class="custom-select d-block w-100" id="presentado_a" required name="presentado">
              <option value="">Seleccione...</option>
				<?php
                  foreach($arrayObservaciones as $n => $valor)
				  {
					  if($valor==$S_observacion){ $selectX='selected="selected"';}
					  else{ $selectX='';}
					 echo'<option value="'.$valor.'" '.$selectX.'>'.$valor.'</option>';
				  }
                  ?>
            </select>
            <?php }else{?>
        
  <input type="text" id="presentado_a" name="presentado" class="form-control" placeholder="presentar a" value="" required>
            <?php }?>
  <button class="btn btn-lg btn-primary btn-block" type="submit">Continuar</button>
  <button class="btn btn-lg btn-block btn-info" type="button" onclick="VOLVER();">Volver</button>
  <p class="mt-5 mb-3 text-muted">CFT Massachusetts - <?php echo date("Y");?> </p>
  </div>
</form>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>