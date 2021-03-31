<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("deudores_mensualidad_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
set_time_limit(600);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Carta Aviso cobranza - Alumno</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
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
<?php
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funciones_sistema.php");
	require("../../../../funciones/class_LISTADOR_ALUMNOS.php");
	require("../../../../funciones/VX.php");
	require("../../../libreria_publica/PHPMailer6/class_EMAIL.php");
	require("../../../../funciones/funciones_varias.php");
if($_POST)
{
	$sede=$_POST["sede"];
	$id_carrera=$_POST["id_carrera"];
	$nivel=$_POST["nivel"];
	$jornada=$_POST["jornada"];
	$grupo=$_POST["grupo"];
	$fecha_corte=$_POST["fecha_corte"];
	$yearIngresoCarrera=$_POST["yearIngresoCarrera"];
	$year_cuotas=$_POST["year_cuotas"];
	$opcion=$_POST["opcion"];
	
	$mesActual=date("m");
	$yearActual=date("Y");
	
	$semestreActual=1;
	if($mesActual>=8){$semestreActual=2;}
	
	$semestreActual=1;
	
	$dias_plazo=$_POST["dias_plazo"];
	
	$fecha_limite=date("Y-m-d", strtotime("$fecha_corte +$dias_plazo days"));///fecha limite =fecha corte +15 dias
	$year_cuotas=$_POST["year_cuotas"];
	/////////////
	$fecha_actual=date("Y-m-d");
}
?>
<body>
<h1 id="banner">Administrador - Envio Carta Aviso Cobranza</h1>
<div id="link"><br />
<a href="../listador_deudores/index.php" class="button">Volver a Seleccion</a><br />
<br />
<a href="#" class="button" onclick="window.print();">Imprimir </a></div>
<div id="apDiv1">
  <table width="100%" align="center">
<thead>
    <tr>
   	 <th colspan="8">Listado Alumnos Seleccionados (contrato <?php echo "$semestreActual - $yearActual";?>)<br /> Carrera: <?php echo NOMBRE_CARRERA($id_carrera);?> Nivel: <?php echo $nivel;?>
					</th>
    </tr>
</thead>
<tbody>
<tr>
<td>N</td>
<td>Rut</td>
<td>Nombre</td>
<td>Apellidos</td>
<td>Nivel</td>
<td>Carrera</td>
<td>Email</td>
<td>Estado</td>
</tr>
<?php
if($_POST)
{

	
	$num_email_enviados=0;
	//datos usuario enviar
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	$cons_DU="SELECT email FROM personal WHERE id='$id_usuario_actual' LIMIT 1";
	$sqli_DU=$conexion_mysqli->query($cons_DU)or die($conexion_mysqli->error);
		$UA=$sqli_DU->fetch_assoc();
		$UA_email=$UA["email"];
	$sqli_DU->free();	
	//----------------------------------------------------------------//
	///se envia una copia de cada mensaje a
	$enviar_mail_CCO=true;
	$email_destino_copia_oculta="soporte@cftmass.cl";
	//----------------------------------------------------------------//
	/////Registro ingreso///	 
	if(DEBUG){ var_dump($_POST);}
	
	
	////////////
	
	 $evento="Envio de Email aviso X email a Alumnos sede: $sede id_carrera: $id_carrera";
	 REGISTRA_EVENTO($evento);
	
	
	if($year_cuotas!="0")
	{ $condicion_year_cuota="AND letras.ano='$year_cuotas'";}
	else{ $condicion_year_cuota="";}
	
	
	
	$LISTA = new LISTADOR_ALUMNOS();
	
	$LISTA->setDebug(DEBUG);
	
	$LISTA->setGrupo($grupo);
	$LISTA->setId_carrera($id_carrera);
	$LISTA->setJornada($jornada);
	$LISTA->setNiveles($nivel);
	$LISTA->setSede($sede);
	$LISTA->setYearIngressoCarrera($yearIngresoCarrera);
	$LISTA->setSituacionAcademica("A");
	
	$LISTA->setSemestreVigencia($semestreActual);
	$LISTA->setYearVigencia($yearActual);
	
	
	if(DEBUG){echo "Total Alumnos ".$LISTA->getTotalAlumno()."<br>";}
	
	$totalAlumnos=$LISTA->getTotalAlumno();
	if($totalAlumnos>0){
	
		$contadorAlumnos=0;
		$mes_actual=abs(date("m"));
		$mes_actual_label=$mes_actual;
		$fecha=date("d")." de ".$mes_actual_label." del ".date("Y");
		$DIRECCION["Talca"]="3 Sur #1068";
		$DIRECCION["Linares"]="O'Higgins #313";
		foreach($LISTA->getListaAlumnos() as $n => $auxAlumno)
		{
			
			$id_alumno=$auxAlumno->getIdAlumno();
			$rut_alumno=$auxAlumno->getRut();
			$nombre=$auxAlumno->getNombre();
			$apellidos=$auxAlumno->getApellido_P()." ".$auxAlumno->getApellido_M();
			$emailAlumno=$auxAlumno->getEmail();
			
			$id_carrera_alumno=$auxAlumno->getIdCarreraPeriodo();
			$nivel_alumno=$auxAlumno->getNivelAlumnoPeriodo();
			$jornada_alumno=$auxAlumno->getJornadaPeriodo();
			$situacion_alumno=$auxAlumno->getSituacionAlumnoPeriodo();
		
			//----------------------------------------------------------------------//
			$cons_cuotas="SELECT * FROM letras WHERE idalumn='$id_alumno' AND fechavenc<='$fecha_corte' AND anulada='N' AND pagada <>'S' $condicion_year_cuota ORDER by id";

			$sqli_cuotas=$conexion_mysqli->query($cons_cuotas);
			$num_cuotas=$sqli_cuotas->num_rows;
			if(DEBUG){ echo"cuotas-> $cons_cuotas<br>Num Cuotas: $num_cuotas<br>";}
			$deuda_alumno=0;
			while($Cx=$sqli_cuotas->fetch_assoc())
			{
				$Cx_deuda=$Cx["deudaXletra"];
				$deuda_alumno+=$Cx_deuda;
			}
			$sqli_cuotas->free();
					
			//////------------------------/correo/------------------//////////
			$cuerpo='<img src="http://intranet.cftmassachusetts.cl/BAses/Images/logo_cft.jpg" width="150" height="120" alt="logo" /><br><br>';
			$cuerpo.=utf8_decode('Sr(ita) <b>'.$nombre.' '.$apellidos.'</b><br>Carrera: '.NOMBRE_CARRERA($id_carrera_alumno).'<br><br>');
			$cuerpo.='Estimado(a) Alumno(a)<br><br>';
			$cuerpo.='Junto con saludar, nos ponemos en contacto con Ud. por las mensualidades establecidas en su contrato de '.utf8_decode("prestación").' de servicios con CFT Massachusetts.<br><br>';
			$cuerpo.=utf8_decode('El Motivo de la comunicacion es que hasta la fecha no hemos recibido el pago de las cuotas pendientes ('.$num_cuotas.' Cuotas), por lo que le solicitamos cancelar lo antes posible')."<br><br>";
			
			$cuerpo.=utf8_decode('El no cumplimiento de los compromisos adquiridos en la fecha señalada, nos faculta para realizar las cobranzas notarial por el monto total del pagaré').", ".utf8_decode("además")." recuerde que el atraso en las cuotas genera intereses y gastos de cobranza.<br>";
			$cuerpo.=utf8_decode('Para conocer más detalles debe acercarse a nuestro Departamento de Finanzas o comunicarse al (071) 2 225713.')."<br><br>";
			$cuerpo.=utf8_decode('*<strong>IMPORTANTE:</strong> si al recibo de la presente ud a regularizado esta situación, rogamos hacer caso omiso de la misma')."<br><br>";
			$cuerpo.='<br> <br>
		Recuerde, para sus consultas puede escribirnos a nuestro correo '.utf8_decode("electrónico").' finanzas_talca@cftmass.cl o llamar al fono 71-2225713<br><br><a href="http://www.cftmass.cl">www.cftmass.cl</a><br><br><tt>Correo generado Automaticamente por favor no responder.<br>Fecha de envio '.date("d-m-Y").' a las '.date('H:i:s').'hrs.</tt></p>';
			
			
			
			
					
					if(empty($num_cuotas)){ $num_cuotas=0;}
					if($num_cuotas>0){$mostrar_alumno=true; if(DEBUG){echo"Utilizar Alumno<br>";}}
					else{ $mostrar_alumno=false; if(DEBUG){echo"No utilizar Alumnos<br>";}}
					
					if($mostrar_alumno)
					{
						$contadorAlumnos++;
						echo'<tr>
							<td>'.$contadorAlumnos.'</td>
							<td>'.$rut_alumno.'</td>
							<td>'.$nombre.'</td>
							<td>'.$apellidos.'</td>
							<td>'.$nivel_alumno.'</td>
							<td>'.NOMBRE_CARRERA($id_carrera_alumno).'</td>
							<td>'.$emailAlumno.'</td>';
						
						if(DEBUG){ echo $cuerpo."<br><br>";}
						$email=new EMAIL(false, $cuerpo);
					
    					$email->Subject = 'CFT Massachusetts - Cobranza';
						
						
						////copia oculta 
						
						if($enviar_mail_CCO)
						{
							$email->AddBCC($email_destino_copia_oculta);
							
							if(comprobar_email($UA_email))
							{$email->AddBCC($UA_email);}
						}
						//-------------------------------------//
							
						$email_valido=comprobar_email($emailAlumno);
						if(DEBUG){ 
						if($email_valido){ $aux_condicion_email="DEBUG email OK";}
						else{ $aux_condicion_email="DEBUG email ERROR";}
						}
						else
						{
						if($email_valido)
							{
								//agrego direccion
								$email->addAddress($emailAlumno, $nombre." ".$apellidos);
								if($email->send())
								{
									$num_email_enviados++;
									$aux_condicion_email="enviado";
									$tipo_registro="Email Masivo";
									$descripcion="Envia Aviso de Cobranza cuotas pendientes($num_cuotas) Deuda Actual($Cx_deuda)";
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
			
			//////------------------------/FIN PDF/------------------//////////
		}//fin while
		$conexion_mysqli->close();
	}
	else
	{
		echo $totalAlumnos.' Alumnos En Condicion De Moroso...<br>';
	}

}
else
{
	echo"Sin Datos Para Generar";
}
?><tr>
<td colspan="9">Numero de Email Enviados (<?php echo $num_email_enviados;?>)</td>
</tr>
</tbody>
</table><br />
</div>
</body>
</html>