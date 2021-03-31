<?php
//-----------------------------------------//
	require("../../OKALIS/seguridad.php");
	require("../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="externo";
	$lista_invitados["privilegio"][]="inspeccion";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
//-----------------------------------------//	
if($_POST)
{
	
	require("../../../funciones/conexion_v2.php");
	$nick=mysqli_real_escape_string($conexion_mysqli, strip_tags($_POST["nick"]));
	$email=mysqli_real_escape_string($conexion_mysqli, strip_tags($_POST["email"]));
	$fono=mysqli_real_escape_string($conexion_mysqli, strip_tags($_POST["fono"]));
	$direccion=mysqli_real_escape_string($conexion_mysqli, strip_tags($_POST["direccion"]));
	$ciudad=mysqli_real_escape_string($conexion_mysqli, strip_tags($_POST["ciudad"]));
	$clave=mysqli_real_escape_string($conexion_mysqli, strip_tags($_POST["clave"]));
	
	$nueva_clave_1=mysqli_real_escape_string($conexion_mysqli,strip_tags($_POST["nueva_clave_1"]));
	$nueva_clave_2=mysqli_real_escape_string($conexion_mysqli,strip_tags($_POST["nueva_clave_2"]));
	$id_user_activo=$_SESSION["USUARIO"]["id"];
	///compara clave ingresada con la existente en bbdd
	
	if(!empty($clave))
	{
		$clave_actual_codif=md5($clave);
		////////////////////////////////////
		$cons_c="SELECT clave FROM personal WHERE id='$id_user_activo' LIMIT 1";
		$sql_c=$conexion_mysqli->query($cons_c);
		$DC=$sql_c->fetch_assoc();
			$clave_existente_codif=$DC["clave"];
		$sql_c->free();	
		////////////////////////////////////
			if($clave_actual_codif==$clave_existente_codif)
			{
				if((!empty($nueva_clave_1))and(!empty($nueva_clave_2)))
				{
					if($nueva_clave_1==$nueva_clave_2)
					{
						$clave_nueva_codif=md5($nueva_clave_1);
						$condicion_clave=", clave='$clave_nueva_codif'";
					}
					else
					{
						//claves nuevas diferentes
						$msj_clave="Las Claves Nuevas No Coinciden...";
					}
				}
				else
				{
					//claves nueva vacias
					$msj_clave="Claves Nuevas Vacias";
				}
			}
			else
			{
				//clave actual con bbdd no coincide
				 $msj_clave="Claves Actual Ingresada Incorrecta...";
			}
	}
	else
	{ $condicion_clave="";}
	
	
	$cons_Up="UPDATE personal SET nick='$nick', email='$email', fono='$fono', direccion='$direccion', ciudad='$ciudad' $condicion_clave WHERE id='$id_user_activo' LIMIT 1";
	
	if($conexion_mysqli->query($cons_Up))
	{ $error=0;}
	else
	{ $error=1;}
	
	$conexion_mysqli->close();
	$msj_clave=base64_encode($msj_clave);
	header("location: index.php?error=$error&msjcl=$msj_clave");
	
}
else
{
	header("location: index.php");
}
?>
