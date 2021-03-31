<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require("../../../funciones/class_LISTADOR_ALUMNOS.php");
define("DEBUG", true);

$LISTA = new LISTADOR_ALUMNOS();

$LISTA->setDebug(false);

$LISTA->setGrupo("A");
$LISTA->setId_carrera(4);
$LISTA->setJornada(0);
$LISTA->setNiveles(array(1));
$LISTA->setSede("Talca");
$LISTA->setYearIngressoCarrera(0);
$LISTA->setSituacionAcademica("A");

$LISTA->setSemestreVigencia(1);
$LISTA->setYearVigencia(2020);

$x=1;
foreach($LISTA->getListaAlumnos() as $n =>$auxALUMNO){

	echo"[$x]--> $n".$auxALUMNO->getRut()." ".$auxALUMNO->getYearIngresoCarreraPeriodo()." ".$auxALUMNO->getIdAlumno()." ".$auxALUMNO->getNombre()." ". $auxALUMNO->getApellido_P()." ".$auxALUMNO->getApellido_M()."<br>";
	$x++;
}

?>