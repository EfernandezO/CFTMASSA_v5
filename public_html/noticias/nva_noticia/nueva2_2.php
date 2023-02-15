<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	OKALIS($lista_invitados);
//-----------------------------------------//	
?>
<?php
	define("DEBUG", false);
	if(DEBUG){var_export($_POST);}
	//verifico los valores de llegada
	$array_formatos_compatibles=array("jpg", "jpeg", "png", "gif");
	$fdia=$_POST["fdia"];
	$fmes=$_POST["fmes"];
	$fano=$_POST["fano"];
	$fautor=$_POST["fautor"];
	$ftitulo=$_POST["ftitulo"];
	$fbreve=$_POST["fbreve"];
	$txt_noticia=$_POST["txt_noticia"];
	
	
	$errorb=0;
	$errorn=0;
	$errori=0;
	$errorG=0;
	$encontrada=0;
	if(empty($fbreve))
	{
	     $errorb=1;
	}
	if(empty($txt_noticia))
	{
	     $errorn=1;
	}
	 $nombre_archivo = $_FILES['fimagen']['name'];
     $tipo_archivo = $_FILES['fimagen']['type'];
     $tmp_nombre=$_FILES['fimagen']['tmp_name'];
	 $extencion_img=end(explode(".",$nombre_archivo));
	 $img=rand(1111,9999)."_".$nombre_archivo;
	 $destino="../image_not/$img";
    if(DEBUG){ echo"T:$tmp_nombre || N:$nombre_archivo-->$extencion_img ---- D: <b>$destino</b>";}
	
	//verifico tipo archivo
	if(empty($nombre_archivo))
	{
		$no_hay_img=true;	
		$no_hay_img_label="VERDADERO";
	}
	else
	{
     if(in_array($extencion_img, $array_formatos_compatibles))
      {
	      //imagen compatible
		  $errori=0;
		  if(DEBUG){ echo"IMAGEN COMPATIBLE<br>";}
	  }
	  else
	  {
	  	$errori=1;
		if(DEBUG){ echo"IMAGEN NO COMPATIBLE<br>";}
	  }
	  $no_hay_img=false;
	  $no_hay_img_label="FALSO";
	}
	
if(DEBUG){ echo"No Hay Imagen--->|$no_hay_img_label|<br>";}
	 // echo"B->$errorb N->$errorn I->$errori<br>";
	  if(($errorb==1)or($errorn==1)or($errori==1))
	  {
	       // echo"redirigir";
		   if(DEBUG){ echo"ERROR $errorb - $errorn - $errori<br>";}
			header ("Location: nueva1_3.php?errorb=$errorb&errorn=$errorn&errori=$errori");
	  }
	  else
	  {
	     //si datos correctos
		 if(DEBUG){ echo"Sin Error, Comienza Carga....<br>";}
		 include("../../../funciones/funcion.php");
		 include("../../../funciones/conexion.php");
		 
		 //purifico valores
		 $fautor=str_inde($fautor,"Anonimo");
		 $ftitulo=str_inde($ftitulo,"Sin Titulo");
		 $fbreve=str_inde($fbreve);
		 
		 //formateo fecha 
		 $fecha="$fdia/$fmes/$fano";
		 $fecha=fecha_mysql(false,$fecha);
		 
		 	if((move_uploaded_file($tmp_nombre, $destino))or($no_hay_img))
		 	{
				$cons_search="SELECT fecha,autor,titulo,breve FROM noticias";
				$sql=mysql_query($cons_search);
				while($A=mysql_fetch_array($sql))
				{
					$fechaX=$A["fecha"];
					$autorX=$A["autor"];
					$tituloX=$A["titulo"];
					$breveX=$A["breve"];
					
					if(($fechaX==$fecha)and($autorX==$fautor)and($tituloX==$ftitulo)and($breveX==$fbreve))
					{
						$encontrada=1;
						break;
					}
				}
				if($encontrada==1)
				{
					echo"<b>La Noticia Ya fue Guardada...</b><br>";
					$errorG=1;
				}
				else
				{
					$cons="INSERT INTO noticias (fecha, autor,titulo, breve, noticia, imagen)  Values('$fecha 	', '$fautor','$ftitulo', '$fbreve', '$txt_noticia', '$img')";
					if(DEBUG){ echo"--> $cons<br>";}
					else
					{
						include("nueva3.php");
						mysql_query($cons)or die("INSERT ".mysql_error());
					}
				}
				mysql_close($conexion);
			}
			else
			{
				echo"<b>No fue Posible Subir La Imagen...</b><br>";
			}
	  }
?>