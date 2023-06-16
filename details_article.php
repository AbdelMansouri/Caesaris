<?php
require_once("inc/init.inc.php");
require_once("inc/functions.inc.php");

if (isset($_GET['id'])) {
  $id_article = $_GET['id'];
  $requete = $pdo->prepare("SELECT * FROM article WHERE id_article = :id");
  $requete->bindParam(':id', $id_article);
  $requete->execute();
  if ($requete->rowCount() > 0) {
    $article = $requete->fetch(PDO::FETCH_ASSOC);
  } else {
    header("Location: " . URL . "shop.php");
    exit;
  }
}

// Ajout au panier
if (isset($_POST['quantity']) && is_numeric($_POST['quantity'])) {
  $quantity = $_POST['quantity'];

  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Ajout au panier : L'article existe déjà dans la session ?
  $articleExistant = array_search($id_article, array_column($_SESSION['cart'], 'id'));

  if ($articleExistant !== false) {
    $_SESSION['cart'][$articleExistant]['quantity'] += $quantity;
  } else {
    $cart_item = array(
      'id' => $article['id_article'],
      "photo" => $article['photo'],
      "titre" => $article['titre'],
      "prix" => $article['prix'],
      "categorie" => $article['categorie'],
      "sexe" => $article['sexe'],
      'quantity' => $quantity
    );

    $_SESSION['cart'][] = $cart_item;
  }
  header("Location: " . URL . "details_article.php" . "?id=" . $article['id_article'] . "&cart=ok");
  exit;
}

// Confirmation si ajout au panier
$cartSuccess = "";
if (isset($_GET['cart']) && $_GET['cart'] === 'ok') {
  $cartSuccess = 'Article ajouté au panier.';
}

$title = "- " . $article['titre'];
require_once("inc/header.inc.php");
require_once("inc/nav.inc.php");
?>

<div class="container min-height">
  <div class="row">
    <div class="col-lg-6 me-5">
      <img src="<?= URL ?>assets/img_produits/<?= $article['photo'] ?>" class="card-img-top img-fluid mb-5" alt="<?= $article['photo'] ?>">
    </div>
    <div class="col-lg-4 ms-5">
      <?php if (isset($_GET['cart']) && $_GET['cart'] === 'ok') : ?>
        <div class="card bg-light mb-3">
          <div class="card-body strong">
            <?= $cartSuccess ?>
          </div>
        </div>
      <?php endif; ?>
      <h1><?= $article['titre'] ?></h1>
      <p>Prix : <span class="text-muted"><?= $article['prix'] ?> €</span></p>
      <p>Catégorie : <span class="text-muted"><?= $article['categorie'] ?></span></p>
      <p>Taille disponible : <span class="text-muted"><?= $article['taille'] ?></span></p>
      <p class="mb-0">Description :</p>
      <p class="text-muted"><?= $article['description'] ?></p>
      <form action="" method="POST" enctype="multipart/form-data">
        <label for="quantity" class="form-label mt-5">Quantité :</label>
        <input type="number" id="quantity" name="quantity" class="form-control w-25" min="1" value="1">
        <button class="btn btn-primary w-100 p-3 d-block mx-auto mt-3 mb-5">Ajouter au panier</button>
      </form>
    </div>
  </div>
</div>


<?php
require_once("inc/footer.inc.php");
?>