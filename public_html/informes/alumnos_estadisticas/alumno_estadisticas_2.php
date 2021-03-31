<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG",false);
	$O=new OKALIS();
	$O->setDisplayErrors(true);
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("informe_alumno_estadisticas_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>Alumno Condicion Contrato</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:442px;
	z-index:1;
	left: 5%;
	top: 202px;
}
</style>
<script src="../../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../SpryAssets/SpryCollapsiblePanel.css"/>
<style type="text/css">
#apDiv2 {
	position:absolute;
	width:90%;
	height:29px;
	z-index:2;
	left: 5%;
	top: 79px;
}
</style>
</head>
<?php
if($_POST)
{
	if(DEBUG){ var_dump($_POST);}
	$sede=$_POST["sede"];
	$id_carrera=$_POST["id_carrera"];
	
	$yearIngreso=$_POST["year_ingreso"];
	$jornada=$_POST["jornada"];

}
?>
<body>
<h1 id="banner">Administrador - Estadisticas</h1>
<div id="link"><br><a href="alumno_estadisticas.php" class="button">Volver a Selecci&oacute;n</a></div>
<div id="apDiv2"><strong>Alumnos<br />
    	Jornada:<?php echo $jornada;?> <br />
    	  A&ntilde;o Ingreso:<?php echo $yearIngreso;?> - Sede: <?php echo $sede;?><br />
    	 </strong></div>
<div id="apDiv1">
  <div id="CollapsiblePanel1" class="CollapsiblePanel">
    <div class="CollapsiblePanelTab" tabindex="0">Alumnos (VER)</div>
    <div class="CollapsiblePanelContent">
    <table border="1" align="center">
<thead>
	<tr>
    	<td colspan="12" align="center">Listado de Alumnos</td>
    </tr>
	<tr>
    	<th>N&deg;</th>
        <th>Sexo</td>
        <th>Sede</th>
        <th>Year ingreso</th>
        <th>ID</th>
        <th>Pais Origen</th>
        <th>Run</th>
        <th>Nombre</th>
        <th>Apellido P</th>
        <th>Apellido M</th>
        <th>Estado Civil</th>
        <th>Edad</th>
        <th>Ciudad</th>
        <th>Carrera</th>
        <th>Jornada</th>
        <th>Area Residencia</th>
        <th>Rango Edad</th>
        <th>Año egreso Liceo</th>
        <th>Liceo procedencia</th>
        <th>Liceo Formacion</th>
        <th>Liceo Dependencia</th>
        
        <th>Otros Estudios Universitarios</th>
        <th>Otros Estudios Tecnicos</th>
        <th>Otros Estudios Profesionales</th>
        
    </tr>
</thead>
<tbody>
<?php
if($_POST)
{
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	require("../../../funciones/class_ALUMNO.php");
	require("../../../funciones/class_LISTADOR_ALUMNOS.php");
	//////////////////////
	///////////////////////
	$ARRAY_LICEO=array();
	$LICEO_FORMACION=array();
	$CIUDAD=array();
	$FUERA_CIUDAD=array();
	$ARRAY_NACIONALIDAD=array();
	$primera=true;
	$contador=0;
	$contador_parcial=0;
	$ARRAY_CIUDADES=array();
	$rango_1=0;
	$rango_2=0;
	$rango_3=0;
	
	$LISTA = new LISTADOR_ALUMNOS();

	$LISTA->setDebug(DEBUG);
	
	$LISTA->setGrupo(0);
	$LISTA->setId_carrera($id_carrera);
	$LISTA->setJornada($jornada);
	$LISTA->setNiveles(array(1));
	$LISTA->setSede($sede);
	$LISTA->setYearIngressoCarrera($yearIngreso);
	$LISTA->setSituacionAcademica("A");
	
	$LISTA->setSemestreVigencia(1);
	$LISTA->setYearVigencia($yearIngreso);
	
	$mostrar=true;
	
	$num_reg=$LISTA->getTotalAlumno();
	if($num_reg>0)
	{
		foreach($LISTA->getListaAlumnos() as $n =>$auxALUMNO){
			
		
			$auxALUMNO->IR_A_PERIODO(1, $yearIngreso);
			$A_sexo=$auxALUMNO->getSexo();
			$A_jornada=$auxALUMNO->getJornadaPeriodo();
			$A_rut=$auxALUMNO->getRut();
			$A_nombre=$auxALUMNO->getNombre();
			$A_apellido_P=$auxALUMNO->getApellido_P();
			$A_apellido_M=$auxALUMNO->getApellido_M();
			$A_ciudad=$auxALUMNO->getCiudad();
			$A_nacionalidad=$auxALUMNO->getNacionalidad();
			$A_liceo_formacion=$auxALUMNO->getLiceoFormacion();
			$A_liceo_dependencia=$auxALUMNO->getLiceoDependencia();
			$A_liceo=$auxALUMNO->getLiceo();
			$arrayNacimiento=explode("-",$auxALUMNO->getFechaNacimiento());
			$A_edad=date("Y")-$arrayNacimiento[0];
			$A_id=$auxALUMNO->getIdAlumno();
			$A_id_carrera=$auxALUMNO->getIdCarreraPeriodo();
			$A_yearIngreso=$auxALUMNO->getYearIngresoCarreraPeriodo();
			$A_yearEgresoLiceo=$auxALUMNO->getLiceoYearEgreso();
			$A_otrosEstudiosU=$auxALUMNO->getOtrosEstudiosU();
			$A_otrosEstudiosT=$auxALUMNO->getOtrosEstudiosT();
			$A_otrosEstudiosP=$auxALUMNO->getOtrosEstudiosP();
			$A_estadoCivil=$auxALUMNO->getEstadoCivil();
			
			
			
			$mostrar=$auxALUMNO->getPresenteEnPeriodo();
			$mostrar=true;
			
			if($mostrar)
			{
				
				
				$contador++;
				
						
						if(isset($ARRAY_NACIONALIDAD[$A_nacionalidad][$A_sexo]))		
						{$ARRAY_NACIONALIDAD[$A_nacionalidad][$A_sexo]++;}
						else{$ARRAY_NACIONALIDAD[$A_nacionalidad][$A_sexo]=1;}
						
						if(isset($ARRAY_LICEO[$A_liceo][$A_sexo]))		
						{$ARRAY_LICEO[$A_liceo][$A_sexo]++;}
						else{$ARRAY_LICEO[$A_liceo][$A_sexo]=1;}
						
						if(isset($LICEO[$A_liceo_dependencia][$A_sexo]))		
						{$LICEO[$A_liceo_dependencia][$A_sexo]++;}
						else{$LICEO[$A_liceo_dependencia][$A_sexo]=1;}
						
						if(isset($LICEO_FORMACION[$A_liceo_formacion][$A_sexo]))
						{$LICEO_FORMACION[$A_liceo_formacion][$A_sexo]++;}
						else{$LICEO_FORMACION[$A_liceo_formacion][$A_sexo]=1;}
						
						if($A_ciudad==$sede)
						{
							if(isset($CIUDAD[$A_sexo])){$CIUDAD[$A_sexo]++;}
							else{$CIUDAD[$A_sexo]=1;}
							
							$areaResidencia="Dentro ciudad";
						}
						else
						{ 
							if(isset($FUERA_CIUDAD[$A_sexo])){$FUERA_CIUDAD[$A_sexo]++;}
							else{$FUERA_CIUDAD[$A_sexo]=1;}
							
							$areaResidencia="Fuera ciudad";
						}
						
						if(isset($ARRAY_CIUDADES[$A_ciudad][$A_sexo])){ $ARRAY_CIUDADES[$A_ciudad][$A_sexo]+=1;}
						else{ $ARRAY_CIUDADES[$A_ciudad][$A_sexo]=1;}
						
						//rango edades
						if($A_edad<21)
						{ $rango_1+=1; $rangoEdadLabel="<21";}
						
						if(($A_edad>=21)and($A_edad<=25))
						{ $rango_2+=1; $rangoEdadLabel="[21 - 25]";}
						
						if($A_edad>25)
						{ $rango_3+=1; $rangoEdadLabel=">25";}	
						
						if(isset($SUMA_CARRERA[$A_id_carrera]))	
						{$SUMA_CARRERA[$A_id_carrera]++;}
						else{$SUMA_CARRERA[$A_id_carrera]=1;}
						
						if(isset($SEXO[$A_sexo]))
						{$SEXO[$A_sexo]++;}
						else{$SEXO[$A_sexo]=1;}
						
						//-----------------------------------------------------------//		
						echo'<tr>
						<td>'.$contador.'</td>
						<td>'.$A_sexo.'</td>
						<td>'.$sede.'</td>
						<td>'.$A_yearIngreso.'</td>
						<td>'.$A_id.'</td>
						<td>'.$A_nacionalidad.'</td>
						<td>'.$A_rut.'</td>
						<td>'.$A_nombre.'</td>
						<td>'.$A_apellido_P.'</td>
						<td>'.$A_apellido_M.'</td>
						<td>'.$A_estadoCivil.'</td>
						<td>'.$A_edad.'</td>
						<td>'.$A_ciudad.'</td>
						<td>'.NOMBRE_CARRERA($A_id_carrera).'</td>
						<td>'.$A_jornada.'</td>
						<td>'.$areaResidencia.'</td>
						<td>'.$rangoEdadLabel.'</td>
						<td>'.$A_yearEgresoLiceo.'</td>
						<td>'.$A_liceo.'</td>
						<td>'.$A_liceo_formacion.'</td>
						<td>'.$A_liceo_dependencia.'</td>
						<td>'.$A_otrosEstudiosU.'</td>
						<td>'.$A_otrosEstudiosT.'</td>
						<td>'.$A_otrosEstudiosP.'</td>
				
					
						</tr>';
						//-----------------------------------------------------------//
			}//fin si mostrar
			
		}
		
					echo'<tr>
						<td colspan="3"><strong>TOTAL</strong><td>
						<td colspan="9" align="right"><strong>'.$contador.'</strong></td>
						</tr>
						<tr>
							<td colspan="15">&nbsp;</td>
						</tr>';
	}
	else
	{ echo"Sin Resultados X esta consulta<br>";}
	
	$conexion_mysqli->close();
}
else
{ echo"Sin Datos<br>";}
/////////////////////////---------------------------------------------///////////////////////////

?>
</tbody>
</table>
    </div>
  </div>
 
<br />
<div id="CollapsiblePanel2" class="CollapsiblePanel">
  <div class="CollapsiblePanelTab" tabindex="0">Estadisticas</div>
  <div class="CollapsiblePanelContent">
  <table width="50%" border="1">
  <thead>
  <tr>
    <th colspan="5">Area de Residencia</th>
    </tr>
   </thead>
   <tbody> 
    <tr>
    <td width="47%">Area</td>
    <td width="27%">Cantidad</td>
    <td width="13%">%</td>
    <td width="13%">Hombres</td>
    <td width="26%">Mujeres</td>
    </tr>
  <?php
  $ciudad_hombres=$CIUDAD["M"];
  $ciudad_mujeres=$CIUDAD["F"];
  if(empty($ciudad_hombres)){ $ciudad_hombres=0;}
  if(empty($ciudad_mujeres)){ $ciudad_mujeres=0;}
  $ciudad_total=$ciudad_hombres+$ciudad_mujeres;
  
  $fuera_ciudad_hombres=$FUERA_CIUDAD["M"];
  $fuera_ciudad_mujeres=$FUERA_CIUDAD["F"];
  if(empty($fuera_ciudad_hombres)){ $fuera_ciudad_hombres=0;}
  if(empty($fuera_ciudad_mujeres)){ $fuera_ciudad_mujeres=0;}
  $fuera_ciudad_total=$fuera_ciudad_hombres+$fuera_ciudad_mujeres;
  ?>
  <tr>
    <td width="47%">Dentro de la Ciudad(<?php echo $sede;?>)</td>
    <td width="27%"><?php echo $ciudad_total; ?></td>
    <td width="13%"><?php echo($ciudad_total * 100)/($ciudad_total + $fuera_ciudad_total); ?></td>
    <td width="13%"><?php echo $ciudad_hombres;?></td>
    <td width="26%"><?php echo $ciudad_mujeres;?></td>
  </tr>
  <tr>
    <td>Fuera de la Ciudad (<?php echo $sede;?>)</td>
    <td><?php echo $fuera_ciudad_total; ?></td>
    <td><?php echo($fuera_ciudad_total * 100)/($ciudad_total + $fuera_ciudad_total); ?></td>
    <td><?php echo $fuera_ciudad_hombres;?></td>
    <td><?php echo $fuera_ciudad_mujeres;?></td>
  </tr>
   </tbody>
</table>
<br />
  <table width="50%" border="1">
  	<thead>
    <tr>
      <th colspan="3">Rango Edades</th>
      </tr>
      </thead>
      <tbody>
      <tr>
      <td>Rango</td>
      <td>Cantidad</td>
      <td>%</td>
    </tr>
    <tr>
      <td>Menor a 21 a&ntilde;os</td>
      <td><?php echo $rango_1;?></td>
      <td><?php echo($rango_1 * 100)/($rango_1 + $rango_2 + $rango_3); ?></td>
    </tr>
    <tr>
      <td>Entre 21 y 25 a&ntilde;os</td>
      <td><?php echo $rango_2;?></td>
      <td><?php echo($rango_2 * 100)/($rango_1 + $rango_2 + $rango_3); ?></td>
    </tr>
     <tr>
      <td>Mayor a 25 a&ntilde;os</td>
      <td><?php echo $rango_3;?></td>
      <td><?php echo($rango_3 * 100)/($rango_1 + $rango_2 + $rango_3); ?></td>
    </tr>
    </tbody>
  </table>
 <br />
 <table width="50%" border="1">
 <thead>
  <tr>
    <th colspan="5">Resumen X Ciudad</th>
    </tr>
  </thead>
  <tbody>
  <tr>
    <td>Ciudad</td>
    <td>Cantidad</td>
    <td>%</td>
    <td>Hombres</td>
    <td>Mujeres</td>
  </tr>
	<?php
    foreach($ARRAY_CIUDADES as $ciudades=> $array_valores)
	{
		if(isset($array_valores["F"])){$mujeres_ciudad=$array_valores["F"];}
		else{$mujeres_ciudad=0;}
		
		if(isset($array_valores["M"])){$hombres_ciudad=$array_valores["M"];}
		else{ $hombres_ciudad=0;}
		
		$TOTAL_CIUDAD=($mujeres_ciudad+$hombres_ciudad);
		$porcentaje_ciudad=($TOTAL_CIUDAD*100)/ $contador;
		
		echo'<tr>
				<td>'.$ciudades.'</td>
				<td>'.$TOTAL_CIUDAD.'</td>
				<td>'.$porcentaje_ciudad.'</td>
				<td>'.$hombres_ciudad.'</td>
				<td>'.$mujeres_ciudad.'</td>
			</tr>';
				
				
	}
	?>
  </tbody>
</table>
</br>
 <table width="50%" border="1">
 <thead>
  <tr>
    <th colspan="5">Liceos de Procedencia</th>
    </tr>
  </thead>
  <tbody>
  <tr>
  	<td>Dependencia</td>
    <td>Cantidad</td>
    <td>%</td>
    <td>Hombres</td>
    <td>Mujeres</td>
  </tr>
  <?php
  if(count($LICEO)>0)
  {
	  foreach($LICEO as $n=> $valor)
	  {
		  if(isset($valor["M"]))
		  {$aux_M=$valor["M"];}
		  else{$aux_M=0;}
		  
		  if(isset($valor["F"]))
		  {$aux_F=$valor["F"];}
		  else{ $aux_F=0;}
		  
		  if(empty($aux_M)){ $aux_M=0;}
		  if(empty($aux_F)){ $aux_F=0;}
		  
		  $aux_total=($aux_M+$aux_F);
		  echo'<tr>
		  		<td>'.$n.'</td>
		  	    <td>'.$aux_total.'</td>
				<td>'.(($aux_total*100)/$contador).'</td>
				<td>'.$aux_M.'</td>
				<td>'.$aux_F.'</td>
				</tr>';
	  }
  }
  ?>
  </tbody>
</table>
 <br />
 <table width="50%" border="1">
 <thead>
  <tr>
    <th colspan="5">Liceos de Formacion</th>
    </tr>
  </thead>
  <tbody>
  <tr>
  	<td>Formacion</td>
    <td>Cantidad</td>
    <td>%</td>
    <td>Hombres</td>
    <td>Mujeres</td>
  </tr>
  <?php
  if(count($LICEO)>0)
  {
	  foreach($LICEO_FORMACION as $nf=> $valorf)
	  {
		  $aux_Mf=$valorf["M"];
		  $aux_Ff=$valorf["F"];
		  if(empty($aux_Mf)){ $aux_Mf=0;}
		  if(empty($aux_Ff)){ $aux_Ff=0;}
		  
		  $aux_totalf=($aux_Mf+$aux_Ff);
		  echo'<tr>
		  		<td>'.$nf.'</td>
		  	    <td>'.$aux_totalf.'</td>
				<td>'.(($aux_totalf*100)/$contador).'</td>
				<td>'.$aux_Mf.'</td>
				<td>'.$aux_Ff.'</td>
				</tr>';
	  }
  }
  ?>
  </tbody>
</table>
</br>
<table width="50%" border="1">
 <thead>
  <tr>
    <th colspan="5">Liceos</th>
    </tr>
  </thead>
  <tbody>
  <tr>
    <td>Nombre Liceo</td>
    <td>Cantidad</td>
    <td>%</td>
    <td>Hombres</td>
    <td>Mujeres</td>
  </tr>
	<?php
    foreach($ARRAY_LICEO as $aux_liceo=> $array_valoresL)
	{
		if(isset($array_valoresL["F"])){$mujeres_liceo=$array_valoresL["F"];}
		else{$mujeres_liceo=0;}
		
		if(isset($array_valoresL["M"])){$hombres_liceo=$array_valoresL["M"];}
		else{ $hombres_liceo=0;}
		
		$TOTAL_LICEO=($mujeres_liceo+$hombres_liceo);
		$porcentaje_liceo=($TOTAL_LICEO*100)/ $contador;
		
		echo'<tr>
				<td>'.$aux_liceo.'</td>
				<td>'.$TOTAL_LICEO.'</td>
				<td>'.$porcentaje_liceo.'</td>
				<td>'.$hombres_liceo.'</td>
				<td>'.$mujeres_liceo.'</td>
			</tr>';
				
				
	}
	?>
  </tbody>
</table>
</br>
  <table width="50%" border="1">
 <thead>
  <tr>
    <th colspan="5">Nacionalidad</th>
    </tr>
  </thead>
  <tbody>
  <tr>
  	<td>Pais</td>
    <td>Cantidad</td>
    <td>%</td>
    <td>Hombres</td>
    <td>Mujeres</td>
  </tr>
  <?php
  if(count($ARRAY_NACIONALIDAD)>0)
  {
	  foreach($ARRAY_NACIONALIDAD as $n=> $valor)
	  {
		  if(isset($valor["M"]))
		  {$aux_M=$valor["M"];}
		  else{$aux_M=0;}
		  
		  if(isset($valor["F"]))
		  {$aux_F=$valor["F"];}
		  else{ $aux_F=0;}
		  
		  if(empty($aux_M)){ $aux_M=0;}
		  if(empty($aux_F)){ $aux_F=0;}
		  
		  $aux_total=($aux_M+$aux_F);
		  echo'<tr>
		  		<td>'.$n.'</td>
		  	    <td>'.$aux_total.'</td>
				<td>'.(($aux_total*100)/$contador).'</td>
				<td>'.$aux_M.'</td>
				<td>'.$aux_F.'</td>
				</tr>';
	  }
  }
  ?>
  </tbody>
</table>
 <br />
 <p>&nbsp;</p>
  </div>
</div>
</div>

<script type="text/javascript">
var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1", {contentIsOpen:false});
var CollapsiblePanel2 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel2");
</script>
</body>
</html>