<?php
$RolePage="client";
require '../session.php';
require './connect.php';



// group_concat => concatener les valeur dans une seul chaines

$reqCoach=$connect->prepare('SELECT c.id,c.*,GROUP_CONCAT(s.nom_specialite SEPARATOR", ") as specialite FROM coach c

                              inner join specialite_coach sc on c.id=sc.id_coach

                              inner join specialite s on s.id=sc.id_specialite
                              group by c.id
                              
  ');

if ($reqCoach->execute()) {
  # code...
  $res=$reqCoach->get_result();
  $coach=$res->fetch_all(MYSQLI_ASSOC);

  // var_dump($coach["nom_specialite"]);

}

// foreach($coach as $coa){
// $coa=[];
//   echo $coa[]["nom_specialite"];
// }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nos Coachs | SportCoach</title>

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

<!-- NAVBAR -->
<?php
require('./components/header.php');
?>

<!-- PAGE HEADER -->
<section class="bg-primary text-white py-20">
  <div class="max-w-7xl mx-auto px-6 text-center">
    <h1 class="text-4xl font-extrabold mb-4">Nos Coachs Professionnels</h1>
    <p class="text-gray-300 max-w-2xl mx-auto">
      Sélectionnez le coach idéal selon votre discipline, niveau et objectifs.
    </p>
  </div>
</section>

<!-- FILTERS -->
<section class="max-w-7xl mx-auto px-6 -mt-10">
  <div class="bg-white shadow-lg rounded-xl p-6 grid md:grid-cols-4 gap-4">
    <input id="searchInput" type="text" placeholder="Rechercher un coach..."
      class="border rounded-lg px-4 py-3 focus:ring-2 focus:ring-accent outline-none">

    <select id="disciplineFilter" class="border rounded-lg px-4 py-3">
      <option value="">Toutes disciplines</option>
      <option value="football">Football</option>
      <option value="tennis">Tennis</option>
      <option value="natation">Natation</option>
      <option value="combat">Combat</option>
    </select>

    <select id="ratingFilter" class="border rounded-lg px-4 py-3">
      <option value="">Toutes notes</option>
      <option value="4">4★ et plus</option>
      <option value="3">3★ et plus</option>
    </select>

    <button id="resetFilters"
      class="border border-primary rounded-lg px-4 py-3 hover:bg-primary hover:text-white transition">
      Réinitialiser
    </button>
  </div>
</section>

<!-- COACHS GRID -->
<section class="max-w-7xl mx-auto px-6 py-20">
  <div id="coachesGrid" class="grid md:grid-cols-3 gap-10">

    <!-- CARD -->
    <?php
    foreach($coach as $coa){
    // var_dump($coa["photo"]);

    ?>
    <div class="coach-card bg-white rounded-xl shadow hover:shadow-xl transition overflow-hidden"
    data-discipline="football" data-rating="5">
      <div class="relative">
        <img src="<?=$coa["photo"]?>" class="w-full h-60 object-cover">
        <span class="absolute top-4 left-4 bg-accent text-white px-3 py-1 rounded-full text-sm font-semibold">
          Certifié
        </span>
      </div>

      <div class="p-6">
        <h3 class="text-xl font-bold mb-1"><?=$coa["nom"]." ".$coa["prenom"]?></h3>
        <p class="text-gray-500 mb-3">
          <i class="fas fa-futbol text-accent"></i>
          <?php
            echo $coa["specialite"];
          ?>
        </p>
        

        <!-- <div class="flex items-center gap-2 text-yellow-400 mb-4">
          ★★★★★ <span class="text-gray-500 text-sm">(124 avis)</span>
        </div> -->

        <div class="flex justify-between text-sm text-gray-500 mb-4">
          <span><i class="fas fa-clock"></i> <?= $coa['experience_en_annee']?></span>
          <span><i class="fas fa-users"></i> 250+ sportifs</span>
        </div>

        <div class="flex justify-between items-center">
          <span class="text-lg font-bold text-primary"><?=$coa['prix']?></span>
          <a href="./coach-profile.php?idProfilCoach=<?=$coa["id"]?>" class="bg-accent text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
            Voir profil
          </a>
        </div>
      </div>
    </div>
    <?php
    }
    ?>
</div>

  <div id="noResults" class="hidden text-center mt-16 text-gray-500">
    <i class="fas fa-search text-4xl mb-4"></i>
    <p>Aucun coach trouvé</p>
  </div>
</section>

<!-- FOOTER -->
<?php
require('./components/footer.php')
?>


<!-- JS FILTER -->
<script>
const searchInput = document.getElementById('searchInput');
const disciplineFilter = document.getElementById('disciplineFilter');
const ratingFilter = document.getElementById('ratingFilter');
const resetBtn = document.getElementById('resetFilters');
const coachCards = document.querySelectorAll('.coach-card');
const noResults = document.getElementById('noResults');

function filterCoaches() {
  let visible = 0;
  coachCards.forEach(card => {
    const text = card.textContent.toLowerCase();
    const discipline = card.dataset.discipline;
    const rating = card.dataset.rating;

    const okSearch = text.includes(searchInput.value.toLowerCase());
    const okDiscipline = !disciplineFilter.value || discipline === disciplineFilter.value;
    const okRating = !ratingFilter.value || rating >= ratingFilter.value;

    if (okSearch && okDiscipline && okRating) {
      card.classList.remove('hidden');
      visible++;
    } else {
      card.classList.add('hidden');
    }
  });
  noResults.classList.toggle('hidden', visible !== 0);
}

searchInput.oninput = filterCoaches;
disciplineFilter.onchange = filterCoaches;
ratingFilter.onchange = filterCoaches;
resetBtn.onclick = () => {
  searchInput.value = '';
  disciplineFilter.value = '';
  ratingFilter.value = '';
  filterCoaches();
};
</script>

</body>
</html>
