<?php
  require_once('templates/header.php');

  // Verifica se Usuario esta autenticados
  require_once('models/User.php');
  require_once('DAO/UserDAO.php');
  require_once('DAO/MovieDAO.php');

  $user = new User();

  $movieDao = new MovieDAO($conn, $BASE_URL);
  $userDao = new UserDAO($conn, $BASE_URL);
  $userData = $userDao->verifyToken(true); // requerindo auth na pag

  $userMovies = $movieDao->getMoviesByUserId($userData->getId());

?>
<div id="main-container" class="container-fluid">
  <h2 class="section-title">DashBoard</h2>
  <p class="section-description">Adicione ou atualize as informações dos filmes que voê enviou</p>
  <div class="col-md-12" id="add-movie-container">
    <a href="<?= $BASE_URL ?>/newMovie.php" class="btn card-btn">
      <i class="fas fa-plus"></i>Adicionar Filme
    </a>
  </div>
  <div class="col-md-12" id="movies-dashboard">
    <table class="table">
      <thead>
        <td scope="col">#</td>
        <td scope="col">Título</td>
        <td scope="col">Nota</td>
        <td scope="col" class="actions-column">Ações</td>
      </thead>
      <tbody>
      <?php foreach ($userMovies as $movie): ?>
        <tr>
          <td scope="row"> <?= $movie->getId() ?></td>
          <td><a href="<?= $BASE_URL ?>/movie.php?id=<?= $movie->getId() ?>" 
            class="table-movie-title text-decoration-none"> <?= $movie->getTitle() ?></a>
          </td>
          <td><i class="fas fa-star"></i> <?= $movie->rating ?></td>
          <td class="actions-column">
            <a href="<?= $BASE_URL ?>/editMovie.php?id=<?= $movie->getId() ?>" class="edit-btn text-decoration-none">
              <i class="far fa-edit"></i> Editar 
            </a>
            <form action="<?= $BASE_URL ?>/movie_process.php" method="POST">
            <input type="hidden" name="type" value="delete">
            <input type="hidden" name="id" value="<?= $movie->getId() ?>">
              <button type="submit" class="delete-btn">
                <i class="fas fa-times"></i> Deletar
              </button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php
require_once('templates/footer.php');
?>
