<?php
  require_once('templates/header.php');

  // Verifica se Usuario esta autenticados
  require_once('models/User.php');
  require_once('DAO/UserDAO.php');
    require_once('DAO/MovieDAO.php');

  $userDao = new UserDAO($conn, $BASE_URL);
  $movieDao = new MovieDAO($conn, $BASE_URL);

  $userData = $userDao->verifyToken(true); // requerindo auth na pag

  // Pega id do filme
  $id = filter_input(INPUT_GET, 'id');

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

  

?>
 <div id="main-container" class="container-fluid">
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-6 offset-md-1">
        <h1><?= $movie->getTitle() ?></h1>
        <p class="page-description">Altere os dados do filme no formulário abaixo:</p>
      <form id="edit-movie-form" action="<?= $BASE_URL ?>/movie_process.php" method="POST"
      enctype="multipart/form-data">
      <input type="hidden" name="type" value="update">
      <input type="hidden" name="id" value="<?= $movie->getId() ?>">
        <div class="form-group mb-2">
          <label for="title">Título: </label>
          <input type="text" class="form-control" id="title" name="title" placeholder="Digite o titulo do seu filme" 
          value="<?= $movie->getTitle() ?>">
        </div>
        <div class="form-group mb-2">
          <label for="image" style="display: block;">Imagem: </label>
          <input accept="image/*" type="file" class="form-control-file" name="image" id="image">
        </div>
        <div class="form-group mb-2">
          <label for="length">Duração: </label>
          <input value="<?= $movie->getLength() ?>" type="text" class="form-control" id="length" name="length" placeholder="Digite a duração do filme">
        </div>
        <div class="form-group mb-2">
          <label for="category">Categoria: </label>
          <select name="category" id="category" class="form-control">
            <option value="">Selecione</option>
            <option value="Ação" <?= $movie->getCategory() === "Ação" ? "selected" : "" ?>>Ação</option>
            <option value="Drama" <?= $movie->getCategory() === "Drama" ? "selected" : "" ?>>Drama</option>
            <option value="Comedia" <?= $movie->getCategory() === "Comedia" ? "selected" : "" ?>>Comédia</option>
            <option value="Fantasia" <?= $movie->getCategory() === "Fantasia" ? "selected" : "" ?>>Fantasia</option>
            <option value="Romance" <?= $movie->getCategory() === "Romance" ? "selected" : "" ?>>Romance</option>
          </select>
        </div>
        <div class="form-group mb-2">
          <label for="trailer">Trailer: </label>
          <input value="<?= $movie->getTrailer() ?>"  type="text" class="form-control" id="trailer" name="trailer" placeholder="Insira o link do trailer">
        </div>
        <div class="form-group mb-2">
          <label for="description">Descrição: </label>
          <textarea class="form-control" rows="5" id="description" name="description" placeholder="Descreva o filme..."><?= $movie->getDescription() ?></textarea>
        </div>
      <input type="submit" class="btn card-btn" value="Atualizar Filme">
      </form>
      </div>
      <div class="col-md-3">
        <div class="movie-image-container" 
        style="background-image: url('<?= $BASE_URL ?>/img/movies/<?= $movie->getImage() ?>');"></div>
      </div>
    </div>
  </div>
  </div>
<?php
  require_once('templates/footer.php');
?>
