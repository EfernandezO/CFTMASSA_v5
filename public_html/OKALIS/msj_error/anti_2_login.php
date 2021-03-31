<?php
function anti2Log()
{
	if(isset($_SESSION["USUARIO"]["id_session"]))
		{
			require("../../../funciones/conexion_v2.php");
			$id_usuario_000=$_SESSION["USUARIO"]["id"];
			$id_session_000=$_SESSION["USUARIO"]["id_session"];
			$privilegio_000=$_SESSION["USUARIO"]["privilegio"];
			switch($privilegio_000)
			{
				case"ALUMNO":
					$cons_session="SELECT estado_conexion, session_id FROM alumno WHERE id='$id_usuario_000' LIMIT 1";
					if(DEBUGX){echo"$cons_session<br>";}
					$sql_session=$conexion_mysqli->query($cons_session)or die($conexion_mysqli->error);
					$DA_000=$sql_session->fetch_assoc();
					$BBDD_id_session=$DA_000["session_id"];
					$BBDD_estado_conexion=strtoupper($DA_000["estado_conexion"]);
					if(DEBUGX){ echo"estado conexion BBDD:$BBDD_estado_conexion<br>ID session BBDD: $BBDD_id_session<br>id sesion actual: $id_session_000<br>";}
					$sql_session->free();
					if($BBDD_estado_conexion=="ON")
					{
						if($id_session_000!=$BBDD_id_session)
						{
							if(DEBUGX){echo"ID session INCORRECTOS<br>";}
							else{ header("Location: http://186.10.233.98/~cftmassa/OKALIS/msj_error/error.php?s=iniciada");}
						}
						else
						{ 
							if(DEBUGX){echo"ID SESSION correctos<br>";}
						}
					}
					else
					{
						if(DEBUGX){echo"Estado Conexion: OFF cerrado<br>";}
							else{ header("Location: http://186.10.233.98/~cftmassa/msj_error/error.php?s=iniciada");}
					}
					break;
			
			}
			unset($BBDD_estado_conexion);
			unset($id_usuario_000);
			unset($id_session_000);
			unset($privilegio_000);
			unset($cons_session);
			unset($sql_session);
			unset($DA_000);
			unset($BBDD_id_session);	
			$conexion_mysqli->close();
	}
}
?>