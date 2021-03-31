<? include ("../../SC/seguridad.php");?>
<? include ("../../SC/privilegio2.php");?>
<?
   if($_SESSION["rut_c"]=="")
   {
       echo"NO SESION<br>";
	   
        	

   }
   else
   {
       include("../../../funciones/conexion.php");
       include("../../../funciones/funcion.php");
       require('../../../librerias/fpdf/fpdf.php');
	   
	   $rut=$_SESSION[rut_c];
	   $carrera=$_SESSION[carrera_c];
	   $sede=$_SESSION[sede_c];
	   $ano=$_SESSION[ano_c];
	   $semestre =$_SESSION[semestre_c];
	   
	    $apoderado=$_SESSION[apoderado_c];
	    $rut_apo=$_SESSION[rutapo_c];
	    $direc_apo=$_SESSION[direcapo_c];
	    $ciu_apo=$_SESSION[ciuapo_c];
	   $tam_pers[0]=216;
		$tam_pers[1]=102;
	   $apoderado=ucwords(strtolower($apoderado));
	   $direc_apo=ucwords(strtolower($direc_apo));
	   $ciu_apo=ucwords(strtolower($ciu_apo));
	   $cons="SELECT id,apellido,nombre,direccion FROM alumno WHERE rut='$rut'and carrera='$carrera' and sede='$sede'";
	   //echo"$cons<br>";
	   
	   $sql=mysql_query($cons)or die(mysql_error());
	   while($A=mysql_fetch_array($sql))
	   {
	       $id=$A["id"]; 
	       $apellido=$A["apellido"];
		   $nombre=$A["nombre"];
		   $direccion=$A["direccion"]; 
		   
		   
		  
	   } 
	       $nombre = ucwords(strtolower($nombre));
	      $apellido = ucwords(strtolower($apellido));
		  $alumno="$nombre $apellido";
	   $conL="SELECT * FROM letras WHERE idalumn='$id' and semestre='$semestre' and ano='$ano' ORDER BY numletra";
	  // echo"$conL<br>";
	   $sql2=mysql_query($conL);
	   
		$pdf=new FPDF('P','mm',$tam_pers); /*Se define las propiedades de la página */
		$pdf->SetMargins(0,0);
		$pdf->SetAutoPageBreak(1,0);
				
	   while($B=mysql_fetch_array($sql2))
	   {
	            $numletra=$B["numletra"];
				$numcuota=$B["numcuota"];
				$fechavenc=$B["fechavenc"];
				$valor=$B["valor"];
				$valor_imp=number_format($valor,0,",",".");
				$totalcuo=$B["totalcuo"];
				$anulada=$B["anulada"];
				$fechemision=$B["fechemision"];
				
				$diaV=substr($fechavenc,-2,2);
				$diaA=substr($fechemision,-2,2);
			
				$anoV=substr($fechavenc,0,4);
				$anoA=substr($fechemision,0,4);
				
				
				$mesA=substr($fechemision,5,2);
				$mesV=substr($fechavenc,5,2);
				
				$fecha_acep="$diaA/$mesA/$anoA";
				$fecha_venc="$diaV/$mesV/$anoV";
				
				$mesV=mes_palabra((int)$mesV);
				$mesA=mes_palabra((int)$mesA);
				$sedeydia="$sede $diaA";
				$fech_pal_v="$diaV de $mesV del $anoV";
				
				if($anulada=="N")
				{
					$cantidad=num_letra($valor);
					$cantidad.=" Pesos";
					$valor_imp="$".$valor_imp;
					$pdf->AddPage(); /* Se añade una nueva página */
					$pdf->SetFont('Arial','',10); 

					//area pequeña prepicado
    				$pdf->Text(20,32,$numletra);
					$pdf->Text(7,47,$alumno);
					$pdf->Text(30,59,$fecha_acep);
					$pdf->Text(30,75,$fecha_venc);
					$pdf->Text(30,85,$valor_imp);
   					//fin area pequeña
   					//area grande prepicado
   					$pdf->Text(156,22,$fecha_venc);
   
   					$pdf->Text(87,32,$sedeydia);
   					$pdf->Text(123,32,$mesA);
   					$pdf->Text(162,32,$anoA);
   					$pdf->Text(183,32,$valor_imp);
   
  					 $pdf->Text(93,40,$fech_pal_v);
   
   					$pdf->Text(106,63,$cantidad);
   
   					$pdf->Text(94,77,$valor_imp);
   					$pdf->Text(94,83,$apoderado);
   					$pdf->Text(94,89,$rut_apo);
   
   					$pdf->Text(100,94,$direc_apo);
   					$pdf->Text(100,99,$ciu_apo);
				

       }
	
			
 
	       
	   }
	   
	    $pdf->Output(); /* El documento se cierra y se envía al navegador */
          
	   
	
	   //$fecha_venc=fecha_vencimiento($num_letra,$semestre,$f_venc,);
	      mysql_free_result($sql);
          mysql_close($conexion);
		 
	}

?>

</body>
</html>
