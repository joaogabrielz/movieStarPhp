<?php

require_once('models/User.php');
require_once('models/Message.php');

class UserDao implements UserDAOinterface {


  private $conn;
  private $url;
  private $message;


  public function __construct(PDO $conn, $url)
  {
    $this->conn = $conn;
    $this->url = $url;
    $this->message = new Message($url);
  }



  public function buildUser($data){

    // $user = new User($data['id'], $data['name'], $data['lastname'], $data['email'], 
    //                  $data['password'], $data['image'], $data['bio'], $data['token']);
    
    $user = new User();

    $user->setId($data["id"]);
    $user->setName($data["name"]);
    $user->setLastname($data["lastname"]);
    $user->setEmail($data["email"]);
    $user->setPassword($data["password"]);
    $user->setImage($data["image"]);
    $user->setBio($data["bio"]);
    $user->setToken($data["token"]);

    return $user;
  }

  public function create(User $user, $authUser = false){

    if($user){

      $name = $user->getName();
      $lastname = $user->getLastname();
      $email = $user->getEmail();
      $password = $user->getPassword();
      $token = $user->getToken();

    $stmt = $this->conn->prepare("INSERT INTO users (
        name, lastname, email, password, token) VALUES
        (:name, :lastname, :email, :password, :token)");

      $stmt->bindParam(":name", $name);
      $stmt->bindParam(":lastname", $lastname);
      $stmt->bindParam(":email", $email);
      $stmt->bindParam(":password", $password);
      $stmt->bindParam(":token", $token);
      
      $stmt->execute();
      
      //AUTENTICAR USUARIO Caso AuthUser == True
      if($authUser){
        $this->setTokenToSession($user->getToken());
      }  
    }
  // return false;

  }

  public function update(User $user, $redirect = true){

  $name = $user->getName();
  $lastname = $user->getLastname();
  $email = $user->getEmail();
  $image = $user->getImage();
  $bio = $user->getBio();
  $token = $user->getToken();
  $id = $user->getId();

  $stmt = $this->conn->prepare("UPDATE users SET 
  name = :name, lastname = :lastname, email = :email, 
  image = :image, bio = :bio, token = :token 
  WHERE id = :id");

    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":lastname", $lastname);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":image", $image);
    $stmt->bindParam(":bio", $bio);
    $stmt->bindParam(":token", $token);
    $stmt->bindParam(":id", $id);
    
    $stmt->execute();
    
    if($redirect){
      // redireciona para o perfil do Usuario
      $this->message->setMessage("Dados atualizados com suceso!", "success", "/editProfile.php");
    }
  }


  public function verifyToken($protected = false){

  if(!empty($_SESSION['token'])){

      //pega token da sessao
      $token = $_SESSION['token'];

    $user = $this->findByToken($token);

    if($user){
      return $user;
    }
    else if ($protected){ //true = usuario nao autenticado

      // redireciona Usuario nao autenticado
      $this->message->setMessage("Faça a autenticação para acessar essa página", "error", "/index.php");
    } 
  }
  else if ($protected){ 
    // redireciona Usuario nao autenticado
    $this->message->setMessage("Faça a autenticação para acessar essa página", "error", "/index.php");
  } 
  }

  public function setTokenToSession($token, $redirect = true){

    // Salvar token na sessao
    $_SESSION['token'] = $token;
    
    if($redirect){
      // redireciona para o perfil do Usuario
      $this->message->setMessage("Seja Bem vindo", "success", "/editProfile.php");
    }
  }

  public function authenticateUser($email, $password){

    $user = $this->findByEmail($email);
    if($user){
      // verificar senhas batem
      if(password_verify($password, $user->getPassword())){

        //Gerar um token e inserir na session
        $token = $user->generateToken();
        $this->setTokenToSession($token, false);

        // atualizar token do usuario
        $user->setToken($token);
        $this->update($user, false);

        return true;
      }
    return false;
    }
  return false;
  }

  public function findByEmail($email){

    if($email){

      $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
      $stmt->bindParam(":email", $email);
      $stmt->execute();

      if($stmt->rowCount() > 0){

        $data = $stmt->fetch();
        $user = $this->buildUser($data);

        return $user;
      }

    return false;
    }
  return false;

  }
  public function findById($id){

    if($id != "") {

      $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id");

      $stmt->bindParam(":id", $id);

      $stmt->execute();

      if($stmt->rowCount() > 0) {

        $data = $stmt->fetch();
        $user = $this->buildUser($data);
        
        return $user;

      } else {
        return false;
      }

    } else {
      return false;
    }
    
  }
  public function findByToken($token){

    if($token){

      $stmt = $this->conn->prepare("SELECT * FROM users WHERE token = :token");
      $stmt->bindParam(":token", $token);
      $stmt->execute();

      if($stmt->rowCount() > 0){

        $data = $stmt->fetch();
        $user = $this->buildUser($data);
        return $user;
      }

    return false;
    }
  return false;

  }

  public function destroyToken(){

    //Remove token da SESSION
    $_SESSION['token'] = "";

    //redirecionar e mostrat msg de sucesso;
    $this->message->setMessage("Você fez logout com sucesso", "success", "/index.php");
  }

  public function changePassword(User $user){

    $id = $user->getId();
    $password = $user->getPassword();

    $stmt = $this->conn->prepare("UPDATE users SET
    password = :password WHERE id = :id");

    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":password", $password);

    $stmt->execute();

 //redirecionar e mostrat msg de sucesso;
    $this->message->setMessage("Senha alterada com sucesso", "success", "/editProfile.php");
  }


}