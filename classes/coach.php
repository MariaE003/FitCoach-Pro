<?php
require_once '../dataBase/connect.php';
require_once './user.php';
class Coach extends User{
    private int  $id;
    private string  $nom;
    private string  $prenom;
    private string  $annee_experience;
    private string  $Bio;
    private float   $prix;
    private string  $photo;
    private string  $telephone;
    private string  $specialite;
    private int $id_user;

    // pour la connexion avec db
    private $pdo;

    function __construct(){
        parent::__construct();
        $this->pdo=DataBase::connect();
        $this->id_user=$id_user;
        $this->id=$id;
        // $this->nom=$nom; => en met ca dans les setter
    }
}
?>