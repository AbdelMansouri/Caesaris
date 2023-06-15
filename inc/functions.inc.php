<?php
// ----------------------- Vérification du statut -----------------------------
function is_user()
{
  return isset($_SESSION['userData']) && $_SESSION['userData']['statut'] == '1';
}

function is_admin()
{
  return isset($_SESSION['userData']) && $_SESSION['userData']['statut'] == '2';
}

// ----------------------- Formulaire d'inscription -----------------------------
// Sécurité : pour éviter les codes HTML dans les champs
function secureHtml($string)
{
  return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

// Pour éviter les doublons (valeur unique)
function verifCorrespondance($pdo, $colonne, $valeur)
{
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt = "SELECT COUNT(*) FROM membre WHERE $colonne = :value";
  $reponse = $pdo->prepare($stmt);
  $reponse->bindParam(':value', $valeur, PDO::PARAM_STR);
  $reponse->execute();
  $correspondance = $reponse->fetchColumn();

  if ($correspondance > 0) {
    return true;
  } else {
    return false;
  }
}

// Pour éviter les doublons article cadre Modification (valeur unique)
function verifCorrespondanceEditArticle($pdo, $colonne, $valeur, $id_article)
{
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt = "SELECT COUNT(*) FROM article WHERE $colonne = :value AND id_article != :id";
  $reponse = $pdo->prepare($stmt);
  $reponse->bindValue(':value', $valeur, PDO::PARAM_STR);
  $reponse->bindValue(':id', $id_article, PDO::PARAM_INT);
  $reponse->execute();
  $correspondance = $reponse->fetchColumn();

  if ($correspondance > 0) {
    return true;
  } else {
    return false;
  }
}

// Pour éviter les doublons article cadre Ajout (valeur unique)
function verifCorrespondanceNewArticle($pdo, $colonne, $valeur)
{
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt = "SELECT COUNT(*) FROM article WHERE $colonne = :value";
  $reponse = $pdo->prepare($stmt);
  $reponse->bindParam(':value', $valeur, PDO::PARAM_STR);
  $reponse->execute();
  $correspondance = $reponse->fetchColumn();

  if ($correspondance > 0) {
    return true;
  } else {
    return false;
  }
}

function calculerTotalPanier()
{
  $total = 0;

  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $cart_item) {
      $prix_article = $cart_item['article']['prix'];
      $quantite = $cart_item['quantity'];
      $total += $prix_article * $quantite;
    }
  }

  return $total;
}
