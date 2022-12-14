<?php

require_once('globals.php');
require_once('db.php');

require_once('models/Movie.php');
require_once('models/Message.php');
require_once('DAO/UserDAO.php');
require_once('DAO/MovieDAO.php');

$message = new Message($BASE_URL);
$userDao = new UserDAO($conn, $BASE_URL);
$movieDao = new MovieDAO($conn, $BASE_URL);

// Check type do formulario
$type = filter_input(INPUT_POST, 'type'); // funcao resgatar inputs livres dados inseridos maliciosos

//Resgata dados do usuario
$userData = $userDao->verifyToken();


if($type === "create"){

  //Receber dados do POST
  $title = filter_input(INPUT_POST, "title");
  $description = filter_input(INPUT_POST, "description");
  $trailer = filter_input(INPUT_POST, "trailer");
  $category = filter_input(INPUT_POST, "category");
  $length = filter_input(INPUT_POST, "length");

  // print_r($_POST);
  // exit;

  $movie = new Movie();


  //Validação minima dados
  if(!empty($title) && !empty($description) && !empty($category)){

    // Preencher os dados do filme
  $movie->setTitle($title);
  $movie->setDescription($description);
  $movie->setTrailer($trailer);
  $movie->setCategory($category);
  $movie->setLength($length);
  $movie->setUsers_id($userData->getId());


    // Upload da imagem
    if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

    $image = $_FILES["image"];
    $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
    $jpgArray = ["image/jpeg", "image/jpg"];

    // Checagem de tipo de imagem
    if (in_array($image["type"], $imageTypes)) {

      // Checar se jpg
      if (!in_array($image, $jpgArray)) {
        $imageFile = imagecreatefromjpeg($image["tmp_name"]);
      } else {
        // Imagem é png
        $imageFile = imagecreatefrompng($image["tmp_name"]);
      }

      // Gerando o nome da imagem
      $imageName = $movie->imageGenerateName();
      imagejpeg($imageFile, "./img/movies/" . $imageName, 100);
      move_uploaded_file($imageName, "/img/movies/"); 

      $movie->setImage($imageName);

    }
    else {
      $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
    }
  }       
    // print_r($_POST);
    // print_r($_FILES);
    // exit;
  $movieDao->create($movie);

  }
  else{
    $message->setMessage("Necessario informar dados minimos Titulo, Descrição e Categoria", "error", "back");
  }

}
else if($type === "delete"){

  //recebe dados do form
  $id = filter_input(INPUT_POST, 'id');

  $movie = $movieDao->findById($id);

  if($movie){

    //Verificar se o film,e é do usuario
    if ($movie->getUsers_id() === $userData->getId()) {

      $movieDao->destroy($movie->getId());
      
    } else {
      $message->setMessage("Informações inválidas!", "error", "/index.php");
    }
  }
  else{
    $message->setMessage("Informações inválidas!", "error", "/index.php");
  }
}
else if($type === "update"){

  //Receber dados do POST
  $title = filter_input(INPUT_POST, "title");
  $description = filter_input(INPUT_POST, "description");
  $trailer = filter_input(INPUT_POST, "trailer");
  $category = filter_input(INPUT_POST, "category");
  $length = filter_input(INPUT_POST, "length");
  $id = filter_input(INPUT_POST, "id");


  $movieData = $movieDao->findById($id);

  // Veridfcia se encontrou o filme
  if($movieData){


    //Verificar se o film,e é do usuario
    if ($movieData->getUsers_id() === $userData->getId()) {

    //Validação minima dados
      if (!empty($title) && !empty($description) && !empty($category)) {

        // Edição de filme
        $movieData->setTitle($title);
        $movieData->setDescription($description);
        $movieData->setTrailer($trailer);
        $movieData->setCategory($category);
        $movieData->setLength($length);


            // Upload da imagem
        if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

          $image = $_FILES["image"];
          $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
          $jpgArray = ["image/jpeg", "image/jpg"];

          // Checagem de tipo de imagem
          if (in_array($image["type"], $imageTypes)) {

            // Checar se jpg
            if (!in_array($image, $jpgArray)) {
              $imageFile = imagecreatefromjpeg($image["tmp_name"]);
            } else {
              // Imagem é png
              $imageFile = imagecreatefrompng($image["tmp_name"]);
            }

            // Gerando o nome da imagem
            $imageName = $movieData->imageGenerateName();
            imagejpeg($imageFile, "./img/movies/" . $imageName, 100);
            move_uploaded_file($imageName, "/img/movies/");

            $movieData->setImage($imageName);

          } else {
            $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
          }
        }


        $movieDao->update($movieData);

      }
      else{
        $message->setMessage("Necessario informar dados minimos Titulo, Descrição e Categoria", "error", "back");
      }
      
    } else {
      $message->setMessage("Informações inválidas!", "error", "/index.php");
    }
  }
  else{
    $message->setMessage("Informações inválidas!", "error", "/index.php");
  }
}
else{
  $message->setMessage("Informações inválidas!", "error", "/index.php");
}