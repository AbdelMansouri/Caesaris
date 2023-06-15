<?php
require_once("../../inc/init.inc.php");
require_once("../../inc/functions.inc.php");
$title = "- Gestion des articles";
if (!is_admin()) {
  header("Location: " . URL . "pages/form/sign_in.php");
  exit;
}

if (isset($_POST['supprimer'])) {
  $id_article = $_POST['supprimer'];
  $requete = $pdo->prepare("DELETE FROM article WHERE id_article = :id");
  $requete->bindParam(':id', $id_article, PDO::PARAM_INT);
  $requete->execute();
  header("Location:" . URL . "pages/admin/gestion_article.php");
}

require_once("../../inc/header.inc.php");
require_once("../../inc/nav.inc.php");
?>

<div class="container-fluid" id="gestion-article">
  <div class="col-12 mx-auto">
    <h1 class="text-center mb-3">Gestion de la boutique</h1>
    <div class="container">
      <div class="card mb-5">
          <a href="<?= URL ?>pages/admin/edit_article.php" class="btn btn-secondary w-25 m-3 mx-auto">Ajouter un article</a>
      </div>
    </div>
    <div class="table-responsive ps-5 pe-5">
      <table class="table">
        <?php
        $reponse = $pdo->query("SELECT * FROM article");
        echo "<tr>";
        echo "<th class='infos-form text-center p-0' style='max-width: 150px;'>Photo</th>";
        for ($i = 0; $i < $reponse->columnCount(); $i++) {
          $infosColonne = $reponse->getColumnMeta($i);
          if ($infosColonne['name'] !== 'photo' && $infosColonne['name'] !== 'id_article') {
            echo "<th class='infos-form text-center p-0' style='max-width: 150px;'>" . str_replace("_", " ", ucfirst($infosColonne['name'])) . "</th>";
          }
        }
        echo "</tr>";
        while ($ligne = $reponse->fetch(PDO::FETCH_ASSOC)) {
          echo "<tr>";
          echo "<td class='value-form text-center text-truncate p-0' style='max-width: 150px;'><img src='" . URL . "assets/img_produits/" . $ligne['photo'] . "' alt='" . $ligne['photo'] . "' style='width: 150px;'></td>";
          foreach ($ligne as $colonne => $valeur) {
            if ($colonne !== 'photo' && $colonne !== 'id_article') {
              echo "<td class='value-form text-center text-truncate' style='max-width: 200px;'>$valeur</td>";
            }
          }
          echo "<td class='value-form text-center text-truncate' style='max-width: 150px;'>";
          echo "<a class='btn btn-primary btn-sm me-2' href='edit_article.php?id=" . $ligne['id_article'] . "'>Modifier</a>";
          echo "<form action='' method='POST' class='d-inline'>";
          echo "<button type='submit' name='supprimer' value='" . $ligne['id_article'] . "' class='btn btn-danger btn-sm'>Supprimer</button>";
          echo "</form>";
          echo "</td>";
          echo "</tr>";
        }
        ?>
      </table>
    </div>
  </div>
</div>

<?php
require_once("../../inc/footer.inc.php");
?>