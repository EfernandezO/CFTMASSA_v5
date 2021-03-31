<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Boletas_pendientes_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(isset($_SESSION["FINANZAS"]))
{ unset($_SESSION["FINANZAS"]);}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Boletas Pendientes</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
<!--
#link {
	text-align: right;
	padding-right: 10px;
}
#apDiv1 {
	position:absolute;
	width:90%;
	height:262px;
	z-index:1;
	left: 5%;
	top: 80px;
	padding: 10px;
}
.Estilo1 {font-size: 12px}
#apDiv1 #msjX {
	font-size: 14px;
	color: #FF3333;
	text-decoration: blink;
	text-align: center;
	font-weight: bold;
}
#apDiv1 #ultima {
	margin-top: 20px;
	margin-right: 0px;
	margin-bottom: 0px;
	margin-left: 0px;
	border: thin solid #0000FF;
	padding: 10px;
}
-->
</style>
<script language="javascript">
function VERIFICAR(indice)
{
	continuar=true;
	//alert(indice);
	campo="folio_new_"+indice;
	nombre_formulario="frm_"+indice;
	formulario=document.getElementById(nombre_formulario);
	valor_campo=document.getElementById(campo).value;
	
	if((valor_campo=="")||(valor_campo==" "))
	{
		continuar=false;
		alert('Ingrese Folio, Antes de Continuar');	
	}
	if(continuar)
	{
		formulario.submit();
	}
}
</script>
<script language="javascript">
function ABRE_VENTANA(url)
{
	//alert(url);
	window.open(url,'boleta','height=500, width=450');
}
</script> 
</head>

<body>
<h1 id="banner">Administrador - Boletas Sin Folio</h1>
<?php
	$privilegio=$_SESSION["USUARIO"]["privilegio"];
	switch($privilegio)
	{
		case"inspeccion":
			$url="../../Administrador/menu_inspeccion/index.php";
			break;
		default:
			$url="../index.php";	
	}
?><br />

<div id="link"><a href="<?php echo $url;?>" class="button">Volver al Menu</a></div>
<div id="apDiv1">
  <table width="100%" border="1">
  <caption>
  Boletas Pendientes (Sin Folio)
  </caption>
  	<thead>
    <tr>
      <th><span class="Estilo1">ID Boleta</span></th>
      <th><span class="Estilo1">ID Alumno</span></th>
      <th><span class="Estilo1">Alumno</span></th>
      <th><span class="Estilo1">Carrera</span></th>
      <th><span class="Estilo1">Sede</span></th>
      <th><span class="Estilo1">Fecha</span></th>
      <th><span class="Estilo1">Glosa</span></th>
      <th><span class="Estilo1">Valor ($)</span></th>
       <th><span class="Estilo1">User</span></th>
      <th><span class="Estilo1">Folio</span></th>
    </tr>
    </thead>
    <tbody>
<?php
	if(DEBUG){ echo"P -> $privilegio<br>"; }
	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
	include("../../../funciones/VX.php");
	//------------------------------------//
	$evento="Ingreso a Boletas Pendientes";
	REGISTRA_EVENTO($evento);
	//--------------------------------------//
	//armo consulta
	$sede_user=$_SESSION["USUARIO"]["sede"];
	if($privilegio!="admi_total")
	{
		$condicion="AND boleta.sede='$sede_user'";
		//$condicion_lb="WHERE sede='$sede_user'";
		$condicion_lb="";
	}
	else
	{
		$condicion="";
		$condicion_lb="";
	}
	
	
	$cons_bo="SELECT boleta.*, alumno.rut, alumno.nombre, alumno.apellido, alumno.apellido_P, alumno.apellido_M, alumno.carrera, boleta.sede FROM boleta INNER JOIN alumno ON boleta.id_alumno = alumno.id WHERE boleta.folio=0 $condicion";
	if(DEBUG){ echo"--> $cons_bo<br>"; }
	$sqli_bo=$conexion_mysqli->query($cons_bo)or die($conexion_mysqli->error);
	$num_boletas_pendientes=$sqli_bo->num_rows;
	
	if($num_boletas_pendientes>0)
	{
		$f=1;
		while($DB=$sqli_bo->fetch_assoc())
		{
			$id_boleta=$DB["id"];
			$id_alumno=$DB["id_alumno"];
			$valor=$DB["valor"];
			$glosa=$DB["glosa"];
			$fecha=$DB["fecha"];
			$folio=$DB["folio"];
			$cod_user=$DB["cod_user"];
			$sede=$DB["sede"];
			$rut_alumno=$DB["rut"];
			$nombre_alumno=$DB["nombre"];
			$apellido=$DB["apellido"];
			$apellido_P=$DB["apellido_P"];
			$apellido_M=$DB["apellido_M"];
			$carrera=$DB["carrera"];
			
			$apellido_new=$apellido_P." ".$apellido_M;
			
			if($apellido_new!=" ")
			{$aux_apellido=$apellido_new;}
			else
			{$aux_apellido=$apellido;}
			$alumno=ucwords(strtolower("$nombre_alumno $aux_apellido"));
			echo'<tr >
		<td>'.$id_boleta.'</td>
	    <td>'.$id_alumno.'</td>
	    <td>'.$alumno.'</td>
	    <td>'.$carrera.'</td>
	    <td>'.$sede.'</td>
		<td>'.fecha_format($fecha).'</td>
	    <td>'.$glosa.'</td>
	    <td>'.number_format($valor,0,",",".").'</td>
		<td>'.$cod_user.'</td>
		<td><form id="frm_'.$f.'" name="frm_'.$f.'" method="post" action="boleta_up.php">
				<input name="id_boleta" type="hidden" value="'.$id_boleta.'" />
				<input name="indice" type="hidden" value="'.$f.'" />
				<input name="folio_new_'.$f.'" type="text" id="folio_new_'.$f.'" size="7" maxlength="10" />
				<input type="button" name="button" id="button" value="&gt;&gt;"  onclick="VERIFICAR(\''.$f.'\');"/>
			  </form></td>
				</tr>';
				$f++;
		}
	}
	else
	{
		//sin boletas pendientes
		echo'<tr>
				  <td colspan="10"><span class="Estilo1">XD Sin Boletas Pendientes...</span></td>
				</tr>';
	}
	$sqli_bo->free();

?>
</tbody>
<tfoot>
<tr >
<td colspan="10"><span class="Estilo1"><?php echo $num_boletas_pendientes; ?> Boleta(s) Pendiente(s)</span></td>
</tr>
</tfoot>
  </table>
  <?php
  	if($_GET)
	{
		$error=$_GET["error"];
		switch($error)
		{
			case"0":
				$msjX="*Boleta Actualizada*";
				break;
			case"1":
				$msjX="*Error Boleta No Actualizada*";
				break;	
			case"2":
				$msjX="*Error Folio Incorrecto o Repetido*";
				break;		
		}
	}
	else
	{ $msjX="";}
  ?>
  <div id="msjX"><?php echo"$msjX";?></div>
  <div id="ultima">
  Ultimas Boletas Impresas<br />
  <?php
  $cons_lb="SELECT id, folio, valor, sede FROM boleta $condicion_lb ORDER by id desc LIMIT 200";
  if(DEBUG){ echo"-->$cons_lb<br>";}
  $sqli_lb=$conexion_mysqli->query($cons_lb)or die($conexion_mysqli->error);
  $contador=0;
  while($D_lb=$sqli_lb->fetch_assoc())
  {
	  $contador++;
	  
	  $last_id=$D_lb["id"];
	  $last_folio=$D_lb["folio"];
	  $last_valor=$D_lb["valor"];
	  $last_sede=$D_lb["sede"];
	  
	  $x_semestre="";
	  $x_year_estudio="";
	  $url_boleta='../contrato/imprimibles/boleta/boleta_1.php?id_boleta='.$last_id.'&semestre='.$x_semestre.'&year_estudio='.$x_year_estudio.'&folio='.$last_folio;
	  
	 $escribir='<a href="#" onclick="ABRE_VENTANA(\''.$url_boleta.'\');" title="Click para Imprimir">Imprimir Boleta FOLIO:'.$last_folio.' Valor:$'.$last_valor.' Sede: '. $last_sede.'</a>';
	 
	 echo $contador." ".$escribir."</br>";
     
 }
 $sqli_lb->free();
 
 $conexion_mysqli->close();
 ?>
  </div>
</div>
</body>
</html>
