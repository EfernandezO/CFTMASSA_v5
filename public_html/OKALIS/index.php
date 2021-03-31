<?php
	if(isset($_SERVER['HTTPS'])){$host="https://186.10.233.98";}
	else{$host="http://186.10.233.98";}
	
	header("location: $host");
?>