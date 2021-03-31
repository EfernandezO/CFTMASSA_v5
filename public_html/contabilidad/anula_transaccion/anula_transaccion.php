<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
//-----------------------------------------//
 define("DEBUG", false);
 if(($_GET)and($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
 { 
 	$continuar=true;
	$_SESSION["ANULA"]["VERIFICADOR"]=true;
 }
 else
 { $continuar=false;}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<title>Anulacion</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:156px;
	z-index:1;
	left: 5%;
	top: 107px;
}
-->
</style>
<script language="javascript">
function CONFIRMAR()
{
	c=confirm('¿Seguro(a) Desea Anular esta Transaccion?\n NOTA: Si se imprimio la boleta se ANULARA, sino se eliminara el registro, el Registro del Pago se Eliminara y se Volvera Al estado Anterior La cuota.');
	if(c)
	{document.frm.submit();}
}
</script>
</head>

<body>
<?php if($continuar){
	if(DEBUG){ var_export($_GET);}
	$id_cuota=$_GET["id_cuota"];
	$alumno=$_SESSION["SELECTOR_ALUMNO"]["nombre"]." ".$_SESSION["SELECTOR_ALUMNO"]["apellido"];
	$valor=$_GET["valor"];
	$id_boleta=$_GET["id_boleta"];
	
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
?>
<h1 id="banner">Finanzas - Anula Transacci&oacute;n</h1>
<div id="apDiv1">
<form action="anula_transaccion_2.php" method="post" name="frm" id="frm">
  <div align="center">
    <table width="60%" border="1">
    	<thead>
      <tr>
        <th colspan="3"><strong>Verificacion Previa (Anulaci&oacute;n)</strong></th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td>&#9658;ID Alumno</td>
        <td colspan="2"><?php echo $id_alumno;?></td>
        </tr>
      <tr>
        <td>&#9658;ALUMNO</td>
        <td colspan="2"><?php echo $alumno;?></td>
      </tr>
      <tr>
        <td>&#9658;ID CUOTA</td>
        <td colspan="2"><?php echo $id_cuota;?>
          <input name="id_cuota" type="hidden" id="id_cuota" value="<?php echo $id_cuota;?>" /></td>
        </tr>
      <tr>
        <td>&#9658;ID BOLETA</td>
        <td colspan="2"><?php echo $id_boleta;?>
          <input name="id_boleta" type="hidden" id="id_boleta" value="<?php echo $id_boleta;?>" /></td>
        </tr>
      <tr>
      <td>&#9658;Valor</td>
      <td colspan="2">$<?php echo number_format($valor,0,",",".");?>
        <input name="valor" type="hidden" id="valor" value="<?php echo $valor;?>" /></td>
      </tr>
      <tr>
        <td width="38%" rowspan="2">&iquest;Imprimio la Boleta?</td>
        <td width="25%"><input type="radio" name="boleta_impresa" id="radio" value="si" />
        Si</td>
        <td width="37%">&nbsp;</td>
      </tr>
      <tr>
        <td><input name="boleta_impresa" type="radio" id="radio2" value="no" checked="checked" />
        No</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3"><div align="right">
          <input type="button" name="Anula" id="Anula" value="Anula"  onclick="CONFIRMAR();"/>
        </div></td>
      </tr>
      </tbody>
    </table>
    <p><a href="javascript:history.back();" class="button">Volver atras</a> <a href="../pagacuo/cuota1.php" class="button">Volver A Cuotas</a></p>
  </div>
  </form>
</div>
<?php }else{?>
<div id="msj">Sin Datos :(</div>
<?php }?>
</body>
</html>
