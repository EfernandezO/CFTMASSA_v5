<?php
 //-----------------------------------------//
	require("../../../OKALIS/seguridad.php");
	require("../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="Docente";
	$lista_invitados["privilegio"][]="jefe_carrera";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//
///verifica alumno este activo
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{ $alumno_activo=$_SESSION["SELECTOR_ALUMNO"]["ACTIVO"];}
else{ $alumno_activo=false;}
/////
///iconos segun sexo
$icono["M"]='<img src="../../../BAses/Images/male_user_icon.png" alt=":D" width="100" height="100" />';
$icono["F"]='<img src="../../../BAses/Images/female_user_icon.png" alt=":D" width="100" height="100" />';

if(isset($_SESSION["SELECTOR_ALUMNO"]["sexo"]))
{ $sexo_alumno=trim($_SESSION["SELECTOR_ALUMNO"]["sexo"]);}
else{ $sexo_alumno="M";}

if(empty($sexo_alumno))
{ $sexo_alumno="M";}


$icono_alumno=$icono[$sexo_alumno];
//////////////////////////////////////
include("../../../../funciones/conexion_v2.php");
include("../../../../funciones/funcion.php");
$id_usuario_activo=$_SESSION["USUARIO"]["id"];
/////////////////////////////////////////////////////////////
	/////-----------------------------
	//session para el CHAT
	/////----------------------------------
	$_SESSION["CHAT"]['nick'] = $_SESSION["USUARIO"]["nick"]; // Must be already set
	//busco usuarios activos
	include("../../../../funciones/VX.php");
	//cambio estado_conexin USER-----------
	 CAMBIA_ESTADO_CONEXION($id_usuario_activo, "on");
	$array_usuarios_activos=USUARIOS_ACTIVOS($id_usuario_activo);
//------------------------------------------

//////////////////////////////////
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>HALL | Docentes</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css">
<!--INICIO -->

  <script type="text/javascript" src="../../../libreria_publica/jquery_libreria/mootools-yui-compressed.js"></script>
  <script type="text/javascript" src="../../../libreria_publica/sexy_lightbox/Mootools/sexylightbox.v2.3.mootools.min.js"></script>
  <link rel="stylesheet" href="../../../libreria_publica/sexy_lightbox/Mootools/sexylightbox.css" type="text/css" media="all" />
  <script type="text/javascript">
    window.addEvent('domready', function(){
      SexyLightbox = new SexyLightBox({color:'black', dir: '../../../libreria_publica/sexy_lightbox/Mootools/sexyimages'});
    });
  </script>
<!--FIN -->

 <script type="text/javascript" src="../../../libreria_publica/jquery_libreria/jquery_1_3_2.min.js"></script>
 <!--INICIO MENU HORIZONTAL-->
  <link rel="stylesheet" type="text/css" href="../../../libreria_publica/menu_horizontal/ddsmoothmenu.css"/>
 
<script type="text/javascript" src="../../../libreria_publica/menu_horizontal/ddsmoothmenu.js">

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
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:40%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 112px;
}
#apDiv2 {
	position:absolute;
	width:45%;
	height:500px;
	z-index:2;
	left: 50%;
	top: 112px;
	overflow: auto;
}
-->
</style>
<style type="text/css">
<!--
.Estilo2 {
	font-size: 12px;
	font-weight: bold;
}
.Estilo4 {font-size: 12px; font-style: italic; }
.Estilo5 {font-size: 12px}
-->
</style>
<script language="javascript">
function REDIRIGIR(url, msj)
{
	c=confirm(msj);
	if(c)
	{
		window.location=url;
	}
}
</script>
</head>

<body>
<h1 id="banner">Gesti&oacute;n de Alumnos para DOCENTES - HALL V 2.1</h1>
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Alumno</a>
    <ul>
        <li><a href="../index.php">Seleccionar</a></li>
    </ul>
</li>
<li><a href="#">Informes</a>
    <ul>
       <?php if(isset($_SESSION["SELECTOR_ALUMNO"]["id"])){?> <li><a href="../../../Certificados/informe_general/informe_general_para_docentes.php" target="_blank">Informe General</a></li><?php }?>
    </ul>
</li>
<li><a href="#">Registros</a>
    <ul>
    <?php if(isset($_SESSION["SELECTOR_ALUMNO"]["id"])){?>
        <li><a href="../../../registro_observaciones/observaciones/hoja_vida.php">Hoja de Vida</a></li>
         <li><a href="../../../Notas_parciales_3/informe_notas_alumno_para_docentes/ver_notas_parciales_v3_1.php">Notas Parciales V3</a></li>
         <?php }?>
    </ul>
</li>
<li><a href="#">ON-line</a>
	<ul>
    	<?php
		if(isset($array_usuarios_activos))
		{
			if($array_usuarios_activos[0]!="No hay usuarios")
			{
				foreach($array_usuarios_activos as $nua=>$valorua)
				{
					echo'<li><a href="#">'.$valorua.' on-line</a></li>';
				}
			}
			else
			{
				echo'<li><a href="#">No hay Usuarios Conectados :(</a></li>';
			}
		}
		else
		{
			echo'<li><a href="#">No hay Usuarios Conectados :[</a></li>';
		}
        ?>
    </ul>
</li>
<li><a href="../volver_menu.php">Volver al Menu</a></li>
<li><a href="../../../OKALIS/msj_error/salir.php">Salir</a></li>
</ul>
<br style="clear: left" />
</div> 
<div id="apDiv1">
  <table width="100%" border="0">
  <thead>
    <tr>
      <th colspan="3"><span class="Estilo2">Alumno Actual</span></th>
      <th width="18%" ><div align="right">
      <?php if((isset($_SESSION["ULTIMO_ALUMNO"])and($_SESSION["ULTIMO_ALUMNO"]["ACTIVO"]))){?>
      <a href="../ultimo_alumno_seleccionado.php" title="Ultimo Alumno Seleccionado"><img src="../../../BAses/Images/atras.png" width="16" height="16" alt="&lt;" /></a>
      <?php }?>
      <a href="../index.php">
      <img src="../../../BAses/Images/icono_alumnos.gif" width="20" height="20" alt="cambiar"  title="Cambiar Alumno"/>
      </a>
      <a href="../deseleccionar_alumno.php" title="Deseleccionar Alumno">
      <img src="../../../BAses/Images/b_drop.png" alt="X" width="16" height="16" />
      </a>
      </div></th>
    </tr>
    </thead>
    <tbody>
    <tr class="odd">
      <td width="17%" rowspan="3"><?php echo $icono_alumno; ?></td>
      <td width="18%"><span class="Estilo2">ID</span></td>
      <td colspan="2"><span class="Estilo4"><?php if(isset($_SESSION["SELECTOR_ALUMNO"]["id"])){ echo $_SESSION["SELECTOR_ALUMNO"]["id"]; } ?></span></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo2">Rut</span></td>
      <td colspan="2"><span class="Estilo4"><?php if(isset($_SESSION["SELECTOR_ALUMNO"]["rut"])){ echo $_SESSION["SELECTOR_ALUMNO"]["rut"]; } ?></span></td>
    </tr>
    <tr class="odd">
      <td><span class="Estilo2">Alumno</span></td>
      <td colspan="2"><span class="Estilo4"><?php if(isset($_SESSION["SELECTOR_ALUMNO"]["nombre"])){ echo $_SESSION["SELECTOR_ALUMNO"]["nombre"]." ".$_SESSION["SELECTOR_ALUMNO"]["apellido"]; } ?></span></td>
    </tr>
    <tr class="odd">
      <td>&nbsp;</td>
      <td><span class="Estilo2">Carrera</span></td>
      <td colspan="2"><span class="Estilo4"><?php if(isset($_SESSION["SELECTOR_ALUMNO"]["carrera"])){ echo $_SESSION["SELECTOR_ALUMNO"]["carrera"]." <strong>".$_SESSION["SELECTOR_ALUMNO"]["nivel"]."</strong>"; } ?> 
      </span></td>
    </tr>
    <tr class="odd">
      <td>&nbsp;</td>
      <td class="Estilo2">Condicion</td>
      <td colspan="2"><span class="Estilo4"><?php if(isset($_SESSION["SELECTOR_ALUMNO"]["situacion"])){ echo $_SESSION["SELECTOR_ALUMNO"]["situacion"]." [".$_SESSION["SELECTOR_ALUMNO"]["ingreso"]."-";} if(isset($_SESSION["SELECTOR_ALUMNO"]["egreso"])){ echo $_SESSION["SELECTOR_ALUMNO"]["egreso"]."]"; }else{ echo"";} ?></span></td>
    </tr>
    </tbody>
  </table>
</div>
<div id="apDiv2">
   <table width="100%">
      <thead>
      <tr>
      	<th colspan="5">Observaciones</th>
      </tr>
        </thead>
        <tbody>
        <?php
		if(isset($_SESSION["SELECTOR_ALUMNO"]))
		{
		if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
		{
			$cons_HV="SELECT * FROM hoja_vida WHERE id_alumno='".$_SESSION["SELECTOR_ALUMNO"]["id"]."' ORDER by fecha desc";
			if(DEBUG){ echo"-->$cons_HV<br>";}
			$sql_HV=mysql_query($cons_HV)or die(mysql_error());
			$num_observaciones=mysql_num_rows($sql_HV);
			if($num_observaciones>0)
			{
				$contador=1;
				while($HV=mysql_fetch_assoc($sql_HV))
				{
					$id_observacion=$HV["id"];
					$observacion=$HV["observacion"];
					$fecha=$HV["fecha"];
					$id_user=$HV["id_user"];
					////////////////////
						$cons_user="SELECT nombre, apellido FROM personal WHERE id ='$id_user'";
						$sql_user=mysql_query($cons_user) or die(mysql_error());
						$DU=mysql_fetch_assoc($sql_user);
						$usuario_nombre=$DU["nombre"];
						$usuario_apellido=$DU["apellido"];
						$usuario_nombre=$usuario_nombre." ".$usuario_apellido;
						mysql_free_result($sql_user);
					//////////////////////
					$tipo_visualizacion=$HV["tipo_visualizacion"];
					echo'<tr>
						  <td>'.$contador.'</td>
						  <td>'.fecha_format($fecha).' '.$tipo_visualizacion.'</td>
						  <td>'.$observacion.'</td>
						  <td><a href="#" title="'.$usuario_nombre.'">'.$id_user.'</a></td>
						  </tr>';
						$contador++;
				}
				mysql_free_result($sql_HV);
			}
			else
			{  echo'<tr><td colspan="7">Sin observaciones Registradas...</td></tr>';}
			
		}
		}
		?>
          </tbody>
        
        <tr>
        <td colspan="7"><div align="right">
        <a href="#"><img src="../../../BAses/Images/add.png" alt="[+]" width="15" height="15" /></a>
        </div></td>
        </tr>
      </table>
</div>
</body>
<?php if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])){ mysql_close($conexion);}?>
</html>