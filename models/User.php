<?php

class User
{

  private $id;
  private $name;
  private $lastname;
  private $email;
  private $password;
  private $image;
  private $bio;
  private $token;


// public function __construct($id, $name, $lastname, $email, $password, $image, $bio, $token) {
//   $this->id = $id;
//   $this->name = $name;
//   $this->lastname = $lastname;
//   $this->email = $email;
//   $this->password = $password;
//   $this->image = $image;
//   $this->bio = $bio;
//   $this->token = $token;
// }


public function generateToken(){
  return bin2hex(random_bytes(50));
}

public function generatePassword($password){
  return password_hash($password, PASSWORD_DEFAULT);
}

public function imageGenerateName(){
  return bin2hex(random_bytes(60)) . ".jpg";
}




public function getId() {
	return $this->id;
}

public function setId($id){
	$this->id = $id;
}


public function getName() {
	return $this->name;
}
public function setName($name){
	$this->name = $name;
}


public function getLastname() {
	return $this->lastname;
}
public function setLastname($lastname){
	$this->lastname = $lastname;
}



public function getEmail() {
	return $this->email;
}
public function setEmail($email){
	$this->email = $email;
}



public function getPassword() {
	return $this->password;
}
public function setPassword($password){
	$this->password = $password;
}



public function getImage() {
	return $this->image;
}
public function setImage($image){
	$this->image = $image;
}


public function getBio() {
	return $this->bio;
}
public function setBio($bio){
	$this->bio = $bio;
}


public function getFullName($user){
  return $user->name . " " . $user->lastname;
}


public function getToken() {
	return $this->token;
}
public function setToken($token){
	$this->token = $token;
}

}


interface UserDAOinterface {

  public function buildUser($data);

  public function create(User $user, $authUser = false);

  public function update(User $user,  $redirect = true);

  public function verifyToken($protected = false);
  public function setTokenToSession($token, $redirect = true);
  public function authenticateUser($email, $password);
  public function findByEmail($email);
  public function findById($id);
  public function findByToken($token);
  public function changePassword(User $user);
  public function destroyToken();

}