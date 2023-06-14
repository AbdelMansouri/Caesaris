<?php
require_once("././inc/init.inc.php");
require_once("././inc/functions.inc.php");
$title = "- Gestion des articles";
if (!is_admin()) {
  header("Location: " . URL . "pages/form/sign_in.php");
  exit;
}
require_once("././inc/header.inc.php");
require_once("././inc/nav.inc.php");
?>

<body>


</body>

<?php
require_once("././inc/footer.inc.php");
?>