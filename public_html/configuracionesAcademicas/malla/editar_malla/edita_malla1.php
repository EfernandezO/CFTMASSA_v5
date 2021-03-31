<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->MALLAS_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Edicion de Malla</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 114px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:31px;
	z-index:2;
	left: 30%;
	top: 762px;
	text-align: center;
}
#apDiv3 {
	position:absolute;
	width:50%;
	height:31px;
	z-index:3;
	left: 5%;
	top: 735px;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	c=confirm('Seguro(a) desea Guardar los Cambios...?');
	if(c)
	{document.getElementById('frm').submit();}
}
</script>
</head>
<?php
if($_GET)
{
	$id_carrera=$_GET["id_carrera"];
	$id_ramo=$_GET["id_ramo"];
	$sede=$_GET["sede"];
}
?>
<body>
<h1 id="banner">Administrador - Malla de Carrera</h1>
<div id="link"><br />
<a href="../ver_malla.php?id_carrera=<?php echo $id_carrera?>&sede=<?php echo $sede;?>" class="button">Volver al Seleccion</a><br />
</div>
<div id="apDiv1">
<?php
if($_GET)
{
	
	////
	//datos del ramo
	require("../../../../funciones/conexion_v2.php");
	require("../../../../funciones/funciones_sistema.php");
	$array_es_asignatura=array(1=>"Si",
							   0=>"No");
		
		$cons="SELECT * FROM mallas WHERE id='$id_ramo' AND id_carrera='$id_carrera' LIMIT 1";
		$sql=mysql_query($cons)or die(mysql_error());
		$DM=mysql_fetch_assoc($sql);
			$M_ramo=$DM["ramo"];
			$M_codigo=$DM["cod"];
			$M_num_posicion=$DM["num_posicion"];
			$M_pr1=$DM["pr1"];
			$M_pr2=$DM["pr2"];
			$M_pr3=$DM["pr3"];
			$M_pr4=$DM["pr4"];
			$M_pr5=$DM["pr5"];
			$M_pr6=$DM["pr6"];
			$M_pr7=$DM["pr7"];
			$M_pr8=$DM["pr8"];
			$M_pr9=$DM["pr9"];
			$M_pr10=$DM["pr10"];
			$M_nivel=$DM["nivel"];
			$M_nombre_carrera=$DM["carrera"];
			
			$numero_horas_teoricas=$DM["horas_teoricas"];
			$numero_horas_practicas=$DM["horas_practicas"];
			
			if(empty($numero_horas_teoricas)){$numero_horas_teoricas=0;}
			if(empty($numero_horas_practicas)){ $numero_horas_practicas=0;}
		mysql_free_result($sql);	
		//------------------------------------------//
		//datos de la malla
		$ARRAY_MALLA=array(0=>"sin pre-requisito");
		$cons_malla="SELECT * FROM mallas WHERE id_carrera='$id_carrera' AND ramo<>''";
		$sql_M=mysql_query($cons_malla)or die(mysql_error());
		$num_ramos=mysql_num_rows($sql_M);
		if($num_ramos>0)
		{
			while($MT=mysql_fetch_assoc($sql_M))
			{
				$MT_codigo=$MT["cod"];
				$MT_ramo=$MT["ramo"];
				$MT_nivel=$MT["nivel"];
				$ARRAY_MALLA[$MT_codigo]=$MT_ramo." [".$MT_nivel."]";
				$MT_es_asignatura=$MT["es_asignatura"];
			}
		}
		mysql_free_result($sql_M);
	mysql_close($conexion);
}
?>
<form action="edita_malla2.php" method="post" enctype="multipart/form-data" id="frm">
<table width="60%" border="1" align="center">
<thead>
  <tr>
    <th colspan="2"><?php echo $M_nombre_carrera;?>
      <input name="id_ramo" type="hidden" id="id_ramo" value="<?php echo $id_ramo;?>" />
      <input name="id_carrera" type="hidden" id="id_carrera" value="<?php echo $id_carrera;?>" />
      <input name="sede" type="hidden" id="sede" value="<?php echo $sede;?>" /></th>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td width="34%">Cod</td>
    <td width="66%">
      <input name="codigo" type="text" id="codigo" value="<?php echo $M_codigo;?>" size="5" /></td>
  </tr>
  <tr>
    <td>Num. Posici&oacute;n</td>
    <td><label for="num_posicion"></label>
      <input name="num_posicion" type="text" id="num_posicion" value="<?php echo $M_num_posicion;?>" size="5" /></td>
  </tr>
  <tr>
    <td>Ramo</td>
    <td>
      <input name="ramo" type="text" id="ramo" value="<?php echo $M_ramo;?>" size="50"/></td>
  </tr>
  <tr>
    <td>Nivel</td>
    <td>
     <?php echo CAMPO_SELECCION("nivel","niveles_academicos",$M_nivel);?></td>
  </tr>
  <tr>
    <td>Numero Horas Teoricas</td>
    <td><label for="numero_horas_teoricas"></label>
      <input name="numero_horas_teoricas" type="text" id="numero_horas_teoricas" value="<?php echo $numero_horas_teoricas;?>" size="10" /></td>
  </tr>
  <tr>
    <td>Numero Horas Practicas</td>
    <td><label for="numero_horas_practicas"></label>
      <input name="numero_horas_practicas" type="text" id="numero_horas_practicas" value="<?php echo $numero_horas_practicas;?>" size="10"/></td>
  </tr>
  <tr>
    <td>Es Asignatura</td>
    <td><select name="es_asignatura" id="es_asignatura">
      <?php
      foreach($array_es_asignatura as $n =>$valor)
	  {
		  if($MT_es_asignatura==$n){ $check='cheacked="checked"';}
		  else{$check='';}
		  
		  echo'<option value="'.$n.'" '.$check.'>'.$valor.'</option>';
	  }
	  ?>
    </select></td>
  </tr>
  <tr>
    <td>Pre-requisito 1</td>
    <td><select name="prerequisito_1" id="prerequisito_1">
      <?php 
	  foreach($ARRAY_MALLA as $nM => $valorM)
	  {
		  if($M_pr1==$nM)
		  { echo'<option value="'.$nM.'" selected="selected">'.$nM.'-'.$valorM.'</option>';}
		  else
		  { echo'<option value="'.$nM.'">'.$nM.'-'.$valorM.'</option>';}
	  }
	  ?>
    </select></td>
    </tr>
  <tr>
    <td>Pre-requisito 2</td>
    <td><select name="prerequisito_2" id="prerequisito_2">
      <?php 
	  foreach($ARRAY_MALLA as $nM => $valorM)
	  {
		  if($M_pr2==$nM)
		  { echo'<option value="'.$nM.'" selected="selected">'.$nM.'-'.$valorM.'</option>';}
		  else
		  { echo'<option value="'.$nM.'">'.$nM.'-'.$valorM.'</option>';}
	  }
	  ?>
    </select></td>
    </tr>
  <tr>
    <td>Pre-requisito 3</td>
    <td><select name="prerequisito_3" id="prerequisito_3">
      <?php 
	  foreach($ARRAY_MALLA as $nM => $valorM)
	  {
		  if($M_pr3==$nM)
		  { echo'<option value="'.$nM.'" selected="selected">'.$nM.'-'.$valorM.'</option>';}
		  else
		  { echo'<option value="'.$nM.'">'.$nM.'-'.$valorM.'</option>';}
	  }
	  ?>
    </select></td>
  </tr>
  <tr>
    <td>Pre-requisito 4</td>
    <td><select name="prerequisito_4" id="prerequisito_4">
      <?php 
	  foreach($ARRAY_MALLA as $nM => $valorM)
	  {
		  if($M_pr4==$nM)
		  { echo'<option value="'.$nM.'" selected="selected">'.$nM.'-'.$valorM.'</option>';}
		  else
		  { echo'<option value="'.$nM.'">'.$nM.'-'.$valorM.'</option>';}
	  }
	  ?>
    </select></td>
  </tr>
  <tr>
    <td>Pre-requisito 5</td>
    <td><select name="prerequisito_5" id="prerequisito_5">
      <?php 
	  foreach($ARRAY_MALLA as $nM => $valorM)
	  {
		  if($M_pr5==$nM)
		  { echo'<option value="'.$nM.'" selected="selected">'.$nM.'-'.$valorM.'</option>';}
		  else
		  { echo'<option value="'.$nM.'">'.$nM.'-'.$valorM.'</option>';}
	  }
	  ?>
    </select></td>
  </tr>
  <tr>
    <td>Pre-requisito 6</td>
    <td><select name="prerequisito_6" id="prerequisito_6">
      <?php 
	  foreach($ARRAY_MALLA as $nM => $valorM)
	  {
		  if($M_pr6==$nM)
		  { echo'<option value="'.$nM.'" selected="selected">'.$nM.'-'.$valorM.'</option>';}
		  else
		  { echo'<option value="'.$nM.'">'.$nM.'-'.$valorM.'</option>';}
	  }
	  ?>
    </select></td>
  </tr>
  <tr>
    <td>Pre-requisito 7</td>
    <td><select name="prerequisito_7" id="prerequisito_7">
      <?php 
	  foreach($ARRAY_MALLA as $nM => $valorM)
	  {
		  if($M_pr7==$nM)
		  { echo'<option value="'.$nM.'" selected="selected">'.$nM.'-'.$valorM.'</option>';}
		  else
		  { echo'<option value="'.$nM.'">'.$nM.'-'.$valorM.'</option>';}
	  }
	  ?>
    </select></td>
  </tr>
  <tr>
    <td>Pre-requisito 8</td>
    <td><select name="prerequisito_8" id="prerequisito_8">
      <?php 
	  foreach($ARRAY_MALLA as $nM => $valorM)
	  {
		  if($M_pr8==$nM)
		  { echo'<option value="'.$nM.'" selected="selected">'.$nM.'-'.$valorM.'</option>';}
		  else
		  { echo'<option value="'.$nM.'">'.$nM.'-'.$valorM.'</option>';}
	  }
	  ?>
    </select></td>
  </tr>
  <tr>
    <td>Pre-requisito 9</td>
    <td><select name="prerequisito_9" id="prerequisito_9">
      <?php 
	  foreach($ARRAY_MALLA as $nM => $valorM)
	  {
		  if($M_pr9==$nM)
		  { echo'<option value="'.$nM.'" selected="selected">'.$nM.'-'.$valorM.'</option>';}
		  else
		  { echo'<option value="'.$nM.'">'.$nM.'-'.$valorM.'</option>';}
	  }
	  ?>
    </select></td>
  </tr>
  <tr>
    <td>Pre-requisito 10</td>
    <td><select name="prerequisito_10" id="prerequisito_10">
      <?php 
	  foreach($ARRAY_MALLA as $nM => $valorM)
	  {
		  if($M_pr10==$nM)
		  { echo'<option value="'.$nM.'" selected="selected">'.$nM.'-'.$valorM.'</option>';}
		  else
		  { echo'<option value="'.$nM.'">'.$nM.'-'.$valorM.'</option>';}
	  }
	  ?>
    </select></td>
  </tr>
  </tbody>    
</table>
</form>
</div>
<div id="apDiv2"><a href="#" class="button_G" onclick="CONFIRMAR();">Guardar Cambios</a></div>
<div id="apDiv3">
<?php
if(isset($_GET["error"]))
{
	$error=$_GET["error"];
	$msj="";
	switch($error)
	{
		case"M1":
			$msj="Codigo para el Ramo invalido, debe ser numerico y no ser utilizado por otro ramo...";
			break;
		case"M2":
			$msj="Ramo Incorrecto, no debe estar en blanco ni en uso por otro ramo...";
			break;
	}
	
	echo"$msj";
}
?>
</div>
</body>
</html>