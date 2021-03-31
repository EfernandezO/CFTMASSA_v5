<?php
//-----------------------------------------//
	require("../OKALIS/seguridad.php");
	require("../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="inspeccion";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../funciones/codificacion.php");?>
<?php
	$privilegios=$_SESSION["USUARIO"]["privilegio"];
	$id_usuario_activo=$_SESSION["USUARIO"]["id"];
	$sede=$_SESSION["USUARIO"]["sede"];
  		$nombre=$_SESSION["USUARIO"]["nombre"];
  		$apellido=$_SESSION["USUARIO"]["apellido"];
	#include("../../funciones/conexion_v2.php");
	/////-----------------------------
	//session para el CHAT
	/////----------------------------------
	if(isset($_SESSION["USUARIO"]["nick"]))
	{ $_SESSION["CHAT"]['nick'] = $_SESSION["USUARIO"]["nick"];}
	else{ $_SESSION["CHAT"]['nick'] = $_SESSION["USUARIO"]["rut"];}
	//busco usuarios activos
	include("../../funciones/VX.php");
	//cambio estado_conexin USER-----------
	 CAMBIA_ESTADO_CONEXION($id_usuario_activo, "on");
	$array_usuarios_activos=USUARIOS_ACTIVOS($id_usuario_activo);
	
//------------------------------------------

/////segun privilegios actuar
switch($privilegios)
{
	case"admi_total":
		$url_menu="../Administrador/ADmenu.php";
		$ver_presupuesto=true;
		$ver_edicion_matricula_arancel=true;
		$ver_edicion_mis_datos_finanzas=false;
		$ver_informes_alumno=true;
		$ver_gestion_empresas=true;
		$ver_gestion_becas=true;
		$ver_cuentas_corrientes=true;
		$ver_proveedores=true;
		break;
	case"inspeccion":
		$url_menu="../Administrador/menu_inspeccion/index.php";
		$ver_presupuesto=true;
		$ver_edicion_matricula_arancel=false;
		$ver_edicion_mis_datos_finanzas=false;
		$ver_informes_alumno=true;
		$ver_gestion_empresas=false;
		$ver_gestion_becas=false;
		$ver_cuentas_corrientes=false;
		$ver_proveedores=false;
		break;
	case"matricula":
		$url_menu="../Administrador/menu_matricula/index.php";
		$ver_presupuesto=false;
		$ver_edicion_matricula_arancel=false;
		$ver_edicion_mis_datos_finanzas=false;
		$ver_informes_alumno=true;
		$ver_gestion_empresas=false;
		$ver_gestion_becas=false;
		$ver_cuentas_corrientes=false;
		$ver_proveedores=true;
		break;
	case"finan":
		$url_menu="#";
		$ver_presupuesto=true;
		$ver_edicion_matricula_arancel=true;
		$ver_edicion_mis_datos_finanzas=true;
		$ver_informes_alumno=true;
		$ver_gestion_empresas=true;
		$ver_gestion_becas=true;
		$ver_cuentas_corrientes=true;
		$ver_proveedores=true;
		break;	
	default:
		$url_menu="#";
		$ver_presupuesto=false;	
		$ver_edicion_matricula_arancel=false;	
		$ver_edicion_mis_datos_finanzas=false;
		$ver_informes_alumno=false;
		$ver_gestion_empresas=false;
		$ver_gestion_becas=false;
		$ver_cuentas_corrientes=false;
		$ver_proveedores=false;
		
}
$fecha_servidor=date("d/m/Y H:i:s");
?>
<link rel="stylesheet" type="text/css" href="../libreria_publica/jquery_treeview/jquery.treeview.css">
	<link rel="stylesheet" type="text/css" href="../libreria_publica/jquery_treeview/demo/screen.css">
	
    <link href="../red-treeview.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../libreria_publica/jquery_libreria/jquery.min_1.2.6.js"></script>
	
	<script src="../libreria_publica/jquery_treeview/lib/jquery.cookie.js" type="text/javascript"></script>
	<script src="../libreria_publica/jquery_treeview/jquery.treeview.js" type="text/javascript"></script>
	
	<script type="text/javascript">
		$(function() {
			$("#browser").treeview();
		});
	</script>
<!--CHAT-->
<link rel="stylesheet" type="text/css" href="../chat/css/chat.css"/>
<script type="text/javascript" src="plugin_chat.js"></script>
<!--CHAT-->    
    
  <!--INICIO MENU HORIZONTAL-->
 <link rel="stylesheet" type="text/css" href="../libreria_publica/menu_horizontal/ddsmoothmenu-v.css"/>  
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

ddsmoothmenu.init({
	mainmenuid: "smoothmenu2", //Menu DIV id
	orientation: 'v', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu-v', //class added to menu's outer DIV
	//customtheme: ["#804000", "#482400"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

</script>
<!--FIN MENU HORIZONTAL-->	
<style type="text/css">
<!--
.Estilo1 {
	font-weight: bold;
	font-size: 12px;
}
.Estilo2 {font-size: 12px}
a:link {
	color: #3399FF;
	text-decoration: none;
}
a:visited {
	color: #3399FF;
	text-decoration: none;
}
a:hover {
	color: #F00;
	text-decoration: none;
}
a:active {
	color: #3399FF;
	text-decoration: none;
}
</style>
<title>Menu Finanzas</title>
</head>
<body>
<h1 id="banner">Administrador - Men&uacute; Finanzas </h1>
<div id="smoothmenu1" class="ddsmoothmenu">
  <ul>
<li><a href="#">Configuración</a>
	<ul>
    	<?php if($ver_edicion_mis_datos_finanzas){?>
       	<li><a href="edit_datos_solo_finan/edit_datos_finan.php">Mis Datos</a></li>
         <?php }if($ver_edicion_matricula_arancel){?>   
        <li><a href="../Edicion_carreras/index.php">Matricula Arancel</a></li>
        <?php }?>
        <?php if($ver_gestion_empresas){?>
        <li><a href="../Empresas/gestion/index.php">Gestion Empresas</a></li>
        <?php }?>
         <?php if($ver_gestion_becas){?>
        <li><a href="../becas/gestion/index.php">Gestion Becas</a></li>
        <?php }?>
         <?php if($ver_cuentas_corrientes){?>
        <li><a href="cuenta_corriente/listador_cuentas.php">Gestion Cta. cte</a></li>
        <?php }?>
          <?php if($ver_proveedores){?>
        <li><a href="proveedores/listar_proveedores.php">Proveedores</a></li>
        <?php }?>
    </ul>
</li>
<li><a href="#">Informes Financieros</a>
	<ul>
    <li><a href="#">de Alumno</a>
    <?php if($ver_informes_alumno){?>
    	<ul>
        	 <li><a href="informe_alumno_beca_V2/index.php">Alumnos Con Beneficios</a></li>
             <li><a href="estado_financiero_historico_alumno/index.php">Alumno y situacion financiera </a></li>
            <li><a href="../informes/alumnos_contrato_moroso_porcentaje/index.php">Alumnos Morosos Matriculados</a></li>
              <li><a href="informe_cuotas_alumno/index.php">Cuotas Alumno</a></li>
              <li><a href="informe_cuotas_alumno_contrato/index.php">Cuota Alumno (contrato)</a></li>
              <li><a href="../informes/alumnos_y_sus_cuotas/index.php">Alumnos con Cuotas</a></li>
            <li><a href="informe_moroso_total/informe_morosos_total_info_beca.php">info Morosos</a></li>
            <li><a href="informe_alumnos_con_excedente/index.php">Alumnos Con Excedente</a></li>
            <li><a href="informe_porcentajes_morosidad/index.php">Porcentaje Morosidad</a></li>
             <li><a href="informe_deudores_mensualidad/index.php">Informe Deudores de Mensualidades</a></li>
             <li><a href="deudores_mensualidad/listador_deudores/index.php">Alumnos Deudores</a></li>
             <li><a href="informe_pagos_especificos_x_rango_alumnos/index.php">Detalle Pagos especificos de alumno</a></li>
        </ul>
        <?php }?>
    </li>
    	
   		 <li><a href="balance/proyecciones_v3/proyeccion_1.php">Proyecciones V3</a></li>
          <li><a href="informe_cuotas_con_deuda_X_mes/index.php">Detalle Cuotas Vencimiento x Mes (Detalle Proyecciones)</a></li>
          <li><a href="informe_pagos/index.php">Informe Caja </a> </li>
          <li><a href="informe_pagos_x_rango/index.php">informe ingresos-egresos</a></li>
          <li><a href="caja/index.php">Caja Diaria</a></li>
          <li><a href="cheque/index.php">Registro Cheques</a></li>
          <li><a href="informe_contratos_del_dia/index.php">Matriculas Generadas</a></li>
          <li><a href="informe_matriculas_estadisticas/index.php">Comparativa de Matriculas</a></li>
          <li><a href="informe_resumen_item/index.php">Resum&eacute;n por Item</a></li>
        <li><a href="informe_ingresos_totales_X_mes/ingresos_totalesXmes.php">Ingresos X mes</a></li>
        <li><a href="informe_pagare_emitidos/index.php">Pagare Emitidos</a></li>
        <li><a href="flujo_de_caja/flujo_caja_1.php">Flujo de Caja</a></li>
        <li><a href="#">Libro de Ventas</a>
<ul>
       	    <li><a href="libro_venta/detalle/index.php">Detalle</a></li>
          		 <li><a href="libro_venta/por_item/index.php">X Item</a></li>
            </ul>
        </li>
        <?php if($ver_presupuesto){?>
        <li><a href="presupuesto/menu_presupuesto.php">Presupuesto</a></li>
        <?php }?>
    </ul>
</li>
<li><a href="#">Registros</a>
	<ul>
    	 <li><a href="boleta_sin_folio/index.php">Boleta Pendiente</a></li>
      	 <li><a href="facturas/registro/ver/ver_factura.php">Registro de Facturas</a></li>
         <li><a href="order_compra/revision/revisar.php">Orden Compra</a></li>
         <li><a href="registro_ingresos_No_boleta/index.php">Registro Ingresos/Egreso NO boleta</a></li>
         <li><a href="registro_ingresos_boleta_empresa/ingresos_boleta.php">Registro Ingresos empresas</a></li>
         <li><a href="gestion_cobranza/operaciones/cobranza_1.php">Cobranza Alumnos</a></li>
         <li><a href="registro_egresos/registra_egreso_1.php">Egresos</a></li>
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

</li>
 </div>
<li><a href="<?php echo $url_menu;?>">Menu Principal</a></li>
<li><a href="../OKALIS/msj_error/salir.php">Salir</a></li>
</ul>
<br style="clear: left" />
</div> 
<div id="link"><?php echo"<em>Fecha Servidor: $fecha_servidor</em>";?></div>
<h3>Sr(a).: <?php echo"$nombre $apellido<br> ";?> </h3>
<div id="main">
    <strong>Opciones Frecuentes - Finanzas</strong>
    <ul id="browser" class="filetree">
    <li class="Estilo1"><a href="contratos_old/index.php"><img src="../libreria_publica/jquery_treeview/images/file.gif" alt="a" /></a><a href="boleta_sin_folio/index.php">Boleta Pendiente</a></li>
      <li class="Estilo1"><strong><img src="../libreria_publica/jquery_treeview/images/file.gif" alt="a" /></strong><a href="facturas/registro/ver/ver_factura.php">Registro de Facturas</a></li>
      <li class="Estilo1"><strong><img src="../libreria_publica/jquery_treeview/images/file.gif" alt="a" /></strong><a href="registro_ingresos_No_boleta/index.php">Registro Ingresos NO boleta</a></li>
      <li class="Estilo1"><strong><img src="../libreria_publica/jquery_treeview/images/folder.gif" alt="a" width="16" height="14" /></strong> Informes
        <ul>
          <li><a href="balance/proyecciones_v3/proyeccion_1.php">Proyecciones de Ingreso Anuales X Arancel </a>V3</li>
          <li><a href="informe_detalle_cuotas/index.php">Detalle Cuotas  (Detalle Proyecciones)</a><img src="../BAses/Images/NEW.gif" alt="new" width="31" height="16" /></li>
          <li><a href="informe_pagos/index.php">Informe Caja </a> </li>
          <li><a href="informe_pagos_x_rango/index.php">informe ingresos-egresos</a></li>
          <li><a href="caja/index.php">Caja Diaria</a></li>
          <li><a href="cheque/index.php">Registro Cheques</a></li>
          <li><a href="informe_contratos_del_dia/index.php">Matriculas Generadas</a></li>
          <li><a href="deudores_mensualidad/listador_deudores/index.php">Alumnos Deudores</a></li>
          <li><a href="informe_alumno_beca_V2/index.php">Alumnos Con Beneficios</a></li>
          <li><a href="informe_resumen_item/index.php">Resum&eacute;n por Item</a></li>
        </ul>
      </li>
  </ul>
</div>
</body>
</html>