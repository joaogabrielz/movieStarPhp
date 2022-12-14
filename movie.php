<?php
  require_once('templates/header.php');

  // Verifica se Usuario esta autenticados
  require_once('models/Movie.php');
  require_once('DAO/MovieDAO.php');
  require_once('DAO/ReviewDAO.php');

  // Pega id do filme
  $id = filter_input(INPUT_GET, 'id');

$movie;

$movieDao = new MovieDAO($conn, $BASE_URL);
$reviewDao = new ReviewDAO($conn, $BASE_URL);

if(empty($id)){
  $message->setMessage("O filme não foi encontrado", "error", "/index.php");
}
else{

  $movie = $movieDao->findById($id);

  // Verifica se filme existe
  if(empty($movie)){
  $message->setMessage("O filme não foi encontrado", "error", "/index.php");
  return;
  }


}

//Checar se filme tem imagem 
if($movie->getImage() == ""){
  $movie->setImage("movie_cover.jpg");
}


// Checar se filme é do proprio usuario..
$userOwnsMovie = false;
if(!empty($userData)){ // vem do headers
  if($userData->getId() === $movie->getUsers_id()){
    $userOwnsMovie = true;
  }

// resgatar as reviews do filme...
  $alreadyReviewed = $reviewDao->hasAlreadyReviewed($id, $userData->getId());
}

//resgatar as reviews do filme
$movieReviews = $reviewDao->getMoviesReview($id);


?>

<div id="main-container" class="container-fluid">
  <div class="row">
    <div class="offset-md-1 col-md-6 movie-container">
      <h1 class="page-title"><?= $movie->getTitle() ?></h1>
      <p class="movie-details">
        <span>Duração: <?= $movie->getLength() ?></span>
        <span class="pipe"></span>
        <span><?= $movie->getCategory() ?></span>
        <span class="pipe"></span>
        <span><i class="fas fa-star"></i> <?= $movie->rating ?><span>
      </p>
      <iframe src="<?= $movie->getTrailer()?>" width="560" height="315"
      frameboard="0" frameborder="0" allow="accelerometer; autoplay; clipboard-write;
      encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
      <p><?= $movie->getDescription() ?></p>  
    </div>
    <div class="col-md-4">
      <div class="movie-image-container" 
      style="background-image: url('<?= $BASE_URL ?>/img/movies/<?= $movie->getImage() ?>');"></div>      
    </div>
    <div class="offset-md-1 col-md-10" id="reviews-container">
      <h3 id="reviews-title">Avaliações: </h3>
      <!-- Verifica se habilita review para o usuário ou não -->
    <?php if(!empty($userData) && !$userOwnsMovie && !$alreadyReviewed): ?>

      <div class="col-md-12" id="review-form-container">
        <h4>Envie sua avaliação: </h4>
        <p class="page-description">Preencha o formulário com a nota e comntário sobre o filme</p>
        <form action="<?= $BASE_URL ?>/review_process.php" id="review-form" method="POST">
          <input type="hidden" name="type" value="create">
          <input type="hidden" name="movies_id" value="<?= $movie->getId() ?>">
            <div class="form-group mb-2">
              <select for="rating" id="rating" name="rating" class="form-control">
                <option value="">Selecione</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
              </select>
            </div>
            <div class="form-group mb-2">
              <label for="rwview">Seu comentário: </label>
              <textarea class="form-control" name="review" id="review" rows="3"
              placeholder="O que você achou do filme? "></textarea>
            </div>
            <input type="submit" class="btn card-btn" value="Enviar comentário">
        </form>
      </div>

    <?php endif; ?>
      <!-- Comntarios -->
     <?php foreach($movieReviews as $review): ?>
        <?php require("templates/user_review.php") ?>
      <?php endforeach; ?>
      <?php if(count($movieReviews) == 0) :?>
        <p class="empty-list">Não há comentarios para este filme ainda..</p>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php
  require_once('templates/footer.php');
?>