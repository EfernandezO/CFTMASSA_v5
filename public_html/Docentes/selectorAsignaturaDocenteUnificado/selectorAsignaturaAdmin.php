<?php
//--------------CLASS_okalis------------------//
require("../../OKALIS/class_OKALIS_v1.php");
define("DEBUG", false);
$O=new OKALIS();
$O->DEBUG=DEBUG;
$O->setDisplayErrors(false);
$O->ruta_conexion="../../../funciones/";
$O->clave_del_archivo=md5("SelectorAsignaturaUnificadoAdministradorV1->ver");
$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("busca_asignaturasAdmin_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCAR_JORNADA_GRUPO");
$xajax->register(XAJAX_FUNCTION,"BUSCAR_ASIGNATURAS");
$xajax->register(XAJAX_FUNCTION,"BUSCAR_CARRERAS");
$xajax->register(XAJAX_FUNCTION,"BUSCAR_SEDES");
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");
$xajax->register(XAJAX_FUNCTION,"BUSCAR_FUNCIONARIOS");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<title>Libro clases - Selector asignatura administrador</title>
<?php $xajax->printJavascript(); ?> 
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 79px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:100px;
	z-index:2;
	left: 30%;
	top: 383px;
	text-align: center;
}
#apDiv3 {
	position:absolute;
	width:40%;
	height:30px;
	z-index:3;
	left: 30%;
	top: 450px;
	text-align: center;
}
</style>
<script language="javascript">
function VERIFICAR()
{
	continuar=true;
	asignatura=document.getElementById('asignatura').value;
	grupo_curso=document.getElementById('grupo_curso').value;
	jornada=document.getElementById('jornada').value;
	
	if((asignatura==0)||(asignatura=""))
	{ continuar=false; alert('Seleccione Asignatura');}
	
	if((grupo_curso==0)||(grupo_curso=""))
	{ continuar=false; alert('Seleccione Grupo');}
	
	if((jornada==0)||(jornada=""))
	{ continuar=false; alert('Seleccione Jornada');}
	
	if(continuar)
	{document.getElementById('frm').submit();}
}
</script>
</head>
<?php
$id_usuario=$_SESSION["USUARIO"]["id"];
$privilegio=$_SESSION["USUARIO"]["privilegio"];
$sede_usuario=$_SESSION["USUARIO"]["sede"];
$mes_actual=date("m");
$year_actual=date("Y");

///segundo semestre desde mes 8 por inicio semestre intitucion
if($mes_actual>=8){$semestre_actual=2;}
else{ $semestre_actual=1;}
//echo"Semestre actual= $semestre_actual<br>";

$array_jornada=array("D"=>"Diurno","V"=>"Vespertino");
$array_sede=array();
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funcion.php");

	switch($privilegio)
	{
		case"admi":
			$url_menu="../../buscador_alumno_BETA/HALL/index.php";	
			$campo_sede=selector_sede("sede", 'onchange="xajax_BUSCAR_CARRERAS(this.value, document.getElementById(\'semestre\').value, document.getElementById(\'year\').value); return false;"',false,true);
			break;
		case"admi_total":
			$url_menu="../../buscador_alumno_BETA/HALL/index.php";
			$campo_sede=selector_sede("sede", 'onchange="xajax_BUSCAR_CARRERAS(this.value, document.getElementById(\'semestre\').value, document.getElementById(\'year\').value); return false;"',false,true);
			break;	
	}
$conexion_mysqli->close();

?>
<body>

<h1 id="banner">Administrador -  Selector asignaturas Unificado - Libro Clases</h1>
<div id="link"><br />
<a href="<?php echo $url_menu;?>" class="button">Volver a Seleccion</a>
<a href="../../Alumnos/menualumnos.php" class="button">Volver a Menu</a>
</div>
<div id="apDiv1">
<form action="#" method="post" target="_blank" id="frm">
  <table width="50%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="2">Buscar Curso</th>
    </tr>
    <tr>
      <td width="46%">Semestre</td>
      <td width="54%"><select name="semestre" id="semestre" onchange="xajax_BUSCAR_SEDES(this.value, document.getElementById('year').value); return false;">
        <option value="1" <?php if($semestre_actual==1){ echo'selected="selected"';}?>>1</option>
        <option value="2" <?php if($semestre_actual==2){ echo'selected="selected"';}?>>2</option>
      </select></td>
    </tr>
    <tr>
      <td>A&ntilde;o</td>
      <td><select name="year" id="year" onchange="xajax_BUSCAR_SEDES(document.getElementById('semestre').value, this.value); return false;">
        <?php
       	$year_inicio=2000;
		$year_final=$year_actual;
		for($y=$year_inicio;$y<=$year_final;$y++)
		{
			if($y==$year_actual)
			{ echo'<option value="'.$y.'" selected="selected">'.$y.'</option>';}
			else
			{ echo'<option value="'.$y.'">'.$y.'</option>';}
		}
	   ?>
      </select></td>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>Sede</td>
      <td><div id="div_sede">
       <?php echo $campo_sede;?>
      </div></td>
    </tr>
    <tr>
      <td>Carrera</td>
      <td>
      <div id="div_carrera">...
        <input name="carrera" type="hidden" id="carrera" value="0" />
      </div></td>
    </tr>
    <tr>
      <td>Asignatura</td>
      <td><div id="div_asignaturas">...
        <input name="asignatura" type="hidden" id="asignatura" value="0" />
        </div></td>
    </tr>
    <tr>
      <td>Jornada</td>
      <td><div id="div_jornada">...
          <input name="jornada" type="hidden" id="jornada" value="0" />
      </div></td>
    </tr>
    <tr>
      <td height="34">Grupo</td>
      <td><div id="div_grupo">
        ...
        <input name="grupo_curso" type="hidden" id="grupo_curso" value="0" />
      </div>    
      </td>
    </tr>
    <tr>
      <td height="34">Docente</td>
      <td><div id="div_docente">
        ...
        <input name="funcionario" type="hidden" id="funcionario" value="0" />
      </div>    
      </td>
    </tr>
    <tr>
    <td colspan="2"><a href="#" class="button_AMARILLO" onclick="xajax_VERIFICAR(xajax.getFormValues('frm'))">Ver Opciones</a></td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="apDiv3">Permise acceder al calificador de los cursos, para los cuales el docente tiene una asignacion previa y gestionar las <br />
  notas Parciales
</div>
<div id="apDiv2">


</div>
</body>
</html>