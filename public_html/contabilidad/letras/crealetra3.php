<? include ("../../SC/seguridad.php");?>
<? include ("../../SC/privilegio2.php");?>
<?
    if(!$_POST)
	{
	    echo"No HAY Valores Para Generar Letra<br>";
	}
	else
	{
	    extract($_POST);
		include("../../../funciones/funcion.php");
		include("../../../funciones/conexion.php");
		require('../../../librerias/fpdf/fpdf.php');
		
		$consL="SELECT numletra FROM letras";
  		$sqlL=mysql_query($consL);
  		$encontrado=0;
  		while($B=mysql_fetch_array($sqlL))
  		{
      		$num_letra=$B["numletra"];
	  		 //echo"$num_letra  == $ocunum_letra<br>";
	  
	  if($ocunum_letra==$num_letra)
	  {
	      $encontrado=1;
		  break;
	  }
	  
  }
  if($encontrado==1)
  {
		$error=1;
  }
  else
  {
       $fecha_v=fecha_mysql(false,$ocuvencimiento);
	   $fecha_a=fecha_mysql(false,$ocuaceptacion);
	   
       $cons="INSERT INTO letras (numletra,idalumn,numcuota,fechavenc,valor,deudaXletra,ano,semestre,fechemision,sede) VALUES('$ocunum_letra','$ocu_ida',$ocu_numcuota,'$fecha_v','$ocuvalor','$ocuvalor','$ocuanoA','$ocusemestre','$fecha_a','$ocusede')";
	   
	  //echo"$cons<br>";
	   
	 mysql_query($cons)or die(mysql_error());
	  mysql_free_result($sqlL);
  		mysql_close($conexion);
		 
	 $ocumesA=mes_palabra($ocumesA);
		$ocumesV=mes_palabra($ocumesV);
		$cantidad=num_letra($ocuvalor)." Pesos";;
		$tam_pers[0]=216;
		$tam_pers[1]=102;
		
		$pdf=new FPDF('P','mm',$tam_pers); 
		$pdf->SetMargins(0,0);
		$pdf->SetAutoPageBreak(1,0);
		$fech_pal_v="$ocudiaV de $ocumesV del $ocuanoV";
		$sedeydia="$ocusede $ocudiaA ";
		$ciudad_apo=ucwords(strtolower($ciudad_apo));
		$direc_apo=ucwords(strtolower($direc_apo));
		$ocuapoderado=ucwords(strtolower($ocuapoderado));
		$ocuvalor2="$".$ocuvalor;
		
		$pdf->AddPage(); /* Se añade una nueva página */
		
					$pdf->SetFont('Arial','',10); 

					//area pequeña prepicado
    				$pdf->Text(20,32,$ocunum_letra);
					$pdf->Text(7,47,$ocualumno);
					$pdf->Text(30,59,$ocuaceptacion);
					$pdf->Text(30,75,$ocuvencimiento);
					$pdf->Text(30,85,$ocuvalor2);
   					//fin area pequeña
   					//area grande prepicado
   					$pdf->Text(156,22,$ocuvencimiento);
   
   					$pdf->Text(87,32,$sedeydia);
   					$pdf->Text(123,32,$ocumesA);
   					$pdf->Text(162,32,$ocuanoA);
   					$pdf->Text(183,32,$ocuvalor);
   
  					 $pdf->Text(93,40,$fech_pal_v);
   
   					$pdf->Text(106,63,$cantidad);
   
   					$pdf->Text(94,77,$ocuvalor);
   					$pdf->Text(94,83,$ocuapoderado);
   					$pdf->Text(94,89,$rut_apo);
   
   					$pdf->Text(100,94,$direc_apo);
   					$pdf->Text(100,99,$ciudad_apo);
					$pdf->Output();

  }
  	
				
	}

?>