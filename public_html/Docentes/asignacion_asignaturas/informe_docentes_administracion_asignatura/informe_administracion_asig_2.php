<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("informe_asignacion_asignatura_docente_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_POST)
{
	$continuar=true;
	$sede=$_POST["fsede"];
	$year=$_POST["year"];
	$semestre=$_POST["semestre"];	
}
else
{ $continuar=false;}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>Informe Administracion de Asignaturas</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<title>Untitled Document</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 159px;
}
</style>
<script language="javascript">
function CONFIRMAR()
{
	c=confirm('Seguro(a) Desea Asignar directamente la Asignacion de asignatura...??');
	if(c){document.getElementById('frm').submit();}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Informe Asignacion Asignatura</h1>
<div id="link"><br>
<a href="informe_administracion_asig_1.php" class="button">Volver a Seleccion</a><br />
<br />
<a href="#" onclick="CONFIRMAR()" class="button_R">Asignacion Masiva Automatica</a></div>
<div id="apDiv1">
<form action="asignacion_automatica.php" method="post" id="frm">
<table width="100%" border="1" align="center">
      <thead>
        <tr>
          <th colspan="13">Docentes Asignacion Asignatura<?php echo "$sede Periodo [$semestre - $year]";?> <input name="sede" type="hidden" value="<?php echo $sede;?>" /><input name="semestre" type="hidden" value="<?php echo $semestre;?>" /><input name="year" type="hidden" value="<?php echo $year;?>" /></th>
        </tr>
      </thead>
      <tr>
      	<td>N.</td>
        <td>Docente</td>
        <td colspan="2">utilizar para generacion</td>
        <td>N. Asignaturas</td>
        <td>Valor Hora</td>
        <td>Total</td>
      </tr>
      <tbody>
      <?php
      if($continuar)
	  {
		  require("../../../../funciones/conexion_v2.php");
		  require("../../../../funciones/funciones_sistema.php");
		  
		  $sede=mysqli_real_escape_string($conexion_mysqli, $sede);
		  $semestre=mysqli_real_escape_string($conexion_mysqli, $semestre);
		  $year=mysqli_real_escape_string($conexion_mysqli, $year);
		  
		  //---------------------------------------//
		   require("../../../../funciones/VX.php");
		   $evento="Informe Administracion de Asignaturas sede: $sede [$semestre $year]";
		   REGISTRA_EVENTO($evento);
		   //-----------------------------------------------//
		  
	  		$cons="SELECT toma_ramo_docente.*, mallas.nivel AS nivel FROM toma_ramo_docente INNER JOIN personal ON toma_ramo_docente.id_funcionario=personal.id  LEFT JOIN mallas ON toma_ramo_docente.cod_asignatura=mallas.cod AND toma_ramo_docente.id_carrera=mallas.id_carrera WHERE toma_ramo_docente.sede='$sede' AND toma_ramo_docente.semestre='$semestre' AND toma_ramo_docente.year='$year' ORDER by personal.apellido_P, personal.apellido_M, personal.id";
			if(DEBUG){echo"->$cons<br>";}
			$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
			$num_registros=$sqli->num_rows;
			$SUMA_TOTAL=0;
			$TOTAL_funcionario=0;
			if($num_registros>0)
			{
				$contador=0;
				$id_funcionario_old=0;
				$valor_hora_old=0;
				$id_carrera_old=0;
				$primera_vuelta=true;
				while($AS=$sqli->fetch_assoc())
				{
					$AS_nivel=$AS["nivel"];
					if(empty($AS_nivel)){ $AS_nivel=0;}
					$AS_id_funcionario=$AS["id_funcionario"];
					$AS_id_carrera=$AS["id_carrera"];
					$AS_jornada=$AS["jornada"];
					$AS_grupo=$AS["grupo"];
					$AS_cod_asignatura=$AS["cod_asignatura"];
					$AS_numero_horas=$AS["numero_horas"];
					$AS_valor_hora=$AS["valor_hora"];
					$AS_total=$AS["total"];
					$AS_numero_cuotas=$AS["numero_cuotas"];
					$AS_condicion=$AS["condicion"];
					
					if(DEBUG){ echo"----> id_funcionario: $AS_id_funcionario <br>---->  id_carrera: $AS_id_carrera <br>----> cod_asignatura: $AS_cod_asignatura<br><br>";}
					//---------------------------------------------------------//
					
					if(!$primera_vuelta)
					{
						if($id_funcionario_old!=$AS_id_funcionario)
						{
							$contador++;
							$TOTAL_funcionario=($cuenta_asignaturas*$valor_hora_old);
							$SUMA_TOTAL+=$TOTAL_funcionario;
							echo'<tr>
							<td>'.$contador.'</td>
							<td>'.$F_docente.' ['.$id_funcionario_old.']</td>';
							if($cuenta_asignaturas>0)
							{
								echo'<td align="center"><input name="DOCENTE['.$id_funcionario_old.']" type="radio" value="si" /> (si)</td>
									 <td align="center"><input name="DOCENTE['.$id_funcionario_old.']" type="radio" value="no" checked="checked"/> (no)</td>
									 <td align="center">'.$cuenta_asignaturas.'<input name="numero_asignaturas['.$id_funcionario_old.']" type="hidden" value="'.$cuenta_asignaturas.'" /></td>
							<td align="right">'.number_format($valor_hora_old,0,",",".").'<input name="valor_hora['.$id_funcionario_old.']" type="hidden" value="'.$valor_hora_old.'" /></td>';
							}
							else
							{
								echo'<td align="center">---</td>
								<td align="center">---</td>
								<td align="center">'.$cuenta_asignaturas.'</td>
								<td align="right">'.number_format($valor_hora_old,0,",",".").'</td>';
							}
						echo'
							<td align="right">'.number_format($TOTAL_funcionario,0,",",".").'</td>
							</tr>';	
							if($AS_nivel>0){$cuenta_asignaturas=1;}
							else{ $cuenta_asignaturas=0;}
						}
						else
						{
							if($AS_nivel>0){$cuenta_asignaturas++;}
						}
					}
					else
					{ 
						if($AS_nivel>0){$cuenta_asignaturas=1;}
						else{ $cuenta_asignaturas=0;}
					}
							
					//------------------------------------------------------/
					$F_docente=NOMBRE_PERSONAL($AS_id_funcionario);
					//--------------------------------------------------------------------//	
					//carrera
					$nombre_carrera=NOMBRE_CARRERA($AS_id_carrera);
					//----------------------------//
					//asignatura
					list($nombre_asignatura, $nivel_asignatura)=NOMBRE_ASIGNACION($AS_id_carrera, $AS_cod_asignatura);
					//----------------------------------------------------------------//
					//-----------------------------------------------//		
					$primera_vuelta=false;
					
					$id_funcionario_old=$AS_id_funcionario;
					$id_carrera_old=$AS_id_carrera;
					$valor_hora_old=$AS_valor_hora;
							
					
				}
				$contador++;
				$TOTAL_funcionario=($cuenta_asignaturas*$AS_valor_hora);
				$SUMA_TOTAL+=$TOTAL_funcionario;
				echo'<tr>
							<td>'.$contador.'</td>
							<td>'.$F_docente.'['.$id_funcionario_old.']</td>';
							if($cuenta_asignaturas>0)
							{
								echo'<td align="center"><input name="DOCENTE['.$AS_id_funcionario.']" type="radio" value="si" />(si)</td>
								<td align="center"><input name="DOCENTE['.$AS_id_funcionario.']" type="radio" value="no" checked="checked"/>(no)</td>
								<td align="center">'.$cuenta_asignaturas.'<input name="numero_asignaturas['.$id_funcionario_old.']" type="hidden" value="'.$cuenta_asignaturas.'" /></td>
							<td align="right">'.number_format($AS_valor_hora,0,",",".").'<input name="valor_hora['.$AS_id_funcionario.']" type="hidden" value="'.$AS_valor_hora.'" /></td>';
							}
							else
							{
								echo'<td align="center">---</td>
								<td align="center">---</td>
								<td align="center">'.$cuenta_asignaturas.'</td>
								<td align="right">'.number_format($AS_valor_hora,0,",",".").'</td>';
							}
					   echo'
							<td align="right">'.number_format($TOTAL_funcionario, 0,",",".").'</td>
							</tr>
						<tr>
							<td colspan="6">TOTAL X ADMINISTRACION ASIGNATURA</td>
							<td align="right">'.number_format($SUMA_TOTAL,0,",",".").'</td>
						</tr>';	
			}
			else
			{ echo'<tr><td colspan="13">Sin Asignaciones Creadas</td></tr>';}
			$sqli->free();
			
		  @mysql_close($conexion);
		  $conexion_mysqli->close();
	  }
	  else
	  { echo'<tr><td colspan="13">Sin datos</td></tr>';}
	  ?>
      </tbody>
  </table>
</form>  
</div>
</body>
</html>