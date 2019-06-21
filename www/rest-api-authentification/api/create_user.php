<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost:1234/pfe/projetpfe/CrowFundingVegaNet/www/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
     // database connection

// files needed to connect to database
include_once 'config/database.php';
include_once 'objects/User.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate product object
$user = new User($db);
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// set product property values
$user->nom = $data->nom;
$user->prenom = $data->prenom;
$user->email = $data->email;
$user->password = $data->password;
$user->num_tel = "+21625874798";
$user->lieuResidence = $data->lieuResidence;
$user->dateNaissance = $date->dateNaissance;

// create the user
if(
    !empty($user->nom) &&
    !empty($user->email) &&
    !empty($user->password) &&
    $user->create()
){
 
    // set response code
    http_response_code(200);
 
    // display message: user was created
    echo json_encode(array("message" => "votre compte à était bien enregistrer."));
}
 
// message if unable to create user
else{
 
    // set response code
    http_response_code(400);
 
    // display message: unable to create user
    echo json_encode(array("message" => "impossible de créer ce utilisateur."));
}
?>