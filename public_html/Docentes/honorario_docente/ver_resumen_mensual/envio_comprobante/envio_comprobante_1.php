<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("revision_mensual_honorario_Docente->envio_comprobante");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
	require("../../../../../funciones/VX.php");
	require("../../../../../funciones/conexion_v2.php");
	require("../../../../../funciones/funciones_varias.php");
	require("../../../../../funciones/funciones_sistema.php");
	require('../../../../libreria_publica/fpdf/mc_table.php');
	require("../../../../libreria_publica/PHPMailer_v5.1/class.phpmailer.php");
	$ARRAY_MESES=array(1=>"Enero",
					2=>"Febrero",
					3=>"Marzo",
					4=>"Abril",
					5=>"Mayo",
					6=>"Junio",
					7=>"Julio",
					8=>"Agosto",
					9=>"Septiembre",
					10=>"Octubre",
					11=>"Noviembre",
					12=>"Diciembre");	
	$sede=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["sede"]));
	$year=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["year_generacion"]));
	$mes=mysqli_real_escape_string($conexion_mysqli, base64_decode($_GET["mes"]));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php");?>
<title>Envio de aviso Honorario Docente</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 131px;
}
</style>
</head>
<body>
<h1 id="banner">Administrador - Envio Aviso Honorario Docente</h1>
<div id="link"><br />
<a href="../index.php" class="button">Volver a seleccion</a></div>
<div id="apDiv1">
<table width="85%" border="1" align="center">
    <thead>
      <tr>
        <th colspan="5">Honorario Docentes <?php echo $sede;?> Periodo [<?php echo"$mes - $year";?>]</th>
        </tr>
      </thead>
    <tbody>
      <tr>
        <td >N</td>
        <td >Rut</td>
        <td >Nombre</td>
        <td >Apellido</td>
        <td >estado</td>
      </tr>
<?php
	/////////////////configuracion inicial/////////////////////
	//----------------------------------------------//
		//datos SMTP
		$host="smtp.gmail.com";
		$direccion_envio="root@cftmassachusetts.cl";
		$nombre_envio="Robot CFT Massachusetts";
		
		$user_correo="no_responder@cftmass.cl";
		$pass_correo="15_xXCo37";
		
		
		
		$enviar_copia_oculta=true;
		$email_BCC="contabilidad@cftmass.cl";

	  	$asunto="Honorarios por Docencia periodo [".$ARRAY_MESES[$mes]." - $year] Talca - CFT Massachusetts";
		$contador_envios=0;
		$contador_envio_general=0;
		//--------------------------------------------------------------//
	$cons_H="SELECT honorario_docente.* FROM honorario_docente INNER JOIN personal ON honorario_docente.id_funcionario=personal.id WHERE honorario_docente.sede='$sede' AND honorario_docente.year_generacion='$year' AND honorario_docente.mes_generacion='$mes' AND honorario_docente.total >'0' ORDER by personal.apellido_P, personal.apellido_M";
	$sqli_H=$conexion_mysqli->query($cons_H)or die($conexion_mysqli->error);
	$num_honorarios=$sqli_H->num_rows;
	$SUMA_TOTAL_HONORARIOS=0;
	if($num_honorarios>0)
	{
		$aux=0;
		//----------------------------------------------//
		while($H=$sqli_H->fetch_assoc())
		{
			$aux++;
			$condicion_email="";
			$H_id=$H["id_honorario"];
			$H_sede=$H["sede"];
			$H_mes=$H["mes_generacion"];
			$H_semestre=$H["semestre"];
			$H_year=$H["year"];
			$H_year_generacion=$H["year_generacion"];
			$H_id_funcionario=$H["id_funcionario"];
			$H_total=$H["total"];
			$H_estado=$H["estado"];
			$H_generado_contabilidad=$H["generado_contabilidad"];
			$H_id_user_generado_contabilidad=$H["id_user_generado_contabilidad"];
			$H_fecha_generado_contabilidad=$H["fecha_generado_contabilidad"];
			$H_fecha_generacion=$H["fecha_generacion"];
			$H_cod_user=$H["cod_user"];
			
			//Datos funcionarios
			$cons_DF="SELECT * FROM personal WHERE id='$H_id_funcionario' LIMIT 1";
			$sqli_DF=$conexion_mysqli->query($cons_DF)or die($conexion_mysqli->error);
				$DF=$sqli_DF->fetch_assoc();
				$F_email=$DF["email"];
				$F_email_personal=$DF["email_personal"];
				//$F_email="informatica@cftmass.cl";
				$F_rut=$DF["rut"];
				$F_nombre=$DF["nombre"];
				$F_apellido=$DF["apellido_P"]." ".$DF["apellido_M"];
				
				if(empty($F_email)){ $F_email=$F_email_personal;}
				
			$sqli_DF->free();
			if(comprobar_email($F_email))
				{
					if(DEBUG){ echo"Email Correcto inicio envio<br>";}
					$enviar_mail=true;
				}
				else
				{
					if(DEBUG){ echo"Email incorrecto no Enviar<br>";}
					$enviar_mail=false;
				}
			//--------------------------------------------------------------------//	
			$cons_HD="SELECT numero_cuotas FROM toma_ramo_docente WHERE id_funcionario='$H_id_funcionario' AND year='$H_year' AND semestre='$H_semestre' AND sede='$H_sede'";
			$sqli_HD=$conexion_mysqli->query($cons_HD);
			$msj_informacion="";
			while($HD=$sqli_HD->fetch_assoc())
			{$aux_cuota=$HD["numero_cuotas"];}
			$msj_informacion=$aux_cuota." ";
			$sqli_HD->free();
			//---------------------------------------------------------------------------///
			$SUMA_TOTAL_HONORARIOS+=$H_total;
			/////////////////////////////////////////////////////////
			
				
			///////////////////////////////////////////////////////////
			if(DEBUG){ echo"<br>--->Rut: $F_rut nombre $F_nombre $F_apellido $H_total Email: $F_email<br>";}
			
			echo'<tr>
					<td>'.$aux.'</td>
					<td>'.$F_rut.'</td>
					<td>'.$F_nombre.'</td>
					<td>'.$F_apellido.'</td>';
			//incio generacion Compraobante para Email	
			//-------------------------------------------------//
				
				
				$logo="../../../../BAses/Images/logoX.jpg";
				$borde=1;
				$letra_1=12;
				$letra_2=10;
				$autor="ACX";
				$titulo="Detalle de Honorarios";
				$zoom=75;
				
				$pdf=new PDF_MC_Table();
				$pdf->AddPage('P','Letter');
				$pdf->SetAuthor($autor);
				$pdf->SetTitle($titulo);
				$pdf->SetDisplayMode($zoom);
				
				$pdf->SetFont('Arial','',10);
				$pdf->Cell(195,6,date("d-m-Y"),$borde*0,1,'R');
				$pdf->image($logo,14,10,30,24,'jpg'); //este es el logo
				
				
				$pdf->SetFont('Arial','B',16);
				$pdf->Cell(195,20,$titulo,$borde*0,1,'C');
				//parrafo 1
				$pdf->Ln(8);
				$pdf->SetFont('Arial','B',$letra_1);
				$pdf->Cell(135,6,"Datos del Docente ",$borde,1,"L");
				$pdf->SetFont('Arial','',$letra_1);
				$pdf->Cell(30,6,"Rut",$borde,0,"L");
				$pdf->Cell(105,6,$F_rut,$borde,1,"L");
				$pdf->Cell(30,6,"Nombre",$borde,0,"L");
				$pdf->Cell(105,6,$F_nombre,$borde,1,"L");
				$pdf->Cell(30,6,"Apellido",$borde,0,"L");
				$pdf->Cell(105,6,$F_apellido,$borde,1,"L");
				
				
				/*$cons_H="SELECT * FROM honorario_docente WHERE id_honorario='$H_id' LIMIT 1";
				$sqli_H=$conexion_mysqli->query($cons_H);
				$H=$sqli_H->fetch_assoc();
					$H_sede=$H["sede"];
					$H_mes=$H["mes_generacion"];
					$H_year=$H["year"];
					$H_year_generacion=$H["year_generacion"];
					$H_total=$H["total"];
					$H_estado=$H["estado"];
					$H_fecha_estado=$H["fecha_estado"];
				$sqli_H->free();	*/
				
				
				
				$pdf->Ln();
				$pdf->SetFont('Arial','B',$letra_1);
				$pdf->Cell(135,6,"Datos Honorarios",$borde,1,"L");
				$pdf->SetFont('Arial','',$letra_1);
				$pdf->Cell(30,6,"Sede",$borde,0,"L");
				$pdf->Cell(105,6,$H_sede,$borde,1,"L");
				$pdf->Cell(30,6,"Periodo",$borde,0,"L");
				$pdf->Cell(105,6,$ARRAY_MESES[$H_mes]."-".$H_year_generacion,$borde,1,"L");
				$pdf->Cell(30,6,"Total a Pagar",$borde,0,"L");
				$pdf->Cell(105,6,"$".number_format($H_total,0,",","."),$borde,1,"L");
				
				
				$cons_P="SELECT * FROM honorario_docente_pagos WHERE id_honorario='$H_id' ORDER by id desc LIMIT 1";
				$sqli_P=$conexion_mysqli->query($cons_P)or die($conexion_mysqli->error);
				$P=$sqli_P->fetch_assoc();
					$P_forma_pago=$P["forma_pago"];
					$P_fecha_pago=$P["fecha_pago"];
					$P_id_cheque=$P["id_cheque"];
					$P_cod_user=$P["cod_user"];
					$P_archivo=$P["archivo"];
				$sqli_P->free();
				
				$informacion_pago=$P_forma_pago;
				
				//cheque
				if($P_id_cheque>0)
				{
					$cons_CH="SELECT * FROM registro_cheques WHERE id='$P_id_cheque' LIMIT 1";
					$sqli_ch=$conexion_mysqli->query($cons_CH);
					$CH=$sqli_ch->fetch_assoc();
						$CH_numero=$CH["numero"];
						$CH_banco=$CH["banco"];
					$sqli_ch->free();	
					$informacion_pago.=" [Numero: $CH_numero Banco: $CH_banco]";
				}
				
			
				$pdf->Ln();
				$pdf->SetFont('Arial','B',$letra_1);
				$pdf->Cell(135,6,"Datos Pago",$borde,1,"L");
				$pdf->SetFont('Arial','',$letra_1);
				$pdf->Cell(30,6,"Forma Pago",$borde,0,"L");
				$pdf->Cell(105,6,$informacion_pago,$borde,1,"L");
				$pdf->Cell(30,6,"Fecha Pago",$borde,0,"L");
				$pdf->Cell(105,6,$P_fecha_pago,$borde,1,"L");
					
				$pdf->Ln();
				$pdf->SetFont('Arial','B',$letra_1);
				$pdf->Cell(190,5,"Detalle",$borde,1,"L");
				
				$cons_HD="SELECT * FROM honorario_docente_detalle WHERE id_honorario='$H_id'";
				$sqli_HD=$conexion_mysqli->query($cons_HD);
				$num_registros=$sqli_HD->num_rows;
				
				$pdf->SetFont('Arial','',8);
				$pdf->Cell(65,6,"Carrera",$borde,0,"L");
				$pdf->Cell(75,6,"Ramo",$borde,0,"L");
				$pdf->Cell(15,6,"Jor-Grup",$borde,0,"C");
				$pdf->Cell(15,6,"Cuota",$borde,0,"C");
				$pdf->Cell(20,6,"Valor Base",$borde,1,"R");
				
				
				$aux_total=0;
				if($num_registros>0)
				{
					while($HD=$sqli_HD->fetch_assoc())
					{
						$HD_id_carrera=$HD["id_carrera"];
						$HD_cod_asignatura=$HD["cod_asignatura"];
						$HD_jornada=$HD["jornada"];
						$HD_grupo=$HD["grupo"];
						$HD_cuota=$HD["cuota"];
						$HD_total_base=$HD["total_base"];
						$HD_cargo=$HD["cargo"];
						$HD_abono=$HD["abono"];
						$HD_glosa_cargo=$HD["glosa_cargo"];
						$HD_glosa_abono=$HD["glosa_abono"];
						$HD_semestre=$HD["semestre"];
						$HD_year=$HD["year"];
						$HD_total_a_pagar=$HD["total_a_pagar"];
						
						//-----------------------------//
						$aux_total+=$HD_total_a_pagar;
						//----------------------------//
						$cons_AS="SELECT numero_cuotas FROM toma_ramo_docente WHERE id_funcionario='$H_id_funcionario' AND id_carrera='$HD_id_carrera' AND grupo='$HD_grupo' AND jornada='$HD_jornada' AND cod_asignatura='$HD_cod_asignatura' AND semestre='$HD_semestre' AND year='$HD_year' LIMIT 1";
						$sqli_AS=$conexion_mysqli->query($cons_AS)or die($conexion_mysqli->error);
							$AS=$sqli_AS->fetch_assoc();
								$AS_numero_cuotas=$AS["numero_cuotas"];
							$sqli_AS->free();	
						//---------------------------------------------------------------------------------------//
						list($HD_ramo, $HD_nivel_ramo)=NOMBRE_ASIGNACION($HD_id_carrera, $HD_cod_asignatura);
						$HD_ramo=utf8_decode($HD_ramo);
						$HD_carrera=utf8_decode(NOMBRE_CARRERA($HD_id_carrera));
						
						
						$pdf->SetWidths(array(65,75,15,15,20));
						$pdf->SetAligns(array("L", "L", "C", "C", "R"));
						$pdf->Row(array($HD_carrera, $HD_ramo, $HD_jornada."-".$HD_grupo, $HD_cuota."/".$AS_numero_cuotas,"$ ". number_format($HD_total_a_pagar,0,",",".")));
						
						
						if($HD_cargo>0)
						{
							$pdf->SetX(20);
							$pdf->Cell(25,5,"Cargos",$borde,0,"L");
							$pdf->Cell(135,5,$HD_glosa_cargo,$borde,0,"L");
							$pdf->Cell(20,5,number_format($HD_cargo,0,",","."),$borde,1,"R");
						}
						if($HD_abono>0)
						{
							$pdf->SetX(20);
							$pdf->Cell(25,5,"Abonos",$borde,0,"L");
							$pdf->Cell(135,5,$HD_glosa_abono,$borde,0,"L");
							$pdf->Cell(20,5,number_format($HD_abono,0,",","."),$borde,1,"R");
						}
						
						
					}
					
					$aux_valor_bruto=($aux_total/0.9);
					$aux_valor_impuesto=($aux_valor_bruto-$aux_total);
					
					$pdf->ln();
					//bruto
					$pdf->Cell(170,5,"Honorarios Brutos (datos para confeccion de Boleta de Honorarios)",$borde,0,"L");
					$pdf->Cell(20,5,"$ ".number_format($aux_valor_bruto,0,",","."),$borde,1,"R");
					//impuesto
					$pdf->Cell(170,5,"10% Impuesto",$borde,0,"L");
					$pdf->Cell(20,5,"$ ".number_format($aux_valor_impuesto,0,",","."),$borde,1,"R");
					//liquido
					$pdf->Cell(170,5,"Total a Pagar",$borde,0,"L");
					$pdf->Cell(20,5,"$ ".number_format($aux_total,0,",","."),$borde,1,"R");
					
					
				}
				else
				{
					$pdf->Cell(195,5,"Sin Registros",$borde,1,"R");
				}
				
				$sqli_HD->free();	
				
				$DIRECCION["Talca"]	="3 Sur 1068";
				$DIRECCION["Linares"]="O´higgins 313";
				
				$nombre_archivo_adjunto="detalle_honorarios_".$sede."_".$mes."_".$year.".pdf";
				$adjunto=$pdf->Output($nombre_archivo_adjunto, 'S');
				$body='<img src="http://cftmassachusetts.cl/~cftmassa/BAses/Images/logo.png" alt="logo_largo" /><br><br>';
				$body.="<strong>Honorario Docente</strong><br>";
				$body.="<strong>Docente</strong> $F_nombre $F_apellido<br>";
				$body.="<strong>Fecha:</strong> ".date("d-m-Y")."<br><br>";
				
				$body.='Junto con Saludarle, le informamos que debe realizar la Boleta de Honorarios correspondiente a la docencia realizada en el CFT Massachusetts sede '.$sede.' en el mes '.$ARRAY_MESES[$mes].' del '.$year.',  según detalle de pago adjunto.<br><br>';
				$body.='La boleta debe ser extendida con la fecha del dia en que se confecciona la boleta (NO UTILIZAR FECHAS ANTERIORES) asi evitamos que su boleta quede fuera del pago de impuestos y posterior anulacion. Utilizar los siguientes datos:<br><br>';
				
				$body.='<strong>Centro de Formación Técnica Massachusetts Ltda.</strong><br>';
				$body.='<strong>Rut 89.921.100-6.</strong><br>';
				$body.='<strong>Direccion: '.$DIRECCION[$sede].', '.$sede.'</strong><br><br>';
			
				$body.='Con la siguiente glosa:<br><br>';
				$body.='<strong>DOCENCIA, MES DE '.$ARRAY_MESES[$mes].', CUOTA  '.$HD_cuota.'/5.</strong><br><br>';
				
				$body.='Favor remitirla al correo contabilidad@cftmass.cl. Una vez recibida la boleta se emitirá el cheque nominativo al docente.<br><br>';
				$body.='<strong>Nota:</strong> No se emitirá cheque si no se ha recepcionado la boleta. Ruego a Ud. cumplir con los plazos para no retrasar su pago.<br>';
				
				$body.="Cualquier consulta dirigirla a nuestro correo electrónico contabilidad@cftmass.cl o bien al Fono 071-2-225713.<br><br>";
				$body.='<img src="http://200.28.135.221/~cftmassa/BAses/Images/login_logo.png"  alt="logo" /><br>';
				$body.='Cordialmente<br>CFT Massachusetts<br>';
				$body.='<font color="red"><a href="http://www.cftmass.cl">cftmass.cl</a></font><br><br>';
				$body.='<hr size="1" width="100%" color="#CCCCCC">';
				$body.='<tt>Este Correo es generado de forma automatica por favor no lo responda<br>© CFT MASSACHUSETTS '.date("Y").'</tt>';
				
				if($enviar_mail)
				{
						$mail = new PHPMailer();
						$mail->Host = "localhost";
						// Datos del servidor SMTP
						if(DEBUG){$mail->SMTPDebug  = 2;}
						//$mail->IsSMTP(); 
						//$mail->Port = 587; 
						//$mail->SMTPSecure = "tls";
						//$mail->SMTPAuth = true; 
						//$mail->Username =$user_correo;  // Nombre de usuario del correo
						//$mail->Password = $pass_correo; // Contraseña
						
						
						$mail->From =$direccion_envio;
						$mail->WordWrap = 50; 
    					$mail->IsHTML(true); 
						$mail->AltBody ="Por favor active la vista HTML para visualizar correctamente el mensaje"; // optional, comment out and test
						$mail->FromName = $nombre_envio;
						$mail->Subject = $asunto;	
						$mail->Body = $body;
						$mail->AltBody ="Honorarios<br>";
						$mail->IsHTML(true);
						$mail->AddAddress($F_email);
						if($enviar_copia_oculta){$mail->AddBCC($email_BCC, $email_BCC);}
						//$mail->AddAttachment($adjunto, "adjunto.pdf",'base64','application/pdf');	
						$mail->AddStringAttachment($adjunto, $nombre_archivo_adjunto,'base64','application/pdf');
						$contador_envio_general++;
						if($mail->Send())
						{ 
							if(DEBUG){ echo"<br><strong>Email Enviado :)</strong><br>";}
						    $condicion_email="Enviado";
							$evento="Envio de Aviso Honorario Docente  $sede [$mes - $year] id_funcionario: $H_id_funcionario [$F_nombre $F_apellido]";
							$contador_envios++;
						}
						else
						{ 
							if(DEBUG){ echo"<br><strong>Error al Enviar Email :(</strong> ".$mail->ErrorInfo."<br>";}
							$condicion_email="No Enviado";
							$evento="Error al Enviar Aviso Honorario Docente  $sede [$mes - $year] id_funcionario: $H_id_funcionario [$F_nombre $F_apellido]";
						}
						
						@REGISTRA_EVENTO($evento);
						$mail->ClearAddresses(); 
						
					
										
					
				}
				else{$condicion_email="email incorrecto";}
				
				echo'<td>'.$condicion_email.'</td>
				</tr>';
				
			//-------------------------------------------//
			//Fin Generacion Comprobante para email
		}
		echo'<tr>
			<td colspan="5"><tt>('.$contador_envios.'/'.$contador_envio_general.') correos enviados Exitosamente...</tt></td>
			</tr>';
	}
	else
	{ echo'<tr><td colspan="5">Sin Honorarios Generados en este Periodos</td></tr>';}
	$sqli_H->free();
		
	$conexion_mysqli->close();
	@mysql_close($conexion);
}
?>
      </tbody>
  </table>
</div>
</body>
</html>