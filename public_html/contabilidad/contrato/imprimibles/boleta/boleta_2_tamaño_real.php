<?php
	//-----------------------------------------//
	require("../../../../OKALIS/seguridad.php");
	require("../../../../OKALIS/okalis.php");
	$lista_invitados["privilegio"][]="admi_total";
	$lista_invitados["privilegio"][]="admi";
	$lista_invitados["privilegio"][]="matricula";
	$lista_invitados["privilegio"][]="finan";
	OKALIS($lista_invitados);
	
	define("DEBUG",false);
	//-----------------------------------------//

	include("../../../../../funciones/conexion.php");
	include("../../../../../funciones/funcion.php");
	include("../../../../../librerias/fpdf/fpdf.php");
	if(DEBUG){ var_export($_POST);}
	
	$No_verifica_folio=false;
	$sede_impresion=$_POST["sede_impresion"];
	$impresora=$_POST["impresora"];
	$caja=$_POST["caja"];
	//------------------------------------------------//
	
	if(isset($_POST["id_boleta"]))
	{
			//sino ver como obtengo id de boleta
			$id_boleta=$_POST["id_boleta"];
	}
	elseif(isset($_SESSION["FINANZAS"]["BOLETA"]["global"]))
	{
		if(($_SESSION["FINANZAS"]["BOLETA"]["global"])and(!isset($_POST["id_boleta"])))
		{$id_boleta=$_SESSION["FINANZAS"]["BOLETA"]["matricula"];}	
	}
	///////////////////////////////////////////
	if((is_numeric($id_boleta))and($id_boleta>0))
	{
		$folio_boleta=$_POST["folio"];
		//actualiza Folio a boleta
		$folio_OK=ACTUALIZA_FOLIO_CAJA($id_boleta, $folio_boleta, $caja);
		if(($folio_OK)or($No_verifica_folio))
		{
			$cons_S="SELECT * FROM boleta WHERE id='$id_boleta'";
			//echo"-> $cons_S<br>";
			$sql_B=mysql_query($cons_S)or die("Buscado datos Boleta".mysql_error());
			$B=mysql_fetch_assoc($sql_B);
			
			$id_boleta=$B["id"];
			$tipo_receptor=$B["tipo_receptor"];
			$id_alumno=$B["id_alumno"];
			$id_empresa=$B["id_empresa"];
			$valor=$B["valor"];
			$glosa=$B["glosa"];
			$fecha=$B["fecha"];
			$sede=$B["sede"];
			
			$borde=1;
			$letra_1=10;
			$autor="ACX";
			$titulo="BOLETA";
			$zoom=50;
			//////////Formatea Variables////////////
			//prefijo
				$prefijo=substr($sede,0,1);
			//fecha
				$D=explode("-",$fecha);
				$year=$D[0];
				$mes=$D[1];
				$dia=$D[2];
				
				$mes_aux=abs($mes);
				$mes_label=mes_palabra($mes_aux);
				
				
			switch($tipo_receptor)	
			{
				case"alumno":
					//dato alumno
					$cons_alumno="SELECT * FROM alumno WHERE id='$id_alumno' LIMIT 1";
					if(DEBUG){ echo"$cons_alumno<br>";}
					$sql_alumn=mysql_query($cons_alumno)or die("Alumno".mysql_error());
					$D_alumno=mysql_fetch_assoc($sql_alumn);
					////le saque utf8_decode
					$nombre=$D_alumno["nombre"];
					$apellido_old=$D_alumno["apellido"];
					$apellido_new=$D_alumno["apellido_P"]." ".$D_alumno["apellido_M"];
			
					$carrera=$D_alumno["carrera"];
					$nivel=$D_alumno["nivel"];
					
					if($apellido_new==" ")
					{$alumno_label="$nombre $apellido_old";}
					else
					{$alumno_label="$nombre $apellido_new";}
						mysql_free_result($sql_alumn);
					$alumno_label=ucwords(strtolower($alumno_label));
					//contrato
					//var_export($_SESSION["FINANZAS"]);
					if(isset($_SESSION["FINANZAS"]["SAVE"]))
					{
						$semestre=$_SESSION["FINANZAS"]["semestre"];
						$year_estudio=$_SESSION["FINANZAS"]["year_estudio"];
					}
					else
					{
						$semestre=$_POST["semestre"];
						$year_estudio=$_POST["year_estudio"];
					}	
					$cons_C="SELECT id FROM contratos2 WHERE id_alumno='$id_alumno' AND sede='$sede' AND semestre='$semestre' AND ano='$year_estudio'";
					if(DEBUG){echo"<br>---> $cons_C<br>";}
					$sql_C=mysql_query($cons_C)or die("DATOS -> Contrato".mysql_error());
					$datos_C=mysql_fetch_assoc($sql_C);
					$id_contrato=$datos_C["id"];
					mysql_free_result($sql_C);
					///////////////////////////TXT en BOLETA/////////////////////////////////////
					$texto_en_boleta="Fecha: $dia/$mes/$year\n";
					$texto_en_boleta.="Sr(a).: $alumno_label ($id_alumno)\n";
					$texto_en_boleta.="Carrera: $carrera. Nivel: $nivel \n";
					$texto_en_boleta.="Sede: $sede.  $prefijo $id_contrato\n";
					//////////////////////////////////////////////////////////////////////////////
					break;
				case"empresa":
					$cons_empresa="SELECT * FROM empresa WHERE id='$id_empresa' LIMIT 1";
					if(DEBUG){ echo"$cons_empresa<br>";}
					$sql_Emp=mysql_query($cons_empresa)or die(mysql_error());
					$DE=mysql_fetch_assoc($sql_Emp);
						$E_nombre_fantasia=$DE["nombre_fantasia"];
						$E_razon_social=$DE["razon_social"];
						$E_rut=$DE["rut"];
					mysql_free_result($sql_Emp);	
					$texto_en_boleta="Fecha: $dia/$mes/$year\n";
					$texto_en_boleta.="Srs.: $E_nombre_fantasia (E".$id_empresa.")\n";
					break;
			}
			
			
			
			
			///////////------------------///////////
			$hoja_boleta[0]=115;
			$hoja_boleta[1]=153;
			$pdf=new FPDF('P','mm',$hoja_boleta);
			$pdf->AddPage();
			$pdf->SetAuthor($autor);
			$pdf->SetTitle($titulo);
			$pdf->SetDisplayMode($zoom);
			//margenes
			$pdf->SetMargins(0,0);
			$pdf->SetAutoPageBreak(false);
			//---------POSICIONANDA para Impresora------------//
			switch($sede_impresion)
			{
				case"Talca":
					$aux_x=2;
					$aux_y=-20;
					break;
				case"Linares":	
					$aux_x=2;
					$aux_y=-10;
					break;
				default:	
					$aux_x=2;
					$aux_y=-20;
			}		
			//---------------------//
			$X=20;
			$Y=45;
			$borde=0;
			$pdf->SetFont('Arial','',12);
			//fecha
			
			
			
			$array_glosa=explode("[br]",$glosa);
			$texto_en_boleta.="---------------------------------------------------\n";
			foreach($array_glosa as $nx => $txt)
			{
				if(!empty($txt))
				{
					//$pdf->MultiCell(54,6,$txt,$borde,1);
					//$pdf->SetX(13+$aux_x);
					$texto_en_boleta.="$txt \n";
				}
			}
		////////////////////////////////////////
			//$pdf->SetXY(67+$aux_x,137+$aux_y);
			//$pdf->Cell(25,6,number_format($valor,0,",","."),$borde,1);
			$texto_en_boleta.="\n---------------------------------------------------\n";
			$texto_en_boleta.="\nVALOR: $".number_format($valor,0,",",".");
			$pdf->SetXY($X+$aux_x,$Y+$aux_y);
			
			////+++++++++++++++++++++++++++++++++++/////
			//opcion con impresora
			switch($impresora)
			{
				case"okidata_320T":
					break;
				case"okidata_321T":
					$texto_en_boleta="\n  \n".$texto_en_boleta;
					break;
			}
			if(DEBUG){ echo"$texto_en_boleta<br>";}
			////++++++++++++++++++++++++++++++++++++////
			
			$pdf->Multicell(75,5,$texto_en_boleta,$borde);
			//////////////////
			//$pdf->Line(0,1,101,1);//arriba
			//$pdf->Line(0,5,101,5);//arriba
			//$pdf->Line(0,10,101,10);//arriba
			//$pdf->Line(0,15,101,15);//arriba
			
		
			//$pdf->Line(105,0,105,153);//derecha
			//$pdf->Line(0,143,105,143);//inferior
			//$pdf->Line(10,10,10,152);//izquierda
			//////////////
			
			if(!DEBUG){$pdf->Output();}
		}
		else
		{
			echo"Folio de Boleta Ya existe, o incorrecto Intentelo de nuevo...<br>";
		}
		mysql_close($conexion);
	}
	else
	{
		echo"NO hay Boleta Para Generar...[$id_boleta]<br>";
	}	
///////////ACTUALIZA FOLIO/////////////////
function ACTUALIZA_FOLIO_CAJA($id_boleta, $folio, $caja)
{
	$cons_BB="SELECT COUNT(id) FROM boleta WHERE NOT(id='$id_boleta') AND folio='$folio'";
	$sql_B=mysql_query($cons_BB)or die("Busca folio -> ".mysql_error());
	$BX=mysql_fetch_row($sql_B);
	$coincidencias=$BX[0];
	if($coincidencias>0)
	{
		//folio repetido
		$continuar=false;
	}
	else
	{
		$continuar=true;
		$cons_UP="UPDATE boleta SET folio='$folio', caja='$caja' WHERE id='$id_boleta' LIMIT 1";
		mysql_query($cons_UP)or die("ACTUALIZA FOLIO -> ".mysql_error());
		if(DEBUG){echo"<br><b>ACTUALIZA_FOLIO_CAJA</b>:<br>--> $cons_UP<br>";}
	}	
	return($continuar);
}
?>