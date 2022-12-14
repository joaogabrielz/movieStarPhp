<?php

class Movie {

  private $id;
  private $title;
  private $description;
  private $image;
  private $trailer;
  private $category;
  private $length;
  private $users_id;
  


  public function imageGenerateName(){
    return bin2hex(random_bytes(60)) . ".jpg";
  }


	public function getId() {
		return $this->id;
	}
	public function setId($id){
		return $this->id = $id;
	}
	

	public function getTitle() {
		return $this->title;
	}
	public function setTitle($title){
		return $this->title = $title;
	}
	


	public function getDescription() {
		return $this->description;
	}
	public function setDescription($description){
		return $this->description = $description;
	}
	

	public function getImage() {
		return $this->image;
	}
	public function setImage($image){
		$this->image = $image;
	}
	

	public function getTrailer() {
		return $this->trailer;
	}
	public function setTrailer($trailer){
		$this->trailer = $trailer;
	}
	

	public function getCategory() {
		return $this->category;
	}
	public function setCategory($category){
		$this->category = $category;
	}
	

	public function getLength() {
		return $this->length;
	}
	public function setLength($length){
		$this->length = $length;
	}
	

	public function getUsers_id() {
		return $this->users_id;
	}
	public function setUsers_id($users_id){
		$this->users_id = $users_id;
	}
}


interface MovieDAOinterface {

  public function buildMovie($data);

  public function findAll();

	public function getLatestMovies();

  public function getMoviesByCategory($category);

  public function getMoviesByUserId($id);

  public function findById($id);

  public function findByTitle($title);

  public function create(Movie $movie);

  public function update(Movie $movie);

  public function destroy($id);
}