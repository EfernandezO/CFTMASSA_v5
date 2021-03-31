<?php
if($_GET)
{
	if(isset($_GET["error"]))
	{ $error=$_GET["error"];}
	else{ $error="";}
	if(isset($_GET["url"]))
	{ $url_pdf=base64_decode($_GET["url"]);}
	else{ $url_pdf="";}
	
	$url_pdf="../visor_pdf/".$url_pdf;
	$url_pdf=base64_encode($url_pdf);
	//echo $url_pdf;
}
else
{
	header("location: ../index.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Autentificacion Massa - Biblioteca</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_validate_password/demo/milk.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:170px;
	height:2px;
	z-index:1;
	left: 50%;
	top: 114px;
	text-align: center;
	font-weight: bold;
	color: #FF0000;
	text-decoration: blink;
}
#Layer2 {
	position:absolute;
	width:178px;
	height:14px;
	z-index:2;
	left: 220px;
	top: 329px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
#apDiv1 {
	position:absolute;
	width:100%;
	height:115px;
	z-index:3;
	left: 0%;
	top: 94px;
}
-->
</style>
<script language="javascript">
function fcnClose()
{
alert("Se va a cerrar SexyLightbox");
// Función necesaria para cerrar la ventana modal
window.parent.SexyLightbox.close();
// Función necesaria para actualizar la ventana padre
window.parent.document.location.reload();
}
</script>
</head>

<body>
<h1 id="banner">Biblioteca - C.F.T. Massachusetts</h1>
<div id="apDiv1">
<div style="clear: both;"><div></div></div>


<div class="content">
    <div id="signupbox">
       <div id="signuptab">
        <ul>
          <li id="signupcurrent"><a href=" ">Acceso</a></li>
        </ul>
      </div>
      <div id="signupwrap">
      		<form action="control_ingreso.php" method="post" name="frm" id="frm" autocomplete="off">
	  		  <table>
	  		  <tr>
	  			<td class="label"><label id="lusername" for="username">Usuario</label></td>
	  			<td class="field"><input id="usuario" name="usuario" type="text" value="" maxlength="50" /></td>
	  			<td class="status"></td>
	  		  </tr>
	  		  <tr>
	  			<td class="label"><label id="lpassword" for="password">Contrase&ntilde;a</label></td>
	  			<td class="field"><input id="pass" name="pass" type="password" maxlength="50" value="" /></td>
	  			<td class="status">&nbsp;</td>
	  		  </tr>
	  		  <tr>
	  			<td class="label"><label id="lpassword_confirm" for="password_confirm">Tipo Cuenta </label></td>
	  			<td class="field"><select name="tipo_cuenta" id="tipo_cuenta">
	  			  <option value="Alumno" selected="selected">Alumno</option>
	  			  <option value="Docente">Docente</option>
	  			  <option value="Administrativo">Administrativo</option>
	  			  </select>	  			</td>
	  			<td class="status"></td>
	  		  </tr>
	  		  <tr>
	  			<td class="label"><label id="lsignupsubmit" for="signupsubmit">Autentificar</label></td>
	  			<td class="field" colspan="2">
	            <input id="signupsubmit" name="signup" type="submit" value="Entrar" />
	            <input name="validador" type="hidden" id="validador" value="<?php echo md5("Massa_".date("d-m-Y"))?>" />	  			<input name="url_pdf" type="hidden" id="url_pdf" value="<?php echo $url_pdf; ?>" /></td>
	  		  </tr>
	  		  </table>
   		  </form>
      </div>
    </div>
</div>
</div>
<?php if($error>=1){?>
<div id="Layer1"><img src="../../BAses/Images/X.jpg" alt="X" width="132" height="101" /><br />
Error Datos Incorrectos</div>
<?php }?>
</body>
</html>