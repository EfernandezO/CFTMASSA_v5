<?php
//--------------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
////////////////////////////////////////////////////
	
///verificar alumno seleccionado	
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))	
{
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
	{
		$alumno_seleccionado=true;	
	}
	else{ $alumno_seleccionado=false;}
}
else
{$alumno_seleccionado=false;}
//verificar id_solicitud

if(isset($_GET["id_solicitud"]))
{
	$id_solicitud=$_GET["id_solicitud"];
	if($id_solicitud>0)
	{ $hay_solicitud=true;}
	else
	{ $hay_solicitud=false;}
}
else
{ $hay_solicitud=false;}

////-***************************************************************************-//
$redirigir=false;
////-***************************************************************************-//
if(($alumno_seleccionado)and($hay_solicitud))
{
	include("../../../../funciones/conexion_v2.php");
	$cons_c="SELECT COUNT(id) FROM registro_certificados WHERE id_solicitud='$id_solicitud'";
	$sql_c=mysql_query($cons_c)or die(mysql_error());
		$Dc=mysql_fetch_row($sql_c);
		$num_certificados=$Dc[0];
		if(empty($num_certificados)){ $num_certificados=0;}
		if(DEBUG){ echo"$cons_c<br>NUM: $num_certificados<br>";}
	mysql_free_result($sql_c);	
		//////////////////////////////////////////////
		if($num_certificados>0)
		{
			$redirigir=true;
			if(DEBUG){ echo"YA EXISTE CERTIFICADO REDIRIGIR<br>";}
			$S_observacion="";
		}
		else
		{
			if(DEBUG){ echo"No existe Certificado Generado<br>";}
			$cons_s="SELECT * FROM solicitudes WHERE id='$id_solicitud' LIMIT 1";
			$sql_s=mysql_query($cons_s)or die(mysql_error());
				$Ds=mysql_fetch_assoc($sql_s);
				$S_observacion=$Ds["observacion"];
			mysql_free_result($sql_s);	
			
			if(empty($S_observacion))
			{ $redirigir=false; if(DEBUG){ echo"Sin Observacion para certificado<br>";}}
			else
			{ $redirigir=true; if(DEBUG){ echo"Existe Observacion Para Certificado<br>Redirigir<br>";}}
			
		}
		
		mysql_close($conexion);
		if($redirigir)
		{
			$action="";
			$url="plan_curricular_v1_2.php?id_solicitud=$id_solicitud";
			if(DEBUG){ echo"URL: $url";}
			else{ header("location: $url");}
		}
		else
		{
			$action="plan_curricular_v1_2.php";
		}
}
else
{ header("location: ../../../buscador_alumno_BETA/HALL/index.php");}


?>
<html>
<head>
<title>certificado - Plan Curricular</title>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<style type="text/css">
<!--
#apDiv1 {	position:absolute;
	width:200px;
	height:115px;
	z-index:8;
	left: 594px;
	top: 78px;
}
#apDiv2 {	position:absolute;
	width:200px;
	height:115px;
	z-index:8;
	left: 575px;
	top: 86px;
}
#apDiv3 {
	position:absolute;
	width:462px;
	height:39px;
	z-index:9;
	left: 102px;
	top: 283px;
}
.Estilo4 {font-size: 12px}
#apDiv4 {
	position:absolute;
	width:40%;
	height:20px;
	z-index:8;
	left: 30%;
	top: 206px;
	text-align: center;
}
-->
</style>
<script language="javascript">
function CONFIRMAR()
{
	continuar=true;
	
	presentado=document.getElementById('presentado').value;
	
	if((presentado=="")||(presentado==" "))
	{
		continuar=false;
		alert('Ingrese El Lugar donde Sera Presentado...!!!');
	}
	
	if(continuar)
	{
		c=confirm('Seguro(a) desea Continuar..??');
		if(c)
		{document.getElementById('frm').submit();}
	}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Certificado Plan Curricular</h1>
<div id="link"><br><a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver al Menu</a> </div>
<div id="layer" style="position:absolute; left:5%; top:98px; width:90%; height:72px; z-index:7">
  <form action="<?php echo $action;?>" method="post"  enctype="multipart/form-data" name="frm"  id="frm">
    <table width="50%" border="0" align="center">
    <thead>
      <tr>
        <th colspan="2" align="center" class="Estilo8" >Datos Para Certificado
          <input name="id_solicitud" type="hidden" id="id_solicitud" value="<?php echo $id_solicitud;?>"></th>
      </tr>
      </thead>
      <tbody>
	  <tr class="odd">
	    <td width="142"><span class="Estilo7">Presentado a:
	      </span></td>
	    <td><input name="presentado" type="text" id="presentado" size="60" value="<?php echo $S_observacion;?>"></td>
	    </tr>
      </tbody>
    </table>
  </form>
</div>
<div id="apDiv4"><a href="#" class="button_G" onClick="CONFIRMAR();">Continuar</a></div>
</body>
</html>