<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("deudores_mensualidad_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Deudores de Mensualidades</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<script src="../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/steel/steel.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:494px;
	height:219px;
	z-index:1;
	left: 52px;
	top: 98px;
}
#link {
	text-align: right;
	padding-right: 10px;
}
#apDiv2 {
	position:absolute;
	left:52px;
	top:123px;
	width:90%;
	height:200px;
	z-index:1;
}
a:link {
	color: #006699;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #006699;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #006699;
}
#apDiv3 {
	position:absolute;
	width:200px;
	height:33px;
	z-index:2;
	left: 603px;
	top: 128px;
}
#apDiv4 {
	position:absolute;
	width:40%;
	height:60px;
	z-index:2;
	left: 30%;
	top: 823px;
	text-align: center;
}
-->
</style>
<script language="javascript">
function CAMBIA_DESTINO(opcion)
{
	switch (opcion)
	{
		case "listado":
			//alert("listado");
			document.getElementById('frm').action='listador_moroso.php';
			break;
		case "carta":
			//alert("carta");
			document.getElementById('frm').action='../genera_carta_cobranza/carta_cobranza.php';
			break;
		case "carta_aviso":
			//alert("carta");
			document.getElementById('frm').action='../genera_carta_aviso/carta_aviso.php';
			break;	
		case "email":
			//alert("carta");
			document.getElementById('frm').action='../genera_carta_aviso/carta_aviso_X_email.php';
			break;		
		case "Boletin Informativo":
			//alert("carta");
			document.getElementById('frm').action='../genera_carta_aviso/boletin_informativo_X_email.php';
			break;		
		case "Boletin Informativo Imprimible":
			//alert("carta");
			document.getElementById('frm').action='../genera_carta_aviso/boletin_informativo_imprimible.php';
			break;				
	}
}
</script>
</head>
<body>
<div id="apDiv4">Genera listados de alumnos deudores de mensualidades para alumnos Vigentes, seg&uacute;n parametros seleccionados</div>
<h1 id="banner">Administrador - Listador Alumnos Morosos (periodo actual)</h1>
<?php
$privilegio=$_SESSION["USUARIO"]["privilegio"];
switch($privilegio)
{
	case"inspeccion":
		$url="../../../Administrador/menu_inspeccion/index.php";
		break;
	default:
		$url="../../index.php";
}
 require("../../../../funciones/conexion_v2.php");
 require("../../../../funciones/funciones_sistema.php");
 $year_actual=date("Y");
?>
<div id="link"><br />
<a href="<?php echo $url;?>" class="button">Volver al menu</a></div>
<div id="apDiv2">
<form action="listador_moroso.php" method="post" name="frm" target="_blank" id="frm">
  <table width="50%" border="0" align="center">
  <thead>
    <tr>
      <th colspan="7"><strong>Parametros de Busqueda para El Listado</strong></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="24%" >Sede</td>
      <td colspan="5"><span class="Estilo2 Estilo2">
        <?php echo CAMPO_SELECCION("sede", "sede");?>
      </span></td>
    </tr>
    <tr>
      <td >Carrera</td>
      <td  colspan="5"><span class="Estilo2 Estilo2">
      <?php echo CAMPO_SELECCION("id_carrera", "carreras","",true);?>
      </span></td>
    </tr>
     <tr>
      <td >year Ingreso Carrera</td>
      <td colspan="5"><?php echo CAMPO_SELECCION("yearIngresoCarrera","year",$year_actual,true);?></td>
    </tr>
   <tr>
      <td><span class="Estilo1">Nivel (s)</span></td>
      <td width="42">1<br /><input name="nivel[]" type="checkbox" id="nivel" value="1" checked="checked" /></td>
      <td width="36">2<br /><input name="nivel[]" type="checkbox" id="nivel_2" value="2" /></td>
      <td width="32">3<br /><input name="nivel[]" type="checkbox" id="nivel_3" value="3" /></td>
      <td width="28">4<br /><input name="nivel[]" type="checkbox" id="nivel_4" value="4" /></td>
      <td width="33">5<br /><input name="nivel[]" type="checkbox" id="nivel_5" value="5" /></td>
    </tr>
    <tr>
      <td >Grupo</td>
      <td colspan="5"> <?php echo CAMPO_SELECCION("grupo","grupo","",true);?></td>
    </tr>
    <tr>
      <td>Jornada</td>
      <td colspan="5"><?php echo CAMPO_SELECCION("jornada","jornada","",true);?></td>
    </tr>
    <tr>
      <td >A&ntilde;o de Cuotas</td>
      <td colspan="5"><?php echo CAMPO_SELECCION("year_cuotas","year",$year_actual,true);?></td>
    </tr>
    <tr>
      <td >Fecha Corte</td>
      <td colspan="5"><input  name="fecha_corte" id="fecha_corte" size="10" maxlength="10" value="<?php echo date("Y-m-d"); ?>" readonly="true"/>
        <input type="button" name="boton" id="boton" value="..." />
        *utiliza fecha de corte</td>
    </tr>
    <tr>
      <td >Dias Plazo</td>
      <td colspan="5"><label for="dias_plazo"></label>
        <select name="dias_plazo" id="dias_plazo">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
          <option value="8">8</option>
          <option value="9">9</option>
          <option value="10" selected="selected">10</option>
          <option value="11">11</option>
          <option value="12">12</option>
          <option value="13">13</option>
          <option value="14">14</option>
          <option value="15">15</option>
          <option value="16">16</option>
          <option value="17">17</option>
          <option value="18">18</option>
          <option value="19">19</option>
          <option value="20">20</option>
        </select></td>
    </tr>
    <tr>
      <td rowspan="10" >Generar</td>
      <td colspan="5"><input name="opcion" type="radio" id="opcion" value="listado" checked="checked"  onclick="CAMBIA_DESTINO('listado')"/>
        Listado</td>
    </tr>
    <tr>
      <td colspan="5"><input name="opcion" type="radio" id="opcion5" value="listado_para_profesores"  onclick="CAMBIA_DESTINO('listado')"/>
Listado para Profesores*</td>
    </tr>
    <tr>
      <td colspan="5"><input name="opcion" type="radio" id="opcion3" value="listado_total"  onclick="CAMBIA_DESTINO('listado')"/>
        Listado con total</td>
    </tr>
    <tr>
      <td colspan="5"><input name="opcion" type="radio" id="opcion4" value="listado_ext"  onclick="CAMBIA_DESTINO('listado')"/>
Listado Ext.</td>
    </tr>
      <tr>
        <td colspan="5"><input type="radio" name="opcion" id="opcion8" value="boletin_informativo_mail"  onclick="CAMBIA_DESTINO('Boletin Informativo')"/>
          Boletin Informativo X mail</td>
      </tr>
      <tr>
        <td colspan="5"><input type="radio" name="opcion" id="opcion9" value="boletin_informativo_imprimible"  onclick="CAMBIA_DESTINO('Boletin Informativo Imprimible')"/>
Boletin Informativo (imprimible)</td>
      </tr>
      <tr>
        <td colspan="5"><input type="radio" name="opcion" id="opcion6" value="carta_aviso"  onclick="CAMBIA_DESTINO('carta_aviso')"/>
Carta Aviso*</td>
      </tr>
      <tr>
      <td colspan="5"><input type="radio" name="opcion" id="opcion7" value="email"  onclick="CAMBIA_DESTINO('email')"/>
        Aviso via Email*</td>
      </tr>
    <tr>
      <td><input type="radio" name="opcion" id="opcion2" value="carta"  onclick="CAMBIA_DESTINO('carta')"/>
        Carta cobranza*</td>
      </tr>
    <tr>
      <td colspan="5"><input type="submit" name="button" id="button" value="Consultar" /></td>
    </tr>
    </tbody>
  </table>
</form>  
</div>
<script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton", "fecha_corte", "%Y-%m-%d");
	   cal.manageFields("boton2", "fecha_corte2", "%Y-%m-%d");
    //]]></script>
</body>
</html>
