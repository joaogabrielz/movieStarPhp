<?php
  require_once('templates/header.php');

  // Verifica se Usuario esta autenticados
  require_once('models/User.php');
  require_once('DAO/UserDAO.php');
  require_once('DAO/MovieDAO.php');


$user = new User();
$userDao = new UserDAO($conn, $BASE_URL);
$movieDao = new MovieDAO($conn, $BASE_URL);

// receber id od usuario
$id = filter_input(INPUT_GET, "id");

if(empty($id)){

  if(!empty($userData)){
    $id = $userData->getId();
  }
  else{
    $message->setMessage("Usuario não encontrado", "error", "/index.php");
  }
}
else{

  $userData = $userDao->findById($id);

  // se nao encontrar  usuario
  if(!$userData){
    $message->setMessage("Usuario não encontrado", "error", "/index.php");  
  }
}

$fullname = $user->getFullName($userData);

if($userData->getImage() == ""){
  $userData->setImage("user.png");
}

// Filmes que o usuario adicionou
$userMovies = $movieDao->getMoviesByUserId($id);

?>

<div id="main-container" class="container-fluid">
  <div class="col-md-8 offset-md-2">
    <div class="row profile-container">
      <div class="col-md-12 about-container">
        <h1 class="page-title"><?= $fullname ?></h1>
        <div id="profile-image-container" class="profile-image"
        style="background-image: url('<?= $BASE_URL ?>/img/users/<?= $userData->getImage() ?>');"></div>
        <h3 class="about-title">Sobre: </h3>
        <?php if(!empty($userData->getBio())) : ?>
          <p class="profile-description"><?= $userData->getBio() ?></p>
        <?php else: ?>
          <p class="profile-description">Usuário ainda não escreveu sua bio..</p>
        <?php endif; ?>
      </div>
      <div class="col-md-12 added-movies-container">
        <h3>Filmes que enviou: </h3>
        <div class="movies-container">
          <?php foreach($userMovies as $movie) : ?>
            <?php require("templates/movie_card.php"); ?>
          <?php endforeach; ?>
          <?php if(count($userMovies) === 0): ?>
            <p class="empty-list">O usuário ainda não enviou filmes..</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
  require_once('templates/footer.php');
?>