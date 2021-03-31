<?php
//-----------------------------------------//
	require("../../../../OKALIS/seguridad.php");
	require("../../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//

$array_cajas=array("TC1"=>"caja 1 Talca",
					"TC2"=>"caja 2 Talca",
					"LC1"=>"caja 1 Linares");
$id_user_activo=$_SESSION["USUARIO"]["id"];					
 //////////////////////XAJAX/////////////////

@require_once ("../../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("busca_folio_server.php");
$xajax->configure('javascript URI','../../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCA_FOLIO_CAJA");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css"/>
<title>Impresion de Boleta</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:85%;
	height:26px;
	z-index:1;
	left: 10%;
	top: 296px;
}
body {
	background-color: #f5f5f5;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 47px;
}
#apDiv3 {
	position:absolute;
	width:90%;
	height:39px;
	z-index:3;
	left: 5%;
	top: 373px;
	text-align: center;
}
-->
</style>
<?php $xajax->printJavascript(); ?> 
<script language="javascript">
function ASIGNAR(folio)
{
	//alert(folio);
	document.getElementById('folio').value=folio;
}
function VERIFICAR()
{
	folio=document.getElementById('folio').value;
	if(folio=="")
	{
		alert('Debe indicar el Folio');
	}
	else
	{
		document.frm.submit();
	}
}
</script>
</head>
<?php
if(isset($_GET["folio"]))
{ $FOLIOX=$_GET["folio"];}
else{ $FOLIOX="";}
?>
<body>
<h1 id="banner">Impresi&oacute;n de Boleta </h1>
<div id="apDiv2">
<form action="boleta_2.php" method="post" name="frm"  id="frm">
<table width="300" border="0" align="center">
<thead>
  <tr>
    <th colspan="2">
    	Boleta
        <?php
if($_GET)
{
	$id_boleta=$_GET["id_boleta"];
	if(isset($_GET["semestre"]))
	{$semestre=$_GET["semestre"];}
	else{ $semestre="";}
	if(isset($_GET["year_estudio"]))
	{$year_estudio=$_GET["year_estudio"];}
	else{ $year_estudio="";}
	echo'('.$id_boleta.')<input name="id_boleta" type="hidden" value="'.$id_boleta.'" /><br>';
	echo'<input name="semestre" type="hidden" value="'.$semestre.'" />';
	echo'<input name="year_estudio" type="hidden" value="'.$year_estudio.'" />';
}
?>
    </th>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td><p>FOLIO</p>    </td>
    <td><input type="text" name="folio" id="folio"  value="<?php if(isset($FOLIOX)){ echo $FOLIOX; }?>"/></td>
  </tr>
  <tr>
    <td height="26">Sede</td>
    <td><?php
	  include("../../../../../funciones/funcion.php");
	  echo selector_sede("sede_impresion"); 
	  ?></td>
  </tr>
  <tr>
    <td>Caja</td>
    <td>
      <select name="caja" id="caja" onchange="xajax_BUSCA_FOLIO_CAJA(this.value, document.getElementById('tipoBoleta').value); return false;">
       <?php
	   //busco que caja tiene vinculada el usuario
       require("../../../../../funciones/conexion_v2.php");
	   	$cons_CA="SELECT caja_asignada FROM personal WHERE id='$id_user_activo' LIMIT 1";
		if(DEBUG){ echo"$cons_CA<br>";}
		$sql_CA=$conexion_mysqli->query($cons_CA)or die($conexion_mysqli->error);
		$D_CA=$sql_CA->fetch_assoc();
			$caja_asignada_user=$D_CA["caja_asignada"];
		$sql_CA->free();
	   //
	   if(DEBUG){echo"caja asignada: $caja_asignada_user<br>";}
	  if($caja_asignada_user=="TODAS")
	   {
		   foreach($array_cajas as $n => $valor)
		   {
			   echo'<option value="'.$n.'">'.$valor.'</option>';
		   }
	   }
	   else
	   {
		   if(!empty($caja_asignada_user))
		   {
			   
			   $aux_caja=$caja_asignada_user;
			   $aux_caja_label=$array_cajas[$aux_caja];
			   echo'<option value="'.$aux_caja.'">'.$aux_caja_label.'</option>';
		   }
		   else
		   {
			   $aux_caja="NN";
			   $aux_caja_label="sin caja asignada";
			   echo'<option value="'.$aux_caja.'">'.$aux_caja_label.'</option>';
		   }
	   }
	   ?>
      </select></td>
  </tr>
  <tr>
    <td>Tipo Boleta</td>
    <td><label for="tipoBoleta"></label>
      <select name="tipoBoleta" id="tipoBoleta" onchange="xajax_BUSCA_FOLIO_CAJA(document.getElementById('caja').value, document.getElementById('tipoBoleta').value); return false;">
        <option value="manual" selected="selected">Manual</option>
        <option value="electronica">electronica</option>
      </select></td>
  </tr>
  <tr>
    <td>Impresora</td>
    <td><label for="impresora"></label>
      <select name="impresora" id="impresora">
        <option value="okidata_320T">okidata_320T</option>
        <option value="okidata_321T">okidata_321T</option>
      </select></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="right">
      <input type="button" name="button" id="button" value="Continuar a impresion"  onclick="VERIFICAR();"/>
    </div></td>
  </tr>
  </tbody>
</table>
</form>
</div>
<div id="apDiv1">
  <div align="center">
    <img src="../../../../BAses/Images/advertencia.png" width="29" height="26" alt="ad" />
    <?php
if(!empty($caja_asignada_user))
{
	if($caja_asignada_user=="TODAS"){ $aux_caja_asignada_user="TC1";}
	else{ $aux_caja_asignada_user=$caja_asignada_user;}
	
	$cons_f="SELECT folio FROM boleta WHERE caja='$aux_caja_asignada_user' AND tipo='manual' ORDER by id DESC LIMIT 1";
	$sql_f=$conexion_mysqli->query($cons_f)or die($conexion_mysqli->error);
	$D_f=$sql_f->fetch_row();
	$last_folio=$D_f[0];
	if(DEBUG){ echo"$cons_f<br> ultimo folio: $last_folio<br>";}
	$probable_folio=$last_folio+1;
	$sql_f->free();
	echo'Probable Folio caja ('.$aux_caja_asignada_user.')<br><a href="#" onclick="ASIGNAR(\''.$probable_folio.'\')"><strong>'.$probable_folio.'</strong></a>';
}
else
{ echo"No hay Sugerencia de Folio...";}


$conexion_mysqli->close();
?></div>
</div>
<div id="apDiv3">Registra el Folio de una Boleta generada, para su posterior<br />
  impresion.</div>
</body>
</html>