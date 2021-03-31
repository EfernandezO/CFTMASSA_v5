<?php
define("DEBUG",false);
if($_GET)
{
	if(!DEBUG)
	{
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=archivo.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
	$valor=$_GET["codigo"];
	$titulo=base64_decode($_GET["titulo"]);
	$consB=base64_decode($valor);
	if(DEBUG)
	{echo"CONSULTA:-> $consB<br>";}
	include("../../../../funciones/conexion.php");
	include("../../../../funciones/funcion.php");

		$sqlB=mysql_query($consB)or die(mysql_error());
		$cuenta_boleta=mysql_num_rows($sqlB);
		$html_tabla='<table border="1" width="100%">
				<thead>
				<tr>
				<th colspan="6">'.$titulo.'</th>
				</tr>
				<tr>
				<th scope="col">ID Boleta</th>
				<th scope="col">ID Alumno</th>
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
				{
					$glosa="*Sin Glosa*";
				}
				
			$html_tabla.='<tr>
				<td>'.$id_boleta.'</td>
				<td>'.$id_alumno.'</td>
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
				mysql_close($conexion);
				echo $html_tabla;
}
?>