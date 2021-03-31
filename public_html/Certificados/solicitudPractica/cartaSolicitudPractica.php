<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG",false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("certificado->cartaSolicitudPracticaV1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//

if(isset($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"])){
  
  	$continuar=false;
	$msjInfo="";
  	$idAlumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
  	$idCarreraAlumno=$_SESSION["SELECTOR_ALUMNO"]["id_carrera"];
  	$rut_alumno=$_SESSION["SELECTOR_ALUMNO"]["rut"];
	$carrera_alumno=$_SESSION["SELECTOR_ALUMNO"]["carrera"];
	$nivelActualAlumno=$_SESSION["SELECTOR_ALUMNO"]["nivel_academico"];
	$tipo_certificado="carta_solicitud_practica";
  	require("../../../funciones/conexion_v2.php");
	require("../../../funciones/funciones_sistema.php");
	
	$conexion_mysqli->close();
	$infoResumen="";
	list($es_egresado, $semestre_egreso, $year_egreso)=ES_EGRESADO_V2($idAlumno, $idCarreraAlumno, $_SESSION["SELECTOR_ALUMNO"]["yearIngresoCarrera"]);
	list($TOTAL_DEUDA, $INTERESES, $GASTOS_COBRANZA)=DEUDA_ACTUAL_V2($idAlumno);
	$matriculaVigente=VERIFICAR_MATRICULA($idAlumno, $idCarreraAlumno, $_SESSION["SELECTOR_ALUMNO"]["yearIngresoCarrera"]);
	
	
	///MANUAL FORZADO
	$matriculaVigente=true;
   //$es_egresado=true; $semestre_egreso=2; $year_egreso=date("Y");
	///
	
	$condicion1=false;
	$condicion2=false;
	$condicion3=false;
	
	
	if($es_egresado){
		$infoResumen.="Egresado: OK<br>";
		$condicion1=true;
	}else{$infoResumen.="Egresado: NO<br>";}
		
	if($matriculaVigente){
		$infoResumen.="Matricula: OK<br>";
		$condicion2=true;
	}else{$infoResumen.="Matricula: NO<br>";}
		
		
	if($condicion1 and $condicion2){$continuar=true;}
	
	
	$SUMA_TOTAL_DEUDA=($TOTAL_DEUDA + $INTERESES + $GASTOS_COBRANZA);
	
	if($continuar){
		if($SUMA_TOTAL_DEUDA>0){
			$msjInfo="ATENCION: Alumno Moroso";
		}
	}
	
}
else
{
	header("location: ../../buscador_alumno_BETA/HALL/index.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php");?>
<title>solicitud de Practica</title>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
<style type="text/css">
<!--
.Estilo5 {font-size: 12px; font-weight: bold; color: #FF0000; }
.Estilo7 {font-size: 12px}
.Estilo8 {font-size: 12px; font-weight: bold; }
#link {
	text-align: right;
	padding-right: 10px;
}
#apDiv1 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:8;
	left: 594px;
	top: 78px;
}
#link #apDiv1 #div_ajax {
	font-style: italic;
	text-align: center;
}
#apDiv2 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:8;
	left: 575px;
	top: 126px;
}
-->
</style>
<script language="javascript">
function CONFIRMAR()
{
	<?php if($continuar){?>
	var continuar=true;
	presentado=document.getElementById('presentado').value;
	cargo=document.getElementById('cargo').value;
	empresa=document.getElementById('empresa').value;
	
	if((presentado=="")||(presentado==" ")){
		continuar=false;
		alert("Ingrese nombre de la persona a la que se presentara la carta");
	}
	if((cargo=="")||(cargo==" ")){
		continuar=false;
		alert("Ingrese cargo de la persona a la que se presentara la carta");
	}
	if((empresa=="")||(empresa==" ")){
		continuar=false;
		alert("Ingrese empresa de la persona a la que se presentara la carta");
	}
	
	if(continuar){
		c=confirm('Seguro(a) desea continuar...?\n <?php echo $msjInfo;?>');
		if(c){
			document.getElementById('frm').submit();
		}
	}
	<?php }else{?>
	alert("Alumno NO cumple requisitos para emitir carta\n por favor verificar...");
	<?php }?>
}
</script>
</head>
<body>
<h1 id="banner">Administrador - Carta Solicitud de Practica</h1>

<div id="link">
  <div id="apDiv2">

  </div><br />

<a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver al Menu</a></div>
<div id="layer" style="position:absolute; left:5%; top:126px; width:384px; height:160px; z-index:7">
  <form action="cartaSolicitudPractica_pdf.php" method="post"  enctype="multipart/form-data" name="frm"  id="frm">
    <table width="462" border="0">
      <thead>
        <tr>
          <th colspan="3" align="center"><span class="Estilo7">Ingrese los Datos</span></th>
        </tr>
      </thead>
      <tbody>
        <tr class="odd">
          <td width="142"><span class="Estilo7">Firma</span></td>
          <td colspan="2"><select name="firma">
            <option value="JUAN PABLO JAÑA PEREZ-Rector" selected="selected">JUAN PABLO JAÑA PEREZ</option>
            <option value="JUAN ANTONIO VEGA GONZALEZ-Director Academico">JUAN ANTONIO VEGA GONZALEZ</option>
           
          </select>          </td>
        </tr>
        <tr class="odd">
          <td><span class="Estilo7">Presentado a: </span></td>
          <td colspan="2"><input name="presentado" type="text" id="presentado" size="40" /></td>
        </tr>
        <tr class="odd">
          <td>Cargo</td>
          <td colspan="2"><input name="cargo" type="text" id="cargo" size="40" /></td>
        </tr>
        <tr class="odd">
          <td>Empresa</td>
          <td colspan="2"><input name="empresa" type="text" id="empresa" size="40" /></td>
        </tr>
        <tr class="odd">
          <td>ver logo</td>
          <td><input type="radio" name="ver_logo" id="radio" value="si" />
            <label for="ver_logo">si</label></td>
          <td><input name="ver_logo" type="radio" id="radio2" value="no" checked="checked" />
            no</td>
        </tr>
        <tr class="odd">
          <td colspan="3"><input type="button" name="Submit" value="Generar Certificado"  onclick="CONFIRMAR()"/></td>
        </tr>
        <?php
		if(isset($_GET["error"]))
		{
			$error=$_GET["error"];
			switch($error)
			{
				case"1":
					$msj="Alumno No Encontrado";
					break;
				case"2":
					$msj="Sin Contrato Vigente...";
					break;	
			}
		}
		else
		{
			$msj="";
		}
        ?>
        <tr class="odd">
          <td colspan="3"><?php echo $infoResumen;?></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>

</body>
</html>