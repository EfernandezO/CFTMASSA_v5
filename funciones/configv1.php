<?php  // intranet archivo de configuracion de ruta copiado de moodle configuration file
		//06/05/2020

unset($CFG);
//global $CFG;
$CFG = new stdClass();

$CFG->wwwroot   = 'http://intranet.cftmassachusetts.cl';
$CFG->dataroot  = '/home/cftmassa/public_html/';
$CFG->funcionesRoot='/home/cftmassa/funciones';
$CFG->libreriasRoot=$CFG->wwwroot.'/libreria_publica';
$CFG->imagenesRoot=$CFG->wwwroot.'/imagenes';