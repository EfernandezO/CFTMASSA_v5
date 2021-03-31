<?php
session_start();
define("DEBUG", false);
require("../../../funciones/VX.php");
$evento="cierra sesion";
$privilegio="";

if(isset($_SESSION["USUARIO"]))
{
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	REGISTRA_EVENTO($evento);
	switch($privilegio)
	{
		case"admi":
			$url="http://intranet.cftmassachusetts.cl/";
			//cambio estado_conexin USER-----------
			CAMBIA_ESTADO_CONEXION($_SESSION["USUARIO"]["id"], "OFF");
			break;
		case"admi_total":
			if (isset($_SERVER['HTTPS'])) 
			{$url="http://intranet.cftmassachusetts.cl";} 
			else {$url="http://intranet.cftmassachusetts.cl";}
			//cambio estado_conexin USER-----------
			CAMBIA_ESTADO_CONEXION($_SESSION["USUARIO"]["id"], "OFF");
			break;
		case"matricula":
			$url="http://intranet.cftmassachusetts.cl";
			//cambio estado_conexin USER-----------
			CAMBIA_ESTADO_CONEXION($_SESSION["USUARIO"]["id"], "OFF");
			break;
		case"inspeccion":
			$url="http://cftmass.cl";
			//cambio estado_conexin USER-----------
			CAMBIA_ESTADO_CONEXION($_SESSION["USUARIO"]["id"], "OFF");
			break;	
		case"ALUMNO":
			$url="http://www.cftmass.cl";
			//cambio estado_conexin USER-----------
			CAMBIA_ESTADO_CONEXION_ALUMNO($_SESSION["USUARIO"]["id"], "OFF");
			break;		
		default:
			$url="http://www.cftmass.cl";		
	}
}	
else
{ $url="http://intranet.cftmassachusetts.cl";}
 /////----------------------------------

@session_destroy();
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
   <meta charset="utf-8" />
   <title>Cerrando Sesion | CFT Massachusetts</title>
   <meta content="width=device-width, initial-scale=1.0" name="viewport" />
   <meta content="" name="description" />
   <meta content="" name="author" />
   
   <link href="../../libreria_publica/archivos_stilo_1/bootstrap.min.css" rel="stylesheet" />
   <link href="../../libreria_publica/archivos_stilo_1/bootstrap-responsive.min.css" rel="stylesheet" />
   <link href="../../libreria_publica/archivos_stilo_1/font-awesome.css" rel="stylesheet" />
   <link href="../../libreria_publica/archivos_stilo_1/style.css" rel="stylesheet" />
   <link href="../../libreria_publica/archivos_stilo_1/style-responsive.css" rel="stylesheet" />
   <link href="../../libreria_publica/archivos_stilo_1/style-default.css" rel="stylesheet" id="style_color" />
<script language="javascript" type="text/javascript">

    var pagina = "<?php echo $url;?>";
    var segundos = 1000;
    function redireccion()
	 {
        document.location.href=pagina;
     }
   setTimeout("redireccion()",segundos);
</script>  
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="error-404">
    <div class="error-wrap error-wrap-404">
        <div class="metro big terques">
           <span> Adiós </span>
        </div>
        <div class="metro green">
            <span><li class="icon-android"><li></span>
        </div>
        <div class="metro yellow">
            <span><li class="icon-apple"><li</span>
        </div>
        <div class="metro purple">
            <span><li class="icon-windows"><li</span>
        </div>
        <div class="metro double red">
            <span class="page-txt"> Sesion Finalizada</span>
        </div>
        <div class="metro gray">
            <a href="http://www.cftmass.cl" class="home"><i class="icon-home"></i> </a>
        </div>

    </div>
</body>
<!-- END BODY -->
</html>