<?php

require_once('globals.php');
require_once('db.php');

require_once('models/Movie.php');
require_once('models/Message.php');
require_once('models/Review.php');

require_once('DAO/UserDAO.php');
require_once('DAO/MovieDAO.php');
require_once('DAO/ReviewDAO.php');

$message = new Message($BASE_URL);
$userDao = new UserDAO($conn, $BASE_URL);
$movieDao = new MovieDAO($conn, $BASE_URL);
$reviewDao = new ReviewDAO($conn, $BASE_URL);


// Check type do formulario
$type = filter_input(INPUT_POST, 'type');

//Resgata dados do usuario
$userData = $userDao->verifyToken();

if($type === 'create'){

  $rating = filter_input(INPUT_POST, 'rating');
  $review = filter_input(INPUT_POST, 'review');
  $movies_id = filter_input(INPUT_POST, 'movies_id');
  $users_id = $userData->getId();

  $reviewObject = new Review();

  $movieData = $movieDao->findById($movies_id);

  // print_r($_POST); exit;

  // valida se filme existe
  if($movieData){

    //dados minimos
    if(!empty($rating) && !empty($review) && !empty($movies_id) ){

      $reviewObject->setRating($rating);
      $reviewObject->setReview($review);
      $reviewObject->setMovies_id($movies_id);
      $reviewObject->setUsers_id($users_id);

      $reviewDao->create($reviewObject);
    }
    else{
      $message->setMessage("Necessario inserir uma Nota e um Comentário", "error", "back");
    }
  }
  else{
    $message->setMessage("Informações inválidas!", "error", "/index.php");
  }
}
else{
  $message->setMessage("Informações inválidas!", "error", "/index.php");
}