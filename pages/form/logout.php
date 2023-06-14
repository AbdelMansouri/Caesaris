<?php
require_once("../../inc/init.inc.php");
session_unset();
session_destroy();

header("Location: " . URL . "pages/form/sign_in.php");
