<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Impresion_Y_Gestion_de_contratos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

//////////////////////////////AJAX//////////////////////////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("actualizar/verificador_contratos_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"CONFIRMAR");
///////////////---------_____________-------////////////////////////////


if(($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])and(isset($_GET["id_contrato"])))
{
	///permitir edicion a administrador t
	
	$editar_contrato=false;
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	if(($privilegio=="admi_total")or($privilegio=="matricula"))
	{
		$action="actualizar/datos_contrato_up.php";
		$editar_contrato=true;
	}
	/////
	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
	require("../../../funciones/funciones_sistema.php");
	
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$rut_alumno=$_SESSION["SELECTOR_ALUMNO"]["rut"];
	$sede_alumno=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	
	$id_contrato=$_GET["id_contrato"];
	
	$cons="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";
	$sql=$conexion_mysqli->query($cons) or die($conexion_mysqli->error);
	$dato=$sql->fetch_assoc();
	//datos del alumno
	$id_alumno=$dato["id"];
	$nombre_alumno=$dato["nombre"];
	$apellido_P_alu=$dato["apellido_P"];
	$apellido_M_alu=$dato["apellido_M"];
	$apellido_alu_full=$apellido_P_alu." ".$apellido_M_alu;
	$apellido_old_version=$dato["apellido"];
	$situacion_alumno=$dato["situacion"];
	$situacion_financiera=$dato["situacion_financiera"];
	$carrera_alumno=$dato["carrera"];
	switch($situacion_alumno)
	{
		case"V":
			$situacion_alumno_label="Vigente";
			break;
		case"M":
			$situacion_alumno_label="Moroso";
			break;
		case"R":
			$situacion_alumno_label="Retirado";
			break;		
	}
	
	if((empty($apellido_alu_full))or($apellido_alu_full==" "))
	{
		
		$apellido_alu_full=$apellido_old_version;
	}
	/////////////////////
	if(DEBUG)
	{echo"id alumno -> $id_alumno<br>";}
	$sql->free();
	
	if(!is_numeric($id_alumno))
	{
		header("location: index.php?errorusuario=si");
	}
	
	//////////////////////////////////////////////////////
	$array_vigencia_contrato=array("semestral", "anual");
	$array_estados_beca_BNM=array("sin_beca", "media_beca", "completa");
	$array_estados_beca_BET=array("sin_beca", "completa");
}
else
{
	header("location: index.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="../../libreria_publica/jquery_libreria/jquery_1_3_2.min.js"></script>
  <script type="text/javascript" src="../../libreria_publica/sexy_lightbox/jQuery/jquery.easing.1.3.js"></script>
  <script type="text/javascript" src="../../libreria_publica/sexy_lightbox/jQuery/sexylightbox.v2.3.jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/sexy_lightbox/jQuery/sexylightbox.css">
  <script type="text/javascript">
    $(document).ready(function(){
      SexyLightbox.initialize({color:'black', dir: '../../libreria_publica/sexy_lightbox/jQuery/sexyimages'});
    });
  </script>
<?php include("../../../funciones/codificacion.php");?>
<title>Informe Financiero Alumno</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
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
<?php $xajax->printJavascript(); ?> 
<!--FIN MENU HORIZONTAL-->	
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:45%;
	height:439px;
	z-index:1;
	left: 2%;
	top: 104px;
}
#apDiv2 {
	position:absolute;
	width:200px;
	height:26px;
	z-index:2;
	left: 488px;
	top: 120px;
}
#apDiv3 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:3;
	left: 508px;
	top: 397px;
}
#apDiv4 {
	position:absolute;
	width:48%;
	height:86px;
	z-index:4;
	left: 50%;
	top: 104px;
}
-->
</style>
<script src="../../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../SpryAssets/SpryCollapsiblePanel.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
 <script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
 <script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">
<style type="text/css">
<!--
.Estilo1 {
	font-size: 12px
}
.Estilo4 {font-size: 12em}
#link {
	text-align: right;
	padding-right: 10px;
}
a:link {
	color: #069;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #069;
}
a:hover {
	text-decoration: underline;
	color: #F00;
}
a:active {
	text-decoration: none;
	color: #069;
}
-->
</style>
<script language="javascript">
function CONFIRMAR()
{
	c=confirm('zSeguro(a) Desea Actualizar los Datos del Contrato?');
	if(c)
	{
		document.frm.submit();
	}
}
function REPACTAR(url)
{
	c=confirm('Seguro(a) habilitar la Reasignacion para este contrato...?');
	if(c)
	{
		window.location=url;
	}
}

function ELIMINAR_PAGO(id_contrato, id_pago, id_boleta)
{
	c=confirm('Desea Eliminar este Pago..?\n (se elimina pago y boleta)');
	if(c)
	{
		url="eliminar_pago/elimina_pago.php?id_contrato="+id_contrato+"&id_pago="+id_pago+"&id_boleta="+id_boleta;
		window.location=url;
		
	}
}
</script>
</head>
<?php
//////contrato
	$cons_contrato="SELECT * FROM contratos2 WHERE id_alumno='$id_alumno' AND id='$id_contrato' LIMIT 1";
	if(DEBUG)
	{echo"<br>---> $cons_contrato<br>";}
	$sql_contrato=$conexion_mysqli->query($cons_contrato)or die("contrato ".$conexion_mysqli->error);
	$D_contrato=$sql_contrato->fetch_assoc();
	////obtengo datos del contrato
	
	//$id_contrato=$D_contrato["id"];
	$C_sede=$D_contrato["sede"];
	$C_cod_user=$D_contrato["cod_user"];
	$id_carrera_contrato=$D_contrato["id_carrera"];
	$C_fecha_inicio=$D_contrato["fecha_inicio"];
	$C_fecha_fin=$D_contrato["fecha_fin"];
	$nivel_alumno_contrato=$D_contrato["nivel_alumno"];
	$nivel_alumno_contrato_2=$D_contrato["nivel_alumno_2"];
	$C_jornada=$D_contrato["jornada"];
	$numero_cuotas=$D_contrato["numero_cuotas"];
	$arancel=$D_contrato["arancel"];
	$saldo_a_favor=$D_contrato["saldo_a_favor"];
	$C_totalBeneficiosEstudiantiles=$D_contrato["totalBeneficiosEstudiantiles"];
	$porcentaje_desc_contado=$D_contrato["porcentaje_desc_contado"];
	$total=$D_contrato["total"];
	$contado_paga=$D_contrato["contado_paga"];
	$cheque_paga=$D_contrato["cheque_paga"];
	$linea_credito_paga=$D_contrato["linea_credito_paga"];
	
	$cantidad_beca=$D_contrato["cantidad_beca"];
	$porcentaje_beca=$D_contrato["porcentaje_beca"];
	$txt_beca=$D_contrato["txt_beca"];
	$beca_nuevo_milenio=$D_contrato["beca_nuevo_milenio"];
	$aporte_beca_nuevo_milenio=$D_contrato["aporte_beca_nuevo_milenio"];
	$beca_excelencia=$D_contrato["beca_excelencia"];
	$aporte_beca_excelencia=$D_contrato["aporte_beca_excelencia"];
	
	if(empty($cantidad_beca)){$cantidad_beca=0;}
	if(empty($porcentaje_beca)){$porcentaje_beca=0;}
	if(empty($aporte_beca_excelencia)){$aporte_beca_excelencia=0;}
	if(empty($aporte_beca_nuevo_milenio)){$aporte_beca_nuevo_milenio=0;}
	
	
	$opcion_pag_matricula=$D_contrato["opcion_pag_matricula"];
	$matricula=$D_contrato["matricula_a_pagar"];
	$condicion=strtolower($D_contrato["condicion"]);
	$excedente=$D_contrato["excedente"];
	$cod_contrato_anterior=$D_contrato["id_contrato_previo"];
	$reasignado=$D_contrato["reasignado"];
	$repactado=$D_contrato["repactado"];
	if($repactado>0){ $repactado_label="Si";}
	else{ $repactado_label="No";}
	$vigencia=$D_contrato["vigencia"];

	$yearIngresoCarrera=$D_contrato["yearIngresoCarrera"];
	$semestre_contrato=$D_contrato["semestre"];
	$year_contrato=$D_contrato["ano"];
	
	$C_fecha_generacion=$D_contrato["fecha_generacion"];
	$sql_contrato->free();
	///////////////////////
	
?>
<body>
<h1 id="banner">Administrador - Informe Financiero</h1>
<div id="smoothmenu1" class="ddsmoothmenu">
  <ul>
<li><a href="#">Contrato</a>
	<ul>
    	<?php if($reasignado!="no"){?>
       	<li><a href="#" onclick="REPACTAR('actualizar/cambio_condicion_repactar.php?id_contrato=<?php echo $id_contrato;?>')">Repactar...?</a></li>
          <?php }?>
        <?php
          if($editar_contrato){?>
        <li><a href="#"  onclick="xajax_CONFIRMAR(xajax.getFormValues('frm'));">Grabar</a></li>
          <?php }?>
          <li><a href="devolucion_excedente/devolucion_excedente.php?id_contrato=<?php echo $id_contrato;?>">Devoluciones</a></li>
		 
          
    </ul>
</li>
<li><a href="#">Cuotas</a>
	<ul>
    	<li><a href="edicion_cuotas/agrega/index.php?id_alumno=<?php echo base64_encode($id_alumno);?>&id_contrato=<?php echo base64_encode($id_contrato);?>&semestre=<?php echo $semestre_contrato;?>&year=<?php echo base64_encode($year_contrato);?>">Agregar Nueva</a></li>
    </ul>
</li>
<li><a href="#">Beneficios Estudiantiles</a>
	<ul>
    	<li><a href="beneficiosEstudiantiles/agrega/index.php?id_alumno=<?php echo base64_encode($id_alumno);?>&id_contrato=<?php echo base64_encode($id_contrato);?>&semestre=<?php echo $semestre_contrato;?>&year=<?php echo base64_encode($year_contrato);?>">Revisar</a></li>
    </ul>
</li>
<li><a href="resumen_all_pagos_pdf/resumen_all_pagos_pdf.php?ver_pagos=todos" target="_blank">pagos</a></li>
<li><a href="index.php">Volver a Contratos</a></li>
</ul>
<br style="clear: left" />
</div> 
<div id="apDiv1">
  <div id="CollapsiblePanel3" class="CollapsiblePanel">
    <div class="CollapsiblePanelTab Estilo1" tabindex="0">...</div>
    <div class="CollapsiblePanelContent">
      <table width="100%" border="0">
      <thead>
      <th colspan="3">Alumno
        </thead></th>
      <tbody>
        <tr>
          <td width="37%">ID Alumno</td>
          <td width="63%"><?php echo $id_alumno;?></td>
        </tr>
        <tr>
          <td>Rut</td>
          <td><?php echo $rut_alumno;?></td>
        </tr>
        <tr>
          <td>Nombre</td>
          <td><?php echo $nombre_alumno;?></td>
        </tr>
        <tr>
          <td>Apellido</td>
          <td><?php echo $apellido_alu_full;?></td>
        </tr>
        <tr>
          <td>Carrera</td>
          <td><?php echo $carrera_alumno;?></td>
        </tr>
         <tr>
          <td>Situacion Financiera</td>
          <td><?php echo $situacion_financiera?></td>
        </tr>
        </tbody>
      </table><br />
    </div>
  </div>
  <div id="CollapsiblePanel4" class="CollapsiblePanel">
    <div class="CollapsiblePanelTab Estilo1" tabindex="0">...</div>
    <div class="CollapsiblePanelContent">
    <form action="<?php echo $action;?>" method="post" name="frm" id="frm">
      <table width="100%" border="0">
      <thead>
      	<tr>
        	<th colspan="2">Contrato <?php echo"COD.: $id_contrato";?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Fecha Generacion</td>
          <td><?php echo "$C_fecha_generacion por el usuario: $C_cod_user";?></td>
        </tr>
        <tr>
          <td>Periodo</td>
          <td width="211">
            Inicio
            __
            <input name="fecha_inicio" type="text" id="fecha_inicio" size="15"  value="<?php echo $C_fecha_inicio;?>"/>
            <input type="button" name="boton1" id="boton1" value="..." />
            <br />
            Fin_____        
            <input name="fecha_fin" type="text" id="fecha_fin" size="15" value="<?php echo $C_fecha_fin;?>"/>
            <input type="button" name="boton2" id="boton2" value="..." /></td>
        </tr>
        <tr>
          <td>Sede</td>
          <td  align="right"><?php echo CAMPO_SELECCION("sede_contrato", "sede",$C_sede, false);?></td>
        </tr>
        <tr>
          <td>Nivel del Alumno Cuando Realizo el Contrato (1 semestre)</td>
          <td><div align="right">
           <?php echo CAMPO_SELECCION("nivel_alumno_contrato", "niveles_academicos", $nivel_alumno_contrato);?>
           
            </div></td>
        </tr>
        <?php if($vigencia=="anual"){?>
        <tr>
          <td>Nivel del Alumno Cuando Realizo el Contrato (2 semestre)</td>
          <td><div align="right"> <?php echo CAMPO_SELECCION("nivel_alumno_contrato_2", "niveles_academicos", $nivel_alumno_contrato_2);?> </div></td>
        </tr>
        <?php }?>
        <tr>
          <td>Carrera Contrato</td>
          <td><?php echo CAMPO_SELECCION("id_carrera_contrato", "carreras",$id_carrera_contrato, false);?></td>
        </tr>
        <tr>
          <td>año Ingreso Carrera</td>
          <td><?php echo CAMPO_SELECCION("yearIngresoCarrera","year",$yearIngresoCarrera);?></td>
        </tr>
        <tr>
          <td>Jornada</td>
          <td><?php echo CAMPO_SELECCION("jornada_contrato", "jornada",$C_jornada, false);?></td>
        </tr>
        <tr>
          <td width="237">Comentario
            <input name="id_contrato" type="hidden" id="id_contrato" value="<?php echo $id_contrato;?>" /></td>
          <td width="211"><textarea name="comentario" id="comentario" cols="25" rows="2"><?php echo $txt_beca?></textarea></td>
        </tr>
        <tr>
          <td>A&ntilde;o</td>
          <td><div align="right">
            <?php echo CAMPO_SELECCION("year_contrato","year",$year_contrato);?>
            </div></td>
        </tr>
        <tr>
          <td>Semestre</td>
          <td><div align="right">
            <?php echo CAMPO_SELECCION("semestre_contrato","semestre",$semestre_contrato);?>
            </div></td>
        </tr>
        <tr>
          <td>Vigencia</td>
          <td>
          <div align="right">
            <select name="vigencia" id="vigencia">
            <?php
			foreach($array_vigencia_contrato as $nvc => $valorvc)
			{
				if($valorvc==$vigencia)
				{ echo'<option value="'.$valorvc.'" selected="selected">'.$valorvc.'</option>';}
				else
				{ echo'<option value="'.$valorvc.'">'.$valorvc.'</option>';}
			}
            ?>
            </select>
           </div>
           </td>
        </tr>
        <tr>
          <td>Beca Nuevo Milenio</td>
          <td><div align="right">
            <select name="beca_nuevo_milenio" id="beca_nuevo_milenio">
            <?php
			foreach($array_estados_beca_BNM as $nbnm => $valorbnm)
			{
				if($valorbnm==$beca_nuevo_milenio)
				{ echo'<option value="'.$valorbnm.'" selected="selected">'.$valorbnm.'</option>';}
				else
				{ echo'<option value="'.$valorbnm.'">'.$valorbnm.'</option>';}
			}
            ?>
            </select>
          </div></td>
        </tr>
        <tr>
          <td>Aporte Beca Nuevo Milenio</td>
          <td><div align="right">
            <label for="aporte_beca_nuevo_milenio"></label>
            $
            <input name="aporte_beca_nuevo_milenio" type="text" id="aporte_beca_nuevo_milenio" value="<?php echo $aporte_beca_nuevo_milenio;?>" />
          </div></td>
        </tr>
        <tr>
          <td>Beca Excelencia</td>
          <td><div align="right">
             <select name="beca_excelencia" id="beca_excelencia">
            <?php
			foreach($array_estados_beca_BET as $nbet => $valorbet)
			{
				if($valorbnm==$beca_excelencia)
				{ echo'<option value="'.$valorbet.'" selected="selected">'.$valorbet.'</option>';}
				else
				{ echo'<option value="'.$valorbet.'">'.$valorbet.'</option>';}
			}
            ?>
            </select>
            </div>
            </td>
        </tr>
        <tr>
          <td>Aporte Beca Excelencia</td>
          <td>
          <div align="right">
          <label for="aporte_beca_excelencia"></label>
            $
            <input name="aporte_beca_excelencia" type="text" id="aporte_beca_excelencia" value="<?php echo $aporte_beca_excelencia;?>" />
            </div>
            </td>
        </tr>
        <tr>
          <td>Matricula</td>
          <td><div align="right">$
            <input name="matricula_contrato" type="text" id="matricula_contrato" maxlength="6" value="<?php echo $matricula;?>"/>
            </div></td>
        </tr>
        <tr>
          <td>Forma Pago</td>
          <td><div align="right">
            <input type="text" name="forma_pago_matricula" id="forma_pago_matricula" value="<?php echo $opcion_pag_matricula;?>"/>
            </div></td>
        </tr>
        <tr>
          <td>Arancel</td>
          <td><div align="right">$
            <input type="text" name="arancel" id="arancel" value="<?php echo $arancel;?>"/>
            </div></td>
        </tr>
        <tr>
          <td>Saldo A favor (pago previo)</td>
          <td><div align="right">$
            <input type="text" name="saldo_a_favor" id="saldo_a_favor" value=" <?php echo $saldo_a_favor;?>"/>
           </div></td>
        </tr>
        <tr>
          <td>Total Beneficios Estudiantiles</td>
          <td><div align="right">$
            <input type="text" name="totalBeneficiosEstudiantiles" id="totalBeneficiosEstudiantiles" value=" <?php echo $C_totalBeneficiosEstudiantiles;?>"/>
          </div></td>
        </tr>
        <tr>
          <td>Cantidad Desc.</td>
          <td><div align="right">
            <input name="cantidad_beca" type="text" id="cantidad_beca" value=" <?php echo $cantidad_beca;?>" />
           </div></td>
        </tr>
        <tr>
          <td>% desc. Beca</td>
          <td><div align="right">
            <input type="text" name="porcentaje_desc_beca" id="porcentaje_desc_beca" value=" <?php echo $porcentaje_beca;?>"/>
           </div></td>
        </tr>
        <tr>
          <td>% desc. Pago Contado</td>
          <td><div align="right">
            <input type="text" name="porcentaje_desc_contado" id="porcentaje_desc_contado" value="<?php echo $porcentaje_desc_contado;?>"/>
            </div></td>
        </tr>
        <tr>
          <td>Total a Pagar</td>
          <td><div align="right">$
            <input type="text" name="total" id="total" value="<?php echo $total;?>"/>
            </div></td>
        </tr>
        <tr>
          <td>Contado</td>
          <td><div align="right">$
            <input type="text" name="contado" id="contado" value="<?php echo $contado_paga;?>"/>
            </div></td>
        </tr>
        <tr>
          <td>Cheque</td>
          <td><div align="right">$
            <input type="text" name="cheque" id="cheque"  value="<?php echo $cheque_paga;?>"/>
            </div></td>
        </tr>
        <tr>
          <td>Linea Credito</td>
          <td><div align="right">$
            <input type="text" name="linea_credito" id="linea_credito" value="<?php echo $linea_credito_paga;?>"/>
            </div></td>
        </tr>
        <tr>
          <td>Excedente Prox Contrato</td>
          <td><div align="right">$
            <input type="text" name="excedentes" id="excedentes" value="<?php echo $excedente;?>"/>
            </div></td>
        </tr>
        <tr>
          <td>Cod Contrato Previo</td>
          <td><div align="right">
            <input type="text" name="id_contrato_anterior" id="id_contrato_anterior" value="<?php echo $cod_contrato_anterior;?>"/>
            </div></td>
        </tr>
        <tr>
          <td>Reasignado</td>
          <td><div align="right"><?php echo $reasignado;?></div></td>
        </tr>
        <tr>
          <td>Repactado</td>
          <td><div align="right"><?php echo $repactado_label;?></div></td>
        </tr>
          </tbody>
      </table>
      </form>
    </div>
  </div>
</div>
<div id="apDiv4">
  <div id="CollapsiblePanel1" class="CollapsiblePanel">
    <div class="CollapsiblePanelTab Estilo1" tabindex="0">...</div>
    <div class="CollapsiblePanelContent">
      <table width="100%" border="0">
      <thead>
      <tr>
      	<th colspan="10">Pagos</th>
      </tr>
    <tr>
      <td><strong>N&deg;</strong></td>
      <td><strong>ID Pago</strong></td>
      <td><strong>ID Cuota*</strong></td>
      <td><strong>Fecha Pago</strong></td>
      <td><strong>Valor</strong></td>
      <td><strong>Glosa</strong></td>
      <td><strong>Forma Pago</strong></td>
      <td><strong>Concepto</strong></td>
      <td><strong>ID user</strong></td>
       <td><strong>&nbsp;</strong></td>
    </tr>
    </thead>
    <tbody>
 <?php
    	$cons_pagos="SELECT * FROM pagos WHERE id_alumno='$id_alumno' AND semestre='$semestre_contrato' AND year='$year_contrato' ORDER by por_concepto";
		if(DEBUG){ echo"$cons_pagos<br>";}
		$sql_pagos=$conexion_mysqli->query($cons_pagos)or die("pagos X->".$conexion_mysqli->error);
		$num_pagos=$sql_pagos->num_rows;
		if($num_pagos>0)
		{
			$numerador=1;
			$acumula_valor=0;
			while($P=$sql_pagos->fetch_assoc())
			{
				$id_pago=$P["idpago"];
				$id_cuota=$P["id_cuota"];
				$id_boleta=$P["id_boleta"];
				$id_alumno=$P["id_alumno"];
				$fecha_pago=$P["fechapago"];
				$valor=$P["valor"];
				$tipodoc=$P["tipodoc"];
				$glosa=$P["glosa"];
				$forma_pago=$P["forma_pago"];
				$fechaV_cheque=$P["fechaV_cheque"];
				$id_cheque=$P["id_cheque"];
				$por_concepto=$P["por_concepto"];
				$cod_user=$P["cod_user"];
				/////////////////////
				$acumula_valor+=$valor;
				if($forma_pago=="cheque")
				{
					$forma_pago_label='<a href="../visor_cheques/ver_cheque.php?id_cheque='.base64_encode($id_cheque).'&TB_iframe=true&height=300&width=470" rel="sexylightbox" title="Ver Cheque">cheque</a>';
				}
				else
				{
					$forma_pago_label=$forma_pago;
				}
				
				////////////////////
				if($id_cuota==0)
				{
					$id_cuota_label="---";
				}
				else
				{
					$id_cuota_label=$id_cuota;
				}
				
				echo'<tr align="center">
					<td><em>'.$numerador.'</em></td>
					<td><em>'.$id_pago.'</em></td>
					<td><em>'.$id_cuota_label.'</em></td>
					<td><em>'.fecha_format($fecha_pago).'</em></td>
					<td><em>$'.number_format($valor,0,",",".").'</em></td>
					<td><textarea name="glosa" cols="12" rows="1">'.$glosa.'</textarea></td>
					<td><em>'.$forma_pago_label.'</em></td>
					<td><em>'.$por_concepto.'</em></td>
					<td><em>'.$cod_user.'</em></td>
					<td><a href="#" onclick="ELIMINAR_PAGO('.$id_contrato.','.$id_pago.', '.$id_boleta.');"><img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="Eliminar" /></a></td>
					</tr>';
				$numerador++;		
			}
			echo'<tr>
				<td colspan="4">Total pagos</td>
				<td colspan="6">$'.number_format($acumula_valor,0,",",".").'</td>
				</tr>';
		}
		else
		{
			echo'<tr>
     		 <td colspan="10">Sin pagos Registrados</td></tr>';
		}
		$sql_pagos->free();
		
	?>
    </tbody>
  </table><br />
  <!--devolucion-->
   <table width="100%" border="0">
      <thead>
      <tr>
      	<th colspan="9">Devoluciones</th>
      </tr>
    <tr>
      <td><strong>N&deg;</strong></td>
      <td><strong>ID Pago</strong></td>
      <td><strong>Fecha Pago</strong></td>
      <td><strong>Valor</strong></td>
      <td><strong>Glosa</strong></td>
      <td><strong>Forma Pago</strong></td>
      <td><strong>Concepto</strong></td>
      <td><strong>ID user</strong></td>
    </tr>
    </thead>
    <tbody>
 <?php
    	$cons_devolucion="SELECT * FROM pagos WHERE id_alumno='$id_alumno' AND sede='$sede_alumno' AND aux_num_documento='$id_contrato' AND movimiento='E' ORDER by por_concepto";
		if(DEBUG){ echo"$cons_devolucion<br>";}
		$sql_pago=$conexion_mysqli->query($cons_devolucion)or die("devolucion X->".$conexion_mysqli->error);
		$num_devolucion=$sql_pago->num_rows;
		if($num_devolucion>0)
		{
			$numerador=1;
			$acumula_valor=0;
			while($P=$sql_pago->fetch_assoc())
			{
				$id_pago=$P["idpago"];
				
				$fecha_pago=$P["fechapago"];
				$valor=$P["valor"];
				$tipodoc=$P["tipodoc"];
				$glosa=$P["glosa"];
				$forma_pago=$P["forma_pago"];
				$fechaV_cheque=$P["fechaV_cheque"];
				$id_cheque=$P["id_cheque"];
				$por_concepto=$P["por_concepto"];
				$cod_user=$P["cod_user"];
				/////////////////////
				$acumula_valor+=$valor;
				if($forma_pago=="cheque")
				{
					$forma_pago_label='<a href="../visor_cheques/ver_cheque.php?id_cheque='.base64_encode($id_cheque).'&TB_iframe=true&height=300&width=470" rel="sexylightbox" title="Ver Cheque">cheque</a>';
				}
				else
				{
					$forma_pago_label=$forma_pago;
				}
				
				////////////////////
				echo'<tr align="center">
					<td><em>'.$numerador.'</em></td>
					<td><em><a href="devolucion_excedente/ver_comprobante_devolucion.php?id_pago='.base64_encode($id_pago).'&TB_iframe=true&height=450&width=550" rel="sexylightbox" title="Ver Comprobante Devolucion">'.$id_pago.'</a></em></td>
					<td><em>'.fecha_format($fecha_pago).'</em></td>
					<td><em>$'.number_format($valor,0,",",".").'</em></td>
					<td><textarea name="glosa" cols="12" rows="1">'.$glosa.'</textarea></td>
					<td><em>'.$forma_pago_label.'</em></td>
					<td><em>'.$por_concepto.'</em></td>
					<td><em>'.$cod_user.'</em></td>
					</tr>';
				$numerador++;		
			}
			echo'<tr>
				<td colspan="4">Total Pagado</td>
				<td colspan="5">$'.number_format($acumula_valor,0,",",".").'</td>
				</tr>';
		}
		else
		{
			echo'<tr>
     		 <td colspan="7">Sin devoluciones Registrados</td></tr>';
		}
		$sql_pago->free();
		
	?>
    </tbody>
  </table>
  <br />
  <table width="100%" border="1" align="center">
<thead>
  <tr>
    <th colspan="3">Becas Asignadas</th>
  </tr>
 </thead>
 <tbody> 
 <tr>
 	<td>N</td>
    <td>beca</td>
    <td>Aporte</td>
 </tr>
 <?php
 //////////////////////////////////////////////////
//asignaciones de beneficios
//////////////////////////////////////////////////////////
	$cons_B="SELECT * FROM beneficiosEstudiantiles_asignaciones WHERE id_alumno='$id_alumno' AND id_contrato='$id_contrato'";
	
	if(DEBUG){echo"--->$cons_B<br>";}
	$sql_B=$conexion_mysqli->query($cons_B)or die("asignaciones".$conexion_mysqli->error);
	$num_becas_asignadas=$sql_B->num_rows;;
	
	$aux_cuenta_asignacion=0;
	$totalbeneficiosEstudiantiles=0;
	if($num_becas_asignadas>0)
	{
		$hay_becas=true;
		while($B=$sql_B->fetch_assoc())
		{
			$aux_cuenta_asignacion++;
			
			$B_id=$B["id"];
			$B_id_beneficio=$B["id_beneficio"];
			$B_valor=$B["valor"];
			
			$totalbeneficiosEstudiantiles+=$B_valor;

			///////////////////////////////////////////
				$cons_B2="SELECT beca_nombre FROM beneficiosEstudiantiles WHERE id='$B_id_beneficio' LIMIT 1";
				$sql_B2=$conexion_mysqli->query($cons_B2)or die("beca".$conexion_mysqli->error);
					$DB=$sql_B2->fetch_assoc();
					$B_nombre=$DB["beca_nombre"];
				$sql_B2->free();
			///////////////////////////////////////////
			
			echo'<tr>
					<td>'.$aux_cuenta_asignacion.'</td>
					<td>'.$B_nombre.'</td>
					<td align="right">'.number_format($B_valor,0,",",".").'</td>
				 </tr>';
		}
	}
	else
	{
		echo'<tr><td colspan="3">Sin Becas Asignadas...</td></tr>';
	}
	echo'<tr><td colspan="2">TOTAL</td><td align="right">'.$totalbeneficiosEstudiantiles.'</td></tr>';
?>
 </tbody>
 </table><br />
  
    </div>
  </div>
  <div id="CollapsiblePanel2" class="CollapsiblePanel">
    <div class="CollapsiblePanelTab Estilo1" tabindex="1">...</div>
    <div class="CollapsiblePanelContent">
    <table width="100%" border="0">
    <thead>
    <tr>
    	<th colspan="11">Linea Credito</th>
    </tr>
    <tr>
        <td><strong>N&deg;</strong></td>
         <td><strong>Año</strong></td>
        <td><strong>ID cuota</strong></td>
        <td><strong>Vencimiento</strong></td>
        <td><strong>Valor</strong></td>
        <td><strong>Deuda x Cuota</strong></td>
        <td><strong>Condicion</strong></td>
        <td><strong>Ultimo Pago</strong></td>
        <td><strong>Tipo</strong></td>
        <td colspan="2"><strong>Opc</strong></td>
    </tr>
       </thead>
    <tbody>
    <?php
    $cons_lineac="SELECT * FROM letras WHERE id_contrato='$id_contrato' ORDER by id";
	$sql_lineac=$conexion_mysqli->query($cons_lineac) or die("LINEA CREDITO -> ".$conexion_mysqli->error);
	$num_cuotas=$sql_lineac->num_rows;
	$total_cuota=array();
	if($num_cuotas>0)
	{
		
		$contador=1;
		while($LC=$sql_lineac->fetch_assoc())
		{
			
			$C_ano=$LC["ano"];
			$C_semestre=$LC["semestre"];
			
			$id_cuota=$LC["id"];
			$fecha_vence=$LC["fechavenc"];
			$valor=$LC["valor"];
			$deudaXletra=$LC["deudaXletra"];
			$pagada=$LC["pagada"];
			$tipo=$LC["tipo"];
			$ultimo_pago=$LC["fecha_ultimo_pago"];
			if(($ultimo_pago=="0000-00-00")or(empty($ultimo_pago)))
			{
				$ultimo_pago="---";
			}
			
			switch($pagada)
			{
				case"S":
					$pagada_label="pagada";
					break;
				case"N":
					$pagada_label="pendiente";
					break;
				case"A":
					$pagada_label="abonada";
					break;		
			}
			if(isset($total_cuota[$tipo])){$total_cuota[$tipo]+=$valor;}
			else{$total_cuota[$tipo]=$valor;}
			
			echo'<tr align="center">
				<td><em>'.$contador.'</em></td>
				<td>'.$C_ano.'</td>
				<td><em>'.$id_cuota.'</em></td>
				<td><em>'.fecha_format($fecha_vence).'</em></td>
				<td><em>$'.number_format($valor,0,",",".").'</em></td>
				<td><em>$'.number_format($deudaXletra,0,",",".").'</em></td>
				<td><em>'.$pagada_label.'</em></td>
				<td><em>'.$ultimo_pago.'</em></td>
				<td><em>'.$tipo.'</em></td>
				<td><a href="edicion_cuotas/edita/index.php?id_cuota='.base64_encode($id_cuota).'&id_alumno='.base64_encode($id_alumno).'&id_contrato='.base64_encode($id_contrato).'&semestre='.$semestre_contrato.'&year='.base64_encode($year_contrato).'"><img src="../../BAses/Images/b_edit.png" width="16" height="16" /></a></td>
				<td><a href="edicion_cuotas/borra/index.php?id_cuota='.base64_encode($id_cuota).'&id_alumno='.base64_encode($id_alumno).'&id_contrato='.base64_encode($id_contrato).'&semestre='.$semestre_contrato.'&year='.base64_encode($year_contrato).'"><img src="../../BAses/Images/b_drop.png" width="16" height="16" /></a></td>
				</tr>';
			$contador++;	
		}
	}
	else
	{
		echo'<tr>
			<td colspan="11">No hay cuotas en la Linea de Credito</td>
			</tr>';
	}
	$sql_lineac->free();
	if(isset($_GET["error"]))
	{
	$error=$_GET["error"];
	$img_ok='<img src="../../BAses/Images/ok.png" width="29" height="26" />';
	$img_error='<img src="../../BAses/Images/b_drop.png" width="16" height="16" />';
	switch($error)
	{
		case"0":
			$msj="Cuota Modificada...";
			$img=$img_ok;
			break;
		case"1":
			$msj="Imposible Modificar Cuota";
			$img=$img_error;
			break;
		case"2":
			$msj="Cuota Eliminada...";
			$img=$img_ok;
			break;
		case"3":
			$msj="Imposible Eliminar Cuota";
			$img=$img_error;
			break;		
		case"4":
			$msj="Cuota Creada...";
			$img=$img_ok;
			break;
		case"5":
			$msj="Imposible Crear Cuota";
			$img=$img_error;
			break;	
		case"P0":
			$msj="Pago Eliminado";
			$img=$img_ok;
			break;					
	}
	}
	else
	{
		$msj="";
		$img="";
	}
	?>
    <?php if(isset($total_cuota["cuota"])){?>
    <tr>
    	<td colspan="3"><strong>Total Arancel</strong></td>
        <td><strong>$ <?php echo number_format($total_cuota["cuota"],0,",",".");?></strong></td>
        <td colspan="8">&nbsp;</td>
     </tr>
     <?php }if(isset($total_cuota["matricula"])){?>
      <tr>
    	<td colspan="3"><strong>Total matricula</strong></td>
        <td><strong>$ <?php echo number_format($total_cuota["matricula"],0,",",".");?></strong></td>
        <td colspan="8">&nbsp;</td>
     </tr>
       <?php }?>
     <?php if(isset($total_cuota["examen"])){?>
    <tr>
    	<td colspan="3"><strong>Total Examen</strong></td>
        <td><strong>$ <?php echo number_format($total_cuota["examen"],0,",",".");?></strong></td>
        <td colspan="8">&nbsp;</td>
     </tr>
       <?php }?>
     <td>  
    	<td colspan="11">
        	<?php echo $msj." ".$img;?>
        </td>
    </tr>
    </tbody>
  </table>
    </div>
  </div>
</div>
<script type="text/javascript">
<!--
var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1");
var CollapsiblePanel2 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel2");
var CollapsiblePanel3 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel3");
var CollapsiblePanel4 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel4");
//-->
</script>
</body>
<?php $conexion_mysqli->close();?>
<script type="text/javascript">
//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton1", "fecha_inicio", "%Y-%m-%d");
	   cal.manageFields("boton2", "fecha_fin", "%Y-%m-%d");

    //]]>
</script>
</html>