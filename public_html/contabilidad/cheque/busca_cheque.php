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
//-----------------------------------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Busca Cheque</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<!--INICIO LIGHTBOX EVOLUTION-->
  <script type="text/javascript" src="../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
 <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox(); 
    });
  </script>
 <!--FIN LIGHTBOX EVOLUTION--> 
<style type="text/css">
<!--
.Estilo1 {font-size: 12px}
#apDiv1 {
	position:absolute;
	width:797px;
	height:49px;
	z-index:1;
	left: 21px;
	top: 253px;
}
#apDiv2 {
	position:absolute;
	width:315px;
	height:116px;
	z-index:2;
	left: 21px;
	top: 60px;
}
.Estilo3 {font-size: 12px; font-weight: bold; }
.Estilo5 {font-size: 12px; font-style: italic; }
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
-->
</style>
<script language="javascript">
function CONFIRMAR()
{
	c=confirm('Cambiar de Condicion??');
	if(c)
	{
		document.frm.submit();
	}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Finanzas Cheques</h1>
<div id="link"><br />
<a href="index.php" class="button">Volver a Seleccion</a></div>
<div id="apDiv1">
<form action="cambia_condicion/cambia_condicion.php" method="post" name="frm" id="frm">
  <table width="100%" border="1">
    <thead>
      <tr align="center">
         <th width="2%" ><div align="center"><span class="Estilo1">-</span></div></th>
        <th width="4%"><div align="center"><span class="Estilo1">N&deg;</span></div></th>
        <th width="4%"><div align="center"><span class="Estilo1">ID</span></div></th>
        <th width="7%"><div align="center"><span class="Estilo1">Alumno</span></div></th>
        <th width="9%"><div align="center"><span class="Estilo1">Numero</span></div></th>
        <th width="8%"><div align="center"><span class="Estilo1">Emision</span></div></th>
        <th width="9%"><div align="center"><span class="Estilo1">Banco</span></div></th>
        <th width="7%"><div align="center"><span class="Estilo1">Valor</span></div></th>
        <th width="13%"><div align="center"><span class="Estilo1">Condicion</span></div></th>
        <th width="12%"><div align="center"><span class="Estilo1">Vencimiento</span></div></th>
        <th width="25%"><div align="center"><span class="Estilo1">Glosa</span></div></th>
        <th width="25%" colspan="2"><div align="center"><span class="Estilo1">Opciones</span></div></th>
      </tr>
    </thead>
    <?php
	if($_POST)
	{
		include("../../../funciones/conexion.php");
		include("../../../funciones/funcion.php");
		if(DEBUG){var_export($_POST);}
		$condicion_banco="";
		
		$opcion_cheque=$_POST["opcion_cheque"];
		switch($opcion_cheque)
		{
			case"recepcion":
				$campo_filtro="fecha";//de ingreso al sistema
				$msj_tipob="por ingreso al sistema";
				break;
			
			case"vencimiento":
				$campo_filtro="fecha_vencimiento";
				$msj_tipob="por vencimiento";
				break;
		}
		
		
		$fecha_ini=$_POST["fecha_inicio"];
		$fecha_fin=$_POST["fecha_fin"];
		$cheque_banco=$_POST["cheque_banco"];
		$sede=$_POST["fsede"];
		if($cheque_banco!="Todos")
		{
			$condicion_banco=" AND banco='$cheque_banco'";
		}
		$cons_cheque="SELECT * FROM registro_cheques WHERE sede='$sede' $condicion_banco AND $campo_filtro BETWEEN '$fecha_ini' AND '$fecha_fin' ORDER By fecha";
		
		if(DEBUG)
		{echo"<br>---> $cons_cheque<br>";}
		
		$sql=mysql_query($cons_cheque)or die(mysql_error());
		$cheques_encontrados=mysql_num_rows($sql);
			$suma_valor=0;
		if($cheques_encontrados>0)
		{
			$contador=0;
			while($CH=mysql_fetch_assoc($sql))
			{
				$contador++;
				$id_cheque=$CH["id"];
				$id_alumno=$CH["id_alumno"];
				$numero=$CH["numero"];
				$fecha_vencimiento=$CH["fecha_vencimiento"];
				$banco=$CH["banco"];
				$valor=$CH["valor"];
				$condicion=$CH["condicion"];
				$fecha_condicion=$CH["fecha_condicion"];
				$sede=$CH["sede"];
				$fecha=$CH["fecha"];
				$glosa=$CH["glosa"];
				
				$suma_valor+=$valor;
				echo'<tr align="center">
					<td><span class="Estilo1"><input name="id_cheque[]" id="id_cheque[]" type="checkbox" value="'.$id_cheque.'" /></span></td>
					<td><span class="Estilo1">'.$contador.'</span></td>
					<td><span class="Estilo1">'.$id_cheque.'</span></td>
					<td><span class="Estilo1">'.$id_alumno.'</span></td>
					<td><span class="Estilo1">'.$numero.'</span></td>
					<td><span class="Estilo1">'.fecha_format($fecha).'</span></td>
					<td><span class="Estilo1">'.$banco.'</span></td>
					<td><span class="Estilo1">$'.number_format($valor,0,",",".").'</span></td>
					<td><span class="Estilo1">'.$condicion.'<br>('.fecha_format($fecha_condicion).')</span></td>
					<td><span class="Estilo1">'.fecha_format($fecha_vencimiento).'</span></td>
					<td><span class="Estilo1"><em>'.$glosa.'</em></span></td>
					<td><a href="editar_cheque/editar_cheque_1.php?id_cheque='.$id_cheque.'&lightbox[iframe]=true&lightbox[width]=450&lightbox[height]=400" class="lightbox">Editar</a></td>
					<td><a href="elimina_cheque/elimina_cheque.php?id_cheque='.$id_cheque.'&lightbox[iframe]=true&lightbox[width]=450&lightbox[height]=400" class="lightbox">Eliminar</a></td>
					</tr>';
			}
		}
		else
		{
			echo'<tr>
				<td colspan="13"><span class="Estilo1">Sin Cheques Encontrados, segun los parametros de Busqueda</span></td>
				</tr>';
		}
	}
?>
    <tfoot>
      <tr>
        <td colspan="13"><span class="Estilo5"><?php echo $cheques_encontrados;?> Cheque Encontrado(s); Suma(n) en Total:$ <?php echo number_format($suma_valor,0,",",".");?></span></td>
      </tr>
    </tfoot>
    
  </table>
  </form>
  <div id="opciones">Para los Cheques Marcados: <a href="#" onclick="CONFIRMAR();">Cambiar Condici&oacute;n</a></div>
</div>
<div id="apDiv2">
  <table width="124%" border="0">
  <thead>
    <tr>
      <th colspan="2"><span class="Estilo3">Parametros</span></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td class="Estilo3">Tipo Busqueda</td>
      <td class="Estilo5"><?php echo $msj_tipob;?></td>
    </tr>
    <tr>
      <td width="39%"><span class="Estilo3">Fecha inicio</span></td>
      <td width="61%"><span class="Estilo5"><?php echo fecha_format($fecha_ini);?></span></td>
    </tr>
    <tr>
      <td><span class="Estilo3">Fecha Fin</span></td>
      <td><span class="Estilo5"><?php echo fecha_format($fecha_fin);?></span></td>
    </tr>
    <tr>
      <td><span class="Estilo3">Banco</span></td>
      <td><span class="Estilo5"><?php echo $cheque_banco;?></span></td>
    </tr>
    <tr>
      <td class="Estilo3">Sede</td>
      <td class="Estilo5"><?php echo $sede;?></td>
    </tr>
    </tbody>
  </table>
</div>
</body>
</html>