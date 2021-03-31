<?php
//--------------CLASS_okalis------------------//
	require("../../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Impresion_Y_Gestion_de_contratos_v1_editar");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
///////////////---------------///////////////
	$acceso=false;
	$comparador=md5("AGREGA_cuota".date("Y-m-d"));
	$validador=$_POST["validador"];

	if($comparador==$validador)
	{$acceso=true;}
///////////////---------------///////////////	
if(($_POST)and($acceso))	
{
	if(DEBUG){ var_dump($_POST);}
	require("../../../../../funciones/conexion_v2.php");
	$id_alumno=$_POST["id_alumno"];
	
	$id_contrato=$_POST["id_contrato"];
	$year=mysqli_real_escape_string($conexion_mysqli, $_POST["year"]);
	$semestre=mysqli_real_escape_string($conexion_mysqli, $_POST["semestre"]);
	$arancel=mysqli_real_escape_string($conexion_mysqli, $_POST["arancel"]);
	$ARRAY_CONSULTAS=array();
	
	$ARRAY_CONSULTAS[0]="DELETE FROM beneficiosEstudiantiles_asignaciones WHERE id_alumno='$id_alumno' AND id_contrato='$id_contrato'";
	
	if(DEBUG){ echo"<br><br>BORRAR los beneficios Antiguos: $cons_B<br>";}
	
	 $totalBeneficios=0;
	 $i=1;
	if(count($_SESSION["FINANZASX"]["beneficiosEstudiantiles"])>0){
	 foreach($_SESSION["FINANZASX"]["beneficiosEstudiantiles"] as $auxIdBeneficio =>$arrayValores){
		 $auxNombre=$arrayValores["nombre"];
		 $auxAporteValor=$arrayValores["aporteValor"];
		 $auxAportePorcentaje=$arrayValores["aportePorcentaje"];
		 $auxTipo=$arrayValores["tipo"];
		 $auxForma=$arrayValores["forma"];
		 
		  if($auxTipo=="porcentaje"){$totalizadoBeneficio=($arancel*$auxAportePorcentaje)/100;}
		  else{$totalizadoBeneficio=$auxAporteValor;}
		
		 $totalBeneficios+=$totalizadoBeneficio;
		 
		 $ARRAY_CONSULTAS[$i]="INSERT INTO beneficiosEstudiantiles_asignaciones (id_beneficio, id_alumno, id_contrato, valor) VALUES ('$auxIdBeneficio', '$id_alumno', '$id_contrato', '$totalizadoBeneficio')";
		 $i++;
	 }//fin foreach
	}//fin si
	$ARRAY_CONSULTAS[$i]="UPDATE contratos2 SET totalBeneficiosEstudiantiles='$totalBeneficios' WHERE id='$id_contrato' LIMIT 1";
	
	
	foreach($ARRAY_CONSULTAS as $i => $consulta){
		if(DEBUG){ echo"---->$consulta<br>";}
		else{ 
			if($conexion_mysqli->query($consulta)){}
			else{ echo"ERROR".$conexion_mysqli->error;}
		}
	}
	
	
	$conexion_mysqli->close();
	
	$url="../../informe_finan1.php?id_contrato=$id_contrato&year=$year&semestre=$semestre";
	if(DEBUG){ echo"URL: $url<br>";}
	else{header("location: $url");}
}
else
{
	if(DEBUG){ echo"sin acceso<br>";}
	else{header("location: ../../index.php");}
}
?>
