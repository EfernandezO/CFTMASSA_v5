<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Proceso_Retiro_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
						   
require("../../../funciones/conexion_v2.php");
require("../../../funciones/funciones_sistema.php");			   

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php"); ?>
<title>Proceso de Retiro</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 62px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:22px;
	z-index:2;
	left: 5%;
	top: 283px;
}
#apDiv2 {
	border: medium solid #39C;
}
#apDiv3 {
	position:absolute;
	width:40%;
	height:28px;
	z-index:3;
	left: 30%;
	top: 521px;
	text-align: center;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	c=confirm('¿Desea Continuar con el Retiro del alumno...?');
	 if(c){document.getElementById('frm').submit();}
}
</script>
<?php
if($_POST)
{
	$action="proceso_retiro_3.php";
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$yearIngresoCarrera=$_SESSION["SELECTOR_ALUMNO"]["yearIngresoCarrera"];
	
	$semestre=$_POST["semestre"];
	$year=$_POST["year"];
	$retiro_id=$_POST["retiro_id"];
	$retiro_motivo=$_POST["retiro_motivo"];
	$retiro_descripcion=$_POST["retiro_descripcion"];
	$retiro_presenta_carta=$_POST["retiro_presenta_carta"];
	$retiro_posible_reincorporacion=$_POST["retiro_posible_reincorporacion"];
		

	$cons_C="SELECT MAX(id) FROM contratos2 WHERE ano='$year' AND condicion='ok' AND id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera' LIMIT 1";
	$sqli_C=$conexion_mysqli->query($cons_C)or die($conexion_mysqli->error);
	$DC=$sqli_C->fetch_row();
		$id_contrato=$DC[0];
		if(empty($id_contrato)){$id_contrato=0;}
	$sqli_C->free();
	
	$ARRAY_CUOTAS=array();
	if($id_contrato>0)
	{
		$CONS_l="SELECT * FROM letras WHERE id_contrato='$id_contrato' ORDER by fechavenc";
		$sqli_1=$conexion_mysqli->query($CONS_l)or die($conexion_mysqli->error);
		$num_cuotas=$sqli_1->num_rows;
		
		if($num_cuotas>0)
		{
			$aux=0;
			while($C=$sqli_1->fetch_assoc())
			{
				$ARRAY_CUOTAS[$aux]["id_cuota"]=$C["id"];
				$ARRAY_CUOTAS[$aux]["valor"]=$C["valor"];
				$ARRAY_CUOTAS[$aux]["deuda"]=$C["deudaXletra"];
				$aux++;
			}
		}
		
		$sqli_1->free();
	}
	$conexion_mysqli->close();
}
?>
</head>

<body>
<h1 id="banner">Administrador - Proceso Retiro</h1>
<div id="apDiv1">
  <form action="<?php echo $action;?>" method="post" id="frm">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="3">Contrato</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="107">Contrato (id)</td>
      <td width="935" colspan="2">
	  <?php echo $id_contrato;?>
      <input name="id_contrato" type="hidden" value="<?php echo $id_contrato;?>" />
      <input name="semestre" type="hidden" value="<?php echo $semestre;?>" />
      <input name="year" type="hidden" value="<?php echo $year;?>" />
      <input name="retiro_id" type="hidden" value="<?php echo $retiro_id;?>" />
      <input name="retiro_motivo" type="hidden" value="<?php echo $retiro_motivo;?>" />
      <input name="retiro_descripcion" type="hidden" value="<?php echo $retiro_descripcion;?>" />
      <input name="retiro_presenta_carta" type="hidden" value="<?php echo $retiro_presenta_carta;?>" />
      <input name="retiro_posible_reincorporacion" type="hidden" value="<?php echo $retiro_posible_reincorporacion;?>" />
      
      </td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    </tbody>
  </table>
  
   <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="4">Cuotas Retiro</th>
    </tr>
    </thead>
    <tbody>
    <tr>
    <td>-</td>
    <td>N.</td>
    <td>Valor</td>
    <td>Deuda</td>
    </tr>
    <?php
    if($num_cuotas>0)
	{
		$contador=0;
		foreach($ARRAY_CUOTAS as $n =>$aux_array)
		{
			$aux_id_cuota=$aux_array["id_cuota"];
			$aux_valor=$aux_array["valor"];
			$aux_deuda=$aux_array["deuda"];
			$contador++;
			
			if($aux_deuda==0){ $campo='';}//sin deuda no permite borrar
			elseif($aux_deuda==$aux_valor){  $campo='<input name="ELIMINAR_CUOTA['.$aux_id_cuota.']" checked="checked" type="checkbox" value="true" />';}//sin pagos se puede eliminar
			else{$campo='';}//abonada 
			
			echo'<tr>
					<td>'.$campo.'</td>
					<td>'.$contador.'</td>
					<td>'.$aux_valor.'</td>
					<td>'.$aux_deuda.'</td>
				 </tr>';
		}
	}
	else
	{ echo'<tr><td colspan="4">Sin Cuotas Asociadas al Contrato</td></tr>';}
	?>
    </tbody>
    </table>
  </form>
</div>
<div id="apDiv3"><a href="#" class="button_G" onclick="CONFIRMAR();">Retirar este Alumno</a></div>
</body>
</html>