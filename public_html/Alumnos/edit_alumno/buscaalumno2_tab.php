<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Modificacion_datos_de_alumno_v1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
if(isset($_GET["ver"]))
{
	$ver=$_GET["ver"];
	if(is_numeric($ver))
	{$ver_tab=$ver;}
	else
	{$ver_tab=0;}
}
else
{ $ver_tab=0;}

if(DEBUG){ echo"VER TAB: $ver_tab<br>";}
?>
<html>
<head>
<title>Modifica antecedentes</title>
<?php include("../../../funciones/codificacion.php");?>
<script src="../../libreria_publica/JSCal/src/js/jscal2.js"></script>
<script src="../../libreria_publica/JSCal/src/js/lang/es.js"></script>

<link rel="stylesheet" type="text/css" href="../../libreria_publica/jquery_treeview/demo/screen.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/jscal2.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/border-radius.css">
<link rel="stylesheet" type="text/css" href="../../libreria_publica/JSCal/src/css/steel/steel.css">

<script src="../../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../SpryAssets/SpryTabbedPanels.css">

<style type="text/css">
<!--
.Estilo2 {color: #0080C0; }
.Estilo4 {font-size: 12px}
.Estilo7 {font-style: italic; font-size: 12px;}
-->
</style>

<style type="text/css">
<!--
#apDiv3 {
	position:absolute;
	width:90%;
	height:115px;
	z-index:1;
	left: 5%;
	top: 67px;
}
#apDiv4 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:2;
	left: 185px;
	top: 270px;
}
#apDiv1 {
	position:absolute;
	width:70%;
	height:115px;
	z-index:2;
	left: 5%;
	top: 427px;
}
#apDiv2 {
	position:absolute;
	width:20%;
	height:33px;
	z-index:3;
	left: 75%;
	top: 404px;
}
-->
</style>
<script language="javascript">
function VERIFICA_NVO_REG()
{
	continuar=true;
	observacion=document.getElementById('observacion').value;
	if((observacion=="")||(observacion==" "))
	{
		continuar=false;
	}
	if(continuar)
	{
		c=confirm('¿Seguro(a) Desea Agregar este Nuevo Registro A la Hoja de Vida Del Alumno?');
		if(c)
		{
			document.frm_O.submit();
		}
	}
	else
	{
		alert('Primero Escriba Una Observacion');
	}	
}
</script>
</head>
    <?php 
	$array_formacion=array("cientifico humanista"=>"Cientifico Humanista","tecnico profecional"=>"Tecnico Profesional", "escuela artistica"=>"Escuelas Artisticas");
	$array_dependencia=array("municipal","particular","corporacion", "particular subvencionado");
	$array_situacion=array("V"=>"Vigente",
						   "P"=>"Postergado",
						   "R"=>"Retirado",
						   "EL"=>"Eliminado");
						   
	$array_sexo=array("M"=>"Masculino","F"=>"Femenino");	
	$array_jornada=array("D"=>"Diurno", "V"=>"Vespertino");
	$array_nivel_situacion=array("ok","pendiente");
 require("../../../funciones/conexion_v2.php");
 require("../../../funciones/funciones_sistema.php");
  ///////////////////////////////////////////
   $id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
//////////////////////////////////////
$AR_fecha_generacion="";
$cons_AR="SELECT * FROM alumno_registros WHERE id_alumno='$id_alumno' AND descripcion='Alumno Cargado Sistema Superintendencia de Salud'";
$sqli_AR=$conexion_mysqli->query($cons_AR)or die($conexion_mysqli->error);
$num_reg_AR=$sqli_AR->num_rows;
if($num_reg_AR>0)
{
	$AR=$sqli_AR->fetch_assoc();
	$AR_fecha_generacion=$AR["fecha_generacion"];
	
}
if(empty($AR_fecha_generacion)){ $msj_supersalud="No cargada";}
else{$msj_supersalud=" Cargada [". $AR_fecha_generacion."]";}

$sqli_AR->free();
///
   $res="SELECT * FROM alumno where id='$id_alumno' LIMIT 1";
  
   $result=$conexion_mysqli->query($res)or die("datos Alumnos".$conexion_mysqli->error);
   $row = $result->fetch_assoc();
  
    $id=$row["id"];
    $rut=$row["rut"];
    $nombre=$row["nombre"];
  
	
	$apellido_P=$row["apellido_P"];
	$apellido_M=$row["apellido_M"];
	
	$id_carrera=$row["id_carrera"];
    $carrera=$row["carrera"];
	$grupo=$row["grupo"];
    $direccion=$row["direccion"];
    $ciudad=$row["ciudad"];
	$pais_origen=$row["pais_origen"];
    $fono=$row["fono"];
    $jornada=$row["jornada"];
    $apoderado=$row["apoderado"];
    $fonoa=$row["fonoa"];
    $clave=$row["clave"];
    $email=$row["email"];
	$emailInstitucional=$row["emailInstitucional"];
	
    $fnac=$row["fnac"];
    $situacion=$row["situacion"];
    $sede=$row["sede"];
    $ingreso=$row["ingreso"];
	$year_egreso=$row["year_egreso"];
	$nivel=$row["nivel"];
	
	$rut_apoderado=$row["rut_apoderado"];
	$direccion_apoderado=$row["direccion_apoderado"];
	$ciudad_apoderado=$row["ciudad_apoderado"];
	$nacionalidad=$row["nacionalidad"];
	
	$fechaRegistro=$row["fecha_registro"];
	
	$idLiceo=$row["idLiceo"];
	$liceo=$row["liceo"];
	$liceo_pais=$row["liceo_pais"];
	$liceo_ciudad=$row["liceo_ciudad"];
	$liceo_dependencia=$row["liceo_dependencia"];
	$liceo_egreso=$row["liceo_egreso"];
	$liceo_formacion=$row["liceo_formacion"];
	$liceo_nem=$row["liceo_nem"];
	$otro_estudio_U=$row["otro_estudio_U"];
	$otro_estudio_T=$row["otro_estudio_T"];
	$otro_estudio_P=$row["otro_estudio_P"];
	$sexo=$row["sexo"];
	$nivel_situacion_actual=$row["nivel_condicion"];
	if(empty($nivel_situacion_actual)){$nivel_situacion_actual="ok";}
   
  $result->free();
  
  $html_liceo='<select name="idLiceo" id="idLiceo">';
   $res="SELECT idLiceo, nombreEstablecimiento, region, comuna FROM liceos  ORDER by region, comuna";
   $sqli=$conexion_mysqli->query($res)or die($conexion_mysqli->error);
   $comunaOLD="";
   $regionOLD="";
   $primeraVuelta=true;
   while($L=$sqli->fetch_assoc()) 
   {
		$auxIdLiceo=$L["idLiceo"];
		$auxNombreEstablecimiento=$L["nombreEstablecimiento"];
		$auxComunaLiceo=$L["comuna"];
		$auxRegionLiceo=$L["region"];
		
		if($comunaOLD!=$auxComunaLiceo){
			if($primeraVuelta){$primeraVuelta=false; $html_liceo.='
			<optgroup label="'.$auxRegionLiceo.'-'.$auxComunaLiceo.'">';}
			else{$html_liceo.='</optgroup>
			<optgroup label="'.$auxRegionLiceo.'-'.$auxComunaLiceo.'">';}
		}
			
		$select='';
		if($idLiceo==$auxIdLiceo){$select='selected="selected"';}
		$html_liceo.='<option value="'.$auxIdLiceo.'" '.$select.'>'.$auxNombreEstablecimiento.'</option>';
	
		$comunaOLD=$auxComunaLiceo;	
   }
	$html_liceo.='<optgroup></select>';
	$sqli->free();
   ?>
<body>
<div id="apDiv2">
<?php
if($_GET)
{
	if(isset($_GET["error"]))
	{
		$error=$_GET["error"];
		switch($error)
		{
			case"1":
				$msj="<strong>ADVERTENCIA</strong>: Rut Ya registrado anteriormente en el sistema...¡¡¡<br>";
				break;
		}
		echo"$msj";
	}
}
?>
</div>
<h1 id="banner">Administrador - Edita Alumno V1.5</h1>

<div id="link"><br>
<a href="../../buscador_alumno_BETA/HALL/index.php" class="button">Volver a Seleccion </a></div>


<div id="apDiv3">
<form action="grabaralumnoact.php" method="post" name="frm" id="frm">
  <div id="TabbedPanels1" class="TabbedPanels">
    <ul class="TabbedPanelsTabGroup">
      <li class="TabbedPanelsTab Estilo4" tabindex="0">&#9658;Datos del Alumno</li>
      <li class="TabbedPanelsTab Estilo4" tabindex="1">&#9658;Datos del Apoderado</li>
      <li class="TabbedPanelsTab Estilo4" tabindex="2">&#9658;Documentos Obligatorios</li>
      <li class="TabbedPanelsTab Estilo4" tabindex="3">&#9658;Datos Liceo</li>
      <li class="TabbedPanelsTab Estilo4" tabindex="4">&#9658;Datos Academicos</li>
       <li class="TabbedPanelsTab Estilo4" tabindex="5">&#9658;Registro</li>
    </ul>
    <div class="TabbedPanelsContentGroup">
      <div class="TabbedPanelsContent">
          Contenido 1
          <table width="553" border="0">
          <tr class="odd">
            <td width="84" bgcolor="#F7F4EE" class="Estilo7">Rut</td>
        <td width="187" bgcolor="#F7F4EE"><input name="rut" type="text" id="rut" value="<?php echo $rut; ?>" size="10" maxlength="10" />
                <span class="Estilo4">
                <input name="id" type="hidden" id="id" value="<?php echo $id; ?>" />
                <?php echo "ID (  $id  )"; ?></span></td>
            <td width="74" bgcolor="#F7F4EE"><span class="Estilo7">Direcci&oacute;n</span></td>
            <td width="190" bgcolor="#F7F4EE"><input name="direccion" type="text" id="direccion" value="<?php echo $direccion; ?>" size="30" maxlength="50" />            </td>
          </tr>
          <tr class="odd">
            <td bgcolor="#F7F4EE"><span class="Estilo7">Nombres</span></td>
            <td bgcolor="#F7F4EE"><input name="nombre" type="text" id="nombre" value="<?php echo $nombre; ?>" size="30" maxlength="40" />            </td>
            <td bgcolor="#F7F4EE"><span class="Estilo7">Fecha Nac.</span></td>
            <td bgcolor="#F7F4EE"><span class="Estilo4">
              <input name="fnac" type="text" id="fnac" size="11" maxlength="10" value="<?php echo"$fnac";?>" onChange="cargarMovimientos();" readonly/>
              <input type="button" name="boton" id="boton" value="..."/>
            </span></td>
          </tr>
          <tr class="odd">
            <td bgcolor="#F7F4EE" class="Estilo7">Apellido P</td>
            <td bgcolor="#F7F4EE"><input name="apellido_P" type="text" id="apellido_P" value="<?php echo $apellido_P;?>" />            </td>
            <td bgcolor="#F7F4EE"><span class="Estilo7">Ciudad</span></td>
            <td bgcolor="#F7F4EE"><input name="ciudad" type="text" id="ciudad" value="<?php echo $ciudad; ?>" size="20" maxlength="20" />            </td>
          </tr>
          <tr class="odd">
            <td bgcolor="#F7F4EE"><span class="Estilo7">Apellido M</span></td>
            <td bgcolor="#F7F4EE"><input name="apellido_M" type="text" id="apellido_M" value="<?php echo $apellido_M;?>" />            </td>
            <td bgcolor="#F7F4EE"><span class="Estilo7">Email</span></td>
            <td bgcolor="#F7F4EE"><input name="email" type="text" id="email" value="<?php echo $email; ?>" size="30" maxlength="50" />            </td>
          </tr>
          <tr class="odd">
            <td bgcolor="#F7F4EE"><span class="Estilo7">Fono</span></td>
            <td bgcolor="#F7F4EE"><input name="fono" type="text" id="fono" value="<?php echo $fono; ?>" size="20" maxlength="20" />            </td>
            <td bgcolor="#F7F4EE" class="Estilo7">Email Institucional</td>
            <td bgcolor="#F7F4EE"><input name="emailInstitucional" type="text" id="emailInstitucional" value="<?php echo $emailInstitucional; ?>" size="30" maxlength="50" /></td>
          </tr>
          <tr class="odd">
            <td bgcolor="#F7F4EE" class="Estilo7">Pais Origen</td>
            <td bgcolor="#F7F4EE"><?php echo CAMPO_SELECCION("pais_origen","paises",$pais_origen,false);?></td>
            <td bgcolor="#F7F4EE" class="Estilo7">Sexo</td>
            <td bgcolor="#F7F4EE"><select name="sexo" id="sexo">
              <?php
				foreach($array_sexo as $n => $valor)
				{
					if($n==$sexo)
					{echo'<option value="'.$n.'" selected="selected">'.$valor.'</option>';}
					else
					{echo'<option value="'.$n.'">'.$valor.'</option>';}	
				}
            ?>
            </select></td>
          </tr>
        </table>
      </div>
      <div class="TabbedPanelsContent"> Contenido 2
        <table width="100%" border="0">
          <tr class="odd">
            <td width="11%" bgcolor="#F7F4EE"><span class="Estilo7">Rut</span></td>
            <td width="42%" bgcolor="#F7F4EE"><input name="rut_apoderado" type="text" id="rut_apoderado" value="<?php echo $rut_apoderado;?>" /></td>
            <td width="12%" bgcolor="#F7F4EE"><span class="Estilo7">Direccion</span></td>
            <td width="35%" bgcolor="#F7F4EE"><input name="direccion_apoderado" type="text" id="direccion_apoderado" value="<?php echo $direccion_apoderado;?>" /></td>
          </tr>
          <tr class="odd">
            <td bgcolor="#F7F4EE"><span class="Estilo7">Nombre </span></td>
            <td bgcolor="#F7F4EE"><input type="text" name="apoderado" value="<?php echo $apoderado; ?>" size="30" maxlength="50" /></td>
            <td bgcolor="#F7F4EE"><span class="Estilo7">Ciudad</span></td>
            <td bgcolor="#F7F4EE"><input name="ciudad_apoderado" type="text" id="ciudad_apoderado" value="<?php echo $ciudad_apoderado;?>" /></td>
          </tr>
          <tr class="odd">
            <td bgcolor="#F7F4EE"><span class="Estilo7">Fono</span></td>
            <td bgcolor="#F7F4EE"><input type="text" name="fonoa" value="<?php echo $fonoa; ?>" size="20" maxlength="20" /></td>
            <td bgcolor="#F7F4EE">&nbsp;</td>
            <td bgcolor="#F7F4EE">&nbsp;</td>
          </tr>
        </table>
      </div>
      <div class="TabbedPanelsContent">
       <?php
      ///////////////////////////
	  //alumno antecedentes
	  $A_licencia_media=0;
	  $A_certificado_nacimiento=0;
	  $A_foto_carnet=0;
	  $A_pase_escolar=0;
	  $A_certificado_residencia=0;
	  $cons_A="SELECT * FROM alumno_antecedentes WHERE id_alumno='$id_alumno' LIMIT 1";
	  $sql_A=$conexion_mysqli->query($cons_A)or die($conexion_mysqli->error);
	  if($sql_A->num_rows>0){
		  $DA=$sql_A->fetch_assoc();
		  
			$A_licencia_media=$DA["licencia_media"];
			$A_certificado_nacimiento=$DA["certificado_nacimiento"];
			$A_foto_carnet=$DA["foto_carnet"];
			$A_pase_escolar=$DA["pase_escolar"];
			$A_certificado_residencia=$DA["certificado_residencia"];
	  }
		
		if($A_licencia_media==1){ $A_licencia_media_condicion='checked';}
		else{ $A_licencia_media_condicion="";}
		if($A_certificado_nacimiento==1){ $A_certificado_nacimiento_condicion='checked';}
		else{ $A_certificado_nacimiento_condicion="";}
		if($A_foto_carnet==1){ $A_foto_carnet_condicion='checked';}
		else{ $A_foto_carnet_condicion="";}
		if($A_pase_escolar==1){ $A_pase_escolar_condicion='checked';}
		else{ $A_pase_escolar_condicion="";}
		if($A_certificado_residencia==1){ $A_certificado_residencia_condicion='checked';}
		else{ $A_certificado_residencia_condicion="";}
		
	 $sql_A->free();

	  ?>
      
        <table width="50%" border="0">
          <tr>
            <td colspan="2">&nbsp;</td>
            </tr>
          <tr>
            <td width="90%" bgcolor="#f5f5f5"><span class="Estilo7">Licencia Ense&ntilde;anza Media</span></td>
            <td width="10%" bgcolor="#f5f5f5"><input name="A_licencia_media" type="checkbox" id="A_licencia_media" value="1" <?php echo $A_licencia_media_condicion;?>>
              <label for="checkbox"></label></td>
            </tr>
          <tr>
            <td bgcolor="#f5f5f5"><span class="Estilo7">Certificado de Nacimiento</span></td>
            <td bgcolor="#f5f5f5"><input name="A_certificado_nacimiento" type="checkbox" id="A_certificado_nacimiento" value="1" <?php echo $A_certificado_nacimiento_condicion;?>></td>
            </tr>
          <tr>
            <td bgcolor="#f5f5f5"><span class="Estilo7">Certificado Residencia</span></td>
            <td bgcolor="#f5f5f5"><input name="A_certificado_residencia" type="checkbox" id="A_certificado_residencia" value="1" <?php echo $A_certificado_residencia_condicion;?>></td>
          </tr>
          <tr>
            <td bgcolor="#f5f5f5"><span class="Estilo7">Fotos Carnet</span></td>
            <td bgcolor="#f5f5f5"><input name="A_foto_carnet" type="checkbox" id="A_foto_carnet" value="1" <?php echo $A_foto_carnet_condicion;?>></td>
            </tr>
          <tr>
            <td bgcolor="#f5f5f5"><span class="Estilo7"><strong>Solicita Pase escolar</strong></span></td>
            <td bgcolor="#f5f5f5"><input name="A_pase_escolar" type="checkbox" id="A_pase_escolar" value="1" <?php echo $A_pase_escolar_condicion;?>></td>
          </tr>
        </table>
      </div>
      <div class="TabbedPanelsContent">
        
        <table width="100%" border="0">
            <tr class="odd">
              <td bgcolor="#F7F4EE">&nbsp;</td>
              <td bgcolor="#F7F4EE">&nbsp;</td>
              <td bgcolor="#F7F4EE"><span class="Estilo7">Pais Educacion Secundaria</span></td>
              <td bgcolor="#F7F4EE"><?php echo CAMPO_SELECCION("pais_liceo","paises",$liceo_pais,false);?></td>
            </tr>
            <tr class="odd">
              <td width="16%" bgcolor="#F7F4EE"><span class="Estilo7">Liceo</span></td>
              <td width="36%" bgcolor="#F7F4EE">
              <input name="liceo" type="text" class="Estilo4" value="<?php echo $liceo; ?>" size="30" maxlength="50" />
              <?php echo $html_liceo;?>
                            </td>
              <td width="14%" bgcolor="#F7F4EE">&nbsp;</td>
              <td width="34%" bgcolor="#F7F4EE">&nbsp;</td>
            </tr>
            <tr class="odd">
              <td bgcolor="#F7F4EE">&nbsp;</td>
              <td bgcolor="#F7F4EE">&nbsp;</td>
              <td bgcolor="#F7F4EE"><span class="Estilo7">Egreso</span></td>
              <td bgcolor="#F7F4EE"><input name="liceo_egreso" type="text" class="Estilo4" id="liceo_egreso" value="<?php echo $liceo_egreso;?>" />              </td>
            </tr>
            <tr class="odd">
              <td bgcolor="#F7F4EE"><span class="Estilo7">Formaci&oacute;n</span></td>
              <td bgcolor="#F7F4EE"><span class="Estilo4 Estilo4">
                <select name="formacion_liceo" id="formacion_liceo">
                  <?php
	  	foreach($array_formacion as $n => $valor)
		{
			if($liceo_formacion==$n)
			{
				echo'<option value="'.$n.'" selected="selected">'.$valor.'</option>';	
			}
			else
			{
				echo'<option value="'.$n.'">'.$valor.'</option>';
			}	
		}
	  ?>
                </select>
              </span></td>
              <td bgcolor="#F7F4EE">NEM</td>
              <td bgcolor="#F7F4EE"><input name="liceo_nem" type="text" class="Estilo4" id="liceo_nem" value="<?php echo $liceo_nem;?>" /></td>
            </tr>
            <tr >
              <td height="27" rowspan="2"><span class="Estilo4 Estilo4">Otros Estudios </span></td>
              <td height="12" ><span class="Estilo4 Estilo4">Universitaria</span></td>
              <td ><span class="Estilo4 Estilo4">Tecnica</span></td>
              <td height="12" ><span class="Estilo4 Estilo4">Profesional</span></td>
            </tr>
            <tr >
              <td height="13"><span class="Estilo4 Estilo4">
                <?php
	   
	   		if($otro_estudio_U=="si")
			{
				echo'<input type="radio" name="otro_estudio_U" id="radio" value="si" checked="checked"/>Si
				<input name="otro_estudio_U" type="radio" id="radio2" value="no" />No';
			}
			else
			{
				echo'<input type="radio" name="otro_estudio_U" id="radio" value="si"/>Si
				<input name="otro_estudio_U" type="radio" id="radio2" value="no" checked="checked" />No';
			}
	   ?>
              </span> </td>
              <td height="13" ><span class="Estilo4 Estilo4">
                <?php
	  
	   		if($otro_estudio_T=="si")
			{
				echo'<input type="radio" name="otro_estudio_T" id="radio" value="si" checked="checked"/>Si
				<input name="otro_estudio_T" type="radio" id="radio2" value="no" />No';
			}
			else
			{
				echo'<input type="radio" name="otro_estudio_T" id="radio" value="si"/>Si
				<input name="otro_estudio_T" type="radio" id="radio2" value="no" checked="checked" />No';
			}
	   ?>
              </span></td>
              <td height="13" ><span class="Estilo4 Estilo4">
                <?php
	   
	   		if($otro_estudio_P=="si")
			{
				echo'<input type="radio" name="otro_estudio_P" id="radio" value="si" checked="checked"/>Si
				<input name="otro_estudio_P" type="radio" id="radio2" value="no" />No';
			}
			else
			{
				echo'<input type="radio" name="otro_estudio_P" id="radio" value="si"/>Si
				<input name="otro_estudio_P" type="radio" id="radio2" value="no" checked="checked" />No';
			}
	   ?>
              </span></td>
            </tr>
        </table>
  </div>
      <div class="TabbedPanelsContent">
      Contenido 4
      <table width="100%" border="0">
            <tr class="odd">
              <td width="20%" bgcolor="#F7F4EE"><span class="Estilo7">Sede</span></td>
              <td width="29%" bgcolor="#F7F4EE"><select name="sede" id="sede">
                <option selected="selected"><?php echo $sede; ?></option>
                <option>Talca</option>
                <option>Linares</option>
              </select>              </td>
              <td width="27%" bgcolor="#F7F4EE"><span class="Estilo7">A&ntilde;o Ingreso</span></td>
              <td width="24%" colspan="2" bgcolor="#F7F4EE"><input name="ingreso" type="text" id="ingreso" value="<?php echo $ingreso; ?>" size="10" maxlength="4" />              </td>
            </tr>
            <tr class="odd">
              <td bgcolor="#F7F4EE"><span class="Estilo7">Nivel</span></td>
              <td bgcolor="#F7F4EE"><?php echo $nivel;?>  <input name="nivel" type="hidden" value="<?php echo $nivel;?>">            </td>
              <td bgcolor="#F7F4EE"><em class="Estilo7">A&ntilde;o Egreso</em></td>
              <td colspan="2" bgcolor="#F7F4EE"><input name="year_egreso" type="text" id="year_egreso" value="<?php echo $year_egreso; ?>" size="10" maxlength="4" readonly /></td>
            </tr>
            <tr class="odd">
              <td bgcolor="#F7F4EE"><span class="Estilo7">Nivel Situacion</span></td>
              <td bgcolor="#F7F4EE"><label for="nivel_situacion"></label>
                <select name="nivel_situacion" id="nivel_situacion">
                <?php 
				foreach($array_nivel_situacion as $n => $valor)
				{
					if($valor==$nivel_situacion_actual)
					{echo'<option value="'.$valor.'" selected>'.$valor.'</option>';}
					else
					{echo'<option value="'.$valor.'" selected>'.$valor.'</option>';}
				}
				?>
              </select>
                <span class="Estilo7">(*si tiene ramos pendientes)</span></td>
              <td bgcolor="#F7F4EE">&nbsp;</td>
              <td colspan="2" bgcolor="#F7F4EE">&nbsp;</td>
            </tr>
            <tr class="odd">
              <td bgcolor="#F7F4EE"><span class="Estilo7">Situaci&oacute;n</span></td>
              <td bgcolor="#F7F4EE"><input name="situacion" type="hidden" value="<?php echo $situacion;?>"><?php echo $situacion;?></td>
              <td bgcolor="#F7F4EE"><span class="Estilo7">Jornada</span></td>
              <td colspan="2" bgcolor="#F7F4EE">
              <?php echo $jornada;?>
              <input name="jornada" type="hidden" value="<?php echo $jornada;?>">
             </td>
            </tr>
            <tr class="odd">
              <td bgcolor="#F7F4EE"><span class="Estilo7">Grupo</span></td>
              <td bgcolor="#F7F4EE"><select name="grupo" id="grupo">
                <?php 
		foreach(range('A', 'Z') as $letra)
		{
			if($grupo==$letra)
			{
				echo'<option value="'.$letra.'" selected="selected">'.$letra.'</option>';
			}
			else
			{
				echo'<option value="'.$letra.'">'.$letra.'</option>';
			}	
		}
		?>
              </select>              </td>
              <td bgcolor="#F7F4EE"><span class="Estilo7">Clave</span></td>
              <td colspan="2" bgcolor="#F7F4EE"><input name="clave" type="text" id="clave" value="<?php echo $clave; ?>" size="10" maxlength="10" /></td>
            </tr>
            <tr class="odd">
              <td bgcolor="#F7F4EE"><span class="Estilo7">Carrera</span></td>
              <td bgcolor="#F7F4EE"><select name="carrera" id="carrera">
                <?php
             $privilegio=$_SESSION["USUARIO"]["privilegio"];
			 switch($privilegio)
			 {
			 	case"admi_total":
				   $res="SELECT * FROM carrera WHERE id >0";
				   $result=$conexion_mysqli->query($res);
				   while($row =$result->fetch_assoc()) 
				   {
					   $carrera_id=$row["id"];
						$nomcar=$row["carrera"];
						if($carrera_id==$id_carrera)
						{
							echo'<option value="'.$carrera_id.'_'.$nomcar.'" selected="selected">'.$carrera_id.'_'.$nomcar.'</option>';
						}
						else
						{
							echo'<option value="'.$carrera_id.'_'.$nomcar.'">'.$carrera_id.'_'.$nomcar.'</option>';
						}	
				   }
					$result->free();
					break;
				default:	
					$res="SELECT * FROM carrera WHERE id >0";
				  $result=$conexion_mysqli->query($res);
				   while($row =$result->fetch_assoc()) 
				   {
					   $carrera_id=$row["id"];
						$nomcar=$row["carrera"];
						if($nomcar==$carrera)
						{
							echo'<option value="'.$carrera_id.'_'.$nomcar.'" selected="selected">'.$nomcar.'</option>';
						}
						else
						{
							echo'<option value="'.$carrera_id.'_'.$nomcar.'">'.$nomcar.'</option>';
						}	
				   }
					$result->free();
					break;
			 }
			 ?>
              </select>              </td>
              <td bgcolor="#F7F4EE" class="Estilo7">Actualizar Carrera en Notas (deshabilitado)</td>
              <td bgcolor="#F7F4EE"><input type="radio" name="actualiza_notas_carrera" id="actualizar_carrera" value="si">
              <label for="actualizar_carrera">Si</label></td>
              <td bgcolor="#F7F4EE"><input name="actualiza_notas_carrera" type="radio" id="actualizar_carrera2" value="si" checked>
                No</td>
            </tr>
            <tr class="odd">
              <td bgcolor="#F7F4EE">&nbsp;</td>
              <td bgcolor="#F7F4EE">&nbsp;</td>
              <td bgcolor="#F7F4EE" class="Estilo7">Eliminar el Actual Registro Academico</td>
              <td bgcolor="#F7F4EE"><input name="borrar_registro_academico" type="radio" id="borrar_registro_academico" value="si">
              Si
                <label for="borrar_registro_academico"></label></td>
              <td bgcolor="#F7F4EE"><input name="borrar_registro_academico" type="radio" id="borrar_registro_academico2" value="no" checked>
                No</td>
            </tr>
            <tr class="odd">
              <td bgcolor="#F7F4EE"><span class="Estilo7">Cargado en Super Salud (solo TENS)</span></td>
              <td bgcolor="#F7F4EE"><?php echo $msj_supersalud;?></td>
              <td bgcolor="#F7F4EE" class="Estilo7">&nbsp;</td>
              <td colspan="2" bgcolor="#F7F4EE">&nbsp;</td>
            </tr>
        </table>
      </div>
      <div class="TabbedPanelsContent">
      <table width="100%" border="0">
            <tr class="odd">
              <td width="20%" bgcolor="#F7F4EE"><span class="Estilo7">Fecha Registro</span></td>
              <td width="20%" bgcolor="#F7F4EE"><span class="Estilo7"> <input name="fechaRegistro" type="text" id="fechaRegistro" size="19" maxlength="19" value="<?php echo"$fechaRegistro";?>"  readonly/>
              <input type="button" name="botonX2" id="botonX2" value="..."/></span></td>
              </tr>
         </table>     
      </div>
    </div>
  </div>
  
<div id="botonera">
          <div align="right">
            <input type="submit" name="Submit" value="Grabar" />
            <input type="reset" name="Submit2" value="Borrar" />
          </div>
  </div>
  
  <?php
  $msj="";
	if($_GET)
	{
		if(isset($_GET["error"]))
		{
			$error=$_GET["error"];
			switch($error)
			{
				case"1":
					$msj="Rut Repetido...";
					break;
			}
		}
	}
	?>
  <div id="mensaje"><?php echo $msj;?> </div>
 </form> 
</div>
<!--FIN DATOS ALUMNOS-->
<!--REGISTRO HOJA VIDA-->
<div id="apDiv1">
  <div id="TabbedPanels2" class="TabbedPanels">
    <ul class="TabbedPanelsTabGroup">
      <li class="TabbedPanelsTab Estilo4" tabindex="5">&#9658;Observaciones Privadas</li>
      <li class="TabbedPanelsTab Estilo4" tabindex="6">Nueva</li>
    </ul>
    <div class="TabbedPanelsContentGroup">
      <div class="TabbedPanelsContent">
      Contenido 1
      <table width="100%" border="0">
          <tr>
            <td bgcolor="#f5f5f5" class="Estilo7">N&deg;</td>
            <td bgcolor="#f5f5f5" class="Estilo7">Fecha</td>
            <td bgcolor="#f5f5f5" class="Estilo7">Usuario</td>
            <td bgcolor="#f5f5f5" class="Estilo7">Observacion</td>
            <td bgcolor="#f5f5f5" class="Estilo7">&nbsp;</td>
          </tr>
          <?php
				 $id_alumno=$_SESSION["SELECTOR_ALUMNO"]["id"];
			
				 	$cons_O="SELECT * FROM hoja_vida WHERE id_alumno='$id_alumno' AND tipo_visualizacion='privada' ORDER by fecha desc";
					$sql_O=$conexion_mysqli->query($cons_O)or die($conexion_mysqli->error);
					$num_regZ=$sql_O->num_rows;
					if($num_regZ>0)
					{
						$aux=0;
						while($HV=$sql_O->fetch_assoc())
						{
							$aux++;
							$id_observacion=$HV["id"];
							$observacion=$HV["observacion"];
							$fecha=$HV["fecha"];
							$id_user_HV=$HV["id_user"];
							
							echo'<tr>
								  <td class="Estilo7">'.$aux.'</td>
								  <td class="Estilo7">'.$fech.'</td>
								  <td class="Estilo7"><a href="#" title="'.NOMBRE_PERSONAL($id_user_HV).'">'.$id_user_HV.'</a></td>
								  <td class="Estilo7">'.$observacion.'</td>
								  <td class="Estilo7"><a href="elimina_observacion_plus/elimina_observacion_plus.php?id_observacion='.$id_observacion.'&autor='.$id_user_HV.'"><img src="../../BAses/Images/b_drop.png" width="16" height="16"></a></td>
								</tr>';
						}
					}
					else
					{
						echo'<tr><td colspan="5" class="Estilo7">Sin Registros...</td>
						</tr>';
					}
				$sql_O->free();
				$conexion_mysqli->close();
            ?>
        </table>
      </div>
      <div class="TabbedPanelsContent">
      Contenido 2
      <form action="agrega_observacion_plus/observacion_plus.php" method="post" name="frm_O" id="frm_O">
          <table width="100%" border="0">
            <tr>
              <td class="Estilo7">&#9658;Nuevo Registro</td>
            </tr>
            <tr>
              <td><textarea name="observacion" id="observacion" cols="45" rows="5"></textarea>
                  <input type="button" name="boton_observacion" id="boton_observacion" value="Agregar Registro" onClick="VERIFICA_NVO_REG();" /></td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<!--FIN HOJA VIDA-->
<script type="text/javascript">
//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: true
      });
      cal.manageFields("boton", "fnac", "%Y-%m-%d"),
	  cal.manageFields("botonX2", "fechaRegistro", "%Y-%m-%d %H:%M:%S");
    //]]>
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1", {defaultTab:<?php echo $ver_tab;?>});
var TabbedPanels2 = new Spry.Widget.TabbedPanels("TabbedPanels2");
</script>
</body>
</html>