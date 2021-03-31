<?php
//-----------------------------------------------------//
if (!file_exists('../../funciones/configv1.php')) {
    echo"Archivo Configuracion No encontrado... :(<br>";
    die;
}
require_once('../../funciones/configv1.php');
//---------------------------------------------//
session_start();
define("DEBUG", false);
$destino="";
$token=md5("/_MxAppPx4dsf".microtime());
$_SESSION["SISTEMA"]["token"]=$token;
date_default_timezone_set("America/Santiago");
//unificar url

$hostActual="http://".$_SERVER['HTTP_HOST'];
$urlActual=$_SERVER['REQUEST_URI'];
$corregirHost=false;
//--------------------------------------------//
if(DEBUG){
	echo"Verificar Host<br> Permitido:$CFG->wwwroot<br>Actual: $hostActual<br>";
}

if($hostActual!==$CFG->wwwroot){ $corregirHost=true;}

if($corregirHost){
	$destino=$CFG->wwwroot.'/Administrador/index.php';
	if(DEBUG){ echo"Corregir Host a: $destino<br>";}
	else{ header("location: $destino");}
}

if(isset($_SESSION["USUARIO"]["autentificado"]))
{
	if(DEBUG){ echo"Sesion USUARIO autentificado existe<br>";}
	if($_SESSION["USUARIO"]["autentificado"])
	{
		$tipo_cuenta=$_SESSION["USUARIO"]["privilegio"];
		//echo"----> $tipo_cuenta<br>";
		 switch($tipo_cuenta)
		 {
		      case "admi":
			       //nivel de administrador 2
			       $url="ADmenu.php";
				   break;
			  case "finan":	
			      $url=" ../contabilidad/index.php";
				   break;
			  case "admi_total":
			       $url="ADmenu.php";
				   break;
			  default:
			  	$url=""; 
		}	
		//echo $url;
		if($url!="")
		{
			@header("location: $url");
		}	
	}
	
}
?>
<!DOCTYPE html>
<html lang="en"><!--<![endif]--><!-- BEGIN HEAD --><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
   <meta charset="utf-8">
   <title>Acceso | Intranet CFT Massachusetts</title>
   <meta content="width=device-width, initial-scale=1.0" name="viewport">
   <meta content="" name="description">

   <link href="../libreria_publica/archivos_stilo_1/bootstrap.min.css" rel="stylesheet">
   <link href="../libreria_publica/archivos_stilo_1/bootstrap-responsive.min.css" rel="stylesheet">
   <link href="../libreria_publica/archivos_stilo_1/font-awesome.css" rel="stylesheet">
   <link href="../libreria_publica/archivos_stilo_1/style.css" rel="stylesheet">
   <link href="../libreria_publica/archivos_stilo_1/style-responsive.css" rel="stylesheet">
   <link href="../libreria_publica/archivos_stilo_1/style-default.css" rel="stylesheet" id="style_color">
<script language="javascript" type="application/javascript">
function CAMBIA_DESTINO(tipo_usuario)
{
	switch(tipo_usuario)
	{
		case "Administrador":
			url="controladmi.php";
			valor="<?php echo md5("adminX".date("d-m-Y"));?>";
			break;
		case "Docente":
			url="../Docentes/controldocen.php";
			valor="<?php echo md5("docenteX".date("d-m-Y"));?>";
			break;
		case "Alumno":
			url="../Alumnos/controlalumn.php";
			valor="<?php echo md5("alumnoX".date("d-m-Y"));?>";
			break;
		default:
			url="controladmi.php";		
			valor="<?php echo md5("adminX".date("d-m-Y"));?>";
	}
	document.getElementById('acceso').innerHTML="["+tipo_usuario+"]";
	document.getElementById('frm').action=url;
	document.getElementById('validador').value=valor;
}
</script> 
   
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="lock">
    <div class="lock-header">
        <!-- BEGIN LOGO -->
        <a class="center" id="logo" href="http://www.cftmass.cl">
            <img class="center" alt="logo" src="../BAses/Images/logo.png">
        </a>
        <!-- END LOGO -->
    </div>
    <div class="login-wrap">
      <form action="controladmi.php" method="post" id="frm">
        <div class="metro single-size red">
            <div class="locked">
                <i class="icon-lock"></i>
                <span>Intranet</span>
            </div>
        </div>
        
        <div class="metro double-size green">
            
                <div class="input-append lock-input">
                    <input name="frut" type="text" class="" id="frut" placeholder="Rut">
                </div>
          
        </div>
        <div class="metro double-size yellow">
        
                <div class="input-append lock-input">
                    <input name="fclave" type="password" class="" id="fclave" placeholder="Clave">
                </div>
            
        </div>
        <div class="metro single-size terques login">
          
             <input name="validador" type="hidden" id="validador" value="<?php echo md5("adminX".date("d-m-Y"));?>" />
       			 <input name="token" type="hidden" id="token" value="<?php echo $token;?>">
                <button type="submit" class="btn login-btn">
                    Entrar
                    <i class=" icon-long-arrow-right"></i>
                </button>
            
        </div>
       
        <div class="metro double-size navy-blue ">
            <a href="https://www.facebook.com/cft.massachusetts" class="social-link">
                <i class="icon-facebook-sign"></i>
                <span>Facebook</span>
            </a>
        </div>
        <div class="metro single-size deep-red">
            <a href="http://www.gmail.com" class="social-link">
                <i class="icon-google-plus-sign"></i>
                <span>Gmail</span>
            </a>
        </div>
        <div class="metro double-size blue">
            <a href="http://www.twitter.com" class="social-link">
                <i class="icon-twitter-sign"></i>
                <span>Twitter</span>
            </a>
        </div>
        <div class="metro single-size purple">
            <a href="http:" class="social-link">
                <i class="icon-skype"></i>
                <span>Skype</span>
            </a>
        </div>
        <div class="login-footer">
            <div class="remember-hint pull-left">
            <?php
			////mensaje de error
			$msj="";
            if(isset($_GET["error"]))
			{
				$error=$_GET["error"];
				switch($error)
				{
					case"AD1":
						$msj='<i class="icon-comment"></i> Datos Incorrectos.. :(';
						break;
					default:	
						$msj="";
				}
			}
			echo $msj;
			?>
          </div>
            <div class="forgot-hint pull-right">
                <a id="forget-password" class="" href="javascript:;">Olvido su Clave ?</a>
            </div>
        </div>
        </form>
        <button class="btn btn-small btn-danger btn" onClick="CAMBIA_DESTINO('Administrador');"><i class="icon-remove icon-white"></i> Administrador</button>
        <button class="btn btn-small btn-danger" onClick="CAMBIA_DESTINO('Docente');"><i class="icon-remove icon-white"></i> Docente</button>
        <button class="btn btn-small btn-danger" onClick="CAMBIA_DESTINO('Alumno');"><i class="icon-remove icon-white"></i> Alumno</button>
         <div id="acceso">[Administrador]</div>
    </div>
   

<!-- END BODY -->
</body></html>