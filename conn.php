<?php


$dbh = new PDO('
	mysql:dbname=db_name;host=localhost',
    'root',
    '',
    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'")
);