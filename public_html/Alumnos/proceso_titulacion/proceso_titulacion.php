<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->setDisplayErrors(true);
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Proceso_titulacion_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$msj_up="";
$msj_error="";
$year_ingreso_alumno=$_SESSION["SELECTOR_ALUMNO"]["ingreso"];
if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
{	

	$action="";
	$es_egresado_js="false";
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$id_carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$yearIngresoCarrera=$_SESSION["SELECTOR_ALUMNO"]["yearIngresoCarrera"];
	
	require("../../../funciones/conexion_v2.php");
	include("../../../funciones/funcion.php");
	include("../../../funciones/funciones_sistema.php");
	
	list($es_egresado, $semestre_egreso, $year_egreso)=ES_EGRESADO_V2($id_alumno, $id_carrera_alumno,$yearIngresoCarrera);
	$semestre_titulo=1;
	$year_titulo=0;
	$nombre_titulo="";
	
	$fecha_generacion="---";
	$cod_proceso_practica="0";
	$cod_user="---";
	$practica_condicion="";
	$practica_fecha_inicio="";
	$practica_lugar="";
	$informe_fecha_recepcion="";
	$examen_condicion="";
	$examen_fecha="";
	$titulo_fecha_emision="";
	$id_user=0;
	$numero_inscripcion_titulo="";
	$notaInformePractica="";
	$notaEvaluacionEmpresa="";
	$notaSupervisionPractica="";
	$notaExamenTitulo="";
	$notaFinalPractica="";
	
		
		 ////////////////////PROCESO PRACTICA///////////////////////////////
		 $array_condiciones_practica=array("pendiente", "reprobada", "aprobada","en_curso");
		 $array_condiciones_examen=array("pendiente", "aprobado", "reprobado");
		 
		 $cons_pp="SELECT * FROM proceso_titulacion WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera_alumno' AND yearIngresoCarrera='$yearIngresoCarrera'";
		 if(DEBUG){ echo"-> $cons_pp<br>";}
		
		 $sql_pp=$conexion_mysqli->query($cons_pp)or die($conexion_mysqli->error);
		 $num_regpp=$sql_pp->num_rows;
	
		 if($num_regpp>0)
		 {
			$DPP=$sql_pp->fetch_assoc();
				$cod_proceso_practica=$DPP["id"];
				$practica_condicion=$DPP["practica_condicion"];
				$practica_fecha_inicio=$DPP["practica_fecha_inicio"];
				$practica_lugar=$DPP["practica_lugar"];
				$informe_fecha_recepcion=$DPP["informe_fecha_recepcion"];
				$examen_condicion=$DPP["examen_condicion"];
				$examen_fecha=$DPP["examen_fecha"];
				$titulo_fecha_emision=$DPP["titulo_fecha_emision"];
				
				$notaInformePractica=$DPP["notaInformePractica"];
				$notaEvaluacionEmpresa=$DPP["notaEvaluacionEmpresa"];
				$notaSupervisionPractica=$DPP["notaSupervisionPractica"];
				$notaExamenTitulo=$DPP["notaExamen"];
				
				$notaFinalPractica=$notaInformePractica*0.3+$notaEvaluacionEmpresa*0.4+$notaSupervisionPractica*0.3;
				
				$semestre_titulo=$DPP["semestre_titulo"];
				$year_titulo=$DPP["year_titulo"];
				$nombre_titulo=$DPP["nombre_titulo"];
				$numero_inscripcion_titulo=$DPP["numero_inscripcion_titulo"];
				
				$fecha_generacion=fecha_format($DPP["fecha_generacion"]);
				$id_user=$DPP["cod_user"];
				
				//echo"===> $examen_condicion<br>";
		 }
		
		 /////////////////////---------------------/////////////////////////
		$sql_pp->free();
	if($es_egresado)
	{	
		$action='proceso_titulacion_2.php';
		$es_egresado_js="true";
	}
	else
	{
		//no es egresado
		if(DEBUG){echo"No se Puede Realizar Proceso de Titulacion, el Alumno no es Egresado...<br>"; }
		$msj_error="No se Puede Realizar o Actualizar el Proceso de Titulacion, el Alumno no es Egresado...<br>";
	}
	 	////////////////////
		$usuario_nombre=NOMBRE_PERSONAL($id_user);

		$notaFinalAsignaturas=PROMEDIO_FINAL_ASIGNATURAS($id_alumno, $id_carrera_alumno, $yearIngresoCarrera);
		

		$botonEliminar="";
			if($cod_proceso_practica>0){
				$botonEliminar='<a href="eliminarProcesoTitulacion?idProceso='.$cod_proceso_practica.'">Eliminar</a>';
			}
		//////////////////////
	///busco nombre titulo		
	$cons_C="SELECT nombre_titulo FROM carrera WHERE id='$id_carrera_alumno'";
	$sqli_C=$conexion_mysqli->query($cons_C)or die($conexion_mysqli->error);
	$CC=$sqli_C->fetch_assoc();
		$C_nombre_titulo=$CC["nombre_titulo"];
	$sqli_C->free();
} 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php"); ?>
<script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>proceso titulacion</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:90%;
	height:429px;
	z-index:1;
	left: 5%;
	top: 65px;
}
a:link {
	color: #006699;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #006699;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #006699;
}
#apDiv2 {
	position:absolute;
	width:60%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 74px;
}
.Estilo2 {font-size: 12}
.Estilo3 {font-size: 12px}
#apDiv3 {
	position:absolute;
	width:20%;
	height:69px;
	z-index:3;
	left: 57px;
	top: 851px;
}
-->
</style>
<script language="javascript" type="application/javascript">

function CONFIRMAR()
{
	continuar=true;
	year_titulo=document.getElementById('year_titulo').value;
	year_ingreso=<?php echo $year_ingreso_alumno;?>;
	es_egresado=<?php echo $es_egresado_js;?>;
	if(year_titulo<=year_ingreso)
	{
		continuar=false;
		alert("Error el año de titulo no puede ser menor o igual al ingreso (<?php echo $year_ingreso_alumno;?>)");
	}
	
	if(!es_egresado)
	{
		alert("IMPORTANTE: No se Puede Realizar Proceso de Titulacion, el alumno no es EGRESADO");
		continuar=false;
	}
	
	if(continuar)
	{document.getElementById('frm').submit();}
	
}
</script>
</head>
<body>
<h1 id="banner">Administrador - Proceso Titulaci&oacute;n</h1>
<div id="apDiv1">
<div align="center">
<form action="<?php echo $action;?>" method="post" name="frm" id="frm">
<table width="90%" border="0" align="center">
		<thead>
           <tr>
             <th colspan="3"  >Informacion de Titulo y Otros</th>
           </tr>
           </thead>
           <tbody>
           <tr>
             <td width="49%"  class="Estilo7"><strong>*Cod. Registro</strong></td>
             <td colspan="2" ><strong><?php echo $cod_proceso_practica;?></strong>
               <input name="codigo_registro" type="hidden" id="codigo_registro" value="<?php echo $cod_proceso_practica;?>" /> 
               <em>(si codigo es&quot;0&quot; Proceso no iniciado)</em>
			   <?php echo $botonEliminar;?></td>
        </tr>
           <tr>
             <td  class="Estilo7"><strong>*Ultimo que Modifico</strong></td>
             <td colspan="2" ><?php echo $usuario_nombre;?> el [<?php echo $fecha_generacion;?>]</td>
           </tr>
           <tr>
             <td colspan="3"  class="Estilo7"><strong>&#9658;Practica</strong></td>
           </tr>
           <tr>
             <td  class="Estilo7">Proceso Practica</td>
             <td colspan="2" >
             <select name="practica_condicion" id="practica_condicion">
             <?php
             	foreach($array_condiciones_practica as $n => $valor)
				{
					if($valor==$practica_condicion)
					{	echo'<option value="'.$valor.'" selected="selected">'.$valor.'</option>';}
					else
					{	echo'<option value="'.$valor.'">'.$valor.'</option>';}
				}
			 ?>
             </select>             </td>
           </tr>
           <tr>
             <td  class="Estilo7">Inicio Practica</td>
             <td colspan="2" ><input name="practica_fecha_inicio" type="text" id="practica_fecha_inicio" size="11" readonly value="<?php echo $practica_fecha_inicio;?>">
              <input type="button" name="btn_pract" id="btn_pract" value="..."></td>
           </tr>
           <tr>
             <td  class="Estilo7">Lugar Practica</td>
             <td colspan="2" ><input name="practica_lugar" type="text" id="practica_lugar" value="<?php echo $practica_lugar;?>" size="60"></td>
           </tr>
           <tr>
             <td  class="Estilo7">Nota informe Practica</td>
             <td colspan="2" ><input name="notaInformePractica" type="text" id="notaInformePractica" size="5" value="<?php echo $notaInformePractica;?>"/> 
               (30%)</td>
           </tr>
           <tr>
             <td  class="Estilo7">Evaluacion de Empresa</td>
             <td colspan="2" ><input name="notaEvaluacionEmpresa" type="text" id="notaEvaluacionEmpresa" size="5" value="<?php echo $notaEvaluacionEmpresa;?>"/> 
             (40%)</td>
           </tr>
           <tr>
             <td  class="Estilo7">Nota supervision</td>
             <td colspan="2" ><input name="notaSupervision" type="text" id="notaSupervision" size="5" value="<?php echo $notaSupervisionPractica;?>"/> 
               (30%)</td>
           </tr>
           <tr>
             <td  class="Estilo7">Nota Final Practica</td>
             <td colspan="2" ><?php echo $notaFinalPractica;?></td>
           </tr>
           <tr>
             <td  class="Estilo7">Recepcionado Informe</td>
             <td colspan="2" ><input name="informe_fecha_recepcion" type="text" id="informe_fecha_recepcion" size="11" readonly value="<?php echo $informe_fecha_recepcion;?>">
              <input type="button" name="btn_infor" id="btn_infor" value="..."></td>
           </tr>
           <tr>
             <td  class="Estilo7">&nbsp;</td>
             <td colspan="2" >&nbsp;</td>
           </tr>
           <tr>
             <td colspan="3"  class="Estilo7"><strong>&#9658;Examen</strong></td>
           </tr>
           <tr>
             <td  class="Estilo7">Realiza Examen</td>
             <td colspan="2" ><select name="examen_condicion" id="examen_condicion">
               <?php
             	foreach($array_condiciones_examen as $nx => $valorx)
				{
					if($valorx==$examen_condicion)
					{	echo'<option value="'.$valorx.'" selected="selected">'.$valorx.'</option>';}
					else
					{	echo'<option value="'.$valorx.'">'.$valorx.'</option>';}
				}
			 ?>
             </select>             </td>
           </tr>
           <tr>
             <td  class="Estilo7">Fecha Examen</td>
             <td colspan="2" ><input name="examen_fecha" type="text" id="examen_fecha" size="11" readonly="readonly" value="<?php echo $examen_fecha;?>" />
             <input type="button" name="btn_examen" id="btn_examen" value="..." /></td>
           </tr>
           <tr>
             <td  class="Estilo7">Nota Examen</td>
             <td colspan="2" ><input name="notaExamen" type="text" id="notaExamen" size="5" value="<?php echo $notaExamenTitulo;?>"/></td>
           </tr>
           <tr>
             <td  class="Estilo7">&nbsp;</td>
             <td colspan="2" >&nbsp;</td>
           </tr>
           <tr>
             <td colspan="3"  class="Estilo7"><strong>&#9658;Titulo</strong></td>
           </tr>
           <tr>
             <td  class="Estilo7">Numero registro (inscripcion titulo)</td>
             <td colspan="2" ><label for="numero_inscripcion_titulo">
               <input name="numero_inscripcion_titulo" type="text" id="numero_inscripcion_titulo" value="<?php echo $numero_inscripcion_titulo;?>" />
             </label></td>
           </tr>
           <tr>
             <td  class="Estilo7">Emision</td>
             <td colspan="2" ><input name="titulo_emision" type="text" id="titulo_emision" size="11" readonly value="<?php echo $titulo_fecha_emision;?>" />
             <input type="button" name="btn_titulo" id="btn_titulo" value="..." /></td>
           </tr>
           <tr>
             <td  class="Estilo7">Semestre Titulo</td>
             <td width="18%" >
             <select name="semestre_titulo">
             	<?php
				for($x=1;$x<=2;$x++)
				{
					if($x==$semestre_titulo){ $select='selected="selected"';}
					else{ $select='';}
					
					echo'<option value="'.$x.'" '.$select.'>'.$x.'</option>';
				}
                ?>
             </select>
             </td>
             <td width="33%" ><span class="Estilo7">A&ntilde;o Acta Titulo</span>
               <select name="year_titulo" id="year_titulo">
               <?php
	  	$años_anteriores=40;
		$años_siguientes=1;
	  	$año_actual=date("Y");
		
		$año_ini=$año_actual-$años_anteriores;
		$año_fin=$año_actual+$años_siguientes;
		
		for($a=$año_fin;$a>=$año_ini;$a--)
		{
			if($a==$year_titulo)
			{echo'<option value="'.$a.'" selected="selected">'.$a.'</option>';	}
			else
			{echo'<option value="'.$a.'">'.$a.'</option>';}	
		}
	  ?>
             </select></td>
           </tr>
           <tr>
             <td  class="Estilo7">Nombre titulo</td>
             <td colspan="2" >
             <select name="nombre_titulo" id="nombre_titulo" >
            	<option value="<?php echo $C_nombre_titulo;?>"><?php echo $C_nombre_titulo;?></option>
             </select>
             </td>
           </tr>
           <tr>
           	<td>Nota Final Titulo</td>
             <td colspan="2"  class="Estilo7"><?php echo number_format(NOTA_FINAL_TITULO($id_alumno, $id_carrera_alumno, $yearIngresoCarrera),1,",",".");?> =(nota Final Asignatura <?php echo $notaFinalAsignaturas;?>)*0.3+(nota final practica <?php echo $notaFinalPractica;?>)*0.35+(nota Examen <?php echo $notaExamenTitulo;?>)*0.35</td>
           </tr>
           <tr>
             <td colspan="3"  class="Estilo7">&nbsp;</td>
           </tr>
           <tr>
             <td colspan="3"  class="Estilo7"><div align="center">
               <input type="button" name="button" id="button" value="Grabar Proceso Titulacion"  onclick="CONFIRMAR();"/>
             </div></td>
           </tr>
           </tbody>
</table>
<br />
</form>
<?php echo $msj_error;?>
</div>
</div>
<script type="text/javascript">
//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
	  cal.manageFields("btn_pract", "practica_fecha_inicio", "%Y-%m-%d");
	  cal.manageFields("btn_infor", "informe_fecha_recepcion", "%Y-%m-%d");
	  cal.manageFields("btn_titulo", "titulo_emision", "%Y-%m-%d");
	  cal.manageFields("btn_examen", "examen_fecha", "%Y-%m-%d");
    //]]>

</script>
</body>
</html>
