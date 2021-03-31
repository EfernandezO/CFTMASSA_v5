<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->setDisplayErrors(false);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->LiceosProcedencia_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//	
$ARRAY_DEPENDENCIA=array("administracion delegada","particular subvencionado", "particular", "municipal");

?>
<html>
<head>
<title>Liceos | Agregar</title>
<?php include("../../../../../funciones/codeificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css">
<script language="javascript">
function VERIFICAR()
{
	continuar=true;
	nombreEstablecimiento=document.getElementById('nombreEstablecimiento').value;
	rbd=document.getElementById('rbd').value;
	
	
	if((nombreEstablecimiento=="")||(nombreEstablecimiento==" "))
	{
		alert('ingrese Nombre para Beca');
		continuar=false;
	}
	if((rbd=="")||(rbd==" "))
	{
		alert('ingrese rbd del liceo');
		continuar=false;
	}
	if(continuar)
	{
		c=confirm('Seguro(a) Desea registrar este Liceo...?');
		if(c){document.getElementById('frm').submit();}}
}
</script>
</head>
<body>
<h1 id="banner">Administrador - Agrega Liceo</h1>
<div id="link"><br>
<a href="../index.php" class="button">Volver a Liceos procedencia</a></div>
<div id="Layer4" style="position:absolute; left:5%; top:109px; width:90%; height:339px; z-index:4"> 
  <form action="agregar2.php" method="post" enctype="multipart/form-data" name="frm" id="frm">
    <table width="60%" border="0" align="center">
    <thead>
      <tr> 
        <th colspan="2">Caracteristicas del Establecimiento</th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td width="134">Region</td>
        <td width="260"> <select name="region">
          <?php
       	for($x=1;$x<=18;$x++){echo'<option value="'.$x.'">'.$x.'</option>';}
		?>
        </select></td>
      </tr>
      <tr>
        <td>Comuna</td>
        <td><label for="comuna"></label>
          <input name="comuna" type="text" id="comuna"></td>
      </tr>
      <tr>
        <td>Nombre Establecimiento</td>
        <td><label for="nombreEstablecimiento"></label>
          <input type="text" name="nombreEstablecimiento" id="nombreEstablecimiento"></td>
      </tr>
      <tr>
        <td>dependencia</td>
        <td> <select name="dependencia">
        <?php
        foreach($ARRAY_DEPENDENCIA as $n => $valor)
		{echo'<option value="'.$valor.'">'.$valor.'</option>';}
		?>
        </select></td>
      </tr>
      <tr>
        <td>RBD</td>
        <td><input type="text" name="rbd" id="rbd"></td>
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