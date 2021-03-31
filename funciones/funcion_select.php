<?php
function generaCarrera()
{
	include("conexion.php");
	$consulta=mysql_query("SELECT DISTINCT carrera FROM asignatura")or die(mysql_error());
	mysql_close($conexion);

	// Voy imprimiendo el primer select compuesto por los paises
	echo "<select name='fcarrera' id='fcarrera' onChange='cargaContenido(this.id)'>";
	echo "<option value='0'>Elige</option>";
	while($registro=mysql_fetch_row($consulta))
	{
		echo "<option value='".$registro[0]."'>".$registro[0]."</option>";
	}
	echo "</select>";
}
?>