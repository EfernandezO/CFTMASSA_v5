<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumnos_Pago_Mensualidades_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
///////////////////////ERRORES X GET////////////////////////////////
///////////////////////
//agrega tipo de pago
//agrega registro de boleta
//04-03-2011 mejora para el multi pago de cuota
///////////////////////
$msj="";
if($_GET)
{
	$error=$_GET["error"];
	switch($error)
	{
		case"0":
			$msj="*Cuota Agregada Correctamente*";
			break;
		case"1":
			$msj="";
			break;
		case"2":
			$msj="*INFO: Ultima Transaccion Anulada...*";
			break;	
	}
}
//////////////////////para mostrar solo opciones solo a primera cuota para admi_total no corre//////////////////////////////////
$privilegio=$_SESSION["USUARIO"]["privilegio"];
switch($privilegio)
{
	case"admi_total":
		$solo_primera_disponible=false;
		break;
	default:
		$solo_primera_disponible=true;	
}
require("../../../funciones/conexion_v2.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Mensualidades | Alumno</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/hint.css-master/hint.css"/>
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<script type="text/javascript" src="../../libreria_publica/jquery_libreria/jquery.min_1.2.6.js"></script>
 <!--INICIO MENU HORIZONTAL-->
 <link rel="stylesheet" type="text/css" href="../../libreria_publica/menu_horizontal/ddsmoothmenu-v.css"/>  
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/menu_horizontal/ddsmoothmenu.css"/>
 
<script type="text/javascript" src="../../libreria_publica/menu_horizontal/ddsmoothmenu.js">

/***********************************************
* Smooth Navigational Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>

<script type="text/javascript">

ddsmoothmenu.init({
	mainmenuid: "smoothmenu1", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu', //class added to menu's outer DIV
	//customtheme: ["#1c5a80", "#18374a"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

ddsmoothmenu.init({
	mainmenuid: "smoothmenu2", //Menu DIV id
	orientation: 'v', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu-v', //class added to menu's outer DIV
	//customtheme: ["#804000", "#482400"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

</script>
<!--FIN MENU HORIZONTAL-->
<style type="text/css">
<!--
#Layer3 {
	position:absolute;
	width:357px;
	height:115px;
	z-index:1;
	left: 55px;
	top: 47px;
	overflow: visible;
}
#Layer2 {
	position:absolute;
	width:383px;
	height:189px;
	z-index:2;
	left: 46px;
	top: 54px;
}
#Layer1 {
	position:absolute;
	width:48px;
	height:20px;
	z-index:1;
	left: 738px;
	top: 212px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #0080C0;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
#Layer4 {
	position:absolute;
	width:275px;
	height:34px;
	z-index:4;
	left: 487px;
	top: 30px;
}
.Estilo17 {font-size: 24px}
#Layer5 {
	position:absolute;
	width:108px;
	height:24px;
	z-index:8;
	left: 75%;
	top: 212px;
}
#div_edicion {
	position:absolute;
	width:56px;
	height:25px;
	z-index:6;
	left: 60%;
	top: 212px;
}
#Layer6 {
	position:absolute;
	width:35%;
	height:24px;
	z-index:17;
	left: 60%;
	top: 378px;
}
#area_cuotas {
	position:absolute;
	width:200px;
	height:115px;
	z-index:8;
	left: 487px;
	top: 77px;
}
#Layer2 {
	position:absolute;
	width:586px;
	height:115px;
	z-index:1;
	left: 23px;
	top: 276px;
}
#Layer2 #frmX #marcados {
	border: thin dashed #339900;
	width: 50%;
}
#apDiv1 {
	position:absolute;
	width:35%;
	height:115px;
	z-index:8;
	left: 60%;
	top: 77px;
}

-->
</style>
<!--<meta http-equiv="Refresh" content="15;URL=cuota1.php">-->
<script language="javascript">
function ACTUALIZAR()
{ 
	 c=confirm('Salir del Pago de Mensualidades..¿?');
	 if(c)
	 {	
	 	//document.location.reload();
	 	window.location="../../buscador_alumno_BETA/HALL/index.php";
	 }	
}
function multipago()
{
	c=confirm('Seguro(a) Que desea Pagar las Cuotas Seleccionadas?');
	if(c)
	{
		document.frmX.submit();
	}
}
</script>
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
</head>
<?php
$_SESSION["CUOTAS"]["verificador"]=true;
     //inicializo variables
	$rut=$_SESSION["SELECTOR_ALUMNO"]["rut"];
	$carrera=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
	$sede=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$A_id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	
	
	//busco si hay cobranzas y cuantas
	$informacion_cobranza="";
	$cons_C1="SELECT COUNT(id_cobranza) FROM cobranza WHERE id_alumno='$id_alumno' AND id_carrera='$A_id_carrera' AND sede='$sede'";
	$sqli_C=$conexion_mysqli->query($cons_C1)or die("Busco Cobranza ".$conexion_mysqli->error);
		$C=$sqli_C->fetch_row();
		$numero_registros_cobranza=$C[0];
		if(empty($numero_registros_cobranza)){ $numero_registros_cobranza=0;}
		if($numero_registros_cobranza>0){ $hay_cobranzas=true; $informacion_cobranza="$numero_registros_cobranza Registros";}
		else{ $hay_cobranzas=false;}
	$sqli_C->free();	
	
?>
<body>
<h1 id="banner">Finanzas - Cancelaci&oacute;n de Cuotas</h1>
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Cuotas</a>
  <ul>
 	 <li><a href="#" onclick="multipago();"><strong>Pagar Cuotas multiple</strong></a></li>
     <li><a href="../registro_ingresos_boleta/ingresos_boleta.php">Otros Pagos</a></li>
  </ul>
</li>
<li><a href="#">Informacion</a>
  <ul>
       <li><a href="../informe_financiero_alumno/informe_finan1.php">Ver Contratos</a></li>
       <li><a href="../informe_pagos/pagos_alumno/pagos_alumno.php?id_alumno=<?php echo base64_encode($id_alumno);?>&amp;" target="_blank">Ver Pagos</a></li>
       <li><a href="boletas_de_alumno.php?id_alumno=<?php echo base64_encode($id_alumno);?>" target="_blank">Boletas de Alumno</a></li>
  </ul>
</li>
<li><a <?php if($hay_cobranzas){?>class="hint--top  hint--always hint--error" data-hint="<?php echo $informacion_cobranza; }?>" href="#">Cobranza</a>
  <ul>
       <li><a href="../gestion_cobranza/operaciones/individuales/lista_cobranzas.php?lightbox[iframe]=true&lightbox[width]=900&lightbox[height]=600" class="lightbox">Revisar</a></li>
      
  </ul>
</li>
<li><a href="#" onclick="ACTUALIZAR();">Volver al Menu</a></li>
</ul>
<br style="clear: left" />
</div>
<div id="Layer6">
  <div align="center"><em><?php echo"$msj";?></em></div>
</div>
<?php
	//genero consulta para cargar datos de alumno
	//-----------------------------------------------//
	include("../../../funciones/VX.php");
	$evento="Ingreso a Pago Cuotas Alumno id_alumno: $id_alumno";
	REGISTRA_EVENTO($evento);
	//-----------------------------------------------//
	
	$consA="SELECT apellido, nombre, apellido_P, apellido_M, situacion, situacion_financiera, jornada, nivel, aplicar_intereses, aplicar_gastos_cobranza FROM alumno WHERE id='$id_alumno' LIMIT 1";
	$sqli_A=$conexion_mysqli->query($consA);
	$A=$sqli_A->fetch_assoc();
	
		$apellido_old=$A["apellido"];
		$apellido_new=$A["apellido_P"]." ".$A["apellido_M"];
		
		$aplicar_intereses=$A["aplicar_intereses"];
		$aplicar_gastos_cobranza=$A["aplicar_gastos_cobranza"];
		
		if($aplicar_intereses==1){$aplicar_intereses=true;}
		else{ $aplicar_intereses=false;}
		
		if($aplicar_gastos_cobranza==1){$aplicar_gastos_cobranza=true;}
		else{ $aplicar_gastos_cobranza=false;}
		
		if($aplicar_intereses){ $info_interes=" Intereses: Si<br>";}
		else{ $info_interes="Intereses: No<br>";}
		
		if($aplicar_gastos_cobranza){ $info_interes.=" Gastos: Si";}
		else{ $info_interes.="Gastos: NO";}
		
		
		if($apellido_new==" ")
		{$apellido_label=$apellido_old;}
		else
		{$apellido_label=$apellido_new;}
	   $nombre=$A["nombre"];
	   $situacion=$A["situacion"];
	   $situacion_financiera=$A["situacion_financiera"];
	   $jornada_alumno=$A["jornada"];
	   $nivel_alumno=$A["nivel"];
	   //cambio formato a las cadenas (primera letra de cada palabra en mayuscula)
	    
	   $nombre = ucwords(strtolower($nombre));
	$sqli_A->free();
	
	
	//muesdtro datos de alumno
	?>
<div id="Layer2" style="position:absolute; left:5%; top:405px; width:90%; height:86px; z-index:2"> 
<form action="multi_pagos/multi_pago1.php" method="post" name="frmX" id="frmX">
             <table width="100%" border="0" summary="Letras">
			   <thead>
               <tr>
                <th  ><div align="center"><strong>*</strong></div></th>
               <th  ><div align="center"><strong>N&deg;</strong></div></th>
               <th  ><div align="center"><strong>A&ntilde;o</strong></div></th>
               <th ><div align="center"><strong>Valor:</strong></div></th>
			   <th  ><div align="center"><strong>Deuda X Letra </strong></div></th>
               <th ><div align="center"><strong>Fecha Vencimiento </strong></div></th>
			   <th ><div align="center"><strong>Condicion</strong></div></th>
			   <th ><div align="center"><strong>Ultimo Pago</strong></div></th>
			   <th ><div align="center"><strong>Tipo</strong></div></th>
               <th colspan="3" ><div align="center"><strong>Opciones</strong></div></th>
               </tr>
			   </thead>
			   <tbody> 
	  <?php
	///////muestro botones extra si activa
	$edicion=true;
	/////
	include("../../../funciones/funcion.php");
	include("../../../funciones/funciones_sistema.php");

   $consL="SELECT * FROM letras WHERE idalumn='$id_alumno' ORDER BY fechavenc";
    $sqlL=$conexion_mysqli->query($consL)or die("|-> CUO ".$conexion_mysqli->error);
	
	$aux=0;	
  	$cuenta=1;
	$aux_mostrar_primera=true;
	$suma_deuda=0;
	$suma_valor=0;
	$TOTAL_INTERES=0;
	$TOTAL_GASTOS_COBRANZA=0;
	$ARRAY_MOROSIDAD_X_PERIODO[0]=0;
	$ARRAY_MOROSIDAD_X_PERIODO[1]=0;
	$ARRAY_MOROSIDAD_X_PERIODO[2]=0;
	$ARRAY_MOROSIDAD_X_PERIODO[3]=0;
	$ARRAY_MOROSIDAD_X_PERIODO[4]=0;
	$ARRAY_MOROSIDAD_X_PERIODO[5]=0;
	
	$primera_vuelta=true;
	while($B=$sqlL->fetch_assoc())
	{
		$id_cuota=$B["id"];
	    $numletra=$B["numletra"];
	    $numcuota=$B["numcuota"];
		$pagada=$B["pagada"];
		$fechavenc=fecha_format($B["fechavenc"]);
		$valor=$B["valor"];
		$deudaXletra=$B["deudaXletra"];
		
		///---------------------------------------------------------//
		//considero solo cuotas no pagadas
		if($pagada=="S")
		{ $cuota_pagada=true;}
		else{ $cuota_pagada=false;}
			
	
		$aux_interes=0;
		$aux_gastos_cobranza=0;
		if(!$cuota_pagada)
		{
			if($aplicar_intereses)
			{$aux_interes=INTERES_X_ATRASO_V2($id_cuota);}
			else{}
			
			if($aplicar_gastos_cobranza)
			{$aux_gastos_cobranza=GASTOS_COBRANZA_V2($id_cuota);}
			else{}
		}
		//---------------------------------------------------------------//
		$TOTAL_INTERES+=$aux_interes;
		$TOTAL_GASTOS_COBRANZA+=$aux_gastos_cobranza;
		
		$anulada=$B["anulada"];
		
		$totalcuo=$B["totalcuo"];
		$fechemision=fecha_format($B["fechemision"]);
		$tipo=$B["tipo"];
		////////////////////
		$semestre=$B["semestre"];
		$year=$B["ano"];
		$ultimo_pago=$B["fecha_ultimo_pago"];
		if((empty($ultimo_pago))or($ultimo_pago=="0000-00-00"))
		{
			$ultimo_pago="---";
			$ultimo_pago_label="---";
		}
		else
		{ $ultimo_pago_label=fecha_format($ultimo_pago);}
		
		//almaceno los nuemros de letra para hacer un truco
		$datos["numero_letra"][$aux]=$numletra;
		
		///morosidad cuotas alumno
		$dias_morosidad_alumno=DIAS_MOROSIDAD($id_alumno, $id_cuota);
		if($dias_morosidad_alumno>0)
			{
				if($dias_morosidad_alumno<=30)
				{ $tipo_morosidad=1;}
				elseif($dias_morosidad_alumno<=60)
				{ $tipo_morosidad=2;}
				elseif($dias_morosidad_alumno<=90)
				{ $tipo_morosidad=3;}
				elseif($dias_morosidad_alumno<=120)
				{ $tipo_morosidad=4;}
				else
				{ $tipo_morosidad=5;}
			}
			else
			{ $tipo_morosidad=0;}
			
		if(isset($ARRAY_MOROSIDAD_X_PERIODO[$tipo_morosidad]))
		{ $ARRAY_MOROSIDAD_X_PERIODO[$tipo_morosidad]+=$deudaXletra;}
		else
		{ $ARRAY_MOROSIDAD_X_PERIODO[$tipo_morosidad]=$deudaXletra;}
		
		
		//-----------------************----------------
		$suma_deuda+=$deudaXletra;
		$suma_valor+=$valor;
		
		switch($pagada)
		{
			case"S":
				$condicion="Pagada";
				$mostrar_opcion_ver=true;
				$mostrar_opcion_pagar=false;
				$mostrar_opcion_abonar=false;
				$mostrar_pago_multiple=false;
				break;
			case"N":
				$condicion="Pendiente";
				$mostrar_pago_multiple=true;
				if($solo_primera_disponible)
				{
					if($primera_vuelta)
					{
						$mostrar_opcion_ver=true;
						$mostrar_opcion_pagar=true;
						$mostrar_opcion_abonar=true;
						$primera_vuelta=false;
					}
					else
					{
						$mostrar_opcion_ver=false;
						$mostrar_opcion_pagar=false;
						$mostrar_opcion_abonar=false;
					}
				}
				else
				{
					$mostrar_opcion_ver=true;
					$mostrar_opcion_pagar=true;
					$mostrar_opcion_abonar=true;
				}
				break;
			case"A":
				$condicion="Abonada"; 	
				$mostrar_pago_multiple=true;
				if($solo_primera_disponible)
				{
					if($primera_vuelta)
					{
						$mostrar_opcion_ver=true;
						$mostrar_opcion_pagar=true;
						$mostrar_opcion_abonar=true;
						$primera_vuelta=false;
					}
					else
					{
						$mostrar_opcion_ver=false;
						$mostrar_opcion_pagar=false;
						$mostrar_opcion_abonar=false;
					}
				}
				else
				{
					$mostrar_opcion_ver=true;
					$mostrar_opcion_pagar=true;
					$mostrar_opcion_abonar=true;
				}
				 break;
		}
		
		if($anulada=="N")
		{     
		       echo'<tr>';
			   
				   if($mostrar_pago_multiple)
				   {echo'<td><input name="id_cuota[]" type="checkbox" id="id_cuota[]" value="'.$id_cuota.'"></td>';}
				   else{ echo'<td>&nbsp;</td>';}
				   
			   echo'<td ><div align="center"><a href="#" title="ID: '.$id_cuota.'">'.$cuenta.'</a></div></td>
				   <td ><div align="center">'.$year.'</div></td>
				   <td><div align="center">$ '.number_format($valor,0,",",".").'</div></td>
				   <td><div align="center"><a href="#" title="Interes: $'.$aux_interes.' Gastos Cobranza: $'.$aux_gastos_cobranza.'">$'.number_format($deudaXletra,0,",",".").'</a></div></td>
				   <td><div align="center"><a href="#" title="Tipo Morosidad:'.$tipo_morosidad.' dias morosidad: '.$dias_morosidad_alumno.'">'.$fechavenc.'</a></div></td>
				   <td><div align="center">'.$condicion.'</div></td>
				   <td><div align="center">'.$ultimo_pago_label.'</div></td>
				   <td><div align="center">'.$tipo.'</div></td>';
				   ///filtro por bloqueo muestro opciones de pago y abono solo a primera cuota sin pagar
				  
				  if($mostrar_opcion_ver)
					{echo'<td><a href="detalle.php?id_cuota='.base64_encode($id_cuota).'">Ver</a></td>';}
					else{echo'<td>&nbsp;</td>';}
				
				 if($mostrar_opcion_pagar)	
				   {echo'<td><a href="pago1.php?id_cuota='.base64_encode($id_cuota).'&ocultoval='.base64_encode($valor).'&ocultodeuda_ac='.base64_encode($deudaXletra).'&oculto_id_alumno='.base64_encode($id_alumno).'&semestre='.base64_encode($semestre).'&year='.base64_encode($year).'">Pagar</a></td>';}
				   else{echo'<td>&nbsp;</td>';}
				   
				  if($mostrar_opcion_abonar)
				  {echo'<td><a href="abono.php?id_cuota='.base64_encode($id_cuota).'&ocultovalor='.base64_encode($valor).'&ocultodeudaXcuo='.base64_encode($deudaXletra).'&oculto_id_alumno='.base64_encode($id_alumno).'&semestre='.base64_encode($semestre).'&year='.base64_encode($year).'">Abonar</a></td>';}
				  else{echo'<td>&nbsp;</td>';}
				  
				   echo'</tr>';
             
			
				$aux++;	 
				$cuenta++;
		}
		else
		{
		  //si hay letras anuladas las cuenta
		   $conta_anulada++;
		}   
	
	
	}
	
	if($aux=="0")
	{
	   echo'<tr>
	        <td colspan="12"><b>No Hay Cuotas Registradas</b> 
			</td>
	        </tr>';
	}	
	$total_pagado=($suma_valor - $suma_deuda);
	
	?>
    </tbody><tfoot><tr><td colspan="12"><?php echo "$aux Cuotas Registradas - Valor Total $ ".number_format($suma_valor,0,",",".")." - Deuda Total $ ".number_format($suma_deuda,0,",",".")." - Total Pagado $ ".number_format($total_pagado,0,",",".");?><div align="right" id="espacio_boton"></div></td></tr></tfoot></table>
    
             <div id="marcados"><em><br />
             Para las Cuotas Marcadas: <a href="#" class="button"  onclick="multipago();">Pagar</a></em><br />
               ...
             </div>
           </form>
</div>
<?php
	//datos para truco xajax
	@$x_datos=base64_encode(serialize($datos));
	//echo $x_datos;
	echo'<input id="datos_ocultos" name="datos_ocultos" type="hidden" value="'.$x_datos.'">';
	//fin truco
	$ano="";

	@list($moroso,$tactual, $deuda_actual)=es_moroso($id_alumno,$semestre,$ano);	
	
		if($moroso)
		{$consAA="UPDATE alumno SET situacion_financiera='M' WHERE id='$id_alumno' LIMIT 1";}
		else
		{$consAA="UPDATE alumno SET situacion_financiera='V' WHERE id='$id_alumno' LIMIT 1";} 
		$conexion_mysqli->query($consAA)or die("<b>Error:</b> ".$conexion_mysqli->error);
		//echo"<b>$consAA</b><br>";
		
		   
//----------------> funcion
function es_moroso($id,$semestre,$ano,$fecha_corte=0)
{
	require("../../../funciones/conexion_v2.php");
    if($fecha_corte==0)
	{$fecha_corte=date("Y-m-d");}
	//elimine semestre de la consulta
	$consX="SELECT SUM(valor) FROM letras WHERE idalumn='$id' AND anulada='N' AND fechavenc<='$fecha_corte'";
	
	
	$consZ="SELECT SUM(deudaXletra) FROM letras WHERE idalumn='$id' AND anulada='N' AND fechavenc<='$fecha_corte'";
	if(DEBUG){echo"<br>-->$consX <br>->$consZ<br>";}
	
	$total_actual=$conexion_mysqli->query($consX) or die($conexion_mysqli->error);
	$datox =$total_actual->fetch_row(); 
		$total_actual_cuota = $datox[0];
		if(empty($total_actual_cuota))
		{$total_actual_cuota=0;}
	//-------------------------------------------//
	$deuda_actual=$conexion_mysqli->query($consZ) or die ($conexion_mysqli->error);
	$datoz =$deuda_actual->fetch_row(); 
	$deuda_actual_cuota = $datoz[0]; 
		if(empty($deuda_actual_cuota))
		{$deuda_actual_cuota=0;}
	//---------------------------------------//	
	$pagado_actual=($total_actual_cuota-$deuda_actual_cuota);
	//---------------------------------------//
	if(DEBUG){echo"<b>En funcion <br> Total a la fecha: $tactual<br> Deuda a la fecha: $dactual<br> Pagado a la fecha: $pagado_actual<br></b>";}
	//determino si moroso
	
	if($pagado_actual==$total_actual_cuota)
	{$moroso=false;}
	else
	{$moroso=true;}
	$total_actual->free();
	$deuda_actual->free();
	return array($moroso,$total_actual_cuota,$deuda_actual_cuota);
	$conexion_mysqli->close();
} 

?>
<div id="apDiv1">
  <table align="center">
    <thead>
      <tr>
        <th colspan="6"></th>
      </tr>
    </thead>
    <tbody>
      <?php
if(DEBUG){var_dump($ARRAY_MOROSIDAD_X_PERIODO);}
include("../../../funciones/G_chart.php");
///////////////////////////ARRAY para GRAFICO////////////////////////////////////
$concat_datos="";
$primera_vuelta=true;
$max_dato=0;
$tabla_datos="";
$tabla_datos_cabecera='<tr>
	<td align="center">Sin Deuda</td>
    <td align="center">30 dias</td>
    <td align="center">30-60 dias</td>
    <td align="center">60-90 dias</td>
    <td align="center">90-120 dias</td>
    <td align="center">120 + dias</td>
</tr>';
foreach($ARRAY_MOROSIDAD_X_PERIODO as $nx => $valorx)
{
	if($valorx>$max_dato){ $max_dato=$valorx;}
	if($primera_vuelta)
	{
			$concat_datos.=$valorx;
			$primera_vuelta=false;
	}
	else
	{ $concat_datos.=",".$valorx;}
	$tabla_datos.='<td align="center">$ '.number_format($valorx,0,",",".").'</td>';
}
$tabla_datos.='</tr>';

		$array_grafico["datos"][]=$concat_datos;
		$array_grafico["rango_X"]="|0|1|2|3|4|5|";
		$array_grafico["tipo"]="bvs";//"bvs";"lc"
		$array_grafico["rango_Y"]="|E|F|M|A|M|J|J|A|S|O|N|D";
		$array_grafico["rango_Y_auto"]=true;//si true no necesita enviar "rango_Y", se genera automaticamnete
		$array_grafico["dato_max"]=$max_dato;
		$array_grafico["etiqueta_izquierda"]="Pesos";
		$array_grafico["etiqueta_inferior"]="Tipo Morosidad";
		$array_grafico["titulo"]="Morosidad Cuotas";
		$array_grafico["simbologia"]="0 sin deuda|1 30 dias|2 30-60 dias|3 60-90 dias|4 90-120 dias|5 mas 120 dias";
		$array_grafico["colores_lineas_hex"]="F1A100";
		$array_grafico["color_titulo_hex"]="F10000";
		$array_grafico["size_titulo"]=20;
		$array_grafico["alto_img_grafico"]=200;
		$array_grafico["ancho_img_grafico"]=350;
		///////////////----------------------------------------------------///////////////
		echo'<tr><td colspan="6" align="center">';
		GRAFICO_GOOGLE($array_grafico);
		echo'</td></tr>'.$tabla_datos_cabecera.$tabla_datos;	
?>
    </tbody>
  </table>
</div>
<div id="Layer3" style="position:absolute; left:5%; top:77px; width:50%; height:252px; z-index:3">
  <table width="100%" border="1">
	   <thead>
       <tr>
	   	<th colspan="4">Datos del Alumno</th>
        </tr>
	   </thead>
        <tbody>
       <tr>
       <td width="22%" ><strong>RUT:</strong></th>
       <td colspan="3" ><?php echo"$rut";?></td>
       </tr>
       <tr>
       <td><strong>Nombre</strong></td>
       <td colspan="3"><?php echo"$nombre";?></td>
       </tr>
       <tr>
       <td><strong>Apellido</strong></td>
       <td colspan="3"><?php echo"$apellido_label";?></td>
       </tr>
       <tr>
       <td><strong>Carrera</strong></td>
       <td colspan="3"><?php echo"$carrera";?></td>
       </tr>
       <tr>
       <td><strong>Sede:</strong></td>
       <td><?php echo"$sede";?></td>
       <td>Situacion Financiera</td>
       <td><?php echo"$situacion_financiera";?></td>
       </tr>
       <tr>
         <td><strong>Jornada</strong></td>
         <td><?php echo $jornada_alumno;?></td>
         <td>Nivel</td>
         <td><?php echo $nivel_alumno;?></td>
       </tr>
       <tr>
         <td rowspan="4"><strong>Deuda Actual</strong></td>
         <td width="30%">Arancel</td>
         <td width="19%" align="right"><?php echo "$ ".number_format($deuda_actual,0,",",".")."";?></td>
         <td width="29%" rowspan="4"><?php echo $info_interes;?></td>
       </tr>
       <tr>
         <td>Interes</td>
         <td align="right"><?php echo "$ ".number_format($TOTAL_INTERES,0,",",".")."";?></td>
       </tr>
       <tr>
         <td>Gastos Cobranza</td>
         <td align="right"><?php echo "$ ".number_format($TOTAL_GASTOS_COBRANZA,0,",",".")."";?></td>
       </tr>
       <tr>
         <td>TOTAL</td>
         <td align="right"><?php echo "$ ".number_format(($TOTAL_GASTOS_COBRANZA+$TOTAL_INTERES+$deuda_actual),0,",",".")."";?></td>
       </tr>
       </tbody>
       </table>
</div>
</body>
</html>