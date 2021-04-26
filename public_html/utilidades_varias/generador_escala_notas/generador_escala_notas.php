<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<style type="text/css">
#apDiv1 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:1;
	left: 59px;
	top: 59px;
}
#div_resultado {
	position:absolute;
	width:200px;
	height:115px;
	z-index:2;
	left: 59px;
	top: 189px;
}
</style>
<script type="text/javascript" src="../../libreria_publica/jquery.js"></script>

<script type="text/javascript">
var pagina=1;
$(document).ready(function()
{
	// Carga inicial	
	cargardatos();
	
});
function cargardatos(){
		// Peticion AJAX
		
		$("#loader").html('<img src="../../BAses/Images/massa_loading.gif" width="80" height="80" alt="cargando..." />');
		$.get("generador_escala_notas_server.php?pagina="+100,
			function(data){
				if (data != "") {
					  $( "#div_resultado" ).fadeIn( "slow", function() {
					// Animation complete
						$('#div_resultado').html(data);
				  });
					
				}
				$('#loader').empty();
			}
		);				
}
</script>
</head>

<body>
<div id="apDiv1">
  <table width="200" border="1">
    <tr>
      <td width="129">Nota Aprobacion</td>
      <td width="55">&nbsp;</td>
    </tr>
    <tr>
      <td>% Exigencia</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Puntaje Total</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</div>
<div id="div_resultado"></div>
</body>
</html>