<?php
// require '../session.php';


// require __DIR__. '../../dataBase/connect.php';
require_once '../dataBase/Connect.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/client.php';
require_once __DIR__ . '/../classes/coach.php';



// require "../connect.php";

if (isset($_POST["inscrir"])) {
  #vilider les champs
  if (!empty($_POST["firstName"]) && !empty($_POST["lastName"]) && !empty($_POST["email"]) && !empty($_POST["phone"]) && !empty($_POST["password"]) && !empty($_POST["confirmPassword"])) {
    $firstname=$_POST["firstName"];
    $lastName=$_POST["lastName"];
    $email=$_POST["email"];
    $phone=$_POST["phone"];
    $password=$_POST["password"];
    $confirmPassword=$_POST["confirmPassword"];
    $role=$_POST["role"];
    // echo $role;

    // $passwordHasher=password_hash($password,PASSWORD_BCRYPT);

    $user=new User();
    $user->setEmail($email);
    $user->setRole($role);
    $user->setPassword($password);
    $id=$user->register();//pour eviter erreur du pdo null
    echo $id;
    var_dump($id);
    if ($id) {
      // echo "insersiont resusiire".$id;
      if ($user->getRole()==="client") {
        $client=new Client();
        $client->setId($id);//pour eviter erreur du pdo null
        $client->setNom($lastName);
        $client->setPrenom($firstname);
        $client->setTelephone($phone);
        $client->registerClient();
      }
      $leRole=$user->getRole();
      var_dump($leRole);
      echo  $leRole;
      if ($leRole==="coach") {
        echo "je suis coach";
        $coach=new Coach();
        $coach->setNom($lastName);
        $coach->setPrenom($firstname);
        $coach->setTelephone($phone);
        // $coach->registerCoach();
        // $coach->setPassword($password);
        // $coach->setEmail($email);

        // $coach->setAnneeExperience();
        // $coach->setBio();
        // $coach->setPrix();
        // $coach->setPhoto();
        // $coach->setSpecialite();
        // $coach->setcertif();

        $coach->registerCoach($id);
      }

      header("Location:login.php");
      exit();


    }
    // $sqlRequette=$connect->prepare("INSERT INTO users(email, password, role) VALUES (?,?,?)");
    // $sqlRequette->bind_param("sss",$email,$passwordHasher,$role);
    // $sqlRequette->execute();

    // if ($sqlRequette->execute()){
    //   // $userId=$connect->insert_id;
    //   if ($role==="client") {
    //   $sqlRequetteClient=$connect->prepare("INSERT INTO client(id_user, nom, prenom, telephone,email) VALUES (?,?,?,?,?)");
    //   $sqlRequetteClient->bind_param("sssss",$id,$lastName,$firstname,$phone,$email);
    //   $sqlRequetteClient->execute();
    // }
    // if ($role==="coach") {
    //   $sqlRequetteClient=$connect->prepare("INSERT INTO coach(id_user, nom, prenom, telephone) VALUES (?,?,?,?)");
    //   $sqlRequetteClient->bind_param("ssss",$id,$lastName,$firstname,$phone);
    //   $sqlRequetteClient->execute();
    // }
    // header("Location:login.php");
    // exit();
    // }

  }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription - SportCoach</title>
  <script src="https://cdn.tailwindcss.com"></script>
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
  <!-- Navigation -->
  <?php
require('../Pages/components/header.php');
?>

  <!-- Register Section -->
  <section class="flex-1 flex items-center justify-center py-12">
    <div class="container mx-auto px-4 max-w-5xl">
      <div class="bg-white rounded-xl shadow-lg overflow-hidden grid md:grid-cols-2">
        
        <!-- Image / Info -->
        <div class="bg-green-600 text-white p-10 flex flex-col justify-center">
          <div class="text-center">
            <i class="fas fa-user-plus text-5xl mb-4"></i>
            <h2 class="text-3xl font-bold mb-2">Rejoignez SportCoach</h2>
            <p>Créez votre compte et commencez votre parcours sportif</p>
          </div>
        </div>

        <!-- Form -->
        <div class="p-10">
          <h1 class="text-2xl font-bold text-gray-800 mb-2">Inscription</h1>
          <p class="text-gray-600 mb-6">Créez votre compte gratuitement</p>

          <!-- Role Selection -->
          <div class="flex space-x-4 mb-6">
            <button type="button" name="client" class="role-btn flex-1 py-2 px-4 border-2 border-green-600 text-green-600 rounded-lg font-semibold active" data-role="client">
              <i class="fas fa-user mr-2"></i> Sportif
            </button>
            <button type="button" name="coach" class="role-btn flex-1 py-2 px-4 border-2 border-gray-300 text-gray-600 rounded-lg font-semibold" data-role="coach">
              <i class="fas fa-chalkboard-teacher mr-2"></i> Coach
            </button>
          </div>

          <form method="POST" id="registerForm" class="space-y-4" onsubmit="return validerForm()">
            <input type="hidden" id="role" name="role" value="client">

            <div class="grid md:grid-cols-2 gap-4">
              <div>
                <label for="firstName" class="block mb-1 font-semibold text-gray-700">Prénom</label>
                <input type="text" id="firstName" name="firstName" placeholder="Votre prénom" 
                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
              </div>
              <div>
                <label for="lastName" class="block mb-1 font-semibold text-gray-700">Nom</label>
                <input type="text" id="lastName" name="lastName" placeholder="Votre nom" 
                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
              </div>
            </div>

            <div>
              <label for="email" class="block mb-1 font-semibold text-gray-700">Email</label>
              <input type="text" id="email" name="email" placeholder="votre@email.com" 
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
            </div>

            <div>
              <label for="phone" class="block mb-1 font-semibold text-gray-700">Téléphone</label>
              <input type="tel" id="phone" name="phone" placeholder="+212 6 12 34 56 78" 
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
            </div>

            <div class="grid md:grid-cols-2 gap-4">
              <div>
                <label for="password" class="block mb-1 font-semibold text-gray-700">Mot de passe</label>
                <div class="relative">
                  <input type="password" id="password" name="password" placeholder="••••••••" 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
                  <button type="button" id="togglePassword" class="absolute right-2 top-2 text-gray-500">
                    <i class="fas fa-eye"></i>
                  </button>
                </div>
                <p class="text-sm text-gray-500 mt-1">Minimum 8 caractères, majuscule, minuscule et chiffre</p>
              </div>
              <div>
                <label for="confirmPassword" class="block mb-1 font-semibold text-gray-700">Confirmer le mot de passe</label>
                <div class="relative">
                  <input type="password" id="confirmPassword" name="confirmPassword" placeholder="••••••••" 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
                  <button type="button" id="toggleConfirmPassword" class="absolute right-2 top-2 text-gray-500">
                    <i class="fas fa-eye"></i>
                  </button>
                </div>
              </div>
            </div>

            <button type="submit" name="inscrir" class="w-full bg-green-600 text-white font-semibold py-3 rounded-lg hover:bg-green-700 transition">Créer mon compte</button>

            <p class="text-center text-gray-600 mt-4">Vous avez déjà un compte ? <a href="login.php" class="text-green-600 underline">Se connecter</a></p>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <?php
require('../Pages//components/footer.php');
?>

  <script>
    // Role selection
    let btnRoles=document.querySelectorAll('.role-btn');
    let inputHidden=document.getElementById('role');
    btnRoles.forEach(btn=>{
      btn.addEventListener("click",()=>{
        
        btnRoles.forEach(b => b.classList.remove('active', 'border-green-600', 'text-green-600'));
        btn.classList.add('active', 'border-green-600', 'text-green-600');
        inputHidden.value=btn.dataset.role;

      })
    })
    
    // Toggle password visibility
    function setupPasswordToggle(toggleId, inputId) {
      document.getElementById(toggleId).addEventListener('click', function() {
        const input = document.getElementById(inputId);
        const icon = this.querySelector('i');
        if (input.type === 'password') {
          input.type = 'text';
          icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
          input.type = 'password';
          icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
      });
    }
    setupPasswordToggle('togglePassword', 'password');
    setupPasswordToggle('toggleConfirmPassword', 'confirmPassword');

    // Form submission (dummy)
    // document.getElementById('registerForm').addEventListener('submit', function(e) {
    //   e.preventDefault();
    //   alert('Inscription réussie !');
    // });

    let firstName=document.getElementById('firstName');
    let lastName=document.getElementById('lastName');
    let email=document.getElementById('email');
    let phone=document.getElementById('phone');
    let password=document.getElementById('password');
    let confirmPassword=document.getElementById('confirmPassword');
    function validerForm(){
      // e.preventDefault();
      let regexName=/^[A-Za-z\s]+$/;
      let regexEmail=/^[A-Za-z0-9-_.]+@gmail\.com$/;
      let regexPhone=/^(06|07)[0-9]{8}$/;
      // let regexPassword=/^[A-Za-z0-9@_-]{5,}$/;
      let regexPassword=/^[A-Za-z0-9@._!-\s]{6,}$/;;
      
      // if (firstName.value && lastName.value && email.value && phone.value && password.value && confirmPassword.value) {
        if (!regexName.test(firstName.value) ){
          alert("le prenom est invalide!");
          firstName.focus();
          
          return false;
        }
        if (!regexName.test(lastName.value) ){
          alert("le nom est invalide!");
          lastName.focus();
          
          return false;
        }
        if (!regexEmail.test(email.value)){
          alert("votre email est invalide!");
          email.focus();
          
          return false;
        }
        if (!regexPhone.test(phone.value)){
          alert("votre telephone est invalide!");
          phone.focus();
          
          return false;
        }
        if (!regexPassword.test(password.value)){
          alert("mot de passe est tres faible!");
          password.focus();
          
          return false;
        }
        if (password.value!=confirmPassword.value) {
            alert("le mot de passe de confermation est incorrect!");
            confirmPassword.focus();
            
            return false;
          }
          return true;
      }
    
      // }
  </script>

</body>
</html>
