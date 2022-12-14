<?php

require_once('globals.php');
require_once('db.php');

require_once('models/User.php');
require_once('models/Message.php');
require_once('DAO/UserDAO.php');

$message = new Message($BASE_URL);

$userDao = new UserDAO($conn, $BASE_URL);

// Check type do formulario
$type = filter_input(INPUT_POST, 'type'); // funcao resgatar inputs livres dados inseridos maliciosos

if ($type === "update") {
  // print_r($_POST);exit;

  //Resgata dados do usuario
  $userData = $userDao->verifyToken();

  //Receber dados do POST
  $name = filter_input(INPUT_POST, "name");
  $lastname = filter_input(INPUT_POST, "lastname");
  $email = filter_input(INPUT_POST, "email");
  $bio = filter_input(INPUT_POST, "bio");


if ($name !== $userData->getName() || $lastname !== $userData->getLastname()
    || $bio !== $userData->getBio() || $email !== $userData->getEmail() ||
    isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

  // Criar um novo objeto de usuário
  $user = new User();

  // Preencher os dados do usuario
  $userData->setName($name);
  $userData->setLastname($lastname);
  $userData->setEmail($email);
  $userData->setBio($bio);


  // Upload da imagem
  if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

  // Possivel problema 
    // magecreatefrompng(): "/tmp/phpBTtun0" is not a valid PNG file
    // Fatal error: Uncaught TypeError: imagejpeg(): Argument #1 ($image) must be of type GdImage
  //Possivel SOlucao
   // Descomentar biblioteca gd no arquivo php.ini
   // parâmetro extension=gd do arquivo pasta php ou apache-> php.ini
    // Reinicie o servidor apache se ubuntu -> systemctl restart apache2.service

  
    $image = $_FILES["image"];
    $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
    $jpgArray = ["image/jpeg", "image/jpg"];


      // Checagem de tipo de imagem
      if (in_array($image["type"], $imageTypes)) {

        
        // Checar se jpg
        if (!in_array($image, $jpgArray)) {

          $imageFile = imagecreatefromjpeg($image["tmp_name"]);   
        } 
        else {
          // Imagem é png
          $imageFile = imagecreatefrompng($image["tmp_name"]);
        }

        
        $imageName = $user->imageGenerateName();
        
        imagejpeg($imageFile, "./img/users/" . $imageName, 100);
        move_uploaded_file($imageName, "/img/users/"); // tive que coloca para poder mover o aqrv

        $userData->setImage($imageName);


      }
    
    }
    else {
      $message->setMessage("Tipo inválido de imagem, Insira png ou jpg!", "error", "back");
    }

  $userDao->update($userData);  

  }
  else{
    $message->setMessage("Não há dados para atualizar", "error", "back");
  }
  
} 
else if ($type === "changepassword") {
  //ATUALIZAR SENHA DO USUARIO

  //Receber dados do POST
  $password = filter_input(INPUT_POST, "password");
  $confirmpassword = filter_input(INPUT_POST, "confirmpassword");
  //$id = filter_input(INPUT_POST, "id"); // Poderia pegar do $userData tbm, INSEGURO

  //Resgata dados do usuario logado
  $userData = $userDao->verifyToken();
  $id = $userData->getId();

  if ($password != "" && $confirmpassword != "") {

    if($password !== $confirmpassword){
      $message->setMessage("As senhas nao conferem!", "error", "back");
      return;
    }
    if (strlen($password) < 6 && strlen($confirmpassword) < 6) {
      $message->setMessage("A senha deve ter tamanho minimo de 6 caracteres", "error", "back");
      return;
    }

    // Criar um novo objeto de usuário
    $user = new User();
  
    $finalPassword = $user->generatePassword($password);
    $user->setPassword($finalPassword);
    $user->setId($id);
  
    $userDao->changePassword($user);

  }
  else{
    $message->setMessage("Porfavor preencha as senhas!", "error", "back");
  }

} 
else {
  $message->setMessage("Informações inválidas!", "error", "/index.php");
}
