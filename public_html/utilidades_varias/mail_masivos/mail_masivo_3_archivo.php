<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Envio_Email_Masivo_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	set_time_limit(600);
	if(DEBUG){error_reporting(E_ALL); ini_set("display_errors", 1);}
require("../../../funciones/conexion_v2.php");
if(DEBUG){ var_dump($_POST);}

if(isset($_POST["array_id_usuario"])){$array_id_usuario=$_POST["array_id_usuario"];}
else{$array_id_usuarios=array();}

$year_actual=date("Y");

/////////////////////////////
if(DEBUG){ var_dump($_POST);}

$asunto_mensaje=$_POST["asunto_mensaje"];
$cuerpo_mensaje=$_POST["cuerpo_mensaje"];
$archivo_adjunto=$_POST["archivo_adjunto"];
$archivo_destinatarios=$_POST["archivo_destinatarios"];
$ruta_archivo="../../CONTENEDOR_GLOBAL/archivos_temporales/";

$archivo_adjunto_full_src=$ruta_archivo.$archivo_adjunto;
 $condicion_tipo_programa="";

$mes_actual=date("m");
if($mes_actual>8)
{ $semestre_actual=2;}
else
{ $semestre_actual=1;}


$cuenta_administrativo=0;

//----------------------------------------------------------------//
///se envia una copia de cada mensaje a
$enviar_mail_CCO=true;
$email_destino_copia_oculta="soporte@cftmass.cl";
//----------------------------------------------------------------//
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Email Masivo 4</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:68px;
	z-index:1;
	left: 5%;
	top: 140px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Envio Masivo Email 4/4</h1>
<div id="link"><br />
<a href="mail_masivo_0.php" class="button">Volver a Seleccion</a><br />
<br />
<a href="#" class="button" onclick="window.print();">Imprimir </a></div>
<div id="apDiv1">
  <table width="100%" align="center">
<thead>
    <tr>
   	 <th colspan="7">Listado Usuarios Seleccionados </th>
    </tr>
</thead>
<tbody>
<tr>
<td>N</td>
<td>Nombre</td>
<td>Apellido P</td>
<td>Apellido M</td>
<td>Email</td>
<td>Estado</td>
</tr>
<?php
$fecha_actual=date("Y-m-d");
$cuenta_usuarios=0;
///////////////////////////
//alumno.grupo='A' AND alumno.situacion='V' AND alumno.nivel=1 AND alumno.sede='Talca'

include("../../../funciones/funciones_sistema.php");
///////////////////////////////////
	require("../../libreria_publica/PHPMailer_v5.1/class.phpmailer.php");
	include("../../../funciones/funciones_varias.php");
		//datos usuario enviar
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$cons_DU="SELECT email FROM personal WHERE id='$id_usuario_actual' LIMIT 1";
	$sqli_DU=$conexion_mysqli->query($cons_DU)or die($conexion_mysqli->error);
		$UA=$sqli_DU->fetch_assoc();
		$UA_email=$UA["email"];
	$sqli_DU->free();	
	/////////////////configuracion inicial/////////////////////
	$host="localhost";
	$remite="no_responder@cftmassachusetts.cl";
	$nombre_remite="Robot C.F.T. Massachusetts";
	$asunto=$asunto_mensaje;
	$body_1='<img src="http://cftmassachusetts.cl/~cftmassa/BAses/Images/logo_cft.jpg" width="150" height="120" alt="logo" /><br><br>';
	$body_1.='<h1>'.$asunto_mensaje.'</h1><p>';				
	$body_3='<br><br>'.$cuerpo_mensaje.'
		<br> <br>
		<a href="http://www.cftmass.cl">www.cftmass.cl</a></p>'; 
					
		/////Registro ingreso///
			 include("../../../funciones/VX.php");
			 $evento="Envio de Email Masivo a segun archivo $archivo_destinatarios";
			 REGISTRA_EVENTO($evento);
			
			$aux=0;	 
//----------------------------------------------------------------------------------//
		$num_email_enviados=0;
		if(count($array_id_usuario)>0)
		{
			$primera_vuelta=true;
			
			foreach($array_id_usuario as $tipo_usuario =>$array_aux_id_usuario)
			{
				if(DEBUG){ echo"<br><br><br><br>Tipo Usuario: $tipo_usuario -> <br>";}
				foreach($array_aux_id_usuario as $n => $aux_id_usuario)
				{
					if(DEBUG){ echo"$n -> $aux_id_usuario<br>";}
					$mostrar_usuario=false;
					
					switch($tipo_usuario)
					{
						case"alumno":
							$mostrar_usuario=true;
							$cons_1="SELECT nombre, apellido_P, apellido_M, sede, email FROM alumno WHERE id='$aux_id_usuario' ORDER by id desc LIMIT 1";
							if(DEBUG){ echo"Busqueda como Alumno -->$cons_1<br>";}
							$sql_1=$conexion_mysqli->query($cons_1)or die($conexion_mysqli->error);
							$D_1=$sql_1->fetch_assoc();
								//datos alumno
								$U_nombre=$D_1["nombre"];
								$U_apellido_P=$D_1["apellido_P"];
								$U_apellido_M=$D_1["apellido_M"];
								$U_sede=$D_1["sede"];
								$U_email=$D_1["email"];
							$sql_1->free();
							break;
						case"funcionario":
							$mostrar_usuario=true;
							$cons_P="SELECT nombre, apellido_P, apellido_M, email, email_personal FROM personal WHERE id='$aux_id_usuario' LIMIT 1";	
							$sqli_P=$conexion_mysqli->query($cons_P)or die($conexion_mysqli->error);
							$P=$sqli_P->fetch_assoc();
								$U_nombre=$P["nombre"];
								$U_apellido_P=$P["apellido_P"];
								$U_apellido_M=$P["apellido_M"];
								$U_email_institucional=$P["email"];
								$U_email_personal=$P["email_personal"];
								if(($U_email_institucional=="Sin Registro")||(empty($U_email_institucional))){ $U_email=$U_email_personal;}
								else{ $PU_email=$U_email_institucional;}
								//$P_email="informatica@cftmass.cl";
							$sqli_P->free();	
							break;	
					}
					if(DEBUG){ echo"Nombre: $U_nombre $U_apellido_P $U_apellido_M EMAIL: $U_email<br>";}
					//$U_email="informatica@cftmass.cl";
									
				if($mostrar_usuario)
				{
					
					////////////////////////////////////////
					if(DEBUG){ echo"<strong>Mostrar USUARIO</strong><br>";}
					$cuenta_usuarios++;
					echo'<tr>
							<td>'.$cuenta_usuarios.'</td>
							<td>'.$U_nombre.'</td>
							<td>'.$U_apellido_P.'</td>
							<td>'.$U_apellido_M.'</td>
							<td>'.$U_email.'</td>';
					$mail = new PHPMailer();
					$mail->Host = $host;
					$mail->From = $remite;
					$mail->FromName = $nombre_remite;
					$mail->Subject = $asunto;	
					$mail->Body = $body_1.$body_3;
					$mail->AltBody =$asunto;
					$mail->IsHTML(true);
					$mail->AddAddress($U_email, $U_email);
					
					////copia oculta 
					if($primera_vuelta)
					{
						if($enviar_mail_CCO)
						{
							$mail->AddBCC($email_destino_copia_oculta);
							if(comprobar_email($UA_email))
							{$mail->AddBCC($UA_email);}
						}
						$primera_vuelta=false;
					}
					//-----------------------------------------//
					
					if(empty($archivo_adjunto)){ if(DEBUG){ echo"Sin Archivo Adjunto<br>";}}
					else{$mail->AddAttachment($archivo_adjunto_full_src);}
					
					$email_valido=comprobar_email($U_email);
					
					if(DEBUG){ 
					if($email_valido){ $aux_condicion_email="DEBUG email OK";}
					else{ $aux_condicion_email="DEBUG email ERROR";}
					}
					else
					{
					if($email_valido)
						{
							
							if($mail->Send())
							{
								$num_email_enviados++;
								$aux_condicion_email="enviado";
								$tipo_registro="Email Masivo";
								$descripcion="Envia Email Masivo asunto: $asunto_mensaje";
								switch($tipo_usuario)
								{
									case"funcionario":
										REGISTRO_EVENTO_FUNCIONARIO($aux_id_usuario,$tipo_registro,$descripcion);
										break;
									case"alumno":
										REGISTRO_EVENTO_ALUMNO($aux_id_usuario,$tipo_registro, $descripcion);
										break;
								}
							}
							else
							{ $aux_condicion_email="fallo al enviar";}
						}
						else
						{$aux_condicion_email="email invalido";}
					}
						echo'<td>'.$aux_condicion_email.'</td>
						</tr>';
					$mail->ClearAddresses();
				}
				else
				{
					if(DEBUG){ echo"<strong>NO Mostrar USUARIOS</strong><br>";}
				}
			}
		}
						
		}
		else
		{	
			echo'<tr><td colspan="9">Sin USUARIOS Seleccionados</td></tr>';
		}
		//fin documento
	@mysql_close($conexion);
	$conexion_mysqli->close();
//////////////////////////////////////////////
?>
<tr>
<td colspan="9">Numero de Email Enviados (<?php echo $num_email_enviados;?>)</td>
</tr>
</tbody>
</table><br />
</div>
</body>
</html>