<? include ("../../SC/seguridad.php");?>
<? include ("../../SC/privilegio2.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<style type="text/css">
<!--

#Layer3 {
	position:absolute;
	width:718px;
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
.Estilo1 {color: #FF0000}
.Estilo2 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
<title>Listador de Morosos</title><head>
<?
	if($_POST)
	{
		include("../../../funciones/funcion.php");
		include("../../../funciones/conexion.php");
		
		extract($_POST);
		
		//$fcarrera=str_inde($fcarrera);
		//$fjornada=str_inde($fjornada);
		$fsede=str_inde($fsede);
		$fnivel=str_inde($fnivel);
		
		$fecha_v=fecha_mysql(false,"$fdia/$fmes/$fano");
		$semestre=substr(actual_semestre($fmes),0,1);
		
		if(checkdate($fmes,$fdia,$fano))
		{
			$fecha_v=fecha_mysql(false,"$fdia/$fmes/$fano");
		}
		else
		{
			$fecha_v=fecha_mysql();
		}
		
		
		
		
		$aux=0;
		$alumno_moroso=0;
		$salto_cada=10;
		$escritos=0;
		
		if($fnivel=="todos")
		{
			$cons="SELECT id,rut,apellido,nombre,carrera,situacion FROM alumno WHERE sede='$fsede' AND situacion ='M' ORDER BY carrera";
		}
		else
		{
				$cons="SELECT id,rut,apellido,nombre,carrera,situacion FROM alumno WHERE sede='$fsede' AND nivel='$fnivel' AND situacion ='M' ORDER BY carrera";
		}	
		
		//echo"-> $cons<br>";
		
		$sql=mysql_query($cons)or die("ERROR".mysql_error());
		$numero_filas=mysql_num_rows($sql);
		
		//echo"numero de alumnos $numero_filas<br>";
		
		echo'
		<div id="Layer3">
  <table width="719" height="98" border="0">
  <tr>
  <td colspan="5" align="center" bgcolor="#CCCCCC"> <span class="Estilo2">Listado De Alumnos Morosos</span></td>
  </tr>
    <tr bgcolor="#CCFF33" >
      <td width="99"><div align="center"><strong>Rut</strong></div></td>
      <td width="119"><div align="center"><strong>Nombre</strong></div></td>
	  <td width="195"><div align="center"><strong>Carrera</strong></div></td>
      <td width="105"><div align="center"><strong>Deuda</strong></div></td>
	  <td width="179"><div align="center"><strong>Vencio</strong></div></td>
    </tr>';
		while($A=mysql_fetch_array($sql))
		{
			$id=$A["id"];
			$rut=$A["rut"];
			$apellido=$A["apellido"];
			$nombre=$A["nombre"];
			$carrera=$A["carrera"];
			$situacion=$A["situacion"];
			
			$nombre = ucwords(strtolower($nombre));
	        $apellido = ucwords(strtolower($apellido));
			
			$alumno="$nombre $apellido";
			
			//aqui buscar letras adeudadas si alumno moroso
			
				
				
				list($Deuda,$caduca)=letra_morosa($id,"0","0",$fecha_v,"V");
				$num_inpagas=count($Deuda);
				
			if($Deuda!=NULL)
			 {
			    $alumno_moroso++;
				
				if($aux%2==0)
				{
					$color="#FFFFCC";
				}
				else
				{
					$color="#FFCCFF";
				}
				//se escriba el encabezado de tabla
				if(($escritos>0)and($escritos%$salto_cada==0))
				{
					echo'<tr bgcolor="#CCFF33" >
      				<td width="99"><div align="center"><strong>Rut</strong></div></td>
     				 <td width="119"><div align="center"><strong>Nombre</strong></div></td>
					 <td width="195"><div align="center"><strong>Carrera</strong></div></td>
     				 <td width="105"><div align="center"><strong>Deuda</strong></div></td>
	  				 <td width="179"><div align="center"><strong>Vencio</strong></div></td>
					</tr>';
				}	
				
				$aux++;
				echo'	
			   <tr  bgcolor="'.$color.'">
      			<td rowspan="'.$num_inpagas.'" valign="top">'.$rut.'</td>
      			<td rowspan="'.$num_inpagas.'" valign="top">'.$alumno.'</td>
				<td rowspan="'.$num_inpagas.'" valign="top">'.$carrera.'</td>';
				
				$total_deuda=array_sum($Deuda);
				for($x=0;$x<$num_inpagas;$x++)
				{
					   	echo'<td bgcolor="'.$color.'" align="center">$ '.$Deuda[$x].'</td>';
						echo'<td bgcolor="'.$color.'" align="center">'.fecha_format($caduca[$x]).'</td>
	</tr>';	
					
					
				}
				$escritos++;
				echo'
				<tr bgcolor="'.$color.'">
				<td colspan="3">Total Deuda</td>
				<td align="center"><span class="Estilo1"><strong>$'.$total_deuda.'
			    </strog> 
			    </span></td>
				<td>&nbsp;</td>
				</tr>
				<tr>
				</tr>';
				
				
			
		
		  }
		}
		echo'
		<tr bgcolor="#CCFF66">
		<td>Total:</td>
		<td colspan="4">'.$numero_filas.' Alumnos Encontrados En la consulta, '.$alumno_moroso.' de ellos son Morosos al '.fecha_format($fecha_v).' </td>
		<tr>';
	}	

?>
</table>
</div>
</body>
</html>

