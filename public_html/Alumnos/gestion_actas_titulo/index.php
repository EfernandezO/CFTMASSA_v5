<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("gestion_actas_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(isset($_GET["edicion"]))
{
	$edicion=$_GET["edicion"];
	if($edicion=="activar"){ $habilitar_edicion=true;}
}
else
{$habilitar_edicion=false; $edicion="no";}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php"); ?>
<title>Actas</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<!--INICIO LIGHTBOX EVOLUTION-->
  <script type="text/javascript" src="../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
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
</script>
<!--FIN MENU HORIZONTAL-->
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/jquery.treeview.css"/>
	<script src="../../libreria_publica/jquery_treeview/lib/jquery.cookie.js" type="text/javascript"></script>
	<script src="../../libreria_publica/jquery_treeview/jquery.treeview.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
		// second example
	$("#browser").treeview({
		animated:"normal",
		persist: "off",
		control: "#treecontrol"
	});
});
</script>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 125px;
}
</style>
</head>

<body>
<h1 id="banner">Administrador - Actas</h1>
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Gestion</a>
	<ul>
    	 <li><a href="carga_actas/cargar_acta_1.php?lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=510"  class="lightbox">Cargar Acta</a></li>
    </ul>
</li>
    <li><a href="#">Edicion</a>
      <ul>
        <li>
			<?php if($habilitar_edicion){?><a href="index.php?edicion=no">Desactivar Edicion</a>
            <?php }else{?><a href="index.php?edicion=activar">Activar Edicion</a>
			<?php }?>
        <li>
    </ul>
    </li>
   
    
    </li>
    <li><a href="../menualumnos.php">Menu Principal</a></li>
</ul>
<br style="clear: left" />
</div>
<div id="apDiv1">
<?php
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	$cons="SELECT * FROM actas";
	$sqli=$conexion_mysqli->query($cons);
	$num_actas=$sqli->num_rows;
	
	$ARRAY_DATOS=array();
	if($num_actas>0)
	{
		
		while($AC=$sqli->fetch_assoc())
		{
			$AC_id=$AC["id_acta"];
			$AC_id_carrera=$AC["id_carrera"];
			$AC_semestre=$AC["semestre"];
			$AC_year=$AC["year"];
			$AC_archivo=$AC["archivo"];
			$AC_observacion=$AC["observacion"];
			$AC_sede=$AC["sede"];
			$AC_tipo=$AC["tipo"];
			$AC_jornada=$AC["jornada"];
			$AC_nivel=$AC["nivel"];
			
			$ARRAY_DATOS[$AC_sede][$AC_year][$AC_id_carrera][$AC_id]["tipo"]=$AC_tipo;
			$ARRAY_DATOS[$AC_sede][$AC_year][$AC_id_carrera][$AC_id]["archivo"]=$AC_archivo;
			$ARRAY_DATOS[$AC_sede][$AC_year][$AC_id_carrera][$AC_id]["observacion"]=$AC_observacion;
			$ARRAY_DATOS[$AC_sede][$AC_year][$AC_id_carrera][$AC_id]["jornada"]=$AC_jornada;
			$ARRAY_DATOS[$AC_sede][$AC_year][$AC_id_carrera][$AC_id]["semestre"]=$AC_semestre;
			$ARRAY_DATOS[$AC_sede][$AC_year][$AC_id_carrera][$AC_id]["nivel"]=$AC_nivel;
			
			
			if(DEBUG){ echo"id_acta: $AC_id semeste: $AC_semestre year: $AC_year sede: $AC_sede id_carrera: $AC_id_carrera tipo: $AC_tipo Nivel: $AC_nivel<br>";}
		}
	}
	$sqli->free();
	
	$conexion_mysqli->close();
	
	if(DEBUG){ var_dump($ARRAY_DATOS);}
	//------------------------------------------------------//
	
	$arbol='<h3>Actas de Titulo y semestrales del CFT</h3>

	<div id="treecontrol">
		<a title="Contrae todo el arbol" href="#" class="button_R"> Contraer Todo</a> | 
		<a title="Expande todo el arbol" href="#" class="button_R"> Expandir Todo</a> 
	</div>';
	$arbol.='<ul id="browser" class="filetree"><li><span class="folder">ACTAS</span>
				<ul>';
	if($num_actas>0)			
	{
		foreach($ARRAY_DATOS as $aux_sede => $aux_array_1)
		{
			ksort($aux_array_1);
			if(DEBUG){echo"$aux_sede -> <br>";}
			$arbol.='<li class="closed"><span class="folder">'.$aux_sede.'</span>';
			$primera_vuelta_1=true;
			foreach($aux_array_1 as $aux_year => $aux_array_2)
			{
				ksort($aux_array_2);
				if(DEBUG){ echo"-> $aux_year  ";}
				if($primera_vuelta_1){$arbol.='<ul>'; $primera_vuelta_1=false;}
				
					$arbol.='<li class="closed"><span class="folder">'.$aux_year.'</span>';
					$primera_vuelta_2=true;
					foreach($aux_array_2 as $aux_id_carrera => $aux_array_3)
					{
						if($primera_vuelta_2){$arbol.='<ul>'; $primera_vuelta_2=false;}
						if(DEBUG){ echo"--> $aux_id_carrera  <br>";}
						$aux_nombre_carrera=NOMBRE_CARRERA($aux_id_carrera);
						$arbol.='<li class="closed"><span class="folder">'.$aux_id_carrera.'_'.$aux_nombre_carrera.'</span>';
						$primera_vuelta_3=true;
						
						$aux_1=0;
						$aux_2=0;
						foreach($aux_array_3 as $aux_id_acta => $aux_array_4)
						{
							if($primera_vuelta_3){$arbol.='<ul>'; $primera_vuelta_3=false;}
							$aux_tipo=$aux_array_4["tipo"];
							$aux_observacion=$aux_array_4["observacion"];
							$aux_archivo=$aux_array_4["archivo"];
							$aux_jornada=$aux_array_4["jornada"];
							$aux_semestre=$aux_array_4["semestre"];
							$aux_nivel=$aux_array_4["nivel"];
							
							if(DEBUG){ echo"--> $aux_id_acta  <br>";}
							
							if($aux_jornada=="V"){$aux_jornada_label="Vespertino";}
							else{ $aux_jornada_label="Diurno";}
							
							switch($aux_tipo)
							{
								case "titulo":
									$aux_1++;
									$nombre_acta_label="Acta $aux_tipo ($aux_1 ".$aux_id_acta.")";
									break;
								case "semestral":
									$aux_2++;
									$nombre_acta_label="Acta $aux_tipo Jornada $aux_jornada_label Nivel $aux_nivel Semestre $aux_semestre ($aux_2 ".$aux_id_acta.")";
									break;	
							}
							
							if($habilitar_edicion){ $boton_edicion='<a href="edicion/eliminar_acta.php?id_acta='.base64_encode($aux_id_acta).'&edicion='.$edicion.'&lightbox[iframe]=true&lightbox[width]=400&lightbox[height]=300"  class="lightbox" title="Eliminar Acta"><img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="eliminar" /></a>';}
							else{ $boton_edicion=''; }
							
							$arbol.='<li><span class="file"><a href="ver_acta.php?id_acta='.base64_encode($aux_id_acta).'&lightbox[iframe]=true&lightbox[width]=600&lightbox[height]=510"  class="lightbox"" title="'.$aux_observacion.'">'.$nombre_acta_label.'</a>'.$boton_edicion.'</span></li>';
						}
						if(!$primera_vuelta_3){$arbol.='</ul>';}
					}
					if(!$primera_vuelta_2){$arbol.='</ul>';}	

			}
			
			$arbol.='</li>';
			if(!$primera_vuelta_1){$arbol.='</ul>';}	
		}
		$arbol.='</ul>';
	}
	else
	{
		$arbol.='<li>Sin Actas Cargadas... :(</li></ul></ul>';
	}
	echo $arbol;
?>
</div>
</body>
</html>