<?php
//-----------------------------------------//
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Envio_Email_Masivo_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	set_time_limit(600);
//-----------------------------------------//	

$num_alumnos_morosos=0;
$num_alumno_al_dia=0;
$num_email_enviados=0;
$continuar=false;
$ver_mensaje_muestra_DEBUG=true;
$tiempo_inicio_script = microtime(true);
//-------------------------------------------///
if(DEBUG){ var_dump($_POST);}

if($_POST){
	if(isset($_POST["array_id_alumno"])){$array_id_alumno=$_POST["array_id_alumno"];}
	else{$array_id_alumno=array();}
	
	$sede=$_POST["sede"];
	$id_carrera=$_POST["id_carrera"];
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
	$continuar=true;
}

//----------------------------------------------------------------//
///se envia una copia de cada mensaje a
$enviar_mail_CCO=true;
$email_destino_copia_oculta="soporte@cftmass.cl";
//----------------------------------------------------------------//
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion_2.php");?>
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
	top: 119px;
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
   	 <th colspan="9">Listado Alumnos Seleccionados<br /> Carrera: <?php echo $id_carrera;?> </th>
    </tr>
</thead>
<tbody>
<tr>
<td>N</td>
<td>Rut</td>
<td>Nombre</td>
<td>Apellidos</td>
<td>Carrera</td>
<td>Email</td>
<td>Estado</td>
</tr>
<?php
$fecha_actual=date("Y-m-d");
///////////////////////////
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");
require("../../../funciones/funciones_varias.php");
require("../../../funciones/class_ALUMNO.php");
require("../../libreria_publica/PHPMailer6/class_EMAIL.php");
	//datos usuario enviar
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$cons_DU="SELECT email FROM personal WHERE id='$id_usuario_actual' LIMIT 1";
	$sqli_DU=$conexion_mysqli->query($cons_DU)or die($conexion_mysqli->error);
		$UA=$sqli_DU->fetch_assoc();
		$UA_email=$UA["email"];
	$sqli_DU->free();	
	/////////////////configuracion inicial/////////////////////
	
	$remite="no_responder@cftmass.cl";
	$nombre_remite="Robot C.F.T. Massachusetts";
	
	$body_1='<img src="http://intranet.cftmassachusetts.cl/BAses/Images/logo_cft.jpg" width="150" height="120" alt="logo" /><br><br>';
	$body_1.='<h1>'.$asunto_mensaje.'</h1><p>';				
	$body_1.='<br><br>'.$cuerpo_mensaje.'
		<br> <br>
		Para sus consultas puede escribirnos a nuestro correo electrónico info@cftmass.cl, llamar al fono 71-2225713<a href="http://www.cftmass.cl"> www.cftmass.cl</a></p>'; 
					
	$body_1.='<tt><br>*Email generado automaticamente, NO responder '.date("d-m-Y H:i:s").'</tt>';				
		/////Registro ingreso///
			 include("../../../funciones/VX.php");
			 $evento="Envio de Email Masivo a Alumnos sede: $sede id_carrera: $id_carrera ASUNTO: $asunto_mensaje";
			 REGISTRA_EVENTO($evento);
			
			$cuenta_alumnos=0;
//----------------------------------------------------------------------------------//
	
		if(count($array_id_alumno)>0)
		{
			$primera_vuelta=true;
			foreach($array_id_alumno as $indice =>$array_datos_alumno)
			{
				$array_datos_alumno=explode("_",$array_datos_alumno);
				$id_alumno=$array_datos_alumno[0];
				$id_carrera_alumno=$array_datos_alumno[1];
				
				$ALUMNO = new ALUMNO($id_alumno);
	
				$rut_alumno=$ALUMNO->getRut();
				$nombre=$ALUMNO->getNombre();
				$apellidos=$ALUMNO->getApellido_P()." ".$ALUMNO->getApellido_M();
				$emailAlumno=$ALUMNO->getEmail();
				$emailInstitucional=$ALUMNO->getEmailInstitucional();
				
				
				//$emailAlumno="informatica@cftmass.cl";
				//$emailInstitucional="informatica@cftmass.cl";
				////////////////////////////////////////
					$cuenta_alumnos++;
					echo'<tr>
							<td>'.$cuenta_alumnos.'</td>
							<td>'.$rut_alumno.'</td>
							<td>'.$nombre.'</td>
							<td>'.$apellidos.'</td>
							<td>'.$id_carrera_alumno.'</td>
							<td>'.$emailAlumno.'</td>';
				
				if(DEBUG){
					if($ver_mensaje_muestra_DEBUG){
						echo"Asunto: $asunto_mensaje<br>";
						echo"Archivo adjunto: $archivo_adjunto_full_src<br><br>";
						echo $body_1;
					}
				}
				
					
					$email=new EMAIL(false, $body_1);
    				$email->Subject = $asunto_mensaje;
					$email->AddAddress($emailAlumno, $nombre." ".$apellidos);
					$email->AddAddress($emailInstitucional, $nombre."".$apellidos);
														
					if($enviar_mail_CCO)
					{
						if($primera_vuelta){
							$email->AddBCC($email_destino_copia_oculta);
							if(comprobar_email($UA_email))
							{$email->AddBCC($UA_email);}
							$primera_vuelta=false;
						}
					}
											
					//-----------------------------------------//
					
					if(empty($archivo_adjunto)){ if(DEBUG){ echo"Sin Archivo Adjunto<br>";}}
					else{$email->AddAttachment($archivo_adjunto_full_src);}
					
					$email_valido=comprobar_email($emailAlumno);
					
					if(DEBUG){ 
						if($email_valido){ $aux_condicion_email="DEBUG email OK";}
						else{ $aux_condicion_email="DEBUG email ERROR";}
					}
					else
					{
					if($email_valido)
						{
							
							if($email->Send())
							{
								$num_email_enviados++;
								$aux_condicion_email="enviado";
								$tipo_registro="Email Masivo";
								$descripcion="Envia Email Masivo asunto: $asunto_mensaje";
								REGISTRO_EVENTO_ALUMNO($id_alumno, $tipo_registro, $descripcion);
							}
							else
							{ $aux_condicion_email="fallo al enviar";}
						}
						else
						{$aux_condicion_email="email invalido";}
					}
						echo'<td>'.$aux_condicion_email.'</td>
						</tr>';
					$email->ClearAddresses();
					}	
		}
		else
		{	
			echo'<tr><td colspan="9">Sin Alumnos Seleccionados</td></tr>';
		}
		//fin documento
	$conexion_mysqli->close();
//////////////////////////////////////////////
$tiempo_fin_script = microtime(true);
$tiempo_de_ejecucion=round($tiempo_fin_script - $tiempo_inicio_script,4);
?>
<tr>
<td colspan="9">Numero de Email Enviados (<?php echo $num_email_enviados;?>) Tiempo de Ejecucion de Script <?php echo $tiempo_de_ejecucion; ?> Segundos</td>
</tr>
</tbody>
</table><br />
</div>
</body>
</html>