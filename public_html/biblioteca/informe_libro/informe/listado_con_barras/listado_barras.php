<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("Bibioteca_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
	require("../../../../../funciones/conexion_v2.php");
	$sede=$_GET["sede"];
	$id_carrera=$_GET["id_carrera"];
	$id_usuario_actual=$_SESSION["USUARIO"]["id"];
	require('code128.php');
		$registrosXhoja=17;
		$pdf=new PDF_Code128();
		$pdf->AddPage();
		$pdf->SetFont('Arial','',10);
		
		$pdf->Cell(195,6,"Listado Barras -$sede ($id_carrera)",0,1,"C");
		
	if($id_carrera!="0")
	{ $condicion_carrera="AND id_carrera='$id_carrera'";}
	else
	{ $condicion_carrera="";}
	
	$cons="SELECT * FROM biblioteca WHERE sede='$sede' $condicion_carrera ORDER by sede, carrera, id_libro";
	if(DEBUG){ echo "$cons<br>";}
	$sql=$conexion_mysqli->query($cons);
	$num_reg=$sql->num_rows;
	if($num_reg>0)
	{
		$x1=10;
		$y1=20;
		$y2=27;
		$aux=0;
		while($L=$sql->fetch_assoc())
		{
			$pdf->SetFont('Arial','',10);
			$aux++;
			$id_libro=$L["id_libro"];
			$titulo_libro=$L["nombre"];
			//A,C,B sets
			$codigo='MassaX'.$id_libro."X".date("dmY")."X".$id_usuario_actual;
			//$codigo=base64_encode($codigo);
			$pdf->Code128($x1,$y1,$codigo,50,7);
			$pdf->SetXY($x1+50,$y1);
			$pdf->Write(5,$aux."[".$id_libro."] ".$titulo_libro);
			$pdf->SetXY($x1,$y2);
			$pdf->SetFont('Arial','',8);
			$pdf->Write(5,'*'.$codigo.'*');
			
			$y1+=15;
			$y2+=15;
			if($aux%$registrosXhoja==0)
			{ 
				$pdf->AddPage();
				$x1=10;
				$y1=20;
				$y2=27;
			}
		}
	}
	$sql->free();
	$conexion_mysqli->close();
	$pdf->Output();
}
else
{ header("location: ../../../menubiblio.php");}
?>