<?php
session_start();
require "../dataBase/Connect.php";
require '../session.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/coach.php';

// if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'coach'){
//     header("Location: ../index.php");
//     exit();
// }


if (isset($_POST["submitProfil"])) {
    //les champs du coach
    $experience=$_POST["experience"];

    // $photo=$_FILES['photo']['name'];
    $photo=$_POST['photo'];
    // echo $photo;
    
    $bio=$_POST["bio"];
    
    //les champs du specialite
    $specialites=$_POST["specialites"];
    // les champs du certif
    $cert=$_POST["certifications"];
    $prix=$_POST["prix"];
    
    // if ( $_SESSION['role'] =='coach') {
    //   $user_id=$_SESSION['user_id'];
    // }
    
    // $Coach=new User();
    // $idUser=$Coach->getId();
    // echo $idUser;

    echo  $_SESSION["user_id"];




    // prendre id coach deja inserer
      //  $reqIdCoach=$connect->prepare('SELECT id FROM coach WHERE id_user=?');
      //  $reqIdCoach->bind_param('s',$user_id);
      //  $reqIdCoach->execute();
      //  $resId=$reqIdCoach->get_result();
   
      //  $IdCoach=$resId->fetch_assoc();
      //  $id_coach=$IdCoach['id'];

    //ajouter les autre champs du coach les coach
    // echo $id_coach;
    // $reqCoach=$connect->prepare("UPDATE coach SET experience_en_annee=?,photo=?,bio=?,prix=? WHERE id=?");
    // $reqCoach->bind_param("sssdi",$experience,$photo,$bio,$prix,$id_coach);
    // $reqCoach->execute();
    // if($reqCoach->execute()){
    //   echo"le coach modifier avec succes" ;
    // }

    // foreach($specialites as $spe){
    //     $req=$connect->prepare("INSERT INTO specialite(nom_specialite) VALUES(?)");
    //     $req->bind_param("s",$spe);
    //     if($req->execute()){
    //       $id_specialite[] = $connect->insert_id;
    //     }
    // }
   
    

    // echo $id_coach;
    // remplir id 

    // ajouter les specialite
    // for($i=0; $i <count($id_specialite) ;$i++){
    //     $req=$connect->prepare("INSERT INTO specialite_coach (id_coach, id_specialite) VALUES(?,?)");
    //     $req->bind_param("ii",$id_coach,$id_specialite[$i]);
    //     $req->execute();
    // }

    // ajouter les certif
    // for ($i=0; $i <count($cert['nom']) ;$i++) { 
    //     $nom=$cert["nom"][$i];
    //     $annee=$cert["annee"][$i];
    //     $etablissement=$cert["etablissement"][$i];
    //     $reqcertif=$connect->prepare("INSERT INTO certification(id_coach, nom_certif, annee, etablissement) VALUES(?,?,?,?)");
    //     $reqcertif->bind_param("isss",$id_coach,$nom,$annee,$etablissement);
    //     $reqcertif->execute();
    // }

    header('Location: ../coach-dashboard.php');

}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profil Coach - SportCoach</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<?php require('../Pages/components/header.php'); ?>

<section class="flex-1 flex items-center justify-center py-12">
<div class="container mx-auto px-4 max-w-5xl">
  <div class="bg-white rounded-xl shadow-lg overflow-hidden grid md:grid-cols-2">

    <!-- Image / Info -->
    <div class="bg-green-600 text-white p-10 flex flex-col justify-center">
      <div class="text-center">
        <i class="fas fa-chalkboard-teacher text-5xl mb-4"></i>
        <h2 class="text-3xl font-bold mb-2">Complétez votre profil Coach</h2>
        <p>Ajoutez vos expériences, spécialités et certifications</p>
      </div>
    </div>

    <!-- Form -->
    <div class="p-10">
      <h1 class="text-2xl font-bold text-gray-800 mb-2">Profil Coach</h1>

      <!-- <?php if($erreur): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $erreur ?></div>
      <?php endif; ?>
      <?php if($success): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= $success ?></div>
      <?php endif; ?> -->

      <form method="POST" enctype="multipart/form-data" class="space-y-4">
        <div>
          <label class="block mb-1 font-semibold text-gray-700">Années d'expérience</label>
          <input type="number" name="experience" min="0" placeholder="Ex: 5" 
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600" required>
        </div>

        <div>
          <label class="block mb-1 font-semibold text-gray-700">Photo</label>
          <!-- <input type="file" name="photo" accept="image/*" class="w-full" required> -->
          <input type="url" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600" name="photo"  class="w-full" required>
        </div>

        <div>
          <label class="block mb-1 font-semibold text-gray-700">Bio</label>
          <textarea name="bio" rows="3" placeholder="Présentez-vous..." 
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600" required></textarea>
        </div>
        <!-- prix -->
        <div>
          <label class="block mb-1 font-semibold text-gray-700">Prix</label>
          <input type="text" name="prix" min="50" placeholder="Ex: 200" 
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600" required>
        
        </div>

        <!-- Spécialités dynamiques -->
        <div>
          <label class="block mb-1 font-semibold text-gray-700">Spécialités</label>
          <div id="specialitesContainer" class="space-y-2">
            <div class="flex space-x-2">
              <input type="text" name="specialites[]" placeholder="Nom de spécialité" 
                class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
              <button type="button" onclick="addSpecialite()" class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">+</button>
            </div>
          </div>
        </div>

        <!-- Certifications dynamiques -->
        <div>
          <label class="block mb-1 font-semibold text-gray-700">Certifications</label>
          <div id="certificationsContainer" class="space-y-2">
            <div class="flex flex-wrap space-y-2 space-x-2">
              <input type="text" name="certifications[nom][]" placeholder="Nom certification" 
                class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
              <input type="text" name="certifications[annee][]" placeholder="Année" 
                class="w-24 border border-gray-300 rounded-lg px-2 py-2 focus:outline-none focus:ring-2 focus:ring-green-600"><br>
              <input type="text" name="certifications[etablissement][]" placeholder="Établissement" 
                class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
              <button type="button" onclick="addCertification()" class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">+</button>
            </div>
          </div>
        </div>

        <button type="submit" name="submitProfil" 
          class="w-full bg-green-600 text-white font-semibold py-3 rounded-lg hover:bg-green-700 transition flex items-center justify-center space-x-2">
          <i class="fas fa-save"></i>
          <span>Enregistrer Profil</span>
        </button>
      </form>
    </div>
  </div>
</div>
</section>

<?php require('../Pages/components/footer.php');?>

<script>
function addSpecialite(){
  const container = document.getElementById('specialitesContainer');
  const div = document.createElement('div');
  div.className = 'flex space-x-2';
  div.innerHTML = `<input type="text" name="specialites[]" placeholder="Nom de spécialité" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
                   <button type="button" onclick="this.parentNode.remove()" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">−</button>`;
  container.appendChild(div);
}

function addCertification(){
  const container = document.getElementById('certificationsContainer');
  const div = document.createElement('div');
  div.className = 'flex space-x-2';
  div.innerHTML = `
    <input type="text" name="certifications[nom][]" placeholder="Nom certification" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
    <input type="text" name="certifications[annee][]" placeholder="Année" class="w-24 border border-gray-300 rounded-lg px-2 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
    <input type="text" name="certifications[etablissement][]" placeholder="Établissement" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
    <button type="button" onclick="this.parentNode.remove()" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">−</button>
  `;
  container.appendChild(div);
}
</script>

</body>
</html>
