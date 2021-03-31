<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="externo";
	$lista_invitados["privilegio"][]="inspeccion";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
//-----------------------------------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Mis Datos</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
a:link {
	color: #3399FF;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #3399FF;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #3399FF;
}
#link {
	text-align: right;
	padding-right: 10px;
}
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 87px;
}
#apDiv1 #frm #mensaje {
	font-weight: bold;
	color: #FF0000;
	text-decoration: blink;
	text-align: center;
}
-->
    </style>
<script language="javascript">
function CONFIRMAR()
{
	continuar=true;
	claveX=document.getElementById('clave').value;
	
	if((claveX=="")||(claveX==" "))
	{
		alert('ingrese una clave');
		continuar=false;
	}
	if(continuar)
	{
		c=confirm('¿Seguro(a) Desea Modificar sus Datos?');
		if(c)
		{
			document.frm.submit();
		}
	}
}
</script>    
</head>
<?php
	require("../../../funciones/conexion_v2.php");
	$id_user_activo=$_SESSION["USUARIO"]["id"];
	$cons="SELECT * FROM personal WHERE id='$id_user_activo'";
	//echo"----> $cons <br>";
	$sql=$conexion_mysqli->query($cons);
	$DP=$sql->fetch_assoc();
	
	$rut=$DP["rut"];
	$nick=$DP["nick"];
	$nombre=$DP["nombre"];
	$apellido=$DP["apellido"];
	$email=$DP["email"];
	$fono=$DP["fono"];
	$direccion=$DP["direccion"];
	$ciudad=$DP["ciudad"];
	$clave=$DP["clave"];
	$sede=$DP["sede"];
	
	$sql->free();
	$conexion_mysqli->close();
?>
<body>
<h1 id="banner">Mis Datos</h1>
<div id="link">
<?php
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	switch($privilegio)
	{
		case"matricula":
			$url_menu="../menu_matricula/index.php";
			break;
		case"inspeccion":
			$url_menu="../menu_inspeccion/index.php";
			break;	
		case"externo":
			$url_menu="../menu_externos/index.php";
			break;	
		default:
			$url_menu="../ADmenu.php";	
			break;
	}
?><br />

 <a href="<?php echo $url_menu;?>" class="button">Volver al Menu</a></div>
  <div id="apDiv1">
  	<form action="edita_mis_datos.php" method="post" name="frm" id="frm">
    <table width="60%" border="0" align="center">
    <thead>
      <tr>
        <th colspan="2" >Modificacion de Mis Datos</th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td width="26%" ><div align="left">ID</div></td>
        <td width="74%" ><div align="left"><em><?php echo $id_user_activo;?></em></div></td>
      </tr>
      <tr>
        <td ><div align="left">Rut</div></td>
        <td ><div align="left"><em><?php echo $rut;?></em></div></td>
      </tr>
      <tr>
        <td ><div align="left">Nombre</div></td>
        <td ><div align="left"><em><?php echo $nombre;?></em></div></td>
      </tr>
      <tr>
        <td ><div align="left">Apellido</div></td>
        <td ><div align="left"><em><?php echo $apellido;?></em></div></td>
      </tr>
      <tr>
        <td >NICK</td>
        <td ><label for="nick"></label>
        <input name="nick" type="text" id="nick" value="<?php echo $nick;?>"/></td>
      </tr>
      <tr>
        <td ><div align="left">Email</div></td>
        <td >
          <div align="left"><em>
            <input name="email" type="text" id="email" value="<?php echo $email;?>" />
          </em></div></td>
      </tr>
      <tr>
        <td ><div align="left">Fono</div></td>
        <td >
          <div align="left"><em>
            <input name="fono" type="text" id="fono" value="<?php echo $fono;?>" />
          </em></div></td>
      </tr>
      <tr>
        <td ><div align="left">Direccion</div></td>
        <td >
          <div align="left"><em>
            <input name="direccion" type="text" id="direccion" value="<?php echo $direccion;?>" />
          </em></div></td>
      </tr>
      <tr>
        <td ><div align="left">Ciudad</div></td>
        <td >
          <div align="left"><em>
            <input name="ciudad" type="text" id="ciudad" value="<?php echo $ciudad;?>" />
          </em></div></td>
      </tr>
      <tr>
        <td ><div align="left">Clave Actual</div></td>
        <td >
          <div align="left"><em>
            <input name="clave" type="text" id="clave" />
        </em></div></td>
      </tr>
      <tr>
        <td >Nueva Clave</td>
        <td ><label for="nueva_clave_1"></label>
        <input type="text" name="nueva_clave_1" id="nueva_clave_1" /></td>
      </tr>
      <tr>
        <td >Verifica Clave</td>
        <td ><label for="nueva_clave_2"></label>
        <input type="text" name="nueva_clave_2" id="nueva_clave_2" /></td>
      </tr>
      <tr>
        <td ><div align="left">Sede</div></td>
        <td ><div align="left"><em><?php echo $sede;?></em></div></td>
      </tr>
      <tr>
        <td ></td>
        <td ><div align="left">
          <input type="button" name="button" id="button" value="Modificar"  onclick="CONFIRMAR();"/>
        </div></td>
      </tr>
      <?php
      if($_GET)
	  {
	  	$error=$_GET["error"];
		$msj_clave=base64_decode($_GET["msjcl"]);
		switch($error)
		{
			case"0":
				$msj="*DATOS MODIFICADOS CON EXITO*";
				break;
			case"1":
				$msj="ERROR AL MODIFICAR LOS DATOS*";
				break;	
		}
	  }
	  ?>
      <tr>
        <td colspan="2"><div id="mensaje"><?php echo "$msj</br>$msj_clave";?></div></td>
      </tr>
      </tbody>
    </table>
    </form>
  </div>

</body>
</html>
