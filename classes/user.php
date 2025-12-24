<?php
require_once __DIR__ . '/../dataBase/Connect.php';
class User{
    protected int $id=0;
    protected string  $email="";
    protected string  $password="";
    protected string  $role="";

    // pour la connexion avec db
    protected $pdo;

    public function __construct(){
        $this->pdo=DataBase::connect();
        // var_dump($this->pdo);
    }
    // 
    public function getId(){return $this->id;}
    // email
    public function getEmail(){return $this->email;}
    public function setEmail(string $email){$this->email=$email;}
    // pass =>hasher
    public function getPassword(){return $this->password;}
    public function setPassword(string $password){$this->password=password_hash($password,PASSWORD_DEFAULT);// $this->password=$password;
    }
    // role
    public function getRole(){return $this->role;}
    public function setRole(string $role){$this->role=$role;}

    // virifier si email est deja exist?
    public function emailExist(string $email){
        $req1=$this->pdo->prepare("SELECT id from users where email=?");
        
        $req1->execute([$email]);
        return $req1->fetch(PDO::FETCH_ASSOC) !== false;
    }

    /* 
    if ($this->emailExists($this->email)) {
        throw new Exception("Cet email est déjà utilisé !");
    }
    */
    
    // insert
    function register(){
        if ($this->emailExist($this->email)) {
            echo '<script>alert("ce email est deja exist !!")</script>';
            return false;
        }
        $req=$this->pdo->prepare("INSERT into users(email, password, role) values(?,?,?)");
        $result=$req->execute([
                $this->email,
                $this->password,
                // password_hash($this->password,PASSWORD_DEFAULT),
                $this->role
            ]
            );
            if ($result){
                $res=$this->id=$this->pdo->lastInsertId();
                echo $res;
                var_dump($res);
                return $res;
            }
    }
    // login
    public function login($email,$password){
        $req=$this->pdo->prepare("SELECT * from users where email = ?");
        $req->execute([$email]);

        $user=$req->fetch(PDO::FETCH_ASSOC);
        if (!$user){
            echo "non email";
            return false;
            
        }
        // else{
        //     echo 'email correct !!'; 
        // }


        if (!password_verify($password,$user["password"])) {
            // password incorrect
            echo "non pw";

            return false;
        }
        // else{
        //     $SESSION["user_id"]=$user['id'];
        //     $SESSION["role"]=$user['role'];

        //     $req=$this->pdo->prepare("SELECT c.experience_en_annee FROM coach c
        //     INNER JOIN users u ON u.id=c.id_user WHERE c.experience_en_annee IS NULL
        //     ");
        //     if($user['role']==="coach" && $req->execute()){
        //         header("Location: ../Pages/addProfilCoach.php");
        //         exit();
        //     }
        //     // else{
        //         // password in correct
        //     // }
        // }
        // else {
            // email inncorect
        // }

        // remplir lobjet from db
        $this->id=$user["id"];
        $this->email=$user["email"];
        $this->role=$user["role"];

        // echo "la connexion est donnnnne";
        return true;


    }
    // public function completerProfilCach(int $id){
    //     $req=$this->pdo->prepare("SELECT c.experience_en_annee FROM coach c
    //     INNER JOIN users u ON u.id=c.id_user WHERE c.experience_en_annee IS NULL and u.id=?
    //     ");
    //     $req->execute([
    //       $id
    //     ]);
    //     $test=$req->fetch(PDO::FETCH_ASSOC);
    //     return $test;
    //     var_dump($test);
    //     // if ($test) {
    //     //     return $test;
    //     // }
    //     // else{
    //     //     echo 'makinch';
    //     // }
    // }

}

// $user1=new User();
// $user1->setEmail("Maria2_elgotby@gmail.com");
// $user1->setPassword("sdfghj");
// $user1->setRole("client");
// if ($user1->register()) {
//     echo "inscription reussite avec id".$user1->getId();
// }else{
//     echo 'inscription non reussite !!';
// }
// $user1->login('Maria2_elgotby@gmail.com','sdfghj1');



?>
