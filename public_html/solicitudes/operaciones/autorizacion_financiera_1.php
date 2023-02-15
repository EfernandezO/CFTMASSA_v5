<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Solicitud->AutorizacionFinanciera");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
 //////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("autoriza_solicitud_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"VERIFICA_SOLICITUD");
/////////////////////////////
//------------------------------------------//
$continuar=false;
$condicionar_situacion_financiera_alumno=false;
$dias_morosidad_maximo=100;
$ruta_archivo_autorizacion="";
//------------------------------------------//
if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
{
	$_SESSION["PAGOS"]["verificador"]=true;
	
	$js_funcion="continuar=true;
	
	if(continuar)
	{
		c=confirm('Seguro(a) Desea Registrar este Pago..?');
		if(c){document.getElementById('frm').submit();}
	}";
	
	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funciones_sistema.php");
	
	$continuar=true;
	$tipo_receptor="alumno";
	//datos alumno
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$rut=$_SESSION["SELECTOR_ALUMNO"]["rut"];
	$nombre=$_SESSION["SELECTOR_ALUMNO"]["nombre"];
	$apellido=$_SESSION["SELECTOR_ALUMNO"]["apellido"];
	$carrera=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
	$sede=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	$jornada_alumno=$_SESSION["SELECTOR_ALUMNO"]["jornada"];
	$nivel_alumno=$_SESSION["SELECTOR_ALUMNO"]["nivel"];
	$situacion_financiera_real="";
	
	//datos generales
	$array_certificados=array("alumno_regular", "copia_titulo", "certificado_titulo", "concentracion_notas", "concentracion_notas_HRS", "egreso", "plan_curricular");
	$fecha_actual=date("Y-m-d");
	$array_bancos=array("Estado","Chile","Santander","Internacional","Scotiabank","Credito e Inversiones","Corpbanca","Bice","HSBC Bank","Itau","Security","Falabella","Ripley","Consorcio","Penta","Paris","BBVA");
 	sort($array_bancos);
	//valores
	$ARRAY_VALORES["certificado"]=array("alumno_regular"=>3000,
										 "copia_titulo"=>9000,
										 "certificado_titulo"=>23000,
										 "concentracion_notas"=>13000,
										 "concentracion_notas_HRS"=>18000,
										 "egreso"=>13000,
										 "plan_curricular"=>12000);
	
	//datos solicitud
	
	if(isset($_GET["id_solicitud"]))
	{
		$id_solicitud=$_GET["id_solicitud"];
		if((is_numeric($id_solicitud))and($id_solicitud>0))
		{ $hay_solicitud=true;}
		else
		{ $hay_solicitud=false;}
	}
	else{ $hay_solicitud=false;}
	
	if($hay_solicitud)
	{
		$path="../../CONTENEDOR_GLOBAL/solicitudes_comprobantes/";
		
		$cons="SELECT * FROM solicitudes WHERE id='$id_solicitud' LIMIT 1";
		
		$sql=$conexion_mysqli->query($cons);
			$Ds=$sql->fetch_assoc();
				$S_tipo=$Ds["tipo"];
				$S_categoria=$Ds["categoria"];
				$S_valor=$ARRAY_VALORES[$S_tipo][$S_categoria];
				$S_autorizado=$Ds["autorizado"];
				$S_archivo_autorizacion=$Ds["archivo_autorizacion"];
				
				if(empty($S_archivo_autorizacion)){ $hay_archivo_solicitud=false;}
				else{ $hay_archivo_solicitud=true;}
					$ruta_archivo_autorizacion=$path.$S_archivo_autorizacion;
			$sql->free();	
			///solicitud ya autorizada
		if($S_autorizado!=="no")
		{$js_funcion="alert('No se puede Procesar esta Solicitudes, ya ha sido AUTORIZADA...!!!');";}
	//////
	}
	else{$id_solicitud=0; $S_valor="";}
	
	$dias_morosidad=DIAS_MOROSIDAD($id_alumno);
	if($dias_morosidad>$dias_morosidad_maximo)
	{ $A_situacion_financiera="Moroso ($dias_morosidad / $dias_morosidad_maximo)"; $es_moroso=true;}
	else
	{ $A_situacion_financiera="Vigente ($dias_morosidad / $dias_morosidad_maximo)"; $es_moroso=false;}
	
	////////////////////////////////////////
	//condicones especiales//
	//morosidad alumno
	if($condicionar_situacion_financiera_alumno)
	{
		if($es_moroso)
		{ $js_funcion="alert('No se puede Autorizar Solicitudes a Alumnos Morosos...!!!');";}
		else
		{ $js_funcion="xajax_VERIFICA_SOLICITUD(xajax.getFormValues('frm')); return false;";}
	}
	else
	{ $js_funcion="xajax_VERIFICA_SOLICITUD(xajax.getFormValues('frm')); return false;";}
	
	//busca cta cte para deposito
	$ARRAY_CTA_CTE=array();
	$cons_cta="SELECT * FROM cuenta_corriente";
	$sql_cta=$conexion_mysqli->query($cons_cta);

	$num_cta=$sql_cta->num_rows;
	if($num_cta>0)
	{
		while($CTA=$sql_cta->fetch_assoc())
		{
			$C_id=$CTA["id"];
			$C_titular=$CTA["titular"];
			$C_banco=$CTA["banco"];
			$C_num_cuenta=$CTA["num_cuenta"];
			
			$ARRAY_CTA_CTE[$C_id]=$C_banco." ".$C_num_cuenta;
		}
	}
	
	$sql_cta->free();
	$conexion_mysqli->close();
}
else
{ $continuar=false;}
/////////////////////////////////////////////////////////

$privilegio=$_SESSION["USUARIO"]["privilegio"];

switch($privilegio)
{
	case"admi_total":
		$ver_autorizacion_forzada=true;
		break;
	default:
		$ver_autorizacion_forzada=false;	
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<?php $xajax->printJavascript(); ?> 
<script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">
<title>Autorizacion Financiera de Solicitudes</title>
<style type="text/css">
#apDiv2 {
	position:absolute;
	width:40%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 51px;
}
#apDiv1 {
	position:absolute;
	width:90%;
	height:44px;
	z-index:3;
	left: 5%;
	top: 319px;
}
#apDiv3 {
	position:absolute;
	width:35%;
	height:17px;
	z-index:4;
	left: 50%;
	top: 112px;
	text-align: center;
}
#apDiv4 {
	position:absolute;
	width:35%;
	height:21px;
	z-index:5;
	left: 50%;
	top: 172px;
	text-align: center;
}
#apDiv5 {
	position:absolute;
	width:35%;
	height:26px;
	z-index:6;
	left: 50%;
	top: 208px;
	text-align: center;
}
#apDiv5 {
	border: thin solid #00F;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	<?php echo $js_funcion;?>	
}
function CONFIRMAR_AUTORIZACION()
{
	c=confirm('Seguro(a) Desea Autorizar Forzadamente esta Solicitud...?');
	if(c){ window.location="autorizacion_forzada_1.php?id_solicitud=<?php echo $id_solicitud;?>";}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Autorizaci&oacute;n Financiera de Solicitudes</h1>
<div id="link"><br />
<a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver a Menu</a></div>
<div id="apDiv2">
  <table width="100%" border="1">
    <thead>
      <tr>
        <th colspan="2">Datos del Alumno</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td width="36%" ><strong>RUT:</strong>
          </th></td>
        <td width="64%" ><?php echo"$rut";?></td>
      </tr>
      <tr>
        <td><strong>Nombre</strong></td>
        <td><?php echo"$nombre";?></td>
      </tr>
      <tr>
        <td><strong>Apellido</strong></td>
        <td><?php echo"$apellido";?></td>
      </tr>
      <tr>
        <td><strong>Carrera</strong></td>
        <td><?php echo"$carrera";?></td>
      </tr>
      <tr>
        <td><strong>Sede:</strong></td>
        <td><?php echo"$sede";?></td>
      </tr>
      <tr>
        <td><strong>Jornada</strong></td>
        <td><?php echo $jornada_alumno;?></td>
      </tr>
      <tr>
        <td><strong>Nivel</strong></td>
        <td><?php echo $nivel_alumno;?></td>
      </tr>
      <tr>
        <td><strong>Situacion Financiera</strong></td>
        <td><?php echo $A_situacion_financiera;?></td>
      </tr>
    </tbody>
  </table>
</div>
<div id="apDiv1">
<?php if($continuar){?>
<form action="autorizacion_financiera_2.php" method="post" id="frm">
  <table width="90%" border="1">
  <thead>
  <tr>
    <th colspan="6">Generacion de Pago
      <input name="id_solicitud" type="hidden" id="id_solicitud" value="<?php echo $id_solicitud;?>" /></th>
    </tr>
    </thead>
    <tbody>
  <tr>
    <td width="25%"><strong>Sede</strong></td>
    <td colspan="5">
	<?php
	  echo CAMPO_SELECCION("fsede","sede",$sede);
	  ?>
      </td>
  </tr>
  <?php if(!$hay_solicitud){?>
  <tr>
    <td>Tipo Solicitud</td>
    <td colspan="5"><select name="tipo" id="tipo">
      <option value="certificado">certificado</option>
    </select></td>
  </tr>
  <tr>
    <td>Categoria</td>
    <td colspan="5"><select name="categoria" id="categoria">
      <?php
        foreach($array_certificados as $n => $valor)
		{
			echo'<option value="'.$valor.'">'.$valor.'</option>';
		}
		?>
    </select></td>
  </tr>
  <?php }?>
  <tr>
    <td><strong>Fecha</strong></td>
    <td colspan="5"><input name="fecha_movimiento" type="text" id="fecha_movimiento" size="11" maxlength="10" value="<?php echo"$fecha_actual";?>" readonly="readonly"/>
      <input type="button" name="boton" id="boton" value="..."/></td>
  </tr>
  <tr>
    <td><strong>Valor ($)</strong></td>
    <td colspan="5">
      <select name="valor" id="valor">
      	<?php
        foreach($ARRAY_VALORES["certificado"] as $ns => $valors)
		{
			if($valors==$S_valor){echo'<option value="'.$valors.'" selected="selected">'.$valors.'</option>';}
			else{echo'<option value="'.$valors.'">'.$valors.'</option>';}
		}
		?>
      </select></td>
  </tr>
  <tr>
    <td rowspan="3"><strong>Foma de Pago</strong></td>
    <td width="4%"><input name="forma_pago" type="radio" id="radio" value="efectivo" checked="checked" />
      <label for="forma_pago"></label></td>
    <td colspan="4">Efectivo</td>
  </tr>
  <tr>
    <td><input type="radio" name="forma_pago" id="radio2" value="cheque" /></td>
    <td width="12%">Cheque</td>
    <td width="17%">N.
      <input name="cheque_numero" type="text" id="cheque_numero" size="15" /></td>
    <td width="19%">Fecha Vencimiento<br />
      <input name="fecha_venc_cheque" type="text" id="fecha_venc_cheque" size="11" maxlength="10" value="<?php echo"$fecha_actual";?>" onchange="cargarMovimientos();" readonly="readonly"/>
      <input type="button" name="boton2" id="boton2" value="..."/></td>
    <td width="23%">Banco<br />
      <select name="cheque_banco" id="cheque_banco" onchange="xajax_ACTUALIZA_CANTIDAD(xajax.getFormValues('frm'), 'CHEQUE');return false;">
        <?php 
		 foreach($array_bancos as $n)
		 {echo'<option value="'.$n.'">'.$n.'</option>'; }
		 ?>
      </select></td>
  </tr>
  <tr>
    <td><input type="radio" name="forma_pago" id="radio3" value="deposito" />
      <label for="forma_pago"></label></td>
    <td>Deposito/transferencia</td>
    <td>N.
      <input name="deposito_numero" type="text" id="deposito_numero" size="15" /></td>
    <td colspan="2">cuenta<br />
      <select name="id_cta_cte" id="id_cta_cte" >
        <?php 
		if(count($ARRAY_CTA_CTE>0))
		{
		 foreach($ARRAY_CTA_CTE as $ncta =>$valorcta)
		 {echo'<option value="'.$ncta.'">'.$valorcta.'</option>'; }
		}
		else{ echo'<option value="0">Sin cta cte Registradas...</option>';}
		 ?>
      </select></td>
    </tr>
    </tbody>
</table>
</form>
  <script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton", "fecha_movimiento", "%Y-%m-%d");
	  cal.manageFields("boton2", "fecha_venc_cheque", "%Y-%m-%d");
		//cargarMovimientos();
    //]]></script>
 <?php }?> 
 <?php
 if(isset($_GET["error"]))
 {
	 $error=$_GET["error"];
	 switch($error)
	 {
		 case"S0":
		 		$msj="Solicitud Creada...";
		 	break;
		 case"S1":
		 		$msj="No se puede Crear solicitud, ya existe una no autorizada...";
		 	break;
		 case"S2":
		 		$msj="";
		 	break;		
		default:
			$msj="";	
	 }
	 echo $msj;
 }
 ?>
</div>
<div id="apDiv3"><a href="#" class="button_G" onclick="CONFIRMAR();">Pagar</a></div>
<?php if(($ver_autorizacion_forzada)and($hay_solicitud)){?><div id="apDiv4"><a href="#" class="button_R" onclick="CONFIRMAR_AUTORIZACION();">Forzar Autorizacion</a></div><?php }?>

<div id="apDiv5">
<?php
if($hay_solicitud)
{
	if($hay_archivo_solicitud)
	{
		echo'<img src="../../BAses/Images/advertencia.png" width="29" height="26" alt="ad" />Existe Un Archivo cargado, Para Revisar <a href="'.$ruta_archivo_autorizacion.'" target="_blank">Click Aqui</a>';
	}
	else
	{ echo'<img src="../../BAses/Images/b_drop.png" width="16" height="16" />Sin Archivo Cargado...';}
}
?>
</div>

</body>
</html>