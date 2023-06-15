<?php
require_once("inc/init.inc.php");
require_once("inc/functions.inc.php");
$title = "- Boutique en ligne";
if (isset($_GET['categorie']) && isset($_GET['sexe'])) {
  $categorie = $_GET['categorie'];
  $sexe = $_GET['sexe'];
  $requete = $pdo->prepare("SELECT * FROM article WHERE categorie = :categorie AND sexe = :sexe");
  $requete->bindParam(':categorie', $categorie);
  $requete->bindParam(':sexe', $sexe);
  $requete->execute();
  $articles = $requete->fetchAll(PDO::FETCH_ASSOC);
} elseif (isset($_GET['sexe'])) {
  $sexe = $_GET['sexe'];
  $requete = $pdo->prepare("SELECT * FROM article WHERE sexe = :sexe");
  $requete->bindParam(':sexe', $sexe);
  $requete->execute();
  $articles = $requete->fetchAll(PDO::FETCH_ASSOC);
} else {
  $requete = $pdo->query("SELECT * FROM article");
  $articles = $requete->fetchAll(PDO::FETCH_ASSOC);
}
require_once("inc/header.inc.php");
require_once("inc/nav.inc.php");
?>

<div class="container-fluid min-height" id="shop">
  <div class="row mx-auto">
    <h1 class="text-center mb-5">Boutique</h1>
    <aside class="col-md-3">
      <h3>Filtres</h3>
      <div class="accordion" id="categorie-accordion">
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#homme-collapse" aria-expanded="true" aria-controls="homme-collapse">
              Homme
            </button>
          </h2>
          <div id="homme-collapse" class="accordion-collapse collapse show">
            <div class="accordion-body">
              <a href="?sexe=m" class="list-group-item">Tout les articles</a>
              <a href="?categorie=T-shirt&sexe=m" class="list-group-item">T-shirts</a>
              <a href="?categorie=Chemise&sexe=m" class="list-group-item">Chemises</a>
              <a href="?categorie=polo&sexe=m" class="list-group-item">Polos</a>
              <a href="?categorie=Veste&sexe=m" class="list-group-item">Vestes</a>
              <a href="?categorie=Manteau&sexe=m" class="list-group-item">Manteaux</a>
              <a href="?categorie=Pantalon&sexe=m" class="list-group-item">Pantalons</a>
              <a href="?categorie=Jean&sexe=m" class="list-group-item">Jeans</a>
            </div>
          </div>
        </div>
        <div class="accordion-item mb-5">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#femme-collapse" aria-expanded="false" aria-controls="femme-collapse">
              Femme
            </button>
          </h2>
          <div id="femme-collapse" class="accordion-collapse collapse">
            <div class="accordion-body">
              <a href="?sexe=f" class="list-group-item">Tout les articles</a>
              <a href="?categorie=T-shirt&sexe=f" class="list-group-item">T-shirts</a>
              <a href="?categorie=Chemise&sexe=f" class="list-group-item">Chemises</a>
              <a href="?categorie=Robe&sexe=f" class="list-group-item">Robes</a>
              <a href="?categorie=Jupe&sexe=f" class="list-group-item">Jupes</a>
              <a href="?categorie=Veste&sexe=f" class="list-group-item">Vestes</a>
              <a href="?categorie=Manteau&sexe=f" class="list-group-item">Manteaux</a>
              <a href="?categorie=Pantalon&sexe=f" class="list-group-item">Pantalons</a>
              <a href="?categorie=Jean&sexe=f" class="list-group-item">Jeans</a>
            </div>
          </div>
        </div>
      </div>
      <!-- Ajoutez d'autres filtres ici -->
    </aside>
    <div class="col-md-9 mt-5">
      <div class="container">
        <div class="row d-flex align-items-stretch">
          <?php foreach ($articles as $article) : ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
              <div class="card h-100">
                <a href="details_article.php?id=<?= $article['id_article'] ?>">
                  <div class="card-image-container" style="height: 300px;">
                    <img src="<?= URL ?>assets/img_produits/<?= $article['photo'] ?>" class="card-img-top img-fluid" alt="<?= $article['photo'] ?>">
                  </div>
                </a>
                <div class="card-body d-flex flex-column">
                  <h5 class="card-title"><?= $article['titre'] ?></h5>
                  <p class="card-text">Prix: <?= $article['prix'] ?> €</p>
                  <p class="card-text">Catégorie: <?= $article['categorie'] ?></p>
                </div>
              </div>
            </div>
          <?php endforeach; ?>

        </div>
      </div>
    </div>
  </div>
</div>


<?php
require_once("inc/footer.inc.php");
?>