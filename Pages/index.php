<?php
require 'connect.php';
require './session.php';

// echo $_SESSION["role"];


?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SportCoach | Trouvez votre coach</title>

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

<!-- HERO -->
<section class="relative bg-primary text-white">
  <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-black/20"></div>
  <div class="relative max-w-7xl mx-auto px-6 py-28 grid md:grid-cols-2 gap-12 items-center">
    <div>
      <h1 class="text-5xl font-extrabold leading-tight mb-6">
        Atteignez vos objectifs sportifs avec un coach expert
      </h1>
      <p class="text-lg text-gray-200 mb-8">
        Plateforme professionnelle de réservation de séances sportives personnalisées.
      </p>
      <div class="flex gap-4">
        <a href="./coaches.php" class="bg-accent px-6 py-3 rounded-lg font-semibold hover:bg-green-600 transition">
          Trouver un coach
        </a>
        <a href="#" class="border border-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-primary transition">
          Devenir coach
        </a>
      </div>
    </div>

    <!-- HERO STATS -->
    <div class="grid grid-cols-2 gap-6">
      <div class="bg-white/10 p-6 rounded-xl backdrop-blur">
        <p class="text-3xl font-bold">250+</p>
        <p class="text-gray-200">Coachs certifiés</p>
      </div>
      <div class="bg-white/10 p-6 rounded-xl backdrop-blur">
        <p class="text-3xl font-bold">4.9/5</p>
        <p class="text-gray-200">Avis sportifs</p>
      </div>
      <div class="bg-white/10 p-6 rounded-xl backdrop-blur">
        <p class="text-3xl font-bold">100%</p>
        <p class="text-gray-200">Flexibilité</p>
      </div>
      <div class="bg-white/10 p-6 rounded-xl backdrop-blur">
        <p class="text-3xl font-bold">24/7</p>
        <p class="text-gray-200">Disponibilité</p>
      </div>
    </div>
  </div>
</section>

<!-- DISCIPLINES -->
<section class="py-24">
  <div class="max-w-7xl mx-auto px-6">
    <h2 class="text-4xl font-bold text-center mb-14">Disciplines Sportives</h2>

    <div class="grid md:grid-cols-3 gap-8">
      <div class="bg-white p-8 rounded-xl shadow hover:shadow-xl transition text-center">
        <i class="fas fa-futbol text-5xl text-accent mb-4"></i>
        <h3 class="text-xl font-bold mb-2">Football</h3>
        <p class="text-gray-500">Technique & performance</p>
      </div>

      <div class="bg-white p-8 rounded-xl shadow hover:shadow-xl transition text-center">
        <i class="fas fa-person-swimming text-5xl text-accent mb-4"></i>
        <h3 class="text-xl font-bold mb-2">Natation</h3>
        <p class="text-gray-500">Endurance & maîtrise</p>
      </div>

      <div class="bg-white p-8 rounded-xl shadow hover:shadow-xl transition text-center">
        <i class="fas fa-hand-fist text-5xl text-accent mb-4"></i>
        <h3 class="text-xl font-bold mb-2">Sports de combat</h3>
        <p class="text-gray-500">Force & discipline</p>
      </div>
    </div>
  </div>
</section>

<!-- FEATURES -->
<section class="bg-white py-24">
  <div class="max-w-7xl mx-auto px-6">
    <h2 class="text-4xl font-bold text-center mb-14">Pourquoi SportCoach ?</h2>

    <div class="grid md:grid-cols-4 gap-10 text-center">
      <div>
        <i class="fas fa-certificate text-5xl text-accent mb-4"></i>
        <h3 class="font-bold text-lg mb-2">Certifié</h3>
        <p class="text-gray-500">Coachs vérifiés</p>
      </div>
      <div>
        <i class="fas fa-calendar-check text-5xl text-accent mb-4"></i>
        <h3 class="font-bold text-lg mb-2">Simple</h3>
        <p class="text-gray-500">Réservation rapide</p>
      </div>
      <div>
        <i class="fas fa-clock text-5xl text-accent mb-4"></i>
        <h3 class="font-bold text-lg mb-2">Flexible</h3>
        <p class="text-gray-500">Horaires adaptés</p>
      </div>
      <div>
        <i class="fas fa-star text-5xl text-accent mb-4"></i>
        <h3 class="font-bold text-lg mb-2">Noté</h3>
        <p class="text-gray-500">Avis transparents</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="bg-primary text-white py-24 text-center">
  <h2 class="text-4xl font-bold mb-4">Commencez votre transformation</h2>
  <p class="mb-8 text-gray-300">Rejoignez la meilleure plateforme de coaching sportif</p>
  <a href="#" class="bg-accent px-8 py-4 rounded-lg font-semibold hover:bg-green-600 transition">
    S'inscrire maintenant
  </a>
</section>

<?php
require('./components/footer.php');
?>

</body>
</html>
