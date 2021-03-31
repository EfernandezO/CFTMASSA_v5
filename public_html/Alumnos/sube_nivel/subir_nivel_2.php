<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Subir _de_nivel_A_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$continuar=false;
if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])
	{ $continuar=true;}
}
///***************************************//
if($continuar)
{
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$rut_alumno=$_SESSION["SELECTOR_ALUMNO"]["rut"];
	$alumno=$_SESSION["SELECTOR_ALUMNO"]["nombre"]." ".$_SESSION["SELECTOR_ALUMNO"]["apellido"];;
	$carrera=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
	$nivel_actual=$_SESSION["SELECTOR_ALUMNO"]["nivel"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	
	$nuevo_nivel=$nivel_actual+1;
	
	if(($nuevo_nivel>0)and($nuevo_nivel<=5))
	{ $procesar=true;}
	else
	{ $procesar=false;}
	
	
	
	switch($nivel_actual)
	{
		case"1":
			$nivel_actual_X="1";
			break;
		case"2":
			$nivel_actual_X="2";
			break;
		case"3":
			$nivel_actual_X="3";
			break;
		case"4":
			$nivel_actual_X="4";
			break;
		case"5":
			$nivel_actual_X="5";
			break;				
			
	}
	
	$tabla="";
	if($procesar)
	{
		$ramos_aprobados=0;
		$ramos_reprobados=0;
		
		$ramos_aprobados_actual=0;
		$ramos_reprobados_actual=0;
		$nota_aprobacion=4;
		
		$img_ok='<img src="../../BAses/Images/ok.png" width="29" height="26" alt="ok">';
		$img_error='<img src="../../BAses/Images/b_drop.png" width="16" height="16" alt="X">';
		
		require("../../../funciones/conexion_v2.php");
		$cons_N="SELECT * FROM notas WHERE id_alumno='$id_alumno' AND ramo<>'' AND nivel<='$nivel_actual_X' ORDER by id";
		if(DEBUG){ echo"-->$cons_N<br>";}
		$sql_N=mysql_query($cons_N)or die("NOTAS ".mysql_error());
		$num_notas=mysql_num_rows($sql_N);
		if(DEBUG){ echo"N: $num_notas<br>";}
		if($num_notas>0)
		{
			while($N=mysql_fetch_assoc($sql_N))
			{
				$N_id=$N["id"];
				$N_ramo=$N["ramo"];
				$N_nivel=$N["nivel"];
				$N_nota=$N["nota"];
				if(empty($N_nota)){ $N_nota=0;}
				$N_semestre=$N["semestre"];
				$N_year=$N["ano"];	
			if(DEBUG){ echo"($N_id) ->$N_ramo | $N_nota | $N_nivel | $N_semestre | $N_year |<br>";}
				///////////////////////
				
				//armo condicion
				////////////////////////////////////////
				if($N_nota>0)
				{
					if($N_nota>=$nota_aprobacion)
					{
						if($N_nivel==$nivel_actual_X)
						{
							$ramos_aprobados_actual++;
						}
						$ramos_aprobados++;
						$tabla.='<tr>
								<td>'.$N_ramo.'</td>
								<td>'.$N_nivel.'</td>
								<td>'.$N_nota.'</td>
								<td>'.$img_ok.'</td>
								</tr>';
					}
					else
					{
						$ramos_reprobados++;
						if($N_nivel==$nivel_actual_X)
						{
							$ramos_reprobados_actual++;
						}
						$tabla.='<tr>
								<td>'.$N_ramo.'</td>
								<td>'.$N_nivel.'</td>
								<td>'.$N_nota.'</td>
								<td>'.$img_error.'</td>
								</tr>';
					}
					
				}
				else
				{
					//sin nota
					$tabla.='<tr>
								<td>'.$N_ramo.'</td>
								<td>'.$N_nivel.'</td>
								<td>'.$N_nota.'</td>
								<td>?</td>
								</tr>';
				}
			}
			
			//////////////////////////
			if($ramos_reprobados==0)
			{
				$action="subir_nivel_3.php";
				$mensaje="Todos Los Ramos Aprobados<br>Sube de Nivel Condicion OK<br> $img_ok";
				$condicion="ok";
				$funcion_js='function CONFIRMAR()
							{
								c=confirm("Seguro(a) Quiere Subir de Nivel a Este Alumno..?");
								if(c)
								{ document.getElementById(\'frm\').submit();}
							}';
				$mostrar_boton=true;
			}
			else
			{
				if($ramos_reprobados_actual<=2)
				{
					$action="subir_nivel_3.php";
					$mensaje="Tiene ($ramos_reprobados_actual/$ramos_reprobados) REPROBADOS o sin Nota<br> Si continua, Subira a Nivel $nuevo_nivel, Pero en CONDICION PENDIENTE ";
					$condicion="pendiente";
					$funcion_js='function CONFIRMAR()
								{
									c=confirm("Seguro(a) Quiere Subir de Nivel a Este Alumno\n Si Continua, Subira de Nivel, pero en CONDICION PENDIENTE...");
									if(c)
									{ document.getElementById(\'frm\').submit();}
								}';
					$mostrar_boton=true;
				}
				elseif($ramos_reprobados_actual<=10)
				{
					$action="subir_nivel_3.php";
					$mensaje="Tiene ($ramos_reprobados_actual/$ramos_reprobados) REPROBADOS o sin Nota<br> Si continua, Subira a Nivel $nuevo_nivel, Pero en CONDICION PENDIENTE \n Ademas Debe Solicitar la SOLICITUD DE GRACIAS ACADEMICA, aprobada por el director academico";
					$condicion="pendiente";
					$funcion_js='function CONFIRMAR()
								{
									var codigo_aleatorio='.date("dmYHis").';
									c=confirm("Seguro(a) Quiere Subir de Nivel a Este Alumno\n Si Continua, Subira de Nivel, pero en CONDICION PENDIENTE...\n y tener Aprobada la: SOLICITUD DE GRACIA ACADEMICA");
									if(c)
									{ 
										x=prompt("ingrese en siguiente codigo para confirmar que tiene\n la SOLICITUD DE GRACIAS ACADEMICA aprobada\n  \n CODIGO:"+codigo_aleatorio);
										if(x==codigo_aleatorio)
										{
											//alert("Codigo OK");
											document.getElementById(\'frm\').submit();
										}
										else{alert("Codigo Incorrecto...");}
									}
								}';
					$mostrar_boton=true;
				}
				else
				{
				$action="";
				$mensaje="Tiene ($ramos_reprobados_actual/$ramos_reprobados) REPROBADOS O SIN NOTA<br> No se Puede Subir a Nivel $nuevo_nivel Nivel a Este Alumno...$img_error";
				$condicion="X";
				$funcion_js='function CONFIRMAR()
							{
								alert("Alumno no Cumple Requisitos Para Subir de Nivel...");
							}';
				$mostrar_boton=true;
				//forzamos que se muestr el boton
				}
			}
		}
		else
		{
			if(DEBUG){ echo"Sin Registro Academico Creado...<br>";}
			$tabla.='<tr><td>Sin Registro Academico Creado...</td></tr>';
		}
		mysql_free_result($sql_N);
		mysql_close($conexion);
	}
}
else
{ header("location: ../../buscador_alumno_BETA/HALL/index.php");}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css"/>
<title>Alumno | Subir de Nivel</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:48px;
	z-index:1;
	left: 5%;
	top: 131px;
	text-align: center;
}
</style>
<script language="javascript">
<?php echo $funcion_js;?>
</script>
</head>

<body>
<h1 id="banner">Administrador - Subir Nivel Alumno</h1>
<div id="link"><br>
<a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver a Seleccion </a></div>
<div id="apDiv1">
<form action="<?php echo $action;?>" method="post" id="frm">
  <table width="50%" border="1" align="center">
  <thead>
  	<tr>
  		<th>Asignatura</th>
        <th>Nivel</th>
        <th>Nota</th>
        <th>Condicion
          <input name="nuevo_nivel" type="hidden" id="nuevo_nivel" value="<?php echo $nuevo_nivel;?>" />
          <input name="nivel_condicion" type="hidden" id="nivel_condicion" value="<?php echo $condicion;?>" />
          <input name="ramos_reprobados" type="hidden" id="ramos_reprobados" value="<?php echo $ramos_reprobados_actual;?>" /></th>
    </tr>
  </thead>
  <tbody>
   <?php echo $tabla;?>
   </tbody>
  </table>
  </form>
  <p><br />
  <?php echo" $mensaje [$condicion]";?></p>
  <p><br />
  <?php if($mostrar_boton){?>
    <a href="#" class="button_G" onclick="CONFIRMAR();">Continuar, subir a nivel <?php echo $nuevo_nivel;?></a></p>
  <?php }?>
</div>
</body>
</html>