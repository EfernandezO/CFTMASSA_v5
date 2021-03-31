<?php
//-----------------------------------------//
	require("../OKALIS/seguridad.php");
	require("../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="ALUMNO";
	$lista_invitados["privilegio"][]="jefe_carrera";
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="ex_alumno";
	$lista_invitados["privilegio"][]="matricula";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
$privilegio=$_SESSION["USUARIO"]["privilegio"];
switch($privilegio)
{
	case"admi_total":
		$permite_editar=true;
		$url_menu="../Administrador/ADmenu.php";
		$num_opciones=8;
		$mostrar_resultados=true;
		break;
	case"admi":
		$permite_editar=true;
		$url_menu="../Administrador/ADmenu.php";
		$num_opciones=8;
		$mostrar_resultados=true;
		break;
	case"finan":
		$permite_editar=false;
		$url_menu="../contabilidad/index.php";
		$num_opciones=5;
		$mostrar_resultados=false;
		break;
	case"ALUMNO":
		$permite_editar=false;
		$url_menu="../Alumnos/alumno_menu.php";
		$num_opciones=5;
		$mostrar_resultados=false;
		break;
	case"Docente":
		$permite_editar=false;
		$url_menu="../Docentes/okdocente.php";
		$num_opciones=5;
		$mostrar_resultados=false;
		break;	
	case"jefe_carrera":
		$permite_editar=false;
		$url_menu="../Docentes/okdocente.php";
		$num_opciones=5;
		$mostrar_resultados=true;
		break;
	case"ex_alumno":
		$permite_editar=false;
		$url_menu="../ex_alumnos/ex_alumnos_MENU.php";
		$num_opciones=5;
		$mostrar_resultados=false;
		break;	
}

if(isset($_SESSION["ENCUESTA"]["contestada"]))
{ unset($_SESSION["ENCUESTA"]["contestada"]);}


$continuar_1=false;
$continuar_2=false;


if(isset($_GET["tipo_usuario"]))
{ $continuar_1=true;}

if(isset($_GET["id_usuario"]))
{ $continuar_2=true;}


if(isset($_GET["semestre_evaluar"]) and(isset($_GET["year_evaluar"])))
{
	$semestre_evaluar=base64_decode($_GET["semestre_evaluar"]);
	$year_evaluar=base64_decode($_GET["year_evaluar"]);
}
else
{
	$year_actual=date("Y");
	$mes_actual=date("m");
	
	if($mes_actual>=8){ $semestre_actual=2;}
	else{$semestre_actual=1;}
	
	$semestre_evaluar=$semestre_actual;
	$year_evaluar=$year_actual;
}


if($continuar_1 and $continuar_2)
{
	$realizar_encuesta_X_tercero=true;
	if(DEBUG){ echo"HAY GET<br>";}
	$tipo_usuario=$_GET["tipo_usuario"];
	$id_usuario=base64_decode($_GET["id_usuario"]);
	$id_carrera=base64_decode($_GET["id_carrera"]);
	$sede_evaluar=base64_decode($_GET["sede_evaluar"]);
	
}
else
{
	$realizar_encuesta_X_tercero=false;
	if(DEBUG){ echo"No hay Get<br>";}
	$tipo_usuario=$_SESSION["USUARIO"]["tipo"];
	$id_usuario=$_SESSION["USUARIO"]["id"];
	$sede_evaluar=$_SESSION["USUARIO"]["sede"];

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../funciones/codificacion.php");?>
<title>Menu encuestas -&gt; Revision</title>
	<link rel="stylesheet" type="text/css" href="../libreria_publica/jquery_treeview/demo/screen.css">
    <link rel="stylesheet" type="text/css" href="../libreria_publica/hint.css-master/hint.css"/>
    	<script type="text/javascript" src="../libreria_publica/jquery_libreria/jquery.min_1.2.6.js"></script>
<!--INICIO MENU HORIZONTAL-->
  <link rel="stylesheet" type="text/css" href="../libreria_publica/menu_horizontal/ddsmoothmenu.css"/>
 
<script type="text/javascript" src="../libreria_publica/menu_horizontal/ddsmoothmenu.js">

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
	<style type="text/css" title="currentStyle">
			@import "../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_page.css";
			@import "../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_table_jui.css";
			@import "../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/examples/examples_support/themes/smoothness/jquery-ui-1.8.4.custom.css";
			
			
    </style>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:52px;
	z-index:1;
	left: 5%;
	top: 163px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:23px;
	z-index:2;
	left: 30%;
	top: 114px;
	text-align: center;
	font-size: medium;
	font-weight: bold;
	border: thin dashed #1E78C3;
}
-->
</style>
<script type="text/javascript" language="javascript" src="../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="ISO-8859-1">
			$(document).ready(function() {
				oTable = $('#example').dataTable({
					"bJQueryUI": true,
					"bPaginate": false
				});
			} );
function CONFIRMAR(url)
{
	c=confirm('Seguro(a) Desea Eliminar esta encuesta');
	if(c)
	{
		window.location=url;
	}
}
</script>
</head>

<body>
<h1 id="banner"> Gesti&oacute;n Encuestas</h1>
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Gestion Encuesta</a>
	<ul>
    	 <li><a href="gestion_encuesta.php">Editar</a></li>
    </ul>
</li>
    <li><a href="<?php echo $url_menu;?>">Menu Principal</a></li>
</ul>
<br style="clear: left" />
</div>
</div>
<div id="apDiv2">Encuestas Disponibles<br />
</div>
<div id="apDiv1" class="demo_jui">
 
    <table width="100%" border="1" align="center" class="display" id="example">
        <thead>
          <tr>
            <th>N</th>
            <th>id</th>
            <th>Nombre</th>
            <th>Descripcion</th>
            <th>Opc</th>
          </tr>
          </thead>
        <tbody>
          <?php
	  require("../../funciones/conexion_v2.php");
	  include("../../funciones/VX.php");
	  //----------------------------------//
	  $evento="Ingreso a Revision Encuestas";
	  REGISTRA_EVENTO($evento);
	   //----------------------------//
	   
	   $cons="SELECT * FROM encuestas_main ORDER by id_encuesta Desc";
	   $sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	   $num_encuestas=$sql->num_rows;
	   if($num_encuestas>0)
	   {
		   $contador=0;
			while($C=$sql->fetch_assoc())
			{
				$contador++;
				
				$visible_alumno=$C["visible_alumno"];
				$visible_docente=$C["visible_docente"];
				$visible_jefe_carrera=$C["visible_jefe_carrera"];
				$visible_exalumno=$C["visible_exalumno"];
				
				if(empty($visible_alumno)){ $visible_alumno="off";}
				if(empty($visible_docente)){ $visible_docente="off";}
				if(empty($visible_jefe_carrera)){ $visible_jefe_carrera="off";}
				if(empty($visible_exalumno)){ $visible_exalumno="off";}
				
				if(DEBUG){ echo"Visible alumno: $visible_alumno<br>Visible docente: $visible_docente<br>visible exalumno: $visible_exalumno<br>";}
				
				$id_encuesta=$C["id_encuesta"];
				$nombre_encuesta=$C["nombre"];
				$descripcion_encuesta=substr($C["descripcion"],0,50)."...";
				$fecha_generacion_encuesta=$C["fecha_generacion"];
				$cod_user_encuesta=$C["cod_user"];
				
				$cons_YC="SELECT COUNT(id_resultados) FROM encuestas_resultados WHERE id_usuario='$id_usuario' AND tipo_usuario='$tipo_usuario' AND id_encuesta='$id_encuesta'";
				$sql_YC=$conexion_mysqli->query($cons_YC)or die($conexion_mysqli->error);
					$Dyc=$sql_YC->fetch_row();
					$num_resultados=$Dyc[0];
				$sql_YC->free();
				if(empty($num_resultados)){ $num_resultados=0;}
				if(DEBUG){ echo"--->$cons_YC<br> num resultados: $num_resultados<br>";}
				
				if($num_resultados>0)
				{ $encuesta_ya_contestada=true;}
				else
				{ $encuesta_ya_contestada=false;}
				
				
				switch($privilegio)
				{
					case"ALUMNO":
						if($visible_alumno=="on"){ $ver_encuesta=true;}
						else{ $ver_encuesta=false;}
						break;
					case"Docente":
						if($visible_docente=="on"){ $ver_encuesta=true;}
						else{ $ver_encuesta=false;}
						break;
					case"jefe_carrera":
						if($visible_jefe_carrera=="on"){ $ver_encuesta=true;}
						else{ $ver_encuesta=false;}
						break;	
					case"ex_alumno":
						if($visible_exalumno=="on"){ $ver_encuesta=true;}
						else{ $ver_encuesta=false;}
						break;
					default:
						if(DEBUG){ echo"Privilegio de Administrador en Uso<br>";}
						if($realizar_encuesta_X_tercero)
						{
							if(DEBUG){ echo"Realiza Encuesta X Tercero: Si<br>Tipo: $tipo_usuario<br>";}
							switch($tipo_usuario)
								{
									case"alumno":
										if($visible_alumno=="on"){ $ver_encuesta=true; if(DEBUG){ echo"Visible para alumno -> Si<br>";}}
										else{ $ver_encuesta=false; if(DEBUG){ echo"Visible para alumno -> No<br>";}}
										break;
									case"docente":
										if($visible_docente=="on"){ $ver_encuesta=true;}
										else{ $ver_encuesta=false;}
										break;
									case"jefe_carrera":
										if($visible_jefe_carrera=="on"){ $ver_encuesta=true;}
										else{ $ver_encuesta=false;}
										break;	
									case"ex_alumno":
										if($visible_exalumno=="on"){ $ver_encuesta=true; if(DEBUG){ echo"Visible para EX alumno -> Si<br>";}}
										else{ $ver_encuesta=false; if(DEBUG){ echo"Visible para EX alumno -> No<br>";}}
										break;
								}
				
						}else{$ver_encuesta=true; if(DEBUG){ echo"Realiza Encuesta X Tercero: NO<br>";}}
				}
				
				
				
				if($ver_encuesta)
				{
				echo'<tr class="gradeA" height="35">
						<td>'.$contador.'</td>
						<td>'.$id_encuesta.'</td>
						<td>'.$nombre_encuesta.'</td>
						<td>'.$descripcion_encuesta.'</td>';
					
					
					echo'<td align="center">';
					
						if($mostrar_resultados){ echo'<a href="resultados/ver_resultados.php?id_encuesta='.$id_encuesta.'" class="button_R">Resultados</a> - ';}
						if($encuesta_ya_contestada)
						{ echo'<a href="#" class="button_R">Contestada</a>';}
						else
						{ echo'<a href="contestar_encuesta/index.php?id_encuesta='.base64_encode($id_encuesta).'&tipo_usuario='.base64_encode($tipo_usuario).'&id_usuario='.base64_encode($id_usuario).'&sede_evaluar='.base64_encode($sede_evaluar).'&semestre_evaluar='.base64_encode($semestre_evaluar).'&year_evaluar='.base64_encode($year_evaluar).'" class="button">Contestar</a>';}
						
						echo'</td>
					 </tr>';
				}
			}
		}
		$sql->free();
	   $conexion_mysqli->close();
       ?>
          </tbody>
        </table>
    <div id="msj">
</div>
</div>
</body>
</html>