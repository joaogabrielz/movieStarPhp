<?php

require_once('globals.php');
require_once('db.php');

require_once('models/User.php');
require_once('models/Message.php');
require_once('DAO/UserDAO.php');

$message = new Message($BASE_URL);

$userDAO = new UserDAO($conn, $BASE_URL);

// Check type do formulario
$type = filter_input(INPUT_POST, 'type'); // funcao resgatar inputs livres dados inseridos maliciosos
//echo $type;

if($type === "register"){

  $name = filter_input(INPUT_POST, "name");
  $lastname = filter_input(INPUT_POST, "lastname");
  $email = filter_input(INPUT_POST, "email");
  $password = filter_input(INPUT_POST, "password");
  $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

  // Verificação dados minimos
  if($name && $lastname && $email && $password){
    
    if($password === $confirmpassword){

      if(strlen($password) < 6 && strlen($confirmpassword) < 6){
        $message->setMessage("A senha deve ter tamanho minimo de 6 caracteres", "error", "back");
        return;
      }

    //Verificar se o emial já esta cadastrado no sistema
      if ($userDAO->findByEmail($email) === false) {

        $user = new User();

      // Criação token e senha
        $userToken = $user->generateToken();
        $finalPassword = $user->generatePassword($password);

        $user->setName($name);
        $user->setLastname($lastname);
        $user->setEmail($email);
        $user->setPassword($finalPassword);
        $user->setToken($userToken);

        // Criando Usuario e Autenticando
        $auth = true;
        $userDAO->create($user, $auth);

      }
      else{
        $message->setMessage("Este email já foi cadastrado no sistema", "error", "back");
        return;
      }
      
    }
    else{
      $message->setMessage("As senhas não conferem", "error", "back");
    }
  }
  else{
    // Erro dados faltando...
  $message->setMessage("Porfavor preencha todos os campos.", "error", "back");
  }
}
else if($type === "login"){

  $email = filter_input(INPUT_POST, "email");
  $password = filter_input(INPUT_POST, "password");

  // Tenta autenticar usuario
  if($userDAO->authenticateUser($email, $password)){

    $message->setMessage("Seja Bem-Vindo!", "success", "/editProfile.php");

  }
  else{
    // redireciona usuario caso nao consiga autenticar
  $message->setMessage("Usuario ou Senha inválidos.", "error", "back");
  }

}
else{
  $message->setMessage("Informações inválidas!", "error", "/index.php");
}