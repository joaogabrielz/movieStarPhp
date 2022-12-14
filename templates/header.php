<?php
  require_once('globals.php');
  require_once('db.php');
  require_once('models/Message.php');
  require_once('DAO/UserDAO.php');

  $message = new Message($BASE_URL);

  $flassMessage = $message->getMessage();

  if(!empty($flassMessage['msg'])){
    // Limpar msg
    $message->clearMessage();
  }

$userDao = new UserDAO($conn, $BASE_URL);

$userData = $userDao->verifyToken(false);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MovieStar</title>
  <link rel="short icon" href="<?= $BASE_URL ?>/img/movestar.ico">
  <!-- bootstrap -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.3/css/bootstrap.css" integrity="sha512-bR79Bg78Wmn33N5nvkEyg66hNg+xF/Q8NA8YABbj+4sBngYhv9P8eum19hdjYcY7vXk/vRkhM3v/ZndtgEXRWw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- fontawesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- css --> 
  <link rel="stylesheet" href="<?= $BASE_URL ?>/css/styles.css">
</head>
<body>
  <header>
    <nav id="main-navbar" class="navbar navbar-expand-lg">
      <a href="<?= $BASE_URL ?>" class="navbar-brand">
        <img src="<?= $BASE_URL ?>/img/logo.svg" alt="movieStar" id="logo">
        <span id="moviestar-title">MovieStar</span>
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
      </button>
      <form action="<?= $BASE_URL ?>/search.php?" method="GET" id="search-form" class="form-inline my-2 my-lg-0">
        <input type="search" name="q" id="search" class="d-inline form-control mr-sm-2" 
        placeholder="Buscar filmes" aria-label="Search">
        <button class="btn my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button>
      </form>
      <div class="collapse navbar-collapse" id="navbar">
        <ul class="navbar-nav">

          <?php if($userData): ?>
            <li class="nav-item">
              <a href="<?= $BASE_URL ?>/newMovie.php" class="nav-link">
                <i class="far fa-plus-square"></i> Incluir Filme
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= $BASE_URL ?>/dashboard.php" class="nav-link">Meus Filmes</a>
            </li>
            <li class="nav-item">
              <a href="<?= $BASE_URL ?>/editProfile.php" class="nav-link bold">
                <?= $userData->getName() ?>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= $BASE_URL ?>/logout.php" class="nav-link">Sair</a>
            </li>

          <?php else: ?>
            <li class="nav-item">
              <a href="<?= $BASE_URL ?>/auth.php" class="nav-link">Entrar / Cadastrar</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </nav>
  </header>
  <?php if(!empty($flassMessage['msg'])): ?>
    <div class="msg-container">
      <p class="msg <?= $flassMessage['type'] ?>"> <?= $flassMessage['msg'] ?> </p>
    </div>
  <?php endif; ?>