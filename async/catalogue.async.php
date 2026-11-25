<?php
// Retourne en JSON les produits filtrés, cherchés et triés (page Collection).
include("../config/config.php");
include("../lib/sql.lib.php");
include("../lib/outils.lib.php");
include("../lib/catalogue.lib.php");

$tri = $_GET["tri"] ?? "RAND()";
$filtre = $_GET["filtre"] ?? "tous";
$recherche = $_GET["q"] ?? "";

$cnx = connexion();
$produits = obtenirProduits($cnx, $tri, $filtre, $recherche, langueDuVisiteur());

header("Content-Type: application/json; charset=utf-8");
echo json_encode($produits);
