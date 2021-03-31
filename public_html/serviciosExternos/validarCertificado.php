<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
define("DEBUG", false);

if(isset($_GET["id_solicitud"])){$id_solicitud=base64_decode($_GET["id_solicitud"]);}
else{ $id_solicitud=0;}
	
if(isset($_GET["codigo"])){$codigo=base64_decode($_GET["codigo"]);}
else{ $codigo=0;}
	
if(DEBUG){ echo"id_solicitud: $id_solicitud<br>codigo: $codigo<br>";}


$CODcodigoOK=false;
$CODsolicitudOK=false;
$continuar=false;
if((is_numeric($id_solicitud))and($id_solicitud>0)){$CODsolicitudOK=true;}
if((!empty($codigo))and($codigo!==" ")){$CODcodigoOK=true;}
	
	
	if($CODcodigoOK and $CODsolicitudOK)
	{
		if(DEBUG){ echo"codigos OK<br>";}
			require("../../funciones/conexion_v2.php");
			require("../../funciones/class_ALUMNO.php");
			require("../../funciones/funciones_sistema.php");
			require("../../funciones/funcion.php");
			$id_solicitudConsulta=mysqli_real_escape_string($conexion_mysqli, $id_solicitud);
			$codigoConsulta=mysqli_real_escape_string($conexion_mysqli, $codigo);
			$cons_S="SELECT * FROM solicitudes WHERE id='$id_solicitudConsulta' LIMIT 1";
			$sql_S=$conexion_mysqli->query($cons_S);
			$num_solicitudes=$sql_S->num_rows;
			if(DEBUG){ echo"$cons_S<br>NUM: $num_solicitudes<br>";}
			if($num_solicitudes>0)
			{
				$continuar=true;
				$Ds=$sql_S->fetch_assoc();
					$S_tipo=$Ds["tipo"];
					$S_categoria=$Ds["categoria"];
					$S_semestre=$Ds["semestre"];
					$S_year=$Ds["year"];
					$S_idAlumno=$Ds["id_receptor"];
					$S_idCarreraReceptor=$Ds["id_carrera_receptor"];
					$ALUMNO=new ALUMNO($S_idAlumno);
				$sql_S->free();	
				//-----------------------------------
				if(DEBUG){ echo"EXISTE CERTIFICADO <br>";}
				$cons_certificado="SELECT * FROM registro_certificados WHERE id_solicitud='$id_solicitudConsulta' LIMIT 1";
				$sql_certificados=$conexion_mysqli->query($cons_certificado)or die($conexion_mysqli->error);
					$D_certificado=$sql_certificados->fetch_assoc();
						$CODIGO_GENERACION=$D_certificado["codigo_generacion"];
						$array_fecha_hora_creacion_certificado=explode(" ",$D_certificado["fecha_hora"]);
						if(DEBUG){ echo"YA EXISTE CERTIFICADO <br>CODIGO: $CODIGO_GENERACION<br>Fecha: ".$array_fecha_hora_creacion_certificado[0];}
					$fecha=fecha($array_fecha_hora_creacion_certificado[0]);
					$sql_certificados->free();	
				}
			
		$conexion_mysqli->close();
	}else{ if(DEBUG){ echo"Error Codigos<br>";}}
	
	//----------------------------------------------------------------------------------------------//	
?>
<!doctype html>
<html>
  <head>
    <?php include("../../funciones/codificacion.php");?>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   
    <title>Validacion de certificados</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.4/examples/pricing/">

    <!-- Bootstrap core CSS -->
<link rel="stylesheet" type="text/css" href="../libreria_publica/bootstrap-4.4.1-dist/css/bootstrap.min.css">

    <!-- Favicons -->

<meta name="theme-color" content="#563d7c">


    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="pricing.css" rel="stylesheet">
  </head>
  <body>
    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
     <img class="mb-2" src="../BAses/Images/logo_pagina.png" alt="" >
</div>

<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
  <h1 class="display-4">Validacion de Certificados</h1>
  <p class="lead">Informacion del certificado que ud. consulta, verifique que concuerde con la informacion fisica.</p>
</div>

<div class="container">
  <div class="card-deck mb-3 text-center">
    <div class="card mb-4 shadow-sm">
      <div class="card-header">
        <h4 class="my-0 font-weight-normal">Codigo: <?php echo $codigo;?></h4>
      </div>
      <div class="card-body">
      <?php if($continuar){?>
        <h1 class="card-title pricing-card-title"><?php echo $S_tipo;?> <small class="text-muted"><?php echo $S_categoria;?></small></h1>
        <ul class="list-unstyled mt-3 mb-4">
          <li><?php echo $ALUMNO->getNombre()." ".$ALUMNO->getApellido_P()." ".$ALUMNO->getApellido_M()?></li>
          <li>Carrera Certificado: <?php echo NOMBRE_CARRERA($S_idCarreraReceptor);?></li>
          <li>Fecha Emision: <?php echo $fecha;?></li>
          <li>Periodo:<?php echo "$S_semestre - $S_year";?></li>
        </ul>
        <button type="button" class="btn btn btn-block btn-success">Certificado esta en nuestros registros</button>
        <?php }else{?>
         <h1 class="card-title pricing-card-title">Certificado <small class="text-muted">No encontrado</small></h1>
        <ul class="list-unstyled mt-3 mb-4">
          <li>.</li>
          <li>.</li>
          <li>.</li>
          <li>.</li>
        </ul>
        <button type="button" class="btn btn btn-block btn-danger">Certificado No Registrado</button>
        <?php }?>
      </div>
    </div>
    
  </div>

  <footer class="pt-4 my-md-5 pt-md-5 border-top">
    <div class="row">
      <div class="col-12 col-md">
       
        <small class="d-block mb-3 text-muted">CFT Massachusetts - <?php echo date("Y");?></small>
      </div>
  
    </div>
  </footer>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>