<?php
require_once '../dataBase/connect.php';
require_once  __DIR__ .'/User.php';

class Coach extends User{
    // private int  $id;
    private string  $nom='';
    private string  $prenom='';
    private int  $annee_experience = 0;
    private string  $bio='';
    private float   $prix=0;
    private string  $photo='';
    private string  $telephone='';
    private array  $specialite=[];
    private array $certif=[];
    // certif
    // private int $id_user;
    private int $coach_id;
    // pour la connexion avec db
    // private $pdo;

    function __construct(){
        parent::__construct();
    }

    public function getNom(){return $this->nom;}
    public function setNom(string $nom){$this->nom=$nom;}

    public function getPrenom(){ return $this->prenom; }
    public function setPrenom(string $prenom){ $this->prenom = $prenom; }

    public function getAnneeExperience(){ return $this->annee_experience; }
    public function setAnneeExperience(int $annee){ $this->annee_experience = $annee; }

    public function getBio(){ return $this->bio; }
    public function setBio(string $bio){ $this->bio = $bio;}

    public function getPrix(){ return $this->prix; }
    public function setPrix(float $prix){ $this->prix = $prix; }

    public function getPhoto(){ return $this->photo; }
    public function setPhoto(string $photo){ $this->photo = $photo; }

    public function getTelephone(){ return $this->telephone; }
    public function setTelephone(string $tel){ $this->telephone = $tel; }

    public function getSpecialite(){ return $this->specialite; }
    public function setSpecialite(string $spec){ $this->specialite[] = $spec; }

    public function getcertif(){ return $this->certif; }
    public function setcertif(array $certif){ $this->certif[] = $certif; }

    public function registerCoach(int $idUser){
        $this->role='coach';
        // if ($this->register()) { //un erreur du id dial user li tinsera mkich
           $req=$this->pdo->prepare("INSERT into coach(id_user, nom, prenom, telephone, experience_en_annee,bio, photo,prix) values(?,?,?,?,?,?,?,?)");
           $req->execute([
            // $this->id,
            $idUser,
            $this->nom,
            $this->prenom,
            $this->telephone,
            $this->annee_experience,
            $this->bio,
            $this->photo,
            $this->prix,
           ]);
           // return false;
           $this->coach_id=$this->pdo->lastInsertId();
           // $this->coach_id=$coach_id;
           return true;
        // }
        // return false;
    }
    public function leCoachConne(int $id){
        $req=$this->pdo->prepare("SELECT * FROM coach  WHERE id_user=?");
        $req->execute([
            $id
        ]);
        $test=$req->fetch(PDO::FETCH_ASSOC);
        // echo "dxcf";
        if ($test) {
            // echo $test["nom"];
            return $test["id"];
        }
        
            
        }
        public function updateProfilCoach(int $idUser, int $experience, string $bio, float $prix, string $photo){
            $req = $this->pdo->prepare("
                UPDATE coach
                SET id_user=?, experience_en_annee = ?, bio = ?, prix = ?, photo = ?
                WHERE id_user = ?
            ");
            return $req->execute([$idUser,$experience, $bio, $prix, $photo,$idUser]);
        }
    // inserer des specialite
    public function saveSpecialite(){
        foreach ($this->specialite as $spec) {

        $req = $this->pdo->prepare("SELECT id FROM specialite WHERE nom_specialite=?");
        $req->execute([$spec]);
        $test = $req->fetch(PDO::FETCH_ASSOC);

        
            $spec_id = $test['id'];
            $req1 = $this->pdo->prepare("INSERT INTO specialite (nom_specialite) VALUES(?)");
            $req1->execute([$spec]);
            $spec_id = $this->pdo->lastInsertId();
        
        // remlpir table associ
        $req1=$this->pdo->prepare("INSERT into specialite_coach(id_coach, id_specialite) VALUES(?,?)");
        $req1->execute([
            $this->coach_id,$spec_id
        ]);
    }
    
}
public function saveCertif(){
    foreach($this->certif as $cert){
        $req2=$this->pdo->prepare("INSERT into certification (id_coach, nom_certif, annee, etablissement) values(?,?,?,?)");
        $req2->execute([
            $this->coach_id,
            $cert["nom_certif"],//nom
            $cert["annee"],
            $cert["etablissement"],
        ]);
    }
}


public function virifierSiCoachCompleterProfil(int $userid){
    $req=$this->pdo->prepare("SELECT experience_en_annee FROM coach WHERE id_user=?");
    $req->execute([
        $userid        
    ]);

    $res1=$req->fetch(PDO::FETCH_ASSOC);
    // echo $res1["id"];
    if ($res1['experience_en_annee']!== null){
        return true;
    }
    return false;
}

public function tousCoach() {
    $req = $this->pdo->prepare("SELECT c.id, c.nom, c.prenom, c.prix, c.photo, c.experience_en_annee,
               GROUP_CONCAT(s.nom_specialite SEPARATOR ', ') AS specialite
        FROM coach c
        inner join specialite_coach sc ON c.id = sc.id_coach
        inner join specialite s ON sc.id_specialite = s.id
        GROUP BY c.id
    ");
    $req->execute();
    return $req->fetchAll(PDO::FETCH_ASSOC);
}

// le detail du coach
public function detailCoach(int $id) {
    $req = $this->pdo->prepare("SELECT c.id, c.nom, c.prenom, c.prix, c.photo, c.telephone, c.bio, c.experience_en_annee,
               GROUP_CONCAT(DISTINCT s.nom_specialite SEPARATOR ', ') AS specialites
        FROM coach c
        inner JOIN specialite_coach sc ON c.id = sc.id_coach
        inner JOIN specialite s ON sc.id_specialite = s.id
        WHERE c.id = ?
        GROUP BY c.id
    ");
    $req->execute([$id]);
    return $req->fetch(PDO::FETCH_ASSOC); 
}
// le certifs du coach
public function CertifCoach(int $id) {
    $reqCertif=$this->pdo->prepare("SELECT c.*,count(*) as nbrCertif,

                        GROUP_CONCAT(ce.nom_certif SEPARATOR ', ') AS nomCertif,

                        GROUP_CONCAT(ce.etablissement SEPARATOR ', ') AS etablissement,

                        GROUP_CONCAT(ce.annee SEPARATOR ', ') AS anneeCertif 

                        FROM coach c
                        inner join certification ce on ce.id_coach=c.id
                        where c.id=?
                        group by c.id
                        ");
$reqCertif->execute([
    $id
]);
    return $reqCertif->fetch(PDO::FETCH_ASSOC); 
}


//  statistique
    public function nbrReservationEnAttente($idCoach) {
        $req = $this->pdo->prepare(
            "SELECT COUNT(*) as nbr FROM reservation WHERE id_coach=? AND status='en_attente'"
        );
        $req->execute([$idCoach]);
        return $req->fetch();
    }
// les seance accepter ce jour 
public function nrbReseValide($idCoach){
    $req=$this->pdo->prepare("SELECT count(*) as nbr from reservation where id_coach=? and status='accepter' and date=CURDATE()");
    $req->execute([$idCoach]);
    return $req->fetch();
}

// 

public function nbrResDemain($idCoach) {
        $req = $this->pdo->prepare(
            "SELECT COUNT(*) as nbr FROM reservation 
             WHERE id_coach=? AND status='accepter' 
             AND date=CURDATE() + INTERVAL 1 DAY"
        );
        $req->execute([$idCoach]);
        return $req->fetch();
    }

    
    public function prochaineRese($idCoach) {
        $req = $this->pdo->prepare(
            "SELECT r.*, u.email FROM reservation r inner join client c ON c.id = r.id_client
             inner join users u ON u.id = c.id_user WHERE r.id_coach=? AND r.date>=CURDATE()
             ORDER BY r.date, r.heure_debut 
             LIMIT 1"
        );
        $req->execute([$idCoach]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }



    public function afficherProfil($idUser) {
    // le coach et leur specialite
    $req = $this->pdo->prepare("SELECT c.*,GROUP_CONCAT(s.nom_specialite SEPARATOR ', ') as specialite from coach c 
    inner join specialite_coach sc on sc.id_coach=c.id 
    inner join specialite s on s.id=sc.id_specialite where id_user=? 
    group by c.id
    ");
    $req->execute([$idUser]);
    $coach = $req->fetch(PDO::FETCH_ASSOC);
    $idC=$coach['id'];
    // Certifications
    $reqCertif = $this->pdo->prepare("SELECT group_concat(nom_certif separator ',') as nom_certif,group_concat(annee separator ',') 
                          as annee,group_concat(etablissement separator ',') as etablissement  from certification 
                          where id_coach=?
                          group by id_coach");

    $reqCertif->execute([$idC]);
    $certif = $reqCertif->fetch(PDO::FETCH_ASSOC);

   
    $coach['nom_certif'] = isset($certif['nom_certif']) ? $certif['nom_certif'] : '';
    $coach['annee'] = isset($certif['annee']) ? $certif['annee'] : '';
    $coach['etablissement']= isset($certif['etablissement']) ? $certif['etablissement'] : '';


    return $coach;
}


}

?>


