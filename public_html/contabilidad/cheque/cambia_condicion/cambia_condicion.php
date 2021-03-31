<?php
   require("../../../SC/seguridad.php");
   require("../../../SC/privilegio2.php");
   define("DEBUG",true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>cambio condicon - cheque</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<style>
#link {
	text-align: right;
	padding-right: 10px;
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
#apDiv1 {
	position:absolute;
	width:309px;
	height:115px;
	z-index:1;
	left: 26px;
	top: 110px;
}
-->
</style>
<script src="../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../../libreria_publica/JSCal/src/js/lang/es.js"></script>

<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/steel/steel.css">
<style type="text/css">
<!--
#apDiv2 {
	position:absolute;
	width:634px;
	height:115px;
	z-index:2;
	left: 20px;
	top: 75px;
}
.Estilo6 {font-size: 10px}
.Estilo7 {font-size: 12px}
-->
</style>
<script language="javascript">
function Verificar()
{
	document.frm.submit();
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Finanzas Cheques</h1>
<div id="link"><a href="../index.php">Volver a Seleccion</a></div>
<?php
	if(DEBUG){ var_export($_POST);}
?>
<div id="apDiv2">
<form action="cambia_condicion_2.php" method="post" name="frm" id="frm">
 <table width="50%" border="1">
    <tr>
      <td colspan="2">Nueva condicion para Cheques Seleccionados</td>
    </tr>
    <tr>
      <td>Nva Condicion</td>
      <td><select name="condicion" id="condicion">
        <option value="depositado">Depositado</option>
        <option value="protestado">Protestado</option>
      </select>      </td>
    </tr>
    <tr>
      <td>Fecha</td>
      <td><input  name="fecha_condicion" id="fecha_condicion" size="10" maxlength="10" readonly="true" value="<?php echo date("Y-m-d");?>"/>
        <input type="button" name="boton2" id="boton2" value="..." /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <table width="100%" border="1">
    <tr>
      <td width="4%">N&deg;</td>
      <td width="4%">ID</td>
      <td width="15%">Banco</td>
      <td width="15%">Valor</td>
      <td width="19%">Recivido</td>
      <td width="21%">Vencimiento</td>
      <td width="22%">condicion actual</td>
    </tr>
    <?php
		$id_cheque=$_POST["id_cheque"];
		$aux=true;
		foreach($id_cheque as $n => $valor)
		{
			if($aux)
			{
				$concat_id_cheque.="$valor";
				$aux=false;
			}
			else
			{
				$concat_id_cheque.=", $valor";
			}	
		}
		include("../../../../funciones/conexion.php");
		include("../../../../funciones/funcion.php");
		$cons_ch="SELECT * FROM registro_cheques WHERE id IN($concat_id_cheque)";
		if(DEBUG){ echo "$cons_ch <br>";}
		$sql_ch=mysql_query($cons_ch)or die(mysql_error());
		$num_reg=mysql_num_rows($sql_ch);
		if($num_reg>0)
		{
			$contador=0;
			while($CH=mysql_fetch_assoc($sql_ch))
			{
				$contador++;
				$id_chequex=$CH["id"];
				$banco=$CH["banco"];
				$valor=$CH["valor"];
				$fecha=$CH["fecha"];
				$fecha_vencimiento=$CH["fecha_vencimiento"];
				$condicion=$CH["condicion"];
				
				echo'<tr>
					 <td>'.$contador.'</td>	
					 <td>'.$id_chequex.'</td>
					 <td>'.$banco.'</td>
					 <td>'.$valor.'</td>
					 <td>'.fecha_format($fecha).'</td>
					 <td>'.fecha_format($fecha_vencimiento).'</td>
					 <td>'.$condicion.'</td>
					 </tr>';
					 
			}
		}
		else
		{}
		mysql_close($conexion);
    ?>
  </table>
  &iquest;Est&aacute; Seguro(a) que desea Cambiar de Condicion estos Cheque(s)?</span>
    <input type="checkbox" name="checkbox2" value="checkbox" onclick="document.frm.Submit.disabled=!document.frm.Submit.disabled" />
Si. Seguro(a). <span class="Estilo6 Estilo7">
<input name="id_cheque" type="hidden" id="id_cheque" value="<?php echo $concat_id_cheque;?>" />
<input type="button" name="Submit" value="continuar" disabled="disabled"  onclick="Verificar();"/>
</span>
</form>
</div>
</body>
 <script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton2", "fecha_condicion", "%Y-%m-%d");
    //]]></script>
</html>
