<?php
require_once("../../inc/init.inc.php");
require_once("../../inc/functions.inc.php");
// Formulaire d'inscription
$sexeError = "";
$pseudoError = "";
$emailError = "";
$emailConfirmError = "";
$mdpError = "";
$mdpConfirmError = "";
$prenomError = "";
$nomError = "";
$adresseError = "";
$villeError = "";
$cpError = "";

// Pré-remplissage du formulaire si déjà saisi
$pseudo = '';
$email = '';
$prenom = '';
$nom = '';
$adresse = '';
$ville = '';
$cp = '';

if (isset($_POST["email"]) && isset($_POST["pseudo"]) && isset($_POST["email-confirm"]) && isset($_POST["mdp"]) && isset($_POST["mdp-confirm"]) && isset($_POST["prenom"]) && isset($_POST["nom"]) && isset($_POST["sexe"]) && isset($_POST["adresse"]) && isset($_POST["ville"]) && isset($_POST["cp"])) {
  $pseudo = secureHtml(trim($_POST["pseudo"]));
  $email = secureHtml(trim($_POST["email"]));
  $emailConfirm = secureHtml(trim($_POST["email-confirm"]));
  $mdp = secureHtml(trim($_POST["mdp"]));
  $mdpConfirm = secureHtml(trim($_POST["mdp-confirm"]));
  $prenom = secureHtml(trim($_POST["prenom"]));
  $nom = secureHtml(trim($_POST["nom"]));
  $sexe = $_POST["sexe"];
  $adresse = secureHtml(trim($_POST["adresse"]));
  $ville = secureHtml(trim($_POST["ville"]));
  $cp = secureHtml(trim($_POST["cp"]));
  $statut = 1;

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

  // Vérification du pseudo (doit être unique)
  if (empty($pseudo)) {
    $pseudoError = "Veuillez saisir un pseudo.";
  } elseif (strlen($pseudo) < 3 || strlen($pseudo) > 15) {
    $pseudoError = "Le pseudo doit avoir entre 3 et 15 caractères.";
  } elseif (verifCorrespondance($pdo, 'pseudo', $pseudo)) {
    $pseudoError = "Ce pseudo est déjà utilisé.";
  }

  $pattern = "/^[a-zA-Z0-9\.\-_]+$/";

  if (!empty($pseudo) && !preg_match($pattern, $pseudo)) {
    $pseudoError = "Caractères autorisés : lettres (majuscules et minuscules), chiffres, point, tiret (-) et underscore (_).";
  }


  // Vérification de l'email
  if (empty($email)) {
    $emailError = "Veuillez saisir une adresse email.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $emailError = "Veuillez saisir une adresse email valide.";
  } elseif (verifCorrespondance($pdo, 'email', $email)) {
    $emailError = "Cet email est déjà utilisé.";
  }

  if (empty($emailConfirm)) {
    $emailConfirmError = "Veuillez confirmer l'adresse e-mail.";
  }

  if (!empty($emailConfirm) && $email !== $emailConfirm) {
    $emailConfirmError = "Les adresses e-mail ne correspondent pas.";
  }

  // Vérification des mdp
  if (empty($mdp)) {
    $mdpError .= "Veuillez saisir un mot de passe.";
  }

  if (!empty($mdp) && strlen($mdp) < 8) {
    $mdpError .= "Veuillez saisir un mot de passe d'au moins 8 caractères.";
  }

  if (empty($mdpConfirm)) {
    $mdpConfirmError .= "Veuillez confirmer le mot de passe.";
  }

  if (!empty($mdpConfirm) && $mdp !== $mdpConfirm) {
    $mdpConfirmError .= "La confirmation du mot de passe ne correspond pas.";
  }

  $mdpHash = password_hash($mdp, PASSWORD_DEFAULT);

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



  $sexeError = secureHtml($sexeError);
  $pseudoError = secureHtml($pseudoError);
  $emailError = secureHtml($emailError);
  $emailConfirmError = secureHtml($emailConfirmError);
  $mdpError = secureHtml($mdpError);
  $mdpConfirmError = secureHtml($mdpConfirmError);
  $prenomError = secureHtml($prenomError);
  $nomError = secureHtml($nomError);
  $adresseError = secureHtml($adresseError);
  $villeError = secureHtml($villeError);
  $cpError = secureHtml($cpError);

  // Insertion dans la BDD
  if (empty($pseudoError) && empty($emailError) && empty($mdpError) && empty($prenomError) && empty($nomError) && empty($sexeError) && empty($adresseError) && empty($villeError) && empty($cpError)) {

    $reponse = $pdo->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, sexe, ville, cp, adresse, statut) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :sexe, :ville, :cp, :adresse, :statut)");
    $reponse->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $reponse->bindParam(':mdp', $mdpHash, PDO::PARAM_STR);
    $reponse->bindParam(':nom', $nom, PDO::PARAM_STR);
    $reponse->bindParam(':prenom', $prenom, PDO::PARAM_STR);
    $reponse->bindParam(':email', $email, PDO::PARAM_STR);
    $reponse->bindParam(':sexe', $sexe, PDO::PARAM_STR);
    $reponse->bindParam(':ville', $ville, PDO::PARAM_STR);
    $reponse->bindParam(':cp', $cp, PDO::PARAM_STR);
    $reponse->bindParam(':adresse', $adresse, PDO::PARAM_STR);
    $reponse->bindParam(':statut', $statut, PDO::PARAM_INT);
    $reponse->execute();
    header("Location:" . URL . "pages/form/sign_in.php?success=inscription");
  }
}

require_once("../../inc/header.inc.php");
require_once("../../inc/nav.inc.php");
?>


  <div class="container">
    <div class="row">
      <div class="col-6 mx-auto mt-5">
        <h1>Créer un compte</h1>
        <form method="POST" action="" enctype="multipart/form-data">
          <!-- SEXE -->
          <div class="mb-1 mt-4">
            <label for="exampleInputEmail1" class="form-label mr-5">Sexe</label>
            <div class="form-check form-check-inline ml-3">
              <input class="form-check-input" type="radio" id="inlineRadio1" name="sexe" value="m" required>
              <label class="form-check-label" for="inlineRadio1">Homme</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" id="inlineRadio2" name="sexe" value="f" required>
              <label class="form-check-label" for="inlineRadio2">Femme</label>
            </div>
            <div class="inscription-error"><?= $sexeError ?></div>
          </div>
          <!-- NOM -->
          <div class="mb-3">
            <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" value="<?= $nom ?>">
            <div class="inscription-error"><?= $nomError ?></div>
          </div>
          <!-- PRENOM -->
          <div class="mb-3">
            <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prenom" value="<?= $prenom ?>">
            <div class="inscription-error"><?= $prenomError ?></div>
          </div>
          <!-- PSEUDO -->
          <div class="mb-3">
            <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Pseudo (3 à 15 caractères)" value="<?= $pseudo ?>">
            <div class="inscription-error"><?= $pseudoError ?></div>
          </div>
          <!-- EMAIL -->
          <div class="mb-3">
            <input type="email" class="form-control" id="email" name="email" placeholder="Adresse email" value="<?= $email ?>">
            <div class="inscription-error"><?= $emailError ?></div>
          </div>
          <!-- CONFIRM EMAIL -->
          <div class="mb-3">
            <input type="email" class="form-control" id="email-confirm" name="email-confirm" placeholder="Confirmer l'adresse email">
            <div class="inscription-error"><?= $emailConfirmError ?></div>
          </div>
          <!-- MDP -->
          <div class="mb-3">
            <input type="password" class="form-control" id="mdp" name="mdp" placeholder="Mot de passe">
            <div class="inscription-error"><?= $mdpError ?></div>
          </div>
          <!-- CONFIRM MDP -->
          <div class="mb-3">
            <input type="password" class="form-control" id="mdp-confirm" name="mdp-confirm" placeholder="Confirmer mot de passe">
            <div class="inscription-error"><?= $mdpConfirmError ?></div>
          </div>
          <!-- ADRESSE -->
          <div class="mb-3">
            <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Adresse" value="<?= $adresse ?>">
            <div class="inscription-error"><?= $adresseError ?></div>
          </div>
          <!-- VILLE -->
          <div class="mb-3">
            <input type="text" class="form-control" id="ville" name="ville" placeholder="Ville" value="<?= $ville ?>">
            <div class="inscription-error"><?= $villeError ?></div>
          </div>
          <!-- CP -->
          <div class="mb-3">
            <input type="number" class="form-control" id="cp" name="cp" placeholder="Code Postal" value="<?= $cp ?>">
            <div class="inscription-error"><?= $cpError ?></div>
          </div>
          <!-- Bouton envoi -->
          <div class="mb-5">
            <button type="submit" class="btn btn-primary">Créer un compte</button>
          </div>
        </form>
      </div>
    </div>
  </div>

<?php
require_once("../../inc/footer.inc.php");
?>