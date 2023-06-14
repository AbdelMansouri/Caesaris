<?php
$inscriptionSuccess = "";

if (isset($_GET['success']) && $_GET['success'] === 'inscription') {
  $inscriptionSuccess = '
  <div class="card bg-light">
    <div class="card-body strong">
      Inscription réussie ! Vous pouvez maintenant vous connecter.
    </div>
  </div>';
}
