<?php
//
function CARACTERES_RAROS($cadena)
{
	$cadena=strtolower($cadena);
	$cadena=str_replace("á","a",$cadena);
	$cadena=str_replace("à","a",$cadena);
	$cadena=str_replace("é","e",$cadena);
	$cadena=str_replace("è","e",$cadena);
	$cadena=str_replace("í","i",$cadena);
	$cadena=str_replace("ì","i",$cadena);
	$cadena=str_replace("ó","o",$cadena);
	$cadena=str_replace("ò","o",$cadena);
	$cadena=str_replace("ú","u",$cadena);
	$cadena=str_replace("ù","u",$cadena);
	
	$cadena=str_replace("ñ","n",$cadena);
	$cadena=str_replace(" ","_",$cadena);
	
	$cadena=str_replace("ã¡","a",$cadena);
	$cadena=str_replace("ã€","a",$cadena);
	$cadena=str_replace("ã¤","a",$cadena);
	$cadena=str_replace("ã©","e",$cadena);
	$cadena=str_replace("ã‰","e",$cadena);
	$cadena=str_replace("ãª","e",$cadena);
	$cadena=str_replace("ã¦","ae",$cadena);
	$cadena=str_replace("ã*","i",$cadena);
	$cadena=str_replace("ã³","o",$cadena);
	$cadena=str_replace("ã“","o",$cadena);
	$cadena=str_replace("ã¶","o",$cadena);
	$cadena=str_replace("ãº","u",$cadena);
	$cadena=str_replace("ã¼","u",$cadena);
	$cadena=str_replace("ã±","n",$cadena);
	$cadena=str_replace("ã‘","n",$cadena);
	$cadena=str_replace("ã§","-",$cadena);
	
	$cadena=str_replace("âº","",$cadena);
	$cadena=str_replace("°","",$cadena);
	
	//echo"------> $cadena";
	return($cadena);
}
///////Valida Rut///////
function RUT_OK($rut)
{
	
	$rut=strtolower($rut);
	$rut=str_replace(".","",$rut);
	$rut=str_replace(" ","",$rut);
	
	
	if(strpos($rut,"-"))
	{
		$array_rut=explode("-",$rut);
		if(is_array($array_rut))
		{$continuar_1=true;}
		else{$continuar_1=false;}
		
		if(isset($array_rut[0]))
		{ $continuar_2=true;}
		else
		{ $continuar_2=false;}
		
		if(isset($array_rut[1]))
		{ $continuar_3=true;}
		else
		{ $continuar_3=false;}
	}
	else{ $continuar_1=false; $continuar_2=false; $continuar_3=false;}
		
	if(empty($rut)){$continuar_4=false;}
	else{$continuar_4=true;}
	
	if(($continuar_1) and( $continuar_2) and($continuar_3) and ($continuar_4))
	{
		$rut_original=$array_rut[0];
		$dv_original=$array_rut[1];
		
		
		$x=2;
		$sumatorio=0;
		for($i=strlen($rut_original)-1;$i>=0;$i--)
		{
			 if ($x>7){$x=2;}
			$sumatorio=$sumatorio+($rut_original[$i]*$x);
			$x++;
		}
		$digito=$sumatorio%11;
		$digito=11-$digito;
		
		switch ($digito) 
		{
		 case 10:
			$digito="k";
			break;
		 case 11:
			$digito="0";
		   break;
		 }
		//---------------------------------------*//
		
		if($digito==$dv_original){$rut_OK=true;}
		else{ $rut_OK=false;}
	}
	else
	{$rut_OK=false;}
	
	return($rut_OK);
}
//----------------------------------------------//
function RUT_DISPONIBLE($rut, $tipo_usuario)
{
	$rut=strtolower($rut);
	require("conexion_v2.php");
	switch($tipo_usuario)
	{
		case"personal":
				$continuar_1=true;
				$cons="SELECT COUNT(rut) FROM personal WHERE rut='$rut'";
				$sqli=$conexion_mysqli->query($cons)or die("Personal :".$conexion_mysqli->error());
				$D=$sqli->fetch_row();
				$num_coincidencias=$D[0];
				if(empty($num_coincidencias)){ $num_coincidencias=0;}
				$sqli->free();
				if($num_coincidencias>0){ $rut_disponible=false;}
				else{ $rut_disponible=true;}
			break;
		case"alumno":
				$continuar_1=true;
				$cons="SELECT COUNT(rut) FROM alumno WHERE rut='$rut'";
				$sqli=$conexion_mysqli->query($cons)or die("Alumno :".$conexion_mysqli->error());
				$D=$sqli->fetch_row();
				$num_coincidencias=$D[0];
				if(empty($num_coincidencias)){ $num_coincidencias=0;}
				$sqli->free();
				if($num_coincidencias>0){ $rut_disponible=false;}
				else{ $rut_disponible=true;}
			break;	
		default:
			$continuar_1=false;	
			$rut_disponible=false;
	}
	$conexion_mysqli->close();
	
	return($rut_disponible);
}
///////Rut digito verificador///////
function validar_rut($rut)
{
//echo"$rut<br>";
$x=2;
$sumatorio=0;
for ($i=strlen($rut)-1;$i>=0;$i--){
 if ($x>7){$x=2;}
  $sumatorio=$sumatorio+($rut[$i]*$x);
  $x++;
}
$digito=$sumatorio%11;
$digito=11-$digito;

switch ($digito) {
 case 10:
	$digito="K";
   break;
 case 11:
	$digito="0";
   break;
  }

return $digito;
}
///////////////////Comprueba Mail////////////////////////////////////
function comprobar_email($email)
{
	if(DEBUG){ echo"<br>------------------------INICIO FUNCION COMPROBAR_EMAIL------------------------<br>";}
	if(DEBUG){ echo"Email a revisar: $email<br>";}
    $mail_correcto = 0;
    //compruebo unas cosas primeras
    if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@"))
	{
		if(DEBUG){ echo"-Primera verificacion OK<br>";}
       if ((!strstr($email,"'")) && (!strstr($email,"\"")) && (!strstr($email,"\\")) && (!strstr($email,"\$")) && (!strstr($email," "))) 
	   {
		   if(DEBUG){ echo"-Segunda verificacion OK<br>";}
          //miro si tiene caracter .
          if (substr_count($email,".")>= 1)
		  {
			  if(DEBUG){ echo"-Tercera verificacion OK<br>";}
             //obtengo la terminacion del dominio
             $term_dom = substr(strrchr ($email, '.'),1);
             //compruebo que la terminación del dominio sea correcta
             if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) )
			 {
				 if(DEBUG){ echo"-Cuarta verificacion OK<br>";}
                //compruebo que lo de antes del dominio sea correcto
                $antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1);
                $caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1);
                if ($caracter_ult != "@" && $caracter_ult != ".")
				{
					if(DEBUG){ echo"-Quinta verificacion OK<br>";}
                   $mail_correcto = 1;
                }else{ if(DEBUG){ echo"-Quinta verificacion ERROR<br>";}}
             }else{ if(DEBUG){ echo"-Cuarta verificacion ERROR<br>";}}
          }else{ if(DEBUG){ echo"-Tercera verificacion ERROR<br>";}}
       }else{ if(DEBUG){ echo"-Segunda verificacion ERROR<br>";}}
    }else{ if(DEBUG){ echo"-Primera verificacion ERROR<br>";}}
	
	   if(DEBUG){ echo"Estado de E-mail "; if($mail_correcto){echo" Corrector<br>";}else{echo" Incorrector<br>";}}
	   if(DEBUG){ echo"<br>--------------------------FIN FUNCION COMPROBAR_EMAIL--------------------------<br>";}
    if ($mail_correcto)
       return 1;
    else
       return 0;
} 
///////////////////para caracteres xajax///////////////////////
function AJAX_CARACTERES_RAROS($cadena)
{
	$cadena=str_replace("Ã³","ó",$cadena);
	$cadena=str_replace("Ã©","é",$cadena);
	
	$cadena=str_replace("Ã","í",$cadena);

	return($cadena);
}
//////////////////////////////////////////////////////////

//limpia variables de entrada de etiquetas y caracteres raros
function LIMPIA_INPUT($input)
{
 
  $search = array(
    '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
    '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
  );
 
    $output = preg_replace($search, '', $input);
    return $output;
}
////////////////////////////////////
///le envio array y limpia sus elementos con funcion LIMPIA_INPUT
function SANITIZAR($input)
{
    if (is_array($input))
	{
        foreach($input as $var=>$val) 
		{
            $output[$var] = SANITIZAR($val);
        }
    }
    else 
	{
        if (get_magic_quotes_gpc())
		 {
            $input = stripslashes($input);
        }
        $input  = LIMPIA_INPUT($input);
        $output = mysql_real_escape_string($input);
    }
    return $output;
}
//--------------------------------------------------------------//

function DIFERENCIA_ENTRE_FECHAS($fecha_1, $fecha_2, $formato_salida="days")
{
	if(DEBUG){ echo"<strong>_____________________INICIO FUNCION DIFERENCIA_ENTRE_FECHAS____________________</strong><br>";}
	if(DEBUG){ echo"Diferencia entre: <br>Fecha 1:$fecha_1 <br> Fecha 2:$fecha_2<br>";}
	$time1 = new DateTime($fecha_1);
	$time2 = new DateTime($fecha_2);
	
	$intervalo = $time1->diff($time2);
	
	$year=$intervalo->y;
	$meses=$intervalo->m;
	$dias=$intervalo->d;
	$total_dias_trancurridos=$intervalo->days;
	$R=$intervalo->invert;
	if($R==1){ $R="-";}else{ $R="";}

	
	switch($formato_salida)
	{
		case"meses_y_fraccion":
			$diferencia=($year*12)+($meses);
			if($dias>0){ $diferencia+=1;}
			$diferencia=$R.$diferencia;
			break;
		default:
			$diferencia=$intervalo->$formato_salida;
			$diferencia=$R.$diferencia;
	}
	
	
	if(DEBUG){ echo"<br>Year: $year <br> Meses: $meses<br> Dias:$dias <br>Total dias Transcurridos: $total_dias_trancurridos <br>R: $R<br>Diferencia: $diferencia [$formato_salida]<br>";}
	
	if(DEBUG){ echo"<strong>_____________________FIN FUNCION DIFERENCIA_ENTRE_FECHAS___________________________</strong><br>";}
	return($diferencia);
}
?>