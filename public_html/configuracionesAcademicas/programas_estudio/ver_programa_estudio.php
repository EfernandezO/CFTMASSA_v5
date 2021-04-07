<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Gestion_carreras_Mallas->PROGRAMAS_ESTUDIO_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
	if(isset($_GET["id_carrera"]))
	{
		$id_carrera=$_GET["id_carrera"];
		if(is_numeric($id_carrera)){ $continuar_1=true;}
		else{ $continuar_1=false;}
	}
	else
	{ $continuar_1=false;}
	
	if(isset($_GET["cod_asignatura"]))
	{
		$cod_asignatura=$_GET["cod_asignatura"];
		if(is_numeric($cod_asignatura)){ $continuar_2=true;}
		else{ $continuar_2=false;}
	}
	else
	{ $continuar_2=false;}
	
	$sede=$_GET["sede"];
	
	if($continuar_1 and $continuar_2)
	{ $continuar=true;}
	else
	{ $continuar=false;}
	
	if($continuar)
	{
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	
	$nombre_carrera=NOMBRE_CARRERA($id_carrera);
	list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($id_carrera, $cod_asignatura);
	
	}
	///horas de programa
	$TOTAL_HORAS_PROGRAMA=0;
	$cons_HT="SELECT DISTINCT(numero_unidad) FROM programa_estudio WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura'";
	$sqli_HT=$conexion_mysqli->query($cons_HT)or die($conexion_mysqli->error);
	$num_programas=$sqli_HT->num_rows;
	if($num_programas>0)
	{
		while($HT=$sqli_HT->fetch_row())
		{
			$aux_numero_unidad=$HT[0];
			$aux_CONS="SELECT cantidad_horas FROM programa_estudio WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' AND numero_unidad='$aux_numero_unidad' LIMIT 1";
			$sqli_aux=$conexion_mysqli->query($aux_CONS)or die("HP ".$conexion_mysqli->error);
				$Pnh=$sqli_aux->fetch_row();
				$aux_numero_hora_x_unidad=$Pnh[0];
				if(empty($aux_numero_hora_x_unidad)){ $aux_numero_hora_x_unidad=0;}
			$TOTAL_HORAS_PROGRAMA+=$aux_numero_hora_x_unidad;
			$sqli_aux->free();	
		}
	}
	$sqli_HT->free();
}
else
{header("location: ../index.php");}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php require("../../../funciones/codificacion.php");?>
<title>Programa de Estudios</title>
	<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
    <link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:55px;
	z-index:1;
	left: 5%;
	top: 177px;
}
-->
</style>

<script type="text/javascript" src="../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
<!--INICIO LIGHTBOX EVOLUTION-->
  
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
 <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox(); 
    });
  </script>
 <!--FIN LIGHTBOX EVOLUTION--> 
  <!--INICIO MENU HORIZONTAL-->
  
<link rel="stylesheet" type="text/css" href="../../libreria_publica/menu_horizontal/ddsmoothmenu-v.css"/>  
<link rel="stylesheet" type="text/css" href="../../libreria_publica/menu_horizontal/ddsmoothmenu.css"/>
 
<script type="text/javascript" src="../../libreria_publica/menu_horizontal/ddsmoothmenu.js">

/***********************************************
* Smooth Navigational Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>

<script type="text/javascript">

ddsmoothmenu.init({
	mainmenuid: "smoothmenu1", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu', //class added to menu's outer DIV
	//customtheme: ["#1c5a80", "#18374a"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

ddsmoothmenu.init({
	mainmenuid: "smoothmenu2", //Menu DIV id
	orientation: 'v', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu-v', //class added to menu's outer DIV
	//customtheme: ["#804000", "#482400"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

</script>
<!--FIN MENU HORIZONTAL-->	
<script type="text/javascript" >

function CONFIRMAR(url)
{
	c=confirm('Seguro(a) Desea Eliminar este Contenido de Programa de Estudios');
	if(c)
	{
		d=confirm('Realmente seguro(a) que desea continuar?')
		if(d){window.location=url;}
	}
}
</script>


</head>
<body>
<h1 id="banner">Administrador - Programa de Estudios</h1>
<div id="smoothmenu1" class="ddsmoothmenu">
  <ul>
<li><a href="#">Programa Estudios</a>
	<ul>
        <li><a href="nvo_programa/nvo_programa_1.php?id_carrera=<?php echo $id_carrera;?>&cod_asignatura=<?php echo $cod_asignatura;?>&sede=<?php echo $sede;?>&lightbox[iframe]=true&lightbox[width]=800&lightbox[height]=550" class="lightbox">Nuevo Registro</a></li>
        
         <li><a href="carga_archivo_PE/carga_archivo_PE1.php?id_carrera=<?php echo $id_carrera;?>&cod_asignatura=<?php echo $cod_asignatura;?>&sede=<?php echo $sede;?>&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=400" class="lightbox">Carga de Archivo</a></li>
    </ul>
</li>
<li><a href="#">Opcion</a>
    	<ul>
        	 <li><a href="genera_pdf/genera_pdf.php?id_carrera=<?php echo $id_carrera;?>&cod_asignatura=<?php echo $cod_asignatura;?>&sede=<?php echo $sede;?>" target="_blank">genera pdf</a></li>
        </ul>
    </li>
</li>
<li><a href="../malla/ver_malla.php?id_carrera=<?php echo $id_carrera;?>&amp;sede=<?php echo $sede;?>">Volver a Mallas</a></li>
</ul>
<br style="clear: left" />
</div> 
<h3>Administre los Programas de la Carrera: <?php echo "($id_carrera) $nombre_carrera";?> - Asignatura: <?php echo "[$cod_asignatura] $nombre_asignatura";?></h3>
<div id="apDiv1" class="demo_jui">
  <table width="80%" border="1" align="center" class="display" id="example">
      <thead>
	    <tr>
	      <th>N</th>
          <th>id</th>
           <th>Tipo</th>
          <th>Num Unidad</th>
	      <th>Nombre Unidad</th>
          <th>Cantidad Horas Semana</th>
          <th>Contenido</th>
	      <th colspan="3">Opciones</th>
        </tr>
    </thead>
        <tbody>
	   <?php
if($continuar)	 
{
	   $cons="SELECT * FROM programa_estudio WHERE id_carrera='$id_carrera' AND cod_asignatura='$cod_asignatura' ORDER by numero_unidad, id_programa";
	   if(DEBUG){ echo"-->$cons<br>";}
	   $sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	   $num_registros=$sql->num_rows;
	   if($num_registros>0)
	   {
		   $contador=0;
		   $total_horas=0;
			while($PE=$sql->fetch_assoc())
			{
				$contador++;
				
				$id_programa=$PE["id_programa"];
				$tipo=$PE["tipo"];
				$numero_unidad=$PE["numero_unidad"];
				$nombre_unidad=$PE["nombre_unidad"];
				$cantidad_horas=$PE["cantidad_horas"];
				$contenido=$PE["contenido"];
				
				$total_horas+=$cantidad_horas;
				
				echo'<tr class="gradeB">
						<td>'.$contador.'</td>
						<td>'.$id_programa.'</td>
						<td>'.$tipo.'</td>
						<td>'.$numero_unidad.'</td>
						<td>'.$nombre_unidad.'</td>
						<td>'.$cantidad_horas.'</td>
						<td>'.$contenido.'</td>
						<td><a href="edita_programa/edita_programa_1.php?id_programa='.$id_programa.'&id_carrera='.$id_carrera.'&cod_asignatura='.$cod_asignatura.'&sede='.$sede.'&lightbox[iframe]=true&lightbox[width]=800&lightbox[height]=550" class="lightbox" title="Editar">Editar</a></td>
						<td><a href="elimina_programa/elimina_programa.php?id_programa='.$id_programa.'&id_carrera='.$id_carrera.'&cod_asignatura='.$cod_asignatura.'&sede='.$sede.'" title="Eliminar">Eliminar</a></td>
					 </tr>';
			}
			echo'<tr>
					<td colspan="5">Total</td>
					<td>'.$TOTAL_HORAS_PROGRAMA.' -> '.($TOTAL_HORAS_PROGRAMA/18).' x semana</td>
					<td colspan="3">&nbsp;</td>
				 </tr>';
		}
		else
		{
			echo'<tr>
					<td colspan="8">Sin Datos</td>
				 </tr>';
		}
		$sql->free();
	   $conexion_mysqli->close();
       ?>
        </tbody>
  </table>
  <div id="error">
  <?php
  if(isset($_GET["error"]))
  {
	  $error=$_GET["error"];
	  $msj="";
	  switch($error)
	  {
		  case"PE2":
		  	$msj="Contenido de Programa de Estudios Eliminado";
		  	break;
		  case"PE3":
		  	$msj="Fallo Al Eliminar Contenido de Programa de Estudios";
		  	break;	
		 	
	  }
	  echo" $msj";
  }
}//fin si continuar
  ?>
  </div>
</div>
</body>
</html>