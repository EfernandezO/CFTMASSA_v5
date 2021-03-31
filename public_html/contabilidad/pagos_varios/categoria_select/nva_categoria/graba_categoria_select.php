<?php include ("../../../../SC/seguridad.php");?>
<?php include ("../../../../SC/privilegio.php");?>
<?php
$url="nvo_categoria.php";
if($_POST)
{
	extract($_POST);
	$tipo_mov=strip_tags($ftipo_mov);
	$nvo_doc=strip_tags($fnvo_doc);
	$nvo_doc=ucwords(strtolower($nvo_doc));
	$seccion="finanzas";
	if(($ftipo_mov!="")and($fnvo_doc!=""))
	{
		include("../../../../../funciones/conexion.php");
		$cons_b="SELECT COUNT(id) FROM parametros WHERE seccion='$seccion' AND tipo='$tipo_mov' AND contenido='$nvo_doc'";
		$sql_x=mysql_query($cons_b)or die(mysql_error());
		$valores=mysql_fetch_row($sql_x);
		$cantidad=$valores[0];
		mysql_free_result($sql_x);
		if($cantidad<=0)
		{
			$cons_i="INSERT INTO parametros (seccion, tipo, contenido, permite_genera_boleta)VALUES('$seccion', '$tipo_mov', '$nvo_doc', '$permitir')";
			if(mysql_query($cons_i))
			{
				$error=0;
			}
			else
			{
				//no insertado
				$error=2;
			}
		}
		else
		{
			//repetida
			$error=3;
		}
		mysql_close($conexion);	
	}
	else
	{
		//datos invalidos
		$error=1;
	}
	$url.="?error=$error";
	header("location: $url");
}
else
{
	//sin POST
	header("location: $url");
}	
?>