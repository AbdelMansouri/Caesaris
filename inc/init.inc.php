<?php
// La connexion à la BDD
$host = "mysql:host=localhost;dbname=tic_site";
$login = "root";
$password = "";
$options = array(
  PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
  PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
);
$pdo = new PDO($host, $login, $password, $options);

// declaration des constantes
$title = "";
define("URL", "http://localhost/DORANCO/PHP/eshop/");

// lancement de la session utilisateur
session_start();
