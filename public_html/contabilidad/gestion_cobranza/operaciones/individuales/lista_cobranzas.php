<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("gestion_cobranza_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

$sede_usuario=$_SESSION["USUARIO"]["sede"];
$year_actual=date("Y");
$fecha_actual=date("Y-m-d");
$continuar=false;
require("../../../../../funciones/conexion_v2.php");

if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]))
{
	$continuar=true;
	$id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
	$id_carrera=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
	$sede=$_SESSION["SELECTOR_ALUMNO"]["sede"];
	
	$fecha_actual=date("Y-m-d");
	
	////busco max año cuotas
	$cons="SELECT MAX(ano) FROM letras WHERE idalumn='$id_alumno' AND pagada<>'S' AND deudaXletra>0";
	$sqli=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
	$DC=$sqli->fetch_row();
		$max_year_cuota=$DC[0];
	$sqli->free();	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php");?>
<title>Cobranza Individual</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css">
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 194px;
}
#apDiv2 {
	position:absolute;
	width:45%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 69px;
}
</style>
<!--INICIO LIGHTBOX EVOLUTION-->
   <script type="text/javascript" src="../../../../libreria_publica/lightbox_1.6.9_evolution/js/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.css" />
  <!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="../../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
  <script type="text/javascript" src="../../../../libreria_publica/lightbox_1.6.9_evolution/js/lightbox/jquery.lightbox.min.js"></script>
 <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox(); 
    });
  </script>
 <!--FIN LIGHTBOX EVOLUTION--> 
<style type="text/css">
#apDiv3 {
	position:absolute;
	width:40%;
	height:24px;
	z-index:3;
	left: 55%;
	top: 100px;
}
</style>
</head>
<body>
<h1 id="banner">Finanzas - Cobranza Individual</h1>
  <?php if($continuar){?>
			
		<div id="apDiv2">
		  <table width="100%" border="1">
		  <thead>
			<tr>
			  <th colspan="2">Datos Alumno</th>
			</tr>
			</thead>
			<tbody>
			<tr>
			  <td width="21%">Alumno</td>
			  <td width="79%"><?php echo $_SESSION["SELECTOR_ALUMNO"]["nombre"]." ".$_SESSION["SELECTOR_ALUMNO"]["apellido"];?></td>
			</tr>
			<tr>
			  <td>Carrera</td>
			  <td><?php echo $_SESSION["SELECTOR_ALUMNO"]["carrera"];?></td>
			</tr>
			</tbody>
		  </table>
		</div>
        <?php } ?>

<div id="apDiv1">
  <table width="100%" align="center" class="display" id="example">
	<thead>
        <tr>
        	<th><strong>N</strong></th>
            <th><strong>Fecha</strong></th>
            <th><strong>Fecha Corte</strong></th>
             <th><strong>Fecha Compromiso</strong></th>
            <th><strong>Deuda Actual</strong></th>
             <th><strong>Hay Respuesta</strong></th>
             <th><strong>Observacion</strong></th>
          <th><strong>Opc</strong></th>
        </tr>
  </thead>
	<tbody>
      
        <?php if($continuar){
			require("../../../../../funciones/funciones_sistema.php");
			$cons_C1="SELECT * FROM cobranza WHERE id_alumno='$id_alumno' AND id_carrera='$id_carrera' AND sede='$sede' ORDER by fecha desc";
			$sql_c1=$conexion_mysqli->query($cons_C1) or die("cobranza ".$conexion_mysqli->error);
			$num_cobranzas=$sql_c1->num_rows;
			if(DEBUG){ echo"-->$cons_C1<br>Num registros:$num_cobranzas<br>";}
			$aux=0;
			if($num_cobranzas>0)
			{
				while($C=$sql_c1->fetch_assoc())
				{
					$aux++;
					$C_id=$C["id_cobranza"];
					$C_tipo=$C["tipo"];
					$C_fecha=$C["fecha"];
					$C_fecha_corte=$C["fecha_corte"];
					$C_fecha_compromiso=$C["fecha_compromiso"];
					$C_hay_respuesta=$C["hay_respuesta"];
					$C_observacion=$C["observacion"];
					$C_deuda_actual=$C["deuda_actual"];
					$C_cod_user=$C["cod_user"];
					$C_deuda_actual=$C["deuda_actual"];
					
					
					if($C_hay_respuesta==1){$C_hay_respuesta_label="Si";}
					else{ $C_hay_respuesta_label="No";}
					
					echo'<tr>
							<td align="center">'.$aux.'</td>
							<td align="center">'.$C_fecha.'</td>
							<td align="center">'.$C_fecha_corte.'</td>
							<td align="center">'.$C_fecha_compromiso.'</td>
							<td align="center">'.$C_deuda_actual.'</td>
							<td align="center">'.$C_hay_respuesta_label.'</td>
							<td align="center">'.$C_observacion.'</td>
							<td align="center"><a href="../editar/edita_cobranza.php?id_cobranza='.base64_encode($C_id).'&lightbox[iframe]=true&lightbox[width]=400&lightbox[height]=500" class="lightbox">Editar</a></td>
						 </tr>';
					
				}
				
			}
			else
			{
				echo'<tr>
						<td colspan="7">Sin Registros...</td>
					</tr>';
			}
		}
		else{ echo"Sin Alumno Seleccionado";}
		$conexion_mysqli->close();
		?>
     </tbody>
</table>
</div>
<div id="apDiv3"><a href="../nueva/nueva_cobranza.php?id_alumno=<?php echo base64_encode($id_alumno);?>&id_carrera=<?php echo base64_encode($id_carrera);?>&year_cuota=<?php base64_encode($max_year_cuota);?>&fecha_corte=<?php echo base64_encode($fecha_actual);?>&lightbox[iframe]=true&lightbox[width]=400&lightbox[height]=500" class="button_R lightbox" >Agregar Nuevo Registro de Cobranza</a></div>
</body>
</html>