<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
/*
*/
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require("src/Exception.php");
require("src/PHPMailer.php");
require("src/SMTP.php");

class EMAIL extends PHPMailer{
	private $DEBUG=false;
	
	
 public function __construct($exceptions=false, $body = '')
    {
        //Don't forget to do this or other things may not be set correctly!
        parent::__construct($exceptions);
        //Set a default 'From' address
        $this->setFrom('no_responder_Cftmass@cftmass.cl', 'no_responder CFT Massachusetts');
        //Send via SMTP
        $this->isSMTP();
        //Equivalent to setting `Host`, `Port` and `SMTPSecure` all at once
        $this->Host = 'tls://smtp.gmail.com:587';
		$this->Username ="no_responder_Cftmass@cftmass.cl";  // Nombre de usuario del correo
    	$this->Password = "cftmass2021"; // Contras
        //Set an HTML and plain-text body, import relative image references
        $this->msgHTML($body, './images/');
        //Show debug output
		$this->SMTPAuth = true; 
    	
		$this->isHTML(true);
       	if($this->DEBUG){$this->SMTPDebug = SMTP::DEBUG_SERVER;}
		
		// $this->msgHTML($body, './images/');
        //Inject a new debug output handler
        //$this->Debugoutput = static function ($str, $level) {
        //    echo "Debug nivel $level; message: $str\n <br>";
        //};
    }

    //Extend the send function
    public function send()
    {
        //$this->Subject = '[Yay for me!] ' . $this->Subject;
        $r = parent::send();
		if($this->DEBUG){echo 'I sent a message with subject '. $this->Subject;}

        return $r;
    }
}


?>