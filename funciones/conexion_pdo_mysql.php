<?php
//---------------------------------//
//defina los parametros
$host="localhost";
$user="consultor";
$pass="estaclave321";
$BBDD="maesstro";
//----------------------------------//
$pdo = new PDO('mysql:host=example.com;dbname=database', 'user', 'password');
$statement = $pdo->query("SELECT 'Hello, dear MySQL user!' AS _message FROM DUAL");
$row = $statement->fetch(PDO::FETCH_ASSOC);
echo htmlentities($row['_message']);
?>