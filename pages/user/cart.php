<?php
require_once("../../inc/init.inc.php");
require_once("../../inc/functions.inc.php");
$title = "- Votre Panier";

if (isset($_POST['supprimer'])) {
  $id_article = $_POST['supprimer'];
  supprimerArticlePanier($id_article);
  header("Location:" . URL . "pages/user/cart.php");
}

require_once("../../inc/header.inc.php");
require_once("../../inc/nav.inc.php");
?>
<?php if (!empty($_SESSION['cart'])) : ?>
  <div class="container min-height" id="cart">
    <h1 class="text-center mb-3">Votre panier</h1>
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Articles</h5>
            <?php foreach ($_SESSION['cart'] as $cart_item) : ?>
              <div class="card mb-3 d-flex flex-row bg-light">
                <div class="col-md-4">
                  <img src="<?= URL ?>assets/img_produits/<?= $cart_item['photo'] ?>" alt="<?= $cart_item['photo'] ?>" class="img-fluid">
                </div>
                <div class="col-md-8">
                  <h6><?= $cart_item['titre'] ?></h6>
                  <p>Prix unitaire: <?= $cart_item['prix'] ?> €</p>
                  <p>Quantité: <?= $cart_item['quantity'] ?></p>
                  <p>Total: <?= $cart_item['prix'] * $cart_item['quantity'] ?> €</p>
                  <form action='' method='POST'>
                    <button type='submit' name='supprimer' value="<?= $cart_item['id'] ?>" class='btn btn-danger btn-sm'>Supprimer</button>
                  </form>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Résumé</h5>
            <p>Total: <?= calculerTotalPanier() ?> €</p>
            <a href="commande.php" class="btn btn-primary">Commander</a>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php else : ?>
  <div class="container min-height d-flex justify-content-center align-items-center" id="cart">
    <div class="d-flex flex-column justify-content-center align-items-center text-center">
      <p class="text-2">Votre panier est vide</p>
      <p class="text-muted">Il semble que vous n’avez pas encore choisi de produit</p>
      <a href="<?= URL ?>shop.php" class="btn btn-secondary btn-sm p-2 ps-3 pe-3">Acheter maintenant</a>
    </div>
  </div>
<?php endif; ?>


<?php
require_once("../../inc/footer.inc.php");
?>