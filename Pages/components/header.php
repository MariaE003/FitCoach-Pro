<?php
// require '';
// require __DIR__ . '../../session.php';//nb
// session_start();
// echo '<pre>';
//  var_dump($_SESSION); 
// echo '</pre>';
// $_SESSION["role"];
// echo $_SESSION["user_id"];
?>
<header class="bg-white shadow sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
    <a class="flex items-center gap-3 text-2xl font-extrabold text-primary">
      <i class="fas fa-dumbbell text-accent"></i> FitCoach
    </a>
    <nav class="hidden md:flex gap-8 font-medium">
      <a href="/FitCoach-Pro/index.php" class="hover:text-accent <?= isset($_SESSION["user_id"])?'flex':'hidden'?>">Accueil</a>
      <a href="/FitCoach-Pro/Pages/coaches.php" class="hover:text-accent <?= isset($_SESSION["user_id"]) &&  $_SESSION["role"]==="client"  ?'flex':'hidden'?>">Coachs</a>
      <a href="/FitCoach-Pro/Pages/Mes-reservations.php" class="hover:text-accent <?= isset($_SESSION["user_id"]) &&  $_SESSION["role"]==="client"  ?'hidden':'flex'?>">Mes Reservation</a>
      <a href="/FitCoach-Pro/Pages/Mes-reservations-coach.php" class="hover:text-accent <?= isset($_SESSION["user_id"]) &&  $_SESSION["role"]==="coach"  ?'flex':'hidden'?>">Mes Reservation (coach)</a>
      <a href="/FitCoach-Pro/Pages/coach-availability.php" class="hover:text-accent <?= isset($_SESSION["user_id"]) &&  $_SESSION["role"]==="coach"  ?'flex':'hidden'?>">disponibilite</a>
      <!-- <a href="../reserver.php" class="hover:text-accent <?= isset($_SESSION["user_id"]) &&  $_SESSION["role"]==="client"  ?'flex':'hidden'?>">reserver</a> -->
      <a href="/FitCoach-Pro/Pages/coach-dashboard.php" class="hover:text-accent <?= isset($_SESSION["user_id"]) &&  $_SESSION["role"]==="coach"  ?'flex':'hidden'?>">Dashboard</a>
      <!-- <a href="#" class="hover:text-accent">Contact</a> -->
    </nav>
    <div class="hidden md:flex gap-3">
      
      <!-- <a href="./auth/login.php" class="px-4 py-2 border border-primary rounded-lg hover:bg-primary hover:text-white transition <?= isset($_SESSION["user_id"])?'hidden':'flex'?>">
        Connexion
      </a>
      <a href="./auth/register.php" class="px-4 py-2  rounded-lg hover:bg-green-600 transition <?= isset($_SESSION["user_id"])?'hidden':'flex'?>">
        Inscription
      </a> -->
      <a href="/FitCoach-Pro/Pages/sportif-profil.php" class="nav-btn px-3 py-1 rounded-lg hover:bg-blue-100 transition cursor-pointer <?= isset($_SESSION["user_id"]) &&  $_SESSION["role"]==="client"  ?'hidden':'flex'?>">Profil</a>
      <a href="/FitCoach-Pro/Pages/profil-du-coach.php" class="nav-btn px-3 py-1 rounded-lg hover:bg-blue-100 transition cursor-pointer <?= isset($_SESSION["user_id"]) &&  $_SESSION["role"]==="coach"  ?'flex':'hidden'?>">Profil</a>
      <form action="" method="POST">       
          <button type="submit" name="logout" 
              class="nav-btn px-3 py-1 rounded-lg hover:bg-blue-100 transition cursor-pointer <?= !isset($_SESSION["user_id"])?"hidden":"flex"?>">
              deconnecter
          </button>
      </form>

    </div>
  </div>
</header>