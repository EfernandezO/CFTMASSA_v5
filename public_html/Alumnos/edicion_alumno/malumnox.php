<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="ALUMNO";
	OKALIS($lista_invitados);
	define("DEBUG",false);
//-----------------------------------------//	
?>
<html>
<head>
<title>Modifica datos Alumnos</title>
<?php include("../../../funciones/codificacion.php"); ?>
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
}
.Estilo1 {color: #0080C0}
a:link {
	text-decoration: none;
	color: #006699;
}
a:visited {
	text-decoration: none;
	color: #006699;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #006699;
}
#Layer1 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:1;
	left: 71px;
	top: 70px;
}
#link {
	text-align: right;
	padding-right: 10px;
}
-->
</style>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<style type="text/css">
<!--
.Estilo2 {
	font-size: 10px;
	font-weight: bold;
}
-->
</style>
<script language="javascript">
function valida()
{
	nombre=document.getElementById('nombre').value;
	apellido_P=document.getElementById('apellido_P').value;
	apellido_M=document.getElementById('apellido_M').value;
	clave=document.getElementById('passs').value;
	direccion=document.getElementById('direccion').value;
	ciudad=document.getElementById('ciudad').value;
	liceo=document.getElementById('liceo').value;
	continuar=true;
	
	if((nombre=="")||(nombre==" "))
	{
		continuar=false;
		alert('ingrese su Nombre');
	}
	if((apellido_P=="")||(apellido_P==" "))
	{
		continuar=false;
		alert('ingrese su Apellido Paterno');
	}
	if((apellido_M=="")||(apellido_M==" "))
	{
		continuar=false;
		alert('ingrese su Apellido Materno');
	}
	if((clave=="")||(clave==" "))
	{
		continuar=false;
		alert('ingrese su clave de acceso');
	}
	if((direccion=="")||(direccion==" "))
	{
		continuar=false;
		alert('ingrese su Direccion');
	}
	if((ciudad=="")||(ciudad==" "))
	{
		continuar=false;
		alert('ingrese la Ciudad donde vive');
	}
	if((liceo=="")||(liceo==" "))
	{
		continuar=false;
		alert('ingrese su Liceo de Procedencia');
	}
	if(continuar)
	{
		c=confirm('Confirma que los Datos Son Correctos \n y desea Guardarlos?');
		if(c)
		{
			document.frm.submit();
		}
	}	
}
</script>
</head>

<body>
<h1 id="banner">Alumno - Mis Datos</h1>
<div id="Layer7" style="position:absolute; left:5%; top:101px; width:90%; height:455px; z-index:2;"> 
  <form action="grabaralumnoactx.php" method="post" enctype="multipart/form-data" name="frm" id="frm">
 <?php
 include("../../../funciones/conexion.php");
 $id_alumno=$_SESSION["USUARIO"]["id"];
//echo $_SESSION[Arut];
 $con="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";
 $R = mysql_query($con)or die(mysql_error());
//echo"$con<br>";
//--------------------------------------------------//
 	 include("../../../funciones/VX.php");
	 //cambio estado_conexion USER-----------
	 CAMBIA_ESTADO_CONEXION_ALUMNO($id_alumno, "ON");
	//-----------------------------------------------//
while($A=mysql_fetch_array($R))
{
 
    $id1=$A["id"];
    $rut=$A["rut"];
    $apellido_P=$A["apellido_P"];
	$apellido_M=$A["apellido_M"];
    $nombre=$A["nombre"];
    $carrera=$A["carrera"];
    $direccion=$A["direccion"];
    $ciudad=$A["ciudad"];
    $fono=$A["fono"];
    $jornada=$A["jornada"];
    $liceo=$A["liceo"];
    $apoderado=$A["apoderado"];
    $fonoa=$A["fonoa"];
    $clave=$A["clave"];
    $email=$A["email"];
    $fnac=$A["fnac"];
    $situacion=$A["situacion"];
    $sede=$A["sede"];
    $ingreso=$A["ingreso"];
   
}
if($ingreso=="")
{$ingreso="NO Registrado";}
     
?> 
    <table width="90%" align="center">
    <thead>
    <tr>
    	<th colspan="2">Datos Personales</th>
    </tr>
    </thead>
    <tbody>
      <tr>
        <td><span class="Estilo2">Rut</span></td>
        <td><?php echo $rut;?>
          <input type="hidden" id="rut" name="rut" value="<?php echo $rut; ?>"></td>
      </tr>
      <tr> 
        <td width="158"><span class="Estilo2">Nombres</span></td>
        <td width="354"> 
          <input type="text" id="nombre" name="nombre" size="40" maxlength="40" value="<?php echo $nombre;?>">
          <input type="hidden" name="id" value="<?php echo $id1;?>">        </td>
      </tr>
      <tr> 
        <td width="158"><span class="Estilo2">Apellidos Paterno</span></td>
        <td width="354"> 
          <input type="text" id="apellido_P" name="apellido_P" size="40" maxlength="40" value="<?php echo $apellido_P; ?>">        </td>
      </tr>
      <tr>
        <td class="Estilo2"><strong>Apellido Materno</strong></td>
        <td> <input type="text" id="apellido_M" name="apellido_M" size="40" maxlength="40" value="<?php echo $apellido_M; ?>"></td>
      </tr>
      <tr> 
        <td width="158"><span class="Estilo2">Clave</span></td>
        <td width="354"> 
          <input name="passs" type="text" id="passs" value="<?php echo $clave;?>" size="10" maxlength="10">        </td>
      </tr>
      <tr> 
        <td width="158"><span class="Estilo2">Fono</span></td>
        <td width="354"> 
          <input type="text" id="fono"  name="fono" size="20" maxlength="20" value="<?php echo $fono;?>">        </td>
      </tr>
      <tr> 
        <td width="158"><span class="Estilo2">Direccion</span></td>
        <td width="354"> 
          <input type="text" id="direccion" name="direccion" size="50" maxlength="50" value="<?php echo $direccion?>">        </td>
      </tr>
      <tr> 
        <td width="158"><span class="Estilo2">Ciudad</span></td>
        <td width="354"> 
          <input type="text" id="ciudad" name="ciudad" size="20" maxlength="20" value="<?php echo $ciudad;?>">        </td>
      </tr>
      <tr> 
        <td width="158"><span class="Estilo2">Email</span></td>
        <td width="354"> 
          <input type="text" name="email" size="50" maxlength="50" value="<?php echo $email; ?>">        </td>
      </tr>
      <tr> 
        <td width="158"><span class="Estilo2">Liceo</span></td>
        <td width="354"> 
          <input type="text" id="liceo" name="liceo" value="<?php echo $liceo;?>" maxlength="50" size="50">        </td>
      </tr>
      <tr> 
        <td width="158"><span class="Estilo2">Apoderado</span></td>
        <td width="354"> 
          <input type="text" id="apoderado" name="apoderado" value="<?php echo $apoderado;?>" maxlength="50" size="50">        </td>
      </tr>
      <tr> 
        <td width="158"><span class="Estilo2">Fono Ap.</span></td>
        <td width="354"> 
          <input type="text" name="fonoa" value="<?php echo $fonoa;?>" size="20" maxlength="20">        </td>
      </tr>
      <tr> 
        <td width="158"><span class="Estilo2">Fecha Nac.</span></td>
        <td width="354"> 
          <input type="text" name="fnac" value="<?php echo $fnac;?>" size="10" maxlength="10">        </td>
      </tr>
      <tr> 
        <td width="158" height="17"><span class="Estilo2">Carrera</span></td>
        <td width="354" height="17"><?php echo $carrera; ?></td>
      </tr>
      <tr>
        <td width="158"><span class="Estilo2">A&ntilde;o Ingreso</span></td>
        <td width="354"><?php echo $ingreso; ?></td>
      </tr>
      <tr> 
        <td width="158"><span class="Estilo2">Sede</span></td>
        <td width="354"><?php echo $sede; ?> </td>
      </tr>
      <tr> 
        <td width="158"><span class="Estilo2">Jornada</span></td>
        <td width="354"><?php echo $jornada; ?></td>
      </tr>
      <tr>
        <td colspan="2"><input type="reset" name="Submit" value="Restablecer">
          <input type="button" name="accion" value="Grabar" onClick="valida();"></td>
        </tr>
      </tbody>
    </table>
    <div align="right"></div>
  </form>
</div>
</body>  
</html>