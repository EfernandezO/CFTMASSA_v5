<?php
//-----------------------------------------//
	require("../../../../OKALIS/seguridad.php");
	require("../../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
$continuar=false;

if($_GET)
{
	if(isset($_GET["id_beca"]))
	{
		$id_beca=base64_decode($_GET["id_beca"]);
		if(is_numeric($id_beca))
		{ $continuar=true;}
	}
	
}

if(!$continuar)
{ 
	if(DEBUG){ echo"DATOS INCORRECTO no continuar<br>";}
	else{ header("location: ../index.php");}
}
?>
<html>
<head>
<title>Becas | Edicion Beca</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css">
<script language="javascript">
function VERIFICAR()
{
	document.getElementById('frm').submit();
}
</script>
</head>
<?php
if($continuar)
{
	$ARRAY_PROCEDENCIA=array("interior","exterior");
	$ARRAY_TIPO_APORTE=array("valor", "porcentaje");
	$ARRAY_CONDICION=array("activa","inactiva");
	$ARRAY_VIGENCIA=array('semestral', 'anual');
	$ARRAY_FORMA_APORTE=array("fijo", "variable");
	
	require("../../../../../funciones/conexion_v2.php");
	$cons="SELECT * FROM beneficiosEstudiantiles WHERE id='$id_beca' LIMIT 1";
	if(DEBUG){ echo"$cons<br>";}
	
	$sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$D=$sql->fetch_assoc();
		$B_familiaBeneficios=$D["familiaBeneficio"];
		$B_patrocinador=$D["patrocinador"];
		$B_procedencia=$D["procedencia"];
		$B_vigencia=$D["vigencia"];
		$B_duracion=$D["duracion"];
		$B_beca_nombre=$D["beca_nombre"];
		$B_beca_tipo_aporte=$D["beca_tipo_aporte"];
		$B_formaAporte=$D["formaAporte"];
		
		//echo"FA: $B_formaAporte<br>";
		$B_beca_aporte_valor=$D["beca_aporte_valor"];
		$B_beca_aporte_porcentaje=$D["beca_aporte_porcentaje"];
		$B_beca_condicion=$D["beca_condicion"];
	$sql->free();
	@mysql_close($conexion);
	$conexion_mysqli->close();
}
?>
<body>
<h1 id="banner">Administrador - Gesti&oacute;n de Becas</h1>
<div id="link"><br>
<a href="../index.php" class="button">Volver a Becas</a></div>
<div id="Layer4" style="position:absolute; left:5%; top:109px; width:90%; height:363px; z-index:4"> 
  <form action="edit_beca2.php" method="post" enctype="multipart/form-data" name="frm" id="frm">
    <table width="60%" border="0" align="center">
    <thead>
      <tr> 
        <th colspan="2">Caracteristicas de la Beca
          <input name="id_beca" type="hidden" id="id_beca" value="<?php echo $id_beca;?>"></th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td>Familia Beneficio</td>
        <td><select name="familiaBeneficio">
          <?php
       	for($x=0;$x<=100;$x++){
			$marcar='';
			if($B_familiaBeneficios==$x){ $marcar='$selected="selected"';}
			echo'<option value="'.$x.'" '.$marcar.'>'.$x.'</option>';
		}
		?>
        </select></td>
      </tr>
      <tr>
        <td>Patrocinador</td>
        <td><label for="patrocinador"></label>
          <input name="patrocinador" type="text" id="patrocinador" size="50" value="<?php echo $B_patrocinador;?>"></td>
      </tr>
      <tr>
        <td>Procedencia</td>
        <td>
        <select name="procedencia">
        <?php
        foreach($ARRAY_PROCEDENCIA as $n => $valor)
		{
			if($B_procedencia==$valor)
			{ echo'<option value="'.$valor.'" selected="selected">'.$valor.'</option>';}
			else
			{ echo'<option value="'.$valor.'">'.$valor.'</option>';}
		}
		?>
        </select>
        </td>
      </tr>
      <tr>
        <td>Vigencia</td>
        <td> <select name="vigencia" id="vigencia">
        <?php
        foreach($ARRAY_VIGENCIA as $n => $valor)
		{
			if($B_vigencia==$valor)
			{ echo'<option value="'.$valor.'" selected="selected">'.$valor.'</option>';}
			else
			{ echo'<option value="'.$valor.'">'.$valor.'</option>';}
		}
		?>
        </select></td>
      </tr>
      <tr>
        <td>Duracion (semestres)</td>
        <td><select name="duracion">
          <?php
       	for($x=0;$x<=6;$x++){
			$marcar='';
			if($x==$B_duracion){$marcar='selected="selected"';}
			echo'<option value="'.$x.'" '.$marcar.'>'.$x.'</option>';
			}
			
		?>
        </select></td>
      </tr>
      <tr>
        <td>Nombre</td>
        <td><input type="text" name="nombre" size="50" maxlength="50" id="nombre" value="<?php echo $B_beca_nombre;?>"></td>
      </tr>
      <tr> 
        <td width="134">Tipo Aporte</td>
        <td width="260"> 
          <select name="tipo_aporte" id="tipo_aporte">
        <?php
        foreach($ARRAY_TIPO_APORTE as $n => $valor)
		{
			if($B_beca_tipo_aporte==$valor)
			{echo'<option value="'.$valor.'" selected="selected">'.$valor.'</option>';}
			else{echo'<option value="'.$valor.'">'.$valor.'</option>';}
		}
		?>
        </select>
         </td>
      </tr>
      <tr>
        <td>Forma Aporte</td>
        <td><select name="formaAporte" id="formaAporte">
          <?php
        foreach($ARRAY_FORMA_APORTE as $n => $valor)
		{
			if($B_formaAporte==$valor)
			{echo'<option value="'.$valor.'" selected="selected">'.$valor.'</option>';}
			else{echo'<option value="'.$valor.'">'.$valor.'</option>';}
		}
		?>
        </select></td>
      </tr>
      <tr> 
        <td width="134">Aporte Valor</td>
        <td width="260"> 
          <input name="aporte_valor" type="text" id="aporte_valor" size="50" maxlength="50" value="<?php echo $B_beca_aporte_valor;?>">        </td>
      </tr>
      <tr> 
        <td width="134">Aporte Porcentaje</td>
        <td width="260"> 
          <input name="aporte_porcentaje" type="text" id="aporte_porcentaje" size="20" maxlength="20" value="<?php echo $B_beca_aporte_porcentaje;?>">        </td>
      </tr>
      <tr> 
        <td width="134">Condicion</td>
        <td width="260"> 
          <select name="condicion" id="condicion">
        <?php
        foreach($ARRAY_CONDICION as $n => $valor)
		{
			if($B_beca_condicion==$valor)
			{echo'<option value="'.$valor.'" selected="selected">'.$valor.'</option>';}
			else{echo'<option value="'.$valor.'">'.$valor.'</option>';}
		}
		?>
        </select>
         </td>
      </tr>
      <tr> 
        <td colspan="2"> 
          <input type="button" name="accion" value="Grabar" onClick="VERIFICAR();">
          <input type="reset" name="Submit2" value="Restablecer"></td>
      </tr>
      </tbody>
    </table>
  </form>
</div>
</body>
</html>