<?php
require_once("../../inc/init.inc.php");
require_once("../../inc/functions.inc.php");
$title = "- Votre Panier";

if (isset($_POST['supprimer'])) {
  $id_article = $_POST['supprimer'];
  supprimerArticlePanier($id_article);
  header("Location:" . URL . "pages/user/cart.php");
}


if (isset($_POST['modifQuantity'])) {
  $article_id = $_POST['article_id'];
  $action = $_POST['modifQuantity'];
  foreach ($_SESSION['cart'] as $changeur => $cart_item) {
    if ($cart_item['id'] === $article_id) {
      if ($action === 'minus') {
        $_SESSION['cart'][$changeur]['quantity'] -= 1;
        if ($_SESSION['cart'][$changeur]['quantity'] < 1) {
          unset($_SESSION['cart'][$changeur]);
        }
      } elseif ($action === 'plus') {
        $_SESSION['cart'][$changeur]['quantity'] += 1;
      }
      break;
    }
  }
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}




require_once("../../inc/header.inc.php");
require_once("../../inc/nav.inc.php");
?>
<?php if (!empty($_SESSION['cart'])) : ?>
  <div class="container min-height" id="cart">
    <h1 class="text-center mb-3">Votre panier</h1>
    <div class="row">
      <div class="col-md-9 mb-5">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Articles</h5>
            <?php
            $totalQuantity = 0;
            foreach ($_SESSION['cart'] as $cart_item) :
              $totalQuantity += $cart_item['quantity'];
            ?>
              <div class="card mb-3 flex-row bg-light cart-card">
                <div class="col-md-3">
                  <img src="<?= URL ?>assets/img_produits/<?= $cart_item['photo'] ?>" alt="<?= $cart_item['photo'] ?>" class="img-fluid">
                </div>
                <div class="col-md-9 cart-card pt-3 pb-3 d-flex justify-content-around align-items-center">
                  <div class="">
                    <a class="a-text d-block" href="<?= URL ?>details_article.php?id=<?= $cart_item['id'] ?>"><?= $cart_item['titre'] ?></a>
                    <a class="a-text-muted d-block" href="<?= URL ?>shop.php?categorie=<?= $cart_item['categorie'] ?>&sexe=<?= $cart_item['sexe'] ?>">Caesaris - <?= $cart_item['categorie'] ?></a>
                  </div>
                  <form action='' method='POST' class="d-flex align-items-center justify-content-center fw-bold modificateur">
                    <input type="hidden" name="article_id" value="<?= $cart_item['id'] ?>">
                    <button type='submit' name='modifQuantity' value='minus' class='btn btn-outline-secondary btn-sm me-2 mb-0 rounded-0'>-</button>
                    <p class="mb-0 ms-2 me-2"><?= $cart_item['quantity'] ?></p>
                    <button type='submit' name='modifQuantity' value='plus' class='btn btn-outline-secondary btn-sm ms-2 mb-0 rounded-0'>+</button>
                  </form>



                  <div class="d-flex">
                    <p class="mb-0 me-2">Prix :</p>
                    <span class="fw-bold"><?= $cart_item['prix'] ?> €</span>
                  </div>
                  <form action='' method='POST'>
                    <button type='submit' name='supprimer' value="<?= $cart_item['id'] ?>" class='btn btn-primary btn-sm mb-0'>Supprimer</button>
                  </form>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-4">Résumé</h5>
            <div class="d-flex ">
              <?php $panier = calculerTotalPanier(); ?>
              <?php if ($totalQuantity > 1) : ?>
                <div class="d-flex">
                  <span class="me-2 mb-0 text-08"><?= $totalQuantity ?></span>
                  <p class="mb-0 text-08">produits</p>
                </div>
                <span class="ms-auto me-4 fw-bold mb-0"><?= $panier['totalSansFrais'] ?> €</span>
              <?php else : ?>
                <div class="d-flex ">
                  <span class="me-2 mb-0 text-08"><?= $totalQuantity ?></span>
                  <p class="mb-0 text-08">produit</p>
                </div>
                <span class="ms-auto me-4 fw-bold mb-0"><?= $panier['totalSansFrais'] ?> €</span>
              <?php endif; ?>
            </div>
            <div class="d-flex">
              <p class="text-08">Frais de livraison</p>
              <span class="ms-auto me-4 fw-bold"><?= $panier['fraisLivraison'] ?> €</span>
            </div>
            <hr class="mb-2 mt-0">
            <div class="d-flex align-items-end">
              <p class="mb-1 total">Total: </p>
              <span class="text-2 ms-auto me-4"><?= $panier['total'] ?> €</span>
            </div>
            <hr class="mt-2 mb-3">
            <a href="" class="btn btn-secondary w-100">Commander</a>
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