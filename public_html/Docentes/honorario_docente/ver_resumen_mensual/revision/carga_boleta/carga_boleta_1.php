<?php
//--------------CLASS_okalis------------------//
	require("../../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../../funciones/";
	$O->clave_del_archivo=md5("pago_honorario_docente_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if((isset($_GET["H_id"]))and(isset($_GET["PH_id"])))
{
	$id_honorario=base64_decode($_GET["H_id"]);
	$PH_id=base64_decode($_GET["PH_id"]);
	
	
	if(is_numeric($id_honorario)){ $continuar=true;}
	else{$continuar=false;}
	
	if(is_numeric($PH_id)){ $continuar_2=true;}
	else{$continuar_2=false;}
	
}
else
{$continuar=false; $continuar_2=false;}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../../funciones/codificacion.php");?>
<title>Carga Boleta Honorario Docente</title>
<link rel="stylesheet" type="text/css" href="../../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../../CSS/tabla_2.css">
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
	top: 275px;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	continuar=false;
	archivo=document.getElementById('archivo').value;
	
	if((archivo=="")||(archivo==" "))
	{ alert('Primero, Cargue la Boleta para continuar...');}
	else{ continuar=true;}
	
	if(continuar)
	{
		c=confirm('Seguro(a) que desea Cargar este Archivo¿?');
		if(c){document.getElementById('frm').submit();}
	}

}
</script>
</head>

<body>
<h1 id="banner">Administrador - Carga Boleta Honorario</h1>


<?php
if($continuar)
{
	
    require("../../../../../../funciones/conexion_v2.php");
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
	$sqli->free();	
		
	$cons_A="SELECT * FROM personal WHERE id='$H_id_funcionario' LIMIT 1";
	$sql_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
	$DA=$sql_A->fetch_assoc();
		$H_rut=$DA["rut"];
		$H_nombre=$DA["nombre"];
		$H_apellido=$DA["apellido"];
	$sql_A->free();
	
	$action="carga_boleta_2.php";

	?>
<form action="<?php echo $action;?>" method="post" enctype="multipart/form-data" id="frm">
<div id="apDiv1">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="2">Honorario Funcionario
       </th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>Sede</td>
      <td><?php echo $H_sede;?></td>
    </tr>
    <tr>
      <td width="21%">Generado en </td>
      <td width="79%"><?php echo"[Mes: $H_mes - Año: $H_year_generacion]";?></td>
    </tr>
    <tr>
      <td>Rut</td>
      <td><?php echo $H_rut;?></td>
    </tr>
    <tr>
      <td>Nombre</td>
      <td><?php echo "$H_nombre $H_apellido";?></td>
    </tr>
    <tr>
      <td>Total</td>
      <td><?php echo number_format($H_total,0,",",".");?></td>
    </tr>
    </tbody>
  </table>
</div>
<div id="apDiv2">

  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="2"><input name="H_id" type="hidden" id="H_id" value="<?php echo $id_honorario;?>" />
        <input name="PH_id" type="hidden" id="PH_id" value="<?php echo $PH_id;?>" />
       Carga Boleta Honorario</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="50%">Boleta Honorario</td>
      <td width="50%">
        <input type="file" name="archivo" id="archivo" />
        (*.pdf)</td>
    </tr>
    </tbody>
  </table><br />
<a href="#" class="button_R" onclick="CONFIRMAR();"> Seguro(a) Desea Cargar esta Boleta ¿?</a>

</div>
<?php }
else
{ echo"Sin Datos...";}
?>
</form>
</body>
</html>