<?php
$RolePage="client";
require '../session.php';
require '../dataBase/connect.php';

$id_user=$_SESSION["user_id"];
// echo $id_user;
if (isset($_GET["idProfilCoach"])) {
  $idcoach=$_GET["idProfilCoach"];
}

// id client
// $req1=$connect->prepare("SELECT id FROM client where id_user=?");
// $req1->bind_param("i",$id_user);
// $req1->execute();
// $res1=$req1->get_result();
// $id_client1=$res1->fetch_assoc();
// $id_client= $id_client1["id"];


// $req=$connect->prepare("SELECT * FROM disponibilite where id_coach=? and disponible=1");
// $req->bind_param("i",$idcoach);
// $req->execute();
// $res=$req->get_result();
// $dispoRows=$res->fetch_all(MYSQLI_ASSOC);


// // grouper les times par dates
// $dispoLignes=[];
// foreach ($dispoRows as $dispo) {
//   $date=$dispo["date"];
//   $time=$dispo["heure_debut"]."-".$dispo["heure_fin"];
//   if (!isset($dispoLignes[$date])) {
//     $dispoLignes[$date]=[];
//   }
//   $dispoLignes[$date][]=$time;  
// }


// virifier 

// if (isset($_POST["reserver"])) {
//   if (!empty($_POST["date"])&&!empty($_POST["Hdebut"])&&!empty($_POST["HFin"])&&!empty($_POST["objectif"])) {
//     $date=$_POST["date"];
//     $Hdebut=$_POST["Hdebut"];
//     $HFin=$_POST["HFin"];
//     $objectif=$_POST["objectif"];
//     $idDispo=$_POST["idDispo"];

//     // inserer la reservation 
//     $reqReser=$connect->prepare("INSERT INTO reservation 

//     (id_client, id_coach, id_disponibilite,heure_debut, heure_fin, objectif, date)

//     VALUES(?,?,?,?,?,?,?)");
    
//     $reqReser->bind_param("iiissss",$id_client,$idcoach,$idDispo,$Hdebut,$HFin,$objectif,$date);

//     if ($reqReser->execute()) {
//       // modifier disponibiliter 
//       $disponible = 0;
//       $reqDis=$connect->prepare("UPDATE disponibilite set disponible=? where id_coach=? and date=? and heure_debut=? and heure_fin=?");
//       $reqDis->bind_param("iisss",$disponible,$idcoach,$date,$Hdebut,$HFin);
      
//       if($reqDis->execute()){
//         header("Location: Mes-reservations.php");
//         exit();
//       }
//     }
    
//   }
// }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Réserver une séance | SportCoach</title>
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
// require('/FitCoach-Pro/Pages/components/header.php');

?>

<!-- CONTENT -->
<section class="max-w-4xl mx-auto px-6 py-16">

  <a href="./coach-profile.php?idProfilCoach=<?=$idcoach?>" class="text-sm text-gray-500 hover:text-accent flex items-center gap-2 mb-6">
    <i class="fas fa-arrow-left"></i> Retour au profil
  </a>

  <div class="bg-white rounded-xl shadow p-8 grid md:grid-cols-3 gap-8">

    <!-- FORM -->
    <div class="md:col-span-2">
      <h1 class="text-3xl font-bold mb-6">
        <i class="fas fa-calendar-plus text-accent"></i> les moments disponible
      </h1>
      <!-- <a href="#" class="bg-neutral-primary-soft block max-w-sm p-6 border border-default rounded-base shadow-xs hover:bg-neutral-secondary-medium">
          <h5 class="mb-3 text-2xl font-semibold tracking-tight text-heading leading-8">Noteworthy technology acquisitions 2021</h5>
          <p class="text-body">Here are the biggest technology acquisitions of 2025 so far, in reverse chronological order.</p>
      </a> -->
      <!-- <h2 class="text-2xl font-bold mb-6 text-gray-800">
        Disponibilités du coach
      </h2> -->
      
      <!-- Date Card -->
      <?php
    if (count($dispoLignes)>0) {
      
    foreach ($dispoLignes as $date=>$time) {
      
      ?>
      <div class="max-w-3xl mx-auto p-6">
        <div class="mb-6 bg-white shadow rounded-xl p-5">
          <h3 class="text-lg font-semibold text-gray-700 mb-4">
            <!-- 📅 Lundi 20 Décembre 2025 -->
            <?php echo $date ?>
          </h3>

          <div class="flex flex-wrap gap-3">
            <?php foreach ($time as $oneTime) {
              $timesPartes=explode("-",$oneTime);
          ?>
            <button id="time" class="time px-4 py-2 rounded-lg border border-green-500 text-green-600 hover:bg-green-500 hover:text-white transition" 
            data-date="<?=$date?>" data-start="<?=$timesPartes[0]?>" data-end="<?=$timesPartes[1]?>" data-id="<?=$dispo['id']?>">
              <?php echo $oneTime ?>
            </button>
            
            <?php
   }
  
   ?>
   </div>
 </div>
 </div>
 <?php
  }
}else{
  ?>
    <div class="max-w-md mx-auto mt-6 bg-green-50 border border-green-300 text-green-800 px-6 py-4 rounded-xl text-center shadow">
      Aucun temps disponible pour ce coach
    </div>

<?php
  }
  ?>

      <h2 class="text-3xl font-bold mb-6">
        <i class="fas fa-calendar-plus text-accent"></i> Réserver une séance
      </h2>

      <!-- les dispo de ce coach -->
       

      <!-- ALERT -->
      <div id="availabilityAlert" class="hidden mb-6 p-4 rounded-lg text-sm"></div>
      <form class="space-y-6" method="POST">
        <input type="hidden" name="idDispo" id="idDispo" value="">
        <div>
          <label class="block mb-1 font-medium">Date</label>
          <input type="date" id="date" readonly name="date"
            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent">
        </div>

        <div>
          <label class="block mb-1 font-medium">Heure Debut</label>
          <input type="time" id="timeStart" name="Hdebut" readonly
            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent">
        </div>
        <div>
          <label class="block mb-1 font-medium">Heure Fin</label>
          <input type="time" id="timeEnd" name="HFin" readonly
            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent">
        </div>

        <div>
          <label class="block mb-1 font-medium">Objectifs</label>
          <textarea rows="3" name="objectif" id="objectif"
            class="w-full border rounded-lg px-4 py-2"
            placeholder="Perte de poids, technique, endurance..."></textarea>
        </div>

        <div class="flex gap-4">

          <button type="submit"
            id="confirmBtn"
            name="reserver"
            disabled
            class="px-6 py-3 bg-accent text-white rounded-lg font-semibold  ">
            Confirmer réservation
          </button>
        </div>

      </form>
    </div>
  </div>
</section>

<?php
// require('/FitCoach-Pro/Pages/components/footer.php');
?>
<script>
  let dateF=document.querySelector("#date"); 
  let timeStart=document.querySelector("#timeStart"); 
  let timeEnd=document.querySelector("#timeEnd"); 
  let objectif=document.querySelector("#objectif"); 
  let allInputs=document.querySelectorAll("#date,#timeStart,#timeEnd,#objectif");
  let time=document.querySelectorAll("#time");
  // btn
  let confirmBtn=document.querySelector("#confirmBtn"); 
  // input hidden
  let input=document.querySelector("#idDispo"); 


  let times=document.querySelectorAll(".time"); 
  times.forEach(btn=>{
    btn.addEventListener("click",()=>{
      let date=btn.dataset.date;
      let startTime=btn.dataset.start
      let endTime=btn.dataset.end
       let idDispo = btn.dataset.id; 

      dateF.value=date;
      timeStart.value=startTime;
      timeEnd.value=endTime;
      confirmBtn.disabled = objectif.value.trim() === "";

      input.value = idDispo;
    })
  })
  objectif.addEventListener("click",()=>{
    confirmBtn.disabled = !(dateF.value && timeStart.value && timeEnd.value && objectif.value.trim());

  });

  
</script>
</body>
</html>









