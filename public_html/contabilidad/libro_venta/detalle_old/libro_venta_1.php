<?php require ("../../../SC/seguridad.php");?>
<?php require ("../../../SC/privilegio2.php");?>
<?php
if(!$_POST)
{
	header("location: index.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-3" />
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla.css">
<title>Libro de Venta</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:720px;
	height:68px;
	z-index:1;
	left: 25px;
	top: 229px;
}
.Estilo1 {font-size: 12px}
#apDiv2 {
	position:absolute;
	width:442px;
	height:115px;
	z-index:2;
	left: 25px;
	top: 69px;
}
.Estilo3 {font-size: 12px; font-weight: bold; }
.Estilo5 {font-size: 12px; font-style: italic; }
#link {
	text-align: right;
	padding-right: 10px;
}
-->
</style>
<script language="javascript">
function exportar_excel(valor, titulo)
{
	//alert(valor);
	window.location="genera_excel.php?codigo="+valor+"&titulo="+titulo;
}
</script>
</head>
<body>
<h1 id="banner">Administrador - Finanzas Libro de Venta</h1>

<div id="link"><br />
<a href="index.php" class="button">Volver a Seleccion</a></div>
<div id="apDiv1">
<table width="104%" border="1">
<thead>
        <tr>
            <th width="13%"><span class="Estilo1">ID Boleta</span></th>
          <th width="16%"><span class="Estilo1">ID Alumno</span></th>
          <th width="10%"><span class="Estilo1">Valor</span></th>
          <th width="11%"><span class="Estilo1">Glosa</span></th>
          <th width="12%"><span class="Estilo1">Fecha</span></th>
          <th width="11%"><span class="Estilo1">Folio</span></th>
          <th width="12%"><span class="Estilo1">Condicion</span></th>
          <th width="15%"><span class="Estilo1">ID Usuario</span></th>
      </tr>
    </thead>
<?php
	include("../../../../funciones/conexion.php");
	include("../../../../funciones/funcion.php");
	$fecha_ini=$_POST["fecha_inicio"];
	$fecha_fin=$_POST["fecha_fin"];
	$sede=$_POST["fsede"];
	
	$titulo_xls="BOLETAS de $sede Periodo ($fecha_ini y $fecha_fin)";
	$cons_boleta="SELECT * FROM boleta WHERE sede='$sede' AND fecha BETWEEN '$fecha_ini' AND '$fecha_fin' ORDER by fecha, folio";
	//echo"=> $cons_boleta<br>";
	
	$sql_boleta=mysql_query($cons_boleta)or die("Boleta.: ".mysql_error());
	$cantidad_boletas=mysql_num_rows($sql_boleta);
	$suma_boleta=0;
	$cantidad_anulada=0;
	if($cantidad_boletas>0)
	{
		while($B=mysql_fetch_assoc($sql_boleta))
		{
			$id_boleta=$B["id"];
			$id_alumno=$B["id_alumno"];
			$valor=$B["valor"];
			$glosa=$B["glosa"];
			$glosa=str_replace("[br]"," ",$glosa);
			$fecha=$B["fecha"];
			$folio=$B["folio"];
			$sede=$B["sede"];
			$estado=$B["estado"];
			$cod_user=$B["cod_user"];
			
			////////////////////
			$cons_user="SELECT nombre, apellido FROM personal WHERE id ='$cod_user'";
			$sql_user=mysql_query($cons_user) or die(mysql_error());
			$DU=mysql_fetch_assoc($sql_user);
			$nombre=$DU["nombre"];
			$apellido=$DU["apellido"];
			$usuario_nombre=$nombre." ".$apellido;
			mysql_free_result($sql_user);
			//////////////////////
			if($estado=="OK")
			{
				$suma_boleta+=$valor;
			}
			if($estado=="ANULADA")
			{
				$cantidad_anulada++;
				$suma_anulada+=$valor;
			}	
			echo'<tr class="odd">
				<td><span class="Estilo5">'.$id_boleta.'</span></td>
				<td><span class="Estilo5">'.$id_alumno.'</span></td>
				<td><span class="Estilo5">$'.number_format($valor,0,",",".").'</span></td>
				<td><textarea name="glosa" cols="17" rows="3">'.$glosa.'</textarea></td>
				<td><span class="Estilo5">'.fecha_format($fecha).'</span></td>
				<td><span class="Estilo5">'.$folio.'</span></td>
				<td><span class="Estilo5">'.$estado.'</span></td>
				<td><span class="Estilo5"><a href="#" title="'.$usuario_nombre.'">'.$cod_user.'</a></span></td>
				 </tr>';
			
		}
	}
	else
	{
		echo'<tr align="center">
			<td colspan="8"><span class="Estilo5">No hay Boletas Registradas en este Periodo</span></td>
			</tr>';
	}
	mysql_free_result($sql_boleta);
	mysql_close($conexion);
?>
<tfoot>
<tr>
	<td colspan="8"><span class="Estilo1"><?php echo $cantidad_boletas-$cantidad_anulada?> Boletas Registradas, por un Total de $<?php echo number_format($suma_boleta,0,",",".");?> <br><?php echo $cantidad_anulada;?> Anuladas, por un total de $<?php echo number_format($suma_anulada,0,",",".");?></span></td>
</tr>
</tfoot>
</table>
</div>
<div id="apDiv2">
  <table width="100%" border="0">
    <tr>
      <th colspan="2" bgcolor="#e5e5e5"><div align="left"><span class="Estilo3">Parametros</span></div></th>
    </tr>
    <tr>
      <td width="23%"><span class="Estilo5">Fecha Inicio</span></td>
      <td width="77%"><span class="Estilo5"><?php echo $fecha_ini;?></span></td>
    </tr>
    <tr>
      <td><span class="Estilo5">Fecha Fin</span></td>
      <td><span class="Estilo5"><?php echo $fecha_fin;?></span></td>
    </tr>
    <tr>
      <td><span class="Estilo5">Sede</span></td>
      <td><span class="Estilo5"><?php echo $sede;?></span></td>
    </tr>
    <tr>
      <td><span class="Estilo1">Exportar</span></td>
      <td><input name="imageField" type="image" src="../../../BAses/Images/excel_icon.png" alt="exportar"  value="<?php echo base64_encode($cons_boleta);?>" onclick="exportar_excel(this.value, '<?php echo base64_encode($titulo_xls);?>')" title="Exportar a Excel"/></td>
    </tr>
  </table>
</div>
</body>
</html>