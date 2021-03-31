<?php
//--------------CLASS_okalis------------------//
	require("../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../funciones/";
	$O->clave_del_archivo=md5("Menu_funcionarios_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
	$privilegios=$_SESSION["USUARIO"]["privilegio"];
	switch($privilegios)
	{
		case"matricula":
			$url_menu="../Administrador/menu_matricula/index.php";
			break;
		default:
			$url_menu="../Administrador/ADmenu.php";
			
	}
	$condicion_nivel="";
	$year_actual=date("Y");
	$mes_actual=date("m");
	if($mes_actual>=8)
	{ $semestre_actual=2;}
	else
	{ $semestre_actual=1;}
	
?>
<html>
<head>
<title>Funcionarios - CFTMASSA</title>
<?php include("../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../libreria_publica/jquery_treeview/jquery.treeview.css">
<link rel="stylesheet" type="text/css" href="../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../libreria_publica/hint.css-master/hint.css">
<style type="text/css">
<!--
.Estilo1 {	font-size: 12px;
	font-weight: bold;
}
#apDiv1 {
	position:absolute;
	width:96%;
	height:98px;
	z-index:1;
	left: 2%;
	top: 111px;
}
-->
    </style>
<style type="text/css" title="currentStyle">
	@import "../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/css/demo_table_jui.css";
	@import "../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/examples/examples_support/themes/smoothness/jquery-ui-1.8.4.custom.css";
</style>
<script type="text/javascript" language="javascript" src="../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../libreria_publica/DataTables-1.7.3/DataTables-1.7.3/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		oTable = $('#example').dataTable({
			"bJQueryUI": true,
			"bPaginate": true});
	} );
</script>
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
</head>

<body>
<h1 id="banner">Administrador - Gestion de Funcionarios</h1>

 <div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="#">Honorario</a>
  <ul>
  <li><a href="honorario_docente/generacion_honorario_docente/Generacion_honorario_1.php">Generacion Honorarios</a></li>
  <li><a href="honorario_docente/ver_resumen_mensual/index.php">Revision Honorarios</a></li>
  <li><a href="honorario_docente/contrato_honorario/ver/index.php">Contrato Honorario</a></li>
  <li><a href="honorario_docente/resumen_pagos_docente/index.php">Resumen Pagos Docente</a></li>
  <li><a href="honorario_docente/detallePagoDocente/detallePago_1.php">Detalle Pagos Docente</a></li>
  </ul>
</li>
<li><a href="#">Asignaciones</a>
  <ul>
  <li><a href="asignacion_asignaturas/ver_asignaciones/ver_asignaciones_general/ver_asignacion_general_1.php">Revision General</a></li>
  <li><a href="asignacion_asignaturas/informe_docentes_administracion_asignatura/informe_administracion_asig_1.php">Informe Administracion Asignatura</a></li>
  <li><a href="asignacion_asignaturas/horario_clases/revisa_horario/revisa_horario_1.php">Horario General</a></li>
  <li><a href="asignacion_asignaturas/horario_clases/registro_horarios/registro_horario_1.php">Control Horario</a></li>
  <li><a href="#">Control Asistencia Docente</a>
      <ul>
        <li><a href="asignacion_asignaturas/horario_clases/registro_horarios/registra_horario_personal_ind/registro_personal_ind_1.php?sede=Talca" target="_blank">Talca</a></li>
        <li><a href="asignacion_asignaturas/horario_clases/registro_horarios/registra_horario_personal_ind/registro_personal_ind_1.php?sede=Linares" target="_blank">Linares</a></li>
      </ul>
   </li>   
  </ul>
</li>
<li><a href="#">Planificaciones</a>
  <ul>
  <li><a href="planificaciones_v2/revision_planificaciones/revision_planificaciones_1.php">Revisión</a></li>
  </ul>
</li>
<li><a href="#">Informes</a>
  <ul>
  <li><a href="informes_docentes/grado_academico_y_remuneraciones/index.php">grado academico y remuneraciones</a></li>
  <li><a href="informes_docentes/resumen_docente_1/resumen_docente_1.php">asignaciones, grado y antiguedad</a></li>
  </ul>
</li>
<li><a href="#">Usuarios</a>
  <ul>
  <li><a href="nvo_funcionario/creadocente.php">Nuevo Usuario</a></li>
  </ul>
</li>
<li><a href="#">Capacitacion</a>
  <ul>
  <li><a href="carga_descarga_tareas_docente/carga_trabajos_para_docente/index.php">Carga descarga trabajos</a></li>
  </ul>
</li>
<li><a href="<?php echo $url_menu;?>">Volver al Menu</a></li>
</ul>
<br style="clear: left" />
</div> 


<div id="apDiv1"><div class="demo_jui">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
        	<th>ID</th>
        	<th>Acceso</th>
            <th>Nivel</th>
            <th>Asignaciones<br>
            [<?php echo "$semestre_actual-$year_actual"; ?>]</th>
            <th>Sede</th>
			<th>Rut</th>
			<th>Nombre</th>
			<th>Apellido</th>
            <th>Honorario</th>
			<th colspan="3">Opci&oacute;n</th>
		</tr>
	</thead>
	<tbody>
			<?php
            include("../../funciones/conexion_v2.php");
				$cons="SELECT * FROM personal $condicion_nivel ORDER by sede, apellido_P, apellido_M";
				if(DEBUG){ echo"$cons<br>";}
				$sqli=$conexion_mysqli->query($cons);
				$num_reg=$sqli->num_rows;
				if($num_reg>0)
				{
					while($F=$sqli->fetch_assoc())
					{
						$F_id=$F["id"];
						$F_rut=$F["rut"];
						$F_nombre=$F["nombre"];
						$F_apellido_P=$F["apellido_P"];
						$F_apellido_M=$F["apellido_M"];
						$F_sede=$F["sede"];
						$F_nivel=$F["nivel"];
						$F_con_acceso=$F["con_acceso"];
						
						if($F_con_acceso=="ON")
						{ $class='class="gradeA"'; $boton='class="button"'; $aux_con_acceso="OFF";}
						else
						{ $class='class="gradeX"'; $boton='class="button_R"'; $aux_con_acceso="ON";}
						
						///-------------------------------------------------------------///
						//hay asignaciones
						$cons_a="SELECT COUNT(id) FROM toma_ramo_docente WHERE id_funcionario='$F_id' AND semestre='$semestre_actual' AND year='$year_actual'";
						$sqli_a=$conexion_mysqli->query($cons_a);
						$D1=$sqli_a->fetch_row();
						$coincidencias=$D1[0];
						$sqli_a->free();
						
						if($coincidencias>0)
						{ $hay_asignaciones='<a href="#" class="button">si</a>';}
						else
						{ $hay_asignaciones='<a href="#" class="button_R">no</a>';}
						///-------------------------------------------------------------///
						
						echo'<tr '.$class.' height="34">
								<td>'.$F_id.'</td>
								<td><a href="edicion_A/cambio_con_acceso.php?fid='.$F_id.'&acceso='.$F_con_acceso.'" '.$boton.' title="Click para pasar a '.$aux_con_acceso.'">'.$F_con_acceso.'</a></td>
								<td align="center">'.$F_nivel.'</td>
								<td align="center">'.$hay_asignaciones.'</td>
								<td>'.$F_sede.'</td>
								<td>'.$F_rut.'</td>
								<td>'.$F_nombre.'</td>
								<td>'.$F_apellido_P.' '.$F_apellido_M.'</td>
								<td align="center"><a href="honorario_docente/pago_honorario_docente/ver_honorario_docente_IND.php?id_funcionario='.$F_id.'">Pagar</a></td>
								<td>
								<a href="edicion_A/mdocente.php?id_fun='.base64_encode($F_id).'"  class="hint--bottom  hint--error" data-hint="Edicion"><img src="../BAses/Images/b_edit.png" width="16" height="16" alt="Editar" title="Editar"></a>
								</td>
								<td><a href="asignacion_asignaturas/asignacion_asignaturas_docente_1.php?fid='.base64_encode($F_id).'" class="hint--bottom  hint--error" data-hint="Asignacion de Asignaturas"><img src="../BAses/Images/add.png" width="32" height="31" alt="Asignaciones"></a></td>
								<td><a href="expediente/ver_expediente.php?id_funcionario='.base64_encode($F_id).'" class="hint--bottom  hint--error" data-hint="Expediente" target="_black"><img src="../BAses/Images/pdf_icon.png" width="31" height="31"></a></td>
							</tr>';
					}
				}
			$sqli->free();	
			$conexion_mysqli->close();
			?>
	</tbody>
</table>
</div></div>
</body>
</html>