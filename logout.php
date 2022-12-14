<?php

require_once('templates/header.php');

if($userDao){ // Se Usuario esta Logado
  $userDao->destroyToken();
}