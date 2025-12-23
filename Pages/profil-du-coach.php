<?php
$RolePage="coach";
require './session.php';
require './connect.php';

$id_user=$_SESSION["user_id"];
// id coach connecter
$req1=$connect->prepare("SELECT id FROM coach where id_user=?");
$req1->bind_param("i",$id_user);
$req1->execute();
$res1=$req1->get_result();
$id_coach=$res1->fetch_assoc();
$id_coach1= $id_coach["id"];
// 
$erreur="";

//le coach et leur specialite
 $req=$connect->prepare("SELECT c.*,GROUP_CONCAT(s.nom_specialite SEPARATOR ', ') as specialite from coach c inner join specialite_coach sc on sc.id_coach=c.id 
  inner join specialite s on s.id=sc.id_specialite where id_user=? 
  group by c.id");
$req->bind_param("i",$id_user);
$req->execute();
$res=$req->get_result()->fetch_assoc();

// convertir specialite en array
$specialite=$res["specialite"];
$ArraySpacilite=explode(",",$specialite);


// les certif du coach
$certif=$connect->prepare("SELECT group_concat(nom_certif separator ',') as nom_certif,group_concat(annee separator ',') 

                          as annee,group_concat(etablissement separator ',') as etablissement  from certification 
                          
                          where id_coach=?
                          group by id_coach");

$certif->bind_param("i",$id_coach1);
$certif->execute();

$cetification=$certif->get_result()->fetch_assoc();

// to  array
$certifNom=explode(",",$cetification["nom_certif"]);
$certifAnnee=explode(",",$cetification["annee"]);
$certifEtabli=explode(",",$cetification["etablissement"]);


if (isset($_POST["save"])) {
  if (!empty($_POST["experience"]) &&!empty($_POST["phone"]) && !empty($_POST["photo"]) && !empty($_POST["bio"]) && !empty($_POST["specialites"]) && !empty($_POST["prix"]) && !empty($_POST["certifications"])) {
    # code...
 
   //les champs du coach
    $experience=$_POST["experience"];
    $phone=$_POST["phone"];
    $photo=$_POST['photo'];
    // echo $photo;
    $bio=$_POST["bio"];
    $prix=$_POST["prix"];

    //les champs du specialite
    $specialites=$_POST["specialites"];
    // les champs du certif
    $cert=$_POST["certifications"];
    
    $reqUpdateCoach=$connect->prepare("UPDATE coach SET photo=?,experience_en_annee=?,bio=?,prix=?,telephone=? where id=?");
    $reqUpdateCoach->bind_param("sssdsi",$photo,$experience,$bio,$prix,$phone,$id_coach1);
    // $reqUpdateCoach->execute();
    $id_specialite = [];
    if ($reqUpdateCoach->execute()) {
      foreach($specialites as $spe){
        // virifier si specialiter est deja exist
        
        // $virifierspec=$connect->prepare("SELECT s.id FROM specialite 
        // inner join specialite_coach sc on s.id=sc.id_specialite
        // WHERE s.nom_specialite=? and sc.id_coach=?");
        $virifierspec=$connect->prepare("SELECT id FROM specialite 
        WHERE nom_specialite=?");
        $virifierspec->bind_param("s",$spe);
        $virifierspec->execute();

        $virifier=$virifierspec->get_result()->fetch_assoc();
        
        if ($virifier) {
          $id_specialite1=$virifier["id"];
          $id_specialite[]=$id_specialite1;
          // // $connect->insert_id;
          // $req=$connect->prepare("UPDATE specialite SET nom_specialite=? where id=?");
          // $req->bind_param("si",$spe,$id_specialite1);
          // $req->execute();
          
        }
        else{
          $req=$connect->prepare("INSERT INTO specialite(nom_specialite) VALUES(?)");
          $req->bind_param("s",$spe);
          if($req->execute()){
            $id_specialite[] = $connect->insert_id;
          }
        }
    }

    // virifier si la liaison entre specialite et coach est deja exist
    for ($i=0; $i <count($id_specialite) ; $i++) { 
      $speccoach=$connect->prepare("SELECT id_specialite FROM specialite_coach where id_coach=? and id_specialite=?");
      $speccoach->bind_param("ii",$id_coach1,$id_specialite[$i]);
      $speccoach->execute();
      $spaCoach=$speccoach->get_result()->fetch_assoc();
      if (!$spaCoach) {
        $reqSc=$connect->prepare("INSERT INTO specialite_coach(id_coach, id_specialite) VALUES(?,?)");
        $reqSc->bind_param("ii",$id_coach1,$id_specialite[$i]);
        $reqSc->execute();
        
      }
    }


    // les certif
    for ($i=0; $i <count($cert["nom"]) ; $i++) { 
      // vifier si certif est deja exist
      $selectcertif=$connect->prepare("SELECT id from certification where nom_certif=? and id_coach=?");
      $selectcertif->bind_param("si",$cert["nom"][$i],$id_coach1);
      $selectcertif->execute();
      $selectcertifid=$selectcertif->get_result()->fetch_assoc();
      if ($selectcertifid){
        $idcertif=$selectcertifid["id"];
        $updateCertif=$connect->prepare("UPDATE certification set nom_certif=?,annee=?,etablissement=? where id=? and id_coach=?");
        $updateCertif->bind_param("sssii",$cert["nom"][$i],$cert["annee"][$i],$cert["etablissement"][$i],$idcertif,$id_coach1);
        $updateCertif->execute();
       
      }else{
        $insertCertif=$connect->prepare("INSERT into certification (id_coach, nom_certif, annee, etablissement) VALUES(?,?,?,?)");
        $insertCertif->bind_param("isss",$id_coach1,$cert["nom"][$i],$cert["annee"][$i],$cert["etablissement"][$i]);
        $insertCertif->execute();
      }
    }

    // header('Location: profil-du');
    }

    }else{
    $erreur="tous les champs sont obligatoire";
  }


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
<?php require('./components/header.php'); ?>
<section class="flex-1 py-10">
  <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-6">

    <!-- Sidebar dashboard -->
   <?php   require './components/aside.php';   ?>

    <!-- Profil Main Content -->
    <div class="md:col-span-3 flex flex-col space-y-6">

      <section class="bg-white p-6 rounded-xl shadow grid gap-6 w-full">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Modifier Profil Coach</h3>

        <form class="space-y-4" method="POST" novalidate>
        <?php if(!empty($erreur)){
              ?>
              <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <?= $erreur ?>
              </div>
            <?php
            };?>
          <!-- Photo -->
          <div>
            <label class="block text-gray-700 mb-1">Photo</label>
            <input type="url" name="photo" placeholder="URL de votre photo" value="<?=$res["photo"]?>" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
          </div>
          <!-- phone -->
          <div>
            <label class="block text-gray-700 mb-1">telephone</label>
            <input type="text" name="phone" placeholder="URL de votre photo" value="<?=$res["telephone"]?>" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
          </div>

          <!-- Biographie -->
          <div>
            <label class="block text-gray-700 mb-1">Bio</label>
            <textarea name="bio" rows="3" placeholder="Présentez-vous..." 
                      class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600"><?=$res["bio"]?></textarea>
          </div>

          <!-- Années d'expérience -->
          <div>
            <label class="block text-gray-700 mb-1">Années d'expérience</label>
            <input type="number" name="experience" min="0" placeholder="Ex: 5"  value="<?=$res["experience_en_annee"]?>" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
          </div>

          <!-- Prix -->
          <div>
            <label class="block text-gray-700 mb-1">Prix (DH)</label>
            <input type="text" name="prix" placeholder="Ex: 200" value="<?=$res["prix"]?>" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
          </div>

          <!-- Spécialités dynamiques -->
          <div>
            <label class="block text-gray-700 mb-1">Spécialités</label>
            <div id="specialitesContainer" class="space-y-2">
              <div class="flex space-x-2">
                <?php
                foreach($ArraySpacilite as $spe){
                ?>
                <input type="text" name="specialites[]" placeholder="Nom de spécialité"  value="<?=$spe?>"
                       class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
                       <?php
              }
              ?>
              <button type="button" onclick="addSpecialite()" class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">+</button>
              </div>
            </div>
          </div>

          <!-- Certifications dynamiques -->
          <div>
            <label class="block text-gray-700 mb-1">Certifications</label>
            <div id="certificationsContainer" class="space-y-2">
              <?php
                for ($i=0; $i < count($certifNom); $i++) { 
                  // echo $certifNom[$i];
                  ?>
                  <div class="flex flex-wrap space-x-2 space-y-2">
                <input type="text" name="certifications[nom][]" placeholder="Nom certification"  value="<?= $certifNom[$i]?>"
                       class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
                <input type="text" name="certifications[annee][]" placeholder="Année"  value="<?= $certifAnnee[$i]?>"
                       class="w-24 border border-gray-300 rounded-lg px-2 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
                <input type="text" name="certifications[etablissement][]" placeholder="Établissement"  value="<?= $certifEtabli[$i]?>"
                       class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
                <!-- <button type="button" onclick="this.parentNode.remove()" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">−</button> -->
                </div>
                <?php
                }
                
                ?>
            </div>
            <button type="button" onclick="addCertification()" 
                    class="mt-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Ajouter Certification</button>
          </div>

          <button type="submit" name="save"
                  class="w-full bg-green-600 text-white font-semibold py-3 rounded-lg hover:bg-green-700 transition flex items-center justify-center space-x-2">
            <i class="fas fa-save"></i>
            <span>Enregistrer Profil</span>
          </button>
        </form>
      </section>
    </div>
  </div>
</section>
<?php require('./components/footer.php'); ?>

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
  div.className = 'flex flex-wrap space-x-2 space-y-2';
  div.innerHTML = `
    <input type="text" name="certifications[nom][]" placeholder="Nom certification" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
    <input type="text" name="certifications[annee][]" placeholder="Année" class="w-24 border border-gray-300 rounded-lg px-2 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
    <input type="text" name="certifications[etablissement][]" placeholder="Établissement" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
    <button type="button" onclick="this.parentNode.remove()" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">−</button>
  `;
  container.appendChild(div);
}
</script>

