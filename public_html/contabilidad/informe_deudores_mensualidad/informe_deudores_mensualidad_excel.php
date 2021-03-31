<?php
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="inspeccion";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	define("DEBUG",false);
	if(!DEBUG)
	{
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=deudores_mensualidad.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
	if(DEBUG){ var_export($_GET);}
if($_GET)
{
		$sede=base64_decode($_GET["sede"]);
		$array_carrera=base64_decode($_GET["carrera"]);
		$array_carrera=explode("_",$array_carrera);
		$id_carrera=$array_carrera[0];
		$carrera=$array_carrera[1];
		$nivel=base64_decode($_GET["nivel"]);
		$fecha_corte=base64_decode($_GET["fecha_corte"]);
		$jornada=base64_decode($_GET["jornada"]);
		$situacion_financiera=base64_decode($_GET["situacion_financiera"]);
		$year_letras=base64_decode($_GET["year_letras"]);
		$mostrar_subtotales=base64_decode($_GET["mostrar_subtotales"]);
		
		//------------------------------//
			if(DEBUG){ echo"<br>Mostrar Subtotales: $mostrar_subtotales<br>";}
		if($mostrar_subtotales=="ON")
		{ $mostrar_subtotales=true;}
		else
		{ $mostrar_subtotales=false;}
	
		//---------------------------------//
}

	include("../../../funciones/funcion.php");
	$columnas=11;
	if($id_carrera==0)
	{
		$columnas=12;
	}
  $TABLA='<table width="80%" border="1" align="center">
  	<thead>
    <tr>
      <th colspan="'.$columnas.'"><div align="center"><strong>Alumnos Con Deuda cuotas('.$year_letras.'), Fecha Corte '.fecha_format($fecha_corte).'<br />Condicion Financiera Actual '.$situacion_financiera.'<br />
          Carrera: '.$carrera.', Nivel '.$nivel.', Jornada '.$jornada.'<br />
         '.$sede.'</strong>
           <br />
      </div></th>

    </tr>
    <tr>
    <td>N&deg;</td>
    <td>Rut</td>
    <td>Nombre</td>
    <td>Apellido P</td>
    <td>Apellido M</td>';
    if($id_carrera==0)
	{
    	$TABLA.='<td>Carrera</td>';
     }
	
    $TABLA.='<td>ID Cuota</td>
	<td>Año Corresponde Cuota</td>
    <td>Vencimiento</td>
    <td>Valor $</td>
    <td>Deuda X Cuota $</td>
    <td>Condici&oacute;n</td>
    </tr>
    </thead>
    <tbody>';

if(($_POST)or($_GET))
{
	
	include("../../../funciones/conexion.php");
	$checked='checked="checked"';
	if($detalle=="ON")
	{ $ver_detalle=true;}
	else
	{ $ver_detalle=false;}

	if($nivel!="todos")
	{ $condicion_nivel="alumno.nivel='$nivel' AND";}
	if($jornada!="todas")
	{ $condicion_jornada="alumno.jornada='$jornada' AND";}
	if($id_carrera!=0)
	{ $condicion_carrera="alumno.id_carrera='$id_carrera' AND";}
	if($situacion_financiera!="todos")
	{ 
		$condicion_financiera="alumno.situacion_financiera='$situacion_financiera' AND";
	}	
	if($year_letras!="Todos")
	{ $condicion_year_letras="letras.ano='$year_letras' AND";}
	
	$consX="SELECT alumno.id, alumno.rut, alumno.nombre, alumno.apellido, alumno.apellido_P, alumno.apellido_M, alumno.carrera, alumno.nivel, alumno.grupo, alumno.situacion_financiera, alumno.jornada,letras.id AS id_letra, letras.fechavenc, letras.valor, letras.deudaXletra, letras.ano, letras.semestre, letras.pagada, letras.tipo FROM alumno INNER JOIN letras ON alumno.id = letras.idalumn WHERE $condicion_carrera alumno.sede='$sede' AND alumno.situacion='V' AND $condicion_nivel $condicion_jornada $condicion_financiera $condicion_year_letras letras.fechavenc <='$fecha_corte' AND NOT(letras.pagada='S') ORDER BY carrera, apellido_p, apellido_M, letras.fechavenc";
	
	if(DEBUG){ echo "$consX<br><br>";}
	$sql=mysql_query($consX)or die("consX ".mysql_error());
	$num_seleccionados=mysql_num_rows($sql);
	if($num_seleccionados>0)
	{
		$id_alumno_old=0;
		$aux=0;
		$primera_vuelta=true;
		$SUMA_TOTAL=0;
		while($DA=mysql_fetch_assoc($sql))
		{
			$aux++;
			$id_alumno=$DA["id"];
			$rut=$DA["rut"];
			$nombre=ucwords(strtolower($DA["nombre"]));
			$apellido=$DA["apellido"];
			$apellido_P=$DA["apellido_P"];
			$apellido_M=$DA["apellido_M"];
			$carrera_alumno=$DA["carrera"];
			$nivel=$DA["nivel"];
			$grupo=$DA["grupo"];
			
			$id_letra=$DA["id_letra"];
			$aux_year_letras=$DA["ano"];
			$fecha_vence=$DA["fechavenc"];
			$valor=$DA["valor"];
			$deudaXletra=$DA["deudaXletra"];
			$ano=$DA["ano"];
			$semestre=$DA["semestre"];
			$pagada=$DA["pagada"];
			$tipo_cuota=$DA["tipo"];
			$situacion_financiera=$DA["situacion_financiera"];
			$jornada_alumno=$DA["jornada"];
			
			$SUMA_TOTAL+=$deudaXletra;
			switch($pagada)
			{
				case"N":
					$condicion_label="pendiente";
					break;
				case"A":
					$condicion_label="abonada";
					break;
				case"S":
					$condicion_label="pagada";		
					break;
			}
			if($primera_vuelta)
			{
				$id_alumno_old=$id_alumno;
				$primera_vuelta=false;
				$cuenta_alumno=1;
			}
			//alumno diferente	
			if($id_alumno_old!=$id_alumno)
			{
				$cuenta_alumno++;
				$id_alumno_old=$id_alumno;
				$cuenta_cuotas=1;
				$imprimir_subtotal=true;
			}
			else
			{
				$cuenta_cuotas++;
				$imprimir_subtotal=false;
			}
			
			if($mostrar_subtotales)
			{
				if($imprimir_subtotal)
				{
				$TABLA.='<tr>
					<td colspan="2"><strong>Subtotal</strong></td>
					<td colspan="'.($columnas-3).'" align="right"><strong>'.$aux_subtotal_parcial.'</strong></td>
					<td>&nbsp;</td>
					</tr>';
					$aux_subtotal_parcial=$deudaXletra;
				}
				else
				{$aux_subtotal_parcial+=$deudaXletra;}
			}
			//*******************************************************//
			
			$TABLA.='<tr>
				<td>'.$aux.'</td>
				<td>'.$rut.'</td>
				<td>'.$nombre.'</td>
				<td>'.$apellido_P.'</td>
				<td>'.$apellido_M.'</td>';
			if($id_carrera==0)
			{
				$TABLA.='<td>'.$carrera_alumno.'</td>';
			}
			$TABLA.='<td>'.$id_letra.'</td>
				<td>'.$aux_year_letras.'</td>
				<td>'.fecha_format($fecha_vence).'</td>
				<td>'.$valor.'</td>
				<td>'.$deudaXletra.'</td>
				<td>'.$condicion_label.'</td>
				</tr>';	
		}//fin while
		if($mostrar_subtotales)
		{
			if(1==1)
				{
				$TABLA.='<tr>
					<td colspan="2"><strong>Subtotal</strong></td>
					<td colspan="'.($columnas-3).'" align="right"><strong>'.$aux_subtotal_parcial.'</strong></td>
					<td>&nbsp;</td>
					</tr>';
				}
		}
		
		$TABLA.='<tr>
					<td colspan="2"><strong>TOTAL</strong></td>
					<td colspan="'.($columnas-3).'" align="right"><strong>'.$SUMA_TOTAL.'</strong></td>
					<td>&nbsp;</td>
				</tr>';
	}
	else
	{
		if(DEBUG){ echo"No seleccionados";}
		$TABLA.='<tr>
		<td colspan="'.$columnas.'">Sin Alumnos con deuda a la fecha seleccionada</td>
		 </tr>';
		
	}
}
else
{
	echo"No DATOS<br>";
}
$TABLA.='</tbody>
<tfoot>
<tr>
	<td colspan="'.$columnas.'">'.$cuenta_alumno.' Alumno(s) con Deuda(s) a la fecha seleccionada</td>
</tr>
</tfoot>
  </table>';
  
  echo $TABLA;
?>