<?php
$USE_ONLINE = TRUE;
if($USE_ONLINE == TRUE) {
    $type = "mysql";
    $servername = "thefestival2022.mysql.database.azure.com";
    $portNumber = "3306";
    $username = "admin1";
    $password = "secret123.";
    $database = "theFestivalDb";
}
else {
    $type = "mysql";
    $servername = "mysql";
    $portNumber = "3306";
    $username = "root";
    $password = "secret123";
    $database = "TheFestivalDb";
}
?>