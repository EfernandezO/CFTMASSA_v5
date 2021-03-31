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
	$array_tipos_cobranza=array("telefonico", "domiciliaria", "carteo","email", "sms");
	
	require("../../../../../funciones/conexion_v2.php");
	$id_cobranza=mysqli_real_escape_string($conexion_mysqli, $_GET["id_cobranza"]);
	$id_cobranza=base64_decode($id_cobranza);

	if(is_numeric($id_cobranza)){ $continuar_1=true;}
	else{ $continuar_1=false;}
	
	if($continuar_1)
	{
		$cons="SELECT * FROM cobranza WHERE id_cobranza='$id_cobranza' LIMIT 1";
		$sqli=$conexion_mysqli->query($cons) or die($conexion_mysqli->error);
			$DC=$sqli->fetch_assoc();
			$C_tipo=$DC["tipo"];
			$C_hay_respuesta=$DC["hay_respuesta"];
			$C_observacion=$DC["observacion"];
			$C_fecha_compromiso=$DC["fecha_compromiso"];
		$sqli->free();	
	}
	
	
	$conexion_mysqli->close();
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
<title>Edita Cobranza</title>
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
	top: 429px;
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
<div id="apDiv1">
<?php if($continuar_1){?>
<form action="edita_cobranza_2.php" method="post" id="frm">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="3">Detalles de la cobranza</th>
      <input name="id_cobranza" type="hidden" value="<?php echo $id_cobranza;?>" />
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>Tipo
        </td>
      <td colspan="2"><label for="tipo_cobranza"></label>
        <select name="tipo_cobranza" id="tipo_cobranza">
        <?php 
			foreach($array_tipos_cobranza as $n => $valor)
			{
				if($C_tipo==$valor){ $selected='selected="selected"';}
				else{ $selected='';}
				
				echo'<option value="'.$valor.'" '.$selected.'>'.$valor.'</option>';
			}
		?>
      </select></td>
    </tr>
    <tr>
      <td>hay respuesta <?php if(DEBUG){echo"$C_hay_respuesta";}?></td>
      <td><input type="radio" name="hay_respuesta" id="hay_respuesta" value="si" <?php if($C_hay_respuesta=="1"){ echo'checked="checked" ';}?>/>
      <label for="hay_respuesta">si</label></td>
      <td><input name="hay_respuesta" type="radio" id="hay_respuesta2" value="no" <?php if($C_hay_respuesta=="0"){ echo'checked="checked" ';}?>/>
        no</td>
    </tr>
    <tr>
      <td>Fecha Compromiso</td>
      <td colspan="2"><input  name="fecha_compromiso" id="fecha_compromiso" size="10" maxlength="10" value="<?php echo $C_fecha_compromiso; ?>" readonly="readonly"/>
        <input type="button" name="boton" id="boton" value="..." /></td>
    </tr>
    <tr>
      <td colspan="3">Observacion</td>
    </tr>
    <tr>
      <td colspan="3" align="center"><label for="observacion"></label>
      <textarea name="observacion" cols="35" rows="7" id="observacion"><?php echo $C_observacion;?></textarea></td>
    </tr>
    </tbody>
  </table>
  </form>
 <?php }else{ echo"Sin Datos<br>";}?> 
</div>
<div id="apDiv2" align="center"><a href="#" class="button_G" onclick="CONFIRMAR();">Grabar</a></div>
 <script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton", "fecha_compromiso", "%Y-%m-%d");
    //]]></script>
</body>
</html>