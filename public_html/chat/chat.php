<?php

/*

Copyright (c) 2009 Anant Garg (anantgarg.com | inscripts.com)

This script may be used for non-commercial purposes only. For any
commercial purposes, please contact the author at 
anant.garg@inscripts.com

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.

*/
@session_start();
global $dbh;
//$dbh = mysql_connect(DBPATH,DBUSER,DBPASS);
//mysql_selectdb(DBNAME,$dbh);

if ($_GET['action'] == "chatheartbeat") { chatHeartbeat(); } 
if ($_GET['action'] == "sendchat") { sendChat(); } 
if ($_GET['action'] == "closechat") { closeChat(); } 
if ($_GET['action'] == "startchatsession") { startChatSession(); } 

if (!isset($_SESSION["CHAT"]['chatHistory'])) {
	$_SESSION["CHAT"]['chatHistory'] = array();	
}

if (!isset($_SESSION["CHAT"]['openChatBoxes'])) {
	$_SESSION["CHAT"]['openChatBoxes'] = array();	
}

function chatHeartbeat() {
require("../../funciones/conexion_v2.php");	
	if(isset($_SESSION["CHAT"])){$nick=$_SESSION["CHAT"]['nick'];}
	else{$nick="";}
	
	$sql = "select * from chat_x_face where (chat_x_face.to = '".$nick."' AND recd = 0) order by id ASC";
	$query = $conexion_mysqli->query($sql)or die("SELECCION".$conexion_mysqli->error);
	$items = '';

	$chatBoxes = array();

	while ($chat =$query->fetch_array()) {

		if (!isset($_SESSION["CHAT"]['openChatBoxes'][$chat['from']]) && isset($_SESSION["CHAT"]['chatHistory'][$chat['from']])) {
			$items = $_SESSION["CHAT"]['chatHistory'][$chat['from']];
		}

		$chat['message'] = sanitize($chat['message']);

		$items .= <<<EOD
					   {
			"s": "0",
			"f": "{$chat['from']}",
			"m": "{$chat['message']}"
	   },
EOD;

	if (!isset($_SESSION["CHAT"]['chatHistory'][$chat['from']])) {
		$_SESSION["CHAT"]['chatHistory'][$chat['from']] = '';
	}

	$_SESSION["CHAT"]['chatHistory'][$chat['from']] .= <<<EOD
						   {
			"s": "0",
			"f": "{$chat['from']}",
			"m": "{$chat['message']}"
	   },
EOD;
		
		unset($_SESSION["CHAT"]['tsChatBoxes'][$chat['from']]);
		$_SESSION["CHAT"]['openChatBoxes'][$chat['from']] = $chat['sent'];
	}

	if (!empty($_SESSION["CHAT"]['openChatBoxes'])) {
	foreach ($_SESSION["CHAT"]['openChatBoxes'] as $chatbox => $time) {
		if (!isset($_SESSION["CHAT"]['tsChatBoxes'][$chatbox])) {
			$now = time()-strtotime($time);
			$time = date('g:iA M dS', strtotime($time));

			$message = "Sent at $time";
			if ($now > 180) {
				$items .= <<<EOD
{
"s": "2",
"f": "$chatbox",
"m": "{$message}"
},
EOD;

	if (!isset($_SESSION["CHAT"]['chatHistory'][$chatbox])) {
		$_SESSION["CHAT"]['chatHistory'][$chatbox] = '';
	}

	$_SESSION["CHAT"]['chatHistory'][$chatbox] .= <<<EOD
		{
"s": "2",
"f": "$chatbox",
"m": "{$message}"
},
EOD;
			$_SESSION["CHAT"]['tsChatBoxes'][$chatbox] = 1;
		}
		}
	}
	
}

	@$sql = "update chat_x_face set recd = 1 where chat_x_face.to = '".$_SESSION["CHAT"]['nick']."' and recd = 0";
	$query = $conexion_mysqli->query($sql);
	
$conexion_mysqli->close();	
	if ($items != '') {
		$items = substr($items, 0, -1);
	}
header('Content-type: application/json');
?>
{
		"items": [
			<?php echo $items;?>
        ]
}

<?php
			exit(0);
}

function chatBoxSession($chatbox) {
	
	$items = '';
	
	if (isset($_SESSION["CHAT"]['chatHistory'][$chatbox])) {
		$items = $_SESSION["CHAT"]['chatHistory'][$chatbox];
	}

	return $items;
}

function startChatSession() {
	$items = '';
	if (!empty($_SESSION["CHAT"]['openChatBoxes'])) {
		foreach ($_SESSION["CHAT"]['openChatBoxes'] as $chatbox => $void) {
			$items .= chatBoxSession($chatbox);
		}
	}


	if ($items != '') {
		$items = substr($items, 0, -1);
	}

header('Content-type: application/json');
?>
{
		"username": "<?php echo $_SESSION["CHAT"]['nick'];?>",
		"items": [
			<?php echo $items;?>
        ]
}

<?php


	exit(0);
}

function sendChat() {
	require("../../funciones/conexion_v2.php");	
	$from = $_SESSION["CHAT"]['nick'];
	//echo"$from ---> nick<br>";
	$to = $_POST['to'];
	$message = $_POST['message'];

	$_SESSION["CHAT"]['openChatBoxes'][$_POST['to']] = date('Y-m-d H:i:s', time());
	
	$messagesan = sanitize($message);

	if (!isset($_SESSION["CHAT"]['chatHistory'][$_POST['to']])) {
		$_SESSION["CHAT"]['chatHistory'][$_POST['to']] = '';
	}

	$_SESSION["CHAT"]['chatHistory'][$_POST['to']] .= <<<EOD
					   {
			"s": "1",
			"f": "{$to}",
			"m": "{$messagesan}"
	   },
EOD;


	unset($_SESSION["CHAT"]['tsChatBoxes'][$_POST['to']]);

	$sql = "insert into chat_x_face (chat_x_face.from,chat_x_face.to,message,sent) values ('".$from."', '".$to."','".$message."',NOW())";
	$query = $conexion_mysqli->query($sql)or die("INSERTANDO ".$conexion_mysqli->error);
	$conexion_mysqli->close();
	echo "1";
	exit(0);
}

function closeChat() {

	unset($_SESSION["CHAT"]['openChatBoxes'][$_POST['chatbox']]);
	
	echo "1";
	exit(0);
}

function sanitize($text) {
	$text = htmlspecialchars($text, ENT_QUOTES);
	$text = str_replace("\n\r","\n",$text);
	$text = str_replace("\r\n","\n",$text);
	$text = str_replace("\n","<br>",$text);
	return $text;
}