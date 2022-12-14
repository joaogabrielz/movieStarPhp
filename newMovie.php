<?php
  require_once('templates/header.php');

  // Verifica se Usuario esta autenticados
  require_once('models/User.php');
  require_once('DAO/UserDAO.php');

  $userDao = new UserDAO($conn, $BASE_URL);
  $userData = $userDao->verifyToken(true); // requerindo auth na pag


?>
  <div id="main-container" class="container-fluid">
    <div class="offset-md-4 col-md-4 new-movie-container">
      <h1 class="page-title">Adicionar Filme</h1>
      <p class="page-description">Adicione sua crítica e compartilhe com o mundo!</p>
      <form action="<?= $BASE_URL ?>/movie_process.php" id="add-movie-form" 
      method="POST" enctype="multipart/form-data">
      <input type="hidden" name="type" value="create">
        <div class="form-group mb-2">
          <label for="title">Título: </label>
          <input type="text" class="form-control" id="title" name="title" placeholder="Digite o titulo do seu filme">
        </div>
        <div class="form-group mb-2">
          <label for="image" style="display: block;">Imagem: </label>
          <input accept="image/*" type="file" class="form-control-file" name="image" id="image">
        </div>
        <div class="form-group mb-2">
          <label for="length">Duração: </label>
          <input type="text" class="form-control" id="length" name="length" placeholder="Digite a duração do filme">
        </div>
        <div class="form-group mb-2">
          <label for="category">Categoria: </label>
          <select name="category" id="category" class="form-control">
            <option value="">Selecione</option>
            <option value="Ação">Ação</option>
            <option value="Drama">Drama</option>
            <option value="Comedia">Comédia</option>
            <option value="Fantasia">Fantasia</option>
            <option value="Romance">Romance</option>
          </select>
        </div>
        <div class="form-group mb-2">
          <label for="trailer">Trailer: </label>
          <input type="text" class="form-control" id="trailer" name="trailer" placeholder="Insira o link do trailer">
        </div>
        <div class="form-group mb-2">
          <label for="description">Descrição: </label>
          <textarea class="form-control" rows="5" id="description" name="description" placeholder="Descreva o filme..."></textarea>
        </div>
      <input type="submit" class="btn card-btn" value="Adicionar Filme">
      </form>
    </div>
  </div>
<?php
  require_once('templates/footer.php');
?>