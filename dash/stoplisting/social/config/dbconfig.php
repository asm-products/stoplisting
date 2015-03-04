<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', ' ');
define('DB_PASSWORD', ' ');
define('DB_DATABASE', ' ');
$db=mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
mysql_select_db(DB_DATABASE, $db);
?>
