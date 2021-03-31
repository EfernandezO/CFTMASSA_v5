<?php
/*Autor Elias Fernandez O
  Fecha 11/04/2008
  Programador computacional
  
  actualizacion: 17/10/2008
  Modulo de funciones Varias 
*/
//Funcion para sacar caracteres indeseados ingresa una cadena y devuelve otra sin dichos caracteres ,si cadena entrate es vacia devuelve vacio como valor del string o saliente


function str_inde($str,$Mensaje="Sin Registro")
{
// saco etiquetas 
$str=strip_tags($str);
// definir los caracteres indeseados 
//comillas simples y doble, slash y basckslash, mayor y menor que , es igual, porcentaje, punto y coma
$indeseado=array(chr(92),chr(34),chr(39),chr(47),chr(60),chr(62),chr(61),chr(37),chr(59),chr(248));
$L=strlen($str);
$ncadena="";
  for($x=0;$x<$L;$x++)
  {
     $cadena=substr($str,$x,1);
	 $y=0;
	 $error=0;
	 while($y < count($indeseado))
	 {
	    
		//echo"<br>compara : $cadena $indeseado[$y]";
	    if($cadena==$indeseado[$y])
		 { 
		 //echo" X<br>";
		   $error=1;
		   $y++;
		   break; 
		 }
		$y++;	 
	 }
	 if($error==0)
	  {
	     $ncadena.=$cadena;
	  }  	 
  }
  if($ncadena=="")
  {
      $ncadena=$Mensaje;
  }
  return($ncadena);
}
//funcion retorna una valor si el estring de entrada en vacio
function str_vacio($txt,$mensaje="Sin Registro")
{
   if($txt=="")
   {
      $txt=$mensaje;
   }
   return($txt);
}
//esta funcion retorna la fecha actual en palabras
function fecha($fecha_actual="", $mostrar_dia_de_semana=true)
{
	if(empty($fecha_actual))
	{
    	$mes = date("n");
		$semana = date("D");
	
    $mesArray = array(
            1 => "Enero", 
            2 => "Febrero", 
            3 => "Marzo", 
            4 => "Abril", 
            5 => "Mayo", 
            6 => "Junio", 
            7 => "Julio", 
            8 => "Agosto", 
            9 => "Septiembre", 
           10 => "Octubre", 
           11 => "Noviembre", 
           12 => "Diciembre");

   
    $semanaArray = array(
              "Mon" => "Lunes", 
              "Tue" => "Martes", 
              "Wed" => "Miercoles", 
              "Thu" => "Jueves", 
              "Fri" => "Viernes", 
              "Sat" => "Sabado", 
              "Sun" => "Domingo");

     $mesReturn = $mesArray[$mes];
     $semanaReturn = $semanaArray[$semana];
     $dia = date("d");
     $año = date ("Y");
	
		if($mostrar_dia_de_semana)
		{$fecha_final=$semanaReturn." ".$dia." de ".$mesReturn." del ".$año;}
		else
		{$fecha_final=$dia." de ".$mesReturn." del ".$año;}
		
     return $fecha_final;
	}
	else
	{
		$array_meses=array("01"=>"Enero",
						  "02"=>"Febrero",
						  "03"=>"Marzo",
						  "04"=>"Abril",
						  "05"=>"Mayo",
						  "06"=>"Junio",
						  "07"=>"Julio",
						  "08"=>"Agosto",
						  "09"=>"Septiembre",
						  "10"=>"Octubre",
						  "11"=>"Noviembre",
						  "12"=>"Diciembre");
		$array_dias=array(1=>"Lunes",
						  2=>"Martes",
						  3=>"Miercoles",
						  4=>"Jueves",
						  5=>"Viernes",
						  6=>"Sabado",
						  7=>"Domingo");				  
						  
		
		
		$array_fecha=explode("-",$fecha_actual);
		$year_actual=$array_fecha[0];
		$mes_actual=$array_fecha[1];
		$dia_actual=$array_fecha[2];
		$dia= date("w",mktime(0, 0, 0, $mes_actual, $dia_actual, $year_actual));
		
		if($mostrar_dia_de_semana)
		{@$fecha_final=$array_dias[$dia]." ".$dia_actual." de ".$array_meses[$mes_actual]." del ".$year_actual;}
		else
		{$fecha_final=$dia_actual." de ".$array_meses[$mes_actual]." del ".$year_actual;}
		return($fecha_final);
	}
}

// devuelve el semestre actual 
 function actual_semestre($mes=0)
	 {
	   if($mes==0)
	   {
	      $mes=date("n");
	   }
	   if(($mes >=1)and($mes <= 6))
	   {
	      $se="1ª Semestre del Año 20".date("y");
	   }
	   else
	   {
	      $se="2ª Semestre del Año 20".date("y");
	   }
	   return($se);
	 }
	 
//funcion que devuelve un arreglo de num_l elementos con las fechas en formato venc_L/MES/ano
//autoincrementa mes y año a partir de los entrantes,recibe semeste(1 o 2)	 
function fecha_vencimiento($num_L,$S,$venc_L,$ano,$mes_inicial=0)
	{
	   //echo"inicio funcion<br>";
	
	       $mes_i=$mes_inicial;
	      // echo"Segundo $mes_i";
		   $f=0;
		   $cxx=0;
		   while($f<$num_L)
		   {
		   		
				if($mes_i<10)
			    {
			   		$mes_i="0".$mes_i;
			    }
				
				if(($venc_L<10)and($cxx==0))
				{
					$venc_L="0".$venc_L;
					$cxx=1;
				}
		      $fecha="$venc_L/$mes_i/$ano";
			   if(($fecha=="29/02/$ano")or($fecha=="30/02/$ano"))
			   {
			   		$fecha="28/02/$ano";
			   }  
		       $caduca[$f]=$fecha;
			  // echo"-->$caduca[$f]<br> ";
			   $mes_i++;
			   
			   $f++;
			   if($mes_i > 12)
			   {
			       $mes_i=1;
				   $ano++;
			   }
		   }
		   
	  
	   return($caduca);
	}	 

//A esta funcion se le ingresa el mes en numero entero y retorna el correspondiente en palabra

function mes_palabra($mes)
{
     $mesArray = array(
            1 => "Enero", 
            2 => "Febrero", 
            3 => "Marzo", 
            4 => "Abril", 
            5 => "Mayo", 
            6 => "Junio", 
            7 => "Julio", 
            8 => "Agosto", 
            9 => "Septiembre", 
           10 => "Octubre", 
           11 => "Noviembre", 
           12 => "Diciembre");
		   
		   $salida=$mesArray[$mes];
		   
		   return($salida);
}

function graba_comentario($id,$valor)
{
	$cons="UPDATE alumno SET coment_beca='$valor' WHERE id='$id'";
	if(mysql_query($cons))
	{
	   $Error=0;
	}
	else
	{
	   $Error=1;
	}
	
	return($Error);
	
}

// funcion de numero a palabras

function num_letra($num, $fem = false, $dec = true) 
{ 
//if (strlen($num) > 14) die("El n?mero introducido es demasiado grande"); 
   $matuni[2]  = "dos"; 
   $matuni[3]  = "tres"; 
   $matuni[4]  = "cuatro"; 
   $matuni[5]  = "cinco"; 
   $matuni[6]  = "seis"; 
   $matuni[7]  = "siete"; 
   $matuni[8]  = "ocho"; 
   $matuni[9]  = "nueve"; 
   $matuni[10] = "diez"; 
   $matuni[11] = "once"; 
   $matuni[12] = "doce"; 
   $matuni[13] = "trece"; 
   $matuni[14] = "catorce"; 
   $matuni[15] = "quince"; 
   $matuni[16] = "dieciseis"; 
   $matuni[17] = "diecisiete"; 
   $matuni[18] = "dieciocho"; 
   $matuni[19] = "diecinueve"; 
   $matuni[20] = "veinte"; 
   $matunisub[2] = "dos"; 
   $matunisub[3] = "tres"; 
   $matunisub[4] = "cuatro"; 
   $matunisub[5] = "quin"; 
   $matunisub[6] = "seis"; 
   $matunisub[7] = "sete"; 
   $matunisub[8] = "ocho"; 
   $matunisub[9] = "nove"; 

   $matdec[2] = "veint"; 
   $matdec[3] = "treinta"; 
   $matdec[4] = "cuarenta"; 
   $matdec[5] = "cincuenta"; 
   $matdec[6] = "sesenta"; 
   $matdec[7] = "setenta"; 
   $matdec[8] = "ochenta"; 
   $matdec[9] = "noventa"; 
   $matsub[3]  = 'mill'; 
   $matsub[5]  = 'bill'; 
   $matsub[7]  = 'mill'; 
   $matsub[9]  = 'trill'; 
   $matsub[11] = 'mill'; 
   $matsub[13] = 'bill'; 
   $matsub[15] = 'mill'; 
   $matmil[4]  = 'millones'; 
   $matmil[6]  = 'billones'; 
   $matmil[7]  = 'de billones'; 
   $matmil[8]  = 'millones de billones'; 
   $matmil[10] = 'trillones'; 
   $matmil[11] = 'de trillones'; 
   $matmil[12] = 'millones de trillones'; 
   $matmil[13] = 'de trillones'; 
   $matmil[14] = 'billones de trillones'; 
   $matmil[15] = 'de billones de trillones'; 
   $matmil[16] = 'millones de billones de trillones'; 

   $num = trim((string)@$num); 
   if ($num[0] == '-') { 
      $neg = 'menos '; 
      $num = substr($num, 1); 
   }else 
      $neg = ''; 
   while ($num[0] == '0') $num = substr($num, 1); 
   if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num; 
   $zeros = true; 
   $punt = false; 
   $ent = ''; 
   $fra = ''; 
   for ($c = 0; $c < strlen($num); $c++) { 
      $n = $num[$c]; 
      if (! (strpos(".,'''", $n) === false)) { 
         if ($punt) break; 
         else{ 
            $punt = true; 
            continue; 
         } 

      }elseif (! (strpos('0123456789', $n) === false)) { 
         if ($punt) { 
            if ($n != '0') $zeros = false; 
            $fra .= $n; 
         }else 

            $ent .= $n; 
      }else 

         break; 

   } 
   $ent = '     ' . $ent; 
   if ($dec and $fra and ! $zeros) { 
      $fin = ' coma'; 
      for ($n = 0; $n < strlen($fra); $n++) { 
         if (($s = $fra[$n]) == '0') 
            $fin .= ' cero'; 
         elseif ($s == '1') 
            $fin .= $fem ? ' una' : ' un'; 
         else 
            $fin .= ' ' . $matuni[$s]; 
      } 
   }else 
      $fin = ''; 
   if ((int)$ent === 0) return 'Cero ' . $fin; 
   $tex = ''; 
   $sub = 0; 
   $mils = 0; 
   $neutro = false; 
   while ( ($num = substr($ent, -3)) != '   ') { 
      $ent = substr($ent, 0, -3); 
      if (++$sub < 3 and $fem) { 
         $matuni[1] = 'una'; 
         $subcent = 'as'; 
      }else{ 
         $matuni[1] = $neutro ? 'un' : 'uno'; 
         $subcent = 'os'; 
      } 
      $t = ''; 
      $n2 = substr($num, 1); 
      if ($n2 == '00') { 
      }elseif ($n2 < 21) 
         $t = ' ' . $matuni[(int)$n2]; 
      elseif ($n2 < 30) { 
         $n3 = $num[2]; 
         if ($n3 != 0) $t = 'i' . $matuni[$n3]; 
         $n2 = $num[1]; 
         $t = ' ' . $matdec[$n2] . $t; 
      }else{ 
         $n3 = $num[2]; 
         if ($n3 != 0) $t = ' y ' . $matuni[$n3]; 
         $n2 = $num[1]; 
         $t = ' ' . $matdec[$n2] . $t; 
      } 
      $n = $num[0]; 
      if ($n == 1) { 
         $t = ' ciento' . $t; 
      }elseif ($n == 5){ 
         $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t; 
      }elseif ($n != 0){ 
         $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t; 
      } 
      if ($sub == 1) { 
      }elseif (! isset($matsub[$sub])) { 
         if ($num == 1) { 
            $t = ' mil'; 
         }elseif ($num > 1){ 
            $t .= ' mil'; 
         } 
      }elseif ($num == 1) { 
         $t .= ' ' . $matsub[$sub] . '?n'; 
      }elseif ($num > 1){ 
         $t .= ' ' . $matsub[$sub] . 'ones'; 
      }   
      if ($num == '000') $mils ++; 
      elseif ($mils != 0) { 
         if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub]; 
         $mils = 0; 
      } 
      $neutro = true; 
      $tex = $t . $tex; 
   } 
   $tex = $neg . substr($tex, 1) . $fin; 
   return ucfirst($tex); 
} 

//funcion  que cambia formato de fecha d/m/aa o dd-mm-aa o cualquier separador introducido a aaaa-mm-dd
function fecha_mysql($actual=true,$fecha=0,$separador="/")
{
    if($actual==true)
	{
		$dia=date("d");
		$mes=date("m");
		$año=date("Y");
		
		
		
		$fecha_final="$año-$mes-$dia";
		
	}
	if($actual== false)
	{
	    $dia="";
		$mes="";
		$año="";
		$cuenta=0;
		
	    for($i=0;$i<strlen($fecha);$i++)
		{
		   $caracter=substr($fecha,$i,1);
		    //echo"$caracter<br>";
			if($caracter==$separador)
			{
			   $cuenta++;
			}
			//echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$cuenta<br>";
			
			if(($cuenta==0)and($caracter!=$separador))
			{
			  $dia.=$caracter;
			 // echo"--------->dia: $dia<br>";
			}
			
			if(($cuenta==1)and($caracter!=$separador))
			{
			   $mes.=$caracter;
			   //echo"--------->mes: $mes<br>";
			}
			
			if(($cuenta==2)and($caracter!=$separador))
			{
			   $año.=$caracter;
			}
			
			
		}
		//echo"<b>Previo: $dia - $mes - $año</b><br>";
		//$largo_dia=strlen($dia);
		//echo"F largo d $largo_dia<br>";
		if($dia < 10)
		{
			
		   $diaX="0".$dia;
		}
		else
		{
		    $diaX=$dia;
		}
		if($mes < 10)
		{
			$mesX="0".$mes;	
		}
		else
		{
		    $mesX=$mes;
		}
		
		$fecha_final="$año-$mesX-$diaX";
		
	}
	
	return($fecha_final);
}

//devuelve fecha de aaaa-mm-dd => dd/mm/aaaa
   function fecha_format($fecha,$separador="/")
  {
    	$dia=substr($fecha,-2,2);
		$mes=substr($fecha,5,2);
		$año=substr($fecha,0,4);
		
		$fecha_N=$dia.$separador.$mes.$separador.$año;
	    
		return($fecha_N);
  }
  
//devuelve un array con los datos de deudaXletra del alumno seleccionado segun datos entrada
function letra_morosa($id,$semestre=0,$ano=0,$fecha_v,$opcion="F")
{
				/*$consL="SELECT deudaXletra FROM letras WHERE idalumn='$id' and semestre='$semestre'and ano='$ano' and anulada='N'and pagada IN('A','N') ORDER BY numletra";
				*/
				if($opcion=="V")
				{
				$consL="SELECT fechavenc,deudaXletra FROM letras WHERE idalumn='$id' and fechavenc<='$fecha_v' and anulada='N'and pagada IN('A','N') ORDER BY numletra";
				}
				if($opcion=="F")
				{
					$consL="SELECT fechavenc,deudaXletra FROM letras WHERE idalumn='$id' and semestre='$semestre' and ano='$ano' and fechavenc <='$fecha_v' and anulada='N'and pagada IN('A','N') ORDER BY numletra";
				}
				
				if($opcion=="A")
				{
					$consL="SELECT fechavenc,deudaXletra FROM letras WHERE idalumn='$id' and ano='$ano' and fechavenc <='$fecha_v' and anulada='N'and pagada IN('A','N') ORDER BY numletra";
				}
				//echo"<b>$consL</b><br>";
				
				$sqlL=mysql_query($consL)or die("ERROR:".mysql_error());
				$num_inpagas=mysql_num_rows($sqlL);
				
				//echo"<br>Nª impagas : $num_inpagas<br>";
				
				if($num_inpagas>0)
				{		
					$i=0;
					while($L=mysql_fetch_array($sqlL))
					{
						$deuda[$i]=$L["deudaXletra"];
						$fecha[$i]=$L["fechavenc"];
						$i++;
					}
				}
				else
				{
				}
				//echo"++++";	
				//var_dump($deuda);
				//echo"++++<br>";
				mysql_free_result($sqlL);
				return array($deuda,$fecha);
				
}
//ingresa fecha inicio y fecha final y selecciona de tabla letras los morosos 
//devuelve un array con los id de los morosos

function alumno_moroso($fecha_inicio="",$fecha_final="",$opcion="1",$ano=0,$criterio="")
{
	if($opcion=="1")
	{
		$cons="SELECT idalumn FROM letras WHERE fechavenc BETWEEN '$fecha_inicio' AND '$fecha_final' AND anulada='N' AND pagada IN('A','N') ORDER BY numletra";
	}
	if(($opcion=="2")and($criterio=="mayor"))
	{
	 	$cons="SELECT idalumn FROM letras WHERE ano >='$ano' AND anulada='N' AND pagada IN('A','N') ORDER BY numletra";
	}
	if(($opcion=="2")and($criterio=="menor"))
	{
	 	$cons="SELECT idalumn FROM letras WHERE ano <='$ano' AND anulada='N' AND pagada IN('A','N') ORDER BY numletra";
	}
					
					//echo"-><b>$cons</b><br>";
					$sql=mysql_query($cons) or die(mysql_error());
					$num=mysql_num_rows($sql);
					
				    if($num>0)
					{
						$i=0;
						while($AB=mysql_fetch_array($sql))
						{
							$ID[$i]=$AB["idalumn"];
							$i++;
						}
						/*var_dump($ID);
				    	foreach($ID as $n => $valor)
						{
							echo"$n -> $valor<br>";
						}
						*/
						$id=array_unique($ID);
					}
					else
					{
						$id=array();
						echo"<b><br><br>NO se Encontraron Letras Morosas en esta Busqueda</b><br>";
					}	
					
					return($id);
}	  


//dado un id selecciona datos de la tabla alumno
function datos_alumno($opcion="1",$id)
{
	if($opcion=="1")
	{
		$consA="SELECT rut,apellido,nombre,carrera FROM alumno WHERE id='$id'";
		$sqlA=mysql_query($consA);
		while($X=mysql_fetch_array($sqlA))
		{
			$rut=$X["rut"];
			$apellido=$X["apellido"];
			$nombre=$X["nombre"];
			$carrera=$X["carrera"];
				
		}
			$nombre = ucwords(strtolower($nombre));
	        $apellido = ucwords(strtolower($apellido));
			$alumno="$nombre $apellido";
			mysql_free_result($sqlA);
			return array($rut,$alumno,$carrera);
	}
	else
	{
		$consA="SELECT sede FROM alumno WHERE id='$id'";
		$sqlA=mysql_query($consA);
		while($X=mysql_fetch_array($sqlA))
		{
			$sede=$X["sede"];
		}
		mysql_free_result($sqlA);
		return($sede);
	}
}
//revisa si ciertos datos ya estan en BBDD tabla="tabla a consultar" campos="campo1,campo2"
//valor1="valor de campo1" igual para valor2
//repotno true o false	
function es_repetida($tabla,$valor1,$valor2,$valor3,$valor4,$campos)
{
    $Campos=explode(",",$campos);
    list($campo1,$campo2,$campo3,$campo4)=$Campos;
	$consR="SELECT $campos FROM $tabla WHERE $campo1='$valor1' and $campo2='$valor2' and $campo3='$valor3' and $campo4='$valor4'";
	//echo"->$consR<br>";
	$sqlR=mysql_query($consR)or die("Error:".mysql_error());
	
	$num=mysql_num_rows($sqlR);
	//echo"-->$num<br>";
	
	if($num>0)
	{
		$repetida=true;
	}
	else
	{
		$repetida=false;
	}
	return($repetida);
}
//devuelve una lista/menu que contiene la ciudad del usuario que esta autentificado y con privilegios
//actualizacion elimino consultas sql y utilizo datos de session nuevos 09-2010 by acx
function selector_sede($nombre_select="fsede", $funcionX="", $ver_opc_todas=false, $ver_opc_seleccione=false,$sede_X_privilegio=true)
{
	//--------------------------------------//
	$array_sede=array("Talca", "Linares");
	$array_id_usuarios_full_sede=array(241, 240, 249);//usuarios que pueden ver todas las sedes aunque nivel no lo permite
	//---------------------------------------//
	$id_usuario_actual_x=$_SESSION["USUARIO"]["id"];
	$sede_usuario=$_SESSION["USUARIO"]["sede"];
	if(isset($_SESSION["USUARIO"]))
	{$privilegio=$_SESSION["USUARIO"]["privilegio"];}
	else
	{$privilegio="";}
	
	
	
	if($sede_X_privilegio)
	{
		switch($privilegio)
		{
			case"admi_total":
				$select='
				<select name="'.$nombre_select.'" id="'.$nombre_select.'" '.$funcionX.'>';
				if($ver_opc_seleccione)
				{ $select.='<option value="0"  selected="selected">Seleccione</option>';}
				$select.='<option value="Talca">Talca</option>
				<option value="Linares">Linares</option>';
				if($ver_opc_todas)
				{ $select.='<option value="todas">Todas</option>';}
				$select.='</select>';
				break;
			case"inspeccion":
				$select='<select name="'.$nombre_select.'" id="'.$nombre_select.'" '.$funcionX.'>';
				if($ver_opc_seleccione)
				{ $select.='<option value="0"  selected="selected">Seleccione</option>';}
				$select.='<option value="Talca">Talca</option>
				<option value="Linares">Linares</option>';
				if($ver_opc_todas)
				{ $select.='<option value="todas">Todas</option>';}
				$select.='</select>';
				break;		
			case"finan":
				$select='<select name="'.$nombre_select.'" id="'.$nombre_select.'" '.$funcionX.'>';
				if($ver_opc_seleccione)
				{ $select.='<option value="0"  selected="selected">Seleccione</option>';}
				$select.='<option value="Talca">Talca</option>
				<option value="Linares">Linares</option>';
				if($ver_opc_todas)
				{ $select.='<option value="todas">Todas</option>';}
				$select.='</select>';
				break;			
			default:
				$select='
				<select name="'.$nombre_select.'" id="'.$nombre_select.'" '.$funcionX.'>';
				if($ver_opc_seleccione)
				{ $select.='<option value="0"  selected="selected">Seleccione</option>';}
				
				foreach($array_sede as $n => $valor)
				{
					if($sede_usuario==$valor)
					{ $mostrar_sede=true;}
					else
					{
						if(in_array($id_usuario_actual_x, $array_id_usuarios_full_sede)) 
						{ $mostrar_sede=true;}
						else
						{ $mostrar_sede=false;}
					}
					
					if($mostrar_sede)
					{$select.='<option value="'.$valor.'">'.$valor.'</option>';}
				}
				
				$select.='</select>';
		}
	}
	else
	{
		$select='<select name="'.$nombre_select.'" id="'.$nombre_select.'" '.$funcionX.'>';
				if($ver_opc_seleccione)
				{ $select.='<option value="0"  selected="selected">Seleccione</option>';}
				$select.='<option value="Talca">Talca</option>
				<option value="Linares">Linares</option>';
				if($ver_opc_todas)
				{ $select.='<option value="todas">Todas</option>';}
				$select.='</select>';
	}
	return($select);
}
//funcion que elimina las letras de un alumno dado su ID
function Elimina_Letras($ID)
{
	include("conexion.php");
	echo"Su ID: $ID<br>";
	$consL="SELECT * FROM letras WHERE idalumn='$ID'";
	//echo"->$consL<br>";
	$sql=mysql_query($consL);
	$num_letras=mysql_num_rows($sql);
	if($num_letras>0)
	{
		while($L=mysql_fetch_array($sql))
		{
			$numletra=$L["numletra"];
			$consD="DELETE FROM letras WHERE idalumn='$ID' and numletra='$numletra'";
			//echo"--> $consD<br>";
			mysql_query($consD)or die(mysql_error());
		}
		echo"<br><b>Letras Eliminadas...</b><br>";
	}
	else
	{
		echo"<b>No tienen Letras Generadas....</b><br>";
	}
}
//retorna un array con los archivos que se encuentran en un directorio determinado, que sean de
//un tipo de archivo especificado(extencion)
function Busca_en_dir($path,$tipo_arch="txt") 
{
	$dir=dir($path);
	$cuenta=0;
	while($elemento=$dir->read())
	{
		$Archivo=explode(".",$elemento);
		//echo"<br> recorro array<br>";
		/*foreach($Archivo as $n => $valor)
		{
			echo"$n -> $valor<br>";
		}*/
		if($Archivo[1]==$tipo_arch)
		{
			$Contenedor[$cuenta]=$elemento;
			$cuenta++;
		}
	}
	//echo"-----> $cuenta<br>";
	return($Contenedor);
}
//funcion especifica para grabar el nivel del alumno
function G_nivel($id,$nivel)
{
	if(($id!="")and($nivel!=""))
	{
		include("conexion.php");
		$consN="UPDATE alumno SET nivel='$nivel' WHERE id='$id'";
		if(mysql_query($consN))
		{
			return(true);
		}
		else
		{
			mysql_error();
			return(false);
		}	
	}
	else
	{
		return(false);
	}
	//mysql_close($conexion);
}
//devuelve la sede del admi segun permisos
function sede_actual()
{
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	//echo"<<$privilegio>><br>";
	if($privilegio=="admi_total")
	{
		$sede_act="Total";
	}
	elseif(($privilegio=="finan")or($privilegio=="admi"))
	{
		$id=$_SESSION["USUARIO"]["id"];
		include("conexion.php");
		$cons="SELECT ciudad FROM personal WHERE id='$id'";
		//echo"->$cons<br>";
		$sql=mysql_query($cons)or die("ERROR En funcion".mysql_error());
		while($R=mysql_fetch_array($sql))
		{
			$sede=$R["ciudad"];
		}
		$sede_act = ucwords(strtolower($sede));
	
		mysql_free_result($sql);
	}
	return($sede_act);
}
function cancela_mat($numero,$valor,$fecha_p)	
{
	include("conexion.php");
	$fecha=fecha_mysql(false,$fecha_p);
	$sede=$_SESSION[sede_c];
	$glosa_mat="Pago de Matricula";
	$cons_matp="INSERT INTO pagos (numletra, fechapago, valor, tipodoc, glosa, sede,movimiento) VALUES('$numero', '$fecha','$valor','B','$glosa_mat','$sede','I')";
	//echo"$cons_matp<br>";
	$consX="SELECT numletra FROM pagos WHERE numletra='$numero' and sede='$sede'";
	$sqlX=mysql_query($consX)or die(mysql_error());
	$num_reg=mysql_num_rows($sqlX);
	if (!$num_reg>0)
	{
		mysql_query($cons_matp)or die(mysql_error());
	}

}
function VERIFICA_CONTRATO($id_alumno, $year_vigencia, $semestre_vigencia)
{
	$contrato_ok="";
	$cons_C="SELECT * FROM contratos2 WHERE id_alumno='$id_alumno' AND condicion IN('ok', 'old') ORDER by id";
	if(DEBUG){ echo"<tt>|====>$cons_C</tt><br>";}
	$sql_C=mysql_query($cons_C)or die("verifica contrato ".mysql_error());
	$num_contratos=mysql_num_rows($sql_C);
	if($num_contratos>0)
	{
		while(($C=mysql_fetch_assoc($sql_C))and(!$contrato_ok))
		{
			$C_id=$C["id"];
			$C_semestre=$C["semestre"];
			$C_year=$C["ano"];
			$C_condicion=$C["condicion"];
			$C_vigencia=$C["vigencia"];
			
			if(DEBUG){ echo"---> |$C_id| $C_semestre |$C_year| $C_condicion |$C_vigencia|";}
			
			switch($C_vigencia)
			{
				case"semestral":
					if(($C_year==$year_vigencia)and($C_semestre==$semestre_vigencia))
					{ 
						$contrato_ok=true;
						if(DEBUG){echo"S :-)<br>";}
					}
					else
					{
						 $contrato_ok=false;
						 if(DEBUG){echo"S :-(<br>";}
					}
					break;
				case"anual":	
					if($C_year==$year_vigencia)
					{ 
						$contrato_ok=true;
						if(DEBUG){echo"A :-)<br>";}
					}
					else
					{ 
						$contrato_ok=false;
						if(DEBUG){echo"A :-(<br>";}
					}
					break;
				default:
					$contrato_ok=false;	
					if(DEBUG){echo"D :-(<br>";}
			}
		}
	}
	else
	{
		$contrato_ok=false;
	}
	mysql_free_result($sql_C);
	return($contrato_ok);
}
//////////////////////
function NUMERO_A_ROMANO($num)
{
	if ($num <0 || $num >9999) {return -1;}
	$r_ones = array(1=>"I", 2=>"II", 3=>"III", 4=>"IV", 5=>"V", 6=>"VI", 7=>"VII", 8=>"VIII",
	9=>"IX");
	$r_tens = array(1=>"X", 2=>"XX", 3=>"XXX", 4=>"XL", 5=>"L", 6=>"LX", 7=>"LXX",
	8=>"LXXX", 9=>"XC");
	$r_hund = array(1=>"C", 2=>"CC", 3=>"CCC", 4=>"CD", 5=>"D", 6=>"DC", 7=>"DCC",
	8=>"DCCC", 9=>"CM");
	$r_thou = array(1=>"M", 2=>"MM", 3=>"MMM", 4=>"MMMM", 5=>"MMMMM", 6=>"MMMMMM",
	7=>"MMMMMMM", 8=>"MMMMMMMM", 9=>"MMMMMMMMM");
	$ones = $num % 10;
	$tens = ($num - $ones) % 100;
	$hundreds = ($num - $tens - $ones) % 1000;
	$thou = ($num - $hundreds - $tens - $ones) % 10000;
	$tens = $tens / 10;
	$hundreds = $hundreds / 100;
	$thou = $thou / 1000;
	if ($thou) {$rnum .= $r_thou[$thou];}
	if ($hundreds) {$rnum .= $r_hund[$hundreds];}
	if ($tens) {$rnum .= $r_tens[$tens];}
	if ($ones) {$rnum .= $r_ones[$ones];}
return $rnum;
}
?>