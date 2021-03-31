<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("gestion_cobranza_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
require("../../../../../funciones/funciones_sistema.php");

$sede_usuario=$_SESSION["USUARIO"]["sede"];
$year_actual=date("Y");
$fecha_actual=date("Y-m-d");

//---------------------------------------------//
//busco id_personal en cobranza
require("../../../../../funciones/conexion_v2.php");
$cons_P="SELECT DISTINCT(cod_user) FROM cobranza";
$sqli_P=$conexion_mysqli->query($cons_P)or die($conexion_mysqli->error);
$num_reg=$sqli_P->num_rows;
$ARRAY_ID_PERSONAL=array();
if($num_reg>0)
{
	$i=0;
	$continuar=true;
	while($P=$sqli_P->fetch_row())
	{
		$aux_id_personal=$P[0];
		$ARRAY_ID_PERSONAL[$i]=$aux_id_personal;
		$i++;
	}
	
}
else
{ $continuar=false;}
$sqli_P->free();
//----------------------------------------//

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php");?>
<title>Cobranza Alumnos</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css"/>

<script src="../../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/steel/steel.css">
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
	top: 359px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:63px;
	z-index:2;
	left: 30%;
	top: 337px;
	bottom: -1px;
}
#apDiv3 {
	position:absolute;
	width:40%;
	height:31px;
	z-index:2;
	left: 30%;
	top: 398px;
	text-align:center;
}
</style>
<script language="javascript" type="text/javascript">
function CONFIRMAR()
{
	c=confirm('Seguro(a) desea generar este Informe..?');
	if(c){document.getElementById('frm').submit();}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Informe Cobranzas Realizadas</h1>
<div id="link"><br />
<a href="../../operaciones/cobranza_1.php" class="button">Volver a Seleccion</a></div>
<div id="Layer1">
  <form action="cobranza_realizada_2.php" method="post" name="frm" id="frm">
    <table width="50%" border="1" align="center">
      <caption>
      </caption>
      <thead>
        <tr>
          <th colspan="5"><span class="Estilo1">Busqueda de Alumnos </span></th>
        </tr>
      </thead>
      <tbody>
        <tr class="odd">
          <td>Rut (filtro)</td>
          <td colspan="4"><label for="rut_alumno"></label>
          <input type="text" name="rut_alumno" id="rut_alumno" /></td>
        </tr>
        <tr class="odd">
          <td width="146"><span class="Estilo1">Sede</span></td>
          <td colspan="4">
		  <?php
			  echo CAMPO_SELECCION("sede","sede",$sede_usuario, false);	  	?>
      </td>
        </tr>
        <tr class="odd">
          <td><span class="Estilo1">Carrera</span></td>
          <td colspan="4">
		  <?php  echo CAMPO_SELECCION("carrera","carreras","1",true);?>
		  </td>
        </tr>
        <tr class="odd">
          <td>Usuario</td>
          <td colspan="4">
          	<select name="id_personal">
			<?php
			if($continuar)
			{
				foreach($ARRAY_ID_PERSONAL as $n =>$valor)
				{
					$aux_nombre_personal=NOMBRE_PERSONAL($valor);
					echo'<option value="'.$valor.'">'.$valor.'_'.$aux_nombre_personal.'</option>';
				}
				echo'<option value="0">Todos</option>';
			}
			else
			{echo'<option value="-1">Sin Registros</option>';}
			?> 
            	</select>         
          </td>
        </tr>
        <tr class="odd">
          <td>Campo para Periodo</td>
          <td colspan="4"><label for="campo_periodo"></label>
            <select name="campo_periodo" id="campo_periodo">
              <option value="fecha" selected="selected">fecha realizacion</option>
              <option value="fecha_compromiso">fecha_compromiso</option>
          </select></td>
        </tr>
        <tr class="odd">
          <td>Periodo</td>
          <td width="46">desde</td>
          <td width="108"><input  name="fecha_inicio" id="fecha_inicio" size="10" maxlength="10" value="<?php echo $fecha_actual; ?>" readonly="readonly"/>
            <input type="button" name="boton1" id="boton1" value="..." />
          </td>
          <td width="41">Hasta</td>
          <td width="112"><input  name="fecha_fin" id="fecha_fin" size="10" maxlength="10" value="<?php echo $fecha_actual; ?>" readonly="readonly"/>
            <input type="button" name="boton2" id="boton2" value="..." />
          </td>
        </tr>
        <tr class="odd">
          <td colspan="5">&nbsp;</td>
        </tr>
      </tbody>
    </table>
  </form>
    <div id="apDiv2">
              <p>Genera Un listado de las cobranzas realizadas en un periodo de tiempo establecido.</p>
</div
></div>
<div id="apDiv3"><?php if($continuar){?><a href="#" class="button_G" onclick="CONFIRMAR();">Generar Informe</a><?php }?></div>
<script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton1", "fecha_inicio", "%Y-%m-%d");
	  cal.manageFields("boton2", "fecha_fin", "%Y-%m-%d");
    //]]></script>
</body>
</html>