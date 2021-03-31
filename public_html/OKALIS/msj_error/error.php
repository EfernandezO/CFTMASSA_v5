<?php
$digito[0]="";
$digito[1]="";
$digito[2]="";
$msj="";
if(isset($_GET["s"]))
{
	$s=$_GET["s"];
	
	switch($s)
	{
		case"error":
			$digito[0]=0;
			$digito[1]=0;
			$digito[2]=1;
			$msj="Error de Acceso...";
			break;
		case"privilegios":
			$digito[0]=0;
			$digito[1]=0;
			$digito[2]=2;
			$msj="Sin Privilegios Para Continuar";
			break;
		case"caduca":	
			$digito[0]=0;
			$digito[1]=0;
			$digito[2]=3;
			$msj="La Sesion ha Caducado, Vuelva a iniciar Sesion";
			break;
		case"iniciada":	
			$digito[0]=0;
			$digito[1]=0;
			$digito[2]=4;
			$msj="La Sesion Ya ha sido iniciada, Cierre la Session o espere 10 min.";
			break;	
		default:
			$digito[0]=0;
			$digito[1]=1;
			$digito[2]=0;
			$msj="Error Predeterminado...";
	}
}
?>
<!DOCTYPE html>
<html lang="en"><!--<![endif]--><!-- BEGIN HEAD --><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
   <meta charset="utf-8">
   <title>Error | Cft Massachusetts</title>
   <meta content="width=device-width, initial-scale=1.0" name="viewport">
   <meta content="" name="description">
   <meta content="" name="author">
   <link href="../../libreria_publica/archivos_stilo_1/bootstrap.min.css" rel="stylesheet">
   <link href="../../libreria_publica/archivos_stilo_1/bootstrap-responsive.min.css" rel="stylesheet">
   <link href="../../libreria_publica/archivos_stilo_1/font-awesome.css" rel="stylesheet">
   <link href="../../libreria_publica/archivos_stilo_1/style.css" rel="stylesheet">
   <link href="../../libreria_publica/archivos_stilo_1/style-responsive.css" rel="stylesheet">
   <link href="../../libreria_publica/archivos_stilo_1/style-default.css" rel="stylesheet" id="style_color">
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="error-500">
    <div class="error-wrap">
        <h1>Ouch!</h1>
        <h2>Parece que hay un Problema...</h2>
        <div class="metro green">
           <span><?php echo $digito[0];?></span>
        </div>
        <div class="metro yellow">
            <span><?php echo $digito[1];?></span>
        </div>
        <div class="metro purple">
            <span><?php echo $digito[2];?></span>
        </div>
        <p><?php echo $msj;?> Para Volver a la Pagina Principal <a href="http://www.cftmass.cl">cftmass.cl</a>, para Volver <a href="javascript:history.go(-1)">Atras</a></p>
    </div>

<!-- END BODY -->
</body></html>