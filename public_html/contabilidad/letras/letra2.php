<?
     include ("../../SC/seguridad.php");
     include ("../../SC/privilegio2.php");
	 
	 extract($_POST);
	 
	 $_SESSION[apoderado_c]=$ocu_apo;
	 $_SESSION[rutapo_c]= $ocu_rutapo;
	 $_SESSION[direcapo_c]=$ocu_direcapo;
	 $_SESSION[ciuapo_c]=$ocu_ciuapo;


	 header("Location: imp_letra.php");

?>