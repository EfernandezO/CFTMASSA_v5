<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="ALUMNO";
	OKALIS($lista_invitados);
	define("DEBUG", false);
//-----------------------------------------//	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Notas del Alumno</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
	<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/tooltip_1/tooltip.css"/>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:90%;
	height:91px;
	z-index:1;
	left: 5%;
	top: 286px;
}
#Layer2 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:1;
	left: 48px;
	top: 117px;
}
a:link {
	text-decoration: none;
	color: #006699;
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
#Layer4 {
	position:absolute;
	width:50%;
	height:122px;
	z-index:2;
	left: 5%;
	top: 51px;
}
.Estilo3 {font-size: 12px; font-weight: bold; }
.Estilo4 {font-size: 12px}
#Layer3 {
	text-align: right;
	padding-right: 10px;
}
-->
</style>
</head>

<body>
<h1 id="banner">Administrador -  Registro Notas Parciales</h1>

<div id="Layer3"><br />
<a href="../okalumno.php" class="button">Volver a menu </a><br />
<a></a>
</div>

<?php
include("../../../funciones/conexion.php");
include("../../../funciones/funcion.php");

if(DEBUG){ var_dump($_SESSION["USUARIO"]);}
if($_SESSION["USUARIO"]["autentificado"]=="SI")
{
	$id_alumno=$_SESSION["USUARIO"]["id"];
	$id_carrera=$_SESSION["USUARIO"]["id_carrera"];
	$frut=$_SESSION["USUARIO"]["rut"];
	$fcarrera=$_SESSION["USUARIO"]["carrera"];
	$fsede=$_SESSION["USUARIO"]["sede"];
	
	$mostrar_boton=true;
	$rut_alumno=$_SESSION["USUARIO"]["rut"];
	$apellido=$_SESSION["USUARIO"]["apellido"];
	
	$nombre=$_SESSION["USUARIO"]["nombre"];
	$nivel=$_SESSION["USUARIO"]["nivel"];
	$ingreso=$_SESSION["USUARIO"]["ingreso"];
	
	
	$alumno=ucwords(strtolower($nombre." ".$apellido));
}
else
{
	
	echo"No hay Alumno Seleccionado...";
}	



?>
<div id="Layer1">
  <table width="100%" border="0" Sumary="notas">
  <caption>
  Notas Parciales
  </caption>
  <thead>
     <tr>
      <th>Asignatura</th>
      <th>Intento</th>
      <th>Condicion</th>
      <th>Nota</th>
      <th>Metodo Evaluacion</th>
      <th>Poderacion</th>
      <th>Fecha Recepcion</th>
      <th>Fecha Ingreso</th>
	  <th>Semestre</th>
	  <th>Año</th>
      <th>Observacion</th>
      <th>Observacion 2</th>
      <th colspan="2">Opci&oacute;n</th>
    </tr>
	</thead>
	<tbody>
	<form id="form" name="form" method="post" action="../edit_nota/edit_nota1.php">
	<?php
	
	$cons="SELECT * FROM notas_parciales WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' ORDER by asignatura, intento";
	if(DEBUG){ echo"$cons<br>";}
	$sql=mysql_query($cons)or die(mysql_error());
	$num_notas=mysql_num_rows($sql);
$conta=1;
if($num_notas>0)
{
	$primera_vuelta=true;
	$CALCULO_NOTAS=array();
	
	$i=0;
	while($N=mysql_fetch_assoc($sql))
	{
		
		
		$mostrar_promedio=false;
		$id_nota_parcial=$N["id"];
		$tabla=$N["tabla"];
		$condicion=$N["condicion"];
		$n_intento=$N["intento"];
		$cod_asignatura=$N["asignatura"];
		$observacion=$N["observacion"];
		$observacion_2=$N["observacion_2"];
		$semestre_N=$N["semestre"];
		$fecha_ingreso=$N["fecha"];
		$fecha_recepcion=$N["fecha_recepcion"];
		$año_N=$N["ano"];
		$nota=$N["nota"];
		$metodo_evaluacion=$N["metodo_evaluacion"];
		$porcentaje=$N["porcentaje"];
		
		
		if($primera_vuelta){ $ultimo_cod_asignatura=$cod_asignatura; $primera_vuelta=false; $ultimo_intento=$n_intento;}
		
		if($cod_asignatura!=$ultimo_cod_asignatura)
		{ $mostrar_promedio=true;}
		else
		{
			if($ultimo_intento!=$n_intento)
			{$mostrar_promedio=true;}
		}
		
		if($mostrar_promedio){ if(DEBUG){var_dump($CALCULO_NOTAS);} $promedio=CALCULA_PROMEDIO($CALCULO_NOTAS); echo'<tr><td colspan="3"><strong>Promedio</strong></td><td colspan="11"><strong>'.$promedio.'</strong></td></tr>'; $CALCULO_NOTAS=array(); $i=0;}
		
		$CALCULO_NOTAS[$i]["metodo_evaluacion"]=$metodo_evaluacion;
		$CALCULO_NOTAS[$i]["nota"]=$nota;
		$CALCULO_NOTAS[$i]["porcentaje"]=$porcentaje;
		$i++;
		
		switch($tabla)
		{
			case"asignatura":
				//busco el nombre de la asignatura
				$cons_na="SELECT asignatura FROM asignatura WHERE id='$cod_asignatura' LIMIT 1";
				//echo"$cons_na";
				$sql_na=mysql_query($cons_na)or die("ERROR en nombre Asignatura".mysql_error());
				$AsigX=mysql_fetch_row($sql_na);
				$nom_asignatura=$AsigX[0];
				mysql_free_result($sql_na);
			break;
			case"mallas":
				$cons_na="SELECT ramo FROM mallas WHERE cod='$cod_asignatura' AND id_carrera='$id_carrera' LIMIT 1";
				//echo"$cons_na";
				$sql_na=mysql_query($cons_na)or die("ERROR en nombre Asignatura Malla".mysql_error());
				$AsigX=mysql_fetch_row($sql_na);
				$nom_asignatura=$AsigX[0];
				mysql_free_result($sql_na);
			break;
		}
		
		
		echo'
		<tr>
      <td><a href="#" title="'.$tabla.'">'.$nom_asignatura.'</a></td>
	  <td>'.$n_intento.'</td>
	  <td><div id="div_condicion_'.$id_nota_parcial.'">'.$condicion.'</div></td>
      <td align="center"><div id="div_nota_'.$id_nota_parcial.'"><a href="#">'.$nota.'</a></div></td>
	  <td align="center">'.$metodo_evaluacion.'</td>
	  <td align="center">'.$porcentaje.'%</td>
	  <td><div id="div_fecha_recepcion_'.$id_nota_parcial.'">'.fecha_format($fecha_recepcion).'</div></td>
	  <td align="center"><div id="div_fecha_ingreso_'.$id_nota_parcial.'">'.fecha_format($fecha_ingreso).'</div></td>
	  <td align="center">'.$semestre_N.'</td>
	  <td align="center">'.$año_N.'</td>
	  <td align="center"><div id="div_observacion_'.$id_nota_parcial.'"><a href="#" class="tooltip" title="'.$observacion.'">'.substr($observacion,0,5).'</a></div></td>
	  <td align="center"><div id="div_observacion_2_'.$id_nota_parcial.'"><a href="#" class="tooltip" title="'.$observacion_2.'">'.substr($observacion_2,0,5).'</a></div></td>
	  <td><div id="div_edicion_'.$id_nota_parcial.'">&nbsp;</div></td>';
	 echo'<td>&nbsp;</td>';
		
		echo'</tr>';
		
		$ultimo_cod_asignatura=$cod_asignatura;
		$ultimo_intento=$n_intento;
	}
	if(1==1){ if(DEBUG){var_dump($CALCULO_NOTAS);} $promedio=CALCULA_PROMEDIO($CALCULO_NOTAS); echo'<tr><td colspan="3"><strong>Promedio</strong></td><td colspan="11"><strong>'.$promedio.'</strong></td></tr>'; $CALCULO_NOTAS=array(); $i=0;}
}
else
{
	//no hay notas
	echo'<tr>
      <td colspan="14" align="center"><b>Sin Notas...</b></td></tr>';
}
echo'
<input name="ocu_carreraX" type="hidden" id="ocu_carreraX"  value="'.$fcarrera.'"/>
<input name="ocu_sedeX" type="hidden" id="ocu_sedeX"  value="'.$fsede.'"/>
<input name="ocu_rutX" type="hidden" id="ocu_rutX"  value="'.$frut.'"/></form><tr>
      <td  colspan="13">&nbsp;</td>
      <td>
                              <div align="right">...';
							  //muestro boton si hay alumno seleccionado bien
							 
								//////////////////
          echo'</div>
     </td>
  </tr>
  </tbody>'; 		

mysql_close($conexion);
?>
</tfoot>
</table>
</div>
<div id="Layer4">
  <table width="95%" border="0" sumary="datos">
  	<thead>
    	<tr>
        	<th colspan="4">Datos Alumno</th>
        </tr>
    </thead>
	<tbody>
	
    <tr class="odd">
      <td width="22%"><strong>ID</strong></td>
      <td colspan="3" class="Estilo4"><?php echo $id_alumno;?></td>
    </tr>
    <tr class="odd">
      <td>Rut</td>
      <td colspan="3"><?php echo $rut_alumno?></td>
    </tr>
    <tr>
      <td>Alumno</td>
      <td colspan="3" ><?php echo $alumno;?></td>
    </tr>
    <tr class="odd">
      <td>Carrera</td>
      <td colspan="3"><?php echo $fcarrera;?></td>
    </tr>
    <tr class="odd">
      <td>Promoción</td>
      <td width="25%"><?php echo $nivel;?></td>
      <td width="26%">A&ntilde;o</td>
      <td width="27%"><?php echo $ingreso;?></td>
    </tr>
    <tr class="odd">
      <td>Sede</td>
      <td colspan="3"><?php echo $fsede;?></td>
    </tr>
	</tbody>
  </table>
</div>
<?php
function CALCULA_PROMEDIO($array_notas)
{
	if(DEBUG){ echo"<strong>Inicio CALCULO Promedio</strong><br>";}

	$cuenta_notas=0;
	$PROMEDIO_X=0;
	foreach($array_notas as $n=>$aux_array)
	{
		$cuenta_notas++;
		
		$aux_metodo=$aux_array["metodo_evaluacion"];
		$aux_nota=$aux_array["nota"];
		$aux_porcentaje=$aux_array["porcentaje"];
		
		if(($aux_metodo==0)or(empty($aux_metodo))){ $aux_metodo="normal";}
		
		switch($aux_metodo)
		{
			case"normal":
				$PROMEDIO_X+=$aux_nota;
				break;
			case"ponderado":
				$PROMEDIO_X+=(($aux_porcentaje/100)*$aux_nota);
				break;
		}
		if(DEBUG){ echo"$n -> [$aux_metodo] $aux_porcentaje % -> $aux_nota<br>";}
	}
	
	switch($aux_metodo)
		{
			case"normal":
				$PROMEDIO_X=($PROMEDIO_X/$cuenta_notas);
				break;
			case"ponderado":
				$PROMEDIO_X=$PROMEDIO_X;
				break;
		}
		
		if(DEBUG){ echo"PROMEDIO: [$aux_metodo] $PROMEDIO_X<br>";}
	return($PROMEDIO_X);	
}
?>
</body>
</html>