<?php
$RolePage="coach";
require '../session.php';
require '../dataBase/connect.php';

$user_id=$_SESSION["user_id"];
// ID coach
$reqcoach=$connect->prepare("SELECT id FROM coach WHERE id_user=?");
$reqcoach->bind_param("i",$user_id);
$reqcoach->execute();
$result=$reqcoach->get_result();
$result1=$result->fetch_assoc();
$idCoach=$result1["id"];
// echo $idCoach;

//erreur
$erreur=""; 
// les dates et heur deja exist
// $reqDates=$connect->prepare("SELECT d.* FROM disponibilite d inner join coach c on c.id=d.id_coach");
// $reqDates->execute();
// $resu=$reqDates->get_result();
// $lesdiponibilite=$resu->fetch_all(MYSQLI_ASSOC);

// 

if (isset($_POST["save"])) {
  if (!empty($_POST["date"]) && !empty($_POST["startTime"]) && !empty($_POST["endTime"])) {
    $date=$_POST["date"];
    $startTime=$_POST["startTime"];
    $endTime=$_POST["endTime"];
    // INSERER DANS DISPO
    // si date ou time deja exist
    // $reqDates=$connect->prepare("SELECT d.* FROM disponibilite d inner join coach c on d.id_coach=? where d.date=? and d.heure_debut=? and d.heure_fin=?");
    $reqDates=$connect->prepare("SELECT id FROM disponibilite where id_coach=? and date=? and heure_debut=? and heure_fin=?");

    $reqDates->bind_param("isss",$idCoach,$date,$startTime,$endTime);
    $reqDates->execute();
    $resu=$reqDates->get_result();

    if ($resu->num_rows===0) {
        $reqSql=$connect->prepare("INSERT INTO disponibilite(id_coach, date, heure_debut, heure_fin) VALUE(?,?,?,?)");
        $reqSql->bind_param("isss",$idCoach,$date,$startTime,$endTime);
        // $reqSql->execute();
        if ($reqSql->execute()) {
          header("Location: ./coach-availability.php");
          exit();
        }
      }else{
        $erreur="ce temps est deja exist !";
      }

  }else{
    $erreur="tous les champs sont obligatoir";
  }
}
$erreurdelete="";
if (isset($_POST["annuler"])) {
  $id_dispo= $_POST["annuler"];
  // 
  $reqifixist=$connect->prepare("SELECT COUNT(*) as count FROM reservation WHERE id_disponibilite = ?");
  $reqifixist->bind_param("i",$id_dispo);
  $reqifixist->execute();
  $virifier=$reqifixist->get_result()->fetch_assoc();

  // if ($virifier['count'] > 0) {
          // $erreurdelete = "Impossible de supprimer, cette disponibilité est déjà réservée !";
      
    // Supprimer les reservation lier a dispo
    $deleteReservations = $connect->prepare("DELETE FROM reservation WHERE id_disponibilite=?");
    $deleteReservations->bind_param("i",$id_dispo);
    $deleteReservations->execute();

    // Supprimer la disponibilité
    
    $reqSql=$connect->prepare("DELETE FROM disponibilite WHERE id=?");
    $reqSql->bind_param("i",$id_dispo);
    $reqSql->execute();
    header("Location: coach-availability.php");
    exit();
  }
// }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Ajouter disponibilité</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<?php
require('./components/header.php');
?>
<div class="bg-white rounded-xl shadow-lg p-6 max-w-md mx-auto my-8">
  <h2 class="text-xl font-bold mb-4 text-gray-800">Ajouter une disponibilité</h2>

  <form id="availabilityForm" class="space-y-4" method="POST">
    <?php if(!empty($erreur)){
              ?>
              <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <?= $erreur ?>
              </div>
            <?php
            };?>
    <div>
      <label for="dateInput" class="block font-semibold text-gray-700 mb-1">Date</label>
      <input type="date" name="date" id="dateInput" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
    </div>

    <div>
      <label for="startTime" class="block font-semibold text-gray-700 mb-1">Heure de début</label>
      <input type="time" name="startTime" id="startTime" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" >
    </div>

    <div>
      <label for="endTime" class="block font-semibold text-gray-700 mb-1">Heure de fin</label>
      <input type="time" name="endTime" id="endTime" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" >
    </div>

    <div class="flex justify-end">
      <button type="submit" name="save" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">Enregistrer</button>
    </div>
  </form>
</div>
<h3 class="font-semibold text-gray-700 mt-6 mb-2 text-center">Disponibilités actuelles</h3>
  <!-- table -->
    <?php
    $sql=$connect->prepare("SELECT * FROM disponibilite
    where id_coach=?");
    $sql->bind_param("i",$idCoach);
    
    $sql->execute();
    $resul=$sql->get_result();
    $disponibilite=$resul->fetch_all(MYSQLI_ASSOC);
    ?>
   <table class="divide-y divide-gray-200" style="margin:auto;min-width:54em;">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">date</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">heure_debut</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">heure_fin</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">action</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <!-- foreach -->
         <?php
         if (count($disponibilite)>0) {
          foreach($disponibilite as $dispo){
         ?>
        <tr>
          <td class="px-6 py-4"><?=$dispo["date"]?></td>
          <td class="px-6 py-4"><?=$dispo["heure_debut"]?></td>
          <td class="px-6 py-4"><?=$dispo["heure_fin"]?></td>
          <td class="px-6 py-4 text-center space-x-2">
            <!-- <button class="text-yellow-500 hover:text-yellow-700" title="Modifier">Modifier
              <i class="fas fa-pen"></i> -->
            </button>
            <form action="" method="POST">
              <button name="annuler"  value="<?=$dispo["id"]?>" class="text-red-500 hover:text-red-700" title="Annuler"> Annuler 
                <i class="fas fa-trash"></i>
              </button> 
            </form>
          </td>
        </tr>
         <?php            
          }
        }else{
          echo "<tr>
            <td colspan='7' class='px-4 py-6 text-center text-gray-500'>aucun disponibilite trouve</td>
          </tr>";
              
        }
        ?>

      </tbody>
    </table>
<?php
require('./components/footer.php');
?>
<script>

</script>


</body>
</html>
