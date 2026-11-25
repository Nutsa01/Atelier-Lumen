<?php
// Quantité maximale d'un même article dans le panier.
const QTE_MAX = 99;

/**
 * Met à jour la date de dernière modification du panier.
 *
 * @param Object $cnx : objet de connexion BD.
 * @param Int $idPanier : identifiant du panier dans la BD.
 */
function noterModificationPanier($cnx, $idPanier) {
  modifier($cnx, "UPDATE panier SET ddm = NOW() WHERE id = " . (int) $idPanier);
}

/**
 * Créé un nouveau panier d'achats.
 * 
 * @param Object $cnx : objet de connexion BD.
 * @param String $numeroPanier : chaîne représentant un numero unique de panier.
 * 
 * @return Int : identifiant du nouveau panier inséré dans la BD.
 * 
 */
function creerPanier($cnx, $numeroPanier) {
  return creer($cnx, "INSERT INTO panier VALUES (0, '$numeroPanier', NOW())"); 
}

/**
 * Obtient l'identifiant d'un panier existant.
 * 
 * @param Object $cnx : objet de connexion BD.
 * @param String $numeroPanier : chaîne représentant un numero unique de panier.
 * 
 * @return Int : identifiant du panier ou 0 si aucun panier ne correspond au
 *               numéro donné en argument.
 */
function obtenirIdPanier($cnx, $numeroPanier) {
  $numeroPanier = mysqli_real_escape_string($cnx, $numeroPanier);
  $resIdPanier = lire($cnx, "SELECT id FROM panier WHERE numero = '$numeroPanier'");
  // Si on trouve un enregistrement correspondant à ce numéro : 
  if(count($resIdPanier) > 0) {
    // On retourne l'identifiant du panier :
    return (int) $resIdPanier[0]["id"];
  }
  // Sinon, on retourne 0
  else {
    return 0;
  }
}

/**
 * Vérifie qu'un panier existe encore dans la BD.
 */
function panierExiste($cnx, $idPanier) {
  $idPanier = (int) $idPanier;
  return count(lire($cnx, "SELECT id FROM panier WHERE id = $idPanier")) > 0;
}

/**
 * Établit l'identifiant du panier de l'utilisateur (session, puis témoin
 * HTTP, sinon création d'un nouveau panier) et le garde en session.
 * À appeler avant tout affichage (le témoin HTTP doit être envoyé avant).
 *
 * @return Int : identifiant du panier dans la BD.
 */
function etablirPanier($cnx) {
  // Panier déjà en session et toujours présent dans la BD : on le garde.
  if (isset($_SESSION["idPanier"]) && panierExiste($cnx, $_SESSION["idPanier"])) {
    return (int) $_SESSION["idPanier"];
  }

  $idPanier = 0;
  // Panier identifié par le témoin HTTP ?
  if (isset($_COOKIE["lumen-panier"])) {
    $idPanier = obtenirIdPanier($cnx, $_COOKIE["lumen-panier"]);
  }

  // Aucun panier valide : on en crée un nouveau.
  if ($idPanier === 0) {
    $numeroPanier = uniqid('lumen-', true);
    setcookie('lumen-panier', $numeroPanier, time()+3600*24*365, "/");
    $idPanier = (int) creerPanier($cnx, $numeroPanier);
  }

  $_SESSION["idPanier"] = $idPanier;
  return $idPanier;
}

/**
 * Obtenir le détail du contenu du panier d'achats d'un utilisateur du site.
 * 
 * @param Object $cnx : objet de connexion BD.
 * @param Int $idPanier : identifiant du panier dans la BD.
 * @param String $langue : code de langue du nom des produits ('fr' par défaut).
 * 
 * @return Array : tableau numérique contenant le détail de chaque article du 
 *                 panier sous forme d'un tableau associatif.
 */
function obtenirDetailPanier($cnx, $idPanier, $langue='fr') {
  $idPanier = (int) $idPanier;
  $colNom = colonneNom($langue);
  $info = lire($cnx, "SELECT pa.*, prd.$colNom AS nom, prd.prix, prd.image 
                        FROM panier_article AS pa 
                          JOIN produit AS prd 
                            ON pa.id_produit = prd.id
                        WHERE id_panier=$idPanier");
  return $info;
}


/**
 * Ajoute un article dans le panier.
 * 
 * @param Object $cnx : objet de connexion BD.
 * @param Int $idPanier : identifiant du panier dans la BD.
 * @param Int $idProduit : identifiant du produit à ajouter.
 * 
 * @return Int : identifiant de l'article de panier ajouté.
 * 
 */
function ajouterArticle($cnx, $idPanier, $idProduit) {
  $idPanier = (int) $idPanier;
  $idProduit = (int) $idProduit;
  noterModificationPanier($cnx, $idPanier);
  $produitExiste = lire($cnx, "SELECT qte FROM panier_article WHERE id_produit = $idProduit AND id_panier = $idPanier");
  if (count($produitExiste) == 0) {
    return creer($cnx,
            "INSERT INTO panier_article VALUES (0, $idProduit, $idPanier, 1)");
  } else {
    $qte = min($produitExiste[0]['qte'] + 1, QTE_MAX);
    return modifier($cnx, "UPDATE panier_article SET qte = $qte WHERE id_produit = $idProduit AND id_panier = $idPanier");
  }
}

/**
 * Modifie la quantité d'un article du panier (supprime l'article si la
 * quantité est 0 ou moins).
 */
function modifierArticle($cnx, $idPanier, $qte, $aid) {
  $idPanier = (int) $idPanier;
  $qte = (int) $qte;
  $aid = (int) $aid;
  if ($qte <= 0) {
    return supprimerArticle($cnx, $idPanier, $aid);
  }
  $qte = min($qte, QTE_MAX);
  noterModificationPanier($cnx, $idPanier);
  return modifier($cnx, "UPDATE panier_article SET qte = $qte WHERE id = $aid AND id_panier = $idPanier");
}

/**
 * Supprime un article du panier.
 */
function supprimerArticle($cnx, $idPanier, $aid) {
  $idPanier = (int) $idPanier;
  $aid = (int) $aid;
  noterModificationPanier($cnx, $idPanier);
  return supprimer($cnx, "DELETE FROM panier_article WHERE id = $aid AND id_panier = $idPanier");
}

/**
 * Calcule le nombre total d'articles (somme des quantités) du panier.
 */
function calculerNombreArticles($detailPanier) {
  $nb = 0;
  foreach ($detailPanier as $article) {
    $nb += $article["qte"];
  }
  return $nb;
}

/**
 * Calcule le sous-total (prix x quantité) du panier.
 */
function calculerSousTotal($detailPanier) {
  $total = 0;
  foreach ($detailPanier as $article) {
    $total += $article["prix"] * $article["qte"];
  }
  return $total;
}

