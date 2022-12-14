<?php
  require_once('templates/header.php');

  require_once('models/User.php');
  require_once('DAO/UserDAO.php');

  $userDao = new UserDAO($conn, $BASE_URL);
  $userData = $userDao->verifyToken(true); // requerindo auth na pag

  $user = new User();
  $fullname = $user->getFullName($userData);

  if($userData->getImage() == ""){
    $userData->setImage("user.png");
  }
?>
  <div id="main-container" class="container-fluid edit-profile-page">
    <div class="col-md-12">

    <form action="<?= $BASE_URL ?>/user_process.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="type" value="update">
        <div class="row">
          <div class="col-md-4">
            <h1> <?= $fullname ?> </h1>
            <p class="page-description">Altere seus dados no formulario abaixo: </p>
            <div class="form-group mb-2">
              <label for="name">Nome: </label>
              <input type="text" class="form-control" id="name" name="name"
               placeholder="Digite o seu nome" value="<?= $userData->getName() ?>">
            </div>
            <div class="form-group mb-2">
              <label for="lastname">Sobrenome: </label>
              <input type="text" class="form-control" id="lastname" name="lastname"
               placeholder="Digite o seu sobrenome" value="<?= $userData->getLastname() ?>">
            </div>
            <div class="form-group mb-2">
              <label for="email">Email: </label>
              <input type="text" readonly class="form-control disabled" id="email" name="email"
               placeholder="Digite seu email" value="<?= $userData->getEmail() ?>">
            </div>
            <input type="submit" class="btn btn card-btn" value="Alterar">
          </div>

          <div class="col-md-4">
            <div id="profile-image-container" 
            style="background-image: url('<?= $BASE_URL ?>/img/users/<?= $userData->getImage() ?>');"></div>
            <div class="form-group mb-2">
              <label for="image">Foto: </label>
              <input accept="image/*" type="file" class="form-control-file" id="image" name="image">
            </div>
            <div class="form-group mb-2">
              <label for="bio">Sobre você: </label>
              <textarea class="form-control" type="text" name="bio" id="bio" rows="5" 
              placeholder="Conte quem você é, oque faz e onde trabalha..."><?= $userData->getBio() ?></textarea>
            </div>
          </div>
        </div>
      </form>

      <div class="row" id="change-password-container">
        <div class="col-md-4">
          <h2>Alterar a senha: </h2>
          <p class="page-description">
            Digite a nova senha e confirme, para alterar
          </p>
        <form action="<?= $BASE_URL ?>/user_process.php" method="POST">
          <input type="hidden" name="type" value="changepassword">
          <input type="hidden" name="id" value="<?= $userData->getId() ?>">
            <div class="form-group mb-2">
              <label for="password">Senha: </label>
              <input type="password" class="form-control" id="password" name="password"
                placeholder="Digite sua senha">
            </div>
            <div class="form-group mb-2">
              <label for="confirmpassword">Confirmação de senha: </label>
              <input type="password" class="form-control" id="confirmpassword" name="confirmpassword"
                placeholder="Confirme sua nova senha">
            </div>
          <input type="submit" class="btn btn card-btn" value="Alterar Senha">
        </form>
        </div>
      </div>
    </div>
  </div>
<?php
  require_once('templates/footer.php');
?>