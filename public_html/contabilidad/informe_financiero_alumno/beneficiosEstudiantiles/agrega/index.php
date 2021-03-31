<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Impresion_Y_Gestion_de_contratos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("beneficiosServer.php");
$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"ASIGNAR_BENEFICIO");
$xajax->register(XAJAX_FUNCTION,"QUITAR_BENEFICIO");
$xajax->register(XAJAX_FUNCTION,"ACTUALIZAR_TABLA_BENEFICIOS");
$xajax->register(XAJAX_FUNCTION,"ACTUALIZAR_BENEFICIO");
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php $xajax->printJavascript(); ?> 
<title>Agrega Beneficio</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">

<script src="../../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/steel/steel.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css"/>

<style>
#link {
	text-align: right;
	padding-right: 10px;
}
#apDiv2 {
	position:absolute;
	width:50%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 123px;
}
#div_beneficiosEstudiantilesAsignados {
	position:absolute;
	width:40%;
	height:115px;
	z-index:2;
	left: 55%;
	top: 125px;
}
#apDiv1 {
	position:absolute;
	width:90%;
	height:44px;
	z-index:3;
	left: 5%;
	top: 309px;
}
-->
</style>
<script language="javascript">
function CONFIRMAR()
{
	continuar=true;
	fecha_vence=document.getElementById('fecha_vence').value;
	valor=document.getElementById('valor_cuota').value;
	deuda=document.getElementById('deudaXcuota').value;
	
	if(fecha_vence=="")
	{
		continuar=false;
		alert('Ingrese Fecha de Vencimiento');
	}
	
	if(valor=="")
	{
		continuar=false;
		alert('Ingrese Valor Cuota');
	}
	if(deuda=="")
	{
		continuar=false;
		alert('ingrese Deuda Cuota');
	}
	
	if(continuar)
	{
		c=confirm("¿Seguro(a) Desea Agregar esta Cuota ?");
		if(c)
		{
			document.frm.submit();
		}
	}	
}
</script>
</head>
<?php
	if(isset($_SESSION["FINANZASX"])){unset($_SESSION["FINANZASX"]);}
	require("../../../../../funciones/funciones_sistema.php");
	 require("../../../../../funciones/conexion_v2.php");
	$sedeAlumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	$id_alumno=base64_decode($_GET["id_alumno"]);
	$id_contrato=base64_decode($_GET["id_contrato"]);
	$year=base64_decode($_GET["year"]);
	$semestre=$_GET["semestre"];
	
	$array_tipo=array("cuota", "matricula");
	$array_condicion=array("N"=>"pendiente", "S"=>"pagada", "A"=>"abonada");
	
	$consA="SELECT arancel FROM contratos2 WHERE id='$id_contrato' LIMIT 1";
	$sqliA=$conexion_mysqli->query($consA);
	$DC=$sqliA->fetch_assoc();
		$arancel=$DC["arancel"];
	$sqliA->free();	
	
	$cons="SELECT id_beneficio, valor FROM beneficiosEstudiantiles_asignaciones WHERE id_contrato='$id_contrato' AND id_alumno='$id_alumno'";
	$sqliBE=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$num_beneficios=$sqliBE->num_rows;
	if(DEBUG){ echo"$cons<br>num: $num_beneficios<br>";}
	$hayBeneficioPrevios=false;
	if($num_beneficios>0){
		$hayBeneficioPrevios=true;
		while($BE=$sqliBE->fetch_assoc()){
			$auxIdBeneficio=$BE["id_beneficio"];
			$auxValor=number_format($BE["valor"],0,"","");
			
			  $cons_BEX="SELECT * FROM beneficiosEstudiantiles WHERE id='$auxIdBeneficio' LIMIT 1";
			  $sqli_BEX=$conexion_mysqli->query($cons_BEX);
			  $DBE=$sqli_BEX->fetch_assoc();
				$_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$auxIdBeneficio]["nombre"]=$DBE["beca_nombre"];
				$_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$auxIdBeneficio]["tipo"]=$DBE["beca_tipo_aporte"];
				$_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$auxIdBeneficio]["forma"]=$DBE["formaAporte"];
				$_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$auxIdBeneficio]["aporteValor"]=$auxValor;
				$_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$auxIdBeneficio]["aportePorcentaje"]=$DBE["beca_aporte_porcentaje"];
				$_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$auxIdBeneficio]["familiaBeneficio"]=$DBE["familiaBeneficio"];
				$_SESSION["FINANZASX"]["beneficiosEstudiantiles"][$auxIdBeneficio]["duracion"]=$DBE["duracion"];
			}
	}
	$sqliBE->free();
?>
<body onload="xajax_ACTUALIZAR_TABLA_BENEFICIOS(<?php echo $arancel;?>);">
<h1 id="banner">Agregar - Beneficios Estudiantiles</h1>
<div id="link"><br />
<a href="../../index.php" class="button">Volver</a></div>
<h3>Agregando Beneficio a Alumno...</h3>
<div id="apDiv2">
  <form action="agrega_rec.php" method="post" name="frm" id="frm">
  <table width="70%" border="0" align="left">
  <thead>
    <tr>
      <th colspan="2"><strong>Agrega 
          <input name="id_alumno" type="hidden" id="id_alumno" value="<?php echo $id_alumno;?>" />
        <input type="hidden" name="id_contrato" id="id_contrato"  value="<?php echo $id_contrato;?>"/>
        <input name="year" type="hidden" id="year" value="<?php echo $year;?>" />
        <input type="hidden" name="semestre" id="semestre"  value="<?php echo $semestre;?>"/>
        <input name="arancel" type="hidden" id="arancel" value="<?php echo $arancel;?>" />
      </strong></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="25%"><em>Beneficio Estudiantil</em></td>
      <td width="75%"><select name="beneficiosDisponibles" id="beneficiosDisponibles">
              <?php
	 
      	$cons_BE="SELECT * FROM beneficiosEstudiantiles WHERE beca_condicion='activa' ORDER by beca_nombre";
		$sqli_BE=$conexion_mysqli->query($cons_BE);
		$numBeneficios=$sqli_BE->num_rows;
		if($numBeneficios>0){
			while($DBE=$sqli_BE->fetch_assoc()){
				$BE_id=$DBE["id"];
				$BE_nombre=$DBE["beca_nombre"];
				$BE_tipoAporte=$DBE["beca_tipo_aporte"];
				$BE_aporteValor=$DBE["beca_aporte_valor"];
				$BE_aportePorcentaje=$DBE["beca_aporte_porcentaje"];
				
				echo'<option value="'.$BE_id.'">'.$BE_nombre.'</option>';
			}
		}
		$sqli_BE->free();
		$conexion_mysqli->close();
	  ?>
              </select>
        <input name="validador" type="hidden" id="validador" value="<?php echo md5("AGREGA_cuota".date("Y-m-d"));?>" />
        <a href="#" class="button_R" onclick="xajax_ASIGNAR_BENEFICIO(document.getElementById('beneficiosDisponibles').value, <?php echo $arancel;?>);"> Asignar</a></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="div_beneficiosEstudiantilesAsignados"></div>
<div id="apDiv1"><a href="#" class="button_G" onclick="xajax_VERIFICAR(<?php echo $id_alumno;?>, <?php echo $arancel;?>);">Grabar</a></div>
<script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton", "fecha_vence", "%Y-%m-%d");
    //]]></script>
</body>
</html>
