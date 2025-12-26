<?php
session_start();
require '../dataBase/connect.php';
require '../classes/User.php';
require '../classes/Coach.php';
// require '../session.php';
$erreur="";


// require "../connect.php";

//     $User1=new User();
// $_SESSION["user_id"]=$User1->getId();
//         echo $_SESSION["user_id"];
//         $_SESSION["role"]=$User1->getRole();

if (isset($_POST["Seconnecter"])) {

//   #vilider les champs
  if (!empty($_POST["email"]) && !empty($_POST["password"])) {
    $email=$_POST["email"];
    $password=$_POST["password"];

    $email = htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8');
    // htmlspecialc/hars => transforme les caractere speciaux en entite HTML (code)
//     // convertit les guilemet simples ' et doubles " entite HTML
    $password = htmlspecialchars(trim($_POST['password']), ENT_QUOTES, 'UTF-8');
//     // echo $role;
    $User=new User();
    if ($User->login($email, $password)) {

    $_SESSION["user_id"] = $User->getId();
    $_SESSION["role"]    = $User->getRole();
    // $User->login($email,$password);
    // 

//     // $passwordHasher=password_hash($password,PASSWORD_BCRYPT);

//     $sqlRequette=$connect->prepare("SELECT * FROM users WHERE email=?");
//     $sqlRequette->bind_param("s",$email);
//     $sqlRequette->execute();

//     //prendre les resultat
//     $Result=$sqlRequette->get_result();


//     if ($Result->num_rows==1) {
//       $user=$Result->fetch_assoc();
//       if (password_verify($password,$user['password'])) {
//         $_SESSION["user_id"]=$user['id'];



        // echo $_SESSION["role"];
        // $role=$_SESSION["role"];
        // echo $role;
        
        // $req=$User->pdo->prepare("SELECT c.experience_en_annee FROM coach c
        // INNER JOIN users u ON u.id=c.id_user WHERE c.experience_en_annee IS NULL and u.id=?
        // ");
        // $req->execute([
        //   $User->getId()
        // ]);
        // $test=$req->fetch(PDO::FETCH_ASSOC);
        $coach=new Coach();
        // $test=$coach->virifierProfilCoach($User->getId());
        // echo $test;
    // if ($test){
          // print_r( $test["experience_en_annee"]);
          if ($_SESSION["role"]==="coach"){
            if ($coach->virifierSiCoachCompleterProfil( $_SESSION["user_id"])) {
            header("Location: /FitCoach-Pro/index.php");
            exit();
          }
            // echo 'hi my coach';
            header("Location: /FitCoach-Pro/auth/addProfilCoach.php");
            exit();
          }

          // if($role==="client"){
          //   header("Location:  /FitCoach-Pro/index.php");
          //   exit();
          // }
          
        // }
        
        // if ($user['role']==="coach"){
        //   if (!$coach->verifierSiCoachCompleterProfil($User->getId())) {
        //     header("Location: /FitCoach-Pro/index.php");
        //     exit();
        //   }
        //   header("Location: /FitCoach-Pro/auth/addProfilCoach.php");
        //   exit();
        // }
        // $idDuUser=$_SESSION["user_id"];
        // $coach=new Coach();
        // $test1=$coach->virifierSiCoachCompleterProfil($User->getId());

        // if($test1===false){
        //   header("Location: /FitCoach-Pro/index.php");
        //   exit();
        // }
        // 
        

        // 
        // si le role est coach il Doit completer leur profil SI IL NA PAS COMPLETER ENCOR

        header("Location: /FitCoach-Pro/index.php");
        exit();
      }
        }
    //   else{
    //     $erreur="mot de passe incorrect !";
    //   }
    // }else{
    //     $erreur="email incorrect !";
    // }    
  // }

}
// $coach=new Coach();
// $User=new User();
// $test1=$coach->virifierSiCoachCompleterProfil($User->getId());
//       var_dump($test1);



?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion - SportCoach</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

  <!-- Navigation -->
  <?php
  require('../Pages/components/header.php');
  ?>

  <!-- Login Section -->
  <section class="flex-1 flex items-center justify-center py-12">
    <div class="container mx-auto px-4 max-w-4xl">
      <div class="bg-white rounded-xl shadow-lg overflow-hidden grid md:grid-cols-2">

        <!-- Image / Info -->
        <div class="bg-green-600 text-white p-10 flex flex-col justify-center">
          <div class="text-center">
            <i class="fas fa-running text-5xl mb-4"></i>
            <h2 class="text-3xl font-bold mb-2">Bienvenue sur SportCoach</h2>
            <p>Connectez-vous pour accéder à votre espace personnel</p>
          </div>
        </div>

        <!-- Form -->
        <div class="p-10">
          <h1 class="text-2xl font-bold text-gray-800 mb-2">Connexion</h1>
          <p class="text-gray-600 mb-6">Accédez à votre compte</p>

          <form method="POST" id="loginForm" class="space-y-4" onsubmit="return validerForm()">
            <?php 
            if(!empty($erreur)){
              ?>
              <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <?= $erreur ?>
              </div>
            <?php
            };
            ?>
            <!-- <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?>
            </div> -->
            <div>
              <label for="email" class="block mb-1 font-semibold text-gray-700">Email</label>
              <input type="email" id="email" name="email" placeholder="votre@email.com" 
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
            </div>

            <div>
              <label for="password" class="block mb-1 font-semibold text-gray-700">Mot de passe</label>
              <div class="relative">
                <input type="password" id="password" name="password" placeholder="••••••••" 
                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
                <button type="button" id="togglePassword" class="absolute right-2 top-2 text-gray-500">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
            </div>

            <div class="flex justify-between items-center text-gray-600 text-sm">
              <label class="inline-flex items-center">
                <input type="checkbox" name="remember" class="form-checkbox border-gray-300 rounded text-green-600">
                <span class="ml-2">Se souvenir de moi</span>
              </label>
              <a href="#" class="text-green-600 hover:underline">Mot de passe oublié ?</a>
            </div>

            <button type="submit" name="Seconnecter" class="w-full bg-green-600 text-white font-semibold py-3 rounded-lg hover:bg-green-700 transition flex items-center justify-center space-x-2">
              <i class="fas fa-sign-in-alt"></i>
              <span>Se connecter</span>
            </button>

            <p class="text-center text-gray-600 mt-4">Vous n'avez pas de compte ? <a href="register.php" class="text-green-600 underline">Créer un compte</a></p>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <?php
require('../Pages/components/footer.php')
?>

  <script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
      const passwordInput = document.getElementById('password');
      const icon = this.querySelector('i');
      
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
      }
    });

    

    // validation
    let email = document.getElementById('email');
    let password = document.getElementById('password');

    function validerForm() {
      let regexEmail=/^[A-Za-z0-9-_.]+@gmail\.com$/;
      let regexPassword=/^[A-Za-z0-9@._!-\s]{6,}$/;

      if (!regexEmail.test(email.value)) {
        alert("email invalide !");
        email.focus();
        return false; 
      }

      if (!regexPassword.test(password.value)) {
        alert("mot de passe invalide !");
        password.focus();
        return false; 
      }
      return true; 
  }


  </script>

</body>
</html>
