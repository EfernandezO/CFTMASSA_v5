<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("revision_mensual_honorario_Docente");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

$mes_actual=date("n");
$year_actual=date("Y");

$ARRAY_MESES=array(1=>"Enero",
					2=>"Febrero",
					3=>"Marzo",
					4=>"Abril",
					5=>"Mayo",
					6=>"Junio",
					7=>"Julio",
					8=>"Agosto",
					9=>"Septiembre",
					10=>"Octubre",
					11=>"Noviembre",
					12=>"Diciembre");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Honorario | Docente</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 164px;
}
</style>
<script language="javascript" type="text/javascript">
function CONFIRMAR(datos, periodo, correo_ya_enviado)
{
	if(correo_ya_enviado==1)
	{
		 alert('Correo ya enviado Previamente...');
		 x=confirm('Seguro(a) desea Volver a enviar este Mensaje Masivo..?');
		 if(x){ continuar_2=true;}
		 else{ continuar_2=false;}
	}
	else{ continuar_2=true;}
	
	url='envio_comprobante/envio_comprobante_1.php?'+datos;
	if(periodo==0)
	{
		c=confirm('intenta Enviar Comprobantes de Un Mes Anterior\n seguro(a) desea Continuar..?');
		if(c){ continuar_1=true;}
		else{ continuar_1=false;}
	}
	else{ continuar_1=true;}
	
	if((continuar_1)&&(continuar_2))
	{
		c=confirm('Seguro(a) que desea Enviar un Aviso por los Honorarios a los Docentes..¿?');
		if(c)
		{
			d=confirm('Realmente seguro(a) que desea enviar..?');
			if(d){ window.location=url;}
		}
	}
	
	
}
</script>
</head>

<body>
<h1 id="banner">Contabilidad - Honorario Docente</h1>
<div id="link"><br />
<a href="../../lista_funcionarios.php" class="button">Volver al Menu</a></div>
<div id="apDiv1">
<table width="50%" border="1" align="center">
<thead>
  <tr>
    <th colspan="8">Honorarios Generados</th>
  </tr>
</thead>
<tbody> 
<tr>
	<td><strong>N.</strong></td>
    <td><strong>Sede</strong></td>
    <td><strong>Año</strong></td>
    <td><strong>Mes</strong></td>
    <td colspan="4"><strong>Opciones</strong></td>
</tr>

	<?php
    require("../../../../funciones/conexion_v2.php");
	$cons_H="SELECT sede, year_generacion, mes_generacion FROM honorario_docente GROUP BY sede, year_generacion, mes_generacion ORDER BY year_generacion DESC, mes_generacion DESC, sede";
	$sqli_H=$conexion_mysqli->query($cons_H)or die($conexion_mysqli->error);
	$num_periodos=$sqli_H->num_rows;
	if($num_periodos>0)
	{
		$aux=0;
		while($H=$sqli_H->fetch_assoc())
		{
			$aux++;
			$H_sede=$H["sede"];
			$H_year_generacion=$H["year_generacion"];
			$H_mes_generacion=$H["mes_generacion"];
			
			//busco en historial
			$cons_BH="SELECT COUNT(id) FROM historial WHERE evento LIKE 'Envio de Aviso Honorario Docente  $H_sede [$H_mes_generacion - $H_year_generacion]%'";
			$sqli_BH=$conexion_mysqli->query($cons_BH)or die($conexion_mysqli->error);
			$D_BH=$sqli_BH->fetch_row();
			$num_eventos=$D_BH[0];
			if(empty($num_eventos)){ $num_eventos=0;}
			if(DEBUG){ echo"---> $cons_BH<br>num eventos: $num_eventos<br>";}
			$sqli_BH->free();
			
			if($num_eventos>0){ $correos_ya_enviados=true; $correos_ya_enviados_js=1; if(DEBUG){ echo"Correo ya enviado previamente...<br>";}}
			else{ $correos_ya_enviados=false; $correos_ya_enviados_js=0; if(DEBUG){ echo"Correo sin Envio Previo...<br>";}}
			
			//------------------------------------------------------------///
			$periodo_actual=0;
			if($H_year_generacion==$year_actual)
			{
				if($H_mes_generacion==$mes_actual){ $periodo_actual=1;}
				elseif($H_mes_generacion==($mes_actual+1)){ $periodo_actual=1;}
				elseif($H_mes_generacion==($mes_actual-1)){ $periodo_actual=1;}
	
			}
			//-----------------------------------------------------------------///
			
			echo'<tr>
					<td>'.$aux.'</td>
					<td>'.$H_sede.'</td>
					<td>'.$H_year_generacion.'</td>
					<td>['.$H_mes_generacion.'] '.$ARRAY_MESES[$H_mes_generacion].'</td>
					<td><a href="revision/revision_1.php?sede='.base64_encode($H_sede).'&year_generacion='.base64_encode($H_year_generacion).'&mes='.base64_encode($H_mes_generacion).'">Revisar</a></td>
					<td><a href="honorario_docente_resumen_pdf.php?sede='.base64_encode($H_sede).'&year_generacion='.base64_encode($H_year_generacion).'&mes='.base64_encode($H_mes_generacion).'" target="_blank">Ver pdf</a></td>
					<td><a href="honorario_docente_resumen_xls.php?sede='.base64_encode($H_sede).'&year_generacion='.base64_encode($H_year_generacion).'&mes='.base64_encode($H_mes_generacion).'" target="_blank">Ver xls</a></td>
					<td><a href="#" onclick="CONFIRMAR(\'sede='.base64_encode($H_sede).'&year_generacion='.base64_encode($H_year_generacion).'&mes='.base64_encode($H_mes_generacion).'\', '.$periodo_actual.', '.$correos_ya_enviados_js.')">Enviar</a></td>
				 </tr>';
		}
	}
	else
	{
		echo'<tr><td colspan="5">Sin Honorarios Generados</td></tr>';
	}
	$sqli_H->free();
	$conexion_mysqli->close();
	@mysql_close($conexion);
	?>
</tbody>    
</table>
</div>
</body>
</html>