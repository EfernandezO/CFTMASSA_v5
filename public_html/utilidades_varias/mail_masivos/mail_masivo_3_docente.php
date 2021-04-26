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

if(isset($_POST["array_id_funcionario"])){$array_id_funcionario=$_POST["array_id_funcionario"];}
else{$array_id_funcionario=array();}



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
if($mes_actual>=8){ $semestre_actual=2;}
else{ $semestre_actual=1;}

if($sede=="")
{$sede="Talca";}
$condicion=" alumno.sede='$sede' AND contratos2.condicion<>'inactivo'";

$cuenta_funcionario=0;

//----------------------------------------------------------------//
///se envia una copia de cada mensaje a
$enviar_mail_CCO=true;
$email_destino_copia_oculta="dat@cftmass.cl";
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
	top: 152px;
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
   	 <th colspan="7">Listado Funcionarios Seleccionados sede: <?php echo $sede;?></th>
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
	$cons_DU="SELECT email, email_personal FROM personal WHERE id='$id_usuario_actual' LIMIT 1";
	$sqli_DU=$conexion_mysqli->query($cons_DU)or die($conexion_mysqli->error);
		$UA=$sqli_DU->fetch_assoc();
		$UA_email=$UA["email"];
		$UA_email_personal=$UA["email_personal"];
	$sqli_DU->free();	
	if((((empty($UA_email))or($UA_email=="Sin Registro"))and(!empty($UA_email_personal)))){ $UA_email=$UA_email_personal;}
	/////////////////configuracion inicial/////////////////////
	$host="localhost";
	$remite="no_responder@cftmassachusetts.cl";
	$nombre_remite="Robot C.F.T. Massachusetts";
	$asunto=$asunto_mensaje;
	$body_1='<img src="http://cftmassachusetts.cl/~cftmassa/BAses/Images/logo_cft.jpg" width="150" height="120" alt="logo" /><br><br>';
	$body_1.='<h1>'.$asunto_mensaje.'</h1><p>';				
	$body_3='<br><br>'.$cuerpo_mensaje.'
		<br> <br>
		Para sus consultas puede escribirnos a nuestro correo electrónico secretaria@cftmass.cl, llamar al fono 71-2225713 en Talca o al 71-2213880 en Linares<a href="http://www.cftmass.cl">www.cftmass.cl</a></p>'; 
					
 							/////Registro ingreso///
								 include("../../../funciones/VX.php");
								 $evento="Envio de Email Masivo a Docentes sede: $sede $asunto_mensaje";
								 REGISTRA_EVENTO($evento);
								
								$aux=0;	 
//----------------------------------------------------------------------------------//
		$num_email_enviados=0;
		if(count($array_id_funcionario)>0)
		{
			$primera_vuelta=true;
			
			foreach($array_id_funcionario as $indice =>$aux_id_funcionarios)
			{
				if(DEBUG){ echo"<br><br><br><br>Indice: $indice -> id_funcionarios: $aux_id_funcionarios<br>";}
				$mostrar_funcionario=true;
				
				$cons_P="SELECT nombre, apellido_P, apellido_M, email, email_personal FROM personal WHERE id='$aux_id_funcionarios' LIMIT 1";	
				$sqli_P=$conexion_mysqli->query($cons_P)or die($conexion_mysqli->error);
				$P=$sqli_P->fetch_assoc();
					$P_nombre=$P["nombre"];
					$P_apellido_P=$P["apellido_P"];
					$P_apellido_M=$P["apellido_M"];
					$P_email=$P["email"];
					$P_email_personal=$P["email_personal"];
					//$P_email="informatica@cftmass.cl";
				$sqli_P->free();	
				if((((empty($P_email))or($P_email=="Sin Registro"))and(!empty($P_email_personal)))){ $P_email=$P_email_personal;}
				$P_email=trim($P_email);
									
				if($mostrar_funcionario)
				{
					
					////////////////////////////////////////
					if(DEBUG){ echo"<strong>Mostrar Docente</strong><br>";}
					$cuenta_funcionario++;
					echo'<tr>
							<td>'.$cuenta_funcionario.'</td>
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
								REGISTRO_EVENTO_FUNCIONARIO($aux_id_funcionarios,$tipo_registro,$descripcion);
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
					if(DEBUG){ echo"<strong>NO Mostrar Funcionario</strong><br>";}
				}
			
			}
						
		}
		else
		{	
			echo'<tr><td colspan="9">Sin Funcionarios Seleccionados</td></tr>';
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