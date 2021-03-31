<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Genera_honorario_1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(DEBUG){ var_dump($_POST);}

if($_POST)
{
	$id_funcionario=$_POST["id_funcionario"];
	$indice=$_POST["indice"];
	$tipo=$_POST["tipo"];
	
	$valor=$_POST["valor"];
	$valor=str_replace(",",".",$valor);
	$glosa=$_POST["glosa"];
	
	if((is_numeric($valor))and($valor>=0))
	{$continuar_1=true;}
	else{$continuar_1=false;}
	
	//------------------------------------------------------//
	
	switch($tipo)
	{
		case"cargos":
			$_SESSION["HONORARIO"][$id_funcionario]["asignaciones"][$indice]["cargo"]=$valor;
			if($valor>0)
			{$_SESSION["HONORARIO"][$id_funcionario]["asignaciones"][$indice]["glosa_cargo"]=$glosa;}
			else{$_SESSION["HONORARIO"][$id_funcionario]["asignaciones"][$indice]["glosa_cargo"]="";}
			break;
		case"abonos":
			$_SESSION["HONORARIO"][$id_funcionario]["asignaciones"][$indice]["abono"]=$valor;
			if($valor>0)
			{$_SESSION["HONORARIO"][$id_funcionario]["asignaciones"][$indice]["glosa_abono"]=$glosa;}
			else{$_SESSION["HONORARIO"][$id_funcionario]["asignaciones"][$indice]["glosa_abono"]="";}
			break;
	}
	//---------------------------------------------------------------------------------------//
	$total_a_pagar=0;
		foreach($_SESSION["HONORARIO"][$id_funcionario]["asignaciones"] as $x => $aux_array)
		{
			$aux_condicion=$aux_array["condicion"];
			$aux_total_base=$aux_array["total_base"];
			$aux_cargo=$aux_array["cargo"];
			$aux_abono=$aux_array["abono"];
			$aux_horas_mensuales=$aux_array["horas_mensuales"];
			$aux_valor_hora=$aux_array["valor_hora"];
			
			$total_base=($aux_horas_mensuales*$aux_valor_hora);
			$total_cargo=($aux_cargo*$aux_valor_hora);
			$total_abono=($aux_abono*$aux_valor_hora);
			
			$aux_total_asignatura=($total_base-$total_cargo)+$total_abono;
			
			if($aux_condicion=="on")
			{ $total_a_pagar+=$aux_total_asignatura;}
		}
		
		if(DEBUG){ echo"TOTAL ASIGNACIONES: $total_a_pagar<br>";}
		
		$_SESSION["HONORARIO"][$id_funcionario]["total_a_pagar"]=$total_a_pagar;
	
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Abonos cargos</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<!--CIERRA LIGHT BOX :D-->
<script language="javascript">
function BATIR()
{
	window.parent.jQuery.lightbox().shake();
	setTimeout("CERRAR()",1500);
}
function CERRAR()
{
	//alert("Se va a cerrar SexyLightbox");
	// Función necesaria para cerrar la ventana modal
	//window.parent.lightbox.close();
	
	window.parent.jQuery.lightbox().close();
	// Función necesaria para actualizar la ventana padre
	window.parent.document.location.reload();
}
<?php if(!DEBUG){?>setTimeout("BATIR()",500);<?php }?>
</script>
<!--FIN CIERRE-->
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 78px;
}
</style>
</head>

<body>
<h1 id="banner">Honorarios - Cargos Abonos V 2.0</h1>
<div id="apDiv1">
  <table width="50%" border="1" align="center">
  <thead>
    <tr>
      <th>Informacion</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td height="81" align="center">Valores Aplicados<br />        <img src="../../../BAses/Images/ok.png" width="29" height="26" alt="ok" /><br /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    </tbody>
  </table>
</div>
</body>
</html>