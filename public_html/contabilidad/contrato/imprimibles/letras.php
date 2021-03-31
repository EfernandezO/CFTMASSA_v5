<?php include ("../../../SC/seguridad.php");?>
<?php include ("../../../SC/privilegio2.php");?>
<?
   if(!$_SESSION["FINANZAS"]["GRABADO"])
   {
       echo"NO SESION<br>";
   }
   else
   {
       include("../../../../funciones/conexion.php");
       include("../../../../funciones/funcion.php");
       require('../../../../librerias/fpdf/fpdf.php');
	   
	  
	   $rut=$_SESSION["FINANZAS"]["rut_alumno"];
	   $carrera=$_SESSION["FINANZAS"]["carrera_alumno"];
	   $sede=$_SESSION["FINANZAS"]["sede_alumno"];
	   $ano=end(explode("-",$_SESSION["FINANZAS"]["fecha_inicio"]));
	   $semestre =$_SESSION["FINANZAS"]["semestre"];
	   $opcion_matricula=$_SESSION["FINANZAS"]["opcion_matricula"];
	   
	    $apoderado="apoderado";
	    $rut_apo="rut apoderado";
	    $direc_apo="direccion apoderado";
	    $ciu_apo="ciudad apoderado";
		
	   $tam_pers[0]=215.9;
		$tam_pers[1]=101.6;
		$fecha_actual=date("d-m-Y");
	   $zoom=82;
	       $id_alumno=$_SESSION["FINANZAS"]["id_alumno"];
	       $apellido=$_SESSION["FINANZAS"]["apellido_alu"];
		   
		   $nombre=$_SESSION["FINANZAS"]["nombre_alu"];
		   $direccion=$_SESSION["FINANZAS"]["direccion_alu"];
	       $nombre = ucwords(strtolower($nombre));
	      $apellido = ucwords(strtolower($apellido));
		  $alumno="$nombre $apellido";
		  
	   //para determinar los datos de quie pondo al final
	   $paga_letra=$_SESSION["FINANZAS"]["paga_letra"];
	   switch($paga_letra)
	   {
	   		case"alumno":
				$aux_nombre=$alumno;
				$aux_rut=$rut;
				$aux_direccion=$_SESSION["FINANZAS"]["direccion_alu"];
				$aux_ciudad=$_SESSION["FINANZAS"]["ciudad_alu"];
				break;
			case"apoderado":
				$aux_nombre=$_SESSION["FINANZAS"]["nombreC_apo"];
				$aux_rut=$_SESSION["FINANZAS"]["rut_apo"];
				$aux_direccion=$_SESSION["FINANZAS"]["direccion_apo"];
				$aux_ciudad=$_SESSION["FINANZAS"]["ciudad_apo"];
				break;	
	   }
	   /////////////////////////////////////
	   
	   
		$pdf=new FPDF('P','mm',$tam_pers); /*Se define las propiedades de la página */
		$pdf->SetMargins(0,0);
		$pdf->SetAutoPageBreak(1,0);
		$pdf->SetDisplayMode($zoom);		
		$aux_i=0;//para avanzar array	
		$totalcuo="999";	
	   	foreach($_SESSION["FINANZAS"]["num_letras"] as $n => $valor)
	   {
	   
	            $aux_numletra=$valor;
				$aux_fechavenc=$_SESSION["FINANZAS"]["fechaV_letras"][$aux_i];
				$aux_valor=$_SESSION["FINANZAS"]["valor_letras"][$aux_i];
				$valor_imp=number_format($aux_valor,0,",",".");
				
				$fecha_emision=$fecha_actual;
				
				$array_fechaV=explode("-",$aux_fechavenc);
				$array_fechaE=explode("-",$fecha_emision);
				
				$diaV=$array_fechaV[0];
				$diaA=$array_fechaE[0];
			
				$anoV=$array_fechaV[2];
				$anoA=$array_fechaE[2];
				
				
				$mesA=$array_fechaE[1];
				$mesV=$array_fechaV[1];
				
				
				$mesV=mes_palabra((int)$mesV);
				$mesA=mes_palabra((int)$mesA);
				
				$sedeydia="$sede, $diaA";
				$fech_pal_v="$diaV de $mesV del $anoV";
				
					$cantidad=num_letra($aux_valor);
					$cantidad.=" Pesos";
					$valor_imp="$".$valor_imp;
					$pdf->AddPage(); /* Se añade una nueva página */
					$pdf->SetFont('Arial','',10); 

					//area pequeña prepicado
    				$pdf->Text(14,29,$aux_numletra);
					$pdf->Text(5,45,$alumno);
					$pdf->Text(30,56,$fecha_emision);
					$pdf->Text(30,74,$aux_fechavenc);
					$pdf->Text(30,82,$valor_imp);
   					//fin area pequeña
					
   					//area grande prepicado
   					$pdf->Text(154,20,$aux_fechavenc);
   					$pdf->Text(87,29,$sedeydia);
					
   					$pdf->Text(123,29,$mesA);
   					$pdf->Text(162,29,$anoA);
   					$pdf->Text(185,29,$valor_imp);
   					
  					 $pdf->Text(93,38,$fech_pal_v);
   					$pdf->Text(106,61,$cantidad);
					
   					$pdf->Text(94,75,$valor_imp);
					
   					$pdf->Text(94,80,$aux_nombre);
   					$pdf->Text(94,85,$aux_rut);
					$pdf->Text(100,90,$sede);
   					$pdf->Text(100,95,$aux_direccion.", ".$aux_ciudad);
   					//$pdf->Text(100,97,$aux_ciudad);
					
				$aux_i++;	
				//break;
				   
	   }
	   if($opcion_matricula=="LETRA")
	   {
	   		$pdf->AddPage(); /* Se añade una nueva página */
			$pdf->SetFont('Arial','',10); 
			$numero_letra=$_SESSION["FINANZAS"]["num_letra_mat"];
			$fecha_vencimiento=$_SESSION["FINANZAS"]["fecha_vence_mat"];
			$array_fechaV_mat=explode("-",$fecha_vencimiento);
			$fecha_vencimiento_imp=$array_fechaV_mat[2]."-".$array_fechaV_mat[1]."-".$array_fechaV_mat[0];
			$fechaV_palabra=$array_fechaV_mat[0]." de ".mes_palabra((int)$array_fechaV_mat[1])." del ".$array_fechaV_mat[2];
			
			$valor_matricula=$_SESSION["FINANZAS"]["matricula"];
			$valor_palabra=num_letra($valor_matricula);
			$valor_palabra.=" Pesos";
			$valor_mat_imp="$".number_format($valor_matricula,0,",",".");
					
					
					//area pequeña prepicado
    				$pdf->Text(14,29,$numero_letra);
					$pdf->Text(5,45,$alumno);
					$pdf->Text(30,56,$fecha_emision);
					$pdf->Text(30,74,$fecha_vencimiento_imp);
					$pdf->Text(30,82,$valor_mat_imp);
   					//fin area pequeña
					
   					//area grande prepicado
   					$pdf->Text(154,20,$fecha_vencimiento_imp);
   
   					$pdf->Text(87,29,$sedeydia);
   					$pdf->Text(123,29,$mesA);
   					$pdf->Text(162,29,$anoA);
   					$pdf->Text(185,29,$valor_imp);
   
  					 $pdf->Text(93,38,$fechaV_palabra);
   
   					$pdf->Text(106,61,$valor_palabra);
   					$pdf->Text(94,75,$valor_mat_imp);
					
   					$pdf->Text(94,80,$aux_nombre);
   					$pdf->Text(94,85,$aux_rut);
   					$pdf->Text(100,90,$sede);
   					$pdf->Text(100,95,$aux_direccion.", ".$aux_ciudad);
	   }
	    $pdf->Output(); /* El documento se cierra y se envía al navegador */
	}
?>