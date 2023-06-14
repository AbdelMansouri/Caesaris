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

