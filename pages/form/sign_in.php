<?php
require_once("../../inc/init.inc.php");
require_once("../../inc/functions.inc.php");
require_once("../../inc/sign_in.inc.php");

if ((is_user() || is_admin())) {
  header("Location: " . URL . "index.php");
  exit;
}

$identifiantError = "";

if (isset($_POST["email"]) && isset($_POST["mdp"])) {
  $email = $_POST["email"];
  $mdp = $_POST["mdp"];

  $reponse = $pdo->prepare("SELECT * FROM membre WHERE email = :email");
  $reponse->bindParam(":email", $email);
  $reponse->execute();
  $userData = $reponse->fetch(PDO::FETCH_ASSOC);

  if ($userData && password_verify($mdp, $userData["mdp"])) {
    $_SESSION["userData"] = $userData;
    header("Location: " . URL . "index.php");
  } else {
    $identifiantError = '
    <div class="card bg-light">
      <div class="card-body">
        Identifiant incorrect. Veuillez réessayer.
      </div>
    </div>';
  }
}

$title = "- Connexion";
require_once("../../inc/header.inc.php");
require_once("../../inc/nav.inc.php");
?>


<div class="container min-height" id="sign-in">
  <div class="row">
    <div class="col-6 mx-auto mt-5">
      <h1>Connexion</h1>
      <?= $inscriptionSuccess ?>
      <div class="connexion-error"><?= $identifiantError ?></div>
      <form method="POST" action="sign_in.php" enctype="multipart/form-data">
        <!-- EMAIL -->
        <div class="mb-3 mt-3">
          <input type="email" class="form-control" id="email" name="email" placeholder="Adresse email">
        </div>
        <!-- MDP -->
        <div class="mb-3">
          <input type="text" class="form-control" id="mdp" name="mdp" placeholder="Mot de passe">
        </div>
        <!-- Bouton envoi -->
        <div class="mb-5 d-flex align-items-center">
          <button type="submit" class="btn btn-primary me-3 p-2 ps-3 pe-3">S'identifier</button>
          <p class="line-height1 mb-0 ms-auto text-muted">Pas encore inscrit(e)?</p>
          <a href="<?= URL ?>pages/form/sign_up.php" class="btn btn-secondary ms-3">Inscrivez-vous</a>
        </div>
      </form>
    </div>
  </div>
</div>


<?php
require_once("../../inc/footer.inc.php");
?>