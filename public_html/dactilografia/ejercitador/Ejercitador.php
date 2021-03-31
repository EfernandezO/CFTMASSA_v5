<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="ALUMNO";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", false);
if($_GET)
{
	include("../../../funciones/conexion_v2.php");
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	$id_usuario=$_SESSION["USUARIO"]["id"];
	$id_leccion=$_GET["id_leccion"];
		
	switch($privilegio)
	{
		case"ALUMNO":
			   include("../../../funciones/VX.php");
			 //cambio estado_conexion USER-----------
			 CAMBIA_ESTADO_CONEXION_ALUMNO($id_usuario, "ON");
			 $evento="Dactilografia -> Leccion: $id_leccion ";
			 REGISTRA_EVENTO($evento);
			//-----------------------------------------------//
			break;
	}
	

	$cons_L="SELECT * FROM dactilografia_lecciones WHERE id='$id_leccion' LIMIT 1";
	if(DEBUG){ echo"---> $cons_L<br>";}
	$sql=$conexion_mysqli->query($cons_L);
	$L=$sql->fetch_assoc();
		$titulo=ucwords(strtolower($L["titulo"]));
		$descripcion=$L["descripcion"];
		$texto_leccion=html_entity_decode($L["texto"]);
		$duracion=$L["duracion_seg"];
		$clasificacion=$L["clasificacion"];
	$sql->free();
	$conexion_mysqli->close();
}
else
{ header("location: ../Lecciones_disponibles.php");}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es"><!---->
<head>
<meta http-equiv="Content-Language" content="es">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Test de velocidad</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<style type="text/css">
#cont .contenido {
	text-align: center;
	width: 90%;
	position: absolute;
	left: 5%;
	z-index: 2;
}
#apDiv1 {
	position:absolute;
	width:306px;
	height:28px;
	z-index:1;
	left: -1px;
	top: 39px;
}
</style>
<script type="text/javascript">
var cuentaInicial = "<?php echo $duracion;?>";
var minutos=0;
var segundos=0;
function fin() 
{
	alert ("Se acabo el Tiempo");
	document.getElementById('indicador_tiempo').value="MAXIMO";
	<?php if(!DEBUG){?>
	analitza_resultat(errors);
	<?php }?>
}

function unoMenos() 
{
	minutos=parseInt((cuentaInicial/60));
	segundos=cuentaInicial-(minutos*60);
with (
document.forms["cuenta"]["regresiva"]) value = 'Tiempo Restante '+minutos+':'+segundos+' segundos.';
if (
cuentaInicial-- > 0
)
setTimeout("unoMenos()", 1000);
else fin();
}
function ini() {
	minutos=parseInt((cuentaInicial/60));
	segundos=cuentaInicial-(minutos*60);
with (
document.forms["cuenta"]["regresiva"]) value = 'Tiempo Restante '+minutos+':'+segundos+' segundos.';
setTimeout("unoMenos()", 1000);
}
function CONTADOR()
{
	//alert('a');
	aux=document.getElementById('contador').value;
	aux=parseInt(aux)+1;
	document.getElementById('contador').value=aux;
}
</script>
</head>

<body onload="document.getElementById('S1').focus(); escrit_inicial(); ini();">
<h1 id="banner">Dactilografia - Test de Velocidad</h1>
<br />
<div id="link"><a href="Ejercitador.php?id_leccion=<?php echo $id_leccion; ?>" class="button">Reset</a>
&nbsp;
<a href="../Lecciones_disponibles.php" class="button">Ver Lecciones</a></div>

<div id="cont">
	<!-- header -->
	
<!-- end header -->
	<div class="contenido">

<script language="javascript" src="asociados_files/encripta.js"></script>
<script>
<?php
	$texto=$texto_leccion;
	$hora_servidor=date("Y-m-d H:i:s");
	echo'var texte="'.$texto.'";
		 var hora_servidor="'.$hora_servidor.'";';
?>	
</script>

<script language="javascript" src="asociados_files/test-encr.js"> </script>

<u><b><?php echo $titulo;?></b></u>
<br><br>
<?php echo $descripcion;?>
<br />
<br>
Teclee el siguiente texto... <br><br>
<div id="contenedor_txt">
<span id="clock">
    <font face="courier new,courier">
        <font size="4">Texto <font color="red"><u>C</u></font>
        argado...</font>
    </font>
</span>
</div>
<form method="POST" id="formul">
<p><textarea rows="10" id="S1" name="S1" cols="80" oninput="javascript:checkPasteFF();" onpaste="return false;" onkeydown="CheckKey(event);" onkeyup="this.value=Comprova_ok(this.value,event);"  onkeypress="CONTADOR();">
</textarea>
  <input name="id_leccion" type="hidden" id="id_leccion" value="<?php echo $id_leccion;?>" />
  <input name="indicador_tiempo" type="hidden" id="indicador_tiempo" value="ok" />
  <br />
  <label for="contador"></label>
  <input name="contador" type="text" id="contador" value="0" size="5"  readonly="readonly"/>
</p>
</form>
<input value="Disminuir tamaño letra" name="dtamano" onclick="d_tamany()" type="button">
<input value="Aumentar tamaño letra" name="atamano" onclick="a_tamany()" type="button">
<br><br>
<script>
var paste_utilitzat=0;
var charCount = document.getElementById("S1").value.length;

function checkPasteFF(methodToCall)
{
	newCharCount = document.getElementById("S1").value.length;
	oldCharCount = charCount;	
	charCount = newCharCount;
	if(newCharCount - oldCharCount > 1) 
	{
		paste_utilitzat=1;
		alert('Presione solo teclas alfanumericas...');
	}
}

</script>
	</div>

	<!-- footer -->	
  <!-- end footer -->
  <div id="apDiv1"><form name="cuenta" action="" >
<input name="regresiva" type="text" size=55 readonly style="border:0px;">
Clasificacion: <?php echo $clasificacion;?>
</form></div>
</div>
</body>
</html>