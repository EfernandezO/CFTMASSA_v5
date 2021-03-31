<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("libro_ventas_X_detalle_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(!$_POST)
{header("location: index.php");}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<title>Libro de Venta</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:68px;
	z-index:1;
	left: 5%;
	top: 244px;
}
.Estilo1 {font-size: 12px}
#apDiv2 {
	position:absolute;
	width:50%;
	height:115px;
	z-index:2;
	left: 5%;
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
<!--INICIO LIGHTBOX EVOLUTION-->
  <script type="text/javascript" src="../../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
 <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox(); 
    });
  </script>
 <!--FIN LIGHTBOX EVOLUTION--> 
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
          <th width="8%"><span class="Estilo1">ID Alumno</span></th>
          <th width="8%">Rut</th>
          <th width="16%">Nombre</th>
          <th width="8%">Carrera</th>
          <th width="8%">promocion/a&ntilde;o</th>
          <th width="10%"><span class="Estilo1">Valor</span></th>
          <th width="11%"><span class="Estilo1">Glosa</span></th>
          <th width="12%"><span class="Estilo1">Fecha</span></th>
          <th width="11%"><span class="Estilo1">Folio</span></th>
          <th width="12%"><span class="Estilo1">Condicion</span></th>
          <th width="15%"><span class="Estilo1">ID Usuario</span></th>
      </tr>
    </thead>
<?php
	require("../../../../funciones/conexion_v2.php");
	include("../../../../funciones/funcion.php");
	include("../../../../funciones/funciones_sistema.php");
	
	$fecha_ini=$_POST["fecha_inicio"];
	$fecha_fin=$_POST["fecha_fin"];
	$sede=$_POST["fsede"];
	
	//--------------------------------------------------------//
	include("../../../../funciones/VX.php");
	$evento="Revisa Libro Ventas X detalle Periodo [$fecha_ini - $fecha_fin]";
	REGISTRA_EVENTO($evento);
	//----------------------------------------------------------//
	
	$titulo_xls="BOLETAS de $sede Periodo ($fecha_ini y $fecha_fin)";
	$cons_boleta="SELECT * FROM boleta WHERE sede='$sede' AND fecha BETWEEN '$fecha_ini' AND '$fecha_fin' ORDER by folio";
	//echo"=> $cons_boleta<br>";
	
	$sql_boleta=$conexion_mysqli->query($cons_boleta)or die($conexion_mysqli->error);
	$cantidad_boletas=$sql_boleta->num_rows;
	$suma_boleta=0;
	$cantidad_anulada=0;
	$suma_anulada=0;
	if($cantidad_boletas>0)
	{
		while($B=$sql_boleta->fetch_assoc())
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
			
			//--------------------------------------/
			//alumno
			$cons_A="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";
			$sqli_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
				$A=$sqli_A->fetch_assoc();
				$A_rut=$A["rut"];
				$A_nombre=$A["nombre"];
				$A_apellido_P=$A["apellido_P"];
				$A_apellido_M=$A["apellido_M"];
				$A_carrera=$A["carrera"];
				$A_id_carrera=$A["id_carrera"];
				$A_ingreso=$A["ingreso"];
				$A_nivel=$A["nivel"];
			$sqli_A->free();	
			//-----------------------------------//
			//personal
			////////////////////
			$usuario_nombre=NOMBRE_PERSONAL($cod_user);
			//////////////////////
			if($estado=="OK")
			{$suma_boleta+=$valor;}
			if($estado=="ANULADA")
			{
				$cantidad_anulada++;
				$suma_anulada+=$valor;
			}	
			echo'<tr class="odd">
				<td><span class="Estilo5">'.$id_boleta.'</span></td>
				<td><span class="Estilo5">'.$id_alumno.'</span></td>
				<td>'.$A_rut.'</td>
				<td>'.$A_nombre.' '.$A_apellido_P.' '.$A_apellido_M.'</td>
				<td><a href="#" title="'.$A_carrera.'">'.$A_id_carrera.'</a></td>
				<td>'.$A_nivel.' - '.$A_ingreso.'</td>
				<td><span class="Estilo5">$'.number_format($valor,0,",",".").'</span></td>
				<td>'.$glosa.'</td>
				<td><span class="Estilo5">'.fecha_format($fecha).'</span></td>
				<td><span class="Estilo5"><a href="modificar_boleta/modifica_boleta_1.php?id_boleta='.base64_encode($id_boleta).'&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=500" class="lightbox" title="Modifica Folio">'.$folio.'</a></span></td>
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
	$sql_boleta->free();
	@mysql_close($conexion);
	$conexion_mysqli->close();
?>
<tfoot>
<tr>
	<td colspan="12"><span class="Estilo1"><?php echo $cantidad_boletas-$cantidad_anulada?> Boletas Registradas, por un Total de $<?php echo number_format($suma_boleta,0,",",".");?></span></td>
</tr>
</tfoot>
</table>
</div>
<div id="apDiv2">
  <table width="100%" border="0">
  <thead>
    <tr>
      <th colspan="2"><div align="left"><span class="Estilo3">Parametros</span></div></th>
    </tr>
    </thead>
    <tbody>
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
    </tbody>
  </table>
</div>
</body>
</html>