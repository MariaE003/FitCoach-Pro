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
    // public function virifierProfilCoach(int $id){
    //     $req=$this->pdo->prepare("SELECT * FROM coach c INNER JOIN users u ON u.id=c.id_user WHERE c.id_user=? ");
    //     // erreur => 0 dans experience 😒😒
    //     // experience_en_annee=0 and 
    //     $req->execute([
    //         $id
    //     ]);
    //     $test=$req->fetch(PDO::FETCH_ASSOC);
    //     // echo "dxcf";
    //     if ($test) {
    //         // echo $test["nom"];
    //         return $test["id"];
    //     }
        
    //     // else{
    //         //     echo 'non trouver';
    //         // }
            
    //     }
    public function leCoachConne(int $id){
        $req=$this->pdo->prepare("SELECT * FROM coach  WHERE id_user=?");
        // erreur => 0 dans experience 😒😒
        // experience_en_annee=0 and 
        $req->execute([
            $id
        ]);
        $test=$req->fetch(PDO::FETCH_ASSOC);
        // echo "dxcf";
        if ($test) {
            // echo $test["nom"];
            return $test["id"];
        }
        
        // else{
            //     echo 'non trouver';
            // }
            
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

        // if ($test) {
            $spec_id = $test['id'];
        // } else {
            $req1 = $this->pdo->prepare("INSERT INTO specialite (nom_specialite) VALUES(?)");
            $req1->execute([$spec]);
            $spec_id = $this->pdo->lastInsertId();
        // }
        // $req=$this->pdo->prepare("INSERT INTO specialite (nom_specialite) VALUES(?)");
        // $req->execute([
        //     $spec
        // ]);
        // $spec_id=$this->pdo->lastInsertId();

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

// id du coach inserer
// public function CoachConnId(int $userid){
//     $req=$this->pdo->prepare("SELECT id FROM coach WHERE id_user=?");
//     $req->execute([
//         $userid        
//     ]);

//     $res1=$req->fetch(PDO::FETCH_ASSOC);
//     // echo $res1["id"];
//     if ($res1) {
//         $coachId=$this->coach_id = $res1['id'];
//         return $coachId;
        
//     }
//     return false;
// }

public function virifierSiCoachCompleterProfil(int $userid){
    // $req=$this->pdo->prepare("SELECT id FROM coach WHERE id_user=?");
    $req=$this->pdo->prepare("SELECT experience_en_annee FROM coach WHERE id_user=?");
    $req->execute([
        $userid        
    ]);

    $res1=$req->fetch(PDO::FETCH_ASSOC);
    // echo $res1["id"];
    if ($res1['experience_en_annee']!== null){
        // $coachId=$this->coach_id = $res1['id'];
        return true;
        // return true;
    }
    return false;
}

public function tousCoach() {
    $req = $this->pdo->prepare("
        SELECT c.id, c.nom, c.prenom, c.prix, c.photo, c.experience_en_annee,
               GROUP_CONCAT(s.nom_specialite SEPARATOR ', ') AS specialite
        FROM coach c
        LEFT JOIN specialite_coach sc ON c.id = sc.id_coach
        LEFT JOIN specialite s ON sc.id_specialite = s.id
        GROUP BY c.id
    ");
    $req->execute();
    return $req->fetchAll(PDO::FETCH_ASSOC);
}



}

?>


