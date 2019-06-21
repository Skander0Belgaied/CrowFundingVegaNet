<?php
  
// 'user' object
class User{

    // database connection and table name
    private $conn;
    private $table_name = "User";
 
    // object properties
    public $idUser;
    public $nom;
    public $prenom;
    public $email;
    public $password;
    public $imgUrl;
    public $num_tel;
   public $lieuResidence;
   public $dateNaissance;
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
 
// create new user record
            function create(){
            
                // insert query
                $query = "INSERT INTO  " . $this->table_name . "
                        SET
                            nom = :nom,
                            prenom = :prenom,
                            email = :email,
                            password = :password,
                            num_tel = :num_tel,
                            lieuResidence = :lieuResidence,
                            dateNaissance = :dateNaissance
                            ";
            
                // prepare the query
                $stmt = $this->conn->prepare($query);
            
                // sanitize
                $this->nom=htmlspecialchars(strip_tags($this->nom));
                $this->prenom=htmlspecialchars(strip_tags($this->prenom));
                $this->email=htmlspecialchars(strip_tags($this->email));
                $this->password=htmlspecialchars(strip_tags($this->password));
                $this->lieuResidence=htmlspecialchars(strip_tags($this->lieuResidence));
                $this->dateNaissance=htmlspecialchars(strip_tags($this->dateNaissance));
            
                // bind the values
                $stmt->bindParam(':nom', $this->nom);
                $stmt->bindParam(':prenom', $this->prenom);
                $stmt->bindParam(':email', $this->email);
                $stmt->bindParam(':num_tel', $this->num_tel);
                $stmt->bindParam(':lieuResidence', $this->lieuResidence);
                $stmt->bindParam(':dateNaissance', $this->num_tel);
            
                // hash the password before saving to database
                $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
                $stmt->bindParam(':password', $password_hash);
            
                // execute the query, also check if query was successful
                if($stmt->execute()){
                    return true;
                }
            
                return false;
            }



// check if given email exist in the database
function emailExists(){
 
    // query to check if email exists
    $query = "SELECT idUser, nom, prenom, password, num_tel,lieuResidence, dateNaissance
            FROM " . $this->table_name . "
            WHERE email = ?
            LIMIT 0,1";
 
    // prepare the query
    $stmt = $this->conn->prepare( $query );
 
    // sanitize
    $this->email=htmlspecialchars(strip_tags($this->email));
 
    // bind given email value
    $stmt->bindParam(1, $this->email);
 
    // execute the query
    $stmt->execute();
 
    // get number of rows
    $num = $stmt->rowCount();
 
    // if email exists, assign values to object properties for easy access and use for php sessions
    if($num>0){
 
        // get record details / values
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
        // assign values to object properties
        $this->idUser = $row['idUser'];
        $this->nom = $row['nom'];
        $this->prenom = $row['prenom'];
        $this->password = $row['password'];
        $this->num_tel = $row['num_tel'];
        $this->lieuResidence = $row['lieuResidence'];
        $this->numdateNaissance_tel = $row['dateNaissance'];
        // return true because email exists in the database
        return true;
    }
 
    // return false if email does not exist in the database
    return false;
}
 


// update a user record
public function update(){
 
    // if password needs to be updated
    $password_set=!empty($this->password) ? ", password = :password" : "";
 
    // if no posted password, do not update the password
    $query = "UPDATE " . $this->table_name . "
            SET
                nom = :nom,
                prenom = :prenom,
                email = :email
                {$password_set}
            WHERE idUser = :idUser";
 
    // prepare the query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->nom=htmlspecialchars(strip_tags($this->nom));
    $this->prenom=htmlspecialchars(strip_tags($this->prenom));
    $this->email=htmlspecialchars(strip_tags($this->email));
 
    // bind the values from the form
    $stmt->bindParam(':nom', $this->nom);
    $stmt->bindParam(':prenom', $this->prenom);
    $stmt->bindParam(':email', $this->email);
 
    // hash the password before saving to database
    if(!empty($this->password)){
        $this->password=htmlspecialchars(strip_tags($this->password));
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);
    }
 
    // unique ID of record to be edited
    $stmt->bindParam(':idUser', $this->idUser);
 
    // execute the query
    if($stmt->execute()){
        return true;
    }
 
    return false;
}


}
?>