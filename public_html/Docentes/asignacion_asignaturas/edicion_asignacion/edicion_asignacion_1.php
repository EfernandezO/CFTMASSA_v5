<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("asignaciones_v1_EDICION");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("proceso_serverX.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCAR_ASIGNATURAS");
$xajax->register(XAJAX_FUNCTION,"ACTUALIZA_TOTAL");
//------------------------------------------------------------//
if(isset($_GET["AS_id"]))
{
	require("../../../../funciones/conexion_v2.php");
	$AS_id=base64_decode($_GET["AS_id"]);
	if(is_numeric($AS_id)){ $continuar=true;}
	else{ $continuar=false;}
	//-----------------------------------------------------//
	$cons_AS="SELECT * FROM toma_ramo_docente WHERE id='$AS_id' LIMIT 1";
	$sqli_AS=$conexion_mysqli->query($cons_AS);
	$AS=$sqli_AS->fetch_assoc();
		$AS_id_carrera=$AS["id_carrera"];
		$AS_cod_asignatura=$AS["cod_asignatura"];
		$AS_jornada=$AS["jornada"];
		$AS_grupo=$AS["grupo"];
		$AS_numero_horas=$AS["numero_horas"];
		$AS_valor_hora=$AS["valor_hora"];
		$AS_sede=$AS["sede"];
		$AS_total=$AS["total"];
		$AS_numero_cuotas=$AS["numero_cuotas"];
		$AS_condicion=$AS["condicion"];
		$AS_semestre=$AS["semestre"];
		$AS_year=$AS["year"];
	$sqli_AS->free();
	//-----------------------------------------------------//
	$array_jornada=array("D"=>"Diurno","V"=>"Vespertino");
	$ARRAY_ASIGNATURAS=array();
	$array_condiciones=array("pendiente", "cancelada");
	 $cons="SELECT * FROM mallas WHERE id_carrera='$AS_id_carrera' AND ramo<>'' ORDER by num_posicion, cod";
		 $sql=$conexion_mysqli->query($cons)or die("asig 1 ".$conexion_mysqli->error);
		 $num_asignaturas=$sql->num_rows;
		 if($num_asignaturas>0)
		 {
			 while($A=$sql->fetch_assoc())
			 {
				 $ASIG_cod=$A["cod"];
				 $ASIG_ramo=$A["ramo"];
				 $ASIG_nivel=$A["nivel"];
				$ARRAY_ASIGNATURAS[$ASIG_cod]='['.$ASIG_nivel.'] '.$ASIG_ramo;
			 }
			$ARRAY_ASIGNATURAS[0]="[00] *JEFATURA";
		    $ARRAY_ASIGNATURAS[99]="[99] *Toma Examen";
		    $ARRAY_ASIGNATURAS[98]="[98] *Revision Informe";
		    $ARRAY_ASIGNATURAS[97]="[97] *Supervision de Practica";
		    $ARRAY_ASIGNATURAS[96]="[96] *Administracion Asignatura";
		    $ARRAY_ASIGNATURAS[95]="[95] *Taller Complementario";
		 }
		
}
else
{ $continuar=false;}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<title>Edicion Asignacion</title>
<?php $xajax->printJavascript(); ?> 
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 45px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:32px;
	z-index:2;
	left: 30%;
	top: 497px;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	c=confirm('Seguro(a) Desea Realizar estas modificaciones..??');
	if(c){ document.getElementById('frm').submit();}
}
</script>
</head>

<body onload="xajax_BUSCAR_ASIGNATURAS(<?php echo $AS_id_carrera;?>, <?php echo $AS_cod_asignatura;?>); return false;">
<h1 id="banner">Administrador -Asignacion de Ramos Docente V. 1.0</h1>

<?php if($continuar){ ?>
<div id="apDiv1">
<form action="edicion_asignacion_2.php" method="post" id="frm">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="3">Modificacion Asignacion
        <input name="AS_id" type="hidden" id="AS_id" value="<?php echo $AS_id;?>" /></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="31%">Sede</td>
      <td width="69%" colspan="2">
	  <?php
	  require("../../../../funciones/funciones_sistema.php");
	  echo CAMPO_SELECCION("sede","sede",$AS_sede);
	  ?>
      </td>
    </tr>
    <tr>
      <td>Carrera</td>
      <td colspan="2"> <select name="id_carrera" id="id_carrera" onchange="xajax_BUSCAR_ASIGNATURAS(this.value, <?php echo $AS_cod_asignatura;?>); return false;">
	  <?php   
   $res="SELECT id, carrera FROM carrera";
   $result=$conexion_mysqli->query($res);
   while($row = $result->fetch_assoc()) 
   {
	    $id_carrera=$row["id"];
    	$nomcar=$row["carrera"];
		if($id_carrera==$AS_id_carrera)
		{echo'<option value="'.$id_carrera.'" selected="selected">'.$id_carrera.'_'.$nomcar.'</option>';}
		else{echo'<option value="'.$id_carrera.'">'.$id_carrera.'_'.$nomcar.'</option>';}
   }
  $result->free();
	 ?>
        </select></td>
    </tr>
    <tr>
      <td>Asignatura</td>
      <td colspan="2"><div id="div_asignaturas">
        <select name="asignatura" id="asignatura">
        </select>
      </div></td>
    </tr>
    <tr>
      <td>Jornada</td>
      <td colspan="2"><select name="jornada" id="jornada">
       <?php
       foreach($array_jornada as $n=>$valor)
	   {
		   if($AS_jornada==$n)
		   {echo'<option value="'.$n.'" selected="selected">'.$valor.'</option>';}
		   else{echo'<option value="'.$n.'">'.$valor.'</option>';}
	   }
	   ?>
      </select></td>
    </tr>
    <tr>
      <td>Grupo</td>
      <td colspan="2"><select name="grupo" id="grupo">
          <?php 
		foreach(range('A', 'Z') as $letra)
		{
			if($AS_grupo==$letra)
			{echo'<option value="'.$letra.'" selected="selected">'.$letra.'</option>';}
			else{echo'<option value="'.$letra.'">'.$letra.'</option>';}
		}
		?>
        </select></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td>Periodo</td>
      <td><?php echo CAMPO_SELECCION("asignacion_semestre","semestre",$AS_semestre,false); ?></td>
      <td><?php echo CAMPO_SELECCION("asignacion_year","year",$AS_year,false); ?></td>
    </tr>
    <tr>
      <td>Valor Hora</td>
      <td colspan="2"><label for="valor_hora"></label>
        <input name="valor_hora" type="text" id="valor_hora" value="<?php echo $AS_valor_hora;?>" onblur="xajax_ACTUALIZA_TOTAL(document.getElementById('numero_horas').value, document.getElementById('valor_hora').value, document.getElementById('total').value); return false;"/> </td>
    </tr>
    <tr>
      <td>Numero Horas</td>
      <td colspan="2"><label for="numero_horas"></label>
        <input name="numero_horas" type="text" id="numero_horas" value="<?php echo $AS_numero_horas;?>" />
        <a href="#" class="button_R"  onclick="xajax_ACTUALIZA_TOTAL(document.getElementById('numero_horas').value, document.getElementById('valor_hora').value, document.getElementById('total').value); return false;">calcular</a></td>
    </tr>
    <tr>
      <td>Total</td>
      <td colspan="2"><label for="total"></label>
        <input name="total" type="text" id="total" value="<?php echo $AS_total;?>" /> 
        <a href="#" class="button_R" onclick="xajax_ACTUALIZA_TOTAL(document.getElementById('numero_horas').value, document.getElementById('valor_hora').value, document.getElementById('total').value); return false;">calcular</a></td>
    </tr>
    <tr>
      <td>Estado</td>
      <td colspan="2">
        <select name="estado" id="estado">
        <?php
        foreach($array_condiciones as $n => $valor)
		{
			if($AS_condicion==$valor)
			{ echo'<option value="'.$valor.'" selected="selected">'.$valor.'</option>';}
			else
			{  echo'<option value="'.$valor.'">'.$valor.'</option>';}
		}
		?>
        </select></td>
    </tr>
    <tr>
      <td>Numero Cuotas</td>
      <td colspan="2"><label for="numero_cuotas"></label>
        <select name="numero_cuotas" id="numero_cuotas">
        <?php for($x=1;$x<13;$x++)
		{
			if($AS_numero_cuotas==$x)
			{echo'<option value="'.$x.'" selected="selected">'.$x.'</option>';}
			else{echo'<option value="'.$x.'">'.$x.'</option>';}
		}?>
        </select></td>
    </tr>
    </tbody>
  </table>
  </form>
  </div>
  <div id="apDiv2"><a href="#" class="button_G">Modificar</a></div>
 <?php } $conexion_mysqli->close();?> 

<div id="apDiv2"><a href="#" class="button_G" onclick="CONFIRMAR();">Modificar</a></div>
</body>
</html>