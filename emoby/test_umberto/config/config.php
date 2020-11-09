<?php
define('DBNAME', 'test');
define('DBUSER', 'test');
define('DBPASS', '#XqwAH79uy');
define('DBHOST', 'testsvr.emoby.it');

try {
  $dbConn = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME.";charset=utf8", DBUSER, DBPASS);
}
catch(PDOException $e)
{
  echo "Si è verificato un'errore";
}
?>