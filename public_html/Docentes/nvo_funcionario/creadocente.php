<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Crea_funcionario_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
//////////////////////XAJAX/////////////////
@require_once ("../../libreria_publica/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax("busca_funcionario_server.php");
$xajax->configure('javascript URI','../../libreria_publica/xajax/');
$xajax->register(XAJAX_FUNCTION,"BUSCAR_RUT_FUNCIONARIO");
//------------------------------------------------------------//
$array_sexo=array("M"=>"Masculino", "F"=>"Femenino");
?>
<html>
<head>
<title>Nuevo Docente</title>
<?php include("../../../funciones/codificacion.php");?>
<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
}
.Estilo1 {color: #0080C0}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
#link {
	text-align: right;
	padding-right: 10px;
}
-->
</style>
<?php $xajax->printJavascript(); ?> 
<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../CSS/tabla_2.css">
 <script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
 <script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">
<style type="text/css">
<!--
.Estilo2 {
	font-size: 12px;
	font-weight: bold;
}
#div_boton {
	position:absolute;
	width:40%;
	height:36px;
	z-index:44;
	left: 30%;
	top: 731px;
}
-->
</style>
<script language="javascript">
function confirmar()
{
	continuar=true;
	nombre=document.getElementById('nombres').value;
	apellido_P=document.getElementById('apellido_P').value;
	apellido_M=document.getElementById('apellido_M').value;
	rut=document.getElementById('rut').value;

	if(nombre=="")
	{
		continuar=false;
		alert('Ingrese el Nombre');
	}
	if(apellido_P=="")
	{
		continuar=false;
		alert('Ingrese el Apellido Paterno');
	}
	if(apellido_M=="")
	{
		continuar=false;
		alert('Ingrese el Apellido Materno');
	}
	if(rut=="")
	{
		continuar=false;
		alert('Ingrese el Rut');
	}

	if(continuar)
	{
		c=confirm('żSeguro(a) Desea Agregar este Nuevo Usuario?');
		if(c)
		{
			document.frm.submit();
		}
	}	
}
</script>
</head>

<body>
<h1 id="banner">Administrador - Nuevo Funcionario</h1>

<div id="link"><br>
<span class="Estilo1"><a href="../lista_funcionarios.php" class="button">Volver al Menu </a></span></div>
<div id="Layer7" style="position:absolute; left:5%; top:88px; width:90%; height:378px; z-index:43;"> 
  <form action="grabardocente.php" method="post" enctype="multipart/form-data" name="frm" id="frm">
    <table width="60%" align="center">
    <thead>
    <tr>
    	<th colspan="4">Nuevo Funcionario</th>
    </tr>
    </thead>
    <tbody>
      <tr>
        <td>Fecha Ingreso institucion</td>
        <td colspan="3"><input  name="fecha_ingreso_institucion" id="fecha_ingreso_institucion" size="15" maxlength="10" readonly value="<?php echo date("Y-m-d");?>"/>
          <input type="button" name="boton1" id="boton1" value="..." /></td>
      </tr>
      <tr>
        <td>Sexo</td>
        <td colspan="3"><label for="sexo"></label>
          <select name="sexo" id="sexo">
          <?php
          foreach($array_sexo as $n => $valor)
		  {echo'<option value="'.$n.'">'.$valor.'</option>';}
		  ?>
          </select></td>
      </tr>
      <tr> 
        <td width="166"><span class="Estilo2">Nombres:</span></td>
        <td colspan="3"> 
          <input type="text" name="nombres" id="nombres"size="30" maxlength="50">        </td>
      </tr>
      <tr> 
        <td width="166" height="34"><span class="Estilo2">Apellido P</span></td>
        <td colspan="3" height="34"> 
          <input type="text" name="apellido_P" id="apellido_P" size="30" maxlength="50">        </td>
      </tr>
      <tr>
        <td>Apellido M</td>
        <td colspan="3"><input type="text" name="apellido_M" id="apellido_M" size="30" maxlength="50"></td>
      </tr>
      <tr> 
        <td width="166"><span class="Estilo2">Rut</span></td>
        <td colspan="3"> 
          <input type="text" name="rut" id="rut" maxlength="10" size="10" onBlur="xajax_BUSCAR_RUT_FUNCIONARIO(this.value); return false;">
          <div id="div_rut">...</div></td>
      </tr>
      <tr>
        <td>Fecha Nacimiento</td>
        <td colspan="3"><input  name="fecha_nacimiento" id="fecha_nacimiento" size="15" maxlength="10" readonly value="<?php echo date("Y-m-d");?>"/>
          <input type="button" name="boton2" id="boton2" value="..." /></td>
        </tr>
      <tr> 
        <td width="166"><span class="Estilo2">Clave</span></td>
        <td width="60">es el rut por defecto</td>
        <td width="122"><strong>Nivel</strong></td>
        <td width="180"> 
		<?php if($_SESSION["privilegio"]=="admi_total")
		{?>
         	<select name="nivel">
            	<option value="1">1 Docente</option>
                <option value="2">2 Admin</option>
                <option value="3">3 Finanzas</option>
                <option value="4">4 ADMIN TOTAL</option>
                <option value="5">5 Matricula</option>
                <option value="6">6 Inspeccion</option>
                <option value="7">7 Externo</option>
                <option value="8">8 Jefe de Carrera</option>
            </select>
            
		 <?php }
		 else
		 {?>
         <select name="nivel">
            	<option value="1" selected>Docente</option>
            </select>
         <?php
		 } 
		 ?>	 </td>
      </tr>
      <tr>
        <td class="Estilo2">Empresa</td>
        <td colspan="3"><label for="organizacion"></label>
        <input name="organizacion" type="text" id="organizacion" value="cft_massa"></td>
      </tr>
      <tr> 
        <td width="166"><strong class="Estilo2">Cuenta Docente</strong></td>
        <td colspan="3"><select name="cuenta_docente" id="cuenta_docente">
          <option value="ACTIVA">ACTIVA</option>
          <option value="INACTIVA">INACTIVA</option>
        </select></td>
      </tr>
      <tr> 
        <td width="166"><span class="Estilo2">Fono</span></td>
        <td colspan="3"><input type="text" name="fono" size="25" maxlength="20"></td>
      </tr>
      <tr> 
        <td width="166"><span class="Estilo2">Direcci&oacute;n</span></td>
        <td colspan="3"><input type="text" name="direccion" size="25" maxlength="50"></td>
      </tr>
      <tr>
        <td class="Estilo2">Ciudad</td>
        <td colspan="3"><input type="text" name="ciudad" id="ciudad"></td>
      </tr>
      <tr>
        <td><span class="Estilo2">Sede</span></td>
        <td colspan="3"><?php include("../../../funciones/funcion.php"); echo selector_sede("fsede");?></td>
      </tr>
      <tr>
        <td><span class="Estilo2">Email (institucional, si existe)</span></td>
        <td colspan="3"><input type="text" name="correo" size="50" maxlength="255"></td>
      </tr>
      <tr> 
        <td width="166">Email (personal, si existe)</td>
        <td colspan="3"><label for="email_personal"></label>
        <input name="email_personal" type="text" id="email_personal" size="50"></td>
      </tr>
      </tbody>
    </table>
  </form>
</div>
<div id="div_boton"></div>
<script type="text/javascript">
//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("boton1", "fecha_ingreso_institucion", "%Y-%m-%d");
	   cal.manageFields("boton2", "fecha_nacimiento", "%Y-%m-%d");

    //]]>
</script>
</body>
</html>