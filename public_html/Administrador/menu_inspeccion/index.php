<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	OKALIS($lista_invitados);
//-----------------------------------------//	
define("DEBUG", false);
/////-----------------------------
	//session para el CHAT
	require("../../../funciones/conexion_v2.php");
	/////----------------------------------
	$_SESSION["CHAT"]["nick"] = $_SESSION["USUARIO"]["nick"]; // Must be already set
	//var_export($_SESSION["CHAT"]);
	//busco usuarios activos
	include("../../../funciones/VX.php");
	$id_usuario_activo=$_SESSION["USUARIO"]["id"];
	//cambio estado_conexin USER-----------
	 CAMBIA_ESTADO_CONEXION($id_usuario_activo, "on");
	$array_usuarios_activos=USUARIOS_ACTIVOS($id_usuario_activo);
	//------------------------------------------
/////////////////////////////////////////////////////////////
	//verificando Mensaje
	
	$cons_M="SELECT COUNT(id) FROM mensajes WHERE destinatario='$id_usuario_activo' AND leido='no'";
	$sql_M=mysql_query($cons_M)or die("mensajes".mysql_error());
	$D_M=mysql_fetch_row($sql_M);
	$mensajes_no_leidos=$D_M[0];
	if(empty($mensajes_no_leidos))
	{ $mensajes_no_leidos=0;}
	mysql_free_result($sql_M);
	if($mensajes_no_leidos>0)
	{ 
		$mensaje_label="($mensajes_no_leidos) Mensaje(s) sin Leer";
		$img_msj='../../BAses/Images/globo_rojo.png';
	}
	else
	{ 
		$mensaje_label="No hay Mensajes Nuevos";
		$img_msj='../../BAses/Images/globo_verde.png';
	}
	$destino='../../Mensajes/ver_mjs/ver_mjs.php';
	$icono_MSJ='<a href="'.$destino.'" onMouseover="ddrivetip(\''.$mensaje_label.'\', 160)"; onMouseout="hideddrivetip()"><img src="'.$img_msj.'" alt="Msj" width="25" /></a> ';
	mysql_close($conexion);
	//////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Menu Inspeccion</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<style type="text/css">
<!--
a:link {
	color: #3399FF;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #3399FF;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #3399FF;
}
.Estilo1 {
	font-size: 12px;
	font-weight: bold;
}
#link {
	text-align: right;
	padding-right: 10px;
}
-->
    </style>
   <link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/jquery.treeview.css">
	<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
	
    <link href="../../red-treeview.css" rel="stylesheet" type="text/css" />
	
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>
	
	<script src="../../libreria_publica/jquery_treeview/lib/jquery.cookie.js" type="text/javascript"></script>
	<script src="../../libreria_publica/jquery_treeview/jquery.treeview.js" type="text/javascript"></script>
	
	<script type="text/javascript">
		$(function() {
			$("#browser").treeview();
		});
	</script>
    <script src="../../libreria_publica/tooldtips/dhtml_tooldtips.js" type="text/javascript"></script>
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
</script>
<!--FIN MENU HORIZONTAL-->
    <style type="text/css">
<!--
.Estilo3 {font-size: 12px}
-->
    </style>
</head>

<body>
<h1 id="banner"> Men&uacute; Principal - Inspecci&oacute;n</h1>
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Utilidades</a>
  <ul>
  <li><a href="#">Plantillas de Impresion</a>
    <ul>
    <li><a href="../../utilidades_varias/lomo_archivador/lomo_archivador_1.php">Lomo Archivador</a></li>
    </ul>
  </li>
  <li><a href=" ../../agenda_contactos/index.php">Agenda de Contactos</a></li>
  </ul>
</li>
<li><a href="#">Configuración</a>
	<ul>
    	<li><a href="../mis_datos/index.php">Mis Datos</a></li>
    </ul>
</li>
<div id="div_user_conectados">
<li><a href="#">ON-line</a>
	
	<ul>
    	<?php
		if(isset($array_usuarios_activos))
		{
			if($array_usuarios_activos[0]!="No hay usuarios")
			{
				foreach($array_usuarios_activos as $nua=>$valorua)
				{
					echo'<li><a href="javascript:void(0)" onclick="javascript:chatWith(\''.$valorua.'\')">Chat con '.$valorua.'</a></li>';
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
 </div>
</li>
<li><a href="../../OKALIS/msj_error/salir.php">Salir</a></li>
</ul>
<br style="clear: left" />
</div>
<div id="link"><?php echo $icono_MSJ;?><a href="index.php" target="_blank" title="Expandir"><img src="../../BAses/Images/expandir.png" width="15" height="15" /></a> <a href="#" onclick="javascript:window.close();" title="Contraer"><img src="../../BAses/Images/contraer.png" width="15" height="15" /></a></div>

<?php
//var_export($_SESSION["USUARIO"]);
$user=$_SESSION["USUARIO"]["nombre"]." ".$_SESSION["USUARIO"]["apellido"];
?>
<div id="user"><h3><strong><span class="Estilo3">Bienvenido</span> Sr. <?php echo $user;?></strong></h3>
</div>
<div id="main">
  <ul id="browser" class="filetree"><li class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /> <a href="../../informes/alumnos_curso_contrato/index.php">Alumnos por Curso </a></li>
    <li class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /> <a href="../../informes/resumen_general/resumen_general_x.php">Resumen General Matriculados</a><img src="../../BAses/Images/NEW.gif" alt="" width="31" height="16" /></li>
	  <li class="Estilo1"><img src="../../libreria_publica/jquery_treeview/images/folder.gif" alt="w" /> <a href="../../Mensajes/index.php">Menu Mensajes</a> </li>
	  <li class="Estilo1"><img src="../../libreria_publica/jquery_treeview/images/folder.gif" alt="e" /> Menu Finanzas<ul><li><a href="../../contabilidad/informe_contratos_del_dia/index.php"><span class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /></span></a> <a href="../../informes/alumnos_y_sus_cuotas/index.php">Alumnos y sus Cuotas</a></li>
	    <li><a href="../../contabilidad/informe_contratos_del_dia/index.php"><span class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /></span></a><a href="../../contabilidad/informe_deudores_mensualidad/index.php"> Alumnos y sus deudas</a></li>
	    <li><a href="../../contabilidad/informe_contratos_del_dia/index.php"><span class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /></span></a> <a href="../../contabilidad/estado_financiero_historico_alumno/index.php">Alumno Deuda y pagos </a></li>
      	  <li><a href="../../contabilidad/caja/index.php"><span class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /></span> Caja Diaria</a></li>
   	      <li><a href="../../contabilidad/informe_financiero_alumno/index.php"><span class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /></span> Estado Financiero Alumno</a></li>
   	      <li><a href="../../contabilidad/informe_pagos/index.php"><span class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /></span> Informe Caja </a></li>
        <li><a href="../../contabilidad/caja/index.php"><span class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /></span></a> <a href="../../contabilidad/informe_pagos_x_rango/index.php">Informe Ingresos-egresos</a></li>
      	<li><a href="../../contabilidad/caja/index.php"><span class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /></span></a> <a href="../../contabilidad/informe_resumen_item/index.php">Resum&eacute;n por Item</a></li>
      	<li><a href="../../contabilidad/cheque/index.php"><span class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /></span> Registro Cheques</a></li>
      	<li><a href="../../contabilidad/informe_contratos_del_dia/index.php"><span class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /></span> Matriculas Generadas</a></li>
      	<li><a href="../../contabilidad/informe_contratos_del_dia/index.php"><span class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /></span></a> <a href="../../contabilidad/informe_matriculas_estadisticas/index.php">Comparativo de Matriculas</a><img src="../../BAses/Images/NEW.gif" alt="" width="31" height="16" /></li>
      	<li><a href="../../contabilidad/informe_contratos_del_dia/index.php"><span class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /></span></a> <a href="../../contabilidad/presupuesto/menu_presupuesto.php">Presupuestos V1.0</a></li>
        <li><a href="../../contabilidad/informe_contratos_del_dia/index.php"><span class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /></span></a> <a href="../../contabilidad/balance/proyecciones_v3/proyeccion_1.php">Proyecciones</a></li>
        <li><a href="../../contabilidad/informe_contratos_del_dia/index.php"><span class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /></span></a> <a href="../../contabilidad/informe_cuotas_con_deuda_X_mes/index.php">Detalle Cuotas Vencimiento x Mes (Detalle Proyecciones)</a></li>
        <li><a href="../../contabilidad/informe_contratos_del_dia/index.php"><span class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /></span></a> <a href="../../contabilidad/informe_alumno_beca_new/index.php">Alumnos Beca y Desc.</a></li>
        <li><a href="../../contabilidad/informe_contratos_del_dia/index.php"><span class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /></span></a> <a href="../../contabilidad/deudores_mensualidad/listador_deudores/index.php">Listador Morosos</a></li>
        <li><a href="../../contabilidad/informe_contratos_del_dia/index.php"><span class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /></span></a> <a href="../../contabilidad/informe_cuotas_alumno_contrato/index.php">Alumno X Curso y Cuotas</a></li>
        </ul>
    </li>
	  <li class="Estilo1"><img src="../../libreria_publica/jquery_treeview/images/folder.gif" alt="e" /> Libro de Ventas
	    <ul>
	      <li><a href="../../contabilidad/informe_contratos_del_dia/index.php"><span class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /></span></a> <a href="../../contabilidad/libro_venta/detalle/index.php">Detalle</a></li>
	      <li><a href="../../contabilidad/informe_contratos_del_dia/index.php"><span class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /></span></a><a href="../../contabilidad/libro_venta/por_item/index.php"> X item</a></li>
        </ul>
      </li>
  <li class="Estilo1"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="e" width="15" height="14" />
	    <a href="../../historial/index.php"><strong>Historial</strong></a></li>
    <li class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/folder.gif" alt="t" /> <a href="../../agenda_contactos/index.php">Agenda de Contactos</a>
    <li class="closed Estilo1"><img src="../../libreria_publica/jquery_treeview/images/folder.gif" alt="e" /> Encuestas
      <ul>
        <li><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /> <a href="../../Alumnos/encuestas/tareas_admin/realizaron_encuesta/encuestados.php">Alumnos Encuestados</a></li>
        <li><a href="../../contabilidad/informe_contratos_del_dia/index.php"><img src="../../libreria_publica/jquery_treeview/images/file.gif" alt="7" /></a> <a href="../../Alumnos/encuestas/seleccion_encuesta.php">Resultado de Encuesta</a>    </li>
    </ul>    
</ul>
  <br />

</div>
</body>
</html>
