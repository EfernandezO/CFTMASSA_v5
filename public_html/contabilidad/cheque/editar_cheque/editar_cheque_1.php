<?php
//-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", false);
//-----------------------------------------//

if(isset($_GET["id_cheque"]))
{
	$id_cheque=$_GET["id_cheque"];
	if(is_numeric($id_cheque))
	{ $continuar=true;}
	else
	{ $continuar=false;}
}
else
{ $continuar=false;}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<title>Documento sin título</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 59px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:28px;
	z-index:2;
	left: 30%;
	top: 339px;
	text-align: center;
}
</style>
 <script src="../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
 <script src="../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/steel/steel.css">
<script language="javascript">
function CONFIRMAR()
{
	c=confirm('Seguro(a) Desea Modificar este Cheque..?');
	if(c){document.getElementById('frm').submit();}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Finanzas Cheques</h1>
<div id="apDiv1">
<?php if($continuar){
		$array_bancos=array("Estado","Chile","Santander","Internacional","Scotiabank","Credito e Inversiones","Corpbanca","Bice","BBVA","HSBC Bank","Itau","Security","Falabella","Ripley","Consorcio","Penta","Paris");
	 	sort($array_bancos);
		require("../../../../funciones/conexion_v2.php");
		
		$cons_ch="SELECT * FROM registro_cheques WHERE id='$id_cheque' LIMIT 1";
		$sqli_ch=$conexion_mysqli->query($cons_ch)or die($conexion_mysqli->error);
			$CH=$sqli_ch->fetch_assoc();
				$CH_numero=$CH["numero"];
				$CH_fecha_vencimiento=$CH["fecha_vencimiento"];
				$CH_banco=$CH["banco"];
				$CH_valor=$CH["valor"];
				$CH_condicion=$CH["condicion"];
				$CH_fecha_condicion=$CH["fecha_condicion"];
				$CH_sede=$CH["sede"];
				$CH_fecha=$CH["fecha"];
				$CH_glosa=$CH["glosa"];
			$sqli_ch->free();	
		
		@mysql_close($conexion);
		$conexion_mysqli->close();
?>	
<form action="editar_cheque_2.php" method="post" id="frm">
  <table width="80%" border="1" align="center">
  <thead>
    <tr>
      <th colspan="2">Cheque</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="51%">id cheque</td>
      <td width="49%"><?php echo $id_cheque;?><input name="id_cheque" type="hidden" value="<?php echo $id_cheque;?>" /></td>
    </tr>
    <tr>
      <td>Valor</td>
      <td><label for="valor"></label>
        <input type="text" name="valor" id="valor"  value="<?php echo $CH_valor;?>"/></td>
    </tr>
    <tr>
      <td>numero Cheque</td>
      <td><input name="numero_cheque" type="text"  value="<?php echo $CH_numero;?>"/></td>
    </tr>
    <tr>
      <td>Banco</td>
      <td><select name="cheque_banco" id="cheque_banco">
                <?php 
		 foreach($array_bancos as $n)
		 {
			if($n==$CH_banco)
			{echo'<option value="'.$n.'" selected="selected">'.$n.'</option>';}
			else{echo'<option value="'.$n.'">'.$n.'</option>';}
		 }
		 ?>
            </select></td>
    </tr>
    <tr>
      <td>Fecha Emision</td>
      <td>
      <input  name="cheque_fecha_emision" id="cheque_fecha_emision" size="10" maxlength="10" readonly="readonly" value="<?php echo $CH_fecha;?>"/>
        <input type="button" name="boton" id="boton" value="..." /></td>
    </tr>
    <tr>
      <td>Fecha Vencimiento</td>
      <td><input  name="cheque_fecha_vence" id="cheque_fecha_vence" size="10" maxlength="10"readonly="readonly" value="<?php echo $CH_fecha_vencimiento;?>"/>
        <input type="button" name="boton2" id="boton2" value="..." /></td>
    </tr>
    </tbody>
  </table>
  </form>
 <?php }else{ echo"Sin datos :(<br>";}?> 
</div>
<div id="apDiv2"><a href="#" class="button_G" onclick="CONFIRMAR();">Modificar</a></div>
<script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton2", "cheque_fecha_vence", "%Y-%m-%d");
	  cal.manageFields("boton", "cheque_fecha_emision", "%Y-%m-%d");

    //]]></script>
</body>
</html>