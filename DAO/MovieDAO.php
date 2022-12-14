<?php

require_once('models/Movie.php');
require_once('models/Message.php');

//Review DAO
require_once('DAO/ReviewDAO.php');


class MovieDao implements MovieDAOinterface
{

  private $conn;
  private $url;
  private $message;

  public function __construct(PDO $conn, $url)
  {
    $this->conn = $conn;
    $this->url = $url;
    $this->message = new Message($url);
  }



  public function buildMovie($data){

     
    $movie = new Movie();

    $movie->setId($data["id"]);
    $movie->setTitle($data["title"]);
    $movie->setDescription($data["description"]);
    $movie->setImage($data["image"]);
    $movie->setTrailer($data["trailer"]);
    $movie->setCategory($data["category"]);
    $movie->setLength($data["length"]);
    $movie->setUsers_id($data["users_id"]);


    //recebe as ratings
    $reviewDao = new ReviewDAO($this->conn, $this->url);
    $rating =  $reviewDao->getRatings($movie->getId());

    $movie->rating = $rating;

    return $movie;
  }

  public function findAll(){

  }

  public function getLatestMovies(){

    $movies = [];

    $stmt = $this->conn->query("SELECT * FROM movies ORDER BY id DESC");

    $stmt->execute();

    if($stmt->rowCount() > 0) {

      $moviesArray = $stmt->fetchAll();

      foreach($moviesArray as $movie) {
        $movies[] = $this->buildMovie($movie);
      }

    }

    return $movies;
  }

  public function getMoviesByCategory($category){

    $movies = [];

      $stmt = $this->conn->prepare("SELECT * FROM movies
                                    WHERE category = :category
                                    ORDER BY id DESC");

      $stmt->bindParam(":category", $category);

      $stmt->execute();

      if($stmt->rowCount() > 0) {

        $moviesArray = $stmt->fetchAll();

        foreach($moviesArray as $movie) {
          $movies[] = $this->buildMovie($movie);
        }

      }

      return $movies;


  }

  public function getMoviesByUserId($id){

    $movies = [];

      $stmt = $this->conn->prepare("SELECT * FROM movies
                                    WHERE users_id = :users_id");

      $stmt->bindParam(":users_id", $id);

      $stmt->execute();

      if($stmt->rowCount() > 0) {

        $moviesArray = $stmt->fetchAll();

        foreach($moviesArray as $movie) {
          $movies[] = $this->buildMovie($movie);
        }

      }

      return $movies;

    
  }

  public function findById($id){

    $movie = [];

    $stmt = $this->conn->prepare("SELECT * FROM movies
                                  WHERE id = :id");

    $stmt->bindParam(":id", $id);

    $stmt->execute();

    if($stmt->rowCount() > 0) {

      $movieData = $stmt->fetch();

      $movie = $this->buildMovie($movieData);

      return $movie;
    }
    else{
      return false;
    }
  }

  public function findByTitle($title){

   

    $movies = [];

    $stmt = $this->conn->prepare("SELECT * FROM movies
                                  WHERE title like :title");

    // $titleF = '%'.$title.'%';
    // $stmt->bindParam(":title", $titleF);

    $stmt->bindValue(":title",'%'.$title.'%');

    $stmt->execute();

    if($stmt->rowCount() > 0) {

      $moviesArray = $stmt->fetchAll();

      foreach($moviesArray as $movie) {
        $movies[] = $this->buildMovie($movie);
      }

    }

    return $movies;


  }

  public function create(Movie $movie){

    if($movie){

      $title = $movie->getTitle();
      $description = $movie->getDescription();
      $image = $movie->getImage();
      $trailer = $movie->getTrailer();
      $category = $movie->getCategory();
      $length = $movie->getLength();
      $users_id = $movie->getUsers_id();

    $stmt = $this->conn->prepare("INSERT INTO movies (
        title, description, image, trailer, category, length, users_id) VALUES
        (:title, :description, :image, :trailer, :category, :length , :users_id)");

      $stmt->bindParam(":title", $title);
      $stmt->bindParam(":description", $description);
      $stmt->bindParam(":image", $image);
      $stmt->bindParam(":trailer", $trailer);
      $stmt->bindParam(":category", $category);
      $stmt->bindParam(":length", $length);
      $stmt->bindParam(":users_id", $users_id);
      
      $stmt->execute();
      
    //Redireciona pra home 
    $this->message->setMessage("Filme adicionado com sucesso!", "success", "/index.php");
    
    }

  }

  public function update(Movie $movie){

    if ($movie) {

      $title = $movie->getTitle();
      $description = $movie->getDescription();
      $image = $movie->getImage();
      $trailer = $movie->getTrailer();
      $category = $movie->getCategory();
      $length = $movie->getLength();
      $id = $movie->getId();

      $stmt = $this->conn->prepare("UPDATE movies SET
        title = :title,
        description = :description,
        image = :image,
        category = :category,
        trailer = :trailer,
        length = :length 
          WHERE id = :id");

      $stmt->bindParam(":title", $title);
      $stmt->bindParam(":description", $description);
      $stmt->bindParam(":image", $image);
      $stmt->bindParam(":trailer", $trailer);
      $stmt->bindParam(":category", $category);
      $stmt->bindParam(":length", $length);
      $stmt->bindParam(":id", $id);
      
      $stmt->execute();
 
      $this->message->setMessage("Filme atualizado com sucesso!", "success", "back");
    
    }

  }

  public function destroy($id){

    if ($id) {

      $stmt = $this->conn->prepare("DELETE FROM movies WHERE id = :id");

      $stmt->bindParam(":id", $id);

      $stmt->execute();

      // Sucesso ao remover filme
      $this->message->setMessage("Filme removido com sucesso!", "success", "/dashboard.php");

    }

  }

}