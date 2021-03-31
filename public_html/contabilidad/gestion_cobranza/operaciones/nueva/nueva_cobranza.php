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

if($_GET)
{
	$array_tipos_cobranza=array("telefonico", "domiciliaria", "carteo","email", "sms", "personal");
	
	require("../../../../../funciones/conexion_v2.php");
	
	$year_cuota=mysqli_real_escape_string($conexion_mysqli, $_GET["year_cuota"]);
	$year_cuota=base64_decode($year_cuota);
	$id_alumno=mysqli_real_escape_string($conexion_mysqli, $_GET["id_alumno"]);
	$id_alumno=base64_decode($id_alumno);
	$id_carrera=mysqli_real_escape_string($conexion_mysqli, $_GET["id_carrera"]);
	$id_carrera=base64_decode($id_carrera);
	$fecha_corte=mysqli_real_escape_string($conexion_mysqli, $_GET["fecha_corte"]);
	$fecha_corte=base64_decode($fecha_corte);
	
	
	if(is_numeric($id_alumno)){ $continuar_1=true;}
	else{ $continuar_1=false;}
	
	if(is_numeric($id_carrera)){ $continuar_2=true;}
	else{ $continuar_2=false;}
	
	$conexion_mysqli->close();
	$fecha_actual=date("Y-m-d");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css">
<script src="../../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/steel/steel.css">
<title>Nueva Cobranza</title>
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
	height:30px;
	z-index:2;
	left: 30%;
	top: 409px;
}
</style>
<script language="javascript" type="application/javascript">
function CONFIRMAR()
{
	c=confirm('¿Seguro(a) desea Continuar...?');
	if(c)
	{document.getElementById('frm').submit();}
}
</script>
</head>

<body>
<h1 id="banner">Finanzas - Nueva Cobranza</h1>
<?php if($continuar_1 and $continuar_2){?>
<div id="apDiv1">
<form action="nueva_cobranza_2.php" method="post" id="frm">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="3">Detalles de la cobranza</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>Tipo
        <input name="id_alumno" type="hidden" id="id_alumno" value="<?php echo $id_alumno; ?>" />
        <input name="id_carrera" type="hidden" id="id_carrera" value="<?php echo $id_carrera;?>" />
        <input name="fecha_corte" id="fecha_corte" type="hidden" value="<?php echo $fecha_corte;?>" />
        <input name="year_cuota" id="year_cuota" type="hidden" value="<?php echo $year_cuota;?>" />
        </td>
      <td colspan="2"><label for="tipo_cobranza"></label>
        <select name="tipo_cobranza" id="tipo_cobranza">
        <?php 
			foreach($array_tipos_cobranza as $n => $valor)
			{echo'<option value="'.$valor.'">'.$valor.'</option>';}
		?>
      </select></td>
    </tr>
    <tr>
      <td>hay respuesta</td>
      <td><input type="radio" name="hay_respuesta" id="hay_respuesta" value="si" />
      <label for="hay_respuesta">si</label></td>
      <td><input name="hay_respuesta" type="radio" id="hay_respuesta2" value="no" checked="checked" />
        no</td>
    </tr>
    <tr>
      <td>Fecha Compromiso</td>
      <td colspan="2"><input  name="fecha_compromiso" id="fecha_compromiso" size="10" maxlength="10" value="<?php echo $fecha_actual; ?>" readonly="readonly"/>
          <input type="button" name="boton" id="boton" value="..." /></td>
      </tr>
    <tr>
      <td colspan="3">Observacion</td>
    </tr>
    <tr>
      <td colspan="3" align="center"><label for="observacion"></label>
      <textarea name="observacion" cols="35" rows="7" id="observacion"></textarea></td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="apDiv2" align="center"><a href="#" class="button_G" onclick="CONFIRMAR();">Grabar</a></div>
 <?php }else{ echo"Sin Datos<br>";}?> 
 <script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton", "fecha_compromiso", "%Y-%m-%d");
    //]]></script>
</body>
</html>