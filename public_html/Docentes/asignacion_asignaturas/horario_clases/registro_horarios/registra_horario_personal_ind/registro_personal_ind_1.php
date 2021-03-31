<?php
//--------------CLASS_okalis------------------//
	require("../../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../../funciones/";
	$O->clave_del_archivo=md5("control_asistencia_docente_individual_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

//////////////////////XAJAX/////////////////
@require_once ("../../../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("registro_personal_ind_server.php");
$xajax->configure('javascript URI','../../../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CARGA_HORARIO");
$xajax->register(XAJAX_FUNCTION,"MARCA_TIME");
////////////////////////////////////////////

if(isset($_GET["sede"]))
{$sede=$_GET["sede"];}
else
{ $sede="sin_sede";}
$fecha_actual=date("Y-m-d");
//$fecha_actual="2015-03-03";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
		<?php include("../../../../../../funciones/codificacion.php");?>
		<?php $xajax->printJavascript(); ?> 
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title>Contro Asistencia Docente</title>
		<link rel="stylesheet" type="text/css" href="../../../../../CSS/css_formulario/style.css">
        <link rel="stylesheet" type="text/css" href="../../../../../CSS/tabla_2.css"/>
		<!--[if lte IE 7]><style>.main{display:none;} .support-note .note-ie{display:block;}</style><![endif]-->
		<style>	
			@import url(http://fonts.googleapis.com/css?family=Montserrat:400,700|Handlee);
			body {
				background: #eedfcc url(../../../../../BAses/images_formulario/bg3.jpg) no-repeat center top;
				-webkit-background-size: cover;
				-moz-background-size: cover;
				background-size: cover;
			}
			.container > header h1,
			.container > header h2 {
				color: #fff;
				text-shadow: 0 1px 1px rgba(0,0,0,0.5);
			}
			#div_resultados
			{
				width:100%;
				padding:5%;
			}
		</style>
        <script>
function pulsar(e) {
  tecla = (document.all) ? e.keyCode :e.which;
  return (tecla!=13);
}
</script> 
    </head>
    <body>
        <div class="container">
		
			<!-- Codrops top bar -->
            <div class="codrops-top">
                <a href="#">Control de Asistencia - CftMASS.CL</a>
                <span class="right">
                    <a href="#">
                        <strong>2016</strong>
                    </a>
                </span>
            </div><!--/ Codrops top bar -->
			
			<header>
			
				<h1><img src="../../../../../BAses/Images/logo.png" width="334" height="80" alt="logo" /></h1>
				<h2>Por favor Ingrese su Rut</h2>
			</header>
			
			<section class="main">
				<form class="form-5 clearfix">
				    <p>
				        <input type="text" id="login" name="login" placeholder="Rut" onkeypress="return pulsar(event)">
				       
				    </p>
				    <button type="button" name="submit" onclick="xajax_CARGA_HORARIO(document.getElementById('login').value,'<?php echo $fecha_actual;?>', '<?php echo $sede;?>')">
				    	<i class="icon-arrow-right"></i>
				    	<span>Entrar</span>
				    </button>     
				</form>​​​​
                 <div id="div_resultados"></div>
			</section>
			
        </div>
   
<div id="div_informacion"></div>
    </body>
</html>