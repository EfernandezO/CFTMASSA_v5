<? include ("../../SC/seguridad.php");?>
<? include ("../../SC/privilegio2.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<style type="text/css">
<!--

#Layer3 {
	position:absolute;
	width:656px;
	height:71px;
	z-index:2;
	left: 21px;
	top: 33px;
}
#Layer4 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:2;
}
#Layer1 {
	position:absolute;
	width:701px;
	height:115px;
	z-index:1;
	left: 24px;
	top: 51px;
}
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
.Estilo2 {
	color: #FF0000;
	font-weight: bold;
}
-->
</style>
<title>Listador de Morosos</title>

<head>

<?
	if($_POST)
	{
		include("../../../funciones/funcion.php");
		include("../../../funciones/conexion.php");
		
		extract($_POST);
		
		$fcarrera=str_inde($fcarrera);
		$fjornada=str_inde($fjornada);
		$fsede=str_inde($fsede);
		$fnivel=str_inde($fnivel);
		
		$fecha_v=fecha_mysql(false,"$fdia/$fmes/$fano");
		$semestre=substr(actual_semestre($fmes),0,1);
		
		
		$contador_moro=0;
		$aux=0;
		$aux2=0;
		$salto_cada=10;
		$escritos=0;
		
		if($fnivel=="T")
		{
			$cons="SELECT id,rut,apellido,nombre,situacion,carrera FROM alumno WHERE carrera='$fcarrera' and jornada='$fjornada' and sede='$fsede' and situacion='M' ORDER BY carrera" ;
		}
		else
		{
		$cons="SELECT id,rut,apellido,nombre,situacion,carrera FROM alumno WHERE carrera='$fcarrera' and jornada='$fjornada' and sede='$fsede' and nivel='$fnivel' and situacion='M' ORDER BY carrera";
		}
		//echo"-> $cons<br>";
		
		$sql=mysql_query($cons)or die("ERROR :<br>".mysql_error());
		$numero_filas=mysql_num_rows($sql);
		
		//echo"numero de alumnos $numero_filas<br>";
		
			echo'
				<div id="Layer1" name="Layer1">
				<table width="702" border="0">
				<tr>
				<td colspan="5" bgcolor="#99FF00"><div align="center" class="Estilo1">Detalle Alumnos Morosos</div></td>
				</tr>
  				<tr>
				<td width="89" bgcolor="#66CCFF"><div align="center"><strong>Rut</strong></div></td>
   				 <td width="230" bgcolor="#66CCFF"><div align="center"><strong>Nombre</strong></div></td>
    			<td width="148" bgcolor="#66CCFF"><div align="center"><strong>Carrera</strong></div></td>
    			<td width="99" bgcolor="#66CCFF"><div align="center"><strong>Deuda</strong></div></td>
    			<td width="114" bgcolor="#66CCFF"><div align="center"><strong>Vencio </strong></div></td>
  				</tr>';
				

		while($A=mysql_fetch_array($sql))
		{
			$id=$A["id"];
			$rut=$A["rut"];
			$apellido=$A["apellido"];
			$nombre=$A["nombre"];
			$situacion=$A["situacion"];
			$carrera=$A["carrera"];
			$nombre = ucwords(strtolower($nombre));
	        $apellido = ucwords(strtolower($apellido));
			$alumno="$nombre $apellido";

				
			list($Deuda,$caduca)=letra_morosa($id,$semestre,$fano,$fecha_v,$fopcion);
				
				if($Deuda!=NULL)
				{
					$columnas=count($Deuda);
					$contador_moro++;
					$total_deu=array_sum($Deuda);
					
					if($aux%2==0)
					{
						$color = "#D5E7FB";
	
					}
					else
					{
					 	$color = "#E8F2FC";
					}
					
					$aux++;
					
					//escribe encabezado
					if(($escritos>0)and($escritos%$salto_cada==0))
					{
						echo'<tr bgcolor="#66CCFF" >
      					<td width="95"><div align="center"><strong>Rut</strong></div></td>
     					 <td width="232"><div align="center"><strong>Nombre</strong></div></td>
						 <td width="72"><div align="center"><strong>Carrera</strong></div></td>
     					 <td width="102"><div align="center"><strong>Deuda</strong></div></td>
	  					 <td width="179"><div align="center"><strong>Vencio</strong></div></td>
						</tr>';
					}
					
					echo' <tr bgcolor="'.$color.'">
   					 <td rowspan="'.$columnas.'" valign="top">'.$rut.'</td>
					 <td rowspan="'.$columnas.'" valign="top">'.$alumno.'</td>
    				<td rowspan="'.$columnas.'" valign="top">'.$carrera.'</td>';
					for($x=0;$x<$columnas;$x++)
					{
						if($x==0)
						{
						echo'
							<td ><div align="right">$'.$Deuda[$x].'</div></td>
							<td><div align="center">'.fecha_format($caduca[$x]).'</div></td>
 					    	</tr>';
						}
						else
						{
							
							if($aux2%2==0)
							{
								$color2="#E0FAC5";
							}
							else
							{
								$color2=$color;
							}
							$aux2++;
							echo'
							<tr bgcolor="'.$color2.'">
							<td><div align="right">$'.$Deuda[$x].'</div></td>
							<td><div align="center">'.fecha_format($caduca[$x]).'</div></td>
							</tr>';
						}
					}
					$escritos++;
					
					echo' <tr>
    					<td colspan="2" bgcolor="#FFFFCC"><b>Total Deuda</b></td>
						<td bgcolor="#FFFFCE">&nbsp;</td>
    					<td bgcolor="#FFFFCC"><div align="right"><span class="Estilo2">$'.$total_deu.'</span></div></td>
						<td bgcolor="#FFFFCE">&nbsp;</td>
  						</tr><tr><td colspan="4"></td></tr>';	 
				}
				
		}		
				
	echo' <tr>
    <td bgcolor="#63CFFF">Total Alumnos</td>
    <td colspan="4" bgcolor="#63CFFF">'.$numero_filas.' Encontrados en la consulta, '.$contador_moro.' de ellos son Morosos al '.fecha_format($fecha_v).'</td>
  </tr>';			
}

?>
</table>
</div>
</div>
</body>
</html>

