<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("gestion_cobranza_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//error_reporting(E_ALL);
require("../../../../funciones/conexion_v2.php");
require("../../../../funciones/funciones_sistema.php");
$continuar=false;
$hay_datos=false;

$year_actual=date("Y");
$mes_actual=date("m");
if($mes_actual>=8){$semestre_actual=2;}
else{$semestre_actual=1;}


///////////////////////////////
//periodod a consultar de contratos
$year_consulta=$year_actual;
$semestre_consulta=$semestre_actual;


if($_POST)
{
	
	if(DEBUG){ var_dump($_POST);}
	$sede=mysqli_real_escape_string($conexion_mysqli, $_POST["sede"]);
	$id_carrera=mysqli_real_escape_string($conexion_mysqli, $_POST["carrera"]);
	$year_ingreso=mysqli_real_escape_string($conexion_mysqli, $_POST["year"]);
	$year_cuotas=mysqli_real_escape_string($conexion_mysqli, $_POST["year_cuotas"]);
	$jornada=mysqli_real_escape_string($conexion_mysqli, $_POST["jornada"]);
	$grupo=mysqli_real_escape_string($conexion_mysqli, $_POST["grupo"]);
	
	$consulta_matricula_vigente=mysqli_real_escape_string($conexion_mysqli, $_POST["matricula_vigente"]);
	
	
	if(isset($_POST["nivel"])){$array_niveles=$_POST["nivel"];}
	else{$array_niveles=array();}
	$fecha_corte=mysqli_real_escape_string($conexion_mysqli, $_POST["fecha_corte"]);
	$posicion_actual=0;
	$posicion_anterior=0;
	$posicion_siguiente=1;
	$continuar=true;
	
}
if($_GET)
{
	$continuar=true;
	if(DEBUG){ var_dump($_GET);}
	$sede=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["sede"]));
	$id_carrera=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["id_carrera"]));
	$year_ingreso=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["year_ingreso"]));
	$year_cuotas=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["year_cuotas"]));
	$jornada=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["jornada"]));
	$grupo=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["grupo"]));
	
	$consulta_matricula_vigente=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["matricula_vigente"]));
	
	$array_niveles=base64_decode($_GET["niveles"]);
	
	
	$array_niveles=unserialize($array_niveles);
	
	
	$fecha_corte=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["fecha_corte"]));
	
	$posicion_actual=base64_decode(mysqli_real_escape_string($conexion_mysqli, $_GET["posicion"]));
	
	if(DEBUG){echo"----> Posicion Actual llegada: $posicion_actual<br>";}
	
	
	if(is_numeric($posicion_actual)){$posicion_ok=true;}
	else{ $posicion_ok=false;}
	
	if($posicion_ok)
	{
		$posicion_anterior=$posicion_actual-1;
		$posicion_siguiente=$posicion_actual+1;
		
		if($posicion_anterior<0){ $posicion_anterior=0;}
		if($posicion_siguiente<0){ $posicion_siguiente=2;}
		if($posicion_actual<0){$posicion_actual=1;}
	}
	else
	{
		$posicion_anterior=0;
		$posicion_actual=1;
		$posicion_siguiente=2;
	}
	
}
if($continuar)
{	

	$array_niveles_serializado=base64_encode(serialize($array_niveles));
	
	if($id_carrera!=="0"){ $condicion_carrera="AND alumno.id_carrera='$id_carrera'";}
	else{ $condicion_carrera="";}
	
	if($year_ingreso!=="0"){ $condicion_ingreso=" AND alumno.ingreso='$year_ingreso'";}
	else{ $condicion_ingreso="";}
	
	if($year_cuotas!=="0"){ $condicion_year_cuota=" AND letras.ano='$year_cuotas'";}
	else{ $condicion_year_cuota="";}
	
	if($jornada!=="0"){ $condicion_jornada=" AND alumno.jornada='$jornada'";}
	else{ $condicion_jornada="";}
	
	if($grupo!=="0"){ $condicion_grupo=" AND alumno.grupo='$grupo'";}
	else{ $condicion_grupo="";}
	
	$condicion_fecha_corte=" AND letras.fechavenc<='$fecha_corte'";
	
	$inicio_ciclio=true;
	$niveles="";
	if(count($array_niveles)>0)
	{
		if(is_array($array_niveles))
		{
			foreach($array_niveles as $nn=>$valornn)
			{
				$valornn=mysqli_real_escape_string($conexion_mysqli, $valornn);
				if($inicio_ciclio)
				{ 
					$niveles.="'$valornn'";
					$inicio_ciclio=false;
				}
				else
				{ $niveles.=", '$valornn'";}
			}
		}
		else{ $niveles="'sin nivel'";}
		$condicion_nivel="AND alumno.nivel IN($niveles)";
	}
	else{$condicion_nivel="";}
	
	$condicion_cuota=" AND letras.pagada IN('N', 'A')";
	
	
	//----------------------------------SELECCION de Alumnos y llenado de array-----------------------------------------------//
	$cons_MAIN="SELECT DISTINCT(idalumn), alumno.id_carrera FROM letras INNER JOIN alumno ON letras.idalumn=alumno.id WHERE alumno.realizar_cobranza='1' $condicion_carrera AND alumno.sede='$sede' $condicion_ingreso $condicion_jornada $condicion_nivel $condicion_grupo $condicion_year_cuota $condicion_cuota $condicion_fecha_corte ORDER by alumno.apellido_P, alumno.apellido_M";
	if(DEBUG){ echo"<br>---> $cons_MAIN<br>";}
	$sqli_M=$conexion_mysqli->query($cons_MAIN)or die("MAIN ".$conexion_mysqli->error);
	$num_alumnos=$sqli_M->num_rows;
	if(DEBUG){ echo"Total alumno encontrados: $num_alumnos<br>";}
	
	$ARRAY_ID_ALUMNOS=array();
	if($num_alumnos>0)
	{
		$aux=0;
		$hay_datos=true;
		while($D=$sqli_M->fetch_row())
		{
			
			$aux_id_carrera=$D[1];
			$aux_id_alumno=$D[0];
			
			$matricula_vigente_alumno=VERIFICAR_MATRICULA($aux_id_alumno, $aux_id_carrera, true, false, $semestre_consulta, false, $year_consulta, false);
	
	
			switch($consulta_matricula_vigente)
			{
				case"si":
					if($matricula_vigente_alumno)
					{$ARRAY_ID_ALUMNOS[$aux]=$aux_id_alumno;$aux++; if(DEBUG){ echo"Alumno CON matricula vigente en [$semestre_consulta - $year_consulta] UTILIZAR<br>";}}
					break;
				case"no":
					if(!$matricula_vigente_alumno)
					{$ARRAY_ID_ALUMNOS[$aux]=$aux_id_alumno;$aux++; if(DEBUG){ echo"Alumno SIN matricula vigente en [$semestre_consulta - $year_consulta] UTILIZAR<br>";}}
					break;
					default:
					$ARRAY_ID_ALUMNOS[$aux]=$aux_id_alumno;$aux++;
					if(DEBUG){ echo"NO considerar Vigencia de Matricula de Alumno[$semestre_consulta - $year_consulta] UTILIZAR<br>";}
			}
			
		}
	}
	$sqli_M->free();
	$num_alumnos_v2=$aux;
	//-------------------------------------fin busqueda y llenado---------------------------------------------//
	if($posicion_siguiente>=$num_alumnos){$posicion_siguiente=$posicion_actual; if(DEBUG){echo"------>Siguiente Mayor a Total<br>";}}
	
	//-----------------------URL-------------------------------------//
	
	$url_datos_1="cobranza_2.php?sede=".base64_encode($sede)."&id_carrera=".base64_encode($id_carrera)."&year_ingreso=".base64_encode($year_ingreso)."&year_cuotas=".base64_encode($year_cuotas)."&jornada=".base64_encode($jornada)."&grupo=".base64_encode($grupo)."&fecha_corte=".base64_encode($fecha_corte)."&posicion=".base64_encode($posicion_siguiente)."&matricula_vigente=".base64_encode($consulta_matricula_vigente)."&niveles=".$array_niveles_serializado;
	
	$url_datos_2="cobranza_2.php?sede=".base64_encode($sede)."&id_carrera=".base64_encode($id_carrera)."&year_ingreso=".base64_encode($year_ingreso)."&year_cuotas=".base64_encode($year_cuotas)."&jornada=".base64_encode($jornada)."&grupo=".base64_encode($grupo)."&fecha_corte=".base64_encode($fecha_corte)."&posicion=".base64_encode($posicion_anterior)."&matricula_vigente=".base64_encode($consulta_matricula_vigente)."&niveles=".$array_niveles_serializado;
	
	
	$url_datos_3="sede=".base64_encode($sede)."&id_carrera=".base64_encode($id_carrera)."&year_ingreso=".base64_encode($year_ingreso)."&year_cuotas=".base64_encode($year_cuotas)."&jornada=".base64_encode($jornada)."&grupo=".base64_encode($grupo)."&fecha_corte=".base64_encode($fecha_corte)."&posicion=".base64_encode($posicion_actual)."&matricula_vigente=".base64_encode($consulta_matricula_vigente)."&niveles=".$array_niveles_serializado;
	
	$url_datos_4="sede=".base64_encode($sede)."&id_carrera=".base64_encode($id_carrera)."&year_ingreso=".base64_encode($year_ingreso)."&year_cuotas=".base64_encode($year_cuotas)."&jornada=".base64_encode($jornada)."&grupo=".base64_encode($grupo)."&fecha_corte=".base64_encode($fecha_corte)."&posicion=".base64_encode($posicion_actual)."&matricula_vigente=".base64_encode($consulta_matricula_vigente)."&niveles=".$array_niveles_serializado;
	
	//-----------------------------------------------------------------------------//
	$lista_posiciones='<select name="posicion" id="posicion" onchange="CAMBIAR_PAGINA(this.value)">';
	for($i=1;$i<=$num_alumnos_v2;$i++)
	{ 
		if($i==($posicion_actual+1)){$select='selected="selected"';}else{$select='';}
		$lista_posiciones.='<option value="'.base64_encode($i-1).'" '.$select.'>'.$i.'</option>';
	}
    $lista_posiciones.='</select>';
	//---------------------------------------------------------------------//
	
	if(DEBUG){echo "alumno ".($posicion_actual+1)." de $num_alumnos_v2 ".$ARRAY_ID_ALUMNOS[$posicion_actual]."<br>";}
	///-----------------------------------------------------------------//
	
	$ruta="../../../CONTENEDOR_GLOBAL/img_alumnos/";
	
	if($num_alumnos_v2>0)
	{
		$cons_A="SELECT * FROM alumno WHERE id='".$ARRAY_ID_ALUMNOS[$posicion_actual]."' LIMIT 1";
		$sqli_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
		$DA=$sqli_A->fetch_assoc();
			$A_id_alumno=$DA["id"];
			$A_rut=$DA["rut"];
			$A_nombre=$DA["nombre"];
			$A_apellido_P=$DA["apellido_P"];
			$A_apellido_M=$DA["apellido_M"];
			$A_year_ingreso=$DA["ingreso"];
			$A_nivel=$DA["nivel"];
			$A_id_carrera=$DA["id_carrera"];
			$A_nombre_carrera=NOMBRE_CARRERA($A_id_carrera);
			$A_jornada=$DA["jornada"];
			$A_img=$DA["imagen"];
			$A_sede=$DA["sede"];
			$A_fono=$DA["fono"];
			$A_fono_2=$DA["fonoa"];
			$A_email=$DA["email"];
			
			//$A_matricula_vigente_alumno=VERIFICAR_MATRICULA($$A_id, $A_id_carrera, true, false, $semestre_consulta, false, $year_consulta, false);
			
			$aplicar_intereses=$DA["aplicar_intereses"];
			$aplicar_gastos_cobranza=$DA["aplicar_gastos_cobranza"];
			
			if($aplicar_intereses==1){$aplicar_intereses=true;}
			else{ $aplicar_intereses=false;}
			
			if($aplicar_gastos_cobranza==1){$aplicar_gastos_cobranza=true;}
			else{ $aplicar_gastos_cobranza=false;}
			
			if($aplicar_intereses){ $info_interes="Si";}
			else{ $info_interes="No";}
			
			if($aplicar_gastos_cobranza){ $info_gastos="Si";}
			else{ $info_gastos="No";}
			
			if(empty($A_img)){ $img_alumno="../../../BAses/Images/login_logo.png";}
			else{ $img_alumno=$ruta.$A_img;}
		$sqli_A->free();	
		
		//$A_deuda_actual=DEUDA_ACTUAL($A_id_alumno, $fecha_corte);
		list($A_deuda_actual_v2, $A_intereses, $A_gastos_cobranza)=DEUDA_ACTUAL_V2($A_id_alumno, $fecha_corte);
		$A_deuda_actual=($A_deuda_actual_v2+$A_intereses+$A_gastos_cobranza);
		$A_dias_morosidad_alumno=DIAS_MOROSIDAD($A_id_alumno);
		//-------------------------------------------------------------//
		//datos deuda
		$cons_M="SELECT * FROM letras WHERE deudaXletra>'0' AND idalumn='$A_id_alumno' AND pagada<>'S' ORDER by fechavenc";
		$sqli=$conexion_mysqli->query($cons_M)or die($conexion_mysqli->error);
		$num_cuotas_morosas=$sqli->num_rows;
		
		$cuenta_cuotas=0;
		$cuenta_cuotas_morosas=0;
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
		if($num_cuotas_morosas>0)
		{
			$primera_vuelta=true;
			while($DC=$sqli->fetch_assoc())
			{
				$id_cuota=$DC["id"];
				$fechavenc=$DC["fechavenc"];
				
				$array_fecha_vence=explode("-",$fechavenc);
				$dia_vence=$array_fecha_vence[2];
				$valor=$DC["valor"];
				$deudaXletra=$DC["deudaXletra"];
				$pagada=$DC["pagada"];
				$totalcuo=$DC["totalcuo"];
				
				if($fechavenc<=$fecha_corte)
				{ $cuenta_cuotas_morosas++;}
				
				if($aplicar_intereses)
				{$aux_interes=INTERES_X_ATRASO_V2($id_cuota);}
				else{$aux_interes=0;}
				
				if($aplicar_gastos_cobranza)
				{$aux_gastos_cobranza=GASTOS_COBRANZA_V2($id_cuota);}
				else{$aux_gastos_cobranza=0;}
				
				$TOTAL_INTERES+=$aux_interes;
				$TOTAL_GASTOS_COBRANZA+=$aux_gastos_cobranza;
				
				$dias_morosidad_cuota=DIAS_MOROSIDAD($A_id_alumno, $id_cuota);
				$tipo_morosidad=TIPO_MOROSIDAD($dias_morosidad_cuota);
				
				
				if(isset($ARRAY_MOROSIDAD_X_PERIODO[$tipo_morosidad]))
				{ $ARRAY_MOROSIDAD_X_PERIODO[$tipo_morosidad]+=$deudaXletra;}
				else
				{ $ARRAY_MOROSIDAD_X_PERIODO[$tipo_morosidad]=$deudaXletra;}
				
				$suma_deuda+=$deudaXletra;
				$suma_valor+=$valor;
				
				$cuenta_cuotas++;
	
			}
		}
		$sqli->free();
	}
	else
	{ if(DEBUG){ echo"Sin Alumnos Encontrados, ";}}

}

$validador=md5("GDXT".date("d-m-Y"));
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Cobranza Alumnos</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>

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

 <!--INICIO MENU HORIZONTAL-->
  <link rel="stylesheet" type="text/css" href="../../../libreria_publica/menu_horizontal/ddsmoothmenu.css"/>
  <script type="text/javascript" src="../../../libreria_publica/menu_horizontal/ddsmoothmenu.js">

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

</script>
<script language="javascript" type="application/javascript">
function ELIMINAR(id_cobranza)
{
	url="eliminar/eliminar.php?id_cobranza="+id_cobranza+"&<?php echo $url_datos_3?>";
	c=confirm('Seguro(a) Desea Eliminar este Registro..?');
	if(c){ window.location=url;}
	
}
function CAMBIAR_PAGINA(posicion_destino)
{
	url_cambio_pagina="cobranza_2.php?sede=<?php echo base64_encode($sede);?>&id_carrera=<?php echo base64_encode($id_carrera);?>&year_ingreso=<?php echo base64_encode($year_ingreso);?>&year_cuotas=<?php echo base64_encode($year_cuotas);?>&jornada=<?php echo base64_encode($jornada);?>&grupo=<?php echo base64_encode($grupo);?>&matricula_vigente=<?php echo $consulta_matricula_vigente;?>&fecha_corte=<?php echo base64_encode($fecha_corte);?>&posicion="+posicion_destino+"&niveles=<?php echo $array_niveles_serializado;?>";
	//alert(url_cambio_pagina);
	window.location=url_cambio_pagina;
}
</script>
<!--FIN MENU HORIZONTAL-->

  

<style type="text/css">
#apDiv1 {
	position:absolute;
	width:46%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 86px;
}
#apDiv2 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 504px;
}
#apDiv3 {
	position:absolute;
	width:43%;
	height:115px;
	z-index:3;
	left: 52%;
	top: 101px;
}
</style>
</head>
<body>
<h1 id="banner">Finanzas - Cobranzas</h1>
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
	<li><a href="#">Cobranza</a>
      <ul>
     	 <li><?php if($hay_datos){?><a href="nueva/nueva_cobranza.php?id_alumno=<?php echo base64_encode($A_id_alumno);?>&id_carrera=<?php echo base64_encode($A_id_carrera);?>&fecha_corte=<?php echo base64_encode($fecha_corte);?>&year_cuota=<?php echo base64_encode($year_cuotas);?>&lightbox[iframe]=true&lightbox[width]=400&lightbox[height]=500" class="lightbox">Nueva</a><?php }?></li>
      </ul>
    </li>
	<li><a href="#">Listados</a>
    	<ul>
        	<li><a href="listados/listado_a_xls.php?<?php echo $url_datos_3;?>" target="_blank">-> XLS</a></li>
        </ul>
    </li>
    <li><a href="#">Operaciones</a>
    	<ul>
        	<li><a href="mailCobranza/envioMail1.php?<?php echo $url_datos_4;?>" target="_blank">Enviar Boletin Informativo (email)</a></li>
            <li><a href="mailCobranza/envioMail2.php?<?php echo $url_datos_4;?>" target="_blank">Carta Cobranza (email)</a></li>
        </ul>
    </li>
	<li><a href="cobranza_1.php">Volver a Seleccion</a></li>
</ul>
</li>
</ul>
<br style="clear: left" />
</div>
<div id="apDiv1">
<?php if($hay_datos){?>
<table width="100%" border="1">
<thead>
<tr>
	<th colspan="6">MATRICULA [<?php echo"$semestre_consulta - $year_consulta";?>] consulta(<?php echo $consulta_matricula_vigente;?>)</th>
    </tr>
</thead>
<tbody>
  <tr>
    <td width="17%" rowspan="2"><img src="<?php echo $img_alumno;?>" alt="" width="60" height="70" id="foto" /></td>
    <td height="32"><strong>Rut</strong></td>
    <td height="32" colspan="2"><a href="../../../buscador_alumno_BETA/enrutador.php?validador=<?php echo $validador;?>&id_alumno=<?php echo $A_id_alumno;?>" target="_blank" title="Click para revisar"><?php echo $A_rut;?></a></td>
    <td colspan="2" align="right">
            <?php echo $lista_posiciones." / $num_alumnos_v2";?></td>
    </tr>
  <tr>
    <td><strong>Alumno</strong></td>
    <td colspan="4"><?php echo"$A_nombre $A_apellido_P $A_apellido_M";?></td>
    </tr>
  <tr>
    <td><strong>Carrera</strong></td>
    <td colspan="5"><?php echo $A_id_carrera."_".$A_nombre_carrera;?></td>
    </tr>
  <tr>
    <td><strong>Fono</strong></td>
    <td><?php echo $A_fono;?></td>
    <td><?php echo $A_fono_2;?></td>
    <td><strong>Email</strong></td>
    <td colspan="2"><?php echo $A_email;?></td>
    </tr>
  <tr>
    <td><strong>Ingreso</strong></td>
    <td width="11%"><?php echo $A_year_ingreso;?></td>
    <td width="11%"><strong>Jornada</strong></td>
    <td width="11%"><?php echo $A_jornada;?></td>
    <td width="9%"><strong>Nivel</strong></td>
    <td width="41%"><?php echo $A_nivel;?></td>
  </tr>
  <tr>
    <td align="left">Intereses (<?php echo $info_interes;?>)</td>
    <td bgcolor="#FFFF00" align="right"><?php echo "$ ".number_format($A_intereses,0,",","."); ?></td>
    <td colspan="2">Fecha Corte</td>
    <td><?php echo $fecha_corte;?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="left">cobranza (<?php echo $info_gastos;?>)</td>
    <td bgcolor="#FFFF00" align="right"><?php echo "$ ".number_format($A_gastos_cobranza,0,",","."); ?></td>
    <td colspan="2">Año de Cuotas</td>
    <td><?php echo $year_cuotas;?></td>
    <td><a href="info_pagos.php?id_alumno=<?php echo base64_encode($A_id_alumno);?>&id_carrera=<?php echo base64_encode($A_id_carrera);?>&fecha_corte=<?php echo base64_encode($fecha_corte);?>&year_cuota=<?php echo base64_encode($year_cuotas);?>&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=500" class="lightbox">Info Cuotas</a></td>
  </tr>
  <tr>
    <td align="left">Arancel</td>
    <td bgcolor="#FFFF00" align="right"><?php echo "$ ".number_format($A_deuda_actual_v2,0,",",".");?></td>
    <td colspan="2"><strong>N. Cuotas Morosas</strong></td>
    <td><?php echo"$cuenta_cuotas_morosas / $cuenta_cuotas";?></td>
    <td><?php echo"[Vencimientos: $dia_vence] $A_dias_morosidad_alumno dias de Morosidad ";?></td>
  </tr>
  <tr>
    <td align="left"><strong>Deuda Total</strong></td>
    <td bgcolor="#FFFF00" align="right"><?php echo "$ ".number_format(($A_deuda_actual_v2+$A_intereses+$A_gastos_cobranza),0,",",".");?></td>
    <td colspan="3">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="left"><a href="<?php echo $url_datos_2;?>" title="ver alumno anterior">Anterior</a></td>
    <td colspan="5" align="right"><a href="<?php echo $url_datos_1;?>" title="ver siguiente alumno">Siguiente</a></td>
  </tr>
    </tbody>
  </table>
</div>
<div id="apDiv2">
<div class="demo_jui">
<table width="100%" align="left" class="display" id="example">
	<thead>
        <tr>
        	<th><strong>N</strong></th>
            <th><strong>Fecha</strong></th>
            <th><strong>Fecha Corte</strong></th>
             <th><strong>Fecha Compromiso</strong></th>
            <th><strong>Deuda Actual</strong></th>
             <th><strong>Hay Respuesta</strong></th>
             <th><strong>Observacion</strong></th>
             <th colspan="2"><strong>Opc</strong></th>
        </tr>
	</thead>
	<tbody>
        <?php
		
			$cons_C1="SELECT * FROM cobranza WHERE id_alumno='".$ARRAY_ID_ALUMNOS[$posicion_actual]."' AND id_carrera='$A_id_carrera' AND sede='$A_sede' ORDER by fecha desc";
			$sql_c1=$conexion_mysqli->query($cons_C1) or die("cobranza ".$conexion_mysqli->error);
			$num_cobranzas=$sql_c1->num_rows;
			if(DEBUG){ echo"-->$cons_C1<br>Num registros:$num_cobranzas<br>";}
			$aux=0;
			if($num_cobranzas>0)
			{
				while($C=$sql_c1->fetch_assoc())
				{
					$aux++;
					$C_id=$C["id_cobranza"];
					$C_tipo=$C["tipo"];
					$C_fecha=$C["fecha"];
					$C_fecha_corte=$C["fecha_corte"];
					$C_fecha_compromiso=$C["fecha_compromiso"];
					$C_hay_respuesta=$C["hay_respuesta"];
					$C_observacion=$C["observacion"];
					$C_deuda_actual=$C["deuda_actual"];
					$C_cod_user=$C["cod_user"];
					$C_deuda_actual=$C["deuda_actual"];
					
					
					if($C_hay_respuesta==1){$C_hay_respuesta_label="Si";}
					else{ $C_hay_respuesta_label="No";}
					
					echo'<tr>
							<td align="center">'.$aux.'/'.$C_id.'</td>
							<td align="center">'.$C_fecha.'</td>
							<td align="center">'.$C_fecha_corte.'</td>
							<td align="center">'.$C_fecha_compromiso.'</td>
							<td align="center">'.$C_deuda_actual.'</td>
							<td align="center">'.$C_hay_respuesta_label.'</td>
							<td align="center">'.$C_observacion.'</td>
							<td align="center"><a href="#" onclick="ELIMINAR(\''.base64_encode($C_id).'\');">Eliminar</a></td>
							<td align="center"><a href="editar/edita_cobranza.php?id_cobranza='.base64_encode($C_id).'&lightbox[iframe]=true&lightbox[width]=400&lightbox[height]=500" class="lightbox">Editar</a></td>
						 </tr>';
					
				}
				
			}
			else
			{
				echo'<tr>
						<td colspan="7">Sin Registros...</td>
					</tr>';
			}
			$conexion_mysqli->close();
		?>
     </tbody>
    </table>
</div>
</div>
<div id="apDiv3">
  <table align="center">
    <thead>
      <tr>
        <th colspan="6"></th>
      </tr>
    </thead>
    <tbody>
      <?php
if(DEBUG){var_dump($ARRAY_MOROSIDAD_X_PERIODO);}
include("../../../../funciones/G_chart.php");
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
<?php }else{ echo"Sin Alumnos con deuda encontrados bajo este filtro... :(";}?>
</body>
</html>