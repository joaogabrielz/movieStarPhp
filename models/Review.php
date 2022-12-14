<?php

class Review
{

  private $id;
  private $rating;
  private $review;
  private $users_id;
  private $movies_id;



	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
	}
	

	public function getRating() {
		return $this->rating;
	}
	public function setRating($rating){
		$this->rating = $rating;
	}
	

	public function getReview() {
		return $this->review;
	}
	public function setReview($review){
		$this->review = $review;
	}


	public function getUsers_id() {
		return $this->users_id;
	}
	public function setUsers_id($users_id) {
		$this->users_id = $users_id;
	}
	

	public function getMovies_id() {
		return $this->movies_id;
	}
	public function setMovies_id($movies_id){
		$this->movies_id = $movies_id;
	}
}

interface ReviewDAOInterface{

  public function buildReview($data);
  public function create(Review $review);
  public function getMoviesReview($id);
  public function hasAlreadyReviewed($id, $userId);
  public function getRatings($id);
}