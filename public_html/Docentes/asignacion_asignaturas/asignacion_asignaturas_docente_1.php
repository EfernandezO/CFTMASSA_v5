<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("asignaciones_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("proceso_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCAR_ASIGNATURAS");
$xajax->register(XAJAX_FUNCTION,"BUSCA_ASIGNACIONES");
$xajax->register(XAJAX_FUNCTION,"GRABA_ASIGNACIONES");
$xajax->register(XAJAX_FUNCTION,"ACTUALIZA_TOTAL");
$xajax->register(XAJAX_FUNCTION,"horasPrograma");
//------------------------------------------------------------//
$sede_usuario_actual=$_SESSION["USUARIO"]["sede"];
$privilegio=$_SESSION["USUARIO"]["privilegio"];

switch($privilegio)
{
	case"admi_total":
		$filtrar_X_sede=false;
		break;
	default:
		$filtrar_X_sede=true;	
}
//-----------------------------------------------------------//
if(isset($_GET["fid"]))
{
	$fid=base64_decode($_GET["fid"]);
	$continuar=true;
}
else
{ $continuar=false;}

if($continuar)
{
		$year_actual=date("Y");
  $array_semeste=array(1,2);
   $mes_actual=date("m");
   
   if($mes_actual>=8)///utilizo agosto para inicio 2 semeste
   { $semeste_actual=2;}
   else{ $semeste_actual=1;}
   ///////////////////////////////////////////////
   include("../../../funciones/conexion_v2.php");
   	//datos del docente
	$cons_D="SELECT * FROM personal WHERE id='$fid' LIMIT 1";
	$sql_D=$conexion_mysqli->query($cons_D)or die($conexion_mysqli->error);
		$D=$sql_D->fetch_assoc();
		$D_nombre=$D["nombre"];
		$D_apellido=$D["apellido_P"]." ".$D["apellido_M"];
		$D_rut=$D["rut"];
	$sql_D->free();	
	
	///valor hora docente
	$cons_VH="SELECT valor_hora FROM toma_ramo_docente WHERE id_funcionario='$fid' ORDER by id DESC";
	$sqli_VH=$conexion_mysqli->query($cons_VH);
		$VHD=$sqli_VH->fetch_assoc();
		$aux_valor_hora_previo=$VHD["valor_hora"];

		if(empty($aux_valor_hora_previo)){$aux_valor_hora_previo=0;}
		$aux_valor_hora_previo=number_format($aux_valor_hora_previo,0,"","");
	$sqli_VH->free();	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Asignacion Ramos - Docente</title>
<?php $xajax->printJavascript(); ?> 
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:45%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 79px;
}
#apDiv2 {
	position:absolute;
	width:45%;
	height:97px;
	z-index:2;
	left: 5%;
	top: 212px;
}
#div_asignaciones {
	position:absolute;
	width:90%;
	height:69px;
	z-index:3;
	left: 5%;
	top: 522px;
}
#div_boton {
	position:absolute;
	width:40%;
	height:38px;
	z-index:4;
	left: 55%;
	top: 259px;
	text-align: center;
}
</style>
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
<script language="javascript">
function ELIMINAR(id_asignacion, id_funcionario)
{
	c=confirm('zSeguro(a) Desea Eliminar esta Asignacion?');
	if(c)
	{
		window.location="elimina_asignacion/elimina_asignacion.php?id_asignacion="+id_asignacion+"&id_funcionario="+id_funcionario;
	}
}
</script>
</head>

<body onload="xajax_BUSCA_ASIGNACIONES(<?php echo $semeste_actual;?>, <?php echo $year_actual;?>, '<?php echo $fid;?>'); return false;">
<h1 id="banner">Administrador -Asignacion de Ramos Docente V. 1.0</h1>
<div id="link"><br>
<a href="../lista_funcionarios.php" class="button">Volver al Menu</a><br /><br />

<a href="../edicion_A/mdocente.php?id_fun=<?php echo base64_encode($fid);?>" class="button" >Modifica Datos</a>
</div>
<?php if($continuar){?>
<div id="apDiv1">

  <table width="100%" border="0" align="left">
    <thead>
      <tr>
        <th colspan="4">Datos Docente</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td width="99"><strong>Docente</strong></td>
        <td colspan="3"><?php echo $D_nombre." ".$D_apellido; ?>
        <input name="id_docente" type="hidden" id="id_docente" value="<?php echo $fid;?>" /></td>
      </tr>
      <tr>
        <td>Semestre</td>
        <td width="112"><select name="semestre" id="semestre" onchange="xajax_BUSCA_ASIGNACIONES(this.value, document.getElementById('year').value, '<?php echo $fid;?>'); return false;">
          <?php
            foreach($array_semeste as $n=>$valor)
			{
				if($valor==$semeste_actual)
				{ $seleccion='selected="selected"';}
				else
				{ $seleccion="";}
				echo'<option value="'.$valor.'" '.$seleccion.'>'.$valor.'</option>';
			}
			?>
        </select></td>
        <td width="144">a&ntilde;o</td>
        <td width="70">
        <select name="year" id="year" onchange="xajax_BUSCA_ASIGNACIONES(document.getElementById('semestre').value, this.value, '<?php echo $fid;?>'); return false;">
        <?php
	  	$anos_anteriores=10;
		$anos_siguientes=1;
	  
		
		$ano_ini=$year_actual-$anos_anteriores;
		$ano_fin=$year_actual+$anos_siguientes;
		
		for($a=$ano_ini;$a<=$ano_fin;$a++)
		{
			if($a==$year_actual)
			{echo'<option value="'.$a.'" selected="selected">'.$a.'</option>';	}
			else
			{echo'<option value="'.$a.'">'.$a.'</option>';}	
		}
	  ?>
        </select>
        </td>
      </tr>
      <tr>
        <td>Tomas Previas</td>
        <td colspan="3">
		<?php
      	$cons_TR="SELECT `semestre`, `year` FROM `toma_ramo_docente` WHERE id_funcionario='$fid' GROUP BY `semestre`, `year` ORDER by `year`, `semestre`";
		$sql_TR=$conexion_mysqli->query($cons_TR)or die($conexion_mysqli->error);
		$num_periodos=$sql_TR->num_rows;
		
		$msj="";
		if($num_periodos>0)
		{
			$msj='<a href="ver_asignaciones/ver_asignacionesFull.php?id_funcionario='.base64_encode($fid).'" class="button" target="_blank">Full</a>&nbsp';
			
			while($PTR=$sql_TR->fetch_assoc())
			{
				$periodo_semestre=$PTR["semestre"];
				$periodo_year=$PTR["year"];
				
				$msj.='<a href="ver_asignaciones/ver_asignaciones.php?id_funcionario='.base64_encode($fid).'&semestre='.base64_encode($periodo_semestre).'&year='.base64_encode($periodo_year).'" class="button_R" target="_blank">'.$periodo_semestre.'-'.$periodo_year.'</a>&nbsp;';
			}
		}
		else
		{ $msj="Sin Registros...";}
		$sql_TR->free();
		echo $msj;
	  ?>
		</td>
      </tr>
    </tbody>
  </table>
</div>
<div id="apDiv2">
  <table width="100%" border="1" align="left">
  <thead>
  <tr>
  	<th colspan="4">Nueva Asignacion</th>
   </tr>
  <tr>
    <td>N. horas pedagogicas (semestrales)</td>
    <td width="25%"><label for="numero_horas"></label>
      <input name="numero_horas" type="text" id="numero_horas" value="0" size="5" onblur="xajax_ACTUALIZA_TOTAL(document.getElementById('numero_horas').value, document.getElementById('valor_hora').value, document.getElementById('total').value); return false;"/></td>
    <td width="12%">TOTAL</td>
    <td width="12%"><label for="total"></label>
      <input name="total" type="text" id="total" value="0" size="15" onblur="xajax_ACTUALIZA_TOTAL(document.getElementById('numero_horas').value, document.getElementById('valor_hora').value, document.getElementById('total').value); return false;"/></td>
  </tr> 
  </thead>
  <tbody>
    <tr>
      <td>Valor Hora</td>
      <td><label for="valor_hora"></label>
        <input name="valor_hora" type="text" id="valor_hora" value="<?php echo $aux_valor_hora_previo;?>" size="11" onblur="xajax_ACTUALIZA_TOTAL(document.getElementById('numero_horas').value, document.getElementById('valor_hora').value, document.getElementById('total').value); return false;"/></td>
      <td width="12%">N. Cuotas</td>
      <td width="12%"><label for="numero_cuotas"></label>
        <select name="numero_cuotas" id="numero_cuotas">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5" selected="selected">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
          <option value="8">8</option>
          <option value="9">9</option>
          <option value="10">10</option>
          <option value="11">11</option>
          <option value="12">12</option>
        </select></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td>Sede</td>
      <td colspan="3"><?php
	  require("../../../funciones/funcion.php");
	  echo selector_sede(); 
	  ?></td>
    </tr>
    <tr>
      <td width="51%">Carrera</td>
      <td colspan="3">
	  <select name="carrera" id="carrera" onchange="xajax_BUSCAR_ASIGNATURAS(this.value); return false;">
      <option value="0_seleccione" selected="selected">0_Seleccione</option>
	  <?php   
   $res="SELECT id, carrera FROM carrera";
   $result=$conexion_mysqli->query($res);
   while($row = $result->fetch_assoc()) 
   {
	    $id_carrera=$row["id"];
    	$nomcar=$row["carrera"];
		echo'<option value="'.$id_carrera.'_'.$nomcar.'">'.$id_carrera.'_'.$nomcar.'</option>';
   }
   $result->free(); 
	 ?>
        </select>
      </td>
    </tr>
    <tr>
      <td>Asignatura</td>
      <td colspan="3"><div id="div_asignaturas">...
        <input name="asignatura" type="hidden" id="asignatura" value="0" />
      </div></td>
    </tr>
    <tr>
      <td>Jornada</td>
      <td colspan="3"><select name="jornada" id="jornada">
        <option value="D">Diurno</option>
        <option value="V">Vespertino</option>
        <option value="A">Ambas</option>
      </select></td>
    </tr>
    <tr>
      <td>Grupo</td>
      <td colspan="3"><label for="grupo"></label>
        <select name="grupo" id="grupo">
          <?php 
		foreach(range('A', 'Z') as $letra)
		{echo'<option value="'.$letra.'">'.$letra.'</option>';}
		?>
        </select></td>
    </tr>
    </tbody>
  </table>
</div>
<div id="div_asignaciones">
  
</div>
<?php }  $conexion_mysqli->close();?>
<div id="div_boton">
<?php
if(isset($_GET["error"]))
{
	$error=$_GET["error"];
	$img_ok='<img src="../../BAses/Images/ok.png" width="29" height="26" />';
	$img_error='<img src="../../BAses/Images/b_drop.png" width="16" height="16" />';
	
	switch($error)
	{
		case"A0":
			$img=$img_ok;
			$msj="Asignacion Agregada...";
			break;
		case"A1":
			$img=$img_ok;
			$msj="Asignacion Eliminada...";
			break;
		case"A2":
			$img=$img_error;
			$msj="Falla al Eliminar Asignacion...";
			break;		
		default:
			$img="";
			$msj="";	
	}
	echo $img.$msj;
}
?>
</div>
</body>
</html>