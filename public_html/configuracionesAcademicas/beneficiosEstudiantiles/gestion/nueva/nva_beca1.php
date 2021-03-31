<?php
//-----------------------------------------//
	require("../../../../OKALIS/seguridad.php");
	require("../../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
$ARRAY_PROCEDENCIA=array("interior","exterior");
$ARRAY_TIPO_APORTE=array("valor", "porcentaje");
$ARRAY_FORMA_APORTE=array("fijo", "variable");
$ARRAY_CONDICION=array("activa","inactiva");
$ARRAY_VIGENCIA=array('semestral', 'anual');
?>
<html>
<head>
<title>Becas | Nueva Beca</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css">
<script language="javascript">
function VERIFICAR()
{
	continuar=true;
	nombre_beca=document.getElementById('nombre').value;
	patrocinador=document.getElementById('patrocinador').value;
	
	if((nombre_beca=="")||(nombre_beca==" "))
	{
		alert('ingrese Nombre para Beca');
		continuar=false;
	}
	if((patrocinador=="")||(patrocinador==" "))
	{
		alert('ingrese Patrocinador para Beca');
		continuar=false;
	}
	if(continuar)
	{
		c=confirm('Seguro(a) Desea Crear esta Beca...?');
		if(c){document.getElementById('frm').submit();}}
}
</script>
</head>
<body>
<h1 id="banner">Administrador - Agrega Beneficio</h1>
<div id="link"><br>
<a href="../index.php" class="button">Volver a Becas</a></div>
<div id="Layer4" style="position:absolute; left:5%; top:109px; width:90%; height:339px; z-index:4"> 
  <form action="nva_beca2.php" method="post" enctype="multipart/form-data" name="frm" id="frm">
    <table width="60%" border="0" align="center">
    <thead>
      <tr> 
        <th colspan="2">Caracteristicas de la Beca</th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td>Familia de Beneficios</td>
        <td> <select name="familiaBeneficio">
          <?php
       	for($x=0;$x<=100;$x++){echo'<option value="'.$x.'">'.$x.'</option>';}
		?>
        </select></td>
      </tr>
      <tr>
        <td>Patrocinador</td>
        <td><label for="patrocinador"></label>
          <input name="patrocinador" type="text" id="patrocinador" size="50"></td>
      </tr>
      <tr>
        <td>Procedencia</td>
        <td>
        <select name="procedencia">
        <?php
        foreach($ARRAY_PROCEDENCIA as $n => $valor)
		{
			echo'<option value="'.$valor.'">'.$valor.'</option>';
		}
		?>
        </select>
        </td>
      </tr>
      <tr>
        <td>Vigencia</td>
        <td> <select name="vigencia">
        <?php
        foreach($ARRAY_VIGENCIA as $n => $valor)
		{echo'<option value="'.$valor.'">'.$valor.'</option>';}
		?>
        </select></td>
      </tr>
      <tr>
        <td>Duracion (semestres)</td>
        <td><select name="duracion">
          <?php
       	for($x=0;$x<=6;$x++){echo'<option value="'.$x.'">'.$x.'</option>';}
		?>
        </select></td>
      </tr>
      <tr>
        <td>Nombre</td>
        <td><input type="text" name="nombre" size="50" maxlength="50" id="nombre"></td>
      </tr>
      <tr> 
        <td width="134">Tipo Aporte</td>
        <td width="260"> 
          <select name="tipo_aporte" id="tipo_aporte">
        <?php
        foreach($ARRAY_TIPO_APORTE as $n => $valor)
		{
			echo'<option value="'.$valor.'">'.$valor.'</option>';
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
		{echo'<option value="'.$valor.'">'.$valor.'</option>';}
		?>
        </select></td>
      </tr>
      <tr> 
        <td width="134">Aporte Valor</td>
        <td width="260"> 
          <input name="aporte_valor" type="text" id="aporte_valor" value="0" size="50" maxlength="50">        </td>
      </tr>
      <tr> 
        <td width="134">Aporte Porcentaje</td>
        <td width="260"> 
          <input name="aporte_porcentaje" type="text" id="aporte_porcentaje" value="0" size="20" maxlength="20">        </td>
      </tr>
      <tr> 
        <td width="134">Condicion</td>
        <td width="260"> 
          <select name="condicion" id="condicion">
        <?php
        foreach($ARRAY_CONDICION as $n => $valor)
		{
			echo'<option value="'.$valor.'">'.$valor.'</option>';
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
