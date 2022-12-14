<?php


  if(empty($movie->getImage())) {
    $movie->setImage("movie_cover.jpg");
  }
//<?= $movie->rating 
?>
<div class="card movie-card">
  <div class="card-img-top" style="background-image: url('<?= $BASE_URL ?>/img/movies/<?= $movie->getImage() ?>')"></div>
  <div class="card-body">
    <p class="card-rating">
      <i class="fas fa-star"></i>
      <span class="rating"><?= $movie->rating ?></span>
    </p>
    <h5 class="card-title">
      <a class="text-decoration-none" href="<?= $BASE_URL ?>/movie.php?id=<?= $movie->getId() ?>"><?= $movie->getTitle() ?></a>
    </h5>
    <a href="<?= $BASE_URL ?>/movie.php?id=<?= $movie->getId() ?>" class="btn btn-primary rate-btn">Avaliar</a>
    <a href="<?= $BASE_URL ?>/movie.php?id=<?= $movie->getId() ?>" class="btn btn-primary card-btn">Conhecer</a>
  </div>
</div>