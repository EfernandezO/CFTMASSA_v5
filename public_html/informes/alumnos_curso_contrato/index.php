<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_alumnos_X_curso_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Administrador - informe</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:186px;
	z-index:1;
	left: 5%;
	top: 100px;
}
.Estilo1 {font-size: 12px}
#Layer2 {
	position:absolute;
	width:168px;
	height:16px;
	z-index:2;
	left: 420px;
	top: 49px;
}
-->
</style>
</head>

<body>
<h1 id="banner">Administrador - Informe de Cursos V3.1</h1>
<?php
$year_actual=date("Y");
require("../../../funciones/funciones_sistema.php");
$privilegio=$_SESSION["USUARIO"]["privilegio"];
switch($privilegio)
{
	case"matricula":
		$url="../../Administrador/menu_matricula/index.php";
		break;
	case"inspeccion":
		$url="../../Administrador/menu_inspeccion/index.php";
		break;
	default:
		$url="../../Administrador/ADmenu.php";	
}
?>
<div id="link"><br><a href="<?php echo $url;?>" class="button">Volver al menu Principal </a>
  </div>
<div id="Layer1">
<form action="genera_informe_v2.php" method="post" name="frm" target="_blank" id="frm">
  <table width="50%" border="1" align="center">
  <caption></caption>
  <thead>
    <tr>
      <th colspan="6"><span class="Estilo1">Busqueda de Alumnos </span></th>
    </tr>
	</thead>
	<tbody>
    <tr class="odd">
      <td width="160"><span class="Estilo1">Sede</span></td>
      <td colspan="5">
      <?php echo CAMPO_SELECCION("fsede","sedexprivilegio","",false,"","fsede");?>
      </td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Carrera</span></td>
      <td colspan="5"><?php echo CAMPO_SELECCION("id_carrera", "carreras", "",true);?></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">A&ntilde;o Ingreso </span></td>
      <td colspan="5"><?php echo CAMPO_SELECCION("year_ingreso","year", $year_actual,true);?></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Jornada</span></td>
      <td colspan="5"><?php echo CAMPO_SELECCION("jornada","jornada","",true);?></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Grupo</span></td>
      <td colspan="5"><select name="grupo" id="grupo">
        <option value="Todos" selected="selected">Todos</option>
        <?php 
		foreach(range('A', 'Z') as $letra)
		{echo'<option value="'.$letra.'">'.$letra.'</option>';}
		?>
        </select></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Nivel (s)</span></td>
      <td width="42">1<br /><input name="nivel[]" type="checkbox" id="nivel[]" value="1" /></td>
      <td width="36">2<br /><input name="nivel[]" type="checkbox" id="nivel[]2" value="2" /></td>
      <td width="32">3<br /><input name="nivel[]" type="checkbox" id="nivel[]3" value="3" /></td>
      <td width="28">4<br /><input name="nivel[]" type="checkbox" id="nivel[]4" value="4" /></td>
      <td width="33">5<br /><input name="nivel[]" type="checkbox" id="nivel[]5" value="5" /></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo1">Estado Actual</span></td>
      <td colspan="5"><select name="estado" id="estado">
        <option value="V">Vigente</option>
        <option value="T">Titulados</option>
        <option value="A" selected="selected">Todos</option>
        <option value="EG">Egresado</option>
        <option value="P">Postergado</option>
        <option value="R">Retirado</option>
        <option value="E">Eliminado</option>
        </select> 
      *siempre utilizar&quot;Todos&quot; para años anteriores</td>
    </tr>
    <tr class="odd">
      <td>A&ntilde;o Vigencia Contrato</td>
      <td colspan="5"><?php echo CAMPO_SELECCION("year_vigencia_contrato","year", $year_actual,false);?></td>
    </tr>
    <tr class="odd">
      <td>Semestre Vigencia Contrato</td>
      <td colspan="5"><?php echo CAMPO_SELECCION("semestre_vigencia_contrato","semestre", "",false);?></td>
    </tr>
    <tr class="odd">
      <td>&nbsp;</td>
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr class="odd">
      <td>Tipo Documento</td>
      <td colspan="2"><input name="tipo_documento" type="radio" id="tipo_documento" value="normal" checked="checked" />
        <label for="tipo_documento"></label>
        Lista Alumnos</td>
      <td><input type="radio" name="tipo_documento" id="tipo_documento2" value="asistencia" />
        asistenc&iacute;a</td>
      <td colspan="2"><input name="tipo_documento" type="radio" id="tipo_documento3" value="full" />
        Full para xls</td>
      </tr>
    </tbody>
    <tr>
      <td>Formato</td>
      <td colspan="2"><input name="formato_salida" type="radio" id="formato_salida" value="pdf" checked="checked" />
        <label for="formato_salida">pdf</label></td>
      <td><input type="radio" name="formato_salida" id="formato_salida2" value="xls" />
        xls</td>
      <td colspan="2"><input type="radio" name="formato_salida" id="formato_salida3" value="html" />
        html</td>
      </tr>
	<tfoot>
    <tr>
      <td colspan="6"><div align="right">
        <input type="submit" name="Submit" value="Generar Informe" />
      </div></td>
      </tr>
	</tfoot>
  </table>
 </form> 
</div>
</body>
</html>