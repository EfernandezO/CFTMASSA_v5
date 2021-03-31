<?php
//--------------------------------------------//
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
	{$alumno_seleccionado=true;	}
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
	require("../../../../funciones/conexion_v2.php");
	$cons_c="SELECT COUNT(id) FROM registro_certificados WHERE id_solicitud='$id_solicitud'";
	$sql_c=$conexion_mysqli->query($cons_c)or die($conexion_mysqli->error);
		$Dc=$sql_c->fetch_row();
		$num_certificados=$Dc[0];
		if(empty($num_certificados)){ $num_certificados=0;}
		if(DEBUG){ echo"$cons_c<br>NUM: $num_certificados<br>";}
	$sql_c->free();	
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
			$sql_s=$conexion_mysqli->query($cons_s)or die($conexion_mysqli->error);
				$Ds=$sql_s->fetch_assoc();
				$S_observacion=$Ds["observacion"];
			$sql_s->free();	
			
			if(empty($S_observacion))
			{ $redirigir=true; if(DEBUG){ echo"Sin Observacion para certificado<br>";}}
			else
			{ $redirigir=true; if(DEBUG){ echo"Existe Observacion Para Certificado<br>Redirigir<br>";}}
			
		}
		
		$conexion_mysqli->close();
		
		if($redirigir)
		{
			$action="";
			$url="certificado_titulo_v1_2.php?id_solicitud=$id_solicitud";
			if(DEBUG){ echo"URL: $url";}
			else{ header("location: $url");}
		}
		else
		{
			$action="certificado_titulo_v1_2.php";
		}
}
else
{ header("location: ../../../buscador_alumno_BETA/HALL/index.php");}
?>
<html>
<head>
<title>certificado Titulo</title>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<script src="../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../../libreria_publica/JSCal/src/js/lang/es.js"></script>

<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/JSCal/src/css/steel/steel.css">
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
	height:24px;
	z-index:8;
	left: 30%;
	top: 268px;
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
		alert('Ingrese El Lugar donde será Presentado...!!!');
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
<h1 id="banner">Administrador - Certificado titulo</h1>
<div id="link"><br><a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver al Menu</a> </div>
<div id="layer" style="position:absolute; left:5%; top:131px; width:90%; height:106px; z-index:7">
  <form action="<?php echo $action;?>" method="post"  enctype="multipart/form-data" name="frm"  id="frm">
    <table width="40%" border="0" align="center">
    <thead>
      <tr>
        <th colspan="2" align="center" class="Estilo8" >Datos Para Certificado</th>
      </tr>
      </thead>
      <tbody>
	  <tr class="odd">
	  <td><span class="Estilo7">Presentado a:
	  </span></td>
	  <td><input name="presentado" type="text" id="presentado" size="40" value="<?php echo $S_observacion;?>"></td>
	  </tr>
      <tr class="odd">
        <td colspan="2"></tr>
      </tbody>
    </table>
  </form>
</div>
<div id="apDiv4"><a href="#" class="button_G" onClick="CONFIRMAR();">Continuar</a></div>
<script type="text/javascript">
//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton1", "fecha_examen", "%Y-%m-%d");
    //]]>
</script>
</body>
</html>