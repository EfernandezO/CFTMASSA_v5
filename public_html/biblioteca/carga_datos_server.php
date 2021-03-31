<?php
//////////////////////XAJAX/////////////////
@require_once ("../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("carga_datos_server.php");
//$xajax->setCharEncoding('ISO-8859-1');
$xajax->configure('javascript URI','../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"RECARGAR");
define("DEBUG", false);
////////////////////////////////////////////

function RECARGAR($FORMULARIO)
{
	require("../../funciones/conexion_v2.php");
	require("../../funciones/funciones_sistema.php");
	$codigo=mysql_real_escape_string($FORMULARIO["codigo_libro"]);
	$sede=mysql_real_escape_string($FORMULARIO["sede"]);
	$id_carrera=mysql_real_escape_string($FORMULARIO["carrera"]);
	$titulo_buscado=mysql_real_escape_string($FORMULARIO["titulo"]);
	$div='apDiv1';
	$html="";
	 $num_reg=0;
	
	$resaltar_texto=false;
	
	if((!empty($codigo))and(is_numeric($codigo)))
	{
		$condicion_codigo="id_libro='$codigo'";
		$condicion_sede="";
		$condicion_carrera="";
		$condicion_titulo="";
	}
	else
	{
		$condicion_codigo="";
		
		if($sede!="todas")
		{ $condicion_sede="sede='$sede'";}
		else{ $condicion_sede="";}
		
		if($id_carrera>0)
		{ $condicion_carrera="AND id_carrera='$id_carrera'";}
		else
		{ $condicion_carrera="";}
		
		if(!empty($titulo_buscado))
		{$condicion_titulo="AND nombre LIKE '%$titulo_buscado%'"; $resaltar_texto=true;}
		else{ $condicion_titulo=""; $resaltar_texto=false;}
	}
	
	
	$html_tabla='<table border="0" id="example" class="display" width="100%">
	<thead>
		<tr>
        	<th>ID Libro</th>
            <th>Sede</th>
            <th>Carrera</th>
            <th>Titulo</th>
			<th>Autor</th>
			<th>Condicion</th>
			<th>V</th>
            <th>E</th>
            <th>B</th>
		</tr>
	</thead>
	<tbody>';
    $cons_l="SELECT * FROM biblioteca WHERE $condicion_codigo $condicion_sede $condicion_carrera $condicion_titulo ORDER by id_carrera, nombre";
 if(DEBUG){ $html.="<br>$cons_l<br>";}
 $aux=0;  
 $cuenta_prestamos=0; 
 $sqli_l=$conexion_mysqli->query($cons_l);
 $num_reg=$sqli_l->num_rows;
 if($num_reg>0)
 {
	 while($row = $sqli_l->fetch_assoc())
	  {
	  $aux++;
	  
	  	$id_alumno=$row["id_alumno"];
	   $id_libro=$row["id_libro"]; 
	   $titulo=$row["nombre"];
	   $autor=$row["autor"];
	   $editorial=$row["editorial"];
	   $year=$row["year"];
	   $prestado=$row["prestado"];
	   $id_carrera=$row["id_carrera"];
		
		$nombre_carrera=NOMBRE_CARRERA($id_carrera);
		$sede=$row["sede"];
	   
	   
	   //////////////
	   if($resaltar_texto)
	   {
		   $titulo_resaltado='<div id="texto_resaltado">'.$titulo_buscado.'</div>';
		   $titulo=str_replace($titulo_buscado,$titulo_resaltado,$titulo);
	   }
	   /////////////
	   
	   if($autor=="")
		{$autor="No Registrado"; }
		if($titulo=="")
		{$titulo="No Registrado"; }
		if($editorial=="")
		{$editorial="No Registrado"; }
		if($sede=="")
		{$sede="No Registrado"; }
		if($year=="")
		{$year="No Reg"; }
			
			switch($prestado)
			{
				case"S":
					$clase_fila='gradeC';
					$clase_boton='button_R';
					$title_boton='Click Para Devolver';
					$url_boton='prestamo_devolucion/devolucion/devolucion.php?id_libro='.$id_libro."&id_alumno=".$id_alumno;
					$cuenta_prestamos++;
					$opcion='<td></td>';
			 		$condicion_label="prestado";
					break;
				case"N":
					$clase_fila='gradeA';
					$clase_boton='button';
					$title_boton='Click para Prestar';
					$url_boton='prestamo_devolucion/prestamo/buscador_alumno/index.php?id_libro='.$id_libro;
					$opcion='<td></td>';
				 	$condicion_label="disponible";
					break;	
			}
			
		 $html_tabla.='<tr height="50">
		  <td>'.$id_libro.'</td>
		  <td>'.$sede.'</td>
		  <td>'.$nombre_carrera.'</td>
		  <td>'.$titulo.'</td>
		  <td>'.$autor.'</td>
		  <td><a title="'.$title_boton.'" class="'.$clase_boton.'" href="'.$url_boton.'">'.$condicion_label.'</a></td>
		  <td><a title="Ver Historial" href="informe_libro/historial_libro/historial_libro.php?id_libro='.$id_libro.'"><img width="26" height="30" alt="ver" src="../BAses/Images/lupa.png"></a></td>
		  <td><a title="Editar" href="edicion_libro/edicion_libro/edicion_L1.php?id_libro='.$id_libro.'"><img width="16" height="16" alt="Edicion" src="../BAses/Images/b_edit.png"></a></td>
		 <td><a title="Eliminar" onclick="ELIMINAR(\'edicion_libro/eliminar_libro/elimina_libro.php?id_libro='.$id_libro.'\');" href="#"><img width="16" height="16" alt="Eliminar" src="../BAses/Images/b_drop.png"></a></td>
		  </tr>'; 
	   }
	  
 }
 else
 {
	  $html_tabla.='<tr height="50">
					  <td colspan="9">Sin Registros</td>
					  </tr>';
 }
  $sqli_l->free();
  $conexion_mysqli->close();
   
    $html_tabla.='</tbody><tfoot><tr><td colspan="9">Numero de Libros Encontrados ('.$num_reg.')</td></tr></tfoot></table>';
	$objResponse = new xajaxResponse();
	//$objResponse->Alert("xajax conectado...");
		$objResponse->Assign($div,"innerHTML",$html_tabla);
	return $objResponse;
}
////////////////
$xajax->processRequest();
?>