<?php
require_once '/../dataBase/Connect.php';

class Disponibilite{
    private $id; 
    private $idcoach;
    private $date;
    private $heure_debut;
    private $heure_fin;
    private $disponible;

    private $pdo;

    public function __construct(){
        $this->pdo=DataBase::connect();
    }

    public function getIdcoach(){return $this->idcoach;}
    // public function setidcoach(int $idcoach){$this->idcoach=$idcoach;}

    public function getId(){return $this->id;}

    public function getDate(){return $this->date;}
    public function setDate($date){$this->date=$date;}

    public function getHeure_debut(){return $this->heure_debut;}
    public function setHeure_debut($heure_debut){$this->heure_debut=$heure_debut;}

    public function getHeure_fin(){return $this->heure_fin;}
    public function setHeure_fin($heure_fin){$this->heure_fin=$heure_fin;}

    public function getDisponible(){return $this->disponible;}
    public function setDisponible($disponible){$this->disponible=$disponible;}


    // id du coach
    public function AjouterDispo(){
        $req1 = $this->pdo->prepare("INSERT INTO disponibilite (idcoach, date, heure_debut, heure_fin) VALUES(?)");
        $req1->execute([
            $this->date,
            $this->heure_debut,
            $this->heure_fin,
        ]);
        $idDispo = $this->pdo->lastInsertId();
        return $idDispo;
    }
    public function ModifierDispo(int $idCoach){
        $req2 = $this->pdo->prepare("UPDATE disponibilite set date=?, heure_debut=?, heure_fin=? where idcoach=?");
        $req2->execute([
            $this->date,
            $this->heure_debut,
            $this->heure_fin,
            $idCoach
        ]);

    }
    // les seance disponible
    public function affichierDispo(){
        $reqSelect=$this->pdo->prepare("SELECT * from disponibilite where disponible=1");
        $reqSelect->execute([]);
        // return
    }
    public function supprimerDispo($id){
        $reqDelete=$this->pdo->prepare("DELETE from disponibilite where id=?");
        $reqDelete->execute([
            $id
        ]);
    }

    public function ModifierStatusDispo($id){
        $reqDelete=$this->pdo->prepare("UPDATE disponibilite set disponible = 0 where id=?");
        $reqDelete->execute([
            $id
        ]);
    }




}



?>