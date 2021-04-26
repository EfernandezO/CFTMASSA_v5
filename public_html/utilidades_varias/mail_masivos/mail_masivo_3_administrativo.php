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

if(DEBUG){ var_dump($_POST);}

if(isset($_POST["array_id_administrativo"])){$array_id_administrativos=$_POST["array_id_administrativo"];}
else{$array_id_administrativos=array();}



$sede=$_POST["sede"];
$year_actual=date("Y");

/////////////////////////////
$asunto_mensaje=$_POST["asunto"];
$cuerpo_mensaje=$_POST["cuerpo"];
$archivo_adjunto=$_POST["archivo_adjunto"];
$ruta_archivo="../../CONTENEDOR_GLOBAL/archivos_temporales/";

$archivo_adjunto_full_src=$ruta_archivo.$archivo_adjunto;
 $condicion_tipo_programa="";

$mes_actual=date("m");
if($mes_actual>8)
{ $semestre_actual=2;}
else
{ $semestre_actual=1;}

if($sede=="")
{$sede="Talca";}

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
	top: 256px;
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
   	 <th colspan="7">Listado Administrativos Seleccionados sede: <?php echo $sede;?></th>
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
///////////////////////////
//alumno.grupo='A' AND alumno.situacion='V' AND alumno.nivel=1 AND alumno.sede='Talca'
require("../../../funciones/conexion_v2.php");
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
	$body_1='<img src="http://200.28.135.221/~cftmassa/BAses/Images/logo_cft.jpg" width="150" height="120" alt="logo" /><br><br>';
	$body_1.='<h1>'.$asunto_mensaje.'</h1><p>';				
	$body_3='<br><br>'.$cuerpo_mensaje.'
		<br> <br>
		<a href="http://www.cftmass.cl">www.cftmass.cl</a></p>'; 
					
		/////Registro ingreso///
			 include("../../../funciones/VX.php");
			 $evento="Envio de Email Masivo a Administrativos sede: $sede $asunto_mensaje";
			 REGISTRA_EVENTO($evento);
			
			$aux=0;	 
//----------------------------------------------------------------------------------//
		$num_email_enviados=0;
		if(count($array_id_administrativos)>0)
		{
			$primera_vuelta=true;
			
			foreach($array_id_administrativos as $indice =>$aux_id_administrativos)
			{
				if(DEBUG){ echo"<br><br><br><br>Indice: $indice -> id_administrativo: $aux_id_administrativos<br>";}
				$mostrar_administrativo=true;
				
				$cons_P="SELECT nombre, apellido_P, apellido_M, email, email_personal FROM personal WHERE id='$aux_id_administrativos' LIMIT 1";	
				$sqli_P=$conexion_mysqli->query($cons_P)or die($conexion_mysqli->error);
				$P=$sqli_P->fetch_assoc();
					$P_nombre=$P["nombre"];
					$P_apellido_P=$P["apellido_P"];
					$P_apellido_M=$P["apellido_M"];
					$P_email_institucional=$P["email"];
					$P_email_personal=$P["email_personal"];
					
					if(DEBUG){ echo"$P_nombre $P_apellido_P<br>EMAIL institucional: |$P_email_institucional|<br>EMAIL personal: |$P_email_personal|<br>";}
					if(($P_email_institucional=="Sin Registro")||(empty($P_email_institucional))){ $P_email=$P_email_personal;}
					else{ $P_email=$P_email_institucional;}
					//$P_email="informatica@cftmass.cl";
				$sqli_P->free();	
									
				if($mostrar_administrativo)
				{
					
					////////////////////////////////////////
					if(DEBUG){ echo"<strong>Mostrar Administrativo</strong><br>";}
					$cuenta_administrativo++;
					echo'<tr>
							<td>'.$cuenta_administrativo.'</td>
							<td>'.$P_nombre.'</td>
							<td>'.$P_apellido_P.'</td>
							<td>'.$P_apellido_M.'</td>
							<td>'.$P_email.'</td>';
					$mail = new PHPMailer();
					$mail->Host = $host;
					$mail->From = $remite;
					$mail->FromName = $nombre_remite;
					$mail->Subject = $asunto;	
					$mail->Body = $body_1.$body_3;
					$mail->AltBody =$asunto;
					$mail->IsHTML(true);
					$mail->AddAddress($P_email, $P_email);
					
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
					
					$email_valido=comprobar_email($P_email);
					
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
								REGISTRO_EVENTO_FUNCIONARIO($aux_id_administrativos,$tipo_registro,$descripcion);
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
					if(DEBUG){ echo"<strong>NO Mostrar Administrativos</strong><br>";}
				}
			
			}
						
		}
		else
		{	
			echo'<tr><td colspan="9">Sin Administrativos Seleccionados</td></tr>';
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