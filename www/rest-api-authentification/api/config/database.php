<?php
// used to get mysql database connection
class Database{
 
    // specify your own database credentials
    private $host = "51.83.58.188";
    private $db_name = "group5";
    private $username = "group5";
    private $password = "group5";
    public $conn;

    // get the database connection
    public function getConnection(){

        $this->conn = null;

        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
        }catch(PDOException $exception){
            echo "Connexion échouée :  " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>