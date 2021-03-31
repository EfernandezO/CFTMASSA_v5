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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../../../funciones/codificacion.php");?>
<title>Edicion Cuota</title>
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/jquery_treeview/demo/screen.css">

<script src="../../../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../../../libreria_publica/JSCal/src/js/lang/es.js"></script>
 
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../../../libreria_publica/JSCal/src/css/steel/steel.css">
<link rel="stylesheet" type="text/css" href="../../../../CSS/tabla_2.css"/>

<style>
#link {
	text-align: right;
	padding-right: 10px;
}
#apDiv1 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 70px;
}
-->
</style>
<script language="javascript">
function CONFIRMAR()
{
	c=confirm("zSeguro(a) Desea Editar esta Cuota ?");
	if(c)
	{
		document.frm.submit();
	}
}
</script>
</head>
<?php
	//////////////
	$array_tipo=array("cuota", "matricula");
	$array_condicion=array("N"=>"pendiente", "S"=>"pagada", "A"=>"abonada");
	
if($_GET)
{
	$id_cuota=base64_decode($_GET["id_cuota"]);
	$id_alumno=base64_decode($_GET["id_alumno"]);
	
	$id_contrato=base64_decode($_GET["id_contrato"]);
	$year=base64_decode($_GET["year"]);
	$semestre=base64_decode($_GET["semestre"]);
	
	require("../../../../../funciones/conexion_v2.php");
	$cons_cuo="SELECT * FROM letras  WHERE id='$id_cuota' AND idalumn='$id_alumno'";
	$sql_C=$conexion_mysqli->query($cons_cuo)or die($conexion_mysqli->error);
	$D_C=$sql_C->fetch_assoc();


	///////////////////////////
	$tipo=$D_C["tipo"];
	$semestre=$D_C["semestre"];
	$year=$D_C["ano"];
	
	$pagada=$D_C["pagada"];
	$valor_cuota=$D_C["valor"];
	$deudaXletra=$D_C["deudaXletra"];
	$fechavenc=$D_C["fechavenc"];
	
	$sql_C->free();
	$conexion_mysqli->close();
}
?>
<body>
<h1 id="banner">Edici&oacute;n -Cuota</h1>
<div id="link"><br />
<a href="../../informe_finan1.php" class="button">Volver</a></div>
<div id="apDiv1">
<form action="edita_cuota_rec.php" method="post" name="frm" id="frm">
  <table width="50%" border="0" align="center">
  <thead>
    <tr>
      <th colspan="2">
        Cuota
        <input name="id_contrato" type="hidden" id="id_contrato" value="<?php echo $id_contrato;?>" />
        <input name="year" type="hidden" id="year" value="<?php echo $year;?>" />
        <input name="semestre" type="hidden" id="semestre" value="<?php echo $semestre;?>" />
      </th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="37%"><em>ID Cuota</em></td>
      <td width="63%"><?php echo $id_cuota;?>
        <input name="id_cuota" type="hidden" id="id_cuota" value="<?php echo $id_cuota;?>" /></td>
    </tr>
    <tr>
      <td><em>Tipo</em></td>
      <td><select name="tipo_cuota" id="tipo_cuota">
        <?php
        	foreach($array_tipo as $n => $valor)
			{
				if($valor==$tipo)
				{echo'<option value="'.$valor.'" selected="selected">'.$valor.'</option>';}
				else
				{echo'<option value="'.$valor.'">'.$valor.'</option>';}	
			}
		?>
        </select>      </td>
    </tr>
    <tr>
      <td><em>Semestre</em></td>
      <td><select name="semestre" id="semestre">
        <?php
			for($x=1;$x<=2;$x++)
			{
				if($x==$semestre)
				{echo'<option value="'.$x.'" selected="selected">'.$x.'</option>';}
				else
				{echo'<option value="'.$x.'">'.$x.'</option>';}	
			}
        ?>
      </select></td>
    </tr>
    <tr>
      <td><em>A&ntilde;o</em></td>
      <td><select name="year" id="year">
        <?php
			$year_ini=date("Y")-15;
			$year_fin=date("Y")+1;
        	for($Y=$year_ini;$Y<=$year_fin;$Y++)
			{
				if($Y==$year)
				{echo'<option value="'.$Y.'" selected="selected">'.$Y.'</option>';}
				else
				{echo'<option value="'.$Y.'">'.$Y.'</option>';}	
			}
		?>
         </select>         </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><em>Valor</em></td>
      <td>
      <input name="valor_cuota" type="text" id="textfield" value="<?php echo $valor_cuota;?>" size="15" /></td>
    </tr>
    <tr>
      <td><em>Deuda X Cuota</em></td>
      <td>
      <input name="deuda_cuota" type="text" id="deuda_cuota"  value="<?php echo $deudaXletra;?>" size="15"/></td>
    </tr>
    <tr>
      <td><em>Condicion</em></td>
      <td><select name="pagada" id="pagada">
      <?php
      foreach($array_condicion as $nx=>$valorxx)
	  {
	  	if($pagada==$nx)
		{echo'<option value="'.$nx.'" selected="selected">'.$valorxx.'</option>';}
		else
		{echo'<option value="'.$nx.'">'.$valorxx.'</option>';}	
	  }
	  ?>
      </select>      </td>
    </tr>
    <tr>
      <td><em>Fecha Vencimiento</em></td>
      <td>
      <input name="fecha_vence" type="text" id="fecha_vence"  value="<?php echo $fechavenc;?>" size="15" readonly="readonly"/>
      <input type="button" name="boton" id="boton" value="..." /></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center">
        <input type="button" name="button" id="button" value="Editar"  onclick="CONFIRMAR();"/>
        <input name="validador" type="hidden" id="validador" value="<?php echo md5("EDICION_cuota".date("Y-m-d"));?>" />
      </div></td>
      </tr>
      </tbody>
  </table>
 </form> 
</div>

<script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton", "fecha_vence", "%Y-%m-%d");
    //]]></script>
</body>
</html>
