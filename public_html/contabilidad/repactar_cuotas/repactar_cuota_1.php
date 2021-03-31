<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Repactar_cuotas_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//--------------//	
$_SESSION["REPACTAR"]["verificador"]=true;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<title>Repactar Cuotas</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:1;
	left: 461px;
	top: 103px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:43px;
	z-index:1;
	left: 5%;
	top: 254px;
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
#apDiv3 {
	position:absolute;
	width:40%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 74px;
}
#apDiv4 {
	position:absolute;
	width:45%;
	height:31px;
	z-index:3;
	left: 50%;
	top: 204px;
}
-->
</style>
</head>

<body>
<h1 id="banner">Administrador - Repactar cuotas</h1>
<div id="link"><br />
<a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver al Menu</a></div>
<div id="apDiv2">
  <table width="80%" border="1" align="left">
  <caption>
  Seleccione el Contrato a Re-pactar
  </caption>
  <thead>
  <tr>
    <th>N&deg;</th>
    <th>COD.</th>
    <th>N Cuotas</th>
    <th>Valor Cuota</th>
    <th>Total Cuotas</th>
    <th>Total Ya Cancelado</th>
    <th>Vigencia</th>
    <th>Opcion</th>
  </tr>
  </thead>
<tbody>
<?php
$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
$carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
$id_carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
$nivel_alumno=$_SESSION["SELECTOR_ALUMNO"]["nivel"];

$semestres_dura_carrera=5;////

require("../../../funciones/conexion_v2.php");

	///////////semestres ranscurridos
	$cons="SELECT * FROM contratos2 WHERE id_alumno='$id_alumno' AND condicion='ok' AND NOT(reasignado='si')";
	if(DEBUG){ echo"--> $cons<br>";}
	$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_reg=$sql->num_rows;
	if($num_reg>0)
	{
		$aux=0;
		$_SESSION["REASIGNAR"]["verificador"]=true;
		while($C=$sql->fetch_assoc())
		{
			$aux++;
			$id_contrato=$C["id"];
			$arancel=$C["arancel"];
			$vigencia=$C["vigencia"];
			$linea_credito_paga=$C["linea_credito_paga"];
			
			//total ya pagado//////////////////////////////////
			$cons_yc="SELECT valor, deudaXletra FROM letras WHERE idalumn='$id_alumno' AND id_contrato='$id_contrato' AND tipo='cuota'";
			if(DEBUG){ echo"---> $cons_yc<br>";}
			$sql_yc=$conexion_mysqli->query($cons_yc)or die($conexion_mysqli->error);
			$num_cuotas=$sql_yc->num_rows;
			
			$total_valor_cuota=0;
			$total_deuda_cuota=0;
			$numero_cuotas=0;
			$total_ya_cancelado=0;
			if($num_cuotas>0)
			{
				
				while($M=$sql_yc->fetch_assoc())
				{
					$numero_cuotas++;
					$valor_cuota=$M["valor"];
					$deudaXcuota=$M["deudaXletra"];
					
					$total_valor_cuota+=$valor_cuota;
					$total_deuda_cuota+=$deudaXcuota;
					
					$pagado_X_cuota=($valor_cuota-$deudaXcuota);
					if(DEBUG){ echo"--->$pagado_X_cuota<br>";}
					$total_ya_cancelado+=$pagado_X_cuota;
				}	
			}
			$sql_yc->free();
			
			////////////////////////////////////////////////////////
			echo'<tr>
			<td align="center">'.$aux.'</td>
			<td align="center">'.$id_contrato.'</td>
			<td align="center">'.$numero_cuotas.'</td>
			<td align="center">'.number_format($valor_cuota,0,",",".").'</td>
			<td align="center">'.number_format($total_valor_cuota,0,",",".").'</td>
			<td align="center">'.number_format($total_ya_cancelado,0,",",".").'</td>
			<td align="center">'.$vigencia.'</td>
			<td align="center">';
			
			$url_2='repactar_cuota_2.php?ID='.base64_encode($id_contrato);
			
			echo'<a href="'.$url_2.'">Seleccionar</a></td>
			</tr>';
		}
	}
	else
	{ echo'<tr><td colspan="6">Sin Contratos Generados, o contrato ya reasignado</td></tr>';}
@mysql_close($conexion);
$conexion_mysqli->close();
////////////////////////
?>
</tbody>
</table></div>
<div id="apDiv3">
  <table width="100%" border="1">
  	<thead>
    <tr>
      <th colspan="2">Alumno:<?php echo $_SESSION["SELECTOR_ALUMNO"]["id"];?></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>Nombre</td>
      <td><?php echo $_SESSION["SELECTOR_ALUMNO"]["nombre"];?></td>
    </tr>
    <tr>
      <td>Apellido</td>
      <td><?php echo $_SESSION["SELECTOR_ALUMNO"]["apellido"];?></td>
    </tr>
    <tr>
      <td>Nivel</td>
      <td><?php echo $_SESSION["SELECTOR_ALUMNO"]["nivel"];?></td>
    </tr>
    <tr>
      <td>Carrera</td>
      <td><?php echo $_SESSION["SELECTOR_ALUMNO"]["carrera"];?></td>
    </tr>
    </tbody>
  </table>
</div>
<div id="apDiv4"></div>
</body>
</html>