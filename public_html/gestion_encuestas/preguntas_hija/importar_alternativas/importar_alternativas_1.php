<?php
//--------------CLASS_okalis------------------//
	require("../../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../../funciones/";
	$O->clave_del_archivo=md5("Gestion de Encuestas_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if($_GET)
{
	if((isset($_GET["id_encuesta"]))and(isset($_GET["id_pregunta"])))
	{
		$id_encuesta=$_GET["id_encuesta"];
		$id_pregunta=$_GET["id_pregunta"];
		
		if((is_numeric($id_encuesta))and(is_numeric($id_pregunta)))
		{ $continuar=true;}
		else
		{ $continuar=false;}
	}
}
else
{ $continuar=false;}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../funciones/codificacion.php");?>
<title>importar alternativas</title>
<link rel="stylesheet" type="text/css" href="../../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../../CSS/tabla_2.css"/>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 92px;
}
#apDiv2 {
	position:absolute;
	width:40%;
	height:53px;
	z-index:2;
	left: 30%;
	top: 291px;
}
</style>
<script language="javascript">
function VERIFICAR()
{
	continuar=false;
	id_pregunta=document.getElementById('id_pregunta_original').value;
	
	if(id_pregunta>0)
	{
		continuar=true;
	}
	else
	{
		continuar=false;
		alert("No hay Pregunta seleccionada...");
	}
	
	if(continuar)
	{
		document.getElementById('frm').submit();
	}
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Importar Alternativas, Encuesta</h1>
<div id="apDiv1">
<?php if($continuar){?>
<form action="importar_alternativas_2.php" method="post" id="frm">
  <table width="100%" border="1">
  <thead>
    <tr>
      <th colspan="2">Importar alternativas
        <input name="id_encuesta" type="hidden" id="id_encuesta" value="<?php echo $id_encuesta;?>" />
        <input name="id_pregunta" type="hidden" id="id_pregunta" value="<?php echo $id_pregunta;?>" /></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="33%">Seleccione la pregunta de la cual importará las alternativas</td>
      <td width="67%"><label for="id_pregunta_original"></label>
        <select name="id_pregunta_original" id="id_pregunta_original">
        <?php
        	require("../../../../funciones/conexion_v2.php");
	 $cons="SELECT * FROM encuestas_pregunta WHERE id_encuesta='$id_encuesta' AND tipo='alternativa' AND id_pregunta<>'$id_pregunta' ORDER by posicion, id_pregunta";
	  
	   $sql=$conexion_mysqli->query($cons)or die($conexion_mysqli->error);
		$num_registros=$sql->num_rows;
		 if(DEBUG){ echo"-->$cons<br>N. $num_registros<br>";}
	   if($num_registros>0)
	   {
		   $contador=0;
			while($M=$sql->fetch_assoc())
			{
				$contador++;
				
				$posicion=$M["posicion"];
				$id_pregunta=$M["id_pregunta"];
				$pregunta=$M["pregunta"];
				$tipo=$M["tipo"];
				
				echo'<option value="'.$id_pregunta.'">'.$pregunta.'</option>';
			}
	   }
	   else
	   {
		   echo'<option value="0">sin preguntas</option>';
	   }
		$sql->free();
		$conexion_mysqli->close();	
		?>
        </select></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    </tbody>
  </table>
  </form>
</div>
<div id="apDiv2"><a href="#" class="button_G" onclick="VERIFICAR();">Continuar</a></div>
<?php }else{ echo"No se puede continuar<br>";}?>
</body>
</html>