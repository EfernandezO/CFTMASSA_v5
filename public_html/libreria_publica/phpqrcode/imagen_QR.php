<?php  
if(isset($_GET["qr_info"])) 
{
	$qr_info=$_GET["qr_info"];
	
    include "qrlib.php";    
    QRcode::png($qr_info);    
}
 ?>

    