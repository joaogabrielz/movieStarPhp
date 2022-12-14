<?php

require_once("models/User.php");

$userModel = new User();

$fullName = $userModel->getFullName($review->user);

//Checar se filme tem imagem 
if($review->user->getImage() == ""){
  $review->user->setImage("user.png");
}

?>

<div class="col-md-12 review">
        <div class="row">
          <div class="col-md-1">
            <div class="profile-image-container review-image" 
            style="background-image: url('<?= $BASE_URL ?>/img/users/<?= $review->user->getImage() ?>');"></div>         
          </div>
          <div class="col-md-9 author-details-container">
            <h4 class="author-name">
              <a href="<?= $BASE_URL ?>/profile.php?<?= $review->user->getId() ?>"><?= $fullName ?></a>
            </h4>
            <p><i class="fas fa-star"></i> <?= $review->getRating() ?></p>
          </div>
          <div class="col-md-12">
            <p class="comment-title">Comentário: </p>
            <p><?= $review->getReview() ?></p>
          </div>
        </div>
  </div>