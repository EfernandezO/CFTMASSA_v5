<?php
if($_GET)
{
	if(isset($_GET["v"])){$V=$_GET["v"];}
	if(isset($_GET["W"])){$W=$_GET["W"];}
	
	$errores=base64_encode($_GET["x"]);
	$caracteres_texto=base64_encode($_GET["l"]);
	$tiempo_segundo=base64_encode($_GET["s"]);
	$hora_inicio=base64_encode($_GET["h"]);
	$hora_fin=base64_encode(date("Y-m-d H:i:s"));
	$id_leccion=base64_encode($_GET["ID"]);
	$pulsaciones=base64_encode($_GET["p"]);
	$indicador_tiempo=base64_encode($_GET["it"]);
	header("location: resultados.php?x=$errores&l=$caracteres_texto&s=$tiempo_segundo&hi=$hora_inicio&hf=$hora_fin&id=$id_leccion&tp=$pulsaciones&it=$indicador_tiempo");
	
}
else
{
	header("location: ../Lecciones_disponibles.php");
}
?>