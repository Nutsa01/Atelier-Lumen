<?php
// Gestion de la session
session_start();

// Inclure le fichier de config du site
include("../config/config.php");

// Inclure la librairie "SQL" pour manipuler les données dans la BDR.
include("../lib/sql.lib.php");
include("../lib/outils.lib.php");
// Obtenir une connexion au serveur de BD.
$cnx = connexion();

// Inclure la librairie de gestion du panier.
include("../lib/panier.lib.php");

// On récupère l'identifiant du panier de l'utilisateur (session, sinon témoin
// HTTP, sinon nouveau panier).
$idPanier = etablirPanier($cnx);

// Le paramètre "action" indique l'opération souhaitée sur le panier :
// 'ajouter', 'modifier' ou 'supprimer'.
// Les modifications sont envoyées en POST (jamais par un simple lien).
$action = $_POST["action"] ?? "";

switch($action) {
  case 'ajouter' :
    $pid = (int) ($_POST["pid"] ?? 0);
    if ($pid > 0) {
      ajouterArticle($cnx, $idPanier, $pid);
    }
    break;

  case 'supprimer' :
    $aid = (int) ($_POST["aid"] ?? 0);
    supprimerArticle($cnx, $idPanier, $aid);
    break;

  case 'modifier' :
    $aid = (int) ($_POST["aid"] ?? 0);
    $qte = (int) ($_POST["qte"] ?? 0);
    modifierArticle($cnx, $idPanier, $qte, $aid);
    break;
}

// Quelle que soit l'opération, on retourne toujours le détail du panier
// après les modifications.
header("Content-Type: application/json; charset=utf-8");
echo json_encode(obtenirDetailPanier($cnx, $idPanier, langueDuVisiteur()));
