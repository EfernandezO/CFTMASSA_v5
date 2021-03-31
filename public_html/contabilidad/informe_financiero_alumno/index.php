<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Impresion_Y_Gestion_de_contratos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(isset($_SESSION["CONTRATO_OLD"]))
{unset($_SESSION["CONTRATO_OLD"]);}

if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
	{ $continuar=true;}
	else
	{ $continuar=false;}
}
else
{ $continuar=false;}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>Informe Financiero Alumno</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
<!--
#Layer2 {position:absolute;
	width:473px;
	height:213px;
	z-index:2;
	left: 36px;
	top: 101px;
}
#Layer1 {
	position:absolute;
	width:86px;
	height:15px;
	z-index:8;
	left: 676px;
	top: 164px;
}
a:link {
	text-decoration: none;
	color: #6699FF;
}
a:visited {
	text-decoration: none;
	color: #6699FF;
}
a:hover {
	text-decoration: none;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #6699FF;
}
#Layer3 {
	position:absolute;
	width:398px;
	height:28px;
	z-index:9;
	left: 96px;
	top: 72px;
}
.Estilo1 {
	font-size: 12px;
	font-weight: bold;
}
.Estilo2 {font-size: 12px}
#link {
	text-align: right;
	padding-right: 10px;
}
#apDiv1 {
	position:absolute;
	width:90%;
	height:24px;
	z-index:8;
	left: 5%;
	top: 133px;
}
-->
</style>
</head>
<body>
<h1 id="banner">Administrador - Finanzas </h1>
<div id="link">
<br />
    <a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver al Menu</a><br /><br />

<a href="../gestion_contratos/listador_contratos.php" class="button">Listador Contratos </a></div>
<div id="apDiv1">
<div align="center">
  <table width="80%" border="1">
  <caption>
  Contratos deL Alumno
  </caption>
  <thead>
    <tr>
      <th>N</th>
      <th>Cod. Contrato</th>
      <th>Ingreso Carrera</th>
      <th>id carrera</th>
      <th>Vigencia</th>
      <th>Semestre</th>
      <th>A&ntilde;o</th>
      <th>Condicion</th>
      <th colspan="5">Opcion</th>
    </tr>
    </thead>
    <tbody>
      <?php
	 if($continuar)
	 {
	 $target_impresion='target="_blank"';
	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];

	
		$cons="SELECT * FROM contratos2 WHERE id_alumno='$id_alumno' ORDER by id DESC";
		if(DEBUG){ echo"-> $cons<br>";}
		$sql=$conexion_mysqli->query($cons);
		$num_reg=$sql->num_rows;
		if($num_reg>0)
		{
			$aux=0;
			$primera_vuelta=true;
			while($C=$sql->fetch_assoc())
			{
				$aux++;
				
				$id_contrato=$C["id"];
				$idCarreraContrato=$C["id_carrera"];
				$semestre=$C["semestre"];
				$vigencia=$C["vigencia"];
				$year=$C["ano"];
				$yearIngresoCarrera=$C["yearIngresoCarrera"];
				$condicion=strtoupper($C["condicion"]);
				$reasignado=$C["reasignado"];
				
				
				//////////////////////////////////////////////////////////
				
				echo'<tr>
					<td><div align="center">'.$aux.'</div></td>
					<td><div align="center">'.$id_contrato.'</div></td>
					<td><div align="center">'.$yearIngresoCarrera.'</div></td>
					<td><div align="center"><a title="'.NOMBRE_CARRERA($idCarreraContrato).'">'.$idCarreraContrato.'</a></div></td> 
					<td><div align="center">'.$vigencia.'</div></td>
					<td><div align="center">'.$semestre.'</div></td>
					<td><div align="center">'.$year.'</div></td>
					<td><div align="center">'.$condicion.'</div></td>
					<td><div align="center"><a href="informe_finan1.php?id_contrato='.$id_contrato.'&semestre='.$semestre.'&year='.$year.'" title="Editar Contrato">Editar Contrato</a></div></td>';
					
					if(($condicion=="OK")or(1==1))//modificado
					{
					echo'<td><div align="center"><a href="../contratos_old/contrato_old.php?id_contrato='.$id_contrato.'&semestre='.$semestre.'&year='.$year.'&tipo_contrato=academico" title="Imprimir Contrato PRESTACION DE SERVICIOS" '.$target_impresion.'>Contrato Prestacion de Servicios</a></div></td>
                    <td><a href="../contratos_old/contrato_old.php?id_contrato='.$id_contrato.'&semestre='.$semestre.'&year='.$year.'&tipo_contrato=credito" title="Imprimir Mandato Pagare" '.$target_impresion.'>Mandato pagare</a></td>
					 <td><a href="../contrato/folio_pagare/folio_pagare_1.php?id_contrato='.$id_contrato.'" title="Imprimir Pagare" '.$target_impresion.'>Pagaré</a></td>';
	  				}
					else
					{
						echo'<td>---</td>
							<td>---</td>
							<td>---</td>';
					}

					if(($reasignado=="no")and($primera_vuelta)and(false))
					{echo'<td><a href="../../becas/recalculo_contrato_cuotas/recalculo_contrato_1.php?id_contrato='.$id_contrato.'" title="'.$num_asignaciones.' Becas Asignadas">recalcular</a></td>';}
					else
					{echo'<td>---</td>';}
					echo'</tr>';
					
					$primera_vuelta=false;
			}
		}
		else
		{
			echo'<tr><td colspan="8">Alumno no tiene Contratos Creados...</td></tr>';
		}
	$sql->free();	
	$conexion_mysqli->close();
	 }
?>
    </tbody>
  </table>
  <br />
*
<?php
if($_GET)
{
		$error=$_GET["error"];
		if(isset($_GET["ID"])){$aux_id_contrato=$_GET["ID"];}
		$img_ok='<img src="../../BAses/Images/ok.png" width="29" height="26" />';
		$img_error='<img src="../../BAses/Images/b_drop.png" width="16" height="16" />';
		$msj="";
		$img="";
		switch($error)
		{
				case"0":
					$msj="Contrato COD.:($aux_id_contrato) Modificado Correctamente...";
					$img=$img_ok;
					break;
		}
		echo" $msj $img<br>";
}
?>
*</div>
</div>
</body>
</html>