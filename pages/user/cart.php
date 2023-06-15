<?php
require_once("../../inc/init.inc.php");
require_once("../../inc/functions.inc.php");
$title = "- Votre Panier";
if (!is_user()) {
  header("Location: " . URL . "pages/form/sign_in.php");
  exit;
}
require_once("../../inc/header.inc.php");
require_once("../../inc/nav.inc.php");
var_dump($_SESSION);
?>

<div class="container min-height">
  <h1>Votre panier</h1>
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>Article</th>
          <th>Prix</th>
          <th>Quantité</th>
          <th>Total</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <!-- Boucle pour afficher les articles dans le panier -->
        <?php foreach ($_SESSION['cart'] as $cart_item) : ?>
          <tr>
            <td><?= $cart_item['article']['titre'] ?></td>
            <td><?= $cart_item['article']['prix'] ?> €</td>
            <td><?= $cart_item['quantity'] ?></td>
            <td><?= $cart_item['article']['prix'] * $cart_item['quantity'] ?> €</td>
            <td>
              <a href="supprimer_article_panier.php?id=<?= $cart_item['article']['id_article'] ?>" class="btn btn-danger">Supprimer</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="text-end">
    <h4>Total: <?= calculerTotalPanier() ?> €</h4>
    <a href="commande.php" class="btn btn-primary">Passer la commande</a>
  </div>
</div>

<?php
require_once("../../inc/footer.inc.php");
?>