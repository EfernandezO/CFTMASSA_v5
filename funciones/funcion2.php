<?php
function ingresos_egresos($tipo_cons,$fecha_corte,$tipo_doc,$sede)
{
	
	//genero consulta adecuada
	if($sede=="Ambas")
	{
		if($tipo_doc=="T")
		{
			switch($tipo_cons)
			{
					case "D":
						$consIE="SELECT * FROM pagos WHERE fechapago ='$fecha_corte'";
						break;
					
					case "M":
					
						$fecha_ini=fecha_inicio_MA($fecha_corte);
						$consIE="SELECT * FROM pagos WHERE fechapago BETWEEN '$fecha_ini' AND '$fecha_corte'";
						break;
					
					case "A":
						$fecha_ini=fecha_inicio_MA($fecha_corte,"A");
						$consIE="SELECT * FROM pagos WHERE fechapago BETWEEN '$fecha_ini' AND '$fecha_corte'";
						break;
				
			}	
		}
		else
		{
				switch($tipo_cons)
				{
					case "D":
					$consIE="SELECT * FROM pagos WHERE fechapago ='$fecha_corte' AND tipodoc='$tipo_doc'";
					break;
					
					case "M":
					
					$fecha_ini=fecha_inicio_MA($fecha_corte);
					$consIE="SELECT * FROM pagos WHERE  tipodoc='$tipo_doc' AND fechapago BETWEEN '$fecha_ini' AND '$fecha_corte'";
					break;
					
					case "A":
						$fecha_ini=fecha_inicio_MA($fecha_corte,"A");
						$consIE="SELECT * FROM pagos WHERE tipodoc='$tipo_doc' AND fechapago BETWEEN '$fecha_ini' AND '$fecha_corte'";
						break;
				
				}
		
		}
	}
	else
	{
		if($tipo_doc=="T")
		{
			switch($tipo_cons)
			{
					case "D":
						$consIE="SELECT * FROM pagos WHERE fechapago ='$fecha_corte' AND sede='$sede'";
						break;
					
					case "M":
					
						$fecha_ini=fecha_inicio_MA($fecha_corte);
						$consIE="SELECT * FROM pagos WHERE sede='$sede' AND fechapago BETWEEN '$fecha_ini' AND '$fecha_corte'";
						break;
					
					case "A":
						$fecha_ini=fecha_inicio_MA($fecha_corte,"A");
						$consIE="SELECT * FROM pagos WHERE sede='$sede' AND fechapago BETWEEN '$fecha_ini' AND '$fecha_corte'";
						break;
				
			}	
		}
		else
		{
				switch($tipo_cons)
				{
					case "D":
					$consIE="SELECT * FROM pagos WHERE fechapago ='$fecha_corte' AND tipodoc='$tipo_doc' AND sede='$sede'";
					break;
					
					case "M":
					
					$fecha_ini=fecha_inicio_MA($fecha_corte);
					$consIE="SELECT * FROM pagos WHERE  tipodoc='$tipo_doc' AND sede='$sede' AND fechapago BETWEEN '$fecha_ini' AND '$fecha_corte'";
					break;
					
					case "A":
						$fecha_ini=fecha_inicio_MA($fecha_corte,"A");
						$consIE="SELECT * FROM pagos WHERE tipodoc='$tipo_doc' AND sede='$sede' AND fechapago BETWEEN '$fecha_ini' AND '$fecha_corte'";
						break;
				
				}
		
		}
	}
	//fin de genera consulta
	
	//echo"<b>$consIE</b><br>";
	
	$sqlIE=mysql_query($consIE) or die("FUNCION 2-> ".mysql_error());
	$i=0;
	$j=0;
	$ingreso_array=array();
	$egreso_array=array();
	while($IE=mysql_fetch_assoc($sqlIE))
	{
		//$num_letra=$IE["numletra"];
		$id_pago=$IE[idpago];
		$id_boleta=$IE["id_boleta"];
		$fecha_pago=$IE["fechapago"];
		$valor=$IE["valor"];
		$tipo_doc=$IE["tipodoc"];
		$forma_pago=$IE["forma_pago"];
		//echo"FPX-> $forma_pago<br>";
		$glosa=$IE["glosa"];
		$movimiento=ucwords(strtolower($IE["movimiento"]));
		$por_concepto=$IE["por_concepto"];
		$aux_num_documento=$IE["aux_num_documento"];
		
		switch($movimiento)
		{
			case"I":
				//echo"guardo_ingreso<br>";
				$ingreso_array["id_pago"][$i]=$id_pago;
				$ingreso_array["id_boleta"][$i]=$id_boleta;
				$ingreso_array["fechapago"][$i]=$fecha_pago;
				$ingreso_array["valor"][$i]=$valor;
				$ingreso_array["tipodoc"][$i]=$tipo_doc;
				$ingreso_array["forma_pago"][$i]=$forma_pago;
				$ingreso_array["glosa"][$i]=$glosa;
				$ingreso_array["movimiento"][$i]=$movimiento;
				$ingreso_array["por_concepto"][$i]=$por_concepto;
				$ingreso_array["aux_num_documento"][$i]=$aux_num_documento;
				$i++;
			break;
			case"E":
				//echo"Guardo_egreso<br>";
				$egreso_array["id_pago"][$j]=$id_pago;
				$egreso_array["id_boleta"][$j]=$id_boleta;
				$egreso_array["fechapago"][$j]=$fecha_pago;
				$egreso_array["valor"][$j]=$valor;
				$egreso_array["tipodoc"][$j]=$tipo_doc;
				$egreso_array["forma_pago"][$j]=$forma_pago;
				$egreso_array["glosa"][$j]=$glosa;
				$egreso_array["movimiento"][$j]=$movimiento;
				$egreso_array["por_concepto"][$j]=$por_concepto;
				$egreso_array["aux_num_documento"][$j]=$aux_num_documento;
				$j++;
			break;
		}	
		
	}
	mysql_free_result($sqlIE);
	//var_export($ingreso_array);
	//echo"<br><br>";
	//var_export($egreso_array);
	return array($ingreso_array,$egreso_array);
}




function fecha_inicio_MA($fecha,$opcion="M")
{
	list($año,$mes,$dia)=explode("-",$fecha);
	
	$finicioM="$año-$mes-01";
	$finicioA="$año-01-01";
	
	if($opcion=="M")
	{
		return($finicioM);
	}
	else
	{
		return($finicioA);
	}	
}
?>