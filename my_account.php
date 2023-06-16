<?php
require_once("inc/init.inc.php");
require_once("inc/functions.inc.php");

if (!(is_user() || is_admin())) {
  header("Location: " . URL . "pages/form/sign_in.php");
  exit;
}
$sexeError = "";
$prenomError = "";
$nomError = "";
$pseudoError = "";
$emailError = "";
$adresseError = "";
$villeError = "";
$cpError = "";



// Pramètrage du formulaire Informations personnelles
if (isset($_POST["prenom"]) && isset($_POST["nom"]) && isset($_POST["sexe"])) {
  $prenom = secureHtml(trim($_POST["prenom"]));
  $nom = secureHtml(trim($_POST["nom"]));
  $sexe = $_POST["sexe"];

  // Vérification du champ sexe
  if (empty($sexe)) {
    $sexeError .= "Veuillez sélectionner votre sexe.";
  }

  // Vérification du nom 
  if (empty($nom)) {
    $nomError = "Veuillez saisir votre nom. ";
  } elseif (!preg_match("/^[a-zA-Z\s]+$/", $nom)) {
    $nomError = "Le nom ne doit contenir que des lettres alphabétiques et des espaces.";
  }

  // Vérification du prenom
  if (empty($prenom)) {
    $prenomError = "Veuillez saisir votre prénom.";
  } elseif (!preg_match("/^[a-zA-Z\s]+$/", $prenom)) {
    $prenomError = "Le prénom ne doit contenir que des lettres alphabétiques et des espaces.";
  }

  // Vérification globale des erreurs
  if (empty($prenomError) && empty($nomError) && empty($sexeError)) {

    $reponse = $pdo->prepare("UPDATE membre SET nom = :nom, prenom = :prenom, sexe = :sexe WHERE id_membre = :id_membre");
    $reponse->bindParam(':nom', $nom, PDO::PARAM_STR);
    $reponse->bindParam(':prenom', $prenom, PDO::PARAM_STR);
    $reponse->bindParam(':sexe', $sexe, PDO::PARAM_STR);
    $reponse->bindParam(':id_membre', $_SESSION["userData"]["id_membre"], PDO::PARAM_INT);
    $reponse->execute();
    $_SESSION["userData"]["nom"] = $nom;
    $_SESSION["userData"]["prenom"] = $prenom;
    $_SESSION["userData"]["sexe"] = $sexe;
    header("location :" . URL . "my_account.php");
  }
}

// Pramètrage du formulaire Informations de livraison
if (isset($_POST["adresse"]) && isset($_POST["ville"]) && isset($_POST["cp"])) {
  $adresse = secureHtml(trim($_POST["adresse"]));
  $ville = secureHtml(trim($_POST["ville"]));
  $cp = secureHtml(trim($_POST["cp"]));

  // Vérification de l'adresse
  if (empty($adresse)) {
    $adresseError .= "Veuillez saisir une adresse.";
  }

  // Vérification de la ville
  if (empty($ville)) {
    $villeError .= 'Veuillez saisir une ville.';
  } elseif (!preg_match("/^[a-zA-Z\s]+$/", $ville)) {
    $villeError = "La ville ne doit contenir que des lettres alphabétiques et des espaces.";
  }

  // Vérification du cp
  if (empty($cp)) {
    $cpError = "Veuillez saisir un code postal.";
  } elseif (!is_numeric($cp) || strlen($cp) > 5) {
    $cpError = "Le code postal doit être composé uniquement de 5 chiffres.";
  }

  // Vérification globale des erreurs
  if (empty($adresseError) && empty($villeError) && empty($cpError)) {
    $reponse = $pdo->prepare("UPDATE membre SET adresse = :adresse, ville = :ville, cp = :cp WHERE id_membre = :id_membre");
    $reponse->bindParam(':adresse', $adresse, PDO::PARAM_STR);
    $reponse->bindParam(':ville', $ville, PDO::PARAM_STR);
    $reponse->bindParam(':cp', $cp, PDO::PARAM_STR);
    $reponse->bindParam(':id_membre', $_SESSION["userData"]["id_membre"], PDO::PARAM_INT);
    $reponse->execute();
    $_SESSION["userData"]["adresse"] = $adresse;
    $_SESSION["userData"]["ville"] = $ville;
    $_SESSION["userData"]["cp"] = $cp;
    header("location :" . URL . "my_account.php");
  }
}

$title = "- Votre Compte";
require_once("inc/header.inc.php");
require_once("inc/nav.inc.php");
?>


  <div class="container" id="my-account">
    <div class="col-6 mx-auto">
      <h1 class="text-center mb-3">Votre compte</h1>

      <?php if (isset($_GET['edit']) && $_GET['edit'] === 'infos') : ?>
        <div class="card rounded-0 mb-5">
          <div class="card-header fw-bold p-3">
            <h3>Informations personnelles</h3>
          </div>
          <div class="card-body d-flex align-items-center">
            <div>
              <img src="assets/img/logo.png" class="card-img-top ms-3" alt="Photo de profil">
            </div>
            <form method="POST" action="<?= URL ?>my_account.php">
              <div class="ms-5">
                <p class="mb-0 fw-bold"> Sexe :</p>
                <div class="form-check form-check-inline ml-3">
                  <input class="form-check-input" type="radio" id="inlineRadio1" name="sexe" value="m" required>
                  <label class="form-check-label" for="inlineRadio1">Homme</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" id="inlineRadio2" name="sexe" value="f" required>
                  <label class="form-check-label" for="inlineRadio2">Femme</label>
                </div>
                <div class="inscription-error"><?= $sexeError ?></div>
                <p class="mb-0 fw-bold"> Prénom :</p>
                <input type="text" name="prenom" value="<?= $_SESSION["userData"]['prenom'] ?>" class="form-control">
                <div class="inscription-error"><?= $prenomError ?></div>
                <p class="mb-0 fw-bold"> Nom :</p>
                <input type="text" name="nom" value="<?= $_SESSION["userData"]['nom'] ?>" class="form-control">
                <div class="inscription-error"><?= $nomError ?></div>
              </div>
          </div>
          <button type="submit" class="btn btn-primary mx-auto w-75 rounded-0 m-3">Enregistrer</button>
          </form>
        </div>
      <?php else : ?>
        <div class="card rounded-0 mb-5">
          <div class="card-header fw-bold p-3">
            <h3>Informations personnelles</h3>
          </div>
          <div class="card-body d-flex align-items-center">
            <div>
              <img src="assets/img/logo.png" class="card-img-top ms-3" alt="Photo de profil">
            </div>
            <form method="GET" action="">
              <div class="ms-5">
                <p class="mb-0 fw-bold"> Sexe :</p>
                <p><?= ($_SESSION["userData"]['sexe'] === 'm') ? 'Homme' : 'Femme' ?></p>
                <p class="mb-0 fw-bold"> Prénom :</p>
                <p><?= $_SESSION["userData"]['prenom'] ?></p>
                <p class="mb-0 fw-bold"> Nom :</p>
                <p><?= $_SESSION["userData"]['nom'] ?></p>
              </div>
          </div>
          <a href="<?= URL ?>my_account.php?edit=infos" class="btn btn-primary mx-auto w-75 rounded-0 m-3">Modifier</a>
          </form>
        </div>
      <?php endif; ?>



      <?php if (isset($_GET['edit']) && $_GET['edit'] === 'adresse') : ?>
        <div class="card rounded-0 mb-5">
          <div class="card-header fw-bold p-3">
            <h3>Informations de livraison</h3>
          </div>
          <div class="card-body d-flex align-items-center">
            <form method="POST" action="<?= URL ?>my_account.php">
              <div class="ms-5">
                <p class="mb-0 fw-bold">Adresse :</p>
                <input type="text" name="adresse" value="<?= $_SESSION["userData"]['adresse'] ?>" class="form-control">
                <div class="inscription-error"><?= $adresseError ?></div>
                <p class="mb-0 fw-bold">Ville :</p>
                <input type="text" name="ville" value="<?= $_SESSION["userData"]['ville'] ?>" class="form-control">
                <div class="inscription-error"><?= $villeError ?></div>
                <p class="mb-0 fw-bold">Code-Postal :</p>
                <input type="text" name="cp" value="<?= $_SESSION["userData"]['cp'] ?>" class="form-control">
                <div class="inscription-error"><?= $cpError ?></div>
              </div>
          </div>
          <button type="submit" class="btn btn-primary mx-auto w-75 rounded-0 m-3">Enregistrer</button>
          </form>
        </div>
      <?php else : ?>
        <div class="card rounded-0 mb-5">
          <div class="card-header fw-bold p-3">
            <h3>Informations de livraison</h3>
          </div>
          <div class="card-body d-flex align-items-center">
            <form method="GET" action="">
              <div class="ms-5">
                <p class="mb-0 fw-bold"> Adresse :</p>
                <p><?= $_SESSION["userData"]['adresse'] ?></p>
                <p class="mb-0 fw-bold"> Ville :</p>
                <p><?= $_SESSION["userData"]['ville'] ?></p>
                <p class="mb-0 fw-bold"> Code-postal :</p>
                <p><?= $_SESSION["userData"]['cp'] ?></p>
              </div>
          </div>
          <a href="<?= URL ?>my_account.php?edit=adresse" class="btn btn-primary mx-auto w-75 rounded-0 m-3">Modifier</a>
          </form>
        </div>
      <?php endif; ?>


      <div class="card rounded-0 mb-5">
        <div class="card-header fw-bold p-3">
          <h3>Connexion et sécurité</h3>
        </div>
        <div class="card-body d-flex align-items-center">
          <div class="ms-5">
            <p class="mb-0 fw-bold"> Pseudo :</p>
            <p><?= $_SESSION["userData"]['pseudo'] ?></p>
            <p class="mb-0 fw-bold"> E-mail :</p>
            <p><?= $_SESSION["userData"]['email'] ?></p>
            <p class="mb-0 fw-bold"> Mot de passe :</p>
            <p>*******</p>
            <p class="user-information">Merci de contacter le support pour modifier vos informations de connexion : contact@contact.fr</p>
          </div>
        </div>
      </div>
      <!-- <?php if (isset($_GET['edit']) && $_GET['edit'] === 'security') : ?>
        <div class="card rounded-0 mb-5">
          <div class="card-header fw-bold p-3">
            <h3>Connexion et sécurité</h3>
          </div>
          <div class="card-body d-flex align-items-center">
            <form method="POST" action="<?= URL ?>my_account.php">
              <div class="ms-5">
                <p>Merci de modifier le pseudo et l'e-mail simultanément</p>
                <p class="mb-0 fw-bold">Pseudo :</p>
                <input type="text" name="pseudo" value="<?= $_SESSION["userData"]['pseudo'] ?>" class="form-control">
                <div class="inscription-error"><?= $pseudoError ?></div>
                <p class="mb-0 fw-bold">E-mail :</p>
                <input type="email" name="email" value="<?= $_SESSION["userData"]['email'] ?>" class="form-control">
                <div class="inscription-error"><?= $emailError ?></div>
                <p class="mb-0 fw-bold">Mot de passe :</p>
                <p>*******</p>
              </div>
          </div>
          <button type="submit" class="btn btn-primary mx-auto w-75 rounded-0 m-3">Enregistrer</button>
          </form>
        </div>
      <?php else : ?>
        <div class="card rounded-0 mb-5">
          <div class="card-header fw-bold p-3">
            <h3>Connexion et sécurité</h3>
          </div>
          <div class="card-body d-flex align-items-center">
            <form method="GET" action="">
              <div class="ms-5">
                <p class="mb-0 fw-bold"> Pseudo :</p>
                <p><?= $_SESSION["userData"]['pseudo'] ?></p>
                <p class="mb-0 fw-bold"> E-mail :</p>
                <p><?= $_SESSION["userData"]['email'] ?></p>
                <p class="mb-0 fw-bold"> Mot de passe :</p>
                <p>*******</p>
              </div>
          </div>
          <a href="<?= URL ?>my_account.php?edit=security" class="btn btn-primary mx-auto w-75 rounded-0 m-3">Modifier</a>
          </form>
        </div>
      <?php endif; ?> -->
    </div>
  </div>


<?php
require_once("inc/footer.inc.php");
?>