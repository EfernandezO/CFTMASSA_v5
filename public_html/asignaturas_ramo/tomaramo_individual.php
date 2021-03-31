<?php
//--------------CLASS_okalis------------------//
	require("../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../funciones/";
	$O->clave_del_archivo=md5("Alumno_Toma_de_ramos_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
////////////////////necesario para Xajax///////////////////////////
@require_once ("../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("verifica_matricula_server.php");
$xajax->configure('javascript URI','../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"VERIFICAR");
$xajax->register(XAJAX_FUNCTION,"CARGA_TOMA_RAMO");
////////////////-------------------*********************---------//
?>
<html>
<head>
<title>Toma de Ramos</title>
<?php include("../../funciones/codificacion.php");?>
 <?php 
 
 	////nivels que se rinden x semestre
 	$ARRAY_NIVELES_X_SEMESTRE[1]=array(1,3,5);
	$ARRAY_NIVELES_X_SEMESTRE[2]=array(2,4);
	$NOTA_APROBACION=4;
 
  if($_SESSION["SELECTOR_ALUMNO"]["ACTIVO"]){$action="graba_toma_ramo.php";}
  else{ $action="";}
  
  
   $id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"]; 
   $nombre_alumno=$_SESSION["SELECTOR_ALUMNO"]["nombre"]." ".$_SESSION["SELECTOR_ALUMNO"]["apellido"];
  
   require("../../funciones/conexion_v2.php");
   require("../../funciones/funciones_sistema.php");
   
    $array_periodosCarrera=array();
   #carrera periodo alumno  tiene registro academico
   $cons_PN="SELECT id_carrera, yearIngresoCarrera FROM `contratos2` WHERE id_alumno='$id_alumno' group by id_carrera, yearIngresoCarrera";
   $sqli_PN=$conexion_mysqli->query($cons_PN) or die($conexion_mysqli->error);
   $numRegistrosPeriodos=$sqli_PN->num_rows;
   $i=0;
   while($DPN=$sqli_PN->fetch_assoc()){
	   $array_periodosCarrera[$i]["id_carrera"]=$DPN["id_carrera"];
	   $array_periodosCarrera[$i]["yearIngresoCarrera"]=$DPN["yearIngresoCarrera"];
	   $i++;
   }
   $sqli_PN->free();
   
  
   $conexion_mysqli->close();
   $array_semeste=array(1,2);
   $mes_actual=date("m");
   
   if($mes_actual>=8)///utilizo agosto para inicio 2 semeste
   { $semeste_actual=2;}
   else{ $semeste_actual=1;}
   $year_actual=date("Y");
   
   
  /* $nivel1=$_POST['ocultoni1'];
   $nivel2=$_POST['ocultoni2'];
   $nivel3=$_POST['ocultoni3'];
   $nivel4=$_POST['ocultoni4'];
   $nivel5=$_POST['ocultoni5']; */
?> 
<style type="text/css">
<!--
.Estilo1 {color: #0080C0}
#link {
	text-align: right;
	padding-right: 10px;
}
-->
</style>
<link rel="stylesheet" type="text/css" href="../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../CSS/tabla_2.css">
<link rel="stylesheet" type="text/css" href="../libreria_publica/hint.css-master/hint.css">
<?php $xajax->printJavascript(); ?> 
<script language="javascript">
function CONFIRMAR()
{
	
	c=confirm('Confirma que los Ramos inscritos a este Alumnos, son los correctos..?\n Si es Asi presione ACEPTAR para  \n Continuar con toma de Ramos...');
	if(c)
	{
		document.frm.submit();
	}
}
function CONFIRMAR_DOBLE()
{
	var codigo_aleatorio=<?php echo date("YmdHi")?>;
	d=confirm('Esta a Punto de Crear una Toma de Ramos que Borrara una toma de Ramos previa y creara la actual\n esta Realmente segura(a) que desea Continuar..?');
	//if(d){ CONFIRMAR();}
	if(d){
		CX=prompt("Ingrese el siguiente codigo para poder continuar con la SOBREESCRITURA de la toma de ramos\n CODIGO: "+codigo_aleatorio+"\n ");
		if(CX==codigo_aleatorio)
		{ 
			//alert("codigo OK...");
			CONFIRMAR();
		}
		else
		{alert("codigo incorrecto intentelo mas tarde..."+codigo_aleatorio);}
	}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Toma de Ramo v1.1</h1>
<div id="link"><br>
  <a href="../buscador_alumno_BETA/HALL/index.php" class="button">
Volver al Menu</a></div>
<div id="Layer1" style="position:absolute; left:5%; top:108px; width:90%; height:151px; z-index:1"> 
  <table width="60%" border="0" align="center">
  <thead>
    <tr>
      <th colspan="4">Datos Alumno</th>
    </tr>
    </thead>
    <tbody>
    <tr> 
      <td width="64"><strong>Alumno</strong></td>
      <td colspan="3"><?php echo $nombre_alumno; ?><input type="hidden" name="id_alumno" id="id_alumno" value="<?php  echo $id_alumno;?>"></td>
    </tr>
    <tr> 
      <td width="64"><strong>Carrera</strong></td>
      <td colspan="3">
       <?php
			foreach($array_periodosCarrera as $n => $valor){
				echo'<a href="#" class="button_R" onClick="xajax_CARGA_TOMA_RAMO('.$id_alumno.', '.$valor["id_carrera"].', '.$valor["yearIngresoCarrera"].');">'.NOMBRE_CARRERA($valor["id_carrera"]).' - '.$valor["yearIngresoCarrera"].'</a><br><br>';
			}
        ?>
      </td>
    </tr>
    </tbody>
</table> 
 

 
 <form action="<?php echo $action;?>" method="post" id="frm" name="frm">
 
<table width="60%" border="0" align="center">
   <thead>
    <tr>
      <th colspan="4">Registro Academico</th>
    </tr>
  </thead>
   <tbody>
    <tr>
      <td>Metodo de Creacion</td>
      <td colspan="3"><label for="metodo"></label>
        <select name="metodo" id="metodo">
          <option value="agregar">agregar</option>
          <option value="crear" selected>Crear Nuevo</option>
        </select></td>
      </tr>
    <tr>
      <td>Semestre</td>
      <td><?php echo CAMPO_SELECCION("semestre","semestre",$semeste_actual); ?></td>
      <td>a&ntilde;o</td>
      <td><?php echo CAMPO_SELECCION("year","year",$year_actual); ?></td>
    </tr>
    <tr>
    <td>Tomas Previas</td>
      <td colspan="3">
        <div id="previas">...</div></td>
    </tr>
  </tbody>
  </table>

	<div id="area">...</div>
    
    </form>
</div>
</body>
</html>