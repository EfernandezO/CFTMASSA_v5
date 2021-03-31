<?php include ("../../../SC/seguridad.php");?>
<?php include ("../../../SC/privilegio2.php");?>
<?php
if($_POST)
{
	$error=0;
	include("../../../../funciones/conexion.php");
	
	$sede=$_SESSION["CUOTA"]["sede_f"];
	$semestre=$_SESSION["CUOTA"]["semestre_f"];
	$ano=$_SESSION["CUOTA"]["ano_f"];
	$id_alumno=$_SESSION["CUOTA"]["id_alumno"];
	
	$numero_letra=$_POST["num_letra"];
	$valor_letra=$_POST["valor_letra"];
	$fecha_vence_letra=$_POST["fecha_vence_letra"];
	$tipo_letra=$_POST["tipo_letra"];
	
	$fecha_actual=date("Y-m-d");
	
	/*foreach($_POST as $n => $valor)
	{
		echo"$n -> $valor <br>";
	}*/
	if((COMPRUEBA_LETRA($numero_letra, $sede))and(is_numeric($valor_letra))and(!empty($fecha_vence_letra)))
	{
		//echo"Datos Correctos<br>";
		$campos="numletra, idalumn, fechavenc, valor, deudaXletra, ano, semestre, fechemision, sede, tipo";
		$valores="'$numero_letra', '$id_alumno', '$fecha_vence_letra', '$valor_letra', '$valor_letra', '$ano', '$semestre', '$fecha_actual', '$sede', '$tipo_letra'";
		$cons_IN="INSERT INTO letras ($campos) VALUES($valores)";
		//echo"$cons_IN<br>";
		if(!mysql_query($cons_IN))
		{
			$error=2;
			echo mysql_error();
		}
		else
		{
			if($_SESSION["FINANZAS"]["GRABADO"])
			{
				//si aun sigo con la session o sea puede acceder al menu despues de matricular actualizo la session para que se refleje en los doc imprimibles
				$_SESSION["FINANZAS"]["num_letras"][]=$numero_letra;
				//para transformar de yyyy-mm-dd => dd-mm-yyyy
				$array_fecha_format=explode("-",$fecha_vence_letra);
				$fecha_vence_letra=$array_fecha_format[2]."-".$array_fecha_format[1]."-".$array_fecha_format[0];
				//------------*************---------------
				$_SESSION["FINANZAS"]["fechaV_letras"][]=$fecha_vence_letra;
				$_SESSION["FINANZAS"]["valor_letras"][]=$valor_letra;
				$_SESSION["FINANZAS"]["arancel"]+=$valor_letra;
			}
		}
		
	}
	else
	{
		//numero de letra incorrecto
		//echo"Errrosaurio<br>";
		$error=1;
	}
	mysql_close($conexion);
	//donde redirijo
	if($error>0)
	{
		@header("location: index.php?error=$error");	
		
	}
	else
	{
		@header("location: ../../pagacuo/cuota1.php?error=$error");	
	}
	
}
else
{
	header("location: ../../pagacuo/cuota1.php");
}	
/////////////COMPRUEBA LETRA///////////////
function COMPRUEBA_LETRA($num_letra, $sede)
{
	//echo"funcion<br>";
	if(is_numeric($num_letra))
	{
		$cons="SELECT COUNT(numletra) FROM letras WHERE numletra='$num_letra' AND sede='$sede'";
		//echo"$cons<br>";
		$sql=mysql_query($cons)or die(mysql_error());
		$L=mysql_fetch_row($sql);
		$coincidencia=$L[0];
		//echo"--> $coincidencia<br>";
		if($coincidencia>0)
		{
			//echo"letra ya registrada<br>";
			$error=false;
		}
		else
		{
			//echo"letra no registrada<br>";
			$error=true;
		}
		
		mysql_free_result($sql);
	}
	else
	{
		$error=false; //hay error
	}	
	return $error;
}
///////////////////////////////////
////////////////////////////////////////////
?>	