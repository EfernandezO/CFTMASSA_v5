<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("creacion_registro_academico_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<html>
<head>
<title>Graba Registro Academico</title>
<?php include("../../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:305px;
	height:115px;
	z-index:1;
	left: 102px;
	top: 113px;
}
-->
</style></head>

<body>
<h1 id="banner">Administrador - Registro Academico</h1>
<div id="link"><br>
<a href="../../../asignaturas_ramo/tomaramo_individual.php" class="button">Ir a: Toma de Ramos </a></div>
<?php
$continuar=false;
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
	{ $continuar=true;}
}
	
if($continuar)
{
	 require('../../../../funciones/conexion_v2.php'); 
  
  $A_nombre=$_SESSION["SELECTOR_ALUMNO"]["nombre"];
  $A_apellidos=$_SESSION["SELECTOR_ALUMNO"]["apellido"];
  $rut=$_SESSION["SELECTOR_ALUMNO"]["rut"];
  $id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
  $id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
  $sede=$_SESSION["SELECTOR_ALUMNO"]["sede"];
  $carrera=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
  $yearIngresoCarrera=$_SESSION["SELECTOR_ALUMNO"]["yearIngresoCarrera"];
   $cb=1;
   $vsw = cambiasw($id_alumno, $id_carrera, $yearIngresoCarrera);//ve si ya tiene registro creado
   
   if ($vsw)
   {
  	if(DEBUG){ echo"Revisando Registros -> No Generado<br>";}

   $res="SELECT * FROM mallas WHERE id_carrera= '$id_carrera' AND ramo<>'' ORDER by num_posicion, cod";
   if(DEBUG){ echo"$res<br>";}
   $result=mysql_query($res);
   while($row = mysql_fetch_array($result)) 
   {
		$cod=$row["cod"];
		$pr1=$row["pr1"];
		$pr2=$row["pr2"];
		$pr3=$row["pr3"];
		$pr4=$row["pr4"];
		$nivel=$row["nivel"];
		$ramo=$row["ramo"];
		$es_asignatura=$row["es_asignatura"];
		++$cb;
		GRABA_REGISTRO($id_alumno, $cod, $nivel, $ramo, $es_asignatura, $sede, $id_carrera, $yearIngresoCarrera);
   }


    $tabla='	 
   <table width="30%" align="center" border="0">
   <thead>
     <tr>
       <th colspan="2" bgcolor="#66CCFF">INFORMACION</th>
     </tr>
	 </thead>
	 <tbody>
     <tr>
       <td><strong>Nombre:</strong></td>
       <td>'.$A_nombre.'</td>
     </tr>
     <tr>
       <td><strong>Apellido:</strong></td>
       <td>'.$A_apellidos.'</td>
     </tr>
	 <tr>
	 	<td>Carrera</td>
		<td>'.$carrera.'</td>
	 </tr>
     <tr>
       <td colspan="2"><div align="center">Su Registro Fue Creado Exitosamente </div></td>
     </tr>
	 </tbody>
   </table>';
	 /////Registro ingreso///
		 include("../../../../funciones/VX.php");
		 $evento="Creacion de Registro Academico id_alumno:$id_alumno id_carrera: $id_carrera yearIngresoCarrera: $yearIngresoCarrera";
		 REGISTRA_EVENTO($evento);
		 $descripcion="Creacion de Registro academico para carrera id_carrera: $id_carrera yearIngresoCarrera: $yearIngresoCarrera";
		REGISTRO_EVENTO_ALUMNO($id_alumno, "Notificacion",$descripcion);
		 ///////////////////////
}
else
 { 
 	if(DEBUG)
	{
		echo"Ya tiene registros Creados...<br>";
	}
  $tabla='
    <table width="30%" border="0" align="center">
    <tr>
      <td width="470" colspan="2" bgcolor="#FF0000"><div align="center" class="Estilo3">INFORMACION</div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center">EL Registro No pudo ser Creado<br>
	Probablemente ya tiene uno Creado... </div></td>
    </tr>
    
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
  </table>';
 }
}
else
{
	$tabla="Sin Seleccion...";
}

function GRABA_REGISTRO($id_alumno, $codf, $nivelf, $ramof, $es_asignaturaf, $sedef, $id_carrera, $yearIngresoCarrera) 
{	
    $result="INSERT INTO notas (id_alumno, id_carrera, yearIngresoCarrera, cod, nivel, ramo, es_asignatura, sede) VALUES ('$id_alumno', '$id_carrera', '$yearIngresoCarrera', '$codf', '$nivelf','$ramof', '$es_asignaturaf', '$sedef')";
	
      if(DEBUG)
   		{
   			echo"Escribiendo Registro -> $result<br>";
  		}
		else
		{
			if(!mysql_query($result))
			 {
				echo "Graba_REGISTRO ".mysql_error();
			 }
		}
}


function cambiasw($id_alumno, $id_carrera, $yearIngresoCarrera)
{
   $res="SELECT * FROM notas WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND yearIngresoCarrera='$yearIngresoCarrera'";
   if(DEBUG){ echo"--->$res<br>";}
   $result=mysql_query($res);
   $num_reg=mysql_num_rows($result);
   if($num_reg>0)
   {
   		$error=false;
   }
   else
   {
   	$error=true;
   }
   if(DEBUG){ echo"Registro de Notas encontradas: $num_reg<br>";}
   return $error;
} 
 
 ?> 
<div id="layer" style="position:absolute; left:5%; top:198px; width:90%; height:29px; z-index:2">
	<?php echo $tabla;?><br>
  <div align="center" class="Estilo2"><a href="../../../buscador_alumno_BETA/HALL/index.php" class="button">Volver al Menu</a> </div>
  
</div>
</body>
</html>