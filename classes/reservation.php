<?php
require_once '/../dataBase/Connect.php';

class Reservation{
    /* 
    
    reservation (id_client, id_coach, id_disponibilite, heure_debut, heure_fin, objectif, date, status)
    */
    private $id; 
    private $id_client;
    private $id_coach;
    private $id_disponibilite;

    private $date;
    private $heure_debut;
    private $heure_fin;
    private $objectif;
    private $status;

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

    public function getObjectif(){return $this->objectif;}
    public function setObjectif($objectif){$this->objectif=$objectif;}

    public function getStatus(){return $this->status;}
    public function setStatus($status){$this->status=$status;}

    // id du coach
    public function AjouterReservation($id_client,$id_coach,$id_disponibilite){
        $req1 = $this->pdo->prepare("INSERT INTO reservation (id_client, id_coach, id_disponibilite, heure_debut, heure_fin, objectif, date) 
        VALUES(?,?,?,?,?,?,?,?)");
        $req1->execute([
            $id_client,
            $id_coach,
            $id_disponibilite,

            $this->heure_debut,
            $this->heure_fin,
            $this->objectif,
            $this->date,

            // $this->status,
        ]);
        $idDispo = $this->pdo->lastInsertId();
        return $idDispo;
    }

    public function ModifierReservation(int $idClient){
        $req2 = $this->pdo->prepare("UPDATE reservation set  heure_debut=?, heure_fin=?, objectif=?, date=?  where id_client=?");
        $req2->execute([

            $this->heure_debut,
            $this->heure_fin,
            $this->objectif,
            $this->date,

            $idClient
        ]);

    }
    public function affichierReservation(){
        $reqSelect=$this->pdo->prepare("SELECT * from reservation");
        $reqSelect->execute([]);
    }
    public function supprimerReservation($id){
        $reqDelete=$this->pdo->prepare("DELETE from reservation where id=?");
        $reqDelete->execute([
            $id
        ]);
    }


}



?>