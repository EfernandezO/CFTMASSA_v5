<?php include ("../../../SC/seguridad.php");?>
<?php include ("../../../SC/privilegio.php");?>
<?php
if($_GET)
{
	include("../../../../funciones/conexion.php");
	$tipo=strip_tags($_GET["mov"]);
	$seccion="finanzas";
	$nombre_select="ftipo_doc";
	$select='<select name="'.$nombre_select.'" id="'.$nombre_select.'">';
	$cons="SELECT contenido FROM parametros WHERE seccion='$seccion' AND tipo='$tipo'";
	//echo"---> $cons<br>";
	$sql=mysql_query($cons)or die(mysql_error());
	$num_reg=mysql_num_rows($sql);
	//echo"$num_reg<br>";
	if($num_reg>0)
	{
		while($A=mysql_fetch_assoc($sql))
		{
			$contenido=$A["contenido"];
			//echo"$contenido<br>";
			$select.='<option value="'.$contenido.'">'.$contenido.'</option>';
		}
	}
	else
	{
		$select.='<option>Sin Elementos</option>';
	}
	$select.='</select>';
	mysql_free_result($sql);
	mysql_close($conexion);
	echo "&nbsp;&nbsp;".$select;
}
?>