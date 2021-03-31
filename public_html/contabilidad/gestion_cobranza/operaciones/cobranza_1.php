<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("gestion_cobranza_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
require("../../../../funciones/funciones_sistema.php");

$sede_usuario=$_SESSION["USUARIO"]["sede"];
$year_actual=date("Y");
$fecha_actual=date("Y-m-d");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Cobranza Alumnos</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>

<script src="../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
  <script type="text/javascript" src="../../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
  <!--INICIO MENU HORIZONTAL-->
  <link rel="stylesheet" type="text/css" href="../../../libreria_publica/menu_horizontal/ddsmoothmenu.css"/>
  <script type="text/javascript" src="../../../libreria_publica/menu_horizontal/ddsmoothmenu.js">

/***********************************************
* Smooth Navigational Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>

<script type="text/javascript">

ddsmoothmenu.init({
	mainmenuid: "smoothmenu1", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu', //class added to menu's outer DIV
	//customtheme: ["#1c5a80", "#18374a"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

</script>
<!--FIN MENU HORIZONTAL-->
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/steel/steel.css">
<style type="text/css">
.Estilo1 {font-size: 12px}
#Layer1 {	position:absolute;
	width:90%;
	height:186px;
	z-index:1;
	left: 5%;
	top: 100px;
}
#apDiv1 {
	position:absolute;
	width:40%;
	height:115px;
	z-index:2;
	left: 30%;
	top: 425px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Cobranza Alumnos</h1>
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
	<li><a href="#">Informes</a>
      <ul>
     	 <li><a href="../informes/cobranzas_realizadas/cobranza_realizada_1.php">Cobranzas Realizadas</a></li>
      </ul>
    </li>
	<li><a href="../../index.php">Volver a Menu</a></li>
</ul>
</li>
</ul>
<br style="clear: left" />
</div>
<div id="Layer1">
  <form action="cobranza_2.php" method="post" name="frm" id="frm">
    <table width="50%" border="1" align="center">
      <caption>
      </caption>
      <thead>
        <tr>
          <th colspan="6"><span class="Estilo1">Busqueda de Alumnos </span></th>
        </tr>
      </thead>
      <tbody>
        <tr class="odd">
          <td width="160"><span class="Estilo1">Sede</span></td>
          <td colspan="5">
		  <?php
			  echo CAMPO_SELECCION("sede","sede",$sede_usuario, false);	  	?>
      </td>
        </tr>
        <tr class="odd">
          <td><span class="Estilo1">Carrera</span></td>
          <td colspan="5">
		  <?php  echo CAMPO_SELECCION("carrera","carreras","1",true);?>
		  </td>
        </tr>
        <tr class="odd">
          <td><span class="Estilo1">A&ntilde;o Ingreso </span></td>
          <td colspan="5"><?php  echo CAMPO_SELECCION("year","year",0,true);?></td>
        </tr>
        <tr class="odd">
          <td><span class="Estilo1">A&ntilde;o Cuotas</span></td>
          <td colspan="5"><?php  echo CAMPO_SELECCION("year_cuotas","year",$year_actual,true);?></td>
        </tr>
        <tr class="odd">
          <td><span class="Estilo1">Jornada</span></td>
          <td colspan="5">
           <?php  echo CAMPO_SELECCION("jornada","jornada","",true);?>
          </td>
        </tr>
        <tr class="odd">
          <td><span class="Estilo1">Grupo</span></td>
          <td colspan="5"><?php  echo CAMPO_SELECCION("grupo","grupo");?></td>
        </tr>
        <tr class="odd">
          <td>matricula vigente</td>
          <td colspan="5"><label for="matricula_vigente"></label>
            <select name="matricula_vigente" id="matricula_vigente">
              <option value="si">si</option>
              <option value="no" selected="selected">no</option>
              <option value="todos">todos</option>
          </select></td>
        </tr>
        <tr class="odd">
          <td>Fecha Corte*</td>
          <td colspan="5"><input  name="fecha_corte" id="fecha_corte" size="10" maxlength="10" value="<?php echo $fecha_actual; ?>" readonly="readonly"/>
          <input type="button" name="boton" id="boton" value="..." />
          <div id="apDiv1">
            <p>Permite mediante la seleccion de un grupo de alumnos, comprobar sus cuotas adeudadas y comenzar el proceso de cobranza.</p>
            <p>*Fecha Corte: todos los vencimientos de cuotas menores o iguales a la fecha de corte seran consideradas.<br />
            </p>
            <p>*Solamente es posible seleccionar alumnos que tienen activada la opcion &quot;seleccionados para realizar cobranza&quot;</p>
          </div></td>
        </tr>
        <tr class="odd">
          <td><span class="Estilo1">Nivel (s)</span></td>
          <td width="42">1 <br />
            <input name="nivel[]" type="checkbox" id="nivel[]" value="1" checked="checked" />
          <label for="nivel[]"></label></td>
          <td width="36">2 <br />
            <input name="nivel[]2" type="checkbox" id="nivel[]2" value="2" checked="checked" /></td>
          <td width="32">3<br />
            <input name="nivel[]3" type="checkbox" id="nivel[]3" value="3" checked="checked" /></td>
          <td width="28">4<br />
            <input name="nivel[]4" type="checkbox" id="nivel[]4" value="4" checked="checked" /></td>
          <td width="33">5<br />
            <input name="nivel[]5" type="checkbox" id="nivel[]5" value="5" /></td>
        </tr>
      </tbody>
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
<script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton", "fecha_corte", "%Y-%m-%d");
    //]]></script>
</body>
</html>