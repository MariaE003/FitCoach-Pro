<?php
$RolePage="client";
require './session.php';
require './connect.php';
// if (isset($_GET["idProfilCoach"])) {
//   $idcoach=$_GET["idProfilCoach"];
// }
$id_user=$_SESSION["user_id"];
if (isset($_POST["detailBtn"])) {
  $deta=$_POST["detailBtn"];
  echo $deta;
}

// id client
$req1=$connect->prepare("SELECT id FROM client where id_user=?");
$req1->bind_param("i",$id_user);
$req1->execute();
$res1=$req1->get_result();
$id_client1=$res1->fetch_assoc();
$id_client= $id_client1["id"];
// les reservation de ce client
$statusEnAttent="en_attente";
$statusAcceptee="acceptee";
$req=$connect->prepare("SELECT c.nom,c.prenom,c.prix,c.photo,r.* FROM reservation r 
  inner join coach c on  r.id_coach=c.id
  where r.id_client=? and (r.status=? or r.status=?)  ");
$req->bind_param("iss",$id_client,$statusEnAttent,$statusAcceptee);
$req->execute();
$res=$req->get_result();
$rservationRows=$res->fetch_all(MYSQLI_ASSOC);



// modification
if (isset($_POST["modifier"])) {
  $id_reservation = $_POST['id_reservation'];
      // modifier reservation 
      $reqRes=$connect->prepare("UPDATE reservation set status=? where id=?");
      $status = "Annuler";
      $reqRes->bind_param("si", $status, $id_reservation);
      // $reqDis->execute();
      // prendre les info pour modifier dispo
      if ($reqRes->execute()) {
        $reqResInfo = $connect->prepare("SELECT id_coach, date, heure_debut, heure_fin FROM reservation WHERE id=?");
        $reqResInfo->bind_param("i", $id_reservation);
        $reqResInfo->execute();
        $resInfo = $reqResInfo->get_result()->fetch_assoc();

        //  modifier disponibiliter 
        $disponible = 1;
        $reqDis=$connect->prepare("UPDATE disponibilite set disponible=? where id_coach=? and date=? and heure_debut=? and heure_fin=?");
        $reqDis->bind_param("iisss",$disponible,$resInfo["id_coach"],$resInfo["date"],$resInfo["heure_debut"],$resInfo["heure_fin"]);
        $reqDis->execute();
        
        // header("Location: coach-profile.php?idProfilCoach=$idcoach");
        header("Location: reserver.php?idProfilCoach=" . $resInfo['id_coach']);
        exit();
      }

}




// hitorique de reservation
$statusAnnuler = "Annuler";
$statusRefuser = "Refuser";

$reqHist = $connect->prepare("SELECT c.nom, c.prenom, c.prix, c.photo, r.* FROM reservation r INNER JOIN coach c ON r.id_coach=c.id WHERE r.id_client=? AND (r.status=? OR r.status=?)");

$reqHist->bind_param("iss", $id_client, $statusAnnuler, $statusRefuser);
$reqHist->execute();
$resHist = $reqHist->get_result();
$reservationHistory = $resHist->fetch_all(MYSQLI_ASSOC);



if (isset($_POST["annuler"])) {

  $id_reservation = $_POST["id_reservation"];
  $status = "Annuler";

  //modifier le status de reservation
  $reqRes = $connect->prepare( "UPDATE reservation SET status=? WHERE id=? AND id_client=?" );
  $reqRes->bind_param("sii", $status, $id_reservation, $id_client);

  if ($reqRes->execute()) {
    // prendre les info du reservation
    $reqInfo = $connect->prepare("SELECT id_coach, date, heure_debut, heure_fin FROM reservation WHERE id=?");
    $reqInfo->bind_param("i", $id_reservation);
    $reqInfo->execute();
    $info = $reqInfo->get_result()->fetch_assoc();

    // modifier dispo 
    $disponible = 1;
    $reqDis = $connect->prepare("UPDATE disponibilite SET disponible=? WHERE id_coach=? AND date=? AND heure_debut=? AND heure_fin=?");
    $reqDis->bind_param("iisss",$disponible,$info["id_coach"],$info["date"],$info["heure_debut"],$info["heure_fin"]);
    $reqDis->execute();
    header("Location: Mes-reservations.php");
    exit();
  }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mes Réservations | SportCoach</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#0f172a',
            accent: '#22c55e',
            soft: '#f8fafc'
          }
        }
      }
    }
  </script>
</head>
<body class="bg-soft text-gray-800">

<!-- NAV -->
<?php
require('./components/header.php')
?>

<!-- CONTENT -->
<section class="max-w-6xl mx-auto px-6 py-16">
  <h1 class="text-3xl font-bold mb-8">Mes Réservations</h1>

  <div class="overflow-x-auto bg-white rounded-xl shadow">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Heure Debut</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Heure Fin</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Coach</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
          <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
         <?php
         if (count($rservationRows)>0) {
         foreach ($rservationRows as $reser) {
        
         ?>
        <tr>
          <td class="px-6 py-4"><?=$reser["date"]?></td>
          <td class="px-6 py-4"><?=$reser["heure_debut"]?></td>
          <td class="px-6 py-4"><?=$reser["heure_fin"]?></td>
          <td class="px-6 py-4"><?=$reser["prenom"]." ".$reser["nom"]?></td>
          <td class="px-6 py-4">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800"><?=$reser["status"]?></span>
          </td>
          <td class="px-6 py-4 text-center space-x-2">
            <button data-date="<?=$reser["date"]?>" data-name="<?=$reser["prenom"]." ".$reser["nom"] ?>" data-start="<?=$reser["heure_debut"]?>" data-end="<?=$reser["heure_fin"]?>" data-status="<?=$reser["status"]?>" data-prix="<?=$reser["prix"]?>"
            data-objectif="<?=$reser["objectif"]?>" data-photo="<?=$reser["photo"]?>" class="text-blue-500 hover:text-blue-700 open-modal" title="Détails">
            <i class="fas fa-eye"></i>
          </button>
          <form action="" method="POST">
            <input type="hidden" name="id_reservation" value="<?=$reser['id']?>">
            <button name="modifier" class="text-yellow-500 hover:text-yellow-700"  title="Modifier">
              <i class="fas fa-pen"></i>
            </button>
          </form>
            <!-- <button class="text-red-500 hover:text-red-700" title="Annuler">
              <i class="fas fa-trash"></i>
            </button> -->
            <form method="POST" class="inline">
            <input type="hidden" name="id_reservation" value="<?=$reser['id']?>">
            <button name="annuler"
              onclick="return confirm('vous voulez vraiment annuler la reservation ?')"
              class="text-red-500 hover:text-red-700"
              title="Annuler">
              <i class="fas fa-trash"></i>
            </button>
          </form>

          </td>
        </tr>
        <?php
         }
        }else{
          echo "<tr>
            <td colspan='7' class='px-4 py-6 text-center text-gray-500'>aucun reservation trouve</td>
          </tr>";
                                
        }
        ?>
        
      </tbody>
    </table>
  </div>
  <!-- les reservation annuller ou refuser -->
   <h2 class="text-2xl font-bold mb-4 mt-8">Historique des Réservations</h2>
  <div class="overflow-x-auto bg-white rounded-xl shadow">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Heure Debut</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Heure Fin</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Coach</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <?php foreach ($reservationHistory as $reser) { ?>
        <tr>
          <td class="px-6 py-4"><?=$reser["date"]?></td>
          <td class="px-6 py-4"><?=$reser["heure_debut"]?></td>
          <td class="px-6 py-4"><?=$reser["heure_fin"]?></td>
          <td class="px-6 py-4"><?=$reser["prenom"]." ".$reser["nom"]?></td>
          <td class="px-6 py-4">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?=($reser["status"]=="Annuler")?"bg-red-100 text-red-800":"bg-gray-200 text-gray-800"?>">
              <?=$reser["status"]?>
            </span>
          </td>
          <td class="px-6 py-4"><?=$reser["prix"]?> DH</td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

</section>  


<!--  -->

<?php
require('./components/footer.php')
?>
</body>
</html>

<!-- Modal Overlay -->
<div id="reservationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
  <!-- le detail du reservation -->

  <div class="bg-white rounded-xl shadow-lg w-11/12 md:w-2/3 lg:w-1/2">
    
    <div class="flex justify-between items-center border-b px-6 py-4">
      <h2 class="text-xl font-bold text-primary">Détails de la réservation</h2>
      <button id="closeModal" class="text-gray-500 hover:text-gray-700">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <div class="px-6 py-4 space-y-4">
      <div class="flex items-center space-x-4">
        <img src="" id="photoModal" alt="Coach" class="rounded-full">
        <div>
          <strong class="text-lg" id="nameModal">Mohammed Benali</strong>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4 text-gray-700">
        <div>
          <span class="font-semibold">Date :</span> <span id="modalDate">16 Décembre 2024</span>
        </div>
        <div>
          <span class="font-semibold">Heure Debut :</span> <span id="modalTimeStart">10:00</span>
        </div>
        <div>
          <span class="font-semibold">Heure Fin :</span> <span id="modalTimeend">10:00</span>
        </div>
        <!-- <div>
          <span class="font-semibold">Type :</span> <span id="modalType">Individuelle</span>
        </div> -->
        <div>
          <span class="font-semibold">Statut :</span> 
          <span class="px-2 py-1 rounded-full bg-green-100 text-green-800" id="modalStatus">Confirmée</span>
        </div>
        <div class="col-span-2">
          <span class="font-semibold">Prix :</span> <span id="modalPrice">200 DH</span>
        </div>
      </div>

      <div>
        <h3 class="font-semibold text-gray-800">Objectif</h3>
        <p class="text-gray-600" id="modalObjectif"></p>
      </div>
    </div>

    <div class="px-6 py-4 border-t flex justify-end">
      <button id="closeModalBtn" class="btn btn-outline px-4 py-2 rounded-lg hover:bg-primary hover:text-white transition">
        Fermer
      </button>
    </div>
  </div>
</div>

<script>
 
  const modal = document.getElementById("reservationModal");
  const closeModal = document.getElementById("closeModal");
  const closeModalBtn = document.getElementById("closeModalBtn");

  
  const modalDate = document.getElementById("modalDate");
  const modalTimeStart = document.getElementById("modalTimeStart");
  const modalTimeEnd = document.getElementById("modalTimeend");
  const modalStatus = document.getElementById("modalStatus");
  const modalPrice = document.getElementById("modalPrice");
  const modalObjectif = document.getElementById("modalObjectif");
  const modalName = document.getElementById("nameModal");
  const modalPhoto = document.getElementById("photoModal");

  // buttons
  const buttonsOpen = document.querySelectorAll(".open-modal");

  buttonsOpen.forEach(btn => {
    btn.addEventListener("click", () => {

      // data attribut
      modalDate.textContent = btn.dataset.date;
      modalTimeStart.textContent = btn.dataset.start;
      modalTimeEnd.textContent = btn.dataset.end;
      modalStatus.textContent = btn.dataset.status;
      modalPrice.textContent = btn.dataset.prix + " DH";
      modalObjectif.textContent = btn.dataset.objectif;
      modalName.textContent = btn.dataset.name;

      // photo
      modalPhoto.src = btn.dataset.photo 
        ? btn.dataset.photo 
        : "https://via.placeholder.com/80";

      // show modal
      modal.classList.remove("hidden");
      modal.classList.add("flex");
    });
  });

  // close modal
  [closeModal, closeModalBtn].forEach(btn => {
    btn.addEventListener("click", () => {
      modal.classList.add("hidden");
      modal.classList.remove("flex");
    });
  });

  
</script>

