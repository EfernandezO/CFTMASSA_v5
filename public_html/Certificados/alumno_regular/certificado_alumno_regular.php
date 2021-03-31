<?php
//--------------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
?>
<html>
<head>
<title>Certificado Alumno Regular</title>
<?php include("../../../funciones/codificacion.php");?>
<style>
<!--
.Normal
	{font-size:16.0pt;
	font-family:"Monotype Corsiva";}
.SpellE
	{}
.Estilo1 {color: #FF0000}
.Estilo3 {font-size: xx-large}
#apDiv1 {
	position:absolute;
	width:238px;
	height:13px;
	z-index:7;
	left: 7px;
	top: 747px;
}
-->
</style>
</head>

<body lang=ES-CL class="Normal" bgcolor="#ffffff">
<div id="Layer1" style="position:absolute; left:58px; top:128px; width:660px; height:14px; z-index:1"> 
  <div align="center">
<?php
$sede=$_SESSION["SELECTOR_ALUMNO"]["sede"];
$rut=$_SESSION["SELECTOR_ALUMNO"]["rut"];
$carrera=$_SESSION["SELECTOR_ALUMNO"]["carrera"];

$presentado=$_SESSION["AUX_CERTIFICADO"]["auxpresentado"];
$niv=$_SESSION["AUX_CERTIFICADO"]["auxnivel"];
$firma=$_SESSION["AUX_CERTIFICADO"]["auxfirma"];

$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
/////////////////////////////////////
$mostar_mensaje=true;
$registrar_certificado=true;
$cb=0;
//////////////////////////////////////
include("../../../funciones/conexion.php");

$res="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";
$result=mysql_query($res)or die("Alumno ".mysql_error());
while($row = mysql_fetch_array($result)) 
{
   // $enid=$row["id"];
    //$enc=$row["clave"];
	
	$apellido_P=$row["apellido_P"];
	$apellido_M=$row["apellido_M"];
	$apellido_old=$row["apellido"];
	$apellido_new=$apellido_P." ".$apellido_M;
	if($apellido_new!=" ")
	{
		 $ena=$apellido_new;
	}
	else
	{
		 $ena=$apellido_old;
	}
	
   
    $enn=$row["nombre"];
    $enr=$row["rut"];
    $end=$row["direccion"];  
    $enci=$row["ciudad"];
    $enf=$row["fono"]; 
    $ene=$row["email"];
    $eni=$row["nivel"];
	$situacion=$row["situacion"];
    ++$cb;
}
$cb=0;

if(($situacion=="V")or($situacion=="M"))
{
	//07-03-2011
	//registro certificado
	////////////////////////////////////////////////
	if($registrar_certificado)
	{
		$id_certificadoX=REGISTRAR_CERTIFICADO($rut, $id_carrera, $carrera, $sede);
		$rut_alumno=$_SESSION["SELECTOR_ALUMNO"]["rut"];
		$carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
		$tipo_certificado="certificado de alumno regular";
		$cons="SELECT COUNT(id) FROM registro_certificados WHERE rut_alumno='$rut_alumno' AND id_carrera='$id_carrera' AND tipo_certificado='$tipo_certificado'";
		$sqlXX=mysql_query($cons)or die(mysql_error());
		$C=mysql_fetch_row($sqlXX);
		$numero_certificados=$C[0];
		if(empty($numero_certificados))
		{ $numero_certificados=0;}
		mysql_free_result($sqlXX);
		//echo"-->($id_certificadoX _ $numero_certificados)<br>";
		
		$codigo_certificado=$id_certificadoX."_".$numero_certificados;
	}
	else
	{ $codigo_certificado="xx_xx";}
	//echo"---> $codigo_certificado<br>";
	////////////////////////////////////////////////
	$res="SELECT * FROM certificados where id_carrera = '$id_carrera' and sede ='$sede' LIMIT 1";
	//echo"---> $res<br>";
	$result=mysql_query($res)or die("decreto ".mysql_error());
	$row = mysql_fetch_assoc($result);
    	$decreto=$row["decreto"];
    	$carreraa=$row["carrera"];
    	$sedea=$row["sede"];
		
		//echo"-----> $decreto<br>";
		@mysql_free_result($result);
		if(($mostar_mensaje)and($situacion=="M")){
	echo'<b><span class="Estilo1"><span class="Estilo3">!! ALUMNO MOROSO ...¡¡</span><br>Certificado Incompleto.</span></b>';
	}
}
else
{
	///en otras situaciones de alumno
}	

mysql_close($conexion);
?>
    <div id="apDiv1">
      <div align="left">Cod. <?php echo $codigo_certificado;?></div>
    </div>
    <p><b><font size="5">CERTIFICADO</font></b></p>
  </div>
</div>
<div id="Layer1" style="position:absolute; left:58px; top:80px; width:659px; height:14px; z-index:1"> 
  <div align="center"> 
    <p align="right"><?php 
$fecha=fecha();
echo "$sede, $fecha"; ?></p>
  </div>
</div>
<div id="Layer2" style="position:absolute; left:58px; top:262px; width:660px; height:123px; z-index:2"> 
  <p align="justify"><b><?php echo $firma; ?></b> del Centro de Formaci&oacute;n T&eacute;cnica, Massachusetts, Certifica:</p>
  <p align="justify" style='line-height:150%'><span class=SpellE>Que, el(la) Se&ntilde;or(ita): 
    <b> <?php echo "$enn $ena"; ?> </b> es alumno(a) regular de la carrera de <?php echo "$carrera $niv"; ?></span></p>
</div>
<div id="Layer3" style="position:absolute; left:63px; top:461px; width:660px; height:48px; z-index:3"> 
  <p align="justify" style='line-height:150%'><span class=SpellE>Que 
  su funcionamiento como &quot;Centro de Formaci&oacute;n T&eacute;cnica&quot; 
  fue aprobado por Decreto Exento N&deg; 29 de 02 de febrero de 1983, inscrito 
  en el Registro correspondiente bajo el N&deg; 77.</span></p></div>
<div id="Layer4" style="position:absolute; left:58px; top:586px; width:660px; height:41px; z-index:4"> 
  <p align="justify" style='line-height:150%'><span class=SpellE>Que, 
  por <?php echo $decreto; ?>, se aprobaron Planes y Programas de Estudios de la 
  mencionada Carrera.</span></p></div>
<div id="Layer5" style="position:absolute; left:63px; top:686px; width:660px; height:84px; z-index:5"> 
  <p align="justify" style='line-height:150%'><span class=SpellE>Se extiende el presente certificado a solicitud del(la) interesado(a) 
    para ser presentado  <?php echo $presentado; ?>.</span></p>
</div>
<div id="Layer6" style="position:absolute; left:429px; top:838px; width:290px; height:26px; z-index:6"> 
  <div align="center">
    <p>__________________________<br>
    Direcci&oacute;n Acad&eacute;mica</p>
  </div>
</div>
<?php 
function fecha(){
$mes = date("n");
$mesArray = array(
1 => "Enero", 
2 => "Febrero", 
3 => "Marzo", 
4 => "Abril", 
5 => "Mayo", 
6 => "Junio", 
7 => "Julio", 
8 => "Agosto", 
9 => "Septiembre", 
10 => "Octubre", 
11 => "Noviembre", 
12 => "Diciembre"
);

$semana = date("D");
$semanaArray = array(
"Mon" => "Lunes", 
"Tue" => "Martes", 
"Wed" => "Miércoles", 
"Thu" => "Jueves", 
"Fri" => "Viernes", 
"Sat" => "Sábado", 
"Sun" => "Domingo", 
);

$mesReturn = $mesArray[$mes];
$semanaReturn = $semanaArray[$semana];
$dia = date("d");
$año = date ("Y");

return $semanaReturn." ".$dia." de ".$mesReturn." de ".$año;
}

//$_SESSION["auxfirma"]="";
//$_SESSION["auxrut"]="";
//$_SESSION["auxpresentado"]="";
//$_SESSION["auxcarrera"]="";
//$_SESSION["auxnivel"]="";
//$_SESSION["auxsede"]="";
/////////////////////////////////
function REGISTRAR_CERTIFICADO($rut, $id_carrera, $carrera, $sede)
{
	$fecha_hora=date("Y-m-d H:i:s");
	$id_user_activo=$_SESSION["USUARIO"]["id"];
	$tipo_certificado="certificado de alumno regular";
	/////////////////////////////////////////////
	$campos="rut_alumno, id_carrera, carrera_alumno, tipo_certificado, fecha_hora, id_user, sede";
	$valores="'$rut', '$id_carrera', '$carrera', '$tipo_certificado', '$fecha_hora', '$id_user_activo', '$sede'";
	
	$cons_IN="INSERT INTO registro_certificados($campos) VALUES($valores)";
	mysql_query($cons_IN)or die("registro certificado ".mysql_error());
	$id_certificado=mysql_insert_id();
	//echo"----------->$cons_IN<br>";
	return($id_certificado);
}
?> 

</body>
</html>