<? include ("../../SC/seguridad.php");?>
<? include ("../../SC/privilegio2.php");?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Imprime Contrato</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:394px;
	height:21px;
	z-index:1;
	left: 203px;
	top: 21px;
}
#Layer2 {
	position:absolute;
	width:743px;
	height:67px;
	z-index:2;
	left: 22px;
	top: 70px;
}
#Layer3 {
	position:absolute;
	width:743px;
	height:51px;
	z-index:3;
	left: 22px;
	top: 140px;
}
#Layer4 {
	position:absolute;
	width:743px;
	height:33px;
	z-index:4;
	left: 22px;
	top: 193px;
}
#Layer5 {
	position:absolute;
	width:743px;
	height:44px;
	z-index:5;
	left: 22px;
	top: 228px;
}
#Layer6 {
	position:absolute;
	width:743px;
	height:197px;
	z-index:6;
	left: 22px;
	top: 274px;
}
#Layer7 {
	position:absolute;
	width:743px;
	height:47px;
	z-index:7;
	left: 0px;
	top: 210px;
}
#Layer8 {
	position:absolute;
	width:743px;
	height:26px;
	z-index:7;
	left: 22px;
	top: 532px;
}
#Layer9 {
	position:absolute;
	width:743px;
	height:23px;
	z-index:1;
	left: 22px;
	top: 555px;
}
#Layer10 {
	position:absolute;
	width:743px;
	height:22px;
	z-index:7;
	left: 22px;
	top: 578px;
}
#Layer11 {
	position:absolute;
	width:743px;
	height:29px;
	z-index:8;
	left: 22px;
	top: 600px;
}
#Layer12 {
	position:absolute;
	width:219px;
	height:70px;
	z-index:9;
	left: 117px;
	top: 630px;
}
.Estilo1 {font-size: 10px}
#Layer13 {
	position:absolute;
	width:219px;
	height:57px;
	z-index:10;
	left: 426px;
	top: 632px;
}
#Layer14 {
	position:absolute;
	width:156px;
	height:20px;
	z-index:11;
	left: 607px;
	top: 34px;
}
#Layer15 {
	position:absolute;
	width:720px;
	height:115px;
	z-index:12;
	left: 22px;
	top: 689px;
}
.Estilo2 {font-size: 12px}
#Layer16 {
	position:absolute;
	width:604px;
	height:63px;
	z-index:13;
	left: 39px;
	top: 951px;
}
-->
</style>
</head>

<body>
<?
if(!$_POST)
{
    echo"No Post<br>";
}
else
{
    extract($_POST);
	
}
?>
<div id="Layer1">
  <div align="center"><strong>CONTRATO DE PRESTACION DE SERVICIOS </strong></div>
</div>
<div id="Layer2"> 
      <div align="justify"><span class="Estilo2">En  <? echo $especifica;?> &nbsp;entre Juan Carlos Figueroa U., RUT: 6.015.058-3, Representante Legal del Centro de Formaci&oacute;n T&eacute;cnica Massachusetts. RUT.:89.921.100-6 con domicilio en <? echo $domicilio_MM;?> de esta ciudad y Don(&ntilde;a) <? echo $alumno;?> con domicilio en <? echo "$domicilio_A";?> en adelante el Alumno, conviene el siguiente contrato de Prestaci&oacute;n de Servicios Educacionales</span>.      </div>
</div>

<div id="Layer3">
  <div align="justify"><strong>PRIMERO: </strong> <span class="Estilo2">El C.F.T Massachusetts, se obliga a impartir la carrera que el alumno opta de acuerdo a los Planes aprobados. Adem&aacute;s ofrecer&aacute; todo el uso del equipamiento Acad&eacute;mico, como Laboratorio de Computaci&oacute;n, Laboratorio de Dactilograf&iacute;a, Laboratorio de Ingl&eacute;s, Laboratorio de Dibujo T&eacute;cnico y uso de Biblioteca, seg&uacute;n necesidad de la carrera.</span></div>
</div>

<div id="Layer4">
  <div align="justify"><strong>SEGUNDO: </strong><span class="Estilo2">Don(&ntilde;a) <? echo"$nombre_comp";?> 
    se obliga a asistir a clases regularmente y a cumplir las norrmas de Evaluaci&oacute;n, Asistencia y Titulaci&oacute;n.</span></div>
</div>

<div id="Layer5">
  <div align="justify"><strong>TERCERO: </strong><span class="Estilo2">Don(&ntilde;a) <? echo"$nombre_comp";?> pagar&aacute; un arancel  de<? echo" $".$arancel;?> por el <? echo"$Semestre";?> y una matricula de<? if($pago_mat=="NO"){echo" $0";}else{echo" $".$matricula;}?> cuya fecha de vencimiento sera fijada libremente por el alumno, en cuotas detalladas al final del presente contrato.</span></div>
</div>

<div id="Layer6">
  <p align="justify"><strong>CUARTO: </strong> <span class="Estilo2">DEL RETIRO:<br />
  a)En el Semestre <?php echo $estacion_ano;?> <? echo"$ano";?>, el alumno puede retirarse de la instituci&oacute;n comunicando por escrito, sin tener ninguna obligaci&oacute;n acad&eacute;mica posterior<br />
  Administrativamente, el alumno tendr&aacute; la obligaci&oacute;n de cancelar, a contar del mes que present&oacute; su carta de aviso de retiro.</span></p>
  <div class="Estilo2" id="Layer7"> 
    <div align="justify"><strong>QUINTO:</strong> El no pago del documento en la fecha dar&aacute; derecho a la instituci&oacute;n a seguir las acciones legales que correspondan, y a suspender las prestaciones motivo de este contrato, siendo de responsabilidad del alumno los da&ntilde;os acad&eacute;micos que por este motivo se ocasione. Ad&eacute;mas, el alumno autoriza a enviar los antecedentes de su deuda vencida a los servicios de informaci&oacute;n comercial para conocimiento p&uacute;blico.</div>
  </div>
  <p class="Estilo2">b)Para el Semestre oto&ntilde;o ,el alumno tendr&aacute; derecho a solicitar la anulaci&oacute;n de las letras firmadas, comunicando por escrito, en los siguientes casos:<br />
&nbsp;&nbsp;    1.S&oacute;lo antes del d&iacute;a de inicio de clases.<br />
&nbsp;&nbsp;&nbsp;2.Por p&eacute;rdida de calidad de alumno(eliminacion)<br />
&nbsp;&nbsp;  3.En casos de fuerza mayor como: Traslado de ciudad, enfermedad u otros, debidamente justificados.<br />
<br />
c)En caso de haberse iniciado las clases, el alumno deber&aacute; regirse por el punto N&ordf; cuarto letra a), anterior.<br />
d)En cualquier caso no estipulado en los puntos anteriores, el alumno podr&aacute; exponer su problema, por escrito al Consejo Superior de la Instituci&oacute;n, el que decidir&aacute; la situaci&oacute;n en particular.</p>
</div>
<div id="Layer9">
      <strong>SEPTIMO: </strong><span class="Estilo2">Es decisi&oacute;n del C.F.T cambios de jornada de un curso cuando lo estime conveniente (s&oacute;lo para</span> <span class="Estilo2"><? echo"$jornada";?></span>)</div>
	  <div id="Layer8">
      <strong>SEXTO: </strong><span class="Estilo2">El Alumno queda matriculado en la Carrera: <? echo"$carrera";?>
      Nivel<strong>: </strong><? echo"$nivel";?>    </span></div>
      <div id="Layer11"><strong>NOVENO:</strong> <span class="Estilo2">Los valores del proceso de titulaci&oacute;n no est&aacute;n contemplados en este contrato.</span></div>
<div id="Layer12">
  <p align="center">&nbsp;________________________&nbsp; <span class="Estilo1">FIRMA ALUMNO O APODERADO <br />
  </span><span class="Estilo2">RUT.: <? echo"$rut";?>&nbsp;</span></p>
</div>
<div id="Layer10">
<strong>OCTAVO: </strong><span class="Estilo2">El Presente contrato durar&aacute; hasta el <? echo"$expira";?></span></div>


 
<div id="Layer13">
  <div align="center">
    <p>________________________<span class="Estilo1">&nbsp;</span></p>
  </div>
</div>
<div id="Layer14">
  <div align="right"><strong>FOLIO:</strong> <? echo"$folio";?></div>
</div>

<div id="Layer15">
  <table width="654" border="0">
    <tr>
      <td width="92"><div align="center">Cuotas</div></td>
      <td width="206"><div align="center">N&ordf; Letas </div></td>
      <td width="187"><div align="center">Fecha Vencimiento </div></td>
      <td width="151"><div align="center">Valor</div></td>
    </tr>
	<?
	  include("../../../funciones/funcion.php");
	 $letra_num=$num_L;
	 $ano2=$ano;
	//inicializo las variables a utilizar
	
	  
	  //++++ AQUI Obtener ultimo numero y asignar
	  
	  //lleno array con numero de letras
	  $jj=0;
	  for($ix=1;$ix<=$num_L;$ix++)
	  {
	  
		$cadena='$zzz = $num_coo'.$ix.';';
		eval($cadena);
	      $numero_letra[$jj]=$zzz;
		  $jj++;
	  }
	  
	  $S=substr($Semestre,0,1);
	  //leno array de fecha de vencimiento
	  $jj=0;
	  for($ix=1;$ix<=$num_L;$ix++)
	  {
	  
		$cadena='$yyy = $fecha_v'.$ix.';';
		eval($cadena);
	      $fecha_venc[$jj]=$yyy;
		  $jj++;
	  }
		//leno array de valores
	$jj=0;
	  for($ix=1;$ix<=$num_L;$ix++)
	  {
	  
		$cadena='$xxx = $valor'.$ix.';';
		eval($cadena);
	      $valor_cuo[$jj]=$xxx;
		  $jj++;
	  }	        
	   for($in=0;$in<$num_L;$in++)
		    {
			  $aux++;
						   
			   echo'<tr>
			        <td><div align="center">'.$aux.' de '.$num_L.'</div></td>
					<td><div align="center">'.$numero_letra[$in].'</div></td>
					<td><div align="center">'.$fecha_venc[$in].'</div></td>
					<td><div align="center">$ '.number_format($valor_cuo[$in],0,",",".").'</div></td>
			        </tr>';
			   
		    }
			
			

	
	include("../../../funciones/conexion.php");
	$fsede=$_SESSION[sede_c];
	$conX="SELECT idalumn,semestre,ano,totalcuo FROM letras WHERE sede='$fsede'";
	$sql= mysql_query($conX)or die("no valor");
	$encontrado=0;
	while($A= mysql_fetch_array($sql))
	{
	
	    $totalcuo=$A["totalcuo"]; 
		$idalumn=$A["idalumn"]; 
		$semestre=$A["semestre"];
		$anoX=$A["ano"];
		
		//echo"<br>$idalumn  $ocultoida<br> $semestre  $S<br> $anoX $ano<br>";
	
	   if(($idalumn==$ocultoida)and($semestre==$S)and($anoX==$ano))
	   {
	        $encontrado=1;
			//echo"Encontrado<br>";
			break;
	   }
	}
	$fecha=fecha_mysql();
	//echo"+++$totalcuo++++<br>";
	
	if($encontrado==1)
	{
	     //si es el mismo numero de cuotas
	     if($num_L==$totalcuo)
			 {
			   //echo"IGUAL NUMERO<br>";
		       for($i=0;$i<$num_L;$i++)
	            {
	             $n_cuo=$i+1;
				 //cambio formato de fecha
				 $fecha_v=fecha_mysql(false,$fecha_venc[$i]);
				 
	             $cons="UPDATE letras SET numletra='$numero_letra[$i]', idalumn='$ocultoida', numcuota='$n_cuo', fechavenc='$fecha_v', valor='$valor_cuo[$i]',deudaXletra='$valor_cuo[$i]', ano='$ano', semestre='$S', anulada='N', pagada='N', totalcuo='$num_L', fechemision='$fecha' WHERE idalumn='$ocultoida' and semestre='$S' and ano='$ano'and numcuota='$n_cuo' and sede='$fsede'";
	             //echo"-> $cons<br><br>";
				 mysql_query($cons)or die(mysql_error());
		        }
				//echo"fin iguales<br>";
			}
			//fin igual umero cuotas
			
			//inicio mayor numero cuotas que existente	
			
			 if($num_L > $totalcuo)
			 {
			    //echo"Mayor<br>";
				//echo"$totalcuo  $num_L $n_cuo<br>";
				
				$w=0;
			    for($j=1;$j <= $num_L;$j++)
				{
				   
				   if($j <= $totalcuo)
				   {
				   
				        $fecha_v=fecha_mysql(false,$fecha_venc[$w]);
						
				        $consM="UPDATE letras SET numletra='$numero_letra[$w]', idalumn='$ocultoida', numcuota='$j', fechavenc='$fecha_v', valor='$valor_cuo[$w]',deudaXletra='$valor_cuo[$w]', ano='$ano', semestre='$S', anulada='N', pagada='N', totalcuo='$num_L', fechemision='$fecha' WHERE idalumn='$ocultoida' and semestre='$S' and ano='$ano'and numcuota='$j' and sede='$fsede'";
				        //echo"<br> UPDATE <br>";
						//echo"<br>==$consM<br>";
						
				   }
				   
				   if($j > $totalcuo)
				   {
				       $fecha_v=fecha_mysql(false,$fecha_venc[$w]);
					   
					   $consM="INSERT INTO letras(numletra, idalumn, numcuota, fechavenc, valor, deudaXletra,ano, semestre, totalcuo, fechemision, sede) VALUES('$numero_letra[$w]', '$ocultoida', '$j', '$fecha_v', '$valor_cuo[$w]', '$valor_cuo[$w]','$ano', '$S', '$num_L', '$fecha', '$fsede')";
					  // echo"<br>INSERT<br>";
					  // echo"-->$consM<br>";
					   
				   }
				   mysql_query($consM)or die(mysql_error());
				   $w++;
				  
				}
				
			 
			 }
			 //fin mayor numero cuotas que existente
			 
			 //inicio menor numero cuotas que existen
			 if($num_L < $totalcuo)
			 {
			    //echo"Menor $num_L  $totalcuo $n_cuo<br>";
				$k=0;
				for($j=1;$j <= $totalcuo;$j++)
				{
				   // echo"J: $j<br>";
				    if($j<=$num_L)
				   {
				      $fecha_v=fecha_mysql(false,$fecha_venc[$k]);
					  
				       $consD="UPDATE letras SET numletra='$numero_letra[$k]', idalumn='$ocultoida', numcuota='$j', fechavenc='$fecha_v', valor='$valor_cuo[$k]', deudaXletra='$valor_cuo[$k]', ano='$ano', semestre='$S', anulada='N', pagada='N', totalcuo='$num_L', fechemision='$fecha' WHERE idalumn='$ocultoida' and semestre='$S' and ano='$ano'and numcuota='$j' and sede='$fsede'";
				      // echo"<br>UPDATE<br>";
					  // echo"==$consD<br>";
				   }
				   
				   if($j >$num_L)
				   {
				        
						$consD="DELETE FROM letras WHERE idalumn='$ocultoida' and semestre='$S' and ano='$ano'and numcuota='$j'and sede='$fsede'";
						//echo"<br>DELETE<br>";
						//echo" X $consD<br>";
				   }
				   mysql_query($consD)or die(mysql_error());
				   $k++;   			
				}
				
			 }
			 		 
	      }  
	   //fin menor numero
	   //inicion no hay registro
	else
	{
	    for($i=0;$i<$num_L;$i++)
	      {
	         $n_cuo=$i+1;
			 
			 $fecha_v=fecha_mysql(false,$fecha_venc[$i]);
			 
	         $cons="INSERT INTO letras(numletra,idalumn,numcuota,fechavenc,valor,deudaXletra,ano,semestre,totalcuo,fechemision,sede) VALUES('$numero_letra[$i]','$ocultoida','$n_cuo','$fecha_v','$valor_cuo[$i]','$valor_cuo[$i]','$ano','$S','$num_L','$fecha','$fsede')";
	        // echo"-> $cons<br>";
	         mysql_query($cons) or die(mysql_error());
	      }
	}
	//inicio graba en tabla contabilidad
	
	$consult="SELECT * FROM contrato";
	$sqlcc=mysql_query($consult);
	while($X=mysql_fetch_array($sqlcc))
	{
	    $idalumn_c=$X["idalumn"];
		$semestre_c=$X["semestre"];
		$ano_c=$X["ano"];
	}
	//echo"<br>$idalumn_c = $ocultoida<br>$semestre_c = $S <br> $ano_c $ano<br>";
	if(($idalumn_c==$ocultoida)and($semestre_c==$S)and($ano_c==$ano))
	{
	     $consulta="UPDATE contrato SET foliocontrato='$folio', totaldeuda='$arancel'  WHERE idalumn='$ocultoida' and semestre='$S' and ano='$ano'";
	}	  
	else
	{
	    $consulta="INSERT INTO contrato (idalumn,foliocontrato, semestre, ano, totaldeuda) VALUES('$ocultoida','$folio','$S','$ano','$arancel')";
	}
	//echo"$consulta<br>";
	$fcomentario=str_inde($fcomentario);
	graba_comentario($ocultoida,$fcomentario);
	G_nivel($ocultoida,$nivel);
//cambiar mensajes
//mensaje descuento
	if(($descuento=="")or($descuento=="0"))
	{
		$descuento="0";
		$msj_d="<br/>";
	}
	else
	{
		$msj_d="<br/>Descuento: $descuento% Motivo: $fcomentario<br/>";
	}
	if($apo_alumn=="")
	{
		$msj_sos="Sostenedor: Alumno.";
	}
	else
	{
		$msj_sos="Sostenedor: $apo_alumn";
	}
	
	switch($pago_mat)
	{
		case "contado":
			cancela_mat($num_boleta,$valor_boleta,$fecha_boleta);
			echo'<tr><td colspan="4"><br>Matricula Numero Boleta: '.$num_boleta.' Fecha: '.$fecha_boleta.' Valor: $'.$valor_boleta.''.$msj_d.''.$msj_sos.'</td></tr>';
			break;
		case "Letra":
			echo'<tr><td colspan="4">'.$msj_d.''.$msj_sos.'</td></tr>';
			break;
		case "NO":
			echo'<tr><td colspan="4">'.$msj_d.''.$msj_sos.'</td></tr>';
			break;		
	}
	mysql_query($consulta);
	mysql_close($conexion);
	//inicializo sesion util para letra
	$_SESSION[ano_c]=$ano;
	$_SESSION[semestre_c]=$S;
	?>
</table></div>
</body>
</html>
