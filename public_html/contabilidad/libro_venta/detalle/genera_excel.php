<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("libro_ventas_X_detalle_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
	if(!DEBUG)
	{
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=boletas_detalle.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
	$valor=$_GET["codigo"];
	$titulo=base64_decode($_GET["titulo"]);
	$consB=base64_decode($valor);
	if(DEBUG)
	{echo"CONSULTA:-> $consB<br>";}
	include("../../../../funciones/conexion_v2.php");
	include("../../../../funciones/funcion.php");

		$sqlB=mysql_query($consB)or die(mysql_error());
		$cuenta_boleta=mysql_num_rows($sqlB);
		$html_tabla='<table border="1" width="100%">
				<thead>
				<tr>
				<th colspan="6">'.$titulo.'</th>
				</tr>
				<tr bgcolor="#33FF99">
				<th scope="col">ID Boleta</th>
				<th scope="col">ID Alumno</th>
			  <th>Rut</th>
			  <th>Nombre</th>
			  <th>Carrera</th>
			  <th>promocion/año</th>
				<th scope="col">Valor</th>
				<th scope="col">Glosa</th>
				<th scope="col">Fecha</th>
				<th scope="col">Folio</th>
				<th scope="col">Estado</th>
				</tr>
				</thead>
				<tbody>';
				$suma_boleta=0;
		if($cuenta_boleta>0)
		{

			while($B=mysql_fetch_assoc($sqlB))
			{
				$id_boleta=$B["id"];
				$id_alumno=$B["id_alumno"];
				$valor=$B["valor"];
				$glosa=$B["glosa"];
				$glosa=str_replace("[br]"," ",$glosa);
				$fecha=$B["fecha"];
				$folio=$B["folio"];
				$estado=$B["estado"];
				$sede=$B["sede"];
				$suma_boleta+=$valor;
				if(empty($glosa))
				{$glosa="*Sin Glosa*";}
				
				//--------------------------------------/
			//alumno
			$cons_A="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";
			$sqli_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
				$A=$sqli_A->fetch_assoc();
				$A_rut=$A["rut"];
				$A_nombre=$A["nombre"];
				$A_apellido_P=$A["apellido_P"];
				$A_apellido_M=$A["apellido_M"];
				$A_carrera=$A["carrera"];
				$A_id_carrera=$A["id_carrera"];
				$A_ingreso=$A["ingreso"];
				$A_nivel=$A["nivel"];
			$sqli_A->free();	
			//-----------------------------------//
				
			$html_tabla.='<tr>
				<td>'.$id_boleta.'</td>
				<td>'.$id_alumno.'</td>
				<td>'.$A_rut.'</td>
				<td>'.$A_nombre.' '.$A_apellido_P.' '.$A_apellido_M.'</td>
				<td><a href="#" title="'.$A_carrera.'">'.$A_id_carrera.'</a></td>
				<td>'.$A_nivel.' -> '.$A_ingreso.'</td>
				<td>$'.number_format($valor,0,",",".").'</td>
				<td>'.$glosa.'</td>
				<td>'.$fecha.'</td>
				<td>'.$folio.'</td>
				<td>'.$estado.'</td>
				 </tr>';
			}
		}
		else
		{
			//no pagos en rango fecha
			$html_tabla.='<tr>
					<td colspan="6">o hay Boletas Registradas en este Periodo</td>
					</tr>';
		}
		$html_tabla.='</tbody>
				<tfoot>
				<tr >
				<td colspan="2">Cantidad de Boletas:</td>
				<td colspan="2" align="right">'.$cuenta_boleta.'</td>
				<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
				<td colspan="2">Total :</td>
				<td colspan="2" align="right">$'.number_format($suma_boleta,0,",",".").'</td>
				<td colspan="2">&nbsp;</td>
				</tr>
				</tfoot>
				</table>';	
				mysql_free_result($sqlB);
				@mysql_close($conexion);
				$conexion_mysqli->close();
				echo $html_tabla;
}
?>