<?php
//--------------CLASS_okalis------------------//
	require("../../OKALIS/class_OKALIS_v1.php");
	define("DEBUG", false);
	$O=new OKALIS();
	$O->DEBUG=DEBUG;
	$O->ruta_conexion="../../../funciones/";
	$O->clave_del_archivo=md5("Gestion de Encuestas_V1");
	$O->PERMITIR_ACCESO_USUARIO();
//--------------FIN CLASS_okalis---------------//
$continuar=false;
if(DEBUG){ var_dump($_GET);}
if(isset($_GET["dato_grafico"]))
{
	$id_encuesta=base64_decode($_GET["id_encuesta"]);
	$id_pregunta=base64_decode($_GET["id_pregunta"]);
	require("../../../funciones/conexion_v2.php");
		$cons_P="SELECT * FROM encuestas_pregunta WHERE id_encuesta='$id_encuesta' AND id_pregunta='$id_pregunta' LIMIT 1";
		 if(DEBUG){ echo"-->$cons_P<br>";}
		 $sql=$conexion_mysqli->query($cons_P)or die("Pregunta".$conexion_mysqli->error);
		 $M=$sql->fetch_assoc();
		 $pregunta=$M["pregunta"];
		 $sql->free();
		 
	 require("../../../funciones/VX.php");
	 $evento="Revisa Resultados-> Grafico de Encuesta id_encuesta: $id_encuesta id_pregunta: $id_pregunta";
	 REGISTRA_EVENTO($evento);	 
		 
	$conexion_mysqli->close();
	$dato_grafico=base64_decode($_GET["dato_grafico"]);
	if(DEBUG){var_dump($dato_grafico);}
	$continuar=true;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../../../funciones/codificacion.php"); ?>
<title>Ver grafico</title>

<link rel="stylesheet" type="text/css" href="../../libreria_publica/amcharts_3.20.7/samples/style.css"/>
        <script src="../../libreria_publica/amcharts_3.20.7/amcharts/amcharts.js" type="text/javascript"></script>
        <script src="../../libreria_publica/amcharts_3.20.7/amcharts/pie.js" type="text/javascript"></script>
<?php if($continuar){?>
        <script>
            var chart;
            var legend;

           // var chartData =[{"country": "Lithuania","value": 260},{"country": "Ireland","value": 201},{"country": "Germany","valuedd": 65},{"country": "Australia","value": 39},{"country": "UK","value": 19},{"country": "Latvia","value": 10}];

            var chartData = <?php echo $dato_grafico;?>


            AmCharts.ready(function () {
                // PIE CHART
                chart = new AmCharts.AmPieChart();
                chart.dataProvider = chartData;
                chart.titleField = "alternativa";
                chart.valueField = "value";
                chart.outlineColor = "#FFFFFF";
                chart.outlineAlpha = 0.8;
                chart.outlineThickness = 2;
                chart.balloonText = "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[porcentaje]]%)</span>";
                // this makes the chart 3D
                chart.depth3D = 15;
                chart.angle = 30;

                // WRITE
                chart.write("chartdiv");
            });
        </script>
    <?php }?>    
</head>

<body>
<div id="chartdiv" style="width:600px; height:400px;"></div>
 <div id="div_texto"><?php if($continuar){ echo "<strong>PREGUNTA: </strong>".$pregunta;}else{echo"No se puede mostrar :(";}?></div>
</body>
</html>