<?php
require_once("inc/init.inc.php");
require_once("inc/functions.inc.php");
$title = "- Accueil";
$requete = $pdo->query("SELECT * FROM article ORDER BY stock DESC LIMIT 4");
$articles = $requete->fetchAll(PDO::FETCH_ASSOC);
require_once("inc/header.inc.php");
require_once("inc/nav.inc.php");
?>
<div class="container-fluid p-0 m-0">
  <img src="<?= URL ?>/assets/img/herobanner.jpg" alt="" class="w-100 img-fluid mb-5">
  <div class="container mt-3">
    <h3 class="text-center mb-4">Nos articles à succès</h3>
    <div class="row d-flex align-items-stretch justify-content-between">
      <?php foreach ($articles as $article) : ?>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-5">
          <div class="card h-100">
            <a href="details_article.php?id=<?= $article['id_article'] ?>">
              <div class="card-image-container">
                <img src="<?= URL ?>assets/img_produits/<?= $article['photo'] ?>" class="card-img-top img-fluid" alt="<?= $article['photo'] ?>">
              </div>
            </a>
            <div class="card-body d-flex flex-column">
              <h5 class="card-title text-2"><?= $article['titre'] ?></h5>
              <p class="card-text">Prix: <?= $article['prix'] ?> €</p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>

    </div>
  </div>
</div>


<?php
require_once("inc/footer.inc.php");
?>