<?php

// Dados do banco...

$db_name = "";
$db_host = "";
$db_user = "";
$db_pass = "";

$conn = new PDO("mysql:dbname=" . $db_name . ";host=" . $db_host, $db_user, $db_pass);

// Habilitar Erros PDO, erro na tela caso erro DB
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
