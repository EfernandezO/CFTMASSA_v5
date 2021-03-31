<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();
	require("../../funciones/class_NOTAS.php");
	
	$id_alumno=195;
	$yearIngresoCarrera=2006;
	$idCarrera=1;
	
	$NOTA= new NOTAS($id_alumno, $yearIngresoCarrera,$idCarrera, "Talca");
	$NOTA->setDebug(true);
	
	$NOTA->setCodAsignatura(1);
	$NOTA->setYear(2006);
	$NOTA->setSemestre(1);
	
	//$NOTA->grabaNota(3.3);
	//$NOTA->borraNota();
	
?>