<?php
class User
{
    public $name;
    public $email;
    public $password;
    public $user_id;
    public $project_name;
    public $project_description;
    public $project_status;

    private $conn;
    private $tbl_users;
    private $tbl_projects;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->tbl_users = 'users';
        $this->tbl_projects = 'projects';
    }

    public function createUser()
    {
      $query = "insert into ". $this->tbl_users ." (name, email, password) values (?, ?, ?)";
      $statement = $this->conn->prepare($query);
      $statement->bind_param("sss", $this->name, $this->email, $this->password);
      if ($statement->execute()) {
          return true;
      }
      else
      {
        return false;
      }
    }

    public function existUser()
    {
        $query = 'select name,email,password from '. $this->tbl_users .' where email = ?';
        $statement = $this->conn->prepare($query);
        $statement->bind_param('s', $this->email);
        if ($statement->execute())
        {
            $result = $statement->get_result();
            $data = $result->fetch_assoc();
            return $data;
        }
        return array();
    }
}