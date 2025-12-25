<?php
$RolePage="coach";
require '../dataBase/connect.php';
require '../session.php';

$id_user=$_SESSION["user_id"];

// id coach
$req1=$connect->prepare("SELECT id FROM coach where id_user=?");
$req1->bind_param("i",$id_user);
$req1->execute();
$res1=$req1->get_result();
$id_coach=$res1->fetch_assoc();
$id_coach1= $id_coach["id"];
// 
$erreur="";

// le coach et leur specialite
$req=$connect->prepare("SELECT c.*,GROUP_CONCAT(s.nom_specialite SEPARATOR ', ') as specialite from coach c inner join specialite_coach sc on sc.id_coach=c.id 
  inner join specialite s on s.id=sc.id_specialite where id_user=? 
  group by c.id");
$req->bind_param("i",$id_user);
$req->execute();
$res=$req->get_result()->fetch_assoc();


// afficher le nombre des reservation en attente
$reqD=$connect->prepare("SELECT count(*) as nombreReservationAttente from reservation   where status=? and  id_coach=? ");
$status="en_attente";
$reqD->bind_param("si",$status,$id_coach1);
$reqD->execute();
$resReservationAtt=$reqD->get_result()->fetch_assoc();

// Séances validées aujourd'hui
$reqValide=$connect->prepare("SELECT count(*) as nombreReservationValideraujour from reservation   where status=? and  id_coach=? and date=CURDATE()");
$statusAcc="Accepter";
// $nowdate=CURDATE();
$reqValide->bind_param("si",$statusAcc,$id_coach1);
$reqValide->execute();
$ResvalideToday=$reqValide->get_result()->fetch_assoc();


//Séances validées demain
$reqValideD=$connect->prepare("SELECT count(*) as nombreReservationValideDemain from reservation   where status=? and  id_coach=? and date=CURDATE() + interval 1 day");
$statusAccDemain="Accepter";
$reqValideD->bind_param("si",$statusAccDemain,$id_coach1);
$reqValideD->execute();
$ResvalideDemain=$reqValideD->get_result()->fetch_assoc();

// Prochaine séance
$reqValideD=$connect->prepare("SELECT * from reservation where id_coach=? and date>=CURDATE() order by date , heure_debut limit 1");
// $statusAccDemain="Accepter";
$reqValideD->bind_param("i",$id_coach1);
$reqValideD->execute();
$ProchaineRe=$reqValideD->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Coach - SportCoach</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

  <!-- Navigation -->
 <?php
require('./components/header.php')
?>

  <!-- Dashboard Content -->
  <section class="flex-1 py-10">
    <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-6">

      <!-- Sidebar -->
      <?php   require './components/aside.php';   ?>

      <!-- Main Content -->
      <div class="md:col-span-3 flex flex-col space-y-6">

        <!-- Statistiques -->
        <section id="statistiques" class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-white p-6 rounded-xl shadow flex flex-col items-center">
            <span class="text-gray-500">Demandes en attente</span>
            <span class="text-3xl font-bold text-green-600 mt-2"><?=$resReservationAtt["nombreReservationAttente"]?></span>
          </div>
          <div class="bg-white p-6 rounded-xl shadow flex flex-col items-center">
            <span class="text-gray-500">Séances validées aujourd'hui</span>
            <span class="text-3xl font-bold text-green-600 mt-2"><?=$ResvalideToday["nombreReservationValideraujour"]?></span>
          </div>
          <div class="bg-white p-6 rounded-xl shadow flex flex-col items-center">
            <span class="text-gray-500">Séances validées demain</span>
            <span class="text-3xl font-bold text-green-600 mt-2"><?=$ResvalideDemain["nombreReservationValideDemain"]?></span>
          </div>
        </section>

        <!-- Prochain sportif -->
         <?php
         if ($ProchaineRe) {
          
         ?>
        <section class="bg-white p-6 rounded-xl shadow flex flex-col md:flex-row justify-between items-center">
          <div>
            <h3 class="font-bold text-gray-800">Prochaine séance</h3>
            <p class="text-gray-600 mt-1"><?=$ProchaineRe["prenom"]." ".$ProchaineRe["nom"]?>Mohammed Benali - 16 Décembre 2024 à 10:00</p>
            <!-- <p class="text-gray-600">Type : Individuelle</p> -->
          </div>
          <a href="./Mes-reservations-coach.php" class="mt-4 md:mt-0 bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">Voir Détails</a>
        </section>
        <?php
         }else{
        ?>
        <section class="bg-white p-6 rounded-xl shadow flex flex-col md:flex-row justify-between items-center">
          <div>
            <h3 class="font-bold text-gray-800">Prochaine séance</h3>
            <p class="text-gray-600 mt-1">aucun seance pour le moment</p>
          </div>
        </section>
        <?php
         }
        ?>

        <!-- Gestion des réservations -->
        <!-- <section id="reservations" class="bg-white p-6 rounded-xl shadow">
          <h3 class="text-lg font-bold text-gray-800 mb-4">Demandes de séances</h3>
          <div class="overflow-x-auto">
            <table class="w-full text-left">
              <thead class="bg-green-600 text-white">
                <tr>
                  <th class="py-2 px-4">Client</th>
                  <th class="py-2 px-4">Date / Heure</th>
                  <th class="py-2 px-4">Type</th>
                  <th class="py-2 px-4">Actions</th>
                </tr>
              </thead>
              <tbody class="text-gray-700">
                <tr class="border-b">
                  <td class="py-2 px-4">Ali Ziani</td>
                  <td class="py-2 px-4">16/12/2024 10:00</td>
                  <td class="py-2 px-4">Individuelle</td>
                  <td class="py-2 px-4 space-x-2">
                    <button class="bg-green-600 text-white px-3 py-1 rounded-lg hover:bg-green-700 transition">Accepter</button>
                    <button class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition">Refuser</button>
                  </td>
                </tr>
                <tr class="border-b">
                  <td class="py-2 px-4">Sara Benali</td>
                  <td class="py-2 px-4">16/12/2024 11:00</td>
                  <td class="py-2 px-4">Groupe</td>
                  <td class="py-2 px-4 space-x-2">
                    <button class="bg-green-600 text-white px-3 py-1 rounded-lg hover:bg-green-700 transition">Accepter</button>
                    <button class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition">Refuser</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </section> -->

        <!-- Gestion du profil -->
        <!-- <section id="profil" class="bg-white p-6 rounded-xl shadow grid md:grid-cols-2 gap-6">
          <div>
            <h3 class="text-lg font-bold text-gray-800 mb-4">Modifier Profil</h3>
            <label class="block text-gray-700 mb-1">Photo</label>
            <input type="file" class="mb-4">
            <label class="block text-gray-700 mb-1">Biographie</label>
            <textarea class="w-full border border-gray-300 rounded-lg px-4 py-2 mb-4" rows="3">Coach expérimenté en fitness et cardio.</textarea>
            <label class="block text-gray-700 mb-1">Disciplines sportives</label>
            <input type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2 mb-4" placeholder="Fitness, Cardio, Musculation">
            <label class="block text-gray-700 mb-1">Certifications</label>
            <input type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2 mb-4" placeholder="Ex: CrossFit Level 1">
            <button class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">Enregistrer</button>
          </div> -->

          <!-- Gestion des disponibilités -->
          <!-- <div>
            <h3 class="text-lg font-bold text-gray-800 mb-4">Disponibilités</h3>
            <div class="space-y-2">
              <div class="flex justify-between items-center bg-gray-100 px-4 py-2 rounded-lg">
                <span>16/12/2024 - 10:00</span>
                <button class="bg-green-600 text-white px-3 py-1 rounded-lg hover:bg-green-700 transition">Modifier</button>
              </div>
              <div class="flex justify-between items-center bg-gray-100 px-4 py-2 rounded-lg">
                <span>16/12/2024 - 11:00</span>
                <button class="bg-green-600 text-white px-3 py-1 rounded-lg hover:bg-green-700 transition">Modifier</button>
              </div>
            </div>
          </div> -->
        </section>

      </div>
    </div>
  </section>

  <!-- Footer -->
  <?php
require('./components/footer.php')
?>

</body>
</html>
