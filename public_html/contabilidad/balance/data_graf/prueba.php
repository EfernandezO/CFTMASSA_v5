<a href="../../../Graf/ofc/open-flash-chart.swf">klk
</a>
<?php 
	echo "Nombre Server: ". $_SERVER['SERVER_NAME'];
?>
<?php
include_once "../../../Graf/ofc/php-ofc-library/open_flash_chart_object.php";
open_flash_chart_object(465, 260,"http://www.cftmass.cl/contabilidad/balance/data_graf/data1.php",false, "../../../Graf/ofc/" );
?>
