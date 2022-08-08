<?php

$con_string = "host=".$dbhost." port=".$dbport." dbname=".$dbdb." user=".$dbuser." password=".$dbpass;
$cdb = pg_connect($con_string);

?>