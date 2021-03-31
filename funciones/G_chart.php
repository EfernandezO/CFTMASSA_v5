<?php
/*
--------FORMATO de DATOS RECIBIDOS-----------
$array_grafico["tipo"]="lc";
$array_grafico["datos"][]="151,5,5,5,5,5,5,5,5,10,5";
$array_grafico["rango_X"]="|A|B|C|D|E|F|G|H|I|J|";
$array_grafico["datos"][]="10,10,10,7,10,10,10,10,10,10";
$array_grafico["rango_Y"]="|1|2|3|4|5|6|7|8|9|10|";
$array_grafico["rango_Y_auto"]=true;//si true no necesita enviar "rango_Y", se genera automaticamnete
$array_grafico["dato_max"]=151;
$array_grafico["etiqueta_izquierda"]="la izquierda";
$array_grafico["etiqueta_inferior"]="la inferior";
$array_grafico["titulo"]="Nuevo Titulo";
$array_grafico["simbologia"]="costo|otro";
$array_grafico["colores_lineas_hex"]="F1A100,1F1F00";
$array_grafico["color_titulo_hex"]="F10000";
$array_grafico["size_titulo"]=20;
===============================================
*/
function GRAFICO_GOOGLE($array_grafico, $debug=false, $mostrar_img=true)
{
	$numero_div_Y=10;
	$rango_Y_auto="";
	$ruta='http://chart.apis.google.com/chart?';
	$tipo="&cht=".$array_grafico["tipo"];
	if((isset($array_grafico["alto_img_grafico"]))and(isset($array_grafico["ancho_img_grafico"])))
	{ $dimensiones="&chs=".$array_grafico["ancho_img_grafico"]."x".$array_grafico["alto_img_grafico"];}
	else
	{$dimensiones="&chs=650x300";}
	$datos="&chd=t:";
	$titulo_grafico=str_replace(" ","+",$array_grafico["titulo"]);
	$titulo=ucwords(CARACTERES_RAROS($titulo_grafico));
	/////////////////asignando valores//////////////////////
	$aux=true;
	foreach($array_grafico["datos"] as $n => $valor)
	{
		if($aux)
		{
			$datos.=$valor;
			$aux=false;
		}
		else
		{$datos.="|".$valor;}
	}
	/////////////////////////////////////
	$etiqueta_izq=str_replace(" ","+",$array_grafico["etiqueta_izquierda"]);
	$etiqueta_inf=str_replace(" ","+",$array_grafico["etiqueta_inferior"]);
	
	$rango_X=$array_grafico["rango_X"];
	if(isset($array_grafico["rango_Y"]))
	{ $rango_Y=$array_grafico["rango_Y"];}
	else
	{ $rango_Y="";}
	$simbologia=$array_grafico["simbologia"];
	$colores_hex=$array_grafico["colores_lineas_hex"];
	//color titulo
	if(!empty($array_grafico["color_titulo_hex"]))
	{$color_titulo=$array_grafico["color_titulo_hex"];}
	else
	{$color_titulo="FF0000";}	
	/////////////////////////
	if(!empty($array_grafico["size_titulo"]))
	{$size_titulo=$array_grafico["size_titulo"];}
	else
	{$size_titulo=16;}	
	
	$max_dato=$array_grafico["dato_max"];
	//para evitar problemas
	if($max_dato<10)
	{$max_dato=10;}
	//echo"=> $max_dato<br>";
	///calculando rango para eje Y
	if($max_dato%10==0)
	{
		$maximo_Y=$max_dato;	
		//echo"divisible x 10 <br>";
	}
	else
	{
		//echo"No divisible inicio ciclo<br>";
		$aux_dato=$max_dato;
		$continuar=true;
		while($continuar)
		{
			$aux_dato++;
			//echo"....> $aux_dato<br>";
			if($aux_dato%10==0)
			{
				$continuar=false;
				$maximo_Y=$aux_dato;
			}
		}
	}
	///////////////////////////////////calculando etiquetas rango Y automaticamente
	//echo"-----> $maximo_Y<br>";
	if(isset($array_grafico["rango_Y_auto"]))
	{ $genera_rango_Y=$array_grafico["rango_Y_auto"];}
	else{ $genera_rango_Y=false;}
	if($genera_rango_Y)
	{
		$avance_Y=($maximo_Y/$numero_div_Y);
		$concat_aux=true;
		for($xx=0;$xx<=$maximo_Y;$xx+=$avance_Y)
		{
			//echo"---> $xx<br>";
			if($concat_aux)
			{
				$rango_Y_auto.="|$xx|";
				$concat_aux=false;
			}
			else
			{$rango_Y_auto.="$xx|";}	
		}
		$rango_final_Y=$rango_Y_auto;
	}
	else
	{
		$rango_final_Y=$array_grafico["rango_Y"];
	}	
	
	
	
	//echo")))))> $rango_final_Y<br>";
	
	$url_grafico=$ruta.$tipo;
	$url_grafico.=$dimensiones;
	$url_grafico.=$datos;
	$url_grafico.="&chco=$colores_hex";//color
	$url_grafico.="&chds=0,$maximo_Y";//rango
	$url_grafico.="&chtt=$titulo";//titulo
	$url_grafico.="&chts=".$color_titulo.",".$size_titulo;
	$url_grafico.="&chdl=$simbologia";//simbologia separar con |
	
	$url_grafico.="&chxt=x,x,y,y&chxl=0:".$rango_X."1:|".$etiqueta_inf."|2:".$rango_final_Y."3:|".$etiqueta_izq."|4:|11111|";
	$url_grafico.="&chxp=1,50|3,50";
	
	
	$IMG='<img name="Grafico" src="'.$url_grafico.'" alt="'.$url_grafico.'">';
	
	if($debug)
	{
		echo "<br><strong>".$url_grafico."</strong><br>";
	}	
	if($mostrar_img){echo $IMG;}
	else{
		return($IMG);
	}
	
}
//funcion caracteres raros para no incluirla aparte
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
	
	//echo"------> $cadena";
	return($cadena);
}

?>