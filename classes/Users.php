<?php

class Users{

  // define properties
  public $name;
  public $email;
  public $password;
  public $user_id;
  public $project_name;
  public $description;
  public $status;

  private $conn;
  private $users_tbl;
  private $projects_tbl;

  public function __construct($db){
     $this->conn = $db;
     $this->users_tbl = "tbl_users";
     $this->projects_tbl = "tbl_projects";
  }

  public function create_user(){

    $user_query = "INSERT INTO ".$this->users_tbl." SET name = ?, email = ?, password = ?";

    $user_obj = $this->conn->prepare($user_query);

    $user_obj->bind_param("sss", $this->name, $this->email, $this->password);

    if($user_obj->execute()){
      return true;
    }

    return false;
  }

  public function check_email(){

    $email_query = "SELECT * from ".$this->users_tbl." WHERE email = ?";

    $usr_obj = $this->conn->prepare($email_query);

    $usr_obj->bind_param("s", $this->email);

    if($usr_obj->execute()){

       $data = $usr_obj->get_result();

       return $data->fetch_assoc();
    }

    return array();
  }

  public function check_login(){

    $email_query = "SELECT * from ".$this->users_tbl." WHERE email = ?";

    $usr_obj = $this->conn->prepare($email_query);

    $usr_obj->bind_param("s", $this->email);

    if($usr_obj->execute()){

       $data = $usr_obj->get_result();

       return $data->fetch_assoc();
    }

    return array();
  }

  // to create projects
  public function create_project(){

      $project_query = "INSERT into ".$this->projects_tbl." SET user_id = ?, name = ?, description = ?, status = ?";

      $project_obj = $this->conn->prepare($project_query);
      // sanitize input variables
      $project_name = htmlspecialchars(strip_tags($this->project_name));
      $description = htmlspecialchars(strip_tags($this->description));
      $status = htmlspecialchars(strip_tags($this->status));
      // bind parameters
      $project_obj->bind_param("isss", $this->user_id, $project_name, $description, $status);

      if($project_obj->execute()){
        return true;
      }

      return false;

  }

  // used to list all projects
  public function get_all_projects(){

    $project_query = "SELECT * from ".$this->projects_tbl." ORDER BY id DESC";

    $project_obj = $this->conn->prepare($project_query);

    $project_obj->execute();

    return $project_obj->get_result();

  }

  public function get_user_all_projects(){

    $project_query = "SELECT * from ".$this->projects_tbl." WHERE user_id = ? ORDER BY id DESC";

    $project_obj = $this->conn->prepare($project_query);

    $project_obj->bind_param("i", $this->user_id);

    $project_obj->execute();

    return $project_obj->get_result();

  }
}

 ?>
