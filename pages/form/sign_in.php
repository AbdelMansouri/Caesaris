<?php
require_once("../../inc/init.inc.php");
require_once("../../inc/functions.inc.php");
require_once("../../inc/sign_in.inc.php");

$title = "- Connexion";
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
    header("Location: " . URL . "accueil.php");
  } else {
    $identifiantError = '
    <div class="card bg-light">
      <div class="card-body">
        Identifiant incorrect. Veuillez réessayer.
      </div>
    </div>';
  }
}
require_once("../../inc/header.inc.php");
require_once("../../inc/nav.inc.php");
?>

<body>
  <div class="container" id="sign-in-form">
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
          <div class="mb-5">
            <button type="submit" class="btn btn-primary">S'identifier</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>

<?php
require_once("../../inc/footer.inc.php");
?>