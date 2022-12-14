<?php

ini_set('display_errors', 1);

session_start();

$BASE_URL = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI'] . "?");
