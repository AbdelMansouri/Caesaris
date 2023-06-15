<?php
require_once("../../inc/init.inc.php");
require_once("../../inc/functions.inc.php");
$title = "- Gestion des articles";
$titre = "";

if (!is_admin()) {
  header("Location: " . URL . "pages/form/sign_in.php");
  exit;
}

if (isset($_GET['id'])) {
  $id_article = $_GET['id'];
  $reponse = $pdo->prepare("SELECT * FROM article WHERE id_article = :id");
  $reponse->bindParam(':id', $id_article, PDO::PARAM_STR);
  $reponse->execute();
  $article = $reponse->fetch(PDO::FETCH_ASSOC);
  $reference = $article['reference'];
  $categorie = $article['categorie'];
  $titre = $article['titre'];
  $description = $article['description'];
  $couleur = $article['couleur'];
  $taille = $article['taille'];
  $sexe = $article['sexe'];
  $photoApercu = $article['photo'];
  $prix = $article['prix'];
  $stock = $article['stock'];
  $_SESSION["editArticle"] = true;
} else {
  $reference = '';
  $categorie = '';
  $titre = '';
  $description = '';
  $couleur = '';
  $taille = '';
  $sexe = '';
  $photoApercu = '';
  $prix = '';
  $stock = '';
}

$referenceError = '';
$categorieError = '';
$titreError = '';
$descriptionError = '';
$couleurError = '';
$tailleError = '';
$sexeError = '';
$photoError = '';
$prixError = '';
$stockError = '';


if (isset($_POST["reference"]) && isset($_POST["categorie"]) && isset($_POST["titre"]) && isset($_POST["description"]) && isset($_POST["couleur"]) && isset($_POST["taille"]) && isset($_POST["sexe"]) && isset($_POST["prix"]) && isset($_POST["stock"]) && isset($_FILES["photo"])) {
  $reference = secureHtml(trim($_POST["reference"]));
  $categorie = secureHtml(trim($_POST["categorie"]));
  $titre = secureHtml(trim($_POST["titre"]));
  $description = secureHtml(trim($_POST["description"]));
  $couleur = secureHtml(trim($_POST["couleur"]));
  $taille = secureHtml(trim($_POST["taille"]));
  $sexe = secureHtml(trim($_POST["sexe"]));
  $prix = secureHtml(trim($_POST["prix"]));
  $stock = secureHtml(trim($_POST["stock"]));
  $photo = $_FILES["photo"];
  $photoName = $photo["name"];
  $photoTemp = $photo["tmp_name"];
  $destination = "../../assets/img_produits/";

  // Vérification de la photo
  if (!isset($_FILES['photo']) || $_FILES['photo']['error'] != UPLOAD_ERR_OK) {
    $photoError = "Veuillez sélectionner une photo.";
  } else {
    $allowedExtensions = array('jpg', 'jpeg', 'png');
    $photoExtension = strtolower(pathinfo($photoName, PATHINFO_EXTENSION));

    if (!in_array($photoExtension, $allowedExtensions)) {
      $photoError = "Extension de fichier non valide. Les extensions autorisées sont : jpg, jpeg, png.";
    } else {
      $photoName = preg_replace('/[^a-zA-Z0-9_.-]/', '', strtolower($photoName));
      $photoName = str_replace(' ', '_', $photoName);
      $destination = $destination . $photoName;
    }
  }

  // Vérification de la référence
  if (empty($reference)) {
    $referenceError = "Veuillez sélectionner la référence.";
  } elseif (!is_numeric($reference)) {
    $referenceError = "La référence doit être composée uniquement de chiffres.";
  } elseif (strlen($reference) > 15) {
    $referenceError = "La référence doit être composée de 15 chiffres au maximum.";
  } elseif (isset($_SESSION["editArticle"]) && verifCorrespondanceEditArticle($pdo, 'reference', $reference, $id_article)) {
    $referenceError = "Cette référence est déjà utilisée.";
  } elseif (!isset($_SESSION["editArticle"]) && verifCorrespondanceNewArticle($pdo, 'reference', $reference)) {
    $referenceError = "Cette référence est déjà utilisée.";
  }
  // Vérification du titre
  if (empty($titre)) {
    $titreError = "Veuillez saisir un titre à l'article (nom).";
  } elseif (strlen($titre) > 100) {
    $titreError = "Le titre ne doit pas dépasser 100 caractères.";
  }
  // Vérification de la catégorie
  if (empty($categorie) || $categorie == "Choisissez la catégorie") {
    $categorieError = "La catégorie est obligatoire.";
  }
  // Vérification de la description
  if (empty($description)) {
    $descriptionError = "Veuillez saisir une descritption pour l'article.";
  }
  // Vérification de la couleur
  if (empty($couleur)) {
    $couleurError = "Veuillez saisir une couleur pour l'article.";
  } elseif (!preg_match('/^[a-zA-Z]+$/', $couleur)) {
    $couleurError = "La couleur ne doit contenir que des lettres.";
  }
  // Vérification de la taille
  if (empty($taille) || $taille == "Choisissez la taille") {
    $tailleError .= "Veuillez sélectionner la taille.";
  }
  // Vérification du sexe
  if (empty($sexe) || $sexe == "Choisissez la sexe") {
    $sexeError = "Le sexe est obligatoire.";
  }
  // Vérification du prix
  if (empty($prix)) {
    $prixError = "Veuillez saisir le prix de l'article.";
  } elseif (!is_numeric($prix) || $prix <= 0) {
    $prixError = "Le prix doit être un nombre positif.";
  }
  // Vérification du stock
  if (empty($stock)) {
    $stockError = "Veuillez saisir le stock actuel de l'article.";
  } elseif (!is_numeric($stock) || $stock < 0) {
    $stockError = "Le stock doit être un nombre positif.";
  }

  // Insertion dans la BDD
  if (empty($referenceError) && empty($categorieError) && empty($titreError) && empty($descriptionError) && empty($couleurError) && empty($tailleError) && empty($sexeError) && empty($prixError) && empty($stockError)) {

    if (!move_uploaded_file($photoTemp, $destination)) {
      $photoError = "Une erreur s'est produite lors du téléchargement de la photo.";
    }

    if (isset($_SESSION["editArticle"])) {
      $reponse = $pdo->prepare("UPDATE article SET reference = :reference, categorie = :categorie, titre = :titre, description = :description, couleur = :couleur, taille = :taille, sexe = :sexe, prix = :prix, stock = :stock, photo = IF(:newPhoto = '', photo, :newPhoto) WHERE id_article = :id");
      $reponse->bindParam(':reference', $reference, PDO::PARAM_STR);
      $reponse->bindParam(':categorie', $categorie, PDO::PARAM_STR);
      $reponse->bindParam(':titre', $titre, PDO::PARAM_STR);
      $reponse->bindParam(':description', $description, PDO::PARAM_STR);
      $reponse->bindParam(':couleur', $couleur, PDO::PARAM_STR);
      $reponse->bindParam(':taille', $taille, PDO::PARAM_STR);
      $reponse->bindParam(':sexe', $sexe, PDO::PARAM_STR);
      $reponse->bindParam(':prix', $prix, PDO::PARAM_STR);
      $reponse->bindParam(':stock', $stock, PDO::PARAM_STR);
      $reponse->bindParam(':newPhoto', $photoName, PDO::PARAM_STR);
      $reponse->bindParam(':id', $id_article, PDO::PARAM_STR);
      $reponse->execute();
      header("Location:" . URL . "pages/admin/gestion_article.php");
    } elseif (!isset($_SESSION["editArticle"]) && empty($photoError)) {
      $reponse = $pdo->prepare("INSERT INTO article (reference, categorie, titre, description, couleur, taille, sexe, prix, stock, photo) VALUES (:reference, :categorie, :titre, :description, :couleur, :taille, :sexe, :prix, :stock, :photo)");
      $reponse->bindParam(':reference', $reference, PDO::PARAM_STR);
      $reponse->bindParam(':categorie', $categorie, PDO::PARAM_STR);
      $reponse->bindParam(':titre', $titre, PDO::PARAM_STR);
      $reponse->bindParam(':description', $description, PDO::PARAM_STR);
      $reponse->bindParam(':couleur', $couleur, PDO::PARAM_STR);
      $reponse->bindParam(':taille', $taille, PDO::PARAM_STR);
      $reponse->bindParam(':sexe', $sexe, PDO::PARAM_STR);
      $reponse->bindParam(':prix', $prix, PDO::PARAM_STR);
      $reponse->bindParam(':stock', $stock, PDO::PARAM_STR);
      $reponse->bindParam(':photo', $photoName, PDO::PARAM_STR);
      $reponse->execute();
      header("Location:" . URL . "pages/admin/gestion_article.php");
    }
  }
}

require_once("../../inc/header.inc.php");
require_once("../../inc/nav.inc.php");
?>

<div class="container mb-5" id="new-article">
  <h1 class="text-center mb-5">
    <?php if (isset($_SESSION["editArticle"])) : ?>
      Modification d'un article
    <?php else : ?>
      Ajout d'un article
    <?php endif; ?>

  </h1>
  <div class="card col-12 mx-auto p-3 mb-3">
    <form action="" method="POST" enctype="multipart/form-data">
      <div class="d-flex">
        <div class="col-5">
          <div class="apercu-article-image mx-auto d-flex align-temps-center justify-content-center border">
            <?php if (!empty($photoApercu)) : ?>
              <img id="article-image" src="<?= URL ?>assets/img_produits/<?= $photoApercu ?>" class="card-img-top d-block mx-auto" alt="Photo de l'article">
            <?php else : ?>
              <img id="article-image" src="<?= URL ?>assets/img/logo.png" class="card-img-top d-block mx-auto" alt="Logo si pas d'image article">
            <?php endif; ?>
          </div>
          <label for="exampleInputEmail1" class="form-label mb-0 fw-bold">Image :</label>
          <input type="file" class="form-control" id="photo-input" name="photo" value="">
          <div class="inscription-error mb-4"><?= $photoError ?></div>
          <label for="exampleInputEmail1" class="form-label mb-0 fw-bold">Titre :</label>
          <input type="text" class="form-control" id="titre" name="titre" placeholder="Titre" value="<?= $titre ?>">
          <div class="inscription-error mb-4"><?= $titreError ?></div>
          <label for="exampleInputEmail1" class="form-label mb-0 fw-bold">Catégorie :</label>
          <select class="form-select form-select-md" aria-label=".form-select-md example" id="categorie" name="categorie">
            <option value="">Choisissez une catégorie</option>
            <option value="tee-shirt" <?= ($categorie === 't-shirt') ? 'selected' : '' ?>>T-shirt</option>
            <option value="chemise" <?= ($categorie === 'chemise') ? 'selected' : '' ?>>Chemise</option>
            <option value="polo" <?= ($categorie === 'polo') ? 'selected' : '' ?>>Polo</option>
            <option value="veste" <?= ($categorie === 'veste') ? 'selected' : '' ?>>Veste</option>
            <option value="manteau" <?= ($categorie === 'manteau') ? 'selected' : '' ?>>Manteau</option>
            <option value="pantalon" <?= ($categorie === 'pantalon') ? 'selected' : '' ?>>Pantalon</option>
            <option value="jean" <?= ($categorie === 'jean') ? 'selected' : '' ?>>Jean</option>
            <option value="robe" <?= ($categorie === 'robe') ? 'selected' : '' ?>>Robe</option>
            <option value="jupe" <?= ($categorie === 'jupe') ? 'selected' : '' ?>>Jupe</option>
          </select>
          <div class="inscription-error mb-4"><?= $categorieError ?></div>
          <label for="exampleInputEmail1" class="form-label mb-0 fw-bold">Description :</label>
          <input type="text" class="form-control" id="description" name="description" placeholder="Description" value="<?= $description ?>">
          <div class="inscription-error mb-4"><?= $descriptionError ?></div>
        </div>



        <div class="col-5 ms-auto mt-4">
          <label for="exampleInputEmail1" class="form-label mb-0 fw-bold">Référence (unique) :</label>
          <input type="text" class="form-control" id="reference" name="reference" placeholder="Référence" value="<?= $reference ?>">
          <div class="inscription-error mb-4"><?= $referenceError ?></div>
          <label for="exampleInputEmail1" class="form-label mb-0 fw-bold">Couleur :</label>
          <input type="text" class="form-control" id="couleur" name="couleur" placeholder="Couleur" value="<?= $couleur ?>">
          <div class="inscription-error mb-4"><?= $couleurError ?></div>
          <label for="exampleInputEmail1" class="form-label mb-0 fw-bold">Taille :</label>
          <select class="form-select form-select-md" aria-label=".form-select-md example" name="taille">
            <option value="">Choisissez la taille</option>
            <option value="XS" <?= ($taille === 'XS') ? 'selected' : '' ?>>XS</option>
            <option value="S" <?= ($taille === 'S') ? 'selected' : '' ?>>S</option>
            <option value="M" <?= ($taille === 'M') ? 'selected' : '' ?>>M</option>
            <option value="L" <?= ($taille === 'L') ? 'selected' : '' ?>>L</option>
            <option value="XL" <?= ($taille === 'XL') ? 'selected' : '' ?>>XL</option>
          </select>
          <div class="inscription-error mb-4"><?= $tailleError ?></div>
          <label for="exampleInputEmail1" class="form-label mb-0 fw-bold">Sexe :</label>
          <select class="form-select form-select-md" aria-label=".form-select-md example" name="sexe">
            <option value="">Choisissez le sexe</option>
            <option value="m" <?= ($sexe === 'm') ? 'selected' : '' ?>>Homme</option>
            <option value="f" <?= ($sexe === 'f') ? 'selected' : '' ?>>Femme</option>
          </select>
          <div class="inscription-error mb-4"><?= $sexeError ?></div>
          <label for="exampleInputEmail1" class="form-label mb-0 fw-bold">Prix :</label>
          <input type="number" class="form-control" id="prix" name="prix" placeholder="Prix" value="<?= $prix ?>">
          <div class="inscription-error mb-4"><?= $prixError ?></div>
          <label for="exampleInputEmail1" class="form-label mb-0 fw-bold">Stock :</label>
          <input type="number" class="form-control" id="stock" name="stock" placeholder="Stock" value="<?= $stock ?>">
          <div class="inscription-error mb-4"><?= $stockError ?></div>
        </div>
      </div>



  </div>
  <div class="col-12 d-flex">
    <a href="<?= URL ?>pages/admin/gestion_article.php" class="btn btn-secondary col-3 d-block ms-auto p-2">Annuler</a>
    <button type="submit" class="btn btn-primary col-3 d-block ms-4 p-2">
      <?php if (isset($_SESSION["editArticle"])) : ?>
        Modifier l'article
      <?php else : ?>
        Ajouter l'article
      <?php endif; ?>
    </button>
  </div>
  </form>
</div>
<?php
require_once("../../inc/footer.inc.php");
?>