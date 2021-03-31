<?php
//---------------------------PRIVILEGIOS OKALIS------------------------------//
//   Version 1.1
//	 Autor acx_poison@gmail.com
//	 para CFTMASS
//   28/07/2011
//	33525da1e53d23189c9291d0e74b076b
//---------------------------------------------------------------------------//
function OKALIS($lista_invitados, $bloqueado=false, $DEBUG=false)
{
	////////////////////////////
	$url_error="http://intranet.cftmassachusetts.cl/OKALIS/msj_error/error.php?s=privilegios";
	$tipo_usuario_aceptado=array("funcionario","alumno", "ex_alumno");
	$continuar=true;
	$error="sin_privilegio";
	////////////////////////////
	if(!$bloqueado)
	{
		if($DEBUG){ var_export($lista_invitados);}
		$id_usuario=$_SESSION["USUARIO"]["id"];
		$sede_usuario=$_SESSION["USUARIO"]["sede"];
		$privilegio_usuario=$_SESSION["USUARIO"]["privilegio"];
		$tipo_usuario=$_SESSION["USUARIO"]["tipo"];
		
		if($DEBUG){ echo"<br>USUARIO ACTUAL: ID $id_usuario SEDE $sede_usuario PRIVILEGIO $privilegio_usuario<br>";}
		//solo x usuario
			if(in_array($tipo_usuario, $tipo_usuario_aceptado))
			{
				if($DEBUG){ echo"Tipo usuario valido...<br>";}
				if(isset($lista_invitados["usuario"]))
				{
					if($DEBUG){ echo"Usuario Definidos...<br>";}
					if(in_array($id_usuario,$lista_invitados["usuario"]))
					{
						if($DEBUG){ echo"Encontrado en lista de USUARIO invitados<br>";}
						$permitir_entrada=true;
						$continuar=false;
					}
					else
					{
						if($DEBUG){ echo"No encontrado dentro de lista de Usuario<br><br>";}
					}
				}
				if($continuar)
				{
					//verificando sede y privilegio 
					if(isset($lista_invitados["sede"]))
					{
						if($DEBUG){ echo" hay sede definidas...<br>";}
						if(in_array($sede_usuario,$lista_invitados["sede"]))
						{
							if($DEBUG){ echo"Sede de Usuario Valida...<br>";}
							if(isset($lista_invitados["privilegio"]))
							{
								if($DEBUG){ echo"Privilegios definidos...<br>";}
								if(in_array($privilegio_usuario,$lista_invitados["privilegio"]))
								{
									if($DEBUG){ echo"Privilegios de usuario validos...<br>";}
									//sede y privilegio concuerda
									$permitir_entrada=true;
								}
								else
								{
									//privilegios de usuario no aceptados
									$permitir_entrada=false;
									if($DEBUG){ echo"Privilegios de usuario NO validos...<br>";}
								}
							}
							else
							{
								//si solo hay sede no privilegio
								//no permito paso
								if($DEBUG){ echo"Privilegios NO definidos...<br>";}
								$permitir_entrada=false;
							}
						}
						else
						{
							//sede de usuario no aceptada
							$permitir_entrada=false;
							if($DEBUG){ echo"Sede de Usuario NO Valida...<br>";}
						}
					}
					else
					{
						if($DEBUG){ echo"No hay sede Definidas...<br>";}
						if(isset($lista_invitados["privilegio"]))
							{
								if($DEBUG){ echo"Privilegios definidos...<br>";}
								if(in_array($privilegio_usuario,$lista_invitados["privilegio"]))
								{
									if($DEBUG){ echo"Privilegios de usuario validos...<br>";}
									//sede y privilegio concuerda
									$permitir_entrada=true;
								}
								else
								{
									//privilegios de usuario no aceptados
									$permitir_entrada=false;
									if($DEBUG){ echo"Privilegios de usuario NO validos...<br>";}
								}
							}
							else
							{
								//si solo hay sede no privilegio
								//no permito paso
								if($DEBUG){ echo"Privilegios NO definidos...<br>";}
								$permitir_entrada=false;
							}		
					}
				}
			}
			else
			{
				if($DEBUG){ echo"Tipo de Usuario NO Aceptado...<br>";}
				$permitir_entrada=false;
			}
	}//fin si bloqueado
	else
	{
		if($DEBUG){ echo"BLOQUEADO";}
		$permitir_entrada=false;
		$error="bloqueado";
	}
	
	if($DEBUG){ echo"PERMITIR ENTRADA: $permitir_entrada<br>";}
	
	if($permitir_entrada)
	{
		if($DEBUG){ echo"PERMITIR ACCESO <br>";}
	}
	else
	{ 
		if($DEBUG){ echo"Redirigir a $url_error<br>";}
		else
		{
			header("location: $url_error&error=$error");
			exit();
		}
	}
}
?>