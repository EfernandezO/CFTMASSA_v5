<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("pago_honorario_docente_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("proceso_server.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CARGA_METODO_PAGO");
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");
//---------------------------------------------------------///
if(isset($_GET["H_id"]))
{
	$id_honorario=base64_decode($_GET["H_id"]);
	if(is_numeric($id_honorario)){ $continuar=true;}
	else{$continuar=false;}
	
}
else
{$continuar=false;}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Pago Honorario Docente</title>
<?php $xajax->printJavascript(); ?> 
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:55%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 63px;
}
#apDiv2 {
	position:absolute;
	width:75%;
	height:103px;
	z-index:2;
	left: 5%;
	top: 307px;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	c=confirm('Seguro(a) que desea Realizar este Pago de Honorario ¿?');
	if(c){xajax_VERIFICAR(xajax.getFormValues('frm')); return false;}
}
</script>
<script src="../../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
 <script src="../../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/steel/steel.css">
</head>

<body>
<h1 id="banner">Administrador - Pago Honorario Docente</h1>


<?php
if($continuar)
{
	$array_bancos=array("Estado","Chile","Santander","Internacional","Scotiabank","Credito e Inversiones","Corpbanca","Bice","BBVA","HSBC Bank","Itau","Security","Falabella","Ripley","Consorcio","Penta","Paris");
 sort($array_bancos);
    require("../../../../../funciones/conexion_v2.php");
	$cons="SELECT * FROM honorario_docente WHERE id_honorario='$id_honorario'";
	$sqli=$conexion_mysqli->query($cons);
	$D=$sqli->fetch_assoc();
		$H_mes=$D["mes_generacion"];
		$H_year=$D["year"];
		$H_year_generacion=$D["year_generacion"];
		$H_id_funcionario=$D["id_funcionario"];
		$H_sede=$D["sede"];
		$H_total=$D["total"];
		$H_estado=$D["estado"];
		$cons_A="SELECT * FROM personal WHERE id='$H_id_funcionario' LIMIT 1";
	$sql_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
	$DA=$sql_A->fetch_assoc();
		$H_rut=$DA["rut"];
		$H_nombre=$DA["nombre"];
		$H_apellido=$DA["apellido"];
	$sql_A->free();	
	$sqli->free();	
	
	if($H_estado!=="cancelado"){$action="pago_honorario_docente_2.php";}
	else{$action="#";}
	
	//busco pagos previo al honorario
	if(DEBUG){echo"Busco Pagos previos a Cuota Honorario:<br>";}
	$consPP="SELECT SUM(valor) FROM honorario_docente_pagos WHERE id_honorario='$id_honorario'";
	if(DEBUG){echo"-->$consPP<br>";}
	$sqliPP=$conexion_mysqli->query($consPP)or die($conexion_mysqli->error);
	$PP=$sqliPP->fetch_row();
	$pagosPrevios=$PP[0];
	if(empty($pagosPrevios)){$pagosPrevios=0;}
	$sqliPP->free();
	if(DEBUG){echo"Pagos previos realizados sumado: $pagosPrevios<br>";}
	$deudaXcuota=$H_total-$pagosPrevios;
	$conexion_mysqli->close();
	?>
<form action="<?php echo $action;?>" method="post" enctype="multipart/form-data" id="frm">
<div id="apDiv1">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="4">Honorario Funcionario
        <input name="id_honorario" type="hidden" id="id_honorario" value="<?php echo $id_honorario;?>" /></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>Sede</td>
      <td colspan="3"><?php echo $H_sede;?></td>
    </tr>
    <tr>
      <td width="21%">Generado en </td>
      <td colspan="3"><?php echo"[Mes: $H_mes - Año: $H_year_generacion]";?></td>
    </tr>
    <tr>
      <td>Rut</td>
      <td colspan="3"><?php echo $H_rut;?></td>
    </tr>
    <tr>
      <td>Nombre</td>
      <td colspan="3"><?php echo "$H_nombre $H_apellido";?></td>
    </tr>
    <tr>
      <td>Total</td>
      <td colspan="3"><input name="total" type="text" value="<?php echo number_format($deudaXcuota,0,"","");?>" /></td>
    </tr>
    <tr>
      <td>Forma de Pago</td>
      <td width="22%"><select name="forma_pago" id="forma_pago" onchange="xajax_CARGA_METODO_PAGO(this.value); return false;">
        <option value="cheque" selected="selected">cheque</option>
        <option value="efectivo">efectivo</option>
        <option value="transferencia">transferencia</option>
      </select></td>
      <td width="15%">Fecha Pago</td>
      <td width="42%"><input  name="fecha_pago" id="fecha_pago" size="15" maxlength="10" readonly="readonly" value="<?php echo date("Y-m-d");?>"/>
        <input type="button" name="boton" id="boton" value="..." /></td>
    </tr>
    </tbody>
  </table>
</div>
<div id="apDiv2">

  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="2">Forma de Pago</th>
    </tr>
    </thead>
    <tbody>
     <tr>
      <td width="50%">Forma Pago</td>
      <td width="50%">Cheque</td>
    </tr>
    <tr>
      <td>Numero Cheque</td>
      <td><label for="numero_cheque"></label>
        <input name="numero_cheque" type="text" id="numero_cheque" size="15" /></td>
    </tr>
    <tr>
      <td>Banco</td>
      <td><select name="cheque_banco" id="cheque_banco">
        <?php 
		 foreach($array_bancos as $n)
		 {
			 if($n=="Santander"){ $select='selected="selected"';}
			 else{$select='';}
			 
			 echo'<option value="'.$n.'" '.$select.'>'.$n.'</option>';
		 }
		 ?>
      </select></td>
    </tr>
    <tr>
      <td>Boleta Honorario</td>
      <td>
        <input type="file" name="archivo" id="archivo" />
        (*.pdf)</td>
    </tr>
    </tbody>
  </table><br /><br />
<a href="#" class="button_R" onclick="CONFIRMAR();"> Seguro(a) Desea Realizar este Pago de Honorario ¿?</a>

</div>
<?php }
else
{ echo"Sin Datos...";}
?>
</form>
<script type="text/javascript">
//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton", "fecha_pago", "%Y-%m-%d");
    //]]>
</script>
</body>
</html>