<?php
define("DEBUG", true);
function SITUACION_PENDIENTE($id_alumno, $nivel, $carrera)
{
	//cambio el nivel al formato romano 
	switch($nivel)
	{
		case"1":
			$nivel_label="'I'";
			break;
		case"2":
			$nivel_label="'I', 'II'";
			break;
		case"3":
			$nivel_label="'I', 'II', 'III'";
			break;
		case"4":
			$nivel_label="'I', 'II', 'III', 'IV'";
			break;			
		case"5":
			$nivel_label="'I', 'II', 'III', 'IV', 'V'";
			break;	
	}
	$cons_malla="SELECT * FROM mallas WHERE carrera='$carrera' AND nivel IN($nivel_label) ORDER by cod";
	if(DEBUG){ echo"malla--> $cons_malla<br>";}
	$sql_malla=mysql_query($cons_malla)or die(mysql_error());
	$numero_de_asignturas=mysql_num_rows($sql_malla);
	if($numero_de_asignturas>0)
	{
		while($M=mysql_fetch_assoc($sql_malla))	
		{
			$M_id=$M["id"];
			$M_cod=$M["cod"];
			$M_ramo=$M["ramo"];
			$M_pr1=$M["pr1"];
			$M_pr2=$M["pr2"];
			$M_pr3=$M["pr3"];
			$M_pr4=$M["pr4"];
			$M_nivel_label=$M["nivel"];
			
			if(DEBUG){ echo"$M_id - $M_nivel_label - $M_ramo - $M_pr1 - $M_pr2 - $M_pr3 - $M_pr4 <br>";}
		}
	}
	else
	{
		echo"Sin asignaturas Encontradas en malla ";
	}
	mysql_free_result($sql_malla);
}
?>
<?php
include("../conexion.php");
	$id_alumno=195;
	$nivel="2";
	$carrera="Programación Computacional";
	echo "PRUEBA..<br>";
	echo"$id_alumno -->";
	SITUACION_PENDIENTE($id_alumno, $nivel, $carrera);
mysql_close($conexion);
?>