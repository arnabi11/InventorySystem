<?php
DEFINE('DB_USER','product_info');
DEFINE('DB_PASSWORD','');
DEFINE('DB_HOST','localhost');
DEFINE('DB_NAME','test1');

$dbc = mysqli_connect("localhost","root","","test1")
OR dies('Could not connect to MySQL: ' .
    mysqli_connect_error());
// echo "DB Connected"
    
?>